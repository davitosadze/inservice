<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Models\Category;

use Exception;

use Illuminate\Support\Facades\Gate;

use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

        return response(Category::orderBy('id', 'desc')->get()->toArray());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //

        $result = ['status' => Response::HTTP_FORBIDDEN, 'success' => false,'errs' => [], 'result' => [], 'statusText' => "" ];

        $response = $request->id ? Gate::inspect('update', Category::find($request->id)) : Gate::inspect('create', Category::class);

        if ($response->allowed()) {

            $validator = Validator::make($request->all(), [
                'name' => [
                    'required',
                    Rule::unique('categories')->ignore($request->id)
                ],
                'name' => [
                    'required'
                ],
                'type' => [
                    'required'
                ],
                'description' => [
                    'required'
                ]
            ]);

            if ($validator->fails()) {

                $result['errs'] = $validator->errors()->all();
                $result['statusText'] = 'შეცდომა, მონაცემების განახლებისას';

                return response()->json($result);
            };

            try {

                // throw new Exception("Try 123");
                // $destroy = CategoryAttribute::find($id)->delete();

                $model = Category::firstOrNew(['id' => $request->id]);
                $model->fill($request->all());
                $model->save();

                $result = Arr::prepend($result, true, 'success');
                $result = Arr::prepend($result, $model, 'result');
                $result = Arr::prepend($result, Response::HTTP_CREATED, 'status');
                $result = Arr::prepend($result, 'მონაცემები განახლდა წარმატებით', 'statusText');

            } catch(Exception $e) {

                $result = Arr::prepend($result, 'შეცდომა, მონაცემების განახლებისას', 'statusText');
                $result = Arr::prepend($result, Arr::prepend($result['errs'], 'გაურკვეველი შეცდომა! '. $e->getMessage()), 'errs');
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
        //

        $response = Gate::inspect('delete', Category::find($id));
 
        if ($response->allowed()) {
            

            $result = ['status' => Response::HTTP_FORBIDDEN, 'success' => false,'errs' => [], 'result' => [], 'statusText' => "" ];

            try {

                $destroy = Category::find($id)->delete();

                if ($destroy) {
                   $result['success'] = true;
                   $result['result'] = $id;
                   $result['status'] = Response::HTTP_CREATED;
                }

            } catch(Exception $e) {
                $result['errs'][0] = 'გაურკვეველი შეცდომა! '. $e->getMessage();
            }

            return response()->json($result, Response::HTTP_CREATED);


        } else {
            $result['errs'][0] = $response->message();
            return response()->json($result);
        }
    }
}
