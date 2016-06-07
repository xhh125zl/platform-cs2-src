<?php
if(!function_exists('get_sys_modules')){//获取系统模块
	function get_sys_modules($DB){
		$modules = array();
		$DB->Get("module","*","where parentid=0 and type='module' order by listorder asc,moduleid asc");
		while($r = $DB->fetch_assoc()){
			$modules[$r["moduleid"]] = $r;	
		}
		return $modules;
	}
}

if(!function_exists('get_sys_url_menu')){//获取系统url页面二级导航
	function get_sys_url_menu($DB, $modules){
		$menus = array();
		$DB->Get("module","*","where parentid>0 and type='url' order by parentid asc,listorder asc,moduleid asc");
		while($r = $DB->fetch_assoc()){
			if(!empty($modules[$r["parentid"]])){
				$menus[$r["parentid"]]["type"][] = $r;
			}			
		}
		return $menus;
	}
}

if(!function_exists('get_sys_material')){//获取图文消息
	function get_sys_material($DB, $UsersID, $type){//type为0时自定义图文，为1时系统图文
		$material = array();
		$condition = "where Users_ID='".$UsersID."' and Material_TableID=0";
		$condition .= $type==1 ? " and Material_Table<>'0' and Material_Display=0" : " and Material_Table='0' and Material_Display=1";
		$condition .= " order by Material_ID desc";
		
		$DB->get("wechat_material","Material_ID,Material_Table,Material_Json,Material_Type",$condition);
		while($r = $DB->fetch_assoc()){
			$json=json_decode($r['Material_Json'],true);
			$r["Title"] = empty($json['Title']) ? $json[0]['Title'] : $json['Title'];			
			$material[] = $r;
		}
		return $material;
	}
}