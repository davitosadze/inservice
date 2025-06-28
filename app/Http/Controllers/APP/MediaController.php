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
        
        // Determine model type and folder name
        if ($request->type == "response") {
            $model = Response::find($request->get('model_id'));
            $folder_name = "რეაგირების სურათები";
        } elseif ($request->type == "service") {
            $model = Service::find($request->get('model_id'));
            $folder_name = "სერვისის სურათები";
        } else {
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
            // Sanitize name/address for metadata
            $clientName = preg_replace('/[^\p{L}\p{N}]/u', '', $purchaser->name);
            $address = preg_replace('/[^\p{L}\p{N}]/u', '', $purchaser->subj_address);

            // Use raw comment for metadata
            $originalComment = $request->get('comment') . '-' . Str::random(5);
            $comment = $originalComment;

            // Slugify for filename to avoid Unicode issues
            $safeFileName = Str::slug($originalComment, '_') . '.png';

            $location = $request->get('location');

            try {
                \DB::beginTransaction();

                $media = $purchaser->addMediaFromRequest('file')
                    ->withCustomProperties([
                        'date' => now()->format('Y-m-d'),
                        'client_name' => $clientName,
                        'address' => $address,
                        'folder_name' => $folder_name,
                        'location' => $location,
                    ])
                    ->usingFileName($safeFileName)
                    ->toMediaCollection('Additional_InformationFiles', 'spaces');

                // Store the actual Georgian comment as metadata
                $media->setCustomProperty('comment', $comment);
                $media->save();

                $mediaPath = $media->getPath();

                if (!Storage::disk('spaces')->exists($mediaPath)) {
                    \DB::rollBack();
                    \Log::error('File was not found on DigitalOcean Spaces after upload');
                    return response()->json([
                        "message" => "File upload failed: File was not found on storage after upload",
                        "success" => false
                    ], 500);
                }

                \DB::commit();

                return response()->json([
                    "message" => "File Uploaded Successfully",
                    "success" => true,
                    "media_url" => $media->getUrl()
                ], 200);
            } catch (\Exception $e) {
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
