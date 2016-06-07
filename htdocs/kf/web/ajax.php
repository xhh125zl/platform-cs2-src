<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
function get_chat_file($fileid) {
	return '/file/chat/'.substr($fileid, 0, 2).'/'.$fileid.'.php';
}
$KfId = $_POST["KfId"];
$rsKF=$DB->GetRs("kf_account","*","where Account_ID=".$KfId);
if(!$rsKF) exit();
$UserId = $_POST["UserId"];
$rsUser=$DB->GetRs("kf_message","*","where Message_ID=".$UserId." and KF_Account='".$rsKF["Account_Name"]."'");
if(!$rsUser) exit();
$fileid = md5($UserId.$KfId);

if($_POST["type"]){
	switch($_POST["type"]){
		case 'add':
			if(!$_POST["Msg"]){
				exit;
			}else{
				$word = $_POST["Msg"];
			}
			$encoding = mb_detect_encoding($word, array('ASCII','UTF-8','GB2312','GBK','BIG5'));
			$word = mb_convert_encoding($word, 'utf-8', $encoding);
			$filename = get_chat_file($fileid);
			if(is_file($_SERVER['DOCUMENT_ROOT'].$filename)) {
				//控制记录体积为500K
				if(filesize($_SERVER['DOCUMENT_ROOT'].$filename) > 500*1024){
					if(!$fp = fopen($_SERVER['DOCUMENT_ROOT'].$filename,'w+')) {
						exit();
					}else{
						fwrite($fp,'<?php exit;?>');
					}
					fclose($fp);
				}
			} else {
				$targetFolder = '/file/chat/'.substr($fileid, 0, 2).'/';
				if(@is_dir($_SERVER['DOCUMENT_ROOT'] .$targetFolder)===false){
					mkdir($_SERVER['DOCUMENT_ROOT'] .$targetFolder,0777);
				}
				file_put_contents($_SERVER['DOCUMENT_ROOT'].$filename, '<?php exit;?>');
			}
			$word = stripslashes(trim($word));
			$word = strip_tags($word);
			$word = nl2br($word);
			$word =  str_replace(array(chr(13), chr(10), "\n", "\r", "\t", '  '),array('', '', '', '', '', ''), $word);
			$word = str_replace('|', ' ', $word);
			$time = time();
			if($word && $fp = fopen($_SERVER['DOCUMENT_ROOT'].$filename,"a")) {
				fwrite($fp, $time."|".$_POST["SendTyp"]."|".$UserId."|".$word."\n");
				fclose($fp);
				if($_POST["SendTyp"]==1){
					$word = '<img src="'.$word.'" width="130" />';
				}else{
					if(strpos($word,'[face')!==false){
						$word = '<img src="/kf/images/expression/'.substr($word, 5, -1).'.gif" />';
					}
				}
				$Data = array(
					"Message_LastTime"=>time()	  
				);
				$Flag = $DB->Set("kf_message",$Data,"where Message_ID=".$UserId);
				$Data = array(
					"status"=>1,
					"MsgList"=>'<div class="message_list_item">
									<div class="message_person_img">
										<div class="p_img"><img src="/kf/images/main/m2.jpg" width="100%" height="100%" /></div>
										<div class="p_name">我</div>
									</div>
									<div class="message_item">'.$word.'</div>
									<div class="message_acctime">'.date("H:i",$time).'</div>
									<div class="clear"></div>
								 </div>
								 <div class="clear"></div>'
				);
			}else{
				$Data = array(
					"status"=>0
				);
			}
		break;
		case 'show':
			$filename = get_chat_file($fileid);
			$data = @file_get_contents($_SERVER['DOCUMENT_ROOT'].$filename);
			$data = trim(substr($data, 13));
			if($data) {
				$html='';
				$data = explode("\n", $data);
				foreach($data as $d){
					list($time, $type, $id, $word) = explode("|", $d);
					if($type==1){
						$word = '<img src="'.$word.'" width="130" />';
					}else{
						if(strpos($word,'[face')!==false){
							$word = '<img src="/kf/images/expression/'.substr($word, 5, -1).'.gif" />';
						}
					}
					if($id == $UserId){
						$html .= '<div class="message_list_item">
									<div class="message_person_img">
										<div class="p_img"><img src="/kf/images/main/m2.jpg" width="100%" height="100%" /></div>
										<div class="p_name">我</div>
									</div>
									<div class="message_item">'.$word.'</div>
									<div class="message_acctime">'.date("H:i",$time).'</div>
									<div class="clear"></div>
								 </div>
								 <div class="clear"></div>';
					}elseif($id == $KfId){
						$html .= '<div class="message_list_item">
									<div class="chat_message_person_img">
										<div class="p_img"><img src="/kf/images/main/m1.jpg" width="100%" height="100%" /></div>
										<div class="p_name">客服</div>
									</div>
									<div class="chat_message_item">'.$word.'</div>
									<div class="chat_message_acctime">'.date("H:i",$time).'</div>
									<div class="clear"></div>
								 </div>
								 <div class="clear"></div>';
					}
				}
				$Data = array(
					"status"=>1,
					"MsgList"=>$html
				);
			}else{
				$Data = array(
					"status"=>0
				);
			}
		break;
	}
	echo json_encode($Data,JSON_UNESCAPED_UNICODE);
}
?>