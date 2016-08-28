<?php
/**
*活动用户组的操作类
*@author Joker-Long
*编写日期：2016年8月18日下午3:59:25
*/
class Group
{
    /**
     * 删除活动的用户组
     * @param string $sId
     * @return boolean
     */
    public static function del($arr){
        global $db_obj;
        $table = 'hw_group';
        $where = "sId={$arr['sId']} and uId={$arr['uId']}";
        echo $where;
        $link = $db_obj->delete($table,$where);//要删除的记录不存在也返回true
        if($link){
            return true;//删除评论成功
        }else{
            return false;//删除平林失败
        }
    }
}