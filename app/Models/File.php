<?php

namespace App\Models;

use App\Models\BaseModel;

class File extends BaseModel
{

    protected $fillable = [
        
    ];

    protected $hidden = [
        
    ];

    // /**
    //  * 取得 模型
    //  * 
    //  * @return App\Models\Reply
    //  */
    // public function reply(){
    //     return $this->hasMany('App\Models\Reply', 'article_id');
    // }

    // /**
    //  * 取得 使用者 模型 一對一關聯
    //  *
    //  * @return App\Models\User
    //  */
    // public function user(){
    //     return $this->hasOne('App\Models\User', 'id', 'user_id');
    // }

}