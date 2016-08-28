<?php 
require_once '../adminInclude.php';
Admin::checkAdminLogined();
$provinces = Province::getAllProvince();
$schools = School::getAllSchool();
$campuses=Campus::getAllCampus();
if(!$campuses){
	Common::alertMes("没有校区，请先添加校区!!", "addCampus.php");
}
$places=Place::getAllPlace();
if(!$places){
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
function changelocation1(locationid)
{
	 var onecount;
	 subcat=new Array(); 
	    <?php $count=0;foreach ($schools as $school):?>
	    subcat[<?php echo $count?>]=new Array("<?php echo $school['sName']?>","<?php
	     
	 echo $school['pId']?>","<?php echo $school['id']?>"); 
	    
	    <?php $count++; endforeach;?>
	   
	  onecount=<?php echo $count;?>;
document.aform.school.length=1;
var provinceId=locationid;
var i;
for(i=0;i<onecount;i++)
{   
if(subcat[i][1]==provinceId)
{
document.aform.school.options[document.aform.school.length]=new Option(subcat[i][0],subcat[i][2]);    }
 }
}

function changelocation2(locationid)
{
	 var onecount;
	 subcat=new Array(); 
	    <?php $count=0;foreach ($campuses as $campus):?>
	    subcat[<?php echo $count?>]=new Array("<?php echo $campus['cName']?>","<?php
	     
	 echo $campus['sId']?>","<?php echo $campus['id']?>"); 
	    
	    <?php $count++; endforeach;?>
	   
	  onecount=<?php echo $count;?>;
document.aform.cId.length=1;
var schoolId=locationid;
var i;
for(i=0;i<onecount;i++)
{   
if(subcat[i][1]==schoolId)
{
document.aform.cId.options[document.aform.cId.length]=new Option(subcat[i][0],subcat[i][2]);    }
 }
}

 function changelocation(locationid)
 {
	 var onecount;
	 subcat=new Array(); 
	    <?php $count=0;foreach ($places as $place):?>
	    subcat[<?php echo $count?>]=new Array("<?php echo $place['pName']?>","<?php
	     
	 echo $place['cId']?>","<?php echo $place['id']?>"); 
	    
	    <?php $count++; endforeach;?>
	   
	  onecount=<?php echo $count;?>;
document.aform.pId.length=1;
 var campusId=locationid;
var i;
 for(i=0;i<onecount;i++)
{   
if(subcat[i][1]==campusId)
{
document.aform.pId.options[document.aform.pId.length]=new Option(subcat[i][0],subcat[i][2]);    }
  }
}
</script>
</head>
<body>
<h3>添加名称</h3>
<form action="doAdminAction.php?act=addActive" method="post" name="aform" enctype="multipart/form-data">
<table width="100%"  border="1" cellpadding="5" cellspacing="0" bgcolor="#cccccc">
	<tr>
		<td align="right">活动名称</td>
		<td><input type="text" name="aName"  placeholder="请输入商品名称"/></td>
	</tr>
	<tr>
		<td align="right">所属场所</td>
		<td>
		<select name="province" id="province" onChange="changelocation1(document.aform.province.options[document.aform.province.selectedIndex].value)" size="1">
          <option >请选择省份</option>
          <?php foreach ($provinces as $province): ?>
          <option value="<?php echo $province['id']; ?>"><?php echo $province['pName']; ?></option>
          <?php endforeach; ?>
        </select>
        <select name="school" id="school" onChange="changelocation2(document.aform.school.options[document.aform.school.selectedIndex].value)" size="1">
          <option >请选择学校</option>
        </select>
        <select name="cId" id="cId" onChange="changelocation(document.aform.cId.options[document.aform.cId.selectedIndex].value)" size="1">
          <option >请选择校区</option>
        </select>
        <select name="pId" id="pId">
  			<option value=''>请选择场所</option>
        </select>
		</td>
	</tr>
	<tr>
		<td align="right">活动次数</td>
		<td><input type="text" name="aTimes"  placeholder="请输入活动发布次数"/></td>
	</tr>
	<tr>
		<td align="right">活动描述</td>
		<td>
			<textarea name="aDesc" id="editor_id" style="width:100%;height:150px;"></textarea>
		</td>
	</tr>
<table width="100%"  border="1" cellpadding="5" cellspacing="0" bgcolor="#cccccc">
	<tr>
		<td colspan="2"><input type="submit"  value="添加活动"/></td>
	</tr>
</table>
</form>
</body>
</html>