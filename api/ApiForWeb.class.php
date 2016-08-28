<?php

/**
 *web的api接口类
 *@author Joker-Long
 *编写日期：2016年8月13日下午8:14:56
 */
class ApiForWeb
{

    /**
     * 获取验证码
     */
    public static function getVerify(){
        Image::verifyImage(1, 4, 50, 3);
    }

    /**
     * 在web端用户通过email注册
     *web端用上后台提供的四位数字图片验证码，app端不用，区别一下，以后优化
     * @return string
     */
    public static function userRegisterByEmailForWeb(){
        global $result; // 更新userLog日志表要用
        $arr['regTime'] = time();
        $arr['password'] = $_POST['password'] ? $_POST['password'] : '';
        $arr['email'] = $_POST['email'] ? $_POST['email'] : ''; // 为NULL的时候才是数据库的NULL,为''空字符串不是
        $verify = $_POST['verify'] ? $_POST['verify'] : '';
        $verify1 = $_SESSION['verify'];
        if ($arr['password'] == '' || $arr['email'] == '' || $verify == '') {
            $result['status'] = 8; // 填写不全
            $result['message'] = '请将信息填写完成';
        } elseif ($verify != $verify1) {
            $result['status'] = 7; // 验证码错误
            $result['message'] = '验证码错误';
        } else {
            $checkEmail = Email::checkEmailExist($arr['email']);
            if ($checkEmail == 2) { // 返回2表示邮箱格式正确且还未被注册
                if (Str::isPassword($arr['password'])) {
                    $arr['password'] = md5(trim($arr['password'])); // 存储的时候密码加密 // var_dump($arr);
                    $arr['username'] = '玩伴' . $arr['phone']; // 分配一个用户名，完善信息再修改//玩伴加上qq号
                    $_SESSION['emailCode'] = md5($arr['username'] . $arr['password'] . time()); // 创建用于激活识别码
                    $_SESSION['emailCode_exptime'] = time() + 60 * 60 * 24; // 过期时间为24小时后
                    $body = "亲爱的" . $arr['username'] . "：<br/>感谢您在汇玩空间网注册了新帐号。<br/>请点击链接激活您的帐号。<br/><a href='http://49.140.166.99:8080/huiwanspace/api/webServer.php?action=emailVerifyForRegister&email=" . $arr['email'] . "&emailCode=" . $_SESSION['emailCode'] . "&sessionId=" . session_id() . "' target='_blank'>http://49.140.166.99:8080/huiwanspace/api/webServer.php?action=emailVerifyForRegister&email=" . $arr['email'] . "&emailCode=" . $_SESSION['emailCode'] . "&sessionId=" . session_id() . "</a><br/>如果以上链接无法点击，请将它复制到你的浏览器地址栏中进入访问，该链接24小时内有效。<br/>如果此次激活请求非你本人所发，请忽略本邮件。<br/><p style='text-align:right'>--------  <a href='http://www.huiwanspace.com'>www.huiwanspace.com<br/>汇玩空间网</p>";
                    $bool = Email::sendEmai($arr['username'], $arr['email'], $body);
                    if ($bool) {
                        $link = User::register($arr);
                        if ($link) {
                            $result['status'] = 1;
                            $result['message'] = '注册成功,邮件已经发送,请前往邮箱进行激活';
                        } else {
                            $result['status'] = 2;
                            $result['message'] = '注册失败,请重新注册';
                            $_SESSION = array();
                        }
                    } else {
                        $result['status'] = 3;
                        $result['message'] = '发送邮件失败,请检查邮箱是否有误';
                        $_SESSION = array();
                    }
                } else {
                    $result['status'] = 4;
                    $result['message'] = '密码格式错误';
                }
            } elseif ($checkEmail == 3) {
                $result['status'] = 5;
                $result['message'] = '邮箱格式错误';
            } elseif ($checkEmail == 1) {
                $result['status'] = 6;
                $result['message'] = '该邮箱已经被注册';
            }
        }
        echo Response::json($result);
    }
    
    /**
     * 在web端用户绑定邮箱
     *web端用上后台提供的四位数字图片验证码，app端不用，区别一下，以后优化
     * @return string
     */
    public static function usermodifyEmailForWeb(){
        global $result; // 更新userLog日志表要用
        $arr['regTime'] = time();
        $arr['password'] = $_POST['password'] ? $_POST['password'] : '';
        $arr['email'] = $_POST['email'] ? $_POST['email'] : ''; // 为NULL的时候才是数据库的NULL,为''空字符串不是
        $verify = $_POST['verify'] ? $_POST['verify'] : '';
        $verify1 = $_SESSION['verify'];
        if ($arr['password'] == '' || $arr['email'] == '' || $verify == '') {
            $result['status'] = 8; // 填写不全
            $result['message'] = '请将信息填写完成';
        } elseif ($verify != $verify1) {
            $result['status'] = 7; // 验证码错误
            $result['message'] = '验证码错误';
        } else {
            $checkEmail = Email::checkEmailExist($arr['email']);
            if ($checkEmail == 2) { // 返回2表示邮箱格式正确且还未被注册
                if (Str::isPassword($arr['password'])) {
                    $arr['password'] = md5(trim($arr['password'])); // 存储的时候密码加密 // var_dump($arr);
                    $_SESSION['emailCode'] = md5($arr['username'] . $arr['password'] . time()); // 创建用于激活识别码
                    $_SESSION['emailCode_exptime'] = time() + 60 * 60 * 24; // 过期时间为24小时后
                    $body = "亲爱的" . $arr['username'] . "：<br/>感谢您在汇玩空间网注册了新帐号。<br/>请点击链接激活您的帐号。<br/><a href='http://49.140.166.99:8080/huiwanspace/api/webServer.php?action=emailVerifyForRegister&email=" . $arr['email'] . "&emailCode=" . $_SESSION['emailCode'] . "&sessionId=" . session_id() . "' target='_blank'>http://49.140.166.99:8080/huiwanspace/api/webServer.php?action=emailVerifyForRegister&email=" . $arr['email'] . "&emailCode=" . $_SESSION['emailCode'] . "&sessionId=" . session_id() . "</a><br/>如果以上链接无法点击，请将它复制到你的浏览器地址栏中进入访问，该链接24小时内有效。<br/>如果此次激活请求非你本人所发，请忽略本邮件。<br/><p style='text-align:right'>--------  <a href='http://www.huiwanspace.com'>www.huiwanspace.com<br/>汇玩空间网</p>";
                    $bool = Email::sendEmai($arr['username'], $arr['email'], $body);
                    if ($bool) {
                        $link = User::register($arr);
                        if ($link) {
                            $result['status'] = 1;
                            $result['message'] = '注册成功,邮件已经发送,请前往邮箱进行激活';
                        } else {
                            $result['status'] = 2;
                            $result['message'] = '注册失败,请重新注册';
                            $_SESSION = array();
                        }
                    } else {
                        $result['status'] = 3;
                        $result['message'] = '发送邮件失败,请检查邮箱是否有误';
                        $_SESSION = array();
                    }
                } else {
                    $result['status'] = 4;
                    $result['message'] = '密码格式错误';
                }
            } elseif ($checkEmail == 3) {
                $result['status'] = 5;
                $result['message'] = '邮箱格式错误';
            } elseif ($checkEmail == 1) {
                $result['status'] = 6;
                $result['message'] = '该邮箱已经被注册';
            }
        }
        echo Response::json($result);
    }
    
    /**
     * 在web端改密码，有四位数字图片验证码
     */
    public static function forgetPasswordForWeb(){
        global $result; // 更新userLog日志表要用
        if($_POST['verify'] == '' || $_POST['loginId'] == ''){
            $result['status'] = 8;
            $result['message'] = '请将信息填写完成';
        }elseif($_POST['verify'] != $_SESSION['verify']){
                $result['status'] = 7;
                $result['message'] = '验证码错误';
        }elseif(Str::isPhone($_POST['loginId'])){//忘记的是手机账号的密码
            $checkPhone = Phone::checkPhoneExist($_POST['loginId']);
            if ($checkPhone == 1){
                $result['status'] = 1;
                $result['message'] = '手机号码已经被注册，可以发送手机验证码用于更改密码';
            }elseif ($checkPhone == 2){
                $result['status'] = 2;
                $result['message'] = '该手机号未被注册';
            }
        }elseif(Str::isEmail($_POST['loginId'])){//忘记的是邮箱账号的密码
            $checkEmail = Email::checkEmailExist($_POST['loginId']);
            if ($checkEmail == 1){
                $_SESSION['email'] = $_POST['loginId'];
                $_SESSION['emailCode'] = md5($_POST['loginId'].time()); //创建用于激活识别码
                $_SESSION['emailCode_exptime'] = time()+60*60*24;//过期时间为24小时后
                $body="尊敬的汇玩空间网用户，您好！"."：<br/><br/><br/>您在访问汇玩空间网时点击了“忘记密码”链接，这是一封密码重置确认邮件。<br/>您可以通过点击以下链接重置帐户密码:<br/><a href='http://49.140.166.99:8080/huiwanspace/api/webServer.php?action=emailVerifyForReset&emailCode=".$_SESSION['emailCode']."&sessionId=".session_id()."' target='_blank'>http://49.140.166.99:8080/huiwanspace/api/webServer.php?action=emailVerifyForReset&emailCode=".$_SESSION['emailCode']."&sessionId=".session_id()."</a><br/>如果以上链接无法点击，请将它复制到你的浏览器地址栏中进入访问，该链接24小时内有效。<br/>如果此次激活请求非你本人所发，请忽略本邮件。<br/><p style='text-align:right'>--------  <a href='http://www.huiwanspace.com'>www.huiwanspace.com<br/>汇玩空间网</p>";
                $bool = Email::sendEmai($_POST['loginId'], $_POST['loginId'], $body);
                if($bool){
                    $result['status'] = 3;//发送成功，请查看短信
                    $result['message'] = '已经发送验证邮箱，请前往修改密码';
                }else{
                    $result['status'] = 4;//发送验证码失败，请重新发送
                    $result['message'] = '发送邮箱失败，请检查邮箱，重新发送';
                    $_SESSION = array();
                }
            }elseif ($checkEmail == 2){
                $result['status'] = 5;
                $result['message'] = '该邮箱未被注册';
            }
        }else{
            $result['status'] = 6;
            $result['message'] = '账号格式错误';
        }
        echo Response::json($result);
    }


    /**
     * 检查用户名存在，注册时和更改用户名时用
     * 存在返回ture，不存在返回false，用于web的remote，默认只识别ture和false
     *
     * @return bool
     */
    public static function usernameExist()
    {
        $checkUsername = User::checkUsernameExist($_POST['username']);
        if ($checkUsername == 1) {
            $mes = true;
        } elseif ($checkUsername == 2) {
            $mes = false;
        }
        echo json_encode($mes);
    }

    /**
     * 检查用户名不存在，登录的时候用
     * 不存在返回ture，存在返回false，用于web的remote，默认只识别ture和false
     *
     * @return bool
     */
    public static function usernameNoExist()
    {
        $checkUsername = User::checkUsernameExist($_POST['username']);
        if ($checkUsername == 1) {
            $mes = false;
        } elseif ($checkUsername == 2) {
            $mes = true;
        }
        echo json_encode($mes);
    }

    /**
     * 检查手机号码存在，注册时用
     * 存在返回ture，不存在返回false，用于web的remote，默认只识别ture和false
     *
     * @return bool
     */
    public static function phoneExist()
    {
        $checkPhone = Phone::checkPhoneExist($_POST['phone']);
        if ($checkPhone == 1) {
            $mes = true;
        } elseif ($checkPhone == 2) {
            $mes = false;
        }
        echo json_encode($mes);
    }

    /**
     * 检查手机号码不存在，登录的时候用
     * 不存在返回ture，存在返回false，用于web的remote，默认只识别ture和false
     *
     * @return bool
     */
    public static function phoneNoExist()
    {
        $checkPhone = Phone::checkPhoneExist($_POST['phone']);
        if ($checkPhone == 1) {
            $mes = false;
        } elseif ($checkPhone == 2) {
            $mes = true;
        }
        echo json_encode($mes);
    }

    /**
     * 检查邮箱存在，注册时用
     * 存在返回ture，不存在返回false，用于web的remote，默认只识别ture和false
     *
     * @return bool
     */
    public static function emailExist()
    {
        $checkEmail = Email::checkEmailExist($_POST['email']);
        if ($checkEmail == 1) {
            $mes = true;
        } elseif ($checkEmail == 2) {
            $mes = false;
        }
        echo json_encode($mes);
    }

    /**
     * 检查邮箱不存在，登录的时候用
     * 不存在返回ture，存在返回false，用于web的remote，默认只识别ture和false
     *
     * @return bool
     */
    public static function emailNoExist()
    {
        $checkEmail = Email::checkEmailExist($_POST['email']);
        if ($checkEmail == 1) {
            $mes = false;
        } elseif ($checkEmail == 2) {
            $mes = true;
        }
        echo json_encode($mes);
    }

    /**
     * 检查loginId存在，注册时用
     * 存在返回ture，不存在返回false，用于web的remote，默认只识别ture和false
     *
     * @return bool
     */
    public static function loginIdExist()
    {
        if (Str::isPhone($_POST['loginId'])) {
            $checkPhone = Phone::checkPhoneExist($_POST['loginId']);
            if ($checkPhone == 1) {
                $mes = true;
            } elseif ($checkPhone == 2) {
                $mes = false;
            }
        } elseif (Str::isEmail($_POST['loginId'])) {
            $checkEmail = Email::checkEmailExist($_POST['loginId']);
            if ($checkEmail == 1) {
                $mes = true;
            } elseif ($checkEmail == 2) {
                $mes = false;
            }
        } else {
            $mes = false;
        }
        echo json_encode($mes);
    }

    /**
     * 检查loginId不存在，登录的时候用
     * 不存在返回ture，存在返回false，用于web的remote，默认只识别ture和false
     *
     * @return bool
     */
    public static function loginIdNoExist()
    {
        if (Str::isPhone($_POST['loginId'])) {
            $checkPhone = Phone::checkPhoneExist($_POST['loginId']);
            if ($checkPhone == 1) {
                $mes = false;
            } elseif ($checkPhone == 2) {
                $mes = true;
            }
        } elseif (Str::isEmail($_POST['loginId'])) {
            $checkEmail = Email::checkEmailExist($_POST['loginId']);
            if ($checkEmail == 1) {
                $mes = false;
            } elseif ($checkEmail == 2) {
                $mes = true;
            }
        } else {
            $mes = false;
        }
        echo json_encode($mes);
    }
    
    /**
     * 在修改用户资料需要密码的时候验证密码
     */
    public static function verifyPassword(){
        global $result; // 更新userLog日志表要用
        $uId = $_SESSION['uId'] ? $_SESSION['uId'] : ''; // 如果用户已经登录，则从session中获取用户的id
        if ($uId != '') {
            $password = $_POST['password']?$_POST['password']:'';
            $verify = $_POST['verify']?$_POST['verify']:'';
            $verify1 = $_SESSION['verify'];
            if($verify == '' || $password == ''){
                $result['status'] = 5;
                $result['message'] = '请输入密码和验证码';
            }elseif($verify != $verify1){
                $result['status'] = 4;
                $result['message'] = '验证码错误';
            }else{
                if(Str::isPassword($password)){
                    $bool = User::verifyPassword($uId, $password);
                    if($bool){
                        $result['status'] = 1;
                        $result['message'] = '密码正确';
                    }else{
                        $result['status'] = 2;
                        $result['message'] = '密码错误';
                    }
                }else{
                    $result['status'] = 3;
                    $result['message'] = '密码格式错误，请输入6—20位由字母、数字组成的密码';
                }
            }
        }else{
            $result['status'] = 6;
            $result['message'] = '还未登录，请先登录';
        }
    
        echo Response::json($result);
    }
}