<?php
require_once '../adminInclude.php';
$act=$_REQUEST['act'];
@$id=$_REQUEST['id'];
if($act=="logout"){
    Admin::logout();
}elseif($act=="addAdmin"){
   $mes=Admin::addAdmin();
}elseif($act=="editAdmin"){
    $where="id={$id}";
    $mes=Admin::editAdmin($where);
}elseif($act=="delAdmin"){
    $where="id={$id}";
    $mes=Admin::delAdmin($where);
}elseif($act=="addCampus"){
    $mes=Campus::addCampus();
}elseif($act=="editCampus"){
    $where="id={$id}";
    $mes=Campus::editCampus($where);
}elseif($act=="delCampus"){
    $mes=Campus::delCampus($id);
}elseif($act=="addPlace"){
    $mes=Place::addPlace();
}elseif($act=="editPlace"){
    $where="id={$id}";
    $mes=Place::editPlace($where);
}elseif($act=="delPlace"){
    $mes=Place::delPlace($id);
}elseif($act=="addActive"){
    $mes=Active::addActive();
}elseif($act=="editActive"){
    $where="id={$id}";
    $mes=Active::editActive($where);
}elseif($act=="delActive"){
    $mes=Active::delActive($id);
}elseif($act=="addUser"){
	$mes=Admin::addUser();
}elseif($act=="delUser"){
		$mes=Admin::delUser($id);
}elseif($act=="editUser"){
	$mes=Admin::editUser($id);	
}elseif($act=="addSituation"){
	$mes=Situation::addSituation();	
}elseif($act=="autoAddSituation"){
    $number = $_POST['number'];
    for($i=1;$i<=$number;$i++){
        Situation::autoAddSituation();
        echo "成功添加添加第{$i}条活动",'<hr>';
    }
}elseif($act=="editSituation"){
	$mes=Situation::editSituation($id);	
}elseif($act=="delSituation"){
    $id = substr($id, 0,strlen($id)-1);//去掉后面的逗号
	$mes=Situation::delSituation($id);	
}elseif($act=="addSituationImage"){
    $mes=Album::addSituationImage($id);
}elseif($act=="waterText"){
	$mes= Image::doWaterText($id);
}elseif($act=="waterPic"){
	$mes= Image::doWaterPic($id);
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Insert title here</title>
</head>
<body>
<?php 
	if($mes){
		echo $mes;
	}
?>
</body>
</html>