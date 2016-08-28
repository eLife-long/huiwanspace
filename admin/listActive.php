<?php 
require_once '../adminInclude.php';
Admin::checkAdminLogined();
$pageSize=3;
@$page=$_REQUEST['page']?(int)$_REQUEST['page']:1;
@$order=$_REQUEST['order']?$_REQUEST['order']:null;
$orderBy=$order?"order by a.".$order:null;
@$keywords=$_REQUEST['keywords']?$_REQUEST['keywords']:null;
$keywordsBy=$keywords?"where (a.`aName` like '%{$keywords}%' OR p.`pName` like '%{$keywords}%' OR c.`cName` like '%{$keywords}%')":null;
//(a.`aName` like '%{$keywords}%' OR s.`pName` like '%{$keywords}%')
$where="$keywordsBy $orderBy";
$rows=Active::getActiveByPage($page, $pageSize,$where);
 //var_dump($rows);
if(!$rows){
    Common::alertMes("sorry,没有该场所或者该校区或者该活动,请添加！", "addActive.php");
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
</head>

<body>
<div id="showDetail"  style="display:none;">

</div>
<div class="details">
                    <div class="details_operation clearfix">
                        <div class="bui_select">
                            <input type="button" value="添&nbsp;&nbsp;加" class="add" onclick="addActive()">
                        </div>
                        <div class="fr">
                            <div class="text">
                                <span>发布次数：</span>
                                <div class="bui_select">
                                    <select name="" id="" class="select" onchange="change(this.value)">
                                    	<option>-请选择-</option>
                                        <option value="aTimes asc">从低到高</option>
                                        <option value="aTimes desc">从高到低</option>
                                    </select>
                                </div>
                            </div>
                            <div class="text">
                                <span>发布时间：</span>
                                <div class="bui_select">
                                    <select name="" id="" class="select" onchange="change(this.value)">
                                        <option>-请选择-</option>
                                        <option value="aPubtime desc">最新发布</option>
                                        <option value="aPubtime asc">历史发布</option>
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
                                <th width="20%">活动名称</th>
                                <th width="10%">所属场所</th>
                                <th width="10%">所属校区</th>
                                <th width="10%">活动发布次数</th>
                                <th width="10%">活动发布时间</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
						<?php foreach ($rows as $row):?>
                            <tr>
                                <!--这里的id和for里面的c1 需要循环出来-->
                                <td><input type="checkbox" id="c1" class="check" value=><label for="c1" class="label"></label><?php echo $row['id'];?></td>
                                <td><?php echo $row['aName'];?></td>
                                <td><?php echo $row['pName'];?></td>
                                <td><?php echo $row['cName'];?></td>
                                <td><?php echo $row['aTimes'];?></td>
                                <td><?php echo date("Y-m-d H:i:s",$row['aPubtime']);?></td>
                                <td align="center">
                                				<input type="button" value="详情" class="btn" onclick="showDetail(<?php echo $row['id'];?>,'<?php echo $row['aName'];?>')"><input type="button" value="修改" class="btn" onclick="editActive(<?php echo $row['id'];?>)"><input type="button" value="删除" class="btn" onclick="delActive(<?php echo $row['id'];?>)">
					                            <div id="showDetail<?php echo $row['id'];?>" style="display:none;">
					                            <!-- 商品详情默认是隐藏的 -->
					                        	<table class="table" cellspacing="0" cellpadding="0">
					                        		<tr>
					                        			<td width="20%" align="right">活动名称</td>
					                        			<td><?php echo $row['aName'];?></td>
					                        		</tr>
					                        		<tr>
					                        			<td width="20%"  align="right">所属场所</td>
					                        			<td><?php echo $row['pName'];?></td>
					                        		</tr>
					                        		<tr>
					                        			<td width="20%"  align="right">活动发布次数</td>
					                        			<td><?php echo $row['aTimes'];?></td>
					                        		</tr>
					                      			<tr>
					                        			<td width="20%"  align="right">活动发布时间</td>
					                        			<td><?php echo date("Y-m-d H:i:s",$row['aPubtime']);?></td>
					                        		</tr>
					                        	</table>
					                        	<span style="display:block;width:80%; ">
					                        	活动描述<br/>
					                        	<?php echo $row['aDesc'];?>
					                        	</span>
					                        </div>
                                
                                </td>
                            </tr>
                          	<?php endforeach;?>
                           	<?php if($totalRows>$pageSize):?>
							<tr>
								<td colspan="7"><?php echo Page::show($page, $totalPage,"keywords={$keywords}&order={$order}")?></td>
							</tr>
							<?php endif;?>
                        </tbody>
                    </table>
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

function addActive(){
	window.location='addActive.php';
}
function editActive(id){
	window.location='editActive.php?id='+id;
}
function delActive(id){
	if(window.confirm("您确定要删除吗？删除之后不可以恢复哦！")){
		window.location="doAdminAction.php?act=delActive&id="+id;
	}//因为删除操作不用在页面中进行，所以直接跳转到doAdminAction.php页面
}
function search(){
	if(event.keyCode==13){
		var val=document.getElementById("search").value;
		window.location="listActive.php?keywords="+val;
	}
}
function change(val){
	window.location="listActive.php?order="+val;
}

</script>
</body>
</html>