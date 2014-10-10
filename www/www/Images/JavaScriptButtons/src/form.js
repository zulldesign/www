'use strict';


var constants = require('./constants'),
    template = require('./util/template'),
    button = require('./button');


module.exports = function form(type, data, config) {
    var model, btn, url, locale;

    btn = button(type, data, config);
    locale = data.get('lc') || constants.DEFAULT_LOCALE;
    
    url = constants.PAYPAL_URL;
    url = url.replace('{host}', config.host || constants.DEFAULT_HOST);

    model = {
        data: data.items,
        button: btn,
        url: url,
        content: constants.STRINGS[locale]
    };

    return template(constants.TEMPLATES.form, model);
};


