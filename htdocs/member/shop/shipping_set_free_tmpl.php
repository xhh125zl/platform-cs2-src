<script id="set_free_tpl" type="text/x-jquery-tmpl">
	<table style="display: table;">		
			   	 		<tr>
			   	 			<th>选择地区</th>
			   	 			
			   	 			<th>设置包邮条件</th>
			   	 			<th>操作</th>
			   	 		</tr>
						
 			{{tmpl($data) '#set_free_tr'}}
	</table>
</script>


<script id="set_free_tr" type="text/x-jquery-tmpl">
    
	<tr data-index="{{= tr_index}}">
	   <td>
			
            <span>未制定区域</span>
			<a href="javascript:void(0)" area_type='set_free' area_value_container="address-area-{{= tr_index}}" class="Edit_Area" title="编辑运送区域" data-areas="">编辑</a>
			
			<input class="address-area" name="areas[]" id="address-area-{{= tr_index}}" value="" type="hidden">
			<input class="area_desc" name="areas_desc[]" id="address-desc-{{= tr_index}}" value="" type="hidden">
		</td>
        
		

		<td>
		   
			<select class="J_ChageContion" name="designated[]">
				
					<option value="0" selected="">件数</option>
			    
					<option value="1">金额</option>
			    
					<option value="2">件数 + 金额</option>
			    
			</select>
			<p class="free-contion"> 满 <input value="" class="input-text" name="preferentialQty[]" notnull type="text"> 件包邮</p>
			
		</td>
		<td>
			<a data-spm-anchor-id="0.0.0.0" href="javascript:void(0)" class="J_AddItem small-icon"></a>
			<a href="javascript:void(0)" class="J_DelateItem small-icon"></a>
		</td>
	</tr>
</script>

<script id="free-contion" type="text/x-jquery-tmpl">
	<p class="free-contion"> 
	{{if contion_index == 0}}
		满 <input value="" class="input-text" name="preferentialQty[]" notnull type="text"> 件包邮
		   <input value="0" type="hidden" name="preferentialMoney[]" />
	{{else contion_index == 1}}
		满 <input class="input-text " name="preferentialMoney[]" value="" notnull type="text">  元包邮
		   <input value="0"  name="preferentialQty[]" notnull type="hidden">
	{{else contion_index == 2}}
		满 <input value="" class="input-text" name="preferentialQty[]"  notnull type="text"> 件 , <input name="preferentialMoney[]" class="input-text input-65" value="" type="text"> 元以上 包邮
	
	{{/if}}
	</p>

</script>