<x-app-layout>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

    <x-slot name="header">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">სტატისტიკა</h1>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->
    </x-slot>

    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Statistics</h3>
                <div class="card-tools"></div>
            </div>
            <!-- /.card-body -->

            <div class="row">
                <div class="col">
                    <div id="evaluations" style="border: 1px solid #ccc"></div>
                </div>
                <div class="col">
                    <div id="invoices" style="border: 1px solid #ccc"></div>
                </div>
                <div class="w-100"></div>
                <br>
                <div class="col">
                    <div id="reports" style="border: 1px solid #ccc"></div>
                </div>

            </div>



        </div>
        </div>
        <!-- /.card -->
    </section>
    <script type="text/javascript">
        google.charts.load('current', {
            'packages': ['corechart']
        });
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {

            var evaluationsData = google.visualization.arrayToDataTable([
                ['მომხმარებელი', 'პროცენტული რაოდენობა'],
                {!! $chartData['evaluations'] !!}
            ]);
            var evaluationOptions = {
                title: 'დეფექტურები'
            };

            var reportsData = google.visualization.arrayToDataTable([
                ['მომხმარებელი', 'პროცენტული რაოდენობა'],
                {!! $chartData['reports'] !!}
            ]);
            var reportOptions = {
                title: 'განფასებები'
            };


            var invoicesData = google.visualization.arrayToDataTable([
                ['მომხმარებელი', 'პროცენტული რაოდენობა'],
                {!! $chartData['invoices'] !!}
            ]);
            var invoiceOptions = {
                title: 'ინვოისები'
            };

            var evaluationsChart = new google.visualization.PieChart(document.getElementById('evaluations'));
            var reportsChart = new google.visualization.PieChart(document.getElementById('reports'));
            var invoicesChart = new google.visualization.PieChart(document.getElementById('invoices'));

            evaluationsChart.draw(evaluationsData, evaluationOptions);
            reportsChart.draw(reportsData, reportOptions);
            invoicesChart.draw(invoicesData, invoiceOptions);


        }
    </script>
</x-app-layout>
