<?php

namespace App\Services;

use Hash;
use App\Repositories\UserRepository;

class UserService
{   
    /** @var UserRepository */
    protected $userRepo;

    public function __construct(UserRepository $userRepo){
        $this->userRepo = $userRepo;
    }

    /**
     * 取得 所有使用者
     *
     * @return void
     */
    public function getAllUser(){
        return $this->userRepo->getAllUser();
    }

    /**
     * 依據name 取得 使用者的ID
     *
     * @param string $name
     * @return int
     */
    public function getIdByName($name){
        $user_id = $this->userRepo->getIdByName($name)->toArray();

        return $user_id['id'];
    }

    public function getUserById($id){
        return $this->userRepo->getUserById($id);
    }

    public function insertUser($input_data){
        $user_data = $input_data['user'];
        $user_data['type'] = "normal";
        $user_data['password'] = Hash::make($user_data['password']);
        $user_data->forget('confirm');
        $this->userRepo->insertUser($user_data);
    }

    public function updateUser($id, $input_data){
        $user = $input_data['user'];
        $this->userRepo->updateUser($id, $user);
    }

    public function deleteUser($id){
        $this->userRepo->deleteUser($id);
    }
}