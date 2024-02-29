@extends('admin.templates.master')

@push('css')
    {{--    <!-- DataTables -->--}}
    {{--    <link rel="stylesheet" href="{{ asset('admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">--}}
    {{--    <link rel="stylesheet"--}}
    {{--          href="{{ asset('admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">--}}
    {{--    <link rel="stylesheet" href="{{ asset('admin/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">--}}
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">

    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('admin/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">

    <style>
        .search-bar {
            margin: 0 0 1rem 0;
            background-color: #ffffff;
            border: 0 solid rgba(0, 0, 0, .125);
            border-radius: .25rem;
        }

        .ui-datepicker-header, .ui-datepicker-calendar thead {
            display: none;
        }

        .ui-datepicker-prev, .ui-datepicker-next {
            visibility: hidden;
        }

        .ui-state-active {
            border: 1px solid #c5c5c5 !important;
            background: #f6f6f6 !important;
            font-weight: normal !important;
            color: #454545 !important;
        }

        @media print {
        }
    </style>
@endpush

@php
    use Carbon\Carbon;
    if (request()->month && request()->year) {
        $startDate = Carbon::createFromDate(request()->year, request()->month, 1);
        $endDate = Carbon::createFromDate(request()->year, request()->month, 1)->endOfMonth();
        $daysCount = $endDate->diffInDays($startDate) + 1;
    } else{
        $startDate = now()->firstOfMonth();
        $endDate = now()->lastOfMonth();
        $daysCount = $endDate->diffInDays($startDate) + 1;
    }
@endphp

@section('content')
    <form action="{{ route('reports.monthly.attendance.date.wise') }}" method="get">
        <div class="search-bar d-print-none">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12 col-lg-2">
                        <div class="form-group">
                            <label>Date From:</label>
                            <input type="text" id="datepicker_from" name="date_from"
                                   value="{{ request()->get('date_from') }}"
                                   class="form-control">
                        </div>
                    </div>
                    <div class="col-12 col-lg-2">
                        <div class="form-group">
                            <label>Date To:</label>
                            <input type="text" id="datepicker_to" name="date_to"
                                   value="{{ request()->get('date_to') }}"
                                   class="form-control">
                        </div>
                    </div>
                    <div class="col-12 col-lg-2">
                        <div class="form-group">
                            <label>Month</label>
                            <span class=" badge badge-danger">Required</span>
                            <select id="month" name="month" class="form-control select2bs4" style="width: 100%;">
                                <option value="">
                                    Select a Month
                                </option>
                                <option @if(request()->get('month') == 12) selected @endif value="12">
                                    December
                                </option>
                                <option @if(request()->get('month') == 11) selected @endif value="11">
                                    November
                                </option>
                                <option @if(request()->get('month') == 10) selected @endif value="10">
                                    October
                                </option>
                                <option @if(request()->get('month') == 9) selected @endif value="09">
                                    September
                                </option>
                                <option @if(request()->get('month') == 8) selected @endif value="08">
                                    August
                                </option>
                                <option @if(request()->get('month') == 7) selected @endif value="07">
                                    July
                                </option>
                                <option @if(request()->get('month') == 6) selected @endif value="06">
                                    June
                                </option>
                                <option @if(request()->get('month') == 5) selected @endif value="05">
                                    May
                                </option>
                                <option @if(request()->get('month') == 4) selected @endif value="04">
                                    April
                                </option>
                                <option @if(request()->get('month') == 3) selected @endif value="03">
                                    March
                                </option>
                                <option @if(request()->get('month') == 2) selected @endif value="02">
                                    February
                                </option>
                                <option @if(request()->get('month') == 1) selected @endif value="01">
                                    January
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-lg-2">
                        <div class="form-group">
                            <label>Year</label>
                            <span class=" badge badge-danger">Required</span>
                            <select id="year" name="year" class="form-control select2bs4" style="width: 100%;">
                                <option value="">
                                    Select a Year
                                </option>
                                @for( $i = 2023; $i <= 2028; $i++)
                                    <option @if(request()->get('year') == $i) selected
                                            @endif value="{{ $i }}"> {{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-lg-2">
                        <div class="form-group">
                            <label>Department</label>
                            <select id="department" name="department" class="form-control select2bs4"
                                    style="width: 100%;">
                                <option value="">Select a Department</option>
                                @foreach(fetchDepartment() as $query)
                                    <option @if(request()->get('department') == $query->department)  selected @endif
                                    value="{{ $query->department }}">{{ $query->department }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-lg-1">
                        <div class="form-group">
                            <label class="d-none d-lg-block">Search</label>
                            <button type="submit" class="form-control btn btn-info">
                                <i class="fas fa-search"></i>
                                Search
                            </button>
                        </div>
                    </div>
                    <div class="col-12 col-lg-1">
                        <div class="form-group">
                            <label class="d-none d-lg-block">Refresh</label>
                            <a href="{{ route('reports.monthly.attendance.date.wise') }}"
                               class="form-control btn btn-danger">
                                <i class="fas fa-sync-alt"></i>
                                Refresh
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">

                    <div class="card-header">
                        <h3 class="card-title float-lg-left d-lg-flex align-items-lg-center">
                            {{ $title }}
                            @if(request()->department)
                                of {{ request()->department }}
                            @endif
                            @if(request()->date_from)
                                from {{ request()->date_from }}-{{ request()->month }}-{{ request()->year }}
                            @endif
                            @if(request()->date_to)
                                to {{ request()->date_to }}-{{ request()->month }}-{{ request()->year }}
                            @endif
                        </h3>
                        <a href="{{ str_replace(request()->segment(3), 'generate-monthly-attendance-date-wise-pdf', url()->full()) }}"
                           class="btn btn-secondary btn-sm float-lg-right">
                            PDF
                        </a>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="example1" class="table table-striped table-bordered">
                            <thead style="position: sticky;top: 0; background:wheat;">
                            <tr>
                                <th>Sl</th>
                                <th>User ID</th>
                                <th>User Name</th>
                                @for($i = 1; $i <= $daysCount; $i++)
                                    <th>{{ $i }}</th>
                                @endfor
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $currentDepartment = null;
                                $departmentId = 0;
                                $departmentCounters = [];
                            @endphp

                            @foreach ($monthly_attendance_reports as $item)
                                @if ($currentDepartment !== $item->department)
                                    @php
                                        $currentDepartment = $item->department;
                                        $departmentId++;
                                        $departmentCounters[$currentDepartment] = 0; // Reset the department-specific counter
                                    @endphp
                                    <tr>
                                        <td colspan="{{ $daysCount + 3 }}" class="text-danger bg-danger">
                                            <strong>{{ $departmentId }}
                                                - {{ $currentDepartment }}</strong></td>
                                    </tr>
                                @endif
                                <tr id="{{ $item->user_id }}">
                                    <td>
                                        {{ ++$departmentCounters[$currentDepartment] }}
                                    </td>
                                    <td>{{ $item->user_id }}</td>
                                    <td><b>{{ fetchUserById($item->user_id) ?? 'NOT AVAILABLE' }}</b></td>
                                    @for($i = 1; $i <= $daysCount; $i++)
                                        <td data-toggle="modal" data-target="#modal-default"
                                            data-user-id="{{ $item->user_id }}" data-date="{{ $i }}"
                                            data-user-name="{{ fetchUserById($item->user_id) }}"
                                            data-department="{{ $item->department }}"
                                            data-days-attended="{{ $item->days_attended }}">
                                            @if(property_exists($item, $i))
                                                @if($item->{$i} == 'present')
                                                    <i class="fas fa-check"></i>
                                                @endif
                                            @endif
                                        </td>
                                    @endfor
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </form>

    <div class="modal fade" id="modal-default">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 d-flex justify-content-center align-items-center my-3">
                            <label style="width: 45%">Date:</label>
                            <input type="text" id="modal-date"
                                   value="" readonly
                                   class="form-control ml-2">
                        </div>

                        <div class="col-12 d-flex justify-content-center align-items-center my-3">
                            <label style="width: 45%">Day:</label>
                            <input type="text" id="modal-day"
                                   value="" readonly
                                   class="form-control ml-2">
                        </div>

                        <div class="col-12 d-flex justify-content-center align-items-center my-3">
                            <label style="width: 45%">In Time:</label>
                            <input type="text" id="modal-in-time"
                                   value="" readonly
                                   class="form-control ml-2">
                        </div>

                        <div class="col-12 d-flex justify-content-center align-items-center my-3">
                            <label style="width: 45%">Out Time:</label>
                            <input type="text" id="modal-out-time"
                                   value="" readonly
                                   class="form-control ml-2">
                        </div>

                        <div class="col-12 d-flex justify-content-center align-items-center my-3">
                            <label style="width: 45%">Total Hour Worked:</label>
                            <input type="text" id="modal-total-hour-worked"
                                   value="" readonly
                                   class="form-control ml-2">
                        </div>

                    </div>
                </div>
                <div class="modal-footer justify-content-end">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
@endsection

@push('js')
    <script>
        $(function () {
            $("#datepicker_from, #datepicker_to").datepicker({dateFormat: 'dd', defaultDate: new Date(2019, 11, 1)});
        });
    </script>

    <!-- Select2 -->
    <script src="{{ asset('admin/plugins/select2/js/select2.full.min.js') }}"></script>
    <!-- Page specific script -->
    <script>
        $(function () {
            //Initialize Select2 Elements
            $('.select2bs4').select2({
                theme: 'bootstrap4'
            })
        });

    </script>
    <script>
        $(document).ready(function () {
            $('td').click(function () {
                var userId = $(this).data('user-id');
                var initialDate = $(this).data('date');
                var date = initialDate.toString().padStart(2, '0');
                var userName = $(this).data('user-name');
                var department = $(this).data('department');
                var daysAttended = $(this).data('days-attended');
                var year = $('#year').val();
                if (!year) {
                    var currentYear = new Date().getFullYear();
                    $('#year').val(currentYear);
                    year = currentYear;
                }
                var month = $('#month').val();
                if (!month) {
                    var currentMonth = new Date().getMonth() + 1; // Months are zero-based
                    var formattedMonth = new Date().toLocaleString('en-US', {month: '2-digit'});
                    $('#month').val(formattedMonth);
                    month = formattedMonth;
                }
                var customDate = new Date(year, month - 1, date);
                var dayOfWeek = customDate.getDay();
                var weekdays = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                var dayName = weekdays[dayOfWeek];

                var monthNames = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
                var monthName = monthNames[month - 1];

                $.ajax({
                    url: '{{ route('get-employees-info-by-date') }}',
                    type: 'GET',
                    data: {
                        user_id: userId,
                        date: date,
                        month: month,
                        year: year,
                        user_name: userName,
                        department: department,
                        days_attended: daysAttended
                    },
                    success: function (response) {
                        if (response && response.length > 0) {
                            var inTime = response[0].in_time;
                            var dateObj = new Date(inTime);
                            var formattedInTime = dateObj.toLocaleTimeString('en-US', {
                                year: "numeric",
                                month: "long",
                                day: "2-digit",
                                hour: "numeric",
                                minute: "2-digit",
                                hour12: true
                            });
                            var modifiedInTime = response[0].modified_in_time;
                            var modifiedOutTime = response[0].modified_out_time;
                            var outTimeObj = new Date("1970-01-01T" + modifiedOutTime + "Z");
                            var inTimeObj = new Date("1970-01-01T" + modifiedInTime + "Z");
                            var timeDifference = outTimeObj - inTimeObj;
                            var hours = Math.floor(timeDifference / (1000 * 60 * 60));
                            var minutes = Math.floor((timeDifference % (1000 * 60 * 60)) / (1000 * 60));

                            var newDateObj = new Date(dateObj);
                            newDateObj.setHours(dateObj.getHours() + hours);
                            newDateObj.setMinutes(dateObj.getMinutes() + minutes);

                            var formattedCalculatedOutTime = newDateObj.toLocaleTimeString('en-US', {
                                year: "numeric",
                                month: "long",
                                day: "2-digit",
                                hour: "numeric",
                                minute: "2-digit",
                                hour12: true
                            });

                            $('.modal-title').html(userName + ' - ' + department);
                            $('#modal-date').val(monthName + ' ' + date + ',' + year);
                            $('#modal-day').val(dayName);
                            $('#modal-in-time').val(formattedInTime);
                            $('#modal-out-time').val(formattedCalculatedOutTime);
                            $('#modal-total-hour-worked').val(hours + " hours " + minutes + " minutes");

                            // Open your modal here
                        } else {
                            $('.modal-title').html(userName + ' - ' + department);
                            $('#modal-date').val(monthName + ' ' + date + ',' + year);
                            $('#modal-day').val(dayName);
                            $('#modal-in-time').val("No Data Available");
                            $('#modal-out-time').val("No Data Available");
                            $('#modal-total-hour-worked').val("No Data Available");
                        }

                    }
                });
            });
        });
    </script>
    <!-- Page specific script -->
    {{--    <script>--}}
    {{--        $(document).ready(function () {--}}
    {{--            $('#example1').DataTable({--}}
    {{--                "responsive": true,--}}
    {{--                "lengthChange": false,--}}
    {{--                "autoWidth": false,--}}
    {{--                "paging": false,--}}
    {{--                "info": false,--}}
    {{--                "ordering": false,--}}
    {{--            });--}}
    {{--        });--}}
    {{--    </script>--}}

    {{--    <script>--}}
    {{--        document.getElementById('generatePdfButton').addEventListener('click', function() {--}}
    {{--            generatePdf();--}}
    {{--        });--}}

    {{--        function generatePdf() {--}}
    {{--            window.location.href = '{{ route("generate-pdf") }}';--}}
    {{--        }--}}
    {{--    </script>--}}

@endpush
