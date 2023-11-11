<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Models\Admin\Employee;
use App\Services\EmployeeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class EmployeeController extends Controller
{
    private $employeeService;

    public function __construct(EmployeeService $employeeService)
    {
        $this->employeeService = $employeeService;
    }

    public function index()
    {
        $data['title']     = 'Employee Information';
        $data['employees'] = DB::connection('odbc')->select("SELECT user_id, name, regist_at FROM users ORDER BY user_id ASC");
//        $title             = 'Delete Employee!';
//        $text              = "Are you sure you want to delete?";
//        confirmDelete($title, $text);
        return view('admin.layouts.employees.index', $data);
    }

//    public function create()
//    {
//        $data['title'] = 'Add New Employee Information';
//        return view('admin.layouts.employees.create', $data);
//    }
//
//    public function store(StoreEmployeeRequest $request)
//    {
//        $request->validated();
//
//        try {
//            $this->employeeService->setInformation($request);
//            Alert::success('Congrats!', 'Employee Information Stored Successfully');
//            return redirect()->route('employees.index');
//        } catch (\Exception $exception) {
//            Alert::error('Error!', 'Employee Information Not Stored Successfully');
//            return redirect()->back();
//        }
//    }
//
//    public function edit($id)
//    {
//        $data['title']    = 'Edit Employee Information';
//        $data['employee'] = Employee::findOrFail($id);
//
//        return view('admin.layouts.employees.edit', $data);
//    }
//
//    public function update(UpdateEmployeeRequest $request)
//    {
//        $request->validated();
//
//        try {
//            $this->employeeService->setInformation($request);
//            Alert::success('Congrats!', 'Employee Information Updated Successfully');
//            return redirect()->route('employees.index');
//        } catch (\Exception $exception) {
//            Alert::error('Error!', 'Employee Information Not Updated Successfully');
//            return redirect()->back();
//        }
//    }
//
//    public function destroy($id)
//    {
//        try {
//            $employee = Employee::findOrFail($id);
//            $employee->delete();
//
//            return redirect()->back();
//        } catch (\Exception $exception) {
//            Alert::error('Error!', 'Something Went Wrong. Contact With Developer');
//            return redirect()->back();
//        }
//    }
}
