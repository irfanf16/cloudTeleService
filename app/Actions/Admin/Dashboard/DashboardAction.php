<?php

namespace App\Actions\Admin\Dashboard;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Lorisleiva\Actions\Concerns\AsAction;
use Yajra\DataTables\DataTables;


class DashboardAction
{
    use AsAction;

    public function asController(Request $request)
    {
        $all_events_count=Event::count();
        $all_confirmed_events=Event::where('status','confirmed')->count();
        $all_tentative_events=Event::where('status','pending')->count();
        $all_cancelled_events=Event::where('status','cancelled')->count();

        $calender_all_events=Event::get();
        $events=[];
        foreach ($calender_all_events as $event){
            $events[] = [
//                'title' =>Str::limit($event->summary ? $event->summary : $event->description, 20, $end='....') ,
                'title' =>trim($event->summary),
                'start' => date('Y-m-d', strtotime($event->start)),
//                'backgroundColor' => '#ff4d4d',
                'textColor'=>$event->status=='cancelled' ? '#ff4d4d':'#00AD4E',
                'url'=>route('event.detail',$event->id),
                'display'=> 'block',
            ];
        }

        return view('admin.dashboard.dashboard')->with([
            'all_events_count'=>$all_events_count,
            'all_confirmed_events'=>$all_confirmed_events,
            'all_tentative_events'=>$all_tentative_events,
            'all_cancelled_events'=>$all_cancelled_events,
            'events'=>$events
        ]);
    }
}
