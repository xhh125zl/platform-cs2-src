// JavaScript Document
/**
 * 点选可选属性或改变数量时修改商品价格的函数
 */
function changePrice()
{
  var attr = getSelectedAttributes(document.forms['addtocart_form']);
  var qty = document.forms['addtocart_form'].elements['Qty'].value;
  var no_attr_price  =  document.forms['addtocart_form'].elements['no_attr_price'].value;
  var param = {action:'price',no_attr_price:no_attr_price,'ProductsID':Products_ID,'attr':attr,'qty':qty,'UsersID':UsersID};
  
  $("#spec_list").attr('value',attr);
  $.get(base_url+'api/shop/cart/ajax.php',param,function(data){
  		 if (data.status = 0)
 		 {
    		alert(data.mgs);
  		 }
  		 else
  		 {
			$("#cur_price").attr('value',data.result);
			$("#cur-price-txt").html(data.result*qty);
		 }
		 
  },'json');
}


function changeAtt(t) {
t.lastChild.checked='checked';
for (var i = 0; i<t.parentNode.childNodes.length;i++) {
        if (t.parentNode.childNodes[i].className == 'cattsel') {
            t.parentNode.childNodes[i].className = '';
        }
    }
t.className = "cattsel";
changePrice();
}

/**

 * 获得选定的商品属性

 */

function getSelectedAttributes(formBuy)

{

  var spec_arr = new Array();

  var j = 0;

  for (i = 0; i < formBuy.elements.length; i ++ )

  {

    var prefix = formBuy.elements[i].name.substr(0, 5);



    if (prefix == 'spec_' && (

       ((formBuy.elements[i].type == 'radio' && formBuy.elements[i].parentNode.className == 'cattsel') || (formBuy.elements[i].type == 'checkbox' && formBuy.elements[i].checked)) ||

      formBuy.elements[i].tagName == 'SELECT'))

    {

      spec_arr[j] = formBuy.elements[i].value;

      j++ ;

    }

  }



  return spec_arr;

}

function mauual_check(){
 var attr_id_str =  $("#spec_list").attr('value');
 var attr_array = attr_id_str.split(',');
 

 for(var product_attr_id in attr_array){
	 $('#spec_value_'+attr_array[product_attr_id]).prop('checked',true);
 }
}
