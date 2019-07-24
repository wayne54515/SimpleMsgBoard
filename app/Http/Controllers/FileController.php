<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Repositories\FileRepository;

class FileController extends Controller
{

    /** @var FileRepository */
    protected $fileRepository;

    /**
     * 建構子
     */
    public function __construct(FileRepository $fileRepository){
        $this->fileRepository = $fileRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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

        if($this->fileRepository->checkFileNotExist($data['user_name'], $data['file_name'])){
            $result = $this->fileRepository->insertFile($data);
        }
        else
            $result = false;
        // $user_name = $data['user_name'];
        // $image_name = $data['img_name'];
        // $data['image']->move(public_path('img/user/' . $user_name . '/'), $image_name);

        return response()->json(['status'=> $result], 200, $this->header);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeAvatar(Request $request)
    {
        $data = $request->all();


        $result = $this->fileRepository->insertAvatar($data);
        // $user_name = $data['user_name'];
        // $image_name = $data['img_name'];
        // $data['image']->move(public_path('img/user/' . $user_name . '/'), $image_name);

        return response()->json(['status'=> $result], 200, $this->header);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $data = $request->all();
        $this->fileRepository->rename($data);
        return response()->json(['status'=> $data], 200, $this->header);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
