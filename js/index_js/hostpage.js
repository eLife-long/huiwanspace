// JavaScript Document
//叠加事件
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
//时间显示
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
//主图片移动
function moveElement(elementID,final_x,final_y,interval) 
{
  if (!document.getElementById) return false;
  if (!document.getElementById(elementID)) return false;
  var elem = document.getElementById(elementID);
  if (elem.movement) {
    clearTimeout(elem.movement);
  }
  if (!elem.style.left) {
    elem.style.left = "0px";
  }
  if (!elem.style.top) {
    elem.style.top = "0px";
  }
  var xpos = parseInt(elem.style.left);
  var ypos = parseInt(elem.style.top);
  if (xpos == final_x && ypos == final_y) {
    return true;
  }
  if (xpos < final_x) {
    var dist = Math.ceil((final_x - xpos)/10);
    xpos = xpos + dist;
  }
  if (xpos > final_x) {
    var dist = Math.ceil((xpos - final_x)/10);
    xpos = xpos - dist;
  }
  if (ypos < final_y) {
    var dist = Math.ceil((final_y - ypos)/10);
    ypos = ypos + dist;
  }
  if (ypos > final_y) {
    var dist = Math.ceil((ypos - final_y)/10);
    ypos = ypos - dist;
  }
  elem.style.left = xpos + "px";
  elem.style.top = ypos + "px";
  var repeat = "moveElement('"+elementID+"',"+final_x+","+final_y+","+interval+")";
  elem.movement = setTimeout(repeat,interval);
}
function photoMove()
{
	var tpsl=document.getElementsByClassName('top_photo_select_list');
	var tpsn=document.getElementById('top_photo_select_name');
	var tpl=document.getElementById('top_photo_left');
	var tpr=document.getElementById('top_photo_right');
	var photo =document.getElementById('photo');
	var a=0;var int;
	tpsl[0].className=tpsl[0].className+' top__photo_select_list_s';
	tpsn.innerHTML='标题1';
	function beginInterval(){
		int=setInterval(function(){
			a++;
			if(a==5){a=0;}
			if(a==0){
				tpsl[4].className="top_photo_select_list";}
			else{
				tpsl[a-1].className="top_photo_select_list";}
			tpsl[a].className=tpsl[a].className+' top__photo_select_list_s';
			tpsn.innerHTML='标题'+(a+1);
			moveElement('photo',(-a)*1199,0,10);
		}
		,4500);
	}
	beginInterval();
	photo.onmouseover=function(){
		clearInterval(int);
		document.getElementById('top_photo_right').style.opacity="1";
		document.getElementById('top_photo_left').style.opacity="1";
	}
	photo.onmouseout=function(){
		beginInterval();
		document.getElementById('top_photo_right').style.opacity="0.05";	
		document.getElementById('top_photo_left').style.opacity="0.05";	
	}
	document.getElementById('top_photo_right').onmouseover=function(){
		document.getElementById('top_photo_right').style.opacity="1";
	}
	document.getElementById('top_photo_right').onmouseout=function(){
		document.getElementById('top_photo_right').style.opacity="0.05";
	}
	document.getElementById('top_photo_left').onmouseover=function(){
		document.getElementById('top_photo_left').style.opacity="1";
	}
	document.getElementById('top_photo_left').onmouseout=function(){
		document.getElementById('top_photo_left').style.opacity="0.05";
	}
	tpl.onclick=function()
	{
		a--;
		if(a==-1){a=4;}
		if(a==4){
			tpsl[0].className="top_photo_select_list";}
		else{
			tpsl[a+1].className="top_photo_select_list";}
		tpsl[a].className=tpsl[a].className+' top__photo_select_list_s';
		moveElement('photo',(-a)*1199,0,10);
		tpsn.innerHTML='标题'+(a+1);
	}
	tpr.onclick=function()
	{
		a++;
		if(a==5){a=0;}
		if(a==0){
			tpsl[4].className="top_photo_select_list";}
		else{
			tpsl[a-1].className="top_photo_select_list";}
		tpsl[a].className=tpsl[a].className+' top__photo_select_list_s';
		tpsn.innerHTML='标题'+(a+1);
		moveElement('photo',(-a)*1199,0,10);
	}
	tpsl[0].onclick=function()
	{
		for(var i=0;i<5;i++){
			tpsl[i].className="top_photo_select_list";
		}
		tpsl[0].className=tpsl[0].className+' top__photo_select_list_s';
		moveElement('photo',(0)*1199,0,10);
		tpsn.innerHTML='标题1';
		a=0;
	}
	tpsl[1].onclick=function()
	{
		for(var i=0;i<5;i++){
			tpsl[i].className="top_photo_select_list";
		}
		tpsl[1].className=tpsl[1].className+' top__photo_select_list_s';
		moveElement('photo',(-1)*1199,0,10);
		tpsn.innerHTML='标题2';
		a=1;
	}
	tpsl[2].onclick=function()
	{
		for(var i=0;i<5;i++){
			tpsl[i].className="top_photo_select_list";
		}
		tpsl[2].className=tpsl[2].className+' top__photo_select_list_s';
		moveElement('photo',(-2)*1199,0,10);
		tpsn.innerHTML='标题3';
		a=2;
	}
	tpsl[3].onclick=function()
	{
		for(var i=0;i<5;i++){
			tpsl[i].className="top_photo_select_list";
		}
		tpsl[3].className=tpsl[3].className+' top__photo_select_list_s';
		moveElement('photo',(-3)*1199,0,10);
		tpsn.innerHTML='标题4';
		a=3;
	}
	tpsl[4].onclick=function()
	{
		for(var i=0;i<5;i++){
			tpsl[i].className="top_photo_select_list";
		}
		tpsl[4].className=tpsl[4].className+' top__photo_select_list_s';
		moveElement('photo',(-4)*1199,0,10);
		tpsn.innerHTML='标题5';
		a=4;
	}
	/*tpsl[5].onclick=function()
	{
		for(var i=0;i<6;i++){
			tpsl[i].className="top_photo_select_list";
		}
		tpsl[5].className=tpsl[5].className+' top__photo_select_list_s';
		moveElement('photo',(-5)*1199,0,10);
		a=5;
	}*/
}
//头栏自适应宽度
function self_adaption(){
	var a=document.getElementsByTagName('body')[0];
	var b=document.getElementById('top_list');
	if(a.clientWidth>1200)
	{b.style.width="100%";}
	else{b.style.width="1200px";}
}

//text框内显示提示内容
function textContent(){
	var a=document.getElementById('username');
	a.value="         账号邮箱/手机号";
	a.style.color="#999";
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
			this.style.color='#999';
			this.style.fontSize="12px"
		}
	}
	var b=document.getElementById('password');
	b.value="         请输入密码";
	b.style.color="#999";
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
			this.style.color='#999';
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
//刷新图片验证码
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


$(document).ready(function(){
	var $pid=7;
	var $pname="吉林";
	var $sid=509;
	var $sname="吉林大学";
	var $url="http://115.28.240.191/";
	var $cid=[];
	var $data1=[];
	var $data2=[];
	var $order=new Array('sPubtime','sPubtime','sPubtime','sPubtime','sPubtime','sPubtime','sPubtime');
	var $isover=0;
	$.windowbox={
		//右侧不感兴趣换一换
		change_right:function(i){
			var $data=[];
			$.ajax({
				type:"POST",
				url:$url+"api/webServer.php",
				dataType:"json",
				data:{
					action:"getListSituation",
					cId:$cid[i-1]
					},
				success:function(data){
					if(data.status==1){
						$data=data.datas;
						for(var $b=1;$b<4;$b++){
							$("#activity0"+i+"_right_title_"+$b).html("&nbsp;");
							$("#activity0"+i+"_right_content_"+$b).html("&nbsp;");}
						for(var $c=1;$c<4;$c++){
							var $r=Math.round(Math.random()*($data.length-1));
							$("#activity0"+i+"_right_title_"+$c).html($data[$r].sTitle);
							$("#activity0"+i+"_right_content_"+$c).html($data[$r].sDesc);}
					}
					else{alert(data.message);
					}
				},
				error:function(jqXHR){
					alert("获取活动发生错误："+jqXHR.status);
				}
			});
		},
		//获取校区活动列表
		getListCampusActive:function(i,j,x,y){
			$.ajax({
				type:"POST",
				url:$url+"api/webServer.php",
				dataType:"json",
				data:{
					action:"getListSituation",
					cId:$cid[i-1],
					uId:117,
					lastTime:j,
					order:y
				},
				success:function(data){
					if(data.status==1){
						$data=data.datas;
						for(var $c=x;$c<$data.length+x;$c++){
							$("#activity0"+i+"_middle__main_list").append($("<img id='activity0"+i+"_middle__main_list_img_"+$c+"' class='middle__main_list_img' src='img/eps7.jpg'/><dt id='activity0"+i+"_middle__main_list_top_"+$c+"' class='middle__main_list_top'><div id='activity0"+i+"_user_dialog_"+$c+"' class='user_dialog'><div  id='activity0"+i+"_user_dialog_user_follow_"+$c+"' class='user_dialog_user_follow'>关注</div><img id='activity0"+i+"_user_dialog_img_"+$c+"' class='user_dialog_img' src='css/index_css/img/user_dialog_triangle.png'/><div class='user_dialog_user_line'></div><img id='activity0"+i+"_user_dialog_user_img_"+$c+"' class='user_dialog_user_img'/><div  id='activity0"+i+"_user_dialog_user_name_"+$c+"' class='user_dialog_user_name'></div><div  id='activity0"+i+"_user_dialog_user_sex_"+$c+"' class='user_dialog_user_sex'></div><div  id='activity0"+i+"_user_dialog_user_about_"+$c+"' class='user_dialog_user_about'></div><div  id='activity0"+i+"_user_dialog_user_level_"+$c+"' class='user_dialog_user_level'></div><div  id='activity0"+i+"_user_dialog_user_follower_"+$c+"' class='user_dialog_user_follower'></div><div  id='activity0"+i+"_user_dialog_user_attention_"+$c+"' class='user_dialog_user_attention'></div><div  id='activity0"+i+"_user_dialog_user_school_"+$c+"' class='user_dialog_user_school'></div></div><div id='activity0"+i+"_middle__main_list_top_title_"+$c+"' class='middle__main_list_top_title'><a href='javascript:;' id='activity0"+i+"_middle__main_list_top_title_text_"+$c+"' class='middle__main_list_top_title_text'>hello01</a></div><div id='activity0"+i+"_middle__main_list_top_time_"+$c+"' class='middle__main_list_top_time'>一天前</div></dt><dd id='activity0"+i+"_middle__main_list_content_"+$c+"' class='middle__main_list_content'><div id='activity0"+i+"_middle__main_list_content_text_"+$c+"' class='middle__main_list_content_text'></div><div id='activity0"+i+"_middle__main_list_content_name_"+$c+"'><a href='javascript:;' id='activity0"+i+"_middle__main_list_content_name_button_"+$c+"' class='middle__main_list_content_name_button'>huiwan</a></div><div id='activity0"+i+"_middle__main_list_content_position_"+$c+"' class='middle__main_list_content_position'>活动地点:"+$data[$c-x].sPosition+"</div><div id='activity0"+i+"_middle__main_list_content_begintime_"+$c+"' class='middle__main_list_content_begintime'>活动时间:"+new Date(parseInt($data[$c-x].sBtime) * 1000).toLocaleString()+"</div><div id='activity0"+i+"_middle__main_list_content_maxnumber_"+$c+"' class='middle__main_list_content_maxnumber'>活动容量:"+$data[$c-x].sNumber+"</div><div id='activity0"+i+"_middle__main_list_content_number_"+$c+"' class='middle__main_list_content_number'>参与人数:"+$data[$c-x].sCurrentNumber+"</div><div id='activity0"+i+"_middle__main_list_content_more_"+$c+"'><a href='javascript:;' id='activity0"+i+"_middle__main_list_content_more_button_"+$c+"'class='middle__main_list_content_more_button' ><i class='iconfont' id='i1' style='color:#00AF42; font-size:14px'>&#xe60b;</i></a></div><div id='activity0"+i+"_middle__main_list_content_like_"+$c+"'><a href='javascript:;' id='activity0"+i+"_middle__main_list_content_like_button_"+$c+"'class='middle__main_list_content_like_button' title='收藏'><i class='iconfont' id='i1' style='color:#FFF; font-size:14px'>&#xe608;</i>收藏</a></div><div class='more_dialog'><div id='activity0"+i+"_middle__main_list_content_appreciate_"+$c+"'><a href='javascript:;' id='activity0"+i+"_middle__main_list_content_appreciate_button_"+$c+"' class='middle__main_list_content_appreciate_button' title='赞'><i class='iconfont' id='i1' style='color:#FFF;'>&#xe63d;</i></a></div><div id='activity0"+i+"_middle__main_list_content_appreciate_number_"+$c+"' class='middle__main_list_content_appreciate_number'>0likes</div><div id='activity0"+i+"_middle__main_list_content_comment_"+$c+"'><a href='javascript:;' id='activity0"+i+"_middle__main_list_content_comment_button_"+$c+"' class='middle__main_list_content_comment_button' title='讨论'><i class='iconfont' id='i1' style='color:#FFF;'>&#xe601;</i></a></div><div id='activity0"+i+"_middle__main_list_content_comment_number_"+$c+"' class='middle__main_list_content_comment_number'>0talks</div><div id='activity0"+i+"_middle__main_list_content_share_"+$c+"'><a href='javascript:;' id='activity0"+i+"_middle__main_list_content_share_button_"+$c+"' class='middle__main_list_content_share_button' title='分享'><i class='iconfont' id='i1' style='color:#FFF;'>&#xe607;</i></a></div><div id='activity0"+i+"_middle__main_list_content_share_number_"+$c+"' class='middle__main_list_content_share_number'>0shares</div><span id='activity0"+i+"_lasttime_"+$c+"' class='lasttime'></span><span id='activity0"+i+"_lastnumber_"+$c+"' class='lastnumber'></span></div></dd>"));
							$("#activity0"+i+"_middle__main_list_img_"+$c).css('display','inline-block'); 
							$("#activity0"+i+"_middle__main_list_top_"+$c).css('display','inline-block');
							$("#activity0"+i+"_middle__main_list_content_"+$c).css('display','inline-block');
							$("#activity0"+i+"_middle__main_list_img_"+$c).attr('src',$data[$c-x].face);
							$("#activity0"+i+"_middle__main_list_top_title_text_"+$c).text($data[$c-x].sTitle);
							$("#activity0"+i+"_middle__main_list_img_"+$c).attr('title',$data[$c-x].uId);
							$("#activity0"+i+"_lasttime_"+$c).html($data[$c-x].sPubtime);
							$("#activity0"+i+"_lastnumber_"+$c).html($c);
							$("#activity0"+i+"_middle__main_list_top_time_"+$c).text(new Date(parseInt($data[$c-x].sPubtime) * 1000).toLocaleString());
							$("#activity0"+i+"_middle__main_list_content_name_button_"+$c).text($data[$c-x].username);
							$("#activity0"+i+"_middle__main_list_content_name_button_"+$c).attr('title',$data[$c-x].uId);
							if($data[$c-x].sex=='保密'){$("#activity0"+i+"_middle__main_list_content_sex_"+$c).html("<i class='iconfont' id='i1' style='color:#F50; font-size:14px'>&#xe60e;</i>");}else if($data[$c-x].sex=='男'){$("#activity0"+i+"_middle__main_list_content_sex_"+$c).html("<i class='iconfont' id='i1' style='color:#03F; font-size:14px'>&#xe60d;</i>");}else if($data[$c-x].sex=='女'){$("#activity0"+i+"_middle__main_list_content_sex_"+$c).html("<i class='iconfont' id='i1' style='color:#F30; font-size:14px'>&#xe60c;</i>");}
							if($data[$c-x].isPraise==x){$("#activity0"+i+"_middle__main_list_content_appreciate_button_"+$c).empty();
				$("#activity0"+i+"_middle__main_list_content_appreciate_button_"+$c).append("<i class='iconfont' id='i2' style='color:#00AF42;'>&#xe63e;</i>");}
							if($data[$c-x].isCollect==x){$("#activity0"+i+"_middle__main_list_content_like_button_"+$c).empty();
				$("#activity0"+i+"_middle__main_list_content_like_button_"+$c).append("<i class='iconfont' id='i2' style='color:#F00; font-size:14px'>&#xe600;</i>收藏");}
							$("#activity0"+i+"_middle__main_list_content_appreciate_number_"+$c).text($data[$c-x].praise+'likes');
							$("#activity0"+i+"_middle__main_list_content_share_number_"+$c).text($data[$c-x].transmit+'shares');
							$("#activity0"+i+"_middle__main_list_content_comment_number_"+$c).text($data[$c-x].comment+'talks');
							$("#activity0"+i+"_middle__main_list_content_text_"+$c).text($data[$c-x].sDesc);}
						if(x==1){
							for(var $c=1;$c<4;$c++){
								var $r=Math.round(Math.random()*($data.length-1));
								$("#activity0"+i+"_right_title_"+$c).text($data[$r].sTitle);
								$("#activity0"+i+"_right_content_"+$c).text($data[$r].sDesc);}
						};
					}
					else{alert(data.message);
					}
				},
				error:function(jqXHR){
					alert("获取第"+i+"校区活动发生错误："+jqXHR.status);
				}
			});
		},
		//获取发布者信息
		getPuserinformation:function(i,j,x){
			if($("#activity0"+i+"_user_dialog_user_name_"+j).html()==''){
			var $data=[];
			$.ajax({
				type:"POST",
				url:$url+"api/webServer.php",
				dataType:"json",
				data:{
					action:"getUserInfo",
					uId:x
					},
				success:function(data){
					if(data.status==1){
						$data=data.datas;
						$("#activity0"+i+"_user_dialog_img_"+j).next().attr('id',$data.id);
						$("#activity0"+i+"_user_dialog_user_img_"+j).attr('src',$data.face);
						$("#activity0"+i+"_user_dialog_user_name_"+j).html($data.username);
						$("#activity0"+i+"_user_dialog_user_sex_"+j).html($data.sex);
						$("#activity0"+i+"_user_dialog_user_about_"+j).html("个人简介:"+$data.about);
						$("#activity0"+i+"_user_dialog_user_level_"+j).html("等级:"+$data.level);
						$("#activity0"+i+"_user_dialog_user_follower_"+j).html("粉丝:"+$data.follower);
						$("#activity0"+i+"_user_dialog_user_attention_"+j).html("关注:"+$data.attention);
						$("#activity0"+i+"_user_dialog_user_school_"+j).html("所在:"+$data.sName);
						}
						//else{alert(data.message);}
				},
				error:function(jqXHR){
					alert("获取用户信息发生错误："+jqXHR.status);
				}
			});
			}
		},
	};
	//jq.ajax获取省份列表
	$(function getCityList(){
		var $data=[];
		$.ajax({
			type:"POST",
			url:$url+"api/webServer.php",
			dataType:"json",
			data:{
				action:"getListProvince"
				},
			success:function(data){
				if(data.status==1){
					$data=data.datas;
					for(var $i=0;$i<$data.length;$i++){
						$("#city_list").append($("<a  href='javascript:;' id="+$data[$i].id+" class='city'>"+$data[$i].pName+"</a>"));
						}
				}
				else{alert(data.message);
				}
			},
			error:function(jqXHR){
				alert("获取城市发生错误："+jqXHR.status);
			}
		});
	});
	//jq.ajax获取学校列表
	$(function getCampusList(){
		var $data=[];
		var $h;
		$.ajax({
			type:"POST",
			url:$url+"api/webServer.php",
			dataType:"json",
			data:{
				action:"getSchoolByPid",
				pId:$pid
				},
			success:function(data){
				if(data.status==1){
					$data=data.datas;
					for(var $i=0;$i<$data.length;$i++){
						$("#campus_list").append($("<a  href='javascript:;' id="+$data[$i].id+" class='campus'>"+$data[$i].sName+"</a>"));
						$h=$i;
						}
				}
				else{alert(data.message);
				}
			},
			error:function(jqXHR){
				alert("获取学校发生错误："+jqXHR.status);
			}
		});
		var int=setInterval(function(){
		if($h=$data.length&&$data.length!=0){
			$("#campus_list a#509").trigger("click");
			clearInterval(int);
			}
		},50);
	});
	//jq.ajax获取用户信息
	$(function getUserInfo(){
		var $data=[];
		$.ajax({
			type:"POST",
			url:$url+"api/webServer.php",
			dataType:"json",
			data:{
				action:"getUserInfo",
				uId:117
				},
			success:function(data){
				if(data.status==1){
					$data=data.datas;
					$("#qwe_top_img").attr('src',$data.face);
					$("#qwe_top_name").html($data.username);
					$("#qwe_top_attention_dialog").html("<a href='javascript:;' id='qwe_top_attention'>关注:</a>"+$data.attention);
					$("#qwe_top_follower_dialog").html("<a href='javascript:;' id='qwe_top_follower'>粉丝:</a>"+$data.follower);
				}
				else{alert(data.message);
				}
			},
			error:function(jqXHR){
				alert("获取用户信息发生错误："+jqXHR.status);
			}
		});
	});
	//jq.ajax获取用户参加的活动信息
	$(function getUserJoin(){
		var $data=[];
		$.ajax({
			type:"POST",
			url:$url+"api/webServer.php",
			dataType:"json",
			data:{
				action:"getUserJoin",
				uId:117
				},
			success:function(data){
				if(data.status==1){
					$data=data.datas;
					for(var $i=0;$i<$data.length;$i++){
						$("#zxc_1_left_ul").append($("<a href='javascript:;' id="+$data[$i].id+" class='zxc_1_left_title'>"+$data[$i].sDesc+"</a><div  class='zxc_1_left_time'><i class='iconfont' >&#xe634;</i>&nbsp;"+new Date(parseInt($data[$i].sBtime) * 1000).toLocaleString()+"<br/><i class='iconfont' >&#xe615;</i>&nbsp;"+$data[$i].sCurrentNumber+"</div>"));
					}
				}
				else{alert(data.message);
				}
			},
			error:function(jqXHR){
				alert("获取用户参加活动信息发生错误："+jqXHR.status);
			}
		});
	});
	//jq.ajax获取用户发布的活动信息
	$(function getListSituation(){
		var $data=[];
		$.ajax({
			type:"POST",
			url:$url+"api/webServer.php",
			dataType:"json",
			data:{
				action:"getListSituation",
				uId:117
				},
			success:function(data){
				if(data.status==1){
					$data=data.datas;
					for(var $i=0;$i<$data.length;$i++){
						$("#zxc_2_left_ul").append($("<a href='javascript:;' id="+$data[$i].id+" class='zxc_2_left_title'>"+$data[$i].sDesc+"</a><div  class='zxc_2_left_time'><i class='iconfont' >&#xe634;</i>&nbsp;"+new Date(parseInt($data[$i].sBtime) * 1000).toLocaleString()+"<br/><i class='iconfont' >&#xe615;</i>&nbsp;"+$data[$i].sCurrentNumber+"</div>"));
					}
				}
				else{alert(data.message);
				}
			},
			error:function(jqXHR){
				alert("获取用户发布活动信息发生错误："+jqXHR.status);
			}
		});
	});
	//jq.ajax获取用户收藏的活动信息
	$(function getUserCollection(){
		var $data=[];
		$.ajax({
			type:"POST",
			url:$url+"api/webServer.php",
			dataType:"json",
			data:{
				action:"getUserCollection",
				uId:117
				},
			success:function(data){
				if(data.status==1){
					$data=data.datas;
					for(var $i=0;$i<$data.length;$i++){
						$("#zxc_3_left_ul").append($("<a href='javascript:;' id="+$data[$i].id+" class='zxc_3_left_title'>"+$data[$i].sDesc+"</a><div  class='zxc_3_left_time'><i class='iconfont' >&#xe634;</i>&nbsp;"+new Date(parseInt($data[$i].sBtime) * 1000).toLocaleString()+"<br/><i class='iconfont' >&#xe615;</i>&nbsp;"+$data[$i].sCurrentNumber+"</div>"));
					}
				}
				else{alert(data.message);
				}
			},
			error:function(jqXHR){
				alert("获取用收藏户活动信息发生错误："+jqXHR.status);
			}
		});
	});
	//jq.ajax获取用户关注的人列表
	$(function getUserAttention(){
		var $data=[];
		$.ajax({
			type:"POST",
			url:$url+"api/webServer.php",
			dataType:"json",
			data:{
				action:"getUserAttention",
				uId:117
				},
			success:function(data){
				if(data.status==1){
					$data=data.datas;
					for(var $i=0;$i<$data.length;$i++){
						$("#zxc_4_left_ul").append($("<div id="+$data[$i].id+" class='zxc_4_left_div'><img src="+$data[$i].face+" class='zxc_4_left_img'/><a href='javascript:;' id="+$data[$i].id+" class='zxc_5_left_name'>"+$data[$i].username+"</a><span class='zxc_4_left_attention'>关注:"+$data[$i].attention+"</span><span class='zxc_4_left_follower'>粉丝:"+$data[$i].follower+"</span><span class='zxc_4_left_sex'>"+$data[$i].sex+"</span></div>"));
					}
				}
				else{alert(data.message);
				}
			},
			error:function(jqXHR){
				alert("获取用户关注发生错误："+jqXHR.status);
			}
		});
	});
	//jq.ajax获取用户粉丝列表
	$(function getUserFollower(){
		var $data=[];
		$.ajax({
			type:"POST",
			url:$url+"api/webServer.php",
			dataType:"json",
			data:{
				action:"getUserFollower",
				uId:117
				},
			success:function(data){
				if(data.status==1){
					$data=data.datas;
					for(var $i=0;$i<$data.length;$i++){
						$("#zxc_5_left_ul").append($("<div id="+$data[$i].id+" class='zxc_5_left_div'><img src="+$data[$i].face+" class='zxc_5_left_img'/><a href='javascript:;' id="+$data[$i].id+" class='zxc_5_left_name'>"+$data[$i].username+"</a><span class='zxc_5_left_attention'>关注:"+$data[$i].attention+"</span><span class='zxc_5_left_follower'>粉丝:"+$data[$i].follower+"</span><span class='zxc_5_left_sex'>"+$data[$i].sex+"</span></div>"));
					}
				}
				else{alert(data.message);
				}
			},
			error:function(jqXHR){
				alert("获取用户粉丝发生错误："+jqXHR.status);
			}
		});
	});
	//获取省份列表
	$("#city_select_button").click(function(){
		$("#campus_list").slideUp(200);
		$("#campus_list_top").slideUp(200);
		$("#city_list_top").slideToggle(30);
		$("#city_list").slideToggle(300);
	});
	//确定省份
	$("#city_list").on("click","a", function(){
		$(this).siblings().css("border-left","solid 2px #CCC");
		$(this).css("border-left","solid 3px #00AF42");
		$pid=$(this).attr('id');
		$pname=$(this).html();
		$("#campus_list").empty();
		$(function(){
			var $data=[];
			$.ajax({
				type:"POST",
				url:$url+"api/webServer.php",
				dataType:"json",
				data:{
					action:"getSchoolByPid",
					pId:$pid
					},
				success:function(data){
					if(data.status==1){
						$data=data.datas;
						for(var $i=0;$i<$data.length;$i++){
							$("#campus_list").append($("<a  href='javascript:;' id="+$data[$i].id+" class='campus'>"+$data[$i].sName+"</a>"));
							}
					}
					else{alert(data.message);
					}
				},
				error:function(jqXHR){
					alert("获取学校发生错误："+jqXHR.status);
				}
			});
		});
		$("#city_selected>span").html($pname);
		$("#school_selected>span").html("&nbsp;");
		$("#city_list").slideUp(200);
		$("#city_list_top").slideUp(200);
 	});
	//获取学校份列表
	$("#campus_select_button").click(function(){
		$("#city_list").slideUp(200);
		$("#city_list_top").slideUp(200);
		$("#campus_list_top").slideToggle(30);
		$("#campus_list").slideToggle(300);
	});
	//确定学校
	$("#campus_list").on("click","a", function(){
		$("#navigation").empty();
		$(this).siblings().css("border-left","solid 2px #CCC");
		$(this).css("border-left","solid 3px #00AF42");
		$sid=$(this).attr('id');
		$sname=$(this).html();
		$("#school_selected>span").html($sname);
		$("#campus_list").slideUp(200);
		$("#campus_list_top").slideUp(200);
		var $data=[];
		var $h=0;
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
					for(var $a=1;$a<8;$a++){
						$("#activity0"+$a+"_dialog").css("display","none");}
					for(var $i=1;$i<=$data.length;$i++){
						$("#activity0"+$i+"_dialog").css('display','block');
						$("#activity0"+$i+"_left_text").html($data[$i-1].cName);
						$("#activity0"+$i+"_left_text").add("#activity0"+$i+"_left_href").add("#activity0"+$i+"_middle_toplist_showmore_text").attr('href',$url+"campus_activitypage.html?status=1&sName="+encodeURI($sname)+"&sId="+$sid+"&cId="+$data[$i-1].id);
						$("#navigation").append($("<a href='javascript:;' id="+$i+" class='navigation_floor' title='"+$data[$i-1].cName+"'>"+$data[$i-1].cName+"</a>"));
						$("#publishfast_dialog_campus").append($("<option value='"+$data[$i-1].id+"'>"+$data[$i-1].cName+"</option>"));
						$cid.push($data[$i-1].id);
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
			if($cid.length>0){
				$.windowbox.getListCampusActive(1,0,1,$order[0]);
			}
			if($cid.length>1){
				$.windowbox.getListCampusActive(2,0,1,$order[1]);
			}
			if($cid.length>2){
				$.windowbox.getListCampusActive(3,0,1,$order[2]);
			}
			if($cid.length>3){
				$.windowbox.getListCampusActive(4,0,1,$order[3]);
			}
			if($cid.length>4){
				$.windowbox.getListCampusActive(5,0,1,$order[4]);
			}
			if($cid.length>5){
				$.windowbox.getListCampusActive(6,0,1,$order[5]);
			}
			if($cid.length>6){
				$.windowbox.getListCampusActive(7,0,1,$order[6]);
			}
			clearInterval(int);}
		},50);
 	});
	//图片翻页箭头的显示与隐藏
	
	//右侧换一换click
	$("#activity01_change_right").click(function(){
			$.windowbox.change_right(1);
	});
	$("#activity02_change_right").click(function(){
			$.windowbox.change_right(2);
	});
	$("#activity03_change_right").click(function(){
			$.windowbox.change_right(3);
	});
	$("#activity04_change_right").click(function(){
			$.windowbox.change_right(4);
	});
	$("#activity05_change_right").click(function(){
			$.windowbox.change_right(5);
	});
	$("#activity06_change_right").click(function(){
			$.windowbox.change_right(6);
	});
	$("#activity07_change_right").click(function(){
			$.windowbox.change_right(7);
	});
	//navigation:css
	$("#navigation").on('mouseover','.navigation_floor',function(){
		$(this).css('border-left','solid 5px #39D973');
		$(this).css('opacity','0.8');
	});
	$("#navigation").on('mouseout','.navigation_floor',function(){
		$(this).css('border-left','solid 5px #EFEFEF');
		$(this).css('opacity','0.2');
	});
	//
	$("#navigation").on('click','.navigation_floor:eq(0)',function(){
		$(window).scrollTop(1080);
	});
	$("#navigation").on('click','.navigation_floor:eq(1)',function(){
		$(window).scrollTop(1600);
	});
	$("#navigation").on('click','.navigation_floor:eq(2)',function(){
		$(window).scrollTop(2120);
	});
	$("#navigation").on('click','.navigation_floor:eq(3)',function(){
		$(window).scrollTop(2640);
	});
	$("#navigation").on('click','.navigation_floor:eq(4)',function(){
		$(window).scrollTop(3160);
	});
	$("#navigation").on('click','.navigation_floor:eq(5)',function(){
		$(window).scrollTop(3680);
	});
	$("#navigation").on('click','.navigation_floor:eq(6)',function(){
		$(window).scrollTop(4200);
	});
	//fix显示与隐藏
	$(function(){
		$(window).scroll(function(){
		if($(window).scrollTop()>420){
			$("#back_to_top").fadeIn(50);
			$("#navigation").fadeIn(50);
			if($(window).scrollTop()>420&&$(window).scrollTop()<1000){
				$("#navigation").children().css('border-left','solid 5px #EFEFEF');
				$("#navigation").children().css('opacity','0.2');
				$("#navigation").on('mouseout','.navigation_floor',function(){
					$(this).css('border-left','solid 5px #EFEFEF');
					$(this).css('opacity','0.2');});
			}
			else if($(window).scrollTop()>1000&&$(window).scrollTop()<1500){
				$("#navigation").children().css('border-left','solid 5px #EFEFEF');
				$("#navigation").children().css('opacity','0.2');
				$("#navigation").on('mouseout','.navigation_floor',function(){
					$(this).css('border-left','solid 5px #EFEFEF');
					$(this).css('opacity','0.2');});
				$("#navigation").children(':first').css('border-left','solid 5px #39D973');
				$("#navigation").children(':first').css('opacity','0.8');
				$("#navigation").on('mouseout','.navigation_floor:first',function(){
					$(this).css('border-left','solid 5px #39D973');
					$(this).css('opacity','0.8');});
			}
			else if($(window).scrollTop()>1500&&$(window).scrollTop()<2070){
				$("#navigation").children().css('border-left','solid 5px #EFEFEF');
				$("#navigation").children().css('opacity','0.2');
				$("#navigation").on('mouseout','.navigation_floor',function(){
					$(this).css('border-left','solid 5px #EFEFEF');
					$(this).css('opacity','0.2');});
				$("#navigation").children(':eq(1)').css('border-left','solid 5px #39D973');
				$("#navigation").children(':eq(1)').css('opacity','0.8');
				$("#navigation").on('mouseout','.navigation_floor:eq(1)',function(){
					$(this).css('border-left','solid 5px #39D973');
					$(this).css('opacity','0.8');});
			}
			else if($(window).scrollTop()>2070&&$(window).scrollTop()<2600){
				$("#navigation").children().css('border-left','solid 5px #EFEFEF');
				$("#navigation").children().css('opacity','0.2');
				$("#navigation").on('mouseout','.navigation_floor',function(){
					$(this).css('border-left','solid 5px #EFEFEF');
					$(this).css('opacity','0.2');});
				$("#navigation").children(':eq(2)').css('border-left','solid 5px #39D973');
				$("#navigation").children(':eq(2)').css('opacity','0.8');
				$("#navigation").on('mouseout','.navigation_floor:eq(2)',function(){
					$(this).css('border-left','solid 5px #39D973');
					$(this).css('opacity','0.8');});
			}
			else if($(window).scrollTop()>2580&&$(window).scrollTop()<3100){
				$("#navigation").children().css('border-left','solid 5px #EFEFEF');
				$("#navigation").children().css('opacity','0.2');
				$("#navigation").on('mouseout','.navigation_floor',function(){
					$(this).css('border-left','solid 5px #EFEFEF');
					$(this).css('opacity','0.2');});
				$("#navigation").children(':eq(3)').css('border-left','solid 5px #39D973');
				$("#navigation").children(':eq(3)').css('opacity','0.8');
				$("#navigation").on('mouseout','.navigation_floor:eq(3)',function(){
					$(this).css('border-left','solid 5px #39D973');
					$(this).css('opacity','0.8');});
			}
			else if($(window).scrollTop()>3100&&$(window).scrollTop()<3600){
				$("#navigation").children().css('border-left','solid 5px #EFEFEF');
				$("#navigation").children().css('opacity','0.2');
				$("#navigation").on('mouseout','.navigation_floor',function(){
					$(this).css('border-left','solid 5px #EFEFEF');
					$(this).css('opacity','0.2');});
				$("#navigation").children(':eq(4)').css('border-left','solid 5px #39D973');
				$("#navigation").children(':eq(4)').css('opacity','0.8');
				$("#navigation").on('mouseout','.navigation_floor:eq(4)',function(){
					$(this).css('border-left','solid 5px #39D973');
					$(this).css('opacity','0.8');});
			}
			else if($(window).scrollTop()>3600&&$(window).scrollTop()<4100){
				$("#navigation").children().css('border-left','solid 5px #EFEFEF');
				$("#navigation").children().css('opacity','0.2');
				$("#navigation").on('mouseout','.navigation_floor',function(){
					$(this).css('border-left','solid 5px #EFEFEF');
					$(this).css('opacity','0.2');});
				$("#navigation").children(':eq(5)').css('border-left','solid 5px #39D973');
				$("#navigation").children(':eq(5)').css('opacity','0.8');
				$("#navigation").on('mouseout','.navigation_floor:eq(5)',function(){
					$(this).css('border-left','solid 5px #39D973');
					$(this).css('opacity','0.8');});
			}
			else if($(window).scrollTop()>4100){
				$("#navigation").children().css('border-left','solid 5px #EFEFEF');
				$("#navigation").children().css('opacity','0.2');
				$("#navigation").on('mouseout','.navigation_floor',function(){
					$(this).css('border-left','solid 5px #EFEFEF');
					$(this).css('opacity','0.2');});
				$("#navigation").children(':eq(6)').css('border-left','solid 5px #39D973');
				$("#navigation").children(':eq(6)').css('opacity','0.8');
				$("#navigation").on('mouseout','.navigation_floor:eq(6)',function(){
					$(this).css('border-left','solid 5px #39D973');
					$(this).css('opacity','0.8');});
			}
		}
		else{
			$("#back_to_top").fadeOut(50);
			$("#navigation").fadeOut(50);
		}
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
	//左侧鼠标滑过效果
	$("#activity01_left").mouseover(function(){
		$("#activity01_left_text").stop();
		$("#activity01_left_text").animate({top:"130px"},300);
		$("#activity01_left_href").stop();
		$("#activity01_left_href").fadeToggle(300);
	});
	$("#activity01_left").mouseout(function(){
		$("#activity01_left_text").stop();
		$("#activity01_left_text").animate({top:"240px"},500);
		$("#activity01_left_href").stop();
		$("#activity01_left_href").fadeToggle(300);
	});
	$("#activity02_left").mouseover(function(){
		$("#activity02_left_text").stop();
		$("#activity02_left_text").animate({top:"130px"},300);
		$("#activity02_left_href").stop();
		$("#activity02_left_href").fadeToggle(300);
	});
	$("#activity02_left").mouseout(function(){
		$("#activity02_left_text").stop();
		$("#activity02_left_text").animate({top:"240px"},500);
		$("#activity02_left_href").stop();
		$("#activity02_left_href").fadeToggle(300);
	});
	$("#activity03_left").mouseover(function(){
		$("#activity03_left_text").stop();
		$("#activity03_left_text").animate({top:"130px"},300);
		$("#activity03_left_href").stop();
		$("#activity03_left_href").fadeToggle(300);
	});
	$("#activity03_left").mouseout(function(){
		$("#activity03_left_text").stop();
		$("#activity03_left_text").animate({top:"240px"},500);
		$("#activity03_left_href").stop();
		$("#activity03_left_href").fadeToggle(300);
	});
	$("#activity04_left").mouseover(function(){
		$("#activity04_left_text").stop();
		$("#activity04_left_text").animate({top:"130px"},300);
		$("#activity04_left_href").stop();
		$("#activity04_left_href").fadeToggle(300);
	});
	$("#activity04_left").mouseout(function(){
		$("#activity04_left_text").stop();
		$("#activity04_left_text").animate({top:"240px"},500);
		$("#activity04_left_href").stop();
		$("#activity04_left_href").fadeToggle(300);
	});
	$("#activity05_left").mouseover(function(){
		$("#activity05_left_text").stop();
		$("#activity05_left_text").animate({top:"130px"},300);
		$("#activity05_left_href").stop();
		$("#activity05_left_href").fadeToggle(300);
	});
	$("#activity05_left").mouseout(function(){
		$("#activity05_left_text").stop();
		$("#activity05_left_text").animate({top:"240px"},500);
		$("#activity05_left_href").stop();
		$("#activity05_left_href").fadeToggle(300);
	});
	$("#activity06_left").mouseover(function(){
		$("#activity06_left_text").stop();
		$("#activity06_left_text").animate({top:"130px"},300);
		$("#activity06_left_href").stop();
		$("#activity06_left_href").fadeToggle(300);
	});
	$("#activity06_left").mouseout(function(){
		$("#activity06_left_text").stop();
		$("#activity06_left_text").animate({top:"240px"},500);
		$("#activity06_left_href").stop();
		$("#activity06_left_href").fadeToggle(300);
	});
	$("#activity07_left").mouseover(function(){
		$("#activity07_left_text").stop();
		$("#activity07_left_text").animate({top:"130px"},300);
		$("#activity07_left_href").stop();
		$("#activity07_left_href").fadeToggle(300);
	});
	$("#activity07_left").mouseout(function(){
		$("#activity07_left_text").stop();
		$("#activity07_left_text").animate({top:"240px"},500);
		$("#activity07_left_href").stop();
		$("#activity07_left_href").fadeToggle(300);
	});
	//上方dialog收起与展开
	$("#asd_ctrl").mouseover(function(){
		$("#asd_ctrl").css('opacity','1');
	});
	$("#asd_ctrl").mouseout(function(){
		$("#asd_ctrl").css('opacity','0.3');
	});
	$("#asd_ctrl").click(function(){
		$("#asd").slideToggle(300);
		setTimeout(function(){
		$("<div id='asdf'><div id='asdf_ctrl'>展开个人驿站<i class='iconfont' style='font-size:12px;'>&#xe61a;</i></div></div>").insertBefore("#asd");
		$("#asdf").on('click',function(){
			$("#asdf").remove();
			$("#asd").slideToggle(300);
		});},240);
	});
	//edit标签css
	$("#asd_edit").mouseover(function(){
		$("#asd_edit").css('opacity','0.7');
		$("#asd_edit").css('background-color','#F60');
		$("#asd_edit").css('color','#FFF');
	});
	$("#asd_edit").mouseout(function(){
		$("#asd_edit").css('opacity','0.3');
		$("#asd_edit").css('background-color','#CECECE');
		$("#asd_edit").css('color','#665');
	});
	//上方左侧栏mouseover、mouseout、click事件效果
	$(".qwe_foot_list").mouseover(function(){
		$(this).css('box-shadow','0px 0px 8px #CCC');
		$(this).next().css('z-index','0');
		$(this).children(".stand").fadeIn(10);
	});
	$(".qwe_foot_list").mouseout(function(){
		$(this).css('box-shadow','none');
		$(this).next().css('z-index','1');
		$(this).children(".stand").fadeOut(10);
	});
	$(".qwe_foot_list").click(function(){
		var $this=$(this);
		$(".qwe_foot_list").children(".stand").css('background-color','#39D973');
		$(".qwe_foot_list").children(".stand").fadeOut(1);
		$(".qwe_foot_list").css('z-index','1');
		$(".qwe_foot_list").children(".qwe_foot_list_img").css('color','#666'); 
		$(".qwe_foot_list").on('mouseover',function(){
			$(this).css('box-shadow','0px 0px 8px #CCC');
			$(this).next().css('z-index','0');
			$(this).children(".stand").fadeIn(10);
		});
		$(".qwe_foot_list").on('mouseout',function(){
			$(this).css('box-shadow','none');
			$(this).next().css('z-index','1');
			$(this).children(".stand").fadeOut(10);
		});
		$this.children(".stand").css('background-color','#00AF42');
		$this.children(".stand").fadeIn(1);
		$this.off('mouseout');
		$this.off('mouseover');
		$this.css('box-shadow','none');
		$this.children(".qwe_foot_list_img").css('color','#00AF42'); 
		$(".zxc_").css('display','none');
		$("#zxc_"+($("#qwe_foot").children(".qwe_foot_list").index($this)+1)).css('display','inline-block');
	});
	//中间按时间排序
	for(var $a=1;$a<=7;$a++){
		$("#activity0"+$a+"_middle_toplist_select_time").click(function(){
			$order[parseInt($(this).index(".middle_toplist_select_time"))]='sPubtime';
			$(this).parent().next().empty();
			$.windowbox.getListCampusActive(parseInt($(this).index(".middle_toplist_select_time"))+1,0,1,'sPubtime');
		});
	};
	//中间按热度排序
	for(var $a=1;$a<=7;$a++){
		$("#activity0"+$a+"_middle_toplist_select_hot").click(function(){
			$order[parseInt($(this).index(".middle_toplist_select_hot"))]='sCurrentNumber';
			$(this).parent().next().empty();
			$.windowbox.getListCampusActive(parseInt($(this).index(".middle_toplist_select_hot"))+1,0,1,'sCurrentNumber');
		});
	};
	//中间收藏事件效果
	for(var $i=1;$i<6;$i++){
		for(var $a=1;$a<=7;$a++){
			$("#activity0"+$a+"_middle__main_list").on("click","#activity0"+$a+"_middle__main_list_content_like_button_"+$i,function(){
			var $this=$(this);
			if($this.children(".iconfont").attr('id')=="i1"){
				$this.empty();
				$this.append("<i class='iconfont' id='i2' style='color:#F00; font-size:14px'>&#xe600;</i>收藏");}
			else if($this.children(".iconfont").attr('id')=="i2"){
				$this.empty();
				$this.append("<i class='iconfont' id='i1' style='color:#FFF; font-size:14px'>&#xe608;</i>收藏");};
			})
		}
	};
	//中间点赞事件效果
	for(var $i=1;$i<6;$i++){
		for(var $a=1;$a<=7;$a++){
			$("#activity0"+$a+"_middle__main_list").on("click","#activity0"+$a+"_middle__main_list_content_appreciate_button_"+$i,function(){
			var $this=$(this);
			if($this.children(".iconfont").attr('id')=="i1"){
				$this.empty();
				$this.append("<i class='iconfont' id='i2' style='color:#00AF42;'>&#xe63e;</i>");}
			else if($this.children(".iconfont").attr('id')=="i2"){
				$this.empty();
				$this.append("<i class='iconfont' id='i1' style='color:#FFF;'>&#xe63d;</i>");};
			})
			$("#activity0"+$a+"_middle__main_list").on("mouseover","#activity0"+$a+"_middle__main_list_content_appreciate_button_"+$i,function(){
				$(this).parent().next().css('display','inline-block');
			})
			$("#activity0"+$a+"_middle__main_list").on("mouseout","#activity0"+$a+"_middle__main_list_content_appreciate_button_"+$i,function(){
				$(this).parent().next().css('display','none');
			})
		}
	};
	//中间分享事件效果
	for(var $i=1;$i<6;$i++){
		for(var $a=1;$a<=7;$a++){
			$("#activity0"+$a+"_middle__main_list").on("click","#activity0"+$a+"_middle__main_list_content_share_button_"+$i,function(){
			var $this=$(this);
			if($this.children(".iconfont").attr('id')=="i1"){
				$this.empty();
				$this.append("<i class='iconfont' id='i2' style='color:#00AF42;'>&#xe603;</i>");}
			else if($this.children(".iconfont").attr('id')=="i2"){
				$this.empty();
				$this.append("<i class='iconfont' id='i1' style='color:#FFF;'>&#xe607;</i>");};
			})
			$("#activity0"+$a+"_middle__main_list").on("mouseover","#activity0"+$a+"_middle__main_list_content_share_button_"+$i,function(){
				$(this).parent().next().css('display','inline-block');
			})
			$("#activity0"+$a+"_middle__main_list").on("mouseout","#activity0"+$a+"_middle__main_list_content_share_button_"+$i,function(){
				$(this).parent().next().css('display','none');
			})
		}
	};
	//中间讨论事件效果
	for(var $i=1;$i<6;$i++){
		for(var $a=1;$a<=7;$a++){
			$("#activity0"+$a+"_middle__main_list").on("mouseover","#activity0"+$a+"_middle__main_list_content_comment_button_"+$i,function(){
				$(this).parent().next().css('display','inline-block');
			})
			$("#activity0"+$a+"_middle__main_list").on("mouseout","#activity0"+$a+"_middle__main_list_content_comment_button_"+$i,function(){
				$(this).parent().next().css('display','none');
			})
		}
	};
	//中间moredialog显示与隐藏
	for(var $i=1;$i<6;$i++){
		for(var $a=1;$a<=7;$a++){
			$("#activity0"+$a+"_middle__main_list").on("click","#activity0"+$a+"_middle__main_list_content_more_button_"+$i,function(){
				if($(this).parent().next().next().is(':hidden')){
					$(this).parent().next().next().fadeIn(100);
				}
				else if(!($(this).parent().next().next().is(':hidden'))){
					$(this).parent().next().next().fadeOut(100);
				}
			})
		}
	};
	for(var $i=1;$i<6;$i++){
		for(var $a=1;$a<=7;$a++){
			$("#activity0"+$a+"_middle__main_list").on("mouseover","#activity0"+$a+"_middle__main_list_content_"+$i,function(){
				$(".more_dialog").fadeOut(10);
				$(this).children("div:last").stop().fadeIn(10);
			})
		}
	};
	//中间user_dialog显示与隐藏
	for(var $i=1;$i<6;$i++){
		for(var $a=1;$a<=7;$a++){
			$("#activity0"+$a+"_middle__main_list").on("mouseover","#activity0"+$a+"_middle__main_list_content_name_button_"+$i,function(){
				$(this).css('color','#02F');
				$this=$(this).parent().parent().prev().children(':first');
				$('.user_dialog').stop();
				$('.user_dialog').not($this).fadeOut(20);
				$.windowbox.getPuserinformation(parseInt($(this).parent().parent().parent().index(".middle__main_list"))+1,parseInt($(this).index("#activity0"+(parseInt($(this).parent().parent().parent().index('.middle__main_list'))+1)+"_middle__main_list .middle__main_list_content_name_button"))+1,$(this).attr('title'));
				$(this).attr('title','');
				$isover=1;
				setTimeout(function(){if($isover==1)$this.fadeIn(50);},500);
			})
			$("#activity0"+$a+"_middle__main_list").on("mouseout","#activity0"+$a+"_middle__main_list_content_name_button_"+$i,function(){
				$(this).css('color','#666');
				$this=$(this).parent().parent().prev().children(':first');
				$('.user_dialog').stop();
				$isover=0;
				setTimeout(function(){if($isover==0)$this.fadeOut(50);},500);
			})
		}
	};
	for(var $i=1;$i<6;$i++){
		for(var $a=1;$a<=7;$a++){
			$("#activity0"+$a+"_middle__main_list").on("mouseover","#activity0"+$a+"_middle__main_list_img_"+$i,function(){
				$this=$(this).next().children(':first');
				$('.user_dialog').stop();
				$('.user_dialog').not($this).fadeOut(20);
				$.windowbox.getPuserinformation(parseInt($(this).parent().index(".middle__main_list"))+1,parseInt($(this).index("#activity0"+(parseInt($(this).parent().index(".middle__main_list"))+1)+"_middle__main_list .middle__main_list_img"))+1,$(this).attr('title'));
				$(this).attr('title','');
				$isover=1;
				setTimeout(function(){if($isover==1)$this.fadeIn(50);},500);
			});
			$("#activity0"+$a+"_middle__main_list").on("mouseout","#activity0"+$a+"_middle__main_list_img_"+$i,function(){
				$this=$(this).next().children(':first');
				$('.user_dialog').stop();
				$isover=0;
				setTimeout(function(){if($isover==0)$this.fadeOut(50);},500);
			});
		}
	};
	for(var $i=1;$i<6;$i++){
		for(var $a=1;$a<=7;$a++){
			$("#activity0"+$a+"_middle__main_list").on("mouseover","#activity0"+$a+"_user_dialog_"+$i,function(){
				$this=$(this);
				$('.user_dialog').stop();
				$isover=1;
			});
			$("#activity0"+$a+"_middle__main_list").on("mouseout","#activity0"+$a+"_user_dialog_"+$i,function(){
				$this=$(this);
				$('.user_dialog').stop();
				$isover=0;
				setTimeout(function(){if($isover==0)$this.fadeOut(50);},500);
			});
		}
	};	
	//中间滚动加载
	$(".middle__main_list").scroll(function(){
	if($(this).scrollTop()>=$(this).get(0).scrollHeight-$(this).height()){
		$.windowbox.getListCampusActive(parseInt($(this).index(".middle__main_list"))+1,$(this).children(':last').children(':last').children(':last').prev().html(),parseInt($(this).children(':last').children(':last').children(':last').html())+1,$order[parseInt($(this).index(".middle_toplist_select_time"))]);
		for(var $i=parseInt($(this).children(':last').children(':last').children(':last').html())+1;$i<parseInt($(this).children(':last').children(':last').children(':last').html())+6;$i++){
			$("#activity0"+(parseInt($(this).index('.middle__main_list'))+1)+"_middle__main_list").on("click","#activity0"+(parseInt($(this).index('.middle__main_list'))+1)+"_middle__main_list_content_like_button_"+$i,function(){
			var $this=$(this);
			if($this.children(".iconfont").attr('id')=="i1"){
				$this.empty();
				$this.append("<i class='iconfont' id='i2' style='color:#F00; font-size:14px'>&#xe600;</i>收藏");}
			else if($this.children(".iconfont").attr('id')=="i2"){
				$this.empty();
				$this.append("<i class='iconfont' id='i1' style='color:#FFF; font-size:14px'>&#xe608;</i>收藏");};
			})
		};
		for(var $i=parseInt($(this).children(':last').children(':last').children(':last').html())+1;$i<parseInt($(this).children(':last').children(':last').children(':last').html())+6;$i++){
			$("#activity0"+(parseInt($(this).index('.middle__main_list'))+1)+"_middle__main_list").on("click","#activity0"+(parseInt($(this).index('.middle__main_list'))+1)+"_middle__main_list_content_appreciate_button_"+$i,function(){
			var $this=$(this);
			if($this.children(".iconfont").attr('id')=="i1"){
				$this.empty();
				$this.append("<i class='iconfont' id='i2' style='color:#00AF42;'>&#xe63e;</i>");}
			else if($this.children(".iconfont").attr('id')=="i2"){
				$this.empty();
				$this.append("<i class='iconfont' id='i1' style='color:#FFF;'>&#xe63d;</i>");};
			})
			$("#activity0"+(parseInt($(this).index('.middle__main_list'))+1)+"_middle__main_list").on("mouseover","#activity0"+(parseInt($(this).index('.middle__main_list'))+1)+"_middle__main_list_content_appreciate_button_"+$i,function(){
				$(this).parent().next().css('display','inline-block');
			})
			$("#activity0"+(parseInt($(this).index('.middle__main_list'))+1)+"_middle__main_list").on("mouseout","#activity0"+(parseInt($(this).index('.middle__main_list'))+1)+"_middle__main_list_content_appreciate_button_"+$i,function(){
				$(this).parent().next().css('display','none');
			})
		};
		for(var $i=parseInt($(this).children(':last').children(':last').children(':last').html())+1;$i<parseInt($(this).children(':last').children(':last').children(':last').html())+6;$i++){
			$("#activity0"+(parseInt($(this).index('.middle__main_list'))+1)+"_middle__main_list").on("click","#activity0"+(parseInt($(this).index('.middle__main_list'))+1)+"_middle__main_list_content_share_button_"+$i,function(){
			var $this=$(this);
			if($this.children(".iconfont").attr('id')=="i1"){
				$this.empty();
				$this.append("<i class='iconfont' id='i2' style='color:#00AF42;'>&#xe603;</i>");}
			else if($this.children(".iconfont").attr('id')=="i2"){
				$this.empty();
				$this.append("<i class='iconfont' id='i1' style='color:#FFF;'>&#xe607;</i>");};
			})
			$("#activity0"+(parseInt($(this).index('.middle__main_list'))+1)+"_middle__main_list").on("mouseover","#activity0"+(parseInt($(this).index('.middle__main_list'))+1)+"_middle__main_list_content_share_button_"+$i,function(){
				$(this).parent().next().css('display','inline-block');
			})
			$("#activity0"+(parseInt($(this).index('.middle__main_list'))+1)+"_middle__main_list").on("mouseout","#activity0"+(parseInt($(this).index('.middle__main_list'))+1)+"_middle__main_list_content_share_button_"+$i,function(){
				$(this).parent().next().css('display','none');
			})
		};
		for(var $i=parseInt($(this).children(':last').children(':last').children(':last').html())+1;$i<parseInt($(this).children(':last').children(':last').children(':last').html())+6;$i++){
			$("#activity0"+(parseInt($(this).index('.middle__main_list'))+1)+"_middle__main_list").on("mouseover","#activity0"+(parseInt($(this).index('.middle__main_list'))+1)+"_middle__main_list_content_comment_button_"+$i,function(){
				$(this).parent().next().css('display','inline-block');
			})
			$("#activity0"+(parseInt($(this).index('.middle__main_list'))+1)+"_middle__main_list").on("mouseout","#activity0"+(parseInt($(this).index('.middle__main_list'))+1)+"_middle__main_list_content_comment_button_"+$i,function(){
				$(this).parent().next().css('display','none');
			})
		};
		for(var $i=parseInt($(this).children(':last').children(':last').children(':last').html())+1;$i<parseInt($(this).children(':last').children(':last').children(':last').html())+6;$i++){
			$("#activity0"+(parseInt($(this).index('.middle__main_list'))+1)+"_middle__main_list").on("click","#activity0"+(parseInt($(this).index('.middle__main_list'))+1)+"_middle__main_list_content_more_button_"+$i,function(){
				if($(this).parent().next().next().is(':hidden')){
					$(this).parent().next().next().fadeIn(100);
				}
				else if(!($(this).parent().next().next().is(':hidden'))){
					$(this).parent().next().next().fadeOut(100);
				}
			})
		};
		for(var $i=parseInt($(this).children(':last').children(':last').children(':last').html())+1;$i<parseInt($(this).children(':last').children(':last').children(':last').html())+6;$i++){
			$("#activity0"+(parseInt($(this).index('.middle__main_list'))+1)+"_middle__main_list").on("mouseover","#activity0"+(parseInt($(this).index('.middle__main_list'))+1)+"_middle__main_list_content_"+$i,function(){
				$(".more_dialog").fadeOut(10);
				$(this).children("div:last").stop().fadeIn(10);
			})
		};
		for(var $i=parseInt($(this).children(':last').children(':last').children(':last').html())+1;$i<parseInt($(this).children(':last').children(':last').children(':last').html())+6;$i++){
			$("#activity0"+(parseInt($(this).index('.middle__main_list'))+1)+"_middle__main_list").on("mouseover","#activity0"+(parseInt($(this).index('.middle__main_list'))+1)+"_middle__main_list_content_name_button_"+$i,function(){
				$(this).css('color','#02F');
				$this=$(this).parent().parent().prev().children(':first');
				$('.user_dialog').stop();
				$('.user_dialog').not($this).fadeOut(20);
				$.windowbox.getPuserinformation(parseInt($(this).parent().parent().parent().index(".middle__main_list"))+1,parseInt($(this).index("#activity0"+(parseInt($(this).parent().parent().parent().index('.middle__main_list'))+1)+"_middle__main_list .middle__main_list_content_name_button"))+1,$(this).attr('title'));
				$isover=1;
				setTimeout(function(){if($isover==1)$this.fadeIn(50);},500,function(){$(this).attr('title','');});
			})
			$("#activity0"+(parseInt($(this).index('.middle__main_list'))+1)+"_middle__main_list").on("mouseout","#activity0"+(parseInt($(this).index('.middle__main_list'))+1)+"_middle__main_list_content_name_button_"+$i,function(){
				$(this).css('color','#666');
				$this=$(this).parent().parent().prev().children(':first');
				$('.user_dialog').stop();
				$isover=0;
				setTimeout(function(){if($isover==0)$this.fadeOut(50);},500);
			})
		};
		for(var $i=parseInt($(this).children(':last').children(':last').children(':last').html())+1;$i<parseInt($(this).children(':last').children(':last').children(':last').html())+6;$i++){
			$("#activity0"+(parseInt($(this).index('.middle__main_list'))+1)+"_middle__main_list").on("mouseover","#activity0"+(parseInt($(this).index('.middle__main_list'))+1)+"_middle__main_list_img_"+$i,function(){
				$this=$(this).next().children(':first');
				$('.user_dialog').stop();
				$('.user_dialog').not($this).fadeOut(20);
				$.windowbox.getPuserinformation(parseInt($(this).parent().index(".middle__main_list"))+1,parseInt($(this).index("#activity0"+(parseInt($(this).parent().index(".middle__main_list"))+1)+"_middle__main_list .middle__main_list_img"))+1,$(this).attr('title'));
				$(this).attr('title','');
				$isover=1;
				setTimeout(function(){if($isover==1)$this.fadeIn(50);},500);
			});
			$("#activity0"+(parseInt($(this).index('.middle__main_list'))+1)+"_middle__main_list").on("mouseout","#activity0"+(parseInt($(this).index('.middle__main_list'))+1)+"_middle__main_list_img_"+$i,function(){
				$this=$(this).next().children(':first');
				$('.user_dialog').stop();
				$isover=0;
				setTimeout(function(){if($isover==0)$this.fadeOut(50);},500);
			});
		};
		for(var $i=parseInt($(this).children(':last').children(':last').children(':last').html())+1;$i<parseInt($(this).children(':last').children(':last').children(':last').html())+6;$i++){
			$("#activity0"+(parseInt($(this).index('.middle__main_list'))+1)+"_middle__main_list").on("mouseover","#activity0"+(parseInt($(this).index('.middle__main_list'))+1)+"_user_dialog_"+$i,function(){
				$this=$(this);
				$('.user_dialog').stop();
				$isover=1;
			});
			$("#activity0"+(parseInt($(this).index('.middle__main_list'))+1)+"_middle__main_list").on("mouseout","#activity0"+(parseInt($(this).index('.middle__main_list'))+1)+"_user_dialog_"+$i,function(){
				$this=$(this);
				$('.user_dialog').stop();
				$isover=0;
				setTimeout(function(){if($isover==0)$this.fadeOut(50);},500);
			});
		};}
	});
	//右侧鼠标滑过事件效果
	for(var $i=1;$i<4;$i++){
		for(var $a=1;$a<=7;$a++){
			$("#activity0"+$a+"_right_title_"+$i).mouseover(function(){
				$this=$(this);
				$this.css("background","rgba(102,102,102,0.85)");
			})
			$("#activity0"+$a+"_right_title_"+$i).mouseout(function(){
				$this=$(this);
				if($this.next().is(':hidden')){
					$this.css("background","rgba(102,102,102,0.3)");}	
			})
		}
	};
	//右侧点击事件效果
	$(".right_title").click(function(){
		$this=$(this);
		if($this.next().is(':hidden')){
			$this.parent().children(":first").next().fadeOut(100);
			$this.parent().children(".right_title").css("background","rgba(102,102,102,0.3)")
			$this.parent().children(".right_content").slideUp(300);
			$this.css("background","rgba(102,102,102,0.85)");
			$this.next().slideDown(300);
		}
		else if(!($this.next().is(':hidden'))){
			$this.parent().children(":first").next().fadeIn(300);
			$this.css("background","rgba(102,102,102,0.3)");
			$this.next().slideUp(300);
		}
	});
	//快捷发布窗口的弹出与隐藏
	$("#publishfast").click(function(){
		var mydate = new Date();
   		var str=""+mydate.getFullYear()+"/";
   		str+=(mydate.getMonth()+1)+"/";
   		str+=mydate.getDate();
		$("#publishfast_dialog_btime").empty().append($("<option value='"+str+"'>"+str+"</option>"));
		str=""+(mydate.getFullYear()+1)+"/";
   		str+=(mydate.getMonth()+1)+"/";
   		str+=mydate.getDate();
		$("#publishfast_dialog_stime").empty().append($("<option value='"+str+"'>"+str+"</option>"));
		$this=$(this);
		$.ajax({
			url:$url+"api/webServer.php",
			type: "POST",
			dataType:"json",
      			data: {
				action:"getActiveByCid",
				cId:$("#publishfast_dialog_campus").val()
				},
				success:function(data){
				if(data.status==1){
					$data=data.datas;
					for(var $i=0;$i<$data.length;$i++){
						$("#publishfast_dialog_activity").append($("<option value='"+$i+"'>"+$data[$i].aName+"&nbsp;"+$data[$i].pName+"</option>"));
					}
				}
				else{alert(data.message);	
				}
			},	
			error:function(jqXHR){
				alert("获取快捷活动发生错误："+jqXHR.status);
			}			
		});	
		$("#publishfast_dialogfloor").fadeIn(300);
	});
	$("#publishfast_close").click(function(){
		$("#publishfast_dialogfloor").fadeOut(300);
	});
	//校区下拉列表失焦
	$("#publishfast_dialog_campus").blur(function(){
		$("#publishfast_dialog_activity").empty();
		$this=$(this);
		$.ajax({
			url:$url+"api/webServer.php",
			type: "POST",
			dataType:"json",
      			data: {
				action:"getActiveByCid",
				cId:$("#publishfast_dialog_campus").val()
				},
				success:function(data){
				if(data.status==1){
					$data=data.datas;
					for(var $i=0;$i<$data.length;$i++){
						$("#publishfast_dialog_activity").append($("<option value='"+$i+"'>"+$data[$i].aName+"&nbsp;"+$data[$i].pName+"</option>"));
					}
				}
				else{alert(data.message);	
				}
			},	
			error:function(jqXHR){
				alert("获取快捷活动发生错误："+jqXHR.status);
			}			
		});	
	});
	//校区下拉列表失焦
	$("#publishfast_dialog_activity").change(function(){
		$.ajax({
			url:$url+"api/webServer.php",
			type: "POST",
			dataType:"json",
      			data: {
				action:"getActiveByCid",
				cId:$("#publishfast_dialog_campus").val()
				},
				success:function(data){
				if(data.status==1){
					$data=data.datas;
					$("#publishfast_dialog_title").val($data[($("#publishfast_dialog_activity").val())].aName);
					$("#publishfast_dialog_description").val($data[($("#publishfast_dialog_activity").val())].aDesc);
				}
				else{alert(data.message);	
				}
			},	
			error:function(jqXHR){
				alert("获取快捷活动描述发生错误："+jqXHR.status);
			}			
		});	
	});
	//快捷发布提交
	$("#publishfast_button").mouseover(function(){
		$("#publishfast_button").css('background','rgba(255,102,0,1)');
	});
	$("#publishfast_button").mouseout(function(){
		$("#publishfast_button").css('background','rgba(255,102,0,0.7)');		
	});
	$("#publishfast_button").click(function(){
		$.ajax({
			url:$url+"api/webServer.php",
			type: "POST",
			dataType:"json",
      			data: {
				action:"pubSituation",
				sId:$sid,
				cId:$("#publishfast_dialog_campus").val(),
				sTitle:$("#publishfast_dialog_title").val(),
				sDesc:$("#publishfast_dialog_description").val(),
				sPosition:'555',//$("#publishfast_dialog_activity").text(),
				sNumber:$("#publishfast_dialog_number").val(),
				sBtime:$("#publishfast_dialog_btime").val(),
				sEtime:$("#publishfast_dialog_stime").val()
				},
				success:function(data){
				if(data.status==1){
					alert(data.message);
				}
				else{alert(data.message);}
			},	
			error:function(jqXHR){
				alert("快捷发布活动发生错误："+jqXHR.status);
			}			
		});	
	});
});
addLoadEvent(showTime);
addLoadEvent(photoMove);
addLoadEvent(self_adaption);