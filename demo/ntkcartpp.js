//
// JavaScript Shopping Cart for PayPal Shop Builder
// (C) 2005-2007 NTK Software
//

// Shopping Cart variables
var nItems         = 0;
var nFlds          = 14; // number of fields
var total          = 0;
var shipcost       = 0; // shipping cost
var handlecost     = 0; // handling cost
var taxcost        = 0; // tax cost
var nCookies       = 0; // number of cookies
var arCookies      = new Array(); // shopping cart
var arShipping     = new Array(); // shipping details

// Load shopping cart
if (NTK_CartAction == 0) {
	LoadCart();
}
// Clear shopping cart
else if (NTK_CartAction == 1) {
	ClearCart();
	LoadCart();
}

//
// Load shopping cart to array
//
function LoadCart() {
	var cookieStr, cookieValue;
	nItems = ReadCookie(ntk_cntCookie);
	if (nItems == "" || nItems == null) nItems = 0;
	nCookies = (nItems > 0) ? (parseInt((nItems-1)/ntk_itemsPerCookie)+1) : 0;
	// Load all cart items
	for (i=0; i<nCookies; i++) {
		cookieStr = ntk_cartCookie + (i+1);
		cookieValue = ReadCookie(cookieStr);
		if (cookieValue == "" || cookieValue == null) {
			nCookies = i; break;
		}
		arCookies[i] = new Array();
		arCookies[i] = cookieValue.split(ntk_cartDelimiter);
	}
	// Load shipping details
	cookieStr = ntk_shipCookie;
	cookieValue = ReadCookie(cookieStr);
	if (cookieValue == "" || cookieValue == null) cookieValue = "|||||||||||";
	arShipping = cookieValue.split(ntk_cartDelimiter);
}

//
// clear shopping cart
//
function ClearCart() {
	WriteCookie(ntk_cntCookie, 0, eval(ntk_cartExpire), ntk_cartPath);
}

//
// Add item to Cart
//
function AddItemToCart(f) {

	var on0, on1, os0, os1;
	var itemnumber, itemname, amt, qty;
	var shipping, shipping2, handling, tax;
	var discounttype; // discount type
	var shiptype; // shipping type
	var p;

	if (nItems >= ntk_maxCartItems && ntk_maxCartItems > 0) {

		alert(ntk_cartFullMsg);

	} else {

		on0 = (f.elements[ntk_fldOn0]) ? f.elements[ntk_fldOn0].value : ""; 
		os0 = (f.elements[ntk_fldOs0]) ? OptionValue(f.elements[ntk_fldOs0]) : ""; 
		// remove option 1 price
		p = os0.indexOf("=");
		if (p >= 0) os0 = os0.substring(0, p);
		on1 = (f.elements[ntk_fldOn1]) ? f.elements[ntk_fldOn1].value : ""; 
		os1 = (f.elements[ntk_fldOs1]) ? OptionValue(f.elements[ntk_fldOs1]) : ""; 
		// remove option 2 price
		p = os1.indexOf("=");
		if (p >= 0) os1 = os1.substring(0, p);
		// move option 2 to option 1 if option 1 is not specified
		if (os0 == "" && os1 != "") {
			on0 = on1; os0 = os1;
			on1 = ""; os1 = "";
		}
		itemnumber = (f.elements[ntk_fldItemNumber])?f.elements[ntk_fldItemNumber].value:"";
		itemname = (f.elements[ntk_fldItemName])?f.elements[ntk_fldItemName].value:"";
		amt = (f.elements[ntk_fldAmount])?f.elements[ntk_fldAmount].value:0;
		qty = (f.elements[ntk_fldQuantity])?f.elements[ntk_fldQuantity].value:1;
		shipping = (f.elements[ntk_fldShipping]) ? f.elements[ntk_fldShipping].value : 0;
		shipping2 = (f.elements[ntk_fldShipping2]) ? f.elements[ntk_fldShipping2].value : 0;
		handling = (f.elements[ntk_fldHandling]) ? f.elements[ntk_fldHandling].value : 0;
		tax = (f.elements[ntk_fldTax]) ? f.elements[ntk_fldTax].value : 0;
		discounttype = (f.elements[ntk_fldDiscountType]) ? f.elements[ntk_fldDiscountType].value : ""; // discount type
		shiptype = (f.elements[ntk_fldShipType]) ? f.elements[ntk_fldShipType].value : ""; // ship type
		
		if (itemname != "") {
			ProcessCartItem(itemnumber, itemname, amt, qty,
				shipping, shipping2, handling, tax,
				on0, on1, os0, os1,
				discounttype, shiptype); // add discount type, ship type
			alert(ntk_cartAddMsg);
			ReloadCurrentPage();
		} else {
			alert(ntk_emptyItemMsg);
		}

	}
}

//
// Process this Shopping Cart Item
//
function ProcessCartItem(num, name, amt, qty,
			shipping, shipping2, handling, tax,
			on0, on1, os0, os1, discounttype, shiptype) {

	for (var i = 1; i <= nItems; i++) {
		if (UpdateCartItem(i, num, name, amt, qty, shipping, shipping2, handling, tax, on0, on1, os0, os1, discounttype, shiptype)) return true;
	}

	nItems++;
	WriteCartItem(nItems, num, name, amt, qty, shipping, shipping2, handling, tax, on0, on1, os0, os1, discounttype, shiptype);
	WriteCookie(ntk_cntCookie, nItems, eval(ntk_cartExpire), ntk_cartPath);
	return true;

}

//
// Update quantity to this Shopping Cart Item
//
function UpdateQuantity(item, qty) {

	var itemStr = "";
	itemStr = ReadItemFromCart(item);
	if (itemStr.length > 0) {
		var fldArrays = itemStr.split(ntk_cartDelimiter);
		if (fldArrays.length == nFlds) {
			fldArrays[3] = IntValue(qty);
			if (fldArrays[3] == 0) {
				RemoveItemFromCart(item);
			} else {
				WriteCartItem(item, fldArrays[0], fldArrays[1], fldArrays[2], fldArrays[3],
					fldArrays[4], fldArrays[5], fldArrays[6], fldArrays[7],
					fldArrays[8], fldArrays[9], fldArrays[10], fldArrays[11],
					fldArrays[12], fldArrays[13]);
			}
			ReloadCurrentPage();
			return true;
		}
	}
	return false;
}

//
// Update this Shopping Cart Item
//
function UpdateCartItem(item, num, name, amt, qty,
			shipping, shipping2, handling, tax,
			on0, on1, os0, os1, discounttype, shiptype) {

	var itemStr = "";
	itemStr = ReadItemFromCart(item);
	if (itemStr.length > 0) {
		var fldArrays = itemStr.split(ntk_cartDelimiter);
		if (fldArrays.length == nFlds) {
			if ((fldArrays[0] == num) && (fldArrays[1] == name) &&
				(fldArrays[8] == on0) && (fldArrays[9] == on1) &&
				(fldArrays[10] == os0) && (fldArrays[11] == os1)) {
				fldArrays[2] = amt;
				fldArrays[3] = parseInt(fldArrays[3]) + parseInt(qty);
				WriteCartItem(item, num, name, fldArrays[2], fldArrays[3], shipping, shipping2, handling, tax, on0, on1, os0, os1, discounttype, shiptype);
				return true;
			}
		}
	}
	return false;
}

//
// Write this Shopping Cart Item
//
function WriteCartItem(item, num, name, amt, qty,
			shipping, shipping2, handling, tax,
			on0, on1, os0, os1, discounttype, shiptype) {

	var itemStr  = num  + ntk_cartDelimiter;
	itemStr += name + ntk_cartDelimiter;
	itemStr += amt  + ntk_cartDelimiter;
	itemStr += qty  + ntk_cartDelimiter;
	itemStr += shipping  + ntk_cartDelimiter;
	itemStr += shipping2  + ntk_cartDelimiter;
	itemStr += handling  + ntk_cartDelimiter;
	itemStr += tax  + ntk_cartDelimiter;
	itemStr += on0  + ntk_cartDelimiter;
	itemStr += on1  + ntk_cartDelimiter;
	itemStr += os0  + ntk_cartDelimiter;
	itemStr += os1  + ntk_cartDelimiter;
	itemStr += discounttype + ntk_cartDelimiter; // discount type
	itemStr += shiptype; // ship type
	WriteItemToCart(item, itemStr);
	return true;

}

//
// Remove item from Cart
//
function RemoveItemFromCart(item) {

	if (confirm(ntk_cartRemoveMsg)) {
		for (var i = item; i <  nItems; i++) {
			var itemStr = ReadItemFromCart(i+1);
			WriteItemToCart(i, itemStr);
		}
		WriteCookie(ntk_cntCookie, nItems-1, eval(ntk_cartExpire), ntk_cartPath);
		var cookieStr = ntk_cartCookie + nItems;
		DeleteCookie(cookieStr, "/");
		ReloadCurrentPage();
	}
}

//
// Reload page
//
function ReloadCurrentPage() {
	var cookieStr, cookieValue;
	// Write all cookies
	for (var i=0; i<nCookies; i++) {
		cookieValue = arCookies[i].join(ntk_cartDelimiter);
		cookieStr = ntk_cartCookie + (i+1);
		WriteCookie(cookieStr, cookieValue, eval(ntk_cartExpire), ntk_cartPath);
	}
	//WriteCookie(ntk_cntCookie, nItems, eval(ntk_cartExpire), ntk_cartPath);

	// Reload page
	location.reload();
}
// Go to Shipping
function GoToShipping() {
GoToPage(ntk_urlShipping);
}
// Go to Confirm
function GoToConfirm() {
GoToPage(ntk_urlConfirm);
}
// Go to page
function GoToPage(url) {
window.location=url;
}

//
// Read Cart from Cookie
//
function ReadItemFromCart(item) {
	var cookieIdx, cookieValue, cookieOffset;
	cookieIdx = parseInt((item-1)/ntk_itemsPerCookie)+1;
	var itemStr = "";
	cookieOffset = (item-1) % ntk_itemsPerCookie;
	if (nCookies >= cookieIdx) {
		for (i=cookieOffset*nFlds; i<(cookieOffset+1)*nFlds-1; i++) {
			itemStr += arCookies[cookieIdx-1][i];
			itemStr += ntk_cartDelimiter;
		}
		itemStr += arCookies[cookieIdx-1][(cookieOffset+1)*nFlds-1];
	}
	return itemStr;
}

//
// Read Cookie value
//
function ReadCookie(name) {

	var arg = name + "=";
	var alen = arg.length;
	var clen = document.cookie.length;
	var i = 0;
	while (i < clen) {
		var j = i + alen;
		if (document.cookie.substring(i, j) == arg) return ReadCookieVal(j);
		i = document.cookie.indexOf(" ", i) + 1;
		if (i == 0) break;
	}
	return null;

}

//
// Read Cookie value from offset
//
function ReadCookieVal(offset) {

	var endStr = document.cookie.indexOf(";", offset);
	if (endStr == -1) endStr = document.cookie.length;
	return unescape(document.cookie.substring(offset, endStr));

}

//
// Write item to shopping cart
//
function WriteItemToCart(item, str) {
	var cookieIdx, cookieValue, cookieOffset;
	var fldArrays = new Array();
	fldArrays = str.split(ntk_cartDelimiter);
	if (fldArrays.length == nFlds) {
		cookieIdx = parseInt((item-1)/ntk_itemsPerCookie)+1;
		if (nCookies < cookieIdx) {
			nCookies += 1;
			cookieIdx = nCookies;
			arCookies[cookieIdx-1] = new Array();
			cookieValue = str;
		} else {
			cookieOffset = (item-1) % ntk_itemsPerCookie;
			if ((cookieOffset+1)*nFlds <= arCookies[cookieIdx-1].length) {
				for (i=0; i<nFlds; i++) arCookies[cookieIdx-1][cookieOffset*nFlds+i] = fldArrays[i];
			}
			cookieValue = arCookies[cookieIdx-1].join(ntk_cartDelimiter);
			cookieValue += ntk_cartDelimiter;
			cookieValue += str;
		}
		arCookies[cookieIdx-1] = cookieValue.split(ntk_cartDelimiter);
	}
}

//
// Write Cookie value
//
function WriteCookie(name, value, expires, path) {
//alert(name + " , " + value + " , " + expires + " , " + path);
	document.cookie = name + "=" + escape(value) +
		((expires) ? "; expires=" + expires.toGMTString() : "") +
		((path) ? "; path=" + path : "");
}

//
// Delete a cookie
//
function DeleteCookie(name, path, domain) {
	if (ReadCookie(name)) {
		document.cookie = name + "=" +
			((path) ? "; path=" + path : "") +
			((domain) ? "; domain=" + domain : "") +
			"; expires=Thu, 01-Jan-70 00:00:01 GMT";
	}
}

//
// Get Cart Expire Time
function CartExpire(day, hour, min, sec) {
	var expireTime = new Date();
	var timeWrk = expireTime.getTime();
	timeWrk += (((((day * 24) + hour) * 60 + min) * 60) + sec) * 1000;
	expireTime.setTime(timeWrk);
	return expireTime;
}


//
// Return integer value
//
function IntValue(obj) {
	if (obj.length == 0) return 1;

	var int_format = "0123456789";
	var check_char;

	for (var i = 0; i < obj.length; i++) {
		check_char = int_format.indexOf(obj.charAt(i));
		if (check_char < 0) return 1;
	}

	return parseInt(obj);
}


//
// Format Currency
//
function FormatCurrency(amt) {
	var sign = "";
	var cents = "";
	var centswrk = "";
	var outstr = "";
	var base = 1;

	for (var i=1; i<=ntk_ccyDecimal; i++) {
		base = base * 10;
	}
//	base = 100;
	amt = amt.toString().replace(/\$|\,/g,'');
	if (isNaN(amt)) amt = "0";
	sign = (amt == (amt = Math.abs(amt)));
	amt = Math.floor(amt * base + 0.50000000001);
	cents = amt % base;
	centswrk = cents + "";
	amt = Math.floor(amt/base).toString();
	for (var i=centswrk.length; i<ntk_ccyDecimal; i++) {
		centswrk = "0" + centswrk;
	}
//	if (cents < 10) cents = "0" + cents;
	for (var i = 0; i < Math.floor((amt.length - (1+i))/3); i++) {
		amt = amt.substring(0, amt.length - (4*i+3)) + ntk_ccyDlm1 + amt.substring(amt.length - (4*i+3));
	}

	outstr = ((sign)?'':'-') + ntk_ccySymbol + amt;
//	if (ntk_ccyShowDecimal)
	if (ntk_ccyDecimal > 0)
		outstr += ntk_ccyDlm2 + centswrk;

	return outstr;
}

//
// Format Percent
//
function FormatPercent(pct) {
	var outstr = pct * 100;
	outstr = outstr + "%";
	return outstr;
}

// check if enter key is pressed
function checkEnter(e){ //e is event object passed from function invocation
	var charCode;
	if (e && e.which) {
		e = e;
		charCode = e.which; //character code is contained in NN's which property
	} else {
		e = event;
		charCode = e.keyCode; //character code is contained in IE's keyCode property
	}
	if (charCode == 13) { //if generated character code is equal to ascii 13 (if enter key)
		return true;
	} else {
		return false;
	}
}

//
// Display Option Value
//
function OptionString(on0, on1, os0, os1) {
	var str = "";
	if (on0 && on0 !="" && os0 && os0 != "") str += on0 + ntk_optionSep + os0;
	if (on1 && on1 !="" && os1 && os1 != "") {
		if (str != "") str += ntk_optionDelim;
		str += on1 + ntk_optionSep + os1;
	}
	return str;
}

//
// Check amount
//
function CheckAmount() {
	if (total > 0) {
		return true;
	} else {
		alert(ntk_invalidAmount);
		return false;
	}
}

function RoundNumber(num, dgt) {
	var newnum = Math.round(num*Math.pow(10,dgt))/Math.pow(10,dgt);
	return newnum;
}

//
// Update Price based on option
// - f.amount_base = base amount
// - f.os0 = option 1
// - f.os1 = option 2
// - f.amount = final amount
// - ntk_divAmountName = div for display amount
//
function UpdatePrice (f) {
	var id, amt, c, p, v, wrk, opr, adj;
	amt = f.elements[ntk_fldAmountBase].value;
	// process option 1
	c = f.elements[ntk_fldOs0];
	if (c) amt = OptionPrice(amt, OptionValue(c));
	// process option 2
	c = f.elements[ntk_fldOs1];
	if (c) amt = OptionPrice(amt, OptionValue(c));
	// update amount
	f.elements[ntk_fldAmount].value = amt;
	// update display amount
	id = f.elements[ntk_fldID].value;
	if (document.getElementsByName) {
		var e=document.getElementsByName(ntk_divAmountName + id);
		for(var i=0;i<e.length;i++){e[i].innerHTML = ntk_priceCaption + FormatCurrency(amt);};
	} else {
		alert(ntk_browserNotSupported);
	}
}

// Get Option Value
function OptionValue(c) {
	var i, p, v;
	if (c.options) {
		p = c.selectedIndex;
		return c.options[p].value;
	} else if (c[0]) {
		for (i=0; i<c.length; i++) {
			if (c[i].checked) {
				v = c[i].value;
				return v;
			}
		}
	} else if (c) {
		if (c.checked) return c.value;
	}
	return "";
}

//
// Calculate price based on option
// - amt = base amount
// - v = option value (format:  option1=1, option2=+3 or option3=-2)
//
function OptionPrice(amt, v) {
	var p, adj, wrkamt;
	wrkamt = amt;
	p  = v.indexOf ("=");
	if (p >= 0) {
		adj = parseFloat(v.substring(p+1));
		if (!isNaN(adj))
			wrkamt = amt * 1.0 + adj * 1.0;
	}
	return wrkamt;
}

//
// Clear option price before submit
//
function ClearOptionPrice(f) {
	var c;
	c = f.elements[ntk_fldOs0];
	if (c) ClearOption(c);
	c = f.elements[ntk_fldOs1];
	if (c) ClearOption(c);
}

function ClearOption(c) {
	var p, v, q;
	if (c.options) {
		p = c.selectedIndex;
		v = c.options[p].value;
		q = v.indexOf("=");
		if (q >= 0) {
			c.options[p].value = v.substring(0,q);
			return true;
		}
	}
	else if (c[0]) {
		for (var i=0; i<c.length; i++) {
			if (c[i].checked) {
				v = c[i].value;
				q = v.indexOf("=");
				if (q >= 0) {
					c[i].value = v.substring(0,q);
					return true;
				}
			}
		}
	}
	else if (c) {
		if (c.checked) {
			v = c.value;
			q = v.indexOf("=");
			if (q >= 0) {
				c.value = v.substring(0,q);
				return true;
			}
		}
	}
	return false;
}

//
// Check Options
//
function CheckOptions(f) {
	var c1, c2, c3, selected;
	c1 = f.elements[ntk_fldOn0];
	c2 = f.elements[ntk_fldOs0];
	c3 = f.elements[ntk_fldOr0];
	if (c1 && c2 && c3) {
		if (c3.value==1) {
			if (!OptionSelected(c2)) {
				alert(ntk_Option1Message + ' ' + c1.value);
				FocusOption(c2);
				return false;
			}
		}
	}
	c1 = f.elements[ntk_fldOn1];
	c2 = f.elements[ntk_fldOs1];
	c3 = f.elements[ntk_fldOr1];
	if (c1 && c2 && c3) {
		if (c3.value==1) {
			if (!OptionSelected(c2)) {
				alert(ntk_Option2Message + ' ' + c1.value);
				FocusOption(c2);
				return false;
			}
		}
	}
	return true;
}

function OptionSelected(c) {
	if (c.options)
			return (c.selectedIndex > 0);
	else if (c[0]) {
		for (var i=0; i<c.length; i++) {
			if (c[i].checked) return true;
		}
		return false;
	}
	else if (c)
		return c.checked;
}

function FocusOption(c) {
	if (c.options)
		c.focus();
	else if (c[0])
		c[0].focus();
	else if (c)
		c.focus();
}

//
// Submit item to shopping cart
//
function SubmitItemToCart(f) {
	if (document.getElementById) {
		if (CheckOptions(f)) AddItemToCart(f);
	} else {
		alert(ntk_browserNotSupported);		
	}
	return false;
}

//
// Submit item to PayPal
//
function SubmitItem(f) {
	if (document.getElementById) {		
		if (CheckOptions(f))
			//ClearOptionPrice(f);
			return true;
		else
			return false;
	} else {
		alert(ntk_browserNotSupported);
		return false;		
	}
	return true;
}

// Check email format
function CheckEmail(val) {
	if (!(val.indexOf("@") > -1 && val.indexOf(".") > -1))
		return false;    
	return true;
}

//
// Submit shipping details
//
function SubmitShipping(f) {
	if (document.getElementById) {		
		if (CheckShipping(f)) {
			AddShipping(f);
			document.location = ntk_urlConfirm;
		}
	} else {
		alert(ntk_browserNotSupported);		
	}
	return false;
}

//
// Check Shipping
//
function CheckShipping(f) {
	var fname, lname, address1, address2, city, state, zip, country, email;
	fname    = f.elements[ntk_fldFirstName];
	lname    = f.elements[ntk_fldLastName];
	address1 = f.elements[ntk_fldAddress1];
	address2 = f.elements[ntk_fldAddress2];
	city     = f.elements[ntk_fldCity];
	//state    = f.elements[ntk_fldState];
	zip      = f.elements[ntk_fldZip];
	country  = f.elements[ntk_fldCountry];
	email    = f.elements[ntk_fldEmail];
	if (fname && !fname.disabled) {if (fname.value=="") {
			alert(ntk_RequiredMessage + ' ' + ntk_firstName);
			FocusOption(fname);
			return false;}
	}
	if (lname && !lname.disabled) {if (lname.value=="") {
			alert(ntk_RequiredMessage + ' ' + ntk_lastName);
			FocusOption(lname);
			return false;}
	}
	if (address1 && !address1.disabled) {if (address1.value=="") {
			alert(ntk_RequiredMessage + ' ' + ntk_address1);
			FocusOption(address1);
			return false;}
	}
	if (city && !city.disabled) {if (city.value=="") {
			alert(ntk_RequiredMessage + ' ' + ntk_city);
			FocusOption(city);
			return false;}
	}
	if (zip && !zip.disabled) {if (zip.value=="") {
			alert(ntk_RequiredMessage + ' ' + ntk_zip);
			FocusOption(zip);
			return false;}
	}
	//if (state) {if (state.selectedIndex<=0 && state.length>1) {
			//alert(ntk_RequiredMessage + ' ' + ntk_fldState);
			//return false;}
	//}
	if (country) {if (country.selectedIndex<=0) {
			alert(ntk_RequiredMessage + ' ' + ntk_country);
			FocusOption(country);
			return false;}
	}
	if (email) {if (email.value=="") {
			alert(ntk_RequiredMessage + ' ' + ntk_email);
			FocusOption(email);
			return false;} else if (!CheckEmail(email.value)) {
			alert(ntk_InvalidMessage + ' ' + ntk_email);
			FocusOption(email);
			return false;}
	}
	return true;
}

//
// Add Shipping
//
function AddShipping(f) {
	var cookieStr, cookieValue;
	var ppac, ppad;
	var fname, lname, address1, address2, city, state, zip, country, email, shipmethod;
	ppac     = f.ppac;
	ppad     = f.ppad;
	fname    = f.elements[ntk_fldFirstName];
	lname    = f.elements[ntk_fldLastName];
	address1 = f.elements[ntk_fldAddress1];
	address2 = f.elements[ntk_fldAddress2];
	city     = f.elements[ntk_fldCity];
	state    = f.elements[ntk_fldState];
	zip      = f.elements[ntk_fldZip];
	country  = f.elements[ntk_fldCountry];
	email    = f.elements[ntk_fldEmail];
	shipmethod = f.elements[ntk_fldShipMethod];
	var cookieValue = "";
	if (ppac) cookieValue += (ppac.checked)?"1":"0";
	cookieValue += ntk_cartDelimiter;
	if (ppad) cookieValue += (ppad.checked)?"1":"0";
	cookieValue += ntk_cartDelimiter;
	if (fname) cookieValue += fname.value;
	cookieValue += ntk_cartDelimiter;
	if (lname) cookieValue += lname.value;
	cookieValue += ntk_cartDelimiter;
	if (address1) cookieValue += address1.value;
	cookieValue += ntk_cartDelimiter;
	if (address2) cookieValue += address2.value;
	cookieValue += ntk_cartDelimiter;
	if (city) cookieValue += city.value;
	cookieValue += ntk_cartDelimiter;
	if (state) cookieValue += state.value;
	cookieValue += ntk_cartDelimiter;
	if (zip) cookieValue += zip.value;
	cookieValue += ntk_cartDelimiter;
	if (country) cookieValue += country.options[country.selectedIndex].value;
	cookieValue += ntk_cartDelimiter;
	if (email) cookieValue += email.value;
	cookieValue += ntk_cartDelimiter;
	if (shipmethod)
		if (shipmethod.options)
			cookieValue += shipmethod.options[shipmethod.selectedIndex].value;
		else
			cookieValue += shipmethod.value;
	// Write the cookies
	cookieStr = ntk_shipCookie;
	WriteCookie(cookieStr, cookieValue, eval(ntk_cartExpire), ntk_cartPath);
	return true;
}

