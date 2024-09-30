<?php

namespace App\Http\Controllers;

use App\Models\ServiceAct;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ServiceActController extends Controller
{
    public function edit($id)
    {

        $model = ServiceAct::firstOrNew(['id' => $id]);
        $this->authorize('view', $model);

        $additional = [];
        $setting = [
            'columns' => [['field' => "name"]],
            'url' => [
                'request' =>
                [
                    'index' => route('services.index', ["type" => "pending"]),
                    'exit' =>  route('services.index', ["type" => "pending"]),

                ],
            ]
        ];

        return view('service-acts.modify', ['model' => $model, 'additional' => $additional, 'setting' => $setting]);
    }





    public function export($id)
    {

        $model = ServiceAct::where('id', $id)->with(['location', 'service'])->first();

        $act = ServiceAct::find($id);

        if ($act && $act->response && $act->response->performer) {
            $performerId = $act->response->performer->id;

            $media = Media::where('model_type', 'App\Models\User')
                ->where('model_id', $performerId)
                ->where('collection_name', 'services-credential')
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
    }
}
