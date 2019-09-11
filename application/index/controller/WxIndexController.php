<?php

/**
 * @Author: LYX6666666
 * @Date:   2019-09-09 20:40:17
 * @Last Modified by:   LYX6666666
 * @Last Modified time: 2019-09-11 09:24:05
 */
namespace app\index\controller;
use app\index\controller\WxController;
 
class WxIndexController extends WxController{
    /**
     * 微信按钮跳转授权
     */
    public function weChatAccredit(){
        $url = 'http://'.$_SERVER['HTTP_HOST'].'/index/WxIndex/getChatInfo';
        $we_chat = new WxController(); //实例化类
        $we_chat->accredit($url); //调用方法
    }
    /**
     * 获取微信用户信息
     */
    public function getChatInfo(){
        $we_chat = new WxController();//实例化微信类
        $code = $_GET['code'];  //获取跳转后的code
   		$state = $_GET['state'];
        $access_token = $we_chat->getAccessToken($code); //根据code获取token
        //根据access_token和openid获取到用户信息
        $we_chat_user_info = $we_chat->getWeChatUserInfo($access_token['access_token'],$access_token['openid']);
        var_dump($we_chat_user_info );
        var_dump($state);
    }
}

// http://awjdjsz.jincheng4917.cn/index.php/index/Wx/weChatAccredit