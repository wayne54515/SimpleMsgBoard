<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Repositories\ReplyRepository;

class ReplyController extends Controller
{
    protected $replyRepository;

    /**
     * 建構子
     */
    public function __construct(ReplyRepository $replyRepository){
        $this->replyRepository = $replyRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $reply = $this->replyRepository->getReply();
        return response()->json(['reply' => $reply],
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
        $this->replyRepository->addReply($data['reply']);
        
        $reply = $this->replyRepository->getLatestReply($data['reply']['article_id']);
        return response()->json(['reply' => $reply],
            200, $this->header);
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $reply = $this->replyRepository->getReply();
        return response()->json(['reply' => $reply], 200, $this->header);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $reply = $this->replyRepository->editReply($id);
        return response()->json(['reply' => $reply], 200);
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
        $this->replyRepository->updateReply($id, $data['reply']);
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
        $this->replyRepository->delReply($id);
        return response()->json(['status' => 'OK'], 200, $this->header);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyByArticleId($id)
    {
        $this->replyRepository->delAllReply($id);
        return response()->json(['status' => 'OK'], 200, $this->header);
    }
}
