// JavaScript Document
$(document).ready(function() {
	//获取cookie
	$(function getCookie(){//name为cookie名称
        $("#username").html($.cookie("username"));
	});
	//表单验证
    $("form").validate({
		errorPlacement: function(error,element){
			element.parent().next().html(error);
		},
		rules:{
			verify:{
				required:true
			},
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
			verify:{
				required:"*请先获取短信验证码,并正确填写",
			},
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
	//获取短信验证码按钮css
	$("#get_verify").mouseover(function(){
		$(this).css("cssText","background:rgba(0,175,66,1);box-shadow:1.2px 1.2px 4px #CCC;");
	});
	$("#get_verify").mouseout(function(){
		$(this).css("cssText","background:rgba(0,175,66,0.5);box-shadow:none");
	});
	//获取短信验证码按钮click事件
	$("#get_verify").click(function get(){
		var $verify_time=60;
		var $phoneverifyflag=0;
		$.ajax({
			type:"POST",
			url:"http://49.140.166.99:8080/huiwanspace/api/webServer.php",
			dataType:"json",
			data:{
				action:"getMessageForReg",
				phone:$.cookie("username")
			},
			success:function(data){
				if(data.status==1){
					alert("验证码发送成功，请留意手机短信!")
					$phoneverifyflag=data.status;
				}
				else{
					$phoneverifyflag=data.status;
					$(".ierror:first").html("*"+data.message);
				}
			},
			error:function(jqXHR){
				alert("发生错误："+jqXHR.status);
			}
		});
		$(function time(){
			if($verify_time==0){
				$phoneverifyflag=0;
				$("#get_verify").html('获取短信验证码');
				$("#get_verify").css('background','rgba(0,175,66,0.5)');
				$("#get_verify").css('box-shadow','none');
				$("#get_verify").css('cursor','pointer');
				$("#get_verify").on('mouseover',function(){
					$("#get_verify").css('background','rgba(0,175,66,1)');
					$("#get_verify").css('box-shadow','1.2px 1.2px 4px #CCC');
				});
				$("#get_verify").on('mouseout',function(){
					$("#get_verify").css('background','rgba(0,175,66,0.5)');
					$("#get_verify").css('box-shadow','none');
				});
				$("#get_verify").on('click',get);
				$verify_time=60;
			}
			else if($phoneverifyflag==1&&$verify_time!=0){
				$("#get_verify").html($verify_time+'s后重新发送');
				$("#get_verify").css('background','rgba(153,153,153,0.5)');
				$("#get_verify").css('box-shadow','none');
				$("#get_verify").css('cursor','default');
				$("#get_verify").off('mouseover');
				$("#get_verify").off('mouseout');
				$("#get_verify").off('click');
				$verify_time--;
				setTimeout(function(){time();},1000);
			}
		});
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
				action:"resetPasswordForPhone",
				phone:$.cookie("username"),
				password:$("#password").val(),
				messageCode:$("#verify").val()
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