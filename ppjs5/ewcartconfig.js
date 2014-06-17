
//
// PayPal Shop Maker Shopping Cart Configuration
// (C)2008-2012 e.World Technology Limited. All rights reserved.
//

var PAYPALSHOPMAKER = {};
(function(P) {

	// Note: If window.localStorage is available, it will be used instead of cookies.
	P.itemsPerCookie = 3; // cart items per cookie
	P.maxCartItems = (typeof window.localStorage == "undefined") ? 45 : 99; // max number of cart items
	P.cartStoName = "pp_cart_item_";
	P.cntStoName = "pp_cart_count";
	P.shipStoName = "pp_ship";
	P.cartDelimiter = "|";
	P.optionDelim = ", ";
	P.optionSep = ": ";
	P.showCartAddMsg = false; // 4.0
	P.showCartRemoveMsg = false; // 4.0

	// Hidden field names
	P.fldID = "id";
	P.fldDiscountType = "discounttype"; // 2.0
	P.fldShipType = "shiptype"; // 2.0
	P.fldTaxType = "taxtype"; // 3.0
	P.fldAmountBase = "amount_base";
	P.fldShipMethod = "shipmethod"; // shipping method
	P.fldDiscountCode= "discountcode"; // discount code // 4.0
	P.fldShipCost = "shipcost"; // ship cost // 3.5
	P.fldTaxCost = "taxcost"; // tax cost // 3.5
	P.fldHandleCost = "handlecost"; // handle cost // 3.5
	P.fldGrandTotal = "grandtotal"; // grand total // 3.5
	P.fldNetPrice = "netprice"; // 4.0

	// 5.0
	P.idCart = "ewCart";
	P.idCheckout = "ewCheckout";
	P.idShip = "ewShip";
	P.idConfirm = "ewConfirm";
	P.idFinish = "ewFinish";
	P.dsShopCart = [];
	P.dsShopCartItems = [];
	P.dsShipView = [];
	P.regions = [
		{id: P.idCart, data: "dsShopCart"},
		{id: P.idCheckout, data: "dsShopCart"},
		{id: P.idShip, data: "dsShipView"},
		{id: P.idConfirm, data: "dsShopCart"},
		{id: P.idFinish, data: ""}
	]; // template id and data
	P.itemForm = "ewItemForm";
	P.countryList = [];
	P.stateList = [];
	P.discountList = [];
	P.discountTypeList = [];
	P.shipMethodList = [];
	P.shipCostList0 = [];
	P.shipCostList1 = [];
	P.shipCostList2 = [];
	P.shipTypeList = [];
	P.taxList = [];
	P.CatId = ""; // Current cateogry ID
	P.CatPath = []; // Current category path
	P.CatName = ""; // Current category name
	P.MenuId = ""; // Current menu page ID

	// phone regular expression // 3.2
	// Note: Change the regular expression as needed, requires separator (fldPhoneSep)
	// if 3 parts, split as night_phone_a, night_phone_b and night_phone_c
	// if 2 parts, split as night_phone_a and night_phone_b
	// if no separator, submit as night_phone_b

	P.fldPhoneSep = "-"; // phone separator // 3.2
	P.fldPhoneRegExp	= "^(\\d{3})" + P.fldPhoneSep + "(\\d{3})" + P.fldPhoneSep + "(\\d{4})$"; // US phone number (123-123-1234) 
	P.fldPhoneRequired	= false; // phone required? // 3.2
	P.fldPhoneCheck = false; // check phone? // 3.2
	P.fldStateCheck = false; // check state? // 3.2
	P.useAddressOverride = true; // 4.0
	P.shippingTaxType = 0; // 3.0
	P.taxType = 2; // 3.0
	P.weightCart = 0; // 3.0 
	P.weightUnit = "kgs"; // 3.0
	P.handlingCart = 0;

	// Menu
	P.RootMenu = null;
	P.RootCat = null;

	// For JavaScript database // 5.0
	//P.PRODUCT_DEFAULT_FILTER = {"ItemSelected":{"!is":[false,0]}}; // Default filter //*** done by DLL

	P.PRODUCT_DEFAULT_FILTER = null; // Default filter
	P.PRODUCT_DEFAULT_SORT = "ItemDisplayOrder, ItemId"; // 501
	P.PRODUCT_ITEMCOUNT_FILTER = {"ItemCount":{"gt":0}}; // Item count filter
	P.keyword = "";
	P.keywords = [];
	P.SEARCH_FIELDS = ["ItemName"];
	P.SEARCH_OPERATOR = "likenocase";

	// Paging // 5.0
	// Note: Always double quote the property names and property values in JSON string.

	P.PAGER_STYLE = 2; // Pager style
	P.PAGE_INDEX_FIELD = ""; // Page index field
	P.PAGE_INDEX_FILTER = "{\"\":{\"left\": \"%s\"}}"; // Page index filter (string)
	P.PAGE_INDEX_OTHER_FILTER = "{\"\":{\"!left\": %s}}"; // Page index filter (string)
	P.displayRecs = 20; // Number of records per page
	P.recPerRow = 4; // Number of records per row (multi column)
	P.totalRecs; // Total number of records (int)
	P.totalPages; // Total number of pages (int)
	P.multiPage; // More than one page (bool)
	P.pageNumber; // Current page number (int)
	P.startRec; // Start record (int)
	P.stopRec; // Stop record (int)
	P.noRec; // No records (bool)
	P.pageIndex = []; // Page index
	P.pageIndexCount = []; // Counts of page index
	P.alpha = ""; // Current page index (char) 

	// Meuu
	P.MENU_DEFAULT_ORDERBY = "MenuLevel, MenuDisplayOrder, MenuId";
	P.CATEGORY_DEFAULT_ORDERBY = "CategoryDisplayOrder";

	// Show simple cart in pages
	P.SHOW_CART_PAGES = ["list", "view", "checkout", "menupage"];
	P.pageID = {}; // Object of Page IDs
	P.dsItem = []; // Dataset (array of objects) for List/View page
	P.oWhere = []; // Filter for List/View page
	P.item = {}; // Current item for View page

	// Colorbox additional configurations
	// Read: http://colorpowered.com/colorbox/

	P.COLORBOX_CONFIG = {};

	// Project settings
	P.DB_TYPE = "JAVASCRIPT";
	P.isJSDB = (P.DB_TYPE.toUpperCase() == "JAVASCRIPT");
	P.AMOUNT_DIV_PREFIX = "pp_amount_";
	P.USE_PAYPAL = true;
	P.CHECK_ITEM_COUNT = false;
	P.UNDEFINED_QUANTITY = true;
	P.DEFINED_QUANTITY = !P.UNDEFINED_QUANTITY;	
	P.PROJECT_CHARSET = "utf-8";
	P.CUSTOM_AS_TEXTAREA = false;
	P.BUSINESS = "admin@zulldesign.ml";
	P.PAYPAL_URL = (P.USE_PAYPAL) ? "https://www.paypal.com/cgi-bin/webscr" : "ipn.php";
	P.CURRENCY_CODE = 'MYR';
	P.DEFAULT_CURRENCY_SYMBOL = 'RM';
	P.DEFAULT_MON_DECIMAL_POINT = '.';
	P.DEFAULT_MON_THOUSANDS_SEP = ',';
	P.DEFAULT_FRAC_DIGITS = 2;	
	P.COMMAND = "cmd";
	P.COMMAND_RESET = "reset";
	P.COMMAND_RESETALL = "resetall";
	P.SEARCH_KEYWORD = "keyword";

	// Search setting, possible options as follow
	// - "" => Exact Match
	// - "OR" => Any words
	// - "AND" => All words

	P.SEARCH_TYPE = "OR";
	P.SEARCH_ALL_CATEGORIES = false;
	P.ROW_CLASS_NAME = "ewTableRow";
	P.ALT_ROW_CLASS_NAME = "ewTableAltRow";
	P.PRODUCT_COUNT_CLASS_NAME = "ewProductCount";
	P.CONTENT_ID = "ewContent";
	P.MENUBAR_MENU_ID = "ewMenu";
	P.MENUBAR_CAT_ID = "ewCategory";
	P.MENUBAR_ROOTMENU_ID = "ewRootMenu";
	P.MENUBAR_ROOTCAT_ID = "ewRootCat";
	P.MENUBAR_VERTICAL = true;
	P.MENUBAR_CLASSNAME = "yuimenu";
	P.MENUBAR_ITEM_CLASSNAME = "yuimenuitem";
	P.MENUBAR_ITEM_LABEL_CLASSNAME = "yuimenuitemlabel";
	P.CART_LIST_PAGE = "cartlist.html";
	P.CART_VIEW_PAGE = "cartview.html";
	P.CHECKOUT_PAGE = "checkout.html";
	P.SHIP_PAGE = "shipping.html";
	P.CONFIRM_PAGE = "confirm.html";
	P.FINISH_PAGE = "finish.html";
	P.MENU_PAGE = "menupage.html";
	P.TEMPLATE_PAGE = "template.html";
	P.QUERY_PAGE = "";
	P.IMAGE_THUMBNAIL_WIDTH = 200;
	P.IMAGE_THUMBNAIL_WIDTH_VIEW = 250;
	P.IMAGE_THUMBNAIL_HEIGHT = 0;
	P.IMAGE_THUMBNAIL_HEIGHT_VIEW = 0;
	P.IMAGE_THUMBNAIL_LIST = "l_";
	P.IMAGE_THUMBNAIL_VIEW = "v_";
	P.IMAGE_FULL_VIEW = "";
	P.USE_ITEM_COUNT = false;
	P.SHOW_SOLD_OUT = true;
	P.LOWERCASE_FILENAME = true;
	P.SWF_DEFAULT_WIDTH = 300;
	P.SWF_DEFAULT_HEIGHT = 150;
	P.SWF_VERSION = "9.0";	
	P.OPTION_REPEAT_COLUMN = 5;
	P.OPTION_SELECT_MULTIPLE_SIZE = 4;
	P.OPTION_TEXTBOX_SIZE = 25;
	P.OPTION_TEXTBOX_MAXLEN = 200;
	P.CATEGORY_ID = "cat";
	P.SESSION_CATEGORY_ID = "pp_categoryid";
	P.SESSION_SEARCH_KEYWORD = "pp_searchkeyword";
	P.IMAGE_PATH = "images";
	P.ITEM_ID = "id";
	P.INCLUDE_SUBCATEGORY = false;
	P.BODY_TITLE = "TEMPLATES DESIGNED BY APPHB";
	P.MENU_ID = "menu";	
	P.START_REC = "start";
	P.PAGE_NO = "page";
	P.SESSION_PAGE_NO = "pp_pagenumber";
	P.PRODUCT_ALPHANUMERIC_INDEX = "";
	P.PRODUCT_ALPHANUMERIC_INDEX += "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
	P.PRODUCT_ALPHANUMERIC_INDEX += "0123456789";
	P.PRODUCT_ALPHANUMERIC_INDEX += "~";
	P.SHOW_ALL_PRODUCTS_CATEGORY = false;
	P.SHOW_CATEGORY_PRODUCT_COUNT = true;
	P.SHOW_EMPTY_CATEGORY = true;
	P.MENU_CLASSNAME = "yuimenu";	
	P.MENU_ITEM_CLASSNAME = "yuimenuitem";
	P.MENU_ITEM_LABEL_CLASSNAME = "yuimenuitemlabel";	
	P.ALPHA_ID = "alpha";
	P.SESSION_ALPHA_ID = "pp_alphaid";
	P.REPLACE_CRLF = true;
	P.DEFAULT_URL = "";

	// DB
	P.DB = TAFFY;
	P.CreateTable = function(ar) {
		return new P.DB(ar);		
	};

	//
	//
	// Shopping cart events
	//
	//
	//
	//
	// Template_Rendering // 5.0
	// argument:
	// e.id - ID of the DIV to render
	// e.data - data (array of object) to be rendered, if any

	P.Template_Rendering = function(e) {

		//alert(e.id);
		// enter your code here

	};

	//
	// Cart_Submitting
	// argument:
	// f - form object

	P.Cart_Submitting = function(f) {

		// enter your code here, return false if you want to abort submission
		return true;
	};	

	//
	// Confirm_Submitting
	// argument:
	// f - form object

	P.Confirm_Submitting = function(f) {

		// enter your code here, return false if you want to abort submission
		return true;
	};
})(PAYPALSHOPMAKER);

// Shortcut
var P = PAYPALSHOPMAKER;
