@extends('admin.layout.app')
@section('content')

    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Flights</h5>
                    <table id="zero-conf" class="display" style="width:100%">
                        <thead>
                        <tr>
                            <th>User</th>
                            <th>Location</th>
                            <th>Designation</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($flights as $flight)
                            <tr>
                                <td>
                                    <div class="mail-info">
                                        <div class="mail-author">
                                            <div class="mail-author-info">
                                                <span class="mail-author-name"><b>Name:</b>>{{$flight->name}}</span>
                                                <span class="mail-author-name"><b>Email:</b>{{$flight->email}}</span>
                                                <span class="mail-author-name"><b>Phone:</b> {{$flight->phone}}</span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>{{Str::limit($flight->location, 20, $end='....')}}</td>
                                <td>{{Str::limit($flight->designation, 40, $end='....')}}</td>
                            </tr>
                        @endforeach
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>


@endsection
