<?php
//如果随机出来后的加价砍价后产品价格小于底价
			$memberActivity =  array();
			$memberActivity['Cur_Price'] = 1528;
			$memberActivity['Helper_Count'] = 1;
			$Record_Reduce = 92;
			$Bottom_Price  =1500;
			
			if($memberActivity['Cur_Price'] - $Record_Reduce < $Bottom_Price){
				$Record_Reduce = $memberActivity['Cur_Price'] - $Bottom_Price;
			}
			
			$data = array(
				'Cur_Price' => $memberActivity['Cur_Price']-$Record_Reduce,
				'Helper_Count' => $memberActivity['Helper_Count']+1
			);
	
			var_dump($data);