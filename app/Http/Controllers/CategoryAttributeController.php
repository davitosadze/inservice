<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Category;
use App\Models\CategoryAttribute;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class CategoryAttributeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //

        $model = CategoryAttribute::where('parent_uuid', null)->where('category_id', $request->category)->with('nested')->get();
        // if ($model->isEmpty()) {
        //     $attr = new CategoryAttribute();
        //     $attr->id =  CategoryAttribute::count() + 1;
        //     $attr->category_type = true;
        //     $model = [$attr];
        // }

        $additional = ['category' => Category::find($request->category), /* 'last_num' => CategoryAttribute::latest('id')->first()->id */];
        $setting = [
            'url' => [
                'request' => 
                    [
                        'index' => route('api.categories.index'),
                        'exit' =>  route('categories.edit', ['category' => 'new']),
                        'destroy' => route('api.category-attributes.destroy', ['category_attribute' => 'delete'])
                    ]
            ]
        ];

        return view('category_attributes.modify', ['model' => $model, 'additional' => $additional, 'setting' => $setting]);
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
    public function store(Request $request)
    {
        //
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
    public function edit($id = null)
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
    }
}
