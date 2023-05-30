<?php

namespace App\Actions\Admin\Events;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Lorisleiva\Actions\Concerns\AsAction;
use Yajra\DataTables\DataTables;


class EventsAction
{
    use AsAction;

    public function asController(Request $request)
    {

        if ($request->ajax()) {
            $data = Event::query()->with('attendees')->orderBy('id','desc')->get();
            return Datatables::of($data)->addIndexColumn()
                ->addColumn('user',function ($row){
                    $name= isset($row->attendees) && $row->attendees[0] ? $row->attendees[0]->fname . ' ' . $row->attendees[0]->lname : 'N/A';
                    $email=isset($row->attendees) && $row->attendees[0] ? $row->attendees[0]->email : 'N/A';
                    $phone=isset($row->attendees) && $row->attendees[0] ? $row->attendees[0]->phone : 'N/A' ;
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
                ->editColumn('services',function ($row){
                    return Str::limit($row->services, 20, $end = '....');
                })
                ->editColumn('description',function ($row){
                    return Str::limit($row->description, 40, $end = '....');
                })
                ->editColumn('start',function ($row){
                    return date('Y-m-d H:i', strtotime($row->start));
                })
                ->addColumn('status',function ($row){
                    $clr= $row->status == 'confirmed' ? 'btn-primary' : ($row->status == 'pending' ? 'btn-info' : 'btn-danger');
                    $btn='  <button data-id="'.$row->id.'" data-summary="'. $row->summary.'"
                                            data-des="'.$row->description.'" data-status="'.$row->status.'"
                                            class="btn  '.$clr.' m-b-xs event-status_change">'. $row->status .'</button>';
               return $btn;
                })
                ->addColumn('action', function($row){
                    $btn = '<a href="'.route('event.detail', $row->id) .'"
                                            class="btn btn-secondary m-b-xs">Detail</a>';
                    return $btn;
                })
                ->rawColumns(['action','user','status'])
                ->make(true);
        }
        return view('admin.events.index');
    }
}
