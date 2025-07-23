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

    $user = Auth::user();
    $type = $request->get("type");

    $responsesQuery = Response::with(['user', 'purchaser', 'region', 'systemOne', 'systemTwo', 'performer'])
        ->orderBy('id', 'desc');

    // Case 1: Show all done responses (status 0 or 3)
    if ($type === 'done') {
            $responses = $responsesQuery->whereIn('status', [0, 3])->take(200)->get();

    // Case 2: Show all client-pending responses (status 4)
    } elseif ($type === 'client-pending') {
        $responses = $responsesQuery->where('status', 4)->get();

    // Case 3: Filter based on responses_limited flag
    } else {
        $statusesToInclude = [1, 2, 5, 9, 10]; // all statuses except done & client-pending

        if ($user->responses_limited) {
            $responses = $responsesQuery
                ->where('manager_id', $user->id)
                ->whereIn('status', $statusesToInclude)
                ->get();
        } else {
            $responses = $responsesQuery
                ->whereIn('status', $statusesToInclude)
                ->get();
        }
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
