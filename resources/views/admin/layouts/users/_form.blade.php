@php
    use App\Models\User;
@endphp

@if(isset($user))
    <input type="hidden" name="id" value="{{ $user->id }}">
@endif

<div class="form-group">
    <label for="name">User Name</label>
    <span class=" badge badge-danger">Required</span>
    <input type="text" name="name"
           value="{{ old('name', isset($user) ? $user->name : null )}}" class="form-control"
           id="name" placeholder="Enter User Name">
    @error('name')
    <div class="alert alert-danger mt-1">{{ $message }}</div>
    @enderror
</div>

<div class="form-group">
    <label for="name">User Email</label>
    <span class=" badge badge-danger">Required</span>
    <input type="email" name="email"
           value="{{ old('email', isset($user) ? $user->email : null )}}" class="form-control"
           id="email" placeholder="Enter User Email">
    @error('email')
    <div class="alert alert-danger mt-1">{{ $message }}</div>
    @enderror
</div>

@if(isset($user))
    <div class="form-group">
        <label for="role">User Role</label>
        <span class=" badge badge-danger">Required</span>
        <br>
        <input type="radio" name="role"
               @if(old( 'role', isset($user) ? $user->role : null ) == User::ROLE_ADMIN) checked
               @endif value="{{ User::ROLE_ADMIN }}" id="admin">
        <label for="admin">Admin</label>
        <br>
        <input type="radio" name="role"
               @if(old( 'role',isset($user) ? $user->role : null ) == User::ROLE_SUPER_ADMIN) checked
               @endif value="{{ User::ROLE_SUPER_ADMIN }}" id="super_admin">
        <label for="super_admin">Super Admin</label>
        @error('role')
        <div class="alert alert-danger">{{ $message }}</div>
        @enderror
    </div>
@endif

@if(!isset($user))
    <div class="form-group">
        <label for="name">Password</label>
        <span class=" badge badge-danger">Required</span>
        <input type="password" name="password"
               value="" class="form-control"
               id="name" placeholder="Enter a Password">
        @error('password')
        <div class="alert alert-danger mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group">
        <label for="name">Confirm Password</label>
        <span class=" badge badge-danger">Required</span>
        <input type="password" name="password_confirmation"
               value="" class="form-control"
               id="name" placeholder="Enter a Password">
        @error('password_confirmation')
        <div class="alert alert-danger mt-1">{{ $message }}</div>
        @enderror
    </div>
@endif

@if(isset($user))
    <div class="form-group">
        <label for="status">User Status</label>
        <span class=" badge badge-danger">Required</span>
        <br>
        <input type="radio" name="status"
               @if(old( 'status', isset($user) ? $user->status : null ) == User::STATUS_ACTIVE) checked
               @endif value="{{ User::STATUS_ACTIVE }}" id="active">
        <label for="active">Active</label>
        <br>
        <input type="radio" name="status"
               @if(old( 'status',isset($user) ? $user->status : null ) == User::STATUS_INACTIVE) checked
               @endif value="{{ User::STATUS_INACTIVE }}" id="inactive">
        <label for="inactive">Inactive</label>
        @error('status')
        <div class="alert alert-danger">{{ $message }}</div>
        @enderror
    </div>
@endif
