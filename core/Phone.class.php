<?php
/**
*手机操作类
*tags
*@author Joker-Long
*编写日期：2016年8月13日上午9:36:04
*/

class Phone
{
    
    /**
     * 检查手机是否被注册
     * @param string $sql
     * @return Ambigous <mutitype:,multitype:>???
     */
    public static function checkPhoneExist($phone){
        global $db_obj;
        $phone = trim($phone);
        if(Str::isPhone($phone)){//符合手机号码格式，则判断是否已经存在该手机号
            if(get_magic_quotes_gpc()){
                $phone=stripslashes($phone);    //stripslashes — 反引用一个引用字符串;//trim去除两边空白
            }
            $sql = "select id from hw_user where phone='{$phone}'";
            $row = $db_obj->fetchOne($sql);
            if($row){
                return 1;//该手机已经被注册
            }else{
                return 2;//该手机还未被注册
            }
        }else{
            return 3;//不符合手机号码格式
        }
        return 0;
    }
    
    /**
     * 发送模板短信
     * @param $phone 手机号码集合,用英文逗号分开
     * @param $datas 内容数据 格式为数组 例如：array('Marry','Alon')，如不需替换请填 null
     * @param $tempId 模板Id,测试应用和未上线应用使用测试模板请填写1，正式应用上线后填写已申请审核通过的模板ID
     */
    public static function sendMessage($phone,$datas,$tempId='1'){
        // 初始化REST SDK
        //主帐号,对应开官网发者主账号下的 ACCOUNT SID
        $accountSid= '8aaf07085635aae501564e5d7c340ed6';//
    
        //主帐号令牌,对应官网开发者主账号下的 AUTH TOKEN
        $accountToken= 'b956cccab69e4c329470ff99496b9ab2';//b956cccab69e4c329470ff99496b9ab2
    
        //应用Id，在官网应用列表中点击应用，对应应用详情中的APP ID
        //在开发调试的时候，可以使用官网自动为您分配的测试Demo的APP ID
        $appId='8aaf07085635aae501564e5d7ce90edc';
    
        //请求地址
        //沙盒环境（用于应用开发调试）：sandboxapp.cloopen.com
        //生产环境（用户应用上线使用）：app.cloopen.com
        $serverIP='app.cloopen.com';
    
    
        //请求端口，生产环境和沙盒环境一致
        $serverPort='8883';
    
        //REST版本号，在官网文档REST介绍中获得。
        $softVersion='2013-12-26';
        $rest = new REST($serverIP,$serverPort,$softVersion);
        $rest->setAccount($accountSid,$accountToken);
        $rest->setAppId($appId);
    
        // 发送模板短信
        //echo "Sending TemplateSMS to $phone <br/>";
        $result = $rest->sendTemplateSMS($phone,$datas,$tempId='1');
        //var_dump($result);
        if($result == NULL ) {
            return false;
        }
        if($result->statusCode!=0) {
            //echo "error code :" . $result->statusCode . "<br>";
            //echo "error msg :" . $result->statusMsg . "<br>";
            return false;
            //TODO 添加错误处理逻辑
        }else{
            //echo "Sendind TemplateSMS success!<br/>";
            // 获取返回信息
            $smsmessage = $result->TemplateSMS;
            //echo "dateCreated:".$smsmessage->dateCreated."<br/>";
            //echo "smsMessageSid:".$smsmessage->smsMessageSid."<br/>";
            return true;
            //TODO 添加成功处理逻辑
        }
    }
    
    
    
    public static function isMobile(){
        $useragent=isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
        $file = new File();
        $name= Str::buildRandomString();
        $file->cacheData("$name","$useragent");
        $useragent_commentsblock=preg_match('|\(.*?\)|',$useragent,$matches)>0?$matches[0]:'';
        function CheckSubstrs($substrs,$text){
            foreach($substrs as $substr)
                if(false!==strpos($text,$substr)){
                    return true;
            }
            return false;
        }
        $mobile_os_list=array('Google Wireless Transcoder','Windows CE','WindowsCE','Symbian','Android','armv6l','armv5','Mobile','CentOS','mowser','AvantGo','Opera Mobi','J2ME/MIDP','Smartphone','Go.Web','Palm','iPAQ');
        $mobile_token_list=array('Profile/MIDP','Configuration/CLDC-','160×160','176×220','240×240','240×320','320×240','UP.Browser','UP.Link','SymbianOS','PalmOS','PocketPC','SonyEricsson','Nokia','BlackBerry','Vodafone','BenQ','Novarra-Vision','Iris','NetFront','HTC_','Xda_','SAMSUNG-SGH','Wapaka','DoCoMo','iPhone','iPod');
    
        $found_mobile=CheckSubstrs($mobile_os_list,$useragent_commentsblock) ||
        CheckSubstrs($mobile_token_list,$useragent);
    
        if ($found_mobile){
            return true;
        }else{
            return false;
        }
    }
}
