<ul class="list-group withdraw-panel">	
            <?php foreach($bankcard_list as $key=>$item):?>
            <li class="list-group-item">
            <?=$item['Card_Name']?>&nbsp;&nbsp;&nbsp;&nbsp;<?=$item['Card_No']?>
            <a href="javascript:void" class="bank_card_edit operate push_right"><span class="fa fa-pencil grey"></span></a>
          
            <a href="javascript:void" class="bank_card_delete operate push_right"><span class="fa fa-trash grey"></span></a>
            </li>
            <?php endforeach; ?>
            
       
    <div class="clearfix"></div>
    </ul>