<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Category;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    // public function __construct()
    // {
    //     $this->authorizeResource(Category::class);
    // }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

        $this->authorize('viewAny', Category::class);

        $additional = [];
        $setting = [
            'columns' => [ ['field' => "name", 'headerName' => 'დასახელება'], ['field' => "description", 'headerName' => 'აღწერა'], ['headerName' => 'ერთეული', 'field' => "type"] ],
            'model' => ['target' => 'Category'],
            'url' => [
                'request' => 
                    [
                        'index' => route('api.categories.index'), 
                        'edit' => route('categories.edit', ['category' => "new"]), 'destroy' => route('api.categories.destroy', ['category' => "__delete__"])
                    ],
                'neseted' => 
                    ['attributes' => route('api.category-attributes.index', ['category' => '{category}'])]
            ]
        ];

        return view('categories.index', ['additional' => $additional, 'setting' => $setting]);
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
            return redirect()->back()->withErrors($validator)->withInput();
        };

        $model = Category::firstOrNew(['id' => $request->id]);
        $model->fill($request->all());
        $model->save();

        $saveOrUpdate = $request->id ? 'განახლდა' : 'დაემატა';

        $message = [
          'flashType'    => 'success',
          'flashMessage' => 'მუნიციპალიტეტი '. $saveOrUpdate .' წარმატებით'
        ];

        return redirect()->route('categories.index')->withInput()->withErrors([])->with($message);
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

        $model = Category::firstOrNew(['id' => $id]);
        $this->authorize('view', $model);


        $additional = [];
        $setting = [
            'columns' => [ ['field' => "name"], ['field' => "description"], ['field' => "type"] ],
            'url' => [
                'request' => 
                [
                    'index' => route('api.categories.index')
                ],
                'nested' => 
                    ['attributes' => route('categories.category-attributes.index', ['category' => '__category__'])]
            ]
        ];

        return view('categories.modify', ['model' => $model, 'additional' => $additional, 'setting' => $setting]);
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
