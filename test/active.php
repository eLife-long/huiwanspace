<?php
require_once '../include.php';

$verify = stripslashes(trim($_GET['verify']));
$nowtime = time();

$sql = "select id,token_exptime from eLife_user where activeFlag='0' and `token`='$verify'";
$row = fetchOne($sql);
if($row){
	if($nowtime>$row['token_exptime']){ //30min
		$msg = '您的激活有效期已过，请登录您的帐号重新发送激活邮件.';
	}else{
		$sql = "update eLife_user set activeFlag=0 where id=".$row['id'];
        $table = 'eLife_user';
        $arr['activeFlag'] = 1;
        $where = "id={$row['id']}";
		$link = update($table,$arr,$where);
		if(!$link) die(0);
		$msg = '激活成功！';
	}
}else{
	$msg = 'error.';	
}

echo $msg;
