<?php
/**
*管理员操作类
*tags
*@author Joker-Long
*编写日期：2016年8月13日下午3:51:32
*/
class Admin
{
    //require_once '../include.php';
    /**
     * 检查管理员是否存在
    * @param string $sql
    * @return Ambigous <mutitype:,multitype:>???
    */
    public static function checkAdmin($sql){
        global $db_obj;
        return $db_obj->fetchOne($sql);
    }
    
    /**
     * 检测是否有管理员登录
     */
    public static function checkAdminLogined(){
        if(@($_SESSION['adminId']==""&&$_COOKIE['adminId']=="")){
            Common::alertMes("请先登录", "login.php");
        }
    }
    
    /**
     * 添加管理员
     * @return string
     */
    public static function addAdmin(){
        global $db_obj;
        $arr=$_POST;
        $arr['password']=md5($_POST['password']);
        if($db_obj->insert("hw_admin",$arr)){
            $mes="添加成功！<br/><a href='addAdmin.php'>继续添加</a>|<a href='listAdmin.php'>查看管理员列表</a>";
        }else{
            $mes="添加失败！<br/><a href='addAdmin.php'>继续添加</a>";
        }
        return $mes;
    }
    
    /**
     * 编辑管理员
     * @param int $id
     * @return string
     */
    public static function editAdmin($where){
        global $db_obj;
        $arr=$_POST;
        $arr['password']=md5($_POST['password']);
        if($db_obj->update("hw_admin", $arr,$where)){
            $mes="编辑成功!<a href='listAdmin.php'>查看管理员列表</a>";
        }else{
            $mes="编辑失败!<a href='listAdmin.php'>请重新修改</a>";
        }
        return $mes;
    }
    
    public static function delAdmin($where){
        global $db_obj;
        if($db_obj->delete("hw_admin",$where)){
            $mes="删除成功!<br/><a href='listAdmin.php'>查看管理员列表</a>";
        }else{
            $mes="删除失败!<br/><a href='listAdmin.php'>请重新删除</a>";
        }
        return $mes;
    }
    /**
     * 得到所有管理员
     * @return array
     */
    public static function getAllAdmin(){
        global $db_obj;
        $sql="select id,adminName,email from hw_admin";
        $rows=$db_obj->fetchAll($sql);
        return $rows;
    }
    
    /**
     * 通过页码获得管理员数量
     * @param int $page
     * @param int $pageSize
     * @return multitype:
     */
    public static function getAdminByPage($page,$pageSize=2){
        global $db_obj;
        $sql="select id from hw_admin";//这里用*浪费资源？换成id??
        $result=mysqli_query($db_obj->connect(),$sql);
        global $totalRows;
        $totalRows=$db_obj->getResultNum($sql);
        global $totalPage;
        $totalPage=ceil($totalRows/$pageSize);//ceil进一取整
        if($page<1||$page==null||!is_numeric($page)){
            $page=1;
        }
        if($page>=$totalPage)
            $page=$totalPage;
            $offset=($page-1)*$pageSize;//偏移量
            $sql="select id,adminName,email from hw_admin limit {$offset},{$pageSize}";
            $rows=$db_obj->fetchAll($sql);
            return $rows;
    }
    /**
     * 注销管理员
     */
    public static function logout(){
        //$_SESSION=array();//这样导致前台也跟着退出？？
        $_SESSION['adminId']=null;
        $_SESSION['adminName']=null;
        if(isset($_COOKIE[session_name("adminName")])){
            setcookie(session_name("adminName"),"",time()-1);
        }//检查session中是否被设置
        if(isset($_COOKIE['adminName'])){
            setcookie('adminName',"",time()-1);
        }
        if(isset($_COOKIE['adminId'])){
            setcookie('adminId',"",time()-1);
        }
        //session_destroy();//这样导致前台也跟着退出？？
        unset($_SESSION['adminName']);
        unset($_SESSION['adminId']);
        header("location:login.php");
    }
    
    /**
     * 添加用户的操作
     * @param int $id
     * @return string
     */
    public static function addUser(){
        global $db_obj;
        $arr=$_POST;
        $arr['password']=md5($_POST['password']);
        $arr['regTime']=time();
        $uploadFile=Upload::uploadFile("../uploads");
        if($uploadFile&&is_array($uploadFile)){
            $arr['face']=$uploadFile[0]['name'];
        }else{
            return "添加失败<a href='addUser.php'>重新添加</a>";
        }
        if($db_obj->insert("hw_user", $arr)){
            $mes="添加成功!<br/><a href='addUser.php'>继续添加</a>|<a href='listUser.php'>查看列表</a>";
        }else{
            $filename="../uploads/".$uploadFile[0]['name'];
            if(file_exists($filename)){
                unlink($filename);
            }
            $mes="添加失败!<br/><a href='addUser.php'>重新添加</a>|<a href='listUser.php'>查看列表</a>";
        }
        return $mes;
    }
    /**
     * 删除用户的操作
     * @param int $id
     * @return string
     */
    public static function delUser($id){
        global $db_obj;
        $sql="select face from hw_user where id=".$id;
        $row=$db_obj->fetchOne($sql);
        $face=$row['face'];
        if(file_exists("../uploads/".$face&&($face!=null))){
            unlink("../uploads/".$face);
        }
        if($db_obj->delete("hw_user","id={$id}")){
            $mes="删除成功!<br/><a href='listUser.php'>查看用户列表</a>";
        }else{
            $mes="删除失败!<br/><a href='listUser.php'>请重新删除</a>";
        }
        return $mes;
    }
    /**
     * 编辑用户的操作
     * @param int $id
     * @return string
     */
    public static function editUser($id){
        global $db_obj;
        $arr=$_POST;
        $arr['password']=md5($_POST['password']);
        //$arr['regTime']=time();注册时间在编辑的时候不用更新
        $uploadFile=Upload::uploadFile("../uploads");
        if($uploadFile&&is_array($uploadFile)){
            $arr['face']=$uploadFile[0]['name'];
        }else{
            return "编辑失败<a href='editUser.php'>重新编辑</a>";
        }
        if($db_obj->update("hw_user", $arr,"id={$id}")){
            $mes="编辑成功!<br/><a href='listUser.php'>查看用户列表</a>";
        }else{
            $mes="编辑失败!<br/><a href='listUser.php'>请重新修改</a>";
        }
        return $mes;
    }
    
    /**
     * 得到所有用户
     *
     * @return array
     */
    public static function getAllUser()
    {
        global $db_obj;
        $sql = "select id,username from hw_user";
        $rows = $db_obj->fetchAll($sql);
        return $rows;
    }
    
    /**
     * 通过页码获得用户数量
     * @param int $page
     * @param int $pageSize
     * @return multitype:
     */
    public static function getUserByPage($page,$pageSize=2){
        global $db_obj;
        $sql="select id from hw_user";//这里用*浪费资源？换成id??
	$result=mysqli_query($db_obj->connect(),$sql);
        global $UtotalRows;
        $UtotalRows=$db_obj->getResultNum($sql);
        global $UtotalPage;
        $UtotalPage=ceil($UtotalRows/$pageSize);//ceil进一取整
        if($page<1||$page==null||!is_numeric($page)){
            $page=1;
        }
        if($page>=$UtotalPage)
            $page=$UtotalPage;
            $offset=($page-1)*$pageSize;//偏移量
            $sql="select id,face,username,email,activeFlag from hw_user limit {$offset},{$pageSize}";
            $rows=$db_obj->fetchAll($sql);
            return $rows;
    }
}
