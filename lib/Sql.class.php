<?php
/**
*拼接sql语句操作类
*@author Joker-Long
*编写日期：2016年8月17日下午4:09:04
*/
class Sql
{
    /**
     * 得到插入的sql
     * @param string $table
     * @param array $array
     * @return string
     */
    public static function sqlForInsert($table, $array){
        $keys = join(",", array_keys($array));
        $vals = "'" . join("','", array_values($array)) . "'";
        $sql = "insert {$table}($keys) values({$vals})";
        //echo $sql;
        return $sql;
    }
    
    /**
     * 得到更新的sql
     * @param string $table
     * @param array $array
     * @param string $where
     * @return string
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
        return $sql;
    }
}