'use strict';


var constants = require('./constants'),
    template = require('./util/template');


module.exports = function Qr(data, config) {
    var model = {}, url, key;
    
    // Defaults
    config = config || {};
    config.size = config.size || constants.QR_SIZE;
    config.host = config.host || constants.DEFAULT_HOST;

    // Construct URL
    url = constants.PAYPAL_URL;
    url = url.replace('{host}', config.host);
    url = url + '?';

    for (key in data.items) {
        if (data.items.hasOwnProperty(key)) {
            url += key + '=' + encodeURIComponent(data.get(key)) + '&';
        }
    }

    url = encodeURIComponent(url);

    // Render
    model.url = constants.QR_URL
		.replace('{host}', config.host)
		.replace('{url}', url)
		.replace('{pattern}', constants.QR_PATTERN)
		.replace('{size}', config.size);


    return template(constants.TEMPLATES.qr, model);
};
