<?php

namespace App\Http\Controllers;

use App\Models\Purchaser;
use App\Models\Region;
use App\Models\Repair;
use App\Models\RepairDevice;
use App\Models\Response;
use App\Models\Service;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use ExpoSDK\Expo;
use ExpoSDK\ExpoMessage;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class RepairController extends Controller
{
    public function index(Request $request)
    {

        $this->authorize('viewAny', Repair::class);



        $setting = [
            'columns' => [
                ['field' => "title", 'headerName' => '№', "valueGetter" => 'data.id', "flex" => 0.5, 'cellStyle' => ['textAlign' => 'center'], 'headerClass' => 'text-center'],
                ['field' => "region_name", 'headerName' => 'რეგიონი', "valueGetter" => 'data.region.name'],

                ['field' => "purchaser_name", 'headerName' => 'კლიენტის სახ.', "valueGetter" => 'data.name'],
                ['field' => "purchaser_address", 'headerName' => 'ობიექტის მისამართი.', "valueGetter" => 'data.subject_address'],
                ['field' => "purchaser_subj_name", 'headerName' => 'ობიექტის სახ.', "valueGetter" => 'data.subject_name'],

                ['field' => "user", 'headerName' => 'მომხმარებელი', "valueGetter" => 'data.user.name'],
                ['field' => "job_time", 'headerName' => 'დრო'],
                ['field' => "purchaser_address", 'headerName' => 'თარიღი', "valueGetter" => 'data.created_at', 'type' => ['dateColumn', 'nonEditableColumn']],
            ],
            'model' => 'repairs',
            'url' => [
                'request' =>
                [
                    'index' => route('api.repairs.index', ["type" => $request->get('type')]),
                    'show' => route('repairs.show', ['repair' => "new"]),
                    'edit' => route('repairs.edit', ['repair' => "new"]),
                    'destroy' => route('api.repairs.destroy', ['repair' => "__delete__"])
                ]
            ],
            "table_view_enabled" => true,

        ];

        $additional = [
            'performers' =>  User::where('id', "!=", auth()->user()->id)->whereHas('roles', function (Builder $query) {
                $query->whereIn('name', ['ინჟინერი']);
            })->get()->toArray(),
        ];

        if (Auth::user()->roles->contains('name', 'ინჟინერი')) {
            $repairs = Repair::with(['user', 'purchaser', 'region'])->orderBy('id', 'desc')
                ->whereIn("status", [1, 5, 10])
                ->where("performer_id", Auth::user()->id);
        } elseif (Auth::user()->roles->contains('name', 'ტექნიკური მენეჯერი - შეზღუდული')) {
            $repairs = Repair::with(['user', 'purchaser', 'region'])->orderBy('id', 'desc')
                ->where("user_id", Auth::user()->id);
        } else {
            $repairs = Repair::with(['user', 'purchaser', 'region'])->orderBy('id', 'desc');
        }

        if ($request->get("type") == "done") {
            $repairs = $repairs->where("status", 3)
                ->orWhere("status", 0)
                ->where("standby_mode", false)
                ->get();
        }
        elseif($request->get("type") == "standby") {
            $repairs = $repairs->where('standby_mode', true)->get();
        } 
        elseif($request->get("type") == "client-pending") {
            $repairs = $repairs->where('status', 4)
            ->where("standby_mode", false)
            ->get();
        }
        else {
            $repairs = $repairs->whereIn("status", [1, 2, 5, 10])
                ->where("standby_mode", false)
                ->get();
        }

 

        return view('repairs.index', ['additional' => $additional, 'setting' => $setting, 'repairs' => $repairs]);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Repair
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Repair
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'subject_name' => ['required'],
            'subject_address' => ['required'],
            'performer_id' => ['required'],
            'estimated_arrival_time' => ['required_with:performer_id'],
            'name' => ['required'],
            // 'identification_num' => ['required'],
        ]);

        if ($validator->fails()) {
            $result['errs'] = $validator->errors()->all();
            $result['statusText'] = 'შეცდომა, მონაცემების განახლებისას';

            return response()->json($result);
        };

        $result = [
            'status' => HttpResponse::HTTP_FORBIDDEN,
            'success' => false,
            'errs' => [],
            'result' => [],
            'statusText' => ""
        ];

        // $response = $request->id ? $this->authorize('update', Repair::find($request->id)) : $this->authorize('create', Repair::class);

        DB::beginTransaction();

        try {

            $model = Repair::firstOrNew(['id' => $request->id]);


            $model->fill($request->all());


            if (!$model->user) {
                $model->user()->associate(auth()->user());
            }

            if ($model->status == 2) {
                $model->status = 3;
                $model->act()->update([
                    "note" => $request->get("job_description"),
                    "inventory_code" => $request->get("inventory_number"),
                    "uuid" => $request->get("requisites"),
                ]);
            }

            if (!$model->id) {
                $model->status = 1;
                $model->save();

                if ($model->performer?->expo_token) {
                    $messages = [
                        new ExpoMessage([
                            'title' => 'რემონტი',
                            'body' => 'თქვენ მიიღეთ ახალი სამუშაო',
                            'to' => $model->performer?->expo_token,
                            'data' => [
                                'url' => 'repairs',
                                'id' => $model->id,
                            ],
                            'sound' => 'default',  
                            'priority' => 'high',  
                            'channelId' => 'default', 
                        ]),
                    ];
                    (new Expo())->send($messages)->push();
                }
            }

            $model->save();


            if ($request->purchaser) {
                $purchaser = json_decode($request->purchaser);
            } else {
                $purchaser = Purchaser::create([
                    "name" => $request->name,
                    "subj_name" => $request->subject_name,
                    "subj_address" => $request->subject_address,
                    "identification_num" => $request->identification_num,
                    "single" => 1
                ]);
            }

            $model->purchaser_id = $purchaser->id;
            $model->save();


            DB::commit();

            $result['success'] = true;
            $result['result'] = $model;
            $result['status'] = HttpResponse::HTTP_CREATED;
            $result['statusText'] = 'მონაცემები განახლდა წარმატებით';
        } catch (Exception $e) {
            $result['statusText'] = 'შეცდომა, მონაცემების განახლებისას';
            $result['errs'][] = 'გაურკვეველი შეცდომა! ' . $e->getMessage();
            DB::rollBack();
        }

        return response()->json($result, HttpResponse::HTTP_CREATED);
    }

    public function changeMode(Request $request, Repair $repair)
    {
        $repair->standby_mode = $request->get('mode');
        $repair->save();

        return back()->with('success', "რემონტის რეჟიმი წარმატებით შეიცვალა");
    }

    public function assignPerformer(Request $request, Repair $repair)
    {

        $repair->performer_id = $request->get('performer_id');
        $repair->save();

 
            if ($repair->performer?->expo_token) {
                $messages = [
                    new ExpoMessage([
                        'title' => 'რემონტი',
                        'body' => 'თქვენ მიიღეთ ახალი სამუშაო',
                        'to' => $repair->performer?->expo_token,
                        'data' => [
                            'url' => 'repairs',
                            'id' => $repair->id,
                        ],
                        'sound' => 'default',  
                        'priority' => 'high',  
                        'channelId' => 'default', 
                    ]),
                ];
                (new Expo())->send($messages)->push();
            }
    
        return back()->with('success', "შემსრულებელი წარმატებით მიება");
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Repair
     */
    public function show(Repair $repair)
    {
        return view("repairs.view", compact('repair'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Repair
     */
    public function edit($id)
    {
        $model = Repair::firstOrNew(['id' => $id]);
        $this->authorize('view', $model);

 
        if (!$model['id'] && $id != 'new') {
            abort(404);
        }
        

        
 
        $additional = [
            'purchasers' => Purchaser::whereNot('single', 1)->get()->toArray(),
            'performers' =>  User::where('id', "!=", auth()->user()->id)->whereHas('roles', function (Builder $query) {
                $query->whereIn('name', ['ინჟინერი']);
            })->get()->toArray(),
            'regions' => Region::get()->toArray(),
            'repair_devices' => RepairDevice::get()->toArray(),
            'descmodel' => $model->fromModel,
        ];



        $setting = ['url' => ['request' => ['index' => route('repairs.index', ["type" => "pending"])]]];

        return view('repairs.modify', ['model' => $model, 'additional' => $additional, 'setting' => $setting,]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Repair
     */
    public function update(Request $request, $id)
    {
        //
    }

    public function arrived($id)
    {
        $response = Repair::find($id);
        $response->time = Carbon::now();
        $response->status = 10;
        $response->save();
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Repair
     */
    public function destroy($id)
    {
        $result = ['status' => HttpResponse::HTTP_FORBIDDEN, 'success' => false, 'errs' => [], 'result' => [], 'statusText' => ""];

        $response = Gate::inspect('delete', Repair::find($id));

        $response = Repair::find($id);
        $response->delete();
        return redirect()->back();
    }

    public function newIndex()
    {
        $this->authorize('viewAny', Repair::class);

        $setting = [
            'columns' => [
                ['field' => "title", 'headerName' => '№', "valueGetter" => 'data.id', "flex" => 0.5, 'cellStyle' => ['textAlign' => 'center'], 'headerClass' => 'text-center'],
                ['field' => "region.name", 'headerName' => 'რეგიონი', "valueGetter" => 'data.region?.name'],
                ['field' => "name", 'headerName' => 'კლიენტის სახ.', "valueGetter" => 'data.name'],
                ['field' => "subject_address", 'headerName' => 'ობიექტის მისამართი', "valueGetter" => 'data.subject_address'],
                ['field' => "subject_name", 'headerName' => 'ობიექტის სახ.', "valueGetter" => 'data.subject_name'],
                ['field' => "user.name", 'headerName' => 'მომხმარებელი', "valueGetter" => 'data.user?.name'],
                ['field' => "performer.name", 'headerName' => 'ინჟინერი', "valueGetter" => 'data.performer?.name'],
                ['field' => "job_time", 'headerName' => 'დრო'],
                ['field' => "created_at", 'headerName' => 'თარიღი', "valueGetter" => 'data.created_at', 'type' => ['dateColumn', 'nonEditableColumn']],
            ],
            'model' => 'repairs',
            'url' => [
                'request' => [
                    'index' => '/api/repairs-done-paginated',
                    'show' => route('repairs.show', ['repair' => "new"]),
                    'edit' => route('repairs.edit', ['repair' => "new"]),
                    'destroy' => route('api.repairs.destroy', ['repair' => "__delete__"])
                ]
            ],
            "table_view_enabled" => true,
        ];

        return view('repairs.new', ['additional' => [], 'setting' => $setting]);
    }
}
