webchat_inner = {
	UserId : "",
	KfId: "",
	UsersID : "",
	CLId : "", //常用语id
	sendType : true, //发送信息开关，防止用户快速提交,3秒后随着showmsg操作自动更新状态
	init : function(){
		webchat_inner.UserId = $("#UserId").val();
		webchat_inner.UsersID = $("#UsersID").val();
		webchat_inner.KfId = $("#KfId").val();
		webchat_inner.ctrlFace(); //表情控制
		webchat_inner.setWindowScrollTop();//设置滚动条
		webchat_inner.setParentNum(webchat_inner.UserId);//设置父级窗口用户列表未查看信息数目
		webchat_inner.sendMsg();//信息发送信息
		webchat_inner.quickSend();//组合键发送信息
		webchat_inner.showMsg();//每3秒获取信息
		webchat_inner.ctrlSource();//管理常用语及聊天记录操作
	},
	ctrlSource : function(){//管理常用语及聊天记录操作
		//-------- 常用语、聊天记录切换start ---------//
		$(".cr_frame ul li").each(function(index, element){
            $(element).click(function(){
				$(this).addClass("active").siblings("li").removeClass("active");
				$(".info_item_frame").eq(index).show().siblings(".info_item_frame").hide();
				if(index==1){ //选中聊天记录
					webchat_inner.showHistroyMsg();
				} 
			});
        });
		$(".msg_icon3").click(function(){
			$(".cr_frame ul li").removeClass("active").eq(1).addClass("active");
			$(".info_item_frame").eq(1).show().siblings(".info_item_frame").hide();
			webchat_inner.showHistroyMsg();
		});
		//-------- 常用语、聊天记录切换end ---------//
	},
	commonLang : function(){ //常用语
		webchat_inner.UserId = $("#UserId").val();
		webchat_inner.UsersID = $("#UsersID").val();
		webchat_inner.KfId = $("#KfId").val();
		$("#addUsuallyBtn").click(function(){
			if($("#usuallyMsg").val()){
				var ctrlTyp = webchat_inner.CLId?"mod":"add"; //添加模式或修改模式
				var lang = $("#usuallyMsg").val().replace(/&/g,"|*|");
				$.ajax({
					type	: "POST",
					url		: "/kf/admin/message.php",
					data	: "KfId="+webchat_inner.KfId+"&UsersID="+webchat_inner.UsersID+"&lang="+lang+"&ctrlTyp="+ctrlTyp+"&CLId="+webchat_inner.CLId,
					dataType: "json",
					async : false,
					success	: function(data){
						if(webchat_inner.CLId){
							var tr = $("#ed"+webchat_inner.CLId).parent().parent();
							$(tr).find("td").eq(0).html('<span class="tbAddFrame hand fz_12px" onClick="webchat_inner.copyLang(this)">'+$("#usuallyMsg").val()+'</span>');																		
							webchat_inner.cancelLang(); //还原为常用语添加状态
							$("#cancelUsuallyBtn").hide();
						} else {
							var $line = $("#info_tb");
							$("#line1").after(data.msg);
							$("#usuallyMsg").val("");
							$("#addUsuallyBtn").val("插入");
						}
					}
				});	
			} else {
				$("#usuallyMsg").focus();
			}
		});
	},
	copyLang : function(e){
		$(".reply_frame").text($(e).text());
	},
	cancelLang : function(e){ //取消常用语编辑状态
		$("#addUsuallyBtn").val("添加");
		$("#usuallyMsg").val("");
		$(e).hide();
		webchat_inner.CLId = ""; //还原为常用语添加状态
		$("#usuallyMsg").css("border","1px #ddd solid");
	},
	ctrlLangEdit: function(e,CLId){ //编辑常用语
		var tr = $(e).parent().parent();
		var td = $(tr).find("td").eq(0);
		var value = td.text();
		value = value.replace(/\s+/g,"");
		webchat_inner.CLId = CLId;
		$("#usuallyMsg").val(value);
		$("#addUsuallyBtn").val("修改");
		$("#cancelUsuallyBtn").show();
		$("#usuallyMsg").css("border","1px #f00 solid");
	},
	ctrlLangDel : function(e,CLId){ //删除常用语
		if(confirm("你确定删除该行信息吗？")){
			var tr = $(e).parent().parent();
			$.ajax({
				type	: "POST",
				url		: "/kf/admin/message.php",
				data	: "ctrlTyp=del&CLId="+CLId,
				dataType: "json",
				success	: function(data){
					if(data.status == 1){
						tr.remove();
					}
				}
			});					
		}
	},
	showHistroyMsg : function(){ //显示历史记录
		$.ajax({
			type	: "POST",
			url		: "/kf/admin/ajax.php",
			data	: "type=show&UserId="+webchat_inner.UserId+"&KfId="+webchat_inner.KfId,
			dataType: "json",
			success	: function(msg){					
				if(msg["status"]==1){
					$("#info_item_2").html(msg["MsgList"]);
					$("#info_item_2").scrollTop(100000);
				}
			}
		});
	},
	cancelSendImg : function(){ //取消发送图片
		$(".reply_frame").html("");	
		$("#SendTyp").val("0");
		$(".reply_frame").attr("contenteditable","true");
		$(".reply_cancel").hide();
	},
	sendImg : function(){
		$("#Upload_form").submit();
	},
	addImg : function(fileName,dateTime){
		var obj = $(".list_frame",window.parent.document);
		var img = "<img class='img' src='"+fileName+"' width='130' />";
		$("#SendTyp",window.parent.document).val("1");//更改编辑状态为1图片
		$(".reply_frame" ,window.parent.document).html(img);
		$(".reply_frame" ,window.parent.document).attr("contenteditable","false");
		$(".reply_cancel",window.parent.document).css("display","block")
	},
	showMsg : function(){
		setInterval(function(){
			$.ajax({
				type	: "POST",
				url		: "/kf/admin/ajax.php",
				data	: "type=show&UserId="+webchat_inner.UserId+"&KfId="+webchat_inner.KfId,
				dataType: "json",
				success	: function(msg){
					if(msg["status"] == 1){
						$(".list_frame").html(msg["MsgList"]);
						webchat_inner.setWindowScrollTop();
					}
				}
			});
			webchat_inner.sendType = true;
		},3000);
	},
	quickSend:function(){ 
		$(document).keydown(function(e){
			var oEvent = window.event||e;
            if (oEvent.keyCode == 13 && oEvent.ctrlKey) {  
				if(!$(".reply_frame").html()){
					alert("请输入留言内容");
					return false;
				}
				webchat_inner.addMsg();
				$(".reply_frame").html("");
            }
		});	
	},
	sendMsg : function(){
		$(".reply_btn").click(function(){
			if(!$(".reply_frame").html()){
				alert("请输入留言内容");
				return false;
			}
			
			webchat_inner.addMsg();
			$(".reply_frame").html(""); //清空留言框
		});
	},
	addMsg : function(){
		if($("#SendTyp").val() == "0"){  //文字状态
			$(".reply_frame img").each(function(index, element) {
				var sTitle = $(element).attr("title");
				if(sTitle){
					$(element).after("<span style='display:none'>[face"+sTitle+"]</span>");
				}
			});
			content = $(".reply_frame").text(); //防止用户复制html到文本框里面
			content = content.replace(/&/g,"|*|");
		} else {
			//图片形式提交获取src
			content = $(".reply_frame").find("img").attr("src");
		}
		if(webchat_inner.sendType){ //为真发送信息
			webchat_inner.sendType = false;
			$.ajax({
				type	: "POST",
				url		: "/kf/admin/ajax.php",
				data	: "type=add&Msg="+content+"&UserId="+webchat_inner.UserId+"&KfId="+webchat_inner.KfId+"&SendTyp="+$("#SendTyp").val()+"&date="+new Date(),
				dataType: "json",
				success	: function(msg){
					if(msg["status"] == 1){
						$(".list_frame").append(msg["MsgList"]);
						webchat_inner.setWindowScrollTop();
						$("#SendTyp").val("0");
						$(".reply_frame").attr("contenteditable","true");
						$(".reply_cancel").hide();
					} else {
						$("#msg"+msg["MId"]+" .message_item").html("添加失败");
					}
				}
			});
		} else {
			alert('信息发送中，请等待');
		}
	},
	setParentNum:function(uid){
		var list = $("#list"+webchat_inner.UserId,window.parent.document);
		list.find(".visitors_num").html(0); //代表已经查看了信息数目
	},
	ctrlFace : function(){ //表情管理
		var tt = null;
		$("#face_icon").click(function(){
			var stau = $(".face_frame").css("display");
			if(stau == "none"){
				frmaeHid(1);
			} else {
				frmaeHid(0);
			}
		});
		$("#face_icon").mouseout(function(){
			tt = setTimeout(function(){
				frmaeHid(0);
			},300);
		});
		$(".face_frame").mouseleave(function(){
			frmaeHid(0);
		});
		$(".face_frame").mouseover(function(){
            clearTimeout(tt);
        });
		//---- 点击事件 ------//
		$(".face_frame li").click(function(){
			var img=$(this).find("img").attr("src");
			var num=$(this).attr("num");  
			$(".reply_frame").focus();
			var img_url = "<img title=\""+num+"\" src='"+img+"' />";
			if($("#SendTyp").val() == "0"){
				_insertimg(img_url);
			}
			frmaeHid(0);
		});
		//---- 闭包函数管理表情及光标位置获取start ------//
		function frmaeHid(num)
		{
			if(num == 1)
			{
				$(".face_frame").show();
				$(".list_frame embed").css("visibility","hidden");
			} else {
				$(".face_frame").hide();
				$(".list_frame embed").css("visibility","visible");
			}
		}
		function _insertimg(str)
		{
			var selection= window.getSelection ? window.getSelection() : document.selection;
			var range= selection.createRange ? selection.createRange() : selection.getRangeAt(0);
			if (!window.getSelection) //ie
			{
				var oFrame = $(".reply_frame").get(0);
				oFrame.focus();
				var selection= window.getSelection ? window.getSelection() : document.selection;
				var range= selection.createRange ? selection.createRange() : selection.getRangeAt(0);
				range.pasteHTML(str);
				range.collapse(false);
				range.select();
			} else {
				var oFrame = $(".reply_frame").get(0);
				oFrame.focus();
				range.collapse(false);
				var hasR = range.createContextualFragment(str);
				var hasR_lastChild = hasR.lastChild;
				while (hasR_lastChild && hasR_lastChild.nodeName.toLowerCase() == "br" && hasR_lastChild.previousSibling && hasR_lastChild.previousSibling.nodeName.toLowerCase() == "br") 
				{
					var e = hasR_lastChild;
					hasR_lastChild = hasR_lastChild.previousSibling;
					hasR.removeChild(e)
				}                                
				range.insertNode(hasR);
				if (hasR_lastChild)
				{
					range.setEndAfter(hasR_lastChild);
					range.setStartAfter(hasR_lastChild)
				}
				selection.removeAllRanges();
				selection.addRange(range)
			}
		}
		//---- 闭包函数管理表情及光标位置获取end ------//
	},
	setWindowScrollTop : function(){ //设置浏览器高度	
		$(".list_frame").scrollTop(1000000);
	}
}