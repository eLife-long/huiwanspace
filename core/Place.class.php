<?php
/**
*校区场所操作类
*tags
*@author Joker-Long
*编写日期：2016年8月13日下午2:14:18
*/
class Place
{
    /**
     * 添加校区场所
     * @return string
     */
    public static function addPlace(){
        global $db_obj;
        $arr=$_POST;
        $uploadFile=Upload::uploadFile("../uploads");
        if($uploadFile&&is_array($uploadFile)){
            $arr['pImg']=$uploadFile[0]['name'];
        }else{
            return "添加失败<a href='addPlace.php'>重新添加</a>";
        }
        if($db_obj->insert("hw_place", $arr)){
            $mes="添加成功!<br/><a href='addPlace.php'>继续添加</a>|<a href='listPlace.php'>查看列表</a>";
        }else{
            $filename="../uploads/".$uploadFile[0]['name'];
            if(file_exists($filename)){
                unlink($filename);
            }
            $mes="添加失败!<br/><a href='addPlace.php'>重新添加</a>|<a href='listPlace.php'>查看列表</a>";
        }
        return $mes;
    }
    
    /**
     * 编辑校区场所
     * @param int $id
     * @return string
     */
    public static function editPlace($where){
        global $db_obj;
        $arr=$_POST;
        $uploadFile=Upload::uploadFile("../uploads");
        if($uploadFile&&is_array($uploadFile)){
            $arr['pImg']=$uploadFile[0]['name'];
        }else{
            return "编辑失败<a href='editPlace.php'>重新编辑</a>";
        }
        if($db_obj->update("hw_place",$arr,$where)){
            $mes="编辑成功!<br/><a href='listPlace.php'>查看列表</a>";
        }else{
            $mes="添加失败!<br/><a href='editPlace.php'>重新编辑</a>|<a href='listPlace.php'>查看列表</a>";
        }
        return $mes;
    }
    
    /**
     * 删除校区场所
     * @param int $id
     * @return string
     */
    public static function delPlace($id){
        global $db_obj;
        $sql="select pImg from hw_place where id=".$id;
        $row=$db_obj->fetchOne($sql);
        $sImg=$row['pImg'];
        if(file_exists("../uploads/".$sImg)){
            unlink("../uploads/".$sImg);
        }
        if($db_obj->delete("hw_place","id={$id}")){
            $mes="删除成功!<br/><a href='listPlace.php'>查看用户列表</a>";
        }else{
            $mes="删除失败!<br/><a href='listPlace.php'>请重新删除</a>";
        }
        return $mes;
    }
    /**
     * 通过分页得到所有校区场所
     * @param int $page
     * @param int $pageSize
     * @return multitype:
     */
    public static function getPlaceByPage($page,$pageSize,$where=null){
        global $db_obj;
        $sql="select p.id from hw_place as p {$where}";
        global $totalRows;
        $totalRows=$db_obj->getResultNum($sql);
        global $totalPage;
        $totalPage=ceil($totalRows/$pageSize);
        if($page<1||$page==null||!is_numeric($page)){
            $page=1;
        }
        if($page>$totalPage)
            return NULL;
            //$page=$totalPage;
            $offset=($page-1)*$pageSize;
            $sql="select p.id,p.pName,p.pDesc,c.cName from hw_place as p join hw_campus c on p.cId=c.id {$where} limit {$offset},{$pageSize}";
            @$rows=$db_obj->fetchAll($sql);
            return $rows;
    }
    
    
    
    
    /**
     * 检查校区下是否有校区场所
     * @param int $cId
     * @return array
     */
    public static function checkPlaceExist($cId){
        global $db_obj;
        $sql="select * from hw_place where cId={$cId}";
        $rows=$db_obj->fetchAll($sql);
        return $rows;
    }
    
    
    
    /**
     *得到所有校区场所
     * @return array
     */
    public static function getAllPlace(){
        global $db_obj;
        $sql="select id,pName,cId from hw_place";
        $rows=$db_obj->fetchAll($sql);
        return $rows;
    }
    
    /**
     *通过校区id得到校区所有校区场所
     * @return array
     */
    public static function getPlaceByCampusId($cId){
        global $db_obj;
        $sql="select p.id,p.pName,c.cName from hw_place as p join hw_campus c on p.cId=c.id where p.cId={$cId}";
        $rows=$db_obj->fetchAll($sql);
        return $rows;
    }
}