<?php
/**
*Enter description here ...
*tags
*@author Joker-Long
*编写日期：2016年8月28日下午2:53:31
*/
$uId = 18004421903;
$pwd = 111111;
$loginId = $_POST['loginId'];
$password = $_POST['password'];
if($uId == $loginId && $password == $pwd){
    $result['status'] = 1;
    $result['message'] = '登录成功';
    session_start();
    $_SESSION['uId'] = $loginId;
}

