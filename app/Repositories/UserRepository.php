<?php
/**
 * 
 * 
 */
namespace App\Repositories;

use App\Models\User;
use Hash;

class UserRepository
{
    /** @var User */
    protected $user;

    public function __construct(User $user){
        $this->user = $user;
    }

    public function getAllUser(){
        return $this->user
            ->with('file')
            ->get();
    }

    /**
     * 依靠user_name取得user_id
     * 
     */
    public function getIdByName($name){
        $userinfo = $this->user
                ->where('name', '=', $name)
                ->first();
                
        return $userinfo;
    }

    public function getUserById($id){
        return $this->user->find($id);
    }

    public function insertUser($user_data){
        $this->user->create($user_data);
    }

    public function updateUser($id, $user){
        $this->user->find($id)->update($user);
    }

    public function deleteUser($id){
        $this->user->destroy($id);
    }

    public function checkEmailExist($email){
        $exist = $this->user
            ->where('email', '=', $email)
            ->count();

        return $exist ?true :false;
    }

    public function checkNameExist($name){
        $exist = $this->user
                ->where('name', '=', $name)
                ->count();

        return $exist ?true :false;
    }

    public function checkAccount($user_data){
        $exist = $this->user
                ->where('email', '=', $user_data['email'])
                ->first();

        if(!empty($exist)){
            if (Hash::check($user_data['password'], $exist['password'])) {
                $result['status'] = true;
                $result['user'] = $exist;
                $result['error'] = "";
                return $result;
            }
            else{
                $result['status'] = false;
                $result['user'] = null;
                $result['error'] = "密碼錯誤";
                return $result; 
            }
        }
        else{
            $result['status'] = false;
            $result['user'] = null;
            $result['error'] = "此信箱未註冊";
            return $result;
        }
        // return $exist;
    }
}