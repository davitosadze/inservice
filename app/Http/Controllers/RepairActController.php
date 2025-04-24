<?php

namespace App\Http\Controllers;

use App\Models\RepairAct;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class RepairActController extends Controller
{

    public function index() {}
    public function edit($id)
    {

        $model = RepairAct::firstOrNew(['id' => $id]);
        $this->authorize('view', $model);


        $additional = [];
        $setting = [
            'columns' => [['field' => "name"]],
            'url' => [
                'request' =>
                [
                    'index' => route('repairs.index', ["type" => "pending"]),
                    'exit' =>  route('repairs.index', ["type" => "pending"]),

                ],
            ]
        ];

        return view('repair-acts.modify', ['model' => $model, 'additional' => $additional, 'setting' => $setting]);
    }





    public function export($id)
    {

        $model = RepairAct::where('id', $id)->with(['location', 'deviceType', 'deviceBrand', 'repair'])->first();

        $act = RepairAct::find($id);

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
