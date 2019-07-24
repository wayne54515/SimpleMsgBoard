<?php

namespace App\Models;

use App\Models\BaseModel;

class Avatar extends BaseModel
{

    protected $fillable = [
        'user_name', 'avatar_name', 'url', 'img_size', 'img_type', 'created_at'
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