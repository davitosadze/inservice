<?php

namespace App\Http\Controllers;

use App\Models\CalendarEvent;
use App\Models\Purchaser;
use App\Models\Region;
use App\Models\Service;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $this->authorize('viewAny', Service::class);



        $setting = [
            'columns' => [
                ['field' => "title", 'headerName' => '№', "valueGetter" => 'data.id', "flex" => 0.5, 'cellStyle' => ['textAlign' => 'center'], 'headerClass' => 'text-center'],
                ['field' => "region_name", 'headerName' => 'რეგიონი', "valueGetter" => 'data.region.name'],

                ['field' => "purchaser_name", 'headerName' => 'კლიენტის სახ.', "valueGetter" => 'data.purchaser.name'],
                ['field' => "purchaser_address", 'headerName' => 'ობიექტის მისამართი.', "valueGetter" => 'data.purchaser.subj_address'],
                ['field' => "purchaser_subj_name", 'headerName' => 'ობიექტის სახ.', "valueGetter" => 'data.purchaser.subj_name'],

                ['field' => "user", 'headerName' => 'მომხმარებელი', "valueGetter" => 'data.user.name'],
                ['field' => "purchaser_address", 'headerName' => 'თარიღი', "valueGetter" => 'data.created_at', 'type' => ['dateColumn', 'nonEditableColumn']],
            ],
            'model' => 'services',
            'url' => [
                'request' =>
                [
                    'index' => route('api.services.index', ["type" => $request->get('type')]),
                    'show' => route('services.show', ['service' => "new"]),
                    'edit' => route('services.edit', ['service' => "new"]),
                    'destroy' => route('api.services.destroy', ['service' => "__delete__"])
                ]
            ],
            "table_view_enabled" => true,

        ];

        $additional = [];

        if (Auth::user()->roles->contains('name', 'ინჟინერი')) {
            $services = Service::with(['user', 'purchaser', 'region'])->orderBy('id', 'desc')
                ->whereIn("status", [1, 10])
                ->where("performer_id", Auth::user()->id);
        } else {
            $services = Service::with(['user', 'purchaser', 'region'])->orderBy('id', 'desc');
        }

        if ($request->get("type") == "done") {
            $services = $services->where("status", 3)
                ->orWhere("status", 0)
                ->get();
        } else {
            $services = $services->whereIn("status", [1, 2, 10])
                ->get();
        }

        return view('services.index', ['additional' => $additional, 'setting' => $setting, 'services' => $services]);
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

        $validator = Validator::make($request->all(), [

            'subject_name' => ['required'],
            'subject_address' => ['required'],
            'region_id' => ['required'],
            'performer_id' => ['required'],
            'name' => ['required'],
            'identification_num' => ['required'],
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

        $response = $request->id ? $this->authorize('update', Service::find($request->id)) : $this->authorize('create', Service::class);

        DB::beginTransaction();

        try {


            $model = Service::firstOrNew(['id' => $request->id]);

            $purchaser = json_decode($request->purchaser);

            $model->fill($request->all());
            $model->purchaser_id = $purchaser->id;


            if (!$model->user) {
                $model->user()->associate(auth()->user());
            }

            if (!$model->id) {
                $model->status = 1;
            }

            if ($model->status == 2) {
                $model->status = 3;
                $model->act()->update([
                    "note" => $request->get("job_description"),
                    "inventory_code" => $request->get("inventory_number"),
                    "uuid" => $request->get("requisites"),
                ]);
            }

            $model->save();

            // $calendarModel = CalendarEvent::firstOrNew(['response_id' => $model->id]);

            // $calendarModel->fill([
            //     "title" => "რეაგირება",
            //     "reason" => $request->job_description,
            //     "content" => $request->content,
            //     "purchaser_id" => $purchaser->id,
            //     "response_id" => $model->id,
            //     "date" => Carbon::now()
            // ])->save();


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


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Service $response)
    {
        return view("services.view", compact('response'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $model = Service::firstOrNew(['id' => $id]);
        $this->authorize('view', $model);

        if (!$model['id'] && $id != 'new') {
            abort(404);
        }
        $additional = [
            'purchasers' => Purchaser::whereNot('single', 1)->get()->toArray(),
            'performers' =>  User::where('id', "!=", auth()->user()->id)->whereHas('roles', function (Builder $query) {
                $query->whereIn('name', ['ინჟინერი']);
            })->get()->toArray(),
            'regions' => Region::get()->toArray()
        ];



        $setting = ['url' => ['request' => ['index' => route('services.index', ["type" => "pending"])]]];

        return view('services.modify', ['model' => $model, 'additional' => $additional, 'setting' => $setting]);
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
    public function destroy($id)
    {
        $result = ['status' => HttpResponse::HTTP_FORBIDDEN, 'success' => false, 'errs' => [], 'result' => [], 'statusText' => ""];

        $response = Gate::inspect('delete', Service::find($id));

        $response = Service::find($id);
        $response->delete();
        return redirect()->back();
    }

    public function arrived($id)
    {
        $response = Service::find($id);
        $response->time = Carbon::now();
        $response->status = 10;
        $response->save();
        return back();
    }
}