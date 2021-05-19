<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'locked_flg',
        'error_count',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
      
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        // 'email_verified_at' => 'datetime',
    ];


    /**
     * 
     * Emailがマッチしたユーザーを返す
     * @param string $email
     * @return object
     * 
     */

     public function getUserByEmail($email)
{

    return User::where('email','=',$email)->first();

}

/**
 * 
 * アカウントロックがされているか
 * @param object $user
 * @return bool
 */

 public function isAccountLocked($user){


     if($user->locked_flg===1){
         return true;
     }
     return false;



 }

/**
 * エラ〜カウントをリセットする
 * @param object $user
 * 
 * 
 * 
 */

public function resetErrorCount($user)
{

    if($user->error_count>0){
        $user->error_count=0;
        $user->save();
    }
}


/**
 * 
 * エラーカウントを増やす
 * @param int $error_count
 * @return int
 */


 public function addErrorCount($error_count)
 {
return $error_count + 1;


 }
 /**
  * @param object $user
  *@return bool
  */
  public function lockAccount($user)
{

    if($user->error_count > 5){

        $user->locked_flg= 1;
        return $user->save();
}
return false;

}


}



