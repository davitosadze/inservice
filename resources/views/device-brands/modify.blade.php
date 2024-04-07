@section('title', 'მოყობილობის ბრენდები')
<x-app-layout>

    <x-slot name="header">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">მოყობილობის ბრენდი</h1>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->
    </x-slot>

    @once
        @push('styles')
            <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.css" rel="stylesheet" />
            <style type="text/css">
                span.select2-container--default .select2-selection--single {
                    padding: 0;
                }
            </style>
        @endpush
    @endonce

    <?php if (session()->getOldInput()) {
        print_r(collect(session()->getOldInput()['item']));
        print_r($model->items);
    }
    ?>

    <!-- Main content -->
    <section class="content">
        {{ Form::model($model, ['route' => ['device-brands.store', $model->id], 'id' => 'render']) }}

        <div id="renderer">


            <div class="card card-primary card-outline card-tabs">
                <div class="card-body">

                    <div class="row">

                        <div class="col-12 col-md-12 col-lg-8 order-2 order-md-1">


                            @csrf
                            <input type="hidden" name="id" value="{{ $model->id }}">
                            <div class="form-group">
                                <label for="formGroupExampleInput">მოყობილობის ბრენდის სახელი</label>
                                {{ Form::text('name', $model->name, ['placeholder' => 'მოყობილობის ბრენდის სახელი', 'class' => 'form-control']) }}

                            </div>





                        </div>

                        <div class="col-12 col-md-12 col-lg-4 order-1 order-md-2">
                            <div class="form-group"></div>

                            <a href="{{ route('reports.index') }}" class="btn btn-warning btn-block"><i
                                    class="far fa-window-close"></i> გასვლა</a>


                            <button class="btn btn-success  btn-block">
                                <i class="far fa-paper-plane"></i> გაგზავნა
                            </button>
                        </div>


                    </div>

                </div>



            </div>

        </div>
        {!! Form::close() !!}

    </section>


    @once
        @push('scripts')
            <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.js"></script>
            <script type="text/javascript">
                let model = @json($model);
                let setting = @json($setting);

                $(document).ready(function() {
                    $('.purchaser').select2();
                });

                $('.purchaser').on('select2:select', function(e) {
                    let res = JSON.parse(e.target.value);
                    if (res) {
                        document.querySelector('input[name="name"]').value = res.name;
                        document.querySelector('input[name="subject_name"]').value = res.subj_name;
                        document.querySelector('input[name="subject_address"]').value = res.subj_address;
                        document.querySelector('input[name="identification_num"]').value = res.identification_num;
                    }
                });

                let useSwall = (res) => {
                    let icon = !res.data.success ? "error" : "success"
                    let exit = {}
                    if (res.data.success) {
                        exit = {
                            showCancelButton: true,
                            showConfirmButton: true,
                            timer: false,
                            cancelButtonText: 'გაგრძელება',
                            confirmButtonText: 'ჩამონათვალში დაბრუნება'
                        }
                    }

                    return Swal.fire({
                        position: 'top-end',
                        icon: icon,
                        title: res.data.statusText,
                        showConfirmButton: false,
                        timer: 2000,
                        ...exit
                    })
                }

                window.addEventListener('load', (event) => {
                    document.querySelector('button.btn-block').addEventListener('click', function(e) {
                        e.preventDefault();

                        let res = new FormData(document.querySelector('#render'));
                        let url = document.querySelector('#render').getAttribute('action')

                        axios({
                                method: 'post',
                                url: url,
                                data: res,
                                headers: {
                                    "Content-Type": "multipart/form-data"
                                }
                            })
                            .then(function(response) {
                                // handle success

                                useSwall(response).then((result) => {

                                    if (result.value) {
                                        window.location.replace(setting.url.request.index.replace(
                                            'api/', ''))
                                    } else if (model.id == null && response.data.success == true) {
                                        window.location.replace(window.location.href.replace('new',
                                            response.data.result.id));
                                    }

                                    if (!response.data.success) {
                                        if (response.data.errs) response.data.errs.map(item => window
                                            .app._context.provides.$toast.error(item, {
                                                position: 'top-right',
                                                duration: 7000
                                            }))
                                    }

                                })
                            })
                            .catch(function(error) {
                                // handle error
                                window.app._context.provides.$toast.error(e.response.statusText, {
                                    position: 'top-right',
                                    duration: 7000
                                })
                            });

                        return false;
                    })


                    let taber = document.querySelector('#create');
                    let acording = document.querySelector('#accordion');

                    setTimeout(() => {

                        if (acording.children.length == 0) {

                            let index = (acording.children.length == 0) ? 0 : acording.children.length;
                            index = index.toString();

                            let elm = document.createElement('tab');
                            acording.appendChild(elm)

                            let tab = createApp(app._context.components.tab, {
                                index: index,
                                res: [],
                                model,
                                isNew: true
                            });
                            tab.mount(elm)
                        }

                        taber.addEventListener('click', function(e) {

                            let index = (acording.children.length == 0) ? 0 : acording.children.length;
                            index = index.toString();

                            let elm = document.createElement('tab');
                            acording.appendChild(elm)

                            let tab = createApp(app._context.components.tab, {
                                index: index,
                                res: [],
                                model,
                                isNew: true
                            });
                            tab.mount(elm)

                        });

                    }, 300)
                });
            </script>
        @endpush
    @endonce

</x-app-layout>
