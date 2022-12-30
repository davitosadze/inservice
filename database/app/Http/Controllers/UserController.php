<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use File;
use App\Models\User;
use Spatie\Permission\Models\Role;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Builder;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $users = User::where('id', "!=", auth()->user()->id)->whereDoesntHave('roles', function (Builder $query) {
            $query->whereIn('name', ['director']);
        })->get();

        return view('user.index', ['model' => $users]);
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

    public function upload(Request $request){
          $path = public_path('tmp/uploads');

          if (!file_exists($path)) {
            mkdir($path, 0777, true);
          }

          $file = $request->file('image');

          $name = uniqid() . '_' . trim($file->getClientOriginalName());

          $file->move($path, $name);

          return ['name'=>$name];
        }

    public function upload2($item){
            $images = User::with(['media'])->find($item);

            if (!$images) {
                $images = [];
            } else {
                $images = $images->getMedia('credential')->toArray();
            };

            return ['media'=>$images];
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
                Rule::unique('users')->ignore($request->id, 'id')
            ],

            'email' => [
                'required',
                Rule::unique('users')->ignore($request->id, 'id')
            ],

            'media' => [
                'required'
            ],

            'inter_password' => auth()->user()->hasRole('director') || auth()->user()->id == $request->id ? [
                'required',
                'confirmed'
            ] : []
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        };

        $model = User::firstOrNew(['id' => $request->id]);
        $model->fill($request->all());

        $request->whenHas('signature', function ($input) use ($model) {
            $from = public_path('tmp/uploads/');
            $model->addMedia($from . $input)->toMediaCollection('credential');
            if (File::exists($from . $model)) File::delete($from . $model);
        });

        $model->password = $request->inter_password;

        $model->save();

        $roles = $request->roles ?: [];
        if ($model->hasRole('director')) array_push($roles, Role::where('name', 'director')->first()->id);

        $model->syncRoles($roles);

        return redirect()->route('users.index')->withSuccess('მონაცემები განახლდა წარმატებით');
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
        $model = User::firstOrNew(['id' => $id]);

        if (!$model['id'] && $id != 'new') {
            abort(404);
        }

        return view('user.modify', ['model' => $model, 'roles' => Role::latest()->where('name', '!=', 'director')->get()]);
    }

    public function profile()
    {
        //
        $model = auth()->user();

        return view('profile', ['model' => $model, 'roles' => Role::latest()->where('name', '!=', 'director')->get()]);
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

        $model = User::find($id)->delete();

        return redirect()->route('users.index')->withSuccess(__('წაშლა შესრულდა წარმატებით!'));
    }
}
