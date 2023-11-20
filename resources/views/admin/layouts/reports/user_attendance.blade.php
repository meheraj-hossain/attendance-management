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

@php
    $monthNames = app('TableNameToMonthName')->getMonthNameFromDatabase();
@endphp

@section('content')
    <form action="{{ route('reports.user.attendance') }}" method="get">
        <div class="search-bar">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12 col-lg-3">
                        <div class="form-group">
                            <label>Date From:</label>
                            <input type="text" id="datepicker_from" name="date_from"
                                   value="{{ request()->get('date_from') }}"
                                   class="form-control"
                                   id="date_from">
                        </div>
                    </div>
                    <div class="col-12 col-lg-3">
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
                            <select id="month" name="month" class="form-control select2bs4" style="width: 100%;">
                                @foreach($monthNames as $index => $month)
                                    <option @if( request()->month  == $month ) selected
                                            @endif
                                            value="{{ $month }}">
                                        {{ $month }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-lg-2">
                        <div class="form-group">
                            <label>User ID</label>
                            <select id="user_id" name="user_id" class="form-control select2bs4" style="width: 100%;">
                                <option value="">Select a User Id</option>
                                @foreach(fetchUser() as $user)
                                    <option @if(request()->get('user_id') == $user->user_id)  selected @endif
                                    value="{{ $user->user_id }}">{{ $user->user_id }} ({{ $user->name }}
                                        )
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
                            <a href="{{ route('reports.user.attendance') }}" class="form-control btn btn-danger">
                                <i class="fas fa-sync-alt"></i>
                                Refresh
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.row -->

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
                                <th>Date</th>
                                <th>Day</th>
                                <th>In Time</th>
                                <th>Out Time</th>
                                <th>Total In Time</th>
                                <th>Total Out Time</th>
                                <th>Total Hour Worked</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if($users_attendance)
                                @foreach ($users_attendance as $key => $user_attendance)
                                    <tr>
                                        <td>{{ ++$key }}</td>
                                        <td>{{ $user_attendance->user_id }}</td>
                                        <td>
                                            <b>
                                                @foreach(fetchUser() as $user)
                                                    @if($user->user_id == $user_attendance->user_id)
                                                        <b>{{ $user->name }}</b>
                                                    @endif
                                                @endforeach
                                            </b>
                                        </td>
                                        <td>{{ $user_attendance->modified_event_time }}</td>
                                        <td>{{ (new DateTime($user_attendance->modified_event_time))->format('l') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($user_attendance->in_time)->format('Y-m-d h:i A') }}</td>
                                        <td>{{ outTime($user_attendance->modified_in_time, $user_attendance->modified_out_time,\Carbon\Carbon::parse($user_attendance->in_time)->format('Y-m-d h:i A') ) }}</td>
                                        <td>{{ $user_attendance->total_in_count }} times</td>
                                        <td>{{ $user_attendance->total_out_count }} times</td>
                                        <td>{{ totalHourWorked($user_attendance->modified_in_time, $user_attendance->modified_out_time) }}</td>
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
