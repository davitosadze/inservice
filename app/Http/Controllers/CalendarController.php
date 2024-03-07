<?php

namespace App\Http\Controllers;

use App\Models\CalendarEvent;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function events($purchaser_id)
    {
        $events = CalendarEvent::where("purchaser_id", $purchaser_id)->get();
        $modifiedEvents = [];

        foreach ($events as $event) {
            $modifiedEvent = [
                "title" => $event->title,
                "time" => [
                    "start" => $event->date,
                    "end" => $event->date,
                ],
                "description" => $event->response?->content,

                "isEditable" => true,
                "id" => $event->id,
            ];

            $modifiedEvents[] = $modifiedEvent;
        }

        return response()->json($modifiedEvents, 200);
    }

    public function getEvent(CalendarEvent $event)
    {
        return response()->json($event);
    }

    public function storeEvent(Request $request)
    {
        CalendarEvent::create([
            "title" => $request->title,
            "purchaser_id" => $request->get('purchaser_id'),
            "date" => Carbon::parse($request->date)
        ]);
        return response()->json("Event Successfully Added", 200);
    }

    public function updateEvent(Request $request, $event_id)
    {
        $event = CalendarEvent::find($event_id);
        $event->title = $request->get("title");
        $event->save();
        return response()->json($event, 200);
    }

    public function delete(CalendarEvent $event)
    {
        $event->delete();
        return response()->json("Event Successfully Deleted", 200);
    }
}
