<?php

namespace App\Http\Controllers\API;

use App\Exports\RepairExport;
use App\Http\Controllers\Controller;
use App\Models\Repair;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Excel\Facades\Excel;

class RepairController extends Controller
{
    public function index(Request $request)
    {

        if (Auth::user()->roles->contains('name', 'ინჟინერი')) {
            $repairs = Repair::with(['user', 'purchaser', 'region', 'performer'])->orderBy('id', 'desc')
                ->whereIn("status", [1, 5, 10])
                ->where("performer_id", Auth::user()->id);
        } elseif (Auth::user()->roles->contains('name', 'ტექნიკური მენეჯერი - შეზღუდული')) {
            $repairs = Repair::with(['user', 'purchaser', 'region', 'performer'])->orderBy('id', 'desc')->where("user_id", Auth::user()->id);;
        } else {
            $repairs = Repair::with(['user', 'purchaser', 'region', 'performer'])->orderBy('id', 'desc');
        }

        if ($request->get("type") == "done") {
            $repairs = $repairs->whereIn('status', [0, 3])
                ->get();
        } else {
            $repairs = $repairs->whereIn("status", [1, 2, 5, 10])
                ->get();
        }

        return response($repairs->toArray());
    }

    public function export(Request $request)
    {
        $from = Carbon::parse($request->from)->startOfDay();
        $to = Carbon::parse($request->to)->endOfDay();



        $name = "რემონტი";
        return Excel::download(new RepairExport($from, $to), "" . $name . ".xlsx");
    }

    public function destroy($id)
    {
        $result = ['status' => HttpResponse::HTTP_FORBIDDEN, 'success' => false, 'errs' => [], 'result' => [], 'statusText' => ""];

        $response = Gate::inspect('delete', Repair::find($id));

        if ($response->allowed()) {

            DB::beginTransaction();

            try {
                $region = Repair::find($id);
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

    public function doneRepairsPaginated(Request $request)
    {
        $perPage = $request->get('perPage', 17);
        $page = $request->get('page', 1);
        $sortField = $request->get('sortField', 'id');
        $sortOrder = $request->get('sortOrder', 'desc');

        $query = Repair::with(['user', 'purchaser', 'region', 'performer'])
            ->whereIn('status', [0, 3]);

        // Apply filters from AG Grid
        if ($request->has('filters')) {
            $filters = json_decode($request->get('filters'), true);

            foreach ($filters as $field => $filterData) {
                $filterValue = $filterData['filter'] ?? null;
                $filterType = $filterData['filterType'] ?? 'text';
                $type = $filterData['type'] ?? 'contains';

                if ($filterValue === null || $filterValue === '') {
                    continue;
                }

                // Handle nested fields (like region.name, user.name)
                if (strpos($field, '.') !== false) {
                    $parts = explode('.', $field);
                    $relation = $parts[0];
                    $column = $parts[1];

                    $query->whereHas($relation, function ($q) use ($column, $filterValue, $type) {
                        $this->applyFilter($q, $column, $filterValue, $type);
                    });
                } else {
                    // Direct field filter
                    $this->applyFilter($query, $field, $filterValue, $type);
                }
            }
        }

        // Handle sorting for nested fields
        if (strpos($sortField, '.') !== false) {
            $query->orderBy('id', $sortOrder);
        } else {
            $query->orderBy($sortField, $sortOrder);
        }

        $paginated = $query->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'data' => $paginated->items(),
            'total' => $paginated->total(),
            'page' => $paginated->currentPage(),
            'lastPage' => $paginated->lastPage(),
            'perPage' => $perPage
        ]);
    }

    private function applyFilter($query, $column, $value, $type)
    {
        switch ($type) {
            case 'contains':
                $query->where($column, 'LIKE', '%' . $value . '%');
                break;
            case 'equals':
                $query->where($column, '=', $value);
                break;
            case 'startsWith':
                $query->where($column, 'LIKE', $value . '%');
                break;
            case 'endsWith':
                $query->where($column, 'LIKE', '%' . $value);
                break;
            case 'notContains':
                $query->where($column, 'NOT LIKE', '%' . $value . '%');
                break;
            default:
                $query->where($column, 'LIKE', '%' . $value . '%');
        }
    }
}
