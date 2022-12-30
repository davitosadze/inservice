<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Models\Purchaser;

use Illuminate\Support\Facades\Gate;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class PurchaserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return response(Purchaser::whereNot('single', 1)->get());
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
        $response = $request->id ? Gate::inspect('update', Purchaser::find($request->id)) : Gate::inspect('create', Purchaser::class);

        if ($response->allowed()) {

            $validator = Validator::make($request->all(), [
                'name' => [
                    'required'
                ],
                'subj_name' => [
                    'required'
                ],
                'subj_address' => [
                    'required'
                ],
                'identification_num' => [
                    'required'
                ]
            ]);

            if ($validator->fails()) {
                $result['errs'] = $validator->errors()->all();
                $result['statusText'] = 'შეცდომა, მონაცემების განახლებისას';

                return response()->json($result);
            };
        

            $model = Purchaser::firstOrNew(['id' => $request->id]);
            $model->fill($request->all());
            $model->save();

            $result['result'] = $model;
            $result['success'] = true;
            $result['status'] = Response::HTTP_CREATED;
            $result['statusText'] = 'მონაცემები განახლდა წარმატებით';

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

        $result = ['status' => Response::HTTP_FORBIDDEN, 'success' => false,'errs' => [], 'result' => [], 'statusText' => "" ];

        $response = Gate::inspect('delete', Purchaser::find($id));

        if ($response->allowed()) {

            try {

                $destroy = Purchaser::find($id)->delete();

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
