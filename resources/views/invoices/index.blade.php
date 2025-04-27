@section('title', 'ინვოისები')
<x-app-layout>
    <x-slot name="header">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">ინვოისები</h1>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->
    </x-slot>

    <!-- Main content -->
    <section class="content">
        <div id="renderer">
            <div class="card-tools mt-2">
                <div class="input-group input-group-sm">
                    <!-- Date Range Selection Form with Inline Fields -->
                    <form action="{{ route('invoices.export') }}" method="get">
                        <div class="input-group input-group-sm">
                            <input type="text" id="purchaser" name="purchaser" class="form-control"
                                placeholder="ობიექტი">
                            <input type="date" id="fromDate" name="from" class="form-control" placeholder="From">
                            <input type="date" id="toDate" name="to" class="form-control" placeholder="To">
                            <button id="export" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-download"></i> გადმოწერა
                            </button>
                        </div>

                    </form>
                    <hr>
                    <div class="card-tools">
                        <div class="input-group input-group-sm" style="width: 150px;">
                            <a href="{{ request()->url() }}/new/edit" id="create"
                                class="btn btn-sm btn-outline-success w-100">
                                <i class="fas fa-shield-alt"></i> დამატება
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Add Button -->

            </div>

            <layout class="mt-2" :user='@json(auth()->user())' :additional='@json($additional)'
                :setting='@json($setting)' name="alter-table"></layout>
        </div>
    </section>


</x-app-layout>


<script>
    let target = document.querySelector("button#create");
    target.addEventListener("click", function(e) {
        window.location.href = e.target.getAttribute("href")
    })
</script>
