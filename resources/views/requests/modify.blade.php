<x-app-layout>
    <x-slot name="header">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">განფასება</h1>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->
    </x-slot>
    
    <!-- Main content -->
    <section class="content">
        <div id="renderer">
            {!! Form::model($model, ['route' => ['api.evaluations.store'], 'id' => 'render']) !!}
            <layout :user='@json(auth()->user())' url="{{request()->url()}}"  :model='@json($model)' :additional='@json($additional)' :setting='@json($setting)' name="request-edit"></layout>
            {!! Form::close() !!}
        </div>
    </section>

</x-app-layout>
