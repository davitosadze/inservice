<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\ClientExpense;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Exception;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Str;

class ClientsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response(Client::orderBy('id', 'desc')->get()->toArray());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function uploadClientFiles(Request $request)
    {


        $filename = mt_rand(1000000000, 9999999999) . '.' . $request->file('file')->getClientOriginalExtension();

        $model_id = $request->model_id;
        if (!$request->model_id) {
            return response()->json("Model Id Not Found");
        }
        if ($request->upload_type == "client_total_files") {
            $model = Client::find($model_id);
            $model->addMedia($request->file)->usingFileName($filename)->toMediaCollection('client_total_files');
        }

        if ($request->upload_type == "client_additional_files") {
            $model = Client::find($model_id);
            $model->addMedia($request->file)->usingFileName($filename)->toMediaCollection('client_additional_files');
        }

        if ($request->upload_type == "expense_files") {
            $model = ClientExpense::find($model_id);
            $model->addMedia($request->file)->usingFileName($filename)->toMediaCollection('expense_files');
        }
    }

    public function updateClientFiles(Request $request, $media_id)
    {

        $media = Media::find($media_id);
        $media->name = $request->name;
        $media->save();

        return response()->json(["success" => "Successfully Updated"], 200);
    }

    public function deleteClientFiles($media_id)
    {
        $media = Media::find($media_id);

        $model_type = $media->model_type;

        $model = $model_type::find($media->model_id);
        $model->deleteMedia($media->id);

        return response()->json(["success" => "Successfully Deleted"], 200);
    }

    public function deleteClientExpense($expense_id)
    {
        ClientExpense::find($expense_id)->delete();
        return response()->json(["success" => true], 200);
    }

    public function addClientExpense(Request $request, $client_id)
    {
        $expense = new ClientExpense();
        $expense->amount = $request->amount;
        $expense->uuid = $request->uuid;
        $expense->client_id = $client_id;
        $expense->save();

        return response()->json(["success" => true, "expense" => $expense], 200);
    }


    public function store(Request $request)
    {

        $result = ['status' => Response::HTTP_FORBIDDEN, 'success' => false, 'errs' => [], 'result' => [], 'statusText' => ""];

        $response = $request->id ? Gate::inspect('update', Client::find($request->id)) : Gate::inspect('create', Client::class);
        if ($response->allowed()) {

            $validator = Validator::make($request->all(), [

                // 'client_name' => [
                //     'required'
                // ],

            ]);

            if ($validator->fails()) {

                $result['errs'] = $validator->errors()->all();
                $result['statusText'] = 'შეცდომა, მონაცემების განახლებისას';

                return response()->json($result);
            };

            try {

                $model = Client::firstOrNew(['id' => $request->id]);
                $model->fill($request->all());
                $model->save();

                foreach ($request->expenses as $item) {
                    if ($item["id"]) {
                        $expense = ClientExpense::find($item["id"]);
                        $expense->amount = $item["amount"];
                        $expense->save();
                    }
                    if (!$model->expenses()->where('uuid', $item["uuid"])->count()) {
                        $expense = new ClientExpense();
                        $expense->amount = $item["amount"];
                        $expense->uuid = $item["uuid"];
                        $model->expenses()->save($expense);
                    }
                }

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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $response = Gate::inspect('delete', Client::find($id));

        if ($response->allowed()) {


            $result = ['status' => Response::HTTP_FORBIDDEN, 'success' => false, 'errs' => [], 'result' => [], 'statusText' => ""];

            try {

                $destroy = Client::find($id)->delete();

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
