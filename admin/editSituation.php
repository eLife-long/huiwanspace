<?php 
require_once '../adminInclude.php';
date_default_timezone_set("utc");//设置脚本的时区
Admin::checkAdminLogined();
$id=$_REQUEST['id'];
$sql = "select sTitle,sDesc,sPubtime,sPosition,sGatherPosition,sSignupBtime,sSignupEtime,sBtime,sEtime,sNumber,sCurrentNumber from eLife_situation where id={$id}";
$row1 = $db_obj->fetchOne($sql);
$rows=Admin::getAllUser();
if(!$rows){
	Common::alertMes("没有相应分类，请先添加分类!!", "addCate.php");
}

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>-.-</title>
<link href="styles/global.css"  rel="stylesheet"  type="text/css" media="all" />
<script type="text/javascript" charset="utf-8" src="../plugins/kindeditor/kindeditor.js"></script>
<script type="text/javascript" charset="utf-8" src="../plugins/kindeditor/lang/zh_CN.js"></script>
<script type="text/javascript" src="scripts/jquery-1.6.4.js"></script>
<script>
        KindEditor.ready(function(K) {
                window.editor = K.create('#editor_id');
        });
        $(document).ready(function(){
        	$("#selectFileBtn").click(function(){
        		$fileField = $('<input type="file" name="thumbs[]"/>');
        		$fileField.hide();
        		$("#attachList").append($fileField);
        		$fileField.trigger("click");
        		$fileField.change(function(){
        		$path = $(this).val();
        		$filename = $path.substring($path.lastIndexOf("\\")+1);
        		$attachItem = $('<div class="attachItem"><div class="left">a.gif</div><div class="right"><a href="#" title="删除附件">删除</a></div></div>');
        		$attachItem.find(".left").html($filename);
        		$("#attachList").append($attachItem);		
        		});
        	});
        	$("#attachList>.attachItem").find('a').live('click',function(obj,i){
        		$(this).parents('.attachItem').prev('input').remove();
        		$(this).parents('.attachItem').remove();
        	});
        });
</script>
</head>
<body>
<h3>添加商品</h3>
<form action="doAdminAction.php?act=editSituation&id=<?php echo $id;?>" method="post" enctype="multipart/form-data">
<table width="100%"  border="1" cellpadding="5" cellspacing="0" bgcolor="#cccccc">
	<tr>
		<td align="right">活动标题</td>
		<td><input type="text" name="sTitle"  placeholder="<?php echo $row1['sTitle'];?>"/></td>
	</tr>
	<tr>
		<td align="right">发布者</td>
		<td>
		<select name="uId">
			<?php foreach($rows as $row):?>
				<option value="<?php echo $row['id'];?>"><?php echo $row['username'];?></option>
			<?php endforeach;?>
		</select>
		</td>
	</tr>
	<tr>
		<td align="right">活动地点</td>
		<td><input type="text" name="sPosition"  placeholder="<?php echo $row1['sPosition'];?>"/></td>
	</tr>
		<tr>
		<td align="right">活动报名开始时间</td>
		<td> <input type="datetime-local" name="sSignupBtime" value="<?php echo date("Y-m-d",$row1['sSignupBtime']);?>T<?php echo date("h:m:s",$row1['sSignupBtime']);?>"/></td>
	</tr>
	</tr>
		<tr>
		<td align="right">活动报名结束时间</td>
		<td> <input type="datetime-local" name="sSignupEtime" value="<?php echo date("Y-m-d",$row1['sSignupEtime']);?>T<?php echo date("h:m:s",$row1['sSignupEtime']);?>"/></td>
	</tr>
	</tr>
		<tr>
		<td align="right">活动开始时间</td>
		<td> <input type="datetime-local" name="sBtime" value="<?php echo date("Y-m-d",$row1['sBtime']);?>T<?php echo date("h:m:s",$row1['sBtime']);?>"/></td>
	</tr>
	</tr>
		<tr>
		<td align="right">活动开始时间</td>
		<td> <input type="datetime-local" name="sEtime" value="<?php echo date("Y-m-d",$row1['sEtime']);?>T<?php echo date("h:m:s",$row1['sEtime']);?>"/></td>
	</tr>
	<tr>
		<td align="right">活动集合地点</td>
		<td><input type="text" name="sGatherPosition"  placeholder="<?php echo $row1['sGatherPosition'];?>"/></td>
	</tr>
	<tr>
		<td align="right">活动限制人数</td>
		<td><input type="text" name="sNumber"  placeholder="<?php echo $row1['sNumber'];?>"/></td>
	</tr>
	<tr>
		<td align="right">活动当前参加人数</td>
		<td><input type="text" name="sCurrentNumber"  placeholder="<?php echo $row1['sCurrentNumber'];?>"/></td>
	</tr>
	<tr>
		<td align="right">活动描述</td>
		<td>
			<textarea name="sDesc" id="editor_id" style="width:100%;height:150px;"><?php echo $row1['sDesc'];?></textarea>
		</td>
	</tr>
	<tr>
		<td align="right">活动图片</td>
		<td>
			<a href="javascript:void(0)" id="selectFileBtn">添加附件</a>
			<div id="attachList" class="clear"></div>
		</td>
	</tr>
	<tr>
		<td colspan="2"><input type="submit"  value="发布商品"/></td>
	</tr>
</table>
</form>
</body>
</html>