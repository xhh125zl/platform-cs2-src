<?php 
require_once($_SERVER["DOCUMENT_ROOT"].'/include/update/common.php');

if(IS_POST){ 
    $post  =  $_POST;
    $data  = [];
    $return_uri = $_SERVER['HTTP_REFERER'];
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
        sendAlert("修改成功","active.php", 2);
    }else{
        sendAlert("修改失败",$return_uri ,2);
    }
}else{
    $ID =  isset($_GET['id']) && $_GET['id']?$_GET['id']:0;
    $sql = "SELECT a.Type_ID,a.*,b.* FROM biz_active as b LEFT JOIN active as a ON b.Active_ID=a.Active_ID WHERE b.Users_ID='{$UsersID}' AND b.Biz_ID='{$BizID}' AND b.ID='{$ID}'";
    $result = $DB->query($sql);
    $rsActive = $DB->fetch_assoc($result);
    if(!$rsActive){
        sendAlert("已申请的活动不存在");
    }
    $list = [];
    if($rsActive['ListConfig']){
        if($rsActive['Type_ID']==0){    //拼团
            $table = "pintuan_products";    
        }elseif($rsActive['Type_ID']==1){   //云购
            $table = "cloud_products";
        }else{
            $table = "shop_products";
        }
        $result = $DB->Get($table,"*","WHERE Users_ID='{$UsersID}' AND Biz_ID='{$BizID}' AND Products_ID in ({$rsActive['ListConfig']})");
        if($result){
            while($res = $DB->fetch_assoc($result))
            {
                $list[$res['Products_ID']]=$res;
            }
        }
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
                      content: '/biz/active/product_select.php?activeid='+"<?=$rsActive['Active_ID'] ?>"
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
              	<form id="product_add_form" class="r_con_form skipForm" method="post" action="active_edit.php">
                    <input type="hidden" name="UsersID" value="<?=$UsersID ?>" />
                    <input type="hidden" name="BizID" value="<?=$BizID ?>" />
                    <input type="hidden" name="toplist" value="<?=$rsActive['ListConfig'] ?>"/>
                    <input type="hidden" name="Indexlist" value="<?=$rsActive['IndexConfig'] ?>"/>
                    <div class="rows">
                    	<label>活动名称</label>
                    	<span class="input" style="width:300px;"><?=$rsActive['Active_Name'] ?>&nbsp;&nbsp;&nbsp;&nbsp;
                    	<a href="#" class="btn_green pos" id="select" style="float: right; margin-right:20px;">选择产品</a>
                    	</span>
                    	<div class="clear"></div>
                    </div>
                    <div class="rows">
                      <label>选择产品</label>
                      <span class="input">
                      	<select multiple="true" name="commit" style="width: 300px;height:100px;">
                      	<?php 
                      	if(!empty($list)){ 
                      	     foreach ($list as $k=>$v){
                      	?>
                      	<option value="<?=$v['Products_ID'] ?>"><?=$v['Products_Name'] ?> </option>
                      	<?php }
                      	}
                      	?>
                      	</select>
                      	（选择要推荐到首页的产品会显示在下面）
                      </span>
                      <div class="clear"></div>
                    </div>
                    <div class="rows">
                      <label>推荐到首页的产品</label>
                      <span class="input">
                      	<ul id="IndexCommit">
                      	<?php 
                      	if(!empty($rsActive['IndexConfig']) && $rsActive['IndexConfig']){
                      	    $indexList = explode(',', $rsActive['IndexConfig']);
                      	     foreach ($indexList as $v){
                      	?>
                      	<li><?=$list[$v]['Products_Name'] ?></li>
                      	<?php }
                      	}
                      	?>
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