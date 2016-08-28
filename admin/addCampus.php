<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Insert title here</title>
</head>
<body>
<h3>添加校区</h3>
<form action="doAdminAction.php?act=addCampus" method="post">
<table width="70%" border="1" cellpadding="5" cellspacing="0" bgcolor="#cccccc">
	<tr>
		<td align="right">校区名称</td>
		<td><input type="text" name="cName" placeholder="请输入校区名称"/></td>
	</tr>
	<tr>
		<td align="right">校区地址</td>
		<td><input type="text" name="cAddress" placeholder="请输入校区地址"/></td>
	</tr>
	<tr>
		<td	colspan="2"><input type="submit" value="添加校区"/></td>
	</tr>
</table>
</form>
</body>
</html>