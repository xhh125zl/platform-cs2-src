<?php 
require_once($_SERVER["DOCUMENT_ROOT"].'/include/update/common.php');
if(IS_AJAX && isset($_POST['action']) && $_POST['action']=='getActive'){
    $active_id = $_POST['aid'];
    $UsersID = $_POST['UsersID'];
    if($active_id){
        $rsActive = $DB->GetRs("active","*","WHERE Users_ID='{$UsersID}' AND Active_ID='{$active_id}'");
        if(!empty($rsActive)){
            die(json_encode(['status'=>1,'data'=>$rsActive],JSON_UNESCAPED_UNICODE));
        }
    }
    die(json_encode(['status'=>0]));
}
if(IS_POST){ 
    $post  =  $_POST;
    $active_id = $post['Active_ID'];
    $return_uri = "active.php";
    $rsActive = $DB->GetRs("active","*","WHERE Users_ID='{$post['UsersID']}' AND Active_ID='{$active_id}'");

    $rsActiveBiz = $DB->GetRs("biz_active","count(*) as total","WHERE Users_ID='{$post['UsersID']}'  AND Active_ID='{$active_id}'");
    
    if($rsActiveBiz['total']>$rsActive['MaxBizCount']){
        sendAlert("只允许{$rsActive['MaxBizCount']}个商家参加活动",$return_uri ,2);
    }
    $data  = [];
    
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
    $activelist = [];
    if(!$Active_ID){
        $time = time();
        $sql = "SELECT * FROM active WHERE Status=1 AND starttime<{$time} AND {$time}<stoptime AND Active_ID NOT IN ( SELECT Active_ID FROM biz_active WHERE Users_ID='{$UsersID}' AND Biz_ID='{$BizID}' )  ORDER BY Type_ID ASC";
        $res = $DB->query($sql);
        $activelist = $DB->toArray($res);
        if(empty($activelist)) sendAlert("没有可以参加的活动");
        $rsActive = $activelist [0];
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
        <link href="http://libs.baidu.com/bootstrap/3.0.3/css/bootstrap.min.css" rel="stylesheet">
        <style>
        label { font-weight:900px;font-family:"宋体"}
        </style>
        <script>
        <?php if($Active_ID){ ?>
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
                        Indexcommit.append("<option value='"+val+"'>"+text+" </option>");
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
                            Indexcommit.append("<option value='"+valarr[i]+"'>"+arr[i]+" </option>");
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
        <?php }else{ ?>
        $(document).ready(function(){
            $('#select').click(function(){
                  var Active_ID = $("input[name='Active_ID']").val();
                  layer.open({
                      type: 2,
                      area: ['800px', '500px'],
                      fix: false,
                      maxmin: true,
                      content: '/biz/active/product_select.php?activeid='+Active_ID
                  });              
            });
        });
        
        
        
        $(function(){
            $("select[name='active']").change(function(){
                var aid = $(this).val();
                $.post("/biz/active/active_add.php",{ aid:aid,action:'getActive',UsersID:"<?=$UsersID ?>"},function(data){
                    if(data.status==1){
                        var active = data.data;
                        $("input[name='IndexBizGoodsCount']").val(active.IndexBizGoodsCount);
                        $("input[name='Active_ID']").val(active.Active_ID);
                        $("#active_name").text(active.Active_Name);
                    }
                },"json");
            });
        });
        
        
        function handle(obj,ptype)
        {
            var $_obj = $(obj);
            var Indexcommit = $("select[name='Indexcommit']");
            var activeCount=$("input[name='IndexBizGoodsCount']").val();
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
                        Indexcommit.append("<option value='"+val+"'>"+text+" </option>");
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
                            Indexcommit.append("<option value='"+valarr[i]+"'>"+arr[i]+" </option>");
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
        <?php } ?>
        </script>
    </head>
	<body>
        <div id="iframe_page">
			<div class="iframe_content">
            	<div id="products" class="r_con_wrap">
              	<form id="product_add_form" class="r_con_form skipForm" method="post" action="active_add.php">
                    <input type="hidden" name="UsersID" value="<?=$UsersID ?>" />
                    <input type="hidden" name="Active_ID" value="<?=$Active_ID?:$rsActive['Active_ID'] ?>" />
                    <input type="hidden" name="BizID" value="<?=$BizID ?>" />
                    <input type="hidden" name="toplist" value=""/>
                    <input type="hidden" name="IndexBizGoodsCount" value="<?=$rsActive["IndexBizGoodsCount"]?>"/>
                    <input type="hidden" name="Indexlist" value=""/>
                    <?php if(!$Active_ID){ ?>
                    <div class="rows">
                    	<label>选择活动</label>
                    	<span class="input" style="width:300px;">
                          <?php if(!empty($activelist)){ ?>
                          <select name="active">
                              <?php foreach($activelist as $k=>$v){ ?>
                              <option value="<?=$v['Active_ID'] ?>"><?=$v['Active_Name'] ?></option>
                              <?php } ?>
                          </select>
                          <?php } ?>
                    	</span>
                    	<div class="clear"></div>
                    </div>
                    <?php } ?>
                    <div class="rows">
                    	<label>活动名称</label>
                    	<span class="input" style="width:300px;"><label id="active_name"><?=$rsActive['Active_Name'] ?></label>&nbsp;&nbsp;&nbsp;&nbsp;
                    	<a href="#" class="btn_green" id="select" style="float: right; margin-right:20px;">选择产品</a>
                    	</span>
                    	<div class="clear"></div>
                    </div>
                    <div class="rows">
                      <label>显示在列表页的产品</label>
                      <span class="input">
                          <div class="box1 col-md-6">
                              <select multiple="multiple" id="bootstrap-duallistbox-nonselected-list_commit" class="form-control" name="commit" style="height: 100px;width:300px;">

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