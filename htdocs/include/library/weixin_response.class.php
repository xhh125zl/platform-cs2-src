<?php
class weixin_response{
	var $db;
	var $usersid;
	var $token;
	var $openid;
	var $userstype;

	function __construct($DB,$usersid){
	
		$this->db = $DB;
		$this->usersid = $usersid;
		
		$item = $this->db->GetRs("users","Users_WechatToken,Users_WechatAppId,Users_WechatAppSecret,Users_WechatType,Users_WechatID","where Users_ID='".$this->usersid."'");
		
		if($item["Users_WechatAppId"] && $item["Users_WechatAppSecret"] && in_array($item["Users_WechatType"],array('1','3'))){
			$this->userstype = 1;
		}else{
			$this->userstype = 0;
		}
		$this->token = $item["Users_WechatToken"];
	}
	
	
	public function responseMsg(){
		$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
			
		if (!empty($postStr)){
        	$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $this->openid = $postObj->FromUserName;
            $toUsername = $postObj->ToUserName;
            $keyword = trim($postObj->Content);
            $time = time();
			$Tpl = $this->tpl_conf();
			$msgType = 'text';
			$textTpl = $Tpl[$msgType];
			$contentStr='';
			$gotiao = 3;
			if($postObj->MsgType=='event'){
				if($postObj->Event=='subscribe'){
					$guanzhu = $ownerid = $productsid = 0;
					$item = array();
					if(!empty($postObj->EventKey)){
						if(strpos($postObj->EventKey,"_user_")>-1){
							$ownerid = intval(str_replace('qrscene_user_','',$postObj->EventKey));
							if(!$ownerid){
							$gotiao = 1;
							$contentStr = '关注有错，请取消关注后重新关注！';	
							}
						}
						 
						if(strpos($postObj->EventKey,"_products_")>-1){
							$gotiao = 1;
							$contentStr = '图片关注有错，请取消关注后重新关注！';
							/* $guanzhu = 1;
							$strtemp = str_replace('qrscene_products_','',$postObj->EventKey);
							$arrTemp = explode("_",$strtemp);
							$ownerid = $arrTemp[0];
							$productsid = $arrTemp[1];
							$item=$this->db->GetRs("shop_products","*","where Users_ID='".$this->usersid."' and Products_ID=".$productsid);
							if(!$item){
								$guanzhu = 0;
							}else{
								$JSON = json_decode($item['Products_JSON'],TRUE);
								$item["img"] = isset($JSON["ImgPath"]) ? $JSON["ImgPath"][0] : '';
							} */
						}
					}
					if($gotiao == 3){
					$info = $this->registeruser($ownerid);
					if($guanzhu==1){
						$array = array();
						$array[] = array(
							"Title"=>$item["Products_Name"],
							"TextContents"=>$item["Products_BriefDescription"],
							"ImgPath"=>"http://".$_SERVER['HTTP_HOST'].$item["img"],
							"Url"=>"http://".$_SERVER['HTTP_HOST']."/api/".$this->usersid."/shop".($info["ownerid"]>0 ? '/'.$info["ownerid"] : '')."/products/".$productsid.'/'
						);
						$msgType = "news";
						$textHeadTpl = $Tpl[$msgType]["Head"];
						$resultHeadStr = sprintf($textHeadTpl, $this->openid, $toUsername, $time, $msgType, count($array));
						$textContentTpl = $Tpl[$msgType]["Content"];
						$resultContentStr = "";
						foreach ($array as $key => $value){
							$resultContentStr .= sprintf($textContentTpl, $value['Title'], $value['TextContents'], $value['ImgPath'], $value['Url']);
						}
									  
						$textFooterTpl = $Tpl[$msgType]["Footer"];
						$resultFooterStr = sprintf($textFooterTpl);
						echo $resultHeadStr . $resultContentStr . $resultFooterStr;
						exit;
					}else{
						$rsReply=$this->db->GetRs("wechat_attention_reply","*","where Users_ID='".$this->usersid."'");
						if($rsReply){
							if($rsReply['Reply_MsgType']){
								$array = $this->get_material($rsReply['Reply_MaterialID']);								
								$msgType = "news";
								$textHeadTpl = $Tpl[$msgType]["Head"];
								$resultHeadStr = sprintf($textHeadTpl, $this->openid, $toUsername, $time, $msgType, count($array));
								$textContentTpl = $Tpl[$msgType]["Content"];
								$resultContentStr = "";
								foreach ($array as $key => $value){
									$resultContentStr .= sprintf($textContentTpl, $value['Title'], $value['TextContents'], $value['ImgPath'], $value['Url']);
								}
									  
								$textFooterTpl = $Tpl[$msgType]["Footer"];
								$resultFooterStr = sprintf($textFooterTpl);
								echo $resultHeadStr . $resultContentStr . $resultFooterStr;
								exit;
							}else{
								$contentStr = '';
								
								if($info["register"]==1 && $rsReply["Reply_MemberNotice"]==1){
									$contentStr = $contentStr.'您好';
									if($info["nickname"]){
										$contentStr = $contentStr.'！'.$info["nickname"].'，';
									}
									$contentStr = $contentStr.'您已成为第'.$info["userno"].'位会员，';
								}
								$contentStr=$contentStr.$rsReply['Reply_TextContents'];
							}
						}else{
							echo "";
							exit;
						}	
					}
					}
				}elseif($postObj->Event=="SCAN"){
					
					$guanzhu = $ownerid = $productsid = 0;
					$item = array();
					
					if(strpos($postObj->EventKey,"user_")>-1){
						$ownerid = intval(str_replace('user_','',$postObj->EventKey));
						if(!$ownerid){
							$gotiao = 1;
							$contentStr = '关注有错，请取消关注后重新关注！';	
							}
					}
					
					if(strpos($postObj->EventKey,"products_")>-1){
						$gotiao = 1;
						$contentStr = '图片关注有错，请取消关注后重新关注！';
						/* $guanzhu = 1;
						$strtemp = str_replace('products_','',$postObj->EventKey);
						$arrTemp = explode("_",$strtemp);
						$ownerid = $arrTemp[0];
						$productsid = $arrTemp[1];
						$item=$this->db->GetRs("shop_products","*","where Users_ID='".$this->usersid."' and Products_ID=".$productsid);
						if(!$item){
							$guanzhu = 0;
						}else{
							$JSON = json_decode($item['Products_JSON'],TRUE);
							$item["img"] = isset($JSON["ImgPath"]) ? $JSON["ImgPath"][0] : '';
						}*/
					}
					if($gotiao == 3){
					$info = $this->registeruser($ownerid);
					
					if($guanzhu==1){
						$array = array();
						$array[] = array(
							"Title"=>$item["Products_Name"],
							"TextContents"=>$item["Products_BriefDescription"],
							"ImgPath"=>"http://".$_SERVER['HTTP_HOST'].$item["img"],
							"Url"=>"http://".$_SERVER['HTTP_HOST']."/api/".$this->usersid."/shop".($info["ownerid"]>0 ? '/'.$info["ownerid"] : '')."/products/".$productsid.'/'
						);
						$msgType = "news";
						$textHeadTpl = $Tpl[$msgType]["Head"];
						$resultHeadStr = sprintf($textHeadTpl, $this->openid, $toUsername, $time, $msgType, count($array));
						$textContentTpl = $Tpl[$msgType]["Content"];
						$resultContentStr = "";
						foreach ($array as $key => $value){
							$resultContentStr .= sprintf($textContentTpl, $value['Title'], $value['TextContents'], $value['ImgPath'], $value['Url']);
						}
									  
						$textFooterTpl = $Tpl[$msgType]["Footer"];
						$resultFooterStr = sprintf($textFooterTpl);
						echo $resultHeadStr . $resultContentStr . $resultFooterStr;
						exit;
					}else{
						echo "";
						exit;
					}
					}
				}elseif($postObj->Event=="unsubscribe"){
					$contentStr = "取消订阅成功！";
				}elseif($postObj->Event=="CLICK"){
					$EventKey=explode("_",$postObj->EventKey);
					if($EventKey[0]=="MenuID"){
						$rsMenu=$this->db->GetRs("wechat_menu","Menu_TextContents","where Users_ID='".$this->usersid."' and Menu_ID='".$EventKey[1]."'");
						if($rsMenu){
							$contentStr = $rsMenu["Menu_TextContents"];						
						}
					}elseif($EventKey[0]=="MaterialID"){
						$array = $this->get_material($EventKey[1]);
						$msgType = "news";								
						$textHeadTpl = $Tpl[$msgType]["Head"];
						$resultHeadStr = sprintf($textHeadTpl, $this->openid, $toUsername, $time, $msgType, count($array));
						$textContentTpl = $Tpl[$msgType]["Content"];
						$resultContentStr = "";
						foreach ($array as $key => $value){
							$resultContentStr .= sprintf($textContentTpl, $value['Title'], $value['TextContents'], $value['ImgPath'], $value['Url']);
						}
						$textFooterTpl = $Tpl[$msgType]["Footer"];
						$resultFooterStr = sprintf($textFooterTpl);
						echo $resultHeadStr . $resultContentStr . $resultFooterStr;
						exit;
					}elseif($EventKey[0]=="myqrcode"){
						$usertemp = $this->db->GetRs("user","User_ID","where Users_ID='".$this->usersid."' and User_OpenID='".$this->openid."'");
						if($usertemp){
							$file_path = $_SERVER["DOCUMENT_ROOT"].'/data/poster/'.$this->usersid.$usertemp["User_ID"].'.png';
							if(!is_file($file_path)){
								$contentStr = '你还没有二维码！<a href="http://'.$_SERVER['HTTP_HOST'].'/api/'.$this->usersid.'/distribute/qrcodehb/">立即生成</a>';
							}else{								
								$fileinfo = array('media'=>new CURLFile($file_path));								
								require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/weixin_material.class.php');
								$weixin_material = new weixin_material($this->db,$this->usersid);
								$data = $weixin_material->upload_files('image',$fileinfo);
								if(empty($data["media_id"])){
									$contentStr = '你还没有二维码！';
								}else{
									$msgType = "image";
									$textTpl = $Tpl[$msgType];
									$contentStr =$data["media_id"];
								}
							}
						}else{
							$contentStr = '你还没有二维码！';
						}
					}elseif($EventKey[0]=="changwenben"){
						$rsMenu=$this->db->GetRs("wechat_menu","Menu_TextContents","where Users_ID='".$this->usersid."' and Menu_ID='".$EventKey[1]."'");
						if($rsMenu){
							$contentStr = $rsMenu["Menu_TextContents"];						
						}else{
							$contentStr = $EventKey[0];
						}
					}else{
						$contentStr = $EventKey[0];
					}						
				}
			}elseif($postObj->MsgType=="text"){
				$msgType = "text";
				$textTpl = $Tpl[$msgType];
				$rsConfig = $this->db->GetRs("kf_config","*","where Users_ID='".$this->usersid."'");
				$Wx_keyword = array_filter(explode('|',$rsConfig['Wx_keyword']));
				$Wx_status = false;
				foreach($Wx_keyword as $key=>$value){
					if(strstr($keyword, $value)){
						$Wx_status = true;
						break;
					}else{
						$Wx_status = false;
						continue;
					}
				}
				if(empty($keyword)){
					$contentStr = "请说些什么...";
				}elseif($Wx_status){
							$msgType = "transfer_customer_service";								
							$transferTpl = $Tpl[$msgType];							
							$result = sprintf($transferTpl, $this->openid, $toUsername, $time,$msgType);
							echo $result;
							exit;
				}else{
					$rsReply=$this->db->GetRs("wechat_keyword_reply","Reply_TextContents,Reply_MsgType,Reply_MaterialID","where Users_ID='".$this->usersid."' and Reply_PatternMethod=0 and Reply_Keywords='".$keyword."' order by Reply_Table desc,Reply_ID desc");
					if(!$rsReply){
						$rsReply=$this->db->GetRs("wechat_keyword_reply","Reply_TextContents,Reply_MsgType,Reply_MaterialID","where Users_ID='".$this->usersid."' and Reply_PatternMethod=1 and Reply_Keywords like '%".$keyword."%' order by Reply_Table desc,Reply_ID desc");
					}
					if(!$rsReply){
						$rsReply=$this->db->GetRs("wechat_attention_reply","Reply_TextContents,Reply_MsgType,Reply_MaterialID","where Users_ID='".$this->usersid."' and Reply_Subscribe=1");
					}
					if($rsReply){
						if($rsReply["Reply_MsgType"]){
							$array = $this->get_material($rsReply["Reply_MaterialID"]);
							$msgType = "news";								
							$textHeadTpl = $Tpl[$msgType]["Head"];
							$resultHeadStr = sprintf($textHeadTpl, $this->openid, $toUsername, $time, $msgType, count($array));
							$textContentTpl = $Tpl[$msgType]["Content"];
							$resultContentStr = "";
							foreach ($array as $key => $value){
								$resultContentStr .= sprintf($textContentTpl, $value['Title'], $value['TextContents'], $value['ImgPath'], $value['Url']);
							}
										  
							$textFooterTpl = $Tpl[$msgType]["Footer"];
							$resultFooterStr = sprintf($textFooterTpl);
							echo $resultHeadStr . $resultContentStr . $resultFooterStr;
							exit;
						}else{
							$contentStr = $rsReply["Reply_TextContents"]; 
						}										  									  
					}else{
						$contentStr = '关键词不对亲！';
						exit;
					}
				}
			}elseif($postObj->MsgType=="image"){
				$msgType = "image";
				$textTpl = $Tpl[$msgType];
				$contentStr =$postObj->MediaId;
			}elseif($postObj->MsgType=="voice"){
				$msgType = "voice";
				$textTpl = $Tpl[$msgType];
				$contentStr =$postObj->MediaId;
			}elseif($postObj->MsgType=="video"){
				$msgType = "text";
				$contentStr = "您发来的是视频消息！\n视频消息缩略图的媒体id为".$postObj->ThumbMediaId."\nMediaId为".$postObj->MediaId;
			}elseif($postObj->MsgType=="location"){
				$lat = floatval($postObj->Location_X);
				$lng = floatval($postObj->Location_Y);
				$distance = 2;//范围半径2000m
				$dlng =  2 * asin(sin($distance / (2 * 6371)) / cos(deg2rad($lat)));
    			$dlng = rad2deg($dlng);
				$dlat = $distance/6371;
    			$dlat = rad2deg($dlat);						
				$lat_min = $lat-$dlat;
				$lat_max = $lat+$dlat;
				$lng_min = $lng-$dlng;
				$lng_max = $lng+$dlng;						
				$this->db->get("stores","Stores_Name, Stores_ImgPath, Stores_Address","where Users_ID='".$this->usersid."' and Stores_PrimaryLat>".$lat_min." and Stores_PrimaryLat<".$lat_max." and Stores_PrimaryLng>".$lng_min." and Stores_PrimaryLng<".$lng_max." order by Stores_ID asc limit 0,4");
						
				$array = array();
				while($value = $this->db->fetch_assoc()){
					$array[] =array(
						"Title"=>$value["Stores_Name"].'【'.$value["Stores_Address"].'】',
						"TextContents"=>"",
						"ImgPath"=>strpos($value["Stores_ImgPath"],"http://")>-1?$value["Stores_ImgPath"]:"http://".$_SERVER['HTTP_HOST'].$value["Stores_ImgPath"],
						"Url"=>"http://".$_SERVER['HTTP_HOST'].'/api/'.$this->usersid.'/stores/'
					);
				}
				
				if(!empty($array)){
					$msgType = "news";								
					$textHeadTpl = $Tpl[$msgType]["Head"];
					$resultHeadStr = sprintf($textHeadTpl, $this->openid, $toUsername, $time, $msgType, count($array));
					$textContentTpl = $Tpl[$msgType]["Content"];
					$resultContentStr = "";
					foreach ($array as $key => $value){
						$resultContentStr .= sprintf($textContentTpl, $value['Title'], $value['TextContents'], $value['ImgPath'], $value['Url']);
					}
					$textFooterTpl = $Tpl[$msgType]["Footer"];
					$resultFooterStr = sprintf($textFooterTpl);
					echo $resultHeadStr . $resultContentStr . $resultFooterStr;
					exit;
				}else{
					$msgType = "text";
					$contentStr = "您发来的是地理位置消息1！\n地理位置维度".$postObj->Location_X."\n地理位置经度".$postObj->Location_Y."\n地图缩放大小".$postObj->Scale."\n地理位置信息".$postObj->Label;
				}
			}elseif($postObj->MsgType=="link"){
				$msgType = "text";
				$contentStr = "您发来的是链接消息！\n消息标题".$postObj->Title."\n消息描述".$postObj->Description."\n消息链接".$postObj->Url;
			}
				
			$resultStr = sprintf($textTpl, $this->openid, $toUsername, $time, $msgType, $contentStr);
            echo $resultStr;
        }else {
        	echo "";
        	exit;
        }
    }

	private function tpl_conf(){
		$array = array(
			"text"=>"<xml>
					<ToUserName><![CDATA[%s]]></ToUserName>
					<FromUserName><![CDATA[%s]]></FromUserName>
					<CreateTime>%s</CreateTime>
					<MsgType><![CDATA[%s]]></MsgType>
					<Content><![CDATA[%s]]></Content>
					<FuncFlag>0</FuncFlag>
					</xml>",		
			"image"=>"<xml>
					<ToUserName><![CDATA[%s]]></ToUserName>
					<FromUserName><![CDATA[%s]]></FromUserName>
					<CreateTime>%s</CreateTime>
					<MsgType><![CDATA[%s]]></MsgType>
					<Image>
					<MediaId><![CDATA[%s]]></MediaId>
					</Image>
					</xml>",
			"voice"=>"<xml>
					<ToUserName><![CDATA[%s]]></ToUserName>
					<FromUserName><![CDATA[%s]]></FromUserName>
					<CreateTime>%s</CreateTime>
					<MsgType><![CDATA[%s]]></MsgType>
					<Voice>
					<MediaId><![CDATA[%s]]></MediaId>
					</Voice>
					</xml>",
			"video"=>"<xml>
					<ToUserName><![CDATA[%s]]></ToUserName>
					<FromUserName><![CDATA[%s]]></FromUserName>
					<CreateTime>%s</CreateTime>
					<MsgType><![CDATA[%s]]></MsgType>
					<Video>
					<MediaId><![CDATA[%s]]></MediaId>
					<Title><![CDATA[%s]]></Title>
					<Description><![CDATA[%s]]></Description>
					</Video> 
					</xml>",
			"music"=>"<xml>
					<ToUserName><![CDATA[%s]]></ToUserName>
					<FromUserName><![CDATA[%s]]></FromUserName>
					<CreateTime>%s</CreateTime>
					<MsgType><![CDATA[%s]]></MsgType>
					<Music>
					<Title><![CDATA[%s]]></Title>
					<Description><![CDATA[%s]]></Description>
					<MusicUrl><![CDATA[%s]]></MusicUrl>
					<HQMusicUrl><![CDATA[%s]]></HQMusicUrl>
					<ThumbMediaId><![CDATA[%s]]></ThumbMediaId>
					</Music>
					</xml>",
			"news"=>array(
				"Head"=>"<xml>
						<ToUserName><![CDATA[%s]]></ToUserName>
						<FromUserName><![CDATA[%s]]></FromUserName>
						<CreateTime>%s</CreateTime>
						<MsgType><![CDATA[%s]]></MsgType>
						<ArticleCount>%s</ArticleCount>
						<Articles>",
				"Content"=>"<item>
						<Title><![CDATA[%s]]></Title> 
						<Description><![CDATA[%s]]></Description>
						<PicUrl><![CDATA[%s]]></PicUrl>
						<Url><![CDATA[%s]]></Url>
						</item>",
				"Footer"=>"</Articles>
						</xml>"
			),
			"transfer_customer_service"=>"<xml>
					<ToUserName><![CDATA[%s]]></ToUserName>
					<FromUserName><![CDATA[%s]]></FromUserName>
					<CreateTime>%s</CreateTime>
					<MsgType><![CDATA[%s]]></MsgType>					
					</xml>"
		);
		return $array;
	}
	
	public function valid(){
        $echoStr = $_GET["echostr"];
        if($this->checkSignature()){
        	echo $echoStr;
        	exit; 
        }
    }
	
	private function checkSignature(){
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];	
        		
		$token = $this->token;
		$tmpArr = array($token, $timestamp, $nonce);
		sort($tmpArr, SORT_STRING);
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );
		
		if( $tmpStr == $signature ){
			return true;
		}else{
			return false;
		}
	}
	
	private function get_material($id){
		$rsMaterial=$this->db->GetRs("wechat_material","Material_Type,Material_Json","where Users_ID='".$this->usersid."' and Material_ID=".$id);
		$Material_Json=json_decode($rsMaterial['Material_Json'],true);
		if(!empty($Material_Json["TextContents"])){
			$Material_Json["TextContents"] = str_replace("<br />","\n",$Material_Json["TextContents"]);
		}
		$array = array();
		
		if($rsMaterial['Material_Type']){
			foreach($Material_Json as $key=>$value){
				$array[] =array(
					"Title"=>$value["Title"],
					"TextContents"=>"",
					"ImgPath"=>strpos($value["ImgPath"],"http://")>-1?$value["ImgPath"]:"http://".$_SERVER['HTTP_HOST'].$value["ImgPath"],
					"Url"=>strpos($value["Url"],"http://")>-1?$value["Url"]:"http://".$_SERVER['HTTP_HOST'].$value["Url"]
				);
			}
		}else{
			$array[] =array(
				"Title"=>$Material_Json["Title"],
				"TextContents"=>$Material_Json["TextContents"],
				"ImgPath"=>strpos($Material_Json["ImgPath"],"http://")>-1?$Material_Json["ImgPath"]:"http://".$_SERVER['HTTP_HOST'].$Material_Json["ImgPath"],
				"Url"=>strpos($Material_Json["Url"],"http://")>-1?$Material_Json["Url"]:"http://".$_SERVER['HTTP_HOST'].$Material_Json["Url"]
			);
		}
		
		return $array;
	}
	
	private function registeruser($ownerid=0){
		$userid = 0;
		$userno = '';
		$nickname = '';
		$register = 0;
		
		$dis_config_limit_result = $this->db->GetRs('distribute_config','Distribute_Limit','where Users_ID="'.$this->usersid.'"');
		if($dis_config_limit_result['Distribute_Limit'] == 1 && $ownerid==0){
			return array('ownerid'=>$ownerid,'userno'=>$userno,'nickname'=>$nickname,'register'=>$register);
		}
		
		$userconfig = $this->db->GetRs("user_config","ExpireTime","where Users_ID='".$this->usersid."'");
		$expiretime = $userconfig["ExpireTime"];
		
		$user = $this->db->GetRs("user","User_ID,Owner_Id,Is_Distribute","where Users_ID='".$this->usersid."' and User_OpenID='".$this->openid."'");
		if(!$user && $this->userstype==1){
			include $_SERVER["DOCUMENT_ROOT"].'/include/library/weixin_token.class.php';
			$weixin_token = new weixin_token($this->db,$this->usersid);
			$uinfo = $weixin_token->GetUserInfo($this->openid);
			if(!empty($uinfo)){
				$Data = array();
				if($uinfo["nickname"]){
					$Data["User_NickName"] = $nickname = $this->removeEmoji1($uinfo["nickname"]);
				}
				if($uinfo["sex"]){
					$Data["User_Gender"] = $uinfo["sex"]==1 ? "男" : "女";
				}
				if($uinfo["province"]){
					$Data["User_Province"] = $uinfo["province"];
				}
				if($uinfo["city"]){
					$Data["User_City"] = $uinfo["city"];
				}
				if($uinfo["headimgurl"]){
					$Data["User_HeadImg"] = $uinfo["headimgurl"];
				}
				$maxUser=$this->db->GetRs("user","User_No","where Users_ID='".$this->usersid."' order by User_No desc");
				if(empty($maxUser["User_No"])){
					$User_No="600001";
				}else{
					$User_No=$maxUser["User_No"]+1;
				}
				
				if($ownerid){
					$Data["Owner_ID"] = $ownerid;
					$where = ['Users_ID' =>$this->usersid, 'User_ID' => $ownerid];
					//算出此人的Root_ID
					$user_obj = User::Multiwhere($where)
								   ->first();
					
					$root_id = $user_obj->Root_ID == 0?$ownerid:$user_obj->Root_ID;
					$Data["Root_ID"] = $root_id;
				}
				
				$Data["User_No"] = $userno = $User_No;
				$Data["User_Profile"] = 0;
				$Data["User_OpenID"] = $this->openid;
				$Data["User_Password"] = md5("123456");
				$Data["User_PayPassword"] = md5("123456");
				$Data["User_From"] = 0;
				$Data["User_CreateTime"] = time();
				$Data["User_ExpireTime"] = $expiretime==0 ? 0 : ( time() + $expiretime*86400 );
				$Data["User_Status"] = 1;
				$Data["User_Remarks"] = "";
				$Data["Users_ID"] = $this->usersid;
				$flag=$this->db->Add("user",$Data);	
				
				if($flag){
					$register = 1;
					$userid = $this->db->insert_id();
					if($ownerid>0){
						require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/weixin_message.class.php');
						$weixin_message = new weixin_message($this->db, $this->usersid, $userid);
						$weixin_message->sendmember();
					}
					//require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Ext/message.func.php');
					//user_create_tem($this->usersid,$userid,"http://".$_SERVER["HTTP_HOST"]."/api/".$this->usersid."/user/");
				}
			}
		}else{
			$ownerid = $user["Is_Distribute"] == 1 ? $user["User_ID"] : $user["Owner_Id"];
		}
		return array('ownerid'=>$ownerid,'userno'=>$userno,'nickname'=>$nickname,'register'=>$register);
	}
	
	private function removeEmoji1($text) {
		
		$regexEmoticons = '/[\x{1F600}-\x{1F64F}]/u';
		$text = preg_replace($regexEmoticons, '', $text);
		
		$regexSymbols = '/[\x{1F300}-\x{1F5FF}]/u';
		$text = preg_replace($regexSymbols, '', $text);
		
		$regexTransport = '/[\x{1F680}-\x{1F6FF}]/u';
		$text = preg_replace($regexTransport, '', $text);
		
		$regexMisc = '/[\x{2600}-\x{26FF}]/u';
		$text = preg_replace($regexMisc, '', $text);
		
		$regexDingbats = '/[\x{2700}-\x{27BF}]/u';
		$text = preg_replace($regexDingbats, '', $text);

		return $text;
	}
}
?>
