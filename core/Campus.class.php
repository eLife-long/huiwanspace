<?php

/**
 *校区操作类
 *tags
 *@author Joker-Long
 *编写日期：2016年8月13日下午1:58:49
 */
class Campus
{

    /**
     * 添加校区
     * 
     * @return string
     */
    public static function addCampus()
    {
        global $db_obj;
        $arr = $_POST;
        if ($db_obj->insert("hw_campus", $arr)) {
            $mes = "添加分类成功!<br/><a href='addCampus.php'>继续添加</a>|<a href='listCampus.php'>查看校区列表</a>";
        } else {
            $mes = "添加分类失败!<br/><a href='addCampus.php'>重新添加</a>|<a href='listCampus.php'>查看校区列表</a>";
        }
        return $mes;
    }

    /**
     * 编辑校区
     * 
     * @param int $id            
     * @return string
     */
    public static function editCampus($where)
    {
        global $db_obj;
        $arr = $_POST;
        if ($db_obj->update("hw_campus", $arr, $where)) {
            $mes = "编辑成功！<br/><a href='listCampus.php'>查看校区列表</a>|<a href='addCampus.php'>添加校区</a>";
        } else {
            $mes = "编辑失败！<br/><a href='editCampus.php'>请重新编辑</a>|<a href='listCampus.php'>查看校区列表</a>";
        }
        return $mes;
    }

    /**
     * 删除校区
     * 
     * @param int $id            
     * @return string
     */
    public static function delCampus($id)
    {
        global $db_obj;
        $res = Place::checkPlaceExist($id); // 判断该分类下是否有商品存在，有则要先删除改分类下的商品才能删除改分类
        if (! $res) {
            $where = "id=" . $id;
            if ($db_obj->delete("hw_campus", $where)) {
                $mes = "删除成功！<br/><a href='listCampus.php'>查看校区列表</a>";
            } else {
                $mes = "删除失败！<br/><a href='listCampus.php'>请重新操作</a>";
            }
            return $mes;
        } else {
            Common::alertMes("不能删除该分类，请先删除该校区下的商店", "listStore.php");
        }
    }

    /**
     * 通过页码获得校区的数量
     * 
     * @param int $page            
     * @param int $pageSize            
     * @return multitype:
     */
    public static function getCampusByPage($page = 1, $pageSize = 10)
    {
        global $db_obj;
        $sql = "select id from hw_campus";
        global $totalRows;
        $totalRows = $db_obj->getResultNum($sql);
        global $totalPage;
        $totalPage = ceil($totalRows / $pageSize);
        if ($page < 1 || $page == null || ! is_numeric($page)) {
            $page = 1;
        }
        if ($page >= $totalPage)
            $page = $totalPage;
        $offset = ($page - 1) * $pageSize;
        $sql = "select * from hw_campus limit {$offset},{$pageSize}";
        $rows = $db_obj->fetchAll($sql);
        return $rows;
    }

    /**
     * 获得所有校区
     * 
     * @return multitype:
     */
    public static function getAllCampus()
    {
        global $db_obj;
        $sql = "select * from hw_campus";
        $rows = $db_obj->fetchAll($sql);
        return $rows;
    }

    /**
     * 通过学校id获取该学校的校区列表
     * 
     * @return multitype:
     */
    public static function getCampusSchoolId($sId)
    {
        global $db_obj;
        $sql = "select * from hw_campus where sId={$sId}";
        $rows = $db_obj->fetchAll($sql);
        return $rows;
    }
}