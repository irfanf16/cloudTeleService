@extends('admin.layout.app')
@section('content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Events</h5>
                    <table id="zero-conf" class="display" style="width:100%">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Services</th>
                                {{--                            <th>Summary</th> --}}
                                <th>Description</th>
                                <th>Start</th>
                                <th>Status</th>
                                <th>Action</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($all_events as $event)
                                <tr>
                                    <td>
                                        <div class="mail-info">
                                            <div class="mail-author">
                                                <div class="mail-author-info">
                                                    <span class="mail-author-name"><b>Name:</b>
                                                        {{ isset($event->attendees) && $event->attendees[0] ? $event->attendees[0]->fname . ' ' . $event->attendees[0]->lname : 'N/A' }}</span>
                                                    <span class="mail-author-name"><b>Email:</b>
                                                        {{ isset($event->attendees) && $event->attendees[0] ? $event->attendees[0]->email : 'N/A' }}</span>
                                                    <span class="mail-author-name"><b>Phone:</b>
                                                        {{ isset($event->attendees) && $event->attendees[0] ? $event->attendees[0]->phone : 'N/A' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ Str::limit($event->services, 20, $end = '....') }}</td>
                                    {{--                                <td>{{Str::limit($event->summary, 20, $end='....')}}</td> --}}
                                    <td>{{ Str::limit($event->description, 40, $end = '....') }}</td>
                                    <td>{{ date('Y-m-d H:i', strtotime($event->start)) }}</td>
                                    <td>
                                        <button data-id="{{ $event->id }}" data-summary="{{ $event->summary }}"
                                            data-des="{{ $event->description }}" data-status="{{ $event->status }}"
                                            class="btn  @if($event->status == 'confirmed')  btn-primary @endif @if($event->status == 'pending')  btn-info @endif @if($event->status == 'cancelled')  btn-danger @endif  m-b-xs event-status_change">{{ $event->status }}</button>
                                    </td>
                                    <td><a href="{{ route('event.detail', $event->id) }}"
                                            class="btn btn-secondary m-b-xs">Detail</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('event.status') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" id="event_id">
                    <div class="modal-header">
                        <h5 class="modal-title event-summary" id="exampleModalLabel">Modal title</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="event-des"></p>
                        <div class="">
                            <h5 class="card-title">Event Status</h5>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status" value="4"
                                       id="pendingEvent">
                                <label class="form-check-label" for="pendingEvent">
                                    Pending
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status" value="1"
                                    id="confirmedEvent">
                                <label class="form-check-label" for="confirmedEvent">
                                    Confirmed
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status" value="3"
                                    id="cancelledEvent">
                                <label class="form-check-label" for="cancelledEvent">
                                    Cancelled
                                </label>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="Submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>

    <script>
        $(document).ready(function() {
            $(document).on('click', '.event-status_change', function() {
                let id = $(this).data('id')
                let summary = $(this).data('summary')
                let des = $(this).data('des')
                let status = $(this).data('status')
                if (status == 'pending') {
                    document.getElementById("confirmedEvent").checked = false;
                    document.getElementById("cancelledEvent").checked = false;
                    document.getElementById("pendingEvent").checked = true;

                }else if (status == 'confirmed') {
                    document.getElementById("confirmedEvent").checked = true;
                    document.getElementById("cancelledEvent").checked = false;
                    document.getElementById("pendingEvent").checked = false;

                } else {
                    document.getElementById("confirmedEvent").checked = false;
                    document.getElementById("cancelledEvent").checked = true;
                    document.getElementById("pendingEvent").checked = false;

                }
                $('#event_id').val(id)
                $('.event-summary').text(summary)
                $('.event-des').text(des)
                $('#exampleModal').modal('show')
            })
        })
    </script>
@endsection
