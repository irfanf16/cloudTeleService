<?php

namespace App\Actions\Admin\Flight;
use App\Models\Flight;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Lorisleiva\Actions\Concerns\AsAction;
use Yajra\DataTables\DataTables;


class FlightAction
{
    use AsAction;

    public function asController(Request $request)
    {

        if ($request->ajax()) {
            $data = Flight::query()->orderBy('id','desc')->get();
            return Datatables::of($data)->addIndexColumn()
                ->addColumn('user',function ($row){
                    $name= $row->name;
                    $email=$row->email;
                    $phone=$row->phone;
                    $user='<div class="mail-info">
                                            <div class="mail-author">
                                                <div class="mail-author-info">
                                                    <span class="mail-author-name"><b>Name:</b>
                                                        '. $name .'</span>
                                                    <span class="mail-author-name"><b>Email:</b>
                                                        '. $email .'</span>
                                                    <span class="mail-author-name"><b>Phone:</b>
                                                        '.$phone. '</span>
                                                </div>
                                            </div>
                                        </div>';
                    return $user;
                })
                ->editColumn('location',function ($row){
                    return Str::limit($row->location, 40, $end = '....');
                })
                ->editColumn('designation',function ($row){
                    return Str::limit($row->designation, 40, $end = '....');
                })
                ->addColumn('action', function($row){
                    $btn = ' <button data-subject="'.$row->location.'" data-message="'.$row->designation.'"
                                            class="btn btn-primary  m-b-xs view-detail">View</button>';
                    return $btn;
                })
                ->rawColumns(['action','user'])
                ->make(true);
        }

        return view('admin.flight.index');

    }
}
