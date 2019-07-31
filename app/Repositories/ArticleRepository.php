<?php
/**
 * ArticleRepository中的 Function只作用在http://localhost:xxxx/article
 * 
 */
namespace App\Repositories;

use App\Models\Article;

class ArticleRepository
{
    /** @var Article */
    protected $article;

    public function __construct(Article $article){
        $this->article = $article;
    }

    /**
     * 取得所有討論資料
     *
     * @return App\Models\Article
     */
    public function getArticle(){
        return $this->article->with('user', 'reply.user')->get();
    }

    /**
     * 回傳最新的5個討論
     * 
     * @return App\Models\Article的最新5筆資料
     */
    public function getArticleDesc($card_id){
        return $this->article
                ->with('user', 'reply.user')
                // ->where('card_id', '=', $card_id)
                // ->orderby('id', 'desc')
                // ->limit(5)    
                ->get(); 
    }

    /**
     * 回傳最新的1個討論
     * 
     * @return App\Models\Article的最新1筆資料
     */
    public function getLatestArticle($card_id){
        return $this->article
                ->with('user', 'reply.user')
                // ->where('card_id', '=', $card_id)
                ->orderby('id', 'desc')
                ->first(); 
                // ->get();
    }

    /**
     * 新增討論內容和標題
     * 
     * 
     */
    public function addArticle($data){
        $user_name = $data['user_name'];
        $user_id = $this->userService->getIdByName($user_name);
        $title = $data['title'];
        $content = $data['content'];
        
        $article = new Article;
        //'user_id', 'title', 'content', 'card_id'
        $article['user_id'] = $user_id;
        $article['title'] = $title;
        $article['content'] = $content;
        // $article['card_id'] = $card_id;
        $article->save();
    }


    /**
     * 尋找特定文章
     * 
     */
    public function editArticle($id){
        return $this->article
                ->find($id);
    }

    /**
     * 修改討論內容和標題
     * 
     * 
     */
    public function updateArticle($id, $title, $content){
        $article = $this->article->find($id);
        $article['title'] = $title;
        $article['content'] = $content;
        $article->save();
    }

    /**
     * 刪除討論串
     * 
     * 
     */
    public function delArticle($id){
        $this->article->find($id)->delete();
    }
   
}