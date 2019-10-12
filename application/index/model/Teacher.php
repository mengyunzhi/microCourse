<?php
namespace app\index\model;
use think\Model;
use app\index\validate\TeacherValidate;


class Teacher extends Model
{
    private static $validate;

    public function save($data = [], $where = [], $sequence = null)
    {
    	if (!$this->validate($this)) {
    		return false;
    	}

    	return parent::save($data, $where, $sequence);
    }

    private function validate() {
    	if (is_null(self::$validate)) {
    		self::$validate = new TeacherValidate();
    	}

    	return self::$validate->check($this);
    }
    
    // 老师登录验证————赵凯强
    static public function login($num, $password)
    {
        // 验证用户是否存在
        $map = array('num' => $num);
        $Teacher = self::get($map);

        if (!is_null($Teacher)) {
            // 验证密码是否正确
            if ($Teacher->checkPassword($password)) {
                session('teacherId', $Teacher->getData('id'));
                return true;
            }
        }
        return false;
    }
    
    // 教师通过微信登录————刘宇轩——废弃
    // static public function login($openid)
    // {
    //     // 验证用户是否存在
    //     $map = array('openid' => $openid);
    //     $Teacher = self::get($map);

    //     if (!is_null($Teacher)) {
    //         session('teacherId', $Teacher->getData('id'));
    //         return true;
    //     }
    //     return false;
    // }
   static public function Wxlogin($openid)
    {
        // 验证用户是否存在
        $map = array('openid' => $openid);
        $Teacher = self::get($map);

        if (!is_null($Teacher)) {
            session('teacherId', $Teacher->getData('id'));
            return true;
        }
        return false;
    }
     
    // 老师登录密码验证————赵凯强
    public function checkPassword($password)
    {
        if ($this->getData('password') === $password)
        {
            return true;
        } else {
            return false;
        }
    }
    
    // 老师注销————赵凯强
    static public function logOut()
    {
        session('teacherId', null);
        return true;
    }
    
    // 老师操作验证————赵凯强
    static public function isLogin()
    {
        $teacherId = session('teacherId');

        if (isset($teacherId)) {
            return true;
        } else {
            return false;
        }
    }
}