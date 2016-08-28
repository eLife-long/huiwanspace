<?php
/**
 *分页类
 *tags
 *@author Joker-Long
 *编写日期：2016年8月13日上午9:32:57
 */
class Province
{
    /**
     * 获取所有省份(按中文拼音排序)
     * @return multitype:|NULL
     */
    public static function getAllProvince(){
        global $db_obj;
        $sql = "SELECT * FROM hw_province order by convert(pName using gbk) asc";
        $rows = $db_obj->fetchAll($sql);
        return $rows;
    }
}