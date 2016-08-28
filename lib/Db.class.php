<?php
/**
 *数据库操作类
 *tags
 *@author Joker-Long
 *编写日期：2016年8月13日上午9:32:57
 */
class Db
{

    private static $_instance;

    private static $_connectSource;

    private $_dbConfig = array(
        'host' => DB_HOST,
        'user' => DB_USER,
        'password' => DB_PWD,
        'database' => DB_DBNAME,
        'port' => DB_PORT,
        'charset' => DB_CHARSET
    );

    private function __construct(){
        
    }

    /**
     * 单例模式实例化对象
     *
     * @return Db
     */
    public static function getInstance(){
        if (! (self::$_instance instanceof self)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * 连接数据库
     */
    public function connect(){
        if (! self::$_connectSource) {
            self::$_connectSource = mysqli_connect($this->_dbConfig['host'], $this->_dbConfig['user'], $this->_dbConfig['password'], $this->_dbConfig['database'], $this->_dbConfig['port']);
            mysqli_set_charset(self::$_connectSource,$this->_dbConfig['charset']);
		if (! self::$_connectSource) {
                die("数据库连接失败Error:" . mysqli_errno() . ":" . mysqli_error());
                // die('mysql connect error' . mysql_error());
            }
        }
	//mysqli_set_charset(self::$_connectSource,$this->_dbConfig['charset']);//在这里会重新给资源？？

        return self::$_connectSource;
    }

    /**
     * 关闭数据库连接
     */
    public function close(){
        return mysqli_close($this->connect());
    }

    /**
     * 完成记录插入操作
     *
     * @param string $talbe            
     * @param array $array            
     * @return number
     */
    public function insert($table, $array){//需要判断$array不为空，不然会报出警告
        $keys = join(",", array_keys($array));
        $vals = "'" . join("','", array_values($array)) . "'";
        $sql = "insert {$table}($keys) values({$vals})";
        $link = mysqli_query($this->connect(), $sql);
        return $link;
    }
    
    // update imooc_admin set username='king',username2='king2' where id=1;
    /**
     * 完成更新操作
     *
     * @param string $table            
     * @param array $array            
     * @param string $where            
     * @return number
     */
    public function update($table, $array, $where = null){
        $str = null;
        foreach ($array as $key => $val) {
            if ($str == null) {
                $sep = "";
            } else {
                $sep = ",";
            }
            $str .= $sep . $key . "='" . $val . "'";
        }
        $sql = "update {$table} set {$str} " . ($where == null ? null : " where " . $where);
        //echo $sql;
        //exit;
        $link = mysqli_query($this->connect(), $sql);
        return $link;
    }

    /**
     * 完成删除操作
     *
     * @param string $table            
     * @param string $where            
     * @return number
     */
    public function delete($table, $where = null){
        $where = $where == null ? null : " where " . $where;
        $sql = "delete from {$table} {$where}";
        $link = mysqli_query($this->connect(), $sql);
        return $link;
    }

    /**
     * 事务处理
     * @param array $sqls
     * @return boolean
     */
    public function transaction($sqls){
        mysqli_autocommit($this->connect(), FALSE);//关闭自动提交功能
        foreach ($sqls as $sql){
            //echo $sql,'<hr>';
            $result = mysqli_query($this->connect(), $sql);
            $count = mysqli_affected_rows($this->connect());
            if(!$result || $count<1){//有执行失败的sql则回滚
                mysqli_rollback($this->connect());//回滚
                return false;
            }
        }
        //全部执行成功则提交
        mysqli_commit($this->connect());//提交
        mysqli_autocommit($this->connect(),TRUE);//关闭事务处理
        return true;
    }
    
    /**
     * 得到指定一条记录
     * 
     * @param string $sql            
     * @param string $result_type            
     * @return multitype://多类型？？？
     */
    public function fetchOne($sql, $result_type = MYSQLI_ASSOC){
        // echo $sql;
        $result = mysqli_query($this->connect(), $sql);
        if ($result) {
            $row = mysqli_fetch_array($result, $result_type);
            return $row; // 若成功但是没有数据则为null
        } else {
            return false; // 获取失败
        }
    }

    /**
     * 得到结果集中所有记录...
     * 
     * @param string $sql            
     * @param string $result_type            
     * @return multitype:
     */
    public function fetchAll($sql, $result_type = MYSQLI_ASSOC){
        // $sql;
        $result = mysqli_query($this->connect(), $sql);
        if ($result) {
            while ($row = mysqli_fetch_array($result, $result_type)) { // 前面的@加上后不弹出错误信息
                $rows[] = $row;
            }
            return $rows; // 若成功但是没有数据则为null
        } else {
            return false; // 获取失败
        }
    }

    /**
     * 得到结果集中记录的条数
     * 
     * @param string $sql            
     * @return number
     */
    public function getResultNum($sql){
        $result = mysqli_query($this->connect(), $sql);
        if ($result) {
            $count = mysqli_num_rows($result);
            if ($count) {
                mysqli_free_result($result);
            }
        } else {
            return false; // 获取失败
        }
        return $count;
    }

    /**
     * 得到表的最大id
     * 参数 表名
     * 
     * @return number
     */
    public function getInsertId(){
        //$sql = "select max(id) from $table";
        $lastid = mysqli_insert_id($this->connect());
	return $lastid;
	/*$row = $this->fetchOne($sql);
        $id = $row['max(id)'];
        return $id;*/
    }
    // mysqli_insert_id这个函数再该版本的php中已经弃用了
}















