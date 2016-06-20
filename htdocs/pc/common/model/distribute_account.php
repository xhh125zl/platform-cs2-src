<?php
namespace common\model;
class distribute_account extends \base\model{
	/**
	 * 返回此分销账号完整父路径
	 * @param  int $level 此店的分销商层数
	 * @return String $fullDisPath
	 */
	public function getFullDisPath($rsAccount) {
		if(!empty($rsAccount['dis_path'])) {
			$fullDisPath = trim($rsAccount['dis_path'], ',');
			$Users_ID = $rsAccount['users_id'];
			$disCollection  = $this->field('User_ID,Dis_Path')->where(array('Users_ID'=>$Users_ID))->select();
			$disDictionary = get_dropdown_collection($disCollection, 'User_ID');
			$Dis_Path = trim($rsAccount['dis_path'], ',');
		
			//向上循环，直至找到自己的根店分销商
			while(!empty($Dis_Path)) {
				$first = strstr($Dis_Path, ',', TRUE);
				$first = $first ? $first : $Dis_Path;
				
				$parentDistirbuteAccount = $disDictionary[$first];
				
				$Dis_Path = $parentDistirbuteAccount['Dis_Path'];
				$Dis_Path = trim($Dis_Path, ',');
			    if(!empty($Dis_Path)) {
					$fullDisPath = $Dis_Path . ',' . $fullDisPath;
				}
			}			
		    return $fullDisPath;
		}else {
			$fullDisPath = '';
		}
		return $fullDisPath ;
	}
}
