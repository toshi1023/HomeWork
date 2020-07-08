<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    // usersテーブルと1対多のリレーション構築(1側の設定)
    public function users()
    {
        return $this->hasMany('App\Models\User');
    }
}
