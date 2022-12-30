<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Illuminate\Support\Facades\DB;


use Illuminate\Support\Arr;

use Illuminate\Support\Str;
use App\Models\CategoryAttribute;

use App\Models\Invoice;
use App\Models\Purchaser;
use App\Models\Attribute;

use Illuminate\Support\Facades\Gate;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return response(Invoice::with(['purchaser', 'category_attributes.category', 'user'])->orderBy('id', 'desc')->where('type', 'invoice')->get()->toArray());
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

        $result = ['status' => Response::HTTP_FORBIDDEN, 'success' => false,'errs' => [], 'result' => [], 'statusText' => "" ];

        $response = $request->id ? Gate::inspect('update', Invoice::find($request->id)) : Gate::inspect('create', Invoice::class);

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

                $model = Invoice::firstOrNew(['id' => $request->id]);
                $model->fill($request->all());

                $purchaser = Purchaser::firstOrNew(['id' => isset($request->purchaser['id']) ? $request->purchaser['id'] : null]);

                if (!isset($purchaser->id)) {
                    $purchaser->single = true;
                };

                if (!$model->user) {
                    $model->user()->associate(auth()->user());
                };

                if (!$model->id) {

                    $user_invoices = $model->user->dates()->firstOrNew(['year' => date('y'), 'month' => date('m'), 'type' => 'invoice']);

                    if (!$user_invoices) {
                        $user_invoices->fill(['year'=> date('y'), 'month' => date('m'), "inovices_length" => 1]);
                    } else {
                        $user_invoices->inovices_length = $user_invoices->inovices_length + 1;
                    }

                    $user_invoices->type = 'invoice';
                    $user_invoices->save();

                    $model->uuid = $this->getUid($user_invoices);
                }

                if (!isset($purchaser->id)) {
                    $purchaser->fill($request->purchaser);
                    $purchaser->save();
                };

                $model->purchaser()->associate($purchaser);
                $model->save();

                $model->category_attributes()->sync(collect($request->category_attributes)
                    ->filter(function ($value, $key) { return $value['category_id'] !== null /* array_key_exists('isSpecial', $value) */ ;})->mapWithKeys(function ($item, $key) {
                        return [$item['id'] => $item['pivot']];
                    })->toArray());
                    
                $arr = array('attributable_id' => null, 'category_attribute_id' => null, 'attributable_type' => null, 'id' => null, 'title' => null, 'qty' => null, 'price' => null, 'service_price' => null, 'is_special' => null, 'calc' => null, 'evaluation_price' => null, 'evaluation_calc' => null, 'evaluation_service_price' => null, 'sort' => null, 'inter' => true, 'isInter' => true);

                //&& !empty($value['name'])
                collect($request->category_attributes)
                    ->filter(function ($value, $key) { return $value['category_id'] == null;})
                        ->each(function ($attribute) use ($model, $arr) {

                            $filter = isset($attribute['id']) ? ['id' => $attribute['id']] : ['uuid' => $attribute['uuid']];
                            $newAttribute = CategoryAttribute::firstOrNew($filter);
                            $newAttribute->fill($attribute);
                            $newAttribute->save();

                            $pivot = $attribute['pivot'];
                            unset($pivot['null']);
                            $attributables = array_intersect_key($pivot, $arr);

                            $model->category_attributes()->attach($newAttribute->id, $attributables);
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

    public function destroy_attribute(Request $request, $id)
    {
        //

        $result = ['status' => Response::HTTP_FORBIDDEN, 'success' => false,'errs' => [], 'result' => [], 'statusText' => "" ];

        $response = 
            $request->filled('invoice') ? Gate::inspect('delete', Invoice::find($request->invoice)) : Gate::inspect('create', Invoice::class);

        if ($response->allowed()) {

            try {

                $destroy = $request->filled('reserve') ? Invoice::find($id) : Attribute::find($id);

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
    public function destroy(Request $request, $id)
    {
        //

        $response = Gate::inspect('delete', Invoice::find($id));

        if ($response->allowed()) {

            $result = ['status' => Response::HTTP_FORBIDDEN, 'success' => false,'errs' => [], 'result' => [], 'statusText' => "" ];

            try {

                $destroy = Invoice::find($id);

                if (!auth()->user()->hasRole('director') && $destroy->user->id !== auth()->user()->id) {
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
