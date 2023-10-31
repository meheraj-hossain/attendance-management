<?php

namespace App\Services;

use App\Models\Admin\Employee;

/**
 * Class EmployeeService.
 */
class EmployeeService
{
    public function setInformation($request)
    {
        try {
            $employee = Employee::where('id', $request->id)->first();
            if (empty($employee)) {
                $employee = new Employee();
            } else {
                $employee->employee_status = $request->employee_status;
            }

            $employee->employee_id          = $request->employee_id;
            $employee->employee_name        = $request->employee_name;
            $employee->employee_email       = $request->employee_email;
            $employee->employee_designation = $request->employee_designation;
            $employee->employee_department  = $request->employee_department;
            $employee->save();
        } catch (\exception $exception) {
            throw new \Exception($exception->getMessage());
        }
    }
}
