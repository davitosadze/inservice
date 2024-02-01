<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Purchaser;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class PurchaserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

        $this->authorize('viewAny', Purchaser::class);

        $additional = [];
        $setting = [
            'columns' => [['field' => "title", 'headerName' => '№', "valueGetter" => 'data.id', "flex" => 0.5, 'cellStyle' => ['textAlign' => 'center'], 'headerClass' => 'text-center'], ['field' => "name", 'headerName' => 'საიდენთიპიკაციო კოდი', "valueGetter" => 'data.identification_num'], ['field' => "name", 'headerName' => 'კლიენტის სახელი'], ['field' => "subj_name", 'headerName' => 'დამატებითი სახელი'], ['field' => "subj_address", 'headerName' => 'კლიენტის მისამართი']],
            'url' => [
                'request' =>
                [
                    'index' => route('api.purchasers.index'),
                    'edit' => route('purchasers.edit', ['purchaser' => "new"]),
                    'show' => route('purchasers.show', ['purchaser' => "new"]),
                    'destroy' => route('api.purchasers.destroy', ['purchaser' => "__delete__"])
                ]
            ],
            'is_table_advanced' => true,
            "table_view_enabled" => true,

        ];
        return view('purchasers.index', ['additional' => $additional, 'setting' => $setting]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $model = Purchaser::find($id);
        // return $model;
        $this->authorize('view', $model);

        $additional = [];
        $setting = [

            'url' => [
                'request' =>
                [
                    'index' => route('api.clients.index')
                ],
            ]

        ];

        $model["gallery"] = $model->getMedia('purchaser-gallery');
        return view('purchasers.view', ['model' => $model, 'additional' => $additional, 'setting' => $setting]);
    }


    public function purchaserFiles($purchaser_id)
    {
        $model = Purchaser::find($purchaser_id);
        function mapMediaFiles($mediaFiles, $collectionName)
        {
            return $mediaFiles->map(function ($media) use ($collectionName) {
                return [
                    'source' => $media->getFullUrl(),
                    'options' => [
                        'type' => 'local',
                        'file' => [
                            'id' => $media->id,
                            'name' => $media->file_name,
                            'size' => $media->size,
                            'type' => $media->mime_type,
                        ],
                    ],
                ];
            });
        }

        $accountingFiles = mapMediaFiles($model->getMedia('AccountingFiles'), 'AccountingFiles');
        $performanceActsFiles = mapMediaFiles($model->getMedia('Performance_ActsFiles'), 'performanceActsFiles');
        $technicalDocumentationFiles = mapMediaFiles($model->getMedia('Technical_DocumentationFiles'), 'technicalDocumentationFiles');
        $additionalInformationFiles = mapMediaFiles($model->getMedia('Additional_InformationFiles'), 'additionalInformationFiles');

        $files["AccountingFiles"] = $accountingFiles->toArray();
        $files["performanceActsFiles"] = $performanceActsFiles->toArray();
        $files["technicalDocumentationFiles"] = $technicalDocumentationFiles->toArray();
        $files["additionalInformationFiles"] = $additionalInformationFiles->toArray();

        return $files;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $model = Purchaser::firstOrNew(['id' => $id]);
        $this->authorize('view', $model);

        $additional = [];
        $setting = [
            'url' => [
                'request' =>
                [
                    'attrs' => route('purchasers.special-attributes.index', ['purchaser' => 'new']),
                    'index' => route('api.purchasers.index')
                ]
            ]
        ];

        return view('purchasers.modify', ['model' => $model, 'additional' => $additional, 'setting' => $setting]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    public function uploadPurchaserGallery(Request $request, $id)
    {
        $purchaser = Purchaser::find($id);
        $purchaser->addMediaFromRequest('purchaserGallery')->toMediaCollection('purchaser-gallery');

        return response()->json($purchaser, 200);
    }

    public function purchaserGallery($id)
    {
        $purchaser = Purchaser::where('id', $id)->first();
        $gallery = $purchaser->getMedia('purchaser-gallery');
        return $gallery->map(function ($media) {
            return [
                'source' => $media->getFullUrl(),

                'options' => [
                    'type' => 'local',
                    'file' => [
                        'id' => $media->id,
                        'name' => $media->file_name,
                        'size' => $media->size,
                        'type' => $media->mime_type,
                    ],
                ],
            ];
        });
    }

    public function uploadFiles(Request $request, $purchaser_id)
    {
        $purchaser = Purchaser::find($purchaser_id);

        $mediaTypes = [
            'Performance_ActsFiles' => 'Performance_ActsFiles',
            'Technical_DocumentationFiles' => 'Technical_DocumentationFiles',
            'Additional_InformationFiles' => 'Additional_InformationFiles',
            'AccountingFiles' => 'AccountingFiles',
        ];

        foreach ($mediaTypes as $requestKey => $collectionName) {
            if ($request->has($requestKey)) {
                $purchaser->addMediaFromRequest($requestKey)->toMediaCollection($collectionName);
                return response()->json(["message" => "File successfully uploaded"], 200);
            }
        }

        return response()->json(["message" => "No valid file key found in the request"], 400);
    }


    public function deleteFiles(Request $request, $id)
    {
        $media = Media::findOrFail($id);

        $media->delete();

        return response()->json(['message' => 'Media file deleted successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
