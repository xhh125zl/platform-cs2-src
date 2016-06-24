<?php
use Illuminate\Database\Capsule\Manager as Capsule;

//平台工具类
if ( ! function_exists('base_url'))
{
	/**
	 * 获取某用户下属分销账号
	 * @param  String $uri        uri参数
	 * @return String $base_url  本站基地址
	 */
	function base_url($uri = ''){
		$base_url = 'http://'.$_SERVER['HTTP_HOST'].'/'.$uri;
		return $base_url;
	}
	
}

if ( ! function_exists('shop_url'))
{
	/**
	 * 获取某用户下属分销账号
	 * @param  String $uri        uri参数
	 * @return String $base_url  本站基地址
	 */
	function shop_url($uri = ''){
		
		$UsersID = $_GET['UsersID'];
		return base_url().'api/'.$UsersID.'/shop/'.$uri;
	}
	
}

if ( ! function_exists('distribute_url'))
{
	/**
	 * 获取某用户下属分销账号
	 * @param  String $uri        uri参数
	 * @return String $base_url  本站基地址
	 */
	function distribute_url($uri = ''){
		
		$UsersID = $_GET['UsersID'];
		return base_url().'api/'.$UsersID.'/distribute/'.$uri;
	}
	
}


if ( ! function_exists('shop_config'))
{
	/**
	 * 获取本店配置
	 * @param  String $UsersID   本店唯一标示
	 * @param  Array $fields    指定的字段
	 * @return Array $shop_config   返回结果
	 */
	function shop_config($UsersID = '',$fields = array()){
		
		$builder = Shop_Config::where('Users_ID',$UsersID);
		if(count($fields) >0 ){
		    if(!$builder->first($fields)) return false;
			$shop_config = $builder->first($fields)
			                       ->toArray();	
		}else{
		    if(!$builder->first()) return false;
			$shop_config = $builder->first()
			                       ->toArray();
		}			
		
		return !empty($shop_config)?$shop_config:false;
	}
}

if ( ! function_exists('dis_config'))
{
	/**
	 * 获取本店配置
	 * @param  String $UsersID   本店唯一标示
	 * @param  Array $fields    指定的字段
	 * @return Array $shop_config   返回结果
	 */
	function dis_config($UsersID = '',$fields = array()){
		
		$builder = Dis_Config::where('Users_ID',$UsersID);
		if(count($fields) >0 ){
		    if(!$builder->first($fields)) return false;
			$dis_config = $builder->first($fields)
			                       ->toArray();	
		}else{
		    if(!$builder->first()) return false;
			$dis_config = $builder->first()
			                       ->toArray();
		}			
		
		return !empty($dis_config)?$dis_config:false;
	}
}


if ( ! function_exists('shop_user_config'))
{
	/**
	 * 获取本店针对用户的配置
	 * @param  String $UsersID   本店唯一标示
	 * @param  Array $fields    指定的字段
	 * @return Array $shop_user_config   返回结果
	 */
	function shop_user_config($UsersID = '',$fields = array()){
		
		$builder = User_Config::where('Users_ID',$UsersID);
		if(count($fields) >0 ){
		    if(!$builder->first($fields)) return false;
			$shop_user_config = $builder->first($fields)->toArray();	
		}else{
		    if(!$builder->first()) return false;
			$shop_user_config = $builder->first()->toArray();	
		}
		return !empty($shop_user_config)?$shop_user_config:false;
	}
}

if ( ! function_exists('round_pad_zero'))
{
	/**
	* 浮点数四舍五入补零函数
	* 
	* @param float $num
	*        	待处理的浮点数
	* @param int $precision
	*        	小数点后需要保留的位数
	* @return float $result 处理后的浮点数
	*/
	function round_pad_zero($num, $precision) {
		if ($precision < 1) {  
				return round($num, $precision);  
			}  
		
			$r_num = round($num, $precision);  
			$num_arr = explode('.', "$r_num");  
			if (count($num_arr) == 1) {  
				return "$r_num" . '.' . str_repeat('0', $precision);  
			}  
			$point_str = "$num_arr[1]";  
			if (strlen($point_str) < $precision) {  
				$point_str = str_pad($point_str, $precision, '0');  
			}  
			return $num_arr[0] . '.' . $point_str;  
	}  
}


if(!function_exists('write_file')){
	
	 /**
	 * Write File
	 *
	 * Writes data to the file specified in the path.
	 * Creates a new file if non-existent.
	 *
	 * @param	string	$path	File path
	 * @param	string	$data	Data to write
	 * @param	string	$mode	fopen() mode (default: 'wb')
	 * @return	bool
	 */
	function write_file($path, $data, $mode = 'wb')
	{
		if ( ! $fp = @fopen($path, $mode))
		{
			return FALSE;
		}

		flock($fp, LOCK_EX);

		for ($result = $written = 0, $length = strlen($data); $written < $length; $written += $result)
		{
			if (($result = fwrite($fp, substr($data, $written))) === FALSE)
			{
				break;
			}
		}

		flock($fp, LOCK_UN);
		fclose($fp);

		return is_int($result);
	}
}


if ( ! function_exists('read_file'))
{
	/**
	* Read File
	*
	* Opens the file specfied in the path and returns it as a string.
	*
	* @access	public
	* @param	string	path to file
	* @return	string
	*/	
	function read_file($file)
	{
		if ( ! file_exists($file))
		{
			return FALSE;
		}

		if (function_exists('file_get_contents'))
		{
			return file_get_contents($file);
		}

		if ( ! $fp = @fopen($file, FOPEN_READ))
		{
			return FALSE;
		}

		flock($fp, LOCK_SH);

		$data = '';
		if (filesize($file) > 0)
		{
			$data =& fread($fp, filesize($file));
		}

		flock($fp, LOCK_UN);
		fclose($fp);

		return $data;
	}
}


if ( ! function_exists('build_withdraw_sn'))
{	
	/**
	* 得到提现流水号
	* @return  string
	*/
	function build_withdraw_sn() {
		/* 选择一个随机的方案 */
		//mt_srand((double) microtime() * 1000000);

		return 'WD' . date('Ymd') . str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);
	}

}
	
if ( ! function_exists('generateUpdateBatchQuery'))
{	
	/**
	 *生成mysql批量更新语句
	 */
	 function generateUpdateBatchQuery($tableName = "", $multipleData = array()){

		if( $tableName && !empty($multipleData) ) {

			// column or fields to update
			$updateColumn = array_keys($multipleData[0]);
			$referenceColumn = $updateColumn[0]; //e.g id
			unset($updateColumn[0]);
			$whereIn = "";

			$q = "UPDATE ".$tableName." SET "; 
			foreach ( $updateColumn as $uColumn ) {
				$q .=  $uColumn." = CASE ";

				foreach( $multipleData as $data ) {
					$q .= "WHEN ".$referenceColumn." = ".$data[$referenceColumn]." THEN '".$data[$uColumn]."' ";
				}
				$q .= "ELSE ".$uColumn." END, ";
			}
			foreach( $multipleData as $data ) {
				$whereIn .= "'".$data[$referenceColumn]."', ";
			}
			$q = rtrim($q, ", ")." WHERE ".$referenceColumn." IN (".  rtrim($whereIn, ', ').")";

			// Update  
			return $q;

		} else {
			return false;
		}
	}

}	
	
if ( ! function_exists('handle_product_list'))
{	
	/*处理产品列表*/
	function handle_product_list($list){
	
		$result = array();

		foreach($list as $key=>$item){
			$JSON = json_decode($item['Products_JSON'],TRUE);
			$product = $item;
			if(isset($JSON["ImgPath"])){
				$product['ImgPath'] = $JSON["ImgPath"][0];
			}else{
				$product['ImgPath'] =  'static/api/shop/skin/default/nopic.jpg';
			}
		
			$result[$product['Products_ID']] = $product;
		}
		return $result;
	}
}


if( !function_exists('FetchRepeatMemberInArray')){
	
	/*获取数组中的重复元素*/
	function FetchRepeatMemberInArray($array) {
		// 获取去掉重复数据的数组
		$unique_arr = array_unique ( $array );
		// 获取重复数据的数组
		$repeat_arr = array_diff_assoc ( $array, $unique_arr );
		return $repeat_arr;
	} 
}


if(!function_exists('sql_diff')){
	/**
	 *新老数据比对，确定哪个需要新增，哪个需要删除，哪个需要更新
	 *@param $new 新数据
	 *@param $old 老数据
	 *
	 */
	 function sql_diff($new,$old){
	 		$need_update = array_intersect($new,$old);
			$need_add =  array_diff($new,$old);
			$need_del =   array_diff($old,$new);
		
		    $res = array(
			    'need_update'=>$need_update,
				'need_add'=>$need_add,
				'need_del'=>$need_del
				);
				
			return $res;	

	 }
}

if(!function_exists('get_dropdown_collection')){
	//生成collection dropdown数组
	function get_dropdown_collection($data,$id_field,$value_field = ''){
	    $drop_down = array();
	
		foreach($data as $key=>$item){
			if(strlen($value_field) > 0 ){
				$drop_down[$item->$id_field] = $item->$value_field;
			}else{
				$drop_down[$item->$id_field] = $item;
			}
		}
	
		return $drop_down;
		
	}
	
}

if(!function_exists('get_dropdown_list')){
	//生成dropdown数组
	function get_dropdown_list($data,$id_field,$value_field = ''){
		$drop_down = array();
	
		foreach($data as $key=>$item){
			if(strlen($value_field) > 0 ){
				$drop_down[$item[$id_field]] = $item[$value_field];
			}else{
				$drop_down[$item[$id_field]] = $item;
			}
		}
	
		return $drop_down;
	}
	
}

if(!function_exists('sdate')){
	/*
	 *return short format date,not incluing hour,minutes,seconds
	 *
	 */
	function sdate($time = '')
	{
		if (strlen($time) == 0) {
			$time = time();
		}
		if (is_string($time)) {
			$time = intval($time);
		}
		return date('Y/m/d', $time);
	}
}

if(!function_exists('ldate')){
	/*
	 *return short format date,not incluing hour,minutes,seconds
	 *
	 */
	function ldate($time = '')
	{
		if (strlen($time) == 0) {
			$time = time();
		}
		if (is_string($time)) {
			$time = intval($time);
		}
		return date('Y/m/d H:i:s', $time);
	}
}

/*开始事务*/
if(!function_exists('begin_trans')){
	function begin_trans(){ Capsule::beginTransaction();}
}

/*回滚事务*/
if(!function_exists('back_trans')){
	function back_trans(){ Capsule::rollback();}
}

/*结束事务*/
if(!function_exists('commit_trans')){
	function commit_trans(){ Capsule::commit();}
}



if(!function_exists('generageUpdateBatchQuery')){
	/**
	 * 生成批量更新Sql语句
	 * @param  string $tableName    
	 * @param  array  $multipleData 需要更新的数据,以子数组第一字段作为case 条件
	 * @return string  $string 所生成的批量更新语句               
	 */
	function generageUpdateBatchQuery($tableName = "", $multipleData = array()){

    	if( $tableName && !empty($multipleData) ) {

        	// column or fields to update
        	$updateColumn = array_keys($multipleData[0]);
        	$referenceColumn = $updateColumn[0]; //e.g id
        	unset($updateColumn[0]);
        	$whereIn = "";

        	$q = "UPDATE ".$tableName." SET "; 
        	foreach ( $updateColumn as $uColumn ) {
            	$q .=  $uColumn." = CASE ";

            	foreach( $multipleData as $data ) {
                	$q .= "WHEN ".$referenceColumn." = ".$data[$referenceColumn]." THEN '".$data[$uColumn]."' ";
            	}
            	$q .= "ELSE ".$uColumn." END, ";
        	}
		
        	foreach( $multipleData as $data ) {
            	$whereIn .= "'".$data[$referenceColumn]."', ";
        	}
        	$q = rtrim($q, ", ")." WHERE ".$referenceColumn." IN (".  rtrim($whereIn, ', ').")";

        	// Update  
        	return $q;

    	} else {
        	return false;
    	}
	}
}

/**
 * 处理单个产品
 * @param  Array $product 需要处理的产品
 * @return Array $product 经过处理的产品 
 */
function handle_product($product){
	
	$JSON = json_decode($product['Products_JSON'],TRUE);
	
	if(isset($JSON["ImgPath"])){
			$product['ImgPath'] = $JSON["ImgPath"][0];
		}else{
			$product['ImgPath'] =  'static/api/shop/skin/default/nopic.jpg';
	}
	
	return $product;
}

	
/*获得产品封面图片地址*/
function get_prodocut_cover_img($Product){
	$JSON = json_decode($Product['Products_JSON'],TRUE);
	$product = $Product;
	
    if(isset($JSON["ImgPath"])){
			$product['ImgPath'] = $JSON["ImgPath"][0];
	}else{
			$product['ImgPath'] =  'static/api/shop/skin/default/nopic.jpg';
	}
		
	return $product['ImgPath'] ;
}

	
/**
 *获取商品的平均评分
 *
 */
function get_comment_aggregate($DB,$UsersID,$ProductID){

	$condition = "where Users_ID = '".$UsersID."' and Product_ID=".$ProductID.' and Status=1';
	$rsCommit = $DB->getRs("user_order_commit","AVG(Score) as Points,COUNT(Item_ID) as NUM",$condition);
	$points = 0; 
    $aggerate = array('points'=>0,'num'=>0);
	
    if(!empty($rsCommit)){
		$aggerate['points'] = intval($rsCommit['Points']);
        $aggerate['num'] = $rsCommit['NUM'];
	}

	return $aggerate;

}
	
/**
 * 获取可用的支付方式
 * @return [type] [description]
 */
function get_enabled_pays($DB,$UsersID){
	$rsPayConfig = $DB->GetRs("users_payconfig","*","where Users_ID='".$UsersID."'");

	$pays = array();
	if($rsPayConfig['PaymentWxpayEnabled'] == 1){
		$pays['Wxpay'] = '微信支付';
	}
	if($rsPayConfig['Payment_AlipayEnabled'] == 1){

		$pays['Alipay'] = '支付宝支付';
	}
	if($rsPayConfig['PaymentYeepayEnabled'] == 1){
	
		$pays['Yeepay'] = '易宝支付';
	}
	
	if($rsPayConfig['Payment_OfflineEnabled'] == 1){
		$pays['Offline'] = '线下支付(货到付款)';
	}
	
	
	return $pays;
}	


/**
 *付款后更改商品库存，若库存为零则下架
 */
function handle_products_count($UsersID,$rsOrder){
	
	global $DB1;
	$CartList = json_decode(htmlspecialchars_decode($rsOrder['Order_CartList']),true);
	
	if(empty($CartList)){
		return false;
	}
	
	//取出购物车所包含产品信息	
	foreach($CartList as $ProductID=>$product_list){
		$qty = 0;
		foreach($product_list as $key=>$item){
			$qty += $item['Qty'];
		}
	
		$condition = "where Users_ID='".$UsersID."' and Products_ID=".$ProductID;
		$rsProduct  = $DB1->GetRs('shop_products','Products_Count',$condition);
		
		$Products_Count = $rsProduct['Products_Count']-$qty;
		$product_data['Products_Count'] = $Products_Count;

		if($Products_Count == 0){
			$product_data['Products_SoldOut'] = 1;
		}
		
		$DB1->set('shop_products',$product_data,$condition);
	}
}

/**
 *获取用户信息edit in 20160329
 */
function handle_getuserinfo($UsersID,$UserID){	
	global $DB1;
		$condition = "where Users_ID='".$UsersID."' and User_ID=".$UserID;

		$rsUser  = $DB1->GetRs('user','User_Mobile',$condition);	

		return $rsUser['User_Mobile'];
}

/**
 *用getDictionary获得的Collection数组生成dropdown数组
 */
function collection_dropdown($dictionary,$field){
	
	$dropdown = array();
	foreach($dictionary as $id=>$item){
		$dropdown[$id] = $item->$field;
	}
	
	return $dropdown;
}
	
	 /**
     * 异步将远程链接上的内容(图片或内容)写到本地
     * 
     * @param unknown $url
     *            远程地址
     * @param unknown $saveName
     *            保存在服务器上的文件名
     * @param unknown $path
     *            保存路径
     * @return boolean
     */
    function put_file_from_url_content($url, $saveName, $path) {
        // 设置运行时间为无限制
        set_time_limit ( 0 );
        
        $url = trim ( $url );
        $curl = curl_init ();
        // 设置你需要抓取的URL
        curl_setopt ( $curl, CURLOPT_URL, $url );
        // 设置header
        curl_setopt ( $curl, CURLOPT_HEADER, 0 );
        // 设置cURL 参数，要求结果保存到字符串中还是输出到屏幕上。
        curl_setopt ( $curl, CURLOPT_RETURNTRANSFER, 1 );
        // 运行cURL，请求网页
        $file = curl_exec ( $curl );
        // 关闭URL请求
        curl_close ( $curl );
        // 将文件写入获得的数据
        $filename = $path . $saveName;
        $write = @fopen ( $filename, "w" );
        if ($write == false) {
            return false;
        }
        if (fwrite ( $write, $file ) == false) {
            return false;
        }
        if (fclose ( $write ) == false) {
            return false;
        }
    }

	if(!function_exists('check_short_msg')){
	/**
	* 检测手机验证码是否正确
	* @param   int $sms_code 手机短信验证码 
	* @param   string $user_mobile 用户手机号码
	* @return  string $result   验证结果是否正确
	*/
	function check_short_msg($user_mobile,$sms_code,$UsersID=''){
	
		$result = true;
		if(!preg_match("/[0-9]{6}/", $sms_code) || $_SESSION[$UsersID.'mobile_code'] != md5($user_mobile.'|'.$sms_code)){
		$result = false;		
		}
	
		return $result;
	}
}

if(!function_exists('randcode')){
	/**
	 * 生成指定长度纯数字随机码
	 * @param  integer $length 所需生成随机码长度
	 * @return string  $temchars  所生成的随机码
	 */
	function randcode($length=6){
		$chars = '0123456789';
		$temchars = '';
		for($i=0;$i<$length;$i++)
		{
			$temchars .= $chars[ mt_rand(0, strlen($chars) - 1) ];
		}
	
		return $temchars;
	}
}

 
if(!function_exists('star_mobile')){
    
   /**
    * 获取掩码手机号 13781673071 变为 137******71
    * @param  string $mobile 原手机号
    * @return string $starMobile   掩码手机号
    *    
    **/
   function star_mobile($mobile = ''){
	   $head = substr($mobile, 0,3);
	   $tail = substr($mobile,-2,2);
	   $starMobile  = $head.'******'.$tail;	   
	   return $starMobile;
   }	
   
	
}

if(!function_exists('getFloatValue')){
	function getFloatValue($f,$len)
	{
	  $tmpInt=intval($f);

	  $tmpDecimal=$f-$tmpInt;
	  $str="$tmpDecimal";
	  $subStr=strstr($str,'.');
	  if($tmpDecimal != 0){	
		 if(strlen($subStr)<$len+1)
		{
			$repeatCount=$len+1-strlen($subStr);
			$str=$str."".str_repeat("0",$repeatCount);

		}
		
		$result = $tmpInt."".substr($str,1,1+$len);
	  }else{
		$result =  	$tmpInt.".".str_repeat("0",$len);
	  }

	  return   $result;

	}
}

if (!function_exists('getmonth')) {//获取前后月的第一天
	function getmonth($type=0){
		$month = date('n');
		$year = date('Y');
		if($type==0){//上个月一号
			if($month==1){
				$month = 12;
				$year = $year - 1;
			}else{
				$month = $month - 1;
			}
		}else{//下个月一号
			if($month==12){
				$month = 1;
				$year = $year + 1;
			}else{
				$month = $month + 1;
			}
		}
		return strtotime($year.'-'.$month.'-01');
	}
}

if(!function_exists('get_dis_level')){
	function get_dis_level($DB,$UsersID){
		$file_path = $_SERVER["DOCUMENT_ROOT"].'/data/cache/'.$UsersID.'dis_level.php';
		if(is_file($file_path)){
			include($file_path);
			return $dis_level;
		}else{
			$dis_level = array();
			$DB->Get('distribute_level','*','where Users_ID="'.$UsersID.'" order by Level_ID asc');
			while($r = $DB->fetch_assoc()){
				$dis_level[$r['Level_ID']] = $r;
			}
			return $dis_level;
		}
	}
}

if(!function_exists('dis_level_update')){//更新分销商级别
	function update_dis_level($DB,$UsersID){
		$dir_name = $_SERVER["DOCUMENT_ROOT"].'/data/cache';
		//目录判断
		if(!is_dir($dir_name)){
			$temp = explode('/', $dir_name);
			$max = count($temp);
			$cur_dir = '';
			for($i = 0; $i < $max; $i++) {
				$cur_dir .= $temp[$i] . '/';
				if(is_dir($cur_dir)) continue;
				@mkdir($cur_dir);
			}
		}
		
		//文件判断			
		$lists = array();
		$DB->Get('distribute_level','*','where Users_ID="'.$UsersID.'" order by Level_ID asc');
		while($r = $DB->fetch_assoc()){
			$lists[$r['Level_ID']] = $r;
		}
		$arrayname = '$dis_level';
		$data = var_export($lists,true);
		$data = "<?php\n".$arrayname." = ".$data.";\n?>";
		
		$filename = $dir_name.'/'.$UsersID.'dis_level.php';
		if(@$fp = fopen($filename, 'wb')) {
			flock($fp, LOCK_EX);
			$len = fwrite($fp, $data);
			flock($fp, LOCK_UN);
			fclose($fp);
			return $len;
		} else {
			return false;
		}
	}
}