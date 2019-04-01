<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class SessionsController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest', [
            'only' => ['create']
        ]);
    }

    public function create()
    {
        return view('sessions.create');
    }

    public function store(Request $request) // 1. 验证登录的参数 2. 验证数据库 进入
    {
        $credentials = $this->validate($request, [
            'email' => 'required|email|max:255',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials, $request->has('remember'))) {
            if (Auth::user()->activated){
                session()->flash('success', '亲，欢迎回家！');
                $fallback = route('users.show', [Auth::user()]);// Auth::user()当前登录用户信息传递show
                return redirect()->intended($fallback); // intended 自动引导访问位置

            }else{
                Auth::logout();
                session()->flash('warring','你的账号未激活，请检查注册邮箱进行激活');
                return redirect('/');
            }

        } else {
            session()->flash('danger', '很抱歉，您的邮箱和密码不匹配'); // 闪存展示失败
            return redirect()->back()->withInput();
        }

        return;
    }

    public function destroy()
    {
        Auth::logout();
        session()->flash('success', '您已成功退出！');
        return redirect('login');
    }
}