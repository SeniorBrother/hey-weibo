<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;


class UsersController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth', [ // 针对 未登录用户访问路由无需验证身份
            'except' => ['show', 'create', 'store', ' index', 'confirmEmail']
        ]);

        $this->middleware('guest', [ //针对 已登录用户 无法访问注册
            'only' => ['create']
        ]);
    }

    public function index()
    {
        $users = User::paginate(10);
        return view('users.index', compact('users'));

    }


    public function create() //注册
    {
        return view('users.create');
    }

    public function show(User $user) //登录用户展示个人信息
    {
        return view('users.show', compact('user'));
    }

    public function store(Request $request) // 表单验证注册新用户
    {
        $this->validate($request, [
            'name' => 'required | max:50',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|confirmed|min:6'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),

        ]);

        $this->sendEmailConfirmationTo($user);
        session()->flash('success', '验证邮件已发送到你的注册邮箱上，请注意查收。');
        return redirect('/');
    }

    public function edit(User $user)
    {
        $this->authorize('update', $user);
        return view('users.edit', compact('user'));
    }

    public function update(User $user, Request $request)
    {
        $this->authorize('update', $user);
        $this->validate($request, [
            'name' => 'required|max:50|',
            'password' => 'nullable|confirmed|min:6'
        ]);

        $data = [];
        $data['name'] = $request->name; //赋值数组

        if ($request->password) {
            $data['password'] = bcrypt($request->password); //密码改动就会加密传递
        }

        $user->update($data);

        session()->flash('success', 'nice，个人资料更新成功啦！');
        return redirect()->route('users.show', $user);
    }

    public function destroy(User $user)
    {
        $this->authorize('destroy', $user);
        $user->delete();
        session()->flash('success', '成功删除用户！');
        return back();
    }

    public function confirmEmai($token)
    {
        $user = User::where('activation_token', $token)->firstOrFail();

        $user->activated = true;
        $user->activation_token = null;
        $user->save();

        Auth::login($user);
        session()->flash('success', '共享您激活账号成功');
        return redirect()->route('user.show', ['user']);
    }


    protected function sendEmailConfirmationTo($user)
    {
        $view = 'emails.confirm';
        $data = compact('user');
        $to = $user->email;
        $subject = '感谢注册 Sunrise 应用！请确认你的邮箱';

        Mail::send($view, $data, function ($message) use ($to, $subject) {
            $message->to($to)->subject($subject);
        });
    }
}
