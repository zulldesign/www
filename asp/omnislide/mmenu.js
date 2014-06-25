if (typeof window.attachEvent=='object'){
document.write('<!--[if lte IE 6]>\n'+
'<script type="text/javascript">\n'+
'var ie6_or_less=1;\n'+
'<\/script>\n'+
'<![endif]-->\n'+
'<!--[if lt IE 5.5]>\n'+
'<script type="text/javascript">\n'+
'var less_than_ie5_5=1;\n'+
'<\/script>\n'+
'<![endif]-->')
}

var menu=[], resizereinit=true;
function truebody(){
return (document.compatMode && document.compatMode!="BackCompat")? document.documentElement : document.body;
}

function getedge(o, is_top){
var edge;
if (is_top)
edge=window.pageYOffset? window.pageYOffset : truebody().scrollTop? truebody().scrollTop : 0;
else{
edge=o.menupos=='left'? 0 : truebody().clientWidth? truebody().clientWidth : window.innerWidth&&truebody().offsetHeight<=window.innerHeight? window.innerWidth :  window.innerWidth? window.innerWidth-20 : 0;
edge+=window.pageXOffset? window.pageXOffset : truebody().scrollLeft? truebody().scrollLeft: 0;
}
return edge;
}

function keep_in_view(o){
if(o.keepinview){
if(o.m.ft){
o.m.ft=0;
o.m.topP=o.m.offsetTop;
o.m.ltop=0;
}
var pt=getedge(o, 'top'), ks=typeof o.keepinview=='number'&&o.keepinview<o.m.topP&&o.keepinview>0? o.keepinview : o.m.topP, smooth=0;
if (pt!=o.m.ltop){
if(o.menupos=='top')
o.m.style.visibility='hidden';
smooth = pt>o.m.topP-ks? .2 * (pt - o.m.ltop - o.m.topP + ks) : o.m.ltop>0? -.2 * o.m.ltop : 0;
smooth = smooth > 0 ? Math.ceil(smooth) : Math.floor(smooth);
}
else if(o.menupos=='top')
o.m.style.visibility='';
o.m.style.top=(o.m.style.top? parseInt(o.m.style.top) : o.m.topP)+smooth+'px';
o.m.ltop += smooth;
}
if(o.menupos=='top'){
if(typeof o.menuleft=='string'){
o.m.style.left=o.menuleft
o.m.style.marginLeft=Math.floor(o.m.getElementsByTagName('div')[0].offsetWidth/-2)+'px';
o.lleft=o.m.offsetLeft;
}
o.m.style.marginLeft=0;
o.m.style.left=o.lleft+(window.pageXOffset? window.pageXOffset : truebody().scrollLeft? truebody().scrollLeft: 0)+'px';
}
else
o.m.style.left=getedge(o)-(o.menupos=='right'? o.m.offsetWidth : 0)+'px';
}
function move(el, num){
el.getElementsByTagName('div')[0].style[el.menupos]=parseInt(el.getElementsByTagName('div')[0].style[el.menupos])+num+'px';
if(el.menupos=='right'){
if(el.kviewtype=='absolute')
el.style.left=parseInt(el.style.left)-num+'px';
el.style.width=parseInt(el.style.width)+num+'px';
}
if(num>0)
el.moving=setTimeout(function(){movein(el)}, el.menuspeed)
else
el.moving=setTimeout(function(){moveout1(el)}, el.menuspeed)
}
function movein(el){
var m1=parseInt(el.getElementsByTagName('div')[0].style[el.menupos]);
if(el.moving)
clearTimeout(el.moving);
if (m1<-1*el.borderwidth)
move(el, Math.min(-1*m1-el.borderwidth, 10));
}
function moveout(el){
if(el.moving)
clearTimeout(el.moving);
el.moving=setTimeout(function(){moveout1(el)}, el.menupause);
}
function moveout1(el){
var aw=el.menupos=='top'? el.b.offsetHeight : el.b.offsetWidth, m1=el.getElementsByTagName('div')[0];
if(el.moving)
clearTimeout(el.moving);
if (parseInt(m1.style[el.menupos])>aw-(el.menupos=='top'? m1.offsetHeight : m1.offsetWidth)+10)
move(el, -10);
else {
m1.style[el.menupos]=aw-(el.menupos=='top'? m1.offsetHeight : m1.offsetWidth)+'px';
if(el.menupos=='right'){
el.style.width=aw+'px';
if(el.kviewtype=='absolute'){
var ed=truebody().clientWidth? truebody().clientWidth : window.innerWidth&&truebody().offsetHeight<=window.innerHeight? window.innerWidth :  window.innerWidth? window.innerWidth-20 : 0;
ed+=window.pageXOffset? window.pageXOffset : truebody().scrollLeft? truebody().scrollLeft: 0;
el.style.left=ed-el.offsetWidth+'px';
}
}
}
}
function to_em(n, o){
return Math.round((n/(16*parseInt(o.fontsize)/100))*1000)/1000;
}
function getrows(o){
var r=o.menuItems.length+(o.wrapbar? 1 : 0);
for (var i_tem = 0; i_tem < o.menuItems.length; i_tem++)
if (o.menuItems[i_tem][4]&&o.menuItems[i_tem][4]=='no')
r--;
return r;
}
function make_bar(o){
var bt=o.menupos=='right'||o.menupos=='top'? '<tr>' : '';
bt+='<td id="'+o.id+'bar" '+(o.menupos=='top'? 'colspan="'+o.d_colspan : 'rowspan="'+getrows(o))+'">'
if (!/<img/.test(o.bartext.toLowerCase())){
for (var i_tem = 0; i_tem < o.bartext.length-(o.menupos=='top'? 1 : 0); i_tem++)
bt+=o.menupos=='top'&&o.bartext.charAt(i_tem)==' '? '\u00a0 ' : o.menupos=='top'? o.bartext.charAt(i_tem)+' ' : '<br>'+o.bartext.charAt(i_tem);
bt+=o.menupos=='top'? o.bartext.charAt(o.bartext.length-1)+'</td>' : '<br>\u00a0</td>';
}
else
bt+=o.bartext+'</td>'
return bt+(o.menupos=='right'? '\n' : '</tr>\n');
}

function make_style(o){
if(o.user_defined_stylesheet&&!o.design_mode)
return '';
var sheet=''
sheet+='#'+o.id+' {\n'+
(o.menupos=='top'? 'top:0;\n' : 'top:'+o.menutop+'px; /*set initial Height from top*/\n')+
(o.menupos=='top'? 'left:'+o.menuleft+(typeof o.menuleft=='number'? 'px' : '')+';\n' : '')+
(o.menupos=='right'&&o.kviewtype=='fixed'? 'right:0;\n' : '')+
'position:'+o.kviewtype+';\n'+
'overflow:'+(o.menupos=='right'? 'hidden' : 'visible')+';\n'+
'z-index:100;\n'+
(o.menupos=='left'? 'left:0;\n' : '')+
'}\n'+
'#'+o.id+' div {\n'+
'border-width:'+(typeof o.outbrdwidth=='number'? o.outbrdwidth+'px' : o.outbrdwidth)+'; /*Menu\'s outer border*/\n'+
'border-style:'+o.outbrdstyle+';\n'+
(o.outbrdcolor=='none'? '' : 'border-color:'+o.outbrdcolor+';\n')+
'position:absolute;\n'+
'color:black;\n'+
'background-color:transparent;\n'+
'}\n'+
'#'+o.id+' table {\n'+
'border:'+o.borderwidth+'px '+o.borderstyle+' '+o.bordercolor+'; /*Menu\'s inner border*/\n'+
(o.menupos=='top'? 'border-left-width:0;\n' : '')+
(o.menupos=='top'? 'border-bottom-width:0;\n' : '')+
'font-family:'+o.menufont+', sans-serif; /*Overall font for Menu*/\n'+
'font-size:'+o.fontsize+';\n'+
'border-collapse:collapse;\n'+
'background-color:'+(o.allowtransparent? 'transparent' : o.bordercolor)+';\n'+
'width:'+to_em(o.barwidth+o.hdingwidth+o.borderwidth*(o.d_colspan+2), o)+'em;\n'+
'}\n'+
'#'+o.id+' td { /*Characteristics for cells in the menu table - do not specify width here*/\n'+
'border-bottom:'+o.borderwidth+'px '+o.borderstyle+' '+o.bordercolor+';\n'+
'border-left:'+o.borderwidth+'px '+o.borderstyle+' '+o.bordercolor+';\n'+
'height:'+to_em(o.linkheight, o)+'em;\n'+
'padding:0;\n'+
'margin:0;\n'+
'text-align:'+o.linktxtalign+';\n'+
'}\n'+
'#'+o.id+' #'+o.id+'bar { /*Characteristics for initially visible \'draw\' bar (the vertical cell)*/\n'+
(o.menupos=='top'? 'height:' : 'width:')+to_em(o.barwidth+(document.all||o.menupos=='top'? o.borderwidth*2 : 0), o)+'em;\n'+
'background-color:'+o.barbgcolor+';\n'+
'color:'+o.barcolor+';\n'+
'font-weight:'+o.barfontweight+';\n'+
'text-align:'+o.baralign+';\n'+
(o.menupos=='top'? '' : 'border-width:0;\n')+
'cursor:default;\n'+
'}\n'+
'#'+o.id+' .heading { /*Characteristics for heading cells in the menu table*/\n'+
'height:'+to_em(o.hdingheight, o)+'em;\n'+
'color:'+o.hdingcolor+';\n'+
'font-weight:'+o.hdingfontweight+';\n'+
'text-indent:'+o.hdingindent+'ex;\n'+
'background-color:'+o.hdingbgcolor+'; /*Background Color for menu headings */\n'+
'width:'+to_em(o.hdingwidth, o)+'em; /*This will be the menu body width.  This'+(o.menupos!='top'? ' (plus #'+o.id+'bar width for left and right menus)' : '')+' and 4 times the border width should also be the menu table\'s approximate width*/\n'+
'vertical-align:'+o.hdingvalign+';\n'+
'text-align:'+o.hdingtxtalign+';\n'+
'border-left-color:'+o.hdingbgcolor+';\n'+
'border-left-style:solid;\n'+
'}\n'+
(o.wrapbar&&o.menupos!='top'? '#'+o.id+' #'+o.id+'lastrow {\n'+
'height:'+to_em(o.barwidth, o)+'em;\n'+
'background-color:'+o.barbgcolor+';\n'+
'border-width:0;\n'+
'margin:0 0 '+o.borderwidth+'px '+o.borderwidth+'px;\n'+
'}\n' : o.menupos!='top'? '#'+o.id+' #'+o.id+'lastrow {\n'+
'border-bottom-width:0;\n'+
'margin:0 0 '+o.borderwidth+'px '+o.borderwidth+'px;\n'+
'}\n' : '')+
'#'+o.id+' a {\n'+
'width:100%;\n'+
'height:100%;\n'+
'display:block;\n'+
'padding-top:'+to_em(o.linktopad, o)+'em;\n'+
'}\n';
if(o.design_mode){
if(document.getElementById('ooostyle'))
alert('Only one menu\'s script generated styles may be displayed at a time!\n\nCurrently showing '+document.getElementById('ooostyle').tell+'\'s stylesheet\n\n(or there is a syntax error - most\n\u00a0\u00a0\u00a0\u00a0likely in the menuItem.js file)');
else{
var isusing=o.user_defined_stylesheet? ' not' : '';
var sw=(window.innerWidth? window.innerWidth : truebody().clientWidth)/1.5; 
document.write('<textarea id="ooostyle" cols="'+Math.floor(sw/8)+'" rows="65" wrap="off" style="margin-left:-'+Math.floor(sw/2)+'px;overflow:auto;position:absolute;top:10px;left:50%;z-index:1000;">\n')
document.write('\/* '+o.id+'\'s Script Generated Styles: */\n\/* '+o.id+' is'+isusing+' currently using these via the script */\n\n'+sheet);
document.write('\n\/* End '+o.id+'\'s Script Generated Styles */');
document.write('</textarea>')
document.getElementById('ooostyle').tell=o.id;
}
}
if(!o.user_defined_stylesheet)
return '<style type="text/css">\n'+sheet+'</style>';
return '';
}
function make_style_make_menu(o, s){
if(s){
if(!o.id) {alert('a unique id is required for each menu');return;};
if(!o.menuItems||o.menuItems.constructor!=Array) {alert('an array of menu items is required for each menu');return;};
if(!o.menutop) {o.menutop=150};
if(!o.menuleft) {o.menuleft='50%'};
if(!o.keepinview&&typeof o.keepinview=='boolean')
o.keepinview=false;
else if(!o.keepinview) {o.keepinview=30};
if(!o.menuspeed) {o.menuspeed=20};
if(!o.menupause) {o.menupause=500};
if(!o.d_colspan) {o.d_colspan=2};
if(!o.allowtransparent) {o.allowtransparent=false};
if(!o.barwidth) {o.barwidth=22};
if(!o.hdingwidth) {o.hdingwidth=149};
if(!o.hdingheight) {o.hdingheight=22};
if(!o.hdingindent) {o.hdingindent=1};
if(!o.linkheight) {o.linkheight=16};
if(!o.outbrdwidth) {o.outbrdwidth=0};
if(!o.outbrdcolor) {o.outbrdcolor="none"};
if(!o.outbrdstyle) {o.outbrdstyle="none"};
if(!o.borderwidth) {o.borderwidth=1};
if(!o.bordercolor) {o.bordercolor="black"};
if(!o.borderstyle) {o.borderstyle="solid"};
if(!o.barcolor) {o.barcolor="white"};
if(!o.barbgcolor) {o.barbgcolor="#444444"};
if(!o.barfontweight) {o.barfontweight="bold"};
if(!o.baralign) {o.baralign="center"};
if(!o.menufont) {o.menufont="verdana"};
if(!o.fontsize) {o.fontsize="80%"};
if(!o.hdingcolor) {o.hdingcolor="white"};
if(!o.hdingbgcolor) {o.hdingbgcolor="#170088"};
if(!o.hdingfontweight) {o.hdingfontweight="bold"};
if(!o.hdingvalign) {o.hdingvalign="middle"};
if(!o.hdingtxtalign) {o.hdingtxtalign="left"};
if(!o.linktopad) {o.linktopad=0};
if(!o.linktxtalign) {o.linktxtalign="left"};
if(!o.linktarget) {o.linktarget=""};
if(!o.menupos) {o.menupos="left"};
if(!o.bartext) {o.bartext="SIDE MENU"};
if(!o.user_defined_stylesheet) {o.user_defined_stylesheet=false};
if(!o.user_defined_markup) {o.user_defined_markup=false};
if(!o.design_mode) {o.design_mode=false};
if(!o.wrapbar) {o.wrapbar=false};
if(!o.kviewtype) {o.kviewtype='absolute'};
if(typeof ie6_or_less!='undefined')
o.kviewtype='absolute';
else if(o.menupos=='top'&&o.kviewtype=='absolute')
o.kviewtype='fixed';
while(!o.menuItems[o.menuItems.length-1])
o.menuItems.length=o.menuItems.length-1;
document.write(make_style(o));
return;
}
else {
if(o.design_mode||!o.user_defined_markup){
var hw=o.hdingwidth;

var tb='<div id="'+o.id+'" onmouseover="movein(this);" onmouseout="moveout(this);"><div><table>\n';
tb+=o.menupos=='right'? make_bar(o) : '';
for (var i_tem = 0; i_tem < o.menuItems.length; i_tem++){
if ((o.menupos=='top'&&i_tem==0)||i_tem>0&&(!o.menuItems[i_tem-1][4]||o.menuItems[i_tem-1][4]!=='no'))
tb+='<tr>'
if (o.menuItems[i_tem][1]&&o.menuItems[i_tem][1]!==''){
tb+='<td '+(i_tem==o.menuItems.length-1&&!o.wrapbar&&o.menupos!='top'? 'id="'+o.id+'lastrow" ' : '')+'colspan="'+(o.menuItems[i_tem][3]&&o.menuItems[i_tem][3]!==''? o.menuItems[i_tem][3] : o.d_colspan)+'"><a href="'+o.menuItems[i_tem][1]+'" target="'+(o.menuItems[i_tem][2]? o.menuItems[i_tem][2] : o.linktarget)+'">'+o.menuItems[i_tem][0]+'</a></td>'
}
else
tb+='<td '+(i_tem==o.menuItems.length-1&&!o.wrapbar&&o.menupos!='top'? 'id="'+o.id+'lastrow" ' : '')+'class="heading" '+(o.menuItems[i_tem][3]&&o.menuItems[i_tem][3]!==''&&o.menuItems[i_tem][3]!==o.d_colspan? 'style="width:'+to_em(hw*o.menuItems[i_tem][3]/o.d_colspan, o)+'em;'+(i_tem>0&&o.menuItems[i_tem-1][4]&&o.menuItems[i_tem-1][4]=='no'? 'border-left-width:0;margin-left:'+o.borderwidth+'px;' : '')+'" ' : '')+'colspan="'+(o.menuItems[i_tem][3]&&o.menuItems[i_tem][3]!==''? o.menuItems[i_tem][3] : o.d_colspan)+'">'+o.menuItems[i_tem][0]+'</td>'
if (!o.menuItems[i_tem][4]||o.menuItems[i_tem][4]!=='no')
tb+=o.menupos=='left'&&i_tem==0? make_bar(o) : '</tr>\n';
}
tb+=o.wrapbar&&o.menupos!='top'? '<tr><td id="'+o.id+'lastrow" colspan="'+o.d_colspan+'">\u00a0</td></tr>\n' : '';
tb+=o.menupos=='top'? make_bar(o) : '';

if(!o.user_defined_markup)
document.write(tb+'</table></div></div>')
}
if(o.design_mode)
document.getElementById('ooostyle').value+='\n\n<!-- The Markup for '+o.id+' -->\n\n'+tb+'</table></div></div>\n\n<!-- End '+o.id+'\'s Markup -->'
o.m=document.getElementById(o.id);
var b=document.getElementById(o.id+'bar');
o.m.b=b;
o.m.ft=1;
o.m.menupos=o.menupos;
o.m.menupause=o.menupause;
o.m.menuspeed=o.menuspeed;
o.m.borderwidth=o.borderwidth;
o.m.kviewtype=o.kviewtype;
resizevent(o);
if(o.menupos=='top'&&typeof window.attachEvent=='object'&&typeof ie6_or_less!='undefined')
window.attachEvent('onscroll', function(){o.m.style.visibility='hidden';});
if(o.kviewtype=='absolute'&&(o.menupos!='top'||(typeof ie6_or_less!='undefined'&&typeof window.attachEvent=='object')))
setInterval(function(){keep_in_view(o)}, 20)
}
}

function resizevent(o){
var m1=o.m.getElementsByTagName('div')[0], bo=o.menupos=='top'? o.m.b.offsetHeight : o.m.b.offsetWidth;
m1.style[o.menupos]=bo-(o.menupos=='top'? m1.offsetHeight : m1.offsetWidth)+'px'
if(o.menupos=='right'){
if(o.kviewtype=='absolute')
o.m.style.left=getedge(o)-o.m.offsetWidth+'px';
o.m.style.width=bo+'px';
o.m.style.height=m1.offsetHeight+'px';
if(typeof less_than_ie5_5!='undefined'){
o.m.onmouseover(o.m);
o.m.onmouseout(o.m);
}
}
if(o.menupos=='top'){
o.m.style.width=m1.style.width=o.m.getElementsByTagName('table')[0].offsetWidth+'px';
if(typeof o.menuleft=='string')
o.m.style.marginLeft=Math.floor(m1.offsetWidth/-2)+'px';
}
}
/*

ONTEXTRESIZE EVENT SPOOFER

Including this file in your page will allow you to assign a function
to window.ontextresize, which will be called when the user changes the
size of text on the page.

Used with (as far as I know) permission from: http://forkandspoonhelmet.com/Fork_and_Spoon_Helmet

*/
var ontextresizeLastSize = false;
if (window.attachEvent) window.attachEvent("onload", initOntextresizeListener);
else if (window.addEventListener) window.addEventListener("load", initOntextresizeListener, false);
function initOntextresizeListener() {
if(!resizereinit||typeof less_than_ie5_5!='undefined')
return;
	var testDiv = document.createElement("div");
	testDiv.style.position = "absolute";
	testDiv.style.height = "1em";
	testDiv.style.width = "1em";
	testDiv.style.top = "-2em";
	testDiv.style.left = "-2em";
	var docTestDiv = document.body.appendChild(testDiv);
	docTestDiv.id = "ontextresizeTestDiv";
	ontextresizeListener = setInterval("ontextresizeCheckTestDiv()",100);
}
function ontextresizeCheckTestDiv() {
	if (ontextresizeLastSize!=document.getElementById("ontextresizeTestDiv").offsetWidth) {
		if (ontextresizeLastSize && window.ontextresize) window.ontextresize.call();
		ontextresizeLastSize = document.getElementById("ontextresizeTestDiv").offsetWidth;
	}
}
window.ontextresize=function(){
for (var i_tem = 0; i_tem < menu.length; i_tem++)
if(typeof menu[i_tem]!='undefined')
resizevent(menu[i_tem]);
};

function make_menus(){
if(document.getElementById){
for (var i_tem = 0; i_tem < menu.length; i_tem++)
if(typeof menu[i_tem]!='undefined')
make_style_make_menu(menu[i_tem], 's');
for (i_tem = 0; i_tem < menu.length; i_tem++)
if(typeof menu[i_tem]!='undefined')
make_style_make_menu(menu[i_tem]);
}
}