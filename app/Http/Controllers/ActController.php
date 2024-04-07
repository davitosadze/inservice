<?php

namespace App\Http\Controllers;

use App\Exports\ActExport;
use App\Models\Act;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ActController extends Controller
{

    public function index()
    {
    }
    public function edit($id)
    {

        $model = Act::firstOrNew(['id' => $id]);
        $this->authorize('view', $model);


        $additional = [];
        $setting = [
            'columns' => [['field' => "name"]],
            'url' => [
                'request' =>
                [
                    'index' => route('api.acts.index')
                ],
            ]
        ];

        return view('acts.modify', ['model' => $model, 'additional' => $additional, 'setting' => $setting]);
    }



    public function export($id)
    {
        $act = Act::where('id', $id)->with(['location', 'deviceType', 'deviceBrand', 'response'])->first();
        $year = Carbon::parse($act->created_at)->year % 10;

        $act_name = "აქტი#" . $act->id;
        return Excel::download(new ActExport($act), '' . $act_name . '.xlsx');
    }
}
