@extends('admin.templates.master')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card" style="height: 200px">
                <div class="card-header">
                    <h3 class="card-title float-lg-left d-lg-flex align-items-lg-center">
                        {{ $title }}
                    </h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body d-flex justify-content-center align-items-center">
                    <h3 class="text-danger">
                        You are not authorized here!
                    </h3>
                </div>
{{--                    <table id="example1" class="table table-bordered table-striped">--}}
{{--                        <thead>--}}
{{--                        <tr>--}}
{{--                            <th>Serial</th>--}}
{{--                            <th>Name</th>--}}
{{--                            <th>Email</th>--}}
{{--                            <th class="text-center">Role</th>--}}
{{--                            <th class="text-center">Status</th>--}}
{{--                            <th class="text-center">Action</th>--}}
{{--                        </tr>--}}
{{--                        </thead>--}}
{{--                        <tbody>--}}
{{--                        @if($users->count() > 0)--}}
{{--                            @foreach($users as $key =>$user)--}}
{{--                                <tr>--}}
{{--                                    <td>{{ ++$key }}</td>--}}
{{--                                    <td> {{ $user->name }}</td>--}}
{{--                                    <td>{{ $user->email }}</td>--}}
{{--                                    <td class="text-center">--}}
{{--                                        <span--}}
{{--                                            class="badge badge-info">{{ User::ROLE[$user->role] }}--}}
{{--                                        </span>--}}
{{--                                    </td>--}}

{{--                                    <td class="d-lg-flex justify-content-lg-center">--}}
{{--                                        <span--}}
{{--                                            class="badge {{ ($user->status == User::STATUS_ACTIVE) ? 'badge-success' : 'badge-danger' }}">{{ User::STATUS[$user->status] }}--}}
{{--                                        </span>--}}
{{--                                    </td>--}}


{{--                                    <td class="text-center">--}}
{{--                                        <a href="{{ route('users.edit', $user->id) }}"--}}
{{--                                           class="btn btn-info btn-sm"><i class="fas fa-edit nav-icon"></i>Edit</a>--}}
{{--                                        <a href="{{ route('users.destroy', $user->id) }}"--}}
{{--                                           class="btn btn-danger btn-sm" data-confirm-delete="true"><i--}}
{{--                                                class="fa fa-trash-alt nav-icon"></i>Delete</a>--}}
{{--                                    </td>--}}
{{--                                </tr>--}}
{{--                            @endforeach--}}
{{--                        @endif--}}
{{--                        </tbody>--}}
{{--                    </table>--}}
{{--                </div>--}}
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
@endsection
