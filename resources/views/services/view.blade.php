@section('title', 'კლიენტები')
<x-app-layout>

    <x-slot name="header">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">სერვისი</h1>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->
    </x-slot>

    <!-- Main content -->
    <section class="content">

        <div class="container-fluid">
            <div class="row">
                <div class="col-12">



                    <div class="invoice p-3 mb-3">

                        @auth
                            @if ((Auth::user()->hasRole('ინჟინერი') && $service->time) || !Auth::user()->hasRole('ინჟინერი'))
                                <div class="col-12 col-md-12 col-lg-4 order-1 order-md-2">
                                    <div class="d-flex justify-content-between">
                                        <a href="{{ route('service-acts.edit', ['service_act' => $service->act ? $service->act->id : 'new', 'service_id' => $service->id]) }}"
                                            class="btn btn-success btn-block mr-2">
                                            <i class="far fa-paper-plane"></i>
                                            @if ($service->act)
                                                აქტის ნახვა
                                            @else
                                                აქტის შევსება
                                            @endif
                                        </a>

                                        @if ($service->act)
                                            <a href="{{ route('service-acts.export', $service->act->id) }}"
                                                class="btn btn-success smaller-export-btn">
                                                <i class="fas fa-file-export"></i>
                                            </a>
                                        @endif
                                    </div>

                                    @can('ჩატი')
                                        @if($service->chat())
                                            <div class="mt-3">
                                                <a href="{{ route('chats.show', $service->chat()->id) }}" class="btn btn-outline-primary d-inline-flex align-items-center">
                                                    <i class="bi bi-chat-dots me-2"></i> ჩატის ნახვა
                                                </a>
                                            </div>
                                        @else
                                            <div class="mt-3">
                                                <a href="{{ route('chats.startChat', ['type' => 'service', 'model_id' => $service->id]) }}" class="btn btn-primary d-inline-flex align-items-center">
                                                    <i class="bi bi-chat-left-text me-2"></i> ჩატის დაწყება
                                                </a>
                                            </div>
                                        @endif
                                    @endcan

                                </div>
                            @endif




                            {{-- @endrole --}}

                        @endauth

                        <p class="lead">კვლევის ობიექტი</p>
                        @if ($service->on_repair)
                            <p>(გადაცემულია რემონტზე)</p>
                        @endif
                        <div class="form-group row">
                            <label for="staticEmail" class="col-sm-3 col-form-label"><b>კლიენტის სახელი:</b></label>
                            <label class="col-sm-9 col-form-label">{{ $service->name }}</label>
                        </div>

                        <div class="form-group row">
                            <label for="staticEmail" class="col-sm-3 col-form-label"><b>დამატებითი სახელი:</b></label>
                            <label class="col-sm-3 col-form-label">{{ $service->subject_name }}</label>
                        </div>

                        <div class="form-group row">
                            <label for="staticEmail" class="col-sm-3 col-form-label"><b>კლიენტის მისამართი:</b></label>
                            <label class="col-sm-9 col-form-label">{{ $service->subject_address }}</label>
                        </div>

                        <div class="form-group row">
                            <label for="staticEmail" class="col-sm-3 col-form-label"><b>საიდენთიფიკაციო
                                    კოდი:</b></label>
                            <label class="col-sm-3 col-form-label">{{ $service->identification_num }}</label>
                        </div>

                        <div class="form-group row">
                            <label for="staticEmail" class="col-sm-3 col-form-label"><b>რეგიონი:</b></label>
                            <label class="col-sm-3 col-form-label">{{ $service->region?->name }}</label>
                        </div>

                        <div class="form-group row">
                            <label for="staticEmail" class="col-sm-3 col-form-label"><b>შემსრულებელი:</b></label>
                            <label class="col-sm-3 col-form-label">{{ $service->performer?->name }}</label>
                        </div>

                        <div class="form-group row">
                            <label for="staticEmail" class="col-sm-3 col-form-label"><b>შინაარსი:</b></label>
                            <label class="col-sm-9 col-form-label">{{ $service->content }}</label>
                        </div>
                        @if ($service->id && $service->status >= 2)
                            <div class="form-group row">
                                <label for="staticEmail" class="col-sm-4 col-form-label"><b>ხარვეზის გამოსწორების
                                        მიზენით
                                        ჩატარებული სამუშაოების დეტალური აღწერა:</b></label>
                                @php
                                    $job_description =
                                        $service->status == 3 ? $service->act?->note : $service->job_description;
                                @endphp

                                <label class="col-sm-8 col-form-label">{{ $job_description }}</label>

                            </div>

                            <div class="form-group row">
                                <label for="staticEmail" class="col-sm-4 col-form-label"><b>დეფექტური აქტ(ებ)ის
                                        რეკვიზიტები:</b></label>
                                <label class="col-sm-8 col-form-label">{{ $service->act?->uuid }}</label>
                            </div>

                            <div class="form-group row">
                                <label for="staticEmail" class="col-sm-4 col-form-label"><b>ინვენტარის ნომერი/აგრეგატის
                                        უნიკალური კოდი (არსებობის შემთხვევაში):</b></label>
                                @php
                                    $inventory_number =
                                        $service->status == 3
                                            ? $service->act?->inventory_code
                                            : $service->inventory_number;
                                @endphp
                                <label class="col-sm-8 col-form-label">{{ $inventory_number }}</label>
                            </div>


                            <div class="form-group row">
                                <label for="staticEmail" class="col-sm-3 col-form-label"><b>ფილიალში გამოცხადების
                                        დრო:</b></label>
                                <label class="col-sm-3 col-form-label">{{ $service->time }}</label>
                            </div>
                        @endif




                    </div>
                </div>
            </div>
        </div>

    </section>

</x-app-layout>
