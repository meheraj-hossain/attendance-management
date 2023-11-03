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

        /*.ui-datepicker-prev, .ui-datepicker-next {*/
        /*    visibility: hidden;*/
        /*}*/
    </style>
@endpush

@section('content')
    <form action="{{ route('reports.monthly.attendance', $month_name) }}" method="get">
        <div class="search-bar">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12 col-lg-4">
                        <div class="form-group">
                            <label>Date From:</label>
                            <input type="text" id="datepicker_from" name="date_from"
                                   value=""
                                   class="form-control"
                                   id="date_from">
                        </div>
                    </div>
                    <div class="col-12 col-lg-4">
                        <div class="form-group">
                            <label>Date To:</label>
                            <input type="text" id="datepicker_to" name="date_to"
                                   value=""
                                   class="form-control"
                                   id="date_to">
                        </div>
                    </div>
                    <div class="col-12 col-lg-3">
                        <div class="form-group">
                            <label>User ID</label>
                            <select id="user_id" name="user_id" class="form-control select2bs4" style="width: 100%;">
                                <option value="">Select a User Id</option>
                                @foreach($users as $user)
                                    <option
                                        value="{{ $user->user_id }}">{{ $user->user_id }} ({{$user->user_name}})
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
                                <th>Total Attendance</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($monthly_attendance_reports as $key => $monthly_attendance_report)
                                <tr>
                                    <td>{{ ++$key }}</td>
                                    <td>
                                        {{ $monthly_attendance_report->user_id }}
                                    </td>
                                    <td>
                                        <b>
                                            @if($monthly_attendance_report->user_name == "")
                                                @php
                                                    $employee_name = \App\Models\Admin\Employee::where('employee_id', $monthly_attendance_report->user_id)->pluck('employee_name')->first()
                                                @endphp
                                                {{ $employee_name }}
                                            @else
                                                {{ $monthly_attendance_report->user_name }}
                                            @endif
                                        </b>
                                    </td>
                                    <td>
                                        {{ $monthly_attendance_report->days_attended }}
                                    </td>
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
            let currentTime = new Date();
            let date = currentTime.getDate();
            $("#datepicker_from, #datepicker_to").datepicker({minDate: -(date - 1), maxDate: 0, dateFormat: 'dd'});
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
