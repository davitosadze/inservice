<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Arr;
use Illuminate\Http\Response;
use App\Models\SpecialAttribute;

class SpecialAttributeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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

       //  $req = $request->all();

       //  print_r($req); exit;

       // $s = collect($req['special'])->map(function ($elm) {

       //  $elm['json'] = json_encode($elm['json']);
       //  return $elm;
       // })->toArray();

       // $req['special'] = $s;

        $result = ['status' => Response::HTTP_FORBIDDEN, 'success' => false,'errs' => [], 'result' => [], 'statusText' => "" ];

        DB::beginTransaction();

        try {

            $upsert = SpecialAttribute::upsert($request->all(), ['category_attribute_id', 'purchaser_id'], ['json']);

            if ($upsert) {
               $result['success'] = true;
               $result['result'] = $upsert;
               $result['status'] = Response::HTTP_CREATED;
               $result = Arr::prepend($result, 'მონაცემები განახლდა წარმატებით', 'statusText');
            }

            DB::commit();

        } catch(Exception $e) {
            $result = Arr::prepend($result, 'შეცდომა, მონაცემების განახლებისას', 'statusText');
            $result = Arr::prepend($result, Arr::prepend($result['errs'], 'გაურკვეველი შეცდომა! '. $e->getMessage()), 'errs');

            DB::rollBack();
        }


        return response()->json($result, Response::HTTP_CREATED);
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
    public function destroy($id, $attribute)
    {
        //

        $result = ['status' => Response::HTTP_FORBIDDEN, 'success' => false,'errs' => [], 'result' => [], 'statusText' => "" ];

        DB::beginTransaction();

        try {
            
            $destroy = SpecialAttribute::find($attribute)->delete();

            if ($destroy) {
               $result['success'] = true;
               $result['result'] = $attribute;
               $result['status'] = Response::HTTP_CREATED;
            }

            DB::commit();

        } catch(Exception $e) {

            $result['errs'][0] = 'გაურკვეველი შეცდომა! '. $e->getMessage();

            DB::rollBack();
        }

        return response()->json($result, Response::HTTP_CREATED);
    }
}
