<?php

namespace App\Http\Controllers\API;


use App\Http\Controllers\Controller;
use App\Models\ServiceAct;
use App\Models\Location;
use App\Models\Service as Service;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Arr;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class ServiceActController extends Controller
{
    public function index()
    {
        return response(ServiceAct::orderBy('id', 'desc')->get()->toArray());
    }

    public function store(Request $request)
    {

        $result = ['status' => Response::HTTP_FORBIDDEN, 'success' => false, 'errs' => [], 'result' => [], 'statusText' => ""];

        $response = $request->id ? Gate::inspect('update', ServiceAct::find($request->id)) : Gate::inspect('create', ServiceAct::class);
        if ($response->allowed()) {

            $validator = Validator::make($request->all(), []);

            if ($validator->fails()) {

                $result['errs'] = $validator->errors()->all();
                $result['statusText'] = 'შეცდომა, მონაცემების განახლებისას';

                return response()->json($result);
            };

            try {


                $model = ServiceAct::firstOrNew(['id' => $request->id]);
                $service_id = $request->get('service_id');
                $service = Service::find($service_id);

                if ($request->approve == 1) {
                    $service->status = 3;
                } elseif ($request->on_repair == 1) {
                    $service->status = 3;
                    $service->on_repair = 1;
                } else {
                    $service->status = 2;
                }

                $service->save();


                $model->fill($request->except(['location_id']));

                if ($request->get('is_mobile')) {
                    $model->is_mobile = 1;
                } else {
                    $model->is_mobile = 0;
                }
                $locationId = $request->get('location_id');
                if (is_numeric($locationId)) {
                    $model->location_id = $locationId;
                } else {
                    $location = Location::firstOrCreate(['name' => $locationId, "not_visible" => 1]);
                    $model->location_id = $location->id;
                }


                if (!$model->uuid) {
                    $todayActs = ServiceAct::count();
                    $today = Carbon::today();
                    $formattedDate = $today->format('ymd');
                    $model->uuid = $formattedDate . "/" . $todayActs;
                }
                if (!$model->user) {
                    $model->user()->associate(auth()->user());
                }

                $model->save();


                $result = Arr::prepend($result, true, 'success');
                $result = Arr::prepend($result, $model, 'result');
                $result = Arr::prepend($result, Response::HTTP_CREATED, 'status');
                $result = Arr::prepend($result, 'მონაცემები განახლდა წარმატებით', 'statusText');
            } catch (Exception $e) {

                $result = Arr::prepend($result, 'შეცდომა, მონაცემების განახლებისას', 'statusText');
                $result = Arr::prepend($result, Arr::prepend($result['errs'], 'გაურკვეველი შეცდომა! ' . $e->getMessage()), 'errs');
            }

            return response()->json($result, Response::HTTP_CREATED);
        } else {
            $result['errs'][0] = $response->message();
            return response()->json($result);
        }
    }


    public function destroy($id)
    {
        //

        $response = Gate::inspect('delete', ServiceAct::find($id));

        if ($response->allowed()) {


            $result = ['status' => Response::HTTP_FORBIDDEN, 'success' => false, 'errs' => [], 'result' => [], 'statusText' => ""];

            try {

                $destroy = ServiceAct::find($id)->delete();

                if ($destroy) {
                    $result['success'] = true;
                    $result['result'] = $id;
                    $result['status'] = Response::HTTP_CREATED;
                }
            } catch (Exception $e) {
                $result['errs'][0] = 'გაურკვეველი შეცდომა! ' . $e->getMessage();
            }

            return response()->json($result, Response::HTTP_CREATED);
        } else {
            $result['errs'][0] = $response->message();
            return response()->json($result);
        }
    }

    public function reject($id)
    {
        try {

            $act = ServiceAct::find($id);
            $act->service->status = 1;


            if ($act->service->save()) {
                $result['success'] = true;
                $result['result'] = $id;
                $result['status'] = Response::HTTP_CREATED;
            }
        } catch (Exception $e) {
            $result['errs'][0] = 'გაურკვეველი შეცდომა! ' . $e->getMessage();
        }

        return response()->json($result, Response::HTTP_CREATED);
    }
}
