<script id="tpl_except" type="text/x-jquery-tmpl">
    <div class="tpl_except">
    <table cellspacing="0">
        <tr>
            <th>运送到</th>
            <th>按{{= method_name}}({{= unit}})</th>
            <th>首费</th>
            <th>续{{= method_name}}({{= unit}})</th>
            <th>续费</th>
            <th>操作</th>
        </tr>
       
            {{tmpl($data) '#tpl_except_tr'}}
       
    </table>
</div>

</script>

<script id="tpl_except_tr" type="text/x-jquery-tmpl">
   <tr>
                <td><span>未添加区域</span> <a class="Edit_Area" href="javascript:void(0)"  area_type='deliver_business' area_value_container="{{= business_alias}}_areas_n{{= num}}" >编辑</a>
					<input name="{{= business_alias}}_areas_n{{= num}}" id="{{= business_alias}}_areas_n{{= num}}" class="{{= business_alias}}_areas" value="" type="hidden"  >
					<input name="{{= business_alias}}_desc_n{{= num}}" class="area_desc" value="" type="hidden">
				</td>
                <td>
                    <input name="{{= business_alias}}_start_n{{= num}}" data-field="start" value="1" class="input-text " autocomplete="off" maxlength="6" aria-label="首{{= unit}}" type="text" notnull>
                </td>
                <td>
                    <input name="{{= business_alias}}_postage_n{{= num}}" data-field="postage" value="" class="input-text" autocomplete="off" maxlength="6" aria-label="首费" type="text" notnull>
                </td>
                <td>
                    <input name="{{= business_alias}}_plus_n{{= num}}" data-field="plus" value="1" class="input-text " autocomplete="off" maxlength="6" aria-label="续{{= unit}}" type="text" notnull>
                </td>
                <td>
                    <input name="{{= business_alias}}_postageplus_n{{= num}}" data-field="postageplus" value="" class="input-text " autocomplete="off" maxlength="6" aria-label="续费" type="text" notnull>
                </td>
                <td><a href="javascript:void(0)" class="delete_rule_link">删除</a></td>
            </tr>
</script>

