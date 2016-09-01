<?php
/**
*用户操作类
*tags
*@author Joker-Long
*编写日期：2016年8月13日下午3:20:39
*/
class User
{
    /**
     * 检测用户是否登录
     */
    public static function checkLogined()
    {
        if (($_SESSION['username'] != '' && $_SESSION['uId'] != '')) {
            return true; // 已经登录
        }
        return false; // 未登录
    }
    
    /**
     * 通过uId检测用户是否登录
     */
    public static function checkLoginedByuId($uId)
    {
        global $db_obj;
        $sql = "select * from hw_userlogined where uId={$uId}";
        $row = $db_obj->fetchOne($sql);
        
        return $row; // 未登录
    }
    
    /**
     * 检查用户名是否存在
     *
     * @param string $sql
     * @return Ambigous <mutitype:,multitype:>???
     */
    public static function checkUsernameExist($username)
    {
        global $db_obj;
        $username = trim($username);
        if (Str::isUsername($username)) { // 符合用户名格式，则判断是否已经存在该用户名
            if (get_magic_quotes_gpc()) {
                $username = stripslashes($username); // stripslashes — 反引用一个引用字符串;//trim去除两边空白
            }
            $sql = "select id from hw_user where username='{$username}'";
            $row = $db_obj->fetchOne($sql);
            if ($row) {
               return 1;
            } else {
                return 2;
            }
        } else {
            return 3;
        }
    }
    
    /**
     * 检查用户输入的性别是否合法
     * @param string $sex
     * @return boolean
     */
    public static function checkUserSex($sex){
        if($sex == '男' || $sex == '女' || $sex == '保密'){
            return true;   
        }
        return false;
    }
    
    /**
     * 得到用户的个人基本信息
     * @param int $uId
     * @return //多类型？？？[]
     */
    public static function getUserInfoByUid($uId){
        global $db_obj;
        $sql = "select * from hw_user where id={$uId}";
        $row = $db_obj->fetchOne($sql);
        
        $sql = "select sName from hw_school where id={$row['sId']}";
        $row1 = $db_obj->fetchOne($sql);
        $row['sName'] = $row1['sName']?$row1['sName']:'待完善';
        
        $sql = "select cName from hw_campus where id={$row['cId']}";
        $row2 = $db_obj->fetchOne($sql);
        $row['cName'] =$row2['cName']?$row2['cName']:'待完善';
        
        $sql = "select pName from hw_province where id={$row['pId']}";
        $row3 = $db_obj->fetchOne($sql);
        $row['pName'] =$row3['pName']?$row3['pName']:'待完善';
        return $row;
    }
    
    /**
     * 得到用户的个人基本信息
     * @param int $uId
     * @return //多类型？？？[]
     */
    /*public static function getUserInfoByUid($uId){
        global $db_obj;
        $sql = "select u.id,u.username,u.sex,u.face,u.follower,u.attention,s.sName,c.cName from hw_user as u join (hw_campus as c join hw_school as s on c.sId=s.id) on u.cId=c.id where id={$uId}";
        $row = $db_obj->fetchOne($sql);
        return $row;
    }*/
    
    /**
     * 更新用户的基本信息
     * @param array $arr
     * @return boolean
     */
    public static function modifyUserInfo($arr,$where){
        global $db_obj;
        $table = 'hw_user';
        $link = $db_obj->update($table, $arr,$where);
        $res_affected = mysqli_affected_rows($db_obj->connect());
        if($link && $res_affected>0){
            return true;
        }else{
            return false;
        }
    }
    
    /**
     * 用户修改密码
     * @param int $uId
     * @param string $password
     * @return boolean
     */
    public static function modifyPassword($uId,$password){
        global $db_obj;
        $table = 'hw_user';
        $arr['password'] = md5($password); 
        $where = "id={$uId}";
        $link = $db_obj->update($table, $arr ,$where);
        $res_affected = mysqli_affected_rows($db_obj->connect());
        if($link && $res_affected>0){
            return true;
        }else{
            return false;
        }
    }
    
    /**
     * 绑定手机号或者更改绑定手机号
     * @param int $uId
     * @param string $phone
     * @return boolean
     */
    public static function bindPhone($uId,$phone){
        global $db_obj;
        $table = 'hw_user';
        $arr['phone'] = $phone;
        $where = "id={$uId}";
        $link = $db_obj->update($table, $arr ,$where);
        $res_affected = mysqli_affected_rows($db_obj->connect());
        if($link && $res_affected>0){
            return true;
        }else{
            return false;
        }
    }
    
    /**
     * 绑定邮箱或者更改绑定邮箱
     * @param int $uId
     * @param string $email
     * @return boolean
     */
    public static function bindEmail($uId,$email){
        global $db_obj;
        $table = 'hw_user';
        $arr['email'] = $email;
        $where = "id={$uId}";
        $link = $db_obj->update($table, $arr ,$where);
        $res_affected = mysqli_affected_rows($db_obj->connect());
        if($link && $res_affected>0){
            return true;
        }else{
            return false;
        }
    }
    
    /**
     * 验证密码，在修改绑定邮箱或修改绑定的手机号时用
     * @param int $uId
     * @param string $password
     * @return 资源
     */
    public static function verifyPassword($uId,$password){
        global $db_obj;
        $password = md5($password);
        $sql = "select id from hw_user where id={$uId} and password='{$password}'";
        //echo $sql;
        $row = $db_obj->fetchOne($sql);
        return $row;
    }
    
    /**
     * 得到指定id的用户基本信息
     * @param int $uId
     * @return //多类型？？？[]
     */
    public static function getUserInfoByUids($uIds){
        global $db_obj;
        $sql = "select id,username,sex,face,sId,cId,experience,about,follower,attention from hw_user where id in({$uIds})";
        $rows = $db_obj->fetchAll($sql);
        if($rows){
            foreach ($rows as &$row){
                $sql = "select sName from hw_school where id={$row['sId']}";
                $row1 = $db_obj->fetchOne($sql);
                $row['sName'] = $row1['sName']?$row1['sName']:'待完善';
                $sql = "select cName from hw_campus where id={$row['cId']}";
                $row2 = $db_obj->fetchOne($sql);
                $row['cName'] =$row2['cName']?$row2['cName']:'待完善';
            }
        }
        return $rows;
    }
    
    /**
     * 获取参加活动的用户组
     * @param int $sId
     * @return multitype:
     */
    public static function getSituationGroupBySid($sId){
        global $db_obj;
        $sql = "select uId from hw_group where sId={$sId}";
        //echo $sql;
        $row = $db_obj->fetchAll($sql);
        return $row;
    }
    
    /**
     * 得到用户参加的活动列表
     * @param int $uId
     * @return multitype:
     */
    public static function getUserJoinSituationByUid($uId){
        global $db_obj;
        $sql = "select * from hw_group where uId={$uId}";
        //echo $sql;
        $row = $db_obj->fetchAll($sql);
        return $row;
    }
    
    /**
     * 用户发布活动
     * @return string
     */
    public static function pubSituation($arr){
        global $db_obj;
        $path="../uploads";
        $uploadFiles=Upload::uploadFile($path);
        //存储上传的图片的缩略图
        //Image::addPicture($uploadFiles,$path);
        mysqli_autocommit($db_obj->connect(), FALSE);
        $res = $db_obj->insert('hw_situation', $arr);
        $res_affected = mysqli_affected_rows($db_obj->connect());
        //更新user表中的记录
        $field = 'lastPubTime';
        $lastTime = time();
        $bool = User::checkIsFirst($arr['uId'], $field);
        if($bool){//是第一次发布
            $sql = "update hw_user set {$field}={$lastTime},experience=experience+2 where id={$arr['uId']}";//更新活动表中的评论数
        }else{
            $sql = "update hw_user set {$field}={$lastTime} where id={$arr['uId']}";//更新活动表中的评论数
        }
        $res1 = mysqli_query($db_obj->connect(), $sql);
        $res1_affected = mysqli_affected_rows($db_obj->connect());
        if(!$res || $res_affected<1 || !$res1 || $res1_affected<1){//插入活动表失败，回滚
            if($uploadFiles){
                Image::deletePicture($uploadFiles);
            }
            mysqli_rollback($db_obj->connect()); 
            return false;
        }
        $lastId = $db_obj->getInsertId();
        if($uploadFiles){//如果有上传图片，则把图片存入相册表
            foreach ($uploadFiles as $uploadFile){
                $arr1['sId'] = $lastId;
                $arr1['albumPath'] = $uploadFile['name'];
                $res1 = Album::addAlbum($arr1);//判断一下是否添加成功
                $res1_affected = mysqli_affected_rows($db_obj->connect());
                if(!$res1 || $res1_affected<1){//插入相册表失败，回滚
                    Image::deletePicture($uploadFiles);
                    mysqli_rollback($db_obj->connect());
                    return false;
                }
            }
        }
        //全部插入成功，提交
        mysqli_commit($db_obj->connect());
        mysqli_autocommit($db_obj->connect(), TRUE);
        return true;
    }
    
    /**
     * 转发活动
     * @param array $arr
     * @return boolean
     */
    public static function transmitSituation($arr){
        global $db_obj;
        mysqli_autocommit($db_obj->connect(), FALSE);
        $table = 'hw_situation';
        $res = $db_obj->insert($table, $arr);
        $res_affected = mysqli_affected_rows($db_obj->connect());
        $sql = "update hw_situation set transmit=transmit+1 where id={$arr['isTransmit']}";//更新活动表中的评论数
        $res1 = mysqli_query($db_obj->connect(), $sql);
        $res1_affected = mysqli_affected_rows($db_obj->connect());
        if($res &&$res_affected>0 && $res1 && $res1_affected>0){
            mysqli_commit($db_obj->connect());
            mysqli_autocommit($db_obj->connect(), TRUE);
            return true;
        }else{
            mysqli_rollback($db_obj->connect());
            return false;
        }
    }
    
    /**
     * 用户删除活动
     * @return string
     */
    public static function delSituation($arr){
        global $db_obj;
        mysqli_autocommit($db_obj->connect(), FALSE);
        //先删除活动图片
        $bool1 = Album::delSituationImage($arr['sId']);
        //再删除活动表hw_situation中的记录
        $bool2 =  Situation::del($arr['sId']);
        //再删除活动的评论记录
        $bool3 = Comment::del($arr);
        //再删除活动的用户组
        $bool4 = Group::del($arr);
        //再删除收藏表中的记录
        $bool5 = Collection::del($arr);
        //再删除赞表中的记录
        $bool6 = Praise::del($arr);
        
        if($bool1 && $bool2 && $bool3 && $bool4 && $bool5 && $bool6){
            //全部删除成功，提交
            mysqli_commit($db_obj->connect());
            mysqli_autocommit($db_obj->connect(), TRUE);
            return true;
        }else{
            mysqli_rollback($db_obj->connect());
            return false;
        }
    }
    
    /**
     * 检查用户是否参加过此活动
     * @param int $uId
     * @param int $sId
     * @return boolean
     */
    public static function checkJoin($uId,$sId){
        global $db_obj;
        $sql = "select isTransmit from hw_situation where id={$sId}";
        $row = $db_obj->fetchOne($sql);
        if($row['isTransmit'] != 0){
            $sId = $row['isTransmit'];
        }
        $sql = "select id from hw_group where uId={$uId} and sId={$sId}";
        $row = $db_obj->fetchOne($sql);
        if($row){
            return true;
        }else{
            return false;
        }
    }
    
    /**
     * 参加活动
     * @param Array $arr
     * @return number
     */
    public static function join($arr){
        global $db_obj;
        $sqls[1] = Sql::sqlForInsert('hw_group', $arr);
        $sqls[2] = "update hw_situation set sCurrentNumber=sCurrentNumber+1 where id={$arr['sId']}";
        //更新user表中的记录
        $field = 'lastJoinTime';
        $lastTime = time();
        $bool = self::checkIsFirst($arr['uId'], $field);
        if($bool){//是当天第一次参加
            $sqls[3] = "update hw_user set {$field}={$lastTime},experience=experience+2 where id={$arr['uId']}";//更新活动表中的评论数
        }else{
            $sqls[3] = "update hw_user set {$field}={$lastTime} where id={$arr['uId']}";//更新活动表中的评论数
        }
        $bool = $db_obj->transaction($sqls);//执行事务
        return $bool;
    }
    
    /**
     * 取消参加活动
     * @param Array $arr
     * @return number
     */
    public static function cancelJoin($arr){
        global $db_obj;
        mysqli_autocommit($db_obj->connect(), FALSE);
        $bool = Group::del($arr);
        $res_affected = mysqli_affected_rows($db_obj->connect());
        $sql = "update hw_situation set sCurrentNumber=sCurrentNumber-1 where id={$arr['sId']}";//更新活动表中的评论数
        $res1 = mysqli_query($db_obj->connect(), $sql);
        $res1_affected = mysqli_affected_rows($db_obj->connect());
        if($bool && $res1 && $res1_affected>0){
            mysqli_commit($db_obj->connect());
            mysqli_autocommit($db_obj->connect(), TRUE);
            return true;
        }else{
            mysqli_rollback($db_obj->connect());
            return false;
        }
    }
    
    /**
     * 点赞
     * @param int $sId
     * @return boolean
     */
    public static function praise($arr){
        global $db_obj;
        $sqls[1] = Sql::sqlForInsert('hw_praise', $arr);
        $sqls[2] = "update hw_situation set praise=praise+1 where id={$arr['sId']}";//更新活动表中的评论数
        //更新user表中的记录
        $field = 'lastPraiseTime';
        $lastTime = time();
        $bool = User::checkIsFirst($arr['uId'], $field);
        if($bool){//是当天第一次参加
            $sqls[3] = "update hw_user set {$field}={$lastTime},experience=experience+2 where id={$arr['uId']}";//更新活动表中的评论数
        }else{
            $sqls[3] = "update hw_user set {$field}={$lastTime} where id={$arr['uId']}";//更新活动表中的评论数
        }
        $bool = $db_obj->transaction($sqls);//执行事务
        return $bool;
    }
    
    /**
     * 取消赞
     * @param int $sId
     * @return boolean
     */
    public static function cancelPraise($arr){
        global $db_obj;
        mysqli_autocommit($db_obj->connect(), FALSE);
        $table = 'hw_praise';
        $where = "sId={$arr['sId']} and uId={$arr['uId']}";
        $res = $db_obj->delete($table, $where);
        $res_affected = mysqli_affected_rows($db_obj->connect());
        $sql = "update hw_situation set praise=praise-1 where id={$arr['sId']}";//更新活动表中的评论数
        $res1 = mysqli_query($db_obj->connect(), $sql);
        $res1_affected = mysqli_affected_rows($db_obj->connect());
        if($res &&$res_affected>0 && $res1 && $res1_affected>0){
            mysqli_commit($db_obj->connect());
            mysqli_autocommit($db_obj->connect(), TRUE);
            return true;
        }else{
            mysqli_rollback($db_obj->connect());
            return false;
        }
    }
    
    /**
     * 检查用户是否赞过此活动
     * @param int $uId
     * @param int $sId
     * @return boolean
     */
    public static function checkPraise($uId,$sId){
        global $db_obj;
        $sql = "select isTransmit from hw_situation where id={$sId}";
        $row = $db_obj->fetchOne($sql);
        if($row['isTransmit'] != 0){
            $sId = $row['isTransmit'];
        }
        $sql = "select id from hw_praise where uId={$uId} and sId={$sId}";
        $row = $db_obj->fetchOne($sql);
        if($row){
            return true;
        }else{
            return false;
        }
    }
    
    /**
     * 得到用户收藏的活动列表
     * @param int $uId
     * @return multitype:
     */
    public static function getUserPraiseByUid($uId){
        global $db_obj;
        $sql = "select * from hw_praise where uId={$uId}";
        //echo $sql;
        $row = $db_obj->fetchAll($sql);
        return $row;
    }
    
    /**
     * 收藏活动
     * @param array $arr
     * @return boolean
     */
    public static function collect($arr){
        global $db_obj;
        $sqls[1] = Sql::sqlForInsert('hw_collection', $arr);
        $sqls[2] = "update hw_situation set collection=collection+1 where id={$arr['sId']}";//更新活动表中的收藏数
        //更新user表中的记录
        $field = 'lastCollectTime';
        $lastTime = time();
        $bool = User::checkIsFirst($arr['uId'], $field);
        if($bool){//是当天第一次参加
            $sqls[3] = "update hw_user set {$field}={$lastTime},experience=experience+2 where id={$arr['uId']}";//更新活动表中的评论数
        }else{
            $sqls[3] = "update hw_user set {$field}={$lastTime} where id={$arr['uId']}";//更新活动表中的评论数
        }
        $bool = $db_obj->transaction($sqls);//执行事务
        return $bool;
    }
    
    /**
     * 取消收藏
     * @param array $arr
     * @return boolean
     */
    public static function cancelCollect($arr){
        global $db_obj;
        mysqli_autocommit($db_obj->connect(), FALSE);
        $table = 'hw_collection';
        $where = "sId={$arr['sId']} and uId={$arr['uId']}";
        $res = $db_obj->delete($table, $where);
        $res_affected = mysqli_affected_rows($db_obj->connect());
        $sql = "update hw_situation set collection=collection-1 where id={$arr['sId']}";//更新活动表中的评论数
        $res1 = mysqli_query($db_obj->connect(), $sql);
        $res1_affected = mysqli_affected_rows($db_obj->connect());
        if($res &&$res_affected>0 && $res1 && $res1_affected>0){
            mysqli_commit($db_obj->connect());
            mysqli_autocommit($db_obj->connect(), TRUE);
            return true;
        }else{
            mysqli_rollback($db_obj->connect());
            return false;
        }
    }
    
    /**
     * 检查用户是否收藏过此活动
     * @param int $uId
     * @param int $sId
     * @return //多类型？？？[]
     */
    public static function checkCollect($uId,$sId){
        global $db_obj;
        $sql = "select isTransmit from hw_situation where id={$sId}";
        $row = $db_obj->fetchOne($sql);
        if($row['isTransmit'] != 0){
            $sId = $row['isTransmit'];
        }
        $sql = "select id from hw_collection where uId={$uId} and sId={$sId}";
        $row = $db_obj->fetchOne($sql);
        if($row){
            return true;
        }else{
            return false;
        }
    }
    
    /**
     * 得到用户收藏的活动列表
     * @param int $uId
     * @return multitype:
     */
    public static function getUserCollectionByUid($uId){
        global $db_obj;
        $sql = "select * from hw_collection where uId={$uId}";
        //echo $sql;
        $row = $db_obj->fetchAll($sql);
        return $row;
    }
    
    /**
     * 加关注
     * @param array $arr
     * @return boolean
     */
    public static function Attention($arr){
        global $db_obj;
        mysqli_autocommit($db_obj->connect(), FALSE);
        //插入follow表中
        $table = 'hw_follow';
        $res = $db_obj->insert($table, $arr);
        $res_affected = mysqli_affected_rows($db_obj->connect());
        //更新关注者在user表中的记录
        $sql = "update hw_user set attention=attention+1 where id={$arr['follower_id']}";//更新活动表中的评论数
        $res1 = mysqli_query($db_obj->connect(), $sql);
        $res1_affected = mysqli_affected_rows($db_obj->connect());
        //更新被关注者在user表中的记录
        $sql = "update hw_user set follower=follower+1 where id={$arr['user_id']}";//更新活动表中的评论数
        $res2 = mysqli_query($db_obj->connect(), $sql);
        $res2_affected = mysqli_affected_rows($db_obj->connect());
        /* var_dump($res);
        var_dump($res1);
        var_dump($res2); */
        if($res &&$res_affected>0 && $res1 && $res1_affected>0 && $res2 && $res2_affected>0){
            mysqli_commit($db_obj->connect());
            mysqli_autocommit($db_obj->connect(), TRUE);
            return true;
        }else{
            mysqli_rollback($db_obj->connect());
            return false;
        }
    }
    
    /**
     * 取消关注
     * @param array $arr
     * @return boolean
     */
    public static function cancelAttention($arr){
        global $db_obj;
        mysqli_autocommit($db_obj->connect(), FALSE);
        //删除follow表中的记录
        $table = 'hw_follow';
        $where = "follower_id={$arr['follower_id']} and user_id={$arr['user_id']}";
        $res = $db_obj->delete($table,$where);
        $res_affected = mysqli_affected_rows($db_obj->connect());
        //更新关注者在user表中的记录
        $sql = "update hw_user set attention=attention-1 where id={$arr['follower_id']}";//更新活动表中的评论数
        $res1 = mysqli_query($db_obj->connect(), $sql);
        $res1_affected = mysqli_affected_rows($db_obj->connect());
        //更新被关注者在user表中的记录
        $sql = "update hw_user set follower=follower-1 where id={$arr['user_id']}";//更新活动表中的评论数
        $res2 = mysqli_query($db_obj->connect(), $sql);
        $res2_affected = mysqli_affected_rows($db_obj->connect());
        if($res &&$res_affected>0 && $res1 && $res1_affected>0 && $res2 && $res2_affected>0){
            mysqli_commit($db_obj->connect());
            mysqli_autocommit($db_obj->connect(), TRUE);
            return true;
        }else{
            mysqli_rollback($db_obj->connect());
            return false;
        }
    }
    
    /**
     * 检查是否关注过该用户
     * @param int $uId
     * @param int $sId
     * @return boolean
     */
    public static function checkAttention($follower_id,$user_id){
        global $db_obj;
        $sql = "select id from hw_follow where follower_id={$follower_id} and user_id={$user_id}";
        //echo $sql;
        $row = $db_obj->fetchOne($sql);
        if($row){
            return true;
        }else{
            return false;
        }
    }
    
    /**
     * 得到用户的粉丝列表
     * @param int $user_id
     * @return multitype:
     */
    public static function getUserFollower($user_id){
        global $db_obj;
        $sql = "select follower_id from hw_follow where user_id={$user_id}";//获取用户的粉丝
        $rows = $db_obj->fetchAll($sql);
        return $rows;
    }
    
    /**
     * 得到用户的关注列表,自己是粉丝，所以穿$follower_id
     * @param int $user_id
     * @return multitype:
     */
    public static function getUserAttention($follower_id){
        global $db_obj;
        $sql = "select user_id from hw_follow where follower_id={$follower_id}";//获取用户的粉丝
        $rows = $db_obj->fetchAll($sql);
        return $rows;
    }
    
    /**
     * 检查用户是否参加过该活动
     * @param int $sId
     * @param int $uId
     * @return //多类型？？？[]
     */
    public static function checkUserJoin($sId,$uId){
        global $db_obj;
        $sql = "select id from hw_group where sId={$sId} and uId={$uId}";
        $row = $db_obj->fetchOne($sql);
        return $row;
    }
    
    /**
     * 用户邮箱登录
     * @param string $email
     * @return //多类型？？？[]
     */
    public static function emailLogin($email){
        global $db_obj;
        $sql = "select * from hw_user where email='{$email}'";
        $row = $db_obj->fetchOne($sql);
        return $row;
    }
    
    /**
     * 用户手机登录
     * @param string $email
     * @return //多类型？？？[]
     */
    public static function phoneLogin($phone){
        global $db_obj;
        $sql = "select * from hw_user where phone='{$phone}'";
        $row = $db_obj->fetchOne($sql);
        return $row;
    }
    
    /**
     * 检查是否是当天第一次登录
     * @param unknown $uId
     * @return boolean
     */
    public static function checkIsFirstLogin($lastLoginTime){
            if($lastLoginTime-strtotime('today')>0){//说明最后一次登录时今天登陆
                //echo '本次登录不是今天第一次登录';
                $flag = false;//标记是否是当天的第一次登录，0代表不是，1代表是
            }else{//说明最后一次次登录时昨天登陆
                //echo '本次登录是今天第一次登录';
                $flag = true;//标记是否是当天的第一次登录，0代表不是，1代表是
            }
        return $flag;
    }
    
    /**
     * 检查是否是当天第一次赞等
     * @param unknown $uId
     * @return boolean
     */
    public static function checkIsFirst($uId,$field){
        global $db_obj;
        $sql = "select {$field} from hw_user where id={$uId}";
        $row = $db_obj->fetchOne($sql);
        if($row[$field]-strtotime('today')>0){//说明最后一次是今天
            //echo '本次登录不是今天第一次';
            $flag = false;//标记是否是当天的第一次
        }else{//说明最后一次次登录时昨天登陆
            //echo '本次登录是今天第一次';
            $flag = true;//标记是否是当天的第一次
        }
        return $flag;
    }
    
    /**
     * 更新用户登录的状态
     * @param int $uId
     * @return boolean
     */
    public static function updateUserLogined($uId,$flag){
        global $db_obj;
        $arr['uId'] = $uId;
        mysqli_autocommit($db_obj->connect(), FALSE);
        //插入hw_userLogined表中
        //$table = 'hw_userLogined';//表名没有大写，这里用大写linux上区分大小写，不能识别
        $table = 'hw_userlogined';
        $res = $db_obj->insert($table, $arr);
        $res_affected = mysqli_affected_rows($db_obj->connect());
        //更新关注者在user表中的记录
        $lastLoginTime = time();
        if($flag){//当天的第一次登录，加经验
            $sql = "update hw_user set lastLoginTime={$lastLoginTime},experience=experience+2 where id={$arr['uId']}";//更新活动表中的评论数
        }else{
            $sql = "update hw_user set lastLoginTime={$lastLoginTime} where id={$arr['uId']}";//更新活动表中的评论数
        }
        $res1 = mysqli_query($db_obj->connect(), $sql);
        $res1_affected = mysqli_affected_rows($db_obj->connect());
        
        if($_SESSION['uId']){//是否当前浏览器或者客户端有用户登陆，有则挤掉
            //删除hw_userLogined表中的记录
            $table = 'hw_userLogined';
            $where = "uId={$_SESSION['uId']}";
            $res2 = $db_obj->delete($table,$where);
            $res2_affected = mysqli_affected_rows($db_obj->connect());
        }else{
            $res2 = true;
            $res2_affected = 1;
        }
        /* var_dump($res);
        var_dump($res1);
        var_dump($res2); */
        if($res &&$res_affected>0 && $res1 && $res1_affected>0 && $res2 && $res2_affected>0){
            mysqli_commit($db_obj->connect());
            mysqli_autocommit($db_obj->connect(), TRUE);
            return true;
        }else{
            mysqli_rollback($db_obj->connect());
            return false;
        }
    }
    
    /**
     * 退出登录
     * @return number
     */
    public static function loginOut(){
        global $db_obj;
        mysqli_autocommit($db_obj->connect(), FALSE);
        $table = 'hw_userlogined';
        $where = "uId={$_SESSION['uId']}";
        $link = $db_obj->delete($table,$where);
        if (isset($_COOKIE[session_name("loginId")])) { // 清空cookie的记录
            if (setcookie(session_name("loginFlag"), "", time() - 1) && setcookie(session_name("loginId"), "", time() - 1)) {
                if($link){//删除hw_userLogined中的记录失败，回滚
                    $_SESSION = array(); // 这里设置为空数组会导致后台也一起跟着退出,因为管理员的字段也是username,把管理员字段改成adminName
                    mysqli_commit($db_obj->connect());
                    mysqli_autocommit($db_obj->connect(), TRUE);
                    return true;
                }else{
                    mysqli_rollback($db_obj->connect());
                    return false;
                }
            } else {//清空cookie的记录失败，回滚
                mysqli_rollback($db_obj->connect());
                return false;
            }
        }  
    }
    
    /**
     * 用户注册
     *
     * @return string
     */
    public static function register($arr)
    {
        global $db_obj;
        $table = 'hw_user';
        $link = $db_obj->insert($table, $arr);
        return $link;
    }
    
    
    /**
     * 修改邮箱账号的密码
     * @param string $email
     * @param string $password
     * @return number
     */
    public static function resetPasswordByEmail($email,$password){
        global $db_obj;
        $table = 'hw_user';
        $arr['password'] = md5($password);
        $where = "email='{$email}'";
        $link = $db_obj->update($table, $arr,$where);
        return $link;
    }
    
    /**
     * 修改手机账号的密码
     * @param string $phone
     * @param string $password
     * @return number
     */
    public static function resetPasswordByPhone($phone,$password){
        global $db_obj;
        $table = 'hw_user';
        $arr['password'] = md5($password);
        $where = "phone='{$phone}'";
        $link = $db_obj->update($table, $arr,$where);
        return $link;
    }
    
    /**
     * 转换用户经验值
     * @param int $exp
     * @return array
     */
    public static function getAboutExperience($exp){
        $arr=array( 
            0,      //0
            5,      //1
            15,     //2
            30,     //3
            60,     //4
            120,    //5
            210,    //6
            330,    //7
            480,    //8
            660,    //9
            870,    //10
            1110,   //11
            1380,   //12
            1680,   //13
            2010,   //14
            2370,   //15
            2760,   //16
            3180,   //17
            3630,   //18
            4110,   //19
            4620,   //20
            5160,   //21
            5730,   //22
            6330,   //23
            6960,   //24
            7620,   //25
            8310,   //26
            9030,   //27
            9780,   //28
            10560,  //29
            11370,  //30
            12210,  //31
            13080,  //32
            13980,  //33
            14910,  //34
            15870,  //35
            16860,  //36
            17780,  //37
            18930,  //38
            20010,  //39
            21120,  //40
            22260,  //41
            23430,  //42
            24630,  //43
            25860,  //44
            27120,  //45
            28410,  //46
            29730,  //47
            31080   //48
        );
        $experience = array();
        //设置升级经验数组
        for($i=1;$i<count($arr);$i++){
    
            if($exp<$arr[$i]){
                //如果经验小于数组中当前值则说明是，当前的$i+1这么多级，于是。。。
                $experience['experience'] = $exp;//总的经验值
                $experience['level'] = $i-1;//等级
                $experience['remain'] = $exp - $arr[$i-1];//过了当前级还剩下多少经验
                $experience['lack'] = $arr[$i] - $exp;//到达下一级还差多少经验 
                return $experience;//ok游戏结束
            }
        }
        $experience['experience'] = $exp;//总的经验值
        $experience['level'] = $i-1;//等级
        $experience['remain'] = $exp - $arr[$i-1];//过了当前级还剩下多少经验
        $experience['lack'] = 0;//到达下一级还差多少经验 
        return $experience;//循环结束还没小于则说明等级到了最大值
    }
    
}