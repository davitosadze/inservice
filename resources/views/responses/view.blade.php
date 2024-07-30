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
                            @if ((Auth::user()->hasRole('ინჟინერი') && $response->time) || !Auth::user()->hasRole('ინჟინერი'))
                                <div class="col-12 col-md-12 col-lg-4 order-1 order-md-2">
                                    <div class="d-flex justify-content-between">
                                        <a href="{{ route('acts.edit', ['act' => $response->act ? $response->act->id : 'new', 'response_id' => $response->id]) }}"
                                            class="btn btn-success btn-block mr-2">
                                            <i class="far fa-paper-plane"></i>
                                            @if ($response->act)
                                                აქტის ნახვა
                                            @else
                                                აქტის შევსება
                                            @endif
                                        </a>

                                        @if ($response->act)
                                            <a href="{{ route('acts.export', $response->act->id) }}"
                                                class="btn btn-success smaller-export-btn">
                                                <i class="fas fa-file-export"></i>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endif




                            {{-- @endrole --}}

                        @endauth

                        <p class="lead">კვლევის ობიექტი</p>
                        @if ($response->on_repair)
                            <p>(გადაცემულია რემონტზე)</p>
                        @endif
                        <div class="form-group row">
                            <label for="staticEmail" class="col-sm-3 col-form-label"><b>კლიენტის სახელი:</b></label>
                            <label class="col-sm-9 col-form-label">{{ $response->name }}</label>
                        </div>

                        <div class="form-group row">
                            <label for="staticEmail" class="col-sm-3 col-form-label"><b>დამატებითი სახელი:</b></label>
                            <label class="col-sm-3 col-form-label">{{ $response->subject_name }}</label>
                        </div>

                        <div class="form-group row">
                            <label for="staticEmail" class="col-sm-3 col-form-label"><b>კლიენტის მისამართი:</b></label>
                            <label class="col-sm-9 col-form-label">{{ $response->subject_address }}</label>
                        </div>

                        <div class="form-group row">
                            <label for="staticEmail" class="col-sm-3 col-form-label"><b>საიდენთიფიკაციო
                                    კოდი:</b></label>
                            <label class="col-sm-3 col-form-label">{{ $response->identification_num }}</label>
                        </div>

                        <div class="form-group row">
                            <label for="staticEmail" class="col-sm-3 col-form-label"><b>რეგიონი:</b></label>
                            <label class="col-sm-3 col-form-label">{{ $response->region?->name }}</label>
                        </div>

                        <div class="form-group row">
                            <label for="staticEmail" class="col-sm-3 col-form-label"><b>შემსრულებელი:</b></label>
                            <label class="col-sm-3 col-form-label">{{ $response->performer?->name }}</label>
                        </div>

                        <div class="form-group row">
                            <label for="staticEmail" class="col-sm-3 col-form-label"><b>შინაარსი:</b></label>
                            <label class="col-sm-9 col-form-label">{{ $response->content }}</label>
                        </div>
                        @if ($response->id && $response->status >= 2)
                            <div class="form-group row">
                                <label for="staticEmail" class="col-sm-4 col-form-label"><b>დანადგარის ლოკაციის ზუსტი
                                        აღწერა:</b></label>

                                @php
                                    $location =
                                        $response->status == 3
                                            ? $response->act?->location?->name
                                            : $response->exact_location;
                                @endphp

                                <label class="col-sm-8 col-form-label">{{ $location }}</label>

                            </div>

                            <div class="form-group row">
                                <label for="staticEmail" class="col-sm-4 col-form-label"><b>ხარვეზის გამოსწორების
                                        მიზენით
                                        ჩატარებული სამუშაოების დეტალური აღწერა:</b></label>
                                @php
                                    $job_description =
                                        $response->status == 3 ? $response->act?->note : $response->job_description;
                                @endphp

                                <label class="col-sm-8 col-form-label">{{ $job_description }}</label>

                            </div>

                            <div class="form-group row">
                                <label for="staticEmail" class="col-sm-4 col-form-label"><b>დეფექტური აქტ(ებ)ის
                                        რეკვიზიტები:</b></label>
                                <label class="col-sm-8 col-form-label">{{ $response->act?->uuid }}</label>
                            </div>

                            <div class="form-group row">
                                <label for="staticEmail" class="col-sm-4 col-form-label"><b>ინვენტარის ნომერი/აგრეგატის
                                        უნიკალური კოდი (არსებობის შემთხვევაში):</b></label>
                                @php
                                    $inventory_number =
                                        $response->status == 3
                                            ? $response->act?->inventory_code
                                            : $response->inventory_number;
                                @endphp
                                <label class="col-sm-8 col-form-label">{{ $inventory_number }}</label>
                            </div>


                            <div class="form-group row">
                                <label for="staticEmail" class="col-sm-3 col-form-label"><b>ფილიალში გამოცხადების
                                        დრო:</b></label>
                                <label class="col-sm-3 col-form-label">{{ $response->time }}</label>
                            </div>
                        @endif

                        <div class="form-group row">
                            <label for="staticEmail" class="col-sm-3 col-form-label"><b>სისტემა 1:</b></label>
                            <label class="col-sm-3 col-form-label">{{ $response->systemOne?->name }}</label>
                        </div>

                        <div class="form-group row">
                            <label for="staticEmail" class="col-sm-3 col-form-label"><b>სისტემა 2:</b></label>
                            <label class="col-sm-3 col-form-label">{{ $response->systemTwo?->name }}</label>
                        </div>



                    </div>
                </div>
            </div>
        </div>

    </section>

</x-app-layout>
