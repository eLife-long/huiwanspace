<?php 
require_once '../adminInclude.php';
@$id=$_REQUEST['id'];
$sql="select id,adminName,password,email from hw_admin where id='{$id}'";

$row=$db_obj->fetchOne($sql);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Insert title here</title>
</head>
<body>
<h3>编辑管理员</h3>
<form action="doAdminAction.php?act=editAdmin&id=<?php echo $id;?>" method="post">
<table width="70%" border="1" cellpadding="5" cellspacing="0" bgcolor="#cccccc">
	<tr>
		<td align="right">管理员名称</td>
		<td><input type="text" name="adminName" placeholder="<?php echo $row['adminName'];?>"/></td>
	</tr>
	<tr>
		<td align="right">管理员密码</td>
		<td><input type="password" name="password" value=""/></td>
	</tr>
	<tr>
		<td align="right">管理员邮箱</td>
		<td><input type="text" name="email" placeholder="<?php echo $row['email'];?>"/></td>
	</tr>
	<tr	colspac="2">
		<td><input type="submit" value="编辑管理员"/></td>
	</tr>
</table>
</form>
</body>
</html>