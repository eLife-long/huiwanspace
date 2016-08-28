<?php 
error_reporting(E_ALL & ~E_NOTICE);
header("content-type:text/html;charset=utf-8");
header("Access-Control-Allow-Origin: *");
date_default_timezone_set("PRC");//设置脚本的时区
session_start();
//$sid1 = !empty($_GET['sessionId']) ? $_GET['sessionId'] : '';//用户邮箱激活码验证，复制到别的浏览器
//var_dump($sid1);
//$sid2 = !empty($_POST['sessionId']) ? $_POST['sessionId'] : '';
//if($sid1 !='') {
    //可以设置一下用已经的sid开启会话
 //   session_id($sid1);
//}elseif($sid2 !='') {
    //可以设置一下用已经的sid开启会话
//            session_id($sid2);
//}
	//if($_POST['agent'] == 'web'){
//	    session_start();
	//}
	//判断app的请求时session开启的条件,防止一直分配sessionId
	/*if(($_POST['agent'] == 'app' && $_POST['action'] == 'getMessageVerify') || $sid2 !='' || $sid1 !=''){
	    session_start();
	}
	if($_POST['agent'] == 'app' && $_POST['action'] == 'userLogin' || $sid2 !='' || $sid1 !=''){
	    session_start();
	}
	if($_GET['action'] == 'getVerify'){
	    session_start();
	}*/

define("ROOT",dirname(__FILE__));//设置根路径

//包含引入文件的路径
set_include_path(".".PATH_SEPARATOR.ROOT."/lib".PATH_SEPARATOR.ROOT."/core".PATH_SEPARATOR.ROOT."/org".PATH_SEPARATOR.ROOT."/configs".PATH_SEPARATOR.ROOT."/api".PATH_SEPARATOR.get_include_path());

//lib基本类
require_once 'Common.class.php';
require_once 'Db.class.php';
require_once 'Sql.class.php';
require_once 'File.class.php';
require_once 'Image.class.php';
require_once 'Page.class.php';
require_once 'Response.class.php';
require_once 'Str.class.php';
require_once 'Upload.class.php';

//core核心操作类
require_once 'Phone.class.php';
require_once 'Email.class.php';
require_once 'Province.class.php';
require_once 'School.class.php';
require_once 'Campus.class.php';
require_once 'Place.class.php';
require_once 'Active.class.php';
require_once 'Admin.class.php';
require_once 'User.class.php';
require_once 'Situation.class.php';
require_once 'Group.class.php';
require_once 'Comment.class.php';
require_once 'Collection.class.php';
require_once 'Praise.class.php';
require_once 'Album.class.php';
require_once 'ApiForWeb.class.php';
require_once 'ApiForApp.class.php';
require_once 'ApiForCommon.class.php';


//org扩展类
require_once 'class.phpmailer.php';
require_once 'class.smtp.php';
require_once 'CCPRestSmsSDK.php';


//configs配置文件
//require_once 'configs.php';//上线时候用的配置文件
require_once 'configsForTest.php';//开发的时候用的配置文件

//连接数据库,返回的链接资源用global修饰
global $db_obj;
$db_obj = Db::getInstance();
$db_obj->connect();
