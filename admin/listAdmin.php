<?php 
require_once '../adminInclude.php';
Admin::checkAdminLogined();
$pageSize=2;
@$page=$_REQUEST['page']?(int)$_REQUEST['page']:1;
$rows=Admin::getAdminByPage($page,$pageSize);
if(!$rows){
    Common::alertMes("sorry,没有管理员,请添加！", "addAdmin.php");
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>-.-</title>
<link rel="stylesheet" href="styles/backstage.css">
</head>

<body>

	<!--表格-->
	<table class="table" cellspacing="0" cellpadding="0">
		<thead>
			<tr>
				<th width="15%">编号</th>
				<th width="25%">管理员名称</th>
				<th width="30%">管理员邮箱</th>
				<th>操作</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($rows as $row):?>
				<tr>
					<!-- 这里的id和for里面的c1需要循环出来 -->
					<td><input type="checkbox" id="c1" class="check"><label for="c1" class="lable"></label><?php echo $row['id'];?></td>
					<td><?php echo $row['adminName'];?></td>
					<td><?php echo $row['email'];?></td>
					<td align="center"><input type="button" value="修改" class="btn" onclick="editAdmin(<?php echo $row['id'];?>)"><input type="button" value="删除" class="btn" onclick="delAdmin(<?php echo $row['id'];?>)"></td>
				</tr>
			<?php endforeach;?>
			<?php if($totalRows>$pageSize):?>
			<tr>
				<td colspan="4"><?php echo Page::show($page, $totalPage)?></td>
			</tr>
			<?php endif;?>
		</tbody>
	</table>
</body>
<script type="text/javascript">
	function editAdmin(id){
			window.location="editAdmin.php?id="+id;
	}
	function delAdmin(id){
			if(window.confirm("您确定要删除吗？删除之后不可以恢复哦！")){
				window.location="doAdminAction.php?act=delAdmin&id="+id;
			}//因为删除操作不用在页面中进行，所以直接跳转到doAdminAction.php页面
	}
</script>
</html>