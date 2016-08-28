<?php
require_once '../adminInclude.php';
$adminName=$_POST['adminName'];
$adminName=addslashes($adminName);//字符转义
$password=md5($_POST['password']);
$verify=$_POST['verify'];
$verify1=$_SESSION['verify'];
@$autoFlag=$_POST['autoFlag'];//这里没选中记住密码为什么报错？？？//复选框初始默认没有post??吗，只好加上@屏蔽警告
if($verify==$verify1){
    $sql="select * from hw_admin where adminName='{$adminName}' and password='{$password}'";
    $row=Admin::checkAdmin($sql);
    if($row){
        //荣国选了一周内自动登录
        if($autoFlag){
            setcookie("adminId",$row['id'],time()+7*24*60*60);    
            setcookie("adminName",$row['adminName'],time()+7*24*60*60);    
        }
        $_SESSION['adminName'] = $row['adminName'];
        $_SESSION['adminId'] = $row['id'];
        Common::alertMes("登录成功", "index.php");
    }else{
        Common::alertMes("登录失败", "login.php");
    }
}else{
    Common::alertMes("验证码错误", "login.php");
}
