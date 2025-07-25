@section('title', 'მომხმარებლები')
<x-app-layout>

    <x-slot name="header">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">მომხმარებელი</h1>
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
                    {{ Form::model($model, ['route' => ['users.store', $model->id ? $model->id : 'new'], 'files' => true]) }}
                    <div class="row">

                        <div class="col-12 col-md-12 col-lg-8 order-2 order-md-1">
                            <div class="tab-content" id="custom-tabs-three-tabContent">


                                <div class="input-list-builder">
                                    <div class="form-row">
                                        <div class="form-group col">
                                            <label for="name">სახელი</label>
                                            <input type="hidden" name="id" value="{{ $model->id }}">
                                            {{ Form::text('name', $model->name, ['class' => 'form-control', 'readonly' => $model->id]) }}
                                        </div>
                                        <div class="form-group col">
                                            <label for="email">ელ-ფოსტა</label>
                                            {{ Form::text('email', $model->email, ['class' => 'form-control', 'readonly' => $model->id]) }}
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col">
                                            <label for="email">თანამდებობა</label>
                                            {{ Form::text('position', $model->position, ['class' => 'form-control', '']) }}
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col">
                                            <label for="status">სტატუსი</label>
                                            {{ Form::select('status', [0 => 'დაუდასტურებელი', 1 => 'დადასტურებული'], $model->status, ['class' => 'form-control']) }}
                                        </div>
                                    </div>
                                    
                                    {{-- @if (auth()->user()->hasRole('director')) --}}
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
                                    {{-- @endif --}}
                                    <div class="form-row">
                                        <div class="form-group col">
                                            <label for="name">სამუშაოს აღწერილობა</label>
                                            {{ Form::textarea('job_description', $model->job_description, ['class' => 'form-control']) }}
                                        </div>

                                    </div>

                                </div>

                                <div class="form-group col">
                                    <label for="avatar">პროფილის სურათი</label>
                                    <input type="file" name="profile_image" class="form-control" id="">
                                </div>


                                @if ($model->getFirstMediaUrl('user-profile-images'))
                                    <div class="row">
                                        <img class="m-5" style="width: 250px"
                                            src="{{ $model->getFirstMediaUrl('user-profile-images') }}" alt="">
                                    </div>
                                @endif
                                <div class="form-group col">
                                    <label for="email">ხელმოწერა</label>
                                    <update-media class="mt-2" key="0" index="inter" each="1"
                                        server="/users/uploads"
                                        media_server="/users/uploads2/{{ $model->id ? $model->id : 'new' }}">
                                    </update-media>
                                </div>

                            </div>

                            <hr />

                            <label for="name">რეაგირებების შეზღუდვა</label>
                            <hr>
                            <div class="form-group">
                                <div class="form-check form-check-inline">
                                    <input @checked($model->responses_limited) value="1" class="form-check-input"
                                        type="checkbox" name="responses_limited" id="responses_limited">
                                    <label class="form-check-label" for="responses_limited">რეაგირებების შეზღუდვა</label>
                                </div>
                            </div>


                            <hr>
                            <label for="name">მიუთითეთ თუ მენეჯერია</label>
                            <hr>
                            <div class="form-group">
                                <div class="form-check form-check-inline">
                                    <input @checked($model->manager_type == 0) value="0" class="form-check-input"
                                        type="radio" name="manager_type" id="group1radio1">
                                    <label class="form-check-label" for="group1radio1">არცერთი</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input @checked($model->manager_type == 1) value="1" class="form-check-input"
                                        type="radio" name="manager_type" id="group1radio1">
                                    <label class="form-check-label" for="group1radio1">რეაგირება</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input @checked($model->manager_type == 2) value="2" class="form-check-input"
                                        type="radio" name="manager_type" id="group1radio2">
                                    <label class="form-check-label" for="group1radio2">სარემონტო სამუშაო</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input @checked($model->manager_type == 3) value="3" class="form-check-input"
                                        type="radio" name="manager_type" id="group1radio3">
                                    <label class="form-check-label" for="group1radio3">გეგმიური სასერვისო
                                        სამუშაო</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input @checked($model->manager_type == 4) value="4" class="form-check-input"
                                        type="radio" name="manager_type" id="group1radio4">
                                    <label class="form-check-label" for="group1radio4">სხვა ტიპის სამუშაო</label>
                                </div>
                            </div>
                            <hr>
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

                        </div>

                        <div class="col-12 col-md-12 col-lg-4 order-1 order-md-2">
                            <div class="form-group"></div>

                            <button onclick="location.href = '{{ route('users.index') }}'" type="button"
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
