'use strict';


var DataStore = require('./util/datastore'),
    constants = require('./constants'),
    button = require('./button'),
    css = require('browserlib').css,
    form = require('./form'),
    QR = require('./qr'),
    hasCss = false;



module.exports = function factory(business, raw, config) {
    var data, wrapper, html, key, label, type;

    if (!business) {
        return false;
    }


    // Normalize incoming data if needed
    if (raw.items) {
        data = raw;
    } else {
        data = new DataStore();

        for (key in raw) {
            if (raw.hasOwnProperty(key)) {
                data.add(key, raw[key]);
            }
        }
    }


    // Defaults
    config = config || {};
    label = config.label || constants.DEFAULT_LABEL;
    type = config.type || constants.DEFAULT_TYPE;


    // Cart
    if (type === 'cart') {
        data.add('cmd', '_cart');
        data.add('add', true);
    // Donation
    } else if (type === 'donate') {
        data.add('cmd', '_donations');
    // Subscribe
    } else if (type === 'subscribe') {
        data.add('cmd', '_xclick-subscriptions');

        if (data.get('amount') && !data.get('a3')) {
            data.add('a3', data.pluck('amount'));
        }
    // Buy Now
    } else if (data.get('hosted_button_id')) {
        data.add('cmd', '_s-xclick');
    } else {
        data.add('cmd', '_xclick');
    }

    // Add common data
    data.add('business', business);
    data.add('bn', constants.BN_CODE.replace(/\{label\}/, label));


    // Build the UI components
    if (type === 'qr') {
        html = QR(data, config);
    } else if (type === 'button') {
        html = button(label, data, config);
    } else {
        html = form(label, data, config);
    }


    // Inject the CSS onto the page
    if (!hasCss) {
        hasCss = true;
        css.inject(document.getElementsByTagName('head')[0], constants.STYLES);
    }


    // Wrap it up all nice and neat and return it
    wrapper = document.createElement('div');
    wrapper.className = constants.WIDGET_NAME;
    wrapper.innerHTML = html;

    return {
        label: label,
        type: type,
        el: wrapper
    };
};
