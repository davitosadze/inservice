<x-app-layout>

    <x-slot name="header">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">როლები</h1>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->
    </x-slot>

    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="card-tools">
                <div class="input-group input-group-sm" style="width: 150px;">
                    <button href="{{request()->url()}}/new/edit" id="create" class="btn btn-sm btn-outline-success">
                        <i class="fas fa-shield-alt"></i> დამატება
                    </button>          
                </div>
            </div>
            <!-- /.card-body -->
            <div id="renderer" class="mt-2">

                <div class="card card-primary card-outline card-tabs">
      <div class="card-body">
               
                <table class="table table-striped">
  <thead>
    <tr>
      <th scope="col">N:</th>
      <th scope="col">სახელი</th>
      <th scope="col" colspan="3" width="1%"></th>
    </tr>
  </thead>
  <tbody>

    @foreach ($model as $user)
    <tr>
      <th scope="row">{{$user->id}}</th>
      <td>{{$user->name}}</td>
      <td><a href="{{ route('roles.edit', $user->id) }}" class="btn btn-info btn-sm">ცვლილება</a></td>
      <td>
        @if(auth()->user()->hasRole('director'))
                            {!! Form::open(['method' => 'DELETE','route' => ['roles.destroy', $user->id],'style'=>'display:inline']) !!}
                            {!! Form::submit('წაშლა', ['class' => 'btn btn-danger btn-sm try-delete']) !!}
                            {!! Form::close() !!}
                            @endif
                        </td>
    </tr>
@endforeach

    
  </tbody>
</table>
            </div>
        </div>

    </div>
       
    </section>

</x-app-layout>

<script>

    let target = document.querySelector("button#create");
    target.addEventListener("click", function(e) {
        window.location.href = e.target.getAttribute("href")
    })

    Array.from(document.querySelectorAll(['input.try-delete'])).map(i => {
        i.addEventListener('click', function (e) {
            e.preventDefault();

            let target = e.currentTarget;

            Swal.fire({
                title: 'დარწმუნებული ხარ?',
                text: "წაშლა!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d7',
                confirmButtonText: 'შესრულება!',
                cancelButtonText: 'გაუქმება'
            }).then((result) => {
                if (result.value) { target.parentElement.submit(); }
            })

        return false;
        });
    });

</script>