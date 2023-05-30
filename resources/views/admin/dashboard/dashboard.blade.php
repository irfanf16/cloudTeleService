@extends('admin.layout.app')
@section('content')
    <style>
        .fc-event-time, .fc-event-title {
            padding: 0 1px;!important;
            white-space: nowrap;!important;
        }

        .fc-title {
            white-space: normal;!important;
        }
        .fc-day-grid-event > .fc-content {
            white-space: nowrap;!important;
        }
    </style>
    <div class="row">
        <div class="col-md-6 col-xl-3">
            <div class="card stat-widget">
                <div class="card-body">
                    <h5 class="card-title">All Events</h5>
                    <h2>{{$all_events_count}}</h2>
                    <p>All Events</p>
                    <div class="progress">
                        <div class="progress-bar bg-info progress-bar-striped" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card stat-widget">
                <div class="card-body">
                    <h5 class="card-title">Confirmed Events</h5>
                    <h2>{{$all_confirmed_events}}</h2>
                    <p>All Confirmed Events</p>
                    <div class="progress">
                        <div class="progress-bar bg-primary progress-bar-striped" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card stat-widget">
                <div class="card-body">
                    <h5 class="card-title">Pending Events</h5>
                    <h2>{{$all_tentative_events}}</h2>
                    <p>All Tentative Events</p>
                    <div class="progress">
                        <div class="progress-bar  bg-success progress-bar-striped" role="progressbar" style="width: 60%" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card stat-widget">
                <div class="card-body">
                    <h5 class="card-title">Cancelled Events</h5>
                    <h2>{{$all_cancelled_events}}</h2>
                    <p>All Cancelled Events</p>
                    <div class="progress">
                        <div class="progress-bar bg-danger  progress-bar-striped" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <div id='calendar'></div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>

    <script>

        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var  events={!! json_encode($events) !!};
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                expandRows: true,
                // events: 'https://fullcalendar.io/demo-events.json?overload-day'
                events: events,
            });
            calendar.render();
        });

    </script>



@endsection
