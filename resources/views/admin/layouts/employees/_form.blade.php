@php
    use \App\Models\Admin\Employee;
@endphp

@if(isset($employee))
    <input type="hidden" name="id" value="{{ $employee->id }}">
@endif

<div class="form-group">
    <label for="name">Employee ID</label>
    <span class=" badge badge-danger">Required</span>
    <input type="number" name="employee_id"
           value="{{ old('employee_id', isset($employee) ? $employee->employee_id : null )}}" class="form-control"
           id="employee_id" placeholder="Enter Employee ID">
    @error('employee_id')
    <div class="alert alert-danger mt-1">{{ $message }}</div>
    @enderror
</div>

<div class="form-group">
    <label for="name">Employee Name</label>
    <span class=" badge badge-danger">Required</span>
    <input type="text" name="employee_name"
           value="{{ old('employee_name', isset($employee) ? $employee->employee_name : null )}}" class="form-control"
           id="employee_name" placeholder="Enter Employee Name">
    @error('employee_name')
    <div class="alert alert-danger mt-1">{{ $message }}</div>
    @enderror
</div>

<div class="form-group">
    <label for="name">Employee Email</label>
    <input type="email" name="employee_email"
           value="{{ old('employee_email', isset($employee) ? $employee->employee_email : null )}}" class="form-control"
           id="employee_email" placeholder="Enter Employee Email">
    @error('employee_email')
    <div class="alert alert-danger mt-1">{{ $message }}</div>
    @enderror
</div>

<div class="form-group">
    <label for="name">Employee Designation</label>
    <input type="text" name="employee_designation"
           value="{{ old('employee_designation', isset($employee) ? $employee->employee_designation : null )}}"
           class="form-control"
           id="employee_name" placeholder="Enter Employee Designation">
    @error('employee_designation')
    <div class="alert alert-danger mt-1">{{ $message }}</div>
    @enderror
</div>

<div class="form-group">
    <label for="name">Employee Department</label>
    <input type="text" name="employee_department"
           value="{{ old('employee_department', isset($employee) ? $employee->employee_department : null )}}"
           class="form-control"
           id="employee_name" placeholder="Enter Employee Department">
    @error('employee_department')
    <div class="alert alert-danger mt-1">{{ $message }}</div>
    @enderror
</div>

@if(isset($employee))
    <div class="form-group">
        <label for="status">Employee Status</label>
        <span class=" badge badge-danger">Required</span>
        <br>
        <input type="radio" name="employee_status"
               @if(old( 'employee_status', isset($employee) ? $employee->employee_status : null ) == Employee::STATUS_ACTIVE) checked
               @endif value="{{ Employee::STATUS_ACTIVE }}" id="active">
        <label for="active">Active</label>
        <br>
        <input type="radio" name="employee_status"
               @if(old( 'employee_status',isset($employee) ? $employee->employee_status : null ) == Employee::STATUS_INACTIVE) checked
               @endif value="{{ Employee::STATUS_INACTIVE }}" id="inactive">
        <label for="inactive">Inactive</label>
        @error('employee_status')
        <div class="alert alert-danger">{{ $message }}</div>
        @enderror
    </div>
@endif
