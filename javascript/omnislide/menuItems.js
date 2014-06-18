/***********************************************
* Omni Slide Menu script - © John Davenport Scheuer
* very freely adapted from Dynamic-FX Slide-In Menu (v 6.5) script- by maXimus
* This notice MUST stay intact for legal use
* Visit Dynamic Drive at http://www.dynamicdrive.com/ for full original source code
* as first mentioned in http://www.dynamicdrive.com/forums
* username:jscheuer1
***********************************************/

//One global variable to set, use true if you want the menus to reinit when the user changes text size (recommended):
resizereinit=true;

menu[1] = {
id:'menu1', //use unique quoted id (quoted) REQUIRED!!
fontsize:'100%', // express as percentage with the % sign
linkheight:22 ,  // linked horizontal cells height
hdingwidth:210 ,  // heading - non linked horizontal cells width
// Finished configuration. Use default values for all other settings for this particular menu (menu[1]) ///

menuItems:[ // REQUIRED!!
//[name, link, target, colspan, endrow?] - leave 'link' and 'target' blank to make a header
["Menu"], //create header
["Dynamic Drive", "http://www.dynamicdrive.com", ""],
["What's New", "http://www.dynamicdrive.com/new.htm",""],
["What's Hot", "http://www.dynamicdrive.com/hot.htm", ""],
["Message Forum", "http://www.dynamicdrive.com/forums", ""],
["Submit Script", "http://www.dynamicdrive.com/submitscript.htm", ""],
["Link to Us", "http://www.dynamicdrive.com/link.htm", ""],

["FAQ", "http://www.dynamicdrive.com/faqs.htm", "", 1, "no"], //create two column row, requires d_colspan:2 (the default)
["Email", "http://www.dynamicdrive.com/contact.htm", "",1],

["External Links", "", ""], //create header
["JavaScript Kit", "http://www.javascriptkit.com", "_new"],
["Freewarejava", "http://www.freewarejava.com", "_new"],
["Coding Forums", "http://www.codingforums.com", "_new"]  //no comma after last entry

]}; // REQUIRED!! do not edit or remove

menu[2] = {  // REQUIRED!!  This menu explicitly declares all available options even if they are the same as the defaults
id:'menu2', //use unique quoted id (quoted) REQUIRED!!
/////////////////////////////////////
///////////// no quotes for these properties (numbers represent pixels unless otherwise noted): ////////////
/////////////////////////////////////
user_defined_stylesheet:false, //if true, prevents script from generating stylesheet for this menu
user_defined_markup:false, //if true, prevents script from generating markup for this menu
design_mode:false,  //if true, generates a report of the script generated/intended styles and markup (as a design aid)
menutop:160,     // initial top offset - except for top menu, where it is meaningless
menuleft:'45%',    // initial left offset - only for top menu, as pixels (can be a quoted percentage - ex: '50%')
keepinview:80,   // Use false (for not static) - OR - true or numeric top offset when page scrolls
menuspeed:20,    // Speed of menu sliding smaller is faster (interval of milliseconds)
menupause:500,   // How long menu stays out when mouse leaves it (in milliseconds)
d_colspan:3,     // Available columns in menu body as integer
allowtransparent:false, // true to allow page to show through menu if other bg's are transparent or border has gaps
barwidth:20,     // bar (the vertical cell) width
wrapbar:true,    // extend and wrap bar below menu for a more solid look (default false) - will revert to false for top menu
hdingwidth:210,  // heading - non linked horizontal cells width
hdingheight:25,  // heading - non linked horizontal cells height
hdingindent:1,   // heading - non linked horizontal cells text-indent represents ex units (@8 pixels decimals allowed)
linkheight:20,   // linked horizontal cells height
linktopad:3,     // linked horizontal cells top padding
borderwidth:2,   // inner border-width used for this menu
/////////////////////////// quote these properties: /////////////////////
bordercolor:'#000080', // inner border color
borderstyle:'solid',    // inner border style (solid, dashed, inset, etc.)
outbrdwidth:'0ex 0ex 0ex 0ex', // outer border-width used for this menu (top right bottom left)
outbrdcolor:'lightblue',  // outer border color
outbrdstyle:'solid',     // outer border style (solid, dashed, inset, etc.)
barcolor:'white',        // bar (the vertical cell) text color
barbgcolor:'#4d6814',   // bar (the vertical cell) background color
barfontweight:'bold',    // bar (the vertical cell) font weight
baralign:'center',       // bar (the vertical cell) right left or center text alignment
menufont:'verdana',      // menu font
fontsize:'90%',          // express as percentage with the % sign
hdingcolor:'white',      // heading - non linked horizontal cells text color
hdingbgcolor:'#4d6814',  // heading - non linked horizontal cells background color
hdingfontweight:'bold',  // heading - non linked horizontal cells font weight
hdingvalign:'middle',    // heading - non linked horizontal cells vertical align (top, middle or center)
hdingtxtalign:'left',    // heading - non linked horizontal cells right left or center text alignment
linktxtalign:'left',     // linked horizontal cells right left or center text alignment
linktarget:'',           // default link target, leave blank for same window (other choices: _new, _top, or a window or frame name)
kviewtype:'fixed',       // Type of keepinview - 'fixed' utilizes fixed positioning where available, 'absolute' fluidly follows page scroll
menupos:'top',         // set side that menu slides in from (right or left or top)
bartext:'MY MENU',       // bar text (the vertical cell) use text or img tag
///////////////////////////
menuItems:[
//[name, link, target, colspan, endrow?] - leave 'link' and 'target' blank to make a header
["Hot Sites"], //create header
["Lois Images", "http://www.loisimages.com", "_new"],
["State Street Blues", "http://www.statestreetbluesstroll.com/","_new"],
["Media Jazz by Night", "http://www.mediajazzbynight.com/", "_new"],
["DD Help Forum", "http://www.dynamicdrive.com/forums", "_new"],

["Search", "", "", 2, "no"], //create two column header w/ 2 and 1 column members, requires d_colspan:3
["Search", "", "", 1],
["Google", "http://www.google.com/", "_new", 1, "no"], //create three column row, requires d_colspan:3
["Yahoo", "http://www.yahoo.com/", "_new", 1, "no"],
["AltaVista", "http://www.altavista.com/", "_new", 1],

["Personal", "", ""], //create header
["Ansi Art Gallery", "http://home.comcast.net/~ansiguy", "_new"],
["John's Javascript Jungle", "http://home.comcast.net/~jscheuer1/side", "_new"]  //no comma after last entry

]}; // REQUIRED!! do not edit or remove

menu[3] = {
id:'menu3', //use unique quoted id (quoted) REQUIRED!!
bartext:'RIGHT MENU',
menupos:'right',
kviewtype:'fixed', 
menuItems:[ // REQUIRED!!
//[name, link, target, colspan, endrow?] - leave 'link' and 'target' blank to make a header
["Menu"], //create header
["Dynamic Drive", "http://www.dynamicdrive.com", ""],
["What's New", "http://www.dynamicdrive.com/new.htm",""],
["What's Hot", "http://www.dynamicdrive.com/hot.htm", ""],
["Message Forum", "http://www.dynamicdrive.com/forums", ""],
["Submit Script", "http://www.dynamicdrive.com/submitscript.htm", ""],
["Link to Us", "http://www.dynamicdrive.com/link.htm", ""],

["FAQ", "http://www.dynamicdrive.com/faqs.htm", "", 1, "no"], //create two column row, requires d_colspan:2 (the default)
["Email", "http://www.dynamicdrive.com/contact.htm", "",1],

["External Links", "", ""], //create header
["JavaScript Kit", "http://www.javascriptkit.com", "_new"],
["Freewarejava", "http://www.freewarejava.com", "_new"],
["Coding Forums", "http://www.codingforums.com", "_new"]  //no comma after last entry

]}; // REQUIRED!! do not edit or remove

////////////////////Stop Editing/////////////////

make_menus();