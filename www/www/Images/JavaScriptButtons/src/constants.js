'use strict';


module.exports = {

	BN_CODE: 'JavaScriptButtons_{label}',

	PAYPAL_URL: 'https://{host}/cgi-bin/webscr',

	QR_URL: 'https://{host}/webapps/ppint/qrcode?data={url}&pattern={pattern}&height={size}',

	QR_PATTERN: 13,

	QR_SIZE: 250,

	PRETTY_PARAMS: {
		name: 'item_name',
		number: 'item_number',
		locale: 'lc',
		currency: 'currency_code',
		recurrence: 'p3',
		period: 't3',
		callback: 'notify_url',
		button_id: 'hosted_button_id'
	},

	WIDGET_NAME: 'paypal-button-widget',

	DEFAULT_HOST: 'www.paypal.com',

	DEFAULT_TYPE: 'button',

	DEFAULT_LABEL: 'buynow',

	DEFAULT_SIZE: 'large',

	DEFAULT_STYLE: 'primary',

	DEFAULT_LOCALE: 'en_US',

	DEFAULT_ENV: 'www',

	TEMPLATES: '$TEMPLATES$',

	STRINGS: '$STRINGS$',

	STYLES: '$STYLES$',

	LOGO: '$LOGO$',

	WORDMARK: {
		primary: '$WORDMARK_PRIMARY$',
		secondary: '$WORDMARK_SECONDARY$'
	}

};
