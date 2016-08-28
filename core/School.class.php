<?php

/**
 *学校操作类
 *tags
 *@author Joker-Long
 *编写日期：2016年8月13日下午1:54:39
 */
class School
{

    /**
     * 获取省份下的所有高校(按中文拼音排序)
     * 
     * @param unknown $pId            
     * @return multitype:|NULL
     */
    public static function getSchoolByProvinceId($pId)
    {
        global $db_obj;
        $sql = "SELECT id,pId,sName FROM hw_school where pId={$pId} order by convert(sName using gbk) asc";
        $rows = $db_obj->fetchAll($sql);
        return $rows;
    }

    /**
     * 获取所有高校(按中文拼音排序)
     * 
     * @return multitype:
     */
    public static function getAllSchool()
    {
        global $db_obj;
        $sql = "SELECT id,pId,sName FROM hw_school order by convert(sName using gbk) asc";
        $rows = $db_obj->fetchAll($sql);
        return $rows;
    }
}