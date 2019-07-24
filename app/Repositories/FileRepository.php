<?php
/**
 * 
 * 
 */
namespace App\Repositories;

use App\Models\File;

class FileRepository
{
    /** @var File */
    protected $file;

    public function __construct(File $file){
        $this->file = $file;
    }

    public function getAllFileByUserName($user_name){
        return $this->file
            ->where('user_name', '=', $user_name)
            ->orderBy('id', 'desc')
            ->get();
    }

    public function getLatestFileUrlByUserName($user_name){
        $file_url = $this->file
                ->where('user_name', '=', $user_name)
                ->first();
                
        return $file_url;
    }

    public function insertFile($data){
        $user_name = $data['user_name'];
        $image_name = $data['img_name'];
        $image_size = $data['img_size'];
        $image_url = 'img/user/' . $user_name . '/' . $image_name;
        $img_data['user_name'] = $user_name;
        $img_data['file_name'] = $image_name;
        $img_data['download_link'] = $image_url;
        $img_data['file_size'] = $image_size;
        
        $data['image']->move(public_path('img/user/' . $user_name . '/'), $image_name);

        $this->file->create($img_data);

        return true;
    }

    public function updateFile($id, $file){
        $this->user->find($id)->update($file);
    }

    public function deleteUser($id){
        $this->user->destroy($id);
    }

    public function checkFileNotExist($user_name, $img_name){
        $fileinfo = $this->file
                ->where('user_name', '=', $user_name)
                ->where('file_name', '=', $img_name)
                ->first();
        if(empty($fileinfo))
            return true;
        else
            return false;
        
    }

}