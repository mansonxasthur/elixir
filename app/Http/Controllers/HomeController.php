<?php

namespace App\Http\Controllers;

use App\Facades\Logger;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    /**
     * Show the admin settings.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function show()
    {
        return view('settings')->with(['user' => auth()->user()]);
    }

    public function info(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|unique:admins,email,' . auth()->id(),
        ]);

        try {
            $user = auth()->user()->update($request->all());

            return response()->json(['data' => $user, 'message' => 'Info has been updated!'], 200);
        } catch (\Exception $e) {
            Logger::debug($e);

            return response()->json(['message' => 'Error: please report to your administrator'], 500);
        }
    }

    public function password(Request $request)
    {
        $request->validate([
            'password' => 'required|confirmed',
        ]);

        try {
            auth()->user()->update([
                'password' => bcrypt($request->password),
            ]);

            return response()->json(['message' => 'Password has been updated!'], 200);
        } catch (\Exception $e) {
            Logger::debug($e);

            return response()->json(['message' => 'Error: please report to your administrator'], 500);
        }
    }
}
