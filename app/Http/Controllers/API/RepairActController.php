<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RepairAct;
use App\Models\Repair;
use App\Models\DeviceBrand;
use App\Models\DeviceType;
use App\Models\Location;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Arr;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class RepairActController extends Controller
{
    public function index()
    {
        return response(RepairAct::orderBy('id', 'desc')->get()->toArray());
    }

    public function show($repair_id)
    {
        $repair = Repair::find($repair_id);
        return response()->json($repair->act, 200);
    }

    public function store(Request $request)
    {

        $result = ['status' => Response::HTTP_FORBIDDEN, 'success' => false, 'errs' => [], 'result' => [], 'statusText' => ""];

        $response = $request->id ? Gate::inspect('update', RepairAct::find($request->id)) : Gate::inspect('create', RepairAct::class);
        if ($response->allowed()) {

            $validator = Validator::make($request->all(), []);

            if ($validator->fails()) {

                $result['errs'] = $validator->errors()->all();
                $result['statusText'] = 'შეცდომა, მონაცემების განახლებისას';

                return response()->json($result);
            };

            try {


                $model = RepairAct::firstOrNew(['id' => $request->id]);
                $repair_id = $request->get('repair_id');
                $repair = Repair::find($repair_id);

                if ($request->approve == 1) {
                    $repair->status = 3;
                } elseif ($request->on_repair == 1) {
                    $repair->status = 3;
                    $repair->on_repair = 1;
                } else {
                    if (!$request->get('is_app')) {
                        $repair->status = 2;
                    } else {
                        $repair->status = 5;
                    }
                }

                $repair->save();


                $model->fill($request->except(['location_id', 'device_type_id', 'device_brand_id']));

                if ($request->get('is_app')) {
                    $model->is_mobile = 1;
                } elseif ($model->is_mobile !== 1) {
                    $model->is_mobile = 0;
                }

                $locationId = $request->get('location_id');
                if (is_numeric($locationId)) {
                    $model->location_id = $locationId;
                } else {
                    $location = Location::firstOrCreate(['name' => $locationId, "not_visible" => 1]);
                    $model->location_id = $location->id;
                }

                if ($request->has('device_type_id')) {
                    $deviceTypeId = $request->get('device_type_id');

                    if (is_numeric($deviceTypeId)) {
                        $model->device_type_id = $deviceTypeId;
                    } else {
                        $deviceType = DeviceType::firstOrCreate(['name' => $deviceTypeId, "not_visible" => 1]);
                        $model->device_type_id = $deviceType->id;
                    }
                }



                if ($request->has('device_brand_id')) {
                    $deviceBrandId = $request->get('device_brand_id');

                    if (is_numeric($deviceBrandId)) {
                        $model->device_brand_id = $deviceBrandId;
                    } else {
                        $deviceBrand = DeviceBrand::firstOrCreate(['name' => $deviceBrandId, "not_visible" => 1]);
                        $model->device_brand_id = $deviceBrand->id;
                    }
                }


                if (!$model->uuid) {
                    $todayActs = RepairAct::count();
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


    public function changeStatusNew($id)
    {
        $repair = Repair::find($id);
        $repair->status = 2;
        $repair->save();
        return response()->json(["success" => true]);
    }


    public function destroy($id)
    {
        //

        $response = Gate::inspect('delete', RepairAct::find($id));

        if ($response->allowed()) {


            $result = ['status' => Response::HTTP_FORBIDDEN, 'success' => false, 'errs' => [], 'result' => [], 'statusText' => ""];

            try {

                $destroy = RepairAct::find($id)->delete();

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

            $act = RepairAct::find($id);
            $act->repair->status = 1;


            if ($act->repair->save()) {
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
