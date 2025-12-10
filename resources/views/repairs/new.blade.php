@section('title', 'რემონტები')
<x-app-layout>

    <x-slot name="header">
    </x-slot>

    <section class="content">
        <!-- Repair Type Tabs -->
        <div class="service-tabs-container">
            <div class="service-tabs">
                <a class="service-tab" href="{{ route('repairs.index', ['type' => 'pending']) }}">
                    <i class="fas fa-tools"></i>
                    <span>მთავარი რემონტები</span>
                </a>
                <a class="service-tab" href="{{ route('repairs.index', ['type' => 'standby']) }}">
                    <i class="fas fa-clock"></i>
                    <span>მოლოდინის რეჟიმი</span>
                </a>
                <a class="service-tab" href="{{ route('repairs.index', ['type' => 'client-pending']) }}">
                    <i class="fas fa-user-clock"></i>
                    <span>კლიენტის მოლოდინი</span>
                </a>
                <a class="service-tab active" href="{{ route('repairs.new') }}">
                    <i class="fas fa-check-circle"></i>
                    <span>დასრულებული რემონტები</span>
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
                    </div>
                </div>
            </div>
        </div>

        <!-- Export Button - Mobile (triggers popup) -->
        <div class="export-mobile mb-3">
            <button id="exportMobileBtn" class="btn btn-primary export-mobile-btn">
                <i class="fas fa-file-excel"></i> Export to Excel
            </button>
        </div>

        <layout class="mt-2"
            :user='@json(auth()->user())'
            :additional='@json($additional)'
            :setting='@json($setting)'
            name="server-side-table">
        </layout>
    </section>

</x-app-layout>

<style>
/* Service Tabs Styling */
.service-tabs-container {
    padding: 0 10px;
}

.service-tabs {
    display: flex;
    gap: 8px;
    background: #f8f9fa;
    padding: 8px;
    border-radius: 12px;
    flex-wrap: wrap;
}

.service-tab {
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

.service-tab:hover {
    color: #495057;
    background: white;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    text-decoration: none;
}

.service-tab.active {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.35);
}

.service-tab.active:hover {
    color: white;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.45);
}

.service-tab i {
    font-size: 14px;
}

@media (max-width: 768px) {
    .service-tabs {
        flex-direction: column;
        gap: 6px;
    }

    .service-tab {
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

.export-btn {
    background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    border: none;
    padding: 10px 25px;
    font-weight: 600;
    border-radius: 6px;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: transform 0.2s, box-shadow 0.2s;
}

.export-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(17, 153, 142, 0.4);
    background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
}

.export-btn:active {
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
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.export-mobile-btn:hover {
    background: linear-gradient(135deg, #5a6fd6 0%, #6a4190 100%);
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Desktop export
    document.getElementById('exportBtnDesktop').addEventListener('click', function() {
        var fromDate = document.getElementById('fromDateDesktop').value;
        var toDate = document.getElementById('toDateDesktop').value;
        handleExport(fromDate, toDate, this);
    });

    // Mobile export - SweetAlert popup
    document.getElementById('exportMobileBtn').addEventListener('click', function() {
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
                handleExport(result.value.fromDate, result.value.toDate, document.getElementById('exportMobileBtn'));
            }
        });
    });

    function handleExport(fromDate, toDate, button) {
        if (fromDate && toDate) {
            // Show loading state
            var originalHtml = button.innerHTML;
            button.disabled = true;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading...';

            // Get filters from AG Grid if available
            var filters = {};
            if (window.repairsGridApi) {
                filters = window.repairsGridApi.getFilterModel() || {};
            }

            var backendLink = "{{ route('api.repairs.export') }}?from=" + fromDate + "&to=" + toDate;
            if (Object.keys(filters).length > 0) {
                backendLink += "&filters=" + encodeURIComponent(JSON.stringify(filters));
            }

            // Create hidden iframe for download
            var iframe = document.createElement('iframe');
            iframe.style.display = 'none';
            iframe.src = backendLink;
            document.body.appendChild(iframe);

            // Reset button after delay
            setTimeout(function() {
                button.disabled = false;
                button.innerHTML = originalHtml;
                document.body.removeChild(iframe);
            }, 3000);
        } else {
            Swal.fire({
                icon: 'warning',
                title: 'Error',
                text: 'Please fill both date fields',
                confirmButtonColor: '#667eea'
            });
        }
    }
});
</script>
