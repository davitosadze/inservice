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
        <!-- Default box -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">მომხმარებლის ჩამონათვალი</h3>
                <div class="card-tools">
                    <div class="input-group input-group-sm" style="width: 150px;">          
                        <button type="submit" onclick="location.href = 'http://invoice/category-attributes/null/edit'" class="btn btn-sm btn-outline-success">
                            <i class="fas fa-shield-alt"></i> დამატება
                        </button>          
                    </div>
                </div>
            </div>
            <!-- /.card-body -->
            <div id="renderer" style="padding: 1em;">
                <layout :user='@json(auth()->user())' :additional='@json($additional)' :setting='@json($setting)' name="world"></layout>
            </div>
        </div>
        <!-- /.card -->
    </section>

</x-app-layout>