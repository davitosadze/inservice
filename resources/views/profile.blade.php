<x-app-layout>

    <x-slot name="header">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">edit</h1>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->
    </x-slot>

    <!-- Main content -->
    <section class="content">
        <!-- Default box -->


        <!-- /.card-body -->
        <div id="renderer">



            <div class="card card-primary card-outline card-tabs">
                <div class="card-body">
                    {{ Form::model($model, ['route' => ['users.store', $model->id ? $model->id : 'new']]) }}
                    <div class="row">

                        <div class="col-12 col-md-12 col-lg-8 order-2 order-md-1">
                            <div class="tab-content" id="custom-tabs-three-tabContent">


                                <div class="input-list-builder">
                                    <div class="form-row">
                                        <div class="form-group col">
                                            <label for="name">სახელი</label>
                                            <input type="hidden" name="id" value="{{ $model->id }}">
                                            {{ Form::text('name', $model->name, ['class' => 'form-control', 'readonly' => true]) }}
                                        </div>
                                        <div class="form-group col">
                                            <label for="email">ელ-ფოსტა</label>
                                            {{ Form::text('email', $model->email, ['class' => 'form-control', 'readonly' => true]) }}
                                        </div>
                                    </div>

                                    @if (auth()->user()->hasRole('director') || auth()->user()->id == $model->id)
                                        <div class="form-row">
                                            <div class="form-group col">
                                                <label for="name">პაროლი</label>
                                                <input type="hidden" name="id" value="{{ $model->id }}">
                                                {{ Form::text('inter_password', $model->inter_password, ['class' => 'form-control']) }}
                                            </div>
                                            <div class="form-group col">
                                                <label for="email">გაიმეორეთ პაროლი</label>
                                                {{ Form::text('inter_password_confirmation', null, ['class' => 'form-control']) }}
                                            </div>
                                        </div>
                                    @endif

                                    <div class="form-row">
                                        <div class="form-group col">
                                            <label for="name">სამუშაოს აღწერილობა</label>
                                            {{ Form::textarea('job_description', $model->job_description, ['class' => 'form-control', 'readonly' => 'true']) }}
                                        </div>

                                    </div>

                                </div>
                            </div>

                            @if (auth()->user()->hasRole('director') || auth()->user()->hasRole('დირექტორი'))
                                <div class="form-group col">
                                    <label for="email">ხელმოწერა</label>
                                    <update-media class="mt-2" key="0" index="inter" each="1"
                                        server="/users/uploads"
                                        media_server="/users/uploads2/{{ $model->id ? $model->id : 'new' }}">
                                    </update-media>
                                </div>


                                <hr />


                                <label for="name">როლები</label>

                                <table class="table table-striped">
                                    <thead>
                                        <th scope="col" width="1%"><input type="checkbox" name="all_permission">
                                        </th>
                                        <th scope="col" width="20%">დასხელება</th>
                                        <th scope="col" width="1%">Guard</th>
                                    </thead>

                                    @foreach ($roles as $role)
                                        <tr>
                                            <td>
                                                {{ Form::checkbox('roles[]', $role->id) }}
                                            </td>
                                            <td>{{ $role->name }}</td>
                                            <td>{{ $role->guard_name }}</td>
                                        </tr>
                                    @endforeach
                                </table>
                            @endif


                        </div>

                        <div class="col-12 col-md-12 col-lg-4 order-1 order-md-2">
                            <div class="form-group"></div>

                            @if ($model->getFirstMediaUrl('user-profile-images'))
                                <div class="row d-flex justify-content-center align-items-center">
                                    <img class="m-5" style="width: 200px"
                                        src="{{ $model->getFirstMediaUrl('user-profile-images') }}" alt="">

                                </div>
                            @endif
                            <div class="row d-flex justify-content-center">
                                <p class="text-center">{{ $model->name }}</p>
                            </div>
                            <div class="row d-flex justify-content-center">
                                <p class="text-center">{{ $model->position }}</p>
                            </div>
                            <button onclick="location.href = '{{ route('dashboard') }}'" type="button"
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
