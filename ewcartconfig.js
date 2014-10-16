//
// Shopping Cart Configuration
//
var EW_USE_PAYPAL     = true; // 3.5
var EW_PAYPAL_URL     = "https://www.paypal.com/cgi-bin/webscr";
var EW_USE_ITEM_COUNT = false; // 3.5
var EW_CHECK_ITEM_COUNT = false; // do not check item count // 3.5
var ew_cartCookie     = "ew_cart_item_";
var ew_cntCookie      = "ew_cart_count";
var ew_shipCookie     = "ew_ship";
var ew_maxCartItems   = 60; // max cart items : < ew_itemsPerCookie*20
var ew_itemsPerCookie = 4; // items per cookie
var ew_cartDelimiter  = "|";
var ew_optionDelim    = "; ";
var ew_optionSep      = ": ";
var ew_multiOptionSep = "|"; // 3.0
var ew_cartExpire     = null; // expires when browser is closed
//var ew_cartExpire     = 'CartExpire(0, 1, 0, 0)'; // expires in 0 day 1 hour 0 minute 0 second
var ew_cartPath       = "/";
var ew_ccySymbol      = "RM";
var ew_ccyDlm1        = ",";
var ew_ccyDlm2        = ".";
//var ew_ccyShowDecimal = true;
var ew_ccyDecimal     = 0;
var ew_ccyExchange    = 1;
var ew_cartAddMsg     = "Item added to your shopping cart.";
var ew_showCartAddMsg = true; // 3.2
var ew_cartFullMsg    = "Your shopping cart is full. Item not added.";
var ew_emptyItemMsg   = "Item name is not valid. Item not added.";
var ew_cartRemoveMsg  = "Are you sure to remove this item from your shopping cart?";
var ew_cartEmptyMsg   = "No items";
var ew_shipEmptyMsg   = "No shipping details"; // 3.0
var ew_TextAreaCntMessage = "characters left"; // 3.0
/* DO NOT CHANGE! (BEGIN) */
var ew_fldID          = "id";
var ew_fldItemNumber  = "item_number"; // 0
var ew_fldItemName    = "item_name"; // 1
var ew_fldAmount      = "amount"; // 2
var ew_fldQuantity    = "quantity"; // 3
var ew_fldShipping    = "shipping"; // 4
var ew_fldShipping2   = "shipping2"; // 5
var ew_fldHandling    = "handling"; // 6
var ew_fldTax         = "tax"; // 7
var ew_fldOn0         = "on0"; // 8
var ew_fldOn0d        = "on0d"; // display
var ew_fldOn1         = "on1"; // 9
var ew_fldOn1d        = "on1d"; // display
var ew_fldOs0         = "os0"; // 10
var ew_fldOs0d        = "os0d"; // display
var ew_fldOs1         = "os1"; // 11
var ew_fldOs1d        = "os1d"; // display
var ew_fldDiscountType = "discounttype"; // 12 (2.0)
var ew_fldShipType    = "shiptype"; // 13 (2.0)
var ew_fldTaxType    = "taxtype"; // 14 (3.0)
var ew_fldOn2         = "on2"; // 15 (3.0)
var ew_fldOn2d        = "on2d"; // display
var ew_fldOn3         = "on3"; // 16 (3.0)
var ew_fldOn3d        = "on3d"; // display
var ew_fldOs2         = "os2"; // 17 (3.0)
var ew_fldOs2d        = "os2d"; // display (non js shopping cart)
var ew_fldOs3         = "os3"; // 18 (3.0)
var ew_fldOs3d        = "os3d"; // display (non js shopping cart)
var ew_fldOr0         = "or0";
var ew_fldOr1         = "or1";
var ew_fldOr2         = "or2"; // 3.0
var ew_fldOr3         = "or3"; // 3.0
var ew_fldWeight      = "weight"; // 19 (3.0)
var ew_fldWeightCart  = "weight_cart"; // 3.0
var ew_fldWeightUnit  = "weight_unit"; // 3.0
var ew_fldAmountBase  = "amount_base";
var ew_fldShipMethod  = "shipmethod"; // shipping method
var ew_fldFirstName   = "first_name"; // first name
var ew_fldLastName    = "last_name"; // last name
var ew_fldAddress1    = "address1"; // address1
var ew_fldAddress2    = "address2"; // address2
var ew_fldCity        = "city"; // city
var ew_fldState       = "state"; // state
var ew_fldZip         = "zip"; // zip
var ew_fldCountry     = "country"; // country
var ew_fldEmail       = "email"; // email
var ew_fldCustom      = "custom"; // custom // 3.0
var ew_fldPhone       = "night_phone"; // phone // 3.2
// non-PayPal fields // 3.5
var ew_fldShipCost    = "shipcost"; // ship cost // 3.5
var ew_fldTaxCost     = "taxcost"; // tax cost // 3.5
var ew_fldHandleCost  = "handlecost"; // handle cost // 3.5
var ew_fldGrandTotal  = "grandtotal"; // grand total // 3.5
/* DO NOT CHANGE! (END) */
var ew_fldQuantitySize = 2; // 3.1
// phone regular expression // 3.2
// Note: Change the regular expression as needed, requires separator (ew_fldPhoneSep)
// if 3 parts, split as night_phone_a, night_phone_b and night_phone_c
// if 2 parts, split as night_phone_a and night_phone_b
// if no separator, submit as night_phone_b
var ew_fldPhoneSep = "-"; // phone separator // 3.2
var ew_fldPhoneRegExp	= "^(\\d{3})" + ew_fldPhoneSep + "(\\d{3})" + ew_fldPhoneSep + "(\\d{4})$"; // US phone number (123-123-1234) 
var ew_fldPhoneRequired	= false; // phone required? // 3.2
var ew_fldPhoneCheck = false; // check phone? // 3.2
var ew_fldStateCheck = false; // check state? // 3.2
var ew_shippingType    = 2;
var ew_shippingTaxType = 0; // 3.0
var ew_taxType         = 2; // 3.0
// Profile Item post type (3.0)
// "0" = always post
// "1" = post if > 0
// "2" = post if >= 0
// "3" = always not post
var ew_profileTaxPostType = "1"; // 3.0
var ew_profileShippingPostType = "1"; // 3.0
var ew_weightCart = 0; // 3.0
var ew_weightUnit = "kgs"; // 3.0
var ew_fldHandlingCart = "handling_cart"; // handling_cart
var ew_HandlingCart = 0;
var ew_fldTaxCart     = "tax_cart"; // tax_cart
var ew_fldRemove      = "<img src='" + EW_ROOT_PATH + "images/delete.gif' alt='Remove' border='0'>";
var ew_descItemNumber = "Item #";
var ew_descItemName   = "Name";
var ew_descOption     = "Options";
var ew_descPrice     = "Price";
var ew_descQuantity   = "Qty";
var ew_descDiscount   = "Discount"; // discount
var ew_descAmount     = "Amount"; // amount
var ew_descSubtotal   = "Sub Total"; // sub total
var ew_descRemove     = "Remove";
var ew_descTotal1     = "Total";
var ew_descTotal2     = "Total (Shipping, handling, and tax may be added upon checkout)";
var ew_descTotal3     = "Total"; // confirm page total description
var ew_descShipping   = "Shipping Cost"; // confirm page shipping cost
var ew_descHandling   = "Handling Cost"; // confirm page handling cost
var ew_descTax        = "Tax"; // confirm page tax cost
var ew_urlCheckout    = "checkout.html";
var ew_urlShipping    = "shipping.html"; // shipping page
var ew_urlConfirm     = "confirm.html"; // confirm page
var ew_qtyExceedMsg   = "Order quantity (%qr) for the product (%i) exceeds quantity in stock (%qa), please reduce the order quantity."; // quantity exceed message // 3.5
var ew_svrErrorMsg    = "Get count for item (%i) error. Server error: %e"; // get item count server error message // 3.5
var ew_svrStatusMsg   = "Get count for item (%i) error. Server status: %s"; // get item count server error message // 3.5
var ew_textCheckout   = "Checkout";
var ew_btnCheckout    = EW_ROOT_PATH + "images/ppcheckout.gif";
var ew_btnClickToPay  = EW_ROOT_PATH + "images/ppclicktopay.gif";
var ew_invalidAmount  = "Total amount must be greater than zero.";
//
var ew_priceCaption = "Price: ";
var ew_divAmountName = "div_amount_";
//
var ew_browserNotSupported = "Browser not supported. Please use newer browser like IE5+, NS6+ or FF1+.";
var ew_qtyMessage = "Quantity is not a valid positive integer.";
var ew_option1Message = "Please input";
var ew_option2Message = "Please input";
var ew_option3Message = "Please input"; // 3.0
var ew_option4Message = "Please input"; // 3.0
var ew_option1PleaseSelect = "Please select"; // 3.0
var ew_option2PleaseSelect = "Please select"; // 3.0
var ew_option3PleaseSelect = "Please select"; // 3.0
var ew_option4PleaseSelect = "Please select"; // 3.0
var ew_option1None = "None"; // 3.0
var ew_option2None = "None"; // 3.0
var ew_option3None = "None"; // 3.0
var ew_option4None = "None"; // 3.0
var ew_RequiredMessage = "Please enter required field:";
var ew_InvalidMessage = "Invalid field:";
var ew_PleaseSelect = "Please Select";
var ew_disableColor = "#ece9d8";
var ew_stateNA = "N.A.";
var ew_useMyPaypalAccount = "Use my PayPal account";
var ew_usePaypalStoredShippingAddress = "Use my address stored with PayPal";
var ew_firstName = "First Name";
var ew_lastName = "Last Name";
var ew_address1 = "Address 1";
var ew_address2 = "Address 2";
var ew_city = "City";
var ew_zip = "Postal Code";
var ew_country = "Country";
var ew_state = "State";
var ew_email = "Email";
var ew_phone = "Phone"; // 3.2
var ew_shippingMethod = "Shipping Method";
var ew_custom = "Others"; // 3.0
var ew_customTextBox = false; // 3.0
// 3.0
var EW_OPTION_SELECT_ONE = 0;
var EW_OPTION_SELECT_MULTIPLE = 1;
var EW_OPTION_RADIO = 2;
var EW_OPTION_CHECKBOX = 3;
var EW_OPTION_TEXT = 4;
//
var EW_OPTION_REPEAT_COLUMN = 5;
var EW_OPTION_SELECT_MULTIPLE_SIZE = 4;
// Region details
// - region id | region name
var ew_regionList  = "1,Asia|2,Africa|4,Central America|5,Europe|6,North America|7,Oceania|9,South America";
// Country details
// - region id , country id , country code | country name
var ew_countryList = "4,1,AI,Anguilla|9,2,AR,Argentina|7,3,AU,Australia|5,4,AT,Austria|5,5,BE,Belgium|9,6,BR,Brazil|6,7,CA,Canada|9,8,CL,Chile|1,9,CN,China|4,10,CR,Costa Rica|1,11,CY,Cyprus|5,12,CZ,Czech Republic|5,13,DK,Denmark|4,14,DO,Dominican Republic|9,15,EC,Ecuador|5,16,EE,Estonia|5,17,FI,Finland|5,18,FR,France|5,19,DE,Germany|5,20,GR,Greece|1,21,HK,Hong Kong|5,22,HU,Hungary|5,23,IS,Iceland|1,24,IN,India|5,25,IE,Ireland|1,26,IL,Israel|5,27,IT,Italy|4,28,JM,Jamaica|1,29,JP,Japan|5,30,LV,Latvia|5,31,LT,Lithuania|5,32,LU,Luxembourg|1,33,MY,Malaysia|5,34,MT,Malta|6,35,MX,Mexico|5,36,NL,Netherlands|7,37,NZ,New Zealand|5,38,NO,Norway|5,39,PL,Poland|5,40,PT,Portugal|1,41,SG,Singapore|5,42,SK,Slovakia|5,43,SI,Slovenia|2,44,ZA,South Africa|1,45,KR,South Korea|5,46,ES,Spain|5,47,SE,Sweden|5,48,CH,Switzerland|1,49,TW,Taiwan|1,50,TH,Thailand|1,51,TR,Turkey|5,52,GB,United Kingdom|6,53,US,United States|9,54,UY,Uruguay|9,55,VE,Venezuela";
// State details
// - country id , state id , state code | state name
var ew_stateList = "53,2,AK,Alaska|53,1,AL,Alabama|53,4,AR,Arkansas|53,3,AZ,Arizona|53,5,CA,California|53,6,CO,Colorado|53,7,CT,Connecticut|53,9,DC,District of Columbia|53,8,DE,Delaware|53,10,FL,Florida|53,11,GA,Georgia|53,12,HI,Hawaii|53,16,IA,Iowa|53,13,ID,Idaho|53,14,IL,Illinois|53,15,IN,Indiana|53,17,KS,Kansas|53,18,KY,Kentucky|53,19,LA,Louisiana|53,22,MA,Massachusetts|53,21,MD,Maryland|53,20,ME,Maine|53,23,MI,Michigan|53,24,MN,Minnesota|53,26,MO,Missouri|53,25,MS,Mississippi|53,27,MT,Montana|53,34,NC,North Carolina|53,35,ND,North Dakota|53,28,NE,Nebraska|53,30,NH,New Hampshire|53,31,NJ,New Jersey|53,32,NM,New Mexico|53,29,NV,Nevada|53,33,NY,New York|53,36,OH,Ohio|53,37,OK,Oklahoma|53,38,OR,Oregon|53,39,PA,Pennsylvania|53,40,RI,Rhode Island|53,41,SC,South Carolina|53,42,SD,South Dakota|53,43,TN,Tennessee|53,44,TX,Texas|53,45,UT,Utah|53,47,VA,Virginia|53,46,VT,Vermont|53,48,WA,Washington|53,50,WI,Wisconsin|53,49,WV,West Virginia|53,51,WY,Wyoming";
//
// Discount details
// - discount type, discount quantity, discount rate (in percent)
//
var ew_discountList = "1,5,5|1,10,10|2,3,10|2,6,20";
//
// Shipping cost details
// - if shipping type = 0, use qty range calculation (qty)
// - type, method, region, country, state, qty, basecost, extracost
// - if shipping type = 1, use price range calculation (price)
// - type, method, region, country, state, price, basecost, extracost
// - if shipping type = 2, use weight range calcuation (weight)
// - type, method, region, country, state, weight, basecost, extracost
//
var ew_shipMethodList = "1,SEA|2,AIR";
var ew_shipcostList0 = "1,1,1,-1,-1,5,10,2|1,1,1,-1,-1,10,20,1|1,1,5,-1,-1,5,15,1.5|1,1,5,-1,-1,10,30,1|1,1,6,-1,-1,5,20,4|1,1,6,-1,-1,10,40,2|1,2,1,-1,-1,5,15,3|1,2,1,-1,-1,10,30,2|1,2,5,-1,-1,5,20,2.5|1,2,5,-1,-1,10,40,2|1,2,6,-1,-1,5,30,5|1,2,6,-1,-1,10,60,2|2,1,1,-1,-1,5,20,3|2,1,1,-1,-1,10,40,2|2,1,5,-1,-1,5,30,2.5|2,1,5,-1,-1,10,60,2|2,1,6,-1,-1,5,40,5|2,1,6,-1,-1,10,80,3|2,2,1,-1,-1,5,30,4|2,2,1,-1,-1,10,60,3|2,2,5,-1,-1,5,50,3.5|2,2,5,-1,-1,10,100,3|2,2,6,-1,-1,5,60,6|2,2,6,-1,-1,10,120,4";
var ew_shipcostList1 = "2,1,-1,-1,-1,1,0,0|2,1,-1,-1,-1,2,0,0|2,1,-1,-1,-1,3,0,0|2,1,-1,-1,-1,4,0,0|2,1,-1,-1,-1,5,0,0";
var ew_shipcostList2 = "";
var ew_shipTypeList = "1,0|2,0";
//
// Tax details
// - type, region, country, state, tax rate (in percent)
//
var ew_taxList = "1,-1,-1,-1,0|1,1,-1,-1,5|1,5,-1,-1,15|1,6,-1,-1,10";
//
// Menu
//
var EW_MENUBAR_VERTICAL_CLASSNAME = "MenuBarVertical";
var EW_MENUBAR_SUBMENU_CLASSNAME = "MenuBarItemSubmenu";
var EW_MENUBAR_RIGHTHOVER_IMAGE = "Spry_1_6_1_022408/SpryMenuBarRightHover.gif";
