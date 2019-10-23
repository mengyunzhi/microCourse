<?php

/**
 * @Author: LYX6666666
 * @Date:   2019-09-09 20:36:04
 * @Last Modified by:   LYX6666666
 * @Last Modified time: 2019-09-18 22:09:05
 */
namespace app\index\controller;
use think\Controller;

/*封装微信相关的各种方法——刘宇轩（粘贴）
 *定义appid
 *定义appsecret
 *拼接URL
 *获取accessToken
 *获取用户信息
 */

class WxController extends Controller{

    protected $appid='wxb56c4d9580a4b65b';
    //你的微信公众号appid
    protected $appsecret = '';
    //你的微信公众号secret 
 
 	//拼接URL
    public function accredit($redirect_url,$state){
        $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$this->appid}&redirect_uri={$redirect_url}&response_type=code&scope=snsapi_userinfo&state={$state}#wechat_redirect";
        $this->redirect($url);
    }

    /**
     * @param $code
     * @return bool|string
     */
    public function getAccessToken($code){
        $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid={$this->appid}&secret={$this->appsecret}&code={$code}&grant_type=authorization_code";
        $res = file_get_contents($url); //获取文件内容或获取网络请求的内容
        $access_token = json_decode($res,true);
        return $access_token;
    }

    /**
     * 获取用户信息
     * @param unknown $openid
     * @param unknown $access_token
     * @return unknown
     */
    public function getWeChatUserInfo($access_token,$openid){
        $url = "https://api.weixin.qq.com/sns/userinfo?access_token={$access_token}&openid={$openid}&lang=zh_CN";
        $output = file_get_contents($url);
        $weChatUserInfo = json_decode($output,true);
        return $weChatUserInfo;
    }
}