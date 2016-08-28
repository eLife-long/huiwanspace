<?php
require_once '../include.php';
$row = checkUserExist();
/*$arr['username'] = stripslashes(trim($_POST['username']));//stripslashes — 反引用一个引用字符串;//trim去除两边空白
if(get_magic_quotes_gpc()){
    $arr['username']=stripslashes($arr['username']);
}
//检测用户名是否存在
$sql = "select * from eLife_user where username='{$arr['username']}'";
$row = fetchOne($sql);
*/
if($row){
	echo '<script>alert("用户名已存在，请换个其他的用户名");window.history.go(-1);</script>';
	exit;
}
exit;

$arr['password'] = md5(trim($_POST['password']));
$arr['email'] = trim($_POST['email']);
$arr['regTime'] = time();

$arr['token'] = md5($arr['username'].$arr['password'].$arr['regTime']); //创建用于激活识别码
$arr['token_exptime'] = time()+60*60*24;//过期时间为24小时后
$table = 'eLife_user';
$link = insert($table, $arr);

if($link){//写入成功，发邮件
    include_once "class.phpmailer.php";
    include_once "class.smtp.php";
    //获取一个外部文件的内容
    $mail=new PHPMailer();
    $mail->Charset='UTF-8';
    $body="亲爱的".$arr['username']."：<br/>感谢您在我站注册了新帐号。<br/>请点击链接激活您的帐号。<br/><a href='http://49.140.166.99:8080/eLife/test/active.php?verify=".$arr['token']."' target='_blank'>http://49.140.166.99:8080/eLife/test/active.php?verify=".$arr['token']."</a><br/>如果以上链接无法点击，请将它复制到你的浏览器地址栏中进入访问，该链接24小时内有效。<br/>如果此次激活请求非你本人所发，请忽略本邮件。<br/><p style='text-align:right'>-------- Hellwoeba.com 敬上</p>";
    //设置smtp参数
    $mail->IsSMTP();
    $mail->SMTPAuth=true;
    $mail->SMTPKeepAlive=true;
    $mail->SMTPSecure= "ssl";
    $mail->Host="smtp.qq.com";
    $mail->Port=465;
    //填写你的email账号和密码
    $mail->Username="250321160@qq.com";
    $mail->Password="xyrglkjyqevxbhfd";#注意这里也要填写授权码就是我在上面QQ邮箱开启SMTP中提到的，不能填邮箱登录的密码哦。
    //设置发送方，最好不要伪造地址
    $mail->From="250321160@qq.com";
    $mail->FromName="隗**";
    $mail->Subject="隗**发来的一封邮件";
    $mail->AltBody=$body;
    $mail->WordWrap=50; // set word wrap
    $mail->MsgHTML($body);
    //设置回复地址
    $mail->AddReplyTo("250321160@qq.com","隗**");
    //添加附件，此处附件与脚本位于相同目录下否则填写完整路径
    //附件的话我就注释掉了
    #$mail->AddAttachment("attachment.jpg");
    #$mail->AddAttachment("attachment.zip");
    //设置邮件接收方的邮箱和姓名
    $mail->AddAddress("{$arr['email']}","hello");
    //使用HTML格式发送邮件
    $mail->IsHTML(true);
    //通过Send方法发送邮件
    //根据发送结果做相应处理
    if(!$mail->Send()){
        echo "Mailer Error:".$mail->ErrorInfo;
    }else{
        echo "Message has been sent";
    }
}