@section('title', 'ინსტრუქცია')
<x-app-layout>

    <x-slot name="header">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">ინსტრუქცია</h1>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->
    </x-slot>

    <!-- Main content -->
    <section class="content">

        <div id="renderer">



            <div class="card card-primary card-outline card-tabs">
                <div class="card-body">
                    {{ Form::model($model, ['route' => ['instructions.store', $model->id ? $model->id : 'new'], 'files' => true]) }}
                    <div class="row">

                        <div class="col-12 col-md-12 col-lg-8 order-2 order-md-1">
                            <div class="tab-content" id="custom-tabs-three-tabContent">


                                <div class="input-list-builder">
                                    <div class="form-row">
                                        <div class="form-group col">
                                            <label for="name">სახელი</label>
                                            <input type="hidden" name="id" value="{{ $model->id }}">
                                            {{ Form::text('name', $model->name, ['class' => 'form-control']) }}
                                        </div>
                                        <div class="form-group col">
                                            <label for="email">Slug</label>
                                            {{ Form::text('slug', $model->email, ['class' => 'form-control']) }}
                                        </div>
                                    </div>


                                </div>

                                <div class="form-group col">
                                    <label for="avatar">ფაილი</label>
                                    <input type="file" name="image" class="form-control" id="">
                                </div>


                                @if ($model->getFirstMediaUrl('instruction-images'))
                                    <div class="row">
                                        <img class="m-5" style="width: 250px"
                                            src="{{ $model->getFirstMediaUrl('instruction-images') }}" alt="">
                                    </div>
                                @endif


                            </div>

                            <hr>


                        </div>

                        <div class="col-12 col-md-12 col-lg-4 order-1 order-md-2">
                            <div class="form-group"></div>

                            <button onclick="location.href = '{{ route('instructions.index') }}'" type="button"
                                class="btn btn-danger  btn-block" style="margin-right: 5px;">
                                <i class="far fa-window-close"></i> გასვლა
                            </button>

                            <button class="btn btn-success  btn-block">
                                <i class="far fa-paper-plane"></i> გაგზავნა
                            </button>
                        </div>
                    </div>

                    {{ Form::close() }}
                </div>
            </div>



            <!-- /.card -->
    </section>

</x-app-layout>
