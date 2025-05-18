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
        try {
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

            if (!$model) {
                return response()->json(["message" => "Model not found", "success" => false], 404);
            }

            $purchaser = $model->purchaser;

            if (!$purchaser) {
                return response()->json(["message" => "Purchaser not found", "success" => false], 404);
            }

            if ($request->hasFile('file')) {
                $clientName = preg_replace('/[^\p{L}\p{N}]/u', '', $purchaser->name);
                $address = preg_replace('/[^\p{L}\p{N}]/u', '', $purchaser->subj_address);
                $comment = $request->get('comment') . "-" . Str::random(5);
                $location = $request->get('location');
                
                try {
                    // Use database transaction
                    \DB::beginTransaction();
                    
                    // First upload the file - this should do all database operations in the transaction
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
    
                    // Set additional properties after upload
                    $media->setCustomProperty('comment', $comment);
                    $media->save();
                    
                    // Get the file path for verification
                    $mediaPath = $media->getPath();
                    
                    // Verify the file exists on storage after upload
                    if (!Storage::disk('spaces')->exists($mediaPath)) {
                        // If file isn't found on storage, roll back the transaction
                        \DB::rollBack();
                        \Log::error('File was not found on DigitalOcean Spaces after upload');
                        return response()->json([
                            "message" => "File upload failed: File was not found on storage after upload", 
                            "success" => false
                        ], 500);
                    }
                    
                    // If file exists, commit the transaction
                    \DB::commit();
                    $mediaUrl = $media->getUrl();
                    
                    return response()->json([
                        "message" => "File Uploaded Successfully", 
                        "success" => true,
                        "media_url" => $mediaUrl
                    ], 200);
                    
                } catch (\Exception $e) {
                    // If any error occurs during the process, roll back the transaction
                    if (\DB::transactionLevel() > 0) {
                        \DB::rollBack();
                    }
                    \Log::error('Media upload failed: ' . $e->getMessage());
                    return response()->json([
                        "message" => "File upload failed: " . $e->getMessage(), 
                        "success" => false
                    ], 500);
                }
            }

            return response()->json(["message" => "No file provided", "success" => false], 400);
        } catch (\Exception $e) {
            \Log::error('Media upload failed: ' . $e->getMessage());
            return response()->json([
                "message" => "File upload failed: " . $e->getMessage(), 
                "success" => false
            ], 500);
        }
    }
}
