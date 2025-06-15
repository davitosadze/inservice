<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\ClientExpense;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Exception;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
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

    public function assignUsers(Request $request, Client $client) {
        $client->user_ids = $request->get('user_ids');
        $client->save();
        return response()->json('success', 200);
    }


    public function store(Request $request)
    {
        $result = ['status' => Response::HTTP_FORBIDDEN, 'success' => false, 'errs' => [], 'result' => [], 'statusText' => ""];

        $response = $request->id ? Gate::inspect('update', Client::find($request->id)) : Gate::inspect('create', Client::class);
        if ($response->allowed()) {

            $validator = Validator::make($request->all(), [
                // Add validation rules
                'toggles' => 'nullable|array', // Ensure toggles is an array
            ]);

            if ($validator->fails()) {
                $result['errs'] = $validator->errors()->all();
                $result['statusText'] = 'შეცდომა, მონაცემების განახლებისას';
                return response()->json($result);
            }

            try {
                // Fetch existing model or create new one
                $model = Client::firstOrNew(['id' => $request->id]);

                // Fill model with request data
                $model->fill($request->except('toggles')); // Exclude toggles from mass assignment
                $model->toggles = $request->has('toggles') ? $request->toggles : []; // Save as JSON




                // Save model
                $model->save();

                // Save expenses if available
                if ($request->has('expenses')) {
                    foreach ($request->expenses as $item) {
                        if (isset($item["id"]) && $item["id"]) {
                            $expense = ClientExpense::find($item["id"]);
                            if ($expense) {
                                $expense->amount = $item["amount"];
                                $expense->save();
                            }
                        }
                        if (!$model->expenses()->where('uuid', $item["uuid"])->count()) {
                            $expense = new ClientExpense();
                            $expense->amount = $item["amount"];
                            $expense->uuid = $item["uuid"];
                            $model->expenses()->save($expense);
                        }
                    }
                }

                // Prepare successful response
                $result = Arr::prepend($result, true, 'success');
                $result = Arr::prepend($result, $model, 'result');
                $result = Arr::prepend($result, Response::HTTP_CREATED, 'status');
                $result = Arr::prepend($result, 'მონაცემები განახლდა წარმატებით', 'statusText');
            } catch (Exception $e) {
                // Handle exception
                $result['statusText'] = 'შეცდომა, მონაცემების განახლებისას';
                $result['errs'][] = 'გაურკვეველი შეცდომა! ' . $e->getMessage();
            }

            return response()->json($result, Response::HTTP_CREATED);
        } else {
            $result['errs'][0] = $response->message();
            return response()->json($result);
        }
    }



    public function registerClient(Request $request)
    {

 
        $validatedData = $request->validate([
            'name' => 'required',
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:6'],
            'companyCode' => 'required',
        ]);

        $client = Client::where('identification_code', $validatedData['companyCode'])->first();
        if (!$client) {
            return response()->json(['message' => 'კომპანია ვერ მოიძებნა'], 422);
        }


 
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => $validatedData['password'],  
            'inter_passwrod' => $validatedData['password'],  
            'status' => 0,  
        ]);

        if ($client->user_ids == null) {
            $client->user_ids = json_encode([$user->id]);
        } else {
            $existingUserIds = json_decode($client->user_ids, true) ?? [];
            $client->user_ids = json_encode(array_merge($existingUserIds, [$user->id]));
        }
        $client->save();
        
 
        
        // Assign "კლიენტი" role if not already assigned
        if (!$user->hasRole('კლიენტი')) {
            $user->assignRole('კლიენტი');
        }
        
        return response()->json(['message' => 'დაელოდეთ ვერიფიკაციას', 'user' => $user], 200);
    
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
