<?php

namespace App\Http\Controllers;

use App\Models\Purchaser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
        }

        return response()->json($dates, 200);
    }
}
