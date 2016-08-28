// JavaScript Document
//获取验证码图片
function verify_Create(parent){
	var newa=document.createElement('img');
	var request=new XMLHttpRequest();
	request.open('GET',"http://49.140.166.99:8080/eLife/api/server.php?action=getVerify",true);
	request.send();
	request.onreadystatechange=function(){
		if(request.readyState===4){
			if(request.status===200){
				newa.src="http://49.140.166.99:8080/eLife/api/server.php?action=getVerify";
			}
		}
	}
	newa.setAttribute('id','verify_img');
	newa.setAttribute('width','80px');
	newa.setAttribute('height','28px');
	parent.appendChild(newa);
}
//刷新验证码图片
function verify_F5(){
    var verify0=document.getElementsByClassName('find_verify')[0];
    var verify1=document.getElementsByClassName('find_verify')[1];
    var parent0=verify0.parentNode;
    var parent1=verify1.parentNode;
	var lastChild0=parent0.lastElementChild?parent0.lastElementChild:parent0.lastChild; 
	var lastChild1=parent1.lastElementChild?parent1.lastElementChild:parent1.lastChild; 
	parent0.removeChild(lastChild0);
	parent1.removeChild(lastChild1);
	verify_Create(parent0);
	verify_Create(parent1);
}

$(document).ready(function(){
	//改密界面的显示与隐藏
	$("#main_left_change").click(function(){
		$("#main_right_find").css('display','none');
		$("#main_right_change").css('display','block');
		$("#main_left_find").css('background','rgba(0,175,66,0.3)');
		$("#main_left_find").stop().animate({left:'22px'},300);
	});
	//找回密码界面的显示与隐藏
	$("#main_left_find").click(function(){
		$("#main_right_find").css('display','block');
		$("#main_right_change").css('display','none');
		$("#main_left_change").css('background','rgba(0,175,66,0.3)');
		$("#main_left_change").stop().animate({left:'22px'},300);
	});
	//左侧栏滑过样式
	$("#main_left_find").mouseover(function(){
		$(this).css('background','rgba(0,175,66,1)');
		$(this).stop().animate({left:'52px'},300);
	});
	$("#main_left_find").mouseout(function(){
		if($("#main_right_change").is(":hidden")){}else{
		$(this).css('background','rgba(0,175,66,0.3)');
		$(this).stop().animate({left:'22px'},300);}
	});
	$("#main_left_change").mouseover(function(){
		$(this).css('background','rgba(0,175,66,1)');
		$(this).stop().animate({left:'52px'},300);
	});
	$("#main_left_change").mouseout(function(){
		if($("#main_right_change").is(":hidden")){
		$(this).css('background','rgba(0,175,66,0.3)');
		$(this).stop().animate({left:'22px'},300);}
	});
	//换一张图片验证码
	$(".change_verify").click(function(){
		verify_F5();
	});
	//账户方式选择滑过样式
	$("#find_phone_selected").mouseover(function(){
		$(this).css('background','rgba(0,175,66,1)');
	});
	$("#find_phone_selected").click(function(){
		if($("#main_right_find_form_phone").is(":hidden")){
			$("#main_right_find_form_email").slideUp(300);
			$("#main_right_find_form_phone").slideDown(300);
			$(this).off('mouseout');
			$(this).css('box-shadow','2px 5px 5px #CCC');
			$(this).next().on('mouseout',function(){
				$(this).css('background','rgba(227,227,227,0.8)');
				});
			$(this).next().css('background','rgba(227,227,227,0.8)');
			$(this).next().css('box-shadow','none');}
	});
	
	$("#find_email_selected").mouseover(function(){
		$(this).css('background','rgba(0,175,66,1)');
	});
	$("#find_email_selected").mouseout(function(){
		$(this).css('background','rgba(227,227,227,0.8)');
	});
	$("#find_email_selected").click(function(){
		if($("#main_right_find_form_email").is(":hidden")){
			$("#main_right_find_form_phone").slideUp(300);
			$("#main_right_find_form_email").slideDown(300);
			$(this).off('mouseout');
			$(this).css('box-shadow','2px 5px 5px #CCC');
			$(this).prev().on('mouseout',function(){
				$(this).css('background','rgba(227,227,227,0.8)');
				});
			$(this).prev().css('background','rgba(227,227,227,0.8)');
			$(this).prev().css('box-shadow','none');}
	});
	
	$("#change_phone_selected").mouseover(function(){
		$(this).css('background','rgba(0,175,66,1)');
	});
	$("#change_phone_selected").click(function(){
		if($("#main_right_change_form_phone").is(":hidden")){
			$("#main_right_change_form_email").slideUp(300);
			$("#main_right_change_form_phone").slideDown(300);
			$(this).off('mouseout');
			$(this).css('box-shadow','2px 5px 5px #CCC');
			$(this).next().on('mouseout',function(){
				$(this).css('background','rgba(227,227,227,0.8)');
				});
			$(this).next().css('background','rgba(227,227,227,0.8)');
			$(this).next().css('box-shadow','none');}
	});
	
	$("#change_email_selected").mouseover(function(){
		$(this).css('background','rgba(0,175,66,1)');
	});
	$("#change_email_selected").mouseout(function(){
		$(this).css('background','rgba(227,227,227,0.8)');
	});
	$("#change_email_selected").click(function(){
		if($("#main_right_change_form_email").is(":hidden")){
			$("#main_right_change_form_phone").slideUp(300);
			$("#main_right_change_form_email").slideDown(300);
			$(this).off('mouseout');
			$(this).css('box-shadow','2px 5px 5px #CCC');
			$(this).prev().on('mouseout',function(){
				$(this).css('background','rgba(227,227,227,0.8)');
				});
			$(this).prev().css('background','rgba(227,227,227,0.8)');
			$(this).prev().css('box-shadow','none');}
	});
	//手机找密表单验证
	$("#main_right_find_form_phone").validate({
		errorPlacement: function(error,element){
			element.parent().next().html(error);
		},
		rules:{
			find_phone:{
				required:true,
				minlength:11,
				maxlength:11,
				remote:{
                	url: "http://49.140.166.99:8080/huiwanspace/api/webServer.php",
                	type: "Post",
					dataType:"json",
                	data: {
						action:"phoneExist",
						phone:function(){return $("#find_phone").val()}
					}
            	}
			},
			find_phone_verify:{
				required:true,
			}
		},
		messages:{
			find_phone:{
				required:"*必须填写手机号码",
				minlength:"*请输入正确的手机号码",
				maxlength:"*请输入正确的手机号码",
				remote:"*该手机号码账户不存在"
			},
			find_phone_verify:{
				required:"*请填写验证码",
			}
		}
	});
	//邮箱找密表单验证
	$("#main_right_find_form_email").validate({
		errorPlacement: function(error,element){
			element.parent().next().html(error);
		},
		rules:{
			find_email:{
				required:true,
				email:true,
				remote:{
                	url: "http://49.140.166.99:8080/huiwanspace/api/webServer.php",
                	type: "Post",
					dataType:"json",
                	data: {
						action:"emailExist",
						email:function(){return $("#find_email").val()}
					}
            	}
			},
			find_email_verify:{
				required:true,
			}
		},
		messages:{
			find_email:{
				required:"*必须填写邮箱地址",
				email:"*请填写正确的邮箱地址",
				remote:"*该邮箱地址账户不存在"
			},
			find_email_verify:{
				required:"*请填写验证码",
			}
		}
	});
	//手机改密表单验证
	$("#main_right_change_form_phone").validate({
		errorPlacement: function(error,element){
			element.parent().next().html(error);
		},
		rules:{
			change_phone:{
				required:true,
				minlength:11,
				maxlength:11,
				remote:{
                	url: "http://49.140.166.99:8080/huiwanspace/api/webServer.php",
                	type: "Post",
					dataType:"json",
                	data: {
						action:"phoneExist",
						phone:function(){return $("#change_phone").val()}
					}
            	}
			},
			change_phone_old_password:{
				required:true
			}
		},
		messages:{
			change_phone:{
				required:"*必须填写手机号码",
				minlength:"*请输入正确的手机号码",
				maxlength:"*请输入正确的手机号码",
				remote:"*该手机号码账户不存在"
			},
			change_phone_old_password:{
				required:"*必须填写旧密码"
			}
		}
	});
	//邮箱改密表单验证
	$("#main_right_change_form_email").validate({
		errorPlacement: function(error,element){
			element.parent().next().html(error);
		},
		rules:{
			change_email:{
				required:true,
				email:true,
				remote:{
                	url: "http://49.140.166.99:8080/huiwanspace/api/webServer.php",
                	type: "Post",
					dataType:"json",
                	data: {
						action:"emailExist",
						email:function(){return $("#change_email").val()}
					}
            	}
			},
			change_email_old_password:{
				required:true
			}
		},
		messages:{
			change_email:{
				required:"*必须填写邮箱地址",
				email:"*必须填写正确的邮箱地址",
				remote:"*该邮箱地址账户不存在"
			},
			change_email_old_password:{
				required:"*必须填写旧密码"
			}
		}
	});
	$(".next").mouseover(function(){
		$(this).css('background','rgba(0,175,66,1)');
		$(this).css('box-shadow','2px 2px 5px #CCC');
	});
	$(".next").mouseout(function(){
		$(this).css('background','rgba(0,175,66,0.8)');
		$(this).css('box-shadow','none');
	});
	//手机找回密码
	$("#find_phone_next_button").click(function phone_find_password(){
		if($("#find_phone").val()==""){
			$("#find_phone").parent().next().html("*请输入手机号码")
		};
		var $data;
		$.ajax({
			type:"POST",
			url:"http://49.140.166.99:8080/huiwanspace/api/webServer.php",
			dataType:"json",
			data:{
				action:"forgetPassword",
				agent:"web",
				longinId:$("#find_phone").val(),
				verify:$("#find_phone_next_button").prev().prev().prev().children(".find_verify").val()
			},
			success:function(data){
				$data=data;
				if(data.status==1){
					$("#find_phone_next_button").prev().prev().css('color','green');
					$("#find_phone_next_button").prev().prev().html($data.message);	
					$.cookie("username",$("#find_phone").val(),{path : '/'});				
				}
				else if(data.status!=1){
					$("#find_phone_next_button").prev().prev().css('color','#F00');
					$("#find_phone_next_button").prev().prev().html("*"+$data.message);
				}
			},
			error:function(jqXHR){
				alert("发生错误："+jqXHR.status);
			}
		});
		$("#find_phone_next_button").off('mouseout');
		$("#find_phone_next_button").off('mouseover');
		$("#find_phone_next_button").off('click');
		$("#find_phone_next_button").css("cssText","background:rgba(204,204,204,1);box-shadow:none;cursor:default");
		setTimeout(function(){
			$("#find_phone_next_button").css("cssText","background:rgba(0,175,66,0.8);cursor:pointer");	
			$("#find_phone_next_button").on('mouseover',function(){
				$(this).css('background','rgba(0,175,66,1)');
				$(this).css('box-shadow','2px 2px 5px #CCC');});
			$("#find_phone_next_button").on('mouseout',function(){
				$(this).css('background','rgba(0,175,66,0.8)');
				$(this).css('box-shadow','none');});
			$("#find_phone_next_button").on('click',phone_find_password);
		},2000);	
	});
	$(document).keydown(function(event){ 
    	if(event.which==13&&(!$("#main_right_find_form_phone").is(":hidden"))){
  				$("#find_phone_next_button").trigger("click")}
 	});
	//邮箱找回密码
	$("#find_email_next_button").click(function email_find_password(){
		if($("#find_email").val()==""){
			$("#find_email").parent().next().html("*请输入邮箱地址")
		};
		var $data;
			$.ajax({
			type:"POST",
			url:"http://49.140.166.99:8080/huiwanspace/api/webServer.php",
			dataType:"json",
			data:{
				action:"forgetPassword",
				agent:"web",
				longinId:$("#find_email").val(),
				verify:$("#find_email_next_button").prev().prev().prev().find(".find_verify").val()
			},
			success:function(data){
				$data=data;
				if(data.status==1){
					$("#find_email_next_button").prev().prev().css('color','green');
					$("#find_email_next_button").prev().prev().html($data.message);					
				}
				else if(data.status!=1){
					$("#find_email_next_button").prev().prev().css('color','#F00');
					$("#find_email_next_button").prev().prev().html("*"+$data.message);
				}
			},
			error:function(jqXHR){
				alert("发生错误："+jqXHR.status);
			}
		});
		$("#find_email_next_button").off('mouseout');
		$("#find_email_next_button").off('mouseover');
		$("#find_email_next_button").off('click');
		$("#find_email_next_button").css("cssText","background:rgba(204,204,204,1);box-shadow:none;cursor:default");
		setTimeout(function(){
			$("#find_email_next_button").css("cssText","background:rgba(0,175,66,0.8);cursor:pointer");	
			$("#find_email_next_button").on('mouseover',function(){
				$(this).css('background','rgba(0,175,66,1)');
				$(this).css('box-shadow','2px 2px 5px #CCC');});
			$("#find_email_next_button").on('mouseout',function(){
				$(this).css('background','rgba(0,175,66,0.8)');
				$(this).css('box-shadow','none');});
			$("#find_email_next_button").on('click',email_find_password);
		},2000);	
	});
	$(document).keydown(function(event){ 
    	if(event.which==13&&(!$("#main_right_find_form_email").is(":hidden"))){
  				$("#find_email_next_button").trigger("click")}
 	});
	/*
	//手机修改密码
	$("#find_phone_next_button").click(function(){
		if($("#change_phone").val()==""){
			$("#change_phone").parent().next().html("*请输入手机号码")
		};
		var $data;
		$.ajax({
			type:"POST",
			url:"http://49.140.166.99:8080/huiwanspace/api/webServer.php",
			dataType:"json",
			data:{
				action:"resetPassword",
				agent:"web",
				longinId:$("#change_phone").val(),
				verify:$("#change_phone_next_button").prev().prev().children(".change_verify").val()
			},
			success:function(data){
				$data=data;
				if(data.status==1){
					$("#change_phone_next_button").prev().prev().css('color','green');
					$("#change_phone_next_button").prev().prev().html($data.message);					
				}
				else if(data.status!=1){
					$("#change_phone_next_button").prev().prev().css('color','#F00');
					$("#change_phone_next_button").prev().prev().html("*"+$data.message);
				}
			},
			error:function(jqXHR){
				alert("发生错误："+jqXHR.status);
			}
		});
		$("#change_phone_next_button").attr("id","change_phone_next_buttonen");	
		$("#change_phone_next_buttonen").off('mouseover');
		$("#change_phone_next_buttonen").off('mouseout');	
		setTimeout(function(){
			$("#change_phone_next_buttonen").attr("id","change_phone_next_button");
			$("#change_phone_next_button").on('mouseover',function(){
				$(this).css('background','rgba(0,175,66,1)');
				$(this).css('box-shadow','2px 2px 5px #CCC');});
			$("#change_phone_next_button").on('mouseout',function(){
				$(this).css('background','rgba(0,175,66,0.8)');
				$(this).css('box-shadow','none');});
		},2000)
	});
	//邮箱修改密码
	$("#change_email_next_button").click(function(){
		if($("#change_email").val()==""){
			$("#change_email").parent().next().html("*请输入邮箱地址")
		};
		var $data;
			$.ajax({
			type:"POST",
			url:"http://49.140.166.99:8080/huiwanspace/api/webServer.php",
			dataType:"json",
			data:{
				action:"forgetPassword",
				agent:"web",
				longinId:$("#change_email").val(),
				verify:$("#change_email_next_button").prev().prev().prev().change(".change_verify").val()
			},
			success:function(data){
				$data=data;
				if(data.status==1){
					$("#change_email_next_button").prev().prev().css('color','green');
					$("#change_email_next_button").prev().prev().html($data.message);					
				}
				else if(data.status!=1){
					$("#change_email_next_button").prev().prev().css('color','#F00');
					$("#change_email_next_button").prev().prev().html("*"+$data.message);
				}
			},
			error:function(jqXHR){
				alert("发生错误："+jqXHR.status);
			}
		});
		$("#change_email_next_button").attr("id","change_email_next_buttonen");	
		$("#change_email_next_buttonen").off('mouseover');
		$("#change_email_next_buttonen").off('mouseout');	
		setTimeout(function(){
			$("#change_email_next_buttonen").attr("id","change_email_next_button");
			$("#change_email_next_button").on('mouseover',function(){
				$(this).css('background','rgba(0,175,66,1)');
				$(this).css('box-shadow','2px 2px 5px #CCC');});
			$("#change_email_next_button").on('mouseout',function(){
				$(this).css('background','rgba(0,175,66,0.8)');
				$(this).css('box-shadow','none');});
		},2000)
	});
	*/
})
