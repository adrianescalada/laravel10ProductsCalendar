<script>
    var SITEURL = "{{ url('/calendar') }}";
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var calendar = $('#calendar').fullCalendar({
        editable: true,
        events: SITEURL + "/",
        displayEventTime: false,
        eventRender: function(event, element, view) {
            event.allDay = event.allDay === 'true';
        },
        selectable: true,
        selectHelper: true,
        select: function(start, end, allDay) {
            let startDate = start.format("Y-MM-DD");
            let endDate = end.format("Y-MM-DD");
            console.log('startDate', startDate, 'endDate', endDate);
            Swal.fire({
                title: 'Event Title',
                input: 'text',
                inputPlaceholder: 'Enter event title',
                showCancelButton: true,
                confirmButtonText: 'Save',
                showLoaderOnConfirm: true,
                preConfirm: (title) => {
                    if (title) {
                        if (!startDate || !endDate) {
                            console.log(title, startDate, endDate, allDay);
                        }
                        var start = startDate;
                        var end = endDate;
                        return $.ajax({
                            url: SITEURL + "/fullcalenderAjax",
                            data: {
                                title: title,
                                start: start,
                                end: end,
                                type: 'add'
                            },
                            type: "POST"
                        }).then(response => {
                            displayMessage("Event Created Successfully");
                            calendar.fullCalendar('renderEvent', {
                                id: response.id,
                                title: title,
                                start: start,
                                end: end,
                                allDay: allDay
                            }, true);
                            calendar.fullCalendar('unselect');
                        }).catch(error => {
                            Swal.showValidationMessage(
                                `Request failed: ${error}`
                            );
                        });
                    } else {
                        return false;
                    }
                },
                allowOutsideClick: () => !Swal.isLoading()
            });
        },
        eventDrop: function(event, delta) {
            var start = event.start.format("Y-MM-DD");
            var end = event.end.format("Y-MM-DD");

            $.ajax({
                url: SITEURL + '/fullcalenderAjax',
                data: {
                    title: event.title,
                    start: start,
                    end: end,
                    id: event.id,
                    type: 'update'
                },
                type: "POST",
                success: function(response) {
                    displayMessage("Event Updated Successfully");
                }
            });
        },
        eventClick: function(event) {
            Swal.fire({
                title: 'Confirmation',
                text: 'Do you really want to delete?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "POST",
                        url: SITEURL + '/fullcalenderAjax',
                        data: {
                            id: event.id,
                            type: 'delete'
                        },
                        success: function(response) {
                            calendar.fullCalendar('removeEvents', event.id);
                            Swal.fire(
                                'Deleted!',
                                'Event has been deleted successfully.',
                                'success'
                            );
                        }
                    });
                }
            });
        }

    });

    function displayMessage(message) {
        toastr.success(message, 'Event');
    }
</script>
