<?php

namespace App\Http\Controllers\APP;

use App\Http\Controllers\Controller;
use App\Models\Repair;
use App\Models\Response;
use App\Models\Service;
use App\Models\ServiceAct;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Str;

class MediaController extends Controller
{
    public function uploadMedia(Request $request)
    {

        $folder_name = "";
        if($request->type == "response") {
            $model = Response::find($request->get('model_id'));
            $folder_name = "რეაგირების სურათები";
        } elseif($request->type == "service"){
            $model = Service::find($request->get('model_id'));
            $folder_name = "სერვისის სურათები";
        }
         else {
            $model = Repair::find($request->get('model_id'));
            $folder_name = "რემონტის სურათები";
        }

         $purchaser = $model->purchaser;

        if ($request->hasFile('file')) {

            $clientName =  preg_replace('/[^\p{L}\p{N}]/u', '', $purchaser->name);
            $address = preg_replace('/[^\p{L}\p{N}]/u', '', $purchaser->subj_address);
            $comment = $request->get('comment') . "-" . Str::random(5);
            $location = $request->get('location');

            $media = $purchaser->addMediaFromRequest('file')
                ->withCustomProperties([
                    'date' => now()->format('Y-m-d'),
                    'client_name' => $clientName,
                    'address' => $address,
                    'folder_name' => $folder_name,
                    'location' => $location,
                ])
                ->usingFileName($comment . '.png')
                ->toMediaCollection('Additional_InformationFiles', 'spaces');

            $media->setCustomProperty('comment', $comment);
            $media->save();
        }

        return response()->json(["message" => "File Uploaded Successfully", "success" => true], 200);
    }
}
