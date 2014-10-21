(function ensightenInit(){var ensightenOptions = {client: "microsoftstore", clientId: 934, publishPath: "prod", isPublic:1, serverComponentLocation: "nexus.ensighten.com/microsoftstore/prod/serverComponent.php", staticJavascriptPath: "nexus.ensighten.com/microsoftstore/prod/code/", ns: 'Bootstrapper', nexus:"nexus.ensighten.com", scUseCacheBuster: "true", enableTagAuditBeacon : "false", enablePagePerfBeacon : "false", registryNs : "ensBootstraps", generatedOn : "Mon Oct 20 22:44:47 GMT 2014", beaconSamplingSeedValue: 11};
if ( !window[ensightenOptions.ns] ) {
window[ensightenOptions.registryNs]||(window[ensightenOptions.registryNs]={});
window[ensightenOptions.registryNs][ensightenOptions.ns]=window[ensightenOptions.ns]=function(f){function m(a){this.name="DependencyNotAvailableException";this.message="Dependency with id "+a+"is missing"}function n(a){this.name="BeaconException";this.message="There was an error durring beacon initialization";a=a||{};this.lineNumber=a.lineNumber||a.line;this.fileName=a.fileName}function p(){for(var a=b.dataDefinitionIds.length,e=!0,d=0;d<a;d++){var c=b.dataDefinitions[b.dataDefinitionIds[d]];if(!c||
null==c.endRegistration){e=!1;break}}e&&b.callOnDataDefintionComplete()}var c={},b={};b.ensightenOptions=ensightenOptions;b.scDataObj={};c.version="1.24.0";c.nexus=f.nexus||"nexus.ensighten.com";c.rand=-1;c.currSec=(new Date).getSeconds();c.options={interval:f.interval||100,erLoc:f.errorLocation||c.nexus+"/error/e.gif",scLoc:f.serverComponentLocation||c.nexus+"/"+f.client+"/serverComponent.php",sjPath:f.staticJavascriptPath||c.nexus+"/"+f.client+"/code/",alLoc:f.alertLocation||c.nexus+"/alerts/a.gif",
publishPath:f.publishPath,isPublic:f.isPublic,client:f.client,clientId:f.clientId,enableTagAuditBeacon:f.enableTagAuditBeacon,scUseCacheBuster:f.scUseCacheBuster,beaconSamplingSeedValue:f.beaconSamplingSeedValue||-1};c.ruleList=[];c.allDeploymentIds=[];c.runDeploymentIds=[];c.exceptionList=[];c.ensightenVariables={};c.test=function(a){if(!(a.executionData.hasRun||a.executionData.runTime&&0<a.executionData.runTime.length)){for(var b=0;b<a.dependencies.length;b++)if(!1===a.dependencies[b]())return;
a.execute()}};m.prototype=Error();m.prototype||(m.prototype={});m.prototype.constructor=m;c.DependencyNotAvailableException=m;n.prototype=Error();n.prototype||(n.prototype={});n.prototype.constructor=n;c.BeaconException=n;c.checkForInvalidDependencies=function(a,e,d,l){for(a=0;a<d.length;a++)if("DEPENDENCYNEVERAVAILABLE"===d[a])return b.currentRuleId=this.id,b.currentDeploymentId=this.deploymentId,b.reportException(new c.DependencyNotAvailableException(l[a])),e&&-1!==e&&c.allDeploymentIds.push(e),
!0;return!1};b.currentRuleId=-1;b.currentDeploymentId=-1;b.reportedErrors=[];b.reportedAlerts=[];b.AF=[];b._serverTime="";b._clientIP="";b.sampleBeacon=function(){var a=!1;try{var b=(c.currSec||0)%20,d=c.options.beaconSamplingSeedValue;-1===d?a=!0:0!==b&&0===d%b&&(a=!0)}catch(l){}return a};b.getServerComponent=function(a){b.callOnGetServerComponent();b.insertScript(window.location.protocol+"//"+c.options.scLoc,!1,a||!0,c.options.scUseCacheBuster)};b.setVariable=function(a,b){c.ensightenVariables[a]=
b};b.getVariable=function(a){return a in c.ensightenVariables?c.ensightenVariables[a]:null};b.testAll=function(){for(var a=0;a<c.ruleList.length;a++)c.test(c.ruleList[a])};b.executionState={DOMParsed:!1,DOMLoaded:!1,dataDefinitionComplete:!1,conditionalRules:!1,readyForServerComponent:!1};b.reportException=function(a){a.timestamp=(new Date).getTime();c.exceptionList.push(a);a=window.location.protocol+"//"+c.options.erLoc+"?msg="+encodeURIComponent(a.message||"")+"&lnn="+encodeURIComponent(a.lineNumber||
a.line||-1)+"&fn="+encodeURIComponent(a.fileName||"")+"&cid="+encodeURIComponent(c.options.clientId||-1)+"&client="+encodeURIComponent(c.options.client||"")+"&publishPath="+encodeURIComponent(c.options.publishPath||"")+"&rid="+encodeURIComponent(b.currentRuleId||-1)+"&did="+encodeURIComponent(b.currentDeploymentId||-1)+"&errorName="+encodeURIComponent(a.name||"");a=b.imageRequest(a);a.timestamp=(new Date).getTime();this.reportedErrors.push(a)};b.Rule=function(a){this.execute=function(){this.executionData.runTime.push(new Date);
b.currentRuleId=this.id;b.currentDeploymentId=this.deploymentId;try{this.code()}catch(a){window[ensightenOptions.ns].reportException(a)}finally{this.executionData.hasRun=!0,-1!==this.deploymentId&&c.runDeploymentIds.push(this.deploymentId),b.testAll()}};this.id=a.id;this.deploymentId=a.deploymentId;this.dependencies=a.dependencies||[];this.code=a.code;this.executionData={hasRun:!1,runTime:[]}};b.registerRule=function(a){if(b.getRule(a.id)&&-1!==a.id)return!1;c.ruleList.push(a);-1!==a.deploymentId&&
c.allDeploymentIds.push(a.deploymentId);b.testAll();return!0};b.getRule=function(a){for(var b=0;b<c.ruleList.length;b++)if(c.ruleList[b].id===a)return c.ruleList[b];return!1};

b.getAllDeploymentIds=function(){return c.allDeploymentIds};b.getRunDeploymentIds=function(){return c.runDeploymentIds};b.hasRuleRun=function(a){return(a=b.getRule(a))?a.executionData.hasRun:!1};c.toTwoChar=function(a){return(2===a.toString().length?
"":"0")+a};b.Alert=function(a){var b=new Date,b=b.getFullYear()+"-"+c.toTwoChar(b.getMonth())+"-"+c.toTwoChar(b.getDate())+" "+c.toTwoChar(b.getHours())+":"+c.toTwoChar(b.getMinutes())+":"+c.toTwoChar(b.getSeconds());this.severity=a.severity||1;this.date=b;this.subject=a.subject||"";this.type=a.type||1;this.ruleId=a.ruleId||-1};b.generateAlert=function(a){a=b.imageRequest(window.location.protocol+"//"+c.options.alLoc+"?d="+a.date+"&su="+a.subject+"&se="+a.severity+"&t="+a.type+"&cid="+c.options.clientId+
"&client="+c.options.client+"&publishPath="+c.options.publishPath+"&rid="+b.currentRuleId+"&did="+b.currentDeploymentId);a.timestamp=(new Date).getTime();this.reportedAlerts.push(a)};b.imageRequest=function(a){var b=new Image(0,0);b.src=a;return b};b.insertScript=function(a,e,d,l){var h=document.getElementsByTagName("script"),g;l=void 0!==l?l:!0;if(void 0!==e?e:1)for(g=0;g<h.length;g++)if(h[g].src===a&&h[g].readyState&&/loaded|complete/.test(h[g].readyState))return;if(d){d=1==d&&"object"==typeof b.scDataObj?
b.scDataObj:d;c.rand=Math.random()*("1E"+(10*Math.random()).toFixed(0));e=window.location.href;if("object"===typeof d)for(g in d){g=~e.indexOf("#")?e.slice(e.indexOf("#"),e.length):"";e=e.slice(0,g.length?e.length-g.length:e.length);e+=~e.indexOf("?")?"&":"?";for(k in d)e+=k+"="+d[k]+"&";e=e.slice(0,-1)+g;break}a+="?";l&&(a+="r="+c.rand+"&");a+="ClientID="+encodeURIComponent(c.options.clientId)+"&PageID="+encodeURIComponent(e)}(function(a,b,e){var d=b.head||b.getElementsByTagName("head");setTimeout(function(){if("item"in
d){if(!d[0]){setTimeout(arguments.callee,25);return}d=d[0]}var a=b.createElement("script");a.src=e;a.onload=a.onerror=function(){this.addEventListener&&(this.readyState="loaded")};d.insertBefore(a,d.firstChild)},0)})(window,document,a)};b.loadScriptCallback=function(a,b,d){var c=document.getElementsByTagName("script"),h;d=c[0];for(h=0;h<c.length;h++)if(c[h].src===a&&c[h].readyState&&/loaded|complete/.test(c[h].readyState))try{b()}catch(g){window[ensightenOptions.ns].reportException(g)}finally{return}c=
document.createElement("script");c.type="text/javascript";c.async=!0;c.src=a;c.onerror=function(){this.addEventListener&&(this.readyState="loaded")};c.onload=c.onreadystatechange=function(){if(!this.readyState||"complete"===this.readyState||"loaded"===this.readyState){this.onload=this.onreadystatechange=null;this.addEventListener&&(this.readyState="loaded");try{b.call(this)}catch(a){window[ensightenOptions.ns].reportException(a)}}};d.parentNode.insertBefore(c,d)};b.unobtrusiveAddEvent=function(a,
b,d){try{var c=a[b]?a[b]:function(){};a[b]=function(){d.apply(this,arguments);return c.apply(this,arguments)}}catch(h){window[ensightenOptions.ns].reportException(h)}};b.anonymous=function(a,e){return function(){try{b.currentRuleId=e?e:"anonymous",a()}catch(d){window[ensightenOptions.ns].reportException(d)}}};b.setCurrentRuleId=function(a){b.currentRuleId=a};b.setCurrentDeploymentId=function(a){b.currentDeploymentId=a};b.bindImmediate=function(a,e,d){if("function"===typeof a)a=new b.Rule({id:e||-1,
deploymentId:d||-1,dependencies:[],code:a});else if("object"!==typeof a)return!1;b.registerRule(a)};b.bindDOMParsed=function(a,e,d){if("function"===typeof a)a=new b.Rule({id:e||-1,deploymentId:d||-1,dependencies:[function(){return window[ensightenOptions.ns].executionState.DOMParsed}],code:a});else if("object"!==typeof a)return!1;b.registerRule(a)};b.bindDOMLoaded=function(a,e,d){if("function"===typeof a)a=new b.Rule({id:e||-1,deploymentId:d||-1,dependencies:[function(){return window[ensightenOptions.ns].executionState.DOMLoaded}],
code:a});else if("object"!==typeof a)return!1;b.registerRule(a)};b.bindPageSpecificCompletion=function(a,e,d){if("function"===typeof a)a=new b.Rule({id:e||-1,deploymentId:d||-1,dependencies:[function(){return window[ensightenOptions.ns].executionState.conditionalRules}],code:a});else if("object"!==typeof a)return!1;b.registerRule(a)};b.bindOnGetServerComponent=function(a,e,d){if("function"===typeof a)a=new b.Rule({id:e||-1,deploymentId:d||-1,dependencies:[function(){return window[ensightenOptions.ns].executionState.readyForServerComponent}],
code:a});else if("object"!==typeof a)return!1;b.registerRule(a)};b.bindDataDefinitionComplete=function(a,e,d){if("function"===typeof a)a=new b.Rule({id:e||-1,deploymentId:d||-1,dependencies:[function(){return window[ensightenOptions.ns].executionState.dataDefinitionComplete}],code:a});else if("object"!==typeof a)return!1;b.registerRule(a)};b.checkHasRun=function(a){if(0===a.length)return!0;for(var e,d=0;d<a.length;++d)if(e=b.getRule(parseInt(a[d],10)),!e||!e.executionData.hasRun)return!1;return!0};
b.bindDependencyImmediate=function(a,e,d,l,h){var g=[];if(!c.checkForInvalidDependencies(e,l,d,h)){g.push(function(){return window[ensightenOptions.ns].checkHasRun(d)});if("function"===typeof a)a=new b.Rule({id:e||-1,deploymentId:l||-1,dependencies:g,code:a});else if("object"!==typeof a)return!1;b.registerRule(a)}};b.bindDependencyDOMLoaded=function(a,e,d,l,h){var g=[];if(!c.checkForInvalidDependencies(e,l,d,h)){g.push(function(){return window[ensightenOptions.ns].executionState.DOMLoaded});g.push(function(){return window[ensightenOptions.ns].checkHasRun(d)});
if("function"===typeof a)a=new b.Rule({id:e||-1,deploymentId:l||-1,dependencies:g,code:a});else if("object"!==typeof a)return!1;b.registerRule(a)}};b.bindDependencyDOMParsed=function(a,e,d,l,h){var g=[];if(!c.checkForInvalidDependencies(e,l,d,h)){g.push(function(){return window[ensightenOptions.ns].executionState.DOMParsed});g.push(function(){return window[ensightenOptions.ns].checkHasRun(d)});if("function"===typeof a)a=new b.Rule({id:e||-1,deploymentId:l||-1,dependencies:g,code:a});else if("object"!==
typeof a)return!1;b.registerRule(a)}};b.bindDependencyPageSpecificCompletion=function(a,e,d,l,h){var g=[];if(!c.checkForInvalidDependencies(e,l,d,h)){g.push(function(){return window[ensightenOptions.ns].executionState.conditionalRules});g.push(function(){return window[ensightenOptions.ns].checkHasRun(d)});if("function"===typeof a)a=new b.Rule({id:e||-1,deploymentId:l||-1,dependencies:g,code:a});else if("object"!==typeof a)return!1;b.registerRule(a)}};b.bindDependencyOnGetServerComponent=function(a,
e,d,l,h){var g=[];if(!c.checkForInvalidDependencies(e,l,d,h)){g.push(function(){return window[ensightenOptions.ns].executionState.readyForServerComponent});g.push(function(){return window[ensightenOptions.ns].checkHasRun(d)});if("function"===typeof a)a=new b.Rule({id:e||-1,deploymentId:l||-1,dependencies:g,code:a});else if("object"!==typeof a)return!1;b.registerRule(a)}};b.bindDependencyPageSpecificCompletion=function(a,e,d,l,h){var g=[];if(!c.checkForInvalidDependencies(e,l,d,h)){g.push(function(){return window[ensightenOptions.ns].executionState.dataDefinitionComplete});
g.push(function(){return window[ensightenOptions.ns].checkHasRun(d)});if("function"===typeof a)a=new b.Rule({id:e||-1,deploymentId:l||-1,dependencies:g,code:a});else if("object"!==typeof a)return!1;b.registerRule(a)}};b.dataDefintionIds=[];b.dataDefinitions=[];b.pageSpecificDataDefinitionsSet=!1;b.setPageSpecificDataDefinitionIds=function(a){for(var e=a.length,d=0;d<e;d++){var c=a[d];if(Array.prototype.indexOf)-1==b.dataDefinitionIds.indexOf(c)&&b.dataDefinitionIds.push(c);else{for(var h=!1,g=b.dataDefinitionIds.length,
f=0;f<g;f++)if(b.dataDefinitionIds[f]===c){h=!0;break}h||b.dataDefinitionIds.push(c)}}b.pageSpecificDataDefinitionsSet=!0;p()};b.DataDefinition=function(a,b){this.id=a;this.registrationFn=b;this.endRegistrationTime=this.startRegistrationTime=null;this.startRegistration=function(){this.startRegistrationTime=new Date};this.endRegistration=function(){this.endRegistrationTime=new Date}};b.registerDataDefinition=function(a,e){var c=b.dataDefinitions[e];c||(c=new b.DataDefinition(e,a),b.dataDefinitions[e]=
c);c.startRegistrationTime||(c.startRegistration(),c.registrationFn(),c.endRegistration());b.pageSpecificDataDefinitionsSet&&p()};b.callOnDataDefintionComplete=function(){b.executionState.dataDefinitionComplete=!0;b.testAll()};b.callOnDOMParsed=function(){window[ensightenOptions.ns].executionState.DOMParsed=!0;window[ensightenOptions.ns].testAll()};b.callOnDOMLoaded=function(){window[ensightenOptions.ns].executionState.DOMParsed=!0;window[ensightenOptions.ns].executionState.DOMLoaded=!0;window[ensightenOptions.ns].testAll()};
b.callOnPageSpecificCompletion=function(){for(var a=document.getElementsByTagName("script"),b=0,c=a.length;b<c;b++)if(a[b].src.match(/\.ensighten\.com\/(.+?)\/code\/.*/i)&&"loaded"!=a[b].readyState&&"complete"!=a[b].readyState){setTimeout(window[ensightenOptions.ns].callOnPageSpecificCompletion,50);return}setTimeout(function(){window[ensightenOptions.ns].executionState.conditionalRules=!0;window[ensightenOptions.ns].testAll()},1)};b.callOnGetServerComponent=function(){window[ensightenOptions.ns].executionState.readyForServerComponent=
!0;window[ensightenOptions.ns].testAll()};b.hasDOMParsed=function(){return window[ensightenOptions.ns].executionState.DOMParsed};b.hasDOMLoaded=function(){return window[ensightenOptions.ns].executionState.DOMLoaded};b.hasPageSpecificCompletion=function(){return window[ensightenOptions.ns].executionState.conditionalRules};var q=function(){var a=[],b=!1,c=!1;return{add:function(f){b&&!c?f():"function"==typeof f&&(a[a.length]=f)},exec:function(){c=!0;do{var f=a;a=[];b=!0;for(var h=0;h<f.length;h++)try{f[h].call(window)}catch(g){window[ensightenOptions.ns].reportException(g)}}while(0<
a.length);c=!1},haveRun:function(){return b}}};b.new_fArray=function(){return q()};c.timer=null;(function(){function a(a,b){return function(){a.apply(b,arguments)}}window.console||(window.console={});var b=window.console;if(!b.log)if(window.log4javascript){var c=log4javascript.getDefaultLogger();b.log=a(c.info,c);b.debug=a(c.debug,c);b.info=a(c.info,c);b.warn=a(c.warn,c);b.error=a(c.error,c)}else b.log=function(){};b.debug||(b.debug=b.log);b.info||(b.info=b.log);b.warn||(b.warn=b.log);b.error||(b.error=
b.log)})();document.addEventListener?(-1<navigator.userAgent.indexOf("AppleWebKit/")?c.timer=window.setInterval(function(){/loaded|complete/.test(document.readyState)&&(clearInterval(c.timer),b.callOnDOMParsed())},50):document.addEventListener("DOMContentLoaded",b.callOnDOMParsed,!1),window.addEventListener("load",b.callOnDOMLoaded,!1)):(setTimeout(function(){var a=window.document;(function(){try{if(!document.body)throw"continue";a.documentElement.doScroll("left")}catch(b){setTimeout(arguments.callee,
15);return}window[ensightenOptions.ns].callOnDOMParsed()})()},1),window.attachEvent("onload",function(){window[ensightenOptions.ns].callOnDOMLoaded()}));"true"===c.options.enableTagAuditBeacon&&b.sampleBeacon()&&window.setTimeout(function(){if(window[ensightenOptions.ns]&&!window[ensightenOptions.ns].mobilePlatform)try{for(var a=[],e,d,l,h,g=0;g<c.ruleList.length;++g)d=c.ruleList[g],l=d.executionData.hasRun?"1":"0",h=d.deploymentId.toString()+"|"+d.id.toString()+"|"+l,a.push(h);e="["+a.join(";")+
"]";var m=window.location.protocol+"//"+c.nexus+"/"+encodeURIComponent(f.client)+"/"+encodeURIComponent(f.publishPath)+"/TagAuditBeacon.rnc?cid="+encodeURIComponent(f.clientId)+"&data="+e+"&idx=0&r="+c.rand;b.imageRequest(m)}catch(n){b.currentRuleId=-1,b.currentDeploymentId=-1,a=new c.BeaconException(n),window[ensightenOptions.ns].reportException(a)}},3E3);window.setInterval(b.testAll,c.options.interval);return b}(ensightenOptions);
"true"===ensightenOptions.enablePagePerfBeacon&&window[ensightenOptions.ns]&&window[ensightenOptions.ns].sampleBeacon()&&window[ensightenOptions.ns].bindDOMParsed(function(){if(!window[ensightenOptions.ns].mobilePlatform){var f=window.performance;if(f){var f=f.timing||{},m="",n=f.navigationStart||0,p,c={connectEnd:"ce",connectStart:"cs",domComplete:"dc",domContentLoadedEventEnd:"dclee",domContentLoadedEventStart:"dcles",domInteractive:"di",domLoading:"dl",domainLookupEnd:"dle",domainLookupStart:"dls",
fetchStart:"fs",loadEventEnd:"lee",loadEventStart:"les",redirectEnd:"rede",redirectStart:"reds",requestStart:"reqs",responseStart:"resps",responseEnd:"respe",secureConnectionStart:"scs",unloadEventStart:"ues",unloadEventEnd:"uee"},m="&ns="+encodeURIComponent(f.navigationStart),b;for(b in c)void 0!==f[b]?(p=f[b]-n,m+="&"+c[b]+"="+(0<p?encodeURIComponent(p):0)):m+="&"+c[b]+"=-1";window[ensightenOptions.ns].timing=m;b=ensightenOptions.nexus||"nexus.ensighten.com";f=ensightenOptions.staticJavascriptPath||
"";m=f.indexOf(".com/");n=f.indexOf("/code/");f=f.substring(m+4,n)+"/perf.rnc";f+="?cid="+encodeURIComponent(ensightenOptions.clientId)+window[ensightenOptions.ns].timing;window[ensightenOptions.ns].imageRequest("//"+b+f)}}});
	Bootstrapper.dataDefinitionIds = [];Bootstrapper.bindImmediate(function(){var Bootstrapper=window["Bootstrapper"];var ensightenOptions=Bootstrapper.ensightenOptions;(function(){try{var t=window.location.href;var pth=window.location.pathname.toLowerCase();var bktest_segmentID="";var o_segID=t.toLowerCase().indexOf("optimizely\x3d");var accountID="";if(pth.indexOf("/store/msusa/")>-1||t.indexOf("SiteID\x3dmsusa")>-1)accountID="222980912";else if(pth.indexOf("/store/msca/")>-1||t.indexOf("SiteID\x3dmsca")>-1)accountID="257586581";else if(pth.indexOf("/store/msuk/")>
-1||t.indexOf("SiteID\x3dmsuk")>-1)accountID="245891958";else if(pth.indexOf("/store/msaus/")>-1||t.indexOf("SiteID\x3dmsaus")>-1)accountID="293603724";else if(pth.indexOf("/store/msde/")>-1||t.indexOf("SiteID\x3dmsde")>-1)accountID="293622600";else if(pth.indexOf("/store/msfr/")>-1||t.indexOf("SiteID\x3dmsfr")>-1)accountID="246342273";else if(pth.indexOf("/store/msmx/")>-1||t.indexOf("SiteID\x3dmsmx")>-1)accountID="305974170";else if(pth.indexOf("/store/msnz/")>-1||t.indexOf("SiteID\x3dmsnz")>-1)accountID=
"305872512";else if(pth.indexOf("/mssg/")>-1||t.indexOf("SiteID\x3dmssg")>-1)accountID="330578509";else if(pth.indexOf("/mseea/da_dk")>-1||t.indexOf("SiteID\x3dmseea")>-1&&t.indexOf("Locale\x3dda_DK")>-1)accountID="335986495";else if(pth.indexOf("/mseea/sv_se")>-1||t.indexOf("SiteID\x3dmseea")>-1&&t.indexOf("Locale\x3dsv_SE")>-1)accountID="336174028";else if(pth.indexOf("/msjp/")>-1||t.indexOf("SiteID\x3dmsjp")>-1)accountID="356890202";else if(pth.indexOf("/msbr/")>-1||t.indexOf("SiteID\x3dmsbr")>
-1)accountID="361490044";else if(pth.indexOf("/msrelcan/")>-1||t.indexOf("SiteID\x3dmsrelcan")>-1)accountID="394000455";else if(pth.indexOf("/msapac/")>-1||t.indexOf("SiteID\x3dmsapac")>-1)accountID="867653691";else if(pth.indexOf("/mskr/")>-1||t.indexOf("SiteID\x3dmskr")>-1)accountID="867653691";else if(pth.indexOf("/msru/")>-1||t.indexOf("SiteID\x3dmsru")>-1)accountID="1445683382";else if(pth.indexOf("/msin/")>-1||t.indexOf("SiteID\x3dmsin")>-1)accountID="1445703402";else if(pth.indexOf("/mseea/es_es")>
-1||t.indexOf("SiteID\x3dmseea")>-1&&t.indexOf("Locale\x3des_ES")>-1)accountID="1410870601";else if(pth.indexOf("/mslatam/")>-1||t.indexOf("SiteID\x3dmslatam")>-1)accountID="1468904248";else if(pth.indexOf("/mseea/nl_nl")>-1||t.indexOf("SiteID\x3dmseea")>-1&&t.indexOf("Locale\x3dnl_NL")>-1)accountID="2088101963";else if(pth.indexOf("/mseea/it_it")>-1||t.indexOf("SiteID\x3dmseea")>-1&&t.indexOf("Locale\x3dit_IT")>-1)accountID="2094940621";else if(pth.indexOf("/mseea/da_dk")>-1||t.indexOf("SiteID\x3dmseea")>
-1&&t.indexOf("Locale\x3dda_DK")>-1)accountID="2105540538";else if(pth.indexOf("/mseea/de_ch")>-1||t.indexOf("SiteID\x3dmseea")>-1&&t.indexOf("Locale\x3dde_CH")>-1)accountID="2105540538";if(accountID&&window.location.host.indexOf(".microsoftstore.com")>-1&&(pth.indexOf("mssg")>-1||pth.indexOf("home")>-1||pth.indexOf("productid.")>-1||pth.indexOf("categoryid")>-1||pth.indexOf("html/pbpage")>-1||pth.indexOf("continueshopping")>-1||pth.indexOf("displaydownloadhistorypage")>-1||pth.indexOf("displayaccountorderlistpage")>
-1||pth.indexOf("displayeditprofilepage")>-1||t.indexOf("Action\x3dDisplayProductSearchResultsPage")>-1||t.indexOf("Action\x3dDisplayProductDetailsPage")>-1||t.indexOf("Action\x3dDisplayProductSearchResultsPage")>-1||t.indexOf("id\x3dThreePgCheckoutConfirmOrderPage")>-1||t.indexOf("id\x3dThreePgCheckoutShoppingCartPage")>-1||t.indexOf("id\x3dThankYouPage")>-1||t.indexOf("id\x3dYourOrderIsBeingProcessedPage")>-1))document.write("\x3cscr"+'ipt type\x3d"text/javascript" src\x3d"'+"//cdn.optimizely.com/js/"+
accountID+'.js"\x3e\x3c/sc'+"ript\x3e")}catch(e){}})()},561839,246654);
Bootstrapper.bindDependencyImmediate(function(){var Bootstrapper=window["Bootstrapper"];var ensightenOptions=Bootstrapper.ensightenOptions;Bootstrapper.createCookie=Bootstrapper.createCookie||function(name,value,days){var expires="";if(days){var date=new Date;date.setTime(date.getTime()+days*24*60*60*1E3);expires="; expires\x3d"+date.toGMTString()}document.cookie=name+"\x3d"+value+expires+"; path\x3d/"};Bootstrapper.readCookie=Bootstrapper.readCookie||function(name){var nameEQ=name+"\x3d";var ca=
document.cookie.split(";");var i;for(i=0;i<ca.length;i++){var c=ca[i];while(c.charAt(0)==" ")c=c.substring(1,c.length);if(c.indexOf(nameEQ)==0)return c.substring(nameEQ.length,c.length)}return""};Bootstrapper.eraseCookie=Bootstrapper.eraseCookie||function(name){Bootstrapper.createCookie(name,"",-1)};Bootstrapper.doBK=~window.location.pathname.indexOf("msusa")&&window.location.pathname.toLowerCase().indexOf("displaythreepgcheckoutaddresspaymentinfopage ")<0&&~window.location.host.indexOf(".microsoftstore.com")?
true:false;Bootstrapper.BKcookiename="boolBKINT";Bootstrapper.BKnotDoneYet=Bootstrapper.readCookie(Bootstrapper.BKcookiename)?false:true;if(~window.location.href.indexOf("bktest\x3d")||Bootstrapper.doBK){var bk12891src=window.location.protocol=="http:"?"http://tags.bluekai.com/site/12891?ret\x3djs\x26":"https://stags.bluekai.com/site/12891?ret\x3djs\x26";Bootstrapper.loadScriptCallback(bk12891src,function(){try{var addCategories=false;var pushEvent=true;var omniture_CIDs=[];window["optimizely"]=window["optimizely"]||
[];if(window.bk_results){var cmp=bk_results.campaigns,i=0,campID;for(i=0;i<cmp.length;i++){campID="bk_campid_"+cmp[i].campaign.toString();if(cmp[i].campaign.toString())omniture_CIDs.push(campID);window["optimizely"].push(["addToSegment",campID])}window.optimizely.push(["trackEvent",window.location.href])}Bootstrapper.createCookie(Bootstrapper.BKcookiename,1,0)}catch(bkerr){}})}},400412,[561839],246640,[246654]);
Bootstrapper.bindImmediate(function(){var Bootstrapper=window["Bootstrapper"];var ensightenOptions=Bootstrapper.ensightenOptions;var regString=new RegExp("https://office.microsoft.com/.*/purchase.aspx");var regString2=new RegExp("/msuk/|SiteID\x3dmsuk");var regString3=new RegExp("https://stores.office.com/subscription/officetrial.aspx");if(document.URL.match(regString)||document.URL.match(regString2)||document.URL.match(regString3)){document._write=document.write;document.write=function(x){var ar=
"";if(arguments.callee.caller)ar=arguments.callee.caller.toString().replace(/(^\s+|\s+$)/g,"");if((ar.indexOf("MscomBeacon()")>-1||ar.indexOf("MscomGetMUID()")>-1)&&Bootstrapper.hasDOMParsed()&&document.getElementsByTagName("html").length&&document.getElementsByTagName("html")[0].innerHTML.match(/\<\/body/i)){var d=document.createElement("div");x=x.split(/\<script/i);var scripts=[];var repSpans=[];var repScripts=[];if(x[0]=="")x.shift();for(var i=0;i<x.length;i++){x[i]=x[i].split(/\/script\>/i);if(x[i][0].indexOf("\x3c")){scripts.push("\x3cscript"+
x[i][0]+"/script\x3e");x[i][0]="\x3cspan name\x3d'ensScript'\x3e\x3c/span\x3e"}x[i]=x[i].join("")}x=x.join("");d.innerHTML=x;var spans=d.getElementsByTagName("span");for(var i=0;i<spans.length;i++)if(spans[i].getAttribute("name")=="ensScript"){var s=scripts.shift();s=s.replace(/\<\/script\>/i,"");s=s.replace(/\s"/g,'"');s=s.split("\x3e");var attr=s[0].split(" ");var script=document.createElement("script");for(var j=1;j<attr.length;j++){attr[j]=attr[j].split("\x3d");var attrName=attr[j].shift();attr[j]=
attr[j].join("\x3d");if(attr[j].match(/^(\'|\")/)){var wrapper=attr[j].slice(0,1);attr[j]=attr[j].slice(1,attr[j].length);attr[j]=attr[j].slice(0,attr[j].lastIndexOf(wrapper))}if(attrName.toLowerCase()=="src")attr[j]=attr[j].replace(/\'$/,"");script.setAttribute(attrName,attr[j]);script.text=s[1]}repSpans.push(spans[i]);repScripts.push(script)}for(var i=repSpans.length-1;i>=0;i--)d.replaceChild(repScripts[i],repSpans[i]);document.body.appendChild(d)}else document._write(x)}}else;},559815,261016);
Bootstrapper.bindImmediate(function(){var Bootstrapper=window["Bootstrapper"];var ensightenOptions=Bootstrapper.ensightenOptions;var monetateStringInfo="";var monetateInScope=0;if(window.location.href.indexOf("disablemonetate")<0)if(window.location.host.indexOf(".microsoftstore.com")>-1&&window.location.href.indexOf("msusa")>-1){monetateStringInfo="b.monetate.net/js/1/a-964d0bce/p/us.microsoftstore.com/";monetateInScope=1}else if(window.location.host.indexOf(".microsoftstore.com")>-1&&window.location.href.indexOf("msaus")>
-1){monetateStringInfo="b.monetate.net/js/1/a-964d0bce/p/aus.microsoftstore.com/";monetateInScope=1}if(monetateInScope){var monetateT=(new Date).getTime();(function(){var p=document.location.protocol;if(p=="http:"||p=="https:"){var m=document.createElement("script");m.type="text/javascript";m.async=true;m.src=(p=="https:"?"https://s":"http://")+monetateStringInfo+Math.floor((monetateT+1631694)/36E5)+"/g";var s=document.getElementsByTagName("script")[0];s.parentNode.insertBefore(m,s)}})()}},549077,
246729);Bootstrapper.getServerComponent(Bootstrapper.getExtraParams ? Bootstrapper.getExtraParams() : undefined);}})();