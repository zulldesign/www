//
// Shopping Cart Configuration
//
var ntk_paypal_url     = "https://www.sandbox.paypal.com/cgi-bin/webscr";
var ntk_cartCookie     = "ntk_cart_item_";
var ntk_cntCookie      = "ntk_cart_count";
var ntk_shipCookie     = "ntk_ship"; // ***
//var ntk_maxCartItems   = 0;
var ntk_maxCartItems   = 60; // *** max cart items : < ntk_itemsPerCookie*20
var ntk_itemsPerCookie = 4; // *** items per cookie
var ntk_cartDelimiter  = "|";
var ntk_optionDelim    = "; ";
var ntk_optionSep      = ": ";
var ntk_cartExpire     = null; // expires when browser is closed
//var ntk_cartExpire     = 'CartExpire(0, 1, 0, 0)'; // expires in 0 day 1 hour 0 minute 0 second
var ntk_cartPath       = "/";
var ntk_ccySymbol      = "RM";
var ntk_ccyDlm1        = ",";
var ntk_ccyDlm2        = ".";
//var ntk_ccyShowDecimal = true;
var ntk_ccyDecimal     = 2;
var ntk_ccyExchange    = 1;
var ntk_cartAddMsg     = "Item added to your shopping cart.";
var ntk_cartFullMsg    = "Your shopping cart is full. Item not added.";
var ntk_emptyItemMsg = "Item name is not valid. Item not added.";
var ntk_cartRemoveMsg  = "Are you sure to remove this item from your shopping cart?";
var ntk_cartEmptyMsg   = "No items";
var ntk_shipEmptyMsg  = "No shipping details"; // ***
var ntk_fldID          = "id";
var ntk_fldItemNumber  = "item_number";
var ntk_fldItemName    = "item_name";
var ntk_fldShipping    = "shipping";
var ntk_fldShipping2   = "shipping2";
var ntk_fldHandling    = "handling";
var ntk_fldTax         = "tax";
var ntk_fldOs0         = "os0";
var ntk_fldOs1         = "os1";
var ntk_fldOn0         = "on0";
var ntk_fldOn1         = "on1";
var ntk_fldOr0         = "or0";
var ntk_fldOr1         = "or1";
var ntk_fldAmountBase  = "amount_base";
var ntk_fldAmount      = "amount";
var ntk_fldQuantity    = "quantity";
var ntk_fldDiscountType    = "discounttype"; //*** discount type
var ntk_fldShipType    = "shiptype"; //*** shipping type
var ntk_fldShipMethod    = "shipmethod"; //*** shipping method
var ntk_fldFirstName   = "first_name"; //*** first name
var ntk_fldLastName    = "last_name"; //*** last name
var ntk_fldAddress1    = "address1"; //*** address1
var ntk_fldAddress2    = "address2"; //*** address2
var ntk_fldCity        = "city"; //*** city
var ntk_fldState       = "state"; //*** state
var ntk_fldZip         = "zip"; //*** zip
var ntk_fldCountry     = "country"; //*** country
var ntk_fldEmail       = "email"; //*** email
var ntk_ShippingType = 0; //***
var ntk_TaxType      = 0; //***
var ntk_fldHandlingCart = "handling_cart"; //*** handling_cart
var ntk_fldTaxCart     = "tax_cart"; //*** tax_cart
var ntk_fldRemove      = "<img src='images/delete.gif' alt='Remove' border='0'>";
var ntk_descItemNumber = "Item #";
var ntk_descItemName   = "Name";
var ntk_descOption     = "Options";
var ntk_descPrice     = "Price"; // ***
var ntk_descQuantity   = "Qty";
var ntk_descDiscount   = "Discount"; //*** discount
var ntk_descAmount     = "Amount"; //** amount
var ntk_descSubtotal   = "Sub Total"; //*** sub total
var ntk_descRemove     = "Remove";
var ntk_descTotal1     = "<b>Total</b>";
var ntk_descTotal2     = "<b>Total (Shipping, handling, and tax may be added upon checkout)</b>";
var ntk_descTotal3     = "<b>Total</b>"; // *** confirm page total description
var ntk_descShipping   = "Shipping Cost"; // *** confirm page shipping cost
var ntk_descHandling   = "Handling Cost"; // *** confirm page handling cost
var ntk_descTax        = "Tax"; // *** confirm page tax cost
var ntk_urlCheckout    = "checkout.html";
var ntk_urlShipping    = "shipping.html"; // *** shipping page
var ntk_urlConfirm     = "confirm.html"; // *** confirm page
var ntk_textCheckout   = "Checkout";
var ntk_btnCheckout    = "images/ppcheckout.gif";
var ntk_btnClickToPay  = "images/ppclicktopay.gif";
var ntk_invalidAmount  = "Total amount must be greater than zero.";
//
var ntk_priceCaption = "Price: ";
var ntk_divAmountName = "div_amount_";
//
var ntk_browserNotSupported = "Browser not supported. Please use newer browser like IE5+, NS6+ or FF1+.";
var ntk_Option1Message = "Please select";
var ntk_Option2Message = "Please select";
var ntk_RequiredMessage = "Please enter required field:"; // ***
var ntk_InvalidMessage = "Invalid field:"; // ***
var ntk_disableColor = "#CCCCCC"; // ***
var ntk_stateNA = "N.A."; // ***
var ntk_useMyPaypalAccount = "Use my Paypal account";
var ntk_usePaypalStoredShippingAddress = "Use PayPal-stored shipping addresses";
var ntk_firstName = "First Name";
var ntk_lastName = "Last Name";
var ntk_address1 = "Address 1";
var ntk_address2 = "Address 2";
var ntk_city = "City";
var ntk_zip = "Postal Code";
var ntk_country = "Country";
var ntk_state = "State";
var ntk_email = "Email";
var ntk_shippingMethod = "<b>Shipping Method:</b> ";
// Region details ***
// - region id | region name
var ntk_regionList  = "1|Asia|2|Africa|3|Central America|4|Europe|5|North America|6|Oceania|7|South America";
// Country details ***
// - region id , country id , country code | country name
var ntk_countryList = "3,1,AI|Anguilla|7,2,AR|Argentina|6,3,AU|Australia|4,4,AT|Austria|4,5,BE|Belgium|7,6,BR|Brazil|5,7,CA|Canada|7,8,CL|Chile|1,9,CN|China|3,10,CR|Costa Rica|1,11,CY|Cyprus|4,12,CZ|Czech Republic|4,13,DK|Denmark|3,14,DO|Dominican Republic|7,15,EC|Ecuador|4,16,EE|Estonia|4,17,FI|Finland|4,18,FR|France|4,19,DE|Germany|4,20,GR|Greece|1,21,HK|Hong Kong|4,22,HU|Hungary|4,23,IS|Iceland|1,24,IN|India|4,25,IE|Ireland|1,26,IL|Israel|4,27,IT|Italy|3,28,JM|Jamaica|1,29,JP|Japan|4,30,LV|Latvia|4,31,LT|Lithuania|4,32,LU|Luxembourg|1,33,MY|Malaysia|4,34,MT|Malta|5,35,MX|Mexico|4,36,NL|Netherlands|6,37,NZ|New Zealand|4,38,NO|Norway|4,39,PL|Poland|4,40,PT|Portugal|1,41,SG|Singapore|4,42,SK|Slovakia|4,43,SI|Slovenia|2,44,ZA|South Africa|1,45,KR|South Korea|4,46,ES|Spain|4,47,SE|Sweden|4,48,CH|Switzerland|1,49,TW|Taiwan|1,50,TH|Thailand|1,51,TR|Turkey|4,52,GB|United Kingdom|5,53,US|United States|7,54,UY|Uruguay|7,55,VE|Venezuela";
// State details ***
// - country id , state id , state code | state name
var ntk_stateList = "53,2,AK|Alaska|53,1,AL|Alabama|53,4,AR|Arkansas|53,3,AZ|Arizona|53,5,CA|California|53,6,CO|Colorado|53,7,CT|Connecticut|53,9,DC|District of Columbia|53,8,DE|Delaware|53,10,FL|Florida|53,11,GA|Georgia|53,12,HI|Hawaii|53,16,IA|Iowa|53,13,ID|Idaho|53,14,IL|Illinois|53,15,IN|Indiana|53,17,KS|Kansas|53,18,KY|Kentucky|53,19,LA|Louisiana|53,22,MA|Massachusetts|53,21,MD|Maryland|53,20,ME|Maine|53,23,MI|Michigan|53,24,MN|Minnesota|53,26,MO|Missouri|53,25,MS|Mississippi|53,27,MT|Montana|53,34,NC|North Carolina|53,35,ND|North Dakota|53,28,NE|Nebraska|53,30,NH|New Hampshire|53,31,NJ|New Jersey|53,32,NM|New Mexico|53,29,NV|Nevada|53,33,NY|New York|53,36,OH|Ohio|53,37,OK|Oklahoma|53,38,OR|Oregon|53,39,PA|Pennsylvania|53,40,RI|Rhode Island|53,41,SC|South Carolina|53,42,SD|South Dakota|53,43,TN|Tennessee|53,44,TX|Texas|53,45,UT|Utah|53,47,VA|Virginia|53,46,VT|Vermont|53,48,WA|Washington|53,50,WI|Wisconsin|53,49,WV|West Virginia|53,51,WY|Wyoming";
//
// Discount details ***
// - discount type, discount quantity, discount rate (in percent)
//
var ntk_discountList = "0,5,5|0,10,10|0,20,20";
//
// Shipping cost details ***
// - type, method, region, country, state, qty, basecost, extracost, price
// - if shipping type = 0, use qty range calculation (qty)
// - if shipping type = 1, use price range calculation (price)
//
//var ntk_shipcalcType = 0;
var ntk_shipMethodList = "0,By Air|1,By Sea";
var ntk_shipcostList = "1,0,-1,-1,-1,3,0,1.5,-1|1,0,-1,-1,-1,6,0,1,-1|1,0,-1,-1,-1,9,0,0.8,-1|1,1,-1,-1,-1,3,0,1,-1|1,1,-1,-1,-1,6,0,0.8,-1|1,1,-1,-1,-1,9,0,0.6,-1|3,0,-1,-1,-1,3,0,1.2,-1|3,0,-1,-1,-1,6,0,1,-1|3,0,-1,-1,-1,9,0,0.8,-1|3,1,-1,-1,-1,3,0,0.6,-1|3,1,-1,-1,-1,6,0,0.5,-1|3,1,-1,-1,-1,9,0,0.4,-1";
var ntk_shipTypeList = "1,0|3,0";
//
// Tax details ***
// - region, country, state, tax rate (in percent)
//
var ntk_taxList = "-1,-1,-1,7.5";
