<?php
class biz{
	var $db;
	var $usersid;
	var $table;	
	var $skin_table;
	var $home_table;
	var $cat_table;
	var $products_table;
	var $primary_key;

	function __construct($DB,$usersid){
		$this->db = $DB;
		$this->usersid = $usersid;
		$this->table = "shop_biz";
		$this->skin_table = "shop_biz_skin";
		$this->home_table = "shop_biz_home";
		$this->cat_table = "shop_biz_category";
		$this->products_table = "shop_products";
		$this->primary_key = "Biz_ID";
	}
	//商家
	public function get_one($itemid){
		$r = $this->db->GetRs($this->table,"*","where Users_ID='".$this->usersid."' and `".$this->primary_key."`=".$itemid);
		return $r;
	}
	
	public function edit($data,$itemid){
		$flag = $this->db->Set($this->table,$data,"where Users_ID='".$this->usersid."' and `".$this->primary_key."`=".$itemid);
		return $flag;
	}
	/*edit in 20160330*/
	public function add($data){
		$flag = $this->db->Add($this->table,$data);		
		if($flag){
			$bizid = $this->db->insert_id();
			$skin = $this->get_one_skin(1);
		
			$Data=array(
				"Users_ID"=>$this->usersid,
				"Biz_ID"=>$bizid,
				"Skin_ID"=>1,
				"Home_Json"=>$skin["Skin_Json"]
			);
			$this->db->Add($this->home_table,$Data);
		}
		return $bizid;
	}
	
	public function delete($itemid){
		$flag=$this->db->Del($this->table,"Users_ID='".$this->usersid."' and `".$this->primary_key."`=".$itemid);
		return $flag;
	}
	
	public function get_list($fields = "*"){
		$condition = "where Users_ID='".$this->usersid."' order by `".$this->primary_key."` desc";
		$lists = array();
		$this->db->Get($this->table,$fields,$condition);
		while($r = $this->db->fetch_assoc()){
			$lists[] = $r;
		}
		return $lists;
	}
	
	public function get_page_list($fields = "*",$condition){
		$lists = array();
		$this->db->getPage($this->table,$fields,$condition,20);
		while($r = $this->db->fetch_assoc()){
			$lists[] = $r;
		}
		return $lists;
	}
	
	public function checkaccount($account){
		$flag = true;
		$r = $this->db->GetRs($this->table,"Biz_ID","where Biz_Account = '".$account."'");
		if($r){
			$flag = false;
		}
		return $flag;
	}
	
	private function get_one_skin($skinid){
		$r = $this->db->GetRs($this->skin_table,"Skin_Json","where Skin_ID=".$skinid);
		return $r;
	}
	
	//商家申请
	public function get_apply_list($condition){
		$lists = array();
		$this->db->getPage("shop_biz_apply","*",$condition,20);
		while($r = $this->db->fetch_assoc()){
			$lists[] = $r;
		}
		return $lists;
	}
	
	public function delete_apply($itemid){
		$flag=$this->db->Del("shop_biz_apply","Users_ID='".$this->usersid."' and `ItemID`=".$itemid);
		return $flag;
	}
	
	public function edit_apply($data,$itemid){
		$flag=$this->db->Set("shop_biz_apply",$data,"where Users_ID='".$this->usersid."' and ItemID=".$itemid);
		return $flag;
	}
	
	//商家后台设置
	public function get_skin_list(){
		$lists = array();
		$this->db->Get($this->skin_table,"*","order by Skin_ID asc");
		while($r = $this->db->fetch_assoc()){
			$lists[] = $r;
		}
		return $lists;
	}
	
	public function get_one_home($itemid,$skinid){
		$r = $this->db->GetRs($this->home_table,"Home_Json","where Users_ID='".$this->usersid."' and Biz_ID=$itemid and Skin_ID=$skinid");
		return $r;
	}
	
	public function set_skin($itemid,$skinid){
		$flag=$this->db->Set($this->table,array("Skin_ID"=>$skinid),"where Users_ID='".$this->usersid."' and Biz_ID=".$itemid);
		$r = $this->get_one_home($itemid,$skinid);
		if(!$r){
			$skin = $this->get_one_skin($skinid);
			$Data=array(
				"Users_ID"=>$this->usersid,
				"Biz_ID"=>$itemid,
				"Skin_ID"=>$skinid,
				"Home_Json"=>$skin["Skin_Json"]
			);
			$add = $this->db->Add($this->home_table,$Data);
			$flag = $flag && $add;
		}
		return $flag;
	}
	
	public function set_home($itemid, $skinid, $data){
		$flag = $this->db->Set($this->home_table,$data,"where Users_ID='".$this->usersid."' and Biz_ID=".$itemid." and Skin_ID=".$skinid);
		return $flag;
	}
	
	public function set_homejson_array($array){
		$data = array();
		foreach($array as $value){
			if(is_array($value['Title'])){
				$value['Title'] = json_encode($value['Title']);
			}
			if(is_array($value['ImgPath'])){
				$value['ImgPath'] = json_encode($value['ImgPath']);
			}
			if(is_array($value['Url'])){
				$value['Url'] = json_encode($value['Url']);
			}
			$data[] = $value;
		}
		return $data;
	}
	
	//商家自定义分类
	public function get_one_cat($itemid,$catid){
		$r = $this->db->GetRs($this->cat_table,"*","where Users_ID='".$this->usersid."' and Biz_ID=$itemid and Category_ID=$catid");
		return $r;
	}
	
	public function add_cat($data){
		$flag=$this->db->Addall($this->cat_table,$data);
		return $flag;
	}
	
	public function edit_cat($data,$catid){
		$flag=$this->db->Set($this->cat_table,$data,"where Category_ID=".$catid);
		return $flag;
	}
	
	public function delete_cat($catid){
		$r = $this->db->GetRs($this->cat_table,"count(Category_ID) as num","where Category_ParentID=".$catid);
		if($r["num"]>0){
			return array("status"=>0,""=>"该分类下有子分类，不能删除");
		}
		$r = $this->db->GetRs($this->products_table,"count(Products_ID) as num","where Products_BizCategory=".$catid);
		if($r["num"]>0){
			return array("status"=>0,""=>"该分类下有产品，不能删除");
		}
		$flag=$this->db->Del($this->cat_table,"Category_ID=".$catid);
		return $flag ? array("status"=>1,"msg"=>"删除成功") : array("status"=>0,"msg"=>"删除失败");
	}
	
	public function get_cat_biz($condition){
		$this->db->get($this->cat_table,"Category_ID,Category_Name,Category_ParentID",$condition." order by Category_ParentID asc,Category_Index asc,Category_ID asc");
		$shop_cates = array();
		while($r = $this->db->fetch_assoc()){
			if($r["Category_ParentID"]==0){
				$shop_cates[$r["Category_ID"]] = $r;
			}else{
				$shop_cates[$r["Category_ParentID"]]["child"][] = $r;
			}
		}
		return $shop_cates;
	}
	
	public function get_cat_child($catid){
		$this->db->get($this->cat_table,"Category_ID,Category_Name","where Category_ParentID=$catid order by Category_Index asc,Category_ID asc");
		$shop_cates = array();
		while($r = $this->db->fetch_assoc()){
			$shop_cates[] = $r;
		}
		return $shop_cates;
	}
}
?>