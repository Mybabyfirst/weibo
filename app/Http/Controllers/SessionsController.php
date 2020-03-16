<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
class SessionsController extends Controller
{
    public function __construct()
    {
        //guest 指定未登录用户 只能访问create方法
        $this->middleware('guest', [
            'only' => ['create']
        ]);
    }

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
        if (Auth::attempt($credentials,$request->has('remember'))) {
            if(Auth::user()->activated){
                // 登录成功后的相关操作
                session()->flash('success',"欢迎回来");
                $fallback = route('users.show', Auth::user());
                return redirect()->intended($fallback);
                //Auth::user() 获取当前用户
                //intended(),该方法将重新定向到上一次访问的页面,并接
                //收一个默认跳转地址的参数,如果上一次记录为空着跳转到默认地址上
            }else{
               Auth::logout();
               session()->flash('warning',"你的账号未激活，请检查邮箱中的注册邮件进行激活。");
               return redirect('/');
            }

        } else {
            // 登录失败后的相关操作
            session()->flash('danger',"邮箱或者密码错误");
            return redirect()->back()->withInput();
            //withInput() 是使模板里的 {{old()}}能获取到上一次的值
        }
    }
    public function destroy()
    {
        Auth::logout();
        session()->flash('success','退出成功');
        return redirect('login');
    }
}
