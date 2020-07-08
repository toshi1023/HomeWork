<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'last_name', 'first_name', 'email', 'password', 'company_id', 'profile_image', 'del_flg'
    ];

    //ブラックリスト方式(idは自動採番以外で入力できないように設定)
    protected $guarded = ['id'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    // companiesテーブルと1対多のリレーション構築(多側の設定)
    public function company()
    {
        return $this->belongsTo('App\Models\Company');
    }

}
