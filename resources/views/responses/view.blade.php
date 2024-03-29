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
                    <!-- Main content -->

                    <div class="invoice p-3 mb-3">

                        <p class="lead">კვლევის ობიექტი</p>
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
                            <label for="staticEmail" class="col-sm-3 col-form-label"><b>საიდენთიპიკაციო
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

                        <div class="form-group row">
                            <label for="staticEmail" class="col-sm-4 col-form-label"><b>დანადგარის ლოკაციის ზუსტი
                                    აღწერა:</b></label>
                            <label class="col-sm-8 col-form-label">{{ $response->exact_location }}</label>
                        </div>

                        <div class="form-group row">
                            <label for="staticEmail" class="col-sm-4 col-form-label"><b>ხარვეზის გამოსწორების მიზენით
                                    ჩატარებული სამუშაოების დეტალური აღწერა:</b></label>
                            <label class="col-sm-8 col-form-label">{{ $response->job_description }}</label>
                        </div>

                        <div class="form-group row">
                            <label for="staticEmail" class="col-sm-4 col-form-label"><b>დეფექტური აქტ(ებ)ის
                                    რეკვიზიტები:</b></label>
                            <label class="col-sm-8 col-form-label">{{ $response->requisites }}</label>
                        </div>

                        <div class="form-group row">
                            <label for="staticEmail" class="col-sm-4 col-form-label"><b>ინვენტარის ნომერი/აგრეგატის
                                    უნიკალური კოდი (არსებობის შემთხვევაში):</b></label>
                            <label class="col-sm-8 col-form-label">{{ $response->inventory_number }}</label>
                        </div>


                        <div class="form-group row">
                            <label for="staticEmail" class="col-sm-3 col-form-label"><b>ფილიალში გამოცხადების
                                    დრო:</b></label>
                            <label class="col-sm-3 col-form-label">{{ $response->time }}</label>
                        </div>

                        <div class="form-group row">
                            <label for="staticEmail" class="col-sm-3 col-form-label"><b>თარიღი:</b></label>
                            <label class="col-sm-3 col-form-label">{{ $response->date }}</label>
                        </div>

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
