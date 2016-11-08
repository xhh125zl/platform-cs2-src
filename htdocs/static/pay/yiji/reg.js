jQuery.validator.addMethod( "isCheckName",function(value,element){       
    var pattern =/^([\u4E00-\u9FA5\uF900-\uFA2Da-zA-Z])+$/;    
        if(value !=''){
            if(!pattern.exec(value)){
                return false;
            }
        }
        return true;     
}); 
jQuery.validator.addMethod( "checkemail",function(value,element){       
    var pattern = /^([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/;    
        if(value !=''){
            if(!pattern.exec(value)){
                return false;
            }
        }
        return true;     
});

jQuery.validator.addMethod( "isUserName",function(value,element){       
    var pattern =/^([a-zA-Z0-9])+$/;    
        if(value !=''){
            if(!pattern.exec(value)){
                return false;
            }
        }
        return true;     
});

jQuery.validator.addMethod( "isSimpleChar",function(value,element){       
    var pattern =/^([0-9\-])+$/;    
        if(value !=''){
            if(!pattern.exec(value)){
                return false;
            }
        }
        return true;     
});
jQuery.validator.addMethod( "isdate",function(value,element){       
    var pattern =/^(\d{4})-(\d{2})-(\d{2})$/;    
        if(value !=''){
            if(!pattern.exec(value)){
                return false;
            }
        }
        return true;     
});


jQuery.validator.addMethod( "isScope",function(value,element){       
    var pattern =/^([\u4E00-\u9FA5\uF900-\uFA2D\/])+$/;    
        if(value !=''){
            if(!pattern.exec(value)){
                return false;
            }
        }
        return true;     
}); 
jQuery.validator.addMethod( "isSpecialChar",function(value,element){       
    var pattern =/^([a-zA-Z0-9\.@_])+$/;    
        if(value !=''){
            if(!pattern.exec(value)){
                return false;
            }
        }
        return true;     
});

jQuery.validator.addMethod( "isArticle",function(value,element){       
    var pattern =/^([\'\"\[\]\.\?\{\}])+$/;

        if(value !=''){
            if(pattern.exec(value)){
                return false;
            }
        }
        return true;     
});
jQuery.validator.addMethod( "isCompire",function(value,element){
    var PriceY = parseFloat($.trim(value));
    var PriceX = parseFloat($.trim($("input[name='PriceX']").val()));
    if(PriceX<PriceY){
       return false;
    }
    return true;     
});
jQuery.validator.addMethod( "isSumCompire",function(value,element){
    var PriceX = parseFloat($.trim($("input[name='PriceX']").val()));
    var xiangoutimes = parseFloat($.trim(value));
    var PriceY = parseFloat($.trim($("input[name='PriceY']").val()));
    var split = parseFloat(PriceY/PriceX);
    if(split<xiangoutimes){
       return false;
    }
    return true;     
});
jQuery.validator.addMethod("isMobile", function(value, element) {
    var length = value.length;
    var mobile = /^(13[0-9]{9})|(18[0-9]{9})|(14[0-9]{9})|(17[0-9]{9})|(15[0-9]{9})$/;
    return this.optional(element) || (length == 11 && mobile.test(value));
});
//判断营业执照号码是否合法
jQuery.validator.addMethod("isLicenceNo", function(value, element) {
    var ret=false;
    if(value=="" || value.length==0) return false;
    if(value.length==15){
        var sum=0;
        var s=[];
        var p=[];
        var a=[];
        var m=10;
        p[0]=m;
        for(var i=0;i<value.length;i++){
           a[i]=parseInt(value.substring(i,i+1),m);
           s[i]=(p[i]%(m+1))+a[i];
           if(0==s[i]%m){
             p[i+1]=10*2;
           }else{
             p[i+1]=(s[i]%m)*2;
            }    
        }                                       
        if(1==(s[14]%m)){
           //营业执照编号正确!
            ret=true;
        }else{
           //营业执照编号错误!
            ret=false;
         }
    }else if(value.length==24){
        ret=true;
    }else{
        ret=false;
    }
    return ret;
});

var nopersonal = {
    rules:{
        mobile: {
          required: true,
          isMobile:true,
          number:true
        },
        enterpriseName: {
          required: true,
          minlength:5,
          maxlength:30,
          isCheckName:true
        },
        licenceNo: {
          required: true,
          isLicenceNo:true
        },
        address: {
          required: true,
          minlength:1,
          maxlength:50,
          isCheckName:true
        },
        organizationCode:{
          required: true,
          number:true
        },
        businessTerm:{
          required: true,
          isdate:true,
          isSimpleChar:true
        },
        businessScope:{
          required: true,
          isScope:true
        }
    },
    messages:{
       mobile: {
        required: "请输入手机号",
        isMobile:"请输入正确的手机号",
        number:"请输入正确的手机号"
      },
       enterpriseName:{
          required: "请输入企业名称",
          minlength:"企业名称应在5-30个字符之间",
          maxlength:"企业名称应在5-30个字符之间",
          isCheckName:"企业名称必需由英文字母或者汉字组成"
        },
        licenceNo:{
          required: "请输入营业执照号码",
          isLicenceNo:"营业执照号必需由15或者24位数字组成",
          number:"请输入正确的营业执照号"
        },
        address: {
          required: "请输入单位所在地址",
            minlength:"地址最多不能超过50个字符",
            maxlength:"地址最多不能超过50个字符",
            isCheckName:"地址必需由英文字母或者汉字组成"
        },
        organizationCode:{
          required: "组织机构代码不能为空",
          number:"请输入正确的组织机构代码"
        },
        businessTerm:{
          required: "营业期限不能为空",
          isdate:"请输入正确的日期格式",
          isSimpleChar:"请输入正确的日期"
        },
        businessScope:{
          required: "经营范围不能为空",
          isScope:"经营范围必需由汉字或者/组成"
        }
    }
};

var personal = {
    rules:{
        mobile: {
          required: true,
          isMobile:true,
          number:true
        }
    },
    messages:{
        mobile: {
          required: "请输入手机号",
          isMobile:"请输入正确的手机号",
          number:"请输入正确的手机号"
        },
    }
}

var jqValidate = {
    isRegister:isRegister!=undefined?isRegister:0,
    RuleParam:{
        rules:{
            userName: {
              required: true,
              minlength:5,
              maxlength:30,
              isUserName:true
            },
            email: {
              required: true,
              checkemail: true,
              isSpecialChar:true
            },
            goods:{
               required: true,
              minlength:2,
              maxlength:50,
              isCheckName:true
            },
            contacts:{
              required: true,
              minlength:2,
              maxlength:20,
              isCheckName:true
            }
        },
        messages:{
            userName: {
              required: "请输入用户名",
              minlength:"用户名长度应该在5-30个字符之间",
              maxlength:"用户名长度应该在5-30个字符之间",
              isUserName:"用户名必需由英文字母或者数字组成"
            },
            email: {
                required: "电子邮箱不能为空",
                checkemail: "请输入正确的电子邮箱",
                isSpecialChar:"请输入合法的电子邮箱"
            },
            goods:{
                required: "主营商品不能为空",
                minlength:"主营商品名称长度应该在2-50个字符之间",
                maxlength:"主营商品名称长度应该在2-50个字符之间",
                isCheckName:"主营商品只能由汉字和英文组成"
            },
            contacts:{
                required: "联系人不能为空",
                minlength:"联系人名称长度应该在2-20个字符之间",
                maxlength:"联系人名称长度应该在2-20个字符之间",
                isCheckName:"联系人只能由汉字和英文组成"
            }
        }
    },
    handle:function(){
        if(this.isRegister==0){
            nopersonal.rules=$.extend(nopersonal.rules,this.RuleParam.rules);
            personal.rules=$.extend(nopersonal.rules,this.RuleParam.rules);
            nopersonal.messages=$.extend(nopersonal.messages,this.RuleParam.messages);
            personal.messages=$.extend(nopersonal.messages,this.RuleParam.messages);
        }
        var errPlace = {
            errorPlacement:function(error, element){
                element.addClass("error");
                error.appendTo(element.parent());
            }
        };
        personal = $.extend(personal,errPlace);
        nopersonal = $.extend(nopersonal,errPlace);
    }
};

$(function(){
        var isPersonal = 1;
				var registerUserType = $("select[name='registerUserType']").val();
				if(registerUserType=='PERSONAL'){
					$(".nopersonal").hide();
					isPersonal = 1;
				}else{
					$(".nopersonal").show();
					$(".personal").hide();
					isPersonal = 0;
				}
				$("select[name='registerUserType']").change(function(){
					var registerUserType = $("select[name='registerUserType']").val();
					if(registerUserType=='PERSONAL'){
						$(".nopersonal").hide();
						isPersonal = 1;
					}else{
						$(".nopersonal").show();
						$(".personal").hide();
						isPersonal = 0;
					}
				});
				jqValidate.handle();

				$("#onbtnclk").click(function(){
					var registerUserType = $("select[name='registerUserType']").val();
					if(registerUserType!='PERSONAL'){
    					 var province = $.trim($("#loc_province").find("option:selected").text());
    					 var city = $.trim($("#loc_city").find("option:selected").text());
    				     $("input[name='provinceName']").val(province);
    				     $("input[name='cityName']").val(city);
    				  $("#base_addform").validate(nopersonal);
					}else{
              $("#base_addform").validate(personal);
					}
				});
}); 
