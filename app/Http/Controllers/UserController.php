<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class UserController extends Controller
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index()
    {
        $data['title'] = 'User Information';
        $data['users'] = User::where('role', User::ROLE_ADMIN)->orderBy('id', 'desc')->get();
        $title         = 'Delete User!';
        $text          = "Are you sure you want to delete?";
        confirmDelete($title, $text);
        return view('admin.layouts.users.index', $data);
    }

    public function create()
    {
        $data['title'] = 'Add New User Information';
        return view('admin.layouts.users.create', $data);
    }

    public function store(StoreUserRequest $request)
    {
        $request->validated();

        try {
            $this->userService->setInformation($request);
            Alert::success('Congrats!', 'User Information Stored Successfully');
            return redirect()->route('users.index');
        } catch (\Exception $exception) {
            Alert::error('Error!', 'User Information Not Stored Successfully');
            return redirect()->back();
        }
    }

    public function edit(string $id)
    {
        $data['title'] = 'Edit User Information';
        $data['user']  = User::findOrFail($id);

        return view('admin.layouts.users.edit', $data);
    }

    public function update(UpdateUserRequest $request)
    {
        $request->validated();

        try {
            $this->userService->setInformation($request);
            Alert::success('Congrats!', 'User Information Updated Successfully');
            return redirect()->route('users.index');
        } catch (\Exception $exception) {
            Alert::error('Error!', 'User Information Not Updated Successfully');
            return redirect()->back();
        }
    }

    public function destroy(string $id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();
            Alert::success('Congrats!', 'User Information Deleted Successfully');
            return redirect()->route('users.index');
        } catch (\Exception $exception) {
            Alert::error('Error!', 'User Information Not Deleted Successfully');
            return redirect()->back();
        }
    }
}
