<?php
/**
*活动评论类
*@author Joker-Long
*编写日期：2016年8月15日下午5:31:51
*/
class Comment
{
    /**
     * 检测用户评论输入的数据
     * @param array $arr
     * @return boolean
     */
    private static function validateComment($arr){
        /*if(!($data['email']=filter_input(INPUT_POST,'email',FILTER_VALIDATE_EMAIL))){
            $errors['email']='请输入合法邮箱';
        }
        if(!($data['url']=filter_input(INPUT_POST,'url',FILTER_VALIDATE_URL))){
            $url='';
        }*/
        $sId = $arr['sId'];//活动id
        $uId1 = $arr['uId1'];//评论人的id
        if((is_numeric($sId)) && (is_numeric($uId1))){
            //检查要评论的活动和评论人是否存在
            //这样是安全一点了，但是效率会下降
            $sql1 = "select id from hw_situation where id={$sId}";
            $sql2 = "select id from hw_user where id={$uId1}";
            global $db_obj;
            $row1 = $db_obj->fetchOne($sql1);
            $row2 = $db_obj->fetchOne($sql2);
            if($row1 && $row2){
               return true; //上传参数正确
            }else{
                return false;//参数错误
            }
            echo '22';
        }else{
            echo '22';
            return false;//参数错误
        }
    }
    
    /**
     * 检测用户回复输入的数据
     * @param array $arr
     * @return boolean
     */
    private static function validateReply($arr){
        $sId = $arr['sId'];//活动id
        $cId = $arr['cId'];//评论的id
        $uId1 = $arr['uId1'];//回复人的id
        $uId2 = $arr['uId2'];//被回复人的id
        if((is_numeric($sId)) && (is_numeric($cId)) && (is_numeric($uId1)) && (is_numeric($uId2))){
            //检查要回复的评论和评论人是否存在
            //这样是安全一点了，但是效率会下降
            $sql1 = "select id from hw_situation where id={$sId}";
            $sql2 = "select id from hw_comment where id={$cId} and uId1={$uId2}";//被回复人的评论是否存在
            $sql3 = "select id from hw_user where id={$uId1}";
            $sql4 = "select id from hw_user where id={$uId2}";
            global $db_obj;
            $row1 = $db_obj->fetchOne($sql1);
            $row2 = $db_obj->fetchOne($sql2);
            $row3 = $db_obj->fetchOne($sql3);
            $row4 = $db_obj->fetchOne($sql4);
            if($row1 && $row2 && $row3 && $row4){
                return true; //上传参数正确
            }else{
                return false;//参数错误
            }
        }else{
            return false;//参数错误
        }
    }
    
    /**
     * 过滤用户输入的特殊字符
     * @param string $str
     * @return boolean|string
     */
    public static function validate_str($str){
        if(mb_strlen($str,'UTF8')<1){
            return false;
        }
        $str=nl2br(htmlspecialchars($str,ENT_QUOTES));
        return $str;
    }
    
    /**
     * 插入评论
     * @return string
     */
    public static function insertComment($arr){
        if(self::validateComment($arr)){
            global $db_obj;
            $table = 'hw_comment';
            $arr['time'] = time();//评论时间
            $link = $db_obj->insert($table, $arr);
            if($link){
                return 1;//插入成功
            }else{
                return 2;//插入失败
            }
        }else{
            return 3;//参数错误
        }
    }
    
    /**
     * 插入评论的回复
     * @return string
     */
    public static function insertReply($arr){
            if(self::validateReply($arr)){
            global $db_obj;
            $table = 'hw_comment';
            $arr['time'] = time();//评论时间
            $link = $db_obj->insert($table, $arr);
            if($link){
                return 1;//插入成功
            }else{
                return 2;//插入失败
            }
        }else{
            return 3;//参数错误
        }
    }
    
    /**
     * 删除活动的评论记录
     * @param string $sId
     * @return boolean
     */
    public static function del($arr){
        global $db_obj;
        $table = 'hw_comment';
        $where = "sId={$arr['sId']} and uId1={$arr['uId']}";
        $link = $db_obj->delete($table,$where);
        if($link){
            return true;//删除评论成功
        }else{
            return false;//删除平林失败
        }
    }
    
    public static function getAllComment($sId){
        $sql = "select * from hw_comment where sId={$sId}";
        global $db_obj;
        //获取所有评论
        $rows = $db_obj->fetchAll($sql);
        return $rows;
    }
}