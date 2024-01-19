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

        .dt-buttons {
            float: left;
        }
    </style>
@endpush

@section('content')
    <form action="{{ route('reports.daily.attendance') }}" method="get">
        <div class="search-bar">
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
                            <a href="{{ route('reports.daily.attendance') }}"
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
                        <h3 class="card-title">
                            {{ $title }}
                        </h3>
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
                                <th>Date</th>
                                <th>Day</th>
                                <th>In Time</th>
                                <th>Out Time</th>
                                {{--                                <th>Total In Time</th>--}}
                                {{--                                <th>Total Out Time</th>--}}
                                <th>Total Hour Worked</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(count($daily_attendance_reports) > 0)
                                @foreach($daily_attendance_reports as $key =>$query)
                                    <tr>
                                        <td>{{ ++$key }}</td>
                                        <td>{{ $query->user_id }}</td>
                                        <td>
                                            <b>
                                                @if(fetchUserById($query->user_id))
                                                    {{ fetchUserById($query->user_id)}}
                                                @else
                                                    NOT AVAILABLE
                                                @endif
                                            </b>
                                        </td>
                                        <td>
                                            @if(fetchDepartmentByUser($query->user_id))
                                                {{ fetchDepartmentByUser($query->user_id) }}
                                            @else
                                                NOT AVAILABLE
                                            @endif
                                        </td>
                                        <td>{{ $query->modified_event_time }}</td>
                                        <td>{{ (new DateTime($query->modified_event_time))->format('l') }}</td>
                                        <td>
                                            @if($query->in_time)
                                                {{ \Carbon\Carbon::parse($query->in_time)->format('Y-m-d h:i A') }}
                                            @else
                                                NOT AVAILABLE
                                            @endif
                                        </td>
                                        <td>
                                            @if($query->modified_in_time && $query->modified_out_time && $query->in_time)
                                                {{ outTime($query->modified_in_time, $query->modified_out_time,\Carbon\Carbon::parse($query->in_time)->format('Y-m-d h:i A') ) }}
                                            @else
                                                NOT AVAILABLE
                                            @endif
                                        </td>
                                        {{--                                        <td>{{ $query->total_in_count }} times</td>--}}
                                        {{--                                        <td>{{ $query->total_out_count }} times</td>--}}
                                        <td>
                                            @if($query->modified_in_time && $query->modified_out_time)
                                                {{ totalHourWorked($query->modified_in_time, $query->modified_out_time) }}
                                            @else
                                                NOT AVAILABLE
                                            @endif
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
            $('#example1').DataTable({
                "responsive": true, "lengthChange": false, "autoWidth": false, "pageLength": 100,
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'pdfHtml5',
                        customize: function (doc) {
                            doc.content.splice(0, 0, {
                                margin: [0, 0, 0, 12],
                                alignment: 'center',
                                image: 'data:image/png;base64,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',
                                width: 100, // Set the width of the image
                                height: 20  // Set the height of the image
                            });
                        }
                    },
                    {
                        extend: 'excel',
                    }
                ]
            });
        });
    </script>
@endpush
