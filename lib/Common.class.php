<?php
/**
 *公共操作类
 *tags
 *@author Joker-Long
 *编写日期：2016年8月13日上午9:32:57
 */
class Common
{

    public static function alertMes($mes, $url){
        echo "<script>alert('($mes)')</script>";
        echo "<script>window.location='{$url}'</script>";
    }

    /**
     * 写入用户请求日志
     */
    public static function writeUserRequestLog(){
        $arr['time'] = date('Y-m-d H:i:s');
        $arr['uId'] = $_SESSION['uId'] ? $_SESSION['uId'] : null; //登录的为用户的id

        if (! $arr['uId']) {//未登录的记录sessionId
            $arr['loginId'] = session_id() ? session_id() : null;
        }
        $arr['userIp'] = $_SERVER["REMOTE_ADDR"]; // 用户ip
        $arr['username'] = $_SESSION['username']; // 用户名
        //echo $arr['username'];
                                                  // echo $_POST['action'];
        $arr['action'] = $_POST['action'] ? $_POST['action'] : null; // 用户的操作
        if (! $arr['action']) {
            $arr['action'] = $_GET['action'] ? $_GET['action'] : null;
        }
        /*
         * if (!public static function_exists('getallheaders'))//获取用户请求头
         * {
         * public static function getallheaders()
         * {
         * foreach ($_SERVER as $name => $value)
         * {
         * if (substr($name, 0, 5) == 'HTTP_')
         * {
         * $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
         * }
         * }
         * return $headers;
         * }
         * }
         *
         * print_r(getallheaders());
         */
        
        $arr['postMessage'] = '';
        
        foreach ($_POST as $key => $value) {
            $arr['postMessage'] .= $key . '=' . $value . '&';
        }
        $table = 'hw_userlog';
        global $db_obj;
        $db_obj->insert($table, $arr);
        global $id;//更新返回信息时需要用到这个id
        $id = $db_obj->getInsertId();
        // $time = date('h-i-s');
        // $post = json_encode($_POST);
        // File::cacheData("$time","$post");
    }

    /**
     * 写入用户请求返回值日志
     */
    public static function writeUserResponseLog(){
        global $result;
        global $db_obj;
        $result['datas'] = '';
        $arr['resultMessage'] = Response::json($result);
        $table = 'hw_userlog';
        //$id = $db_obj->getInsertId();
        global $id;
        $where = "id='{$id}'"; 
        $db_obj->update($table, $arr, $where);
    }
}