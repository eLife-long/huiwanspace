// JavaScript Document

function GetRequest() {
 var url = "?status=1&message=%E6%88%90%E5%8A%9F%E8%B7%B3%E8%BD%AC%E5%88%B0%E4%BF%AE%E6%94%B9%E5%AF%86%E7%A0%81%E9%A1%B5%E9%9D%A2&email=250321160@qq.com&emailCode=367bdc7acf82c452818bc57f20e5c939&sessionId=k2lo584qcmgu5psu6e639i2g26"
 //var url = location.search; //获取url中"?"符后的字串
 var theRequest = new Object();
 if (url.indexOf("?") != -1) {
  var str = url.substr(1);
  strs = str.split("&");
  for(var i = 0; i < strs.length; i ++) {
   theRequest[strs[i].split("=")[0]]=(strs[i].split("=")[1]);
  }
 }
 return theRequest;
}
$(document).ready(function() {
	var $emailcode;
	var $session;
    $(function(){
		var Request = new Object();
		Request = GetRequest();
		$("#username").html(Request['email']);
		$session=Request['sessionId'];
		$emailcode=Request['emailCode'];
	});
	//密码表单验证
	$("form").validate({
		errorPlacement: function(error,element){
			element.parent().next().html(error);
		},
		rules:{
			password:{
				required:true,
				minlength:6,
				maxlength:16
			},
			password_again:{
				required:true,
				minlength:6,
				maxlength:16,
				equalTo:"#password"
			}
		},
		messages:{
			password:{
				required:"*必须填写密码",
				minlength:"*密码不能少于6字节",
				maxlength:"*密码不能多于16字节"
			},
			password_again:{
				required:"*必须再次填写密码",
				minlength:"*密码不能少于6字节",
				maxlength:"*密码不能多于16字节",
				equalTo:"*密码不一致"
			}
		}
	});
	//确认键css
	$("#button").mouseover(function(){
		$(this).css("cssText","background:rgba(0,175,66,1);box-shadow:1.2px 1.2px 5px #666");
	});
	$("#button").mouseout(function(){
		$(this).css("cssText","background:rgba(0,175,66,0.8);box-shadow:none");
	});
	//确认键click事件
	$("#button").click( function ok(){
		if($("#password").val()==""||$("#password").val()!=$("#password_again").val()){
			$("#find_email").parent().next().html("*请正确输入新密码")
		};
		var $data;
			$.ajax({
			type:"POST",
			url:"http://49.140.166.99:8080/huiwanspace/api/webServer.php",
			dataType:"json",
			data:{
				action:"resetPasswordForEmail",
				email:$("#username").html(),
				password:$("#password").val(),
				emailCode:$emailcode,
				sessionId:$session
			},
			success:function(data){
				$data=data;
				if(data.status==1){
					alert($data.message);					
				}
				else if(data.status!=1){
					alert("*"+$data.message);
				}
			},
			error:function(jqXHR){
				alert("发生错误："+jqXHR.status);
			}
		});
		$("#button").off('mouseout');
		$("#button").off('mouseover');
		$("#button").off('click');
		$("#button").css("cssText","background:rgba(204,204,204,1);box-shadow:none;cursor:default");
		setTimeout(function(){
			$("#button").css("cssText","background:rgba(0,175,66,0.8);cursor:pointer");	
			$("#button").on('mouseover',function(){
				$(this).css('background','rgba(0,175,66,1)');
				$(this).css('box-shadow','1.2px 1.2px 5px #666');});
			$("#button").on('mouseout',function(){
				$(this).css('background','rgba(0,175,66,0.8)');
				$(this).css('box-shadow','none');});
			$("#button").on('click',ok);
		},2000);	
	});
});