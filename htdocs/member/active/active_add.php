<?php 
require_once($_SERVER["DOCUMENT_ROOT"].'/include/update/common.php');

if(IS_POST){ 
    $post  =  $_POST;
    $data  = [];
    $return_uri = "active_add.php";
    if($post['starttime']>=$post['stoptime']){
        sendAlert("开始时间不能大于结束时间",$return_uri, 2);
    }
    if(empty($post['Active_Name']) || !$post['Active_Name']){
        sendAlert("活动名称不能为空!",$return_uri, 2);
    }
    $rsActive = $DB->GetRs("active","*","WHERE Users_ID='{$UsersID}' AND Active_Name='{$post['Active_Name']}' ");
    if($rsActive){
        sendAlert("活动名称不能重复", $return_uri, 2);
    }
    $data['Users_ID'] = $UsersID;
    $data['Active_Name'] = $post['Active_Name'];
    $data['MaxGoodsCount'] = intval($post['MaxGoodsCount']);
    $data['MaxBizCount'] = intval($post['MaxBizCount']);
    $data['BizGoodsCount'] = intval($post['BizGoodsCount']);
    $data['IndexBizGoodsCount'] = intval($post['IndexBizGoodsCount']);
    $data['IndexShowGoodsCount'] = intval($post['IndexShowGoodsCount']);
    $data['ListShowGoodsCount'] = intval($post['ListShowGoodsCount']);
    $data['BizShowGoodsCount'] = intval($post['BizShowGoodsCount']);
    $data['starttime'] = strtotime($post['starttime']);
    $data['stoptime'] = strtotime($post['stoptime']) + 86398;
    $data['Type_ID'] = $post['ActiveType'];
    $data['addtime'] = time();
    if (isset($post['imgurl']) && $post['imgurl']) {
        $data['imgurl'] = $post['imgurl'];
    }
    $data['Status'] = $post['Status'];
    $flag = $DB->Add("active", $data);
    if(true == $flag)
    {
        sendAlert("添加成功","active_list.php", 2);
    }else{
        sendAlert("添加失败",$return_uri ,2);
    }
}else{
    $Starttime = strtotime(date("Y-m-d")." 00:00:00");
    $Stoptime = strtotime(date("Y-m-d")." 23:59:59")+3*86400;
    $Starttime = date('Y-m-d',$Starttime);
    $Stoptime = date('Y-m-d',$Stoptime);
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
        <script type='text/javascript' src='/static/member/js/global.js'></script>
        <link rel="stylesheet" href="/third_party/kindeditor/themes/default/default.css" />
		<script type='text/javascript' src="/third_party/kindeditor/kindeditor-min.js"></script>
		<script type='text/javascript' src="/third_party/kindeditor/lang/zh_CN.js"></script>
        <script type='text/javascript' src='/static/member/js/shop.js'></script>
        <script type='text/javascript' src='/static/js/plugin/layer/layer.js'></script>
		<link href='/static/member/css/shop.css' rel='stylesheet' type='text/css' />
        <link href='/static/member/css/user.css' rel='stylesheet' type='text/css' />
        <script type='text/javascript' src='/static/member/js/user.js'></script>
        <link href='/static/js/plugin/lean-modal/style.css' rel='stylesheet' type='text/css' />
        <script type='text/javascript' src='/static/js/plugin/lean-modal/lean-modal.min.js'></script>
        <link href='/static/js/plugin/operamasks/operamasks-ui.css' rel='stylesheet' type='text/css' />
        <script type='text/javascript' src='/static/js/plugin/operamasks/operamasks-ui.min.js'></script>
        <script type='text/javascript' src='/static/js/plugin/daterangepicker/moment_min.js'></script>
        <link href='/static/js/plugin/daterangepicker/daterangepicker.css' rel='stylesheet' type='text/css' />
        <script type='text/javascript' src='/static/js/plugin/daterangepicker/daterangepicker.js'></script> 
        <script src="/static/js/plugin/laydate/laydate.js"></script>
        <script>
            var Browser = new Object(); 
            KindEditor.ready(function(K) {
              K.create('textarea[name="Description"]', {
                themeType : 'simple',
                filterMode : false,
                uploadJson : '/member/upload_json.php?TableField=web_column&Users_ID=<?=$UsersID?>',
                fileManagerJson : '/member/file_manager_json.php',
                allowFileManager : false,
                
              });
              var editor = K.editor({
                uploadJson : '/member/upload_json.php?TableField=web_article',
                fileManagerJson : '/member/file_manager_json.php',
                showRemote : true,
                allowFileManager : true,
              });
              K('#ImgUpload').click(function(){
                editor.loadPlugin('image', function() {
                  editor.plugin.imageDialog({
                    clickFn : function(url, title, width, height, border, align) {
                      K('#PicDetail').html('<div><a href="'+url+'" target="_blank"><img src="'+url+'" /></a><a onclick="return imagedel(this);"><span>删除</span></a><input type="hidden" name="imgurl" value="'+url+'" /></div>');
                      editor.hideDialog();
                    }
                  });
                });
              });
              K('#PicDetail div span').click(function(){
                K(this).parent().remove();
              });
            });
        </script>
    </head>
	<body>
        <div id="iframe_page">
			<div class="iframe_content">
            	<div id="products" class="r_con_wrap">
              	<form id="product_add_form" class="r_con_form skipForm" method="post" action="active_add.php">
                    <div class="rows">
                      <label>活动名称</label>
                      <span class="input price">
                      <input type="text" name="Active_Name" value="" class="form_input" size="5" style="width: 200px;float: left;margin-right:40px;" maxlength="10" notnull />
                      </span>
                      <div class="clear"></div>
                    </div>
                    <div class="rows">
                      <div class="rows">
                      <label>产品图片</label>
                      <span class="input"> <span class="upload_file">
                      <div>
                        <div class="up_input">
                          <input type="button" id="ImgUpload" value="添加图片" style="width:80px;" notnull />
                        </div>
                        <div class="tips">上传活动封面图</div>
                        <div class="clear"></div>
                      </div>
                      </span>
                      <div class="img" id="PicDetail">
                      </div>
                      </span>
                      <div class="clear"></div>
                    </div>
                    <div class="rows">
                      <label>活动类型</label>
                      <span class="input">
					  <select name="ActiveType">
					  	 <?php foreach ($ActiveType as $k => $v) {?>
					  	 <option value="<?=$k ?>" <?=$k==0?'selected':'' ?>><?=$ActiveType[$k] ?></option>
					  	 <?php }?>
					  </select>
                      </span>
                      <div class="clear"></div>
                    </div> 
                    <div class="rows">
                      <label>商家数</label>
                      <span class="input">
                      <input type="text" name="MaxBizCount" value="100" class="form_input" size="5" maxlength="10" /> <span class="tips" />&nbsp;（允许多少个商家参加活动）</span>
                      </span>
                      <div class="clear"></div>
                    </div>
                    <div class="rows">
                      <label>最多产品数</label>
                      <span class="input">
                      <input type="text" name="MaxGoodsCount" value="20" class="form_input" size="5" maxlength="100" /> <span class="tips" />&nbsp;</span>
                      	（拼团活动总共可以参加的产品数量）
                      </span>
                      <div class="clear"></div>
                    </div>
                    <div class="rows">
                      <label>推荐产品数</label>
                      <span class="input">
                      <input type="text" name="BizGoodsCount" value="10" class="form_input" size="5" maxlength="100" /> <span class="tips" />&nbsp;</span>
                      	（每个商家最多可推荐的产品数量）
                      </span>
                      <div class="clear"></div>
                    </div>
                    <div class="rows">
                      <label>推荐产品数</label>
                      <span class="input">
                      <input type="text" name="IndexBizGoodsCount" value="1" class="form_input" size="5" maxlength="100" /> <span class="tips" />&nbsp;</span>
                      	（每个商家可以推荐到首页的产品数量）
                      </span>
                      <div class="clear"></div>
                    </div>
                    <div class="rows">
                      <label>首页显示产品数</label>
                      <span class="input">
                      <input type="text" name="IndexShowGoodsCount" value="20" class="form_input" size="5" maxlength="100" /> <span class="tips" />&nbsp;</span>
                      	（活动首页可以显示的产品的数量）
                      </span>
                      <div class="clear"></div>
                    </div>
                    <div class="rows">
                      <label>列表页显示产品数</label>
                      <span class="input">
                      <input type="text" name="ListShowGoodsCount" value="20" class="form_input" size="5" maxlength="100" /> <span class="tips" />&nbsp;</span>
                      	（活动列表页可以显示的产品的数量）
                      </span>
                      <div class="clear"></div>
                    </div>
                    <div class="rows">
                      <label>商家店铺页显示产品数</label>
                      <span class="input">
                      <input type="text" name="BizShowGoodsCount" value="20" class="form_input" size="5" maxlength="100" /> <span class="tips" />&nbsp;</span>
                      	（商家店铺页可以显示的产品的数量）
                      </span>
                      <div class="clear"></div>
                    </div>
                    <div class="rows">
                          <label>参与活动时间</label>
                       	  <span class="input time">
                          <div class="l">
                              <div class="form-group">
                                  <div class="input-group" id="reportrange" style="width:auto">
                                  		<input placeholder="开始时间" class="laydate-icon" name="starttime" value="<?=$Starttime ?>" onclick="laydate()">-
                                  		<input placeholder="截止时间" class="laydate-icon" name="stoptime" value="<?=$Stoptime ?>" onclick="laydate()">
                                  </div>
                              </div>
                          </div>
                          <div class="clear"></div>
                    </div>
                    <div class="rows">
                      <label>活动状态</label>
                      <span class="input" style="font-size:12px;">
                          <input type="radio" id="status_0" value="0" name="Status" /><label for="status_0"> 关闭 </label>&nbsp;&nbsp;
                          <input type="radio" id="status_1" value="1" name="Status" checked /><label for="status_1"> 开启 </label>
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
                  