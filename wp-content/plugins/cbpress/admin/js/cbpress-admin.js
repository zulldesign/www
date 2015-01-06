	jQuery.fn.extend({
	    propAttr: jQuery.fn.prop || jQuery.fn.attr
	});


	(function ($) {
	    $.fn.showHide = function (options) {
		//default vars for the plugin
		var defaults = {
		    speed: 1000,
		    easing: '',
		    changeText: 0,
		    showText: 'Show',
		    hideText: 'Hide',
		};
		var options = $.extend(defaults, options);
		$(this).click(function () {
		    // this var stores which button you've clicked
		    var toggleClick = $(this);
		    // this reads the rel attribute of the button to determine which div id to toggle
		    var toggleDiv = $(this).attr('rel');
		    // here we toggle show/hide the correct div at the right speed and using which easing effect
		    $(toggleDiv).slideToggle(options.speed, options.easing, function () {
			// this only fires once the animation is completed
			if (options.changeText == 1) {
			    $(toggleDiv).is(":visible") ? toggleClick.text(options.hideText) : toggleClick.text(options.showText);
			}
		    });
		    return false;
		});
	    };
	})(jQuery);
	jQuery(document).ready(function ($) {
	    $('.show_hide').showHide({
		speed: 1000, // speed you want the toggle to happen
		easing: '', // the animation effect you want. Remove this line if you dont want an effect and if you haven't included jQuery UI
		changeText: 1, // if you dont want the button text to change, set this to 0
		showText: 'View', // the button text to show when a div is closed
		hideText: 'Close' // the button text to show when a div is open
	    });
	});
	/*====================/


	jqtabs v1.0


	Michael Jasper


	http://www.mikedoesweb.com/tabs/


	(c) 2011


	=====================*/
	(function ($) {
	    $.fn.jqtabs = function (cycleSpeed) {
		return this.each(function () {
		    var all = this;
		    var cycleIterator = 1;
		    var numberOfTabs = 0;
		    var allTabs;
		    /*  Tab Click


		    =====================*/
		    $(".navigation li", this).click(function () {
			changeTo(this)
		    });
		    /*  Initiate Cycle


		    =====================*/
		    if (cycleSpeed) {
			//array of tabs
			allTabs = $(".navigation li", all);
			numberOfTabs = allTabs.length;
			setInterval(function () {
			    var tabIndex = (cycleIterator % numberOfTabs);
			    var tabToCycle = allTabs[tabIndex];
			    changeTo(tabToCycle);
			    cycleIterator++;
			}, cycleSpeed);
		    }
		    /*  changeTo(tab) Function


		    =====================*/
		    var changeTo = function (tab) {
			if (!$(tab).hasClass("active")) {
			    $(".panel", all).hide();
			    $("#" + $(tab).attr("title")).fadeIn('fast', function () {
				$(".active", all).removeClass("active");
				$(tab).addClass("active");
			    });
			}
		    };
		});
	    }
	})(jQuery);

	function cbp_reset_textbox(id, d, m, e) {
	    if (confirm(m + "\n\n" + d)) {
		document.getElementById(id).value = d;
		e.className = 'hidden';
	    }
	}

	function cbp_textbox_value_changed(e, d, l) {
	    if (e.value == d) {
		// document.getElementById(l).className='hidden';
	    } else {
		document.getElementById(l).className = '';
	    }
	}

	function cbp_toggle_blind(id) {
	    if (document.getElementById(id)) {
		if (document.getElementById(id).style.display == 'none') {
		    Effect.BlindDown(id);
		} else {
		    Effect.BlindUp(id);
		}
	    }
	    return false;
	}

	function CbpressImport() {
	    var $ = jQuery;
	    var self = this;
	    var ajaxStarted = 0;
	    var ajaxDone = 0;
	    this.importid = 0;
	    this.ajaxurl = cbpressjs.ax;
	    this.nonce = cbpressjs.salt;
	    this.importid = 1;
	    this.started = 0;
	    this.running = 0;
	    this.logreads = 0;
	    this.toread = 0;
	    this.good_to_go = 0;
	    this.refreshId = null;
	    this.done = 0;
	    this.errors = 0;
	    this.busy = false;
	    this.params = {};
	    this.reqid = function () {
		return new Date().getTime() + "" + 1;
	    };
	    this.in_progress = function () {
		return self.started != 0;
	    };
	    this.import_check = function () {
		// alert('import_check');
		$.post(
		self.ajaxurl, self.params, function (data) {
		    $("#import-loading").hide();
		    $("#import-error").hide();
		    $.each(data.errors, function (i, err) {
			$("#import-error").show();
			$("#import-error").html(err);
			self.errors = 1;
			self.done = 1;
			ajaxDone = 1;
		    });
		    if (self.errors == 1) {
			$("#progbar").hide();
			return false;
		    }
		    $("#progbar").show();
		    // self.toread = data.toread;
		    ajaxDone = data.done;
		    self.setter('toread', data.toread);
		    self.setter('done', data.done);
		    self.update_progress(data);
		    if (self.started == 1 || self.done == 0) {}
		}, 'json');
	    };
	    this.setter = function (k, v) {
		self[k] = v;
	    };
	    this.set_done = function (v) {
		self.done = v;
	    };
	    this.update_progress = function (data) {
		$("#cbp-progress-text").html(data.text);
		$('#cbp-progress-value').css('width', data.width);
		self.to_screen(data.textupdate);
		str = data.cats + ' categories, ' + data.products + ' products';
		$("#progressbarText1").html('Loaded: ' + str);
	    };
	    this.to_screen = function (msg) {
		$("#import-done").show();
		if ((self.done == 1 || ajaxDone == 1) && self.errors == 0) {
		    msg = '<h3>Import Complete</h3>';
		} else {}
		// msg = msg + ' ' + self.logreads;
		$("#import-done").html('<h3>' + msg + '</h3>');
	    };
	    this.pollster = function () {
		self.logreads++;
		if (self.done == 0) {
		    if (self.started > 0) {
			self.import_check();
		    }
		} else {
		    clearInterval(self.refreshId);
		    self.done = 1;
		    if (self.errors == 0) {
			self.to_screen('Import Complete');
		    }
		}
	    };
	    this.makeuri = function (command) {
		return self.ajaxurl + '&_random=' + self.reqid() + '&action=cbp_import_' + command;
	    };
	    this.showinfo = function () {
		var div = $("<div/>").addClass("module").attr("id", 'xpostcustomstuff');
		var widefat = $("<table/>").addClass("form-table").attr("id", 'admin_actions').width("100px");
		var title = $("<th/>").addClass("title").attr("colspan", '3').html('Admin Actions');
		var link1 = $("<td/>").addClass("visit").append(jQuery("<a/>").attr("target", "_blank").attr("href", self.makeuri('start')).html("start"));
		var link2 = $("<td/>").addClass("visit").append(jQuery("<a/>").attr("target", "_blank").attr("href", self.makeuri('run')).html("run"));
		var link3 = $("<td/>").addClass("visit").append(jQuery("<a/>").attr("target", "_blank").attr("href", self.makeuri('check')).html("check"));
		widefat.append($("<tr/>").append(title).append(link1).append(link2).append(link3));
		div.append(widefat);
		div.appendTo("#admin_import_info");
	    };
	    this.log = function (msg) {
		if (window.console && window.console.log) {
		    window.console.log(msg);
		} else if (window.opera && window.opera.postError) {
		    window.opera.postError(msg);
		}
	    };
	    this.startInterval = function () {
		self.refreshId = window.setInterval(function () {
		    self.pollster();
		}, 400);
	    };
	    this.import_run = function () {
		$.get(
		self.makeuri('start'), function (errmsg) {
		    errmsg = $.trim(errmsg);
		    if (errmsg.length > 1) {
			self.to_screen('Oops...' + errmsg);
			$("#import-loading").hide();
			$("#import-error").show();
			$("#import-error").html(errmsg);
			self.errors = 1;
		    } else {
			$.post(self.makeuri('run'));
			self.started = 1;
			var time1 = setTimeout(function () {
			    self.startInterval();
			}, 5000);
		    }
		});
	    };
	    this.import_now = function () {
		self.to_screen('Requesting data from ClickBank... please wait');
		self.import_run();
	    };
	    this.import_start = function (element) {
		ajaxStarted = 1;
		$.post(self.ajaxurl, {
		    'action': 'cbp_import_busy'
		}, function (data) {
		    self.busy = data;
		    if (data == true) {
			self.to_screen('Import already in progress');
			self.started = 1;
			var time1 = setTimeout(function () {
			    self.startInterval();
			}, 5000);
			return false;
		    } else {
			$("#import-loading").show();
			$('a.cbpress-importlog').hide();
			if (self.in_progress()) {
			    self.to_screen('In progress, please wait...');
			} else {
			    self.import_now();
			}
		    }
		});
		return false;
	    };
	    this.cursor_change = function () {
		$("html").bind("ajaxStart", function () {
		    ajaxStarted = 1;
		    $(this).addClass('busy');
		}).bind("ajaxStop", function () {
		    if (ajaxStarted == 1) {
			ajaxStarted = 0;
			// self.done = 1;
		    }
		    $(this).removeClass('busy');
		});
	    };
	    this.import_read_status = function () {
		params = {
		    action: "cbp_import_check",
		    _ajax_nonce: cbpressjs.salt,
		    r: self.reqid()
		};
		$.ajax({
		    type: 'POST',
		    url: cbpressjs.ajaxurl,
		    data: params,
		    success: function (result) {
			// $("#import-results").show().html( $.dump( result ) );
			$("#import-percent").show().html(result.pct + '%');
		    },
		    dataType: "json"
		});
	    };
	    this.setup = function () {
		self.cursor_change();
		$.ajaxSetup({
		    cache: false
		});
		$('a.cbpress-importlog').unbind('click').click(function () {
		    return self.import_start(this)
		});
	    };
	    this.init = function () {
		$("#import-button").hide();
		this.params = {
		    'action': 'cbp_import_check',
		    'type': 'json',
		    'security': self.nonce,
		    '_ajax_nonce': self.nonce,
		    'randval': self.reqid()
		};
		$("#import-busy-check").show().html('<p><b>Please wait... checking the importer</b></p>');
		$.ajax({
		    type: 'GET',
		    url: cbpressjs.ajaxurl,
		    data: {
			action: "cbp_import_busy",
			nonce: cbpressjs.nonce,
			r: $.CBP.newreq()
		    },
		    error: function (data, transport) {
			alert(data.status + " " + transport);
			self.setup(false);
		    },
		    success: function (result) {
			if (result.status == 'busy') {
			    if (1 == 2) {
				var time1 = setTimeout(function () {
				    $("#import-busy").show();
				    $("#import-busy-check").html('<p><b>Import currently in progress</b></p>');
				}, 1000);
				self.import_read_status();
			    } else {
				// ajaxStarted = 1;
				self.started = 1;
				self.done = 0;
				var time1 = setTimeout(function () {
				    self.startInterval();
				}, 2000);
				self.setup();
				// var time1 = setTimeout(function() { self.startInterval(); }, 1000);
			    }
			} else {
			    var time1 = setTimeout(function () {
				$("#import-busy-check").html('').hide();
				$("#import-button").show();
				$("#import-busy").hide();
			    }, 1000);
			    self.setup();
			}
		    },
		    dataType: "json"
		});
		// self.setup(false);
		// if(cbpressjs.showdebug == 1) self.showinfo();
		return self;
	    };
	}

	function load_cbpress() {
	    var $ = jQuery;
	    $.CBP = {
		init: function () {
		    $('.deleteme').live("click", function (event) {
			return confirm("Are you sure you want to delete this?");
		    });
		    $('.deletecat').live("click", function (event) {
			return confirm("Delete this Category and it's Sub-Categories?");
		    });
		},
		init_import: function () {
		    var importer = new CbpressImport();
		    // importer.import_isbusy();
		    importer.init();
		},
		init_placeholders: function () {
		    var input = document.createElement('input');
		    var hasPlaceholderSupport = ('placeholder' in input);
		    if (hasPlaceholderSupport) return true;
		    $('input[type="text"]').each(function () {
			var input = this;
			if ($(input).attr('placeholder')) {
			    $(input).blur(function () {
				if ($(this).val() == '') {
				    $(this).val($(this).attr('placeholder'));
				}
			    });
			    $(input).focus(function () {
				if ($(this).val() == $(this).attr('placeholder')) {
				    // $(this).val(''); 
				}
			    });
			    if ($(input).val() == '') {
				$(input).val($(input).attr('placeholder'));
			    }
			}
		    });
		},
		init_tree1: function () {
		    $("#tree1").dynatree({
			persist: true,
			checkbox: true,
			selectMode: 3,
			onPostInit: function (isReloading, isError) {
			    logMsg("onPostInit(%o, %o)", isReloading, isError);
			    // Re-fire onActivate, so the text is update
			    this.reactivate();
			},
			onActivate: function (node) {
			    $("#echoActive").text(node.data.title);
			},
			onDeactivate: function (node) {
			    $("#echoActive").text("-");
			},
			onDblClick: function (node, event) {
			    logMsg("onDblClick(%o, %o)", node, event);
			    node.toggleExpand();
			}
		    });
		},
		getid: function (e) {
		    return jQuery(e).attr('id');
		},
		newreq: function () {
		    return new Date().getTime() + "" + 1;
		},
		reloader: function (msg) {
		    go = cbpressjs.redirector;
		    if (msg != '') {
			go = go + "&msg=" + escape(msg);
		    }
		    window.location.href = go;
		    // if(msg == ''){
		    // 	window.location.href = window.location.href.replace(/&?msg=([^&]$|[^&]*)/i, ""); 
		    // }else{
		    // 	window.location.href = window.location.href.replace(/&?msg=([^&]$|[^&]*)/i, "") + "&msg="+escape(msg); 
		    // }
		    // window.location.reload(); 
		},
		select_all: function () {
		    $('.item :checkbox').each(function () {
			this.checked = (this.checked ? '' : 'checked');
		    });
		    return false;
		},
		dialog: function (divid, title, width, height, closeText) {
		    var btns = {};
		    btns[closeText] = function () {
			$(this).dialog("destroy");
		    };
		    $(divid).dialog({
			width: width,
			height: height,
			bgiframe: true,
			dialogClass: 'infoBoxContent',
			title: title,
			modal: true,
			close: function () {
			    $(this).dialog('destroy');
			},
			buttons: btns
		    });
		    return false;
		},
		dialogwin: function (divid, title, width, height, closeText) {
		    var btns = {};
		    btns[closeText] = function () {
			$(this).dialogwin("destroy");
		    };
		    $(divid).dialog({
			width: width,
			height: height,
			bgiframe: true,
			dialogClass: 'infoBoxContent',
			title: title,
			modal: false,
			close: function () {
			    $(this).dialogwin('destroy');
			}
		    });
		    return false;
		},
		init_setup: function () {
		    $("#findOrder").click(function () {
			$.CBP.dialog('#find_order', "Finding Your Order Number", 600, 400, 'Close');
		    });
		    $("#ccexample").click(function () {
			$.CBP.dialog('#creditCard', "Example Credit Card Statement", 400, 200, 'Close');
		    });
		    $("#pp1example").click(function () {
			$.CBP.dialog('#paypal1', "Example Paypal Transaction Detail", 415, 270, 'Close');
		    });
		    $("#pp2example").click(function () {
			$.CBP.dialog('#paypal2', "Example Credit Card Statement For PayPal", 400, 380, 'Close');
		    });
		    $("#showReceipt").click(function () {
			$.CBP.dialog('#receiptImg', "Example Purchase Notification Email", 660, 300, 'Close');
		    });
		    $("#activateButton").click(function () {
			msg = '';
			if ($("#cbp_api_aff").val() == '') {
			    msg = 'Please enter your ClickBank Affiliate ID';
			}
			if ($("#cbp_api_rec").val() == '') {
			    msg = 'Please enter your ClickBank Receipt';
			}
			if (msg != '') {
			    msg = '<div class="cb-message"><div class="cb-error"><strong><p>' + msg + '</p></strong></div></div>';
			    $("#cbp_api_response").html(msg);
			    return false;
			}
		    });
		},
		init_cluetips: function () {
		    $('.hoverable').cluetip({
			local: true,
			showTitle: false,
			arrows: false,
			tracking: false,
			dropShadow: true
		    });
		    $('thead.tooltips th').cluetip({
			local: true,
			showTitle: false,
			arrows: true
		    });
		},
		init_help: function () {
		    var helploaded = 0;
		    if (helploaded == 0) {
			$("#helpcontent").load('http://help.cbpress.com/',
			null,

			function () {
			    helploaded = 1;
			});
		    }
		},
		load_searchpanel: function () {
		    $("#searchpanel").load(
		    cbpressjs.ax + '&action=cbp_search_box',
		    null,

		    function () {
			loaded = 1;
			$(this).toggle();
		    });
		},
		init_searchpanel: function () {
		    var loaded = 0;
		    if ($('.noproducts').length) {
			$("#searchpanel").css({
			    position: "relative"
			});
		    }
		    if (cbpressjs.sb == 1) {
			$.CBP.load_searchpanel();
			loaded = 1;
		    }
		    $(".searchpanel_btn").click(function (event) {
			if (loaded == 0) {
			    $("#searchpanel").load(
			    cbpressjs.ax + '&action=cbp_search_box',
			    null,

			    function () {
				loaded = 1;
				$(this).slideToggle("fast");
			    });
			} else {
			    $.get(cbpressjs.ax + '&action=cbp_searchbox_off');
			    $("#searchpanel").slideToggle("fast");
			}
			$(this).toggleClass("searchpanel_active");
			event.preventDefault();
			event.stopPropagation();
			return false;
		    });
		},
		init_tabs: function () {
		    activeTab = "#tab1";
		    activeTabContent = "#content1";
		    $(".option").click(function () {
			deactivatedTab = "#" + $(this).attr('id');
			deactivatedTabContent = $(deactivatedTab).attr('tab');
			id = $(this).attr('tab');
			$(activeTab).removeClass('active');
			$(activeTabContent).addClass('hide');
			$(deactivatedTab).addClass('active');
			$(deactivatedTabContent).removeClass('hide');
			activeTab = deactivatedTab;
			activeTabContent = deactivatedTabContent;
		    });
		},
		/*** Results ***/
		items_to: function (type, target) {
		    params = {
			target: $('#add_' + target + '_id').val(),
			action: 'cbp_' + type + '_to' + target,
			checked: $('.item :checked').serialize(),
			_ajax_nonce: cbpressjs.salt
		    };
		    msg = 'items added to ' + target;
		    if (params.target == '') return alert('please select a ' + target);
		    if (params.checked.length > 0) {
			if (confirm($.CBP.vars.are_you_sure)) {
			    $('#loading').show();
			    $("#addresult").html('please wait...');
			    $.get(cbpressjs.ajaxurl, params, $.CBP.reloader(msg));
			}
		    } else {
			alert($.CBP.vars.none_selected);
		    }
		    return false;
		},
		toggle_me: function (action, id) {
		    params = {};
		    params.action = action;
		    params.id = id;
		    params.r = $.CBP.newreq();
		    params.nonce = $.CBP.vars.nonce;
		    $.post(
		    ajaxurl,
		    params,

		    function (r) {
			// $('#debug').html(r);
		    });
		},
		init_results: function () {
		    params = {};
		    // toggle join or product
		    $('.cbp_toggle_prod').click(function (event) {
			$.CBP.toggle_me('cbp_toggle_prod', $(this).attr('id'));
			event.preventDefault();
			event.stopPropagation();
			return false;
		    });
		    $('a.select-all').unbind('click').click(function () {
			return $.CBP.select_all();
		    });
		    $('input.add-all-list').unbind('click').click(function () {
			return $.CBP.items_to('prod', 'list');
		    });
		    $('input.add-all-cat').unbind('click').click(function () {
			return $.CBP.items_to('prod', 'cat');
		    });
		    $('a.hop-visit').click(

		    function () {
			var $link = $(this);
			var emDiv = $('#dialog');
			emDiv.addClass('loaded').dialog({
			    autoOpen: false,
			    modal: true,
			    resizable: true,
			    draggable: true,
			    closeOnEscape: true,
			    height: 600,
			    width: 900,
			    hide: 'slow',
			    dialogClass: 'ui-widget ui-widget-content ui-corner-all',
			    title: $link.attr('title')
			});
			$('#dialog').empty();
			$('#dialog').dialog('open').html('<iframe id="dialogFrame" width="100%" height="100%" marginWidth="0" marginHeight="0" frameBorder="0" scrolling="auto" src="' + $link.attr('href') + '" />');
			return false;
		    });
		},
		sort_order_save: function ($list_id) {
		    $('#loading').show();
		    items = $('#sortable').sortable('serialize');
		    // alert($list_id);
		    // alert(items);
		    // return false;
		    // $.post(cbpressjs.ax, params, reloader);
		    $.post(
		    cbpressjs.ax, {
			action: 'cbp_save_listorder',
			list_id: $list_id,
			_ajax_nonce: $.CBP.vars.nonce,
			items: items
		    },

		    function () {
			$('#loading').hide();
			$('#toggle_sort_off').hide();
			$('#toggle_sort_on').show();
			$('#sortable').sortable('disable');
			$('#sortable li').removeClass('sortable');
		    });
		    return false;
		},
		sort_order: function () {
		    $("#sortable").sortable({
			placeholder: "ui-state-highlight"
		    });
		    $("#sortable").disableSelection();
		    $('#toggle_sort_on').hide();
		    $('#toggle_sort_off').show();
		    // $( '#items li' ).addClass( 'sortable' );
		    return false;
		},
		init_sorting: function () {
		    $('a.sort-on').unbind('click').click(function () {
			return $.CBP.sort_order();
		    });
		    $('a.sort-save').unbind('click').click(function () {
			return $.CBP.sort_order_save($.CBP.getid(this));
		    });
		},
		init_autocomplete: function () {
		    var baseu1 = cbpressjs.ajaxurl + "?action=cbp_get_hops&";
		    var baseu2 = cbpressjs.ajaxurl + "?action=cbp_get_lids&";
		    var limit = 30;
		    if (1 == 1) {
			// var ajaxid = new Date().getTime()+""+1;
			// dataType: 'json',
			$(".vendorSuggest").autocomplete(baseu1 + 'ajaxid=' + new Date().getTime() + "" + 1 + '&', {
			    matchContains: false,
			    minChars: 0,
			    formatItem: function (row) {
				str1 = row[0];
				return str1.toUpperCase() + " (<strong>" + row[1] + "</strong>)";
			    },
			    formatResult: function (row) {
				return row[0];
			    },
			    width: 360,
			    max: limit
			});
			$(".lidSuggest").autocomplete(baseu2 + 'ajaxid=' + new Date().getTime() + "" + 1 + '&', {
			    matchContains: false,
			    minChars: 0,
			    formatItem: function (row) {
				str1 = row[0];
				return str1.toUpperCase() + " (<strong>" + row[1] + "</strong>)";
			    },
			    formatResult: function (row) {
				return row[0];
			    },
			    width: 360,
			    max: limit
			});
			$(".vendorList").autocomplete(baseu1 + 'ajaxid=' + new Date().getTime() + "" + 1 + '&', {
			    matchContains: false,
			    minChars: 0,
			    formatItem: function (row) {
				str1 = row[0];
				return str1.toUpperCase() + " (<strong>" + row[1] + "</strong>)";
			    },
			    formatResult: function (row) {
				return row[0];
			    },
			    width: 360,
			    max: limit
			});
			var formatItem = function (row) {
			    str1 = row[0];
			    return str1.toUpperCase() + " (<strong>" + row[1] + "</strong>)";
			};
			var formatResult = function (row) {
			    return row[0].replace(/(<.+?>)/gi, '');
			};
			$(".select-vendor").autocomplete(baseu1, {
			    matchContains: true,
			    minChars: 0,
			    formatItem: formatItem,
			    formatResult: formatResult,
			    width: 300,
			    max: limit
			});
		    }
		},
		init_lists: function () {
		    $('a.editlist').click(function (event) {
			id = $.CBP.getid(this);
			id = id.replace('cbpop', '');
			$.CBP.list_edit_win(id);
			event.preventDefault();
		    });
		},
		savelist: function (params, id) {
		    u = cbpressjs.ax + '&_random=' + $.CBP.newreq() + '&action=cbp_save_list';
		    $.post(
		    u, params, function (data) {
			if (data.err) {
			    $("#error_message_" + id).html(data.msg);
			    return false;
			} else {
			    $.CBP.reloader('List Saved');
			    $("#editwin").html('List Saved');
			}
		    }, 'json');
		},
		list_edit_win: function (id) {
		    sburi = cbpressjs.ax + '&_random=' + $.CBP.newreq() + '&action=cbp_list_form&list_id=' + id;
		    $('#editwin').html('');
		    $('#editform').remove();
		    $("<div/>").attr("id", "editform").attr("style", "max-height: 500px;").appendTo("#editwin");
		    title = (id > 0) ? 'Edit List ' + id : 'Add New List';
		    $('#editform').load(sburi, null, function () {
			$('#editwin').dialog({
			    modal: false,
			    title: title,
			    buttons: {
				"Save": function () {
				    params = $(this).find("input");
				    $.CBP.savelist(params, id);
				},
				"Cancel": function () {
				    $(this).dialog("close");
				}
			    }
			});
		    });
		    $("#editwin").dialog("open");
		},
		init_cats: function () {
		    $('a.editcat').click(function (event) {
			$.CBP.cat_edit_win($.CBP.getid(this));
			event.preventDefault();
		    });
		},
		savecat: function (params, cid) {
		    u = cbpressjs.ax + '&_random=' + $.CBP.newreq() + '&action=cbp_save_cat';
		    $.post(
		    u, params, function (data) {
			if (data.err) {
			    $("#error_message_" + cid).html(data.msg);
			    return false;
			} else {
			    $.CBP.reloader('Category Saved');
			    $("#editwin").html('Category Saved');
			}
		    }, 'json');
		},
		cat_edit_win: function (id) {
		    sburi = cbpressjs.ax + '&_random=' + $.CBP.newreq() + '&action=cbp_cat_form&cid=' + id;
		    $('#editwin').html('');
		    $('#editform').remove();
		    $("<div/>").attr("id", "editform").attr("style", "max-height: 500px;").appendTo("#editwin");
		    $('#editform').load(sburi, null, function () {
			$('#editwin').dialog({
			    modal: false,
			    title: 'Edit Category',
			    buttons: {
				"Save": function () {
				    params = $(this).find("input");
				    $.CBP.savecat(params, id);
				},
				"Cancel": function () {
				    $(this).dialog("close");
				}
			    }
			});
		    });
		    $("#editwin").dialog("open");
		},
		/*** Product, dynatree ***/
		init_product: function () {
		    $("#product_cats").dynatree({
			title: "Categories",
			imagePath: cbpressjs.imageurl,
			checkbox: true,
			selectMode: 3,
			initAjax: {
			    url: cbpressjs.ajaxurl,
			    data: {
				action: "cbp_prod_tree",
				nonce: cbpressjs.nonce,
				lid: cbpressjs.lid,
				r: new Date().getTime() + "" + 1
			    }
			},
			updatePage: function (node) {
			    var selNodes = node.tree.getSelectedNodes();
			    var selKeys = $.map(selNodes, function (node) {
				return node.data.key;
			    });
			    $("#product_cats_count").text(selKeys.length);
			    $("#product_cid_list").val(selKeys.join(","));
			},
			onSelect: function (select, node) {
			    // Display list of selected nodes					
			    var selNodes = node.tree.getSelectedNodes();
			    var selNames = $.map(selNodes, function (node) {
				return '<li>' + node.data.tooltip + '</li>';
			    });
			    var selKeys = $.map(selNodes, function (node) {
				return node.data.key;
			    });
			    $("#product_cats_count").text(selKeys.length);
			    $("#product_cid_list").val(selKeys.join(","));
			},
			onActivate: function (node) {
			    var selNodes = node.tree.getSelectedNodes();
			    var selKeys = $.map(selNodes, function (node) {
				return node.data.key;
			    });
			    $("#echoAct").text(node.data.key);
			    $("#product_cid_list").val(selKeys.join(","));
			},
			onDeactivate: function (node) {
			    $("#echoAct").text("-");
			},
			onDblClick: function (node, event) {
			    logMsg("onDblClick(%o, %o)", node, event);
			    node.toggleExpand();
			}
		    });
		    $("#product_form").submit(function () {
			msg = '';
			if ($("#prod_source").val() == 'custom') {
			    if ($("#prod_redirect_url").val() == '') {
				msg = 'Please enter the LINK to this product';
			    }
			}
			if ($("#prod_title").val() == '') {
			    msg = 'Please enter a title for this product';
			}
			if (msg != '') {
			    msg = '<div class="cb-message"><div class="cb-error"><strong><p>' + msg + '</p></strong></div></div>';
			    $("#prod_error").html(msg);
			    return false;
			}
			return true;
		    });
		},
		vars: {
		    nonce: cbpressjs.nonce,
		    // ajaxurl: cbpressjs.ajaxurl,
		    are_you_sure: 'Are you sure?',
		    none_selected: 'No items were selected',
		    page: 0
		}
	    };
	    var myvars = $.CBP.vars;
	    $.CBP.init();
	    if (cbpressjs.ispp == 1) {
		$.CBP.init_placeholders();
		$.CBP.init_searchpanel(); // header search
		$.CBP.init_tabs(); // help tabs		
		$.CBP.init_cluetips();
		$("#cbpsummaryLink").click(function () {
		    $.CBP.dialogwin('#cbpsummary', "Product Database Summary", 'auto', 'auto', 'Close');
		});
		/**** toggle for cat help postbox, etc ****/
		$("div.toggle_content").hide();
		$("div.toggle").click(function () {
		    $(this).toggleClass('toggleoff');
		    $(this).next('div.toggle_content').toggle(250);
		});
	    }
	    if (cbpressjs.page == 'help') {
		$.CBP.init_help();
	    }
	    if (cbpressjs.page == 'cats') {
		$.CBP.init_cats();
	    }
	    if (cbpressjs.page == 'lists' || cbpressjs.editing == 12) {
		$.CBP.init_lists();
		$.CBP.init_sorting();
		$.CBP.init_autocomplete();
	    }
	    if (cbpressjs.page == 'settings') {}
	    if (cbpressjs.page == 'product') {
		$.CBP.init_results();
		$.CBP.init_product();
	    }
	    if (cbpressjs.page == 'import') {
		$.CBP.init_import();
	    }
	    if (cbpressjs.page == 'products') {
		$.CBP.init_results();
		$.CBP.init_product();
	    }
	    if (cbpressjs.page == 'setup') {
		$.CBP.init_setup();
	    }
	}
	// HOTFIX: We can't upgrade to jQuery UI 1.8.6 (yet)
	// This hotfix makes older versions of jQuery UI drag-and-drop work in IE9
	(function ($) {
	    var a = $.ui.mouse.prototype._mouseMove;
	    $.ui.mouse.prototype._mouseMove = function (b) {
		if ($.browser.msie && document.documentMode >= 9) {
		    b.button = 1
		};
		a.apply(this, [b]);
	    }
	}(jQuery));
	jQuery(document).ready(function () {
	    var $ = jQuery;
	    load_cbpress();
	});

	function hopshield(m) {
	    aff = cbpressjs.aff;
	    window.open('http://www.clickbank.com/info/jmap.htm?affiliate=' + aff + '&vendor=' + m, 'link', 'resizable,width=750,height=300');
	}

	function clearInput(i) {
	    if (i.value == i.defaultValue) {
		i.value = '';
	    }
	}

	function restoreInput(i) {
	    if (i.value == '') {
		i.value = i.defaultValue;
	    }
	}
	(function ($) {
	    function reqid() {
		return new Date().getTime() + "" + 1;
	    }

	    function getid(e) {
		return jQuery(e).attr('id');
	    }
	    CBP_admin = function (args) {
		var opts = $.extend({
		    scripts: cbpressjs.base + 'js/',
		    ajaxurl: cbpressjs.ax,
		    nonce: ''
		}, args);
		var params = {
		    'action': '',
		    'randval': reqid()
		};

		function reqid() {
		    return new Date().getTime() + "" + 1;
		}

		function report() {
		    $("#report tr#parent").addClass("odd");
		    $("#report tr#child:not(.odd)").hide();
		    $("#report tr:first-child").show();
		    $("#report tr#parent.odd").click(function () {
			$(this).next("tr#child").toggle();
			$(this).find(".arrow").toggleClass("up");
		    });
		    $("#report tr.expand").show();
		}

		function admin() {
		    report();
		}
		var api = {
		    admin: admin,
		    reqid: reqid
		};
		return api;
	    };
	    CBpress = function (args) {
		var opts = $.extend({
		    ajaxurl: '',
		    nonce: '',
		    are_you_sure: 'Are you sure?',
		    none_selected: 'No items were selected',
		    page: 0
		}, args);
		var reloader = function () {
		    window.location.reload();
		};

		function do_items(type, command) {
		    var checked = $('.item :checked');
		    alert('ddddd');
		    if (checked.length > 0) {
			if (confirm(opts.are_you_sure)) {
			    params = {
				action: 'cbp_' + type + '_' + command,
				checked: checked.serialize(),
				_ajax_nonce: opts.nonce
			    };
			    $('#loading').show();
			    $.post(opts.ajaxurl, params, reloader);
			}
		    } else {
			alert(opts.none_selected);
		    }
		    return false;
		}

		function sort_order() {
		    $('#items').sortable();
		    $('#toggle_sort_on').hide();
		    $('#toggle_sort_off').show();
		    $('#items li').addClass('sortable');
		    return false;
		}

		function cbp_items_to(type, target) { // cat or list
		    params = {};
		    if (target != '') {
			params.target = $('#add_' + target + '_id').val();
			params.action = 'cbp_' + type + '_to_' + target;
			params.checked = $('.item :checked').serialize();
			params._ajax_nonce = opts.nonce;
		    }
		    if (params.target == '') {
			return alert('please select a ' + target);
		    }
		    if (params.checked.length > 0) {
			if (confirm(opts.are_you_sure)) {
			    $('#loading').show();
			    $.post(opts.ajaxurl, params, reloader);
			}
		    } else {
			alert(opts.none_selected);
		    }
		    return false;
		}

		function select_all() {
		    $('.item :checkbox').each(function () {
			this.checked = (this.checked ? '' : 'checked');
		    });
		    return false;
		}

		function edit_items(type) {
		    $('a.select-all').unbind('click').click(function () {
			return select_all();
		    });
		    $('a.toggle-all').unbind('click').click(function () {
			return do_items(type, 'toggle');
		    });
		    $('a.reset-all').unbind('click').click(function () {
			return do_items(type, 'reset');
		    });
		    $('input.move-all').unbind('click').click(function () {
			return move_all(type);
		    });
		}
		var api = {
		    edit_items: edit_items
		};
		return api;
	    }
	})(jQuery);
	var cbp_admin;
	var CBpress;
	(function ($) {
	    $(document).ready(function () {
		cbp_admin = new CBP_admin();
		cbp_admin.admin();
		$("a[id^=show_]").click(function (event) {
		    $("#extra_" + $(this).attr('id').substr(5)).slideToggle("slow");
		    event.preventDefault();
		});
	    });
	    jQuery.fn.jExpand = function () {
		var element = this;
		jQuery(element).find("tr:odd").addClass("odd");
		jQuery(element).find("tr:not(.odd)").hide();
		jQuery(element).find("tr:first-child").show();
		jQuery(element).find("tr.odd").click(function () {
		    jQuery(this).next("tr").toggle();
		});
	    }
	})(jQuery);
	// jQuery.noConflict();