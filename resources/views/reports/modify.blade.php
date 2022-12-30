<x-app-layout>

    <x-slot name="header">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">რეპორტი</h1>
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

   {{--  <?php if (session()->getOldInput()) {
            print_r(collect(session()->getOldInput()['item']));
            print_r($model->items);
           //$model->items = collect(session()->getOldInput()['item']);
        }
    ?> --}}

    <!-- Main content -->
    <section class="content">
{{Form::model($model, ['route' => ['reports.store', $model->id], 'id' => 'render'])}}
        
            <div id="renderer">

                
                 <div class="card card-primary card-outline card-tabs">
      <div class="card-body">

<div class="row">

          <div class="col-12 col-md-12 col-lg-8 order-2 order-md-1">

            
                @csrf
                <div class="mb-3">

                    <input type="hidden" name="id" value="{{$model->id}}">

                    <address>
                    <input type="hidden" >

                    <div class="row mb-2" style="align-items: center;">
                      <div class="col-4"><b>დასახელება :</b></div>
                      <div class="col-8">
                        {{Form::text('title', $model->title, ['class' => 'form-control'])}}
                    </div>
                    </div>

                    <hr />

                    <p class="lead">კვლევის ობიექტი</p>


                    <div class="row mb-2" style="align-items: center;">
                      <div class="col-4"><b>აირჩიეთ :</b></div>
                      <div class="col-8">
                        <select name="version" class="form-control version">
                            <option disabled selected>--- აირჩეთ ---</option>
                            @foreach ($additional['purchasers'] as $version)
                                <option value='@json($version)' @selected(old('version') == $version)>
                                    {{ $version['name'] }} / {{ $version['subj_name'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    </div>

                    <div class="row mb-2" style="align-items: center;">
                      <div class="col-4"><b>კლიენტის სახელი :</b></div>
                      <div class="col-8">
                        {{Form::text('name', $model->name, ['class' => 'form-control'])}}
                    </div>
                    </div>

                    <div class="row mb-2" style="align-items: center;">
                      <div class="col-4"><b>დამატებითი სახელი :</b></div>
                      <div class="col-8">
                        {{Form::text('subject_name', $model->subject_name, ['class' => 'form-control'])}}
                    </div>
                    </div>

                    <div class="row mb-2" style="align-items: center;">
                      <div class="col-4"><b>კლიენტის მისამართი :</b></div>
                      <div class="col-8">
                        {{Form::text('subject_address', $model->subject_address, ['class' => 'form-control'])}}
                    </div>
                    </div>

                    <div class="row mb-2" style="align-items: center;">
                      <div class="col-4"><b>საიდენთიპიკაციო კოდი :</b></div>
                      <div class="col-8">
                        {{Form::text('identification_num', $model->identification_num, ['class' => 'form-control'])}}
                    </div>
                    </div>
                  
                  </address>
                </div>

                <hr />

                <div id="accordion" class="mt-2">

                    @foreach ($model->items as $index => $item)
                        <tab index="{{$index}}" :key="{{$index}}" :res="{{$item}}" :model="{{$model}}"></tab>
                    @endforeach
                </div>


                <button type="button" id="create" class="btn btn-sm btn-outline-success">
                        <i class="fas fa-shield-alt"></i> დამატება
                    </button>

                

               
            </div>

            <div class="col-12 col-md-12 col-lg-4 order-1 order-md-2">
            <div class="form-group"></div>

                <a href="{{ route('reports.index') }}" class="btn btn-warning btn-block"><i class="far fa-window-close"></i> გასვლა</a>

                @if($model->id)
                    <a href="{{ route('reports.show', ['report' => $model->id]) }}" class="btn btn-success btn-block">ნახვა</a>
                @endif
            

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

                $(document).ready(function() { $('.version').select2(); });

                $('.version').on('select2:select', function (e) {
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
                            showCancelButton: true, showConfirmButton: true, timer: false, cancelButtonText: 'გაგრძელება', confirmButtonText: 'ჩამონათვალში დაბრუნება' 
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
                              headers: {"Content-Type": "multipart/form-data"}
                            })
                            .then(function (response) {
                            // handle success

                            useSwall(response).then((result) => {

                                if (result.value) {
                                  window.location.replace(setting.url.request.index.replace('api/', ''))
                                } else if (model.id == null && response.data.success == true) {
                                  window.location.replace(window.location.href.replace('new', response.data.result.id));
                                }

                                if (!response.data.success) {
                                  if (response.data.errs) response.data.errs.map(item => window.app._context.provides.$toast.error(item, { position: 'top-right', duration: 7000 }))
                                }

                              })
                          })
                          .catch(function (error) {
                            // handle error
                            window.app._context.provides.$toast.error(e.response.statusText, { position: 'top-right', duration: 7000 })
                          });

                        return false;
                    })


                    let taber = document.querySelector('#create');
                    let acording = document.querySelector('#accordion');

                    setTimeout(() => {

                        if (acording.children.length == 0) {

                        let index = (acording.children.length == 0) ? 0 : acording.children.length; index = index.toString();

                        let elm = document.createElement('tab');
                        acording.appendChild(elm)

                        let tab = createApp(app._context.components.tab, { index : index, res: [], model, isNew: true });
                        tab.mount(elm)
                    }

                    taber.addEventListener('click', function (e) {

                        let index = (acording.children.length == 0) ? 0 : acording.children.length; index = index.toString();

                        let elm = document.createElement('tab');
                        acording.appendChild(elm)

                        let tab = createApp(app._context.components.tab, { index : index, res: [], model, isNew: true });
                        tab.mount(elm)

                    });

                    }, 300)
                });

            </script>
        @endpush
    @endonce

</x-app-layout>


<script>
    // console.log('vue', app._context.components.tab)

    // document.querySelector('.version').addEventListener('change', (e) => {
    //     let res = JSON.parse(e.target.value);
    //     if (res) {
    //         document.querySelector('input[name="name"]').value = res.name;
    //         document.querySelector('input[name="subject_name"]').value = res.subj_name;
    //         document.querySelector('input[name="subject_address"]').value = res.subj_address;
    //         document.querySelector('input[name="identification_num"]').value = res.identification_num;
    //     }
    // });

</script>

{{-- 
<div class="card">
    <div class="card-header" id="headingThree">
      <h5 class="mb-0">
        <button onclick="return false;" class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
          Collapsible Group Item #3
        </button>
      </h5>
    </div>
    <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
      <div class="card-body">
        Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
      </div>
    </div>
  </div> --}}