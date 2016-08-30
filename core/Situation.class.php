<?php
/**
*用户活动操作类
*tags
*@author Joker-Long
*编写日期：2016年8月13日下午3:57:07
*/
class Situation
{
    /**
     * 添加用户活动
    * @return string
    */
    public static function addSituation(){
        global $db_obj;
        $arr=$_POST;
        foreach ($arr as $temp){
            if($temp==""){
                $mes="填写不全，请重新操作。<a href='addSituation.php' target='mainFrame'>重新添加</a>";
                return $mes;
            }
        }
        $arr['sSignupBtime']=strtotime($arr['sSignupBtime']);//将任何英文文本的日期时间描述解析为时间戳。
        $arr['sSignupEtime']=strtotime($arr['sSignupEtime']);
        $arr['sBtime']=strtotime($arr['sBtime']);
        $arr['sEtime']=strtotime($arr['sEtime']);
        $arr['sPubtime']=time();
    
        $path="../uploads";
        $uploadFiles=Upload::uploadFile($path);
        //存储上传的图片
        Image::addPicture($uploadFiles,$path);
    
        $res=$db_obj->insert("hw_situation", $arr);
        $table = "hw_situation";
        $sId=$db_obj->getTableMaxId($table);
        if($sId){
            foreach (@$uploadFiles as $uploadFile) {
                $arr1['sId'] = $sId;
                $arr1['albumPath'] = $uploadFile['name'];
                $link = Album::addAlbum($arr1);//判断一下是否添加成功
            }
            $mes="<p>添加成功！！</p><a href='addSituation.php' target='mainFrame'>继续添加</a>|<a href='listSituation.php' target='mainFrame'>查看商品列表</a>";
        }else{
            //删除上传的图片
            Image::deletePicture($uploadFiles);
            $mes="<p>添加失败!</p><a href='addSituation.php' target='mainFrame'>重新添加</a>";
        }
        return $mes;
    }
    
    /**
     * 自动添加用户活动
     * @return string
     */
    public static function autoAddSituation(){
        global $db_obj;
        $users = Admin::getAllUser();
        $schools = School::getAllSchool();
        $pos1 = array_rand($users);
        $pos2 = array_rand($schools);
        $arr['uId'] = $users[$pos1]['id'];
        $arr['sId'] = $schools[$pos2]['id'];
        $campuses = Campus::getCampusSchoolId($arr['sId']);
        $pos3 = @array_rand($campuses);
        $arr['cId'] = $campuses[$pos3]['id'];
        $arr['sTitle'] = Str::getStringOfChinese(rand(3, 10));
        $arr['sDesc'] = Str::getStringOfChinese(rand(30, 50));
        $arr['sPosition'] = Str::getStringOfChinese(rand(5, 13));
        $arr['sGatherPosition'] = Str::getStringOfChinese(rand(5, 13));
        $arr['sNumber'] = rand(2, 50);
        $arr['sCurrentNumber'] = rand(0, $arr['sNumber']);
        $arr['sPubtime'] = time();
        $arr['sSignupBtime'] = time();
        $arr['sSignupEtime'] = time()+rand(2, 30)*24*60*60;
        $arr['sBtime'] = $arr['sSignupEtime'];
        $arr['sEtime'] = time()+rand(1, 72)*60*60;
        //print_r($arr);
        $res=$db_obj->insert("hw_situation", $arr);
        $table = "hw_situation";
        $sId= $db_obj->getInsertId();
        //echo $sId,'<hr>';
        $arr1['sId'] = $sId;
        if($res){
            //$path = "F:/11111/";  //要获取的目录
             //$path = "/home/long/imagesexp/";
             $path = FILE_PATH;
	     $uploadFiles = Upload::getPictureFromFile($path,rand(2, 10));
		if(is_array($uploadFiles)&&$uploadFiles){
                foreach ($uploadFiles as $key=>$uploadFile){
           $ext = Str::getExt($uploadFile);      //得到图片的扩展名
             $fileName = Str::getUniName().'.'.$ext;  //得到唯一文件名
                   Image::thumb($uploadFile,"../uploads/".$fileName);
		//	Image::thumb($uploadFile,"../image_50/".$fileName,50,50);
                  //  Image::thumb($uploadFile,"../image_220/".$fileName,220,220);
                   // Image::thumb($uploadFile,"../image_350/".$fileName,350,350);
                   // Image::thumb($uploadFile,"../image_800/".$fileName,800,800);
                    $table = "hw_situation";
                    $arr1['sId'] = $sId;
                    $arr1['albumPath'] = $fileName;
                    $link = Album::addAlbum($arr1);//判断一下是否添加成功
   $mes="添加活动成功";
                }
            }else{
                $mes="添加图片失败";
            }
        }else{
            $mes = "添加活动失败";
        }
        return $mes;
    }
    
    
    /**
     * 编辑用户活动
     * @return string
     */
    public static function editSituation($id){
        global $db_obj;
        $where="id={$id}";
        $arr=$_POST;
        foreach ($arr as $temp){
            if($temp==""){
                $mes="填写不全，请重新操作。<a href='addSituation.php' target='mainFrame'>重新添加</a>";
                return $mes;
            }
        }
        $arr['sSignupBtime']=strtotime($arr['sSignupBtime']);//将任何英文文本的日期时间描述解析为时间戳。
        $arr['sSignupEtime']=strtotime($arr['sSignupEtime']);
        $arr['sBtime']=strtotime($arr['sBtime']);
        $arr['sEtime']=strtotime($arr['sEtime']);
        //$sql = "select sPubtime from hw_situation where {$where}";//更新后发布时间
        //$row1 = fetchOne($sql);
        $path="../uploads";
        $uploadFiles=Upload::uploadFile($path);
        //存储上传的图片
        Image::addPicture($uploadFiles,$path);
    
        $res= $db_obj->update("hw_situation", $arr,$where);
        if($res){
            foreach ($uploadFiles as $uploadFile) {
                $arr1['sId'] = $id;
                $arr1['albumPath'] = $uploadFile['name'];
                $link =  Album::addAlbum($arr1);//判断一下是否添加成功
            }
            $mes="<p>更新成功！！</p><a href='listSituation.php' target='mainFrame'>查看活动列表</a>";
        }else{
            Image::deletePicture($uploadFiles);
            $mes="<p>更新失败!</p><a href='editSituation.php' target='mainFrame'>查看活动列表</a>";
        }
        return $mes;
    }
    
    /**
     * 删除用户活动批量删除
     * string $id 是一个字符串 ，如"1,2,3"或"2"
     * @return string
     */
    public static function delSituation($id){
        global $db_obj;
        //先删除活动图片
        Album::delSituationImage($id);
        //再删除活动表hw_situation中的记录
        $where="id in ($id)";
        $table = "hw_situation";
        $link =  $db_obj->delete($table,$where);
        if($link){
            $mes = "删除成功";
        }else{
            $mes = "删除失败";
        }
        return $mes;
    }
    
    /**
     * 删除用户活动批量删除
     * string $id 是一个字符串 ，如"1,2,3"或"2"
     * @return string
     */
    public static function del($id){
        global $db_obj;

        //再删除活动表hw_situation中的记录
        $where="id in ($id)";
        $table = "hw_situation";
        $link =  $db_obj->delete($table,$where);
        if($link){
            return true;
        }else{
            return false;
        }
    }
    /**
     * 通过页码获得校区的数量
     * @param int $page
     * @param int $pageSize
     * @return multitype:
     */
    public static function getSituationByPage($page,$pageSize=5,$where=null){
        global $db_obj;
        $sql="select s.id from hw_situation as s join hw_user u on s.uId=u.id {$where}";
        //echo $sql,'<hr>';
        global $result;
        global $totalRows;
        $totalRows= $db_obj->getResultNum($sql);
        global $totalPage;
        $totalPage=ceil($totalRows/$pageSize);
        if($page<1||$page==null||!is_numeric($page)){
            $page=1;
        }
        if($page>$totalPage){
            $result['status'] = 3;
            $result['message'] = '没有更多数据';
            return ;
        }
    
        //$page=$totalPage;
        $offset=($page-1)*$pageSize;
        $sql="select s.id,s.sTitle,s.sDesc,s.sPubtime,s.sPosition,s.sGatherPosition,s.sSignupBtime,s.sSignupEtime,s.sBtime,s.sEtime,s.sNumber,s.sCurrentNumber,u.username,u.sex,u.face from hw_situation as s join hw_user u on s.uId=u.id {$where} limit {$offset},{$pageSize}";
        //echo $sql,'<hr>';
        @$rows= $db_obj->fetchAll($sql);
        if(!$rows){
            $result['status'] = 2;
            $result['message'] = '获取数据失败';
            return ;
        }
        $result['status'] = 1;
        $result['message'] = '获取数据成功';
        return $rows;
    }
    
    /**
     * 获得所有活动
     *
     * @param int $pageSize
     * @return multitype:
     */
    public static function getSituation($pageSize=5,$where=null,$orderBy){
        global $db_obj;
        //$sql="select s.*,u.username,u.sex,u.face from hw_situation as s join hw_user u on s.uId=u.id {$where} limit {$pageSize}";
        $sql="select s.*,u.username,u.sex,u.face,c.cName,s2.sName from hw_situation as s join(hw_campus as c join hw_school s2 on c.sId=s2.id) on s.cId=c.id join hw_user u on s.uId=u.id {$where} {$orderBy} limit {$pageSize}";
        //echo $sql;
        $rows= $db_obj->fetchAll($sql);
        return $rows;
    }
    
    /**
     * 通过活动id获得该活动详情
     *
     * @param int $pageSize
     * @return multitype:
     */
    public static function getOneSituation($id){
        global $db_obj;
        //$sql="select s.*,u.username,u.sex,u.face from hw_situation as s join hw_user u on s.uId=u.id {$where} limit {$pageSize}";
        $sql="select s.*,u.username,u.sex,u.face,c.cName,s2.sName from hw_situation as s join(hw_campus as c join hw_school s2 on c.sId=s2.id) on s.cId=c.id join hw_user u on s.uId=u.id where s.id={$id}";
        //echo $sql;
        $row = $db_obj->fetchOne($sql);
        return $row;
    } 
    
    /**
     * 增加活动人数
     * @param int $num
     * @return number
     */
    public static function AddJoinNumber($num,$id){
        global $db_obj;
        $sql = "update hw_situation set sCurrentNumber=sCurrentNumber+{$num} where id={$id}";
        $row = mysqli_query($db_obj->connect(), $sql);
        return $row;
    }
    
    public static function AddSituationInfo(&$result1,$i,$row2,$path){
        $result1[$i]['ts_uId'] = urlencode($row2['id']);
        $result1[$i]['ts_username'] = urlencode($row2['username']);
        $result1[$i]['ts_sex'] = urlencode($row2['sex']);
        $result1[$i]['ts_face'] = urlencode($path . $row2['face']);
        $result1[$i]['id'] = urlencode($row2['id']);
    }
    
    public static function isOption(&$result1,$i,$sId){
        if ($_SESSION['uId'] != '') { // 如果登录了,则显示是否赞过等
            $bool = User::checkPraise($_SESSION['uId'], $sId);
            if ($bool) {
                $result1[$i]['isPraise'] = 1; // 赞了
            } else {
                $result1[$i]['isPraise'] = 0; // 没有赞过
            }
            $bool = User::checkCollect($_SESSION['uId'], $sId);
            if ($bool) {
                $result1[$i]['isCollect'] = 1; // 已经收藏
            } else {
                $result1[$i]['isCollect'] = 0; // 还没有收藏
            }
            $bool = User::checkJoin($_SESSION['uId'], $sId);
            if ($bool) {
                $result1[$i]['isJoin'] = 1; // 已经参加
            } else {
                $result1[$i]['isJoin'] = 0; // 还没有参加
            }
        } else { // 否则，默认为没有赞过
            $result1[$i]['isPraise'] = 0; // 没有赞过
            $result1[$i]['isCollect'] = 0; // 还没有收藏
            $result1[$i]['isJoin'] = 0; // 还没有参加
        }
    }
    
    public static function isTransmit($sId){
        global $db_obj;
        $sql = "select isTransmit from hw_situation where id={$sId}";
        $row = $db_obj->fetchOne($sql);
        if($row['isTransmit'] != 0){
            return $row['isTransmit'];
        }else{
            return 0;
        }
    }
    
}
