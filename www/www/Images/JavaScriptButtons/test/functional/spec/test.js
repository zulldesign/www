/*jshint node:true, evil:true */
/*global describe:true, it:true, paypal:true, document:true, window:true, before:true, beforeEach:true */

'use strict';


if (typeof window === 'undefined') {
	var fs = require('fs'),
		should = require('should'),
		jsdom = require('jsdom').jsdom,
		jsdomOptions = { features: { QuerySelector: true }},
		testFile = fs.readFileSync('./test/functional/index.html').toString(),
		document = jsdom(testFile, null, jsdomOptions),
		window = document.createWindow();

	eval(fs.readFileSync('dist/button.js').toString());
}

// Test the object's integrity
describe('JavaScript API', function () {

	var namespace;

	before(function () {
		namespace = window.paypal;
	});

	it('Should have a paypal namespace', function () {
		namespace.should.be.an.Object;
	});

	it('Should have a create method', function () {
		namespace.button.create.should.be.a.Function;
	});

	it('Create return false if no parameters', function () {
		var result = namespace.button.create();

		result.should.equal(false);
	});

});


// Test the buttons counter object
describe('Test page button counter', function () {

	var buttons;

	before(function () {
		buttons = window.paypal.button.counter;
	});

	it('Should have seven buy now buttons', function () {
		buttons.buynow.should.equal(15);
	});

	it('Should have two cart buttons', function () {
		buttons.cart.should.equal(3);
	});

	it('Should have two donation buttons', function () {
		buttons.donate.should.equal(3);
	});

	it('Should have two subscribe buttons', function () {
		buttons.subscribe.should.equal(3);
	});
});


// Test environments
describe('Environments', function () {

	var sandbox, www;

	before(function () {
		sandbox = document.querySelector('#sandbox form');
		www = document.querySelector('#buynow-sm form');
	});

	it('Should be a sandbox button', function () {
		sandbox.action.should.include('//www.sandbox.paypal');
	});

	it('Should be a www button', function () {
		www.action.should.include('//www.paypal');
	});

});


// Test different forms
describe('Form factors', function () {

	it('Should produce a valid form', function () {
		document.querySelectorAll('#buynow-sm form').length.should.equal(1);
	});

	it('Should produce a single button', function () {
		document.querySelectorAll('#button form').length.should.equal(0);
		document.querySelectorAll('#button button').length.should.equal(1);
	});

	it('Should produce a valid QR code', function () {
		document.querySelector('#qr img').src.should.include('//www.paypal.com');
	});
});


// Test editable fields
describe('Editable buttons', function () {

	var inputs;

	before(function () {
		inputs = document.querySelectorAll('#buynow-editable input[type=text]');
	});

	it('Should have three inputs', function () {
		inputs.length.should.equal(3);
	});

	it('Should have a CSS class on the input', function () {
		inputs[0].className.should.include('paypal-input');
	});

	it('Should have a CSS class on the label', function () {
		inputs[0].parentNode.className.should.include('paypal-label');
	});

	it('Should have proper labels', function () {
		var labels = document.querySelectorAll('#buynow-editable label');

		labels[0].textContent.replace(/^\s+/, '').replace(/\s+$/, '').should.equal('Item');
		labels[1].textContent.replace(/^\s+/, '').replace(/\s+$/, '').should.equal('Amount');
		labels[2].textContent.replace(/^\s+/, '').replace(/\s+$/, '').should.equal('Quantity');
	});

	it('Should have a CSS class on the container', function () {
		inputs[0].parentNode.parentNode.className.should.include('paypal-group');
	});

});


// Test multi-language support
describe('Multi-language button images', function () {

	function testLanguage(locale, type, expected) {
		it('Should have a ' + locale + ' version of the ' + type + ' button', function () {
			var button = document.querySelector('#' + type + '-' + locale + ' button[type=submit] .paypal-button-content'),
				buttonText = button && button.textContent;

			buttonText.should.equal(expected);
		});
	}

	testLanguage('es_ES', 'buynow', 'Comprar con ');
	testLanguage('de_DE', 'buynow', 'Kaufen mit ');
	testLanguage('ja_JP', 'buynow', 'で購入手続きに進む');
});


// Test multiple button image sizes
describe('Multiple button image sizes', function () {

	function testSize(size, type, expected) {
		it('Should have a ' + size + ' version of ' + type + ' button', function () {
			var button = document.querySelector('#' + type + '-' + size + ' button[type=submit]'),
				buttonClass = button && button.className;

			buttonClass.should.include(expected);
		});
	}

	testSize('sm', 'buynow', 'small');
	testSize('md', 'buynow', 'medium');
	testSize('lg', 'buynow', 'large');
	testSize('sm', 'buynow-secondary', 'small');
	testSize('md', 'buynow-secondary', 'medium');
	testSize('lg', 'buynow-secondary', 'large');
	testSize('sm', 'cart', 'small');
	testSize('md', 'cart', 'medium');
	testSize('lg', 'cart', 'large');
	testSize('sm', 'donate', 'small');
	testSize('md', 'donate', 'medium');
	testSize('lg', 'donate', 'large');
	testSize('sm', 'subscribe', 'small');
	testSize('md', 'subscribe', 'medium');
	testSize('lg', 'subscribe', 'large');
});


// Test button styles
describe('Styled buttons', function () {

	var primary, secondary;

	before(function () {
		primary = document.querySelectorAll('.paypal-style-primary');
		secondary = document.querySelectorAll('.paypal-style-secondary');
	});

	it('Should have primary buttons', function () {
		primary.length.should.equal(20);
	});

	it('Should have secondary buttons', function () {
		secondary.length.should.equal(3);
	});

});


describe('Options buttons', function () {
	var form = document.getElementById('button-options');
	var selects = form.getElementsByTagName('select');

	it('Should have two selects', function () {
		selects.length.should.equal(2);
	});

	it('Should have the right number of options per select', function () {
		selects[0].options.length.should.equal(3);
		selects[1].options.length.should.equal(5);
	});

	it('Should have the right values per options', function () {
		selects[0].options[0].textContent.should.equal('Blue 8.00');
		selects[0].options[0].value.should.equal('Blue');

		selects[1].options[0].textContent.should.equal('Tiny');
		selects[1].options[0].value.should.equal('Tiny');
	});

});
