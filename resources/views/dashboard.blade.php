<x-app-layout>
    <x-slot name="header">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">მთავარი</h1>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->
    </x-slot>

    <!-- Main content -->
    <section class="content">
        <div id="renderer">

            <layout :user='@json(auth()->user())' url="{{ request()->url() }}" name="dashboard">
            </layout>
            {!! Form::close() !!}
        </div>
    </section>

</x-app-layout>
