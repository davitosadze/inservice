<?php

namespace App\Http\Controllers;

use App\Exports\ActExport;
use App\Exports\TestExport;
use App\Models\Act;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ActController extends Controller
{

    public function index() {}
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
                    'index' => route('responses.index', ["type" => "pending"]),
                    'exit' =>  route('responses.index', ["type" => "pending"]),

                ],
            ]
        ];

        return view('acts.modify', ['model' => $model, 'additional' => $additional, 'setting' => $setting]);
    }





    public function export($id)
    {

        $model = Act::where('id', $id)->with(['location', 'deviceType', 'deviceBrand', 'response'])->first();

        $act = Act::find($id);

        if ($act && $act->response && $act->response->performer) {
            $performerId = $act->response->performer->id;

            $media = Media::where('model_type', 'App\Models\User')
                ->where('model_id', $performerId)
                ->where('collection_name', 'credential')
                ->first();

            if ($media) {
                $signature = $media->original_url;
            } else {
                //  
                $signature = null;
            }
        } else {
            $signature = null;
        }
        $name = "აქტი#" . $model->id . ".pdf";

        $pdf = PDF::setOptions(['isRemoteEnabled' => true, 'dpi' => 150, 'defaultFont' => 'sans-serif'])->loadView('acts.pdf', ['model' => $model, "signature" => $signature]);


        return $pdf->stream($name);


        // $act = Act::where('id', $id)->with(['location', 'deviceType', 'deviceBrand', 'response'])->first();
        // $year = Carbon::parse($act->created_at)->year % 10;

        // $act_name = "აქტი#" . $act->id;
        // return Excel::download(new ActExport($act), '' . $act_name . '.xlsx');
    }
}
