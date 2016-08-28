<?php
/**
*相册操作类
*tags
*@author Joker-Long
*编写日期：2016年8月13日下午3:29:24
*/
class Album
{
    /**
     * 添加图片路径
    * @param unknown $arr
    * @return $link
    */
    public static function addAlbum($arr){
        global $db_obj;
        $link = $db_obj->insert("hw_album", $arr);
        return $link;
    }
    
    /**
     * 删除图片路径
     * @param unknown $arr
     * @return $link
     */
    public static function delAlbum($where){
        global $db_obj;
        $link = $db_obj->delete("hw_album", $where);
        return $link;
    }
    
    /**
     * 得到图片路径
     * @param unknown $arr
     * @return $link
     */
    public static function getAlbum($where){
        global $db_obj;
        $sql = "select * from hw_album where {$where}";
        $rows = $db_obj->fetchAll($sql);
        return $rows;
    }
    
    /**
     * 根据活动id得到活动所有图片
     * @param int $sId
     * @return array
     */
    public static function getSituationImageBysId($sId){
        global $db_obj;
        $sql="select id,albumPath from hw_album where sId={$sId} ";
        $rows=$db_obj->fetchAll($sql);
        return $rows;
    }
    
    /**
     * 添加活动图片
     * @param unknown $id
     * @return string
     */
    public static function addSituationImage($id){
        $sId = $id;
        $path="../uploads";
        $uploadFiles = Upload::uploadFile($path);
        //* 存储上传图片的缩略图
        Image::addPicture($uploadFiles,$path);
        if($sId){
            foreach ($uploadFiles as $uploadFile) {
                $arr1['sId'] = $sId;
                $arr1['albumPath'] = $uploadFile['name'];
                $link = self::addAlbum($arr1);//判断一下是否添加成功
            }
            $mes="<p>添加成功！！</p><a href='addImageBySituation.php' target='mainFrame'>继续添加</a>|<a href='listImageBySituation.php' target='mainFrame'>查看商品列表</a>";
        }else{
            //删除上传的图片
            Image::deletePicture($uploadFiles);
            $mes="<p>添加失败!</p><a href='addImageBySituation.php' target='mainFrame'>重新添加</a>";
        }
        return $mes;
    }
    
    /**
     * 删除用户图片
     * @param string $id要批量删除
     * @return string
     */
    public static function delSituationImage($id){
        $where = "sId in ($id)";
        $rows = self::getAlbum($where);
        if($rows){
            //先删除图片
            foreach ($rows as $row){
                if(file_exists("../uploads/".$row['albumPath'])){
                    unlink("../uploads/".$row['albumPath']);
                }
                if(file_exists("../image_800/".$row['albumPath'])){
                    unlink("../image_800/".$row['albumPath']);
                }
                if(file_exists("../image_50/".$row['albumPath'])){
                    unlink("../image_50/".$row['albumPath']);
                }
                if(file_exists("../image_220/".$row['albumPath'])){
                    unlink("../image_220/".$row['albumPath']);
                }
                if(file_exists("../image_350/".$row['albumPath'])){
                    unlink("../image_350/".$row['albumPath']);
                }
                //在删除hw_album表中的路径记录
                $link = self::delAlbum($where);
                if(!$link){
                    $mes = "删除图片路径失败";
                    return false;
                }
            }
        }elseif($rows === false){
            $mes = "获取图片路径失败";
            return false;
        }else{
            return true;//没有图片，不用删除
        }
        return true;//删除相册表成功
    }
}