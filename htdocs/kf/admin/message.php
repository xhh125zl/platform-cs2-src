<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
if($_POST["ctrlTyp"]){
	switch($_POST["ctrlTyp"]){
		case 'add':
			$rsKF=$DB->GetRs("kf_account","*","where Account_ID='".$_POST["KfId"]."'");
			$Data = array(
				"Users_ID"=>$_POST["UsersID"],
				"KF_Account"=>$rsKF["Account_Name"],
				"Lan_Content"=>$_POST["lang"],
				"Lan_CreateTime"=>time()
			);
			$Flag = $DB->Add("kf_language",$Data);
			if($Flag){
				$Lan_ID = $DB->insert_id();
				$line = '<tr>
                                	<td height="28"  class="t1">
                                    	<span  class="tbAddFrame hand fz_12px">
											'.$_POST["lang"].'                                        </span>
                                    </td>
                                    <td align="center">
                                    	<span class="tbEdit hand" id="ed'.$Lan_ID.'" onClick="webchat_inner.ctrlLangEdit(this,'.$Lan_ID.')">
                                        	<img src="/kf/images/main/mod.gif" />
                                        </span>
                                        <span class="tbDel hand" id="delbtn'.$Lan_ID.'" onClick="webchat_inner.ctrlLangDel(this,'.$Lan_ID.')">
                                        	<img src="/kf/images/main/del.gif" />
                                        </span>
                                    </td>
                                </tr>';
				$Data = array(
					"status"=>1,
					'msg'=>$line
				);
			}else{
				$Data = array(
					"status"=>0,
					'msg'=>'添加失败'
				);
			}
		break;
		case 'mod':
			$LanID = $_POST["CLId"];
			$Data = array(
				"Lan_Content"=>$_POST["lang"]
			);
			$Flag = $DB->Set("kf_language",$Data,"where Lan_ID=".$LanID);
			if($Flag){
				$Data = array(
					"status"=>1			  
				);
			}else{
				$Data = array(
					"status"=>0,
					'msg'=>'修改失败！'
				);
			}
		break;
		case 'del':
			$LanID = $_POST["CLId"];
			$Flag=$DB->Del("kf_language","Lan_ID=".$LanID);
			if($Flag){
				$Data = array(
					"status"=>1
				);
			}else{
				$Data = array(
					"status"=>0,
					"msg"=>'删除失败'
				);
			}
		break;
	}
	echo json_encode($Data,JSON_UNESCAPED_UNICODE);
}
?>