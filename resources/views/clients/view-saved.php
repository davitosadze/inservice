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
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <!-- Main content -->

                <div class="invoice p-3 mb-3">
                    <div style="">
                        <div class="form-group row">
                            <label for="staticEmail" class="col-sm-3 col-form-label"><b>დასახელება :</b></label>
                            <div class="col-sm-9">
                                <p>{{ $client->client_name }}</p>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label" for="formGroupExampleInput2">საიდენტიფიკაციო კოდი
                                :</label>
                            <div class="col-sm-9">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label" for="formGroupExampleInput">იურიდიული მისამართი
                                :</label>
                            <div class="col-sm-9">

                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label" for="formGroupExampleInput">მომსახურების შიდა სახელი
                                :</label>

                            <div class="col-sm-9">

                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label" for="formGroupExampleInput">კონტრაქტის მომსახურების
                                ტიპი :</label>

                            <div class="col-sm-9">

                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label" for="formGroupExampleInput">კონტრაქტის დაწყების
                                თარიღი :</label>

                            <div class="col-sm-9">

                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label" for="formGroupExampleInput">კონტრაქტის დასრულების
                                თარიღი :</label>

                            <div class="col-sm-9">

                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label" for="formGroupExampleInput">კონტრაქტის პერიოდი / დღე
                                :</label>

                            <div class="col-sm-9">
                                <span>#</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label" for="formGroupExampleInput">კონტრაქტის ნარჩენი დღეები
                                :</label>

                            <div class="col-sm-9">
                                <span>#</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label" for="formGroupExampleInput">კონტრაქტის სტატუსი
                                :</label>

                            <div class="col-sm-9">
                                #
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label" for="formGroupExampleInput">გარანტიის დაწყების თარიღი
                                :</label>

                            <div class="col-sm-9">

                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label" for="formGroupExampleInput">გარანტიის დასრულების
                                თარიღი :</label>

                            <div class="col-sm-9">

                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label" for="formGroupExampleInput">გარანტიის პერიოდი / დღე
                                :</label>

                            <div class="col-sm-9">
                                <span>#</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label" for="formGroupExampleInput">გარანტიის ნარჩენი
                                დღეები :</label>

                            <div class="col-sm-9">
                                <span>#</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label" for="formGroupExampleInput">გარანტიის სტატუსი
                                :</label>

                            <div class="col-sm-9">
                                #
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label" for="formGroupExampleInput">კლიენტის
                                მაიდენთიფიცირებელი კოდი :</label>

                            <div class="col-sm-9">

                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label" for="formGroupExampleInput">საკონტაქტო პირი
                                :</label>
                            <div class="col-sm-9">

                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label" for="formGroupExampleInput">საკონტაქტო პირის ნომერი
                                :</label>

                            <div class="col-sm-9">

                            </div>
                        </div>




                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>