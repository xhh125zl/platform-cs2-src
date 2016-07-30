<?php 
require_once($_SERVER["DOCUMENT_ROOT"].'/include/update/common.php');

if(IS_POST){ 
    $post  =  $_POST;
    $data  = [];
    $return_uri = $_SERVER['HTTP_REFERER'];
    $data['ListConfig'] = $post['toplist'];
    $data['IndexConfig'] = $post['Indexlist'];
    $data['Status'] = 1;
    $ID = $_POST["ID"];
    $flag = $DB->Set("biz_active", $data,"WHERE Users_ID='{$post['UsersID']}' AND Biz_ID={$post['BizID']} AND ID={$ID}");
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
         <link href="http://libs.baidu.com/bootstrap/3.0.3/css/bootstrap.min.css" rel="stylesheet">
        <link href='/static/api/active/bootstrap-duallistbox.min.css' rel='stylesheet' type='text/css' />
        <script type='text/javascript' src='/static/api/active/bootstrap.min.js'></script>
        <script type='text/javascript' src='/static/api/active/jquery.bootstrap-duallistbox.min.js'></script>
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
        });
        
        function handle(obj,ptype)
        {
            var $_obj = $(obj);
            var Indexcommit = $("select[name='Indexcommit']");
            var activeCount=<?=$rsActive['IndexBizGoodsCount']?$rsActive['IndexBizGoodsCount']:0 ?>;
            if(ptype=='copy')
            {
                var val = $_obj.parent().parent().find("select").val();
                if(val==null) return ;
                val = val.toString();
                var text = $_obj.parent().parent().find("select > option:selected").text();
                if(val.indexOf(',')==-1){
                    //只选择一个值
                    var t = Indexcommit.find("option").text();
                    var tArr = t.split(' ');
                    if(tArr.length-1>=activeCount){  
                        alert("超过了推荐首页所设置的最大值 "+activeCount);
                        return ;
                    }
                    if(t.indexOf(text)==-1){
                        Indexcommit.append("<option value='"+val+"'>"+text+"</option>");
                    }
                }else{
                    var arr = text.split(' ');
                    var valarr = val.split(',');
                    var t = Indexcommit.find("option").text();
                    for(var i=0;i<arr.length-1;i++)
                    {
                        var tArr = t.split(' ');
                        if(tArr.length-1>=activeCount){  
                            alert("超过了推荐首页所设置的最大值 "+activeCount);
                            break ;
                        }
                        if(t.indexOf(arr[i])==-1){
                            Indexcommit.append("<option value='"+valarr[i]+"'>"+arr[i]+"</option>");
                        }
                    }
                }
            }else if(ptype=='remove'){
                 var text = $_obj.parent().parent().find("select > option").text();
                 var textArr = text.split(' ');
                if(textArr.length-1>1){
                    var findobj = $_obj.parent().parent().find("select >option:selected");
                    findobj.remove();
                }else{
                    
                }
            }
        }
        </script>
    </head>
	<body>
        <div id="iframe_page">
			<div class="iframe_content">
            	<div id="products" class="r_con_wrap">
              	<form id="product_add_form" class="r_con_form skipForm" method="post" action="active_edit.php">
              	    <input type="hidden" name="ID" value="<?=$ID ?>" />
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
                        <div class="box1 col-md-6">
                            
                            <select multiple="multiple" id="bootstrap-duallistbox-nonselected-list_commit" class="form-control" name="commit" style="height: 100px;width:300px;">
                            <?php 
                            if(!empty($list)){ 
                                 foreach ($list as $k=>$v){
                            ?>
                            <option value="<?=$v['Products_ID'] ?>"><?=$v['Products_Name'] ?> </option>
                            <?php }
                            }
                            ?>
                            </select>
                            <div class="btn-group buttons">
                                <button type="button" class="btn moveall btn-default glyphicon glyphicon-arrow-down" title="复制" onclick="handle(this,'copy')">  
                                </button>
                                <button type="button" class="btn move btn-default glyphicon glyphicon-arrow-up" title="删除" onclick="handle(this,'remove')">
                                </button>
                            </div>
                        </div>
                      </span>
                      <div class="clear"></div>
                    </div>
                    <div class="rows">
                      <label>推荐到首页的产品</label>
                      <span class="input">
                        <div class="box1 col-md-6">
                            <div class="btn-group buttons">
                                <button type="button" class="btn moveall btn-default glyphicon glyphicon-arrow-up" title="删除" onclick="handle(this,'remove')">  
                                </button>
                            </div>
                            <select multiple="multiple" id="bootstrap-duallistbox-nonselected-list_commit" class="form-control" name="Indexcommit" style="height: 100px;width:300px;">
                            <?php 
                            if(!empty($rsActive['IndexConfig']) && $rsActive['IndexConfig']){
                      	       $indexList = explode(',', $rsActive['IndexConfig']);
                      	       if(!empty($indexList)){
                      	         foreach ($indexList as $v){
                            ?>
                            <option value="<?=isset($list[$v]['Products_ID'])?$list[$v]['Products_ID']:'' ?>"><?=isset($list[$v]['Products_Name'])?$list[$v]['Products_Name']:'' ?> </option>
                            <?php 
                                 }
                             }
                            }
                            ?>
                            </select>
                        </div>
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