<?php
require_once '../adminInclude.php';
Admin::checkAdminLogined();
@$id=$_REQUEST['id'];
$sql="select pName,pDesc from hw_place where id='{$id}'";
$row1=$db_obj->fetchOne($sql);
$rows=Campus::getAllCampus();
if(!$rows){
    Common::alertMes("没有校区，请先添加校区!!", "addCampus.php");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Insert title here</title>
</head>
<body>
<h3>编辑场所</h3>
<form action="doAdminAction.php?act=editPlace&id=<?php echo $id;?>" method="post" enctype="multipart/form-data">
<table width="70%" border="1" cellpadding="5" cellspacing="0" bgcolor="#cccccc">
	<tr>
		<td align="right">场所名称</td>
		<td><input type="text" name="pName" placeholder="<?php echo $row1['pName'];?>"/></td>
	</tr>
	<tr>
		<td align="right">所属校区</td>
		<td>
		<select name="cId">
			<?php foreach($rows as $row):?>
				<option value="<?php echo $row['id'];?>"><?php echo $row['cName'];?></option>
			<?php endforeach;?>
		</select>
		</td>
	</tr>
	<tr>
		<td align="right">场所描述</td>
		<td><input type="text" name="pDesc" placeholder="<?php echo $row1['pDesc'];?>"/></td>
	</tr>
	<tr>
		<td align="right">场所图片</td>
		<td><input type="file" name="myFile" /></td>
	</tr>
	<tr>
		<td colspan="2"><input type="submit"  value="编辑场所"/></td>
	</tr>

</table>
</form>
</body>
</html>