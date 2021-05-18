<?php

namespace App\Http\Controllers\Auth;
use App\Http\Requests\LoginFormRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
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
        $credentials=$request->only('email','password');


        $user=User::where('email','=',$credentials['email'])->first();
        if (!is_null($user)){
            
            if($user->locked_flg===1){
                
                
                return back()->withErrors([
                    'danger' => 'アカウントがロックされています。',
                    ]);
                
                

            }
            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();
                

                if($user->error_count > 0){
                //成功したらエラーカウント０にする
                    $user->error_count=0;
                  $user->save();
                }
                return redirect()->route('home')->with('success','ログイン成功しました！');
            }
            //ログインnに失敗したらエラーカウントを１増やす
            $user->error_count=$user->error_count+1;

            //エラーカウントが６以上の場合はアカウントロックする
            if($user->error_count > 5){

                $user->locked_flg=1;
                $user->save();
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
