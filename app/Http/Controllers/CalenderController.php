<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use Illuminate\Http\JsonResponse;

class CalenderController extends Controller
{
    public function index(Request $request)
    {
        return match (true) {
            $request->ajax() => $this->indexAjax($request),
            default => view('calendar.index')
        };
    }

    public function ajax(Request $request): JsonResponse
    {
        return match ($request->type) {
            'add' => $this->addEvent($request),
            'update' => $this->updateEvent($request),
            'delete' => $this->deleteEvent($request),
            default => response()->json(['message' => 'Invalid action'], 400)
        };
    }

    private function indexAjax(Request $request): JsonResponse
    {
        $data = Event::whereDate('start', '>=', $request->start)
            ->whereDate('end',   '<=', $request->end)
            ->get(['id', 'title', 'start', 'end']);

        return response()->json($data);
    }

    private function addEvent(Request $request): JsonResponse
    {
        $event = Event::create([
            'title' => $request->title,
            'start' => $request->start,
            'end' => $request->end,
        ]);

        return response()->json($event);
    }

    private function updateEvent(Request $request): JsonResponse
    {
        $event = Event::find($request->id);
        if (!$event) {
            return response()->json(['message' => 'Event not found'], 404);
        }

        $event->update([
            'title' => $request->title,
            'start' => $request->start,
            'end' => $request->end,
        ]);

        return response()->json($event);
    }

    private function deleteEvent(Request $request): JsonResponse
    {
        $event = Event::find($request->id);
        if (!$event) {
            return response()->json(['message' => 'Event not found'], 404);
        }
        $event->delete();
        return response()->json(['message' => 'Event deleted']);
    }
}
