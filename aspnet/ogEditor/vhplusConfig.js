 /* -------------------------------------------------------------------
 * A site-root relative path, which is resolved against the site root.
 * Your site root is http://www.yourdomain.com 
 * Please use single-slash for delimiter character in the file path
 * ----------------------------------------------------------------- */
var pathToOgEditor = "/ogEditor"; // a path from your site root to the ogEditor folder.
var profileRoot = "/workplace/users"; // a path from your site root to the end-user-workspace directory.
var pathToYourEditorFolder = "/";// a path from your site root to the folder containing ogEditor embedded page.


 /* -------------------------------------------------------------------
 * The relative path from ogEditor folder to
 * the location of your company logo image.
 * ----------------------------------------------------------------- */
var companyLogo = "img/ogeditor_logo.png";// a path from ogEditor folder to the location of your company logo image.


var isDemo = false; //Demo version does not allow file saving, image uploading, and folder creation.

// the value should be true or false. if isFullScreen is true then full screen mode will be applied
var isFullScreen = true;

// if parentNodeID is blank then ogEditor will be inserted after opening body tag (or before the end body tag).
var parentNodeID = "";// should be element id

// if isFullScreen is true then the value of ogEditorWidth will be ignored
var ogEditorWidth = "";// should be number of pixels

// if isFullScreen is true then the value of ogEditorHeight will be ignored
var ogEditorHeight = "";// should be number of pixels

var indentWidthForText = 5;// should be number of spaces

var indentWidthForHtml = 20;// should be number of pixels
	
var maxUndoRedo = 7;// should be positive number bigger than zero

var DefaultEncoding = "UTF-8"; // do not change this

var htmlExt = ",html,htm,"; // do not change this

var acceptExtension = ",html,htm,xml,js,css,txt,php,aspx,asp,jsp,java,";//,ini,log";// do not change this

var Vhplustoolbar = new Array();

Vhplustoolbar["default"] = [
'open','new','save','print','|','cut','copy','paste','|','undo','redo','find','|','bold','italic','underline','strikethrough','subscript','superscript','|','link','table','image','orderlist','uorderlist','fontcolor','backcolor','justifyleft','justifycenter','justifyright','indent','outdent','rule','pagebreak','guideline','surround','cctrl'
];

Vhplustoolbar["simple"] = [
'open','new','save','|','cut','copy','paste','|','undo','redo','|','bold','italic','underline'
];


 /* -------------------------------------------------------------------
 * Custom Control related variables
 * ----------------------------------------------------------------- */
var ogCControl = new Object();
ogCControl["gradButton"] = {
	name:"gradButton",
	desc:"Custom Control: gradButton",
	ogCCGeneral:[{attribute:"href", key:"href", require:"true", desc:"", value:"javascript:submit()"},
                {attribute:"label", key:"label", require:"true", desc:"", value:"Submit"}],
    ogCCDetails:[{attribute:"id", key:"id", require:"false", desc:"", value:""},
                {attribute:"name", key:"name", require:"false", desc:"", value:""}]
    };
ogCControl["naviMenu"] = {
	name:"naviMenu",
	desc:"Custom Control: naviMenu",
	ogCCGeneral:[],
	ogCCDetails:[{attribute:"id", key:"id", require:"false", desc:"", value:""},
                {attribute:"name", key:"name", require:"false", desc:"", value:""}]
    };



 /* -------------------------------------------------------------------
 * Template related variables
 * ----------------------------------------------------------------- */
var templates = new Array;
templates[0] = {name:"diary", desc:""};
templates[1] = {name:"essay", desc:""};
templates[2] = {name:"ogForm", desc:""};
templates[3] = {name:"3colmnLayout", desc:""};
templates[4] = {name:"blank", desc:""};