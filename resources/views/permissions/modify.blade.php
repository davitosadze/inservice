<x-app-layout>

    <x-slot name="header">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">ნებართვა</h1>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->
    </x-slot>

    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
       
            <div id="renderer">

                

                 <div class="card card-primary card-outline card-tabs">
      <div class="card-body">
        <div class="row">

          <div class="col-12 col-md-12 col-lg-8 order-2 order-md-1">


            {{Form::model($model, ['route' => ['permissions.store', $model->id]])}}
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">დასახელება</label>
                    <input type="hidden" name="id" value="{{$model->id}}">
                    
                    <input value="{{ $model->name }}" 
                        type="text" 
                        class="form-control" 
                        name="name" 
                        placeholder="დასახელება">

                    @if ($errors->has('name'))
                        <span class="text-danger text-left">{{ $errors->first('name') }}</span>
                    @endif
                </div>

                <button type="submit" class="btn btn-success">შენახვა</button>
                <a href="{{ route('permissions.index') }}" class="btn btn-primary ml-1">Back</a> 

                {{Form::close()}}
          </div>

      
    </div>

</div>

</div>

    
           
        <!-- /.card -->
    </section>

</x-app-layout>