<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;

class LogoutController extends Controller
{
    //
    public function logout(Request $request)
    {
        Auth::logout();
        return redirect("/auth/login");
    }
}
