<?php
session_start();
header("Cache-Control: no-cache");  
header("Pragma: no-cache");
header("Expires: ".gmdate("D, d M Y H:i:s",time())." GMT");
header('Content-Type:text/html; charset=utf-8');
require_once('Ext/mysql.inc.php');
require_once('dbconfig.php');
require_once('eloquent.php');

define('CMS_ROOT', $_SERVER["DOCUMENT_ROOT"]);

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

function input_check($parameter){
	$parameter = trim($parameter);
	$parameter = strip_tags($parameter,"");
	$parameter = str_replace("\n", "", str_replace(" ", "", $parameter));
	$parameter = str_replace("\t","",$parameter);
	$parameter = str_replace("\r\n","",$parameter);
	$parameter = str_replace("\r","",$parameter);
	$parameter = str_replace("'","",$parameter);
	$parameter = trim($parameter); 
	return $parameter;
}

function myerror($error_level,$error_message,$error_file,$error_line,$error_context){
	$E_Name=array(
	"2"=>"E_WARNING 非致命的 run-time 错误。不暂停脚本执行。",
	"8"=>"E_NOTICE Run-time 通知。脚本发现可能有错误发生，但也可能在脚本正常运行时发生。",
	"256"=>"E_USER_ERROR 致命的用户生成的错误。这类似于程序员使用 PHP 函数 trigger_error() 设置的 E_ERROR。",
	"512"=>"E_USER_WARNING 非致命的用户生成的警告。这类似于程序员使用 PHP 函数 trigger_error() 设置的 E_WARNING。",
	"1024"=>"E_USER_NOTICE 用户生成的通知。这类似于程序员使用 PHP 函数 trigger_error() 设置的 E_NOTICE。 ",
	"4096"=>"E_RECOVERABLE_ERROR 可捕获的致命错误。类似 E_ERROR，但可被用户定义的处理程序捕获。(参见 set_error_handler()) ",
	"8191"=>"E_ALL 所有错误和警告，除级别 E_STRICT 以外。（在 PHP 6.0，E_STRICT 是 E_ALL 的一部分）"
	);
	echo '<fieldset class="errlog">
	<legend>错误信息提示</legend>
	<label class="tip">错误事件：'.$E_Name[$error_level].'</label><br>
	<label class="tip">'.$error_file.'错误行数：第<font color="red">'.$error_line.'</font>行</label><br>
	<label class="msg">错误原因：'.$error_message.'</label>
	</fieldset>';
	exit();
	foreach($error_context as $keyword=>$value){
		echo '<fieldset>
		<legend>'.$keyword.'</legend>
		<label>';
		if(is_array($value)){
			foreach($value as $key=>$val){
				echo $key."=>".$val."<br>";
			}
		}elseif(is_object($value)){
			foreach((array)$value as $key=>$val){
				echo $key."=>".$val."<br>";
			}
		}else{
			echo $value;
		}
		echo '</label>
		</fieldset>';
	}
}

set_error_handler('myerror',E_ALL);
require_once('ad.php');
?>