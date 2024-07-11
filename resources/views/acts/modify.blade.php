@section('title', 'აქტის შედგენა')
<x-app-layout>

    <x-slot name="header">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">აქტის შედგენა</h1>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->
    </x-slot>

    <!-- Main content -->
    <section class="content">


        <div id="renderer">
            {!! Form::model($model, ['route' => ['api.acts.store'], 'id' => 'render']) !!}
            <layout :user='@json(auth()->user())' url="{{ request()->url() }}"
                :model='@json($model)' :additional='@json($additional)'
                :setting='@json($setting)' name="insert-act"></layout>
            {!! Form::close() !!}
        </div>

    </section>
    @push('head')
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <style>
            html,
            body {
                overflow: hidden;
                width: 100%;
                height: 100%;
                -webkit-transform: rotate(0deg);
                -moz-transform: rotate(0deg);
                -ms-transform: rotate(0deg);
                -o-transform: rotate(0deg);
                transform: rotate(0deg);
            }

            @media only screen and (orientation:landscape) {
                body {
                    -webkit-transform: rotate(0deg);
                    -moz-transform: rotate(0deg);
                    -ms-transform: rotate(0deg);
                    -o-transform: rotate(0deg);
                    transform: rotate(0deg);
                }
            }

            @media only screen and (orientation:portrait) {
                body {
                    -webkit-transform: rotate(0deg);
                    -moz-transform: rotate(0deg);
                    -ms-transform: rotate(0deg);
                    -o-transform: rotate(0deg);
                    transform: rotate(0deg);
                }
            }
        </style>
    @endpush
</x-app-layout>
