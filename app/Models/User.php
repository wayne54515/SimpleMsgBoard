<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'type', 'color', 'sex', 'created_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token' ,'updated_at'
    ];

    /**
     * 取得 模型
     * 
     * @return App\Models\File
     */
    public function file(){
        return $this->hasMany('App\Models\file', 'user_name', 'name');
    }

    public function avatar(){
        return $this->hasOne('App\Models\avatar', 'user_name', 'name')->orderBy('id', 'desc');
    }

}
