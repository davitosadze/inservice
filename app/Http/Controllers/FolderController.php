<?php

namespace App\Http\Controllers;

use App\Models\Purchaser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class FolderController extends Controller
{
    public function getFolders($purchaser_id)
    {
        $purchaser = Purchaser::find($purchaser_id);
        $media = $purchaser->getMedia('Additional_InformationFiles');

        $dates = [];

        foreach ($media as $item) {
            $date = $item->getCustomProperty('date', 'თარიღის გარეშე');
            $location = $item->getCustomProperty('location', 'ლოკაციის გარეშე');
            $fileName = $item->file_name;
            $fileUrl = $item->getUrl();
            $fileUrl = preg_replace('/\d{4}-\d{2}-\d{2}/', $date, $fileUrl);

            if (!array_key_exists($date, $dates)) {
                $dates[$date] = [];
            }

            if (!array_key_exists($location, $dates[$date])) {
                $dates[$date][$location] = [];
            }

            $dates[$date][$location][] = [
                'file_name' => $fileName,
                'file_url'  => $fileUrl,
            ];

            // return response()->json($item, 200);
        }

        return response()->json($dates, 200);
    }

    public function deleteFile(Request $request)
    {
        $fileName = $request->input('fileName');
        $mediaItem = Media::where('file_name', $fileName)->first();

        if ($mediaItem) {
            // Delete media using Spatie Media Library
            $mediaItem->delete();

            return response()->json(['message' => 'File deleted successfully.'], 200);
        }

        return response()->json(['message' => 'File not found.'], 404);
    }

    public function deleteLocation(Request $request)
    {
        $location = $request->input('location');
        $mediaItems = Media::where('location', $location)->get();

        if ($mediaItems->isEmpty()) {
            return response()->json(['message' => 'Location not found or has no files.'], 404);
        }

        foreach ($mediaItems as $mediaItem) {
            $mediaItem->delete(); // Deletes from database and storage
        }

        return response()->json(['message' => 'Location and its files deleted successfully.'], 200);
    }
}
