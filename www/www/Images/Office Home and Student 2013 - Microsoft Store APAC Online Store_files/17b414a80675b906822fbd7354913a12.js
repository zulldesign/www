Bootstrapper.bindDependencyDOMParsed(function(){var Bootstrapper=window["Bootstrapper"];var ensightenOptions=Bootstrapper.ensightenOptions;window.varSegmentation=0;varClickTracking=0;window.varCustomerTracking=1;var Route=12280;var Ctrl="SD882";var ctURLs=["YourNextPC/categoryID.67297800"];var ctURL2lc=window.location.href.toLowerCase();for(var i=0;i<ctURLs.length;i++)if(ctURL2lc.indexOf(ctURLs[i].toLowerCase())>-1){console.log("CT\x3d1");varClickTracking=1;break}var fl=0,sessionId="",sessionDuration=
18E5,sessionCookieName="MC0",qs="",imgArray=[],imgArrayIndex=0,tz=420,clickedElements=new Array("A","IMG","AREA","INPUT"),setCot=0,trans_pixel_url="//c.microsoft.com/trans_pixel.aspx?",autoFirePV=0,muidCreated=0,coreAttributes=",sr,bs,ts,tz,ctrl,route,ti,si,se,sv,fi,fv,cid,tr,cn,ct,cot,cs,cnt,hp,cd,rsd,rsus,rsqs,rihs,r,pkey,",customTags="",clickInfo="",customInfo="",wcs=[],na=[],ms=[],vs=3.5;document.newasyncwrite=function(c){try{if(document.getElementById("_TMInsertedData")===null){var newNode=document.createElement("div");
newNode.style.display="none";newNode.id="_TMInsertedData";document.getElementsByTagName("body")[0].appendChild(newNode)}document.getElementById("_TMInsertedData").innerHTML+=c}catch(e){}};function MscomInit(){MscomsetEvents();window.varAutoFirePV!=undefined?autoFirePV=window.varAutoFirePV:autoFirePV=1;if(autoFirePV==1)MscomSendPageView(window.varCustomerTracking!=undefined&&window.varCustomerTracking==1?1:0);else{MscomSetSharedData();if(muidCreated!=1)MscomGetMUID(0)}}function MscomSendPageView(createMuid){MscomResetArrays();
MscomSetSharedData(0);wcs["wcs.et"]=0;if(createMuid!=undefined&&createMuid!=0&&muidCreated!=1)MscomGetMUID(1);else MscomBeacon()}function MscomCustomEvent(){try{MscomResetArrays();MscomSetSharedData(5);wcs["wcs.et"]=1;var len=arguments.length;for(var i=0;i<len;){var argumentname=arguments[i].toString().toLowerCase();var argvalue;var argname;var index=argumentname.indexOf("\x3d");if(index>=0){argname=unescape(argumentname.substring(0,index));argvalue=unescape(argumentname.substring(index+1,argumentname.length));
argvalue=argvalue==undefined?"":argvalue;if(coreAttributes.indexOf(","+argname.toLowerCase()+",")>=0){wcs["wcs."+argname.toLowerCase()]=argvalue==undefined?"":argvalue;i=i+1}else{argname.indexOf("ms.")==0?ms[argname]=argvalue:na[argname]=argvalue;i=i+1}}else{argname=arguments[i].toString();argvalue=arguments[i+1]==undefined?"":arguments[i+1].toString();argname.indexOf("wcs.")==0?wcs[argname.toLowerCase()]=argvalue:argname.indexOf("ms.")==0?ms[argname]=argvalue:coreAttributes.indexOf(","+argname.toLowerCase()+
",")>=0?wcs["wcs."+argname.toLowerCase()]=argvalue:na[argname]=argvalue;i=i+2}}MscomBeacon()}catch(e){}}function MscomProcessClick(event){MscomResetArrays();wcs["wcs.et"]=2;try{var evt=event||window.event;var e;if(evt){e=evt.srcElement||evt.target;while(e.tagName&&MscomIsInList(e.tagName)==0)e=e.parentElement||e.parentNode}var pii=0;if(e&&e.tagName)switch(e.tagName){case "A":MscomSetSharedData(1);MscomReadAllTags(e);pii=MscomIsPII(e);if(pii==0){var title;if(document.all)title=e.innerText||e.innerHTML;
else title=e.text||e.innerHTML;wcs["wcs.cn"]=title;wcs["wcs.cid"]=MscomGetId(e);wcs["wcs.ct"]=e.href?e.href:""}MscomBeacon();break;case "IMG":MscomSetSharedData(2);MscomReadAllTags(e);pii=MscomIsPII(e);if(pii==0){wcs["wcs.cn"]=e.alt?e.alt:"";wcs["wcs.cid"]=MscomGetId(e);wcs["wcs.ct"]=MscomGetImageHREF(e)}MscomBeacon();break;case "AREA":MscomSetSharedData(3);MscomReadAllTags(e);pii=MscomIsPII(e);if(pii==0){wcs["wcs.cn"]=e.alt?e.alt:"";wcs["wcs.cid"]=MscomGetId(e);wcs["wcs.ct"]=e.href?e.href:""}MscomBeacon();
break;case "INPUT":MscomSetSharedData(4);MscomReadAllTags(e);var type=e.type||"";var ctx="";if(type&&(type=="button"||type=="reset"||type=="submit"||type=="image")||type=="text"&&(evt.which||evt.keyCode)==13){var t=e.value||e.name||e.alt||e.id;pii=MscomIsPII(e);if(pii==0){wcs["wcs.cn"]=t?t:"";wcs["wcs.cid"]=MscomGetId(e)}if(e.form){wcs["wcs.ct"]=e.form.action||window.location.pathname;var elems=e.form.elements;var n=1;for(var i=0;i<elems.length;i++){var etype=elems[i].type;if(etype=="text"){pii=MscomIsPII(elems[i]);
if(pii==0){ctx+="\x26wcs.t"+n+"\x3d"+MscomEncode(elems[i].name||elems[i].id)+"\x26wcs.v"+n+"\x3d"+MscomEncode(elems[i].value);n++}}}}else wcs["wcs.ct"]=window.location.pathname;if(ctx!="")wcs["wcs.ctx"]=ctx;else wcs["wcs.ctx"]=""}MscomBeacon();break;default:break}}catch(e){}}function MscomBeacon(){try{var src=[];src.push(window.location.protocol+trans_pixel_url);MscomInitMeta();var wcsvalues=MscomGetStrFromArray(wcs);if(wcsvalues.charAt(0)=="\x26")wcsvalues=wcsvalues.substring(1);src.push(wcsvalues);
src.push(MscomGetStrFromArray(ms));src.push(MscomGetStrFromArray(na));var srcString=src.join("");if(srcString.length>2048)srcString=srcString.substring(0,2038)+"\x26wcs.tr\x3d1";else srcString+="\x26wcs.tr\x3d0";if(document.images){imgArray[imgArrayIndex]=new Image;imgArray[imgArrayIndex].src=srcString;imgArrayIndex++}else document.newasyncwrite('\x3cIMG ALT\x3d"" BORDER\x3d"0" NAME\x3d"bImg" WIDTH\x3d"1" HEIGHT\x3d"1" SRC\x3d"'+srcString+'"/\x3e')}catch(e){}}window.MscomBeacon=window.MscomBeacon||
MscomBeacon;window.MscomProcessClick=window.MscomProcessClick||MscomProcessClick;function MscomGetDebugValues(){wcs["wcs.v"]=vs;window.varCustomerTracking!=undefined?wcs["wcs.vct"]=window.varCustomerTracking:wcs["wcs.vct"]="";window.varSegmentation!=undefined?wcs["wcs.vs"]=window.varSegmentation:wcs["wcs.vs"]="";varClickTracking!=undefined?wcs["wcs.vclt"]=varClickTracking:wcs["wcs.vclt"]="";window.varAutoFirePV!=undefined?wcs["wcs.vfpv"]=window.varAutoFirePV:wcs["wcs.vfpv"]=""}function MscomSetTitle(){wcs["wcs.ti"]=
document.title}function MscomSetTimeZoneOffSet(){var currDate=new Date;tz=currDate.getTimezoneOffset();wcs["wcs.tz"]=tz/-60}function MscomSetReferrer(){var ref=document.referrer;if(ref!=null&&ref!="")wcs["wcs.r"]=ref}function MscomSetTimeStamp(){var currDate=new Date;var currTime=currDate.getTime();wcs["wcs.ts"]=currTime.toString()}function MscomSetScreenResolution(){if(typeof screen=="object")wcs["wcs.sr"]=screen.width+"x"+screen.height}function MscomSetClickStreamFlag(){if(window.varSegmentation!=
undefined&&varSegmentation==1)wcs["wcs.cs"]="1"}function MscomReadAllTags(obj){while(obj&&obj!="undefined"){MscomReadElementTags(obj);obj=obj.parentElement||obj.parentNode}}function MscomSetCot(cotValue){cotValue!=undefined?wcs["wcs.cot"]=cotValue:wcs["wcs.cot"]=""}function MscomSetSharedData(cotValue){MscomSetTimeZoneOffSet();MscomSetCot(cotValue);MscomSetRouteCtrl();MscomSetTimeStamp();MscomSetCookieDisabledFlag();MscomSetEventId();MscomSetScreenResolution();MscomGetBrowserSize();MscomGetCTypeHpInfo();
MscomSetClickStreamFlag();MscomIsHP();MscomSetReferrer();MscomSetTitle();MscomGetCurrentSD();MscomGetDebugValues()}function MscomGetCurrentSD(){wcs["wcs.rsd"]=window.location.host;if(window.location.pathname!="")wcs["wcs.rsus"]=window.location.pathname;else wcs["wcs.rsus"]="";if(window.location.search!="")wcs["wcs.rsqs"]=window.location.search;else wcs["wcs.rsqs"]="";if(window.location.protocol=="https"||window.location.protocol=="https:")wcs["wcs.rihs"]="1";else wcs["wcs.rihs"]="0"}function MscomGetFlashInfo(){var flashMax=
(new Date).getYear()-1992;if(navigator.userAgent.indexOf("MSIE")!=-1)for(var i=flashMax;i>0;i--)try{var flash=new ActiveXObject("ShockwaveFlash.ShockwaveFlash."+i);wcs["wcs.fi"]="1";wcs["wcs.fv"]=i+".0";break}catch(e){}else if(navigator.plugins["Shockwave Flash"]){wcs["wcs.fi"]="1";var plugin=navigator.plugins["Shockwave Flash"];wcs["wcs.fv"]=plugin.description.split(" ")[2]}}function MscomInitMeta(){var metaelems;if(document.all)metaelems=document.all.tags("meta");else if(document.documentElement)metaelems=
document.getElementsByTagName("meta");metaTags="";if(typeof metaelems!="undefined")for(var i=0;i<metaelems.length;i++){var meta=metaelems.item(i);if(meta.name){var mt=meta.name.toLowerCase();if(mt.indexOf("ms.")==0)ms[meta.name]=meta.content}}}function MscomReadElementTags(obj){var result="";if(obj){var pii=MscomIsPII(obj);if(pii==0)for(var attr in obj.attributes)if(attr!=undefined)if(obj.attributes[attr]!=null&&obj.attributes[attr]!=undefined){var nn=obj.attributes[attr].name;if(nn!=null&&nn!=undefined){var nnl=
nn.toLowerCase();if(nnl.indexOf("ms.")==0)ms[nn]=obj.attributes[attr].value}}}return result}function MscomSetEventId(){wcs["wcs.eid"]=GenerateGuid()}function MscomGetBrowserSize(){if(document.body.clientWidth!=undefined)wcs["wcs.bs"]=document.body.clientWidth+"x"+document.body.clientHeight;else if(document.documentElement&&document.documentElement.clientWidth!=undefined)wcs["wcs.bs"]=document.documentElement.clientWidth+"x"+document.documentElement.clientHeight;else if(window.innerWidth!=undefined)wcs["wcs.bs"]=
window.innerWidth+"x"+window.innerHeight}function MscomSetRouteCtrl(){Route!=undefined?wcs["wcs.route"]=Route:wcs["wcs.route"]="";Ctrl!=undefined?wcs["wcs.ctrl"]=Ctrl:wcs["wcs.ctrl"]=""}function MscomGetCTypeHpInfo(){if(document.body&&document.body.addBehavior){document.body.addBehavior("#default#clientCaps");if(document.body.connectionType)wcs["wcs.cnt"]=document.body.connectionType}}function MscomIsHP(){if(document.body&&document.body.addBehavior){document.body.addBehavior("#default#homePage");
wcs["wcs.hp"]=document.body.isHomePage(location.href)?"1":"0"}}function MscomSetCookieDisabledFlag(){var cookiePre="";var index=document.cookie.indexOf(sessionCookieName+"\x3d");if(index==-1){MscomSetTimeStamp();sessionId=wcs["wcs.ts"];if(wcs["wcs.cd"]==1)return;cookiePre=sessionCookieName+"\x3d"+sessionId}else{var start=index+sessionCookieName.length+1;var end=document.cookie.length;cookiePre=sessionCookieName+"\x3d"+document.cookie.substring(start,end)}document.cookie=cookiePre;index=document.cookie.indexOf(sessionCookieName+
"\x3d");index==-1?wcs["wcs.cd"]=1:wcs["wcs.cd"]=0}function GuidPart(){return((1+Math.random())*65536|0).toString(16).substring(1)}function GenerateGuid(){return GuidPart()+GuidPart()+"-"+GuidPart()+"-"+GuidPart()+"-"+GuidPart()+"-"+GuidPart()+GuidPart()+GuidPart()}function Mscomdebug(){window.alert(arguments[0])}function MscomGetId(obj){if(obj){if(obj.id==undefined)return"";return obj.id}return""}function MscomGetImageHREF(obj){var temp=obj;if(obj){obj=obj.parentElement||obj.parentNode;if(obj&&obj.tagName==
"A")return obj.href?obj.href:"";if(temp&&temp.src)return temp.src}return""}function MscomIsInList(tag){for(var t in clickedElements)if(clickedElements[t]==tag.toUpperCase())return 1;return 0}function MscomsetEvents(){if(varClickTracking!=undefined&&varClickTracking==1)if(document.body)if(document.body.addEventListener){var event=navigator.appVersion.indexOf("MSIE")!=-1?"click":"mousedown";document.body.addEventListener(event,window["MscomProcessClick"],0)}else if(document.body.attachEvent)document.body.attachEvent("onclick",
window["MscomProcessClick"])}function MscomGetMUID(firebeacon){if(muidCreated==1)if(firebeacon==1){MscomBeacon();return}if(window.varCustomerTracking!=undefined&&varCustomerTracking==1)try{var muidsrc=window.location.protocol+"//c1.microsoft.com/c.gif?DI\x3d4050\x26did\x3d1\x26t\x3d";if(firebeacon==1)document.newasyncwrite('\x3ciframe id\x3d"_msnFrame" src\x3d"'+muidsrc+'" style\x3d"z-index:-1;height:1px;width:1px;display:none;visibility:hidden;" onload\x3d"javascript:MscomBeacon();"\x3e\x3c/iframe\x3e');
else document.newasyncwrite('\x3ciframe id\x3d"_msnFrame" src\x3d"'+muidsrc+'" style\x3d"z-index:-1;height:1px;width:1px;display:none;visibility:hidden;"\x3e\x3c/iframe\x3e');muidCreated=1}catch(e){muidCreated=0}else if(firebeacon==1)MscomBeacon()}function MscomEncode(S){return typeof encodeURIComponent=="function"?encodeURIComponent(S):escape(S)}function MscomGetStrFromArray(strarray){var retValue="";for(var key in strarray)if(strarray.hasOwnProperty(key))if(strarray[key]!=undefined)retValue+="\x26"+
MscomEncode(key)+"\x3d"+MscomEncode(strarray[key]);else retValue+="\x26"+MscomEncode(key)+"\x3d";return retValue}function MscomResetArrays(){wcs=[];na=[];ms=[]}function MscomIsPII(obj){try{var pii=obj.getAttribute("data-dc");if(pii!=null&&pii!=undefined)if(pii.toLowerCase()=="pii")return 1;else return 0;else return 0}catch(e){return 0}}if(!varClickTracking)MscomInit()},545827,[558977],246576,[246573]);