@section('title', 'ინსტრუქცია')
<x-app-layout>

    <x-slot name="header">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">ინსტრუქცია</h1>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->
    </x-slot>

    <!-- Main content -->
    <section class="content">

        <div id="renderer">
            <layout :user='@json(auth()->user())' :model='{{ $model }}' url="{{ request()->url() }}"
                name="tree-form">
            </layout>

        </div>
        <!-- /.card -->
    </section>

</x-app-layout>
