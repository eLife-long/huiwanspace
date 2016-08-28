<?php 
require_once '../adminInclude.php';
Admin::checkAdminLogined();
$pageSize=3;
@$page=$_REQUEST['page']?(int)$_REQUEST['page']:1;
@$order=$_REQUEST['order']?$_REQUEST['order']:null;
$orderBy=$order?"order by s.".$order:null;
@$keywords=$_REQUEST['keywords']?$_REQUEST['keywords']:null;
$keywordsBy=$keywords?"where (s.`sTitle` like '%{$keywords}%' OR u.`username` like '%{$keywords}%')":null;
//(a.`aName` like '%{$keywords}%' OR s.`sName` like '%{$keywords}%')
$where="$keywordsBy $orderBy";
$rows=Situation::getSituationByPage($page, $pageSize,$where);
 //var_dump($rows);
if(!$rows){
    Common::alertMes("sorry,没有该商店或者该校区或者该活动,请添加！", "addSituation.php");
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

<div class="details">
                    <div class="details_operation clearfix">

                        <div class="fr">
                            <div class="text">
                                <span>当前参加人数：</span>
                                <div class="bui_select">
                                    <select id="" class="select" onchange="change(this.value)">
                                    	<option>-请选择-</option>
                                        <option value="sNumber asc" >由低到高</option>
                                        <option value="sNumber desc">由高到底</option>
                                    </select>
                                </div>
                            </div>
                            <div class="text">
                                <span>发布时间：</span>
                                <div class="bui_select">
                                 <select id="" class="select" onchange="change(this.value)">
                                 	<option>-请选择-</option>
                                        <option value="sPubtime desc" >最新发布</option>
                                        <option value="sPubtime asc">历史发布</option>
                                    </select>
                                </div>
                            </div>
                            <div class="text">
                                <span>搜索</span>
                                <input type="text" value="" class="search"  id="search" onkeypress="search()" >
                            </div>
                        </div>
                    </div>
                    <!--表格-->
                    <table class="table" cellspacing="0" cellpadding="0">
                        <thead>
                            <tr>
                                <th width="10%">编号</th>
                                <th width="15%">活动标题</th>
                                <th width="5%">发布者</th>
                                <th>活动图片</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach($rows as $row):?>
                            <tr>
                                <!--这里的id和for里面的c1 需要循环出来-->
                                <td><input  type="checkbox" id="c<?php echo $row['id'];?>" class="check" value=<?php echo $row['id'];?>><label for="c1" class="label"><?php echo $row['id'];?></label></td>
                                
                                <td><?php echo $row['sTitle']; ?></td>
                                <td><?php echo $row['username']; ?></td>
								<td>
					                        			<?php 
					                        			$proImgs=Album::getSituationImageBysId($row['id']);
					                        			if($proImgs):
					                        			foreach($proImgs as $img):
					                        			?>
					                        			
					                        			<img width="100" height="100" src="../uploads/<?php echo $img['albumPath'];?>" alt="" /> &nbsp;&nbsp;
					                        			<?php endforeach;
					                        			else:echo "该活动还没有上传图片，请先添加";
					                        			endif;
					                        			?>
					             </td>
					             <td>
					           		<input type="button" value="添加图片" onclick="doImg('<?php echo $row['id'];?>','addSituationImage')" class="btn"/>
					             	
					             	<br/>
					             	<input type="button" value="添加文字水印" onclick="doImg('<?php echo $row['id'];?>','waterText')" class="btn"/>
					             	
					             	<br/>
					             		<input type="button" value="添加图片水印" onclick="doImg('<?php echo $row['id'];?>','waterPic')" class="btn"/>
					             </td>       
					                        		
					                        		
                                
                                
                            </tr>
                           <?php  endforeach;?>
                           <?php if($totalRows>$pageSize):?>
							<tr>
								<td colspan="5"><?php echo Page::show($page, $totalPage,"keywords={$keywords}&order={$order}")?></td>
							</tr>
							<?php endif;?>
                        </tbody>
                    </table>
                </div>
 <script type="text/javascript">
 		function doImg(id,act){
 	 		if(act = "addSituationImage"){
 	 			window.location="addImageBySituation.php?sId="+id;
 	 		}
 	 	 	else{
				window.location="doAdminAction.php?act="+act+"&id="+id;
 	 	 	}
 	 	}
 		function search(){
 			if(event.keyCode==13){
 				var val=document.getElementById("search").value;
 				window.location="listImageBySituation.php?keywords="+val;
 			}
 		}
 		function change(val){
 			window.location="listImageBySituation.php?order="+val;
 		}
 </script>
</body>
</html>