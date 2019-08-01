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
    public function getArticleById($article_id){
        return $this->article
                ->with('user', 'reply.user')
                ->where('id', '=', $article_id)   
                ->get(); 
    }

    /**
     * 回傳最新的1個討論
     * 
     * @return App\Models\Article的最新1筆資料
     */
    public function getLatestArticle(){
        return $this->article
                ->with('user', 'reply.user')
                ->orderby('id', 'desc')
                ->first(); 
    }

    /**
     * 新增討論內容和標題
     * 
     * 
     */
    public function addArticle($data){
        $this->article->create($data);
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
    public function updateArticle($id, $data){
        $this->article->find($id)->update($data);
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