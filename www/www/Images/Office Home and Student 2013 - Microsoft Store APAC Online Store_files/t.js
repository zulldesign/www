var merchenta={};merchenta.get_api_url=get_api_url=function(){return window.parent.document.location.protocol+"//"+mc_api_url};merchenta.init=function(){if(document.getElementById("mc_data")==null){return}var l=10;var k=function(t){var u=0;if(t.length==0){return 0}for(i=0;i<t.length;i++){c=t.charCodeAt(i);u=31*u+c;u=u&u}return u};var b=function(w){var v=document.getElementById("mc_data").children;var x=[];for(var u in v){if(v[u].className==w){x.push(v[u].innerHTML)}}var t=x.join("|");l^=k(t);return t};var r=function(u){var v,t,z,w=document.cookie.split(";");for(v=0;v<w.length;v++){t=w[v].substr(0,w[v].indexOf("="));z=w[v].substr(w[v].indexOf("=")+1);t=t.replace(/^\s+|\s+$/g,"");if(t==u){return unescape(z)}}};var e=b("mc_event");var m=b("mc_retailer");var o=encodeURIComponent(b("mc_sku"));var q=encodeURIComponent(b("mc_order_ref"));var h=encodeURIComponent(b("mc_ordervalue"));var a=encodeURIComponent(b("mc_crm"));var g=encodeURIComponent(b("mc_duration"));var f=encodeURIComponent(document.referrer);var d=encodeURIComponent(b("mc_crm_status"));var j=encodeURIComponent(b("mc_crm_last_purchase"));var s=r("merchenta");var p=(typeof(s)!=="undefined"&&s!=null);merchenta.iframe_url=merchenta.get_api_url()+"?mc_event="+e+"&mc_retailer="+m+"&mc_sku="+o+"&mc_order_ref="+q+"&mc_hash="+l+"&mc_ordervalue="+h+"&mc_dcr="+f+"&mc_crm="+a+"&mc_duration="+g+"&mc_crm_status="+d+"&mc_crm_last_purchase="+j+"&no_cache="+Math.random().toString().substr(2);if(p){merchenta.iframe_url+="&mc_fpc="+s}var n=document.createElement("script");n.type="text/javascript";n.async=true;n.src=merchenta.get_api_url().match(/https?:\/\/[a-zA-Z0-9:._-]+\//)[0]+"merchenta/ping";document.getElementsByTagName("head")[0].appendChild(n)};merchenta.ping=function(a){if(a!=null){merchenta.iframe_url+="&mc_ping="+a.hash}merchenta.callTrack()};merchenta.callTrack=function(){var a=document.createElement("iframe");a.src=merchenta.iframe_url;a.setAttribute("style","border:none; display: none;");a.className="mc_iframe";a.width=a.height=0;a.onload=function(){var b=document.createElement("script");b.type="text/javascript";b.async=true;b.src=merchenta.get_api_url().match(/https?:\/\/[a-zA-Z0-9:._-]+\//)[0]+"merchenta/fpc";document.getElementsByTagName("head")[0].appendChild(b)};document.getElementsByTagName("body")[0].appendChild(a)};merchenta.init();