'use strict';


var assert = require('assert'),
    DataStore = require('../../../src/util/datastore'),
    datastore;


describe('DataStore tests', function () {


    beforeEach(function () {
        datastore = new DataStore();
    });


    it('should have an exported API', function () {
        assert.ok(datastore);
        assert.ok(datastore.add);
        assert.ok(datastore.remove);
        assert.ok(datastore.get);
        assert.ok(datastore.pluck);
        assert.ok(datastore.parse);
        assert.deepEqual(datastore.items, {});
    });


    it('should be able to add/remove items', function () {
        datastore.add('test1', '1');
        datastore.add('test2', {
            value: '2',
            label: 'tester',
            editable: true
        });

        assert.deepEqual(datastore.items, {
            test1: { label: '', value: '1', editable: false },
            test2: { label: 'tester', value: '2', editable: true }
        });

        datastore.remove('test2');

        assert.deepEqual(datastore.items, {
            test1: { label: '', value: '1', editable: false }
        });

        datastore.remove('test1');

        assert.notStrictEqual(datastore.items, {});
    });


    it('should be able to get items', function () {
        datastore.add('test1', '1');
        datastore.add('test2', {
            value: '2',
            label: 'tester',
            editable: true
        });

        assert.equal(datastore.get('test1'), '1');
        assert.equal(datastore.get('test2'), '2');
    });


    it('should be able to pluck items', function () {
        datastore.add('test1', '1');
        datastore.add('test2', {
            value: '2',
            label: 'tester',
            editable: true
        });

        assert.equal(datastore.pluck('test1'), '1');
        assert.deepEqual(datastore.items, {
            test2: { label: 'tester', value: '2', editable: true }
        });
        assert.equal(datastore.pluck('test2'), '2');
        assert.deepEqual(datastore.items, {});
    });


});