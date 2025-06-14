@section('title', 'სერვისები')
<x-app-layout>

    <x-slot name="header">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">სერვისები</h1>
                    </div><!-- /.col -->
                    @if (Auth::user()->hasRole('ინჟინერი'))
                        <div class="col-sm-6 mt-2">
                            <a class="btn btn-primary" href="{{ route('services.index') }}">სერვისები</a>
                            <a class="btn ml-1  btn-outline-primary"
                                href="{{ route('responses.index') }}">რეაგირებები</a>
                            <a class="btn ml-1  btn-outline-primary" href="{{ route('repairs.index') }}">რემონტები</a>
                        </div>
                    @endif
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

            @if (Auth::user()->can('სერვისის შექმნა'))
                <div class="card-tools">
                    <div class="input-group input-group-sm" style="width: 150px;">
                        <button href="{{ request()->url() }}/new/edit" id="create"
                            class="btn btn-sm btn-outline-success">
                            <i class="fas fa-shield-alt"></i> დამატება
                        </button>
                    </div>
                </div>
            @endif

            @if (app('request')->input('type') != 'done')
                <div class="mt-2 view-switcher">
                    <button id="switchToGrid" class="btn active btn-sm btn-outline-success">გრიდი</button>
                    <button id="switchToTable" class="btn btn-sm btn-outline-success ml-2">ცხრილი</button>
                </div>
                <hr>
                <div id="gridView" class="row">
                    @foreach ($services as $service)
                        <div class="col-sm-6">
                            <div class="card">
                                <h5
                                    class="@if ($service->status == 1) rag-red @elseif($service->status == 2) rag-yellow @elseif($service->status == 10 || $service->status == 5) rag-green-arrived @endif card-header">
                                    <span class="left">{{ $service->id }}</span>
                                    <span class="right">{{ $service->performer?->name }}</span>
                                </h5>
                                <div class="card-body">
                                    <h5 class="card-title">{{ $service->name }}</h5>
                                    <p class="card-text">{{ $service->subject_address }}</p>
                                    <p class="card-text">{{ $service->subject_name }}</p>

                                    <div class="row" role="group" aria-label="Button group">

                                        @php
                                            $act_id = $service->act ? $service->act->id : 'new';
                                        @endphp
                                        <div class="col-sm-6">

                                            @if (!Auth::user()->hasRole('ინჟინერი'))
                                                <a href="{{ route('service-acts.edit', ['service_act' => $act_id, 'service_id' => $service->id]) }}"
                                                    class="mr-2 btn btn-success">
                                                    აქტი
                                                </a>
                                            @endif

                                            @if (Auth::user()->can('სერვისის ნახვა'))
                                                <a href="{{ route('services.show', $service->id) }}"
                                                    class="btn btn-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            @endif

                                            @role('ინჟინერი')
                                                @if (!$service->time)
                                                    <a href="{{ route('services.arrived', $service->id) }}"
                                                        onclick="return confirm('ნამდვილად მიხვედით?')"
                                                        class="ml-2 btn btn-success">
                                                        <i class="fas fa-globe">მივედი</i>
                                                    </a>
                                                @endif
                                            @endrole



                                            @if (Auth::user()->can('სერვისის რედაქტირება'))
                                                <a href="{{ route('services.edit', $service->id) }}"
                                                    class="ml-2 btn btn-primary">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            @endif
                                        </div>
                                        <div class="col-sm-6" style="text-align: right;">
                                            @if (Auth::user()->can('სერვისის წაშლა'))
                                                <form method="POST"
                                                    action="{{ route('services.destroy', $service->id) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button style="text-align: right" type="button"
                                                        class="btn btn-danger" onclick="confirmDelete(this)">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                        <hr>
                                        <div class="col-sm 12">
                                            <div class="mt-2 card-footer">
                                                <div class="left">
                                                    <b>
                                                        <p>შექმნის დრო:</p>
                                                        <p>{{ \Carbon\Carbon::parse($service->created_at)->format('H:i / d.m.Y') }}
                                                        </p>
                                                    </b>
                                                </div>
                                                <div class="right">
                                                    <b>
                                                        <p>ადგილზე მისვლის დრო:</p>
                                                        <p>{{ $service->time ? \Carbon\Carbon::parse($service->time)->format('H:i / d.m.Y') : '-' }}
                                                        </p>
                                                    </b>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>
            @endif
            <!-- /.card-body -->
            <div id="renderer" class="mt-2">
                <layout class="mt-2" :user='@json(auth()->user())' :additional='@json($additional)'
                    :setting='@json($setting)' name="alter-table">
                </layout>
            </div>
    </section>

</x-app-layout>
<script src="https://code.jquery.com/jquery-3.6.0.js"></script>

<script>
    $('#renderer').hide();

    $('#switchToGrid').click(function() {
        $('#gridView').show();
        $('#switchToGrid').addClass('active');
        $('#switchToTable').removeClass('active');

        $('#renderer').hide();
    });

    $('#switchToTable').click(function() {
        $('#gridView').hide();
        $('#switchToGrid').removeClass('active');
        $('#switchToTable').addClass('active');

        $('#renderer').show();
    });

    let target = document.querySelector("button#create");
    target.addEventListener("click", function(e) {
        window.location.href = e.target.getAttribute("href")
    })

    function confirmDelete(button) {
        if (confirm('ნამდვილად გსურთ სერვისის წაშლა?')) {
            button.closest('form').submit();
        }
    }
    $(document).ready(function() {

        if ("{{ app('request')->input('type') }}" == "done") {
            $('#renderer').show();

        }

        $("#export").click(function() {
            var fromDate = $("#fromDate").val();
            var toDate = $("#toDate").val();

            if (fromDate && toDate) {
                var backendLink = "{{ route('api.services.export') }}?from=" + fromDate + "&to=" +
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
    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .card-footer {
        font-size: 13px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .rag-red {
        background-color: #ec3339 !important;
        color: white;
    }

    .rag-yellow {
        background-color: #fbb42f !important;
    }

    .rag-green-arrived {
        background-color: #00c079 !important;
    }
</style>
