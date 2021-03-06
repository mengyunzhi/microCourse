<?php

/**
 * @Author: LYX6666666
 * @Date:   2019-09-09 20:40:17
 * @Last Modified by:   LYX6666666
 * @Last Modified time: 2019-10-18 21:32:29
 */
namespace app\index\controller;
use app\index\controller\WxController;
use app\index\controller\StudentController;
use app\index\controller\LoginController;
use app\index\model\Student;
use app\index\model\Teacher;
use app\index\model\Term;
 
class WxindexController extends WxController {

    //方法名：长度不能是8位！
	public static $openIdTest = 'openIdTest';
	public static $page = 'page';
	public static $score = 'score';
	public static $course = 'course';
	public static $info = 'info';
    public static $online = 'online';
    public static $tpage = 'tpage';//Teacher page
    public static $tcourse = 'tcourse';//Teacher course
    public static $tgrade = 'tgrade';//Teacher grade

    public function openIdTest() {  //OpsnId测试
    	$this->weChatAccredit($this::$openIdTest);
    }

    public function page(){ // 跳转到主页
		$this->weChatAccredit($this::$page);
    }

    public function course(){ // 跳转到课程查询
		$this->weChatAccredit($this::$course);
    }

    public function score(){ // 跳转到成绩查询
		$this->weChatAccredit($this::$score);
    }

    public function info(){ // 跳转到个人信息
		$this->weChatAccredit($this::$info);
    }

    public function online(){//上课签到
        $this->_weChatAccredit($this::$online,$this->request->param('id'));
    }
    
    public function tpage(){ //教师登录
        $this->weChatAccredit($this::$tpage);
    }

    public function tcourse(){ //教师登录
        $this->weChatAccredit($this::$tcourse);
    }

    public function tgrade(){ //教师登录
        $this->weChatAccredit($this::$tgrade);
    }

    public function Wx(){
        $this->request->param('echostr');
    }
     
    /**
     * 微信按钮跳转授权
     */
    public function weChatAccredit($buttonType) {
        $url = 'http://'.$_SERVER['HTTP_HOST'].'/index/WxIndex/getChatInfo';
        $we_chat = new WxController();  //实例化类
        $we_chat->accredit($url,$buttonType); //调用方法
    }

    //上一个函数的重载，仅提供扫码签到的功能
    public function _weChatAccredit($buttonType,$id) {
        $url = 'http://'.$_SERVER['HTTP_HOST'].'/index/WxIndex/getChatInfo';
        $we_chat = new WxController();  //实例化类
        $we_chat->accredit($url,$id);   //state实际上是传了课程信息ID！！
    }

    /**
     * 获取微信用户信息
     */
    public function getChatInfo(){
        $we_chat = new WxController();//实例化微信类
        $code = $_GET['code'];  	//获取跳转后的code
        //var_dump($code);
   		$state = $_GET['state'];	//获取state
        // dump($_GET);
        // return;

        $access_token = $we_chat->getAccessToken($code); //根据code获取token
        //var_dump($access_token);
        //根据access_token和openid获取到用户信息
        //$we_chat_user_info = $we_chat->getWeChatUserInfo($access_token['access_token'],$access_token['openid']);
        //var_dump($we_chat_user_info);
        // 
        $this->gogogo($state,$access_token["openid"]);
        //$this->gogogo($state,$we_chat_user_info["openid"]);
    }

    //用于跳转到各个方法，传入OpenId和跳转的方法
    public function gogogo($state,$openid)
    {
        Teacher::logout();
        Student::logout();
        if (Teacher::Wxlogin($openid)){
            //尝试登陆教师，如果成功，不进行跳转，只存Session
        } 
    	
        else{
            // 登陆，直接调用M层方法，进行登录
    		if (Student::Wxlogin($openid)){
    		//尝试登陆学生，如果成功，不进行跳转，只存Session
                if (Student::where('openid',$openid)->find()->name == Null) {
                    //当学生ID数据为空时，不知道此用户时学生还是教师
                    //判断是否是要跳转到教师查看签到的界面
                    if (strlen($state) == 8) {
                        $row = substr($state,4,2)*1;
                        $column = substr($state,6,2)*1;
                        //如果长度为8，并且行号列号都是0，跳转到Login的OnlineSee方法（不需要注册）
                        if ($row == 0 || $column == 0) {
                            $this->redirect('http://'.$_SERVER['HTTP_HOST'].'/index/Login/OnlineSee?id='.substr($state,0,4));
                        }
                    }
                }else{
                    if (strlen($state) == 8) {
                        $row = substr($state,4,2)*1;
                        $column = substr($state,6,2)*1;
                        //如果长度为8，并且行号列号都是0，跳转到Login的OnlineSee方法（不需要注册）
                        if ($row == 0 || $column == 0) {
                            $this->redirect('http://'.$_SERVER['HTTP_HOST'].'/index/Login/OnlineSee?id='.substr($state,0,4));
                        }
                    }
                }
    		} else {
    		//如果失败，说明该用户是新用户
            
                //判断是否是要跳转到教师查看签到的界面
                if (strlen($state) == 8) {
                    $row = substr($state,4,2)*1;
                    $column = substr($state,6,2)*1;
                    //如果长度为8，并且行号列号都是0，跳转到Login的OnlineSee方法（不需要注册）
                    if ($row == 0 || $column == 0) {
                        $this->redirect('http://'.$_SERVER['HTTP_HOST'].'/index/Login/OnlineSee?id='.substr($state,0,4));
                    }
                }
                //如果不是，则是学生或教师，马上存学生的OpenID,取出ID，跳转到注册界面
    			$Student = new Student();
    			$Student->openid = $openid;
    			$Student->save();
    			$this->redirect('http://'.$_SERVER['HTTP_HOST'].'/index/Login/judgeRole?id='.$Student->id);
    		}
        }

        if(Term::NoTerm()) {
            // return $this->fetch('Login/noterm');
            return $this->error('系统尚未初始化', url('Login/noterm'));
        }

        //dump(Student::Wxlogin($openid));
        //dump(Teacher::Wxlogin($openid));
        //dump(Student::isLogin());
        //dump(Teacher::isLogin());
        //dump(session('teacherId'));
        //dump(session('studentId'));
        //die();

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
    		//课程查询	
    		case 'course':
    			$this->redirect('http://'.$_SERVER['HTTP_HOST'].'/index/Student/course');
    			break;
    		//成绩查询	
    		case 'score':
    			$this->redirect('http://'.$_SERVER['HTTP_HOST'].'/index/Student/score');
    			break;
    		//个人信息	
    		case 'info':
    			$this->redirect('http://'.$_SERVER['HTTP_HOST'].'/index/Student/info');
    			break;
            //教师主页  
            case 'tpage':
                $this->redirect('http://'.$_SERVER['HTTP_HOST'].'/index/Teacher/page');
                break;
            //课程管理  
            case 'tcourse':
                $this->redirect('http://'.$_SERVER['HTTP_HOST'].'/index/Teacher/course');
                break;
            //成绩录入  
            case 'tgrade':
                $this->redirect('http://'.$_SERVER['HTTP_HOST'].'/index/Teacher/grade');
                break;

    		//扫码签到，state作为课程信息ID来使用
    		default:
                //如果行号列号都是0，就跳转到Teacher下的查看签到，如果不是就跳转到学生签到
                $row = substr($state,4,2)*1;
                $column = substr($state,6,2)*1;
                if ($row == 0 || $column == 0) {
                    $this->redirect('http://'.$_SERVER['HTTP_HOST'].'/index/Teacher/OnlineSee?id='.substr($state,0,4));
                }else{
                    $this->redirect('http://'.$_SERVER['HTTP_HOST'].'/index/Student/entercourse?id='.$state);
                }
    			break;
    	}
    	
    }

}

// http://awjdjsz.jincheng4917.cn/index.php/index/Wx/weChatAccredit