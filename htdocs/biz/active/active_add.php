<?php 
require_once($_SERVER["DOCUMENT_ROOT"].'/include/update/common.php');

if(IS_POST){ 
    $post  =  $_POST;
    $data  = [];
    $return_uri = "active.php";
    $data['Users_ID'] = $post['UsersID'];
    $data['Active_ID'] = $post['Active_ID'];
    $data['Biz_ID'] = $post['BizID'];
    $data['ListConfig'] = $post['toplist'];
    $data['IndexConfig'] = $post['Indexlist'];
    $data['Status'] = 1;
    $data['addtime'] = time();
    $flag = $DB->Add("biz_active", $data);
    if(true == $flag)
    {
        sendAlert("添加成功","active.php", 2);
    }else{
        sendAlert("添加失败",$return_uri ,2);
    }
}else{
    $Active_ID = isset($_GET['activeid']) && $_GET['activeid']?$_GET['activeid']:0;
    $rsActive = [];
    if($Active_ID){
        $rsActive = $DB->GetRs("active","*","WHERE Users_ID='{$UsersID}' AND Active_ID='{$Active_ID}'");
    }
    $flag = $DB->GetRs("biz_active","*","WHERE Users_ID='{$UsersID}' AND Biz_ID='{$BizID}' AND Active_ID='{$Active_ID}' ");
    if(!$Active_ID){
        sendAlert("所参加的活动不存在");
    }
    if($flag){
        sendAlert("不能重复推荐产品");
    }
}
?>
<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="utf-8" />
        <title>添加活动</title>
        <link href='/static/css/global.css' rel='stylesheet' type='text/css' />
        <link href='/static/member/css/main.css' rel='stylesheet' type='text/css' />
        <script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
        <script type='text/javascript' src='/static/js/plugin/layer/layer.js'></script>
        <script>
        $(document).ready(function(){
            $('#select').click(function(){
                  layer.open({
                      type: 2,
                      area: ['800px', '500px'],
                      fix: false,
                      maxmin: true,
                      content: '/biz/active/product_select.php?activeid='+"<?=$Active_ID ?>"
                  });              
            });
			var count =1;
			var activeCount=<?=$rsActive['IndexBizGoodsCount']?$rsActive['IndexBizGoodsCount']:0 ?>;
            $("select[name='commit']").change(function(){
                if(count>activeCount){
					alert("首页只能推荐"+activeCount+"个");
					$(this).find("option").removeAttr("selected");
					count = 1;
					return false;
                }
                count++;
                var text = $(this).find(":selected").text();
                var arr = text.split(' ');
                var html = '';
                for(var i=0;i<arr.length;i++)
                {
					html+="<li>"+arr[i]+"</li>";
                }
				$("input[name='Indexlist']").val($(this).val());
				$("#IndexCommit").html(html);
             });
        });
        </script>
    </head>
	<body>
        <div id="iframe_page">
			<div class="iframe_content">
            	<div id="products" class="r_con_wrap">
              	<form id="product_add_form" class="r_con_form skipForm" method="post" action="active_add.php">
                    <input type="hidden" name="UsersID" value="<?=$UsersID ?>" />
                    <input type="hidden" name="Active_ID" value="<?=$Active_ID ?>" />
                    <input type="hidden" name="BizID" value="<?=$BizID ?>" />
                    <input type="hidden" name="toplist" value=""/>
                    <input type="hidden" name="Indexlist" value=""/>
                    <div class="rows">
                    	<label>活动名称</label>
                    	<span class="input" style="width:300px;"><?=$rsActive['Active_Name'] ?>&nbsp;&nbsp;&nbsp;&nbsp;
                    	<a href="#" class="btn_green" id="select" style="float: right; margin-right:20px;">选择产品</a>
                    	</span>
                    	<div class="clear"></div>
                    </div>
                    <div class="rows">
                      <label>选择产品</label>
                      <span class="input">
                      	<select multiple="true" name="commit" style="width: 300px;height:100px;">
                      	</select>
                      	（选择要推荐到首页的产品）
                      </span>
                      <div class="clear"></div>
                    </div>
                    <div class="rows">
                      <label>推荐到首页的产品</label>
                      <span class="input">
                      	<ul id="IndexCommit">
                      	</ul>
                      </span>
                      <div class="clear"></div>
                    </div>
                    <div class="rows">
                      <label></label>
                      <span class="input">
                      	  <input type="submit" class="btn_green" name="submit" value="提交保存" />
                      <div class="clear"></div>
                    </div>      
            	</form>
            </div>
          </div>
        </div>
	</body>
</html>