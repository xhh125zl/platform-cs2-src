<?php
require_once (CMS_ROOT . '/include/update/common.php');

if (IS_POST) {
    $post = $_POST;
    $data = [];
    $return_uri = $_SERVER['HTTP_REFERER'];
    if ($post['starttime'] >= $post['stoptime']) {
        sendAlert("开始时间不能大于结束时间", $return_uri, 2);
    }
    if (empty($post['Active_Name']) || ! $post['Active_Name']) {
        sendAlert("活动名称不能为空!", $return_uri, 2);
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
    $type_id = $post['typeid'];
    $flag = $DB->Set("active", $data, "WHERE Users_ID='{$UsersID}' AND Active_ID='{$type_id}' ");
    if (false === $flag) {
        sendAlert("编辑失败", $return_uri, 2);
    } else {
        sendAlert("编辑成功", "active_list.php", 2);
    }
} else {
    $type_id = $_GET['typeid'];
    $rsActive = $DB->GetRs("active", "*", "WHERE Users_ID='{$UsersID}' AND Active_ID='{$type_id}' ");
    if (! $rsActive) {
        sendAlert("不正确的参数传递", "active_list.php", 2);
    }
    $typelist = $DB->Get("active_type", "*", "WHERE Status=1");
    $typelist = $DB->toArray($typelist);
    if (empty($typelist)) {
        sendAlert("请添加活动类型", "type_add.php", 2);
    }
}
?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8" />
<title>修改活动</title>
<link href='/static/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/member/css/main.css' rel='stylesheet'
	type='text/css' />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/member/js/global.js'></script>
<link rel="stylesheet"
	href="/third_party/kindeditor/themes/default/default.css" />
<script type='text/javascript'
	src="/third_party/kindeditor/kindeditor-min.js"></script>
<script type='text/javascript'
	src="/third_party/kindeditor/lang/zh_CN.js"></script>
<script type='text/javascript' src='/static/member/js/shop.js'></script>
<script type='text/javascript' src='/static/js/plugin/layer/layer.js'></script>
<link href='/static/member/css/shop.css' rel='stylesheet'
	type='text/css' />
<link href='/static/member/css/user.css' rel='stylesheet'
	type='text/css' />
<script type='text/javascript' src='/static/member/js/user.js'></script>
<link href='/static/js/plugin/lean-modal/style.css' rel='stylesheet'
	type='text/css' />
<script type='text/javascript'
	src='/static/js/plugin/lean-modal/lean-modal.min.js'></script>
<link href='/static/js/plugin/operamasks/operamasks-ui.css'
	rel='stylesheet' type='text/css' />
<script type='text/javascript'
	src='/static/js/plugin/operamasks/operamasks-ui.min.js'></script>
<script type='text/javascript'
	src='/static/js/plugin/daterangepicker/moment_min.js'></script>
<link href='/static/js/plugin/daterangepicker/daterangepicker.css'
	rel='stylesheet' type='text/css' />
<script type='text/javascript'
	src='/static/js/plugin/daterangepicker/daterangepicker.js'></script>
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
                uploadJson : '/member/upload_json.php?TableField=web_article&Users_ID=<?=$UsersID?>',
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
            
            function imagedel(o) {
                $(o).parent().remove();
                return false;
              }

            function imagedel1(i) {
                $('.imagedel' + i).remove();
                return false;
              }
        </script>
<style>
#PicDetail img {
	width: 100px;
}

.r_con_form .rows .input .error {
	color: #f00;
}
</style>
</head>
<body>
	<div id="iframe_page">
		<div class="iframe_content">
			<div id="products" class="r_con_wrap">
				<form id="product_add_form" class="r_con_form skipForm"
					method="post" action="active_edit.php">
					<input type="hidden" name="typeid" value="<?=$type_id ?>" />
					<div class="rows">
						<label>活动名称</label> <span class="input price"> <input type="text"
							name="Active_Name" value="<?=$rsActive['Active_Name']?>"
							class="form_input" size="5"
							style="width: 200px; float: left; margin-right: 40px;"
							maxlength="10" notnull />
						</span>
						<div class="clear"></div>
					</div>
					<div class="rows">
						<label>活动类型</label> <span class="input"> <select name="ActiveType">
                             <?php foreach ($typelist as $k => $v) {?>
                             <option value="<?=$v['Type_ID'] ?>"
									<?=$rsActive['Type_ID']==$v['Type_ID']?'selected':'' ?>><?=$v['Type_Name'] ?></option>
                             <?php }?>
                          </select>
						</span>
						<div class="clear"></div>
					</div>
					<div class="rows">
						<div class="rows">
							<label>产品图片</label> <span class="input"> <span
								class="upload_file">
									<div>
										<div class="up_input">
											<input type="button" id="ImgUpload" value="添加图片"
												style="width: 80px;" notnull />
										</div>
										<div class="tips">上传活动封面图</div>
										<div class="clear"></div>
									</div>
							</span>
								<div class="img" id="PicDetail">
                      	 <?php if($rsActive['imgurl']){ ?>
                      	 <div>
										<a href="js:void()" target="_blank"> <img
											src="<?=$rsActive['imgurl']?>">
										</a> <a onclick="return imagedel(this);"><span>删除</span></a> <input
											type="hidden" name="imgurl" value="<?=$rsActive['imgurl']?>">
									</div>
                      	 <?php } ?>
                      </div>
							</span>
							<div class="clear"></div>
						</div>
						<div class="rows">
							<label>商家数</label> <span class="input"> <input type="text"
								name="MaxBizCount" value="<?=$rsActive['MaxBizCount']?>"
								class="form_input" size="5" maxlength="10" /> <span class="tips" />&nbsp;（允许多少个商家参加活动）
							</span> </span>
							<div class="clear"></div>
						</div>
						<div class="rows">
							<label>活动最多产品数</label> <span class="input"> <input type="text"
								name="MaxGoodsCount" value="<?=$rsActive['MaxGoodsCount']?>"
								class="form_input" size="5" maxlength="100" /> <span
								class="tips" />&nbsp;
							</span> （拼团活动总共可以参加的产品数量） </span>
							<div class="clear"></div>
						</div>
						<div class="rows">
							<label>商家推荐产品数</label> <span class="input"> <input type="text"
								name="BizGoodsCount" value="<?=$rsActive['BizGoodsCount']?>"
								class="form_input" size="5" maxlength="100" /> <span
								class="tips" />&nbsp;
							</span> （每个商家最多可推荐的产品数量） </span>
							<div class="clear"></div>
						</div>
						<div class="rows">
							<label>推荐首页产品数</label> <span class="input"> <input type="text"
								name="IndexBizGoodsCount"
								value="<?=$rsActive['IndexBizGoodsCount']?>" class="form_input"
								size="5" maxlength="100" /> <span class="tips" />&nbsp;
							</span> （每个商家可以推荐到首页的产品数量） </span>
							<div class="clear"></div>
						</div>
						<div class="rows">
							<label>首页显示产品数</label> <span class="input"> <input type="text"
								name="IndexShowGoodsCount"
								value="<?=$rsActive['IndexShowGoodsCount']?>" class="form_input"
								size="5" maxlength="100" /> <span class="tips" />&nbsp;
							</span> （活动首页可以显示的产品的数量） </span>
							<div class="clear"></div>
						</div>
						<div class="rows">
							<label>列表页显示产品数</label> <span class="input"> <input type="text"
								name="ListShowGoodsCount"
								value="<?=$rsActive['ListShowGoodsCount']?>" class="form_input"
								size="5" maxlength="100" /> <span class="tips" />&nbsp;
							</span> （活动列表页可以显示的产品的数量） </span>
							<div class="clear"></div>
						</div>
						<div class="rows">
							<label>商家店铺页显示产品数</label> <span class="input"> <input type="text"
								name="BizShowGoodsCount"
								value="<?=$rsActive['BizShowGoodsCount']?>" class="form_input"
								size="5" maxlength="100" /> <span class="tips" />&nbsp;
							</span> （商家店铺页可以显示的产品的数量） </span>
							<div class="clear"></div>
						</div>
						<div class="rows">
							<label>参与活动时间</label> <span class="input time">
								<div class="l">
									<div class="form-group">
										<div class="input-group" id="reportrange" style="width: auto">
											<input placeholder="开始时间" class="laydate-icon"
												name="starttime"
												value="<?=date("Y-m-d",$rsActive['starttime'])?>"
												onclick="laydate()">- <input placeholder="截止时间"
												class="laydate-icon" name="stoptime"
												value="<?=date("Y-m-d",$rsActive['stoptime'])?>"
												onclick="laydate()">
										</div>
									</div>
								</div>
								<div class="clear"></div>
						
						</div>
						<div class="rows">
							<label>活动状态</label> <span class="input" style="font-size: 12px;">
                      	<?php if($rsActive['Status']==1){ ?>
                          <input type="radio" id="status_0" value="0"
								name="Status" /><label for="status_0"> 关闭 </label>&nbsp;&nbsp; <input
								type="radio" id="status_1" value="1" name="Status" checked /><label
								for="status_1"> 开启 </label>
                      	<?php }else{ ?>
                      	  <input type="radio" id="status_0" value="0"
								name="Status" checked /><label for="status_0"> 关闭 </label>&nbsp;&nbsp;
								<input type="radio" id="status_1" value="1" name="Status" /><label
								for="status_1"> 开启 </label>
                      	<?php }?>
                      </span>
							<div class="clear"></div>
						</div>
						<div class="rows">
							<label></label> <span class="input"> <input type="submit"
								class="btn_green" name="submit" value="提交保存" />
								<div class="clear"></div>
						
						</div>
				
				</form>
			</div>
		</div>
	</div>
	<script>
        $(function(){
            $("#product_add_form").submit(function(){
            	var MaxBizCount=parseInt($("input[name='MaxBizCount']").val()),
                    MaxGoodsCount=parseInt($("input[name='MaxGoodsCount']").val()),
                    BizGoodsCount=parseInt($("input[name='BizGoodsCount']").val()),
                    IndexBizGoodsCount=parseInt($("input[name='IndexBizGoodsCount']").val()),
                    IndexShowGoodsCount=parseInt($("input[name='IndexShowGoodsCount']").val()),
                    ListShowGoodsCount=parseInt($("input[name='ListShowGoodsCount']").val()),
                    BizShowGoodsCount=parseInt($("input[name='BizShowGoodsCount']").val());

                if(IndexBizGoodsCount>3){
                    $("input[name='IndexBizGoodsCount']").parent().find(".tips").html("推荐首页产品数不能大于3").addClass("error").show();
                    return false;
                }else{
                    $("input[name='IndexBizGoodsCount']").parent().find(".tips").hide();
                }
                if(IndexShowGoodsCount>8){
                    $("input[name='IndexShowGoodsCount']").parent().find(".tips").html("推荐首页产品数不能大于8").addClass("error").show();
                    return false;
                }else{
                    $("input[name='IndexShowGoodsCount']").parent().find(".tips").hide();
                }
                if(MaxGoodsCount<=IndexShowGoodsCount){
                    $("input[name='IndexShowGoodsCount']").parent().find(".tips").html("首页显示产品数要小于活动最多产品数").addClass("error").show();
                    return false;
                }else{
                    $("input[name='IndexShowGoodsCount']").parent().find(".tips").hide();
                }
                if(MaxGoodsCount<=BizGoodsCount){
                    $("input[name='BizGoodsCount']").parent().find(".tips").html("商家推荐产品数要小于活动最多产品数").addClass("error").show();
                    return false;
                }else{
                    $("input[name='BizGoodsCount']").parent().find(".tips").hide();
                }
                if(MaxGoodsCount<=ListShowGoodsCount){
                    $("input[name='ListShowGoodsCount']").parent().find(".tips").html("列表页显示产品数要小于活动最多产品数").addClass("error").show();
                    return false;
                }else{
                    $("input[name='ListShowGoodsCount']").parent().find(".tips").hide();
                }

                if(MaxGoodsCount<=BizShowGoodsCount){
                    $("input[name='BizShowGoodsCount']").parent().find(".tips").html("商家店铺页显示产品数要小于活动最多产品数").addClass("error").show();
                    return false;
                }else{
                    $("input[name='BizShowGoodsCount']").parent().find(".tips").hide();
                }
            });

            $("input[name='BizGoodsCount'],input[name='IndexBizGoodsCount'],input[name='IndexShowGoodsCount'],input[name='ListShowGoodsCount'],input[name='BizShowGoodsCount']").blur(function(){
            	var curvalue = parseInt($(this).val());
                var MaxGoodsCount = parseInt($("input[name='MaxGoodsCount']").val());
                var IndexShowGoodsCount = parseInt($("input[name='IndexShowGoodsCount']").val());
                if(IndexShowGoodsCount>8){
                    $("input[name='IndexShowGoodsCount']").parent().find(".tips").html("推荐首页产品数不能大于8").addClass("error").show();
                    return false;
                }else{
                    $("input[name='IndexShowGoodsCount']").parent().find(".tips").hide();
                }
                if(curvalue>=MaxGoodsCount){
                    var msg = '';
                    if($(this).attr("name")=='BizGoodsCount'){
                        msg = "商家推荐产品数要小于活动最多产品数";
                    }else if($(this).attr("name")=='IndexBizGoodsCount'){
                        msg = "推荐首页产品数要小于活动最多产品数";
                    }else if($(this).attr("name")=='IndexShowGoodsCount'){
                        msg = "首页显示产品数要小于活动最多产品数";
                    }else if($(this).attr("name")=='ListShowGoodsCount'){
                        msg = "列表页显示产品数要小于活动最多产品数";
                    }else if($(this).attr("name")=='BizShowGoodsCount'){
                        msg = "商家店铺页显示产品数要小于活动最多产品数";
                    }
                    $(this).focus();
                    $(this).parent().find(".tips").html(msg).addClass("error").show();
                }else{
                    $(this).parent().find(".tips").hide();
                }
            
            });
        });
       </script>
</body>
</html>
