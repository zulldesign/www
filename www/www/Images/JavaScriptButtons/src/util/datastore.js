'use strict';


var constants = require('../constants');


function DataStore() {
    this.items = {};
}


DataStore.prototype.add = function addData(key, val) {
    // Remap nice values
    key = constants.PRETTY_PARAMS[key] || key;

    // Wrap strings in the value object
    if (typeof val === 'string') {
        val = {
            value: val
        };
    }

    this.items[key] = {
        label: val.label || '',
        value: val.value || '',
        editable: !!val.editable
    };
};


DataStore.prototype.get = function getData(key) {
    var item = this.items[key];

    return item && item.value;
};


DataStore.prototype.remove = function removeData(key) {
    delete this.items[key];
};


DataStore.prototype.pluck = function pluckData(key) {
    var val = this.get(key);
    this.remove(key);

    return val;
};


DataStore.prototype.parse = function parseData(el) {
    var attrs, attr, matches, key, label, value, editable, len, i;

    if ((attrs = el.attributes)) {

        for (i = 0, len = attrs.length; i < len; i++) {
            attr = attrs[i];

            if ((matches = attr.name.match(/^data-([a-z0-9_]+)(-editable)?/i))) {
                key = matches[1];
                editable = !!matches[2];
                value = attr.value;

                if (key.indexOf('option') === 0) {
                    value = value.split('=');
                    label = value[0];
                    value = value[1].split(',');
                }

                this.add(key, {
                    label: label,
                    value: value,
                    editable: editable
                });
            }


        }
    }
};



module.exports = DataStore;
