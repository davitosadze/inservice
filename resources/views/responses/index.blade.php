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
    let target = document.querySelector("button#create");
    target.addEventListener("click", function(e) {
        window.location.href = e.target.getAttribute("href")
    })

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
