<?php 
ini_set("display_errors","On");
$base_url = base_url();

if(empty($_SESSION["Users_Account"]))
{
	header("location:/member/login.php");
}

$action=empty($_REQUEST['action'])?'':$_REQUEST['action'];
if(!empty($action)){
	if($action == 'protitle'){
		$accountid = $_POST["AccountID"];
		$value = $_POST["Value"];
		$Flag = $DB->Set("distribute_account","Professional_Title=".$value,"where Account_ID=".$accountid);
		if($Flag){
			$Data=array("status"=>1);
		}else{
			$Data=array("status"=>0,"msg"=>"修改失败");
		}
		echo json_encode($Data,JSON_UNESCAPED_UNICODE);
		exit;
	}
}

$rsConfig = Dis_Config::find($_SESSION['Users_ID']);
$dis_title_level = Dis_Config::get_dis_pro_title($_SESSION['Users_ID']);
if(!$dis_title_level){
	$dis_title_level = array();
}
$sha_config = array();
if($rsConfig['Sha_Agent_Type'] != 0){
    $sha_config = json_decode($rsConfig['Sha_Rate'],true);  
}

 
if(isset($_GET["action"])){
	if($_GET["action"]=="del"){		
		$rs = $DB->GetRs("distribute_account","*","where Users_ID='".$_SESSION["Users_ID"]."' and Account_ID=".$_GET["AccountID"]);
		
		if($rs){
		  //针对于此平台的两次删除bug
		  $Flag = $DB->Set("user",array("Is_Distribute"=>0),"where Users_ID='".$_SESSION["Users_ID"]."' and User_ID=".$rs["User_ID"]);
		}
		
		$Flag=$DB->Del("distribute_account","Users_ID='".$_SESSION["Users_ID"]."' and Account_ID=".$_GET["AccountID"]);
		if($Flag){
			echo '<script language="javascript">alert("删除成功");window.location="'.$_SERVER['HTTP_REFERER'].'";</script>';
		}else{
			echo '<script language="javascript">alert("删除失败");history.back();</script>';
		}
		exit;
	}
	
	
	if($_GET["action"]=="pass"){//通过审核
		$Flag = Dis_Account::find($_GET['AccountID'])->update(array('Is_Audit'=>1));
		if($Flag){
			echo '<script language="javascript">alert("审核通过");window.location="'.$_SERVER['HTTP_REFERER'].'";</script>';
		}else{
			echo '<script language="javascript">alert("操作失败");history.back();</script>';
		}
		exit;
	}
	
	if($_GET["action"] == "disable"){//禁用账号
		$Flag = Dis_Account::find($_GET['AccountID'])->update(array('status'=>0));
		if($Flag){
			echo '<script language="javascript">alert("禁用成功");window.location="'.$_SERVER['HTTP_REFERER'].'";</script>';
		}else{
			echo '<script language="javascript">alert("操作失败");history.back();</script>';
		}
		
		exit;
	}
	
	if($_GET["action"] == "enable"){//开启账号
		$Flag = Dis_Account::find($_GET['AccountID'])->update(array('status'=>1));
		if($Flag){
			echo '<script language="javascript">alert("启用成功");window.location="'.$_SERVER['HTTP_REFERER'].'";</script>';
		}else{
			echo '<script language="javascript">alert("操作失败");history.back();</script>';
		}
		exit;
		
	}
	
	//开启分销代理
	if($_GET["action"]=="enable_agent"){
		$Flag = Dis_Account::find($_GET['AccountID'])->update(array('Enable_Agent'=>1,'sha_level'=>$_GET['level']));		      
		$sha_tie = new Dis_Agent_Tie();
		$sha_tie->type = 2;
		$sha_tie->Users_ID = $_SESSION["Users_ID"];
		$sha_tie->Disname = $_GET['AccountID'];
		$sha_tie->Barjson = '开启股东';
		$sha_tie->Order_CreateTime = time();
		$sha_tie->save();
		if($Flag){
			echo '<script language="javascript">alert("启用成功");window.location="'.$_SERVER['HTTP_REFERER'].'";</script>';
		}else{
			echo '<script language="javascript">alert("操作失败");history.back();</script>';
		}
		exit;
	}
	
	//关闭代理
	if($_GET["action"]=="disable_agent"){
		$Flag = Dis_Account::find($_GET['AccountID'])->update(array('Enable_Agent'=>0));
		$sha_tie = new Dis_Agent_Tie();
		$sha_tie->type = 2;
		$sha_tie->Users_ID = $_SESSION["Users_ID"];
		$sha_tie->Disname = $_GET['AccountID'];
		$sha_tie->Barjson = '关闭股东';
		$sha_tie->Order_CreateTime = time();
		$sha_tie->save();
		if($Flag){
			echo '<script language="javascript">alert("关闭成功");window.location="'.$_SERVER['HTTP_REFERER'].'";</script>';
		}else{
			echo '<script language="javascript">alert("操作失败");history.back();</script>';
		}
		exit;
	}
	
}else{
	
	$_SERVER['HTTP_REFERER'] = $_SERVER['REQUEST_URI'];	

}


//获取分销账号列表

$builder = Dis_Account::where('Users_ID',$_SESSION["Users_ID"]);

$page_base =base_url('member/distribute/account.php');

$url_param = array();	             
if(!empty($_GET["search"])){
	$url_param['search'] = 1;
	
	if(!empty($_GET["Keyword"])&&strlen(trim($_GET["Keyword"]))>0){		
		$Shop_Name = $_GET["Keyword"];
		$builder->where("Shop_Name","like",'%'.$Shop_Name.'%');
		 
		$url_param['Keyword'] = $Shop_Name;
	}
	if(!empty($_GET['level'])){
		$builder->where('Professional_Title',$_GET['level']-1);
		$url_param['level'] = $_GET['level'];
	}
	if($_GET['is_root'] == 1){
		$builder->where('invite_id',0);
	}
	$url_param['is_root'] = $_GET['is_root'];
	
}
$builder->orderBy('Account_CreateTime','desc');


$account_list = $builder->paginate(10);

$account_list->setPath(base_url('member/distribute/account.php'));
if(!empty($url_param)){
	$account_list->appends($url_param);
}

$page_links = $account_list->render();

//生成用户drop_down数组
$inviter_ids = $account_list->map(function($account){
				  return $account->invite_id;
			   })->toArray();
$user_ids = $account_list->map(function($account){
				  return $account->User_ID;
			   })->toArray();
$User_IDS = array_unique(array_merge($user_ids,$inviter_ids));
$users = User::whereIn('User_ID',$User_IDS);
$user_list = $users->get(array('User_ID','User_NickName'))->toArray();
$user_dropdown = array();
foreach($user_list as $key=>$user){
	$user_dropdown[$user['User_ID']] = $user['User_NickName'];
}
//分销商级别
$dis_level = get_dis_level($DB,$_SESSION["Users_ID"]); 
?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title></title>
<link href='/static/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/css/bootstrap.min.css?data=041607' rel='stylesheet' type='text/css' />
<link href='/static/member/css/main.css?data=041607' rel='stylesheet' type='text/css' />
<link href='/static/member/css/area_content.css?data=041607' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/js/jquery.form.js'></script>
<script type='text/javascript' src='/static/js/bootstrap.min.js'></script>
<script type='text/javascript' src='/static/member/js/global.js'></script>
</head>

<body>
<!--[if lte IE 9]><script type='text/javascript' src='/static/js/plugin/jquery/jquery.watermark-1.3.js'></script>
<![endif]-->
<div id="iframe_page">
  <div class="iframe_content">
    <link href='/static/member/css/distribute.css?data=041607' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/member/js/distribute/account.js'></script>
    <div class="r_nav">
      <ul>
        <li class="cur"> <a href="account.php">分销账号管理</a> </li>
      </ul>
    </div>
    <link href='/static/js/plugin/lean-modal/style.css' rel='stylesheet' type='text/css' />
    <script language="javascript">
	var  base_url = '<?=$base_url?>';
	var level_ary=Array();
	level_ary[0] = '无';
	<?php foreach($dis_title_level as $k=>$v){
		 echo 'level_ary['.$k.']="'.$v['Name'].'";';
	}?>
	$(document).ready(function(){account_obj.account_init();});
</script>
    <div id="update_post_tips"></div>
    <div>
   
      </div>
    <div id="user" class="r_con_wrap">
    	
      <form class="search" id="search_form" method="get" action="?">
         <span> 关键字：</span>
          <input type="text" name="Keyword" value="" class="form_input" size="15" />
          <span>&nbsp;类型&nbsp;</span>
   		  <select name="is_root">
          <option value="0">&nbsp;&nbsp;全部&nbsp;&nbsp;</option>
          <?php if(!empty($_GET['is_root'])&&$_GET['is_root'] == 1):?>
          <option value="1" selected>&nbsp;&nbsp;根店&nbsp;&nbsp;</option></select>
          <?php else: ?>
          <option value="1">&nbsp;&nbsp;根店&nbsp;&nbsp;</option></select>
		  <?php endif;?>
		  <span>&nbsp;爵位级别&nbsp;</span>
		  <select name="level">
		  <option value="0">全部</option>
		  <option value="1">无级别</option>
		  <?php foreach($dis_title_level as $key=>$l){?>
		  <option value="<?php echo $key+1;?>"><?php echo $l["Name"];?></option>
		  <?php }?>
		  </select>
          &nbsp;&nbsp;
          <input type="hidden" name="search" value="1" />
          <input type="submit" class="search_btn" value=" 搜索 " />
      </form>
      
      <table width="100%" align="center" border="0" cellpadding="5" cellspacing="0" class="r_con_table">
        <thead>
          <tr>
            <td width="2%" nowrap="nowrap">序号</td>
            <td width="5%" nowrap="nowrap">推荐人</td>
            <td width="8%" nowrap="nowrap">店名</td>
            <td width="8%" nowrap="nowrap">微信名</td>
            <td width="5%" nowrap="nowrap">佣金余额</td>    
            <td  width="5%" nowrap="nowrap">审核状态</td>
            <td width="6%" nowrap="nowrap">总收入</td>
            <td width="6%" nowrap="nowrap">销售额</td>
            <td width="5%" nowrap="nowrap">爵位</td>
            <td width="5%" nowrap="nowrap">分销商级别</td>
            <td width="5%" nowrap="nowrap">加入时间</td>
            <td width="5%" nowrap="nowrap">状态</td>
            <td width="11%" nowrap="nowrap" class="last"><strong>操作</strong></td>
          </tr>
        </thead>
        <tbody>
          <?php 
	
		$account_array = $account_list->toArray();
		foreach($account_array['data'] as $key=>$account){
			$Total_Sales[$key] = Order::where(array('Owner_ID'=>$account['User_ID'],'Order_Status'=>4))->sum('Order_TotalPrice');

			?>
      		
            
            <tr UserID="<?=$account['User_ID']?>" AccountID="<?=$account['Account_ID']?>">
          	<td nowarp="nowrap"><?=$account['Account_ID']?></td>  
            <td nowrap="nowraqp">
		
            <?php 
				if($account['invite_id'] == 0){
					$inviter_name = '来自总店';
				}else{
					$inviter_name =  !empty($user_dropdown[$account['invite_id']])?$user_dropdown[$account['invite_id']]:'信息缺失';
				}

			?>
            
            <span><?=$inviter_name?></span>
            </td>
            <td nowarp="nowrap" field=1><?=$account['Shop_Name']?></td>
            <td nowarp="nowrap"><?= !empty($user_dropdown[$account['User_ID']])?$user_dropdown[$account['User_ID']]:'信息缺失';?></td>
            <td nowarp="nowrap">&yen;<?=round_pad_zero($account['balance'],2)?></td>
            <td nowarp="nowrap"><?=$account['Is_Audit']?'已通过':'未通过'?></td>
            <td nowarp="nowrap">&yen;<?=round_pad_zero($account['Total_Income'],2)?></td>
            <td nowrap="nowrap">&yen;<?=round_pad_zero(empty($Total_Sales[$key])?'0':$Total_Sales[$key],2)?>元</td>
            <td nowrap="nowrap" class="upd_selecjiejiet" field="5"><span class="upd_txt"><?php echo !empty($dis_title_level[$account['Professional_Title']])?$dis_title_level[$account['Professional_Title']]['Name']:'无'; ?></span></td>
            <td nowarp="nowrap"><?=empty($dis_level[$account['Level_ID']]['Level_Name'])?'无':$dis_level[$account['Level_ID']]['Level_Name']?></td>
			<td nowrap="nowrap"><?php echo date("Y-m-d H:i:s",$account['Account_CreateTime']) ?></td>
          	<td nowrap="nowrap">
            	<?php if($account['status'] ==1 ):?>
                	<img src="/static/member/images/ico/yes.gif"/>
				<?php else: ?>
                    <img src="/static/member/images/ico/no.gif"/>
                <?php endif; ?>
            	
            </td>
            <td nowrap="nowrap" class="last">
              <!-- 代理开关begin -->              
              <?php if($rsConfig['Sha_Agent_Type'] != 0):?>
				<?php if($rsConfig['Sha_Agent_Type'] == 1): ?>					
                 		<?php if($account['Enable_Agent'] == 0): ?>
<!--                			<a href="?action=enable_agent&AccountID=<?php //echo $account['Account_ID'];?>"/>开启股东</a>|-->
                                <a href="javascript:void(0)" url="<?php echo $account['Account_ID'];?>" onclick="onSha(this)">开启股东</a>
                  		<?php else: ?>
                    		<a href="?action=disable_agent&AccountID=<?php echo $account['Account_ID'];?>"/>关闭股东</a>|
                    	<?php endif;?>					
					<?php endif;?>
					<?php endif;?>					
                <?php if($rsConfig['Dis_Agent_Type'] == 1): ?>
                	<a class="agent_info" agent-id="<?=$account['Account_ID']?>" href="javascript:void(0)"/>代理信息</a>|
                <?php endif; ?>			 
              <!-- 代理开关end -->  
            <?php if($account['Is_Audit'] == 0): ?>
            <a href="?action=pass&AccountID=<?=$account['Account_ID']?>">通过</a>
            <?php endif; ?>
            <?php if($account['status'] == 1):?>
            <a href="?action=disable&AccountID=<?=$account['Account_ID']?>" onClick="if(!confirm('禁用后此分销商不可分销,你确定要禁用么？')){return false};">禁用</a>|
            <a href="account_posterity.php?User_ID=<?=$account['User_ID']?>" >下属</a>
          	<?php else: ?>
            <a href="?action=enable&AccountID=<?=$account['Account_ID']?>" title="启用" >启用</a></td>
            <?php endif; ?>
            <br><span  style="display:none"> 
                <?php
                if($rsConfig['Sha_Agent_Type'] != 0){
                    if(!empty($sha_config['sha'])){
                        foreach ($sha_config['sha'] as $k => $v) { ?>
                            <a href="?action=enable_agent&AccountID=<?=$account['Account_ID']?>&level=<?=$k?>"/><?php echo $v['name'];?></a>&nbsp;&nbsp;
                <?php        }
                    }
                }    
                ?>
              </sapn>
        	</td>
          </tr>
          <?php  }
		  ?>
		 
        </tbody>
      </table>
      <div class="page"><?=$page_links?></div>
     	
      
    </div>
  </div>
  
  
  
</div>
</div>

 <!-- 代理信息modal begin -->
    	<div class="container">
       		<div class="row">

		<div class="modal"  role="dialog" id="agent-info-modal">
  <div class="modal-dialog">
    
    	<div class="modal-content">

        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
          <h5 class="modal-title" id="mySmallModalLabel">代理信息</h5>
        </div>
        <div class="modal-body">
             <p>正在加载中...</p>
            <div class="clearfix"></div>
        </div>
        <div class="modal-footer">
       	<a class="btn btn-default" id="confirm_dis_area_agent_btn">确定</a>
        <a class="btn btn-danger" id="cancel_shipping_btn close" data-dismiss="modal">取消</a>
        </div>
      </div>
  </div>
</div>
    
    
            </div>
        </div>
<!-- 代理信息modal end -->

<script>
function onSha(t){
    $(t).parent().find('span').css('display','block');
        //$(t).blur(function(){
        $(t).click(function(){
            $(t).parent().find('span').toggle();
        })
           // $(t).parent().find('span').css('display','none');
        //})
}
</script>
</body>
</html>