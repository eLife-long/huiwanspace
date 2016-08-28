<?php 
require_once '../adminInclude.php';
Admin::checkAdminLogined();
$pageSize=10;
@$page=$_REQUEST['page']?(int)$_REQUEST['page']:1;
@$order=$_REQUEST['order']?$_REQUEST['order']:null;
$orderBy=$order?"order by s.".$order:null;
//echo $orderBy;
@$keywords=$_REQUEST['keywords']?$_REQUEST['keywords']:null;
$keywordsBy=$keywords?"where (s.`sTitle` like '%{$keywords}%' OR u.`username` like '%{$keywords}%')":null;
//(a.`aName` like '%{$keywords}%' OR s.`sName` like '%{$keywords}%')
$where="$keywordsBy $orderBy";
//echo $where;
$rows=Situation::getSituationByPage($page, $pageSize,$where);
 //var_dump($rows);
if(!$rows){
    Common::alertMes("sorry,没有活动,请添加！", "addSituation.php");
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>-.-</title>
<link rel="stylesheet" href="styles/backstage.css">
<link rel="stylesheet" href="scripts/jquery-ui/css/ui-lightness/jquery-ui-1.10.4.custom.css" />
<script src="scripts/jquery-ui/js/jquery-1.10.2.js"></script>
<script src="scripts/jquery-ui/js/jquery-ui-1.10.4.custom.js"></script>
<script src="scripts/jquery-ui/js/jquery-ui-1.10.4.custom.min.js"></script>
<script type="text/javascript">
function selectAll(){
	var a = document.getElementsByTagName("input");
	if(a[0].checked){
		for(var i = 0;i<a.length;i++){
			if(a[i].type == "checkbox") 
				a[i].checked = false;
		}
	}else{
		for(var i = 0;i<a.length;i++){
		if(a[i].type == "checkbox") 
			a[i].checked = true;
		}
	}
}
function delAll(){
	var a = document.getElementsByTagName("input");
	if(!a[0].checked){
		for(var i = 0;i<a.length;i++){
			if(a[i].type == "checkbox") 
				a[i].checked = false;
		}
	}else{
		for(var i = 0;i<a.length;i++){
		if(a[i].type == "checkbox") 
			a[i].checked = true;
		}
	}
}
function checkbox()
{
var str=document.getElementsByName("box");
var objarray=str.length;
var chestr="";
for (i=0;i<objarray;i++)
{//牛图库JS特效，http://js.niutuku.com/
  if(str[i].checked == true)
  {
   chestr+=str[i].value+",";
  }
}//牛图库JS特效，http://js.niutuku.com/
//最后一项不要逗号
if(chestr == "")
{
  alert("删除操作请至少选择一项！");
}
	return chestr;
}

</script> 
</head>

<body>
<div id="showDetail"  style="display:none;">

</div>
<div class="details">
                    <div class="details_operation clearfix">
                        <div class="bui_select">
                            <input type="button" value="添&nbsp;&nbsp;加" class="add" onclick="addSituation()">
                        </div>
                        <div class="">                                
                                <form action="doAdminAction.php?act=autoAddSituation" method="post" enctype="multipart/form-data">
								<table width="5%" border="1" cellpadding="5" cellspacing="0" bgcolor="#ffffff">
                            	<tr>
                            		<td><input type="text" name="number" placeholder="请输入要添加的活动数量"/></td>
                            		<td><input type="submit"  value="添加自动添加活动"/></td>
                            	</tr>                           
                            	</table>
                            	</form>
                        </div>
                        
                        <div class="fr">
                            <div class="text">
                                <span>发布次数：</span>
                                <div class="bui_select">
                                    <select name="" id="" class="select" onchange="change(this.value)">
                                    	<option>-请选择-</option>
                                        <option value="sNumber asc">从低到高</option>
                                        <option value="sNumber desc">从高到低</option>
                                    </select>
                                </div>
                            </div>
                            <div class="text">
                                <span>发布时间：</span>
                                <div class="bui_select">
                                    <select name="" id="" class="select" onchange="change(this.value)">
                                        <option>-请选择-</option>
                                        <option value="sPubtime desc">最新发布</option>
                                        <option value="sPubtime asc">历史发布</option>
                                    </select>
                                </div>
                            </div>
                            <div class="text">
                                <span>搜索</span>
                                <input type="text" value="" class="search" id="search" onkeypress="search()">
                            </div>
                        </div>
                    </div>
                    <!--表格-->
                    <table width="100%" class="table" cellspacing="0" cellpadding="0">
                        <thead>
                            <tr>
                                <th width="10%">编号</th>
                                <th width="20%">活动标题</th>
                                <th width="10%">发布者</th>
                                <th width="10%">活动当前参加人数</th>
                                <th width="10%">活动发布时间</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
						<?php foreach ($rows as $row):?>
                            <tr>
                                <!--这里的id和for里面的c1 需要循环出来-->
                                <td><input type="checkbox" name="box" id="c1" class="check" value="<?php echo $row['id'];?>"><label for="c1" class="label"></label><?php echo $row['id'];?></td>
                                <td><?php echo $row['sTitle'];?></td>
                                <td><?php echo $row['username'];?></td>
                                <td><?php echo $row['sNumber'];?></td>
                                <td><?php echo date("Y-m-d H:i:s",$row['sPubtime']);?></td>
                                <td align="center">
                                				<input type="button" value="详情" class="btn" onclick="showDetail(<?php echo $row['id'];?>,'<?php echo $row['sTitle'];?>')"><input type="button" value="修改" class="btn" onclick="editSituation(<?php echo $row['id'];?>)"><input type="button" value="删除" class="btn" onclick="delSituation()">
					                            <div id="showDetail<?php echo $row['id'];?>" style="display:none;">
					                            <!-- 商品详情默认是隐藏的 -->
					                        	<table class="table" cellspacing="0" cellpadding="0">
					                        		<tr>
					                        			<td width="20%" align="right">活动标题</td>
					                        			<td><?php echo $row['sTitle'];?></td>
					                        		</tr>
					                        		<tr>
					                        			<td width="20%"  align="right">发布者</td>
					                        			<td><?php echo $row['username'];?></td>
					                        		</tr>
					                      			<tr>
					                        			<td width="20%"  align="right">活动发布时间</td>
					                        			<td><?php echo date("Y-m-d H:i:s",$row['sPubtime']);?></td>
					                        		</tr>
					                        		<tr>
					                        			<td width="20%"  align="right">活动地点</td>
					                        			<td><?php echo $row['sPosition'];?></td>
					                        		</tr>
					                        		<tr>
					                        			<td width="20%"  align="right">活动报名开始时间</td>
					                        			<td><?php echo date("Y-m-d H:i:s",$row['sSignupBtime']);?></td>
					                        		</tr>
					                        		<tr>
					                        			<td width="20%"  align="right">活动报名结束时间</td>
					                        			<td><?php echo date("Y-m-d H:i:s",$row['sSignupEtime']);?></td>
					                        		</tr>
					                        		<tr>
					                        			<td width="20%"  align="right">活动开始时间</td>
					                        			<td><?php echo date("Y-m-d H:i:s",$row['sBtime']);?></td>
					                        		</tr>
					                        		<tr>
					                        			<td width="20%"  align="right">活动结束时间</td>
					                        			<td><?php echo date("Y-m-d H:i:s",$row['sEtime']);?></td>
					                        		</tr>
					                        		<tr>
					                        			<td width="20%"  align="right">活动限制人数</td>
					                        			<td><?php echo $row['sNumber'];?></td>
					                        		</tr>
					                        		<tr>
					                        			<td width="20%"  align="right">活动当前参加人数</td>
					                        			<td><?php echo $row['sCurrentNumber'];?></td>
					                        		</tr>
					                        		<tr>
					                        			<td width="20%"  align="right">活动图片</td>
					                        			<td>
					                        				<?php $proImgs=Album::getSituationImageBysId($row['id']);
					                        				if($proImgs){
					                        				foreach ($proImgs as $img):
					                        				?>
					                        				<img width="100" height="100" src="../uploads/<?php echo $img['albumPath'];?>" alt=""/> &nbsp;&nbsp;
					                        				<?php endforeach;?>
					                        				<?php 
					                        				}
					                        				else{echo "该活动还没有上传图片，请先上传";}
					                        				?>
					                        			</td>
					                        		</tr>
					                        	</table>
					                        	<span style="display:block;width:80%; ">
					                        	商品描述<br/>
					                        	<?php echo $row['sDesc'];?>
					                        	</span>
					                        </div>
                                
                                </td>
                            </tr>
                          	<?php endforeach;?>                          	
                           	<?php if($totalRows>$pageSize):?>
							<tr>
								<td colspan="6"><?php echo Page::show($page, $totalPage,"keywords={$keywords}&order={$order}")?></td>
							</tr>
							<?php endif;?>
							
                        </tbody>
                    </table>
                    <input type="button" name="select" onclick="selectAll()" value="全选"/>
                    <input type="button" name="select" onclick="delAll()" value="取消"/>
                    <input type="button" name="del" onclick="delSituation()" value="删除"/>
                </div>
<script type="text/javascript">
function showDetail(id,t){//
	$("#showDetail"+id).dialog({
		  height:"auto",
	      width: "auto",
	      position: {my: "center", at: "center",  collision:"fit"},
	      modal:false,//是否模式对话框
	      draggable:true,//是否允许拖拽
	      resizable:true,//是否允许拖动
	      title:"商品名称："+t,//对话框标题
	      show:"slide",
	      hide:"explode"
	});
}

function addSituation(){
	window.location='addSituation.php';
}
function editSituation(id){
	window.location='editSituation.php?id='+id;
}
function delSituation(){
	
	
	var id = checkbox();//得到已选的项,一个字符串
	  //alert("您先择的是："+id);
	if(id != ""){
		if(window.confirm("您确定要删除吗？删除之后不可以恢复哦！")){
			window.location="doAdminAction.php?act=delSituation&id="+id;
		}//因为删除操作不用在页面中进行，所以直接跳转到doAdminAction.php页面
	}
}
function search(){
	if(event.keyCode==13){
		var val=document.getElementById("search").value;
		window.location="listSituation.php?keywords="+val;
	}
}
function change(val){
	window.location="listSituation.php?order="+val;
}

</script>
</body>
</html>