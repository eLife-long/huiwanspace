<?php 
require_once '../adminInclude.php';
Admin::checkAdminLogined();
$sId=$_GET['sId'];
$sql="select sTitle from hw_situation where id='{$sId}'";
$row=$db_obj->fetchOne($sql);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Insert title here</title>
</head>
<body>
<h3>添加用户</h3>
<form action="doAdminAction.php?act=addSituationImage&id=<?php echo $sId;?>" method="post" enctype="multipart/form-data">
<table width="70%" border="1" cellpadding="5" cellspacing="0" bgcolor="#cccccc">
	<tr>
		<td align="right">活动名称</td>
		<td><?php echo $row['sTitle'];?></td>
	</tr>
	<tr>
		<td align="right">请选择要上传的活动图片</td>
		<td><input type="file" name="myFile" /></td>
	</tr>
	<tr>
		<td colspan="2"><input type="submit"  value="添加活动图片"/></td>
	</tr>

</table>
</form>
</body>
</html>