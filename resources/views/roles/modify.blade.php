<x-app-layout>

    <x-slot name="header">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">როლი</h1>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->
    </x-slot>

    <!-- Main content -->
    <section class="content">
{{Form::model($model, ['route' => ['roles.store', $model->id]])}}
        
            <div id="renderer">

                
                 <div class="card card-primary card-outline card-tabs">
      <div class="card-body">

<div class="row">

          <div class="col-12 col-md-12 col-lg-8 order-2 order-md-1">

            
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">დასახელება</label>
                    <input type="hidden" name="id" value="{{$model->id}}">

                    {{Form::text('name', $model->name, ['type' => 'text', 'class' => 'form-control', 'placeholder' => 'დასახელება'])}}
                </div>
                
                <label for="permissions" class="form-label">უფლებები</label>

                <table class="table table-striped">
                    <thead>
                        <th scope="col" width="1%">
                            <input type="checkbox" name="all_permission" @if (count($permissions) == count($model->permissions)) checked @endif>
                        </th>
                        <th scope="col" width="20%">დაასახელება</th>
                        <th scope="col" width="1%">Guard</th> 
                    </thead>

                    @foreach($permissions as $permission)
                        <tr>
                            <td>
                                {{Form::checkbox('permissions[]', $permission->id)}}
                            </td>
                            <td>{{ $permission->name }}</td>
                            <td>{{ $permission->guard_name }}</td>
                        </tr>
                    @endforeach
                </table>

               
            </div>

            <div class="col-12 col-md-12 col-lg-4 order-1 order-md-2">
            <div class="form-group"></div>
                <a href="{{ route('roles.index') }}" class="btn btn-danger btn-block"><i class="far fa-window-close"></i> გასვლა</a>
            

            <button class="btn btn-success  btn-block">
                <i class="far fa-paper-plane"></i> გაგზავნა
            </button>
          </div>

      
    </div>

    </div>



</div>

</div>
{!! Form::close() !!}
        
    </section>

</x-app-layout>


<script>
    
    let selecter = document.querySelector('input[name^="all_permission"]');
    let inputs = document.querySelectorAll('input[name^="permissions"]');


    selecter.addEventListener('click', e => {
        let target = e.currentTarget;

        if (!target.checked) {
            Array.from(inputs).map(e => e.checked = false)
        } else {
            Array.from(inputs).map(e => e.checked = true)
        }
    })

</script>