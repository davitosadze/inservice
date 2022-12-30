<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;


use App\Models\Category;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use App\Models\CategoryAttribute;

use App\Models\Evaluation;
use App\Models\Purchaser;
use App\Models\Attribute;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class EvaluationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

        return response(Evaluation::with(['purchaser', 'category_attributes.category', 'user'])->orderBy('id', 'desc')->where('type', 'evaluation')->get()->toArray());
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
        //

        // return collect($request->category_attributes)->filter(function ($value, $key) { return $value['category_id'] == null ;}); exit;


        $result = ['status' => Response::HTTP_FORBIDDEN, 'success' => false,'errs' => [], 'result' => [], 'statusText' => "" ];

        $response = $request->id ? Gate::inspect('update', Evaluation::find($request->id)) : Gate::inspect('create', Evaluation::class);

        if ($response->allowed()) {

            $validator = Validator::make($request->all(), [
                // 'title' => [
                //     'required',
                //     Rule::unique('evaluations')->ignore($request->id)
                // ]
            ]);

            if ($validator->fails()) {
                $result['errs'] = $validator->errors()->all();
                $result['statusText'] = 'შეცდომა, მონაცემების განახლებისას';
                
                return response()->json($result);
            };

            DB::beginTransaction();

            try {

                $model = Evaluation::firstOrNew(['id' => $request->id]);
                $model->fill($request->all());

                if (!$model->user) {
                    $model->user()->associate(auth()->user());
                }

                if (!$model->id) {

                    $user_invoices = $model->user->dates()->firstOrNew(['year' => date('y'), 'month' => date('m'), 'type' => 'evaluation']);

                    if (!$user_invoices) {
                        $user_invoices->fill(['year'=> date('y'), 'month' => date('m'), "inovices_length" => 1]);
                    } else {
                        $user_invoices->inovices_length = $user_invoices->inovices_length + 1;
                    }

                    $user_invoices->type = 'evaluation';
                    $user_invoices->save();

                    $model->uuid = $this->getUid($user_invoices);
                }

                $purchaser = Purchaser::firstOrNew(['id' => isset($request->purchaser['id']) ? $request->purchaser['id'] : null]);
                if (!isset($purchaser->id)) {
                    $purchaser->single = true;
                };
                $purchaser->fill($request->purchaser);
                $purchaser->save();

                $model->purchaser()->associate($purchaser);
                $model->save();

                $model->category_attributes()->sync(collect($request->category_attributes)
                    ->filter(function ($value, $key) { return $value['category_id'] !== null /* array_key_exists('isSpecial', $value) */ ;})->mapWithKeys(function ($item, $key) {
                        return [$item['id'] => $item['pivot']];
                    })->toArray());

                collect($request->category_attributes)
                    ->filter(function ($value, $key) { return $value['category_id'] == null ;})
                        ->each(function ($attribute) use ($model) {

                            $newAttribute = CategoryAttribute::firstOrNew(['id' => isset($attribute['id']) ? $attribute['id'] : null]);
                            $newAttribute->fill($attribute);
                            $newAttribute->save();

                            $model->category_attributes()->attach($newAttribute->id, $attribute['pivot']);
                });


                $model->fresh();


                $result['success'] = true;
                $result['result'] = $model;
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
    public function destroy_attribute(Request $request, $id)
    {
        //

        $result = ['status' => Response::HTTP_FORBIDDEN, 'success' => false,'errs' => [], 'result' => [], 'statusText' => "" ];

        $response = 
            $request->filled('evaluation') ? Gate::inspect('delete', Evaluation::find($request->evaluation)) : Gate::inspect('create', Evaluation::class);

        if ($response->allowed()) {

            $destroy = $request->filled('reserve') ? Evaluation::find($id) : Attribute::find($id);

            try {

                $destroy = $destroy->delete();

                if ($destroy) {
                   $result['success'] = true;
                   $result['result'] = $id;
                   $result['status'] = Response::HTTP_CREATED;
                }

            } catch(Exception $e) {
                $result['errs'][0] = 'გაურკვეველი შეცდომა! '. $e->getMessage();
            }

            return response()->json($result, Response::HTTP_CREATED);

        } else {
            $result['errs'][0] = $response->message();
            return response()->json($result);
        }
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

        $result = ['status' => Response::HTTP_FORBIDDEN, 'success' => false,'errs' => [], 'result' => [], 'statusText' => "" ];

        $response = Gate::inspect('delete', Evaluation::find($id));

        if ($response->allowed()) {

            try {

                $destroy = Evaluation::find($id);

                if (!auth()->user()->hasRole('director') && $destroy->user()->id !== auth()->user()->id) {
                    $result['errs'][0] = 'არ გაქვთ უფლება';
                    return response()->json($result);
                }
                
                $destroy = $destroy->delete();

                if ($destroy) {
                   $result['success'] = true;
                   $result['result'] = $id;
                   $result['status'] = Response::HTTP_CREATED;
                }

            } catch(Exception $e) {
                $result['errs'][0] = 'გაურკვეველი შეცდომა! '. $e->getMessage();
            }

            return response()->json($result, Response::HTTP_CREATED);

        } else {
            $result['errs'][0] = $response->message();
            return response()->json($result);
        }
    }
}


// array_combine(
//     array_column(a
//         rray_column($request->category_attributes, 'pivot'), 'category_id'), array_column($request->category_attributes, 'pivot'))