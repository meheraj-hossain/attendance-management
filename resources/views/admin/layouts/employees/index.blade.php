@extends('admin.templates.master')

@php
    use \App\Models\Admin\Employee
@endphp

@push('css')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet"
          href="{{ asset('admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endpush

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title float-lg-left d-lg-flex align-items-lg-center">
                        {{ $title }}
                    </h3>
                    <a href="{{ route('employees.create') }}" class="btn btn-info btn-sm float-lg-right"> Add New
                        Employee Info</a>

                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>Serial</th>
                            <th>Employee ID</th>
                            <th>Employee Name</th>
                            <th>Employee Email</th>
                            <th>Employee Designation</th>
                            <th>Employee Department</th>
                            <th>Employee Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if($employees->count() > 0)
                            @foreach($employees as $key =>$employee)
                                <tr>
                                    <td>{{ ++$key }}</td>
                                    <td> {{ $employee->employee_id }}
                                    </td>
                                    <td>{{ $employee->employee_name }}</td>
                                    <td>{{ $employee->employee_email }}</td>
                                    <td>{{ $employee->employee_designation }}</td>
                                    <td>{{ $employee->employee_department }}</td>
                                    <td class="d-lg-flex justify-content-lg-center">
                                        <span
                                            class="badge {{ ($employee->employee_status == Employee::STATUS_ACTIVE) ? 'badge-success' : 'badge-danger' }}">{{ Employee::STATUS[$employee->employee_status] }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('employees.edit', $employee->id) }}"
                                           class="btn btn-info btn-sm"><i class="fas fa-edit nav-icon"></i>Edit</a>
                                        {{--                                        <form action="{{ route('employees.destroy') }}" method="post"--}}
                                        {{--                                              style="display:inline">--}}
                                        {{--                                            @csrf--}}
                                        {{--                                            @method('delete')--}}
                                        {{--                                            <input type="hidden" name="id" value="{{ $employee->id }}">--}}
                                        {{--                                            <button title="Delete" type="submit" class="btn btn-danger btn-sm"--}}
                                        {{--                                                    data-confirm-delete="true">--}}
                                        {{--                                                <i class="fa fa-trash-alt nav-icon"></i>--}}
                                        {{--                                                Delete--}}
                                        {{--                                            </button>--}}
                                        {{--                                        </form>--}}
                                        <a href="{{ route('employees.destroy', $employee->id) }}"
                                           class="btn btn-danger btn-sm" data-confirm-delete="true"><i
                                                class="fa fa-trash-alt nav-icon"></i>Delete</a>


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

    <!-- Page specific script -->
    <script>
        $(function () {
            $("#example1").DataTable({
                "responsive": true, "lengthChange": false, "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        });
    </script>
@endpush

{{--select b.user_id,b.month_name,sum(1) days_attended from(--}}
{{--select a.user_id,event_date, DATEPART(mm,event_date) month_name from (--}}
{{--select user_id, cast(event_time as date)  event_date from auth_logs_202310) a--}}
{{--group by user_id ,event_date--}}
{{--) b  group by user_id,month_name--}}
