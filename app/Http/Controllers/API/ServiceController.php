<?php

namespace App\Http\Controllers\API;

use App\Exports\ServiceExport;
use App\Http\Controllers\Controller;

use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Excel\Facades\Excel;

class ServiceController extends Controller
{
    public function index(Request $request)
    {

        if (Auth::user()->roles->contains('name', 'ინჟინერი')) {
            $responses = Service::with(['user', 'purchaser', 'region', 'performer'])->orderBy('id', 'desc')
                ->whereIn("status", [1, 5, 10])
                ->where("performer_id", Auth::user()->id);
        } else {
            $responses = Service::with(['user', 'purchaser', 'region',  'performer'])->orderBy('id', 'desc');
        }

        if ($request->get("type") == "done") {
            $responses = $responses->where("status", 3)
                ->orWhere("status", 0)
                ->get();
        } else {
            $responses = $responses->whereIn("status", [1, 2, 5, 10])
                ->get();
        }

        return response($responses->toArray());
    }

    public function export(Request $request)
    {
        $from = Carbon::parse($request->from)->startOfDay();
        $to = Carbon::parse($request->to)->endOfDay();



        $name = "სერვისი";
        return Excel::download(new ServiceExport($from, $to), "" . $name . ".xlsx");
    }

    public function destroy($id)
    {
        $result = ['status' => HttpResponse::HTTP_FORBIDDEN, 'success' => false, 'errs' => [], 'result' => [], 'statusText' => ""];

        $response = Gate::inspect('delete', Service::find($id));

        if ($response->allowed()) {

            DB::beginTransaction();

            try {
                $region = Service::find($id);
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

    public function doneServicesPaginated(Request $request)
    {
        $perPage = $request->get('perPage', 17);
        $page = $request->get('page', 1);
        $sortField = $request->get('sortField', 'id');
        $sortOrder = $request->get('sortOrder', 'desc');

        $query = Service::with(['user', 'purchaser', 'region', 'performer'])
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
