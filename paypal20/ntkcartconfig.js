<!--##SESSION ntkcartconfig##-->
//
// Shopping Cart Configuration
//
<!--##$COND #Product/TblppSandbox/EQ/True##-->
var ntk_paypal_url     = "https://www.sandbox.paypal.com/cgi-bin/webscr";
<!--##$/COND##-->
<!--##$COND #Product/TblppSandbox/EQ/False##-->
var ntk_paypal_url     = "https://www.paypal.com/cgi-bin/webscr";
<!--##$/COND##-->
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
var ntk_ccySymbol      = "<!--##=DisplayCurrencyCode##-->";
var ntk_ccyDlm1        = "<!--##=CurrencyGroupSeparator##-->";
var ntk_ccyDlm2        = "<!--##=CurrencyDecimalSeparator##-->";
//var ntk_ccyShowDecimal = true;
var ntk_ccyDecimal     = <!--##=CurrencyNumDigits##-->;
var ntk_ccyExchange    = <!--##=TblppCcyExchangeRate##-->;
var ntk_cartAddMsg     = "<!--##@\CartAddMessage##-->";
var ntk_cartFullMsg    = "<!--##@\CartFullMessage##-->";
var ntk_emptyItemMsg = "<!--##@\EmptyItemMessage##-->";
var ntk_cartRemoveMsg  = "<!--##@\CartRemoveMessage##-->";
var ntk_cartEmptyMsg   = "<!--##@\CartEmptyMessage##-->";
var ntk_shipEmptyMsg  = "<!--##@\ShipEmptyMessage##-->"; // ***
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
var ntk_ShippingType = <!--##=ShippingType##-->; //***
var ntk_TaxType      = <!--##=TaxType##-->; //***
var ntk_fldHandlingCart = "handling_cart"; //*** handling_cart
var ntk_fldTaxCart     = "tax_cart"; //*** tax_cart
var ntk_fldRemove      = "<img src='images/delete.gif' alt='Remove' border='0'>";
var ntk_descItemNumber = "<!--##@\DescItemNumber##-->";
var ntk_descItemName   = "<!--##@\DescItemName##-->";
var ntk_descOption     = "<!--##@\DescOption##-->";
var ntk_descPrice     = "<!--##@\DescPrice##-->"; // ***
var ntk_descQuantity   = "<!--##@\DescQuantity##-->";
var ntk_descDiscount   = "<!--##@\DescDiscount##-->"; //*** discount
var ntk_descAmount     = "<!--##@\DescAmount##-->"; //** amount
var ntk_descSubtotal   = "<!--##@\DescSubtotal##-->"; //*** sub total
var ntk_descRemove     = "<!--##@\DescRemove##-->";
var ntk_descTotal1     = "<!--##@\DescTotal1##-->";
var ntk_descTotal2     = "<!--##@\DescTotal2##-->";
var ntk_descTotal3     = "<!--##@\DescTotal3##-->"; // *** confirm page total description
var ntk_descShipping   = "<!--##@\DescShipping##-->"; // *** confirm page shipping cost
var ntk_descHandling   = "<!--##@\DescHandling##-->"; // *** confirm page handling cost
var ntk_descTax        = "<!--##@\DescTax##-->"; // *** confirm page tax cost
var ntk_urlCheckout    = "<!--##=CheckoutFn##-->";
var ntk_urlShipping    = "<!--##=ShippingFn##-->"; // *** shipping page
var ntk_urlConfirm     = "<!--##=ConfirmFn##-->"; // *** confirm page
var ntk_textCheckout   = "<!--##@\Checkout##-->";
var ntk_btnCheckout    = "images/ppcheckout.gif";
var ntk_btnClickToPay  = "images/ppclicktopay.gif";
var ntk_invalidAmount  = "<!--##@\InvalidAmount##-->";
//
var ntk_priceCaption = "<!--##@\Price##-->: ";
var ntk_divAmountName = "div_amount_";
//
var ntk_browserNotSupported = "<!--##@\BrowserNotSupported##-->";
var ntk_Option1Message = "<!--##@\Option1Message##-->";
var ntk_Option2Message = "<!--##@\Option2Message##-->";
var ntk_RequiredMessage = "<!--##@\RequiredMessage##-->"; // ***
var ntk_InvalidMessage = "<!--##@\InvalidMessage##-->"; // ***
var ntk_disableColor = "#CCCCCC"; // ***
var ntk_stateNA = "<!--##@\StateNA##-->"; // ***

var ntk_useMyPaypalAccount = "<!--##@\UseMyPaypalAccount##-->";
var ntk_usePaypalStoredShippingAddress = "<!--##@\UsePaypalStoredShippingAddress##-->";
var ntk_firstName = "<!--##@\FirstName##-->";
var ntk_lastName = "<!--##@\LastName##-->";
var ntk_address1 = "<!--##@\Address1##-->";
var ntk_address2 = "<!--##@\Address2##-->";
var ntk_city = "<!--##@\City##-->";
var ntk_zip = "<!--##@\Zip##-->";
var ntk_country = "<!--##@\Country##-->";
var ntk_state = "<!--##@\State##-->";
var ntk_email = "<!--##@\Email##-->";
var ntk_shippingMethod = "<!--##@\ShippingMethod##-->";

// Region details ***
// - region id | region name
var ntk_regionList  = "<!--%=CustomQueries.RegionList-->";
// Country details ***
// - region id , country id , country code | country name
var ntk_countryList = "<!--%=CustomQueries.CountryList-->";
// State details ***
// - country id , state id , state code | state name
var ntk_stateList = "<!--%=CustomQueries.StateList-->";
//
// Discount details ***
// - discount type, discount quantity, discount rate (in percent)
//
var ntk_discountList = "<!--%=CustomQueries.DiscountList-->";
//
// Shipping cost details ***
// - type, method, region, country, state, qty, basecost, extracost, price
// - if shipping type = 0, use qty range calculation (qty)
// - if shipping type = 1, use price range calculation (price)
//
//var ntk_shipcalcType = <!--##=PROJ.ShippingCalcType##-->;
var ntk_shipMethodList = "<!--%=CustomQueries.ShipMethodList-->";
var ntk_shipcostList = "<!--%=CustomQueries.ShipcostList-->";
var ntk_shipTypeList = "<!--%=CustomQueries.ShipTypeList-->";
//
// Tax details ***
// - region, country, state, tax rate (in percent)
//
var ntk_taxList = "<!--%=CustomQueries.TaxList-->";
<!--##/SESSION##-->