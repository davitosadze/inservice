@section('title', 'კალენდარი')
<x-app-layout>

    <x-slot name="header">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-8">
                        <div class="d-flex align-items-center">
                            <h1 class="m-0 mr-4">კალენდარი</h1>
                            <div class="calendar-nav-controls d-flex align-items-center">
                                <button id="prevMonth" class="btn btn-outline-secondary btn-sm mr-2">
                                    <i class="fas fa-chevron-left"></i>
                                </button>
                                <button id="nextMonth" class="btn btn-outline-secondary btn-sm mr-3">
                                    <i class="fas fa-chevron-right"></i>
                                </button>
                                <button id="todayBtn" class="btn btn-primary btn-sm mr-3">დღეს</button>
                                <h3 id="currentMonthYear" class="mb-0 text-muted"></h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="d-flex justify-content-end align-items-center">
                            <div class="btn-group mr-3" role="group">
                                <button type="button" id="monthViewBtn" class="btn btn-outline-primary btn-sm">თვე</button>
                                <button type="button" id="weekViewBtn" class="btn btn-outline-primary btn-sm active">კვირა</button>
                            </div>
                            <select id="monthSelect" class="form-control mr-2" style="width: auto;">
                                <option value="1" {{ date('n') == 1 ? 'selected' : '' }}>იანვარი</option>
                                <option value="2" {{ date('n') == 2 ? 'selected' : '' }}>თებერვალი</option>
                                <option value="3" {{ date('n') == 3 ? 'selected' : '' }}>მარტი</option>
                                <option value="4" {{ date('n') == 4 ? 'selected' : '' }}>აპრილი</option>
                                <option value="5" {{ date('n') == 5 ? 'selected' : '' }}>მაისი</option>
                                <option value="6" {{ date('n') == 6 ? 'selected' : '' }}>ივნისი</option>
                                <option value="7" {{ date('n') == 7 ? 'selected' : '' }}>ივლისი</option>
                                <option value="8" {{ date('n') == 8 ? 'selected' : '' }}>აგვისტო</option>
                                <option value="9" {{ date('n') == 9 ? 'selected' : '' }}>სექტემბერი</option>
                                <option value="10" {{ date('n') == 10 ? 'selected' : '' }}>ოქტომბერი</option>
                                <option value="11" {{ date('n') == 11 ? 'selected' : '' }}>ნოემბერი</option>
                                <option value="12" {{ date('n') == 12 ? 'selected' : '' }}>დეკემბერი</option>
                            </select>
                            <select id="yearSelect" class="form-control" style="width: auto;">
                                @for($year = date('Y') - 2; $year <= date('Y') + 2; $year++)
                                    <option value="{{ $year }}" {{ date('Y') == $year ? 'selected' : '' }}>{{ $year }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="calendar-legend">
                        <div class="legend-item">
                            <div class="legend-color repair"></div>
                            <span>რემონტები</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-color service"></div>
                            <span>სერვისები</span>
                        </div>
                    </div>
                    <div class="calendar-container">
                        <div class="calendar-wrapper">
                            <div class="calendar-header">
                                <div class="calendar-time-header"></div>
                                <div class="calendar-day-header">ორშაბათი</div>
                                <div class="calendar-day-header">სამშაბათი</div>
                                <div class="calendar-day-header">ოთხშაბათი</div>
                                <div class="calendar-day-header">ხუთშაბათი</div>
                                <div class="calendar-day-header">პარასკევი</div>
                                <div class="calendar-day-header">შაბათი</div>
                                <div class="calendar-day-header">კვირა</div>
                            </div>
                            <div id="calendar-grid" class="calendar-grid">
                                <!-- Calendar will be populated by JavaScript -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <link rel="stylesheet" href="{{ asset('css/calendar.css') }}">
    <script src="{{ asset('js/calendar.js') }}"></script>

</x-app-layout>