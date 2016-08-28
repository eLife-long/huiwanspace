<?php
/**
*场所提供的活动标题
*tags
*@author Joker-Long
*编写日期：2016年8月13日下午2:20:02
*/

class Active
{
    /**
     * 添加活动
     * @return string
     */
    public static function addActive(){
        global $db_obj;
        $arr=$_POST;
        unset($arr['province']);
        unset($arr['school']);
        $arr['aPubtime']=time();
        if($db_obj->insert("hw_active",$arr)){
            $mes="添加活动成功!<br/><a href='addActive.php'>继续添加</a>|<a href='listActive.php'>查看活动列表</a>";
        }else{
            $mes="添加活动失败!<br/><a href='addActive.php'>重新添加</a>|<a href='listActive.php'>查看活动列表</a>";
        }
        return $mes;
    }
    
    /**
     * 编辑活动
     * @param int $id
     * @return string
     */
    public static function editActive($where){
        global $db_obj;
        $arr=$_POST;
        if($db_obj->update("hw_active", $arr,$where)){
            $mes="编辑成功！<br/><a href='listActive.php'>查看活动列表</a>|<a href='addActive.php'>添加活动</a>";
        }else{
            $mes="编辑失败！<br/><a href='editActive.php'>请重新编辑</a>|<a href='listActive.php'>查看活动列表</a>";
        }
        return $mes;
    }
    
    /**
     * 删除活动
     * @param int $id
     * @return string
     */
    public static function delActive($id){
        global $db_obj;
        $where = "id=" . $id;
        if ($db_obj->delete("hw_active", $where)) {
            $mes = "删除成功！<br/><a href='listActive.php'>查看校区列表</a>";
        } else {
            $mes = "删除失败！<br/><a href='listActive.php'>请重新操作</a>";
        }
        return $mes;
    }
    /**
     * 通过页码获得校区的活动列表
     * @param int $page
     * @param int $pageSize
     * @return multitype:
     */
    public static function getActiveByPage($page,$pageSize=2,$where=null){
        global $db_obj;
        $sql="select a.id from hw_active as a join (hw_place as p join hw_campus c on p.cId=c.id) on a.pId=p.id {$where}";
        global $totalRows;
        $totalRows=$db_obj->getResultNum($sql);
        global $totalPage;
        $totalPage=ceil($totalRows/$pageSize);
        if($page<1||$page==null||!is_numeric($page)){
            $page=1;
        }
        if($page>=$totalPage)$page=$totalPage;
        $offset=($page-1)*$pageSize;
        $sql="select a.id,a.aName,a.aTimes,a.aPubtime,a.aDesc,p.pName,c.cName from hw_active as a join (hw_place as p join hw_campus c on p.cId=c.id) on a.pId=p.id {$where} limit {$offset},{$pageSize}";
        @$rows=$db_obj->fetchAll($sql);
        return $rows;
    }
    
    /**
     * 获得所有活动
     * @return multitype:
     */
    public static function getAllActive(){
        global $db_obj;
        $sql="select id,aName from hw_active";
        $rows=$db_obj->fetchAll($sql);
        return $rows;
    }
    
    /**
     * 获取校区提供的的快捷活动标题列表
     * @param unknown $cId
     * @return multitype:
     */
    public static function getAcitveByCampusId($cId){
        global $db_obj;
        $sql="select a.id,a.aName,a.aTimes,a.aPubtime,a.aDesc,p.pName,c.cName from hw_active as a join (hw_place as p join hw_campus c on p.cId=c.id) on a.pId=p.id where a.cId={$cId}";
        $rows=$db_obj->fetchAll($sql);
        return $rows;
    }
    
    /**
     * 获取场所提供的的快捷活动标题列表
     * @param unknown $pId
     * @return multitype:
     */
    public static function getAcitveByPlaceId($pId){
        global $db_obj;
        $sql="select a.id,a.aName,a.aTimes,a.aPubtime,a.aDesc,p.pName,c.cName from hw_active as a join (hw_place as p join hw_campus c on p.cId=c.id) on a.pId=p.id where a.pId={$pId}";
        $rows=$db_obj->fetchAll($sql);
        return $rows;
    }
}