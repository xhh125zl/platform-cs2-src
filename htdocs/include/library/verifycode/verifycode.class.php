<?php
class wzwcode{
	public static $seKey = 'randcode';
	public static $expire = 3000;
	
	public static $codeSet = '346789ABCDEFGHJKLMNPQRTUVWXYabcdefghjkmnpqrtuvwxy';
	public static $fontSize = 35;
	public static $useCurve = true;
	public static $useNoise = true;
	public static $imageH = 0;
	public static $imageL = 0;
	public static $length = 4;
	public static $bg = array(243, 251, 254);  // 背景
	
	protected static $_image = null;
	protected static $_color = null;
	
	/**
	 * 输出验证码并把验证码的值保存的session中
	 * 验证码保存到session的格式为： $_SESSION[self::$seKey] = array('code' => '验证码值', 'time' => '验证码创建时间');
	 */
	public static function entry() {
		// 图片宽(px)
		self::$imageL || self::$imageL = self::$length * self::$fontSize * 1.5 + self::$fontSize*1.5; 
		// 图片高(px)
		self::$imageH || self::$imageH = self::$fontSize * 2;
		self::$_image = imagecreate(self::$imageL, self::$imageH); 
		imagecolorallocate(self::$_image, self::$bg[0], self::$bg[1], self::$bg[2]); 
		self::$_color = imagecolorallocate(self::$_image, mt_rand(1,120), mt_rand(1,120), mt_rand(1,120));
		$fft_array = array('elephant.ttf','arial.ttf','hyq1gjm.ttf');
		
		$ttf = $fft_array[rand(0,count($fft_array)-1)];
		if (self::$useNoise) {
			self::_writeNoise();
		} 
		if (self::$useCurve) {
			self::_writeCurve();
		}
		
		$code = array();
		$codeNX = 0;
		for ($i = 0; $i<self::$length; $i++) {
			$code[$i] = self::$codeSet[mt_rand(0, (strlen(self::$codeSet)-1))];
			$codeNX += mt_rand(self::$fontSize*1.2, self::$fontSize*1.6);
			imagettftext(self::$_image, self::$fontSize, mt_rand(-40, 70), $codeNX, self::$fontSize*1.5, self::$_color, $_SERVER["DOCUMENT_ROOT"].'/include/library/verifycode/'.$ttf, $code[$i]);
		}
		
		isset($_SESSION) || session_start();
		$_SESSION[self::$seKey]['code'] = join('', $code);
		$_SESSION[self::$seKey]['time'] = time();
				
		header('Cache-Control: private, max-age=0, no-store, no-cache, must-revalidate');
		header('Cache-Control: post-check=0, pre-check=0', false);		
		header('Pragma: no-cache');
		ob_clean();
		header("content-type: image/png");		
		imagepng(self::$_image); 
		imagedestroy(self::$_image);
	}
	
    protected static function _writeCurve() {
		$A = mt_rand(1, self::$imageH/10);
		$b = mt_rand(-self::$imageH/4, self::$imageH/4);
		$f = mt_rand(-self::$imageH/4, self::$imageH/4);
		$T = mt_rand(self::$imageH*1.5, self::$imageL*2);
		$w = (2* M_PI)/$T;
						
		$px1 = 0;
		$px2 = mt_rand(self::$imageL/2, self::$imageL * 0.667); 	
		for ($px=$px1; $px<=$px2; $px=$px+ 0.9) {
			if ($w!=0) {
				$py = $A * sin($w*$px + $f)+ $b + self::$imageH/2;
				$i = (int) ((self::$fontSize - 6)/8);
				while ($i > 0) {	
				    imagesetpixel(self::$_image, $px + $i, $py + $i, self::$_color);			    
				    $i--;
				}
			}
		}
		
		$A = mt_rand(1, self::$imageH/10);
		$f = mt_rand(-self::$imageH/4, self::$imageH/4);
		$T = mt_rand(self::$imageH*1.5, self::$imageL*2);
		$w = (2* M_PI)/$T;		
		$b = $py - $A * sin($w*$px + $f) - self::$imageH/2;
		$px1 = $px2;
		$px2 = self::$imageL;
		for ($px=$px1; $px<=$px2; $px=$px+ 0.9) {
			if ($w!=0) {
				$py = $A * sin($w*$px + $f)+ $b + self::$imageH/2;
				$i = (int) ((self::$fontSize - 8)/8);
				while ($i > 0) {			
				    imagesetpixel(self::$_image, $px + $i, $py + $i, self::$_color);
				    $i--;
				}
			}
		}
	}

	protected static function _writeNoise() {
		for($i = 0; $i < 10; $i++){
		    $noiseColor = imagecolorallocate(
		                      self::$_image, 
		                      mt_rand(150,225), 
		                      mt_rand(150,225), 
		                      mt_rand(150,225)
		                  );
			for($j = 0; $j < 5; $j++) {
			    imagestring(
			        self::$_image,
			        5, 
			        mt_rand(-10, self::$imageL), 
			        mt_rand(-10, self::$imageH), 
			        self::$codeSet[mt_rand(0, (strlen(self::$codeSet)-1))],
			        $noiseColor
			    );
			}
		}
	}
	
	
	public static function check($code) {
		isset($_SESSION) || session_start();
		if(empty($code) || empty($_SESSION[self::$seKey])) {
			return false;
		}
		
		if(time() - $_SESSION[self::$seKey]['time'] > self::$expire) {
			unset($_SESSION[self::$seKey]);
			return false;
		}
		
		if(strtolower($code) == strtolower($_SESSION[self::$seKey]['code'])) {
			return true;
		}

		return false;
	}
}

?>