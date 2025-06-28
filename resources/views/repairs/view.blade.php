@section('title', 'კლიენტები')
<x-app-layout>

    <x-slot name="header">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">კლიენტი</h1>
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
                            @if ((Auth::user()->hasRole('ინჟინერი') && $repair->time) || !Auth::user()->hasRole('ინჟინერი'))
                                <div class="col-12 col-md-12 col-lg-4 order-1 order-md-2">
                                    <div class="d-flex justify-content-between">
                                        <a href="{{ route('repair-acts.edit', ['repair_act' => $repair->act ? $repair->act->id : 'new', 'repair_id' => $repair->id]) }}"
                                            class="btn btn-success btn-block mr-2">
                                            <i class="far fa-paper-plane"></i>
                                            @if ($repair->act)
                                                აქტის ნახვა
                                            @else
                                                აქტის შევსება
                                            @endif
                                        </a>

                                        @if ($repair->act)
                                            <a href="{{ route('repair-acts.export', $repair->act->id) }}"
                                                class="btn btn-success smaller-export-btn">
                                                <i class="fas fa-file-export"></i>
                                            </a>
                                        @endif
                                    </div>

                                    @can('ჩატი')
                                        @if($repair->chat())
                                            <div class="mt-3">
                                                <a href="{{ route('chats.show', $repair->chat()->id) }}" class="btn btn-outline-primary d-inline-flex align-items-center">
                                                    <i class="bi bi-chat-dots me-2"></i> ჩატის ნახვა
                                                </a>
                                            </div>
                                        @else
                                            <div class="mt-3">
                                                <a href="{{ route('chats.startChat', ['type' => 'repair', 'model_id' => $repair->id]) }}" class="btn btn-primary d-inline-flex align-items-center">
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
                        @if (!Auth::user()->hasRole('ინჟინერი'))

                            @if ($repair->on_repair)
                                @if ($repair->from == 'response')
                                    გადაცემულია რემონტზე რეაგირებიდან <a
                                        href="{{ route('responses.show', $repair->from_id) }}">
                                        {{ '#' . $repair->from_id }}</a>
                                @elseif($repair->from == 'service')
                                    გადაცემულია რემონტზე სერვისიდან <a
                                        href="{{ route('services.show', $repair->from_id) }}">{{ '#' . $repair->from_id }}</a>
                                @else
                                    გადაცემულია რემონტზე კლიენტისგან
                                @endif
                            @endif
                            <hr>
                        @endif


                        <div class="form-group row">
                            <label for="staticEmail" class="col-sm-3 col-form-label"><b>კლიენტის სახელი:</b></label>
                            <label class="col-sm-9 col-form-label">{{ $repair->name }}</label>
                        </div>

                        <div class="form-group row">
                            <label for="staticEmail" class="col-sm-3 col-form-label"><b>დამატებითი
                                    სახელი:</b></label>
                            <label class="col-sm-3 col-form-label">{{ $repair->subject_name }}</label>
                        </div>

                        <div class="form-group row">
                            <label for="staticEmail" class="col-sm-3 col-form-label"><b>კლიენტის
                                    მისამართი:</b></label>
                            <label class="col-sm-9 col-form-label">{{ $repair->subject_address }}</label>
                        </div>

                        <div class="form-group row">
                            <label for="staticEmail" class="col-sm-3 col-form-label"><b>საიდენთიფიკაციო
                                    კოდი:</b></label>
                            <label class="col-sm-3 col-form-label">{{ $repair->identification_num }}</label>
                        </div>

                        <div class="form-group row">
                            <label for="staticEmail" class="col-sm-3 col-form-label"><b>რემონტის
                                    მოწყობილობა:</b></label>
                            <label class="col-sm-3 col-form-label">{{ $repair->repairDevice?->name }}</label>
                        </div>


                        <div class="form-group row">
                            <label for="staticEmail" class="col-sm-3 col-form-label"><b>რეგიონი:</b></label>
                            <label class="col-sm-3 col-form-label">{{ $repair->region?->name }}</label>
                        </div>

                        <div class="form-group row">
                            <label for="staticEmail" class="col-sm-3 col-form-label"><b>შემსრულებელი:</b></label>
                            <label class="col-sm-3 col-form-label">{{ $repair->performer?->name }}</label>
                        </div>

                        <div class="form-group row">
                            <label for="staticEmail" class="col-sm-3 col-form-label"><b>შინაარსი:</b></label>
                            <label class="col-sm-9 col-form-label">{{ $repair->content }}</label>
                        </div>
                        @if ($repair->id && $repair->status >= 2)
                            <div class="form-group row">
                                <label for="staticEmail" class="col-sm-4 col-form-label"><b>დანადგარის ლოკაციის
                                        ზუსტი
                                        აღწერა:</b></label>

                                @php
                                    $location =
                                        $repair->status == 3 ? $repair->act?->location?->name : $repair->exact_location;
                                @endphp

                                <label class="col-sm-8 col-form-label">{{ $location }}</label>

                            </div>

                            <div class="form-group row">
                                <label for="staticEmail" class="col-sm-4 col-form-label"><b>ხარვეზის გამოსწორების
                                        მიზენით
                                        ჩატარებული სამუშაოების დეტალური აღწერა:</b></label>
                                @php
                                    $job_description =
                                        $repair->status == 3 ? $repair->act?->note : $repair->job_description;
                                @endphp

                                <label class="col-sm-8 col-form-label">{{ $job_description }}</label>

                            </div>

                            <div class="form-group row">
                                <label for="staticEmail" class="col-sm-4 col-form-label"><b>დეფექტური აქტ(ებ)ის
                                        რეკვიზიტები:</b></label>
                                <label class="col-sm-8 col-form-label">{{ $repair->act?->uuid }}</label>
                            </div>

                            <div class="form-group row">
                                <label for="staticEmail" class="col-sm-4 col-form-label"><b>ინვენტარის
                                        ნომერი/აგრეგატის
                                        უნიკალური კოდი (არსებობის შემთხვევაში):</b></label>
                                @php
                                    $inventory_number =
                                        $repair->status == 3
                                            ? $repair->act?->inventory_code
                                            : $repair->inventory_number;
                                @endphp
                                <label class="col-sm-8 col-form-label">{{ $inventory_number }}</label>
                            </div>


                            <div class="form-group row">
                                <label for="staticEmail" class="col-sm-3 col-form-label"><b>ფილიალში გამოცხადების
                                        დრო:</b></label>
                                <label class="col-sm-3 col-form-label">{{ $repair->time }}</label>
                            </div>
                        @endif




                    </div>
                </div>
            </div>
        </div>

    </section>

</x-app-layout>
