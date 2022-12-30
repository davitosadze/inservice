<?php

namespace App\Http\Controllers;

use File;

use App\Models\Purchaser;
use App\Models\Report;
use App\Models\ReportItem;

use Illuminate\Http\Request;

use ImageOptimizer;

use Illuminate\Support\Arr;

use Illuminate\Http\Response;

use Barryvdh\DomPDF\Facade\Pdf;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Gate;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $this->authorize('viewAny', Report::class);

        $additional = [];
        $setting = [
            'columns' => [
                ['field' => "title", 'headerName' => '№', "valueGetter" => 'data.uuid', "flex" => 0.5, 'cellStyle' => ['textAlign' => 'center'], 'headerClass' => 'text-center'],
                ['field' => "title", 'headerName' => 'დასახელება'],
                ['field' => "purchaser_name", 'headerName' => 'კლიენტის სახ.', "valueGetter" => 'data.subject_name'],
                ['field' => "purchaser_subj_name", 'headerName' => 'მისამართი', "valueGetter" => 'data.subject_address'],
                ['field' => "user", 'headerName' => 'მომხმარებელი', "valueGetter" => 'data.user.name'],
                ['field' => "purchaser_address", 'headerName' => 'თარიღი', "valueGetter" => 'data.created_at', 'type' => ['dateColumn', 'nonEditableColumn']],
            ],
            'url' => [
                'request' => 
                    [
                        'show' => route('reports.show', ['report' => "new"]),
                        'edit' => route('reports.edit', ['report' => "new"]),
                        'destroy' => route('reports.destroy', ['report' => 'new', 'inter' => true])
                    ]
                ]
        ];

        return view('reports.index', ['model' => Report::with(['user'])->orderBy('id', 'desc')->get(), 'additional' => $additional, 'setting' => $setting]);
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

    public function InterImage($source, $destination, $quality) { 
        // Get image info 
        $imgInfo = getimagesize($source); 
        $mime = $imgInfo['mime']; 
         
        // Create a new image from file 
        switch($mime){ 
            case 'image/jpeg': 
                $image = imagecreatefromjpeg($source); 
                break; 
            case 'image/png': 
                $image = imagecreatefrompng($source); 
                break; 
            case 'image/gif': 
                $image = imagecreatefromgif($source); 
                break; 
            default: 
                $image = imagecreatefromjpeg($source); 
        } 

        if ($mime == 'image/png') {
            imagesavealpha($image, true);
            imagepng($image, $destination);
        } else {
            imagejpeg($image, $destination, $quality); 
        }
         
        return $destination; 
    }

    public function upload(Request $request) {
        $path = public_path('tmp/uploads'); if (!file_exists($path)) { mkdir($path, 0777, true); }
        $file = $request->file('image'); $name = uniqid() . '_' . trim($file->getClientOriginalName());

        $this->InterImage($file->getPathName(), $path.'/'.$name, 70);
        return ['name'=>$name];
    }


    public function upload2($item){
        $images = ReportItem::with(['media'])->find($item);

        if (!$images) {
            $images = [];
        } else {
            $images = $images->getMedia('report')->toArray();
        };

        return ['media'=>$images];
    }

    public function getUid ($invoice) {
        return $invoice->year.'.'.$invoice->month.'.'.sprintf("%02d", $invoice->inovices_length).'.'.sprintf("%02d", auth()->user()->id);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $result = ['status' => Response::HTTP_FORBIDDEN, 'success' => false,'errs' => [], 'result' => [], 'statusText' => "" ];

        $response = $request->id ? $this->authorize('update', Report::find($request->id)) : $this->authorize('create', Report::class);

        DB::beginTransaction();

        try {

            $validator = Validator::make($request->all(), [
                'title' => [
                    'required',
                    'notIn:director',
                    Rule::unique('reports')->ignore($request->id, 'id')
                ],
                'subject_name' => [
                    'required'
                ],
                'subject_address' => [
                    'required'
                ],
                'item.*.*.title' => 'required',
                'item.*.*.value' => 'required'
            ]);

            if ($validator->fails()) {
                $result['errs'] = $validator->errors()->all();
                $result['statusText'] = 'შეცდომა, მონაცემების განახლებისას';
                
                return response()->json($result);
            };

            $model = Report::firstOrNew(['id' => $request->id]);
            $model->fill($request->all());

            if (!$model->user) {
                $model->user()->associate(auth()->user());
            }

            if (!$model->id) {

                $user_invoices = $model->user->reports()->firstOrNew(['year' => date('y'), 'month' => date('m'), 'type' => 'report']);

                if (!$user_invoices) {
                    $user_invoices->fill(['year'=> date('y'), 'month' => date('m'), "inovices_length" => 1, 'type' => 'report']);
                } else {
                    $user_invoices->inovices_length = $user_invoices->inovices_length + 1;
                }

                $user_invoices->save();

                $model->uuid = $this->getUid($user_invoices);
            }

            $model->save();

            $request->whenHas('item', function ($input) use ($model) {

                collect($input)->map(function($item) use ($model) {

                    $upserts = collect($item)->map(fn ($i) => array_filter($i, fn ($k) => $k !== 'media', ARRAY_FILTER_USE_KEY)  )->toArray();

                    $model->items()->upsert($upserts, ['uuid'], array_keys($upserts[1]));

                    ReportItem::whereIn('uuid', array_column($item, 'uuid'))->get()->map(function($i) use ($model, $item) {
                        $i->report()->associate($model)->save();
                        $key = array_search($i->uuid, array_column($item, 'uuid'));
                        if (isset($item[$key]) && isset($item[$key]['media']) && !empty($item[$key]['media'])) {
                            $from = public_path('tmp/uploads/');

                            collect($item[$key]['media'])->map(function($single) use ($from, $i) {
                                $i->addMedia($from . $single)->toMediaCollection('report');
                                File::delete($from . $single);
                            });
                        };
                    });

                    // print_r(ReportItem::whereIn('uuid', array_column($item, 'uuid'))->map());
                    // $modelItem = $model->items()->firstOrNew(['id' => isset($item['id']) ? $item['id'] : null]);
                    // $modelItem->fill($item);
                    // if (isset($item['media'])  && !empty($item['media'])) {
                    //     $from = public_path('tmp/uploads/');
                    //     collect($item['media'])->map(function($single) use ($from, $modelItem) {
                    //         $modelItem->addMedia($from . $single)->toMediaCollection('images');
                    //     });
                    // };
                    // $modelItem->save();
                });

            });

            $request->whenHas('deleted_media', function ($input) {
                Media::whereIn('id', Arr::flatten($input))->get()->map(fn ($i) => $i->delete());
            });

            DB::commit();

            $result['success'] = true;
            $result['result'] = $model;
            $result['status'] = Response::HTTP_CREATED;
            $result = Arr::prepend($result, 'მონაცემები განახლდა წარმატებით', 'statusText');
           

        } catch(Exception $e) {
            $result = Arr::prepend($result, 'შეცდომა, მონაცემების განახლებისას', 'statusText');
            $result = Arr::prepend($result, Arr::prepend($result['errs'], 'გაურკვეველი შეცდომა! '. $e->getMessage()), 'errs');

            DB::rollBack();
        }

        return response()->json($result, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //

        $model = Report::with(['items'])->firstOrNew(['id' => $id]);
        $name = $model->uuid. '.pdf';

        $pdf = PDF::setOptions(["isPhpEnabled" => true, 'isRemoteEnabled' => true, 'dpi' => 150, 'defaultFont' => 'sans-serif'])->loadView('reports.show', compact('model'));
        return $pdf->stream($name);

        // return view('reports.show');
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

        $model = Report::with(['items.media'])->firstOrNew(['id' => $id]);
        $this->authorize('view', $model);

        if (!$model['id'] && $id != 'new') {
            abort(404);
        }

        $additional = [
            'purchasers' => Purchaser::get()->toArray()
        ];

        $setting = ['url' => [ 'request' => ['index' => route('reports.index') ] ] ];
    
        return view('reports.modify', ['model' => $model, 'additional' => $additional, 'setting' => $setting]);
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        //
        $result = ['status' => Response::HTTP_FORBIDDEN, 'success' => false,'errs' => [], 'result' => [], 'statusText' => "" ];

        $response = Gate::inspect('delete', Report::find($id));

        if ($response->allowed()) {

            DB::beginTransaction();

            try {

                $request->whenHas('inter', function ($input) use ($id, $result) {
                    $report = Report::find($id);
                    $report->items->map(function($reportItem) {
                        $reportItem->nested()->get()->map(fn($i)=>$i->delete());$reportItem->delete();
                    });
                    $report->delete();
                    $result['result'] = $report;
                }, function () use ($id, $result) {
                    $report = ReportItem::find($id); $report->nested()->get()->map(fn($i)=>$i->delete()); $report->delete();
                    $result['result'] = $report;
                });


                $result['success'] = true;
                $result['status'] = Response::HTTP_CREATED;
                $result = Arr::prepend($result, 'მონაცემები განახლდა წარმატებით', 'statusText');

                DB::commit();
               

            } catch(Exception $e) {
                $result = Arr::prepend($result, 'შეცდომა, მონაცემების განახლებისას', 'statusText');
                $result = Arr::prepend($result, Arr::prepend($result['errs'], 'გაურკვეველი შეცდომა! '. $e->getMessage()), 'errs');

                DB::rollBack();
            }

            return response()->json($result, Response::HTTP_CREATED);

        } else {
            $result['errs'][0] = $response->message();
            return response()->json($result);
        }
    }
}
