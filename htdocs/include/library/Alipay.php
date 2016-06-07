<?php 
/**
 * @name 支付宝Model 
 */
class Alipay
{
	private $alipay_cnf;
	
    function __construct($alipay_cnf)
    {
    	
		$this->alipay_cnf = $alipay_cnf;
		//var_dump(write_file('../../log.txt','哈哈哈',"wb"));
		//exit();
	}
    
    /**
     * @name 获得发起支付请求的HTML
     * @param Object $order 订单
    
     */
    function get_payreq_html($order)
    {
        require_once($_SERVER["DOCUMENT_ROOT"]."/third_party/alipay/alipay_submit.class.php");

       

        //支付类型
        $payment_type = "1";
        //必填，不能修改
        //服务器异步通知页面路径
        $notify_url = $this->alipay_cnf['notify_url'];
        //需http://格式的完整路径，不能加?id=123这类自定义参数

        //页面跳转同步通知页面路径
        $return_url = $this->alipay_cnf['return_url'];
        //需http://格式的完整路径，不能加?id=123这类自定义参数，不能写成http://localhost/

        //卖家支付宝帐户
        $seller_email = $this->alipay_cnf['seller_email'];
        //必填

        //商户订单号
        $out_trade_no = $order['Record_Sn'];
        //商户网站订单系统中唯一订单号，必填

        //订单名称
        $subject = '好分销续费订单';
        //必填

        //付款金额
        $total_fee = $order['Record_Money'];
        //必填
		$body = $order['description'];
        
		//商品展示地址
        $show_url = "";
        //需以http://开头的完整路径，例如：http://www.xxx.com/myorder.html

        //防钓鱼时间戳
        $anti_phishing_key = '';
        //若要使用请调用类文件submit中的query_timestamp函数

        //客户端的IP地址
        $exter_invoke_ip = "";
        //非局域网的外网IP地址，如：221.0.0.1


        /************************************************************/
        
        //构造要请求的参数数组，无需改动
        $parameter = array(
        		"service" => "create_direct_pay_by_user",
        		"partner" => trim($this->alipay_cnf['partner']),
        		"payment_type"	=> $payment_type,
        		"notify_url"	=> $notify_url,
				"notify_type"  => '',
        		"seller_email"	=> $seller_email,
        		"out_trade_no"	=> $out_trade_no,
        		"subject"	=> $subject,
        		"total_fee"	=> $total_fee,
        		"body"	=> $body,
        		"show_url"	=> $show_url,
        		"anti_phishing_key"	=> $anti_phishing_key,
        		"exter_invoke_ip"	=> $exter_invoke_ip,
        		"_input_charset"	=> trim(strtolower($this->alipay_cnf['input_charset']))
        );
        
        //建立请求
        $alipaySubmit = new AlipaySubmit($this->alipay_cnf);
        $html_text = $alipaySubmit->buildRequestForm($parameter,'get');
        return $html_text;
    }

    
    function test_notify($text='')
    {
        $tools_db = $this->load->database('tools', TRUE);
        $tools_db->insert('alipay_log', array('txt'=>$text, 'dateline'=>date('Y-m-d H:i:s')));
    }
}