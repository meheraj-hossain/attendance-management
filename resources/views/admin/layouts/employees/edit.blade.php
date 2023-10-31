@extends('admin.templates.master')

@section('content')
    <div class="row">
        <!-- left column -->
        <div class="offset-md-3 col-md-6">
            <!-- general form elements -->
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">{{$title}}</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form role="form" action="{{route('employees.update', $employee->id)}}" method="post"
                      enctype="multipart/form-data">
                    @csrf
                    @method('put')
                    <div class="card-body">
                        @include('admin.layouts.employees._form')
                    </div>
                    <!-- /.card-body -->

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
            <!-- /.card -->
        </div>
        <!--/.col (left) -->
    </div>
@endsection
