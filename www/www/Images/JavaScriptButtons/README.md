# PayPal Payment Buttons [![Build Status](https://travis-ci.org/paypal/JavaScriptButtons.png?branch=master)](https://travis-ci.org/paypal/JavaScriptButtons)

PayPal payment buttons that are as easy as including a snippet of code. [Try it out and configure your own](http://paypal.github.com/JavaScriptButtons/).

We have a few flavors of buttons for you to use:



## Stand Alone Buttons

Perfect for use with Express Checkout or other API-based solutions

```html
<script async src="paypal-button.min.js?merchant=YOUR_MERCHANT_ID"
    data-button="buynow"
></script>
```

Any type of button may be used: `buynow`, `cart`, `donate`, or `subscribe`.



## PayPal Payments Standard Buttons

Buttons that create a PayPal button and HTML checkout form for you.


### Buy Now
Buy Now buttons are for single item purchases.

```html
<script async src="paypal-button.min.js?merchant=YOUR_MERCHANT_ID"
    data-button="buynow"
    data-type="form"
    data-name="My product"
    data-amount="1.00"
></script>
```

Add `data-hosted_button_id` to your script along with your button ID for hosted buttons.


### Add To Cart
Add To Cart buttons let users add multiple items to their PayPal cart.

```html
<script async src="paypal-button.min.js?merchant=YOUR_MERCHANT_ID"
    data-button="cart"
    data-type="form"
    data-name="Product in your cart"
    data-amount="1.00"
></script>
```

### QR Codes
QR codes can be easily scanned with a smart phone to check out.

```html
<script async src="paypal-button.min.js?merchant=YOUR_MERCHANT_ID"
    data-button="buynow"
    data-type="qr"
    data-name="Product via QR code"
    data-amount="1.00"
></script>
```

### Donations
Donation buttons let you accept donations from your users.

```html
<script async src="paypal-button.min.js?merchant=YOUR_MERCHANT_ID"
    data-button="donate"
    data-type="form"
    data-name="My donation"
    data-amount="1.00"
></script>
```

### Subscriptions
Subscribe buttons let you set up payment subscriptions.

```html
<script async src="paypal-button.min.js?merchant=YOUR_MERCHANT_ID"
    data-button="subscribe"
    data-type="form"
    data-name="My product"
    data-amount="1.00"
    data-recurrence="1"
    data-period="M"
></script>
```

## PayPal Payments Standard Features

### Data variables
All of PayPal's [HTML button variables](https://developer.paypal.com/webapps/developer/docs/classic/paypal-payments-standard/integration-guide/Appx_websitestandard_htmlvariables/) are supported by prefixing their name with "data-". Here are the most commonly used:

* `data-name` Description of the item.
* `data-number` The number of the item.
* `data-amount` The price of the item.
* `data-currency` The currency of the item (note: these cannot be mixed).
* `data-quantity` Quantity of items to purchase.
* `data-shipping` The cost of shipping this item.
* `data-tax` Transaction-based tax override variable.
* `data-size` For button images: `small` and `large` work. For QR codes enter the pixel length of the longest side.
* `data-style` The style of the button. Can be set to `primary` (default) and `secondary`.
* `data-locale` The desired locale of the PayPal site.
* `data-callback` The IPN notify URL to be called on completion of the transaction.
* `data-host` The PayPal host to checkout in, e.g. `www.sandbox.paypal.com` (defaults to 'www.paypal.com').
* `data-type` The type of button to render. `button` for a plain button (default), `form` to create a button with a PayPal Payments Standard HTML form, or `qr` to create a PayPal Payments Standard compatible QR code.


### Editable inputs
Creating editable inputs is easy. Just add `-editable` to the name of your variable, e.g. `data-quantity-editable`, and an input field will magically appear for your users.


### Options fields
Allow the user to choose from multiple options with the following syntax:

```
data-option0="Color=Blue:8.00,Green:12.00,Red:10.00"
data-option1="Size=Small,Medium,Large"
```


### Callback notification
On completion of a transaction you can get a payment notification ([IPN](https://developer.paypal.com/docs/classic/ipn/integration-guide/IPNIntro/)) on a callback URL you specify using the `data-callback` attribute. An [IPN simulator](https://developer.paypal.com/webapps/developer/applications/ipn_simulator) is available on the sandbox.


## Button Features

### Localization
* Changing the default language of a button can be done by setting the variable `data-lc` with the correct locale code, e.g. es_ES.
* Changing the default input labels of editable buttons can be done by overriding the default configuration, e.g. `paypal.button.config.labels`.


### JavaScript API
There's even a fancy JavaScript API if you'd like to pragmatically create your buttons.

**paypal.button.create(business, data, config, parentNode)**  
Creates and returns an HTML element that contains the button code. 
> **business** - A string containing either the business ID or the business email  
> **data** - A JavaScript object containing the button variables  
> **config** - A configuration object for the button. Possible settings are `button`, `type`, `style`, `size`, and `host`   
> **parentNode** - An HTML element to add the newly created button to (Optional)  

**paypal.button.process(node)** 
Parses `node` and automatically runs `paypal.button.create` on any `<script>` element it finds matching the right criteria. This is called automatically on `document.body`.


## Browser support 
The JavaScript buttons have been tested and work in all modern browsers including:

* Chrome
* Safari
* Firefox
* Internet Explorer 8+.


## Getting your Merchant ID
Your merchant ID needs to be added to the URL of the referenced script. This ID can either be your Secure Merchant ID, which can be found by logging into your PayPal account and visiting your profile, or your email address.


## Contributing 

We love contributions! If you'd like to contribute please submit a pull request via GitHub. 

[Mocha](https://github.com/visionmedia/mocha) is used to run our test cases. Please be sure to run these prior to your pull request and ensure nothing is broken.


## Authors
**Jeff Harrell**  
[https://github.com/jeffharrell](https://github.com/jeffharrell)

**Mark Stuart**  
[https://github.com/mstuart](https://github.com/mstuart)
