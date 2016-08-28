<?php
/**
 *图片操作类
 *tags
 *@author Joker-Long
 *编写日期：2016年8月13日上午9:32:57
 */
class Image
{
    //通过GD库做验证码
    /**
     * @param int $type
     * @param int $length
     * @param int $pixel
     * @param int $line
     * @param string $sess_name
     */
    public static function verifyImage($type=1,$length=4,$pixel=0,$line=0,$sess_name="verify"){
        //session_start();//包含文件里已经开启了，这里再开启会出错
        //创建画布
        $width = 80;
        $height = 28;
        $image = imagecreatetruecolor($width, $height);  //返回一个图像标识符，代表了一幅大小为 x_size  和 y_size  的黑色图像。
        $white = imagecolorallocate($image, 255, 255, 255);//改变画布颜色
        $black = imagecolorallocate($image, 0,0,0);
        //用填充矩形填充画布
        imagefilledrectangle($image, 1,1,$width-2,$height-2, $white);
        //确定验证码的类型和长度，字符串的操作，在string.func.php中封装
        $chars = Str::buildRandomString($type,$length);
//echo $chars;
//exit;
        //接受字符串，为对比验证码做准备
        $_SESSION[$sess_name]=$chars;
        //字体文件数组
        $fontfiles=array("MSYH.TTF","MSYHBD.TTF","SIMHEI.TTF","SIMKAI.TTF","SIMSUN.TTC");
        //将生成的字符串一个一个输入到画布中，不能一次输入吗？
        for($i=0;$i<$length;$i++){
            $size=mt_rand(14,18);   //字符的大小
            $angle=mt_rand(-15,15); //字符的旋转角度
            $x=5+$i*$size;          //字符的位置x轴
            $y=mt_rand(20,26);      //字符的位置y轴
            $fontfile="../fonts/".$fontfiles[mt_rand(0,count($fontfiles)-1)];//随机选取字体
            $color=imagecolorallocate($image, mt_rand(50,90), mt_rand(80,200),mt_rand(90,180)); //字符的颜色
            $text=substr($chars, $i,1);//每次截取一个字符
//var_dump($fontfile);
//exit;
            imagettftext($image, $size, $angle, $x, $y, $color, $fontfile, $text);//往画布填上字符
        }
        //加上点干扰,$pixel默认是0,就是不用点干扰
        if($pixel){
            for($i=0;$i<$pixel;$i++){
                imagesetpixel($image, mt_rand(0,$width-1),mt_rand(0,$height-1), $black);
            }
        }
        //加上线干扰，$line默认是0，就是不用线干扰
        if($line){
            for($i=0;$i<$line;$i++){
                $color=imagecolorallocate($image, mt_rand(50,90),mt_rand(80,200),mt_rand(90,180));
                imageline($image, mt_rand(0,$width-1),mt_rand(0,$height),mt_rand(0,$width-1),mt_rand(0,$height-1),$color);
            }
        }
        //输出格式
        header("content-type:image/gif");
        //输出画布
        $bool = imagegif ( $image );
        if($bool){
            //销毁画布
            imagedestroy($image);
            return true;
        }else{
            //销毁画布
            imagedestroy($image);
            return false;
        }
    
    }
    
    public static function thumb($filename,$destination=null,$dst_w=null,$dst_h=null,$isReservedSource=true,$scale=0.5){
list($src_w,$src_h,$imagetype)=getimagesize($filename);
        if(is_null($dst_w)||is_null($dst_h)){
            $dst_w=ceil($src_w*$scale);
            $dst_h=ceil($src_h*$scale);
        }
        $mime=image_type_to_mime_type($imagetype);
 //创建图像
        $createFun=str_replace("/", "createfrom", $mime);//例如，image/jpg换成imagecreatefromjpg,通用
     //输出图像
        $outFun=str_replace("/",null,$mime);//例如image/jpg换成imagejpg;
        $src_image=$createFun($filename);
        $dst_image=imagecreatetruecolor($dst_w, $dst_h);
        //改变图像大小
        imagecopyresampled($dst_image, $src_image, 0, 0, 0, 0, $dst_w, $dst_h, $src_w, $src_h);
        //创建目标文件夹
        if($destination&&!file_exists(dirname($destination))){
            mkdir(dirname($destination),0777,true);
        }
        $dstFilename=$destination==null?Str::getUniName().".".Str::getExt($filename):$destination;
        //输出图片
      $bool =  $outFun($dst_image,$dstFilename);
 imagedestroy($src_image);
        imagedestroy($dst_image);
        if(!$isReservedSource){//$isReservedSource默认为true,表示不删除原图片
            unlink($filename);
        }
        return $dstFilename;
    }
    
    public static function waterText($filename,$text="世龙帅气，嘿嘿嘿！！",$fontfile="MSYH.TTF"){
        $fileInfo=getimagesize($filename);
        $tw=ceil($fileInfo[0]/3);
        $th=ceil($fileInfo[1]/2);
        $ts=ceil($fileInfo[0]/10);
        $mime=$fileInfo['mime'];
        $createFun=str_replace("/", "createfrom", $mime);
        $outFun=str_replace("/", null, $mime);
        $image=$createFun($filename);
        $color=imagecolorallocatealpha($image, 255,0,0,10);
        $fontfile="../fonts/{$fontfile}";
        imagettftext($image, $ts, 0, $tw, $th, $color, $fontfile, $text);
        //header("content-type:".$mime);
        $outFun($image,$filename);
        imagedestroy($image);
    }
    
    public static function waterPic($dstFile,$srcFile="../admin/images/logo.jpg",$pct=30){
        $srcFileInfo=getimagesize($srcFile);
        $src_w=$srcFileInfo[0];
        $src_h=$srcFileInfo[1];
        $dstFileInfo=getimagesize($dstFile);
        $srcMime=$srcFileInfo['mime'];
        $dstMime=$dstFileInfo['mime'];
        $createSrcFun=str_replace("/", "createfrom", $srcMime);
        $createDstFun=str_replace("/", "createfrom", $dstMime);
        $outDstFun=str_replace("/", null, $dstMime);
        $dst_im=$createDstFun($dstFile);
        $src_im=$createSrcFun($srcFile);
        imagecopymerge($dst_im, $src_im, 0,0,0,0, $src_w, $src_h,$pct);
        //header("content-type:".$dstMime);
        $outDstFun($dst_im,$dstFile);
        imagedestroy($src_im);
        imagedestroy($dst_im);
    }
    
    
    /**
     * 存储上传图片的缩略图
     *
     */
    public static function addPicture($uploadFiles,$path){
        if(is_array($uploadFiles)&&$uploadFiles){
            foreach ($uploadFiles as $key=>$uploadFile){
                self::thumb($path."/".$uploadFile['name'],"../image_50/".$uploadFile['name'],50,50);
                self::thumb($path."/".$uploadFile['name'],"../image_220/".$uploadFile['name'],220,220);
                self::thumb($path."/".$uploadFile['name'],"../image_350/".$uploadFile['name'],350,350);
                self::thumb($path."/".$uploadFile['name'],"../image_800/".$uploadFile['name'],800,800);
            }
        }else{
            $mes="您还没有添加图片哦，请重新操作。<a href='addSituation.php' target='mainFrame'>重新添加</a>";
            return $mes;
        }
    }
    /**
     * 删除项目本地图片
     *
     */
    public static function deletePicture($uploadFiles){
        foreach($uploadFiles as $uploadFile){
            if(file_exists("../uploads/".$uploadFile['name'])){
                unlink("../uploads/".$uploadFile['name']);
            }
            if(file_exists("../image_800/".$uploadFile['name'])){
                unlink("../image_800/".$uploadFile['name']);
            }
            if(file_exists("../image_50/".$uploadFile['name'])){
                unlink("../image_50/".$uploadFile['name']);
            }
            if(file_exists("../image_220/".$uploadFile['name'])){
                unlink("../image_220/".$uploadFile['name']);
            }
            if(file_exists("../image_350/".$uploadFile['name'])){
                unlink("../image_350/".$uploadFile['name']);
            }
        }
    }
    
    
    
}
