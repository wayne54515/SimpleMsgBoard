<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Repositories\ArticleRepository;

class ArticleController extends Controller
{
    protected $articleRepository;
    // protected $userService;

    /**
     * 建構子
     */
    public function __construct(ArticleRepository $articleRepository){
        $this->articleRepository = $articleRepository;
    }

    /**
     * get latest article
     */
    public function latest($id)
    {
        $article = $this->articleRepository->getLatestArticle($id);
        return response()->json(['article' => $article],
            200, $this->header);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $article = $this->articleRepository->getArticle();
        return response()->json(['article' => $article],
            200, $this->header);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        
        $this->articleRepository->addArticle($data['article']);
        
        $article = $this->articleRepository->getLatestArticle($data['article']);
        return response()->json(['article' => $article],
            200, $this->header);
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $article = $this->articleRepository->getArticleById($id);
        return response()->json(['article' => $article],
            200, $this->header);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $article = $this->articleRepository->editArticle($id);
        return response()->json(['article' => $article],
            200, $this->header);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        $this->articleRepository->updateArticle($id, $data['article']);
        return response()->json(['status' => 'OK'], 200, $this->header);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->articleRepository->delArticle($id);
        return response()->json(['status' => 'OK'], 200, $this->header);
    }
}
