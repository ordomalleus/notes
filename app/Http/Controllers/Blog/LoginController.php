<?php

namespace App\Http\Controllers\Blog;

use Auth;
use Session;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class LoginController extends Controller
{

    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Показ формы аутонтификации
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function login()
    {
        return view('blog.login.login');
    }

    /**
     * Аутонтификация пользователя в системе
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function loginUser(Request $request)
    {
        $email = $request->input(['email']);
        $password = $request->input(['password']);

        if (Auth::attempt(['email' => $email, 'password' => $password])){
            return redirect()->intended('/');
        }
        //Передаем флаг bad если не прошли аутонтификацию
        return redirect()->back()->with(['bad' => true]);

    }

    /**
     * Выход из системы
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function logout()
    {
        Auth::logout();
        Session::flush();
        return redirect('/');
    }
}
