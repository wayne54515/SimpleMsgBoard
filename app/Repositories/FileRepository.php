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
        $file_size = $this->getFileSize((int)$data['file_size']);
        $file_url = 'user_file/' . $user_name . '/' . $file_name;
        $file_type = $data['file_type'];
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
        $image_size = $this->getFileSize((int)$data['img_size']);
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

    public function rename($data){
        // $this->user->find($id)->update($file);
        $old_data = $data['old_data'];
        $user_name = $old_data['user_name'];
        $new_name = $data['new_name'];
        $file_url = 'user_file/' . $user_name . '/' . $new_name;
        $pre_url = $old_data['download_link'];

        $file_data['file_name'] = $new_name;
        $file_data['download_link'] = $file_url;

        // unlink(public_path($pre_url));
        
        // move(public_path($pre_url), public_path($file_url));

        rename(public_path($pre_url), public_path($file_url));
        

        $this->file
            ->where('user_name', '=', $user_name)
            ->where('download_link', '=', $pre_url)
            ->update($file_data);

        return "success";
    }

    public function deleteFile($id){
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

    function getFileSize($num){
        $p = 0;
        $format='bytes';
        if($num>0 && $num<1024){
            $p = 0;
            return number_format($num).' '.$format;
        }
        if($num>=1024 && $num<pow(1024, 2)){
            $p = 1;
            $format = 'KB';
        }
        if ($num>=pow(1024, 2) && $num<pow(1024, 3)) {
            $p = 2;
            $format = 'MB';
        }
        if ($num>=pow(1024, 3) && $num<pow(1024, 4)) {
            $p = 3;
            $format = 'GB';
        }
        if ($num>=pow(1024, 4) && $num<pow(1024, 5)) {
            $p = 3;
            $format = 'TB';
        }
        $num /= pow(1024, $p);
        return number_format($num, 2).' '.$format;
    }

}