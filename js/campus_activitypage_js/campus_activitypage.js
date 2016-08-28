//添加启动函数
function addLoadEvent(func)
{
	var oldonload=window.onload;
	if(typeof window.onload!='function')
	{
		window.onload=func;
	}
	else
	{
		window.onload=function()
		{
			oldonload();
			func();
		}
	}
}
//获取时间
function checkTime(i)
{
	if(i<10)
	i='0'+i;
	return i;
}
function showTime()
{
	var my_date=new Date();
	var year=my_date.getFullYear();
	var month=my_date.getMonth()+1;
	var date=my_date.getDate();
	var weekday=new Array(7);
	weekday[0]="Sunday";
	weekday[1]="Monday";
	weekday[2]="Tuesday";
	weekday[3]="Wednesday";
	weekday[4]="Thursday";
	weekday[5]="Friday";
	weekday[6]="Saturday";
	var day=my_date.getDay();
	var hours=my_date.getHours();
	var minutes=my_date.getMinutes();
	var seconds=my_date.getSeconds();
	document.getElementById("top_list_time").innerHTML=year+"年"+month+"月"+date+"日"+"&nbsp;&nbsp;"+weekday[day]+"&nbsp;&nbsp;"+	hours+":"+checkTime(minutes)+":"+checkTime(seconds);
	setTimeout(showTime,500);
}
//在某节点之后插入新的节点
function insertAfter(newE,targetElement) {
  var parent = targetElement.parentNode;
  alert(parent.firstChild.firstChild.innerHTML);
  if (parent.lastChild === targetElement) {
    parent.appendChild(newE);
  } else {
    parent.insertBefore(newE,targetElement.nextSibling);
  }
}
//动态扩充DOM树——新建元素ID、内含列数、内容数组、列表编号
function prepareSlideshow(newElement,nums,array,no) {
  if (!document.getElementsByTagName) return false;
  if (!document.getElementById) return false;
  var newElement=document.createElement("div");
  newElement.setAttribute("id",newElement);
  var newul=document.createElement("ul");
  var newli=new Array;
  var newA=new Array;
  for(var i=0;i<nums;i++)
  {
	  newli[i]=document.createElement("li");
	  newA[i]=document.createElement("a");
	  newA[i].setAttribute('class','place');
	  newA[i].setAttribute("href","#");
	  newA[i].innerHTML=array[i];
	  newli[i].appendChild(newA[i]);
	  newul.appendChild(newli[i]);
  }
  newElement.appendChild(newul);
  //insertAfter(newElement,campus_list[no].previousSibling);
  precampus_list[no].parentNode.insertBefore(newElement,precampus_list[no]);
  mouseMove();
}
//头栏自适应长度
function self_adaption(){
	var a=document.getElementsByTagName('body')[0];
	var b=document.getElementById('top_list');
	if(a.clientWidth>1230)
	{b.style.width="100%";}
	else{b.style.width="1230px";}
}
//text框内显示提示内容
function textContent(){
	var a=document.getElementById('username');
	a.value="         账号邮箱/手机号";
	a.style.color="#666";
	a.style.fontSize="12px";
	a.onfocus=function(){
		if(this.value=='         账号邮箱/手机号')
		{
			this.value='';
			this.style.color='#FFF';
			this.style.fontSize="17px";
		} 
	};
	a.onblur=function(){
		if(this.value=='')
		{
			this.value='         账号邮箱/手机号';
			this.style.color='#666';
			this.style.fontSize="12px"
		}
	}
	var b=document.getElementById('password');
	b.value="         请输入密码";
	b.style.color="#666";
	b.style.fontSize="12px";
	b.type='text';
	b.onfocus=function(){
		if(this.value=='         请输入密码')
		{
			this.value='';
			this.style.color='#FFF';
			this.type='password';
			this.style.fontSize="17px";
		}
	};
	b.onblur=function(){
		if(this.value=='')
		{
			this.type='text';
			this.value='         请输入密码';
			this.style.color='#666';
			this.style.fontSize="12px";
		}
	}
}
//新建图片验证码
function verify_Create(parent){
	var newa=document.createElement('img');
	var request=new XMLHttpRequest();
	request.open('GET',"http://115.28.240.191/api/webServer.php?action=getVerify",true);
	request.send();
	request.onreadystatechange=function(){
		if(request.readyState===4){
			if(request.status===200){
				newa.src="http://115.28.240.191/api/webServer.php?action=getVerify";
			}
		}
	}
	newa.setAttribute('id','verify_img');
	parent.appendChild(newa);
}
//更新图片验证码
function verify_F5(){
    var verify=document.getElementById('verify');
    var parent=verify.parentNode;
	parent.removeChild(verify_img);
	verify_Create(parent);
}
//正则表达式检查手机号码
function checkPhone(phone){
	if(!(/^1[3|4|5|7|8]\d{9}$/.test(phone))){
		return 0;
	}else{
		return 1;
	}
}
//从url上获取信息的函数
function GetRequest() {
 //var url = "?status=1&sName=%e5%90%89%e6%9e%97%e5%a4%a7%e5%ad%a6&sId=509&cId=1"
 var url = location.search; //获取url中"?"符后的字串
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

$(document).ready(function(){
	var $pid=7;
	var $pname="吉林";
	var $sid;
	var $sname;
	var $firstcid;
	var $cid=[];
	var $url="http://115.28.240.191/";
	//从url上获取信息
	$(function(){
		var Request = new Object();
		Request = GetRequest();
		$sid=Request['sId'];
		$sname=decodeURI(Request['sName']);
		$firstcid=Request['cId'];
	});
	$.windowbox={
		//获取各校区内场所回调的函数
		get_place:function(i){
			var $data=[];
			$.ajax({
				type:"POST",
				url:$url+"api/webServer.php",
				dataType:"json",
				data:{
					action:"getPlaceByCid",
					cId:$cid[i]
					},
				success:function(data){
					if(data.status==1){
						$data=data.datas;
							for(var $c=0;$c<$data.length;$c++){
								$(".position_list_list:eq("+i+")")
								.append($("<a  href='javascript:;' id="+$data[$c].id+" class='place'>"+$data[$c].pName+"<div class='line'></div></a>"));
							}
					}
					else{alert(data.message);
					}
				},
				error:function(jqXHR){
					alert("获取活动发生错误："+jqXHR.status);
				}
			});
		},
		//获取各校区内活动的回调函数
		get_campus_action:function(i){
			var $data=[];
			$.ajax({
				type:"POST",
				url:$url+"api/webServer.php",
				dataType:"json",
				data:{
					action:"getListSituation",
					cId:$cid[i]
					},
				success:function(data){
					if(data.status==1){
						$data=data.datas;
							for(var $c=0;$c<$data.length;$c++){
								$("dl.middle")
								.append($("<div class='middle_activity' id="+$data[$c].id+"><img src="+$data[$c].face+" class='middle_img'/><dt class='middle'>"+$data[$c].sTitle+"</dt><div class='middle_pname'>活动发起人:"+$data[$c].username+"</div><div class='middle_ptime'>发布时间:"+new Date(parseInt($data[$c].sPubtime) * 1000).toLocaleString()+"</div><div class='middle_position'>活动地点:"+$data[$c].sPosition+"</div><div class='middle_maxnumber'>限制人数:"+$data[$c].sNumber+"</div><div class='middle_number'>当前人数:"+$data[$c].sCurrentNumber+"</div><div class='middle_stime'>报名起止时间:"+new Date(parseInt($data[$c].sSignupBtime) * 1000).toLocaleString()+"—"+new Date(parseInt($data[$c].sSignupEtime) * 1000).toLocaleString()+"</div><div class='middle_appreciate'><i class='iconfont' style='color:#00AF42;'>&#xe63d;</i>"+$data[$c].praise+"likes</div><div class='middle_like'><i class='iconfont' style='color:#00AF42; font-size:14px'>&#xe608;</i>"+$data[$c].collection+"collects</div><div class='middle_talk'><i class='iconfont' style='color:#00AF42;'>&#xe601;</i>"+$data[$c].comment+"talks</div><div class='middle_transmit'><i class='iconfont' style='color:#00AF42;'>&#xe607;</i>"+$data[$c].transmit+"shares</div><dd class='middle'>"+$data[$c].sDesc+"</dd></div>"));
							}
					}
					else{alert(data.message);
					}
				},
				error:function(jqXHR){
					alert("获取活动发生错误："+jqXHR.status);
				}
			});
		},
		//获取各校区内快捷活动的回调函数
		get_campus_fastaction:function(i){
			var $data=[];
			$.ajax({
				type:"POST",
				url:$url+"api/webServer.php",
				dataType:"json",
				data:{
					action:"getActiveByCid",
					cId:$cid[i]
					},
				success:function(data){
					if(data.status==1){
						$data=data.datas;
							for(var $c=0;$c<$data.length;$c++){
								$("dl.right")
								.append($("<div class='right_activity' id="+$data[$c].id+"><dt class='right'>"+$data[$c].aName+"</dt><dd class='right'>"+$data[$c].aDesc+"</dd></div>"));
							}
					}
					else{alert(data.message);
					}
				},
				error:function(jqXHR){
					alert("获取活动发生错误："+jqXHR.status);
				}
			});
		},
		//获取各场所内活动的回调函数
		get_place_action:function(i){
			var $data=[];
			$.ajax({
				type:"POST",
				url:$url+"api/webServer.php",
				dataType:"json",
				data:{
					action:"getActiveByPid",
					pId:i
					},
				success:function(data){
					if(data.status==1){
						$data=data.datas;
							for(var $c=0;$c<$data.length;$c++){
								$("dl.middle")
								.append($("<div class='middle_activity'><dt class='middle'>"+$data[$c].aName+"</dt> <dd class='middle'>"+$data[$c].aDesc+"</dd></div>"));
							}
					}
					else{alert(data.message);
					}
				},
				error:function(jqXHR){
					alert("获取活动发生错误："+jqXHR.status);
				}
			});
		},
		firstCid: function(i){
					$(".position_list_list:eq("+i+")").css("display","block");
					$(".position_list:eq("+i+")").children('.stand').css('background','rgba(0,175,66,1)');
					$(".position_list:eq("+i+")").children('.stand').fadeIn(10);
					$(".position_list:eq("+i+")").children('i').css('color','#00AF42');
					$(".position_list:eq("+i+")").css('background','rgba(255,255,255,1)');
					$(".position_list:eq("+i+")").off('mouseover');
					$(".position_list:eq("+i+")").off('mouseout');	
		}
	}

	//初始化载入数据
	$(function(){
		$("#campus_name").html($sname);
		var $data=[];
		var $h;
		$.ajax({
			type:"POST",
			url:$url+"api/webServer.php",
			dataType:"json",
			data:{
				action:"getCampusBySid",
				sId:$sid
				},
			success:function(data){
				if(data.status==1){
					$cid=[];
					$data=data.datas;
						$(".position_list").css("display","none");
						$(".position_list_list").css("display","none");
					for(var $i=0;$i<$data.length;$i++){
						$(".position_list:eq("+$i+")").append($data[$i].cName);
						$(".position_list:eq("+$i+")").css("display","block");
						$cid.push($data[$i].id);
						$h=$i;
					}
				}
				else{alert(data.message);
				}
			},
			error:function(jqXHR){
				alert("获取校区发生错误："+jqXHR.status);
			}
		});
		var int=setInterval(function(){
			if($h=$data.length&&$data.length!=0){
				if($cid.length>0){$.windowbox.get_place(0);if($cid[0]==$firstcid){$.windowbox.firstCid(0);$.windowbox.get_campus_action(0);
				$.windowbox.get_campus_fastaction(0);}}
				if($cid.length>1){$.windowbox.get_place(1);if($cid[1]==$firstcid){$.windowbox.firstCid(1);$.windowbox.get_campus_action(1);
				$.windowbox.get_campus_fastaction(1);}}
				if($cid.length>2){$.windowbox.get_place(2);if($cid[2]==$firstcid){$.windowbox.firstCid(2);$.windowbox.get_campus_action(2);
				$.windowbox.get_campus_fastaction(2);}}
				if($cid.length>3){$.windowbox.get_place(3);if($cid[3]==$firstcid){$.windowbox.firstCid(3);$.windowbox.get_campus_action(3);
				$.windowbox.get_campus_fastaction(3);}}
				if($cid.length>4){$.windowbox.get_place(4);if($cid[4]==$firstcid){$.windowbox.firstCid(4);$.windowbox.get_campus_action(4);
				$.windowbox.get_campus_fastaction(4);}}
				if($cid.length>5){$.windowbox.get_place(5);if($cid[5]==$firstcid){$.windowbox.firstCid(5);$.windowbox.get_campus_action(5);
				$.windowbox.get_campus_fastaction(5);}}
				if($cid.length>6){$.windowbox.get_place(6);if($cid[6]==$firstcid){$.windowbox.firstCid(6);$.windowbox.get_campus_action(6);
				$.windowbox.get_campus_fastaction(6);}}
				clearInterval(int);
			}
		},50);
	});
	//左侧校区title的css
	$(".position_list").mouseover(function(){
		$(this).css('background','rgba(255,255,255,1)');
		$(this).children('.stand').fadeIn(10);
		
	});
	$(".position_list").mouseout(function(){
		$(this).css('background','rgba(255,255,255,0.4)');
		$(this).children('.stand').fadeOut(10);
	});
	//左侧校区title的click事件
	$(".position_list").click(function(){
		if($(this).next().is(':hidden')){
			$(".position_list").children('.stand').css('background','rgba(57,217,115,1)');
			$(".position_list").children('.stand').fadeOut(10);
			$(".position_list").children('i').css('color','#666');
			$(".position_list").on('mouseover',function(){
				$(this).css('background','rgba(255,255,255,1)');
				$(this).children('.stand').fadeIn(10);
			});
			$(".position_list").on('mouseout',function(){
				$(this).css('background','rgba(255,255,255,0.4)');
				$(this).children('.stand').fadeOut(10);
			});
			$(".position_list").next().slideUp(300);
			$(".position_list").css('background','rgba(255,255,255,0.4)');
			$(this).off('mouseover');
			$(this).off('mouseout');
			$(this).css('background','rgba(255,255,255,1)');
			$(this).children('.stand').css('background','rgba(0,175,66,1)');
			$(this).children('.stand').fadeIn(10);
			$(this).children('i').css('color','#00AF42');
			$(this).next().slideDown(300);
			$("dl.middle").empty();
			$.windowbox.get_campus_action($(this).attr('id'));
			$("dl.right").empty();
			$.windowbox.get_campus_fastaction($(this).attr('id'));
		}
		else{
			$(this).on('mouseover',function(){
				$(this).css('background','rgba(255,255,255,1)');
			});
			$(this).on('mouseout',function(){
				$(this).css('background','rgba(255,255,255,0.5)');
			});
			$(this).next().slideUp(300);
		}
	});
	//左侧场所content的css
	$(".position_list_list").on("mouseover","a", function() {
		$(this).children('.line').stop().slideDown(10);
 	});
	$(".position_list_list").on("mouseout","a", function() {
		$(this).children('.line').stop().slideUp(10);
 	});
	//左侧场所content的click事件
	$(".position_list_list").on("click","a", function() {
		$("dl.middle").empty();
		$.windowbox.get_place_action($(this).attr('id'));
 	});
	//回顶按钮的自显示与隐藏
	$(function(){
		$(window).scroll(function(){
		if($(window).scrollTop()>200){
			$("#back_to_top").fadeIn(50);
		}
		else{$("#back_to_top").fadeOut(50);}
		});
	});
	//登录对话框弹出
    $("#buttonin").click(function(){
		$("#login_dialogfloor").fadeIn(250);
		textContent();
		verify_F5();
	});
	//Esc退出登录对话框
	$(document).keydown(function(event){ 
    	if(event.which==27){
			$("#login_dialogfloor").fadeOut(250);
			$("#username").val("");
			$("#password").val("");
			$("#verify").val("");
			$("#error_information").html("");}
 	});
	//click退出登录对话框
	$("#login_close").click(function(){
		$("#login_dialogfloor").fadeOut(250);
		$("#username").val("");
		$("#password").val("");
		$("#verify").val("");
		$("#error_information").html("");	
	});
	//检查账户是否存在
	$("#username").blur(function(){
		if(checkPhone($("#username").val())==1){
			$.ajax({
				url:$url+"api/webServer.php",
				type: "POST",
				dataType:"json",
       			data: {
					action:"phoneExist",
					phone:function(){return $("#username").val()}
				},
				success:function(data){
					if(data==true){
						$("#username").parent().next().html("&nbsp;");	
					}
					else if(data==false){
						$("#username").parent().next().html("*该账户不存在,请注册");	
					}
					else if(data==null){
						$("#username").parent().next().html("*账号格式错误");	
					}
				},	
				error:function(jqXHR){
					alert("检查账户发生错误："+jqXHR.status);
				}			
			});	
       	}
		else if(checkPhone($("#username").val())==0&&$("#username").val()!=""){
			$.ajax({
				url:$url+"api/webServer.php",
				type: "POST",
				dataType:"json",
       			data: {
					action:"emailExist",
					email:function(){return $("#username").val()}
				},
				success:function(data){
					if(data==true){
						$("#username").parent().next().html("&nbsp;");	
					}
					else if(data==false){
						$("#username").parent().next().html("*该账户不存在,请注册");	
					}
					else if(data==null){
						$("#username").parent().next().html("*账号格式错误");	
					}
				},	
				error:function(jqXHR){
					alert("检查账户发生错误："+jqXHR.status);
				}			
			});	
		}
	});
	//表单验证
	$("#login_form").validate({
		errorPlacement: function(error,element){
			element.parent().next().html(error);
		},
		rules:{
			username:{
				required:true,
			},
			password:{
				required:true,
				minlength:6,
				maxlength:16
			}
		},
		messages:{
			username:{
				required:"*必须填写账户名",
			},
			password:{
				required:"*必须填写密码",
				minlength:"*密码不能少于6字节",
				maxlength:"*密码不能多于16字节"
			}
		}
	});
	//换一张图片验证码
	$("#change_img").click(function(){
		verify_F5();
	});
	//登录
	$("#login_button").click(function login_button_click(){
		$.ajax({
			type:"POST",
			url:$url+"api/webServer.php",
			dataType:"json",
			data:{
				action:"userLogin",
				loginId:$("#username").val(),
				password:$("#password").val()
			},
			success:function(data){
				if(data.status==1){
					$("#error_information").html("登录成功");	
					setTimeout(function(){$("#login_close").trigger("click");},1000);
				}
				else{
					$("#error_information").html("*"+data.message);	
				}
			},
			error:function(jqXHR){
				alert("登录发生错误："+jqXHR.status);
			}
		});
		$(this).attr("id","login_buttonen");
		setTimeout(function(){$("#login_buttonen").attr("id","login_button");},2000)
	});
	//Enter登录
	$(document).keydown(function(event){ 
    	if(event.which==13&&(!$("#login_dialogfloor").is(':hidden'))){
  				$("#login_button").trigger("click");}
 	});
});
addLoadEvent(showTime);
addLoadEvent(self_adaption);