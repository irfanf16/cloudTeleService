<?php

namespace App\Actions\Admin\Contact;

use App\Models\Contact;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Lorisleiva\Actions\Concerns\AsAction;
use Yajra\DataTables\DataTables;


class GetAllContactAction
{
    use AsAction;

    public function asController(Request $request)
    {
        if ($request->ajax()) {
            $data = Contact::query()->orderBy('id','desc')->get();
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
                ->editColumn('subject',function ($row){
                    return Str::limit($row->subject, 40, $end = '....');
                })
                ->editColumn('message',function ($row){
                    return Str::limit($row->message, 40, $end = '....');
                })
                ->addColumn('action', function($row){
                    $btn = ' <button data-subject="'.$row->subject.'" data-message="'.$row->message.'"
                                            class="btn btn-primary  m-b-xs view-detail">View</button>';
                    return $btn;
                })
                ->rawColumns(['action','user'])
                ->make(true);
        }
        return view('admin.contacts.index');

    }
}
