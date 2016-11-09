<?php
require_once "config.inc.php";
require_once(CMS_ROOT . '/include/api/b2cshopconfig.class.php');
require_once CMS_ROOT . '/include/api/product_category.class.php';

//检查用户是否登录
if(empty($BizAccount)){
    header("location:/biz/login.php");
    exit;
}

?>
<!doctype html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0">
    <meta name="app-mobile-web-app-capable" content="yes">
    <title>发布产品</title>
</head>
<link href="../static/user/css/product.css?t=<?php echo time(); ?>" type="text/css" rel="stylesheet">
<link href="../static/user/css/font-awesome.min.css" type="text/css" rel="stylesheet">
<script type="text/javascript" src="../static/user/js/jquery-1.8.3.min.js"></script>
<script type="text/javascript" src="../static/user/js/layer.js"></script>
<script  type="text/javascript"  src="../static/user/js/jquery.uploadView.js"></script>
<script  type="text/javascript"  src="../static/user/js/jquery.uploadView1.js"></script>
<script  type="text/javascript"  src="../static/user/js/product.js?t=<?php echo time(); ?>"></script>
<style type="text/css">
    .btn-upload {width: 43px;height: 43px;position: relative;float:left; border:1px #999 dashed; margin-left: 10px; }
    .btn-upload a {display: block;width: 43px;line-height: 43px;text-align: center;color: #4c4c4c;background: #fff;}
    .btn-upload input {width: 43px;height: 43px;position: absolute;left: 0px;top: 0px;z-index: -1;filter: alpha(opacity=0);-moz-opacity: 0;opacity: 0;cursor: pointer;}
    .deleted{cursor: pointer;width: 45px;display: block;height: 20px;line-height: 20px;text-align: center;position: relative;background: #000;color: #fff;font-size: 12px;filter: alpha(opacity=50);-moz-opacity: 0.5;-khtml-opacity: 0.5;opacity: 0.5;margin-top: -20px;}
    .deleted1{cursor: pointer;width: 45px;display: block;height: 20px;line-height: 20px;text-align: center;position: relative;background: #000;color: #fff;font-size: 12px;filter: alpha(opacity=50);-moz-opacity: 0.5;-khtml-opacity: 0.5;opacity: 0.5;margin-top: -20px;}
    .notNull { color: red; }
</style>
<body>
<?php
//检查用户是否已经交过费用
$users = b2cshopconfig::getConfig(['Users_Account' => $BizAccount]);
if ($users['errorCode'] == 0) {
    $rsBiz = b2cshopconfig::getVerifyconfig(['Biz_Account' => $BizAccount]);
    if (empty($rsBiz['bizData'])) {
        echo '<script>layer.open({content: "商家不存在", shadeClose: false, btn: "确定", yes: function(){history.back();});</script>';
        exit;
    }
    $bizData = $rsBiz['bizData'];
    if ($bizData['is_agree'] !=1 || $bizData['is_auth'] !=2 || $bizData['is_biz'] !=1) {
        echo '<script>layer.open({ content: "您尚未通过商家认证,不能当供货商<br>请进行商家认证后再进行此项操作!", shadeClose: false, btn: "确认", yes: function(){history.back();} });</script>';
        exit;
    }
}else{
    echo '<script>layer.open({content: "服务器网络异常,数据通信失败", shadeClose: false, btn: "确定", yes: function(){history.back();}});</script>';
    exit;
}

//加载分类
require_once 'lib/product_category.php';

?>
<div class="w">
    <div class="back_x">
        <a class="l" href="?act=store">&nbsp;取消</a><h3>发布产品</h3>
    </div>
    <input type="hidden" name="Products_Id" value="">
    <div class="name_pro">
        <input type="text" name="Products_Name" value="" placeholder="请输入商品名称" maxlength="80">
        <div class="img_add">
            <div class="js_uploadBox">
                <div class="js_showBox"></div>
                <div class="btn-upload">
                    <a href="javascript:void(0);" id="add_img">+</a>
                    <input class="js_upFile" type="file" name="cover">
                </div>
                <!--image_files显示base64编码过的字符串,image_path存放所有的图片路径-->
                <input type="hidden" id="image_files" value="">
                <input type="hidden" name="image_path" value=""/>
            </div>
        </div>
        <div class="note">商品封面（最少一张，最多三张）</div>
    </div>
    <div class="name_pro">
        <!-- <textarea name="BriefDescription" style="margin-left: 2%; width: 95%;height: 100px;line-height: 25px;border: none;" placeholder="请输入商品简介"></textarea> -->
        <textarea name="Description" style="margin-left: 2%; width: 95%;height: 100px;line-height: 20px;border: none;" placeholder="请输入商品详细介绍"></textarea>
        <div class="img_add">
            <div class="js_uploadBox1">
                <div class="js_showBox1"></div>
                <div class="btn-upload">
                    <a href="javascript:void(0);" id="add_img1">+</a>
                    <input class="js_upFile1" type="file" name="cover">
                </div>
                <!--image_files显示base64编码过的字符串,image_path存放所有的图片路径-->
                <input type="hidden" id="image_files1" value="">
                <input type="hidden" name="image_path1" value=""/>
            </div>
        </div>
    </div>
    <div class="list_table">
        <table width="96%" class="table_x">
            <tr>
                <th><span class="notNull">*</span>产品类型：</th>
                <td>
                    <select name="TypeID">
                        <option value="">选择产品类型</option>
                        <?php
                            if (!empty($Products_Type)) {
                                foreach ($Products_Type as $v) {
                                    echo '<option value="' . $v['ProductType_FromID'] . '">' . $v['Type_Name'] . '</option>';
                                }
                            }
                        ?>
                        <option value="0">其他</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th><span class="notNull">*</span>订单流程：</th>
                <td style="line-height: 35px;">
                    <input type="radio" name="ordertype" value="0" checked="checked" id="type0"><label for="type0">&nbsp;实物</label>&nbsp;&nbsp;
                    <input type="radio" name="ordertype" value="1" id="type1"><label for="type1">&nbsp;虚拟</label>&nbsp;&nbsp;
                    <input type="radio" name="ordertype" value="2" id="type2"><label for="type2">&nbsp;卡密</label>
                    <span style=" float:right;" id="ordertype">说明&nbsp;<i class="fa  fa-angle-right fa-x" aria-hidden="true" style="font-size:20px;"></i></span>
                </td>
            </tr>
            <tr>
                <th><span class="notNull">*</span>原价(￥)：</th>
                <td><input type="number" name="PriceY" maxlength="10" class="user_input" value="" placeholder="请输入商品原价"></td>
            </tr>
            <tr>
                <th><span class="notNull">*</span>现价(￥)：</th>
                <td><input type="number" name="PriceX" maxlength="10" class="user_input" value="" placeholder="请输入商品现价"></td>
            </tr>
            <tr style="display:none;">
                <th>是否推荐　<br/>到批发商城：</th>
                <td><input class="toggle-switch" type="checkbox" name="is_Tj" checked=""></td>
            </tr>
            <tr class="is_Tj">
                <th><span class="notNull">*</span>供货价(￥)：</th>
                <td><input type="number" name="PriceS" maxlength="10" class="user_input" value="" placeholder="请输入商品供货价"></td>
            </tr>
            <tr class="is_Tj">
                <th><span class="notNull">*</span>所属分类：</th>
                <td id="test1"><span id="b2c_category" firstCate="" secondCate=""></span><span class="right">选择分类&nbsp;<i class="fa  fa-angle-right fa-x" aria-hidden="true" style="font-size:20px;"></i></span></td>
            </tr>
            <tr>
                <th><span class="notNull">*</span>产品利润：</th>
                <td><input type="number" name="Products_Profit" maxlength="4" class="user_input" value="" placeholder="佣金将按照产品利润发放" /></td>
            </tr>
            <tr>
                <th><span class="notNull">*</span>购买送积分：</th>
                <td><input type="number" name="Products_Integration" maxlength="6" class="user_input" value="" placeholder="请输入送积分数" /></td>
            </tr>
            <tr>
                <th><span class="notNull">*</span>产品重量：</th>
                <td><input type="number" name="Products_Weight" maxlength="6" class="user_input" value="" placeholder="产品重量,单位为kg" /> </td>
            </tr>
            <tr>
                <th><span class="notNull">*</span>库存(件)：</th>
                <td><input type="number" name="count" maxlength="6" class="user_input" value="" placeholder="请输入商品库存"></td>
            </tr>
            <tr>
                <th><span class="notNull">*</span>选择运费：</th>
                <td>
                    <input type="radio" value="1" name="freeshipping" id="freeshipping1" checked="checked" /><label for="freeshipping1">&nbsp;免运费</label>&nbsp;&nbsp;
                    <input type="radio" value="0" name="freeshipping" id="freeshipping0"/><label for="freeshipping0">&nbsp;运费模板</label>
                </td>
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
                <td id="test2"><span id="category" firstCate="" secondCate=""></span><span class="right">选择分类&nbsp;<i class="fa  fa-angle-right fa-x" aria-hidden="true" style="font-size:20px;"></i></span></td>
            </tr>
            <tr>
                <th>是否置顶：</th>
                <td><input class="toggle-switch" type="checkbox" name="IsHot" checked=""></td>
            </tr>
            <tr>
                <th>是否推荐：</th>
                <td><input class="toggle-switch" type="checkbox" name="IsRecommend" checked=""></td>
            </tr>
            <tr>
                <th>是否新品：</th>
                <td><input class="toggle-switch" type="checkbox" name="IsNew" checked=""></td>
            </tr>
            <tr>
                <th>余额支付：</th>
                <td><input class="toggle-switch" type="checkbox" name="IsPaysBalance" checked=""></td>
            </tr>
            <tr>
                <th>是否显示：</th>
                <td><span style="line-height:38px;">注：门槛商品是否在商城显示</span><input class="toggle-switch" type="checkbox" name="IsShow" checked=""></td>
            </tr>
        </table>
    </div>
    <div class="clear"></div>
    <div class="kb"></div>
    <div class="bottom">
        <div class="pro_foot">
            <a>上架产品</a>
        </div>
    </div>
</div>
</body>
</html>
