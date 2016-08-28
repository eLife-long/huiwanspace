<?php 
require_once '../adminInclude.php';
Admin::checkAdminLogined();
$pageSize=2;
@$page=$_REQUEST['page']?(int)$_REQUEST['page']:1;
$rows=Place::getPlaceByPage($page,$pageSize);
if(!$rows){
    Common::alertMes("sorry,还没有场所,请添加！", "addPlace.php");
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
				<th width="15%">场所编号</th>
				<th width="20%">场所名称</th>
				<th width="10%">所属校区</th>
				<th width=30%>场所描述</th>
				<th>操作</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($rows as $row):?>
				<tr>
					<!-- 这里的id和for里面的c1需要循环出来 -->
					<td><input type="checkbox" id="c1" class="check"><label for="c1" class="lable"></label><?php echo $row['id'];?></td>
					<td><?php echo $row['pName'];?></td>
					<td><?php echo $row['cName'];?></td>
					<td><?php echo $row['pDesc'];?></td>
					<td align="center"><input type="button" value="修改" class="btn" onclick="editPlace(<?php echo $row['id'];?>)"><input type="button" value="删除" class="btn" onclick="delPlace(<?php echo $row['id'];?>)"></td>
				</tr>
			<?php endforeach;?>
			<?php if($totalRows>$pageSize):?>
			<tr>
				<td colspan="5"><?php echo Page::show($page, $totalPage)?></td>
			</tr>
			<?php endif;?>
		</tbody>
	</table>
</body>
<script type="text/javascript">
	function editPlace(id){
			window.location="editPlace.php?id="+id;
	}
	function delPlace(id){
			if(window.confirm("您确定要删除吗？删除之后不可以恢复哦！")){
				window.location="doAdminAction.php?act=delPlace&id="+id;
			}//因为删除操作不用在页面中进行，所以直接跳转到doAdminAction.php页面
	}
</script>
</html>