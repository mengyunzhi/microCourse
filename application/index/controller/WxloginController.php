<?php
$HTTP_RAW_POST_DATA = isset($GLOBALS['HTTP_RAW_POST_DATA']) ? $GLOBALS['HTTP_RAW_POST_DATA'] : file_get_contents("php://input");
    $post_obj = simplexml_load_string($HTTP_RAW_POST_DATA, 'SimpleXMLElement', LIBXML_NOCDATA);

    $msg_type = $post_obj->MsgType;

    switch ($msg_type) {
        case 'text':
            $keyword = trim($post_obj->Content);
            $msg_tpl = '<xml><ToUserName><![CDATA[%s]]></ToUserName><FromUserName><![CDATA[%s]]></FromUserName><CreateTime>%s</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA[%s]]></Content></xml>';
        if($post_obj->Content == '课程'){
		$result = Term::Weekday();
		echo $result;break;
}    
	
$result =  sprintf($msg_tpl,$post_obj->FromUserName, $post_obj->ToUserName, time(), $post_obj->FromUserName.$post_obj->ToUserName);
            echo $result;
            return;
            break;
        
        default:
            # code...
            break;
    }

