<x-app-layout>

    <x-slot name="header">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">მასალების ჩამონათვალი</h1>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->
    </x-slot>

    <!-- Main content -->
    <section class="content">

        <div class="card-tools mt-2">
            <div class="input-group input-group-sm" style="width: 150px;">          
                <button href="{{ route('categories.edit', ["category" => "new"]) }}" id="create" class="btn btn-sm btn-outline-success">
                    <i class="fas fa-shield-alt"></i> დამატება
                </button>          
            </div>
        </div>

        <layout class="mt-2" :user='@json(auth()->user())' :additional='@json($additional)' :setting='@json($setting)' name="alter-table"></layout>


    </section>

</x-app-layout>


<script>
    let target = document.querySelector("button#create");
    target.addEventListener("click", function(e) {
        window.location.href = e.target.getAttribute("href")
    })
</script>