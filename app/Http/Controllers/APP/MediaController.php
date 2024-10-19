<?php

namespace App\Http\Controllers\APP;

use App\Http\Controllers\Controller;
use App\Models\ServiceAct;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    public function uploadMedia(Request $request)
    {
        $serviceAct = ServiceAct::find($request->get('service_act_id'));
        $purchaser = $serviceAct->service?->purchaser;

        if ($request->hasFile('file')) {
            $media = $purchaser->addMediaFromRequest('file')
                ->toMediaCollection('Additional_InformationFiles');

            $media->setCustomProperty('comment', $request->get('comment'));
            $media->save();
        }

        return response()->json(["message" => "File Uploaded Successfully", "success" => true], 200);
    }
}
