<?php
/**
*赞表的操作类
*@author Joker-Long
*编写日期：2016年8月20日上午10:06:26
*/
class Praise
{
    /**
     * 删除活动的用户组
     * @param string $sId
     * @return boolean
     */
    public static function del($arr){
        global $db_obj;
        $table = 'hw_praise';
        $where = "sId={$arr['sId']} and uId={$arr['uId']}";
        $link = $db_obj->delete($table,$where);
        if($link){
            return true;//删除评论成功
        }else{
            return false;//删除平林失败
        }
    }
}