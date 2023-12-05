@extends('admin.templates.master')

@push('css')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet"
          href="{{ asset('admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
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
    </style>
@endpush

@section('content')
    <form action="{{ route('reports.monthly.attendance') }}" method="get">
        <div class="search-bar">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12 col-lg-2">
                        <div class="form-group">
                            <label>Date From:</label>
                            <input type="text" id="datepicker_from" name="date_from"
                                   value="{{ request()->get('date_from') }}"
                                   class="form-control"
                                   id="date_from">
                        </div>
                    </div>
                    <div class="col-12 col-lg-2">
                        <div class="form-group">
                            <label>Date To:</label>
                            <input type="text" id="datepicker_to" name="date_to"
                                   value="{{ request()->get('date_to') }}"
                                   class="form-control"
                                   id="date_to">
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
                            <select id="department" name="department" class="form-control select2bs4" style="width: 100%;">
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
                            <label class="invisible">Search</label>
                            <button type="submit" class="form-control btn btn-info">
                                <i class="fas fa-search"></i>
                                Search
                            </button>
                        </div>
                    </div>
                    <div class="col-12 col-lg-1">
                        <div class="form-group">
                            <label class="invisible">Refresh</label>
                            <a href="{{ route('reports.monthly.attendance') }}" class="form-control btn btn-danger">
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
                        <h3 class="card-title">{{ $title }}</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>Serial</th>
                                <th>User ID</th>
                                <th>User Name</th>
                                <th>Department Name</th>
                                <th>Total Attendance</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if($monthly_attendance_reports)
                                @foreach($monthly_attendance_reports as $key => $monthly_attendance_report)
                                    <tr>
                                        <td>{{ ++$key }}</td>
                                        <td>
                                            {{ $monthly_attendance_report->user_id }}
                                        </td>
                                        <td>
                                            <b>
                                                @if(fetchUserById($monthly_attendance_report->user_id))
                                                    {{ fetchUserById($monthly_attendance_report->user_id)}}
                                                @else
                                                    NOT AVAILABLE
                                                @endif
                                            </b>
                                        </td>
                                        <td>
                                            @if(fetchDepartmentByUser($monthly_attendance_report->user_id))
                                                {{ fetchDepartmentByUser($monthly_attendance_report->user_id) }}
                                            @else
                                                NOT AVAILABLE
                                            @endif
                                        </td>
                                        <td>
                                            {{ $monthly_attendance_report->days_attended }} days
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
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

@endsection

@push('js')
    <!-- DataTables  & Plugins -->
    <script src="{{ asset('admin/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('admin/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
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

    <!-- Page specific script -->
    <script>
        $(function () {
            $("#example1").DataTable({
                "responsive": true, "lengthChange": false, "autoWidth": false, "pageLength": 500, "oLanguage": {
                    "sSearch": "Quick Search"
                },
                "buttons": ["copy", "csv", "excel", "pdf", "print"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        });
    </script>
@endpush
