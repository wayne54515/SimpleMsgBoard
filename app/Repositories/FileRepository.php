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

    public function getAllFileByName(){
        return $this->user->all();
    }

    public function getLatestFileByName($name){
        $userinfo = $this->user
                ->where('name', '=', $name)
                ->first();
                
        return $userinfo;
    }

    public function insertFile($file_data){
        $this->user->create($file_data);
    }

    public function updateFile($id, $file){
        $this->user->find($id)->update($file);
    }

    public function deleteUser($id){
        $this->user->destroy($id);
    }

}