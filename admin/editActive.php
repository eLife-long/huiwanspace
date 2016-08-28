<?php
require_once '../adminInclude.php';
Admin::checkAdminLogined();
@$id=$_REQUEST['id'];
$sql="select aName,aDesc,aTimes from hw_active where id='{$id}'";
$row1=$db_obj->fetchOne($sql);
$rows=Campus::getAllCampus();
if(!$rows){
	Common::alertMes("没有校区，请先添加校区!!", "addCampus.php");
}
$rows1=Place::getAllPlace();
if(!$rows1){
    Common::alertMes("没有场所，请先添加场所!!", "addPlace.php");
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
<script>
        KindEditor.ready(function(K) {
                window.editor = K.create('#editor_id');
        });
</script>
<script language="JavaScript">
var onecount;
subcat=new Array(); 
   <?php $count=0;foreach ($rows1 as $row):?>
   subcat[<?php echo $count?>]=new Array("<?php echo $row['pName']?>","<?php
    
echo $row['cId']?>","<?php echo $row['id']?>"); 
   
   <?php $count++; endforeach;?>
  
 onecount=<?php echo $count;?>;

 function changelocation(locationid)
 {
document.aform.pId.length=1;
 var cId=locationid;
var i;
 for(i=0;i<onecount;i++)
{   
if(subcat[i][1]==cId)
{
document.aform.pId.options[document.aform.pId.length]=new Option(subcat[i][0],subcat[i][2]);    }
  }
}
</script>
</head>
<body>
<h3>添加商品</h3>
<form action="doAdminAction.php?act=editActive&id=<?php echo $id;?>" method="post" name="aform" enctype="multipart/form-data">
<table width="100%"  border="1" cellpadding="5" cellspacing="0" bgcolor="#cccccc">
	<tr>
		<td align="right">活动名称</td>
		<td><input type="text" name="aName"  placeholder="<?php echo $row1['aName'];?>"/></td>
	</tr>
	<tr>
		<td align="right">所属场所</td>
		<td>
		<select name="cId" id="cId" onChange="changelocation(document.aform.cId.options[document.aform.cId.selectedIndex].value)" size="1">
          <option >请选择校区</option>
          <?php foreach ($rows as $row): ?>
          <option value="<?php echo $row['id']; ?>"><?php echo $row['cName']; ?></option>
          <?php endforeach; ?>
        </select>
        <select name="pId" id="pId">
  			<option>请选择场所</option>
        </select>
		</td>
	</tr>
	<tr>
		<td align="right">活动次数</td>
		<td><input type="text" name="aTimes"  placeholder="<?php echo $row1['aTimes'];?>"/></td>
	</tr>
	<tr>
		<td align="right">商品描述</td>
		<td>
			<textarea name="aDesc" id="editor_id" style="width:100%;height:150px;"><?php echo $row1['aDesc'];?></textarea>
		</td>
	</tr>
<table width="100%"  border="1" cellpadding="5" cellspacing="0" bgcolor="#cccccc">
	<tr>
		<td colspan="2"><input type="submit"  value="编辑活动"/></td>
	</tr>
</table>
</form>
</body>
</html>