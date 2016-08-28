<?php
/**
*本地开发时用的配置文件
*@author Joker-Long
*编写日期：2016年8月27日下午12:31:03
*/
//define("DB_HOST","localhost");//怎么linux不能识别localhost
define("DB_HOST","127.0.0.1");//主机名
define("DB_USER","root");//数据库用户名
define("DB_PWD","root");//数据库用户密码
define("DB_DBNAME","huiwanspace");//选择的数据库
define("DB_PORT","3306");//数据库端口号
define("DB_CHARSET","UTF8");//数据库发送和接受的时候的编码格式
define("OUR_SITE","http://49.140.166.99:8080/huiwanspace");//服务器地址
define("FILE_PATH", "F:/11111/");//自动添加测试数据的文件地址