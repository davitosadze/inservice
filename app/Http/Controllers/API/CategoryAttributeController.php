<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Http\Response;

use App\Models\CategoryAttribute;
use App\Models\CategoryAttributeAlter;

use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class CategoryAttributeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
         return response(CategoryAttribute::where('parent_uuid', null)->with('nested')->orderBy('id', 'desc')->get()->toArray());
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
        // $alter = collect($request->alter)->filter(function ($value, $key) {
        //     return $value['type'] != 'main';
        // })->toArray();
        // $inter = array_map(function ($item) use ($intersect) { return array_intersect_key($item, array_flip($intersect)); }, $request->all());
        // CategoryAttribute::upsert($request->send, ['id'], array_keys($request->send[0]));

        $result = ['status' => Response::HTTP_FORBIDDEN, 'success' => false,'errs' => [], 'result' => [], 'statusText' => "" ];

        try {

            $upsert = CategoryAttribute::upsert($request->all(), ['uuid'], array_keys($request->all()[0]));

            if ($upsert) {
               $result['success'] = true;
               $result['result'] = $upsert;
               $result['status'] = Response::HTTP_CREATED;
               $result = Arr::prepend($result, 'მონაცემები განახლდა წარმატებით', 'statusText');
            }

        } catch(Exception $e) {
            $result = Arr::prepend($result, 'შეცდომა, მონაცემების განახლებისას', 'statusText');
            $result = Arr::prepend($result, Arr::prepend($result['errs'], 'გაურკვეველი შეცდომა! '. $e->getMessage()), 'errs');
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

        return response(CategoryAttribute::where('parent_id', null)->where('category_id', $id)->with('nested')->get());
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

        try {
            
            $destroy = CategoryAttribute::find($id)->delete();

            if ($destroy) {
               $result['success'] = true;
               $result['result'] = $id;
               $result['status'] = Response::HTTP_CREATED;
            }

        } catch(Exception $e) {
            $result['errs'][0] = 'გაურკვეველი შეცდომა! '. $e->getMessage();
        }

        return response()->json($result, Response::HTTP_CREATED);
    }
}
