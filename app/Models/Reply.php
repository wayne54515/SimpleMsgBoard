<?php

namespace App\Models;

use App\Models\BaseModel;

class Reply extends BaseModel
{
    protected $table = 'reply';
    
    protected $fillable = [
        'user_name', 'content', 'article_id'
    ];

    protected $hidden = [
        
    ];

    /**
     * 回傳 回覆到討論 模型
     * 
     * @return App\Models\Article
     */
    // public function article(){
    //     return $this->belongsTo('App\Models\Article');
    // }

    /**
     * 取得 使用者 模型 一對一關聯
     *
     * @return App\Models\User
     */
    public function user(){
        return $this->hasOne('App\Models\User', 'name', 'user_name');
    }
}
