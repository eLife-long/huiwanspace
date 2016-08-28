<?php
/**
*电子邮箱类
*tags
*@author Joker-Long
*编写日期：2016年8月13日上午10:39:20
*/
class Email
{
    /**
     * 检查邮箱是否存在
     * @param string $sql
     * @return Ambigous <mutitype:,multitype:>???
     */
    public static function checkEmailExist($email){
        global $db_obj;
        $email = trim($email);
        if(Str::isEmail($email)){//符合邮箱格式，则判断是否已经存在该用户名
            if(get_magic_quotes_gpc()){
                $email=stripslashes($email);    //stripslashes — 反引用一个引用字符串;//trim去除两边空白
            }
            $sql = "select id from hw_user where email='{$email}'";
            $row = $db_obj->fetchOne($sql);
            if($row){
                return 1;//该邮箱已经被注册
            }else{
                return 2;//该邮箱还未被注册
            }
        }else{
            return 3;//不符合邮箱格式
        }
        return 0;
    }
    
    /**
     * 发送邮件
     * @param unknown $username
     * @param unknown $email
     * @param unknown $token
     * @return boolean
     */
    public static function sendEmai($username,$email,$body){
        $mail=new PHPMailer();
        $mail->Charset='UTF-8';
        //$body="亲爱的".$username."：<br/>感谢您在汇玩空间网注册了新帐号。<br/>请点击链接激活您的帐号。<br/><a href='http://49.140.166.99:8080/hw/api/server.php?action=mailVerify&email=".$email."&emailCode=".$emailCode."&sessionId=".session_id()."' target='_blank'>http://49.140.166.99:8080/hw/api/server.php?action=mailVerify&email=".$email."&emailCode=".$emailCode."&sessionId=".session_id()."</a><br/>如果以上链接无法点击，请将它复制到你的浏览器地址栏中进入访问，该链接24小时内有效。<br/>如果此次激活请求非你本人所发，请忽略本邮件。<br/><p style='text-align:right'>--------  <a href='http://www.huiwanspace.com'>www.huiwanspace.com<br/>汇玩空间网</p>";
        //设置smtp参数
        $mail->IsSMTP();
        $mail->SMTPAuth=true;
        $mail->SMTPKeepAlive=true;
        $mail->SMTPSecure= 'ssl';
        $mail->Host='smtp.qq.com';
        $mail->Port=465;
        //填写你的email账号和密码
        $mail->Username='250321160@qq.com';
        $mail->Password="xyrglkjyqevxbhfd";#注意这里也要填写授权码就是我在上面QQ邮箱开启SMTP中提到的，不能填邮箱登录的密码哦。
        //设置发送方，最好不要伪造地址
        $mail->From='250321160@qq.com';
        $mail->FromName='汇玩空间网';
        $mail->Subject='汇玩空间网发来的一封邮件';
        $mail->AltBody=$body;
        $mail->WordWrap=50; // set word wrap
        $mail->MsgHTML($body);
        //设置回复地址
        $mail->AddReplyTo('250321160@qq.com','汇玩空间网');
        //添加附件，此处附件与脚本位于相同目录下否则填写完整路径
        //附件的话我就注释掉了
        #$mail->AddAttachment("attachment.jpg");
        #$mail->AddAttachment("attachment.zip");
        //设置邮件接收方的邮箱和姓名
        $mail->AddAddress($email,$username);
        //使用HTML格式发送邮件
        $mail->IsHTML(true);
        //通过Send方法发送邮件
        //根据发送结果做相应处理
        if(!$mail->Send()){
            //echo "Mailer Error:".$mail->ErrorInfo;
            return false;
        }else{
            //echo "Message has been sent";
            return true;
        }
    }
    
    


    
    
}