@section('title', 'პარამეტრები')
<x-app-layout>

    <x-slot name="header">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">პარამეტრები</h1>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->
    </x-slot>

    <!-- Main content -->
    <section class="content">
        <form action="{{ route('options.store') }}" method="POST">
            @csrf
            <div class="d-flex justify-content-between">
                <input type="email" value="{{ $option ? $option->email : '' }}" name="email"
                    class="form-control" id="">
                <input type="submit" name="submit" class="btn btn-primary" value="განახლება" id="">
            </div>
        </form>
    </section>

</x-app-layout>
