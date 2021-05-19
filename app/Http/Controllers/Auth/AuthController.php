<?php

namespace App\Http\Controllers\Auth;
use App\Http\Requests\LoginFormRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
class AuthController extends Controller
{

    public function __construct(User $user)
    {
        $this->user=$user;

    }
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
        $credentials=$request->only('email','password');
  

        $user=$this->user->getUserByEmail($credentials['email']);
        if (!is_null($user)){
            
            if($this->user->isAccountLocked($user))
            {
                 return back()->withErrors([
                'danger' => 'アカウントがロックされています。'
                    ]);
                
                

            }
            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();
                $this->user->resetErrorCount($user);

               
                return redirect()->route('home')->with('success','ログイン成功しました！');
            }
            //ログインnに失敗したらエラーカウントを１増やす
            $user->error_count= $this->user->addErrorCount($user->error_count);
            //エラーカウントが６以上の場合はアカウントロックする
            if($this->user->lockAccount($user)){

                return back()->withErrors([
                    'danger' => 'アカウントがロックさ荒れました。解除したい場合は運営者に連絡してください。',
                    ]);
            }
            $user->save();
            
        }

        return back()->withErrors([
            'danger' => 'メールアドレスか、パスワードが間違っています',
            ]);
    }
    /**
     * ユーザーをアプリケーションからログアウトさせる
     * @param \Illminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * 
     * 
     */

     public function logout(Request $request)
     {
         Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login.show')->with("logout",'ログアウトしました');
        }
}
