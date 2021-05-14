<?php

namespace App\Http\Controllers\Auth;
use App\Http\Requests\LoginFormRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{

    /**
     * @return View
     */
    public function showLogin()
    {
            return view('login.login_form');
    }
    /**
     * @params App\Http\Requests\LoginFormRequest;
     * $request
     */
    public function login(LoginFormRequest $request)
    {
        dd($request->all());
    }
}
