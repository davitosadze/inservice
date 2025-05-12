@section('title', 'რემონტი')
<x-app-layout>

    <x-slot name="header">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">რემონტი</h1>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->
    </x-slot>

    @once
        @push('styles')
            <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.css" rel="stylesheet" />
            <style type="text/css">
                span.select2-container--default .select2-selection--single {
                    padding: 0;
                }
            </style>
        @endpush
    @endonce

    <?php if (session()->getOldInput()) {
        print_r(collect(session()->getOldInput()['item']));
        print_r($model->items);
    }
    ?>

    <!-- Main content -->
    <section class="content">

        {{ Form::model($model, ['route' => ['repairs.store', $model->id], 'id' => 'render']) }}

        <div id="renderer">


            <div class="card card-primary card-outline card-tabs">
                <div class="card-body">

                    <div class="row">

                        <div class="col-12 col-md-12 col-lg-8 order-2 order-md-1">


                            @csrf


                            <div class="mb-3">



                                <p class="lead">კვლევის ობიექტი</p>

                                <input type="hidden" name="id" value="{{ $model->id }}">
                                @if($model->type == 2) არასაკონტრაქტო @endif
                                <hr>

                                <div class="form-group mb-2" style="align-items: center;">
                                    <div class="col-12"><b>აირჩიეთ :</b></div>
                                    <div class="col-12">
                                        <select name="purchaser" class="form-control purchaser">
                                            <option disabled selected>--- აირჩეთ ---</option>
                                            @foreach ($additional['purchasers'] as $purchaser)
                                                <option @disabled($model->id)
                                                    value='@json($purchaser)' @selected($model->purchaser_id == $purchaser['id'])>
                                                    {{ $purchaser['name'] }} / {{ $purchaser['subj_name'] }} /
                                                    {{ $purchaser['subj_address'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>


                                <div class="form-group mt-3 mb-2" style="align-items: center;">
                                    <div class="col-12"><b>კლიენტის სახელი :</b></div>
                                    <div class="col-12">
                                        {{ Form::text('name', $model->name, ['class' => 'form-control', 'readonly' => $model->id ? true : false]) }}
                                    </div>
                                </div>

                                <div class="form-group mt-3 mb-2" style="align-items: center;">
                                    <div class="col-12"><b>დამატებითი სახელი :</b></div>
                                    <div class="col-12">
                                        {{ Form::text('subject_name', $model->subject_name, ['class' => 'form-control', 'readonly' => $model->id ? true : false]) }}
                                    </div>
                                </div>

                                <div class="form-group mt-3 mb-2" style="align-items: center;">
                                    <div class="col-12"><b>კლიენტის მისამართი :</b></div>
                                    <div class="col-12">
                                        {{ Form::text('subject_address', $model->subject_address, ['class' => 'form-control', 'readonly' => $model->id ? true : false]) }}
                                    </div>
                                </div>

                                <div class="form-group mt-3 mb-2" style="align-items: center;">
                                    <div class="col-12"><b>საიდენთიფიკაციო კოდი :</b></div>
                                    <div class="col-12">
                                        {{ Form::text('identification_num', $model->identification_num, ['class' => 'form-control', 'readonly' => $model->id ? true : false]) }}
                                    </div>
                                </div>

                            </div>


                            <div class="mb-3">
                                <div class="form-group mt-3 mb-2" style="align-items: center;">
                                    <div class="col-12"><b>რეგიონი :</b></div>
                                    <div class="col-12">
                                        <select name="region_id" class="form-control region">
                                            <option disabled selected>--- აირჩეთ ---</option>
                                            @foreach ($additional['regions'] as $region)
                                                <option @disabled($model->id) value='{{ $region['id'] }}'
                                                    @selected($model->region_id == $region['id'])>
                                                    {{ $region['name'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="form-group mt-3 mb-2" style="align-items: center;">
                                    <div class="col-12"><b>შემსრულებელი :</b></div>
                                    <div class="col-12">
                                        <select name="performer_id" class="form-control performer">
                                            <option disabled selected>--- აირჩეთ ---</option>
                                            @foreach ($additional['performers'] as $performer)
                                                <option @selected($model->performer_id == $performer['id']) value='{{ $performer['id'] }}'
                                                    @selected(old('performer_id') == $performer['id'])>
                                                    {{ $performer['name'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @if ($model->performer?->is_hidden)
                                        <center><b>
                                                არჩეული შემსრულებელი:
                                                {{ $model->performer?->name . ' ბაზიდან წაშლილია' }}
                                            </b></center>
                                    @endif
                                </div>
                            </div>


                            <div class="mb-3">
                                <div class="form-group mt-3 mb-2" style="align-items: center;">
                                    <div class="col-12"><b>რემონტის მოწყობილობის დასახელება :</b></div>
                                    <div class="col-12">
                                        <select name="repair_device_id" class="form-control performer">
                                            <option disabled selected>--- აირჩეთ ---</option>

                                            @foreach ($additional['repair_devices'] as $device)
                                                <option @selected($model->repair_device_id == $device['id']) value='{{ $device['id'] }}'>
                                                    {{ $device['name'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                </div>
                            </div>

                            <div class="form-group">
                                <label for="formGroupExampleInput">შინაარსი:</label>
                                {{ Form::textarea('content', $model->content, ['placeholder' => 'შინაარსი', 'cols' => 2, 'rows' => 4, 'class' => 'form-control', 'readonly' => $model->id ? true : false]) }}

                            </div>
                            @if ($model->id && $model->status >= 2)
                                <div class="form-group">
                                    <label for="formGroupExampleInput">დანადგარის ლოკაციის ზუსტი აღწერა:</label>

                                    {{ Form::textarea('exact_location', $model->exact_location ? $model->exact_location : $model->act?->location?->name, ['placeholder' => 'დანადგარის ლოკაციის ზუსტი აღწერა', 'cols' => 2, 'rows' => 2, 'class' => 'form-control']) }}

                                </div>

                                <div class="form-group">
                                    <label for="formGroupExampleInput">ხარვეზის გამოსწორების მიზენით ჩატარებული
                                        სამუშაოების
                                        დეტალური აღწერა:</label>

                                    {{ Form::textarea('job_description', $model->job_description ? $model->job_description : $model->act?->note, ['placeholder' => 'ხარვეზის გამოსწორების მიზენით ჩატარებული სამუშაოების დეტალური აღწერა', 'cols' => 2, 'rows' => 2, 'class' => 'form-control']) }}

                                </div>

                                <div class="form-group">
                                    <label for="formGroupExampleInput">დეფექტური აქტ(ებ)ის რეკვიზიტები</label>

                                    {{ Form::text('requisites', $model->act?->uuid, ['placeholder' => 'დეფექტური აქტ(ებ)ის რეკვიზიტები', 'cols' => 2, 'rows' => 2, 'class' => 'form-control']) }}

                                </div>

                                <div class="form-group">
                                    <label for="formGroupExampleInput">ინვენტარის ნომერი/აგრეგატის უნიკალური კოდი
                                        (არსებობის
                                        შემთხვევაში)</label>


                                    {{ Form::text('inventory_number', $model->inventory_number ? $model->inventory_number : $model->act?->inventory_code, ['placeholder' => 'ინვენტარის ნომერი/აგრეგატის უნიკალური კოდი (არსებობის შემთხვევაში)', 'cols' => 2, 'rows' => 2, 'class' => 'form-control']) }}

                                </div>

                                <div class="form-group">
                                    <label for="formGroupExampleInput">ფილიალში გამოცხადების დრო</label>
                                    {{ Form::text('time', $model->act?->created_at, ['placeholder' => 'ფილიალში გამოცხადების დრო', 'class' => 'form-control']) }}

                                </div>

                                {{-- 
                                <div class="form-group mb-5">
                                    <label for="formGroupExampleInput">თარიღი</label>
                                    {{ Form::date('date', $model->date, ['class' => 'form-control']) }}

                                </div> --}}
                            @endif


                        </div>

                        <div class="col-12 col-md-12 col-lg-4 order-1 order-md-2">
                            <div class="form-group"></div>

                            <a href="{{ route('responses.index') }}" class="btn btn-warning btn-block"><i
                                    class="far fa-window-close"></i> გასვლა</a>

                            <button class="btn btn-success  btn-block">
                                <i class="far fa-paper-plane"></i> გაგზავნა
                            </button>

                            {{-- Custom Field: Show description based on $from --}}
                            @if($additional['descmodel'])
                            <div class="form-group mt-4">
                                <label for="related_description"><b>აღწერა</b></label>
                                <textarea class="form-control" id="related_description" rows="3" readonly>{{ $additional['descmodel']?->content  }}</textarea>
                            </div>
                            @endif
                            
                            @php
                            $mediaLabel = $model->from === 'service' ? 'სერვისის სურათები' : ($model->from === 'response' ? 'რეაგირების სურათები' : null);
                            $mediaFiles = $model->purchaser?->getMedia('Additional_InformationFiles')->filter(function($media) use ($mediaLabel) {
                                return $media->getCustomProperty('folder_name') === $mediaLabel;
                            });
                            @endphp
                            
                            @if($mediaLabel && $mediaFiles && $mediaFiles->count())
                                <div class="form-group mt-3">
                                    <label><b>{{ $mediaLabel }}</b></label>
                                    <div class="container">
                                        <div class="row">
                                            @foreach($mediaFiles as $file)
                                                <div class="col-6 mb-2">
                                                    <a href="{{ $file->getFullUrl() }}" target="_blank">
                                                        <img src="{{ $file->getFullUrl() }}" class="img-fluid rounded" style="max-height: 180px;" alt="file image">
                                                    </a>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endif
                        
                      
                        </div>
                    </div>
                </div>
            </div>

        </div>
        {!! Form::close() !!}

    </section>


    @once
        @push('scripts')
            <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.js"></script>
            <script type="text/javascript">
                let model = @json($model);
                let setting = @json($setting);

                $(document).ready(function() {
                     $('.purchaser').select2();


                });

                $('.purchaser').on('select2:select', function(e) {
                    let res = JSON.parse(e.target.value);
                    if (res) {
                        document.querySelector('input[name="name"]').value = res.name;
                        document.querySelector('input[name="subject_name"]').value = res.subj_name;
                        document.querySelector('input[name="subject_address"]').value = res.subj_address;
                        document.querySelector('input[name="identification_num"]').value = res.identification_num;
                    }
                });

                let useSwall = (res) => {
                    let icon = !res.data.success ? "error" : "success"
                    let exit = {}
                    if (res.data.success) {
                        exit = {
                            showCancelButton: true,
                            showConfirmButton: true,
                            timer: false,
                            cancelButtonText: 'გაგრძელება',
                            confirmButtonText: 'ჩამონათვალში დაბრუნება'
                        }
                    }

                    return Swal.fire({
                        position: 'top-end',
                        icon: icon,
                        title: res.data.statusText,
                        showConfirmButton: false,
                        timer: 2000,
                        ...exit
                    })
                }

                window.addEventListener('load', (event) => {
                    document.querySelector('button.btn-block').addEventListener('click', function(e) {
                        e.preventDefault();

                        let res = new FormData(document.querySelector('#render'));
                        let url = document.querySelector('#render').getAttribute('action')

                        axios({
                                method: 'post',
                                url: url,
                                data: res,
                                headers: {
                                    "Content-Type": "multipart/form-data"
                                }
                            })
                            .then(function(response) {
                                // handle success

                                useSwall(response).then((result) => {

                                    if (result.value) {
                                        window.location.replace(setting.url.request.index.replace(
                                            'api/', ''))
                                    } else if (model.id == null && response.data.success == true) {
                                        window.location.replace(window.location.href.replace('new',
                                            response.data.result.id));
                                    }

                                    if (!response.data.success) {
                                        if (response.data.errs) response.data.errs.map(item => window
                                            .app._context.provides.$toast.error(item, {
                                                position: 'top-right',
                                                duration: 7000
                                            }))
                                    }

                                })
                            })
                            .catch(function(error) {
                                // handle error
                                window.app._context.provides.$toast.error(e.response.statusText, {
                                    position: 'top-right',
                                    duration: 7000
                                })
                            });

                        return false;
                    })



                });
            </script>
        @endpush
    @endonce

</x-app-layout>
