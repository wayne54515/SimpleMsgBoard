<?php
/**
 * 
 * 
 */
namespace App\Repositories;

use App\Models\File;
use App\Models\Avatar;

class FileRepository
{
    /** @var File */
    protected $file;

    protected $avatar;

    public function __construct(File $file, Avatar $avatar){
        $this->file = $file;
        $this->avatar = $avatar;
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
        $file_name = $data['file_name'];
        $file_size = $data['file_size'];
        $file_url = 'user_file/' . $user_name . '/' . $file_name;
        $file_type = $data['img_type'];
        $file_data['user_name'] = $user_name;
        $file_data['file_name'] = $file_name;
        $file_data['download_link'] = $file_url;
        $file_data['file_size'] = $file_size;
        $file_data['file_type'] = $file_type;
        
        $data['file']->move(public_path('user_file/' . $user_name . '/'), $file_name);

        $this->file->create($file_data);

        return true;
    }

    public function insertAvatar($data){
        $user_name = $data['user_name'];
        $image_name = $data['img_name'];
        $image_size = $data['img_size'];
        $image_url = 'img/user/' . $user_name . '/' . $image_name;
        $image_type = $data['img_type'];
        $pre_url = $data['pre_url'];
        $img_data['user_name'] = $user_name;
        $img_data['avatar_name'] = $image_name;
        $img_data['url'] = $image_url;
        $img_data['img_size'] = $image_size;
        $img_data['img_type'] = $image_type;

        if(file_exists(public_path($pre_url)))
            unlink(public_path($pre_url));
        
        $data['image']->move(public_path('img/user/' . $user_name . '/'), $image_name);

        $this->avatar->create($img_data);

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