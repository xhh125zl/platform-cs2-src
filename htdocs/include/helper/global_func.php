<?php
/**
  *	公用函数	
  *
  */

/**
 * 对字符串过滤
 * 
 */
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

/**
 * 在input的value属性中输出字符串
 * @param string $string 
 * return return string
 */
function input_output($string) {
    return empty($string) ? '' : htmlspecialchars($string);
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

/**
 * 转义
 * @param $mixed 数组|字符串
 * @param $force 强制转换
 * @return mixed 数组|字符串
 */
function daddslashes($string, $force = 0) { 
	if (!get_magic_quotes_gpc() || $force) { 
		if (is_array($string)) { 
			foreach ($string as $key => $val) { 
				$string[$key] = daddslashes($val, $force); 
			} 
		} else { 
			$string = addslashes($string); 
		} 
	} 
	return $string; 
}

 
/**
 * 檢測手機號
 * @param string $str
 * @return bool  true false
 */
function is_mobile($mobile) {
	if(preg_match("/^1[34578]{1}\d{9}$/",$mobile)){  
		return true;  
	}else{  
		return false;  
	}  
}

/**
 * 檢測有无特殊符号
 * @param string $str
 * @return bool  true false
 */
function checkChar($str) {
	if(preg_match("/[\'.,:;*?~`!@#$%^&+=)(<>{}]|\]|\[|\/|\\\|\"|\|/",$str)){
		return true;  
	}else{  
		return false;  
	}  
}
/**
 * 直接过滤掉js代码
 * @param string $str
 * @return  
 */
function checkJS($str){
	$str=preg_replace("/<script([^>]*)>(.*)<\/script>/i","<script></script>",$str);
	return $str;
}
/**
 * 过滤掉SQL代码
 * @param string $str
 * @return string
 */
function checkSql($str) {

    $filterString = [
        "select",
        "delete",
        "insert",
        "update",
        "truncate",
        "create",
        "execute",
        "where",
        "between",
        "and",
        "or",
        "count",
        "chr",
        "mid",
        "master",
        
        "char",
        "declare",
        "=",
        "%20",
    ];

    $str = str_ireplace($filterString, "", $str);
    
    return $str;

//    $str = str_replace("and","",$str);
//    $str = str_replace("execute","",$str);
//    $str = str_replace("update","",$str);
//    $str = str_replace("count","",$str);
//    $str = str_replace("chr","",$str);
//    $str = str_replace("mid","",$str);
//    $str = str_replace("master","",$str);
//    $str = str_replace("truncate","",$str);
//    $str = str_replace("char","",$str);
//    $str = str_replace("declare","",$str);
//    $str = str_replace("select","",$str);
//    $str = str_replace("create","",$str);
//    $str = str_replace("delete","",$str);
//    $str = str_replace("insert","",$str);
//    $str = str_replace("'","",$str);
//    $str = str_replace(" ","",$str);
//    $str = str_replace("or","",$str);
//    $str = str_replace("=","",$str);
//    $str = str_replace("%20","",$str);
//    return $str;
} 

/* Remove JS/CSS/IFRAME/FRAME 过滤JS/CSS/IFRAME/FRAME/XSS等恶意攻击代码(可安全使用)
 * Return string
 */
function cleanJsCss($html){
    $html=trim($html);
    $html=preg_replace('/\0+/', '', $html);
    $html=preg_replace('/(\\\\0)+/', '', $html);
    $html=preg_replace('#(&\#*\w+)[\x00-\x20]+;#u',"\\1;",$html);
    $html=preg_replace('#(&\#x*)([0-9A-F]+);*#iu',"\\1\\2;",$html);
    $html=preg_replace("/%u0([a-z0-9]{3})/i", "&#x\\1;", $html);
    $html=preg_replace("/%([a-z0-9]{2})/i", "&#x\\1;", $html);
    $html=str_replace(array('<?','?>'),array('<?','?>'),$html);
    $html=preg_replace('#\t+#',' ',$html);
    $scripts=array('javascript','vbscript','script','applet','alert','document','write','cookie','window');
    foreach($scripts as $script){
        $temp_str="";
        for($i=0;$i<strlen($script);$i++){
            $temp_str.=substr($script,$i,1)."\s*";
        }
        $temp_str=substr($temp_str,0,-3);
        $html=preg_replace('#'.$temp_str.'#s',$script,$html);
        $html=preg_replace('#'.ucfirst($temp_str).'#s',ucfirst($script),$html);
    }
    $html=preg_replace("#<a.+?href=.*?(alert\(|alert&\#40;|javascript\:|window\.|document\.|\.cookie|<script|<xss).*?\>.*?</a>#si", "", $html);
    $html=preg_replace("#<img.+?src=.*?(alert\(|alert&\#40;|javascript\:|window\.|document\.|\.cookie|<script|<xss).*?\>#si", "", $html);
    $html=preg_replace("#<(script|xss).*?\>#si", "<\\1>", $html);
    $html=preg_replace('#(<[^>]*?)(onblur|onchange|onclick|onfocus|onload|onmouseover|onmouseup|onmousedown|onselect|onsubmit|onunload|onkeypress|onkeydown|onkeyup|onresize)[^>]*>#is',"\\1>",$html);
    $html=preg_replace('#<(/*\s*)(alert|applet|basefont|base|behavior|bgsound|blink|body|expression|form|frameset|frame|head|html|ilayer|iframe|input|layer|link|meta|object|plaintext|style|script|textarea|title|xml|xss)([^>]*)>#is', "<\\1\\2\\3>", $html);
    $html=preg_replace('#(alert|cmd|passthru|eval|exec|system|fopen|fsockopen|file|file_get_contents|readfile|unlink)(\s*)\((.*?)\)#si', "\\1\\2(\\3)", $html);
    $bad=array(
        'document.cookie'	=> '',
        'document.write'	=> '',
        'window.location'	=> '',
        "javascript\s*:"	=> '',
        "Redirect\s+302"	=> '',
        '<!--'				=> '<!--',
        '-->'				=> '-->'
    );
    foreach ($bad as $key=>$val){
        $html=preg_replace("#".$key."#i",$val,$html);
    }
    return	cleanFilter($html);
}

//过滤HTML标签
function cleanFilter($html){
    $html=trim($html);
    $html=preg_replace("/<p[^>]*?>/is","",$html);
    $html=preg_replace("/<div[^>]*?>/is","",$html);
    $html=preg_replace("/<ul[^>]*?>/is","",$html);
    $html=preg_replace("/<li[^>]*?>/is","",$html);
    $html=preg_replace("/<span[^>]*?/is","",$html);
    $html=preg_replace("/<video[^>]*?/is","",$html);
    $html=preg_replace("/<script[^>]*?/is","",$html);
    $html=preg_replace("/<style[^>]*?/is","",$html);
    $html=preg_replace("/<select[^>]*?/is","",$html);
    $html=preg_replace("/<iframe[^>]*?/is","",$html);
    $html=preg_replace("/<audio[^>]*?/is","",$html);
    $html=preg_replace("/<object[^>]*?/is","",$html);
    $html=preg_replace("/<a[^>]*?>(.*)?<\/a>/is","",$html);
    $html=preg_replace("/<table[^>]*?>/is","",$html);
    $html=preg_replace("/<tr[^>]*?>/is","",$html);
    $html=preg_replace("/<td[^>]*?>/is","",$html);
    $html=preg_replace("/<ol[^>]*?>/is","",$html);
    $html=preg_replace("/<form[^>]*?>/is","",$html);
    $html=preg_replace("/<input[^>]*?>/is","",$html);
    return $html;

}