<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class SessionsController extends Controller
{
    public function create()
    {
        return view('sessions.create');
    }

    public function store(Request $request)
    {
        $credentials = $this->validate($request, [
            'email' => 'required|email|max:255',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            session()->flash('success','亲，欢迎回家！');
            return redirect()->route('users.show',[Auth::user()]); // Auth::user()当前登录用户信息传递show

        } else {
            session()->flash('danger', '很抱歉，您的邮箱和密码不匹配'); // 闪存展示失败
            return redirect()->back()->withInput();
        }

        return;
    }
}
