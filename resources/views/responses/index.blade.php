@section('title', 'რეაგირებები')
<x-app-layout>

    <x-slot name="header">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">რეაგირებები</h1>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->
    </x-slot>

    <!-- Main content -->
    <section class="content">

        <div class="card-tools">
            <div class="input-group input-group-sm">
                <input type="date" id="fromDate" class="form-control" placeholder="From">
                <input type="date" id="toDate" class="form-control" placeholder="To">
                <button id="export" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-download"></i> Export
                </button>
            </div>

            <br>

            <div class="card-tools">
                <div class="input-group input-group-sm" style="width: 150px;">
                    <button href="{{ request()->url() }}/new/edit" id="create"
                        class="btn btn-sm btn-outline-success">
                        <i class="fas fa-shield-alt"></i> დამატება
                    </button>
                </div>
            </div>

            @if (app('request')->input('type') == 'pending')
                <hr>
                <div class="row">
                    @foreach ($responses as $response)
                        <div class="col-sm-6">
                            <div class="card">
                                <h5
                                    class="@if ($response->status == 1) rag-red @elseif($response->status == 2) rag-yellow @endif card-header">
                                    {{ $response->id }}</h5>
                                <div class="card-body">
                                    <h5 class="card-title">{{ $response->purchaser?->name }}</h5>
                                    <p class="card-text">{{ $response->purchaser?->subj_address }}</p>
                                    <p class="card-text">{{ $response->purchaser?->subj_name }}</p>
                                    <div class="row" role="group" aria-label="Button group">

                                        <div class="col-sm-3">
                                            @if (Auth::user()->can('რეაგირების ნახვა'))
                                                <a href="{{ route('responses.show', $response->id) }}"
                                                    class="btn btn-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            @endif

                                            @if (Auth::user()->can('რეაგირების რედაქტირება'))
                                                <a href="{{ route('responses.edit', $response->id) }}"
                                                    class="ml-2 btn btn-primary">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            @endif
                                        </div>
                                        <div class="col-sm-9" style="text-align: right;">
                                            @if (Auth::user()->can('რეაგირების წაშლა'))
                                                <form method="POST"
                                                    action="{{ route('responses.destroy', $response->id) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button style="text-align: right" type="button"
                                                        class="btn btn-danger" onclick="confirmDelete(this)">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>
            @else
                <!-- /.card-body -->
                <div id="renderer" class="mt-2">
                    <layout class="mt-2" :user='@json(auth()->user())'
                        :additional='@json($additional)' :setting='@json($setting)'
                        name="alter-table">
                    </layout>
                </div>
            @endif
    </section>

</x-app-layout>
<script src="https://code.jquery.com/jquery-3.6.0.js"></script>

<script>
    let target = document.querySelector("button#create");
    target.addEventListener("click", function(e) {
        window.location.href = e.target.getAttribute("href")
    })

    function confirmDelete(button) {
        if (confirm('ნამდვილად გსურთ რეაგირების წაშლა?')) {
            button.closest('form').submit();
        }
    }
    $(document).ready(function() {

        $("#export").click(function() {
            var fromDate = $("#fromDate").val();
            var toDate = $("#toDate").val();

            if (fromDate && toDate) {
                var backendLink = "{{ route('api.responses.export') }}?from=" + fromDate + "&to=" +
                    toDate;
                window.location.href = backendLink;
            } else {
                alert("გთხოვთ შეავსოთ ორივე ველი");
            }

        });
    });

    Array.from(document.querySelectorAll(['input.try-delete'])).map(i => {
        i.addEventListener('click', function(e) {
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
                if (result.value) {
                    target.parentElement.submit();
                }
            })

            return false;
        });
    });
</script>
<style>
    .rag-red {
        background-color: #ec3339 !important;
        color: white;
    }

    .rag-yellow {
        background-color: #fbb42f !important;
    }
</style>
