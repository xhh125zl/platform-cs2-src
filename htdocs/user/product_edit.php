<?php
require_once "/config.inc.php";
require_once(CMS_ROOT . '/include/api/product.class.php');
require_once(CMS_ROOT . '/include/api/b2cshopconfig.class.php');

//检查用户是否登录
if(empty($BizAccount)){
    header("location:/user/login.php");
}

?>
<!doctype html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0">
    <meta name="app-mobile-web-app-capable" content="yes">
    <title>编辑产品</title>
</head>
<link href="../static/user/css/product.css" type="text/css" rel="stylesheet">
<link href="../static/user/css/font-awesome.min.css" type="text/css" rel="stylesheet">
<script type="text/javascript" src="../static/user/js/jquery-1.8.3.min.js"></script>
<script type="text/javascript" src="../static/user/js/layer.js"></script>
<script  type="text/javascript"  src="../static/user/js/jquery.uploadView.js"></script>
<script  type="text/javascript"  src="../static/user/js/jquery.uploadView1.js"></script>
<script  type="text/javascript"  src="../static/user/js/product.js"></script>
<style type="text/css">
    .btn-upload {width: 43px;height: 43px;position: relative; float: left; border:1px #999 dashed; margin-left: 10px; }
    .btn-upload a {display: block;width: 43px;line-height: 43px;text-align: center;color: #4c4c4c;background: #fff;}
    .btn-upload input {width: 43px;height: 43px;position: absolute;left: 0px;top: 0px;z-index: -1;filter: alpha(opacity=0);-moz-opacity: 0;opacity: 0;cursor: pointer;}
    .deleted{cursor: pointer;width: 45px;display: block;height: 20px;line-height: 20px;text-align: center;position: relative;background: #000;color: #fff;font-size: 12px;filter: alpha(opacity=50);-moz-opacity: 0.5;-khtml-opacity: 0.5;opacity: 0.5;margin-top: -20px;}
    .deleted1{cursor: pointer;width: 45px;display: block;height: 20px;line-height: 20px;text-align: center;position: relative;background: #000;color: #fff;font-size: 12px;filter: alpha(opacity=50);-moz-opacity: 0.5;-khtml-opacity: 0.5;opacity: 0.5;margin-top: -20px;}
    .notNull { color: red; }
</style>
<body>
<?php
//检查用户是否已经交过费用
$users = b2cshopconfig::getConfig(array('Users_Account' => $BizAccount));
if ($users['errorCode'] == 0) {
    $bizData = $users['configData'];
    if ($bizData['expiresTime'] != 0 && $bizData['expiresTime'] < time()) {
        if ($bizData['need_charg'] == 1) {
            echo '<script>layer.open({content: "此项功能已到期,必须先交费才可以使用", shadeClose: false, btn: "确定", yes: function(){history.back();}});</script>';
            exit();
        }
    }
    $rsBiz = b2cshopconfig::getVerifyconfig(['Biz_Account' => $BizAccount]);
    if (empty($rsBiz['bizData'])) {
        echo '<script>layer.open({content: "商家不存在", shadeClose: false, btn: "确定", yes: function(){history.back();}});</script>';
        exit;
    }
} else {
    echo '<script>layer.open({content: "服务器网络异常,数据通信失败", shadeClose: false, btn: "确定", yes: function(){history.back();}});</script>';
    exit;
}

//获取商品数据
$product_id = 0;
if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];
} else {
    echo '<script>layer.open({content: "商品id获取失败", shadeClose: false, btn: "确定", yes: function(){history.back();}});</script>';
}

$postdata['Biz_Account'] = $BizAccount;
$postdata['Products_ID'] = $product_id;
$postdata['is_Tj'] = 1;
$resArr = product::getProductArr($postdata);
//检测是否有数据
if ($resArr['errorCode'] != 0 || empty($resArr['data']['Products_ID'])) {
    echo '<script>layer.open({content: "商品不存在，或获取数据失败", shadeClose: false, btn: "确定", yes: function(){history.back();}});</script>';
}
$productData = $resArr['data'];     //获取的产品参数
if (isset($productData['Products_FromId']) && $productData['Products_FromId'] > 0) {
    echo '<script>layer.open({content: "分销商品不能编辑", shadeClose: false, btn: "确定", yes: function(){history.back();}});</script>';
}

function cutstr_html($string,$length=0,$ellipsis='…'){
    $string=strip_tags($string);
    $string=preg_replace('/\n/is','',$string);
    $string=preg_replace('/\r/is',' ',$string);
    $string=preg_replace('/\t/is',"\r",$string);  //tab
    $string=preg_replace('/ |　/is','',$string);
    $string=preg_replace('/&nbsp;/is','',$string);
    preg_match_all("/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/",$string,$string);
    if(is_array($string)&&!empty($string[0])){
        if(is_numeric($length)&&$length){
            $string=implode('',array_slice($string[0],0,$length)).$ellipsis;
        }else{
            $string=implode('',$string[0]);
        }
    }else{
        $string='';
    }
    return $string;
}
//封面图片
$image_path_arr = json_decode($productData['Products_JSON'], true)['ImgPath'];
$images_path = array();
$image_path = '';
foreach ($image_path_arr as $k => $v) {
    if ( strpos($v, IMG_SERVER) !== false ) {
        if (substr(IMG_SERVER, -1) == '/') {
            $image_path .=  '/'.str_replace(IMG_SERVER, '', $v).',';
        } else {
            $image_path .=  str_replace(IMG_SERVER, '', $v).',';
        }
        $images_path[] = $v;
    } else {
        $image_path .= $v.',';
        if (substr(IMG_SERVER, -1) == '/') {
            $images_path[] = IMG_SERVER.ltrim($v, '/');
        } else {
            $images_path[] = IMG_SERVER.$v;
        }
    }
}
$image_path = rtrim($image_path, ',');
//$image_path = implode(',', $image_path_arr);

//商品详情处理  内容及图片
$des_con = htmlspecialchars_decode($productData['Products_Description'], ENT_QUOTES);
//$description = htmlspecialchars(strip_tags($des_con,'<p><a>'));
$description = cutstr_html($des_con);
preg_match_all('/<img (.*?)+src=[\'"](.*?)[\'"]/i', $des_con, $img_arr);
$des_img_path = implode(',', $img_arr[2]);

//推荐到批发商城时的分类
$b2c_firstCateName = '';
$b2c_secondCateName = '';
$firstCateName = '';
$secondCateName = '';
if ($postdata['is_Tj'] == 1 && $productData['is_Tj'] == 1) {
    $b2cCate = $productData['b2cCategory'];
    $b2cCateId = explode(',', $b2cCate['B2CProducts_Category']);
    $b2c_firstCateId = $b2cCateId[0];
    $b2c_secondCateId = $b2cCateId[1];
    foreach ($b2cCate['B2CShop_Category'] as $k => $v) {
        if ($v['Category_ID'] == $b2c_firstCateId) {
            $b2c_firstCateName = $v['Category_Name'];
        }
        if ($v['Category_ID'] == $b2c_secondCateId) {
            $b2c_secondCateName = $v['Category_Name'];
        }
    }
    $b2c_cateName = $b2c_firstCateName.'，'.$b2c_secondCateName;
}

//401商品分类
$cate = explode(',', $productData['Products_Category']);
$firstCateId = $cate[1];
$secondCateId = $cate[2];
foreach ($productData['Category401'] as $k => $v) {
    if ($v['Category_ID'] == $firstCateId) {
        $firstCateName = $v['Category_Name'];
    }
    if ($v['Category_ID'] == $secondCateId) {
        $secondCateName = $v['Category_Name'];
    }
}
$cateName = $firstCateName.'，'.$secondCateName;

?>
<div class="w">
    <div class="back_x">
        <a class="l" href="javascript:self.location=document.referrer;">&nbsp;取消</a><h3>编辑产品</h3>
    </div>
    <input type="hidden" name="Products_ID" value="<?php echo $postdata['Products_ID']; ?>">
    <div class="name_pro">
        <input type="text" name="Products_Name" value="<?php echo $productData['Products_Name']; ?>" placeholder="请输入商品名称" maxlength="80">
        <div class="img_add">
            <div class="js_uploadBox">
                <div class="js_showBox">
                	<?php if (!empty($images_path)) { ?>
                	<?php foreach ($images_path as $k => $v) { ?>
                		<div style="width: 45px;float:left;margin:0 2px">
                			<img src="<?php echo $v; ?>" style="display: block; width: 43px; height: 43px;">
                			<span class="deleted">删除</span>
                		</div>
                	<?php }} ?>
                </div>
                <div class="btn-upload">
                    <a href="javascript:void(0);" id="add_img">+</a>
                    <input class="js_upFile" type="file" name="cover">
                </div>
                <!--image_files显示base64编码过的字符串,image_path存放所有的图片路径-->
                <input type="hidden" id="image_files" value="">
                <input type="hidden" name="image_path" value="<?php if (!empty($image_path)) {echo $image_path;} ?>"/>
            </div>
        </div>
        <div class="note">商品封面（最少一张，最多三张）</div>
    </div>
    <div class="name_pro">
        <!-- <textarea name="BriefDescription" style="margin-left: 2%; width: 95%;height: 100px;line-height: 25px;border: none;" placeholder="请输入商品描述信息"><?php echo $productData['Products_BriefDescription']; ?></textarea> -->
        <textarea name="Description" style="margin-left: 2%; width: 95%;height: 100px;line-height: 20px;border: none;" placeholder="请输入商品详细介绍"><?php echo $description; ?></textarea>
        <div class="img_add">
            <div class="js_uploadBox1">
                <div class="js_showBox1">
                    <?php if (!empty($img_arr[2])) { ?>
                    <?php foreach ($img_arr[2] as $k => $v) { ?>
                        <div style="width: 45px;float:left;margin:0 2px">
                            <img src="<?php echo IMG_SERVER.$v; ?>" style="display: block; width: 43px; height: 43px;">
                            <span class="deleted">删除</span>
                        </div>
                    <?php }} ?>
                </div>
                <div class="btn-upload">
                    <a href="javascript:void(0);" id="add_img1">+</a>
                    <input class="js_upFile1" type="file" name="cover">
                </div>
                <!--image_files显示base64编码过的字符串,image_path存放所有的图片路径-->
                <input type="hidden" id="image_files1" value="">
                <input type="hidden" name="image_path1" value="<?php if (!empty($image_path)) {echo $des_img_path;} ?>"/>
            </div>
        </div>
        <!--<div class="img_add">
            <!--这里写配图的一些代码--
        </div>
        <p>配图（最多6张）</p>-->
    </div>
    <div class="list_table">
        <table width="96%" class="table_x">
            <tr>
                <th><span class="notNull">*</span>原价(￥)：</th>
                <td><input type="number" name="PriceY" class="user_input" value="<?php echo $productData['Products_PriceY']; ?>" placeholder="请输入商品原价"></td>
            </tr>
            <tr>
                <th><span class="notNull">*</span>现价(￥)：</th>
                <td><input type="number" name="PriceX" class="user_input" value="<?php echo $productData['Products_PriceX']; ?>" placeholder="请输入商品现价"></td>
            </tr>
            <input type="hidden" name="isSolding" value="<?php echo $productData['isSolding']; ?>" >
            <tr>
                <th>是否推荐　<br/>到批发商城：</th>
                <td><input class="toggle-switch" type="checkbox" name="is_Tj" <?php if ($productData['is_Tj'] == 1) {echo 'checked="checked"';} else {echo '';} ?>></td>
            </tr>
            <tr class="is_Tj" style="<?php if ($productData['is_Tj'] == 1) {echo 'display:table-row';} else {echo 'display:none;';} ?>">
                <th><span class="notNull">*</span>供货价(￥)：</th>
                <td><input type="number" name="PriceS" class="user_input" value="<?php if ($productData['is_Tj'] == 1) {echo $productData['Products_PriceS'];} ?>" placeholder="请输入商品供货价"></td>
            </tr>
            <tr class="is_Tj" style="<?php if ($productData['is_Tj'] == 1) {echo 'display:table-row';} else {echo 'display:none;';} ?>">
                <th><span class="notNull">*</span>所属分类：</th>
                <td id="test1"><span id="b2c_category" firstCate="<?php if (isset($b2c_firstCateId)) {echo $b2c_firstCateId;} ?>" secondCate="<?php if (isset($b2c_secondCateId)) {echo $b2c_secondCateId;} ?>"><?php if (isset($b2c_cateName)) {echo $b2c_cateName;} ?></span><span class="right">选择分类&nbsp;<i class="fa  fa-angle-right fa-x" aria-hidden="true" style="font-size:20px;"></i></span></td>
            </tr>
            <tr>
                <th><span class="notNull">*</span>产品利润：</th>
                <td><input type="number" name="Products_Profit" class="user_input" value="<?php echo $productData['Products_Profit']; ?>" placeholder="佣金将按照产品利润发放" /></td>
            </tr>
            <tr>
                <th><span class="notNull">*</span>购买送积分：</th>
                <td><input type="number" name="Products_Integration" class="user_input" value="<?php echo $productData['Products_Integration']; ?>" placeholder="请输入送积分数" /></td>
            </tr>
            <tr>
                <th><span class="notNull">*</span>产品重量：</th>
                <td><input type="number" name="Products_Weight" class="user_input" value="<?php echo $productData['Products_Weight']; ?>" placeholder="产品重量,单位为kg" /> </td>
            </tr>
            <tr>
                <th><span class="notNull">*</span>库存(件)：</th>
                <td><input type="number" name="count" class="user_input" value="<?php echo $productData['Products_Count']; ?>" placeholder="请输入商品库存"></td>
            </tr>
            <tr>
                <th><span class="notNull">*</span>选择运费：</th>
                <td><input type="radio" value="1" name="freeshipping" <?php if ($productData['Products_IsShippingFree'] == 1) {echo 'checked="checked"';} else {echo '';} ?> />&nbsp;免运费 &nbsp;&nbsp;<input type="radio" value="0" name="freeshipping" <?php if ($productData['Products_IsShippingFree'] == 0) {echo 'checked="checked"';} else {echo '';} ?> />&nbsp;运费模板 </td>
            </tr>
            <tr style="display: none;">
                <th>产品类型：</th>
                <td>
                    <select>
                        <option value="">01</option>
                        <option value="">02</option>
                    </select>
                </td>
            </tr>
            <tr style="display: none;">
                <th>产品属性：</th>
                <td></td>
            </tr>
            <tr>
                <th>佣金设置：</th>
                <td><span class="right" id="set_commission">设置佣金&nbsp;<i class="fa  fa-angle-right fa-x" aria-hidden="true" style="font-size:20px;"></i></span></td>
            </tr>
            <tr>
                <th><span class="notNull">*</span>所属分类：</th>
                <td id="test2"><span id="category" firstCate="<?php if (isset($firstCateId)) {echo $firstCateId;} ?>" secondCate="<?php if (isset($secondCateId)) {echo $secondCateId;} ?>"><?php if (isset($cateName)) {echo $cateName;} ?></span><span class="right">选择分类&nbsp;<i class="fa  fa-angle-right fa-x" aria-hidden="true" style="font-size:20px;"></i></span></td>
            </tr>
            <tr>
                <th>是否置顶：</th>
                <td><input class="toggle-switch" type="checkbox" name="IsHot" <?php if ($productData['Products_IsHot'] == 1) {echo 'checked="checked"';} else {echo '';} ?> ></td>
            </tr>
            <tr>
                <th>是否推荐：</th>
                <td><input class="toggle-switch" type="checkbox" name="IsRecommend" <?php if ($productData['Products_IsRecommend'] == 1) {echo 'checked="checked"';} else {echo '';} ?> ></td>
            </tr>
            <tr>
                <th>是否新品：</th>
                <td><input class="toggle-switch" type="checkbox" name="IsNew" <?php if ($productData['Products_IsNew'] == 1) {echo 'checked="checked"';} else {echo '';} ?> ></td>
            </tr>
        </table>
    </div>
    <div class="clear"></div>
    <div class="kb"></div>
    <div class="bottom">
        <div class="pro_foot">
            <a>编辑产品</a>
        </div>
    </div>
</div>
<?php
if ($rsBiz['bizData']['is_agree'] !=1 || $rsBiz['bizData']['is_auth'] !=2 || $rsBiz['bizData']['is_biz'] !=1) {
?>
<script language="javascript">
$(function(){
    $('input[name="is_Tj"]').change(function(){
        if ($('input[name="is_Tj"]:checked').val() == 'on') {
            var me = $(this);
            me.prop("checked", false);
            $('.is_Tj').attr('style', 'display:none;');
            layer.open({
                content: "对不起,您尚未进行商家认证<br>请认证后再进行此项操作",
                shadeClose: false,
                btn: '我知道了',
                yes: function(){
                    layer.closeAll();
                    return false;
                }
            });
        }
    })
})
</script>
<?php
}
?>
</body>
</html>