// JavaScript Document
/**
 * 点选可选属性或改变数量时修改商品价格的函数
 */
function changePrice() {
    var attr = getSelectedAttributes(document.forms['addtocart_form']);

    var qty = document.forms['addtocart_form'].elements['Qty'].value;
    var param = {
        action: 'getPrice',
        'ProductsID': Products_ID,
        'attr': attr,
    };
    $("#spec_list").attr('value', attr);
    $.get(shop_ajax_url,param,function(data){
    	if (data.status = 0) {
    		alert(data.mgs);
    	}else {
    		$(".now_price price").html(data.result);
    	}
    },'json');
}

function changeAtt(t) {
    t.lastChild.checked = 'checked';
    for (var i = 0; i < t.parentNode.childNodes.length; i++) {
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

function getSelectedAttributes(formBuy) {

    var spec_arr = new Array();

    var j = 0;

    for (i = 0; i < formBuy.elements.length; i++) {

        var prefix = formBuy.elements[i].name.substr(0, 5);

        if (prefix == 'spec_' && ((formBuy.elements[i].type == 'radio' && formBuy.elements[i].parentNode.className == 'cattsel') || (formBuy.elements[i].type == 'checkbox' && formBuy.elements[i].checked))) {
            spec_arr[j] = formBuy.elements[i].value;
			if(formBuy.elements[i].type == 'checkbox'){//多选属性特效
				formBuy.elements[i].parentNode.className = 'cattsel';
			}
            j++;
        }else if(formBuy.elements[i].type == 'checkbox' && !formBuy.elements[i].checked){//多选属性特效
			formBuy.elements[i].parentNode.className = '';
		}
    }
    return spec_arr;
}