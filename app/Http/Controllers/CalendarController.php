<?php

namespace App\Http\Controllers;

use App\Models\CalendarEvent;
use App\Models\Repair;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function index()
    {
        return view('calendar.index');
    }

    public function getCalendarData(Request $request)
    {
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);

        $startOfMonth = Carbon::create($year, $month, 1)->startOfDay();
        $endOfMonth = Carbon::create($year, $month, 1)->endOfMonth()->endOfDay();

        $repairs = collect();
        $services = collect();

        // Only fetch repairs if user has repair view permission
        if (auth()->user()->can('რემონტის ნახვა')) {
            $repairs = Repair::whereNotNull('estimated_arrival_time')
                ->whereBetween('estimated_arrival_time', [$startOfMonth, $endOfMonth])
                ->with(['purchaser', 'performer'])
                ->get();
        }

        // Only fetch services if user has service view permission
        if (auth()->user()->can('სერვისის ნახვა')) {
            $services = Service::whereNotNull('estimated_arrival_time')
                ->whereBetween('estimated_arrival_time', [$startOfMonth, $endOfMonth])
                ->with(['purchaser', 'performer'])
                ->get();
        }

        $calendarData = [];

        foreach ($repairs as $repair) {
            $date = Carbon::parse($repair->estimated_arrival_time)->format('Y-m-d');
            if (!isset($calendarData[$date])) {
                $calendarData[$date] = [];
            }
            $calendarData[$date][] = [
                'type' => 'repair',
                'id' => $repair->id,
                'title' => $repair->content ?? 'რემონტი',
                'purchaser' => $repair->purchaser->name ?? '',
                'performer' => $repair->performer->name ?? '',
                'time' => Carbon::parse($repair->estimated_arrival_time)->format('H:i'),
                'sort_time' => Carbon::parse($repair->estimated_arrival_time)->format('H:i')
            ];
        }

        foreach ($services as $service) {
            $date = Carbon::parse($service->estimated_arrival_time)->format('Y-m-d');
            if (!isset($calendarData[$date])) {
                $calendarData[$date] = [];
            }
            $calendarData[$date][] = [
                'type' => 'service',
                'id' => $service->id,
                'title' => $service->content ?? 'სერვისი',
                'purchaser' => $service->purchaser->name ?? '',
                'performer' => $service->performer->name ?? '',
                'time' => Carbon::parse($service->estimated_arrival_time)->format('H:i'),
                'sort_time' => Carbon::parse($service->estimated_arrival_time)->format('H:i')
            ];
        }

        // Sort events by time within each day
        foreach ($calendarData as $date => &$events) {
            usort($events, function($a, $b) {
                return strcmp($a['sort_time'], $b['sort_time']);
            });
        }

        return response()->json($calendarData);
    }

    public function events($purchaser_id)
    {
        $events = CalendarEvent::where("purchaser_id", $purchaser_id)->with('response')->get();
        $modifiedEvents = [];

        foreach ($events as $event) {

            $reason =  $event->response?->act ? $event->response?->act?->note  : $event->response?->job_description;
            $content = "შინაარსი: " . $event->response?->content . "<br><br>" . "გამოსწორების მიზეზი: " . $reason;

            $modifiedEvent = [
                "title" => $event->title,
                "time" => [
                    "start" => $event->date,
                    "end" => $event->date,
                ],
                "description" => $event->response ? $content : "",

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
