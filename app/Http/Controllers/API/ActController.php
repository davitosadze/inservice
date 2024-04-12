<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Act;
use App\Models\DeviceBrand;
use App\Models\DeviceType;
use App\Models\Location;
use App\Models\Response as ModelsResponse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Arr;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class ActController extends Controller
{
    public function index()
    {
        return response(Act::orderBy('id', 'desc')->get()->toArray());
    }

    public function store(Request $request)
    {
        $result = ['status' => Response::HTTP_FORBIDDEN, 'success' => false, 'errs' => [], 'result' => [], 'statusText' => ""];

        $response = $request->id ? Gate::inspect('update', Act::find($request->id)) : Gate::inspect('create', Act::class);
        if ($response->allowed()) {

            $validator = Validator::make($request->all(), []);

            if ($validator->fails()) {

                $result['errs'] = $validator->errors()->all();
                $result['statusText'] = 'შეცდომა, მონაცემების განახლებისას';

                return response()->json($result);
            };

            try {


                $model = Act::firstOrNew(['id' => $request->id]);
                $response_id = $request->get('response_id');
                $response = ModelsResponse::find($response_id);
                $response->status = 2;
                $response->save();


                $model->fill($request->except(['location_id', 'device_type_id', 'device_brand_id']));

                $locationId = $request->get('location_id');
                if ($request->has('location_id') && is_numeric($locationId)) {
                    $model->location_id = $locationId;
                }

                $deviceTypeId = $request->get('device_type_id');
                if ($request->has('device_type_id') && is_numeric($deviceTypeId)) {
                    $model->device_type_id = $deviceTypeId;
                }

                $deviceBrandId = $request->get('device_brand_id');
                if ($request->has('device_brand_id') && is_numeric($deviceBrandId)) {
                    $model->device_brand_id = $deviceBrandId;
                }


                if (!$model->uuid) {
                    $todayActs = Act::count();
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

        $response = Gate::inspect('delete', Act::find($id));

        if ($response->allowed()) {


            $result = ['status' => Response::HTTP_FORBIDDEN, 'success' => false, 'errs' => [], 'result' => [], 'statusText' => ""];

            try {

                $destroy = Act::find($id)->delete();

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
}
