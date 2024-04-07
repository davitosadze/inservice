<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\DeviceBrand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Arr;
use Illuminate\Http\Response;

class DeviceBrandController extends Controller
{
    public function index()
    {
        return response(DeviceBrand::get()->toArray());
    }

    public function destroy($id)
    {
        $result = ['status' => Response::HTTP_FORBIDDEN, 'success' => false, 'errs' => [], 'result' => [], 'statusText' => ""];

        $response = Gate::inspect('delete', DeviceBrand::find($id));

        if ($response->allowed()) {

            DB::beginTransaction();

            try {
                $deviceBrand = DeviceBrand::find($id);
                $deviceBrand->delete();
                $result['success'] = true;
                $result['status'] = Response::HTTP_CREATED;
                $result = Arr::prepend($result, 'მონაცემები განახლდა წარმატებით', 'statusText');

                DB::commit();
            } catch (Exception $e) {
                $result = Arr::prepend($result, 'შეცდომა, მონაცემების განახლებისას', 'statusText');
                $result = Arr::prepend($result, Arr::prepend($result['errs'], 'გაურკვეველი შეცდომა! ' . $e->getMessage()), 'errs');

                DB::rollBack();
            }

            return response()->json($result, Response::HTTP_CREATED);
        } else {
            $result['errs'][0] = $response->message();
            return response()->json($result);
        }
    }
}
