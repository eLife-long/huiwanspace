<?php
/**
 *web端的接口
 *@author Joker-Long
 *编写日期：2016年8月13日下午7:58:32
 */
// http://49.140.166.99:8080/huiwanspace/api/webServer.php
require_once '../lib/Str.class.php';
$_POST = Str::filterInput($_POST);//过滤用户输入
$sId = ! empty($_GET['sessionId']) ? $_GET['sessionId'] : '';
if ($sId != '') {
    // 让激活邮箱的链接在别的浏览器也可以打开
    session_id($sId);
}

if($sId == ''){
    $sId = ! empty($_POST['sessionId']) ? $_POST['sessionId'] : '';
    if ($sId != '') {
        // web端测试的时候模仿app上传sessionId
        session_id($sId);
    }
}
session_start();
require_once '../include.php';
$action = $_POST['action'];
if (! $action) {
    $action = $_GET['action'];
}
if (! $action) { // 获取验证码或者验证激活邮箱的时候需要get传action
    $action = $_GET['action'];
}

Common::writeUserRequestLog(); // 写入用户请求日志

switch ($action) {
    case "getListSituation": // 获取活动列表,参数参看接口文档getSituationById
        ApiForCommon::getListSituation();
        break;
    
    case "getSituationById": // 获取活动列表,参数参看接口文档
        ApiForCommon::getSituationById();
        break;
    // 这一段还没有写开发文档 kaishi//好像还有获取用户个人发布的活动的没写开发文档
    case "pubSituation": // 发布用户活动
        ApiForCommon::pubSituation();
        break;
    
    case "delSituation": // 删除用户活动
        ApiForCommon::delSituation();
        break;
    
    case "transmitSituation": // 转发用户活动
        ApiForCommon::transmitSituation();
        break;
    
    case "comment": // 评论活动
        ApiForCommon::comment();
        break;
    
    case "reply": // 回复评论
        ApiForCommon::reply();
        break;
    
    case "getListComment": // 获取活动的评论列表
        ApiForCommon::getListComment();
        break;
    
    case "join": // 参加活动
        ApiForCommon::join();
        break;
    
    case "cancelJoin": // 取消参加活动
        ApiForCommon::cancelJoin();
        break;
        
    case "getSituationGroup": // 获取参加活动的用户组
        ApiForCommon::getSituationGroup();
        break;
    
    case "getUserJoin": // 获取用户参加的活动列表
        ApiForCommon::getUserJoin();
        break;
    
    case "checkCollect":    //检查用户是否收藏过该活动
        ApiForCommon::cancelCollect();
        break;
        
    case "collect": // 收藏活动
        ApiForCommon::collect();
        break;
    
    case "cancelCollect": // 取消收藏
        ApiForCommon::cancelCollect();
        break;
    
    case "getUserCollection": // 获取用户收藏的活动列表
        ApiForCommon::getUserCollection();
        break;
    case "checkPraise":     //检查用户是否赞过该活动
        ApiForCommon::checkPraise();
        break;
        
    case "praise": // 点赞
        ApiForCommon::praise();
        break;
    
    case "cancelPraise": // 取消赞
        ApiForCommon::cancelPraise();
        break;
        
    case "getUserPraise"://获取用户赞过的活动列表
        ApiForCommon::getUserPraise();
        break;
    
    case "follow": // 加关注
        ApiForCommon::follow();
        break;
    
    case "cancelFollow": // 取消关注
        ApiForCommon::cancelFollow();
        break;
    
    case "getUserFollower": // 获取粉丝列表
        ApiForCommon::getUserFollower();
        break;
    
    case "getUserAttention": // 获取用户关注列表
        ApiForCommon::getUserAttention();
        break;
    
    
    
    // 这一段还没有写开发文档 jieshu
    case "getListSituationImage": // 获取单个活动图片列表
        ApiForCommon::getSituationImage();
        break;
    
    case "getListProvince": // 获取省份列表
        ApiForCommon::getListProvince();
        break;
    
    case "getListSchool": // 获取学校列表
        ApiForCommon::getListSchool();
        break;
    
    case "getSchoolByPid": // 通过省份的id获取该省份的学校列表
        ApiForCommon::getSchoolByPid();
        break;
    
    case "getCampusBySid": // 通过学校的id获取该学校的校区列表
        ApiForCommon::getCampusBySid();
        break;
    
    case "getPlaceByCid": // 通过校区的id获取校区提供的地点列表
        ApiForCommon::getPlaceByCid();
        break;
    
    case "getActiveByCid": // 通过校区id获取校区提供的快捷活动标题列表
        ApiForCommon::getActiveByCid();
        break;
    
    case "getActiveByPid": // 通过场所id获取场所提供的快捷标题列表
        ApiForCommon::getActiveByPid();
        break;
    
    case "getVerify": // 获取验证码
        ApiForWeb::getVerify();
        break;
    
    case "userLogin": // 用户登陆
        ApiForCommon::userLogin();
        break;
    
    case "getUserInfo": // 获取用户信息
        ApiForCommon::getUserInfo();
        break;
        
    case "modifyUserInfo":  //修改用户个人基本信息
        ApiForCommon::modifyUserInfo();
        break;
        
    case "modifyPassword":  //修改密码
        ApiForCommon::modifyPassword();
        break;
        
    case "bindPhone":   //绑定/修改绑定手机号码
        ApiForCommon::bindPhone();
        break;
        
    case "bindEmail":   //绑定/更改绑定邮箱
        ApiForCommon::bindEmail();
        break;
        
    case "emailVerifyForBindEmail": //验证绑定邮箱，执行更新用户表中的邮箱字段
        ApiForCommon::emailVerifyForBindEmail();
        break;
        
    case "verifyPassword":  //在修改用户资料需要密码的时候验证密码
        ApiForWeb::verifyPassword();
        break;
    
    case "userLoginOut": // 用户退出登录
        ApiForCommon::userLoginOut();
        break;
    
    case "getMessageForReg": // 获取短信验证码用于注册
        ApiForCommon::getMessageForReg();
        break;
    
    case "getMessageForReset": // 获取短信验证码用于重置密码
        ApiForCommon::getMessageForReset();
        break;
    
    case "usernameExist": // 检查用户名存在
        ApiForWeb::usernameExist();
        break;
    
    case "usernameNoExist": // 检查用户名不存在
        ApiForWeb::usernameNoExist();
        break;
    
    case "phoneExist": // 检查手机号码存在
        ApiForWeb::phoneExist();
        break;
    
    case "phoneNoExist": // 检查手机号码不存在
        ApiForWeb::phoneNoExist();
        break;
    
    case "emailExist": // 检查邮箱存在
        ApiForWeb::emailExist();
        break;
    
    case "emailNoExist": // 检查邮箱不存在
        ApiForWeb::emailNoExist();
        break;
    
    case "loginIdExist": // 检查loginId存在
        ApiForWeb::loginIdExist();
        break;
    
    case "loginIdNoExist": // 检查loginId不存在
        ApiForWeb::loginIdNoExist();
        break;
    
    case "userRegisterByEmail": // 用户邮箱注册
        ApiForWeb::userRegisterByEmailForWeb();
        break;
    
    case "userRegisterByPhone": // 用户手机号码注册,手机注册web去app一致，用公共的接口
        ApiForCommon::userRegisterByPhone();
        break;
    
    case "emailVerifyForRegister": // 注册时的邮箱激活码验证
        ApiForCommon::emailVerifyForRegister();
        break;
    
    case "emailVerifyForReset": // 更改密码邮箱激活码验证
        ApiForCommon::emailVerifyForReset();
        break;
    
    case "forgetPassword": // 检查忘记密码输入的手机号或邮箱和验证码,已经注册的账号，则发送验证邮箱或者短信
        ApiForWeb::forgetPasswordForWeb();
        break;
    
    case "resetPasswordForEmail": // 邮箱账号改密码
        ApiForCommon::resetPasswordForEmail();
        break;
    
    case "resetPasswordForPhone": // 手机账号改密码
        ApiForCommon::resetPasswordForPhone();
        break;
    
    default:
        return NULL;
        break;
}
Common::writeUserResponseLog();//写入用户请求返回值日志、
