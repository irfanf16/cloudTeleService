@extends('admin.layout.app')
@section('content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Customer Detail</h5>
                    <dl class="row">
                        <dt class="col-sm-3">Name:</dt>
                        <dd class="col-sm-9">
                            {{ isset($event->attendees) && $event->attendees[0] ? $event->attendees[0]->fname . ' ' . $event->attendees[0]->lname : 'N/A' }}
                        </dd>

                        <dt class="col-sm-3">Email:</dt>
                        <dd class="col-sm-9">
                            <a href="#" class="card-link">
                                {{ isset($event->attendees) && $event->attendees[0] ? $event->attendees[0]->email : 'N/A' }}</a>
                        </dd>
                        <dt class="col-sm-3">Phone:</dt>
                        <dd class="col-sm-9">
                            <a href="#"
                                class="card-link">{{ isset($event->attendees) && $event->attendees[0] ? $event->attendees[0]->phone : 'N/A' }}</a>
                        </dd>

                    </dl>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <div class="float-end">
                        <button class="btn btn-warning add-remove-note mx-1">Add/Remove Notes</button>
                    </div>
                    <div class="float-end">
                        <button class="btn btn-info add-remove-doc mx-1">Add/Remove Documents</button>
                    </div>
                    <h5 class="card-title">Event Detail</h5>
                    <p class="card-description">{{ $event->summary }}</p>
                    <dl class="row">
                        <dt class="col-sm-3">Description:</dt>
                        <dd class="col-sm-9">{{ $event->description }}</dd>

                        <dt class="col-sm-3">hangout Link:</dt>
                        <dd class="col-sm-9">
                            <a href="#" class="card-link">{{ $event->hangoutLink }}</a>
                        </dd>

                        <dt class="col-sm-3">Start:</dt>
                        <dd class="col-sm-9">
                            <span class="badge bg-info">
                                {{ date('Y-m-d H:i', strtotime($event->start)) }}
                            </span>
                        </dd>
                        <dt class="col-sm-3">Services:</dt>
                        <dd class="col-sm-9">{{ $event->services }}</dd>

                    </dl>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Event Documents</h5>
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Type</th>
                                <th scope="col">Description</th>
                                <th scope="col">Doc Link</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $count=0 @endphp
                            @foreach ($all_documents as $doc)
                                <tr>
                                    <th scope="row">{{ $count }}</th>
                                    <td><span
                                            class="badge @if ($doc->type == 'document') bg-success @else bg-warning @endif">{{ $doc->type }}</span>
                                    </td>
                                    <td>{{ $doc->description }}</td>
                                    <td>
                                        @if ($doc->type == 'document')
                                            <a target="_blank" href="{{ Storage::url($doc->doc_link) }}"
                                                class="btn btn-secondary m-b-xs">View File</a>
                                            {{--                                        <a target="_blank" href="{{storage_path('/app/public/'.$doc->doc_link)}}" class="btn btn-secondary m-b-xs">Link1</a> --}}
                                        @else
                                            --
                                        @endif
                                    </td>
                                </tr>
                                @php $count++ @endphp
                            @endforeach
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg">
            <div class="modal-content ">
                <form action="{{ route('add.event.doc') }}" method="POST" class="needs-validation" novalidate>
                    @csrf
                    <input type="hidden" value="{{ $event->id }}" name="id" id="event_id">
                    <input type="hidden" value="note" name="type">
                    <div class="modal-header">
                        <h5 class="modal-title event-summary" id="exampleModalLabel">Add Notes</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="event-des"></p>
                        <div class="card-body">
                            <div class="table-responsive" style="max-height: 400px">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th width="90%" scope="col">Notes</th>
                                            <th scope="col">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody id="noteTable">
                                        @if (count($notes) > 0)
                                            @foreach ($notes as $key => $doc)
                                                @if ($key == 0)
                                                    <tr>
                                                        <td>
                                                            <textarea placeholder="Please Enter Description" name="description[]" style="width: 100%" required>{{ $doc->description }}</textarea>
                                                            <div class="invalid-feedback">
                                                                Please enter description.
                                                            </div>
                                                        </td>
                                                        <td><span class="badge bg-info add_note">Add More</span></td>
                                                    </tr>
                                                @else
                                                    <tr>
                                                        <td>
                                                            <textarea placeholder="Please Enter Description" name="description[]" style="width: 100%" required>{{ $doc->description }}</textarea>
                                                            <div class="invalid-feedback">
                                                                Please enter description.
                                                            </div>
                                                        </td>
                                                        <td><span class="badge bg-danger remove-note-tr">Remove</span></td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        @else
                                            <tr>
                                                <td>
                                                    <textarea placeholder="Please Enter Description" name="description[]" style="width: 100%" required></textarea>
                                                    <div class="invalid-feedback">
                                                        Please enter description.
                                                    </div>
                                                </td>
                                                <td><span class="badge bg-info add_note">Add More</span></td>
                                            </tr>
                                        @endif

                                    </tbody>
                                </table>
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

    <div class="modal fade" id="addDoc" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg">
            <div class="modal-content ">
                <form action="{{ route('add.event.doc') }}" method="POST" class="needs-validation"
                    enctype="multipart/form-data" novalidate>
                    @csrf
                    <input type="hidden" value="{{ $event->id }}" name="id" id="event_id">
                    <input type="hidden" value="document" name="type">
                    <div class="modal-header">
                        <h5 class="modal-title event-summary" id="exampleModalLabel">Add Documents</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="event-des"></p>
                        <div class="card-body">
                            <div class="table-responsive" style="max-height: 400px">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th width="90%" scope="col">Documents</th>
                                            <th scope="col">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody id="docTable">
                                        @if (count($documents) > 0)
                                            @foreach ($documents as $key => $doc)
                                                @if ($key == 0)
                                                    <tr data-id="{{ $doc->id }}">
                                                        <td>
                                                            <div class="mb-3">
                                                                <div class="d-flex">
                                                                    <input type="hidden" name="old_doc_ids[]"
                                                                        value="{{ $doc->id }}">
                                                                    <input class="form-control" name="doc_image_old[]"
                                                                        value="{{ $doc->description }}" type="file"
                                                                        id="formFile">
                                                                    {{--                                                                <a target="_blank" --}}
                                                                    {{--                                                                   href="{{Storage::url($doc->doc_link) }}" --}}
                                                                    {{--                                                                   class="btn btn-secondary m-b-xs">View</a> --}}
                                                                </div>
                                                            </div>

                                                        </td>
                                                        <td><span class="badge bg-info add_doc">Add More</span></td>
                                                    </tr>
                                                @else
                                                    <tr data-id="{{ $doc->id }}">
                                                        <td>

                                                            <div class="mb-3">
                                                                <div class="d-flex">
                                                                    <input type="hidden" name="old_doc_ids[]"
                                                                        value="{{ $doc->id }}">
                                                                    <input class="form-control" name="doc_image_old[]"
                                                                        value="{{ $doc->description }}" type="file"
                                                                        id="formFile">
                                                                    {{--                                                                <a target="_blank" --}}
                                                                    {{--                                                                   href="{{Storage::url($doc->doc_link) }}" --}}
                                                                    {{--                                                                   class="btn btn-secondary m-b-xs">View</a> --}}
                                                                </div>
                                                            </div>

                                                        </td>
                                                        <td><span class="badge bg-danger remove-doc-tr">Remove</span></td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        @else
                                            <tr>
                                                <td>
                                                    <div class="mb-3">
                                                        <input class="form-control" name="doc_image[]" type="file"
                                                            id="formFile">
                                                    </div>
                                                    <div class="invalid-feedback">
                                                        Please add document.
                                                    </div>
                                                </td>
                                                <td><span class="badge bg-info add_doc">Add More</span></td>
                                            </tr>
                                        @endif

                                    </tbody>
                                </table>
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
            $(document).on('click', '.add-remove-doc', function() {
                $('#addDoc').modal('show')
            })
            $(document).on('click', '.remove-doc-tr', function() {
                let doc_id = $(this).parents('tr').data('id');
                if (doc_id) {
                    $('#docTable').append(
                        '<input type="hidden" name="deleted_doc_ids[]" value="' + doc_id + '">'
                    )
                }
                $(this).parents('tr').remove();
            });
            $(document).on('click', '.add_doc', function() {
                $('#docTable').append(
                    '<tr> <td><div class="mb-3"> <label for="formFile" class="form-label">Document</label> <input class="form-control" type="file" name="doc_image[]" id="formFile"> </div> <div class="invalid-feedback">Please add document. </div></td> <td><span class="badge bg-danger remove-doc-tr">Remove</span></td> </tr>'
                )
            });

            $(document).on('click', '.add-remove-note', function() {
                $('#exampleModal').modal('show')
            })
            $(document).on('click', '.remove-note-tr', function() {
                $(this).parents('tr').remove();
            });
            $(document).on('click', '.add_note', function() {
                $('#noteTable').append(
                    '<tr> <td><textarea name="description[]" style="width: 100%" required></textarea></td> <td><span class="badge bg-danger remove-note-tr">Remove</span></td> </tr>'
                )
            });
        })
    </script>
@endsection
