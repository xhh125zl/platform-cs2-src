(function() {
    if(this.tee==undefined){
        this.tee = {}
    }

    var nothing = function(){}
    this.tee.api_base_url = "https://api.teegon.com/";
    if (typeof TEE_API_URL !== 'undefined') {
      this.tee.api_base_url = TEE_API_URL;
    }

    this.tee.$ = function(selector, context, undefined) {
      var matches = {
        '#': 'getElementById',
        '.': 'getElementsByClassName',
        '@': 'getElementsByName',
        '=': 'getElementsByTagName',
        '*': 'querySelectorAll'
      }[selector[0]];
      var el = (((context === undefined) ? document: context)[matches](selector.slice(1)));
      return ((el.length < 2) ? el[0]: el);
    };

    this.tee.obj = function(params){
        var obj = {
          'handler': {'complete':nothing, 'success':nothing, 'error':nothing},
          'params': params,
        }
        obj.complete = function(f){
          obj.handler.complete = f.bind(obj);
          return obj;
        }
        obj.success = function(f){
          obj.handler.success = f.bind(obj);
          return obj;
        }
        obj.error = function(f){
          obj.handler.error = f.bind(obj);
          return obj;
        }
        return obj;
    }
}).call(this);
;(function () {

    var charge = function (data) {
        if (typeof(data) == "string" && data[0] == '{') {
            data = JSON.parse(data);
        }
        if (!data.error && data.result && data.result.action) {
            switch (data.result.action.type) {
                case 'form':
                    form_submit(data.result.action.url, 'POST', data.result.action.params);
                    break;
                case 'js':
                    if(data.result.action.url !=""){
                        window.location.href = data.result.action.url;
                    }else{
                        eval(data.result.action.params);
                    }
                    
                    break;
                case 'url':
                    //var iframe = document.createElement("iframe");
                    //iframe.src = data.result.action.url;
                    //iframe.style.width = "100%";
                    //iframe.style.height = "100%";
                    //document.body.appendChild(iframe);
                    window.location = data.result.action.url;
                default:
                    if (console && console.error)console.error('undefined action:' + data.result.action.type);
            }
        } else {
            alert(data.error);
            if (console && console.error)console.error(data);
        }
    }

    var ChargeWizard = function (options) {
        if (!options.charge_id) {
            alert("emtpy charge_id?");
        }
        options.platform = options.platform ? options.platform : "pc";
        if (options.platform == "mobile") {
            load_charge_panel_mobile(options);
        } else {
            window.location = trim_p(window.tee.api_base_url) + "/app/checkout/pc?id=" + options.charge_id + "&list=" + options.payments;
        }
    }

    var trim_p = function (a) {
        if (a.substr(a.length - 1, 1) == "/") {
            a = a.substr(0, a.length - 1);
        }
        return a;
    }

    var load_charge_panel_mobile = function (options) {

        var ifrm = document.createElement("IFRAME");

        ifrm.setAttribute("src", trim_p(window.tee.api_base_url) + "/app/checkout/mobile?id=" + options.charge_id + "&list=" + options.payments);
        ifrm.style.width = "100%";

        ifrm.style.position = 'fixed';
        ifrm.style.bottom = '0';
        ifrm.style.left = '0';

        ifrm.style.zIndex = '100';
        ifrm.style.border = 'none';
        ifrm.style.visibility = 'hidden';
        ifrm.onload = function () {
            ifrm.style.visibility = "inherit";
        }

        document.body.appendChild(ifrm);
        var oWarp = document.createElement('div');
        document.body.appendChild(oWarp)
        oWarp.style.width = '100%';
        oWarp.style.height = document.documentElement.clientHeight + 'px';
        oWarp.style.background = 'rgba(0,0,0,.3)'
        oWarp.style.position = 'absolute';
        oWarp.style.left = '0';
        oWarp.style.top = '0';
        oWarp.style.zIndex = '99';
        var oBtns = document.getElementById('pay-btns');

        document.body.style.height = document.documentElement.clientHeight + 'px';
        document.body.style.overflow = 'hidden';

        event.stopPropagation();

        var func_listener = function () {
            ifrm.remove();
            oWarp.remove();
            document.removeEventListener('click', func_listener, false);
        };
        document.addEventListener('click', func_listener, false);
    }

    var form_submit = function (url, method, params) {
        var form = window.document.createElement("form");
        form.setAttribute("method", method);
        form.setAttribute("action", url);
        form.setAttribute("target", "_top");

        for (var key in params) {
            if (params.hasOwnProperty(key)) {
                var ipt = window.document.createElement("input");
                ipt.setAttribute("type", "hidden");
                ipt.setAttribute("name", key);
                ipt.setAttribute("value", params[key]);
                form.appendChild(ipt);
            }
        }

        document.body.appendChild(form);
        form.submit();
    }

    this.charge = charge;
    this.ChargeWizard = ChargeWizard;
}).call(this.tee);
;(function() {

  var checkout = function(params){
    var obj = window.tee.obj(params);
    this.JSONP({
        keep: true,
        url: getCheckOutUrl,
        data: params,
        complete: function(channel){
          params.url = window.tee.api_base_url+"/app/checkout/do";
          params.channel = channel;
          window.tee.charge(params);
        }
    });
    return obj;
  }

  var getCheckOutUrl = function(){
      var ua = window.navigator.userAgent.toLowerCase();
      if(ua.match(/MicroMessenger/i) == 'micromessenger'){
          return window.tee.api_base_url+"/app/checkout/show_wx";
      }else{
          return window.tee.api_base_url+"/app/checkout";
      }
  }

  this.checkout = checkout;
}).call(this.tee);
;(function() {
  var JSONP, computedUrl, createElement, encode, noop, objectToURI, random, randomString;

  createElement = function(tag) {
    return window.document.createElement(tag);
  };

  encode = window.encodeURIComponent;
  random = Math.random;

  JSONP = function(options) {
    var callback, callbackFunc, callbackName, done, head, params, script;
    options = options ? options : {};
    params = {
      keep: options.keep || false,
      data: options.data || {},
      error: options.error || noop,
      success: options.success || noop,
      beforeSend: options.beforeSend || noop,
      complete: options.complete || noop,
      url: options.url || ''
    };
    params.computedUrl = computedUrl(params);
    if (params.url.length === 0) {
      throw new Error('MissingUrl');
    }
    done = false;
    if (params.beforeSend({}, params) !== false) {
      callbackName = options.callbackName || 'callback';
      callbackFunc = options.callbackFunc || 'jsonp_' + randomString(15);
      callback = params.data[callbackName] = callbackFunc;
      window[callback] = function(data) {
        if(!params.keep){
          window[callback] = null;
        }
        params.success(data, params);
        return params.complete(data, params);
      };
      script = createElement('script');
      script.src = computedUrl(params);
      script.async = true;
      script.onerror = function(evt) {
        params.error({
          url: script.src,
          event: evt
        });
        return params.complete({
          url: script.src,
          event: evt
        }, params);
      };
      script.onload = script.onreadystatechange = function() {
        if (!done && (!this.readyState || this.readyState === 'loaded' || this.readyState === 'complete')) {
          done = true;
          script.onload = script.onreadystatechange = null;
          if (script && script.parentNode) {
            script.parentNode.removeChild(script);
          }
          return script = null;
        }
      };
      head = head || window.document.getElementsByTagName('head')[0] || window.document.documentElement;
      head.insertBefore(script, head.firstChild);
    }
    return {
      abort: function() {
        window[callback] = function() {
          return window[callback] = null;
        };
        done = true;
        if (script && script.parentNode) {
          script.onload = script.onreadystatechange = null;
          if (script && script.parentNode) {
            script.parentNode.removeChild(script);
          }
          return script = null;
        }
      }
    };
  };

  noop = function() {
    return void 0;
  };

  computedUrl = function(params) {
    var url;
    url = params.url;
    url += params.url.indexOf('?') < 0 ? '?' : '&';
    url += objectToURI(params.data);
    return url;
  };

  randomString = function(length) {
    var str;
    str = '';
    while (str.length < length) {
      str += random().toString(36)[2];
    }
    return str;
  };

  objectToURI = function(obj) {
    var data, key, value;
    data = [];
    for (key in obj) {
      value = obj[key];
      data.push(encode(key) + '=' + encode(value));
    }
    return data.join('&');
  };

  this.JSONP = JSONP;
  this.createElement = createElement;

}).call(this.tee);