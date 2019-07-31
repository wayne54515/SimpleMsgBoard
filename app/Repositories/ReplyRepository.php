<?php
/**
 * 
 * 
 */
namespace App\Repositories;

use App\Models\Reply;

class ReplyRepository
{
    /** @var Reply */
    protected $reply;

    public function __construct(Reply $reply){
        $this->reply = $reply;
    }

    /**
     * 取得所有討論資料
     *
     * @return App\Models\Reply
     */
    public function getReply(){
        return $this->reply->with('user')->get();
    }

    /**
     * 回傳最新的1個回應
     * 
     * @return App\Models\Reply的最新1筆資料
     */
    public function getLatestReply($article_id){
        return $this->reply
                ->with('user')
                ->where('article_id', '=', $article_id)
                ->orderby('id', 'desc')
                ->first(); 
    }

    /**
     * 新增回應內容
     * 
     * 
     */
    public function addReply($data){
        $user_name = $data['user_name'];
        $user_id = $this->userService->getIdByName($user_name);
        $content = $data['content'];
        $article_id = $data['article_id'];

        $reply = new Reply;
        $reply['user_id'] = $user_id;
        $reply['content'] = $content;
        $reply['article_id'] = $article_id;
        $reply->save();
    }


    /**
     * 尋找特定回應
     * 
     */
    public function editReply($id){
        return $this->reply->find($id);
    }

    /**
     * 修改回應內容
     * 
     * 
     */
    public function updateReply($id, $content){
        $reply = $this->reply->find($id);
        $reply['content'] = $content;
        $reply->save();
    }

    /**
     * 刪除回應
     * 
     * 
     */
    public function delReply($id){
        $this->reply->find($id)->delete();
    }

    public function delAllReply($id){
        $this->reply->where('article_id', '=', $id)->delete();
    }
   
}