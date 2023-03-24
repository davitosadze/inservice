@section('title', 'კლიენტები')
<x-app-layout>

    <x-slot name="header">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">კლიენტი</h1>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->
    </x-slot>

    <!-- Main content -->
    <section class="content">


        <div id="renderer">
            {{-- {!! Form::model($model, ['route' => ['api.clients.show'], 'id' => 'render']) !!} --}}
            <layout :user='@json(auth()->user())' url="{{ request()->url() }}"
                :model='@json($model)' :additional='@json($additional)'
                :setting='@json($setting)' name="client-view"></layout>
            {!! Form::close() !!}
        </div>

    </section>
    <script type="text/javascript">
        window.Laravel = {
            csrfToken: "{{ csrf_token() }}",
            jsPermissions: {!! auth()->user()
                ?->jsPermissions() !!}
        }
    </script>
</x-app-layout>
