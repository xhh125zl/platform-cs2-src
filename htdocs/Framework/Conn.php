<?php
session_start();
header("Cache-Control: no-cache");  
header("Pragma: no-cache");
header("Expires: ".gmdate("D, d M Y H:i:s",time())." GMT");
header('Content-Type:text/html; charset=utf-8');

define('CMS_ROOT', $_SERVER["DOCUMENT_ROOT"]);

require_once(CMS_ROOT.'/include/helper/global_func.php');
require_once('Ext/mysql.inc.php');
require_once('dbconfig.php');
require_once('eloquent.php');

global $DB1;
$DB1=$DB=new mysql($host,$user,$pass,$data,$code="utf8",$conn="conn");

$setting = $DB->GetRs("setting","*","where id=1");

$SiteName = $setting["sys_name"];
$Copyright = $setting["sys_copyright"];
$Copyright = str_replace('&quot;','"',$Copyright);
$Copyright = str_replace("&quot;","'",$Copyright);
$Copyright = str_replace('&gt;','>',$Copyright);
$Copyright = str_replace('&lt;','<',$Copyright);
$SiteLogo = $setting["sys_logo"];
$ak_baidu = $setting["sys_baidukey"];
$alipay_partner =  $setting['alipay_partner'];
$alipay_key = $setting['alipay_key'];
$alipay_selleremail = $setting['alipay_selleremail'];

set_error_handler('myerror',E_ALL);
require_once('ad.php');

//请求过滤  因接口问题，暂不启用
/*foreach (array('_COOKIE', '_POST', '_GET') as $_request) {
	if (!empty($$_request)) {

		//直接将数组转换成变量键值对
		foreach ($$_request as $_key => $_value) {
			$_key{0} != '_' && $$_key = daddslashes($_value);
		}

		//数组递归过滤
		$$_request = daddslashes($$_request);

	}
}*/

?>