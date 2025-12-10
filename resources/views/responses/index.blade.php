@section('title', 'რეაგირებები')
<x-app-layout>

    <x-slot name="header">
    </x-slot>

    <!-- Main content -->
    <section class="content">

                <!-- Response Type Tabs -->
                <div class="response-tabs-container">
                    <div class="response-tabs">
                        <a class="response-tab {{ !request()->get('type') || request()->get('type') === 'pending' ? 'active' : '' }}"
                           href="{{ route('responses.index', ['type' => 'pending']) }}">
                            <i class="fas fa-bolt"></i>
                            <span>მთავარი რეაგირებები</span>
                        </a>
                        <a class="response-tab {{ request()->get('type') === 'client-pending' ? 'active' : '' }}"
                           href="{{ route('responses.index', ['type' => 'client-pending']) }}">
                            <i class="fas fa-clock"></i>
                            <span>კლიენტის მოლოდინში</span>
                        </a>
                        <a class="response-tab"
                           href="{{ route('responses.new') }}">
                            <i class="fas fa-check-circle"></i>
                            <span>დასრულებული რეაგირებები</span>
                        </a>
                    </div>
                </div>

        <!-- Export Section - Desktop -->
        <div class="export-section export-desktop mb-3">
            <div class="export-card">
                <div class="export-header">
                    <i class="fas fa-file-excel"></i>
                    <span>Export to Excel</span>
                </div>
                <div class="export-body">
                    <div class="date-inputs">
                        <div class="date-field">
                            <label for="fromDateDesktop">From</label>
                            <input type="date" id="fromDateDesktop" class="form-control">
                        </div>
                        <div class="date-field">
                            <label for="toDateDesktop">To</label>
                            <input type="date" id="toDateDesktop" class="form-control">
                        </div>
                        <button id="exportBtnDesktop" class="btn btn-success export-btn">
                            <i class="fas fa-download"></i> Export
                        </button>
                        @if (Auth::user()->can('რეაგირების შექმნა'))
                        <button href="{{ request()->url() }}/new/edit" id="create" class="btn btn-primary action-btn">
                            <i class="fas fa-plus"></i> დამატება
                        </button>
                        <button type="button" class="btn btn-info action-btn" id="createClientOrder">
                            <i class="fas fa-user-plus"></i> კლიენტის შეკვეთა
                        </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Export Button - Mobile (triggers popup) -->
        <div class="export-mobile mb-3">
            <button id="exportMobileBtn" class="btn btn-primary export-mobile-btn">
                <i class="fas fa-file-excel"></i> Export to Excel
            </button>
            @if (Auth::user()->can('რეაგირების შექმნა'))
            <button href="{{ request()->url() }}/new/edit" id="createMobile" class="btn btn-success export-mobile-btn mt-2">
                <i class="fas fa-plus"></i> დამატება
            </button>
            <button type="button" class="btn btn-info export-mobile-btn mt-2" id="createClientOrderMobile">
                <i class="fas fa-user-plus"></i> კლიენტის შეკვეთა
            </button>
            @endif
        </div>

            @if (app('request')->input('type') != 'done')
                <div class="mt-2 view-switcher">
                    <button id="switchToGrid" class="btn active btn-sm btn-outline-success">გრიდი</button>
                    <button id="switchToTable" class="btn btn-sm btn-outline-success ml-2">ცხრილი</button>
                </div>
                <hr>
                <div id="gridView" class="row">

                    @foreach ($responses as $response)
                        @php
                            $statusClass = '';

                            if ($response->on_repair == 1) {
                                $statusClass = 'rag-on-repair';
                            } elseif ($response->status == 1) {
                                $statusClass = 'rag-red';
                            } elseif ($response->status == 2) {
                                $statusClass = 'rag-yellow';
                            } elseif (in_array($response->status, [5, 10])) {
                                $statusClass = 'rag-green-arrived';
                            } elseif($response->status == 9) {
                                $statusClass = 'rag-gray';
                            }
                        @endphp
                        <div class="col-sm-6">
                            <div class="card">

                                <h5 class="{{ $statusClass }} card-header">
                                    <span class="left">{{ $response->id }}</span>
                                    <span class="right">{{ $response->performer?->name }}</span>
                                </h5>
                                <div class="card-body">
                                    <h6>@if($response->type == 2) არასაკონტრაქტო @endif</h6> 

                                    <h5 class="card-title">{{ $response->name }}</h5>
                                    <p class="card-text">{{ $response->subject_address }}</p>
                                    <p class="card-text">{{ $response->subject_name }}</p>

                                    <div class="row" role="group" aria-label="Button group">

                                        @php
                                            $act_id = $response->act ? $response->act->id : 'new';
                                        @endphp
                                        <div class="col-sm-6">
                                            @if (!Auth::user()->hasRole('ინჟინერი'))
                                                <a href="{{ route('acts.edit', ['act' => $act_id, 'response_id' => $response->id]) }}"
                                                    class="mr-2 btn btn-success">
                                                    აქტი
                                                </a>
                                            @endif

                                            @if ($response->by_client && !$response->manager_id)
                                                <form method="POST" action="{{ route('responses.assign-manager', $response->id) }}" style="display: inline;">
                                                    @csrf
                                                    <button type="submit" 
                                                        onclick="return confirm('ნამდვილად მიიღებთ ამ რეაგირებას?')"
                                                        class="btn btn-warning mr-2">
                                                        მივიღე
                                                    </button>
                                                </form>
                                            @endif

                                            @if (Auth::user()->can('რეაგირების ნახვა'))
                                                <a href="{{ route('responses.show', $response->id) }}"
                                                    class="btn btn-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            @endif

                                            @role('ინჟინერი')
                                                @if (!$response->time)
                                                    <a href="{{ route('responses.arrived', $response->id) }}"
                                                        onclick="return confirm('ნამდვილად მიხვედით?')"
                                                        class="ml-2 btn btn-success">
                                                        <i class="fas fa-globe">მივედი</i>
                                                    </a>
                                                @endif
                                            @endrole



                                            @if (Auth::user()->can('რეაგირების რედაქტირება'))
                                                <a href="{{ route('responses.edit', $response->id) }}"
                                                    class="ml-2 btn btn-primary">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            @endif
                                        </div>
                                        <div class="col-sm-6" style="text-align: right;">
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
                                        <hr>
                                        <div class="col-sm 12">
                                            <div class="mt-2 card-footer">
                                                <div class="left">
                                                    <b>
                                                        <p>შექმნის დრო:</p>
                                                        <p>{{ \Carbon\Carbon::parse($response->created_at)->format('H:i / d.m.Y') }}
                                                        </p>
                                                    </b>
                                                </div>
                                                <div class="right">
                                                    <b>
                                                        <p>ადგილზე მისვლის დრო:</p>
                                                        <p>{{ $response->time ? \Carbon\Carbon::parse($response->time)->format('H:i / d.m.Y') : '-' }}
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
<script src="{{ asset('js/admin-order-modal.js') }}"></script>

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

    // Create button click handlers
    let createBtn = document.querySelector("button#create");
    if (createBtn) {
        createBtn.addEventListener("click", function(e) {
            window.location.href = e.target.closest('button').getAttribute("href")
        })
    }

    let createMobileBtn = document.querySelector("button#createMobile");
    if (createMobileBtn) {
        createMobileBtn.addEventListener("click", function(e) {
            window.location.href = e.target.closest('button').getAttribute("href")
        })
    }

    // Add event listener for client order buttons
    document.addEventListener('DOMContentLoaded', function() {
        const createClientOrderBtn = document.getElementById('createClientOrder');
        if (createClientOrderBtn) {
            createClientOrderBtn.addEventListener('click', function() {
                if (window.adminOrderModal) {
                    window.adminOrderModal.show();
                }
            });
        }

        const createClientOrderMobileBtn = document.getElementById('createClientOrderMobile');
        if (createClientOrderMobileBtn) {
            createClientOrderMobileBtn.addEventListener('click', function() {
                if (window.adminOrderModal) {
                    window.adminOrderModal.show();
                }
            });
        }
    });

    function confirmDelete(button) {
        if (confirm('ნამდვილად გსურთ რეაგირების წაშლა?')) {
            button.closest('form').submit();
        }
    }

    function handleExport(fromDate, toDate, buttonElement) {
        if (fromDate && toDate) {
            var backendLink = "{{ route('api.responses.export') }}?from=" + fromDate + "&to=" + toDate;

            // Get filters from AG Grid if available
            if (window.responsesGridApi) {
                var filterModel = window.responsesGridApi.getFilterModel();
                if (filterModel && Object.keys(filterModel).length > 0) {
                    backendLink += "&filters=" + encodeURIComponent(JSON.stringify(filterModel));
                }
            }

            // Show loading state
            if (buttonElement) {
                var originalContent = buttonElement.innerHTML;
                buttonElement.disabled = true;
                buttonElement.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading...';

                // Reset button after download starts (approximate delay)
                setTimeout(function() {
                    buttonElement.disabled = false;
                    buttonElement.innerHTML = originalContent;
                }, 3000);
            }

            window.location.href = backendLink;
        } else {
            Swal.fire({
                icon: 'warning',
                title: 'Error',
                text: 'Please fill both date fields',
                confirmButtonColor: '#667eea'
            });
        }
    }

    $(document).ready(function() {

        if ("{{ app('request')->input('type') }}" == "done") {
            $('#renderer').show();
        }

        // Desktop export
        $("#exportBtnDesktop").click(function() {
            var fromDate = $("#fromDateDesktop").val();
            var toDate = $("#toDateDesktop").val();
            handleExport(fromDate, toDate, this);
        });

        // Mobile export - SweetAlert popup
        $("#exportMobileBtn").click(function() {
            Swal.fire({
                title: 'Export to Excel',
                html: `
                    <div style="text-align: left;">
                        <div style="margin-bottom: 15px;">
                            <label style="display: block; margin-bottom: 5px; font-weight: 500; color: #666;">From</label>
                            <input type="date" id="swalFromDate" class="swal2-input" style="width: 100%; margin: 0;">
                        </div>
                        <div>
                            <label style="display: block; margin-bottom: 5px; font-weight: 500; color: #666;">To</label>
                            <input type="date" id="swalToDate" class="swal2-input" style="width: 100%; margin: 0;">
                        </div>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: '<i class="fas fa-download"></i> Export',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#11998e',
                cancelButtonColor: '#6c757d',
                preConfirm: () => {
                    const fromDate = document.getElementById('swalFromDate').value;
                    const toDate = document.getElementById('swalToDate').value;
                    if (!fromDate || !toDate) {
                        Swal.showValidationMessage('Please fill both date fields');
                        return false;
                    }
                    return { fromDate, toDate };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading popup
                    Swal.fire({
                        title: 'Exporting...',
                        html: '<i class="fas fa-spinner fa-spin fa-2x"></i><br><br>Preparing your Excel file',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true
                    });
                    handleExport(result.value.fromDate, result.value.toDate, null);
                }
            });
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

    .rag-gray {
        background-color: #a9a9a9 !important;
    }

    .rag-on-repair {
        background-color: #a020f045 !important;
    }

    /* Response Tabs Styling */
    .response-tabs-container {
        padding: 0 5px;
    }

    .response-tabs {
        display: flex;
        gap: 8px;
        background: #f8f9fa;
        padding: 8px;
        border-radius: 12px;
        flex-wrap: wrap;
    }

    .response-tab {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 10px 18px;
        border-radius: 8px;
        text-decoration: none;
        color: #6c757d;
        font-weight: 500;
        font-size: 14px;
        transition: all 0.2s ease;
        background: transparent;
        border: 1px solid transparent;
    }

    .response-tab:hover {
        color: #495057;
        background: white;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        text-decoration: none;
    }

    .response-tab.active {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.35);
    }

    .response-tab.active:hover {
        color: white;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.45);
    }

    .response-tab i {
        font-size: 14px;
    }

    @media (max-width: 768px) {
        .response-tabs {
            flex-direction: column;
            gap: 6px;
        }

        .response-tab {
            justify-content: center;
            padding: 12px 16px;
        }
    }

    /* Export Styling */
    .export-section {
        padding: 0 10px;
    }

    .export-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 10px;
        padding: 15px 20px;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }

    .export-header {
        color: white;
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .export-header i {
        font-size: 18px;
    }

    .export-body {
        background: white;
        border-radius: 8px;
        padding: 15px;
    }

    .date-inputs {
        display: flex;
        align-items: flex-end;
        gap: 15px;
        flex-wrap: wrap;
    }

    .date-field {
        flex: 1;
        min-width: 150px;
    }

    .date-field label {
        display: block;
        font-size: 12px;
        color: #666;
        margin-bottom: 5px;
        font-weight: 500;
    }

    .date-field input {
        border: 1px solid #ddd;
        border-radius: 6px;
        padding: 8px 12px;
        font-size: 14px;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .date-field input:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.15);
        outline: none;
    }

    .export-btn, .action-btn {
        border: none;
        padding: 10px 20px;
        font-weight: 600;
        border-radius: 6px;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .export-btn {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    }

    .export-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(17, 153, 142, 0.4);
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    }

    .action-btn {
        padding: 10px 16px;
    }

    .action-btn:hover {
        transform: translateY(-2px);
    }

    .export-btn:active, .action-btn:active {
        transform: translateY(0);
    }

    /* Mobile Export Button */
    .export-mobile {
        display: none;
        padding: 0 10px;
    }

    .export-mobile-btn {
        width: 100%;
        padding: 12px 20px;
        font-weight: 600;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    /* Responsive visibility */
    @media (max-width: 768px) {
        .export-desktop {
            display: none;
        }

        .export-mobile {
            display: block;
        }
    }
</style>
