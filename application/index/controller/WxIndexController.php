<?php

/**
 * @Author: LYX6666666
 * @Date:   2019-09-09 20:40:17
 * @Last Modified by:   LYX6666666
 * @Last Modified time: 2019-09-19 21:06:43
 */
namespace app\index\controller;
use app\index\controller\WxController;
use app\index\controller\StudentController;
use app\index\controller\LoginController;
use app\index\model\Student;
 
class WxIndexController extends WxController {
	public static $openIdTest = 'openIdTest';
	public static $page = 'page';

    public function openIdTest() {  //OpsnId测试
    	$this->weChatAccredit($this::$openIdTest);
    }

    public function page(){ // 跳转到主页
		$this->weChatAccredit($this::$page);
    }

    /**
     * 微信按钮跳转授权
     */
    public function weChatAccredit($buttonType) {
        $url = 'http://'.$_SERVER['HTTP_HOST'].'/index/WxIndex/getChatInfo';
        $we_chat = new WxController(); //实例化类
        $we_chat->accredit($url,$buttonType); //调用方法
    }

    /**
     * 获取微信用户信息
     */
    public function getChatInfo(){
        $we_chat = new WxController();//实例化微信类
        $code = $_GET['code'];  	//获取跳转后的code
   		$state = $_GET['state'];	//获取state
        $access_token = $we_chat->getAccessToken($code); //根据code获取token
        //根据access_token和openid获取到用户信息
        $we_chat_user_info = $we_chat->getWeChatUserInfo($access_token['access_token'],$access_token['openid']);
        $this->gogogo($state,$we_chat_user_info["openid"]);
        // var_dump($we_chat_user_info );
        // var_dump($state);
    }

    //用于跳转到各个方法，传入OpenId和跳转的方法
    public function gogogo($state,$openid)
    {
    	// 登陆，直接调用M层方法，进行登录
		if (Student::Wxlogin($openid)){
			//如果成功，不进行跳转，只存Session
		} else {
			//如果失败，马上存OpenID,取出ID，跳转到注册界面
			$Student = new Student();
			$Student->openid = $openid;
			$Student->save();
			$this->redirect('http://'.$_SERVER['HTTP_HOST'].'/index/Login/register?id='.$Student->id);
		}

		//跳转，根据state跳转到不同界面
    	switch ($state) {
    		//OpenId测试
    		case 'openIdTest':
    			$this->redirect('http://'.$_SERVER['HTTP_HOST'].'/index/Student/OpenIdtest');
    			break;
    		//首页
    		case 'page':
    			$this->redirect('http://'.$_SERVER['HTTP_HOST'].'/index/Student/page');
    			break;
    		//暂不处理
    		default:

    			break;
    	}
    	
    }

}

// http://awjdjsz.jincheng4917.cn/index.php/index/Wx/weChatAccredit