<?php

namespace App\Actions\Admin\Events;
use App\Models\Document;
use App\Models\Event;
use App\Models\User;
use Illuminate\Support\Facades\Request;
use Lorisleiva\Actions\Concerns\AsAction;


class EventDetailAction
{
    use AsAction;

    public function asController($id)
    {
        $event=Event::with('attendees')->find($id);
        if (!$event){
            return redirect()->back();
        }
        $all_documents=Document::where('event_id',$id)->get();
        $documents=Document::where('event_id',$id)->where('type','document')->get();
        $notes=Document::where('event_id',$id)->where('type','note')->get();
        return view('admin.events.detail')->with([
            'event'=>$event,
            'all_documents'=>$all_documents,
            'documents'=>$documents,
            'notes'=>$notes,
        ]);
    }
}
