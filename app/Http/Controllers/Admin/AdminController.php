<?php

namespace App\Http\Controllers\Admin;

use App\Admin;
use App\Facades\Logger;
use App\Notifications\AdminWelcomeNotification;
use App\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $role_id = auth()->user()->role_id;
        return view('admin.admins')->with([
            'admins' => Admin::with('role')->where('role_id', '>', $role_id)->get(),
            'roles' => Role::where('id', '>', $role_id)->get()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'role_id' => 'required|exists:roles,id'
        ]);

        $password = Str::random(8);
        try {
            $admin = Admin::create([
                'name' => $request->name,
                'email' => $request->email,
                'role_id' => $request->role_id,
                'password' => $password,
            ]);

            $admin->notify(new AdminWelcomeNotification($password));
            return response()->json(['data' => $admin->loadmissing('role'), 'message' => 'Admin has been created successfully'], 201);
        } catch (\Exception $e) {
            Logger::debug($e);

            return response()->json(['message' => 'Error: please report to your administrator'], 500);
        }
    }

    public function update(Admin $admin, Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'role_id' => 'required|exists:roles,id'
        ]);

        try {
            $admin->update([
                'name' => $request->name,
                'email' => $request->email,
                'role_id' => $request->role_id,
            ]);

            return response()->json(['data' => $admin->loadmissing('role'), 'message' => 'Admin has been updated!'], 200);
        } catch (\Exception $e) {
            Logger::debug($e);

            return response()->json(['message' => 'Error: please report to your administrator'], 500);
        }
    }

    public function destroy(Admin $admin)
    {
        try {
            $admin->delete();

            return response()->json(['data' => $admin->loadmissing('role'), 'message' => 'Admin has been deleted!'], 200);
        } catch (\Exception $e) {
            Logger::debug($e);

            return response()->json(['message' => 'Error: please report to your administrator'], 500);
        }
    }
}
