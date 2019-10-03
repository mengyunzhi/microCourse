<?php
namespace app\index\model;
use think\Model;



class Admin extends Model
{

    // 管理员登录验证————赵凯强
    static public function login($num, $password)
    {
        // 验证用户是否存在
        $map = array('num' => $num);
        $Admin = self::get($map);

        if (!is_null($Admin)) {
            // 验证密码是否正确
            if ($Admin->checkPassword($password)) {
                session('adminId', $Admin->getData('id'));
                return true;
            }
        }

        return false;
    }
    
    // 管理员登录密码验证————赵凯强
    public function checkPassword($password)
    {
        if ($this->getData('password') === $password)
        {
            return true;
        } else {
            return false;
        }
    }
    
    // 管理员注销————赵凯强
    static public function logOut()
    {
        session('adminId', null);
        return true;
    }
    
    // 管理员操作验证————赵凯强
    static public function isLogin()
    {
        $adminId = session('adminId');

        if (isset($adminId)) {
            return true;
        } else {
            return false;
        }
    }
}