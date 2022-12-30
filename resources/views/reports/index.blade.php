<x-app-layout>

    <x-slot name="header">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">დეფექტურები</h1>
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
                <layout class="mt-2" :user='@json(auth()->user())' :additional='@json($additional)' :setting='@json($setting)' :model='@json($model)' name="report-table"></layout>
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



{{-- <table class="table table-striped mt-2 mb-2">
  <thead>
    <tr>
      <th scope="col">N:</th>
      <th scope="col">სახელი</th>
      <th scope="col">თარიღი</th>
      <th scope="col" style="text-align: center;">კვლევის ობიექტის რაოდენობა</th>
      <th scope="col" colspan="3" width="1%"></th>
    </tr>
  </thead>
  <tbody>

    @foreach ($model as $user)
    <tr>
      <th scope="row">{{$user->id}}</th>
      <td>{{$user->title}}</td>
      <td>{{$user->created_at}}</td>
      <td style="text-align: center;"><span class="badge badge-success">{{count($user->items)}}</span></td>
      <td><a href="{{ route('reports.show', $user->id) }}" class="btn btn-success btn-sm">ნახვა</a></td>
      <td><a href="{{ route('reports.edit', $user->id) }}" class="btn btn-warning btn-sm">ცვლილება</a></td>
      <td>
                            {!! Form::open(['method' => 'DELETE','route' => ['reports.destroy', ['report' => $user->id, 'inter' => true]],'style'=>'display:inline']) !!}
                            {!! Form::submit('წაშლა', ['class' => 'btn btn-danger btn-sm try-delete']) !!}
                            {!! Form::close() !!}
                        </td>
    </tr>
@endforeach

    
  </tbody>
</table> --}}

          

{{-- {{ $model->links() }} --}}