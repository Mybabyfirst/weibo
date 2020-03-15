<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
class SessionsController extends Controller
{
    public function create()
    {
        return view('sessions.create');
    }
    public function store(Request $request)
    {
        $credentials = $this->validate($request,[
            'email' => 'required|email|max:255',
            'password' => 'required'
        ]);
        if (Auth::attempt($credentials)) {
            // 登录成功后的相关操作
            session()->flash('success',"欢迎回来");
            return redirect()->route('users.show',[Auth::user()]);
            //Auth::user() 获取当前i用户
        } else {
            // 登录失败后的相关操作
            session()->flash('danger',"邮箱或者密码错误");
            return redirect()->back()->withInput();
            //withInput() 是使模板里的 {{old()}}能获取到上一次的值
        }
    }
}
