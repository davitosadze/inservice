<?php

namespace App\Http\Controllers\API;

use App\Exports\ResponseExport;
use App\Http\Controllers\Controller;

use App\Models\Response;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Excel\Facades\Excel;

class ResponseController extends Controller
{
    public function index(Request $request)
    {

        if (Auth::user()->roles->contains('name', 'ინჟინერი')) {
            $responses = Response::with(['user', 'purchaser', 'region',  'systemOne', 'systemTwo', 'performer'])->orderBy('id', 'desc')
                ->whereIn("status", [1, 5, 10])
                ->where("performer_id", Auth::user()->id);
        } elseif (Auth::user()->roles->contains('name', 'ტექნიკური მენეჯერი - შეზღუდული')) {
            $responses = Response::with(['user', 'purchaser', 'region',  'systemOne', 'systemTwo', 'performer'])
            ->orderBy('id', 'desc')
            ->where("user_id", Auth::user()->id);
        } elseif(Auth::user()->roles->contains('name', 'დირექტორი')) {
            $responses = Response::with(['user', 'purchaser', 'region',  'systemOne', 'systemTwo', 'performer'])->orderBy('id', 'desc');
        } else {
            $responses = Response::with(['user', 'purchaser', 'region',  'systemOne', 'systemTwo', 'performer'])
            ->where("manager_id", Auth::user()->id)
            ->orWhere('manager_id', null)
            ->orderBy('id', 'desc');
        }

        if ($request->get("type") == "done") {
            $responses = $responses->whereIn('status', [0, 3])
                ->get();
        } else {
            $responses = $responses->whereIn("status", [1, 2, 5, 9, 10])
                ->get();
        }

        return response($responses->toArray());
    }

    public function export(Request $request)
    {
        $from = Carbon::parse($request->from)->startOfDay();
        $to = Carbon::parse($request->to)->endOfDay();



        $name = "რეაგირება";
        return Excel::download(new ResponseExport($from, $to), "" . $name . ".xlsx");
    }

    public function destroy($id)
    {
        $result = ['status' => HttpResponse::HTTP_FORBIDDEN, 'success' => false, 'errs' => [], 'result' => [], 'statusText' => ""];

        $response = Gate::inspect('delete', Response::find($id));

        if ($response->allowed()) {

            DB::beginTransaction();

            try {
                $region = Response::find($id);
                $region->delete();
                $result['success'] = true;
                $result['status'] = HttpResponse::HTTP_CREATED;
                $result = Arr::prepend($result, 'მონაცემები განახლდა წარმატებით', 'statusText');

                DB::commit();
            } catch (Exception $e) {
                $result = Arr::prepend($result, 'შეცდომა, მონაცემების განახლებისას', 'statusText');
                $result = Arr::prepend($result, Arr::prepend($result['errs'], 'გაურკვეველი შეცდომა! ' . $e->getMessage()), 'errs');

                DB::rollBack();
            }

            return response()->json($result, HttpResponse::HTTP_CREATED);
        } else {
            $result['errs'][0] = $response->message();
            return response()->json($result);
        }
    }
}
