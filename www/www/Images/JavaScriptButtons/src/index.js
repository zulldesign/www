'use strict';


var DataStore = require('./util/datastore'),
    factory = require('./factory'),
    app = {};


app.counter = {
    buynow: 0,
    cart: 0,
    donate: 0,
    subscribe: 0
};


app.create = function (business, data, config, parent) {
    var result = factory(business, data, config);

    if (result) {
        // Log how many buttons were created
        app.counter[result.label] += 1;

        // Add it to the page
        if (parent) {
            parent.appendChild(result.el);
        }
    }

    return result;
};


app.process = function (el) {
    var nodes = el.getElementsByTagName('script'),
        node, data, business, i, len;

    for (i = 0, len = nodes.length; i < len; i++) {
        node = nodes[i];

        if (!node || !node.src) {
            continue;
        }

        data = new DataStore();
        data.parse(node);

        // If there's a merchant ID attached then it's a button of interest
        if ((business = node.src.split('?merchant=')[1])) {
            app.create(
                business,
                data,
                {
                    type: data.pluck('type'),
                    label: data.pluck('button'),
                    size: data.pluck('size'),
                    style: data.pluck('style'),
                    host: data.pluck('host')
                },
                node.parentNode
            );

            // Clean up
            node.parentNode.removeChild(node);
        }
    }
};



// Support node and the browser
if (typeof window === 'undefined') {
    module.exports = app;
} else {
    // Make the API available
    if (!window.paypal) {
        window.paypal = {};
        window.paypal.button = app;
    }

    // Bind to existing scripts
    window.paypal.button.process(document);
}
