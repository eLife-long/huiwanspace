-- 创建数据库eLife
CREATE DATABASE IF NOT EXISTS `eLife` CHARACTER SET 'utf8' COLLATE 'utf8_general_ci';
USE `eLife`;
-- 防止中文乱码
-- 创建管理员表eLife_admin -- 之后要有一个空格，不然dos会响一下，怎么回事？
DROP TABLE IF EXISTS `eLife_admin`;
CREATE TABLE `eLife_admin`(
	`id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT KEY,
	`username` varchar(20) NOT NULL UNIQUE,
	`password` char(32) NOT NULL,
	`email` varchar(50) NOT NULL
)ENGINE=InnoDB DEFAULT CHARSET=utf8;
INSERT eLife_admin value(1,"long","long","250321160@qq.com");

-- 创建校区表eLife_campus
DROP TABLE IF EXISTS `eLife_campus`;
CREATE TABLE `eLife_campus`(
	`id` smallint(5) unsigned NOT NULL AUTO_INCREMENT KEY,
	`cName` varchar(50) DEFAULT NULL UNIQUE
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 在NOT NULL之间输入的空格的格式不对则sql语句不通过，忘了用了什么格式了，不小心按到的，两种格式的空格距离不一样

-- 创建商店表eLife_store
DROP TABLE IF EXISTS `eLife_store`;
CREATE TABLE `eLife_store`(
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT KEY,
	`cId` int(10) unsigned NOT NULL,
	`sName` varchar(50) NOT NULL UNIQUE,
	`sDese` text,
	`sImg` varchar(255) NOT NULL
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 创建活动表eLife_active
DROP TABLE IF EXISTS `eLife_active`;
CREATE TABLE `eLife_active`(
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT KEY,
	`sId` int(10) unsigned NOT NULL,
	`aDese` text,
	`aTimes` int(20) unsigned NOT NULL
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 创建用户表`eLife_user`
DROP TABLE IF EXISTS `eLife_user`;
CREATE TABLE `eLife_user`(
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT KEY,
	`username` varchar(20) NOT NULL UNIQUE,
	`password` char(32)	NOT NULL,
	`sex` enum('男','女','保密') NOT NULL DEFAULT '保密',
	`email` varchar(50) NOT NULL,
	`face` varchar(50) NOT NULL,
	`regTime` int (10) unsigned NOT NULL,
	`activeFlag` tinyint(1) DEFAULT '0'
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

