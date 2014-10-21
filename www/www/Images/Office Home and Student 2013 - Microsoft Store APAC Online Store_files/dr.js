/*IntitialInventoryCheck.js*/
$(function(){
    var InventoryCheckPID = '';
    if($('input[name=productID]').length && !$('.product-colors li a').length && !$('.surface-family li a').length){
        InventoryCheckPID = $('input[name=productID]').val();
        if($('input[name=productID]').attr('data-deliverymethod') === 'physical' && $('input[name=productID]').attr('button-override') === 'false'){
            var initialSelectedIndex = $('.option-list:not(".definingLevelTwo,.userSelection") li a,.size-list li a').parent('li').index($('.product-data-container li.active'));
            if(initialSelectedIndex != -1){
                if(initialSelectedIndex == 0){
                    InventoryCheck(InventoryCheckPID);
                }
            }else{
                InventoryCheck(InventoryCheckPID);
            }
        }
    } else if($('.product-colors li a').length){
        InventoryCheckPID = $('.product-colors li a').eq(0).attr('var-pid');
        if($('.product-colors').siblings('input[name=productID]').attr('button-override') === 'false'){
            var initialSelectedIndex = $('.product-colors li a').parent('li.selected').index();
            if(initialSelectedIndex != -1){
                if(initialSelectedIndex == 0){
                    InventoryCheck(InventoryCheckPID);
                }
            }else{
                InventoryCheck(InventoryCheckPID);
            }
        }
    } else if($('.surface-family li a').length){
        InventoryCheckPID = $('.surface-family li a').eq(0).attr('var-pid');
        if($('.surface-family').siblings('input[name=productID]').attr('button-override') === 'false'){
            InventoryCheck(InventoryCheckPID);
        }
    } else if($('.product-family').length){
        InventoryCheckPID = $('.product-family').find('option:eq(0)').val();
        if($('.product-family').find('option:eq(0)').attr('data-deliverymethod') === 'physical' && $('.product-family').find('option:eq(0)').attr('button-override') === 'false'){
            InventoryCheck(InventoryCheckPID);
        }
    }
    if($('.product-additional-info-main').length > 0 && InventoryCheckPID > 0) {
        $('.product-additional-info-main').attr('default-vid', InventoryCheckPID);
    }
});
/*contentSwitching.js*/
$(function(){
    'use strict';
    var selectedIndex = 0,
        heroImages = $(".product-hero"),
        thumbImages = $(".product-thumbnails"),
        buyBoxHeading = $('.buy-box .buy-box-heading'),
        varPrice = $('.buy-box .price'),
        varPriceLink = $('.buy-box .price.price-link'),
        varBuyButton = $('.buy-box .btnSubmitSpinContainer'),
        varTabs = $('.tabify'),
        varNavWrap = $('.nav-wrap, .nav-wrap-slider'),
    //new items for responsive PDP
        $descBlockDesktop = $('.description-desktop'),
        $descBlockMobile = $('.description-mobile'),
        $priceBlock = $('.buy-box .price-block'),
        $titleBlock = $('.title-block'),
        $titleDesktop = $('.title-desktop'),
        $titleMobile = $('.title-mobile'),
        $sliderTabs = $('.media-container .slider-tabs'),
        switchingArray = new Array(heroImages, thumbImages, buyBoxHeading, varPrice, varPriceLink, varTabs, varNavWrap, varBuyButton, $descBlockDesktop, $descBlockMobile, $priceBlock, $titleDesktop, $titleMobile, $sliderTabs),
        colorSwatch = $('.product-colors li a'),
        variationSelector = $('.product-family'),
        surfaceSelector = $('.surface-family li a'),
        boxSelector = $('.option-list:not(".definingLevelTwo,.userSelection") li a,.size-list li a');

    if(variationSelector.length){
        var initialSelectedIndex = variationSelector.find('option:selected').index();
        updateCustomBundle(variationSelector.find('option').filter(':selected').val());
        updateAddons(variationSelector.find('option').filter(':selected').val());
        if(initialSelectedIndex !== 0){
            variationSwitch();
        }
    }
    variationSelector.change(function(e){
        $(".product-hero .video-container:visible").pauseVideo();
        var selectedVariationID = $(".variations option:selected").attr("value");
        $(".pd-bottom-banner .varOfferDiv").addClass("hide-option");
        $(".pd-bottom-banner .varOfferDiv[data-pid='"+selectedVariationID+"']").removeClass("hide-option");
        variationSwitch();
    });

    if(colorSwatch.length){
        var initialSelectedIndex = colorSwatch.parent('li.selected').index();
        updateCustomBundle($('.product-colors li.selected a').attr('var-pid'));
        updateAddons($('.product-colors li.selected a').attr('var-pid'));
        if(initialSelectedIndex !== 0){
            colorSwitch(colorSwatch.filter(':eq('+initialSelectedIndex+')'));
        } else{
            colorSwitchChangeLabel();
        }
    }
    colorSwatch.click(function(){
        $(".product-hero .video-container:visible").pauseVideo();
        var selectedVariationID = $('.product-colors li.selected a').attr('var-pid');
        $(".pd-bottom-banner .varOfferDiv").addClass("hide-option");
        $(".pd-bottom-banner .varOfferDiv[data-pid='"+selectedVariationID+"']").removeClass("hide-option");
        colorSwitch($(this));
    });

    if(surfaceSelector.length){
        var initialSelectedIndex = surfaceSelector.parent('li').index($('.product-data-container li.active'));
        updateCustomBundle($('.surface-family li.active').attr('data-value'));
        updateAddons($('.surface-family li.active').attr('data-value'));
        if(initialSelectedIndex !== 0){
            surfaceSwitch(surfaceSelector.filter(':eq('+initialSelectedIndex+')'));
        }
    }
    surfaceSelector.click(function(){
        $(".product-hero .video-container:visible").pauseVideo();
        var selectedVariationID = $('.surface-family li.selected a').attr('var-pid');
        $(".pd-bottom-banner .varOfferDiv").addClass("hide-option");
        $(".pd-bottom-banner .varOfferDiv[data-pid='"+selectedVariationID+"']").removeClass("hide-option");
        surfaceSwitch($(this));
    });

    if(boxSelector.length){
        var initialSelectedIndex = boxSelector.parent('li').index($('.product-data-container li.active'));
        updateCustomBundle($('.option-list:not(".definingLevelTwo,.userSelection") li.active,.size-list li.active').attr('data-pid'));
        updateAddons($('.option-list:not(".definingLevelTwo,.userSelection") li.active,.size-list li.active').attr('data-pid'));
        if(initialSelectedIndex !== 0){
            boxSwitch(boxSelector.filter(':eq('+initialSelectedIndex+')'));
        } else{
            boxSwitchChangeLabel();
        }
    }
    boxSelector.click(function(){
        $(".product-hero .video-container:visible").pauseVideo();
        var selectedVariationID = $('.option-list:not(".definingLevelTwo,.userSelection") li.active,.size-list li.active').attr('data-pid');
        $(".pd-bottom-banner .varOfferDiv").addClass("hide-option");
        $(".pd-bottom-banner .varOfferDiv[data-pid='"+selectedVariationID+"']").removeClass("hide-option");
        boxSwitch($(this));
    });

    function colorSwitchChangeLabel(){
        colorSwatch.closest('ul').prevAll('p:eq(0)').find('.selected-variation').text($.trim(colorSwatch.parent().filter('.selected').find('a').attr('title')));
    }

    function colorSwitch(obj){
        var $this = obj,
            index = $this.parent('li').index();
        if(selectedIndex === index){
            colorSwitchChangeLabel();
            return;
        }
        for(var i = 0; i < switchingArray.length; i++){
            if($(switchingArray[i]).length > 1){
                var oldItem = $(switchingArray[i][selectedIndex]);
                var newItem = $(switchingArray[i][index]);
                if(oldItem.is(':visible')){
                    oldItem.fadeOut(function(){ $(this).css('display',''); }).addClass("hide-option");
                    newItem.fadeIn(function(){ $(this).css('display',''); }).removeClass("hide-option");
                }else{
                    oldItem.css('display','').addClass("hide-option");
                    newItem.css('display','').removeClass("hide-option");
                }
            }
        }
        colorSwatch.parent().removeClass("selected");
        $this.parent().addClass("selected");
        if($this.parent().hasClass('selected')){
            $('input[name="productID"]').attr('value', $this.attr('var-pid'));
        }
        $('.product-colors').siblings('input[name=productID]').attr('button-override', $('.buy-box .btnSubmitSpinContainer').eq(index).attr('var-button-override'));
        if($('.product-colors').siblings('input[name=productID]').attr('button-override') === 'false'){
            InventoryCheck($this.attr('var-pid'));
        }
        $('.product-additional-info-main div.product-additional-info.variation').hide();
        $(".product-additional-info-main div.product-additional-info.variation[id='ms_PDP_Additional_Info_" + $('.product-colors').siblings('input[name=productID]').attr('value') + "']").show();
        var isResponsive = $('#body').parent().hasClass('rwd') ? true : false;
        if (!isResponsive) {
            var sliderControl = new SliderControl({
                'slides': $('.tabify:visible .slider-content > section')
            });
            $('.tabify:visible .slider-tabs a:eq(0)').click();
        }
        selectedIndex = index;
        $(window).trigger('variation-change'); /* Microsoft variation trigger callback */
        updateCustomBundle($('.product-colors li.selected a').attr('var-pid'));
        updateAddons($('.product-colors li.selected a').attr('var-pid'));
        colorSwitchChangeLabel();
    }

    function surfaceSwitch(obj){
        var $this = obj,
            index = $this.parent('li').index();
        if(selectedIndex === index){
            return;
        }
        for(var i = 0; i < switchingArray.length; i++){
            if($(switchingArray[i]).length > 1){
                var oldItem = $(switchingArray[i][selectedIndex]);
                var newItem = $(switchingArray[i][index]);
                if(oldItem.is(':visible')){
                    oldItem.fadeOut(function(){ $(this).css('display',''); }).addClass("hide-option");
                    newItem.fadeIn(function(){ $(this).css('display',''); }).removeClass("hide-option");
                }else{
                    oldItem.css('display','').addClass("hide-option");
                    newItem.css('display','').removeClass("hide-option");
                }
            }
        }
        surfaceSelector.parent().removeClass("active");
        $this.parent().addClass("active");
        if($this.parent().hasClass('active')){
            if ($this.parent().attr('data-purchaseable') == "false") {
                $('.btnSubmitSpinContainer:visible').hide();
            } else {
                $('.btnSubmitSpinContainer:not(:visible)').show();
            }
            $('input[name="productID"]').attr('value', $this.parent().attr('data-value'));
        }
        $('.surface-family').siblings('input[name=productID]').attr('button-override', $('.buy-box .btnSubmitSpinContainer').eq(index).attr('var-button-override'));
        if($('.surface-family').siblings('input[name=productID]').attr('button-override') === 'false'){
            InventoryCheck($this.parent().attr('data-value'));
        }
        $('.product-additional-info-main div.product-additional-info.variation').hide();
        $(".product-additional-info-main div.product-additional-info.variation[id='ms_PDP_Additional_Info_" + $('.surface-family').siblings('input[name=productID]').attr('value') + "']").show();
        selectedIndex = index;
        $(window).trigger('variation-change'); /* Microsoft variation trigger callback */
        updateCustomBundle($('.surface-family li.active').attr('data-value'));
        updateAddons($('.surface-family li.active').attr('data-value'));
    }

    function boxSwitchChangeLabel(){
        boxSelector.closest('ul').prevAll('p:eq(0)').find('.selected-variation').text($.trim(boxSelector.parent().filter('.active').find('a').text()));
    }

    function boxSwitch(obj){
        var $this = obj,
            index = $this.parent('li').index();
        if(selectedIndex === index){
            boxSwitchChangeLabel();
            return;
        }
        for(var i = 0; i < switchingArray.length; i++){
            if($(switchingArray[i]).length > 1){
                var oldItem = $(switchingArray[i][selectedIndex]);
                var newItem = $(switchingArray[i][index]);
                if(oldItem.is(':visible')){
                    oldItem.fadeOut(function(){ $(this).css('display',''); }).addClass("hide-option");
                    newItem.fadeIn(function(){ $(this).css('display',''); }).removeClass("hide-option");
                }else{
                    oldItem.css('display','').addClass("hide-option");
                    newItem.css('display','').removeClass("hide-option");
                }
            }
        }
        boxSelector.parent().removeClass("active");
        $this.parent().addClass("active");
        if($this.parent().hasClass('active')){
            if ($this.parent().attr('data-purchaseable') == "false") {
                $('.btnSubmitSpinContainer:visible').hide();
            } else {
                $('.btnSubmitSpinContainer:not(:visible)').show();
            }
            $('input[name="productID"]').attr('value', $this.parent().attr('data-pid'));
        }
        $('.option-list:not(".definingLevelTwo,.userSelection"),.size-list').siblings('input[name=productID]').attr('button-override', $('.buy-box .btnSubmitSpinContainer').eq(index).attr('var-button-override'));
        if($this.parent().attr('data-deliverymethod') != 'download' && $('.option-list:not(".definingLevelTwo,.userSelection"),.size-list').siblings('input[name=productID]').attr('button-override') === 'false'){
            InventoryCheck($this.parent().attr('data-pid'));
        }
        $('.product-additional-info-main div.product-additional-info.variation').hide();
        $(".product-additional-info-main div.product-additional-info.variation[id='ms_PDP_Additional_Info_" + $this.parent().attr('data-pid') + "']").show();
        selectedIndex = index;
        $(window).trigger('variation-change'); /* Microsoft variation trigger callback */
        updateCustomBundle($('.option-list:not(".definingLevelTwo,.userSelection") li.active,.size-list li.active').attr('data-pid'));
        updateAddons($('.option-list:not(".definingLevelTwo,.userSelection") li.active,.size-list li.active').attr('data-pid'));
        boxSwitchChangeLabel();
    }

    function pauseAllVideos(){
        var mediaContainer = $('.media-container');
        mediaContainer.find(".product-hero .video-container").pauseVideo();
        mediaContainer.find(".youtube-container").stopYoutubeVideo();
    }

    function variationSwitch(){
        var $this = variationSelector,
            index = $this.find('option').filter(':selected').index();
        if(selectedIndex === index){
            return;
        }
        pauseAllVideos();
        for(var i = 0; i < switchingArray.length; i++){
            if($(switchingArray[i]).length > 1){
                var oldItem = $(switchingArray[i][selectedIndex]);
                var newItem = $(switchingArray[i][index]);
                if(oldItem.is(':visible')){
                    oldItem.fadeOut(function(){ $(this).css('display',''); }).addClass("hide-option");
                    newItem.fadeIn(function(){ $(this).css('display',''); }).removeClass("hide-option");
                }else{
                    oldItem.css('display','').addClass("hide-option");
                    newItem.css('display','').removeClass("hide-option");
                }
            }
        }
        if($this.find('option').filter(':selected').attr('data-deliverymethod') === 'physical' && $this.find('option').filter(':selected').attr('button-override') === 'false'){
            InventoryCheck($this.find('option').filter(':selected').val());
        }
        $('.product-additional-info-main div.product-additional-info.variation').hide();
        $(".product-additional-info-main div.product-additional-info.variation[id='ms_PDP_Additional_Info_" + $this.find('option').filter(':selected').val() + "']").show();
        var isResponsive = $('#body').parent().hasClass('rwd') ? true : false;
        if (!isResponsive) {
            var sliderControl = new SliderControl({
                'slides': $('.tabify:visible .slider-content > section')
            });
            $('.tabify:visible .slider-tabs a:eq(0)').click();
        }
        selectedIndex = index;
        $(window).trigger('variation-change'); /* Microsoft variation trigger callback */
        updateCustomBundle($this.find('option').filter(':selected').val());
        updateAddons($this.find('option').filter(':selected').val());
    }
    function updateCustomBundle(vid){
        $('#dr_customBundles a').each(function(){
            var url = $(this).attr('href');
            if(url.indexOf('bundlePID') > -1) {
                var oldPID = url.substr(url.indexOf('bundlePID') + 10);
                $(this).attr('href',url.replace(oldPID,vid));
            }
        });
    }
    function updateAddons(vid){
        $('div.addon.variation').each(function(){
            var variationID = $(this).attr('data-vid');
            if(variationID != vid) {
                $(this).hide();
            }
            else {
                $(this).show();
            }
        });
    }
});

/* Real-time Inventory Check */
function InventoryCheck(pid){
    var $buttonContainer = $('.btnSubmitSpinContainer:not(.hide-option)').length ? $('.btnSubmitSpinContainer:not(.hide-option)') : $('.pdp-cta');
    $.ajax({
        url: "/store/" + inputVariables.storeData.page.siteid + "/" + inputVariables.storeData.page.locale + "/DisplayPage/id.ProductInventoryStatusXmlPage/productID." + pid,
        beforeSend: function(){
            $('.buySpan_AddtoCart', $buttonContainer).hide();
            $('#load_image', $buttonContainer).show();
        },
        cache: false
    }).done(function(xmlData){
        var $xml = $(xmlData),
            $inventoryStatus = $xml.find("inventoryStatus");
        $oosMessageOverrideStatus = $(".buy-box span.buyBtn_oosOverride");
        if($inventoryStatus.text() === 'PRODUCT_INVENTORY_OUT_OF_STOCK' && $oosMessageOverrideStatus.length < 1){
            $('#dr_customBundles').addClass('hide');
            if ($('.overrideButton', $buttonContainer).length < 1) {
                $('.buyBtn_AddtoCart', $buttonContainer).hide();
                if($('.buyBtn_outOfStock', $buttonContainer).length < 1){
                    $('.buySpan_AddtoCart', $buttonContainer).prepend('<input type="submit" class="button buyBtn_outOfStock box" title="' + inputVariables.storeData.resources.text.OUT_OF_STOCK + '" value="' + inputVariables.storeData.resources.text.OUT_OF_STOCK + '" disabled="disabled" />');
                }
            }
            $('#load_image', $buttonContainer).hide();
            $('.buySpan_AddtoCart', $buttonContainer).show();
        }else if($inventoryStatus.text() === 'PRODUCT_INVENTORY_OUT_OF_STOCK' && $oosMessageOverrideStatus.length){
            $('#dr_customBundles').addClass('hide');
            $('.buyBtn_AddtoCart', $buttonContainer).hide();
            $('.buySpan_AddtoCart', $buttonContainer).html($oosMessageOverrideStatus.html());
            $('#load_image', $buttonContainer).hide();
            $('.buySpan_AddtoCart', $buttonContainer).show();
        }else{
            $('#dr_customBundles').removeClass('hide');
            $('.buyBtn_outOfStock', $buttonContainer).remove();
            $('.buyBtn_AddtoCart', $buttonContainer).show();
            $('#load_image', $buttonContainer).hide();
            $('.buySpan_AddtoCart', $buttonContainer).show();
        }
    });
}

/* Quickview.js */
$(function(){
    $('form[name=CrossSellForm] .childProductImage,form[name=ProductPickerForm] .childProductImage').on('mouseover',function(e){
        var $qvButton = $(this).siblings('.quickViewMedium');
        $qvButton.css({
            'margin-left': ($(this).parents('.grid-unit').width() - $qvButton.outerWidth()) / 2 + 'px',
            'margin-top': -(($(this).outerHeight() - $qvButton.outerHeight()) / 2 + $qvButton.outerHeight()) + 'px'
        });
        $qvButton.show();
        $(document).one('click', function(e){
            $qvButton.hide();
        });
    }).on('mouseout', function(e){
        $(this).siblings('.quickViewMedium').hide();
    });

    $('.quickViewMedium').on('mouseover', function(e){
        $(this).show();
    }).on('mouseout', function(e){
        $(this).hide();
    });

    $('a.qvoverlay').on('click', function(){
        var $original = $(this),
            $originalContent = $original.data('QuickViewContent');
        parentSelector = '#body',
            $overlay = $('#dr_quickviewOverlay'),
            ajaxURL = inputVariables.storeData.actionName.QuickView + '/productID.' + $(this).attr('pid');

        if($overlay.length === 0){
            $original.parents(parentSelector).before('<div id="dr_quickviewOverlay" style="display:none"></div>');
            $overlay = $('#dr_quickviewOverlay');
        }
        if($overlay.parents(parentSelector) !== $original.parents(parentSelector)){
            $overlay.detach();
            $original.parents(parentSelector).before($overlay);
        }

        $overlay.hide();
        $overlay.css('left', ($(window).width() - $overlay.outerWidth()) / 2);
        $overlay.css('top', ($(window).height() - $overlay.outerHeight()) / 2);

        $overlay.html('<img id="quickviewLoaderImage" class="qvLoader" src="' + inputVariables.storeData.resources.images.loader + '" alt="loader"/>').show();

        if(ajaxURL !== null){
            $.get(ajaxURL, function(data){
                $overlay.html(data);
                $original.data('QuickViewContent', data);
                $overlay.find('.dr_closeQuickView').one('click', function(e){
                    e.preventDefault();
                    $overlay.hide();
                });
            });
        }
    });
});

var cookieObj = {
    setCookie: function(c_name,c_value){
        document.cookie = c_name + "=" + escape(c_value);
    },
    setPathCookie: function(c_name,c_value){
        document.cookie = c_name + "=" + escape(c_value) + "; path=/;";
    },
    setPathDomainCookie: function(c_name,c_value){
        document.cookie = c_name + "=" + escape(c_value) + "; domain=.microsoftstore.com; path=/;";
    },
    setPathCookieWithExpiration: function(c_name,c_value,nDays){
        var today = new Date();
        var expire = new Date();
        if (nDays==null || nDays==0) nDays=1;
        expire.setTime(today.getTime() + 3600000*24*nDays);
        document.cookie = c_name + "=" + escape(c_value) + ";expires=" + expire.toGMTString() + "; path=/";
    },
    getCookie: function(c_name){
        var c_value = document.cookie;
        var c_start = c_value.indexOf(" " + c_name + "=");
        if(c_start == -1){
            c_start = c_value.indexOf(c_name + "=");
        }
        if(c_start == -1){
            c_value = null;
        } else {
            c_start = c_value.indexOf("=", c_start) + 1;
            var c_end = c_value.indexOf(";", c_start);
            if(c_end == -1){
                c_end = c_value.length;
            }
            c_value = unescape(c_value.substring(c_start, c_end));
        }
        return c_value;
    },
    deleteCookie: function(c_name){
        document.cookie = c_name + '=; expires=Thu, 01-Jan-70 00:00:01 GMT;';
    },
    deletePathDomainCookie: function(c_name){
        document.cookie = c_name + '=; expires=Thu, 01-Jan-70 00:00:01 GMT; domain=.microsoftstore.com; path=/;';
    }
};

var queryString = {
    cleanUrl: function(Key) {
        var url = window.location.href;
        KeysValues = url.split(/[\?/]+/);
        for (i = 0; i < KeysValues.length; i++) {
            KeyValue = KeysValues[i].split(".");
            if (KeyValue[0] == Key) {
                return KeyValue[1];
            }
        }
    },
    regularUrl: function(Key) {
        var url = window.location.href;
        KeysValues = url.split(/[\?&]+/);
        for (i = 0; i < KeysValues.length; i++) {
            KeyValue = KeysValues[i].split("=");
            if (KeyValue[0] == Key) {
                return KeyValue[1];
            }
        }
    }
};

$(function(){
    $(document).on('click', '.sign-out,.bottom-sign-out,.mobile-sign-out', function(){
        cookieObj.deletePathDomainCookie('_cjd');
    });
    if(inputVariables.storeData.page.currentPageName.length > 0){
        if(inputVariables.storeData.page.currentPageName == 'ThreePgCheckoutShoppingCartPage' || inputVariables.storeData.page.currentPageName == 'ShoppingCartPage' || inputVariables.storeData.page.currentPageName == 'MobileActivationsCoveragePage'|| inputVariables.storeData.page.currentPageName == 'ProductCrossSellPage' || inputVariables.storeData.page.currentPageName == 'EditProfilePage' || inputVariables.storeData.page.currentPageName == 'AccountOrderListPage' || inputVariables.storeData.page.currentPageName == 'DownloadHistoryPage' || inputVariables.storeData.page.currentPageName == 'AddEditPaymentPage' || inputVariables.storeData.page.currentPageName == 'AccountReturnListPage' || inputVariables.storeData.page.currentPageName == 'ThreePgCheckoutAddressPaymentInfoPage'){
            if(inputVariables.storeData.page.cartJsonData){
                cookieObj.setPathDomainCookie('_cjd',JSON.stringify(inputVariables.storeData.page.cartJsonData));
            }
        }
    }
});

function updateOneSiteSignInLink() {
    var $mobileSignIn = $('#mobile-sign-in');

    if ($mobileSignIn.data('isAuthenticated') !== 'true') {
        return;
    }

    var signOutUrl = buildSignOutUrl();
    $.get(signOutUrl, function(signOutAnchorHtml) {
        var username = $mobileSignIn.data('userName');
        var hiMessage = inputVariables.storeData.resources.text.Monaco_HI_USERNAME.replace('{0}', username);
        var byeMessage = inputVariables.storeData.resources.text.Monaco_NOT_USERNAME.replace('{0}', username);
        var $signOutAnchor = $(signOutAnchorHtml);

        $('#desktop-sign-in-menu').find('.top-level-link-text').html(hiMessage);
        $mobileSignIn.find('.top-level-link-text').html(hiMessage + '<br />' + inputVariables.storeData.resources.text.Monaco_YOUR_ACCOUNT);
        $('li.signin-footer').find('.signin-button').html(byeMessage).attr('href', $signOutAnchor.attr('href')).removeClass('signin-button');
        $('li.signin-footer.signin-button-container').removeClass('signin-button-container');
    });
}

/*cartJsonData isAuthenticated function */
function updateSignInOutLink(cartJsonData){
    var $signInLink = $('li.signInOutLink,#mobile-sign-in,#desktop-sign-in-menu');
    var micrositelinks='',allmicrositelinks='';
    if(cartJsonData.isAuthenticated == "true"){
        $signInLink.data("isAuthenticated","true");
        $signInLink.data("userName",cartJsonData.userName);
        if(cartJsonData.eligibleMicroStores){
            micrositelinks = '<li class="main microsites"><a href="javascript:void(0);">' + inputVariables.storeData.resources.text.MY_MICROSOFT_STORES + '</a></li><li><a href="'+inputVariables.storeData.actionName.PublicStoreHomePage+'">' + inputVariables.storeData.resources.text.MAIN_SITE + '</a></li>';
            allmicrositelinks = '<li><a href="'+inputVariables.storeData.actionName.PublicStoreHomePage+'">' + inputVariables.storeData.resources.text.MAIN_SITE + '</a></li>';
            $.each(cartJsonData.eligibleMicroStores,function(index,jsonObject){
                if(index <=3){
                    $.each(jsonObject, function(key,val){
                        micrositelinks = micrositelinks + '<li><a href="'+inputVariables.storeData.actionName.SwitchMicrostore+'/marketID.'+val+'">'+key+'</a></li>';
                    });
                }
                $.each(jsonObject, function(key,val){
                    allmicrositelinks = allmicrositelinks + '<li><a href="'+inputVariables.storeData.actionName.SwitchMicrostore+'/marketID.'+val+'">'+key+'</a></li>';
                });
            });
            $( ".site-footer" ).append('<div id="micrositelinks" class="micrositelinks hide"><h1 class="heading--large">'+inputVariables.storeData.resources.text.SELECT_YOUR_SITE+'</h1><ul>'+allmicrositelinks+'</ul></div>');
            if(queryString.regularUrl('wa') && inputVariables.storeData.resources.text.SiteSetting_ListMicroSitesEnabledCC == "true"){
                var popuphtml=$('#micrositelinks').html();
                $.magnificPopup.open({
                    items: {
                        src: '<div class="micrositelinks">'+popuphtml+'</div>',
                        type: 'inline'
                    }
                });
                $('.mfp-content #micrositelinks').removeClass('hide');
            }
        }
    }else{
        $signInLink.data("isAuthenticated","false");
    }
    if($signInLink.is('#mobile-sign-in')){
        updateOneSiteSignInLink();
    } else {
        signInOutLink(micrositelinks);
    }
}

function getCartJsonDataAjax(){
    if(queryString.cleanUrl('mktp')){
        var marketparam = queryString.cleanUrl('mktp');
    }else{
        var marketparam = queryString.regularUrl('mktp') ? queryString.regularUrl('mktp') : inputVariables.storeData.page.mktp;
    }
    $.getJSON('/store/' + inputVariables.storeData.page.siteid + '/' + inputVariables.storeData.page.locale + '/DisplayPage/id.DRCartSummaryJSONPage/mktp.' + marketparam + '/output.json/jsonp=?', function(cartJsonData){
        cookieObj.setPathDomainCookie('_cjd',JSON.stringify(cartJsonData));
        if(cartJsonData.lineItems > 0){
            var rtlCharacter = '';
            //if($('body').hasClass('rtlanguage')) rtlCharacter = '?';
            if($('body').hasClass('rtlanguage')) rtlCharacter = '';
            $('.lineItemQuantity').text(rtlCharacter + cartJsonData.lineItems);
        }
        updateSignInOutLink(cartJsonData);
        if(cartJsonData.footerMarketAjax){
            $('.locale-selector').html('<span class="icon-globe"></span>' + cartJsonData.footerMarketAjax);
        }
    });
}

function getCartJsonDataCookie(){
    if(queryString.cleanUrl('mktp')){
        var marketparam = queryString.cleanUrl('mktp');
    }else{
        var marketparam = queryString.regularUrl('mktp') ? queryString.regularUrl('mktp') : inputVariables.storeData.page.mktp;
    }
    var cartJsonData = cookieObj.getCookie('_cjd');
    if(cartJsonData){
        cartJsonData = JSON.parse(cartJsonData);
        if(cartJsonData.mktp == marketparam){
            if(cartJsonData.lineItems > 0){
                var rtlCharacter = '';
                //if($('body').hasClass('rtlanguage')) rtlCharacter = '?';
                if($('body').hasClass('rtlanguage')) rtlCharacter = '';
                $('.lineItemQuantity').text(rtlCharacter + cartJsonData.lineItems);
            }
            updateSignInOutLink(cartJsonData);
            if(cartJsonData.footerMarketAjax){
                $('.locale-selector').html('<span class="icon-globe"></span>' + cartJsonData.footerMarketAjax);
            }
        }else{
            getCartJsonDataAjax();
        }
    }
}

/*goGreatWith.js*/
$(function(){
    "use strict"
    var addToCartLinks = $(".product-add-on .add-to-cart a");
    addToCartLinks.live('click', function(e){
        e.preventDefault();
        var container = $(this).parent();
        var index = addToCartLinks.index(this);
        var offerPID = $(this).attr('pid-ref');
        var buyurl = '/store/' + inputVariables.storeData.page.siteid + '/' + inputVariables.storeData.page.locale + '/AddItemToCartIndependent/productID.' + offerPID + '?jsonCallback=?';
        if(inputVariables.storeData.subDomain.subDomainList != 'Config_SubDomainList'){
            var subDomainList = inputVariables.storeData.subDomain.subDomainList;
            var subDomainArray = subDomainList.split(',');
            for(var i = 0; i < subDomainArray.length; i++){
                if(subDomainArray[i] == window.location.hostname){
                    buyurl = 'http://www.microsoftstore.com/store/' + inputVariables.storeData.page.siteid + '/' + inputVariables.storeData.page.locale + '/AddItemToCartIndependent/productID.' + offerPID + '?jsonCallback=?';
                }
            }
        }

        container.html('<p>' + inputVariables.storeData.resources.text.PROCESSING + '</p>');

        $.ajax({
            url: "/store/" + inputVariables.storeData.page.siteid + "/" + inputVariables.storeData.page.locale + "/DisplayPage/id.ProductInventoryStatusXmlPage/productID." + offerPID,
            cache: false
        }).done(function(xmlData){
            var $xml = $(xmlData),
                $inventoryStatus = $xml.find("inventoryStatus");
            if($inventoryStatus.text() === 'PRODUCT_INVENTORY_OUT_OF_STOCK'){
                container.html('<p>' + inputVariables.storeData.resources.text.OUT_OF_STOCK + '</p>');
                container.addClass('gray-text-color');
            } else {
                $.ajax({
                    url: buyurl,
                    dataType: 'jsonp',
                    cache: false
                }).done(function(data){
                    if($('.cart').is(':visible')){
                        $.getJSON('/store/' + inputVariables.storeData.page.siteid + '/' + inputVariables.storeData.page.locale + '/DisplayPage/id.DRCartSummaryJSONPage/output.json/jsonp=?', function(cartJsonData){
                            var cookieName = inputVariables.storeData.page.siteid + '_cart_summary';
                            cookieObj.setPathCookieWithExpiration(cookieName,cartJsonData.lineItems,365);
                            cookieObj.setPathDomainCookie('_cjd',JSON.stringify(cartJsonData));
                            if(cartJsonData.lineItems > 0){
                                var rtlCharacter = '';
                                if($('body').hasClass('rtlanguage')) rtlCharacter = '?';
                                $('.lineItemQuantity').text(rtlCharacter + cartJsonData.lineItems);
                            }
                            updateSignInOutLink(cartJsonData);
                        });
                        var checkoutButton = $('input', this);
                        checkoutButton.live('click', function(){
                            if($(this).attr('data-url')) location.href = $(this).attr('data-url');
                        });
                    }
                    container.html("<p>" + inputVariables.storeData.resources.text.ADDED_TO_CART + "</p>");
                    container.addClass('green-text-color');
                }).fail(function(jqXHR, textStatus, errorThrown){
                    container.html("<p>" + inputVariables.storeData.resources.text.UNABLE_TO_ADD_ITEM + "</p>");
                    container.addClass('gray-text-color');
                });
            }
        }).fail(function(){
            container.html("<p>" + inputVariables.storeData.resources.text.UNABLE_TO_ADD_ITEM + "</p>");
            site
            container.addClass('gray-text-color');
        });
    });
});

/*addon.js*/
$(function(){
    if($('div.addon').length > 0){
        var addon = $('div.addon');
        $('.buy-box a.buyBtn_AddtoCart').on('click', function(){
            if(!$(this).hasClass('overrideButton')){
                var parentProductID,
                    buyURL,
                    overrideBuyURL = false,
                    $buyBox = $('.buy-box');
                if($('select[name=productID]', $buyBox).length > 0){
                    parentProductID = $('select[name=productID]', $buyBox).val();
                } else {
                    parentProductID = $('input[name=productID]:eq(0)', $buyBox).val();
                }

                buyURL = 'http://www.microsoftstore.com/store/' + inputVariables.storeData.page.siteid + '/' + inputVariables.storeData.page.locale + '/buy/productID.' + parentProductID,

                    $('input[name=popularaddons]:checked', addon).each(function(){
                        if($(this).attr('bundleofferid')){
                            if($(this).parent().attr('data-vid')){
                                if($(this).parent().attr('data-vid') == parentProductID) {
                                    buyURL += '/OfferID.' + $(this).attr('bundleofferid');
                                }
                            } else {
                                buyURL += '/OfferID.' + $(this).attr('bundleofferid');
                            }
                        }
                        overrideBuyURL = true;
                    });
                if(overrideBuyURL){
                    window.location = buyURL;
                } else {
                    return true;
                }
                return false;
            }
        });
    }
});

$(function(){
    if(inputVariables.storeData.page.currentPageName.length > 0){
        var cookieName = inputVariables.storeData.page.siteid + '_cart_summary';
        if(inputVariables.storeData.page.currentPageName == 'MobileActivationsCoveragePage' || inputVariables.storeData.page.currentPageName == 'ProductCrossSellPage' || inputVariables.storeData.page.currentPageName == 'ThreePgCheckoutShoppingCartPage') {
            var cartCount = inputVariables.storeData.page.cartJsonData.lineItems;
            if(cartCount !== null){
                cookieObj.setPathCookieWithExpiration(cookieName,cartCount,365);
            }
        } else if(inputVariables.storeData.page.currentPageName == 'ThankYouPage' || inputVariables.storeData.page.currentPageName == 'YourOrderIsBeingProcessedPage'){
            cookieObj.deleteCookie(cookieName);
        }
    }
});

(function($){
    /*--- Sorting function for offers in token ---*/
    $.fn.sorting = function(){
        var $element = $(this),
            sortingArray = new Array(),
            $parent = $element.parent();

        $parent.hide();
        $element.each(function(index){
            sortingArray.push($(this).clone());
        });
        for(var i = 1; i < sortingArray.length; i++){
            var sortingAttr = parseInt(sortingArray[i].attr('sort')),
                sortingElement = sortingArray[i],
                holePos = i;

            while(holePos > 0 && sortingAttr < sortingArray[holePos - 1].attr('sort')){
                sortingArray[holePos] = sortingArray[holePos - 1];
                holePos--;
            }
            sortingArray[holePos] = sortingElement;
        }
        $parent.empty();
        for(var i = 0; i < sortingArray.length; i++){
            $parent.append(sortingArray[i].clone());
        }
        $parent.show();
    }

    /*--- Price Sorting function for filter/sort ---*/
    $.fn.priceSorting = function(order){
        var $element = $(this),
            sortingArray = new Array();

        $element.each(function(index){
            sortingArray.push($(this).clone());
        });

        for(var i = 0; i < sortingArray.length; i++){
            var sortingAttr = sortingArray[i].attr('data-sort'),
                sortingElement = sortingArray[i],
                holePos = i;

            if(order == 'ascending'){
                while(holePos > 0 && parseFloat(sortingAttr) < parseFloat(sortingArray[holePos - 1].attr('data-sort'))){
                    sortingArray[holePos] = sortingArray[holePos - 1];
                    holePos--;
                }
            } else if (order == 'descending'){
                while(holePos > 0 && parseFloat(sortingAttr) > parseFloat(sortingArray[holePos - 1].attr('data-sort'))){
                    sortingArray[holePos] = sortingArray[holePos - 1];
                    holePos--;
                }
            }
            sortingArray[holePos] = sortingElement;
        }

        return sortingArray;
    }
})($)

if(inputVariables.storeData.page.currentPageName.length > 0){
    if(inputVariables.storeData.page.currentPageName == 'ThankYouPage'){
        $(document).ready(function(){
            var imageSrc = inputVariables.storeData.resources.images.loader;
            // hide
            $('#dr_productInformation div.ty_selectBinarySetForm div.dr_downloadButtonsList').hide();
            // loading
            $('#dr_productInformation div.ty_selectBinarySetForm').append('<img id="loader" src="'+imageSrc+'"/>');
            // Set the correct download links for the selected binary set
            $('#dr_productInformation div.ty_selectBinarySetForm select.dr_selectBinarySet').change(function(e){
                var $me = $(this),
                    id = $me.attr('id'),
                    val = $me.val(),
                    selector = '#' + id + val;
                $(selector).siblings().hide();
                $(selector).fadeIn('slow');
            });
            // Load download links
            $('#dr_productInformation div.ty_selectBinarySetForm a.getDownload').each(function(index, element){
                $(this).parent().load($(this).attr('href') + '/ajax.true',function(){
                    $('#dr_productInformation div.ty_selectBinarySetForm #loader').remove();
                    $('#dr_productInformation div.ty_selectBinarySetForm select.dr_selectBinarySet').trigger('change');
                });
            });
        });
    }

    if(inputVariables.storeData.page.currentPageName == 'DownloadHistoryPage'){
        $(document).ready(function(){
            // Load download links
            $('.dr_downloadInfoRightColumn div.dr_selectBinarySetForm a.getDownload').each(function(index, element){
                $(this).parent().load($(this).attr('href') + '/ajax.true/dps.true', function(){
                    $('a', this).removeClass('orange');
                });
            });
        });
    }

    if(inputVariables.storeData.page.currentPageName == 'AccountOrderListPage'){
        $(document).ready(function(){
            var previousItemType = "", $previousItem;
            $('#dr_AccountOrderList .dr_orderInfoRightColumn .line-item').each(function(){
                if($(this).find('.child-line-item').length > 0) {
                    if(previousItemType == "individual") {
                        $previousItem.addClass("border-bottom-small row-margin-bottom-small");
                    }
                    previousItemType = "bundle";
                } else {
                    previousItemType = "individual";
                }
                $previousItem = $(this);
            });
        });
    }
}

$.getCachedScript = function(src, callback){
    return $.ajax({
        url: src,
        dataType: 'script',
        cache: true,
        success: callback
    });
};

$(function(){
    var $paymentInfoElemet = $('.dr_shippingContainer').length ? $('.dr_shippingContainer') : $('.paymentInstrumentList'),
        $confirmElemet = $('#dr_confirmShipping').length ? $('#dr_confirmShipping') : $('#dr_confirmPaymentMethod');
    if(!$paymentInfoElemet.length){
        $paymentInfoElemet = $('.dr_emailContainer');
    }
    var buttonWidth = $('.continueButtonTop').width(),
        headerTextWidth = $paymentInfoElemet.find('h1:eq(0),h2:eq(0)').width();
    headerTextMaxWidth = ((headerTextWidth - buttonWidth) / headerTextWidth) * 100 + '%'
    $paymentInfoElemet.find('h1:eq(0),h2:eq(0)').css('max-width', headerTextMaxWidth);

    if($confirmElemet.prev().prev().hasClass('submitButtonTop')){
        var buttonWidth = $('.submitButtonTop').width(),
            headerTextWidth = $confirmElemet.find('h1:eq(0),h2:eq(0)').width();
        headerTextMaxWidth = ((headerTextWidth - buttonWidth) / headerTextWidth) * 100 + '%'
        $confirmElemet.find('h1:eq(0),h2:eq(0)').css('max-width', headerTextMaxWidth);
    }
});

function buildRedirectUrl() {
    var ru = '';

    if(inputVariables.storeData.page.currentPageName == 'ThankYouPage' || inputVariables.storeData.page.currentPageName == 'AddEditAddressPage' || inputVariables.storeData.page.currentPageName == 'AddEditPaymentPage' || inputVariables.storeData.page.currentPageName == 'AccountOrderListPage' || inputVariables.storeData.page.currentPageName == 'SavedCartHistoryPage' || inputVariables.storeData.page.currentPageName == 'DownloadHistoryPage' || inputVariables.storeData.page.currentPageName == 'WishlistPage' || inputVariables.storeData.page.currentPageName == 'SavedItems' || inputVariables.storeData.page.currentPageName == 'SubscriptionListPage' || inputVariables.storeData.page.currentPageName == 'ServerErrorPage' || inputVariables.storeData.page.currentPageName == 'EditProfilePage' || inputVariables.storeData.page.currentPageName == 'PurchasePlanLandingPage' || inputVariables.storeData.page.currentPageName == 'SignOutWLIDPage'){
        ru = 'http://' + location.hostname + inputVariables.storeData.actionName.Home;
    } else if(inputVariables.storeData.page.currentPageName == 'ThreePgCheckoutShoppingCartPage'){
        // Always go back to a safe cart URL, to avoid increasing the product quantity, or removing phantom line items, etc
        ru = '//' + location.hostname + inputVariables.storeData.actionName.ShoppingCart;
    } else if(inputVariables.storeData.request.method == 'POST'){
        // You obviously can't redirect back to a POSTed page, so reconstruct the URL using the DisplayPage action
        ru = location.protocol + '//' + location.hostname + inputVariables.storeData.actionName.DisplayPage + '&id=' + inputVariables.storeData.page.currentPageName;
        if(inputVariables.storeData.request.marketID){
            ru += "&marketID="+inputVariables.storeData.request.marketID
        }
    } else {
        // Last resort is to just grab whatever is on the current URL, exactly as it appears
        ru = location.href;
    }

    return ru;
}

function buildSignOutUrl() {
    if(inputVariables.storeData.page.currentPageName.length > 0){
        var ru = buildRedirectUrl();
    }

    var isSecure = (location.protocol === 'https:');
    var signOutUrl = inputVariables.storeData.resources.text.SIGNOUT_URL_PREFIX + encodeURIComponent(ru) + '&secure=' + isSecure + '&tagtype=text';

    return signOutUrl;
}

function signInOutLink(micrositelinks){
    if($('li.signInOutLink').data("isAuthenticated") == "true"){
        var username = $('li.signInOutLink').data("userName");
        $('li.signInOutLink').addClass('signedIn');

        //var au = 'http://' + location.hostname + inputVariables.storeData.actionName.EditProfile;
        var signoutSuffix = '<a href="javascript:void(0)" class="shopper-name">' + inputVariables.storeData.resources.text.TEXT_HI + ' ' + username + '</a>';
        var signoutMenuTop = '<div class="account-menu"><ul><li class="main"><a href="javascript:void(0);">' + inputVariables.storeData.resources.text.MY_ORDERS + '</a></li><li><a href="' + inputVariables.storeData.actionName.AccountOrderList + '">' + inputVariables.storeData.resources.text.ORDER_HISTORY + '</a></li>' + (inputVariables.storeData.resources.text.SiteSetting_SelfServiceReturnEnabledCC == "true" ? '<li><a href="#returnhistory">' + inputVariables.storeData.resources.text.RETURN_HISTORY + '</a></li>' : '') + '<li><a href="' + inputVariables.storeData.actionName.DownloadHistory + '">' + inputVariables.storeData.resources.text.DIGITAL_CONTENT + '</a></li><li class="main"><a href="javascript:void(0);">' + inputVariables.storeData.resources.text.MY_ACCOUNT + '</a></li><li><a href="' + inputVariables.storeData.actionName.EditProfile + '/tab.addressbook">' + inputVariables.storeData.resources.text.ADDRESS_BOOK + '</a></li><li><a href="' + inputVariables.storeData.actionName.AddEditPayment + '">' + inputVariables.storeData.resources.text.PAYMENT + '</a></li><li><a href="' + inputVariables.storeData.actionName.EditProfile+ '/tab.profile">' + inputVariables.storeData.resources.text.ACCOUNT_PROFILE + '</a></li>'+ (inputVariables.storeData.resources.text.SiteSetting_ListMicroSitesEnabledCC == "true" ? micrositelinks : '') +'<li class="sign-out">';
        var signoutMenuBottom = '</li></ul></div>';
        var signOutUrl = buildSignOutUrl();

        $.get(signOutUrl, function(content){
            window.WLIDSignOutLinkContent = '<div class="hover-background">' + signoutSuffix + signoutMenuTop + content.replace(/href=/, ' href=').replace(/\n$/, '') + signoutMenuBottom + '</div>';
            $('.signInOutLink').html(WLIDSignOutLinkContent.replace(/Sign out/, inputVariables.storeData.resources.text.SIGN_OUT));
            $('.bottom-sign-out').html(content.replace(/href=/, ' href=').replace(/\n$/, '').replace(/Sign out/, inputVariables.storeData.resources.text.SIGN_OUT));
            $('.mobile-sign-out').attr('href',$(content).attr('href').replace(/href=/, ' href=').replace(/\n$/, ''));
            $('.mobile-sign-out').html(function(){
                return $(this).html().replace(inputVariables.storeData.resources.text.SIGN_IN,inputVariables.storeData.resources.text.SIGN_OUT);
            });
            $('.hover-background').on('click touchstart', function(){
                $('.signInOutLink, .account-menu').toggleClass('active');
            });
            $(document.body).on('click touchstart', function(e){
                if($(e.target).parents('.signInOutLink, .account-menu').length === 0){
                    $('.signInOutLink, .account-menu').removeClass('active');
                }
            });
            $('.top-level-menuitem').on('click touchstart', function(e){
                $('.signInOutLink, .account-menu').removeClass('active');
            });
        }, 'html');
        $('.main.microsites a').live('click',function(){
            var popuphtml=$('#micrositelinks').html();
            $.magnificPopup.open({
                items: {
                    src: '<div class="micrositelinks">'+popuphtml+'</div>',
                    type: 'inline'
                }
            });
            $('.mfp-content #micrositelinks').removeClass('hide');
        });
    } else {
        $('.signInOutLink').removeClass('signedIn');
    }
}

/* BazaarVoice */
$(window).load(function(){
    var $body = $('body');
    if(inputVariables.storeData.resources.text.SiteSetting_BazaarvoiceEnabled === 'true'){
        if($body.hasClass('HomeOffersPage') || $body.hasClass('CategoryProductListPage') || $body.hasClass('ProductSearchResultsPage')) {
            var bvParam = { productIds: [], containerPrefix: 'BVRRInlineRating' };
            $(".category-products a[pid-ref]").each(function(){
                bvParam.productIds.push($(this).attr("pid-ref"));
            });
            $(".category-products div[rroverrideid]").each(function(){
                bvParam.productIds.push($(this).attr("rroverrideid"));
            });
            $BV.ui('rr', 'inline_ratings', bvParam);
        } else if ($body.hasClass('CategoryListPage'))  {
            $('.module-indicator').each(function(){
                var containerPrefix = 'BVRRInlineRating_' + $(this).attr('data-offerid'),
                    bvParam = { productIds: [], containerPrefix: containerPrefix };

                $(this).filter(':[pid-ref]').each(function(){
                    bvParam.productIds.push($(this).attr("pid-ref"));
                });
                $(this).find('a[pid-ref]').each(function(){
                    bvParam.productIds.push($(this).attr("pid-ref"));
                });
                $BV.ui('rr', 'inline_ratings', bvParam);
            });
        } else if ($body.hasClass('ProductCrossSellPage')){
            var bvParam = { productIds: [], containerPrefix: 'BVRRInlineRating' };
            $(".category-products div[pid-ref]").each(function(){
                bvParam.productIds.push($(this).attr("pid-ref"));
            });
            $BV.ui('rr', 'inline_ratings', bvParam);
        }
    }
});

// widget enabled event definition
var widgets = [{
    when: 'domReady',
    list: ['cart','global-content-async','product-offers','image_switcher','variationSelectorMain','ProductCrossSellPage','account','addEditAddress','dr_selectBinarySetForm','hero_tabs_vertical','menu.drop_down','widget.tabs','dynaBreadcrumbs','rotatetabs','socialLinks','videoContainer','editLink','btnSubmitSpinContainer','continueButtonBottom','spinnerMain','msSeoFooterContentMain','ms_ty_terms_conditions','pagination','EditProfilePage','profileInfoUpdate','6up_rotating','rotating_selector','ageGate','dr_Compare','compareContainer','topFeaturesToggle','newAddress','dr_shippingEstimator','dr_shippingContainer','paymentInstrumentList','variationPriceSwitcher','reversed_tile','featuredLaptop','facet-search','color_switcher','popularAddons','addons_color_switcher','carrierSelector','selectedVariationPrice','triggerOverlayPopUp','moveOnMax','autoHashed','shoppingCartFrame','tabSwitcher','smartRows','searchPagePagination','wee_text','battery_text','OfficeComparePage','childProducts','ThreePgCheckoutCollectPaymentInfoPage','recommended-products','recommended-products-legacy','scrollDisplay','dr_catSortOptions','MobileActivationsCoveragePage','MobileActivationsChoosePlanPage','MobileActivationsChooseAddOnsPage','MobileActivationsCreditCheckPage','plancollapse','popularAccessories','overlayPopUpWindow','defaultoptin','showcase','servicePlanMiniContainer','definingLevelTwo','userSelection','MobileActivationsPlanPage','MobileActivationsPreActivatePage','addressSequenceDisplay','ProductPickerPage','subsBillingCountries','gallery-overlay','disabled-button','ThreePgCheckoutShoppingCartPage','ProductDetailsPage','MobileProductDetailsPage','site-flow','shipping-information','cart-candy-rack','PurchasePlanLandingPage','ThankYouPage','ThreePgCheckoutConfirmOrderPage','registrationForm-form-error','search-tip','call-center-tool','promo-code','ReturnsPage']
}, {
    when: 'onLoad',
    list: ['page','validation','page_inner','differentAccountLink','dr_BreakoutRedirect','dr_BreakoutScriptForwardCVVRedirect','photo_360','checkoutFormAutoSubmit']
}];
// widgetize utility
$.widgetize = function(widgetName, widgetFunction, timeDelay){
    //console.log( widgetName );
    if($.isFunction(widgetFunction)) // Ensure that we have a function to call.
    {
        var _TargetElement = $('.' + widgetName); // Construct the jQuery object by selecting the widgetName as a class of the tartget element.
        if(_TargetElement.length) // Ensure that the element is actually on the page to keep from filling memory with unwanted functions.
        {
            // Constuct an anonymous function to call when the event is fired and bind it to the document.
            $(document).bind('enabled.' + widgetName, function(e){
                e.stopImmediatePropagation();
                if(!isNaN(timeDelay) && timeDelay > 0) // Check that timeDelay is a number and that it is greater than zero(0) then create the timeout object.
                    setTimeout(function(){
                        widgetFunction.call(e.target);
                    }, timeDelay);
                else // There is no delay so just call the function.
                    widgetFunction.call(e.target);
            });
        }
    } else {
        if(window.console){
            //console.log('Widget enabled: '+widgetName);
        }
        $('.' + widgetName).each(function(){
            if(!$(this).hasClass('enabled')){
                $(this).addClass('enabled').trigger('enabled.' + widgetName);
            }
        });
    }
};

// trigger enabled event
$.each(widgets, function(i, definition){
    var context = window,
        event = 'load';
    if(definition.when === 'domReady'){
        context = document;
        event = 'ready';
    }
    $(context).bind(event, function(){
        // First add a class of "enabled" to all widgets in this list
        if(definition.list.length > 0){
            $('.' + definition.list.join(',.')).addClass('enabled');
            if(window.console){
                //console.log('Widgets enabled ['+definition.when+']: '+definition.list.join(','));
            }
            // Next trigger the event for each specific widget
            $.each(definition.list, function(j, widgetName){
                $('.' + widgetName).trigger('enabled.' + widgetName);
            });
        }
    });
});

/* For global asynchronous content */
$.widgetize('global-content-async', function(){
    if(inputVariables.storeData.page.siteid != 'mscommon'){
        $.ajax({
            url:'/store/' + inputVariables.storeData.page.siteid + '/' + inputVariables.storeData.page.locale + '/GlobalContentAsync/ThemeID.' + inputVariables.storeData.page.themeID + (inputVariables.storeData.page.siteid == 'msus' || inputVariables.storeData.page.siteid == 'mslatam' || inputVariables.storeData.page.siteid == 'msapac' || inputVariables.storeData.page.siteid == 'msmea' || inputVariables.storeData.page.siteid == 'msaus' || inputVariables.storeData.page.siteid == 'msnz' || inputVariables.storeData.page.siteid == 'msgulf' || inputVariables.storeData.page.siteid == 'msgulf2' ? '/mktp.' + inputVariables.storeData.page.mktp:'') + '/Currency.' + inputVariables.storeData.page.currency + ((inputVariables.storeData.page.Env === 'DESIGN')?'/Env.DESIGN':''),
            datatype:'html',
            cache:true,
            success: function (data) {
                var $data = $(data);

                /** SEO footer **/
                $('.footer-offer').replaceWith($data.find('#seo-footer').html());
                $('.rwd .footer-links .list-of-links h3.seo-footer').click(function(){
                    if($('.store-viewport-detector .desktop').is(':hidden')){
                        $(this).parent().find('ul').slideToggle('fast');
                        $(this).toggleClass('active');
                    }
                });

                /** L2_Nav_Promo offer **/
                var promos = $data.find('.l2-nav-promo').each(function () {
                    var category = $(this).data("catid");
                    var nodetoReplace = $("[data-l2-promo-catid='" + category + "']");
                    var firstUl = nodetoReplace.next('ul');
                    firstUl.append(firstUl.next().html());
                    firstUl.next().remove();
                    nodetoReplace.replaceWith($(this).html());
                });
            }
        });
    }
});

/*Variations selector widget */
$.widgetize('variationSelectorMain', function(){
    $('option', $(this)).each(function(){
        var currentClass = $(this).attr('class');
        currentClass = currentClass.replace(/_/g, ' ');
        $(this).attr('class', currentClass);
    });
    $('.variationSelectorMain').each(function(){
        var $deliveryMethods = $('.variationSelector input[name*="variationDLM"]', this);
        if($deliveryMethods.length <= 1){
            $deliveryMethods.hide();
        }
    });
    $('.variationSelector .grid-row').each(function(){
        $('span.variationPriceCart[data-subscriptiontype]:first', this).removeClass("hide");
    });
    $(this).each(function(index, element){
        var $me = $(this);
        if($('select.variations', $me).length === 0){ // for Call Center Tool since the variation select is ajaxed in, not on the initial page
            return;
        } else {
            var $orginalSelect = $('select.variations', $me).eq(0),
                $orginalSelectContent = $orginalSelect.clone(),
                $clonedSelect = $orginalSelect.clone(),
                initialVariationToShow,
                initialClassToShow = '';
            var defaultselection = $orginalSelect.find("option:selected").attr('class');
            if(defaultselection){
                defaultselection = defaultselection.replace(/ +\S*$/ig, '');
                defaultselection = defaultselection.split(/[, ]+/).pop();
            }
            if($('.variationSelector', $me).hasClass('versionSelector')){
                $orginalSelect.hide();
                initialVariationToShow = ($clonedSelect.find("option:selected").length) ? $clonedSelect.find("option:selected").attr('class') : $clonedSelect.find('option:eq(0)').attr('class');
                if(initialVariationToShow){
                    initialVariationToShow = $.trim(initialVariationToShow);
                    initialVariationToShow = initialVariationToShow.split(' ');
                    $.each(initialVariationToShow, function(index, value){
                        $me.find('input:radio[value=' + value + ']').attr('checked', true);
                    });
                    $('.variationSelector .grid-row input:checked', $me).each(function(){
                        var value = $(this).attr('value');

                        initialClassToShow = initialClassToShow + '.' + value;
                    });
                    $clonedSelect.find('option').each(function(){
                        if(!$(this).is(initialClassToShow)){
                            $(this).remove();
                        }
                    });
                }
                $clonedSelect.appendTo($me);
                $('select.variations:first', $me).removeAttr('onchange');
            }
            $('.variationSelector .grid-row input', $me).bind('click', function(){
                var initialClassToShow = '';
                $orginalSelectContent = $('select.variations', $me).eq(0).clone();
                $orginalSelectContent.show();
                $('select.variations', $me).eq(1).replaceWith($orginalSelectContent);
                $('.variationSelector .grid-row input:checked', $me).each(function(){
                    var value = $(this).attr('value');
                    initialClassToShow = initialClassToShow + '.' + value;
                });
                $('option', $('select.variations', $me).eq(1)).each(function(){
                    if(!$(this).is(initialClassToShow)){
                        $(this).remove();
                    }
                });
                $('select.variations option:selected', $me).eq(0).removeAttr('selected');
                $('select.variations', $me).eq(1).find('option.' + defaultselection + '').attr('selected', true);
                var selectdValue = $('select.variations:eq(1) option:selected', $me).val();
                if($('.color_switcher').length != 0){
                    $('.colorSwitchT', '.color_switcher').each(function(){
                        $(this).find('a').removeClass("active");
                        $(this).hide();
                        if($(this).is(initialClassToShow)){
                            $(this).show();
                        }
                        if($(this).find('div').attr('data-id') == selectdValue){
                            $(this).find('a').addClass('active');
                        }
                    });
                    if($('.productColor').length != 0){
                        $('.productColor').html($('.colorSwitchT a.active').attr('title'));
                    }
                }
                $('select.variations:eq(0) option[value=' + selectdValue + ']', $me).attr('selected', true);
                $('select.variations', $me).eq(0).trigger('change');
                var url = $('select.variations option[value=' + selectdValue + ']', $me).eq(0).attr('data-href');
                if($('select.variations option[value=' + selectdValue + ']', $me).eq(0).hasClass('physical')){
                    if(url){
                        getStockStatusCart(selectdValue, url);
                    }
                } else {
                    if(url){
                        $('select.variations').attr('disabled', 'disabled');
                        $('select.dr_qtySelect').attr('disabled', 'disabled');
                        $('.variationSelectorMain .variationSelector .grid-row input').attr('disabled', 'disabled');
                        window.location = url;
                    }
                }
                $('select.variations', $me).eq(1).change(function(){
                    $('select.variations option:selected', $me).eq(0).removeAttr('selected');
                    var changedValue = $(this).val();
                    $('select.variations option[value=' + changedValue + ']', $me).eq(0).attr('selected', true);
                    $('select.variations', $me).eq(0).trigger('change');
                    defaultselection = $('select.variations', $me).eq(0).find("option:selected").attr('class');
                    if(defaultselection){
                        defaultselection = defaultselection.replace(/ +\S*$/ig, '');
                        defaultselection = defaultselection.split(/[, ]+/).pop();
                    }
                    var url = $('select.variations option[value=' + $(this).val() + ']', $me).eq(0).attr('data-href');
                    if($('select.variations option[value=' + changedValue + ']', $me).eq(0).hasClass('physical')){
                        if(url){
                            getStockStatusCart(changedValue, url);
                        }
                    } else {
                        if(url){
                            $('select.variations').attr('disabled', 'disabled');
                            $('select.dr_qtySelect').attr('disabled', 'disabled');
                            $('.variationSelectorMain .variationSelector .grid-row input').attr('disabled', 'disabled');
                            window.location = url;
                        }
                    }
                });
            });
            var deliveryMethodToHide = 'false',
                deliveryMethodList = '';
            $('.variationDefiningType input', $me).bind('click', function(){
                $('.variationSelector input', $me).parent().show();
                var versionType = $(this).val();
                if(versionType){
                    $.each(['physical', 'download', 'downloadbackup'], function(i, deliveryMethod){
                        deliveryMethodList = '';
                        $('select.variations option', $me).each(function(){
                            deliveryMethodList = deliveryMethodList + $(this).is('.' + versionType + '.' + deliveryMethod + '');
                        });
                        var pattern = /true/;
                        var exists = pattern.test(deliveryMethodList);
                        if(!exists) $('.variationSelector input[value=' + deliveryMethod + ']', $me).parent().hide();
                    });
                }
                if($('select.variations', $me).eq(1).find('option').length === 0){
                    $('.variationSelector .grid-row input:checked', $me).each(function(){

                        if($(this).parent().css('display') === 'none'){
                            $(this).parent().prev().find('input').trigger('click');
                            var initialClassToShow = '';
                            $orginalSelectContent = $('select.variations', $me).eq(0).clone();
                            $orginalSelectContent.show();
                            $('select.variations', $me).eq(1).replaceWith($orginalSelectContent);
                            $('.variationSelector .grid-row input:checked', $me).each(function(){
                                var value = $(this).attr('value');
                                initialClassToShow = initialClassToShow + '.' + value;
                            });
                            $('option', $('select.variations', $me).eq(1)).each(function(){
                                if(!$(this).is(initialClassToShow)){
                                    $(this).remove();
                                }
                            });
                            //$('select.variations option:selected',$me).eq(0).removeAttr('selected');
                            $('select.variations', $me).eq(1).find('option.' + defaultselection + '').attr('selected', true);
                            var selectdValue = $('select.variations:eq(1) option:selected', $me).val();
                            $('select.variations:eq(0) option[value=' + selectdValue + ']', $me).attr('selected', true);
                            $('select.variations', $me).eq(0).trigger('change');
                            $('select[name="productID"]:last', $me).trigger('change');
                            var url = $('select.variations option[value=' + selectdValue + ']', $me).eq(0).attr('data-href');
                            if(url){
                                $('select.variations').attr('disabled', 'disabled');
                                $('select.dr_qtySelect').attr('disabled', 'disabled');
                                $('.variationSelectorMain .variationSelector .grid-row input').attr('disabled', 'disabled');
                                window.location = url;
                            }
                            $('select.variations', $me).eq(1).change(function(){
                                $('select.variations option:selected', $me).eq(0).removeAttr('selected');
                                var changedValue = $(this).val();
                                $('select.variations option[value=' + changedValue + ']', $me).eq(0).attr('selected', true);
                                $('select.variations', $me).eq(0).trigger('change');
                                defaultselection = $('select.variations', $me).eq(0).find("option:selected").attr('class');
                                if(defaultselection){
                                    defaultselection = defaultselection.replace(/ +\S*$/ig, '');
                                    defaultselection = defaultselection.split(/[, ]+/).pop();
                                }
                                var url = $('select.variations option[value=' + $(this).val() + ']', $me).eq(0).attr('data-href');
                                if(url){
                                    $('select.variations').attr('disabled', 'disabled');
                                    $('select.dr_qtySelect').attr('disabled', 'disabled');
                                    $('.variationSelectorMain .variationSelector .grid-row input').attr('disabled', 'disabled');
                                    window.location = url;
                                }
                            });
                        }
                    });
                }

            });
            $('.buyBtn_AddtoCart').bind('click', function(){
                if($('.variationSelector').hasClass('versionSelector')){
                    $('select.variations').eq(0).removeAttr('name');
                }
            });
        }
        var deliveryMethodToHideCart = 'false',
            deliveryMethodListCart = '';
        var versionTypeCart = $('.variationDefiningType input:checked', $me).val();
        if(versionTypeCart){
            $.each(['physical', 'download', 'downloadbackup'], function(i, deliveryMethodCart){
                deliveryMethodListCart = '';
                $('select.variations option', $me).each(function(){
                    deliveryMethodListCart = deliveryMethodListCart + $(this).is('.' + versionTypeCart + '.' + deliveryMethodCart + '');
                });
                var pattern = /true/;
                var exists = pattern.test(deliveryMethodListCart);
                if(!exists) $('.variationSelector input[value=' + deliveryMethodCart + ']', $me).parent().hide();
            });
        }
        if($('body').hasClass('ThreePgCheckoutShoppingCartPage')){
            var variationLanguageCart = $('option:selected', $me).attr("data-productlanguage"),
                variationDeliveryCart = $('option:selected', $me).attr("data-deliverymethod");

            function switchVariationPricesCart(variationLanguageCart, variationDeliveryCart){
                var $variationPriceContainerCart = $('.variationPriceCart', $me).parents('li'),
                    $variationPriceWithDeliveryCart = "",
                    $variationPriceWithoutDeliveryCart = "",
                    $variationPriceCart = "";
                if($variationPriceContainerCart.length == 0){
                    $variationPriceWithDeliveryCart = $('.variationPriceCart[data-productlanguage="' + variationLanguageCart + '"][data-deliverymethod="' + variationDeliveryCart + '"]', $me);
                    $variationPriceWithoutDeliveryCart = $('.variationPriceCart[data-productlanguage="' + variationLanguageCart + '"]', $me);
                    $variationPriceCart = $variationPriceWithDeliveryCart.length > 0 ? $variationPriceWithDeliveryCart : $variationPriceWithoutDeliveryCart;
                    $variationPriceCart.removeClass("hide");
                } else {
                    $variationPriceContainerCart.each(function(){
                        $variationPriceWithDeliveryCart = $('.variationPriceCart[data-productlanguage="' + variationLanguageCart + '"][data-deliverymethod="' + variationDeliveryCart + '"]', this);
                        $variationPriceWithoutDeliveryCart = $('.variationPriceCart[data-productlanguage="' + variationLanguageCart + '"]', this);
                        $variationPriceCart = $variationPriceWithDeliveryCart.length > 0 ? $variationPriceWithDeliveryCart : $variationPriceWithoutDeliveryCart;
                        $variationPriceCart.removeClass("hide");
                    });
                }
            }
            switchVariationPricesCart(variationLanguageCart, variationDeliveryCart);
        }
    });
    $('.ProductDetailsPage .variationSelector .grid-row input:checked,#dr_callCenterToolProductSelectorSection .variationSelector .grid-row input:checked').click();
    $('select.dr_qtySelect').change(function(){
        if($(this).val() != null){
            $('.variationSelectorMain .variationSelector .grid-row input').attr('disabled', 'disabled');
            $('select.variations').attr('disabled', 'disabled');
            $('select.dr_qtySelect').attr('disabled', 'disabled');
        }
    });
    $('.ThreePgCheckoutShoppingCartPage .dr_deleteItemLink').click(function(){
        $('.variationSelectorMain .variationSelector .grid-row input').attr('disabled', 'disabled');
        $('select.variations').attr('disabled', 'disabled');
        $('select.dr_qtySelect').attr('disabled', 'disabled');
    });
    $('.ThreePgCheckoutShoppingCartPage .colorSwitchT a').click(function(e){
        $('.ThreePgCheckoutShoppingCartPage .colorSwitchT a').not(this).removeAttr('href').css({
            cursor: 'pointer'
        });
    });

    if($(this).hasClass('win8Selector')){
        var languageSelectorOptionsArr = [];
        $(this).find('select[name="productID"]:last option').each(function(){
            var optionText = $.trim($(this).text());
            if($.inArray(optionText, languageSelectorOptionsArr) == -1){
                languageSelectorOptionsArr.push(optionText);
            } else {
                $(this).remove();
            }
        });
        var $languageSelector = $('select[name="productID"]:last');
        $languageSelector.live("change", function(){
            var dlmSelected = $('.variationSelector .grid-row input:checked').val();
            if(dlmSelected == "physical"){
                var variationLanguage = $('option:selected', this).attr("data-productlanguage");
                $('select.variations').removeAttr('disabled');
                $('.variationDataContainer').addClass("hide");
                $('.btnSubmitSpinContainer .buySpan_AddtoCart').addClass("hideImp");
                $('.variationDataContainer[data-productlanguage="' + variationLanguage + '"][data-deliverymethod="physical"]').removeClass("hide");
                $('.btnSubmitSpinContainer .buySpan_AddtoCart[data-deliverymethod="physical"]').removeClass("hideImp");
            } else {
                $('select.variations').attr('disabled', 'disabled');
                $('.variationDataContainer').addClass("hide");
                $('.btnSubmitSpinContainer .buySpan_AddtoCart').addClass("hideImp");
                $('.variationDataContainer[data-deliverymethod="download"]:first').removeClass("hide");
                $('.btnSubmitSpinContainer .buySpan_AddtoCart[data-deliverymethod="download"]').removeClass("hideImp");
            }
        });
        $('.variationSelector .grid-row input', this).click(function(){
            $('select.variations').removeAttr('disabled');
            $languageSelector.trigger("change");
        });
        $languageSelector.trigger("change");

    }
    //out of stock for variations
    var $buttonInputText = $('.buySpan_AddtoCart input').val();
    $('.pdp .versionSelector input').bind('click', function(){
        $('.pdp select[name="productID"]:last').trigger('change');
    });
    $('.pdp select[name="productID"]:last').live('change', function(){
        getStockStatus();
    });
    $('.pdp .popularAddons .dr_productName input').live('click', function(){
        getStockStatusAddons($(this));
    });

    function getStockStatus(){
        var PhysicalProduct = $('select.variations').eq(1).val(),
            $buttonInput = $('.buySpan_AddtoCart input');
        $buttonInput.show();
        $buttonInput.siblings('div').remove();
        if($('select.variations').eq(1).find('option[value=' + PhysicalProduct + ']').hasClass('physical')){
            $('.buySpan_AddtoCart').hide();
            $('#load_image').show();
            $.ajax({
                url: "/store/" + inputVariables.storeData.page.siteid + "/" + inputVariables.storeData.page.locale + "/DisplayPage/id.ProductInventoryStatusXmlPage/productID." + PhysicalProduct,
                cache: false
            }).done(function(xmlData){
                $xml = $(xmlData),
                    $inventoryStatus = $xml.find("inventoryStatus");
                if($inventoryStatus.text() === 'PRODUCT_INVENTORY_OUT_OF_STOCK'){
                    $buttonInput.hide();
                    $buttonInput.siblings('div').remove();
                    $('<div class="dr_button_oos">' + inputVariables.storeData.resources.text.OUT_OF_STOCK + '</div>').insertAfter($buttonInput);
                    if($.trim($('input[data-vid=' + PhysicalProduct + ']').siblings('p.surfacePromoMessaging').html()).length === 0){
                        $('input[data-vid=' + PhysicalProduct + ']').siblings('p.surfacePromoMessaging').html(inputVariables.storeData.resources.text.OUT_OF_STOCK);
                    }
                    $('#load_image').hide();
                    $('.buySpan_AddtoCart').show();
                } else {
                    $('#load_image').hide();
                    $buttonInput.show();
                    $('.buySpan_AddtoCart').show();
                }
            });
        }
    }

    function getStockStatusCart(PhysicalProduct, url){
        $('select.variations').attr('disabled', 'disabled');
        $('select.dr_qtySelect').attr('disabled', 'disabled');
        $('.variationSelectorMain .variationSelector .grid-row input').attr('disabled', 'disabled');
        var getStockStatusCartVarTemp;
        $.ajax({
            url: "/store/" + inputVariables.storeData.page.siteid + "/" + inputVariables.storeData.page.locale + "/DisplayPage/id.ProductInventoryStatusXmlPage/productID." + PhysicalProduct,
            cache: false
        }).done(function(xmlData){
            $xml = $(xmlData),
                $inventoryStatus = $xml.find("inventoryStatus");
            if($inventoryStatus.text() === 'PRODUCT_INVENTORY_OUT_OF_STOCK'){
                url = url.replace("lineItemID", "param");
                window.location = url;
            } else {
                window.location = url;
            }
        });
    }

    function getStockStatusAddons($me){
        var PhysicalProduct = $me.val();
        $me.siblings('div.addOnOOS').remove();
        if($me.is(':checked')){
            $.ajax({
                url: "/store/" + inputVariables.storeData.page.siteid + "/" + inputVariables.storeData.page.locale + "/DisplayPage/id.ProductInventoryStatusXmlPage/productID." + PhysicalProduct,
                cache: false
            }).done(function(xmlData){
                $xml = $(xmlData),
                    $inventoryStatus = $xml.find("inventoryStatus");
                if($inventoryStatus.text() === 'PRODUCT_INVENTORY_OUT_OF_STOCK'){
                    $('<div class="addOnOOS">' + inputVariables.storeData.resources.text.OUT_OF_STOCK + '</div>').insertAfter($me.siblings('label'));
                    $('.popularAddons_content .dr_productName input[value=' + PhysicalProduct + ']').removeAttr('checked');
                    $('.popularAddons_content .dr_productName input[value=' + PhysicalProduct + ']').attr('disabled', 'disabled');
                }
            });
        }
    }
    $('.pdp select[name="productID"]:last').trigger('change');
});

$.widgetize('ProductCrossSellPage', function(){
    var inputNameArray = ["ORIG_VALUE_offerProductInstanceID","offerProductInstanceID","ORIG_VALUE_offerInstanceID","offerInstanceID","ORIG_VALUE_isSelected","isSelected"];
    for(var j = 0; j<inputNameArray.length; j++){
        $('input[name^='+inputNameArray[j]+']').each(function(n){
            $(this).attr('name',inputNameArray[j]+'$$'+(1001+n));
            $(this).attr('id',inputNameArray[j]+'$$'+(1001+n));
        });
        $('label[for^='+inputNameArray[j]+']').each(function(n){
            $(this).attr('for',inputNameArray[j]+'$$'+(1001+n));
        });
    }

    $('.featured-child ul').each(function(index){
        $('li:eq('+index+')',this).attr('class','selected');
    });
    $('.featured-child ul li a').on('click',function(){
        var index = $(this).parents('li').index();
        $('.featured-child:visible').hide();
        $('.featured-child:eq('+index+')').show();
        $('.featured-child:visible li:eq('+index+')').attr('class','selected');
    });
});

$.widgetize('variationPriceSwitcher', function(){
    var $languageSelector = $('select[name="productID"]:last'),
        $languageSelectorOptions = $('option', $languageSelector),
        $languageSelectorOptionsSelected = $('option[selected="selected"]', $languageSelector);

    function switchVariationPrices(variationLanguage, variationDelivery){
        $('.variationPrice').addClass("hide");
        var $variationPriceContainer = $('.variationPrice').parents('li'),
            $variationPriceWithDelivery = "",
            $variationPriceWithoutDelivery = "",
            $variationPrice = "";
        if($variationPriceContainer.length == 0){
            $variationPriceWithDelivery = $('.variationPrice[data-productlanguage="' + variationLanguage + '"][data-deliverymethod="' + variationDelivery + '"]');
            $variationPriceWithoutDelivery = $('.variationPrice[data-productlanguage="' + variationLanguage + '"]');
            $variationPrice = $variationPriceWithDelivery.length > 0 ? $variationPriceWithDelivery : $variationPriceWithoutDelivery;
            $variationPrice.removeClass("hide");
        } else {
            $variationPriceContainer.each(function(){
                $variationPriceWithDelivery = $('.variationPrice[data-productlanguage="' + variationLanguage + '"][data-deliverymethod="' + variationDelivery + '"]', this);
                $variationPriceWithoutDelivery = $('.variationPrice[data-productlanguage="' + variationLanguage + '"]', this);
                $variationPrice = $variationPriceWithDelivery.length > 0 ? $variationPriceWithDelivery : $variationPriceWithoutDelivery;
                $variationPrice.removeClass("hide");
            });
        }
    }
    $('select[name="productID"]:last').live("change", function(){
        var variationLanguage = $('option:selected', this).attr("data-productlanguage"),
            variationDelivery = $('option:selected', this).attr("data-deliverymethod");
        switchVariationPrices(variationLanguage, variationDelivery);
    });
    if($languageSelectorOptionsSelected.length == $languageSelectorOptions.length){
        $('option', $languageSelector).removeAttr("selected");
        $('option:last', $languageSelector).attr("selected", true);
    }
    $('#variationDLMPhysical, #variationDLMDownload').live('click', function(){
        $('select[name="productID"]:last').trigger("change");
    });
    $languageSelector.trigger("change");
});

jQuery.fn.outer = function(){
    return $($('<div></div>').html(this.clone())).html();
}

/*msSeoFooterContentMain*/
$.widgetize('msSeoFooterContentMain', function(){

    $('a.msSeoFooterTabAnchor', '#msSeoFooter').live('keydown', function(e){
        if(e.which === 13){
            // I'm not sure the line below does what is intended; this sets the DIV
            // container's outline, not the link's.
            $(this).parent().css({
                'outline': '0'
            });
            $(this).siblings('.msSeoFooterClick').click();
            return false;
        }
    }).live('click', function(e){
        $(this).siblings('.msSeoFooterClick').click();
        return false;
    });

    $('span.msSeoFooterClick', '#msSeoFooter').click(function(e){
        $('.msSeoArrow', this).toggleClass('msSeoArrowUp');
        $(this).next().slideToggle('slow');
        $('html, body').animate({
            scrollTop: $(document).height()
        }, 1000);
        return false;
    });
});
/*Cart Summary widget */
$.widgetize('cart', function(){
    if($(this).is(':visible')){
        var marketparamURL = queryString.cleanUrl('mktp') ? queryString.cleanUrl('mktp') : queryString.regularUrl('mktp');
        if(marketparamURL){
            getCartJsonDataAjax();
        }else{
            getCartJsonDataCookie();
        }
        var checkoutButton = $('input', this);
        checkoutButton.live('click', function(){
            if($(this).attr('data-url')) location.href = $(this).attr('data-url');
        });
        $('a.remove-prod').click(function(){
            $('a.remove-prod').css('visibility','hidden');
        });
    }
});

/*Account Sign in & Sign Out Link */
$.widgetize('account', function(){
    if($(this).length > 0 && $(this).css('display') != 'none'){
        $('#signInLink', this).removeClass('inactive');
    }
});

/* AddEditAddress expand/collapse Profile Page*/
$.widgetize('addEditAddress', function(){
    if($('.dr_addressInfoLeft .dr_addressInfo', this).length > 0){
        $('.dr_addressInfo', this).hide();
        $('.dr_addressInfoLeft', this).each(function(){
            var addressInfo = $(this);
            addressInfo.addClass('active');
            $('.dr_myAccountNicknames', addressInfo).click(function(){
                if($('.dr_myAccountNicknames .expand', addressInfo).html() == '+'){
                    $('.dr_myAccountNicknames .expand', addressInfo).html('-');
                } else {
                    $('.dr_myAccountNicknames .expand', addressInfo).html('+');
                }
                $('.dr_addressInfo', addressInfo).toggle();
            });
        });
        $('.dr_addressInfo').eq(0).show();
        $('.dr_myAccountNicknames .expand').eq(0).html('-');
    }
});

/* Download select dropdown Thank you page & Digital locker page*/
$.widgetize('dr_selectBinarySetForm', function(){
    $(this).each(function(){
        var me = $(this);
        $('.downloadOptions:first', me).show();
        $('select', me).change(function(){
            $('.downloadOptions', me).hide();
            var classToShow = $("select", me).val();
            $('.' + classToShow, me).show();
        });
    });
});

/* vertical hero tabs widget */
$.widgetize('hero_tabs_vertical', function(){

    // This function takes a jQuery object containing the tag anchors and the current index of the active tab and executes a click event for the "next" tab.
    if(!$.NextHeroTab){
        $.fn.NextHeroTab = function(tabanchors, tabindex){
            var CurrentTabIndex = (tabindex + 1) % tabanchors.length; // Get the next tab index, 0 (zero) if we are at the end of the array.
            tabanchors.eq(CurrentTabIndex).click(); // Perform a click event on the new active tab.
            return;
        }
    }

    var HeroContentLayers = $('.grid_6 .article', this),
        HeroContentTabs = $('.tab_vertical', this),
        HeroContentTabsList = $('ul li', HeroContentTabs),
        HeroContentTabsAnchors = $('ul li a', HeroContentTabs),
        CurrentTabIndex = -1, // Tracks the current tab's index in the jQuery object array (index tracker variable). This is set to -1 to indicate initial page load.
        IsAutoScrollEnable = $(this).attr('data-auto-scroll') === 'true' ? true : false, // Set a boolean indicating
        DisplayIntervalTime = parseInt($(this).attr('data-interval')), // Set a boolean indicating
        AutoScrollInterval = null,
        AutoScrollStartTimer = null,
        DoAutoScroll = function(){
            AutoScrollInterval = setInterval(function(){
                HeroContentTabsAnchors.NextHeroTab(HeroContentTabsAnchors, CurrentTabIndex);
            }, DisplayIntervalTime);
        };

    HeroContentLayers.hide(); // Initially hide all of the content to prevent overlapping.

    HeroContentTabsAnchors.click(function(e){
        var thisAnchor = $(this), // Get the clicked anchor.
            thisAnchorParent = thisAnchor.parent(), // Gets the LI parent of the clicked anchor.
            DataIndex = parseInt(thisAnchorParent.attr('data-index')) - 1,
            IsSameTab = CurrentTabIndex == DataIndex, // Determine if the clicked tab index is equal to the currently displayed tab.
            IsInitialLoad = CurrentTabIndex < 0; // Check if this is the initial page load. The CurrentTabIndex is set to -1 to indicate page load.

        CurrentTabIndex = DataIndex; // Set the current index of the clicked tab to the index tracker variable.
        e.preventDefault(); // Prevent the browser from navigating to the URL in the anchor's href attribute.

        if(!IsSameTab){ // If the user clicked the same tab that is being displayed then skip this step to prevent flickering the tab content.
            // Remove all active class names from the tabs anchors and tab LI parents.
            HeroContentTabsList.removeClass('containsActive');
            HeroContentTabsAnchors.removeClass('active');

            // Add the active class names to the clicked anchor and LI parent.
            thisAnchorParent.addClass('containsActive');
            thisAnchor.addClass('active');

            HeroContentLayers.hide(); // Hide all tab contents in preperation for displaying the clicked anchor tab contents.
            if(IsInitialLoad) HeroContentLayers.eq(CurrentTabIndex).show(); // Simply show the contents if this is the initial page load.
            else HeroContentLayers.eq(CurrentTabIndex).fadeIn(); // Fade in the clicked anchor tab contents.
        }

        // If auto scroll is enabled and there was an actual mouse click event then re-start auto scrolling.
        if(IsAutoScrollEnable && e.which){
            if(AutoScrollInterval) clearInterval(AutoScrollInterval); // Stop the current auto scrolling routine.
            DoAutoScroll(); // Call to re-start the auto scrolling routine.
        }
    });

    if(IsAutoScrollEnable) DoAutoScroll(); // Start the auto scrolling routine.
    HeroContentTabsAnchors.first().click(); // Activate the first tab anchor's content.
});

/* widget to control header dropdown */
$.widgetize('menu.drop_down', function(){
    $('.menu.drop_down li').click(function(){
        if($(this).find('ul').length > 0){
            $(this).find('ul').slideToggle("slow");
            return false;
        }
    });
    $('.menu.drop_down li ul li').click(function(e){
        e.stopPropagation()
    });
});

$.widgetize('widget.tabs', function(){
    /*@description build tab controls*/

    $('.section', $(this)).hide();
    $('.section:first', $(this)).show();

    /*@description if a hash value in URL*/
    if(window.location.hash){
        var urlTabIndex = window.location.hash;
        var tabExists = false;
        $('.tab ul li a').each(function(){
            if($(this).attr('href') === urlTabIndex){
                tabExists = true;
                var parentTab = $(this).parents('.widget.tabs');
                $('.tab ul li a', parentTab).removeClass('active');
                $(this).addClass('active');
                var tabIndex = $(this).attr('href'),
                    sectionWidth = $(tabIndex, parentTab).width();

                if(!parentTab.is('.animate')){
                    $('.section', parentTab).hide();
                    $(tabIndex, parentTab).show();
                } else {
                    var $currentSection = $('.section:visible', parentTab).is($(tabIndex, parentTab)) ? false : $('.section:visible', parentTab);
                    if($currentSection){

                        var direction = ($currentSection.attr('id').replace('tab', '') - $(tabIndex, parentTab).attr('id').replace('tab', '')) > 0 ? '+' : '-',
                            sectionWidth = direction == '-' ? $currentSection.width() : $currentSection.width() * -1,
                            topPos = $('.tab', parentTab).height() + 20;
                        parentTab.css('overflow', 'hidden');
                        $(tabIndex, parentTab).addClass('ready').css({
                            "top": topPos,
                            "left": sectionWidth
                        }).show();
                        $('.tabSwitcher').css("visibility", "hidden");
                        $currentSection.stop(true, true).animate({
                            "left": direction + "=" + Math.abs(sectionWidth) + "px"
                        }, 'fast', function(){
                            $(this).hide().css({
                                "top": "",
                                "left": ""
                            });
                        });
                        $(tabIndex, parentTab).stop(true, true).animate({
                            "left": direction + "=" + Math.abs(sectionWidth) + "px"
                        }, 'fast', function(){
                            $(this).css({
                                "top": "",
                                "left": ""
                            }).removeClass('ready');
                            $('.tabSwitcher').css("visibility", "inherit");
                        });
                    }
                }
                return false;
            }
        });
        if(!tabExists){
            $('ul li a',$(this)).removeClass('active');
            $('.tab ul li:first a,.maBlueArrow:first',$(this)).addClass('active');
        }
    } else {
        /*@description initialize tab controls*/
        $('ul li a,.maBlueArrow',$(this)).removeClass('active');
        $('.tab ul li:first a,.maBlueArrow:first',$(this)).addClass('active');
    }

    /*@todo this needs run multiple instances of .tab on a page
     @todo discover the height of the tallest ection and equalize height to prevent "jumping"*/
    $('.tab ul li a', $(this)).click(function(){
        var parentTab = $(this).parents('.widget.tabs');
        $('.tab ul li a,.maBlueArrow', parentTab).removeClass('active');
        $(this).addClass('active');
        $(this).siblings('.maBlueArrow').addClass('active');
        var tabIndex = $(this).attr('href');
        $('.section', parentTab).hide();
        $(tabIndex, parentTab).show();
        return false;
    });
    /* Start scroll Code */
    $('.section', $(this)).each(function(index, element){
        var sliderContent = [],
            container = $(this),
            size = container.attr('data-size') - 0,
            totalNumberOfArticles = container.find('.article').length,
            articleWidth = container.find('.article').width();
        containerWidth = $('body').hasClass('ThreePgCheckoutShoppingCartPage') ? 700 : container.width();
        if((totalNumberOfArticles > size) && (totalNumberOfArticles > 1)){
            $('.tabs.leftright', container).show();
            var items = container.find('.article, .clear').get();
            var i = 1;
            if($('body').hasClass('HomeOffersPage') || $('body').hasClass('CallCenterToolPage')){
                size = size - 2;
            }
            while(items.length){
                if(i == 1){
                    if($('body').hasClass('ThreePgCheckoutShoppingCartPage')){
                        $(items.splice(0, size + 2)).wrapAll('<div id="slide' + i + '" class="slide grid_4 alpha omega active"></div>');
                    } else {
                        $(items.splice(0, size + 2)).wrapAll('<div id="slide' + i + '" class="slide grid_8 alpha omega active"></div>');
                    }
                } else {
                    if($('body').hasClass('ThreePgCheckoutShoppingCartPage')){
                        $(items.splice(0, size + 2)).wrapAll('<div id="slide' + i + '" class="slide grid_4 alpha omega"></div>');
                    } else {
                        $(items.splice(0, size + 2)).wrapAll('<div id="slide' + i + '" class="slide grid_8 alpha omega"></div>');
                    }
                }
                i++;
            }
            container.find('.sectionContainer').css('overflow', 'hidden');
            container.find('.slider').css('width', '1000em');
            container.find('.slide').css('visibility', 'hidden');
            container.find('.slide:first').css('visibility', 'visible');
            container.find('.tabs.leftright').each(function(index){
                $('ul li a', this).removeClass('active');
                if($('body').hasClass('rtlanguage')){
                    $('ul li:last a', this).addClass('disabled');
                } else {
                    $('ul li:first a', this).addClass('disabled');
                }
            });
            var nextSlide = function(direction, $activeSlide){
                    if($('body').hasClass('rtlanguage')){
                        if(direction === "left"){
                            return $activeSlide.next();
                        } else {
                            return $activeSlide.prev();
                        }
                    } else {
                        if(direction === "right"){
                            return $activeSlide.next();
                        } else {
                            return $activeSlide.prev();
                        }
                    }
                },
                addRemoveClass = function(direction, $activeSlide, $nextSlide){
                    if(direction === "right"){
                        $activeSlide.removeClass('omega');
                        $nextSlide.removeClass('alpha');
                    } else {
                        $activeSlide.addClass('alpha');
                        $nextSlide.addClass('omega');
                    }
                };
            container.find('.tabs.leftright a').bind('click', function(e){
                if($('.slider:animated').length == 0){
                    if(!$(this).hasClass('disabled') && !$(this).hasClass('active')){
                        $('.tabs.leftright ul li a', container).removeClass('active disabled');
                        $(this).addClass('active');
                        $('.slide', container).css('visibility', 'visible');
                        var $activeSlide = $('.slide.active', container),
                            nextDirection = $(this).parent().hasClass('rightwards') ? 'right' : 'left';
                        $nextSlide = nextSlide(nextDirection, $activeSlide),
                            slideLen = $activeSlide.next().length === 0 ? $activeSlide.find('.article').length : $nextSlide.find('.article').length;
                        if(slideLen < 4){
                            addRemoveClass(nextDirection, $activeSlide, $nextSlide);
                            if(nextDirection != 'right'){
                                $nextSlide.find('.article:lt(' + slideLen + ')').css('visibility', 'visible');
                            }
                        }
                        var slideDistance;
                        if($('body').hasClass('ThreePgCheckoutShoppingCartPage')){
                            slideDistance = slideLen - 1;
                        } else {
                            slideDistance = slideLen;
                        }
                        $('.slide', container).removeClass('active');
                        container.find('.slider').animate({
                            left: (nextDirection === 'right' ? '-=' : '+=') + (slideLen < 4 ? (((articleWidth * slideLen) + (20 * (slideDistance)))) : containerWidth)
                        }, 500, function(){
                            if(slideLen < 4){
                                if(nextDirection === 'right'){
                                    $('.slide', container).not($activeSlide).css('visibility', 'hidden');
                                    $activeSlide.find('.article:lt(' + slideLen + ')').css('visibility', 'hidden');
                                } else {
                                    $('.slide', container).not($nextSlide).css('visibility', 'hidden');
                                }
                            } else {
                                $('.slide', container).css('visibility', 'hidden');
                            }
                            $nextSlide.addClass('active').css('visibility', 'visible');
                        });
                        if($nextSlide.attr('id') == $('.slide', container).first().attr('id')){
                            $(this).addClass('disabled');
                        }
                        if($nextSlide.attr('id') == $('.slide', container).last().attr('id')){
                            $(this).addClass('disabled');
                        }
                        $(this).removeClass('active');
                    }
                    return false;
                } else {
                    e.stopImmediatePropagation();
                    return false;
                }
            });

        }
    });
    if(!$('.candyRack .horizontal_tabbed_scroller .tab ul').children().length){
        $('.candyRack').hide();
    }
});
$.widgetize('dynaBreadcrumbs', function(){
    window.buildcrumb = function(data){
        var breadcrumbTree = [], // Array to hold the breadcrumb structure.
            id = $('.dynaBreadcrumbs').attr('data-category'),
            storedCategoryIncludeProduct = $('.dynaBreadcrumbs').attr('data-category-include'),
            itemFound = false, // Boolean indicating that a breadcrumb structure was found.
            foundDepth = 0,
            jsonParser = function(jsonObject, level){
                level++; // Integer, tracks the current depth that the JSON tree is.
                for(var childObject in jsonObject){
                    // Since we are only going to process objects we filter out string values (i.e. id, name, type).
                    if(typeof jsonObject[childObject] !== 'string' && jsonObject.hasOwnProperty(childObject) && !itemFound){
                        // Constantly resetting the array length to prevent unwanted values. This is needed in case the last parent category
                        // contained sub-categories but this parent category has the matching id.
                        breadcrumbTree.length = level;
                        // Since the level integer is incremented before this step we need to subtract 1 from it to create a true zero based number for the array index.
                        breadcrumbTree[level - 1] = {
                            id: childObject,
                            name: jsonObject[childObject].name,
                            type: jsonObject[childObject].type
                        }; // Add the current object parameters to the structure array.
                        // Match the passed id with the current json object key.
                        if(id === childObject || storedCategoryIncludeProduct === 'false'){
                            // The passed id was found so we end the search and continue with the current breadcrumb tree structure.
                            itemFound = true;
                            foundDepth = level;
                            break;
                        }
                        // The passed id was not located in this object so process the next object.
                        if(typeof jsonObject[childObject] === 'object' && !itemFound) itemFound = jsonParser(jsonObject[childObject], level); // This is a cascading return value.
                    }
                }
                return itemFound;
            };

        jsonParser(data, 0); // Call the function to parse the JSON.

        // If the passed id is matched with an id in the JSON then we process the structure array.
        // Since the structure array contains data even if the id is not located, we need this to ensure that we are not
        // producing an inaccurate breadcrumb.
        if(itemFound){
            var ulObject = $('<ul></ul>'),
                liObject, // Used to construct the breadcrumb items. The UL is a dummy object that will be discarded.
                breadcrumbHTML = '', // String variable that will hold the output HTML.
                currentPage = $('.dynaBreadcrumbs').attr('data-current-page');

            // If there is a data-current-page attribute then we are on a PDP. Get the product name from the data-current-page attribute.
            if(currentPage) breadcrumbTree.push({
                id: '999',
                name: currentPage
            });

            var foundDepth = breadcrumbTree.length

            $.each(breadcrumbTree, function(index){
                liObject = $('<span></span>'); // Create a LI jQuery object.
                // If this is the last item in the array we need to make it active.
                if((index === foundDepth - 1) && (foundDepth > 1)){
                    if($('body').hasClass('ProductDetailsPage')) liObject.append($('<a></a>').attr({
                        'href': (this.type === "home" ? inputVariables.storeData.actionName.Home : this.type === "cat" ? inputVariables.storeData.actionName.CategoryList + '/categoryID.' + this.id : inputVariables.storeData.actionName.ProductList + '/categoryID.' + this.id),
                        'title': this.name
                    }).html(this.name));
                    else liObject.addClass('active').text(this.name);
                } else if(foundDepth > 1){
                    liObject.append($('<a></a>').attr({
                        'href': (this.type === "home" ? inputVariables.storeData.actionName.Home : this.type === "cat" ? inputVariables.storeData.actionName.CategoryList + '/categoryID.' + this.id : inputVariables.storeData.actionName.ProductList + '/categoryID.' + this.id),
                        'title': this.name
                    }).html(this.name)).append(' > ');
                } else {
                    liObject.append($('<a></a>').attr({
                        'href': (this.type === "home" ? inputVariables.storeData.actionName.Home : this.type === "cat" ? inputVariables.storeData.actionName.CategoryList + '/categoryID.' + this.id : inputVariables.storeData.actionName.ProductList + '/categoryID.' + this.id),
                        'title': this.name
                    }).html(this.name));
                }
                ulObject.append(liObject);
            });
            breadcrumbHTML = ulObject.html();
            $('.breadcrumbs').show();
            $('.dynaBreadcrumbs').html(breadcrumbHTML);
        }
        return;
    };
    $.ajax({
        url: '/store?SiteID=' + inputVariables.storeData.page.siteid + '&Locale=' + inputVariables.storeData.page.locale + '&Action=DisplayBreadcrumbJSON&output=json&catalog=true&jsonp=window.buildcrumb',
        datatype: 'script',
        cache: true,
        success: function(html){
            //success
        }
    });
});

$.widgetize('socialLinks', function(){
    var thisElement = $(this), // The active shareLinks element.
        thisList = $('ul', this), // Put the UL element in a placeholder.
        thisLocality = inputVariables.storeData.page.locale, // Get the current locality.
        thisShareMessage = thisElement.attr('data-sharemessage'), // Get the message for the share.
        thisShareUrl = thisElement.attr('data-shareurl') ? thisElement.attr('data-shareurl') : location.href, // Get the URL to share.
    // Set the order that the links will appear, additionally parameters that will be used within the links HTML (if present).
        linkOrder = [{
            id: 'email'
        }, {
            id: 'twitter'
        }, {
            id: 'facebook_share'
        }]; // {id:'facebook', htmlParameters:{send: true, layout: 'button_count',width: 150, show_faces: false, font: 'arial'}}],
    // This is where the links are defined.
    linkDefinitions = {
        'twitter': { // Key for hte link.
            'url': 'http://twitter.com/share?text={title}&url={url}', // Optional: The URL that will be used to post the share.
            'html': null, // Optional: The HTML to write into the LI element for hte link. This is used for links that may require third party code.
            'js_src': null, // Optional: The URL of a JavaScript file to include. This is used for links that may require third party code.
            'locale_fix': null, // Optional: Used to map unsupported localities to closest supported equivalent.
            'linkTile': inputVariables.storeData.resources.text.CLICK_TO_SHARE_TWITTER, // Optional: The contents for the title attribute of the anchor.
            'name': 'Twitter', // Optional: The name of the link destination.
            'pop': true, // Optional: The boolean indicating if the link will open a new window.
            'class': 'twitterLink', // Optional: The CSS class name for the link.
            'parameters': 'menubar=1,resizable=1,width=800,height=400' // Optional: The parameters used to open a new window in the case that the pop boolean is true.
        },
        'facebook_like': {
            'url': null,
            'html': '<div id="fb-root" class="FBLike"></div><fb:like class="FBLike" href="{url}" send="{send}" layout="{layout}" width="{width}" show_faces="{show_faces}" font="{font}" id="FBLike"></fb:like>',
            'js_src': 'http://connect.facebook.net/{locality}/all.js#appId=219839751376953&xfbml=1',
            'locale_fix': {
                'en_CA': 'en_US'
            }, // Facebook does not support en_CA so remap to en_US.
            'linkTile': null,
            'name': null,
            'pop': false,
            'class': 'facebookLink',
            'parameters': null
        },
        'facebook_share': {
            'url': 'http://www.facebook.com/sharer.php?s=100&p[title]={title}&p[url]={url}&p[images][0]={image}',
            'html': null,
            'js_src': null,
            'locale_fix': null,
            'linkTile': inputVariables.storeData.resources.text.CLICK_TO_SHARE_FACEBOOK,
            'name': 'Email',
            'pop': true,
            'class': 'facebookShareLink',
            'parameters': 'menubar=1,resizable=1,width=800,height=400'
        },
        'email': {
            'url': 'mailto:?body={title}%0D{url}&subject={title}',
            'html': null,
            'js_src': null,
            'locale_fix': null,
            'linkTile': inputVariables.storeData.resources.text.CLICK_TO_SHARE_EMAIL,
            'name': 'Email',
            'pop': false,
            'class': 'emailLink',
            'parameters': null
        }
    },
        js = null, // Script oject placeholder.
        liObject = null; // List item oject placeholder.

    thisList.append($('<li/>').html('<span>' + inputVariables.storeData.resources.text.SHARE_COLON + '</span>')); // Add the label.
    $.each(linkOrder, function(){
        var thisLinkDefinition = linkDefinitions[this.id]; // Get the definition for this link using the ID from the linkOrder array object.
        liObject = $('<li/>'); // Create the list item object.
        if(thisLinkDefinition['class']) // Add the class name is specified.
            liObject.addClass(thisLinkDefinition['class']);
        if(thisLinkDefinition.url) // Create and append an anchor element to the list item if specified.
            liObject.append($('<a />').attr({
                'href': 'javascript:void(null)',
                'title': thisLinkDefinition.linkTile,
                'ref': this.id // This is required in order to retrieve the URL for the link.
            }).html(' ')); // Add a non-breaking space to the anchor.
        if(thisLinkDefinition.html) // Insert HTML to the list item if specified.
        {
            var html = thisLinkDefinition.html; // Get the HTML for the link.
            if(this.htmlParameters) // If there are parameters for the HTML, replace the strings.
                for(var param in this.htmlParameters)
                    html = html.replace(new RegExp('{' + param + '}', 'g'), this.htmlParameters[param]);
            liObject.append(html.replace('{url}', thisShareUrl)); // Append the HTML to the list item.
        }
        thisList.append(liObject); // Append the list item to the UL element.
        // This next section needs to be done after the list item has been appended in order to allow the HTML to render BEFORE the JavaScript executes.
        // JavaScript does not act on the HTML otherwise.
        if(thisLinkDefinition.js_src) // Create a SCRIPT element is js_src is specified.
        {
            if(!$('#' + this.id + '_script').length){
                adjustedLocale = thisLinkDefinition.locale_fix[thisLocality] ? thisLinkDefinition.locale_fix[thisLocality] : thisLocality;
                js = document.createElement('script');
                js.setAttribute('src', thisLinkDefinition.js_src.replace('{locality}', adjustedLocale));
                js.setAttribute('id', this.id + '_script');
                liObject[0].appendChild(js);
            }
        }
    });
    // Attach the click even on the A elements.

    $('ul li a', thisElement).live('click', function(){
        var thisRef = $(this).attr('ref'), // Get the definition id from the anchor.
            thisLinkDefinition = linkDefinitions[thisRef], // Get the definition object using the definition id.
            href = thisLinkDefinition.url.replace(/\{title\}/g, thisShareMessage).replace('{url}', thisShareUrl), // Process the URL.
            imageSrc = inputVariables.storeData.resources.images.MSFT_StoreMark;
        if(href.match(/\{image\}/ig)){
            if($('.image img').length) imageSrc = $('.image img').attr('src');
            href = href.replace(/\{image\}/gi, encodeURIComponent(imageSrc));
        }
        if(thisLinkDefinition.pop) // If pop is true then open a new window.
            window.open(href, thisLinkDefinition.name, thisLinkDefinition.parameters);
        else // If pop is NOT true then open the link in hte same window.
            location.href = href;
    });

    // Need to do: Tracking.

});

/*
 $.widgetize('product-search-form', function(){
 $(this).submit(function(e){
 e.preventDefault();
 var keywords = encodeURIComponent($(this).find('input#search-box').val().replace(/ /g, '+'));
 window.location = location.protocol + '//' + location.hostname + inputVariables.storeData.actionName.ProductSearchResultsPage + '/keywords.' + keywords;
 });
 });
 */

/* rotatetabs */
$.widgetize('rotatetabs', function(){
    $.extend($.easing, {
        easeOutQuint: function(x, t, b, c, d){
            return c * ((t = t / d - 1) * t * t * t * t + 1) + b;
        }
    });
    var rotatetabs = [], //array to store IDs of our tabs
        ind = 0, //index for array
        inter, //store setInterval reference
        change = function(stringref){ //change tab and highlight current tab title
            if($('body').hasClass('rtlanguage')){
                var $marginLefty = $('.rotatetab#' + stringref).css('marginRight', '752px'),
                    $activeTab = $('.rotatetabs .active').css('marginRight', '0px');
                $activeTab.animate({
                    'marginRight': '-752px'
                }, 2000, "easeOutQuint");
                $marginLefty.animate({
                    'marginRight': 0
                }, 2000, "easeOutQuint", function(){
                    $('.rotatetab#' + stringref).addClass('active');
                    $('.rotatetab:not(#' + stringref + ')').removeClass('active');
                });
            } else {
                var $marginLefty = $('.rotatetab#' + stringref).css('marginLeft', '752px'),
                    $activeTab = $('.rotatetabs .active').css('marginLeft', '0px');
                $activeTab.animate({
                    'marginLeft': '-752px'
                }, 2000, "easeOutQuint");
                $marginLefty.animate({
                    'marginLeft': 0
                }, 2000, "easeOutQuint", function(){
                    $('.rotatetab#' + stringref).addClass('active');
                    $('.rotatetab:not(#' + stringref + ')').removeClass('active');
                });
            }
            //clear highlight from previous tab title
            $('.htabs a:not(#' + stringref + 't)').removeClass('active');
            $('.htabs a:not(#' + stringref + 't)').siblings().removeClass('active')
            $('.htabs a:not(#' + stringref + 't)').parent().removeClass('active');
            //highlight currenttab title
            $('.htabs a[href=#' + stringref + ']').addClass('active');
            $('.htabs a[href=#' + stringref + ']').siblings().addClass('active');
            $('.htabs a[href=#' + stringref + ']').parent().addClass('active');
        };
    $(".rotatetab").map(function(){ //store all tabs in array
        rotatetabs[ind++] = $(this).attr("id");
    });
    ind = 1; //set index to next element to fade
    /*$('.htabs a').each(function(){
     $(this).parent().width($(this).width());
     }); */
    $('#' + rotatetabs[0] + 't').addClass('active'); //highlight the current tab title
    $(".htabs a").click(function(){ //handler for clicking on tabs
        window.clearInterval(inter); //if tab is clicked, stop rotating


        if($(this).hasClass('active')) return false;
        stringref = $(this).attr("href").split('#')[1]; //store reference to clicked tab
        $('.rotatetab').stop(true, true);
        change(stringref); //display referenced tab
        return false;
    });
    inter = window.setInterval(function(){ //start rotating tabs
        change(rotatetabs[ind++]); //call change to display next tab
        if(ind >= rotatetabs.length) //if it's the last tab, clear the index
            ind = 0;


    }, 5000);
});

/*Search widget
 $.widgetize('header_search', function(){
 var thisElement = $(this),
 searchField = $('.input_text', thisElement),
 thisLocality = inputVariables.storeData.page.locale, // Get the current locality.
 searchCategoryID = $('.header_search').attr('data-searchid'),
 thisSiteId = inputVariables.storeData.page.siteid,
 keywordData = null,
 map = null,
 fieldDefaultValue = searchField.attr('data-default-value');
 if(fieldDefaultValue && !searchField.is(':focus')){
 searchField.addClass('default').val(fieldDefaultValue);
 searchField.live('focus', function(){
 var thisElement = $(this),
 thisDefaultValue = thisElement.attr('data-default-value');
 if(thisElement.val() == thisDefaultValue) thisElement.removeClass('default').val('');
 });
 }
 $('.header_search').each(function(){
 var $me = $(this);
 $('input[type=image]', $me).bind('click', function(){
 if($('.input_text', $me).val() == inputVariables.storeData.resources.text.SEARCH_MICROSOFT_STORE){
 $('.input_text', $me).val('');
 }
 });
 });
 window.autocompleteSetup = function(data){
 $(".input_text").autocomplete(data.productInfo.product, {
 matchContains: false,
 delay: 10,
 scrollHeight: 210,
 scroll: false,
 autofill: false,
 max: 5,
 formatItem: function(row, i, max){
 var defaultThumbSrc = inputVariables.storeData.resources.images.default_64x64;
 return "<div class='pWrap' title='" + row.displayName + "'><div class='imgDiv'><img src='" + (row.msSmall ? row.msSmall : defaultThumbSrc) + "' alt=' ' align='middle' /></div> <h3>" + row.displayName + '</h3></div>'; // Truncate to 34 characters.
 },
 formatMatch: function(row, i, max){
 return row.displayName + " " + row.productID + " " + row.keywords;
 },
 formatResult: function(row){
 return row.displayName;
 }
 });

 $('.input_text').result(function(event, data, formatted){
 $("#result").val(!data ? "" : data.productID);
 $("#ProductSearchForm").submit();
 });

 if($('.input_text').hasClass('has-focus')){
 $('.input_text').trigger('focus').trigger($.browser.opera ? "keypress" : "keydown");
 }
 };

 $.getScript('/store?SiteID=' + thisSiteId + '&Locale=' + thisLocality + '&Action=DisplayKeywordMapJSON&output=json&catalog=true&jsonp=window.setupKeywords');

 window.setupKeywords = function(data){
 keywordData = data;
 }

 window.checkValueAgainstMap = function(value){
 var returnValue = null;
 if(keywordData && keywordData.keywordToLandingMap){
 $.each(keywordData.keywordToLandingMap.categories, function(){
 var categoryData = this;
 if($.trim(categoryData.categoryName.toUpperCase()) === value.toUpperCase()){
 returnValue = categoryData;
 return false;
 }
 });
 }
 return returnValue;
 };

 if(thisElement.attr('data-enabled') && thisElement.attr('data-enabled') == 'true'){
 $.ajax({
 url: inputVariables.storeData.resources.javascript.jquery_autocomplete,
 dataType: 'script',
 cache: true,
 success: function(){
 $.ajax({
 url: '/store?SiteID=' + thisSiteId + '&Locale=' + thisLocality + '&Action=DisplayPage&id=DRProductInfoJSPage&CategoryID=' + searchCategoryID + '&size=1000&version=2&output=json&content=displayName+msSearchRank&orderBy=msSearchRank+descending&catalog=false&jsonp=window.autocompleteSetup',
 dataType: 'script',
 cache: true
 });
 }
 });
 }

 searchField.parents('#ProductSearchForm').submit(function(e, data) // Using this method to prevent multiple submit events on a single form.
 {
 var keywordValue = $("input[name='keywords']", this).val().replace(/[\*&:;]/g, '').replace(/(^[\s]+|[\s]+$)/g, '');
 var resultValue = $(this).children('#result, #result2').val();
 $("input[name='keywords']", this).val(keywordValue);

 if(resultValue.length !== 0){
 window.location = '/store/' + thisSiteId + '/' + thisLocality + '/pdp/productID.' + resultValue;
 return false;
 } else if(keywordValue.length > 0){
 var map = window.checkValueAgainstMap(keywordValue);
 if(map && map.id && map.id.length > 0){
 window.location = '/store/' + thisSiteId + '/' + thisLocality + '/cat/categoryID.' + map.id;
 return false;
 } else return true;
 } else return false;
 });
 window.searchLoaded = true;
 }, 2000);
 */

/* widget for signout link */
$.widgetize('signInOutLink', function(){
    if($(this).data("isAuthenticated") == "true"){
        $(this).addClass('signedIn');
        var username = $(this).data("userName");
        if(inputVariables.storeData.page.currentPageName.length > 0){
            if(inputVariables.storeData.page.currentPageName == 'ThankYouPage' || inputVariables.storeData.page.currentPageName == 'AddEditAddressPage' || inputVariables.storeData.page.currentPageName == 'AddEditPaymentPage' || inputVariables.storeData.page.currentPageName == 'AccountOrderListPage' || inputVariables.storeData.page.currentPageName == 'SavedCartHistoryPage' || inputVariables.storeData.page.currentPageName == 'DownloadHistoryPage' || inputVariables.storeData.page.currentPageName == 'WishlistPage' || inputVariables.storeData.page.currentPageName == 'SavedItems' || inputVariables.storeData.page.currentPageName == 'SubscriptionListPage' || inputVariables.storeData.page.currentPageName == 'ServerErrorPage' || inputVariables.storeData.page.currentPageName == 'EditProfilePage' || inputVariables.storeData.page.currentPageName == 'PurchasePlanLandingPage' || inputVariables.storeData.page.currentPageName == 'SignOutWLIDPage'){
                var ru = 'http://' + location.hostname + inputVariables.storeData.actionName.Home;
            } else if(inputVariables.storeData.page.currentPageName == 'ThreePgCheckoutShoppingCartPage'){
                // Always go back to a safe cart URL, to avoid increasing the product quantity, or removing phantom line items, etc
                var ru = '//' + location.hostname + inputVariables.storeData.actionName.ShoppingCart;
            } else if(inputVariables.storeData.request.method == 'POST'){
                // You obviously can't redirect back to a POSTed page, so reconstruct the URL using the DisplayPage action
                var ru = location.protocol + '//' + location.hostname + inputVariables.storeData.actionName.DisplayPage + '&id=' + inputVariables.storeData.page.currentPageName;
                if(inputVariables.storeData.request.marketID){
                    ru += "&marketID="+inputVariables.storeData.request.marketID
                }
            } else {
                // Last resort is to just grab whatever is on the current URL, exactly as it appears
                var ru = location.href;
            }
        }
        //var au = 'http://' + location.hostname + inputVariables.storeData.actionName.EditProfile;
        var signoutSuffix = inputVariables.storeData.resources.text.TEXT_HI + ' ' + username;
        var isSecure = (location.protocol === 'https:');
        var signOutUrl = inputVariables.storeData.resources.text.SIGNOUT_URL_PREFIX + encodeURIComponent(ru) + '&secure=' + isSecure + '&tagtype=text';
        var signoutMenuTop = '<div class="account-menu"><ul><li class="main"><a href="javascript:void(0);">' + inputVariables.storeData.resources.text.MY_ORDERS + '</a></li><li><a href="#orderhistory">' + inputVariables.storeData.resources.text.ORDER_HISTORY + '</a></li>' + (inputVariables.storeData.resources.text.SiteSetting_SelfServiceReturnEnabledCC == "true" ? '<li><a href="#returnhistory">' + inputVariables.storeData.resources.text.RETURN_HISTORY + '</a></li>' : '') + '<li><a href="#digitalcontent">' + inputVariables.storeData.resources.text.DIGITAL_CONTENT + '</a></li><li class="main"><a href="#myaccount">' + inputVariables.storeData.resources.text.MY_ACCOUNT + '</a></li><li><a href="#addressbook">' + inputVariables.storeData.resources.text.ADDRESS_BOOK + '</a></li><li><a href="#payment">' + inputVariables.storeData.resources.text.PAYMENT + '</a></li><li><a href="#accountprofile">' + inputVariables.storeData.resources.text.ACCOUNT_PROFILE + '</a></li><li class="sign-out">';
        var signoutMenuBottom = '</li></ul></div>';

        $.get(signOutUrl, function(content){
            window.WLIDSignOutLinkContent = '<div class="hover-background">' + signoutSuffix + signoutMenuTop + content.replace(/href=/, ' href=').replace(/\n$/, '') + signoutMenuBottom + '</div>';
            $('.signInOutLink').html(WLIDSignOutLinkContent.replace(/Sign out/, inputVariables.storeData.resources.text.SIGN_OUT));
            $('.bottom-sign-out').html(content.replace(/href=/, ' href=').replace(/\n$/, '').replace(/Sign out/, inputVariables.storeData.resources.text.SIGN_OUT));
            $('.hover-background').mouseover(function(){
                $('.signInOutLink').addClass('active');
                $('.account-menu').show();
            }).mouseout(function(){
                $('.signInOutLink').removeClass('active');
                $('.account-menu').hide();
            });
        }, 'html');
    } else {
        $('.signInOutLink').removeClass('signedIn');
    }
}, 10);

/* widget for sign in with different account link */
$.widgetize('differentAccountLink', function(){
    var ru = 'http://' + location.hostname + inputVariables.storeData.actionName.UpdatePI + '/Env.DESIGN/PITokenId.' + inputVariables.storeData.request.PITokenId;
    var isSecure = (location.protocol === 'https:');
    var linkText  = inputVariables.storeData.resources.text.SIGN_IN_WITH_DIFFERENT_ACCOUNT;
    var signOutUrl = inputVariables.storeData.resources.text.SIGNOUT_URL_PREFIX + encodeURIComponent(ru) + '&secure=' + isSecure + '&tagtype=text';
    $.get(signOutUrl, function(content){
        window.WLIDSignOutLinkContent = content.replace(/href=/, ' href=').replace(/\n$/, '');
        //window.WLIDSignOutLinkContent = inputVariables.storeData.resources.text.TEXT_HI + ' ' + username + ' (' + content.replace(/href=/, ' href=').replace(/\n$/, '') + ')';
        $('.differentAccountLink').html(WLIDSignOutLinkContent.replace(/Sign out/, linkText));
    }, 'html');
}, 10);

/* Select payment method to update */
$(document).ready(function() {
    $('.UpdatePICustomerInfoPage .paymentInstrumentList .editLink').click(function(){
        piid = $(this).parent().attr('for');
        $('form[name="UpdatePaymentInstrumentInfo"] input[name="editcard"]').val('true');
        $('form[name="UpdatePaymentInstrumentInfo"] input[name="piid"]').val(piid);
        $('input#checkoutButton').trigger('click');
    });
});

/* widget to Lazy Load Images */
$.widgetize('page_inner', function(){
    $('img[data-src]').each(function(){
        var curImg = $(this);
        curImg.attr('src', curImg.attr('data-src'));
    });
}, 10);


$.widgetize('videoContainer', function(){
    $.ajax({
        url: "//dri1.img.digitalrivercontent.net/Storefront/Site/mscommon/cm/multimedia/js/common/commonfiles_0084.00.js",
        dataType: 'script',
        cache: true,
        success: function(){
            if(window.SC_Agegate){
                window.SC_Agegate.init();
            }
        }
    });
    $(".closeButton").live('click', function(){
        $('#overlayWrap').fadeOut('fast');
        $('.videoOverlay object').each(function(){
            this.Content.MediaPlayer.Stop();
        });
    });
    $('.videoOverlayLink').bind('click', function(e){
        if($('#overlayWrap').length !== 0){
            $('#overlayWrap').fadeIn('fast', function(){
                $('.sc_thumb_step1', $('#overlayWrap')).click();
            });
        } else {
            $sc_thumb_step1 = $(e.target).parent();
            $sc_thumb_step1.click();
        }
        return false;
    });
});

/* widget to display pagination on Search result page */
$.widgetize('searchPagePagination', function(){
    var $page = $(this).find('.dr_pages li').not('.paginationArrow');
    var pageCount = $page.length;
    //console.log(pageCount);
    var $currentPage = $page.filter('.dr_selected');
    var $lastPage = $page.last();
    var $firstPage = $page.first();
    var $penultimatePage = $lastPage.prev();
    $lastPage.addClass('lastPage');
    if(pageCount > 5){
        $page.hide();
        if($lastPage.is('.dr_selected') || $penultimatePage.is('.dr_selected') || $penultimatePage.prev().is('.dr_selected')){
            $lastPage.show();
            $penultimatePage.show().prev().show().prev().show().before('<li class="dr_spacer">...</li>');
            $firstPage.show();
        } else if($firstPage.is('.dr_selected')){
            $currentPage.show().next().show().next().show().next().show().after('<li class="dr_spacer">...</li>');
            $lastPage.show();
        } else {
            $currentPage.show().prev().show().end().next().show().next().show();
            if(!$penultimatePage.prev().prev().is('.dr_selected')){
                $currentPage.next().next().after('<li class="dr_spacer">...</li>');
            }
            $lastPage.show();
        }
    }
});

/* widget to display pagination on Order History page */
$.widgetize('pagination', function(){
    var listContainer = $('.pagination').attr('data-list');
    $orderList = $('div#' + listContainer),
        pageSize = 3,
        startIndex = $orderList ? parseInt($orderList.attr('data-startIndex'), 10) : 0,
        totalItems = $orderList ? parseInt($orderList.attr('data-totalItems'), 10) : 0,
        firstItemOnPage = 1,
        i = 1,
        totalPages = 1,
        totalPagesFloor = 1,
        currentPage = 0,
        lastItemOnPage = pageSize,
        spacerAdded = false,
        isNextToCurrentPage = false,
        selected = '',
        pageStartIndex = 0,
        nextPageStartIndex = 0,
        previousPageStartIndex = 0,
        s = [],
        actionName='';

    switch(listContainer){
        case 'accountOrderListContainer':
            actionName = inputVariables.storeData.actionName.AccountOrderList;
            break;
        case 'accountReturnListContainer':
            actionName = inputVariables.storeData.actionName.AccountReturnList;
            break;
        default:
            break;
    }

    if(isNaN(startIndex)){
        startIndex = 0;
    }
    if(startIndex % pageSize > 0){
        startIndex = 0;
    }

    firstItemOnPage = startIndex + 1;
    totalPages = totalItems / pageSize;
    totalPagesFloor = Math.floor(totalPages);
    if(totalPagesFloor < totalPages){
        totalPages = totalPagesFloor + 1;
    }
    currentPage = startIndex / pageSize + 1;
    lastItemOnPage = (currentPage === totalPages) ? totalItems : (startIndex + pageSize);
    s.push('<div class="dr_pagination">');
    s.push('<span class="showing">' + inputVariables.storeData.resources.text.SHOWING + ' ' + firstItemOnPage + '-' + lastItemOnPage + ' ' + inputVariables.storeData.resources.text.OF + ' ' + totalItems + '</span>');
    s.push('<span>' + inputVariables.storeData.resources.text.PAGE_COLON + '</span>');
    if((currentPage === totalPages || currentPage === totalPages - 1) && totalPages > 3){
        s.push('<a class="dr_page" title="' + inputVariables.storeData.resources.text.PAGE_NUMBER_1 + '" href="' + actionName + '/startIndex.0" >1</a>');
        s.push('<em class="dr_spacer">...</em>');
    }

    if(totalPages === 3 && currentPage !== 1){
        s.push('<a class="dr_page" title="' + inputVariables.storeData.resources.text.PAGE_NUMBER_1 + '" href="' + actionName + '/startIndex.0" >1</a>');
        i = 2;
    }

    for(; i <= totalPages; i++){
        isNextToCurrentPage = i - 1 === currentPage || i + 1 === currentPage;
        if(!spacerAdded && i - 2 === currentPage && totalPages > 3){
            s.push('<em class="dr_spacer">...</em>');
            spacerAdded = true;
        }
        if(isNextToCurrentPage || i === currentPage || i === totalPages){
            selected = i === currentPage ? ' dr_selected' : '';
            pageStartIndex = (i - 1) * pageSize;
            var pageLinkTitle = inputVariables.storeData.resources.text.PAGE_NUMBER_HASHED;
            pageLinkTitle = pageLinkTitle.replace('#', i);
            s.push('<a title="' + pageLinkTitle + '" class="dr_page' + selected + ' " href="' + actionName + '/startIndex.' + pageStartIndex + '" >' + i + '</a>');
        }
    }
    s.push('<span class="paginationArrow">');
    if(currentPage > 1){
        previousPageStartIndex = (currentPage - 1) * pageSize - pageSize;
        s.push('<a class="back" title="' + inputVariables.storeData.resources.text.PREVIOUS_PAGE + '" href="' + actionName + '/startIndex.' + previousPageStartIndex + '" >&#160;</a>');
    }
    if(currentPage < totalPages){
        nextPageStartIndex = (currentPage - 1) * pageSize + pageSize;
        s.push('<a class="next" title="' + inputVariables.storeData.resources.text.NEXT_PAGE + '" href="' + actionName + '/startIndex.' + nextPageStartIndex + '" >&#160;</a>');
    }
    s.push('</span>');
    s.push('</div>');
    $('#displaySize').html(s.join(''));
});

/*Edit link on PCF and Account */
$.widgetize('defaultoptin', function(){
    $(this).find('input').change(function(){
        var $optIn = $(this);
        //Get the data from all the required fields
        var siteID = inputVariables.storeData.page.siteid,
            locale = inputVariables.storeData.page.locale,
            callingPage = inputVariables.storeData.page.currentPageName || '',
            CSRFAuthKey = $('form[name=MSEditProfileForm] input[name=CSRFAuthKey]'),
            optIn = $optIn.is(':checked') ? 'on' : 'off',
            data = 'Action=DisplayAccountMenuPage&SiteID=' + siteID + '&Locale=' + locale +

                '&optIn=' + optIn + '&Form=' + 'com.digitalriver.template.form.client.microsoft.MSEditProfileForm' + '&CSRFAuthKey=' + encodeURIComponent(CSRFAuthKey.val()) + '&ORIG_VALUE_optIn=true' + '&CallingPageID=' + callingPage;
        $.ajax({
            url: "/store/",
            type: "POST",
            data: data,
            cache: false,
            success: function(html){
                //success
            }
        });
    });
    var $OptInContainer = $(this),
        isDefaultOptIn = $OptInContainer.attr('defaultoptin').toUpperCase() === "TRUE" ? true : false;
    if(isDefaultOptIn){
        $('input', $OptInContainer).attr('checked', true);
        $('input', $OptInContainer).trigger('change');
        $('label', $OptInContainer).html(inputVariables.storeData.resources.text.EMAIL_OPT_OUT_TEXT);
    }
    if($('form[name=CheckoutAddressForm]').find('input[name=optIn]').is(':checked')){
        $('input[name=optInTemp]').attr('checked', true);
    }
});

$.widgetize('editLink', function(){
    $('form[name=MSEditProfileForm] .editLink').bind('click', function(){
        $(this).parent().find('.dr_formLine,.dr_requiredFieldsInfo,.applyButton').show();
        $(this).parent().find('.editLink,.fieldInfo').hide();
        $(this).parent().find('input[name=confirmEmail]').val('');
        return false;
    });
    /*var defaultOptIn = $('.defaultoptin').attr('defaultoptin');
     if(defaultOptIn === 'true'){
     $('#dr_optInEmail input').attr('checked', true);
     }*/
    return false;
});
$.widgetize('profileInfoUpdate', function(){
    $('.dr_emailContainer .dr_button,.dr_profileContainer .dr_button,.email-details .dr_button').click(function(e){
        var $parentObj = $(this).parent().parent();
        $('.dr_formLine input', $parentObj).each(function(){
            if($(this).is('[data-name]')){
                var inputFieldName = $(this).attr('data-name'),
                    inputFieldValue = $(this).val();
                $('form[name="MSEditProfileForm"] input[name="' + inputFieldName + '"]').val(inputFieldValue);
            }
        });
    });
});
/*Spinner */
$.widgetize('spinnerMain', function(){
    var counter = 0;
    t = 370;
    $('.spinner img').hide();
    setInterval(function(){
        $('.spinner img').eq(counter).fadeIn(t, function(){
            $(this).fadeOut(t);
            counter = counter + 1;
            if(counter == 5) counter = 0;
        });
    }, 400);
});

/* dr_BreakoutRedirect */
$.widgetize('dr_BreakoutRedirect', function(){
    if(inputVariables.storeData.page.currentPageName.length > 0){
        if(inputVariables.storeData.page.currentPageName == 'BreakoutScript'){
            top.location = 'https://' + inputVariables.storeData.request.serverName + '/store/' + inputVariables.storeData.page.siteid + '/' + inputVariables.storeData.page.locale + '/DisplayThreePgCheckoutConfirmOrderPage';
        }
    }
});

/* dr_BreakoutScriptForwardCVVRedirect */
$.widgetize('dr_BreakoutScriptForwardCVVRedirect', function(){
    if(inputVariables.storeData.page.currentPageName.length > 0){
        if(inputVariables.storeData.page.currentPageName == 'BreakoutScriptForwardCVVPage'){
            top.location = 'https://' + inputVariables.storeData.request.serverName + '/store/' + inputVariables.storeData.page.siteid + '/' + inputVariables.storeData.page.locale + '/DisplayThankYouPage/reqID.' + inputVariables.storeData.request.reqID;
        }
    }
});


/* Trigger the bottom submit button on pcf flow */
$.widgetize('continueButtonBottom', function(){
    $(this).find('input#checkoutButton').bind('click', function(){
        $('.continueButtonTop input#checkoutButtonTop').trigger('click');
    });
    return false;
});

$.widgetize('btnSubmitSpinContainer', function(){
    $('input, a.button', this).bind('click', function(){
        var $parObj = $('.btnSubmitSpinContainer input, .btnSubmitSpinContainer a.button').parent();
        if($parObj.hasClass('clone')){
            $parObj = $('input', '.btnSubmitSpinContainer').parent('.' + $parObj.attr('class').replace(' ', '.'));
        }
        if(!$(this).is('[target]')){
            if($(this).closest('form').attr('name') === 'CheckoutConfirmOrderForm'){
                var $confirmOrderForm = $(this).closest('form');
                $confirmOrderForm.submit(function(){
                    q = false;
                    var $terms = $('#dr_TermsOfServiceAcceptance, #dr_TermsOfServiceAcceptanceForMA,#dr_germanyAgeConfirm');
                    var $termsCheckbox = $terms.find(':checkbox');
                    if(!$termsCheckbox.length || !$termsCheckbox.not(':checked').length){
                        $parObj.hide();
                        var imageSrc = inputVariables.storeData.request.scheme + ':' + inputVariables.storeData.resources.images.loader;
                        if(navigator.userAgent.match(/msie|trident/i)){
                            $parObj.siblings('#load_image').html('<img height="41" width="41" src=" ' + imageSrc + '" />').css('float', 'right').show();
                        } else {
                            $parObj.siblings('#load_image').css('float', 'right').show();
                        }
                        if($('#backToCCT').size() > 0){
                            $('#backToCCT').hide();
                        }
                        return true;
                    } else {
                        $termsCheckbox.closest('#dr_TermsOfSaleAcceptance, #dr_TermsOfSaleAcceptanceForMA, #germanyAgeConfirm').siblings('.dr_error').html("");
                        $termsCheckbox.closest('#dr_TermsOfSaleAcceptance, #dr_TermsOfSaleAcceptanceForMA, #germanyAgeConfirm').css({"border": "none"});
                        $termsCheckbox.not(':checked').closest('#dr_TermsOfSaleAcceptance').siblings('.dr_error').html(inputVariables.storeData.resources.text.ALERT_TERMS_AND_CONDITION);
                        $termsCheckbox.not(':checked').closest('#dr_TermsOfSaleAcceptanceForMA').siblings('.dr_error').html(inputVariables.storeData.resources.text.ALERT_MA_TERMS_AND_CONDITION);
                        $termsCheckbox.not(':checked').closest('#germanyAgeConfirm').siblings('.dr_error').html(inputVariables.storeData.resources.text.Monaco_WARNING_AGE_MSSG_CONFIRM_ERROR);
                        $termsCheckbox.not(':checked').closest('#dr_TermsOfSaleAcceptance, #dr_TermsOfSaleAcceptanceForMA,#germanyAgeConfirm').css({
                            "border": "thin solid #FF0000"
                        });
                        var targetPos = $('body').height() - $(window).height();
                        if(targetPos > parseInt($termsCheckbox.not(':checked').closest('#dr_TermsOfSaleAcceptance, #dr_TermsOfSaleAcceptanceForMA,#germanyAgeConfirm').siblings('.dr_error').first().offset().top)) {
                            targetPos = parseInt($termsCheckbox.not(':checked').closest('#dr_TermsOfSaleAcceptance, #dr_TermsOfSaleAcceptanceForMA,#germanyAgeConfirm').siblings('.dr_error').first().offset().top);
                        }
                        var currentPos = $('html, body').offset().top;
                        if(navigator.userAgent.match(/msie|trident/i)){
                            currentPos = document.documentElement.scrollTop;
                        }
                        if(currentPos < 0) {
                            currentPos = currentPos * -1;
                        }
                        if(currentPos < targetPos) {
                            $('html, body').stop(true, false).animate({
                                scrollTop: targetPos
                            }, 1000);
                        }
                        return false;
                    }
                });
            } else {
                $parObj.hide();
                var imageSrc = inputVariables.storeData.request.scheme + ':' + inputVariables.storeData.resources.images.loader;
                if(navigator.userAgent.match(/msie|trident/i)){
                    $parObj.siblings('#load_image').html('<img height="41" width="41" src=" ' + imageSrc + '" />').show();
                } else {
                    $parObj.siblings('#load_image').show();
                }
            }
        }
    });
});

$.widgetize('ms_ty_terms_conditions', function(){
    $('#ms_ty_terms_conditions').click(function(){
        $('#ms_ty_terms_conditions_overlay').show();
        return false;
    });
    $('#ms_overlay_close_btn').click(function(){
        $('#ms_ty_terms_conditions_overlay').hide();
        return false;
    });
});

$.widgetize('wee_text', function(){
    $('.wee_text').click(function(){
        $('.wee_text_overlay').show();
        return false;
    });
    $('.ms_overlay_close_btn').click(function(){
        $('.wee_text_overlay').hide();
        return false;
    });
});

$.widgetize('battery_text', function(){
    $('.battery_text').click(function(){
        $('.battery_text_overlay').show();
        return false;
    });
    $('.ms_overlay_close_btn').click(function(){
        $('.battery_text_overlay').hide();
        return false;
    });
});


/*Image Switcher widget */
/*$.widgetize('image_switcher', function(){
 function hideImages(divName){
 if(divName==='largeImageContainer'){
 $('.largeImageContainer div,.largeImageContainer div img').addClass("hide");
 } else if (divName==='thumbImageContainer'){
 $('.thumbImageContainer > div:not(.suites)').addClass("hide");
 } else {
 $('.'+ divName +'').addClass("hide");
 }
 }

 function showLargeImage(obj){
 var largeImage = $(this).find('img').data('largeimage');
 var realSrc=$('.largeImageContainer').find('img[data-largeimage='+ largeImage +']').attr('data-realsrc');
 $('.largeImageContainer').find('img[data-largeimage='+ largeImage +']').attr('src', realSrc);
 $('.largeImageContainer').find('img[data-largeimage='+ largeImage +'], img[data-largeimage='+ largeImage +'] img, img[data-largeimage='+ largeImage +'] div').removeClass("hide");
 $('.largeImageContainer').find('div[data-largeimage='+ largeImage +'], div[data-largeimage='+ largeImage +'] div, div[data-largeimage='+ largeImage +'] img').removeClass("hide");
 $('.largeImageContainer').find('img[data-largeimage='+ largeImage +']').parent().removeClass("hide");
 $('.largeImageContainer').find('div[data-largeimage='+ largeImage +']').parent().removeClass("hide");
 }

 function showThumbs(obj){
 $(this).parent().parent().removeClass("hide");
 $(this).parent().siblings().removeClass("hide");
 $('.thumbImageContainer a').removeClass("active");
 $(this).addClass('active');
 }

 function resetImages(obj){
 hideImages('largeImageContainer');
 hideImages('thumbImageContainer');
 showLargeImage.apply(this);
 showThumbs.apply(this);
 }

 function switchImages(variationID){
 //console.log('variationID=' + variationID);
 hideImages('largeImageContainer');
 hideImages('thumbImageContainer');
 $('.largeImageContainer div[data-largeimage='+ variationID +'],.thumbImageContainer div[data-id='+ variationID +']').removeClass("hide");
 $('.largeImageContainer div[data-largeimage='+ variationID +'] img').addClass("hide");
 $('.largeImageContainer div[data-largeimage='+ variationID +'] img:first').removeClass("hide");
 $('.largeImageContainer div[data-largeimage='+ variationID +'] div.sc_embed:first').removeClass("hide");
 $('.largeImageContainer div[data-largeimage='+ variationID +'] div.sc_body:first').removeClass("hide");
 $('.largeImageContainer div[data-largeimage='+ variationID +'] .PhotoContain').removeClass("hide");
 $('.largeImageContainer div[data-largeimage='+ variationID +'] .PhotoContain div').removeClass("hide");
 $('.thumbImageContainer div[data-id='+ variationID +'] a').removeClass("active");
 $('.thumbImageContainer div[data-id='+ variationID +'] a:first').addClass("active");
 }

 $('div.thumbImageContainer a').each(function(index){
 $(this).click(function(e){
 e.preventDefault();
 resetImages.apply(this);
 });
 });

 var variationID;
 // Handle situation where there is a language selector
 $('select[name="productID"]').change(function(){
 variationID = $('select[name="productID"]').val();
 if(variationID !== ""){
 switchImages(variationID);
 }
 });

 // Handle individual product
 if ($('select[name="productID"]').length === 0){
 variationID = $('input[name="productID"]').val();
 if($('#dr_ProductDetails').hasClass('Bundle')){
 variationID = $('input.buyBtn_AddtoCart').attr('data-basepid');
 }
 if(typeof variationID != 'undefined'){
 if(variationID !== ""){
 switchImages(variationID);
 }
 }
 }
 });*/

//Save optIn value on Edit profile page
$.widgetize('EditProfilePage', function(){
    /*$('input#optIn').change(function(){
     //Get the data from all the required fields
     var SiteID = $('form[name=MSEditProfileForm] input[name=SiteID]'),
     Locale = $('form[name=MSEditProfileForm] input[name=Locale]'),
     firstName = $('form[name=MSEditProfileForm] input[name=firstName]'),
     lastName = $('form[name=MSEditProfileForm] input[name=lastName]'),
     email = $('form[name=MSEditProfileForm] input[name=email]'),
     confirmEmail = $('form[name=MSEditProfileForm] input[name=confirmEmail]'),
     CSRFAuthKey = $('form[name=MSEditProfileForm] input[name=CSRFAuthKey]'),
     optIn = $('#optIn').is(':checked') ? 'on' : 'off',
     data = 'Action=DisplayAccountMenuPage&SiteID=' + SiteID.val() + '&Locale=' + Locale.val() +

     '&optIn=' + optIn + '&Form=' + 'com.digitalriver.template.form.client.microsoft.MSEditProfileForm' + '&CSRFAuthKey=' + encodeURIComponent(CSRFAuthKey.val()) + '&ORIG_VALUE_optIn=true' + '&CallingPageID=' + 'EditProfilePage';
     $.ajax({
     url: "/store/",
     type: "POST",
     data: data,
     cache: false,
     success: function(html){
     //success
     }
     });
     });
     var $OptInContainer = $('.defaultoptin'),
     isDefaultOptIn = $OptInContainer.attr('defaultoptin').toUpperCase() === "TRUE" ? true : false;
     if(isDefaultOptIn){
     $('input', $OptInContainer).attr('checked', true);
     $('input', $OptInContainer).trigger('change');
     $('label', $OptInContainer).html(inputVariables.storeData.resources.text.EMAIL_OPT_OUT_TEXT);
     }*/
    $('.dr_profile_info_container2').each(function(){
        var shippingCountry = $('select.country', this).attr('data-country');
        $('select.country option', this).each(function(){
            var optionValue = $(this).attr('value');
            if(optionValue != shippingCountry){
                $(this).remove();
            }
        });
        $('.dr_formLine:even',$(this)).addClass('odd');
    });
    $('#dr_AddressEntryFields .dr_formLine:even').addClass('odd');
    if($('#dr_addressUpdates select#country').length > 0){
        $('#dr_addressUpdates .dr_formLine').hide();
        $('#dr_addressUpdates select#country').parent().show();
        $("#dr_addressUpdates select#country option[selected='selected']").removeAttr("selected"); //deselect all options
        $("#dr_addressUpdates select#country option[value='']").attr("selected", "selected"); //select the second option

        $('#dr_addressUpdates select#country').change(function(){
            $('.SetUserError').hide();
            var shippingCountry = $(this).val();
            $.ajax({
                url: '/Storefront/Site/mscommon/cm/multimedia/js/dr-CrossBorderMapping_16.js', //holds the mapping to the form fields
                dataType: 'json',
                cache: true,
                success: function(data){
                    $.each(data.COUNTRYinfoModal, function(){
                        datacon = this;
                        if(datacon.COUNTRYinfo.shippingCountry === shippingCountry){
                            mappingExistence = true;
                            $('#dr_addressUpdates select#country option').each(function(){
                                var optionValue = $(this).val();
                                if(optionValue != shippingCountry){
                                    $(this).remove();
                                }
                            });
                            if(datacon.COUNTRYinfo.shippingState === 'false'){
                                $('#dr_addressUpdates .dr_formLine').show();
                                $('#dr_addressUpdates select#state').parents('.state').remove();
                                //$('#dr_addressUpdates select#state').parent().hide();
                                //$('#dr_addressUpdates select#state').attr('data-required','false');
                            } else {
                                var shippingStateOptions = datacon.COUNTRYinfo.shippingStateOptions;
                                $('#dr_addressUpdates select#state').html(shippingStateOptions);
                                $('#dr_addressUpdates .dr_formLine').show();
                                $('#dr_addressUpdates select#state').parent().show();
                                $('#dr_addressUpdates select#state').attr('data-required', 'true');
                            }
                            if(datacon.COUNTRYinfo.address2 != null && datacon.COUNTRYinfo.address2 == "false"){
                                $('#dr_addressUpdates .dr_formLine.address2').hide();
                            } else {
                                $('#dr_addressUpdates .dr_formLine.address2').show();
                            }
                            $('#dr_addressUpdates select#country').attr('disabled', 'disabled');
                        }
                        $('#dr_myAccountColumn2Padding fieldset').each(function(){
                            var shippingCountryOther = $('select.country', this).attr('data-country');
                            if(datacon.COUNTRYinfo.shippingCountry === shippingCountryOther){
                                if(datacon.COUNTRYinfo.shippingState === 'false'){
                                    $('.cityState select', this).parent().remove();
                                } else {
                                    var shippingStateOptions = datacon.COUNTRYinfo.shippingStateOptions;
                                    $('.cityState select', this).html(shippingStateOptions);
                                }
                                if(datacon.COUNTRYinfo.address2 != null && datacon.COUNTRYinfo.address2 == "false"){
                                    $('.address2', this).hide();
                                } else {
                                    $('.address2', this).show();
                                }
                            }
                            $('select.country', this).attr('disabled', 'disabled')
                        });
                    });
                    // Address ordering on Customer Info Page
                    var seqList = inputVariables.storeData.resources.shippingAddrSeq[shippingCountry];
                    if(seqList){
                        if(!seqList.match("Config_AddressSequence_")){
                            var seqArray = seqList.split(',');
                            var addrContent = [];
                            addrContent.push($('<div>').append($('#dr_AddressEntryFields .dr_formLine #firstName').parent().clone()).html());
                            addrContent.push($('<div>').append($('#dr_AddressEntryFields .dr_formLine #lastName').parent().clone()).html());
                            $.each(seqArray,function(index,val){
                                if(val=='Address1'){
                                    var elementsToPush = $('<div>').append($('#dr_AddressEntryFields .dr_formLine #addr1').parent().clone()).html();
                                    addrContent.push(elementsToPush);
                                }
                                else if(val=='Address2'){
                                    var elementsToPush = $('<div>').append($('#dr_AddressEntryFields .dr_formLine #addr2').parent().clone()).html();
                                    addrContent.push(elementsToPush);
                                }
                                else if(val=='PostalCode'){
                                    var elementsToPush = $('<div>').append($('#dr_AddressEntryFields .dr_formLine #zip').parent().clone()).html();
                                    addrContent.push(elementsToPush);
                                }
                                else if(val=='City'){
                                    var elementsToPush = $('<div>').append($('#dr_AddressEntryFields .dr_formLine #city').parent().clone()).html();
                                    elementsToPush = elementsToPush + $('<div>').append($('#dr_AddressEntryFields .dr_formLine #state').parent().clone()).html();
                                    addrContent.push(elementsToPush);
                                }
                            });
                            addrContent.push($('<div>').append($('#dr_AddressEntryFields .dr_formLine #phone').parent().clone()).html());
                            addrContent.push($('<div>').append($('#dr_AddressEntryFields .dr_formLine #country').parent().clone()).html());
                            $('#dr_addressUpdates #dr_AddressEntryFields').html(addrContent.join(''));
                            $('input#addr2').focus(function(){
                                $(this).siblings('.optionalText').hide();
                            });
                            $('input#addr3').focus(function(){
                                $(this).siblings('.optionalText').hide();
                            });
                            $('input#addr2, input#addr3').siblings('.optionalText').click(function(){
                                $(this).hide();
                                $(this).siblings('input#addr2, input#addr3').focus();
                            });
                        }
                    }
                    $('#dr_AddressEntryFields .dr_formLine').removeClass('odd');
                    $('#dr_AddressEntryFields .dr_formLine:visible:even').addClass('odd');
                },
                error: function(){
                    //submit the form if any error
                }
            });
        });
        var $MSAddEditAddressForm = $('form[name=MSAddEditAddressForm]');
        $MSAddEditAddressForm.submit(function(){
            /*if($('#dr_addressUpdates select#state').attr('data-required') === 'false'){
             $('#dr_addressUpdates select#state').parent().remove();
             }*/
            $('select#country').removeAttr('disabled');
        });
        $('#dr_addressUpdates select#country').change();
    }
    if(_TM.pstor_mktid === undefined){
        var pstorinfo = cookieObj.getCookie('pstor_info');
        if(pstorinfo){
            var pstorinfo = decodeURIComponent(pstorinfo),
                pstorinfo = pstorinfo.split(",")
            url = inputVariables.storeData.actionName.PurchasePlanReturnUserPage + '/marketID.' + pstorinfo[0];
            $('.accountBreadcrumbs').parent().after('<div class="grid-container returnps">'+inputVariables.storeData.resources.text.Monaco_RETURN_TO_PRIVATE_STORE+' <a href="'+url+'" title="'+pstorinfo[1]+'">'+pstorinfo[1]+'</a>'+inputVariables.storeData.resources.text.Monaco_PRIVATE_STORE+'</div>');
        }
    }
    $('.ship-to-page a.ship').click(function(e){
        $('.dr_profile_info_container2').hide();
        $('fieldset.active .dr_profile_info_container1').show();
        $('#new-address').show();
        $('.ship-to-page').hide();
        $('.title .list').removeClass("hidden-md").removeClass("hidden-lg").hide();
        $('.address-list').addClass("hidden-xs").addClass("hidden-sm");
        $('.back-link .address').show();
        $('.back-link .account').hide();
        $('#dr_AddressEntryFields .dr_formLine').removeClass('odd');
        $('#dr_AddressEntryFields .dr_formLine:visible:even').addClass('odd');
        $('.SetUserError').hide();
    });
    $('.dr_editLink a').click(function(e){
        e.preventDefault();
        $(this).parent().parent().hide().siblings(".dr_profile_info_container2").show();
        var phonevalue1 = $('.phone1').val().replace(/\D*/g, '');
        $('.phone1').val(phonevalue1);
    });
    $('.dr_editLink a.edit').each(function(){
        $(this).click(function(e){
            if($('.rwd .store-viewport-detector .desktop').is(':hidden')){
                $(window).scrollTop(0);
            }
            $('.dr_profile_info_container2').hide();
            $('.address-list fieldset.active .dr_profile_info_container1').show();
            $('#new-address').hide();
            $(this).parents('fieldset').find('.dr_profile_info_container2').show();
            $('.dr_formLine',$(this).parents('fieldset').find('.dr_profile_info_container2')).removeClass('odd');
            $('.dr_formLine:visible:even',$(this).parents('fieldset').find('.dr_profile_info_container2')).addClass('odd');
            $(this).parents('fieldset').find('.dr_profile_info_container1').hide();
            $('.address-list fieldset').removeClass("active");
            $(this).parents('fieldset').addClass("active");
            $('.address-list').removeClass("hidden-xs").removeClass("hidden-sm");
            $('.address-list fieldset').addClass("hidden-xs").addClass("hidden-sm");
            $('.address-list fieldset.active').removeClass("hidden-xs").removeClass("hidden-sm");
            $('.ship-to-page').show();
            $('.title .list').removeClass("hidden-md").removeClass("hidden-lg").hide();
            $('.ship-to-page').addClass("hidden-xs").addClass("hidden-sm");
            $('#dr_myAccountColumn2Padding h1').addClass("hidden-xs").addClass("hidden-sm");
            $('.back-link .address').show();
            $('.back-link .account').hide();
            $('.SetUserError').hide();
        });
    });
    $('.back-link .address').click(function(e){
        e.preventDefault();
        $('.dr_profile_info_container2').hide();
        $('.address-list fieldset.active .dr_profile_info_container1').show();
        $('.address-list fieldset').removeClass("active");
        $('.address-list fieldset').removeClass("hidden-xs").removeClass("hidden-sm");
        $('.title .list').show();
        $('.ship-to-page').removeClass("hidden-xs").removeClass("hidden-sm");
        $('#dr_myAccountColumn2Padding h1').removeClass("hidden-xs").removeClass("hidden-sm");
        $('.ship-to-page').show();
        $('#new-address').hide();
        $('.address-list').removeClass("hidden-xs").removeClass("hidden-sm");
        $('.back-link .address').hide();
        $('.back-link .account').show();
    });

    $('#dr_AddEditAddress .dr_myAccountSiteButtons .dr_button').click(function(e){
        var $parentObj = $(this).parent().parent(),
            addEntryID = $parentObj.attr('data-addressEntryID') || "",
            phoneno = "";
        $('.dr_formLine input, .dr_formLine select', $parentObj).each(function(){
            var inputFieldName = $(this).attr('data-name'),
                inputFieldValue = $(this).val();
            if(inputFieldName==="phoneNumber"){
                phoneno += inputFieldValue;
                inputFieldValue = phoneno;
            }
            var thisSiteID=inputVariables.storeData.page.siteid;
            var thisLocale=inputVariables.storeData.page.locale;
            if(thisSiteID==="mseea"){
                if(thisLocale==="sv_SE"){
                    if(inputFieldName==="postalCode"){
                        var pCode = inputFieldValue;
                        var newPostal = pCode.substring(0,3) + " " + pCode.substring(3);
                        inputFieldValue = pCode.replace(pCode, newPostal);
                    }
                }
            }
            $('form[name="MSAddEditAddressForm"] input[name="'+inputFieldName+'"]').val(inputFieldValue);
            $('form[name="MSAddEditAddressForm"] select[name="'+inputFieldName+'"] option[value="'+inputFieldValue+'"]').attr("selected", "selected");
        });
        $('form[name="MSAddEditAddressForm"] input[name="addressEntryID"], form[name="MSAddEditAddressForm"] input[name="ORIG_VALUE_addressEntryID"]').val(addEntryID);
    });
    $('#dr_EditProfile .dr_myAccountSiteButtons .dr_button').click(function(e){
        var $parentObj = $('#dr_EditProfile .profileMainInfo');
        $('.dr_formLine input', $parentObj).each(function(){
            var inputFieldName = $(this).attr('data-name'),
                inputFieldValue = $(this).val();
            $('form[name="MSEditProfileForm"] input[name="'+inputFieldName+'"]').val(inputFieldValue);
        });
    });
    $('.dr_profile_info_container2 .dr_formLine.state select').each(function(){
        $('option[value="'+$(this).attr('valueToselect')+'"]',$(this)).attr("selected", true);
    });
    if($('#dr_AddEditAddress').hasClass('form-error')){
        var addressEntryID = $('form[name="MSAddEditAddressForm"] input[name=addressEntryID]').val();
        if(addressEntryID ==''){
            $('#dr_AddEditAddress .ship-to-page a.ship').click();
            $('#new-address .SetUserError').show();
        } else {
            $('div[data-addressentryid='+addressEntryID+']').parent().find('.dr_editLink a').click();
            $('div[data-addressentryid='+addressEntryID+']').parent().find('.SetUserError').show();
        }
    }
    $('input#addr2').focus(function(){
        $(this).siblings('.optionalText').hide();
    });
    $('input#addr3').focus(function(){
        $(this).siblings('.optionalText').hide();
    });
    $('input#addr2, input#addr3').siblings('.optionalText').click(function(){
        $(this).hide();
        $(this).siblings('input#addr2, input#addr3').focus();
    });

    if(window.location.href.match(/anchor=new-address/g)){
        var top = $('#new-address').offset().top;
        $(window).scrollTop(top);
        $('.ship-to-page a.ship').click();
    }
    $('form[name="MSAddEditAddressForm"]').attr('action',window.location.href.substr(window.location.href.indexOf("/store")));
    $('form[name="MSEditProfileForm"]').attr('action',window.location.href.substr(window.location.href.indexOf("/store")));
});

$.widgetize('validation', function () {
    var validation = {
        "regexs": {
            "invalidCharacterRegex": /[(\*\(\)\+\:\;\`\~\$\%\^&<\>\=\_\@\!\|)+]/,
            "invalidEmailCharacterRegex": /[(\*\(\)\+\:\;\`\~\$\%\^&<\>\=\!)+]/,
            "invalidZipCharacterRegex": /(^\d+$)|(^\d+-\d+$)/,
            "poBoxRegex": /(p\.?\s?o\.?\s?b\.?(ox)?(\s|[0-9])|post\soffice)/i,
            "postalCodeFormatSE": /^\d{3,3}\s\d{2,2}$/, //Exact 6 char, NNN NN, eg."232 51"
            "postalCodeFormatGB": /^[A-Za-z]{1,2}[0-9Rr][0-9A-Za-z]?\s[0-9][AaBbD-Hd-hJjLlNnP-Up-uW-Zw-z]{2}$/,
            //Max 9 Char, eg."RG6 1WG"
            "postalCodeFormatUS": /(^\d{5}$)|(^\d{5}-\d{4}$)/, //Max 10 Char (No Gaps), NNNNN or NNNNN-NNNN, eg."98052-1234"
            "postalCodeFormatCH": /^\d{4,4}$/, //Exact 4 char (No Gaps), NNNN, eg."1118"
            "postalCodeFormatES": /^\d{5,5}$/, //Exact 5 char (No Gaps), NNNNN, eg."12345"
            "postalCodeFormatFR": /^\d{5,5}$/, //Exact 5 char (No Gaps), NNNNN, eg."12345"
            "postalCodeFormatDE": /^\d{5,5}$/, //Exact 5 char (No Gaps), NNNNN, eg."12345"
            "postalCodeFormatNL": /^\d{4,4}\s{0,1}[A-Za-z]{2,2}$/, //Max 7 Char, NNNN AA, eg."1234 CD"
            "postalCodeFormatIT": /^\d{5,5}$/, //Exact 5 char (No Gaps), NNNNN, eg."12345"
            "postalCodeFormatIE": /^[A-Za-z0-9\s]{1,10}$/, //Max 10 Char, eg." ABC 12 "
            "postalCodeFormatCA": /^[A-Za-z]\d[A-Za-z]\s{0,1}\d[A-Za-z]\d$/, //Max Char 7, ANA NAN, eg."C2B 4E7"
            "postalCodeFormatBE": /^\d{4,4}$/, //Exact 4 Char (No Gaps), NNNN, eg."1234"
            "postalCodeFormatAT": /^\d{4,4}$/, //Exact 4 char (No Gaps), NNNN, eg."1234"
            "postalCodeFormatAU": /^\d{4,4}$/, //Exact 4 char (No Gaps), NNNN, eg."1234"
            "postalCodeFormatNZ": /^[A-Za-z0-9\s]{1,10}$/, //Max Char 10, eg." A12 CDE "
            "postalCodeFormatNO": /^\d{4,4}$/, //Exact 4 char (No Gaps), NNNN, eg."1234"
            "postalCodeFormatBG": /^\d{4,4}$/, //Exact 4 char (No Gaps), NNNN, eg."1118"
            "postalCodeFormatCY": /(^\d{4,4}$)|(^(CY|cy){1,1}-\d{4,4}$)/, //Max 8 char(No Gaps), NNNN or CY-NNNN or cy-NNNN, eg."1234 or CY-1234"
            "postalCodeFormatCZ": /(^\d{5}$)|(^\d{3}\s\d{2}$)/, //Exact 5 char, NNNNN or NNN NN eg. "12345 or 123 45"
            "postalCodeFormatEE": /^\d{5}$/, //Exact 5 char (No Gaps), NNNNN, eg."12345"
            "postalCodeFormatGR": /(^\d{5}$)|(^\d{3}\s\d{2}$)/, //Exact 5 char, NNNNN or NNN NN eg. "12345 or 123 45"
            "postalCodeFormatHU": /(^\d{4}$)|(^(HU|hu){1,1}-\d{4,4}$)/, //Max 6 char (No Gaps), NNNN or HU-NNNN or hu-NNNN eg. "1234 or hu-1234 or HU-1234"
            "postalCodeFormatIS": /(^\d{3}$)|(^(IS|is){1,1}-\d{3,3}$)/, //Max 5 char (No Gaps), NNN or IS-NNN or is-NNN eg. "123 or IS-123 or is-123"
            "postalCodeFormatLV": /(^\d{4}$)|(^(LV|lv){1,1}-\d{4,4}$)|(^(LV|lv){1,1}\d{4,4}$)/, //Max 6 char (No Gaps), NNNN or LVNNNN or lvNNNN or LV-NNNN or lv-NNNN, eg. "1234 or LV-1234 or lv-1234"
            "postalCodeFormatLI": /(^\d{4}$)|(^(LI|li){1,1}-\d{4,4}$)/, //Max 6 char (No Gaps), NNNN or LI-NNNN or li-NNNN, eg. "1234 or LI-1234 or li-1234"
            "postalCodeFormatLT": /(^\d{5}$)|(^(LT|lt){1,1}-\d{5,5}$)|(^(LT|lt){1,1}\d{5,5}$)/, //Max 7 char (No Gaps), NNNNN or LTNNNNN or ltNNNNN or LT-NNNNN or lt-NNNNN, eg. "12345 or LT-12345 or LT12345 or lt-12345 or lt12345"
            "postalCodeFormatLU": /(^\d{4}$)|(^(L|l){1,1}\d{4,4}$)|(^(LU|lu){1,1}\d{4,4}$)/, // Max 6 char (No Gaps), NNNN or LNNNN or lNNNN or LUNNNN or luNNNN, eg. "1234 or L1234 or I1234 or LU1234 or lu1234"
            "postalCodeFormatMT": /^[A-Za-z]{3,3}\s{1,1}\d{4,4}$/, //Max 7 char, AAA NNNN eg. "ABc 1234"
            "postalCodeFormatPL": /(^\d{5,5}$)|(^\d{2}-\d{3}$)/, //Max 5 char (No Gaps), NNNNN or NN-NNN, eg. "12345 or 12-234"
            "postalCodeFormatRO": /^\d{6,6}$/, //Exact 6 char (No Gaps), NNNNNN, eg. "123456"
            "postalCodeFormatSK": /^\d{3}\s\d{2}$/, //Max 5 char, NNN NN, eg. "123 45"
            "postalCodeFormatSI": /(^\d{4}$)|(^(SI|si){1,1}-\d{4,4}$)/, // Max 6 char (No Gaps), NNNN or SI-NNNN or si-NNNN, eg. "1234 or SI-1324 or si-1234"
            "postalCodeFormatDK": /(^\d{4}$)|(^(DK|dk){1,1}\d{4,4}$)/, //Max 6 char(No Gaps), NNNN or DKNNNN or dkNNNN, eg."1234 or DK1234 or dk1234"
            "postalCodeFormatFI": /(^\d{5}$)|(^(FI|fi){1,1}\d{5,5}$)|(^(FIN|fin){1,1}\d{5,5}$)/, //Max 8 char (No Gaps), NNNNN or FINNNNN or fiNNNNN or FINNNNNN or finNNNNN, eg. "12345 or FI12345 or fi12345 or FIN12345 or fin12345"
            "postalCodeFormatPT": /(^\d{4}$)|(^\d{4}-\d{3}$)|(^(PT|pt){1,1}\d{4,4}$)|(^(PT|pt){1,1}-\d{4,4}$)|(^(PT|pt){1,1}\d{4}-\d{3}$)|(^(PT|pt){1,1}-\d{4}-\d{3}$)/
            //Max 10 Char (No Gaps), NNNN or NNNN-NNN or PTNNNN or ptNNNN or PT-NNNN or pt-NNNN or PTNNNN-NNN or ptNNNN-NNN or PT-NNNN-NNN or pt-NNNN-NNN, eg."1234 or 1234-123 or PT1234 or pt1234 or PT-1234 or pt-1234 or PT1234-123 or pt1234-123 or PT-1234-123 or pt-1234-123"
        },
        "getKeyCodes": {
            "_getAlphaKeyCodes": function () {
                // 65-90 = A-Z | 97-122 = a-z
                return [65, 66, 67, 68, 69, 70, 71, 72, 73, 74, 75, 76, 77, 78, 79, 80, 81, 82, 83, 84, 85, 86, 87, 88, 89, 90, 97, 98, 99, 100, 101, 102, 103, 104, 105, 106, 107, 108, 109, 110, 111, 112, 113, 114, 115, 116, 117, 118, 119, 120, 121, 122];
            },
            "_getNumericKeyCodes": function () {
                // 46 = . or Delete for keyCode | 48-57 = numbers 0-9
                return [48, 49, 50, 51, 52, 53, 54, 55, 56, 57];
            },
            "_getNavigationKeyCodes": function () {
                // 0 = unknown, but could be any of the navigation keys on keypress in Mozilla
                // 8,9,13,27 = Backspace, Tab, Enter, Esc
                // NB: IE should ignore navigation keys in the keypress event.
                return [0, 8, 9, 13, 27];
            },
            "_getDefinedKeyCodes": function () {
                //this includes all numeric + [^ | ~ * ]
                return [42, 48, 49, 50, 51, 52, 53, 54, 55, 56, 57, 94, 124, 126];
            },
            "_getNonAlphaNumericCodes": function () {
                //! " # $ % & ( ) * + , _ . : ; < = > ?  @ [ \ ] ^ _ ` { | } ~
                return [33, 34, 35, 36, 37, 38, 40, 41, 42, 43, 44, 45, 46, 58, 59, 60, 61, 62, 63, 64, 91, 92, 93, 94, 95, 96, 123, 124, 125, 126];
            }
        },
        "keyCodeType": {
            "isKeyCodeNumeric": function (which) {
                var allowableKeyCodes = [];
                allowableKeyCodes = $.merge(allowableKeyCodes, validation.getKeyCodes._getNavigationKeyCodes());
                allowableKeyCodes = $.merge(allowableKeyCodes, validation.getKeyCodes._getNumericKeyCodes());
                return $.inArray(which, allowableKeyCodes) > -1;
            },
            "isKeyCodeAlphanumeric": function (which) {
                var notallowedKeyCodes = validation.getKeyCodes._getNonAlphaNumericCodes();
                /*
                 allowableKeyCodes = $.merge(allowableKeyCodes, validation.getKeyCodes._getNavigationKeyCodes());
                 allowableKeyCodes = $.merge(allowableKeyCodes, validation.getKeyCodes._getNumericKeyCodes());
                 allowableKeyCodes = $.merge(allowableKeyCodes, validation.getKeyCodes._getAlphaKeyCodes());
                 allowableKeyCodes = $.merge(allowableKeyCodes, [32, 39, 47]);
                 */
                return $.inArray(which, notallowedKeyCodes) > -1;
            },
            "isKeyCodeAlpha": function (which) {
                var allowableKeyCodes = [];
                /*allowableKeyCodes = $.merge(allowableKeyCodes, validation.getKeyCodes._getNavigationKeyCodes());
                 allowableKeyCodes = $.merge(allowableKeyCodes, validation.getKeyCodes._getAlphaKeyCodes());
                 allowableKeyCodes = $.merge(allowableKeyCodes, [32, 39]);*/
                allowableKeyCodes = $.merge(allowableKeyCodes, validation.getKeyCodes._getNonAlphaNumericCodes());
                allowableKeyCodes = $.merge(allowableKeyCodes, validation.getKeyCodes._getNumericKeyCodes());
                allowableKeyCodes = $.merge(allowableKeyCodes, [47]);
                var removeItem = 45;
                allowableKeyCodes = $.grep(allowableKeyCodes, function (value) {
                    return value != removeItem;
                });
                return $.inArray(which, allowableKeyCodes) > -1;
            }
        },
        "preventEntries": {
            "preventNonnumericEntry": function (e) {
                if (!e.ctrlKey && !e.altKey && !validation.keyCodeType.isKeyCodeNumeric(e.which)) {
                    e.preventDefault();
                    return false;
                }
            },
            "preventNonalphanumericEntry": function (e) {
                //if (!e.ctrlKey && !e.altKey && !validation.keyCodeType.isKeyCodeAlphanumeric(e.which)){
                if (validation.keyCodeType.isKeyCodeAlphanumeric(e.which)) {
                    e.preventDefault();
                    return false;
                }
            },
            "preventNonalphaEntry": function (e) {
                //if (!e.ctrlKey && !e.altKey && !validation.keyCodeType.isKeyCodeAlpha(e.which)){
                if (validation.keyCodeType.isKeyCodeAlpha(e.which)) {
                    e.preventDefault();
                    return false;
                }
            },
            "preventAlphaEntry": function (e) {
                if (!e.ctrlKey && !e.altKey && $.inArray(e.which, validation.getKeyCodes._getAlphaKeyCodes()) !== -1) {
                    e.preventDefault();
                    return false;
                }
            },
            "preventNumericEntry": function (e) {
                if (!e.ctrlKey && !e.altKey && $.inArray(e.which, validation.getKeyCodes._getNumericKeyCodes()) !== -1) {
                    e.preventDefault();
                    return false;
                }
            },
            "preventSpecialEntry": function (e) {
                if (!e.ctrlKey && !e.altKey && $.inArray(e.which, validation.getKeyCodes._getDefinedKeyCodes()) !== -1) {
                    e.preventDefault();
                    return false;
                }
            },
            "replaceNonalphaCharacters": function (value) {
                //return value.replace(/[^a-z\u0020\u0027]*/ig, '');
                return value.replace(/[!"#\$%&\(\)\*\+,_\.:;<=>\?@\[\\\]\^_`{\|}~]*[0-9]*/ig, '');
            },
            "replaceNonalphanumericCharacters": function (value) {
                //return value.replace(/[^0-9a-zA-Z\u0020\u0027\u002F]*/ig, '');
                return value.replace(/[!"#\$%&\(\)\*\+,_\.:;<=>\?@\[\\\]\^_`{\|}~]*/ig, '');
            },
            "replaceNonnumericCharacters": function (value) {
                return value.replace(/[^0-9]*/ig, '');
            },
            "replaceNonnumericdashCharacters": function (value) {
                return value.replace(/[^0-9\-]*/ig, '');
            },
            "replaceAlphaCharacters": function (value) {
                return value.replace(/[^a-zA-Z]*/g, '');
            },
            "replaceNumericCharacters": function (value) {
                return value.replace(/\d*/g, '');
            },
            "replaceSpecialCharacters": function (value) {
                return value.replace(/[0-9\^\*\|~]/g, '');
            }
        },
        "preventPastedEntries": {
            "preventPaste": function (fn, e) {
                try {
                    window.clipboardData.setData('text', fn.call(this, window.clipboardData.getData('text')));
                } catch (x) {
                    window.setTimeout(function () {
                        $(e.target).val(fn.call(this, $(e.target).val()));
                    }, 100);
                }
            },
            "preventNonalphaPaste": function (e) {
                validation.preventPastedEntries.preventPaste(validation.preventEntries.replaceNonalphaCharacters, e);
            },
            "preventNonalphanumericPaste": function (e) {
                validation.preventPastedEntries.preventPaste(validation.preventEntries.replaceNonalphanumericCharacters, e);
            },
            "preventNonnumericPaste": function (e) {
                validation.preventPastedEntries.preventPaste(validation.preventEntries.replaceNonnumericCharacters, e);
            },
            "preventNonnumericdashPaste": function (e) {
                validation.preventPastedEntries.preventPaste(validation.preventEntries.replaceNonnumericdashCharacters, e);
            },
            "preventAlphaPaste": function (e) {
                validation.preventPastedEntries.preventPaste(validation.preventEntries.replaceAlphaCharacters, e);
            },
            "preventNumericPaste": function (e) {
                validation.preventPastedEntries.preventPaste(validation.preventEntries.replaceNumericCharacters, e);
            },
            "preventSpecialPaste": function (e) {
                validation.preventPastedEntries.preventPaste(validation.preventEntries.replaceSpecialCharacters, e);
            }
        },
        "validateFormFields": {
            "validatePhone": function (value, required) {
                if (required) {
                    return value.length >= 10;
                } else {
                    return value.length === 0 || value.length >= 10;
                }
            },
            "validatePostalCode": function (value) {
                return value.length === 5;
            },
            "validateZipCode": function (value) {
                return validation.regexs.invalidZipCharacterRegex.test(value) && value.length >= 5;
            },
            "validateCharacters": function (value) {
                return validation.regexs.invalidCharacterRegex.test(value);
            },
            "validateText": function (value) {
                return value.length > 0;
            },
            "validateEmail": function (value) {
                if (!/@/.test(value)) {
                    return false;
                }

                var splitEmailString = value.split("@"),
                    userNameString = splitEmailString[0],
                    domainNameString = splitEmailString[1],
                    character = 0,
                    i = 0,
                    n = 0;

                if (userNameString.length > 64 || userNameString.length < 1 || domainNameString.length > 48 || domainNameString.length < 1) {
                    return false;
                }
                if (userNameString.indexOf("..") !== -1 || domainNameString.indexOf("..") !== -1) {
                    return false;
                }
                if (userNameString[0] === '.' || userNameString[userNameString.length - 1] === '.' || domainNameString[0] === '.' || domainNameString[domainNameString.length - 1] === '.') {
                    return false;
                }

                for (i = 0, n = userNameString.length; i < n; i++) {
                    character = userNameString.charCodeAt(i);
                    if (character >= 1 && character <= 38) {
                        return false;
                    }
                    if (character >= 40 && character <= 44) {
                        return false;
                    }
                    if (character >= 58 && character <= 64) {
                        return false;
                    }
                    if (character >= 91 && character <= 94) {
                        return false;
                    }
                    if (character === 96) {
                        return false;
                    }
                    if (character >= 123 && character <= 127) {
                        return false;
                    }
                }

                for (i = 0, n = domainNameString.length; i < n; i++) {
                    character = domainNameString.charCodeAt(i);
                    if (character >= 1 && character <= 44) {
                        return false;
                    }

                    if (character === 47) {
                        return false;
                    }
                    if (character >= 58 && character <= 64) {
                        return false;
                    }
                    if (character >= 91 && character <= 96) {
                        return false;
                    }
                    if (character >= 123 && character <= 127) {
                        return false;
                    }
                }
                return !validation.regexs.invalidEmailCharacterRegex.test(value) && value.length >= 7;
            },
            "validatePOBox": function (value) {
                return value.length > 0 && validation.regexs.poBoxRegex.test(value);
            },
            "validatePstalCodeByCountry": function (value, countryVal) {
                switch (countryVal) {
                    case "AU":
                        return validation.regexs.postalCodeFormatAU.test(value);
                        break;
                    case "CA":
                        return validation.regexs.postalCodeFormatCA.test(value);
                        break;
                    case "NZ":
                        return validation.regexs.postalCodeFormatNZ.test(value);
                        break;
                    case "GB":
                        return validation.regexs.postalCodeFormatGB.test(value);
                        break;
                    case "US":
                        return validation.regexs.postalCodeFormatUS.test(value);
                        break;
                    case "SE":
                        return validation.regexs.postalCodeFormatSE.test(value);
                        break;
                    case "CH":
                        return validation.regexs.postalCodeFormatCH.test(value);
                        break;
                    case "ES":
                        return validation.regexs.postalCodeFormatES.test(value);
                        break;
                    case "FR":
                        return validation.regexs.postalCodeFormatFR.test(value);
                        break;
                    case "DE":
                        return validation.regexs.postalCodeFormatDE.test(value);
                        break;
                    case "NL":
                        return validation.regexs.postalCodeFormatNL.test(value);
                        break;
                    case "IT":
                        return validation.regexs.postalCodeFormatIT.test(value);
                        break;
                    case "IE":
                        return validation.regexs.postalCodeFormatIE.test(value);
                        break;
                    case "BE":
                        return validation.regexs.postalCodeFormatBE.test(value);
                        break;
                    case "AT":
                        return validation.regexs.postalCodeFormatAT.test(value);
                        break;
                    case "NO":
                        return validation.regexs.postalCodeFormatNO.test(value);
                        break;
                    case "BG":
                        return validation.regexs.postalCodeFormatBG.test(value);
                        break;
                    case "CY":
                        return validation.regexs.postalCodeFormatCY.test(value);
                        break;
                    case "CZ":
                        return validation.regexs.postalCodeFormatCZ.test(value);
                        break;
                    case "EE":
                        return validation.regexs.postalCodeFormatEE.test(value);
                        break;
                    case "GR":
                        return validation.regexs.postalCodeFormatGR.test(value);
                        break;
                    case "HU":
                        return validation.regexs.postalCodeFormatHU.test(value);
                        break;
                    case "IS":
                        return validation.regexs.postalCodeFormatIS.test(value);
                        break;
                    case "LV":
                        return validation.regexs.postalCodeFormatLV.test(value);
                        break;
                    case "LI":
                        return validation.regexs.postalCodeFormatLI.test(value);
                        break;
                    case "LT":
                        return validation.regexs.postalCodeFormatLT.test(value);
                        break;
                    case "LU":
                        return validation.regexs.postalCodeFormatLU.test(value);
                        break;
                    case "MT":
                        return validation.regexs.postalCodeFormatMT.test(value);
                        break;
                    case "PL":
                        return validation.regexs.postalCodeFormatPL.test(value);
                        break;
                    case "RO":
                        return validation.regexs.postalCodeFormatRO.test(value);
                        break;
                    case "SK":
                        return validation.regexs.postalCodeFormatSK.test(value);
                        break;
                    case "SI":
                        return validation.regexs.postalCodeFormatSI.test(value);
                        break;
                    case "DK":
                        return validation.regexs.postalCodeFormatDK.test(value);
                        break;
                    case "FI":
                        return validation.regexs.postalCodeFormatFI.test(value);
                        break;
                    case "PT":
                        return validation.regexs.postalCodeFormatPT.test(value);
                        break;
                    default:
                        return true;
                }
            },
            "validateNonASCII": function (value) {
                return /^[\x00-\xFF]*$/.test(value);
            }
        },
        "intializeValidation": function () {
            $('[name=firstName], [name=lastName], [data-name=firstName], [data-name=lastName], [data-name=name1], [data-name=name2], [name=SHIPPINGname1], [name=SHIPPINGname2], [name=BILLINGname1], [name=BILLINGname2]').keypress(validation.preventEntries.preventNonalphaEntry);
            $('[name=firstName], [name=lastName], [data-name=firstName], [data-name=lastName], [data-name=name1], [data-name=name2], [name=SHIPPINGname1], [name=SHIPPINGname2], [name=BILLINGname1], [name=BILLINGname2]').bind('paste', validation.preventPastedEntries.preventNonalphaPaste);
            /*$('[data-name=line1], [data-name=line2]').keypress(validation.preventEntries.preventNonalphanumericEntry);
             $('[data-name=line1], [data-name=line2]').bind('paste', validation.preventPastedEntries.preventNonalphanumericPaste);*/
            $('[data-name=city],[name=SHIPPINGcity],[name=BILLINGcity]').keypress(validation.preventEntries.preventSpecialEntry);
            $('[data-name=city],[name=SHIPPINGcity],[name=BILLINGcity]').bind('paste', validation.preventPastedEntries.preventSpecialPaste);
            $('[data-name=phoneNumber], [name=SHIPPINGphoneNumber], [data-name=mobileNumber], [data-name=govtIdentificationNumber], [data-name=ssn],[name=BILLINGphoneNumber]').keypress(validation.preventEntries.preventNonnumericEntry);
            $('[data-name=phoneNumber], [name=SHIPPINGphoneNumber], [data-name=mobileNumber], [data-name=govtIdentificationNumber], [data-name=ssn],[name=BILLINGphoneNumber]').bind('paste', validation.preventPastedEntries.preventNonnumericPaste);
            $('[data-name=zipCode]').keypress(validation.preventEntries.preventAlphaEntry);
            $('[data-name=zipCode]').bind('paste', validation.preventPastedEntries.preventNonnumericdashPaste);
        }
    };
    validation.intializeValidation();
    $('.dr_button').click(function (e) {
        var flag = true,
            billingFlag = true,
            $parent = $(this).parents("fieldset").length === 0 ? $(this).parents(".profileMainInfo .profileName,.profileMainInfo .profileEmail , .dr_profileContainer, .ThreePgCheckoutConfirmOrderPage .profileInfoUpdate,.dr_emailContainer,form[name='CheckoutAddressForm'],.newCustomer,.existingCustomer,.customerAccountInfo") : $(this).parents("fieldset"),
            $parentContainers = $('.fieldset', $parent).length === 0 ? $parent : $('.fieldset', $parent),
            $addr2Obj = $('[data-name=line2], [name=SHIPPINGline2]', $parentContainers);

        $parentContainers.each(function () {
            var $parentContainer = $(this),
                $billingFirstName = $('[name=BILLINGname1]', $parentContainer),
                billingFirstNameLength = $billingFirstName.length !== 0 ? $.trim($billingFirstName.val()).length : -1,
                $billingFirstNameRequired = $('[required=billingName1]', $parentContainer),

                $billingLastName = $('[name=BILLINGname2]', $parentContainer),
                billingLastNameLength = $billingLastName.length !== 0 ? $.trim($billingLastName.val()).length : -1,
                $billingLastNameRequired = $('[required=billingName2]', $parentContainer),

                $billingAddr1 = $('[name=BILLINGline1]', $parentContainer),
                billingAddr1Length = $billingAddr1.length !== 0 ? $.trim($billingAddr1.val()).length : -1,
                $billingAddr1Required = $('[required=billingLine1]', $parentContainer),

                $billingAddr2 = $('[name=BILLINGline2]', $parentContainer),
                $billingAddr2Required = $('[required=billingLine2]', $parentContainer),

                $billingCity = $('[name=BILLINGcity]', $parentContainer),
                billingCityLength = $billingCity.length !== 0 ? $.trim($billingCity.val()).length : -1,
                $billingCityRequired = $('[required=billingCity]', $parentContainer),

                $billingState = $('[name=BILLINGstate]', $parentContainer),
                billingStateLength = $billingState.length !== 0 ? $.trim($billingState.val()).length : -1,
                $billingStateRequired = $('[required=billingState]', $parentContainer),

                $billingCountry = $('[name=BILLINGcountry]', $parentContainer),
                $billingCountryVal = $('[name=BILLINGcountry]', $parentContainer).val(),
                billingCountryLength = $billingCountry.length !== 0 ? $.trim($billingCountry.val()).length : -1,
                $billingCountryRequired = $('[required=billingCountry]', $parentContainer),

                $billingZip = $('[name=BILLINGpostalCode]', $parentContainer),
                billingZipLength = $billingZip.length !== 0 ? $.trim($billingZip.val()).length : -1,
                $billingZipRequired = $('[required=billingPostalCode]', $parentContainer),

                $billingEmail = $('[name=EMAILemail],[name=BILLINGemail]', $parentContainer),
                billingEmailLength = $billingEmail.length !== 0 ? $.trim($billingEmail.val()).length : -1,
                $billingEmailRequired = $('[required=billingEmail]', $parentContainer),

                $billingConfirmEmail = $('[name=EMAILconfirmEmail]', $parentContainer),
                billingConfirmEmailLength = $billingConfirmEmail.length !== 0 ? $.trim($billingConfirmEmail.val()).length : -1,
                $billingConfirmEmailRequired = $('[required=billingConfirmEmail]', $parentContainer),

                $billingPhone = $('[name=BILLINGphoneNumber]', $parentContainer),
                $billingPhoneDataLength = $('[name=BILLINGphoneNumber]', $parentContainer).length,
                billingPhoneLength = 0,

                $billingPhoneRequired = $('[required=billingPhoneNumber]', $parentContainer);

            $billingPhone.each(function () {
                billingPhoneLength += $.trim($(this).val()).length !== 0 ? $.trim($(this).val()).length : 0;
            });
            billingPhoneLength = billingPhoneLength !== 0 ? billingPhoneLength : -1;

            $billingAddr1.removeClass('dr_input_invalid');
            $billingAddr1Required.parent('.textbox').removeClass('error');
            if($billingAddr1Required.length){
                if ($billingAddr1.length) {
                    if (billingAddr1Length === 0) {
                        if(!$('.validation').hasClass('hidden-form')){
                            $billingAddr1Required.parents('.dr_ms_error').show();
                            $billingAddr1Required.parent('.textbox').addClass('error');
                            $billingAddr1Required.html(inputVariables.storeData.resources.text.BILLINGADDRESS_LINE1_ERROR);
                            $billingAddr1.addClass('dr_input_invalid');
                            $billingAddr1.focus();
                        }
                        billingFlag = false;
                    } else if (!validation.validateFormFields.validateNonASCII($billingAddr1.val())) {
                        if (inputVariables.storeData.page.siteid !== 'msjp' && inputVariables.storeData.page.siteid !== 'msru' && inputVariables.storeData.page.siteid !== 'mskr' && inputVariables.storeData.page.siteid !== 'mstw' && inputVariables.storeData.page.siteid !== 'msmea' && inputVariables.storeData.page.siteid !== 'msgulf' && inputVariables.storeData.page.siteid !== 'msgulf2') {
                            if(!$('.validation').hasClass('hidden-form')){
                                $billingAddr1Required.parents('.dr_ms_error').show();
                                $billingAddr1Required.parent('.textbox').addClass('error');
                                $billingAddr1Required.html(inputVariables.storeData.resources.text.NON_ASCII_CHARACTERS_ERROR);
                                $billingAddr1.addClass('dr_input_invalid');
                                $billingAddr1.focus();
                            }
                            billingFlag = false;
                        }
                    } else if (validation.validateFormFields.validateCharacters($billingAddr1.val())) {
                        if(!$('.validation').hasClass('hidden-form')){
                            $billingAddr1Required.parents('.dr_ms_error').show();
                            $billingAddr1Required.parent('.textbox').addClass('error');
                            $billingAddr1Required.html(inputVariables.storeData.resources.text.BILLINGADDRESS_LINE1_ERROR);
                            $billingAddr1.addClass('dr_input_invalid');
                            $billingAddr1.focus();
                        }
                        billingFlag = false;
                    } else {
                        if (validation.validateFormFields.validatePOBox($billingAddr1.val())) {
                            if(!$('.validation').hasClass('hidden-form')){
                                $billingAddr1Required.parents('.dr_ms_error').show();
                                $billingAddr1Required.parent('.textbox').addClass('error');
                                $billingAddr1Required.html(inputVariables.storeData.resources.text.BILLINGADDRESS_LINE1_ERROR);
                                $billingAddr1.addClass('dr_input_invalid');
                                $billingAddr1.focus();
                            }
                            billingFlag = false;
                        } else {
                            if(!$('.validation').hasClass('hidden-form')){
                                $billingAddr1Required.html('');
                            }
                        }
                    }
                }
            }

            $billingAddr2.removeClass('dr_input_invalid');
            $billingAddr2Required.parent('.textbox').removeClass('error');
            if($billingAddr2Required.length){
                if ($billingAddr2.length) {
                    if (validation.validateFormFields.validatePOBox($billingAddr2.val())) {
                        if(!$('.validation').hasClass('hidden-form')){
                            $billingAddr2Required.parents('.dr_ms_error').show();
                            $billingAddr2Required.parent('.textbox').addClass('error');
                            $billingAddr2Required.html(inputVariables.storeData.resources.text.BILLINGADDRESS_LINE1_ERROR);
                            $billingAddr2.addClass('dr_input_invalid');
                            $billingAddr2.focus();
                        }
                        billingFlag = false;
                    } else if (!validation.validateFormFields.validateNonASCII($billingAddr2.val())) {
                        if (inputVariables.storeData.page.siteid !== 'msjp' && inputVariables.storeData.page.siteid !== 'msru' && inputVariables.storeData.page.siteid !== 'mskr' && inputVariables.storeData.page.siteid !== 'mstw' && inputVariables.storeData.page.siteid !== 'msmea' && inputVariables.storeData.page.siteid !== 'msgulf' && inputVariables.storeData.page.siteid !== 'msgulf2') {
                            if(!$('.validation').hasClass('hidden-form')){
                                $billingAddr2Required.parents('.dr_ms_error').show();
                                $billingAddr2Required.parent('.textbox').addClass('error');
                                $billingAddr2Required.html(inputVariables.storeData.resources.text.NON_ASCII_CHARACTERS_ERROR);
                                $billingAddr2.addClass('dr_input_invalid');
                                $billingAddr2.focus();
                            }
                            billingFlag = false;
                        }
                    } else if (validation.validateFormFields.validateCharacters($billingAddr2.val())) {
                        if(!$('.validation').hasClass('hidden-form')){
                            $billingAddr2Required.parents('.dr_ms_error').show();
                            $billingAddr2Required.parent('.textbox').addClass('error');
                            $billingAddr2Required.html(inputVariables.storeData.resources.text.BILLINGADDRESS_LINE1_ERROR);
                            $billingAddr2.addClass('dr_input_invalid');
                            $billingAddr2.focus();
                        }
                        billingFlag = false;
                    } else {
                        if(!$('.validation').hasClass('hidden-form')){
                            $billingAddr2Required.html('');
                        }
                    }
                }
            }

            $billingCity.removeClass('dr_input_invalid');
            $billingCityRequired.parent('.textbox').removeClass('error');
            if($billingCityRequired.length){
                if (billingCityLength === 0) {
                    if(!$('.validation').hasClass('hidden-form')){
                        $billingCityRequired.parents('.dr_ms_error').show();
                        $billingCityRequired.parent('.textbox').addClass('error');
                        $billingCityRequired.html(inputVariables.storeData.resources.text.BILLINGADDRESS_CITY_ERROR);
                        $billingCity.addClass('dr_input_invalid');
                        $billingCity.focus();
                    }
                    billingFlag = false;
                } else if (!validation.validateFormFields.validateNonASCII($billingCity.val())) {
                    if (inputVariables.storeData.page.siteid !== 'msjp' && inputVariables.storeData.page.siteid !== 'msru' && inputVariables.storeData.page.siteid !== 'mskr' && inputVariables.storeData.page.siteid !== 'mstw' && inputVariables.storeData.page.siteid !== 'msmea' && inputVariables.storeData.page.siteid !== 'msgulf' && inputVariables.storeData.page.siteid !== 'msgulf2') {
                        if(!$('.validation').hasClass('hidden-form')){
                            $billingCityRequired.parents('.dr_ms_error').show();
                            $billingCityRequired.parent('.textbox').addClass('error');
                            $billingCityRequired.html(inputVariables.storeData.resources.text.NON_ASCII_CHARACTERS_ERROR);
                            $billingCity.addClass('dr_input_invalid');
                            $billingCity.focus();
                        }
                        billingFlag = false;
                    }
                } else if (validation.validateFormFields.validateCharacters($billingCity.val())) {
                    if(!$('.validation').hasClass('hidden-form')){
                        $billingCityRequired.parents('.dr_ms_error').show();
                        $billingCityRequired.parent('.textbox').addClass('error');
                        $billingCityRequired.html(inputVariables.storeData.resources.text.BILLINGADDRESS_CITY_ERROR);
                        $billingCity.addClass('dr_input_invalid');
                        $billingCity.focus();
                    }
                    billingFlag = false;
                } else if (billingCityLength > 0) {
                    if(!$('.validation').hasClass('hidden-form')){
                        $billingCityRequired.html('');
                    }
                }
            }

            $billingState.removeClass('dr_input_invalid');
            $billingStateRequired.parent('.textbox').removeClass('error');
            if($billingStateRequired.length){
                if (billingStateLength === 0) {
                    if(!$('.validation').hasClass('hidden-form')){
                        $billingStateRequired.parents('.dr_ms_error').show();
                        $billingStateRequired.parent('.textbox,.drop-down-list').addClass('error');
                        $billingStateRequired.html(inputVariables.storeData.resources.text.BILLINGADDRESS_STATE_ERROR);
                        $billingState.addClass('dr_input_invalid');
                        $billingState.focus();
                    }
                    billingFlag = false;
                } else if (!validation.validateFormFields.validateNonASCII($billingState.val())) {
                    if (inputVariables.storeData.page.siteid !== 'msjp' && inputVariables.storeData.page.siteid !== 'msru' && inputVariables.storeData.page.siteid !== 'mskr' && inputVariables.storeData.page.siteid !== 'mstw' && inputVariables.storeData.page.siteid !== 'msmea' && inputVariables.storeData.page.siteid !== 'msgulf' && inputVariables.storeData.page.siteid !== 'msgulf2') {
                        if(!$('.validation').hasClass('hidden-form')){
                            $billingStateRequired.parents('.dr_ms_error').show();
                            $billingStateRequired.parent('.textbox,.drop-down-list').addClass('error');
                            $billingStateRequired.html(inputVariables.storeData.resources.text.NON_ASCII_CHARACTERS_ERROR);
                            $billingState.addClass('dr_input_invalid');
                            $billingState.focus();
                        }
                        billingFlag = false;
                    }
                } else if (billingStateLength > 0) {
                    if(!$('.validation').hasClass('hidden-form')){
                        $billingStateRequired.html('');
                    }
                }
            }

            $billingCountry.removeClass('dr_input_invalid');
            if($billingCountryRequired.length){
                if (billingCountryLength === 0) {
                    if(!$('.validation').hasClass('hidden-form')){
                        $billingCountryRequired.parents('.dr_ms_error').show();
                        $billingCountryRequired.html(inputVariables.storeData.resources.text.BILLINGADDRESS_COUNTRY_ERROR);
                        $billingCountry.addClass('dr_input_invalid');
                        $billingCountry.focus();
                    }
                    billingFlag = false;
                } else if (!validation.validateFormFields.validateNonASCII($billingCountry.val())) {
                    if (inputVariables.storeData.page.siteid !== 'msjp' && inputVariables.storeData.page.siteid !== 'msru' && inputVariables.storeData.page.siteid !== 'mskr' && inputVariables.storeData.page.siteid !== 'mstw' && inputVariables.storeData.page.siteid !== 'msmea' && inputVariables.storeData.page.siteid !== 'msgulf' && inputVariables.storeData.page.siteid !== 'msgulf2') {
                        if(!$('.validation').hasClass('hidden-form')){
                            $billingCountryRequired.parents('.dr_ms_error').show();
                            $billingCountryRequired.html(inputVariables.storeData.resources.text.NON_ASCII_CHARACTERS_ERROR);
                            $billingCountry.addClass('dr_input_invalid');
                            $billingCountry.focus();
                        }
                        billingFlag = false;
                    }
                } else if (billingCountryLength > 0) {
                    if(!$('.validation').hasClass('hidden-form')){
                        $billingCountryRequired.html('');
                    }
                }
            }

            $billingZip.removeClass('dr_input_invalid');
            var isNullAccepted = $billingZipRequired.length > 0 ? false : billingZipLength > 0 ? false : true;
            $billingZipRequired.parent('.textbox').removeClass('error');
            if($billingZipRequired.length){
                if (!isNullAccepted) {
                    if (billingZipLength === 0 || (billingZipLength > 0 && !validation.validateFormFields.validatePstalCodeByCountry($.trim($billingZip.val()), $SHIPPINGcountryVal))) {
                        if(!$('.validation').hasClass('hidden-form')){
                            $billingZipRequired.parents('.dr_ms_error').show();
                            $billingZipRequired.parent('.textbox').addClass('error');
                            $billingZipRequired.html(inputVariables.storeData.resources.text.BILLINGADDRESS_POSTALCODE_ERROR);
                            $billingZip.addClass('dr_input_invalid');
                            $billingZip.focus();
                        }
                        billingFlag = false;
                    } else if (!validation.validateFormFields.validateNonASCII($billingZip.val())) {
                        if (inputVariables.storeData.page.siteid !== 'msjp' && inputVariables.storeData.page.siteid !== 'msru' && inputVariables.storeData.page.siteid !== 'mskr' && inputVariables.storeData.page.siteid !== 'mstw' && inputVariables.storeData.page.siteid !== 'msmea' && inputVariables.storeData.page.siteid !== 'msgulf' && inputVariables.storeData.page.siteid !== 'msgulf2') {
                            if(!$('.validation').hasClass('hidden-form')){
                                $billingZipRequired.parents('.dr_ms_error').show();
                                $billingZipRequired.parent('.textbox').addClass('error');
                                $billingZipRequired.html(inputVariables.storeData.resources.text.NON_ASCII_CHARACTERS_ERROR);
                                $billingZip.addClass('dr_input_invalid');
                                $billingZip.focus();
                            }
                            billingFlag = false;
                        }
                    }
                } else if (billingZipLength > 0) {
                    if(!$('.validation').hasClass('hidden-form')){
                        $billingZipRequired.html('');
                    }
                }
            }

            $billingPhone.removeClass('dr_input_invalid');
            $billingPhoneRequired.parent('.textbox').removeClass('error');
            if($billingPhoneRequired.length){
                if ($billingPhoneDataLength !== 0) {
                    if (billingPhoneLength <= 0) {
                        if(!$('.validation').hasClass('hidden-form')){
                            $billingPhoneRequired.parents('.dr_ms_error').show();
                            $billingPhoneRequired.parent('.textbox').addClass('error');
                            var phoneErrorMsg = inputVariables.storeData.resources.text.BILLINGADDRESS_PHONE_ERROR;
                            phoneErrorMsg = phoneErrorMsg.replace('10', '1');
                            $billingPhoneRequired.html(phoneErrorMsg);
                            $billingPhone.addClass('dr_input_invalid');
                            $billingPhone.focus();
                        }
                        billingFlag = false;
                    } else if (!validation.validateFormFields.validateNonASCII($billingPhone.val())) {
                        if (inputVariables.storeData.page.siteid !== 'msjp' && inputVariables.storeData.page.siteid !== 'msru' && inputVariables.storeData.page.siteid !== 'mskr' && inputVariables.storeData.page.siteid !== 'mstw' && inputVariables.storeData.page.siteid !== 'msmea' && inputVariables.storeData.page.siteid !== 'msgulf' && inputVariables.storeData.page.siteid !== 'msgulf2') {
                            if(!$('.validation').hasClass('hidden-form')){
                                $billingPhoneRequired.parents('.dr_ms_error').show();
                                $billingPhoneRequired.parent('.textbox').addClass('error');
                                $billingPhoneRequired.html(inputVariables.storeData.resources.text.NON_ASCII_CHARACTERS_ERROR);
                                $billingPhone.addClass('dr_input_invalid');
                                $billingPhone.focus();
                            }
                            billingFlag = false;
                        }
                    } else if (billingPhoneLength >= 1) {
                        if(!$('.validation').hasClass('hidden-form')){
                            $billingPhoneRequired.html('');
                        }
                    }
                }
            }

            $billingLastName.removeClass('dr_input_invalid');
            $billingLastNameRequired.parent('.textbox').removeClass('error');
            if($billingLastNameRequired.length){
                if (billingLastNameLength === 0) {
                    if(!$('.validation').hasClass('hidden-form')){
                        $billingLastNameRequired.parents('.dr_ms_error').show();
                        $billingLastNameRequired.parent('.textbox').addClass('error');
                        $billingLastNameRequired.html(inputVariables.storeData.resources.text.BILLINGADDRESS_NAME2_ERROR);
                        $billingLastName.parent().show();
                        $billingLastName.parent().siblings('.applyButton').show();
                        $billingLastName.addClass('dr_input_invalid');
                        $billingLastName.focus();
                    }
                    billingFlag = false;
                } else if (!validation.validateFormFields.validateNonASCII($billingLastName.val())) {
                    if (inputVariables.storeData.page.siteid !== 'msjp' && inputVariables.storeData.page.siteid !== 'msru' && inputVariables.storeData.page.siteid !== 'mskr' && inputVariables.storeData.page.siteid !== 'mstw' && inputVariables.storeData.page.siteid !== 'msmea' && inputVariables.storeData.page.siteid !== 'msgulf' && inputVariables.storeData.page.siteid !== 'msgulf2') {
                        if(!$('.validation').hasClass('hidden-form')){
                            $billingLastNameRequired.parents('.dr_ms_error').show();
                            $billingLastNameRequired.parent('.textbox').addClass('error');
                            $billingLastNameRequired.html(inputVariables.storeData.resources.text.NON_ASCII_CHARACTERS_ERROR);
                            $billingLastName.parent().show();
                            $billingLastName.parent().siblings('.applyButton').show();
                            $billingLastName.addClass('dr_input_invalid');
                            $billingLastName.focus();
                        }
                        billingFlag = false;
                    }
                } else if (billingLastNameLength > 0) {
                    if(!$('.validation').hasClass('hidden-form')){
                        $billingLastNameRequired.html('');
                    }
                }
            }

            $billingFirstName.removeClass('dr_input_invalid');
            $billingFirstNameRequired.parent('.textbox').removeClass('error');
            if($billingFirstNameRequired.length){
                if (billingFirstNameLength === 0) {
                    if(!$('.validation').hasClass('hidden-form')){
                        $billingFirstNameRequired.parents('.dr_ms_error').show();
                        $billingFirstNameRequired.parent('.textbox').addClass('error');
                        $billingFirstNameRequired.html(inputVariables.storeData.resources.text.BILLINGADDRESS_NAME1_ERROR);
                        $billingFirstName.addClass('dr_input_invalid');
                        $billingFirstName.parent().show();
                        $billingFirstName.parent().siblings('.applyButton').show();
                        $billingFirstName.focus();
                    }
                    billingFlag = false;
                } else if (!validation.validateFormFields.validateNonASCII($billingFirstName.val())) {
                    if (inputVariables.storeData.page.siteid !== 'msjp' && inputVariables.storeData.page.siteid !== 'msru' && inputVariables.storeData.page.siteid !== 'mskr' && inputVariables.storeData.page.siteid !== 'mstw' && inputVariables.storeData.page.siteid !== 'msmea' && inputVariables.storeData.page.siteid !== 'msgulf' && inputVariables.storeData.page.siteid !== 'msgulf2') {
                        if(!$('.validation').hasClass('hidden-form')){
                            $billingFirstNameRequired.parents('.dr_ms_error').show();
                            $billingFirstNameRequired.parent('.textbox').addClass('error');
                            $billingFirstNameRequired.html(inputVariables.storeData.resources.text.NON_ASCII_CHARACTERS_ERROR);
                            $billingFirstName.addClass('dr_input_invalid');
                            $billingFirstName.parent().show();
                            $billingFirstName.parent().siblings('.applyButton').show();
                            $billingFirstName.focus();
                        }
                        billingFlag = false;
                    }
                } else if (billingFirstNameLength > 0) {
                    if(!$('.validation').hasClass('hidden-form')){
                        $billingFirstNameRequired.html('');
                    }
                }
            }

            if (billingEmailLength !== -1) {
                $billingEmail.removeClass('dr_input_invalid');
                $billingEmailRequired.parent('.textbox').removeClass('error');
                $billingConfirmEmail.removeClass('dr_input_invalid');
                $billingConfirmEmailRequired.parent('.textbox').removeClass('error');
                $billingEmail.val($billingEmail.val().replace(/^[\s\u00A0]*|[\s\u00A0]*$/g, ''));
                if($billingEmailRequired.length){
                    if ($billingEmail.length && (billingEmailLength > 0 || $billingEmailRequired.length)) {
                        $billingEmailRequired.html('');
                        $billingConfirmEmailRequired.html('');
                        if (!validation.validateFormFields.validateEmail($billingEmail.val())) {
                            if(!$('.validation').hasClass('hidden-form')){
                                errorMessage = inputVariables.storeData.resources.text.ENTER_EMAIL_ADDRESS;
                                if ($billingEmail.val().length < 7) {
                                    errorMessage = inputVariables.storeData.resources.text.EMAIL_ADDRESSES_MUST_BE_7_CHARACTERS;
                                }
                                $billingEmailRequired.parents('.dr_ms_error').show();
                                $billingEmailRequired.parent('.textbox').addClass('error');
                                $billingEmailRequired.html(errorMessage);
                                $billingEmail.addClass('dr_input_invalid');
                                if (billingFlag) {
                                    $billingEmail.focus();
                                }
                            }
                            billingFlag = false;
                        } else if (!validation.validateFormFields.validateNonASCII($billingEmail.val())) {
                            if (inputVariables.storeData.page.siteid !== 'msjp' && inputVariables.storeData.page.siteid !== 'msru' && inputVariables.storeData.page.siteid !== 'mskr' && inputVariables.storeData.page.siteid !== 'mstw' && inputVariables.storeData.page.siteid !== 'msmea' && inputVariables.storeData.page.siteid !== 'msgulf' && inputVariables.storeData.page.siteid !== 'msgulf2') {
                                if(!$('.validation').hasClass('hidden-form')){
                                    $billingEmailRequired.parents('.dr_ms_error').show();
                                    $billingEmailRequired.parent('.textbox').addClass('error');
                                    $billingEmailRequired.html(inputVariables.storeData.resources.text.NON_ASCII_CHARACTERS_ERROR);
                                    $billingEmail.addClass('dr_input_invalid');
                                    if (flag) {
                                        $billingEmail.focus();
                                    }
                                }
                                billingFlag = false;
                            }
                        } else if ($billingConfirmEmail.length && $billingConfirmEmail.val() !== $billingEmail.val()) {
                            if(!$('.validation').hasClass('hidden-form')){
                                errorMessage = inputVariables.storeData.resources.text.EMAIL_ADDRESSES_MUST_MATCH;
                                $billingConfirmEmailRequired.parents('.dr_ms_error').show();
                                $billingConfirmEmailRequired.parent('.textbox').addClass('error');
                                $billingConfirmEmailRequired.html(errorMessage);
                                $billingConfirmEmail.addClass('dr_input_invalid');
                                if (billingFlag) {
                                    $billingConfirmEmail.focus();
                                }
                            }
                            billingFlag = false;
                        } else if (!validation.validateFormFields.validateNonASCII($billingConfirmEmail.val())) {
                            if (inputVariables.storeData.page.siteid !== 'msjp' && inputVariables.storeData.page.siteid !== 'msru' && inputVariables.storeData.page.siteid !== 'mskr' && inputVariables.storeData.page.siteid !== 'mstw' && inputVariables.storeData.page.siteid !== 'msmea' && inputVariables.storeData.page.siteid !== 'msgulf' && inputVariables.storeData.page.siteid !== 'msgulf2') {
                                if(!$('.validation').hasClass('hidden-form')){
                                    $billingConfirmEmailRequired.parents('.dr_ms_error').show();
                                    $billingConfirmEmailRequired.parent('.textbox').addClass('error');
                                    $billingConfirmEmailRequired.html(inputVariables.storeData.resources.text.NON_ASCII_CHARACTERS_ERROR);
                                    $billingConfirmEmail.addClass('dr_input_invalid');
                                    if (billingFlag) {
                                        $billingConfirmEmail.focus();
                                    }
                                }
                                billingFlag = false;
                            }
                        } else {
                            $billingEmail.blur();
                            $billingConfirmEmail.blur();
                        }
                    }
                }
            }
            var $nickname = $('[data-name=nickname]', $parentContainer),
                nicknameLength = $nickname.length !== 0 ? $.trim($nickname.val()).length : -1,
                $nicknameRequired = $('[required=nickname]', $parentContainer),

                $firstname = $('[data-name=name1]', $parentContainer),
                firstnameLength = $firstname.length !== 0 ? $.trim($firstname.val()).length : -1,
                $firstnameRequired = $('[required=name1]', $parentContainer),

                $lastname = $('[data-name=name2]', $parentContainer),
                lastnameLength = $lastname.length !== 0 ? $.trim($lastname.val()).length : -1,
                $lastnameRequired = $('[required=name2]', $parentContainer),

                $addr1 = $('[data-name=line1], [name=SHIPPINGline1]', $parentContainer),
                addr1Length = $addr1.length !== 0 ? $.trim($addr1.val()).length : -1,
                $addr1Required = $('[required=address1], [required=shippingAddress1]', $parentContainer),

                $addr2 = $('[data-name=line2], [name=SHIPPINGline2]', $parentContainer),
                $addr2Required = $('[required=address2], [required=shippingAddress2]', $parentContainer),

                $city = $('[data-name=city], [name=SHIPPINGcity]', $parentContainer),
                cityLength = $city.length !== 0 ? $.trim($city.val()).length : -1,
                $cityRequired = $('[required=city], [required=shippingCity]', $parentContainer),

                $state = $('[data-name=state], [name=SHIPPINGstate]', $parentContainer),
                stateLength = $state.length !== 0 ? $.trim($state.val()).length : -1,
                $stateRequired = $('[validate=state], [required=shippingState]', $parentContainer),
                $idState = $('[data-name=idState]', $parentContainer),
                idStateLength = $idState.length !== 0 ? $.trim($idState.val()).length : -1,
                $idStateRequired = $('[validate=idState]', $parentContainer),

                $SHIPPINGcountry = $('[name=SHIPPINGcountry],[name=country],[data-name=country]', $parentContainer),
                $SHIPPINGcountryVal = $('[name=SHIPPINGcountry],[name=country],[data-name=country]', $parentContainer).val(),
                SHIPPINGcountryLength = $SHIPPINGcountry.length !== 0 ? $.trim($SHIPPINGcountry.val()).length : -1,
                $SHIPPINGcountryRequired = $('[required=shippingCountry]', $parentContainer),

                $zip = $('[data-name=postalCode], [name=SHIPPINGpostalCode]', $parentContainer),
                zipLength = $zip.length !== 0 ? $.trim($zip.val()).length : -1,
                $zipRequired = $('[required=zip], [required=shippingPostalCode]', $parentContainer),

                $zipCode = $('[data-name=zipCode]', $parentContainer),
                zipCodeLength = $zipCode.length !== 0 ? $.trim($zipCode.val()).length : -1,
                $zipCodeRequired = $('[required=zipCode]', $parentContainer),

                $name1 = $('[data-name=firstName]', $parentContainer),
                name1Length = $name1.length !== 0 ? $.trim($name1.val()).length : -1,
                $name1Required = $('[required=firstName]', $parentContainer),

                $name2 = $('[data-name=lastName]', $parentContainer),
                name2Length = $name2.length !== 0 ? $.trim($name2.val()).length : -1,
                $name2Required = $('[required=lastName]', $parentContainer),

                $SHIPPINGname1 = $('[name=SHIPPINGname1]', $parentContainer),
                SHIPPINGname1Length = $SHIPPINGname1.length !== 0 ? $.trim($SHIPPINGname1.val()).length : -1,
                $SHIPPINGname1Required = $('[required=shippingName1]', $parentContainer),

                $SHIPPINGname2 = $('[name=SHIPPINGname2]', $parentContainer),
                SHIPPINGname2Length = $SHIPPINGname2.length !== 0 ? $.trim($SHIPPINGname2.val()).length : -1,
                $SHIPPINGname2Required = $('[required=shippingName2]', $parentContainer),

                $email = $('[data-name=email]', $parentContainer),
                emailLength = $email.length !== 0 ? $.trim($email.val()).length : -1,
                $emailRequired = $('[required=email]', $parentContainer),

                $confirmEmail = $('[data-name=confirmEmail]', $parentContainer),
                confirmEmailLength = $confirmEmail.length !== 0 ? $.trim($confirmEmail.val()).length : -1,
                $confirmEmailRequired = $('[required=confirmEmail]', $parentContainer),

                $phone = $('[data-name=phoneNumber], [name=SHIPPINGphoneNumber]', $parentContainer),
                $phoneDataLength = $('[data-name=phoneNumber], [name=SHIPPINGphoneNumber]', $parentContainer).length,
                phoneLength = 0,

                minPhoneLength = isNaN(inputVariables.storeData.resources.text.resKey) ? (isNaN(inputVariables.storeData.resources.text.SiteSetting_PhoneNumberMinLength) ? 10 : inputVariables.storeData.resources.text.SiteSetting_PhoneNumberMinLength) : inputVariables.storeData.resources.text.resKey,
                $phoneRequired = $('[required=phone], [required=shippingPhoneNumber]', $parentContainer),
                $billingPhoneRequired = $('[required=billingPhoneNumber]', $parentContainer),
                phoneValidationRequired = inputVariables.storeData.resources.text.isReqPhone,
                $accountNum = $('[data-name=accountNumber]', $parentContainer),
                accountNumLength = $accountNum.length !== 0 ? $.trim($accountNum.val()).length : -1,
                $accountNumRequired = $('[required=accountNumber]', $parentContainer),
                $password = $('[data-name=password]', $parentContainer),
                passwordLength = $password.length !== 0 ? $.trim($password.val()).length : -1,
                $passwordRequired = $('[required=password]', $parentContainer),
                $govtIdentificationNumber = $('[data-name=govtIdentificationNumber]', $parentContainer),
                govtIdentificationNumberLength = $govtIdentificationNumber.length !== 0 ? $.trim($govtIdentificationNumber.val()).length : -1,
                $govtIdentificationNumberRequired = $('[required=govtIdentificationNumber]', $parentContainer),
                $idnumber = $('[data-name=idnumber]', $parentContainer),
                idnumberLength = $idnumber.length !== 0 ? $.trim($idnumber.val()).length : -1,
                $idnumberRequired = $('[required=idnumber]', $parentContainer),
                $expdate = $('[data-name=expirationdate]', $parentContainer),
                expdateLength = $expdate.length !== 0 ? $.trim($expdate.val()).length : -1,
                $expdateRequired = $('[required=expirationdate]', $parentContainer),
                $birthday = $('[data-name=birthday]', $parentContainer),
                birthdayLength = $birthday.length !== 0 ? $.trim($birthday.val()).length : -1,
                $birthdayRequired = $('[required=birthday]', $parentContainer),
                $ssn = $('[data-name=ssn]', $parentContainer),
                $ssnDataLength = $('[data-name=ssn]', $parentContainer).length,
                ssnLength = 0,
                $ssnRequired = $('[required=ssn]', $parentContainer),
                $mobile = $('[data-name=mobileNumber]', $parentContainer),
                $mobileDataLength = $('[data-name=mobileNumber]', $parentContainer).length,
                mobileLength = 0,
                $mobileRequired = $('[required=mobileNumber]', $parentContainer);

            if ($('select#shippingCountry,select#country').val() == 'NO') {
                minPhoneLength = 8;
            }
            if (phoneValidationRequired == 'false') {
                phoneLength = 100;
            }
            $phone.each(function () {
                phoneLength += $.trim($(this).val()).length !== 0 ? $.trim($(this).val()).length : 0;
            });
            phoneLength = phoneLength !== 0 ? phoneLength : -1;
            $ssn.each(function () {
                ssnLength += $.trim($(this).val()).length !== 0 ? $.trim($(this).val()).length : 0;
            });
            ssnLength = ssnLength !== 0 ? ssnLength : -1;
            $mobile.each(function () {
                mobileLength += $.trim($(this).val()).length !== 0 ? $.trim($(this).val()).length : 0;
            });
            mobileLength = mobileLength !== 0 ? mobileLength : -1;

            $nickname.removeClass('dr_input_invalid');
            if($nicknameRequired.length){
                if (nicknameLength === 0) {
                    if(!$('.validation').hasClass('hidden-form')){
                        $nicknameRequired.parents('.dr_ms_error').show();
                        $nicknameRequired.html(inputVariables.storeData.resources.text.BILLINGADDRESS_NICKNAME_ERROR);
                        $nickname.addClass('dr_input_invalid');
                        $nickname.focus();
                    }
                    flag = false;
                } else if (!validation.validateFormFields.validateNonASCII($nickname.val())) {
                    if (inputVariables.storeData.page.siteid !== 'msjp' && inputVariables.storeData.page.siteid !== 'msru' && inputVariables.storeData.page.siteid !== 'mskr' && inputVariables.storeData.page.siteid !== 'mstw' && inputVariables.storeData.page.siteid !== 'msmea' && inputVariables.storeData.page.siteid !== 'msgulf' && inputVariables.storeData.page.siteid !== 'msgulf2') {
                        if(!$('.validation').hasClass('hidden-form')){
                            $nicknameRequired.parents('.dr_ms_error').show();
                            $nicknameRequired.html(inputVariables.storeData.resources.text.NON_ASCII_CHARACTERS_ERROR);
                            $nickname.addClass('dr_input_invalid');
                            $nickname.focus();
                        }
                        flag = false;
                    }
                } else if (nicknameLength > 0) {
                    if(!$('.validation').hasClass('hidden-form')){
                        $nicknameRequired.html('');
                    }
                }
            }

            $addr1.removeClass('dr_input_invalid');
            $addr1Required.parent('.textbox').removeClass('error');
            if($addr1Required.length){
                if ($addr1.length) {
                    if (addr1Length === 0) {
                        if(!$('.validation').hasClass('hidden-form')){
                            $addr1Required.parents('.dr_ms_error').show();
                            $addr1Required.parent('.textbox').addClass('error');
                            $addr1Required.html(inputVariables.storeData.resources.text.BILLINGADDRESS_LINE1_ERROR);
                            $addr1.addClass('dr_input_invalid');
                            $addr1.focus();
                        }
                        flag = false;
                    } else if (!validation.validateFormFields.validateNonASCII($addr1.val())) {
                        if (inputVariables.storeData.page.siteid !== 'msjp' && inputVariables.storeData.page.siteid !== 'msru' && inputVariables.storeData.page.siteid !== 'mskr' && inputVariables.storeData.page.siteid !== 'mstw' && inputVariables.storeData.page.siteid !== 'msmea' && inputVariables.storeData.page.siteid !== 'msgulf' && inputVariables.storeData.page.siteid !== 'msgulf2') {
                            if(!$('.validation').hasClass('hidden-form')){
                                $addr1Required.parents('.dr_ms_error').show();
                                $addr1Required.parent('.textbox').addClass('error');
                                $addr1Required.html(inputVariables.storeData.resources.text.NON_ASCII_CHARACTERS_ERROR);
                                $addr1.addClass('dr_input_invalid');
                                $addr1.focus();
                            }
                            flag = false;
                        }
                    } else if (validation.validateFormFields.validateCharacters($addr1.val())) {
                        if(!$('.validation').hasClass('hidden-form')){
                            $addr1Required.parents('.dr_ms_error').show();
                            $addr1Required.parent('.textbox').addClass('error');
                            $addr1Required.html(inputVariables.storeData.resources.text.BILLINGADDRESS_LINE1_ERROR);
                            $addr1.addClass('dr_input_invalid');
                            $addr1.focus();
                        }
                        flag = false;
                    } else {
                        if (validation.validateFormFields.validatePOBox($addr1.val())) {
                            if(!$('.validation').hasClass('hidden-form')){
                                $addr1Required.parents('.dr_ms_error').show();
                                $addr1Required.parent('.textbox').addClass('error');
                                $addr1Required.html(inputVariables.storeData.resources.text.BILLINGADDRESS_LINE1_ERROR);
                                $addr1.addClass('dr_input_invalid');
                                $addr1.focus();
                            }
                            flag = false;
                        } else {
                            if(!$('.validation').hasClass('hidden-form')){
                                $addr1Required.html('');
                            }
                        }
                    }
                }
            }

            $addr2.removeClass('dr_input_invalid');
            $addr2Required.parent('.textbox').removeClass('error');
            if($addr2Required.length){
                if ($addr2.length) {
                    if (validation.validateFormFields.validatePOBox($addr2.val())) {
                        if(!$('.validation').hasClass('hidden-form')){
                            $addr2Required.parents('.dr_ms_error').show();
                            $addr2Required.parent('.textbox').addClass('error');
                            $addr2Required.html(inputVariables.storeData.resources.text.BILLINGADDRESS_LINE1_ERROR);
                            $addr2.addClass('dr_input_invalid');
                            $addr2.focus();
                        }
                        flag = false;
                    } else if (!validation.validateFormFields.validateNonASCII($addr2.val())) {
                        if (inputVariables.storeData.page.siteid !== 'msjp' && inputVariables.storeData.page.siteid !== 'msru' && inputVariables.storeData.page.siteid !== 'mskr' && inputVariables.storeData.page.siteid !== 'mstw' && inputVariables.storeData.page.siteid !== 'msmea' && inputVariables.storeData.page.siteid !== 'msgulf' && inputVariables.storeData.page.siteid !== 'msgulf2') {
                            if(!$('.validation').hasClass('hidden-form')){
                                $addr2Required.parents('.dr_ms_error').show();
                                $addr2Required.parent('.textbox').addClass('error');
                                $addr2Required.html(inputVariables.storeData.resources.text.NON_ASCII_CHARACTERS_ERROR);
                                $addr2.addClass('dr_input_invalid');
                                $addr2.focus();
                            }
                            flag = false;
                        }
                    } else if (validation.validateFormFields.validateCharacters($addr2.val())) {
                        if(!$('.validation').hasClass('hidden-form')){
                            $addr2Required.parents('.dr_ms_error').show();
                            $addr2Required.parent('.textbox').addClass('error');
                            $addr2Required.html(inputVariables.storeData.resources.text.BILLINGADDRESS_LINE1_ERROR);
                            $addr2.addClass('dr_input_invalid');
                            $addr2.focus();
                        }
                        flag = false;
                    } else {
                        if(!$('.validation').hasClass('hidden-form')){
                            $addr2Required.html('');
                        }
                    }
                }
            }

            $city.removeClass('dr_input_invalid');
            $cityRequired.parent('.textbox').removeClass('error');
            if($cityRequired.length){
                if (cityLength === 0) {
                    if(!$('.validation').hasClass('hidden-form')){
                        $cityRequired.parents('.dr_ms_error').show();
                        $cityRequired.parent('.textbox').addClass('error');
                        $cityRequired.html(inputVariables.storeData.resources.text.BILLINGADDRESS_CITY_ERROR);
                        $city.addClass('dr_input_invalid');
                        $city.focus();
                    }
                    flag = false;
                } else if (!validation.validateFormFields.validateNonASCII($city.val())) {
                    if (inputVariables.storeData.page.siteid !== 'msjp' && inputVariables.storeData.page.siteid !== 'msru' && inputVariables.storeData.page.siteid !== 'mskr' && inputVariables.storeData.page.siteid !== 'mstw' && inputVariables.storeData.page.siteid !== 'msmea' && inputVariables.storeData.page.siteid !== 'msgulf' && inputVariables.storeData.page.siteid !== 'msgulf2') {
                        if(!$('.validation').hasClass('hidden-form')){
                            $cityRequired.parents('.dr_ms_error').show();
                            $cityRequired.parent('.textbox').addClass('error');
                            $cityRequired.html(inputVariables.storeData.resources.text.NON_ASCII_CHARACTERS_ERROR);
                            $city.addClass('dr_input_invalid');
                            $city.focus();
                        }
                        flag = false;
                    }
                } else if (validation.validateFormFields.validateCharacters($city.val())) {
                    if(!$('.validation').hasClass('hidden-form')){
                        $cityRequired.parents('.dr_ms_error').show();
                        $cityRequired.parent('.textbox').addClass('error');
                        $cityRequired.html(inputVariables.storeData.resources.text.BILLINGADDRESS_CITY_ERROR);
                        $city.addClass('dr_input_invalid');
                        $city.focus();
                    }
                    flag = false;
                } else if (cityLength > 0) {
                    if(!$('.validation').hasClass('hidden-form')){
                        $cityRequired.html('');
                    }
                }
            }

            $state.removeClass('dr_input_invalid');
            $stateRequired.parent('.textbox').removeClass('error');
            if($stateRequired.length){
                if (stateLength === 0) {
                    if(!$('.validation').hasClass('hidden-form')){
                        $stateRequired.parents('.dr_ms_error').show();
                        $stateRequired.parent('.textbox,.drop-down-list').addClass('error');
                        $stateRequired.html(inputVariables.storeData.resources.text.BILLINGADDRESS_STATE_ERROR);
                        $state.addClass('dr_input_invalid');
                        $state.focus();
                    }
                    flag = false;
                } else if (!validation.validateFormFields.validateNonASCII($state.val())) {
                    if (inputVariables.storeData.page.siteid !== 'msjp' && inputVariables.storeData.page.siteid !== 'msru' && inputVariables.storeData.page.siteid !== 'mskr' && inputVariables.storeData.page.siteid !== 'mstw' && inputVariables.storeData.page.siteid !== 'msmea' && inputVariables.storeData.page.siteid !== 'msgulf' && inputVariables.storeData.page.siteid !== 'msgulf2') {
                        if(!$('.validation').hasClass('hidden-form')){
                            $stateRequired.parents('.dr_ms_error').show();
                            $stateRequired.parent('.textbox,.drop-down-list').addClass('error');
                            $stateRequired.html(inputVariables.storeData.resources.text.NON_ASCII_CHARACTERS_ERROR);
                            $state.addClass('dr_input_invalid');
                            $state.focus();
                        }
                        flag = false;
                    }
                } else if (stateLength > 0) {
                    if(!$('.validation').hasClass('hidden-form')){
                        $stateRequired.html('');
                    }
                }
            }

            $idState.removeClass('dr_input_invalid');
            if($idStateRequired.length){
                if (idStateLength === 0) {
                    if(!$('.validation').hasClass('hidden-form')){
                        $idStateRequired.parents('.dr_ms_error').show();
                        $idStateRequired.html(inputVariables.storeData.resources.text.BILLINGADDRESS_STATE_ERROR);
                        $idState.addClass('dr_input_invalid');
                        $idState.focus();
                    }
                    flag = false;
                } else if (!validation.validateFormFields.validateNonASCII($idState.val())) {
                    if (inputVariables.storeData.page.siteid !== 'msjp' && inputVariables.storeData.page.siteid !== 'msru' && inputVariables.storeData.page.siteid !== 'mskr' && inputVariables.storeData.page.siteid !== 'mstw' && inputVariables.storeData.page.siteid !== 'msmea' && inputVariables.storeData.page.siteid !== 'msgulf' && inputVariables.storeData.page.siteid !== 'msgulf2') {
                        if(!$('.validation').hasClass('hidden-form')){
                            $idStateRequired.parents('.dr_ms_error').show();
                            $idStateRequired.html(inputVariables.storeData.resources.text.NON_ASCII_CHARACTERS_ERROR);
                            $idState.addClass('dr_input_invalid');
                            $idState.focus();
                        }
                        flag = false;
                    }
                } else if (idStateLength > 0) {
                    if(!$('.validation').hasClass('hidden-form')){
                        $idStateRequired.html('');
                    }
                }
            }

            $SHIPPINGcountry.removeClass('dr_input_invalid');
            if($SHIPPINGcountryRequired.length){
                if (SHIPPINGcountryLength === 0) {
                    if(!$('.validation').hasClass('hidden-form')){
                        $SHIPPINGcountryRequired.parents('.dr_ms_error').show();
                        $SHIPPINGcountryRequired.html(inputVariables.storeData.resources.text.BILLINGADDRESS_COUNTRY_ERROR);
                        $SHIPPINGcountry.addClass('dr_input_invalid');
                        $SHIPPINGcountry.focus();
                    }
                    flag = false;
                } else if (!validation.validateFormFields.validateNonASCII($SHIPPINGcountry.val())) {
                    if (inputVariables.storeData.page.siteid !== 'msjp' && inputVariables.storeData.page.siteid !== 'msru' && inputVariables.storeData.page.siteid !== 'mskr' && inputVariables.storeData.page.siteid !== 'mstw' && inputVariables.storeData.page.siteid !== 'msmea' && inputVariables.storeData.page.siteid !== 'msgulf' && inputVariables.storeData.page.siteid !== 'msgulf2') {
                        if(!$('.validation').hasClass('hidden-form')){
                            $SHIPPINGcountryRequired.parents('.dr_ms_error').show();
                            $SHIPPINGcountryRequired.html(inputVariables.storeData.resources.text.NON_ASCII_CHARACTERS_ERROR);
                            $SHIPPINGcountry.addClass('dr_input_invalid');
                            $SHIPPINGcountry.focus();
                        }
                        flag = false;
                    }
                } else if (SHIPPINGcountryLength > 0) {
                    if(!$('.validation').hasClass('hidden-form')){
                        $SHIPPINGcountryRequired.html('');
                    }
                }
            }

            $zip.removeClass('dr_input_invalid');
            var isNullAccepted = $zipRequired.length > 0 ? false : zipLength > 0 ? false : true;
            $zipRequired.parent('.textbox').removeClass('error');
            if($zipRequired.length){
                if (!isNullAccepted) {
                    if (zipLength === 0 || (zipLength > 0 && !validation.validateFormFields.validatePstalCodeByCountry($.trim($zip.val()), $SHIPPINGcountryVal))) {
                        if(!$('.validation').hasClass('hidden-form')){
                            $zipRequired.parents('.dr_ms_error').show();
                            $zipRequired.parent('.textbox').addClass('error');
                            $zipRequired.html(inputVariables.storeData.resources.text.BILLINGADDRESS_POSTALCODE_ERROR);
                            $zip.addClass('dr_input_invalid');
                            $zip.focus();
                        }
                        flag = false;
                    } else if (!validation.validateFormFields.validateNonASCII($zip.val())) {
                        if (inputVariables.storeData.page.siteid !== 'msjp' && inputVariables.storeData.page.siteid !== 'msru' && inputVariables.storeData.page.siteid !== 'mskr' && inputVariables.storeData.page.siteid !== 'mstw' && inputVariables.storeData.page.siteid !== 'msmea' && inputVariables.storeData.page.siteid !== 'msgulf' && inputVariables.storeData.page.siteid !== 'msgulf2') {
                            if(!$('.validation').hasClass('hidden-form')){
                                $zipRequired.parents('.dr_ms_error').show();
                                $zipRequired.parent('.textbox').addClass('error');
                                $zipRequired.html(inputVariables.storeData.resources.text.NON_ASCII_CHARACTERS_ERROR);
                                $zip.addClass('dr_input_invalid');
                                $zip.focus();
                            }
                            flag = false;
                        }
                    }
                } else if (zipLength > 0) {
                    if(!$('.validation').hasClass('hidden-form')){
                        $zipRequired.html('');
                    }
                }
            }

            $zipCode.removeClass('dr_input_invalid');
            $zipCodeRequired.parent('.textbox').removeClass('error');
            if($zipCodeRequired.length){
                if (zipCodeLength === 0 || (zipCodeLength > 0 && !validation.validateFormFields.validateZipCode($.trim($zipCode.val())))) {
                    if(!$('.validation').hasClass('hidden-form')){
                        $zipCodeRequired.parents('.dr_ms_error').show();
                        $zipCodeRequired.parent('.textbox').addClass('error');
                        $zipCodeRequired.html(inputVariables.storeData.resources.text.BILLINGADDRESS_POSTALCODE_ERROR);
                        $zipCode.addClass('dr_input_invalid');
                        $zipCode.focus();
                    }
                    flag = false;
                } else if (!validation.validateFormFields.validateNonASCII($zipCode.val())) {
                    if (inputVariables.storeData.page.siteid !== 'msjp' && inputVariables.storeData.page.siteid !== 'msru' && inputVariables.storeData.page.siteid !== 'mskr' && inputVariables.storeData.page.siteid !== 'mstw' && inputVariables.storeData.page.siteid !== 'msmea' && inputVariables.storeData.page.siteid !== 'msgulf' && inputVariables.storeData.page.siteid !== 'msgulf2') {
                        if(!$('.validation').hasClass('hidden-form')){
                            $zipCodeRequired.parents('.dr_ms_error').show();
                            $zipCodeRequired.html(inputVariables.storeData.resources.text.NON_ASCII_CHARACTERS_ERROR);
                            $zipCode.addClass('dr_input_invalid');
                            $zipCode.focus();
                        }
                        flag = false;
                    }
                } else if (zipCodeLength > 0) {
                    if(!$('.validation').hasClass('hidden-form')){
                        $zipCodeRequired.html('');
                    }
                }
            }

            $phone.removeClass('dr_input_invalid');
            $phoneRequired.parent('.textbox').removeClass('error');
            if($phoneRequired.length){
                if ($phoneDataLength !== 0) {
                    if (phoneLength <= 0) {
                        if(!$('.validation').hasClass('hidden-form')){
                            $phoneRequired.parents('.dr_ms_error').show();
                            $phoneRequired.parent('.textbox').addClass('error');
                            var phoneErrorMsg = inputVariables.storeData.resources.text.BILLINGADDRESS_PHONE_ERROR;
                            phoneErrorMsg = phoneErrorMsg.replace('10', '1');
                            $phoneRequired.html(phoneErrorMsg);
                            $phone.addClass('dr_input_invalid');
                            $phone.focus();
                        }
                        flag = false;
                    } else if (!validation.validateFormFields.validateNonASCII($phone.val())) {
                        if (inputVariables.storeData.page.siteid !== 'msjp' && inputVariables.storeData.page.siteid !== 'msru' && inputVariables.storeData.page.siteid !== 'mskr' && inputVariables.storeData.page.siteid !== 'mstw' && inputVariables.storeData.page.siteid !== 'msmea' && inputVariables.storeData.page.siteid !== 'msgulf' && inputVariables.storeData.page.siteid !== 'msgulf2') {
                            if(!$('.validation').hasClass('hidden-form')){
                                $phoneRequired.parents('.dr_ms_error').show();
                                $phoneRequired.parent('.textbox').addClass('error');
                                $phoneRequired.html(inputVariables.storeData.resources.text.NON_ASCII_CHARACTERS_ERROR);
                                $phone.addClass('dr_input_invalid');
                                $phone.focus();
                            }
                            flag = false;
                        }
                    } else if (phoneLength >= 1) {
                        if(!$('.validation').hasClass('hidden-form')){
                            $phoneRequired.html('');
                        }
                    }
                }
            }

            $accountNum.removeClass('dr_input_invalid');
            if($accountNumRequired.length){
                if (accountNumLength === 0) {
                    if(!$('.validation').hasClass('hidden-form')){
                        $accountNumRequired.parents('.dr_ms_error').show();
                        $accountNumRequired.html(inputVariables.storeData.resources.text.BILLINGADDRESS_ACCOUNTNUM_ERROR);
                        $accountNum.addClass('dr_input_invalid');
                        $accountNum.focus();
                    }
                    flag = false;
                } else if (!validation.validateFormFields.validateNonASCII($accountNum.val())) {
                    if (inputVariables.storeData.page.siteid !== 'msjp' && inputVariables.storeData.page.siteid !== 'msru' && inputVariables.storeData.page.siteid !== 'mskr' && inputVariables.storeData.page.siteid !== 'mstw' && inputVariables.storeData.page.siteid !== 'msmea' && inputVariables.storeData.page.siteid !== 'msgulf' && inputVariables.storeData.page.siteid !== 'msgulf2') {
                        if(!$('.validation').hasClass('hidden-form')){
                            $accountNumRequired.parents('.dr_ms_error').show();
                            $accountNumRequired.html(inputVariables.storeData.resources.text.NON_ASCII_CHARACTERS_ERROR);
                            $accountNum.addClass('dr_input_invalid');
                            $accountNum.focus();
                        }
                        flag = false;
                    }
                } else if (accountNumLength > 0) {
                    if(!$('.validation').hasClass('hidden-form')){
                        $accountNumRequired.html('');
                    }
                }
            }

            $password.removeClass('dr_input_invalid');
            if($passwordRequired.length){
                if (passwordLength === 0) {
                    if(!$('.validation').hasClass('hidden-form')){
                        $passwordRequired.parents('.dr_ms_error').show();
                        $passwordRequired.html(inputVariables.storeData.resources.text.BILLINGADDRESS_PASSWORD_ERROR);
                        $password.addClass('dr_input_invalid');
                        $password.focus();
                    }
                    flag = false;
                } else if (!validation.validateFormFields.validateNonASCII($password.val())) {
                    if (inputVariables.storeData.page.siteid !== 'msjp' && inputVariables.storeData.page.siteid !== 'msru' && inputVariables.storeData.page.siteid !== 'mskr' && inputVariables.storeData.page.siteid !== 'mstw' && inputVariables.storeData.page.siteid !== 'msmea' && inputVariables.storeData.page.siteid !== 'msgulf' && inputVariables.storeData.page.siteid !== 'msgulf2') {
                        if(!$('.validation').hasClass('hidden-form')){
                            $passwordRequired.parents('.dr_ms_error').show();
                            $passwordRequired.html(inputVariables.storeData.resources.text.NON_ASCII_CHARACTERS_ERROR);
                            $password.addClass('dr_input_invalid');
                            $password.focus();
                        }
                        flag = false;
                    }
                } else if (passwordLength > 0) {
                    if(!$('.validation').hasClass('hidden-form')){
                        $passwordRequired.html('');
                    }
                }
            }

            $govtIdentificationNumber.removeClass('dr_input_invalid');
            if($govtIdentificationNumberRequired.length){
                if (govtIdentificationNumberLength === 0) {
                    if(!$('.validation').hasClass('hidden-form')){
                        $govtIdentificationNumberRequired.parents('.dr_ms_error').show();
                        $govtIdentificationNumberRequired.html(inputVariables.storeData.resources.text.BILLINGADDRESS_GOVIDNUM_ERROR);
                        $govtIdentificationNumber.addClass('dr_input_invalid');
                        $govtIdentificationNumber.focus();
                    }
                    flag = false;
                } else if (!validation.validateFormFields.validateNonASCII($govtIdentificationNumber.val())) {
                    if (inputVariables.storeData.page.siteid !== 'msjp' && inputVariables.storeData.page.siteid !== 'msru' && inputVariables.storeData.page.siteid !== 'mskr' && inputVariables.storeData.page.siteid !== 'mstw' && inputVariables.storeData.page.siteid !== 'msmea' && inputVariables.storeData.page.siteid !== 'msgulf' && inputVariables.storeData.page.siteid !== 'msgulf2') {
                        if(!$('.validation').hasClass('hidden-form')){
                            $govtIdentificationNumberRequired.parents('.dr_ms_error').show();
                            $govtIdentificationNumberRequired.html(inputVariables.storeData.resources.text.NON_ASCII_CHARACTERS_ERROR);
                            $govtIdentificationNumber.addClass('dr_input_invalid');
                            $govtIdentificationNumber.focus();
                        }
                        flag = false;
                    }
                } else if (govtIdentificationNumberLength == 4) {
                    if(!$('.validation').hasClass('hidden-form')){
                        $govtIdentificationNumberRequired.html('');
                    }
                }
            }

            $mobile.removeClass('dr_input_invalid');
            if($mobileRequired.length){
                if ($mobileDataLength !== 0) {
                    if (mobileLength < 10) {
                        if(!$('.validation').hasClass('hidden-form')){
                            $mobileRequired.parents('.dr_ms_error').show();
                            $mobileRequired.html(inputVariables.storeData.resources.text.BILLINGADDRESS_PHONE_ERROR);
                            $mobile.addClass('dr_input_invalid');
                            $mobile.focus();
                        }
                        flag = false;
                    } else if (!validation.validateFormFields.validateNonASCII($mobile.val())) {
                        if (inputVariables.storeData.page.siteid !== 'msjp' && inputVariables.storeData.page.siteid !== 'msru' && inputVariables.storeData.page.siteid !== 'mskr' && inputVariables.storeData.page.siteid !== 'mstw' && inputVariables.storeData.page.siteid !== 'msmea' && inputVariables.storeData.page.siteid !== 'msgulf' && inputVariables.storeData.page.siteid !== 'msgulf2') {
                            if(!$('.validation').hasClass('hidden-form')){
                                $mobileRequired.parents('.dr_ms_error').show();
                                $mobileRequired.html(inputVariables.storeData.resources.text.NON_ASCII_CHARACTERS_ERROR);
                                $mobile.addClass('dr_input_invalid');
                                $mobile.focus();
                            }
                            flag = false;
                        }
                    } else if (mobileLength >= 10) {
                        if(!$('.validation').hasClass('hidden-form')){
                            $mobileRequired.html('');
                        }
                    }
                }
            }

            $idnumber.removeClass('dr_input_invalid');
            if($idnumberRequired.length){
                if (idnumberLength === 0) {
                    if(!$('.validation').hasClass('hidden-form')){
                        $idnumberRequired.parents('.dr_ms_error').show();
                        $idnumberRequired.html(inputVariables.storeData.resources.text.BILLINGADDRESS_IDNUM_ERROR);
                        $idnumber.addClass('dr_input_invalid');
                        $idnumber.focus();
                    }
                    flag = false;
                } else if (!validation.validateFormFields.validateNonASCII($idnumber.val())) {
                    if (inputVariables.storeData.page.siteid !== 'msjp' && inputVariables.storeData.page.siteid !== 'msru' && inputVariables.storeData.page.siteid !== 'mskr' && inputVariables.storeData.page.siteid !== 'mstw' && inputVariables.storeData.page.siteid !== 'msmea' && inputVariables.storeData.page.siteid !== 'msgulf' && inputVariables.storeData.page.siteid !== 'msgulf2') {
                        if(!$('.validation').hasClass('hidden-form')){
                            $idnumberRequired.parents('.dr_ms_error').show();
                            $idnumberRequired.html(inputVariables.storeData.resources.text.NON_ASCII_CHARACTERS_ERROR);
                            $idnumber.addClass('dr_input_invalid');
                            $idnumber.focus();
                        }
                        flag = false;
                    }
                } else if (idnumberLength > 0) {
                    if(!$('.validation').hasClass('hidden-form')){
                        $idnumberRequired.html('');
                    }
                }
            }

            $expdate.removeClass('dr_input_invalid');
            if($expdateRequired.length){
                if (expdateLength === 0) {
                    if(!$('.validation').hasClass('hidden-form')){
                        $expdateRequired.parents('.dr_ms_error').show();
                        $expdateRequired.html(inputVariables.storeData.resources.text.BILLINGADDRESS_EXPDATE_ERROR);
                        $expdate.addClass('dr_input_invalid');
                        $expdate.focus();
                    }
                    flag = false;
                } else if (!validation.validateFormFields.validateNonASCII($expdate.val())) {
                    if (inputVariables.storeData.page.siteid !== 'msjp' && inputVariables.storeData.page.siteid !== 'msru' && inputVariables.storeData.page.siteid !== 'mskr' && inputVariables.storeData.page.siteid !== 'mstw' && inputVariables.storeData.page.siteid !== 'msmea' && inputVariables.storeData.page.siteid !== 'msgulf' && inputVariables.storeData.page.siteid !== 'msgulf2') {
                        if(!$('.validation').hasClass('hidden-form')){
                            $expdateRequired.parents('.dr_ms_error').show();
                            $expdateRequired.html(inputVariables.storeData.resources.text.NON_ASCII_CHARACTERS_ERROR);
                            $expdate.addClass('dr_input_invalid');
                            $expdate.focus();
                        }
                        flag = false;
                    }
                } else if (expdateLength > 0) {
                    if(!$('.validation').hasClass('hidden-form')){
                        $expdateRequired.html('');
                    }
                }
            }

            $birthday.removeClass('dr_input_invalid');
            if($birthdayRequired.length){
                if (birthdayLength === 0) {
                    if(!$('.validation').hasClass('hidden-form')){
                        $birthdayRequired.parents('.dr_ms_error').show();
                        $birthdayRequired.html(inputVariables.storeData.resources.text.BILLINGADDRESS_BIRTHDAY_ERROR);
                        $birthday.addClass('dr_input_invalid');
                        $birthday.focus();
                    }
                    flag = false;
                } else if (!validation.validateFormFields.validateNonASCII($birthday.val())) {
                    if (inputVariables.storeData.page.siteid !== 'msjp' && inputVariables.storeData.page.siteid !== 'msru' && inputVariables.storeData.page.siteid !== 'mskr' && inputVariables.storeData.page.siteid !== 'mstw' && inputVariables.storeData.page.siteid !== 'msmea' && inputVariables.storeData.page.siteid !== 'msgulf' && inputVariables.storeData.page.siteid !== 'msgulf2') {
                        if(!$('.validation').hasClass('hidden-form')){
                            $birthdayRequired.parents('.dr_ms_error').show();
                            $birthdayRequired.html(inputVariables.storeData.resources.text.NON_ASCII_CHARACTERS_ERROR);
                            $birthday.addClass('dr_input_invalid');
                            $birthday.focus();
                        }
                        flag = false;
                    }
                } else if (birthdayLength > 0) {
                    if(!$('.validation').hasClass('hidden-form')){
                        $birthdayRequired.html('');
                    }
                }
            }

            $ssn.removeClass('dr_input_invalid');
            if($ssnRequired.length){
                if ($ssnDataLength !== 0) {
                    if (ssnLength < 9) {
                        if(!$('.validation').hasClass('hidden-form')){
                            $ssnRequired.parents('.dr_ms_error').show();
                            $ssnRequired.html(inputVariables.storeData.resources.text.BILLINGADDRESS_SSN_ERROR);
                            $ssn.addClass('dr_input_invalid');
                            $ssn.focus();
                        }
                        flag = false;
                    } else if (!validation.validateFormFields.validateNonASCII($ssn.val())) {
                        if (inputVariables.storeData.page.siteid !== 'msjp' && inputVariables.storeData.page.siteid !== 'msru' && inputVariables.storeData.page.siteid !== 'mskr' && inputVariables.storeData.page.siteid !== 'mstw' && inputVariables.storeData.page.siteid !== 'msmea' && inputVariables.storeData.page.siteid !== 'msgulf' && inputVariables.storeData.page.siteid !== 'msgulf2') {
                            if(!$('.validation').hasClass('hidden-form')){
                                $ssnRequired.parents('.dr_ms_error').show();
                                $ssnRequired.html(inputVariables.storeData.resources.text.NON_ASCII_CHARACTERS_ERROR);
                                $ssn.addClass('dr_input_invalid');
                                $ssn.focus();
                            }
                            flag = false;
                        }
                    } else if (ssnLength >= 9) {
                        if(!$('.validation').hasClass('hidden-form')){
                            $ssnRequired.html('');
                        }
                    }
                }
            }

            $name2.removeClass('dr_input_invalid');
            $name2Required.parent('.textbox').removeClass('error');
            if($name2Required.length){
                if (name2Length === 0) {
                    if(!$('.validation').hasClass('hidden-form')){
                        $name2Required.parents('.dr_ms_error').show();
                        $name2Required.parent('.textbox').addClass('error');
                        $name2Required.html(inputVariables.storeData.resources.text.BILLINGADDRESS_NAME2_ERROR);
                        $name2.parent().show();
                        $name2.parent().siblings('.applyButton').show();
                        $name2.addClass('dr_input_invalid');
                        $name2.focus();
                    }
                    flag = false;
                } else if (!validation.validateFormFields.validateNonASCII($name2.val())) {
                    if (inputVariables.storeData.page.siteid !== 'msjp' && inputVariables.storeData.page.siteid !== 'msru' && inputVariables.storeData.page.siteid !== 'mskr' && inputVariables.storeData.page.siteid !== 'mstw' && inputVariables.storeData.page.siteid !== 'msmea' && inputVariables.storeData.page.siteid !== 'msgulf' && inputVariables.storeData.page.siteid !== 'msgulf2') {
                        if(!$('.validation').hasClass('hidden-form')){
                            $name2Required.parents('.dr_ms_error').show();
                            $name2Required.parent('.textbox').addClass('error');
                            $name2Required.html(inputVariables.storeData.resources.text.NON_ASCII_CHARACTERS_ERROR);
                            $name2.parent().show();
                            $name2.parent().siblings('.applyButton').show();
                            $name2.addClass('dr_input_invalid');
                            $name2.focus();
                        }
                        flag = false;
                    }
                } else if (name2Length > 0) {
                    if(!$('.validation').hasClass('hidden-form')){
                        $name2Required.html('');
                    }
                }
            }

            $name1.removeClass('dr_input_invalid');
            $name1Required.parent('.textbox').removeClass('error');
            if($name1Required.length){
                if (name1Length === 0) {
                    if(!$('.validation').hasClass('hidden-form')){
                        $name1Required.parents('.dr_ms_error').show();
                        $name1Required.parent('.textbox').addClass('error');
                        $name1Required.html(inputVariables.storeData.resources.text.BILLINGADDRESS_NAME1_ERROR);
                        $name1.addClass('dr_input_invalid');
                        $name1.parent().show();
                        $name1.parent().siblings('.applyButton').show();
                        $name1.focus();
                    }
                    flag = false;
                } else if (!validation.validateFormFields.validateNonASCII($name1.val())) {
                    if (inputVariables.storeData.page.siteid !== 'msjp' && inputVariables.storeData.page.siteid !== 'msru' && inputVariables.storeData.page.siteid !== 'mskr' && inputVariables.storeData.page.siteid !== 'mstw' && inputVariables.storeData.page.siteid !== 'msmea' && inputVariables.storeData.page.siteid !== 'msgulf' && inputVariables.storeData.page.siteid !== 'msgulf2') {
                        if(!$('.validation').hasClass('hidden-form')){
                            $name1Required.parents('.dr_ms_error').show();
                            $name1Required.parent('.textbox').addClass('error');
                            $name1Required.html(inputVariables.storeData.resources.text.NON_ASCII_CHARACTERS_ERROR);
                            $name1.addClass('dr_input_invalid');
                            $name1.parent().show();
                            $name1.parent().siblings('.applyButton').show();
                            $name1.focus();
                        }
                        flag = false;
                    }
                } else if (name1Length > 0) {
                    if(!$('.validation').hasClass('hidden-form')){
                        $name1Required.html('');
                    }
                }
            }

            $lastname.removeClass('dr_input_invalid');
            $lastnameRequired.parent('.textbox').removeClass('error');
            if($lastnameRequired.length){
                if (lastnameLength === 0) {
                    if(!$('.validation').hasClass('hidden-form')){
                        $lastnameRequired.parents('.dr_ms_error').show();
                        $lastnameRequired.parent('.textbox').addClass('error');
                        $lastnameRequired.html(inputVariables.storeData.resources.text.BILLINGADDRESS_NAME2_ERROR);
                        $lastname.parent().show();
                        $lastname.parent().siblings('.applyButton').show();
                        $lastname.addClass('dr_input_invalid');
                        $lastname.focus();
                    }
                    flag = false;
                } else if (!validation.validateFormFields.validateNonASCII($lastname.val())) {
                    if (inputVariables.storeData.page.siteid !== 'msjp' && inputVariables.storeData.page.siteid !== 'msru' && inputVariables.storeData.page.siteid !== 'mskr' && inputVariables.storeData.page.siteid !== 'mstw' && inputVariables.storeData.page.siteid !== 'msmea' && inputVariables.storeData.page.siteid !== 'msgulf' && inputVariables.storeData.page.siteid !== 'msgulf2') {
                        if(!$('.validation').hasClass('hidden-form')){
                            $lastnameRequired.parents('.dr_ms_error').show();
                            $lastnameRequired.parent('.textbox').addClass('error');
                            $lastnameRequired.html(inputVariables.storeData.resources.text.NON_ASCII_CHARACTERS_ERROR);
                            $lastname.parent().show();
                            $lastname.parent().siblings('.applyButton').show();
                            $lastname.addClass('dr_input_invalid');
                            $lastname.focus();
                        }
                        flag = false;
                    }
                } else if (lastnameLength > 0) {
                    if(!$('.validation').hasClass('hidden-form')){
                        $lastnameRequired.html('');
                    }
                }
            }

            $firstname.removeClass('dr_input_invalid');
            $firstnameRequired.parent('.textbox').removeClass('error');
            if($firstnameRequired.length){
                if (firstnameLength === 0) {
                    if(!$('.validation').hasClass('hidden-form')){
                        $firstnameRequired.parents('.dr_ms_error').show();
                        $firstnameRequired.parent('.textbox').addClass('error');
                        $firstnameRequired.html(inputVariables.storeData.resources.text.BILLINGADDRESS_NAME1_ERROR);
                        $firstname.addClass('dr_input_invalid');
                        $firstname.parent().show();
                        $firstname.parent().siblings('.applyButton').show();
                        $firstname.focus();
                    }
                    flag = false;
                } else if (!validation.validateFormFields.validateNonASCII($firstname.val())) {
                    if (inputVariables.storeData.page.siteid !== 'msjp' && inputVariables.storeData.page.siteid !== 'msru' && inputVariables.storeData.page.siteid !== 'mskr' && inputVariables.storeData.page.siteid !== 'mstw' && inputVariables.storeData.page.siteid !== 'msmea' && inputVariables.storeData.page.siteid !== 'msgulf' && inputVariables.storeData.page.siteid !== 'msgulf2') {
                        if(!$('.validation').hasClass('hidden-form')){
                            $firstnameRequired.parents('.dr_ms_error').show();
                            $firstnameRequired.parent('.textbox').addClass('error');
                            $firstnameRequired.html(inputVariables.storeData.resources.text.NON_ASCII_CHARACTERS_ERROR);
                            $firstname.addClass('dr_input_invalid');
                            $firstname.parent().show();
                            $firstname.parent().siblings('.applyButton').show();
                            $firstname.focus();
                        }
                        flag = false;
                    }
                } else if (firstnameLength > 0) {
                    if(!$('.validation').hasClass('hidden-form')){
                        $firstnameRequired.html('');
                    }
                }
            }

            $SHIPPINGname2.removeClass('dr_input_invalid');
            $SHIPPINGname2Required.parent('.textbox').removeClass('error');
            if($SHIPPINGname2Required.length){
                if (SHIPPINGname2Length === 0) {
                    if(!$('.validation').hasClass('hidden-form')){
                        $SHIPPINGname2Required.parents('.dr_ms_error').show();
                        $SHIPPINGname2Required.parent('.textbox').addClass('error');
                        $SHIPPINGname2Required.html(inputVariables.storeData.resources.text.BILLINGADDRESS_NAME2_ERROR);
                        $SHIPPINGname2.parent().show();
                        $SHIPPINGname2.parent().siblings('.applyButton').show();
                        $SHIPPINGname2.addClass('dr_input_invalid');
                        $SHIPPINGname2.focus();
                    }
                    flag = false;
                } else if (!validation.validateFormFields.validateNonASCII($SHIPPINGname2.val())) {
                    if (inputVariables.storeData.page.siteid !== 'msjp' && inputVariables.storeData.page.siteid !== 'msru' && inputVariables.storeData.page.siteid !== 'mskr' && inputVariables.storeData.page.siteid !== 'mstw' && inputVariables.storeData.page.siteid !== 'msmea' && inputVariables.storeData.page.siteid !== 'msgulf' && inputVariables.storeData.page.siteid !== 'msgulf2') {
                        if(!$('.validation').hasClass('hidden-form')){
                            $SHIPPINGname2Required.parents('.dr_ms_error').show();
                            $SHIPPINGname2Required.parent('.textbox').addClass('error');
                            $SHIPPINGname2Required.html(inputVariables.storeData.resources.text.NON_ASCII_CHARACTERS_ERROR);
                            $SHIPPINGname2.parent().show();
                            $SHIPPINGname2.parent().siblings('.applyButton').show();
                            $SHIPPINGname2.addClass('dr_input_invalid');
                            $SHIPPINGname2.focus();
                        }
                        flag = false;
                    }
                } else if (SHIPPINGname2Length > 0) {
                    if(!$('.validation').hasClass('hidden-form')){
                        $SHIPPINGname2Required.html('');
                    }
                }
            }

            $SHIPPINGname1.removeClass('dr_input_invalid');
            $SHIPPINGname1Required.parent('.textbox').removeClass('error');
            if($SHIPPINGname1Required.length){
                if (SHIPPINGname1Length === 0) {
                    if(!$('.validation').hasClass('hidden-form')){
                        $SHIPPINGname1Required.parents('.dr_ms_error').show();
                        $SHIPPINGname1Required.parent('.textbox').addClass('error');
                        $SHIPPINGname1Required.html(inputVariables.storeData.resources.text.BILLINGADDRESS_NAME1_ERROR);
                        $SHIPPINGname1.addClass('dr_input_invalid');
                        $SHIPPINGname1.parent().show();
                        $SHIPPINGname1.parent().siblings('.applyButton').show();
                        $SHIPPINGname1.focus();
                    }
                    flag = false;
                } else if (!validation.validateFormFields.validateNonASCII($SHIPPINGname1.val())) {
                    if (inputVariables.storeData.page.siteid !== 'msjp' && inputVariables.storeData.page.siteid !== 'msru' && inputVariables.storeData.page.siteid !== 'mskr' && inputVariables.storeData.page.siteid !== 'mstw' && inputVariables.storeData.page.siteid !== 'msmea' && inputVariables.storeData.page.siteid !== 'msgulf' && inputVariables.storeData.page.siteid !== 'msgulf2') {
                        if(!$('.validation').hasClass('hidden-form')){
                            $SHIPPINGname1Required.parents('.dr_ms_error').show();
                            $SHIPPINGname1Required.parent('.textbox').addClass('error');
                            $SHIPPINGname1Required.html(inputVariables.storeData.resources.text.NON_ASCII_CHARACTERS_ERROR);
                            $SHIPPINGname1.addClass('dr_input_invalid');
                            $SHIPPINGname1.parent().show();
                            $SHIPPINGname1.parent().siblings('.applyButton').show();
                            $SHIPPINGname1.focus();
                        }
                        flag = false;
                    }
                } else if (SHIPPINGname1Length > 0) {
                    if(!$('.validation').hasClass('hidden-form')){
                        $SHIPPINGname1Required.html('');
                    }
                }
            }

            if (emailLength !== -1) {
                $email.removeClass('dr_input_invalid');
                $emailRequired.parent('.textbox').removeClass('error');
                $confirmEmail.removeClass('dr_input_invalid');
                $confirmEmailRequired.parent('.textbox').removeClass('error');
                $email.val($email.val().replace(/^[\s\u00A0]*|[\s\u00A0]*$/g, ''));
                if($emailRequired.length){
                    if ($email.length && (emailLength > 0 || $emailRequired.length)) {
                        $emailRequired.html('');
                        $confirmEmailRequired.html('');
                        if (!validation.validateFormFields.validateEmail($email.val())) {
                            if(!$('.validation').hasClass('hidden-form')){
                                errorMessage = inputVariables.storeData.resources.text.ENTER_EMAIL_ADDRESS;
                                if ($email.val().length < 7) {
                                    errorMessage = inputVariables.storeData.resources.text.EMAIL_ADDRESSES_MUST_BE_7_CHARACTERS;
                                }
                                $emailRequired.parents('.dr_ms_error').show();
                                $emailRequired.parent('.textbox').addClass('error');
                                $emailRequired.html(errorMessage);
                                $email.addClass('dr_input_invalid');
                                if (flag) {
                                    $email.focus();
                                }
                            }
                            flag = false;
                        } else if (!validation.validateFormFields.validateNonASCII($email.val())) {
                            if (inputVariables.storeData.page.siteid !== 'msjp' && inputVariables.storeData.page.siteid !== 'msru' && inputVariables.storeData.page.siteid !== 'mskr' && inputVariables.storeData.page.siteid !== 'mstw' && inputVariables.storeData.page.siteid !== 'msmea' && inputVariables.storeData.page.siteid !== 'msgulf' && inputVariables.storeData.page.siteid !== 'msgulf2') {
                                if(!$('.validation').hasClass('hidden-form')){
                                    $emailRequired.parents('.dr_ms_error').show();
                                    $emailRequired.parent('.textbox').addClass('error');
                                    $emailRequired.html(inputVariables.storeData.resources.text.NON_ASCII_CHARACTERS_ERROR);
                                    $email.addClass('dr_input_invalid');
                                    if (flag) {
                                        $email.focus();
                                    }
                                }
                                flag = false;
                            }
                        } else if ($confirmEmail.length && $confirmEmail.val() !== $email.val()) {
                            if(!$('.validation').hasClass('hidden-form')){
                                errorMessage = inputVariables.storeData.resources.text.EMAIL_ADDRESSES_MUST_MATCH;
                                $confirmEmailRequired.parents('.dr_ms_error').show();
                                $confirmEmailRequired.parent('.textbox').addClass('error');
                                $confirmEmailRequired.html(errorMessage);
                                $confirmEmail.addClass('dr_input_invalid');
                                if (flag) {
                                    $confirmEmail.focus();
                                }
                            }
                            flag = false;
                        } else if (!validation.validateFormFields.validateNonASCII($confirmEmail.val())) {
                            if (inputVariables.storeData.page.siteid !== 'msjp' && inputVariables.storeData.page.siteid !== 'msru' && inputVariables.storeData.page.siteid !== 'mskr' && inputVariables.storeData.page.siteid !== 'mstw' && inputVariables.storeData.page.siteid !== 'msmea' && inputVariables.storeData.page.siteid !== 'msgulf' && inputVariables.storeData.page.siteid !== 'msgulf2') {
                                if(!$('.validation').hasClass('hidden-form')){
                                    $confirmEmailRequired.parents('.dr_ms_error').show();
                                    $confirmEmailRequired.parent('.textbox').addClass('error');
                                    $confirmEmailRequired.html(inputVariables.storeData.resources.text.NON_ASCII_CHARACTERS_ERROR);
                                    $confirmEmail.addClass('dr_input_invalid');
                                    if (flag) {
                                        $confirmEmail.focus();
                                    }
                                }
                                flag = false;
                            }
                        } else {
                            $email.blur();
                            $confirmEmail.blur();
                        }
                    }
                }
            }
        });
        if (!flag || !billingFlag) {
            if(!$('.validation').hasClass('hidden-form')){
                $('.addressFields').show();
                $('select#shippingCountry').removeAttr('disabled');
                $('select#country').removeAttr('disabled');
                if (e.preventDefault) {
                    e.preventDefault();
                    $('[id=load_image]').each(function () {
                        var $dr_button = $(this).closest('.btnSubmitSpinContainer').find('.dr_button');
                        if ($dr_button.length) {
                            $dr_button.parent().show();
                            $(this).hide();
                        }
                    })
                }
                $('.validation-summary').addClass('error');
            } else {
                window.location = 'https://www.microsoftstore.com/store/' + inputVariables.storeData.page.siteid + '/' + inputVariables.storeData.page.locale + '/DisplayThreePgCheckoutAddressPaymentInfoPage/shipping.error';
            }
            $('form[name="MSAddEditAddressForm"]').addClass('error');
        } else {
            $('form[name="MSAddEditAddressForm"]').removeClass('error');
            $('.validation-summary').removeClass('error');
        }
        return flag;
    });
});

$.widgetize('ReturnsPage', function(){
  $("#returnOrderListContainer .dr_orderInfoRightColumn .itemContent").each(function(){
    if(!$(this).is(":visible")) {
      $(this).find('.dr_incentivePrice input[name^="quantityToReturn"]').attr('value','0');
    }
  });
  totalItem();
  
  $('#returnOrderListContainer .dr_orderInfoRightColumn .dr_orderItemDetails .dr_incentivePrice input[name^="quantityToReturn"]').each(function(){
    $(this).keypress(function(e){
      if(!e.ctrlKey && !e.altKey && $.inArray(e.which, [0, 8, 9, 13, 27, 48, 49, 50, 51, 52, 53, 54, 55, 56, 57]) < 0) {
        e.preventDefault();
        return false;
      }
    });
    $(this).focusout(function(){
      if($(this).val().length > 0) {
        $(this).val(parseInt($(this).val().replace(/[^0-9]*/ig, '')));
      }
      totalItem();
    });
  });
  
  $('#returnOrderListContainer .dr_orderInfoRightColumn .comments textarea').each(function(){
    var $element = $(this);
    $element.keypress(function(e){
      if($element.val().length >= 226) {
        $element.val($element.val().substr(0, 226));
        e.preventDefault();
        return false;
      }
    });
    $element.bind("paste", function () {
      setTimeout(function () {
        if($element.val().length >= 226) {
          $element.val($element.val().substr(0, 226));
        }
      }, 0);
    });
  });
  
  shipAddress();
  changeLayout();
  
  $("#returnOrderListContainer .itemContent").each(function(){
    var parentPos = $(this).find("input[name^='lineItemID']").attr('name').replace('lineItemID','');
    $("#returnOrderListContainer .dr_orderInfoRightColumn .comments").each(function(){
      var pos = $(this).find("input[name^='ORIG_VALUE_comment']").attr('name').replace('ORIG_VALUE_comment','');
      $(this).find("textarea[name='comment']").attr('name','comment' + pos);
    });
    $(".child-product input[name*='$$'],.child-product select[name*='$$'],.child-product textarea[name*='$$']",$(this)).each(function(){
      var elementName = $(this).attr('name').split("$$");
      $(this).attr('name',elementName[0] + parentPos + elementName[1]);
    });
  });
  $("#returnOrderListContainer .dr_orderInfoRightColumn .additional-item .row[data-liid] input[name='addItem']").each(function(){
    $(this).click(function(){
      if($("#returnOrderListContainer .dr_orderInfoRightColumn .additional-item .row[data-liid]:visible input[name='addItem']:not(:checked)").length == 0) {
        $("#returnOrderListContainer .dr_orderInfoRightColumn .additional-item .header-row input").attr('checked', true);
        $("#returnOrderListContainer .dr_orderInfoRightColumn .additional-item .dr_myAccountSiteButtons input").show();
      }
      else {
        if ($("#returnOrderListContainer .dr_orderInfoRightColumn .additional-item .row[data-liid]:visible input[name='addItem']:checked").length == 0) {
          $("#returnOrderListContainer .dr_orderInfoRightColumn .additional-item .dr_myAccountSiteButtons input").hide();
        }
        else {
          $("#returnOrderListContainer .dr_orderInfoRightColumn .additional-item .dr_myAccountSiteButtons input").show();
        }
        $("#returnOrderListContainer .dr_orderInfoRightColumn .additional-item .header-row input").attr('checked', false);
      }
    });
  });
  $("#returnOrderListContainer .dr_orderInfoRightColumn .additional-item .header-row input").click(function(){
    if($(this).prop('checked')) {
      $("#returnOrderListContainer .dr_orderInfoRightColumn .additional-item .row[data-liid]:visible input[name='addItem']").attr('checked', true);
      $("#returnOrderListContainer .dr_orderInfoRightColumn .additional-item .dr_myAccountSiteButtons input").show();
    }
    else {
      $("#returnOrderListContainer .dr_orderInfoRightColumn .additional-item .row[data-liid]:visible input[name='addItem']").attr('checked', false);
      $("#returnOrderListContainer .dr_orderInfoRightColumn .additional-item .dr_myAccountSiteButtons input").hide();
    }
  });
  $("#returnOrderListContainer .dr_orderInfoRightColumn .additional-item .dr_myAccountSiteButtons input").click(function(){
    $("#returnOrderListContainer .dr_orderInfoRightColumn .additional-item .row[data-liid]:visible input[name='addItem']:checked").each(function(){
      var lineItemID = $(this).val();
      $(this).parent().hide();
      var $targetRow = $("#returnOrderListContainer .dr_orderInfoRightColumn .itemContent[data-liid='" + lineItemID + "']");
      $targetRow.show();
      $targetRow.find("input[name^='ORIG_VALUE_quantityToReturn']").each(function(){
        if($(this).val() == 1) {
          var inputName = $(this).attr("name").replace("ORIG_VALUE_quantityToReturn","quantityToReturn");
          $targetRow.find("input[name='" + inputName + "']").val(1);
        }
      });
      $targetRow.find("input[name^='quantityToReturn']:hidden").each(function(){
        var inputName = $(this).attr("name").replace("quantityToReturn","ORIG_VALUE_quantityToReturn");
        if($targetRow.find("input[name='" + inputName + "']").length == 1) {
          $(this).val($targetRow.find("input[name='" + inputName + "']").val());
        }
      });
    });
    totalItem();
    changeLayout();
    $("#returnOrderListContainer .dr_orderInfoRightColumn .additional-item .dr_myAccountSiteButtons input").hide();
    $("#returnOrderListContainer .dr_orderInfoRightColumn .additional-item .header-row input").attr('checked', false);
    $("#returnOrderListContainer .dr_orderInfoRightColumn .additional-item .row[data-liid] input[name='addItem']").attr('checked', false);
    $(this).hide();
    if($("#returnOrderListContainer .dr_orderInfoRightColumn .additional-item .row[data-liid]:visible input[name='addItem']").length == 0) {
      $("#returnOrderListContainer .dr_orderInfoRightColumn .additional-item").hide();
    }
    $("#returnOrderListContainer .dr_orderInfoRightColumn a.removeLink").show();
  });
  $("#returnOrderListContainer .dr_orderInfoRightColumn .itemContent").each(function(){
    var $root = $(this), lineItemID = $(this).attr('data-liid');
    $root.find('a.removeLink').click(function(e){
      e.preventDefault();
      $root.hide();
      $("#returnOrderListContainer .dr_orderInfoRightColumn .additional-item").show();
      $("#returnOrderListContainer .dr_orderInfoRightColumn .additional-item .row[data-liid='" + lineItemID + "']").show();
      $root.find("input[name^='quantityToReturn']").val(0);
      $('input[name^="quantityToReturn"]', $root).removeClass('error').parent().parent().find('div.error').hide();
      $('select[name^="reason"]', $root).removeClass('error');
      $('.productKeys div.error', $root).hide();
      $('.comments div.error', $root).hide();
      totalItem();
      changeLayout();
      if($("#returnOrderListContainer .dr_orderInfoRightColumn .itemContent:visible").length < 2) {
        $("#returnOrderListContainer .dr_orderInfoRightColumn a.removeLink").hide();
      }
    });
  });
  $("#returnOrderListContainer .returns_submit").click(function(e){
    var flag = true;
    var top = 0 ;
    $("#returnOrderListContainer .itemContent:visible").each(function(){
      var itemQty = 0;
      $('input[name^="quantityToReturn"]', this).each(function(index){
        if(index == 0){
          if($(this).val().length == 0 || parseInt($(this).val()) <= 0 || !$.isNumeric($(this).val())) {
            flag = false;
            $(this).addClass('error').parent().parent().find('div.error').show();
            if(top == 0) {top = $(this).offset().top;}
          } else {
            itemQty = parseInt($(this).val());
            $(this).removeClass('error').parent().parent().find('div.error').hide();
          }
        }
      });
      if($('.parent-product input[name^="selectedDigitalRight"]:visible', this).length > 0) {
        if($('.parent-product input[name^="selectedDigitalRight"]:checked', this).length == 0) {
          flag = false;
          $('.parent-product .productKeys div.error', this).show();
          if(top == 0) {top = $('.parent-product .productKeys', this).offset().top;}
        } else {
          if(itemQty != $('.parent-product input[name^="selectedDigitalRight"]:checked', this).length) {
            flag = false;
            $('.parent-product .productKeys div.error', this).show();
            if(top == 0) {top = $('.parent-product .productKeys', this).offset().top;}
          } else {
            $('.parent-product .productKeys div.error', this).hide();
          }
        }
      }
      $('.child-product', this).each(function(){
        if($('input[name^="quantityToReturn"]', this).length > 0) {
          itemQty = parseInt($('input[name^="quantityToReturn"]', this).val());
        } else {
          itemQty = parseInt($(this).find('.quantity').text());
        }
        if($('input[name^="selectedDigitalRight"]:visible', this).length > 0) {
          if($('input[name^="selectedDigitalRight"]:checked', this).length == 0) {
            flag = false;
            $('.productKeys div.error', this).show();
            if(top == 0) {top =  $('.productKeys', this).offset().top;}
          } else {
              if(itemQty != $('input[name^="selectedDigitalRight"]:checked', this).length) {
                flag = false;
                $('.productKeys div.error', this).show();
                if(top == 0) {top =  $('.productKeys', this).offset().top;}
              } else {
                $('.productKeys div.error', this).hide();
              }
          }
        }
      });
      if(inputVariables.storeData.resources.text.SiteSetting_ReturnReasonValidationEnabled == "true") {
        $('select[name^="reason"]', this).each(function(){
          if($(this).val().length == 0) {
            if($(this).parent().parent().hasClass('child-product')) {
              var $inputQuantity = $(this).parent().parent().find('input[name^="quantityToReturn"]');
              if(($inputQuantity.length > 0 && $inputQuantity.val() != "0") || $inputQuantity.length == 0) {
                flag = false;
                $(this).addClass('error');
                $('div.error', $(this).parent()).show();
                if(top == 0) {top =  $(this).offset().top;}
              }
            } else {
              flag = false;
              $(this).addClass('error');
              $('div.error', $(this).parent()).show();
              if(top == 0) {top =  $(this).offset().top;}
            }
          } else {
              $(this).removeClass('error');
              $('div.error', $(this).parent()).hide();
          }
        });
      }
    });
    if($('#returnOrderListContainer .agreement input[name="software"]:visible').length > 0 && $('#returnOrderListContainer .agreement input[name="software"]:checked').length == 0) {
      if($('#returnOrderListContainer div[data-type="digital"] input[name^="quantityToReturn"]').length > 0) {
        $('#returnOrderListContainer .agreement div.error').hide();
        $('#returnOrderListContainer div[data-type="digital"] input[name^="quantityToReturn"]').each(function(){
          if($(this).val() > 0) {
            flag = false;
            $('#returnOrderListContainer .agreement div.error').show();
            if(top == 0) {top =   $('#returnOrderListContainer .agreement').offset().top;}
          }
        });
      } else {
        flag = false;
        $('#returnOrderListContainer .agreement div.error').show();
        if(top == 0) {top =   $('#returnOrderListContainer .agreement').offset().top;}
      }
    } else {
      $('#returnOrderListContainer .agreement div.error').hide();
    }
    if(flag) {
      $('#dr_AddEditAddress input').attr('disabled','disabled');
    } else {
      $(window).scrollTop(top);
    }
    return flag;
  });
  function totalItem() {
    var totalItem = 0;
    $("#returnOrderListContainer .dr_orderInfoRightColumn .itemContent").each(function(){
      var $root = $(this);
      if ($(this).find('.dr_incentivePrice input[name^="quantityToReturn"]').length) {
        var parentRemain = 0;
        $(this).find('.dr_incentivePrice input[name^="quantityToReturn"]').each(function(index){
          if($(this).val() < 0) {
            $(this).val(0);
          }
          var item = $(this).val();
          var allItem = $(this).parent().parent().find('.item_count').text().replace("/","").trim();
          if(parseInt(item) > parseInt(allItem)) {
            $(this).val(allItem);
            item = allItem;
          }
          if(index == 0) {
            if(item != "") {
              parentRemain = parseInt(allItem) - parseInt(item);
              totalItem = parseInt(totalItem) + parseInt(item);
              $root.find(".child-product").each(function(){
                if($(this).find('.totalQuantity').length) {
                  var subTotal = $(this).find('.totalQuantity').text();
                  $(this).find('.quantity').html((parseInt(subTotal)/parseInt(allItem))*parseInt(item));
                  totalItem = parseInt(totalItem) + ((parseInt(subTotal)/parseInt(allItem))*parseInt(item));
                }
              });
            } else {
              $(this).val(0);
              parentRemain = parseInt(allItem);
              $root.find(".child-product").each(function(){
                $(this).find('.quantity').html(0);
              });
            }
          } else {
            if(item == "") { item = 0;}
            if((parseInt(allItem) - parseInt(item)) > parentRemain) {
              $(this).val(parseInt(allItem) - parentRemain);
              item = parseInt(allItem) - parentRemain;
            }
            totalItem = parseInt(totalItem) + parseInt(item);
          }
        });
      }
    });
    $("#returnOrderListContainer .dr_orderInfoLeftColumn .total_items").html("<strong>" + totalItem + "</strong>");
  }
  function shipAddress() {
    $("#dr_myAccountColumn2Padding div.edit_Address a").click(function(e){
      e.preventDefault();
      $('#dr_myAccountColumn2Padding h1,#dr_myAccountColumn2Padding .dr_editLink,#dr_myAccountColumn2Padding ul, .ship-to-page').show();
      $("#dr_myAccountColumn2Padding div.edit_Address,#returnOrderListContainer .returns_submit").hide();
    });
    $('.ship-to-page a.ship').click(function(e){
      $('.dr_profile_info_container2').hide();
      $('fieldset.active .dr_profile_info_container1').show();
      $('#new-address').show();
      $('.ship-to-page').hide();
      $('#new-address .dr_myAccountSiteButtons .use').hide();
      $('.title .list').removeClass("hidden-md").removeClass("hidden-lg").hide();
      $('.address-list').addClass("hidden-xs").addClass("hidden-sm");
      $('.back-link .address').show();
      $('.back-link .account').hide();
      $('#dr_AddressEntryFields .dr_formLine').removeClass('odd');
      $('#dr_AddressEntryFields .dr_formLine:visible:even').addClass('odd');
    });
    $('.dr_editLink a.edit').each(function(){
      $(this).click(function(e){
        $('.dr_profile_info_container2').hide();
        $('.address-list fieldset.active .dr_profile_info_container1').show();
        $('#new-address').hide();
        $(this).parents('fieldset').find('.dr_profile_info_container2').show();
        $('.dr_formLine',$(this).parents('fieldset').find('.dr_profile_info_container2')).removeClass('odd');
        $('.dr_formLine:visible:even',$(this).parents('fieldset').find('.dr_profile_info_container2')).addClass('odd');
        $(this).parents('fieldset').find('.dr_profile_info_container1').hide();
        $('.address-list fieldset').removeClass("active");
        $(this).parents('fieldset').addClass("active");
        $('.address-list').removeClass("hidden-xs").removeClass("hidden-sm");
        $('.address-list fieldset').addClass("hidden-xs").addClass("hidden-sm");
        $('.address-list fieldset.active').removeClass("hidden-xs").removeClass("hidden-sm");
        $('.ship-to-page').show();
        $('.title .list').removeClass("hidden-md").removeClass("hidden-lg").hide();
        $('.ship-to-page').addClass("hidden-xs").addClass("hidden-sm");
        $('#dr_myAccountColumn2Padding h1').addClass("hidden-xs").addClass("hidden-sm");
        $('.back-link .address').show();
        $('.back-link .account').hide();
        $(this).parents('fieldset').find('.dr_profile_info_container2 .dr_myAccountSiteButtons .submit').hide();
      });
    });
    
    $('.back-link .address').click(function(e){
      e.preventDefault();
      $('.dr_profile_info_container2').hide();
      $('.address-list fieldset.active .dr_profile_info_container1').show();
      $('.address-list fieldset').removeClass("active");
      $('.address-list fieldset').removeClass("hidden-xs").removeClass("hidden-sm");
      $('.title .list').show();
      $('.ship-to-page').removeClass("hidden-xs").removeClass("hidden-sm");
      $('#dr_myAccountColumn2Padding h1').removeClass("hidden-xs").removeClass("hidden-sm");
      $('.ship-to-page').show();
      $('#new-address').hide();
      $('.address-list').removeClass("hidden-xs").removeClass("hidden-sm");
      $('.back-link .address').hide();
      $('.back-link .account').show();
    });
    
    $('.dr_editLink a.edit').click(function(e){
      e.preventDefault();
      $(this).parent().parent().hide().siblings(".dr_profile_info_container2").show();
      var phonevalue1 = $('.phone1').val().replace(/\D*/g, '');        
      $('.phone1').val(phonevalue1);    
    });
    $('.dr_profile_info_container2 .dr_formLine.state select').each(function(){
      $('option[value="'+$(this).attr('valueToselect')+'"]',$(this)).attr("selected", true);    
    });
    if($('#dr_AddEditAddress').attr('data-error')==='true'){
      $('#dr_myAccountColumn2Padding h1,#dr_myAccountColumn2Padding .dr_editLink,#dr_myAccountColumn2Padding ul, .ship-to-page').show();
      $("#dr_myAccountColumn2Padding div.edit_Address,#returnOrderListContainer .returns_submit").hide();
      var addressEntryID = $('form[name="MSAddEditAddressForm"] input[name=addressEntryID]').val(),
      $addressEntryIDCur = $('div[data-addressentryid='+addressEntryID+']');
      $('div[data-addressentryid='+addressEntryID+']').show();
      $('div[data-addressentryid='+addressEntryID+']').siblings().hide();
      $('.SetUserError',$addressEntryIDCur.parent()).show();
      if(addressEntryID ==''){
        $('#dr_addressUpdates .SetUserError').show();
        $('.ship-to-page a.ship').click();
      }
      
    }  
    $('input#addr2').focus(function(){
      $(this).siblings('.optionalText').hide();
    });
    $('input#addr3').focus(function(){
      $(this).siblings('.optionalText').hide();
    });
    $('input#addr2, input#addr3').siblings('.optionalText').click(function(){
      $(this).hide();
      $(this).siblings('input#addr2, input#addr3').focus();
    });
    
    if(window.location.href.match(/anchor=new-address/g)){
      var top = $('#new-address').offset().top;
      $(window).scrollTop(top);
      $('.ship-to-page a.ship').click();
    }
    $('form[name="MSAddEditAddressForm"]').attr('action','/store/' + inputVariables.storeData.page.siteid + '/' + inputVariables.storeData.page.locale + '/DisplayReturnsPage/requisitionID.' + $('form[name="ReturnRequestForm"] input[name="requisitionID"]').val());
    $('#dr_AddEditAddress .dr_myAccountSiteButtons .dr_button, #dr_AddEditAddress .dr_myAccountSiteButtons .button').click(function(e){
      var $parentObj = $(this).parent().parent(),
        addEntryID = $parentObj.attr('data-addressEntryID') || "",
        addressID = $parentObj.attr('data-addressID') || "",
        phoneno = "";
      if($(this).hasClass("submit") || $(this).hasClass("use")) {
        if(!$('form[name="MSAddEditAddressForm"]').hasClass('error')) {
          $('.dr_formLine input, .dr_formLine select', $parentObj).each(function(){
            var inputFieldName = $(this).attr('data-name'),
                inputFieldValue = $(this).val() ;          
            if(inputFieldName==="phoneNumber"){
              phoneno += inputFieldValue;
              inputFieldValue = phoneno;
            }
            var thisSiteID=inputVariables.storeData.page.siteid;
            var thisLocale=inputVariables.storeData.page.locale;
            if(thisSiteID==="mseea"){
              if(thisLocale==="sv_SE"){
                if(inputFieldName==="postalCode"){
                  var pCode = inputFieldValue;
                  var newPostal = pCode.substring(0,3) + " " + pCode.substring(3);
                  inputFieldValue = pCode.replace(pCode, newPostal);
                }
              }
            }  
            $('form[name="MSAddEditAddressForm"] input[name="'+inputFieldName+'"]').val(inputFieldValue);
            $('form[name="MSAddEditAddressForm"] select[name="'+inputFieldName+'"] option[value="'+inputFieldValue+'"]').attr("selected", "selected");
          });
          $('form[name="MSAddEditAddressForm"] input[name="addressEntryID"], form[name="MSAddEditAddressForm"] input[name="ORIG_VALUE_addressEntryID"]').val(addEntryID);
          var showList = false;
          if($(this).hasClass("use")) {
            $('form[name="MSAddEditAddressForm"]').attr('action','/store/' + inputVariables.storeData.page.siteid + '/' + inputVariables.storeData.page.locale + '/DisplayReturnsPage/requisitionID.' + $('form[name="ReturnRequestForm"] input[name="requisitionID"]').val() + '/addressID.'+addressID);
          }
          else {
            $('form[name="MSAddEditAddressForm"]').attr('action','/store/' + inputVariables.storeData.page.siteid + '/' + inputVariables.storeData.page.locale + '/DisplayReturnsPage/requisitionID.' + $('form[name="ReturnRequestForm"] input[name="requisitionID"]').val());
            showList = true;
          }
          var addressFields = [];
          $('form[name="MSAddEditAddressForm"]').find('input').each(function(){
            var fieldVal = $(this).val();
            addressFields.push(this.name + '=' + encodeURIComponent(fieldVal));
          });
          if(addressFields.length){
            $('#dr_AddEditAddress .dr_myAccountSiteButtons .dr_button').attr('disabled','disabled');
            $.ajax({
              url: $('form[name="MSAddEditAddressForm"]').attr('action'),
              type: 'POST',
              dataType: 'html',
              data: addressFields.join('&'),
              success: function(data){
                if($(data).find('#dr_AddEditAddress').length > 0) {
                  $('form[name="MSAddEditAddressForm"]').html($(data).find('form[name="MSAddEditAddressForm"]').html());
                  $('#dr_AddEditAddress').html($(data).find('#dr_AddEditAddress').html());
                  $('form[name="ReturnRequestForm"] input[name="addressID"]').val($(data).find('form[name="ReturnRequestForm"] input[name="addressID"]').val());
                  if($(data).find('#dr_AddEditAddress').attr('data-error') === 'true') {
                    $('#dr_AddEditAddress').attr('data-error','true');
                  }
                  $("#dr_myAccountColumn2Padding div.edit_Address,#returnOrderListContainer .returns_submit").show();
                  $('.validation').removeClass('enabled');
                  $.widgetize('validation');
                  shipAddress();
                  if(showList) {
                    $("#dr_myAccountColumn2Padding div.edit_Address a").click();
                  }
                }
                else {
                  location.reload();
                }
              },
              error: function(){
                location.reload();
              }
            });
          }
        }
      }
      else {
        $('.dr_profile_info_container2').hide();
        $('.address-list fieldset.active .dr_profile_info_container1').show();
        $('.address-list fieldset').removeClass("active");
        $('.address-list fieldset').removeClass("hidden-xs").removeClass("hidden-sm");
        $('.title .list').show();
        $('.ship-to-page').removeClass("hidden-xs").removeClass("hidden-sm");
        $('#dr_myAccountColumn2Padding h1').removeClass("hidden-xs").removeClass("hidden-sm");
        $('.ship-to-page').show();
        $('#new-address').hide();
        $('.address-list').removeClass("hidden-xs").removeClass("hidden-sm");
      }
    });
    $('.dr_editLink a.set').click(function(e){
      e.preventDefault();
      var url = $(this).attr('href');
       $.ajax({
        url: url,
        dataType: 'html',
        success: function(data){
          if($(data).find('#dr_AddEditAddress').length > 0) {
            $('form[name="MSAddEditAddressForm"]').html($(data).find('form[name="MSAddEditAddressForm"]').html());
            $('#dr_AddEditAddress').html($(data).find('#dr_AddEditAddress').html());
            $('form[name="ReturnRequestForm"] input[name="addressID"]').val($(data).find('form[name="ReturnRequestForm"] input[name="addressID"]').val());
            if($(data).find('#dr_AddEditAddress').attr('data-error') === 'true') {
              $('#dr_AddEditAddress').attr('data-error','true');
            }
            $("#dr_myAccountColumn2Padding div.edit_Address,#returnOrderListContainer .returns_submit").show();
            $('.validation').removeClass('enabled');
            $.widgetize('validation');
            shipAddress();
          }
          else {
            location.reload();
          }
        },
        error: function(){
          location.reload();
        }
      });
    });
    $('.dr_profile_info_container2').each(function(){
      var shippingCountry = $('select.country', this).attr('data-country');
      $('select.country option', this).each(function(){
        var optionValue = $(this).attr('value');
        if(optionValue != shippingCountry){
          $(this).remove();
        }
      });
    });
    if($('#dr_addressUpdates select#country').length > 0){
      $('#dr_addressUpdates .dr_formLine').hide();
      $('#dr_addressUpdates select#country').parent().show();
      $("#dr_addressUpdates select#country option[selected='selected']").removeAttr("selected"); //deselect all options
      $("#dr_addressUpdates select#country option[value='']").attr("selected", "selected"); //select the second option
      $("#dr_addressUpdates .dr_myAccountSiteButtons .dr_button").attr("disabled", "disabled");

      $('#dr_addressUpdates select#country').change(function(){
        var shippingCountry = $(this).val();
        $(this).parent().find('input[name="country"]').val(shippingCountry);
        $.ajax({
          url: '/Storefront/Site/mscommon/cm/multimedia/js/dr-CrossBorderMapping_16.js', //holds the mapping to the form fields
          dataType: 'json',
          cache: true,
          success: function(data){
            $.each(data.COUNTRYinfoModal, function(){
              datacon = this;
              if(datacon.COUNTRYinfo.shippingCountry === shippingCountry){
                mappingExistence = true;
                $('#dr_addressUpdates select#country option').each(function(){
                  var optionValue = $(this).val();
                  if(optionValue != shippingCountry){
                    $(this).remove();
                  }
                });
                if(datacon.COUNTRYinfo.shippingState === 'false'){
                  $('#dr_addressUpdates .dr_formLine').show();
                  $('#dr_addressUpdates select#state').parents('.state').remove();
                  //$('#dr_addressUpdates select#state').parent().hide();
                  //$('#dr_addressUpdates select#state').attr('data-required','false');
                } else {
                  var shippingStateOptions = datacon.COUNTRYinfo.shippingStateOptions;
                  $('#dr_addressUpdates select#state').html(shippingStateOptions);
                  $('#dr_addressUpdates .dr_formLine').show();
                  $('#dr_addressUpdates select#state').parent().show();
                  $('#dr_addressUpdates select#state').attr('data-required', 'true');
                }
                if(datacon.COUNTRYinfo.address2 != null && datacon.COUNTRYinfo.address2 == "false"){
                  $('#dr_addressUpdates .dr_formLine.address2').hide();
                } else {
                  $('#dr_addressUpdates .dr_formLine.address2').show();
                }
                $('#dr_addressUpdates select#country').attr('disabled', 'disabled');
                $("#dr_addressUpdates .dr_myAccountSiteButtons .dr_button").removeAttr("disabled");
              }
              $('#dr_myAccountColumn2Padding fieldset').each(function(){
                var shippingCountryOther = $('select.country', this).attr('data-country');
                if(datacon.COUNTRYinfo.shippingCountry === shippingCountryOther){
                  if(datacon.COUNTRYinfo.shippingState === 'false'){
                    $('.cityState select', this).parent().remove();
                  } else {
                    var shippingStateOptions = datacon.COUNTRYinfo.shippingStateOptions;
                    $('.cityState select', this).html(shippingStateOptions);
                  }
                  if(datacon.COUNTRYinfo.address2 != null && datacon.COUNTRYinfo.address2 == "false"){
                    $('.address2', this).hide();
                  } else {
                    $('.address2', this).show();
                  }
                }
                $('select.country', this).attr('disabled', 'disabled');
              });
            });
            // Address ordering on Customer Info Page
            var seqList = inputVariables.storeData.resources.shippingAddrSeq[shippingCountry];
            if(seqList){
              if(!seqList.match("Config_AddressSequence_")){
                var seqArray = seqList.split(',');
                var addrContent = [];
                addrContent.push($('<div>').append($('#dr_AddressEntryFields .dr_formLine #firstName').parent().clone()).html());
                addrContent.push($('<div>').append($('#dr_AddressEntryFields .dr_formLine #lastName').parent().clone()).html());
                $.each(seqArray,function(index,val){
                  if(val=='Address1'){
                      var elementsToPush = $('<div>').append($('#dr_AddressEntryFields .dr_formLine #addr1').parent().clone()).html();
                      addrContent.push(elementsToPush);
                  }
                  else if(val=='Address2'){
                      var elementsToPush = $('<div>').append($('#dr_AddressEntryFields .dr_formLine #addr2').parent().clone()).html();
                      addrContent.push(elementsToPush);
                  }
                  else if(val=='PostalCode'){
                      var elementsToPush = $('<div>').append($('#dr_AddressEntryFields .dr_formLine #zip').parent().clone()).html();
                      addrContent.push(elementsToPush);
                  }
                  else if(val=='City'){
                      var elementsToPush = $('<div>').append($('#dr_AddressEntryFields .dr_formLine #city').parent().clone()).html();
                      elementsToPush = elementsToPush + $('<div>').append($('#dr_AddressEntryFields .dr_formLine #state').parent().clone()).html();
                      addrContent.push(elementsToPush);
                  }
                });
                addrContent.push($('<div>').append($('#dr_AddressEntryFields .dr_formLine #phone').parent().clone()).html());
                addrContent.push($('<div>').append($('#dr_AddressEntryFields .dr_formLine #country').parent().clone()).html());
                $('#dr_addressUpdates #dr_AddressEntryFields').html(addrContent.join(''));
                $('input#addr2').focus(function(){
                  $(this).siblings('.optionalText').hide();
                });
                $('input#addr3').focus(function(){
                  $(this).siblings('.optionalText').hide();
                });
                $('input#addr2, input#addr3').siblings('.optionalText').click(function(){
                  $(this).hide();
                  $(this).siblings('input#addr2, input#addr3').focus();
                });
                $('#dr_addressUpdates .reset').click(function(e){
                  e.preventDefault();
                  $.ajax({
                    url: $('form[name="MSAddEditAddressForm"]').attr('action'),
                    type: 'GET',
                    dataType: 'html',
                    success: function(data){
                      if($(data).find('#dr_AddEditAddress').length > 0) {
                        $('#dr_AddEditAddress').html($(data).find('#dr_AddEditAddress').html());
                        $('form[name="ReturnRequestForm"] input[name="addressID"]').val($(data).find('form[name="ReturnRequestForm"] input[name="addressID"]').val());
                        $('.validation').removeClass('enabled');
                        $.widgetize('validation');
                        shipAddress();
                        $("#dr_myAccountColumn2Padding div.edit_Address a").click();
                        var top = $('#new-address').offset().top;
                        $(window).scrollTop(top);
                        $('.ship-to-page a.ship').click();
                      }
                      else {
                        location.reload();
                      }
                    },
                    error: function(){
                      location.reload();
                    }
                  });
                });
              }
            }
            $('#dr_AddressEntryFields .dr_formLine').removeClass('odd');
            $('#dr_AddressEntryFields .dr_formLine:visible:even').addClass('odd');
          },
          error: function(){
            //submit the form if any error
          }
        });
      });
      $('#dr_addressUpdates select#country').change();
      $('#dr_addressUpdates .reset').click(function(e){
        e.preventDefault();
        $.ajax({
          url: $('form[name="MSAddEditAddressForm"]').attr('action'),
          type: 'GET',
          dataType: 'html',
          success: function(data){
            if($(data).find('#dr_AddEditAddress').length > 0) {
              $('#dr_AddEditAddress').html($(data).find('#dr_AddEditAddress').html());
              $('form[name="ReturnRequestForm"] input[name="addressID"]').val($(data).find('form[name="ReturnRequestForm"] input[name="addressID"]').val());
              $('.validation').removeClass('enabled');
              $.widgetize('validation');
              shipAddress();
              $("#dr_myAccountColumn2Padding div.edit_Address a").click();
              var top = $('#new-address').offset().top;
              $(window).scrollTop(top);
              $('.ship-to-page a.ship').click();
            }
            else {
              location.reload();
            }
          },
          error: function(){
            location.reload();
          }
        });
      });
    }
  }
  function changeLayout() {
    if($('#returnOrderListContainer .dr_orderInfoRightColumn .itemContent[data-type="physical"]:visible').length > 0 || $('#returnOrderListContainer .dr_orderInfoRightColumn .child-product[data-type="physical"]:visible').length > 0) {
      $('#dr_AddEditAddress').show();
      $('.return-label').show();
      $('.confirm-mail').hide();
    }
    else {
      $('#dr_AddEditAddress').hide();
      $('.return-label').hide();
      $('.confirm-mail').show();
    }
    if($('#returnOrderListContainer .dr_orderInfoRightColumn .itemContent[data-type="digital"]:visible').length > 0 || $('#returnOrderListContainer .dr_orderInfoRightColumn .child-product[data-type="digital"]:visible').length > 0) {
      $('#returnOrderListContainer .agreement').show();
    }
    else {
      $('#returnOrderListContainer .agreement').hide();
      $('#returnOrderListContainer .agreement div.error').hide();
    }
  }
});

$.focusRingRemoval = function(){
    $('a,input').live('mousedown', function(){
        $(this).css({
            'outline-style': 'none'
        });
    });
};

$.SanitizeTextField = function(){
    var checkFieldValue = function(e){
        var thisElement = $(this);
        if(thisElement.val().match(/[\*\(\)\+\:\;\`\~\$\%\^&<\>\=\-\_\@\!]/gi)){
            this.focus();
            thisElement.val('');
            return false;
        }
        return true;
    }
    $("input[type='text'], input:not([type])").live('blur', checkFieldValue);
    $('form').submit(function(){
        var returnValue = true;
        $("input[type='text'], input:not([type])").each(function(){
            returnValue = checkFieldValue.call(this);
            if(!returnValue) return false;
        });
        return returnValue;
    });
};

(function($){
    $.fn.wrapChildren = function(options){
        var options = $.extend({
            childElem: undefined,
            sets: 1,
            wrapper: 'div'
        }, options || {});
        if(options.childElem === undefined) return this;
        return this.each(function(){
            var elems = $(this).children(options.childElem);
            var arr = [];
            elems.each(function(i, value){
                arr.push(value);
                if(((i + 1) % options.sets === 0) || (i === elems.length - 1)){
                    var set = $(arr);
                    arr = [];
                    set.wrapAll($("<" + options.wrapper + ">"));
                }
            });
        });
    }
    $.fn.equalHeights = function(){
        var maxheight = 0;
        $(this).each(function(){
            containerHeight = $(this).outerHeight(false);
            maxheight = (containerHeight > maxheight) ? containerHeight : maxheight;
        });
        $(this).css('height', maxheight + 'px');
    }
})($);
$('.dr_savedCart').wrapChildren({
    childElem: 'div.lineItem',
    sets: 6,
    wrapper: 'div class="grouped"'
});
$('.twoupFourupDetails').equalHeights();
$('.top3up4up .topFeaturesToggle .dr_productName').equalHeights();
$('.top3up4up .topFeaturesToggle .productPrice').equalHeights();
$('.top3up4up .topFeaturesToggle .count').equalHeights();
$('.top3up .dr_productName, .top3up .suitesDesc').equalHeights();
$('.shoppingTipsDetails').equalHeights();
$('.grouped').each(function(){
    $('.lineItemPrice', this).equalHeights();
});
$('.HomeOffersPage .horizontal_tabbed_scroller .slider,.bottom4up').each(function(){
    $('.dr_productName', this).equalHeights();
});
$('.dr_Compare tr').each(function(){
    $('td', this).equalHeights();
    $('th', this).equalHeights();
});

$('.CategoryListPage .horizontal_tabbed_scroller .dr_productName').equalHeights();
$('.CategoryListPage .horizontal_tabbed_scroller .ms_specListItem').equalHeights();


(function($){
    $.getUrlVar = function(key){
        var result = new RegExp(key + "=([^&]*)", "i").exec(window.location.search);
        return result && unescape(result[1]) || "";
    };
})($);

jQuery(document).ready(function($){
    /*@description initialize widgets*/
    $('.widget').removeClass('inactive');
    $('.widget').addClass('active');
    //$.SanitizeTextField();
    //$.focusRingRemoval(); // Removed to meet pdp build comps.
});

function navigateURL(ref){
    var url = ref.options[ref.selectedIndex].getAttribute('data-href');
    var changedValue = ref.options[ref.selectedIndex].getAttribute('value');
    $('select.variations').attr('disabled', 'disabled');
    $('select.dr_qtySelect').attr('disabled', 'disabled');
    $('.variationSelectorMain .variationSelector .grid-row input').attr('disabled', 'disabled');
    if(url){
        $.ajax({
            url: "/store/" + inputVariables.storeData.page.siteid + "/" + inputVariables.storeData.page.locale + "/DisplayPage/id.ProductInventoryStatusXmlPage/productID." + changedValue,
            cache: false
        }).done(function(xmlData){
            $xml = $(xmlData),
                $inventoryStatus = $xml.find("inventoryStatus");
            if($inventoryStatus.text() === 'PRODUCT_INVENTORY_OUT_OF_STOCK'){
                url = url.replace("lineItemID", "param");
                window.location = url;
            } else {
                window.location = url;
            }
        }).fail(function(xhr, status){
            window.location = url;
        });
    } else {
        window.location = url;
    }
}

/* Compare Office/Windows pages */
if(($('body').hasClass('CompareOfficeSuitesPage') || $('body').hasClass('CompareWindowsEditionsPage')) || ($('body').hasClass('CompareOfficeMacEditionsPage') && ($('#CompareTable').length))){
    var pidStr, pids, siteID, locale, thisAttr, fullPath;
    if($('body').hasClass('CompareOfficeSuitesPage')){
        pidStr = inputVariables.storeData.resources.text.CONFIG_COMPARE_OFFICE_SUITES;
    } else if($('body').hasClass('CompareOfficeMacEditionsPage')){
        pidStr = inputVariables.storeData.resources.text.CONFIG_COMPARE_OFFICE_FOR_MAC;
    } else {
        pidStr = inputVariables.storeData.resources.text.CONFIG_COMPARE_WINDOWS;
    }
    pids = pidStr.split(",");
    siteID = inputVariables.storeData.page.siteid;
    locale = inputVariables.storeData.page.locale;

    function getProdInfo(pid, attr){
        var thisClass, url, discounted, unitPrice, unitPriceWithDiscount, from, regular, name;
        thisClass = 'pid' + pid;
        //console.log(attr);
        if(attr === 'msMedium'){
            url = '/store/' + siteID + '/' + locale + '/DisplayDRProductInfo/productID.' + pid + '/content.name/version.2/output.json/jsonp=?';
            $.getJSON(url, function(data){
                thisAttr = data.productInfo.product.msMedium;
                if(thisAttr){
                    //console.log('fullPath=' + fullPath);
                    fullPath = thisAttr;
                } else {
                    fullPath = inputVariables.storeData.resources.images.MissingImage;
                }
                $('th').each(function(){
                    if($(this).hasClass(thisClass)){
                        $(this).find('img.compareBoxImg').attr('src', fullPath);
                    }
                });
            });
        } else if(attr === 'price'){
            from = inputVariables.storeData.resources.text.FROM;
            regular = inputVariables.storeData.resources.text.REGULAR_PRICE_COLON;
            url = '/store/' + siteID + '/' + locale + '/DisplayDRProductInfo/productID.' + pid + '/content.name+' + attr + '/version.2/output.json/jsonp=?';
            $.getJSON(url, function(data){
                if(data.productInfo.product.price){
                    discounted = data.productInfo.product.price.discounted;
                    unitPrice = data.productInfo.product.price.unitPrice;
                    unitPriceWithDiscount = data.productInfo.product.price.unitPriceWithDiscount;
                    if(discounted === false){
                        if(unitPrice !== ''){
                            $('th').each(function(){
                                if($(this).hasClass(thisClass)){
                                    $(this).find('.price').html(from + ' ' + unitPrice);
                                }
                            });
                        }
                    }
                    if(discounted === true){
                        if(unitPrice !== ''){
                            $('th').each(function(){
                                if($(this).hasClass(thisClass)){
                                    $(this).find('.price').html(from + ' ' + unitPriceWithDiscount + '<br/>' + '<span class="additional">' + regular + ' <del>' + unitPrice + '</del>' + '</span>');
                                }
                            });
                        }
                    }
                }
            });
        } else if(attr === 'displayName'){
            url = '/store/' + siteID + '/' + locale + '/DisplayDRProductInfo/productID.' + pid + '/content.name+' + attr + '/version.2/output.json/jsonp=?';
            $.getJSON(url, function(data){
                if(data.productInfo.product.displayName){
                    name = data.productInfo.product.displayName;
                    $('th').each(function(){
                        if($(this).hasClass(thisClass)){
                            $(this).find('p').html(name);
                        }
                    });
                }
            });
        } else {}
    }
    $(document).ready(function(){
        $.each(pids, function(index, value){
            var pidClass, thIndex;
            pidClass = 'pid' + value;
            thIndex = index + 1;
            $('th:eq(' + thIndex + ')').addClass(pidClass);
            $('th:eq(' + thIndex + ') a').attr('href', '/store/' + inputVariables.storeData.page.siteid + '/' + inputVariables.storeData.page.locale + '/pdp/productID.' + value);
            getProdInfo(value, 'msMedium');
            getProdInfo(value, 'price');
            getProdInfo(value, 'displayName');
        });
    });
}

/*xbox 6up script*/
$.widgetize('6up_rotating', function(){
    var counter = 6;
    if($(this).hasClass('hub5up')){
        counter = 5;
    }
    var $sixuproot = $(this).find('.items');
    var totallis = $(this).find('.items li:not(".cloned")').length;
    var left = 0;
    var liWidth = $(this).find('.items li:not(".cloned"):eq(0)').width();
    if(totallis >= counter){
        $(this).find('.items li:not(".cloned")').each(function(){
            var $ele = $(this),
                $eleClone = $ele.clone();
            $eleClone.addClass('cloned');
            if($ele.next('li:not(".cloned")').length == 0){
                $eleClone.prependTo($sixuproot);
            } else {
                $eleClone.appendTo($sixuproot);
            }
        });
        $sixuproot.css('left', (-1 * liWidth));
        $(this).find('.navButtons .rightBack a,').click(function(){
            $sixuproot.stop();
            left = $sixuproot.position().left % liWidth;
            if(left != 0){
                $sixuproot.css('left', $sixuproot.position().left - left - liWidth);
            }
            if($sixuproot.position().left < (-1 * liWidth * totallis)){
                $sixuproot.css('left', $sixuproot.position().left + (totallis * liWidth));
            }
            $sixuproot.animate({
                'left': "-=" + liWidth
            }, "normal");
        });

        $(this).find('.navButtons .leftBack a').click(function(){
            $sixuproot.stop();
            left = $sixuproot.position().left % liWidth;
            if(left != 0){
                $sixuproot.css('left', $sixuproot.position().left - left);
            }
            if($sixuproot.position().left > (-1 * liWidth)){
                $sixuproot.css('left', $sixuproot.position().left - (totallis * liWidth));
            }
            $sixuproot.animate({
                'left': "+=" + liWidth
            }, "normal");
        });
    } else {
        var contentWidth = $(this).find('.item div.scrollable').width();
        //contentWidth = contentWidth * (6 - totallis) / 12;
        //$(this).find('ul.items').css('left',contentWidth);
        contentWidth = contentWidth / totallis;
        $(this).find('ul.items li').css('width', contentWidth);
    }
    $actualCnt = $(this).find('.scrollable ul li').not('.cloned').length;
    if($actualCnt <= counter){
        $(this).find('.navButtons').css('visibility', 'Hidden');
    }
});

$.widgetize('rotating_selector', function(){
    $(this).find('.items li:not(".cloned"):eq(0)').addClass('active');
    $(this).find('.items li.cloned:eq(1)').addClass('active');
    $(this).find('.promoContainer:eq(0)').show();
    $(this).find('.items li a').each(function(){
        if($(this).attr('data-id').length > 0){
            $(this).removeAttr('href');
            $(this).click(function(){
                var pid = $(this).attr('data-id');
                $('.rotating_selector .promoContainer').hide();
                $('.promoContainer[data-ID=' + pid + ']').show();
                $('.rotating_selector .items li').removeClass('active');
                $('.rotating_selector .items li a[data-id=' + pid + ']').parents().parents().addClass('active');
            });
        }
    });

});
/*---End of xbox script*/
$.widgetize('ageGate', function(){
    var $ageGate = $(this),
        age = ($.getUrlVar('ageGate') === '') ? 'empty' : $.getUrlVar('ageGate'),
        restrictedMsg = '';
    submitButtonUrl = ''

    $('.selector_box').click(function(e){
        $(this).siblings('.option_container').toggle();
        e.preventDefault();
    });

    $(document).mouseup(function(e){
        if($('.option_container').is(":visible") && $(e.target).parents('.selector_box').length == 0){
            $('.option_container').hide();
        }
    });

    $('.option_container li').click(function(){
        if($(this).parent().siblings('.selector_box').attr('value')){
            $(this).parent().siblings('.selector_box').html($(this).attr('value')).attr('value', $(this).attr('value'));
        } else {
            $(this).parent().siblings('.selector_box').html($(this).text());
        }
        $(this).parent().hide();
    });

    $('.ageGateSubmit').click(function(){
        var now = new Date();
        var thisYear = now.getFullYear();
        var selectedYear = $('#ageGateYear .selector_box').attr('value');
        if(selectedYear === 'year'){
            $('#ageGateYear .selector_box, #ageGateYear .option_container').css('border-color', 'red');
        } else {
            var AgeGateMonth = $('#ageGateMonth .selector_box').html();
            var AgeGateDay = $('#ageGateDay .selector_box').html();
            var AgeGateYear = $('#ageGateYear .selector_box').attr('value');
            var c = 18,
                b = new Date;
            $('#ageGateMonth .option_container li').each(function(){
                if($(this).text() == AgeGateMonth){
                    AgeGateMonth = $('#ageGateMonth .option_container li').index($(this)) + 1;
                }
            });
            b.setFullYear(AgeGateYear, AgeGateMonth - 1, AgeGateDay);
            var a = new Date;
            a.setFullYear(a.getFullYear() - c);
            if(a - b < 0){
                $('.ageGateContainer form').hide();
                $('.ageGateContainer').html("<label>" + inputVariables.storeData.resources.text.AGEGATE_SORRY_NO_PERMISSION + "</label>").show();
            } else {
                age = thisYear - selectedYear;
                $(this).parents('form').attr('action', location.protocol + '//' + location.host + location.pathname + '?ageGate=' + age);
                $(this).parents('form').submit();
            }
        }
    });
});
$.widgetize('dr_Compare', function(){
    $("#dr_Compare tr:odd").addClass('odd');
    $('.dr_prodCompare').each(function(){
        $(this).appendTo($(this).prev());
    });
});
$.widgetize('compareContainer', function(){
    $('.dr_prodCompare').each(function(){
        $(this).appendTo($(this).next());
    });
});

$.widgetize('dr_shippingEstimator', function(){
    shippingMethodIDSelected = $("#dr_shippingEstimator input[type='radio']:checked").val();
    $('.dr_shippingEstimator input').click(function(){
        var shippingMethodID = $(this).val(),
            shippingForm = $('form[name="EstimateShippingCostForm"]');
        if(shippingMethodID != shippingMethodIDSelected){
            $('.shippingMethodRadio input').attr('disabled', 'disabled');
            $('form[name="EstimateShippingCostForm"] input[name="shippingOptionID"]').val(shippingMethodID);
            if($('a.newAddress').hasClass('active')){
                $('form[name="EstimateShippingCostForm"] input[name="newAddress"]').val('true');
            }
            var addressRE = /^(SHIPPING)\w+$/,
                addressFields = [],
                siteID = inputVariables.storeData.page.siteid,
                initialPost = 'Action=UpdateBillingShippingAddress&SiteID=' + siteID + '&';
            $('form[name="CheckoutAddressForm"]').find('input:text,select').each(function(){
                if(addressRE.test(this.name)){
                    var fieldVal = $(this).val();
                    addressFields.push(this.name + '=' + encodeURIComponent(fieldVal));
                }
            });
            if(addressFields.length){
                $.ajax({
                    url: '/store/',
                    type: 'POST',
                    global: false,
                    dataType: 'xml',
                    data: initialPost + addressFields.join('&')
                });
            }
            shippingForm.submit();
        }
    });
    if($('div.addressFields').attr('newaddress') != 'true'){
        if($('#shippingAddressBook option').length != 1){
            $('#dr_shipping select#shippingAddressBook').change();
        } else {
            if($('#shippingAddressBook option').eq(0).val() != 'NEW'){
                $('#dr_shipping select#shippingAddressBook').change();
            }
        }
    } else {
        $('a.newAddress').addClass('active');
        $('a.newAddress').trigger('click');
        $('.addressFields').show();
        if($('#shippingAddressBook option[value=NEW]').length == 0){
            $('#shippingAddressBook').append('<option value="NEW" selected="selected">...</option>');
        }
    }
    $('input#shippingAddress2').focus(function(){
        $(this).siblings('.optionalText').hide();
    });
    $('input#shippingAddress2').siblings('.optionalText').click(function(){
        $(this).hide();
        $(this).siblings('input#shippingAddress2').focus();
    });
    var prefix = 'shipping';
    if(document.getElementById(prefix + 'Address2')){
        if(document.getElementById(prefix + 'Address2').value != ""){
            $('.optionalText').hide();
        }
    }

    var $CheckoutAddressForm = $('form[name=CheckoutAddressForm]');
    $CheckoutAddressForm.submit(function(){
        if($('select#shippingState').attr('data-required') === 'false'){
            $('select#shippingState').parent().remove();
        }
        $('select#shippingCountry').removeAttr('disabled');
    });
    if($('select#shippingCountry').length > 0){
        $('#dr_shipping .dr_formLine').hide();
        $('select#shippingCountry').parent().show();
        $('select#shippingCountry').parents('.addressFields,.defaultAddressFields').show();
        $("select#shippingCountry option[selected='selected']").removeAttr("selected"); //deselect all options
        $("select#shippingCountry option[value='']").attr("selected", "selected"); //select the second option
        $('select#shippingCountry').change(function(){
            var shippingCountry = $(this).val();
            //console.log(shippingCountry);
            $.ajax({
                url: '/Storefront/Site/mscommon/cm/multimedia/js/dr-CrossBorderMapping_16.js', //holds the mapping to the form fields
                dataType: 'json',
                cache: true,
                success: function(data){
                    $.each(data.COUNTRYinfoModal, function(){
                        datacon = this;
                        if(datacon.COUNTRYinfo.shippingCountry === shippingCountry){
                            mappingExistence = true;
                            $('select#shippingCountry option').each(function(){
                                var optionValue = $(this).val();
                                if(optionValue != shippingCountry){
                                    $(this).remove();
                                }
                            });
                            $('select#shippingAddressBook option').each(function(){
                                var optionValue = $(this).attr('data-country');
                                if(optionValue != shippingCountry){
                                    $(this).remove();
                                }
                            });
                            if($('select#shippingAddressBook option').length <= 0){
                                if($('a#newAddress').length){
                                    $('a.newAddress').addClass('active');
                                    $('a.newAddress').siblings().hide();
                                    $('a.newAddress').trigger('click');
                                } else {
                                    $('#shippingAddressBook').append('<option value="NEW" selected="selected">...</option>');
                                }
                            }
                            $('select#shippingAddressBook').change();
                            if(datacon.COUNTRYinfo.shippingState === 'false'){
                                $('a.newAddress').trigger('click');
                                $('#shippingState option').removeAttr('selected');
                                $('#shippingState option:eq(0)').text(inputVariables.storeData.resources.text.STATE_PROVINCE).attr('selected', 'selected');
                                $('#shippingState').addClass('empty');
                                $('#dr_shipping .addressFields .dr_formLine, #dr_shipping .defaultAddressFields .dr_formLine').removeClass('even');
                                $('#dr_shipping .dr_formLine').show();
                                if(datacon.COUNTRYinfo.address2 != null && datacon.COUNTRYinfo.address2 == "false"){
                                    $('.dr_formLine.address2').hide();
                                } else {
                                    $('.dr_formLine.address2').show();
                                }
                                $('select#shippingState').parent().hide();
                                $('#dr_shipping .addressFields .dr_formLine:visible:odd, #dr_shipping .defaultAddressFields .dr_formLine:visible:odd').addClass('even');
                                $('select#shippingState').attr('data-required', 'false');
                            } else {
                                var shippingStateOptions = datacon.COUNTRYinfo.shippingStateOptions;
                                $('select#shippingState').html(shippingStateOptions);
                                $('select#shippingAddressBook').change();
                                $('a.newAddress').trigger('click');
                                $('#shippingState option').removeAttr('selected');
                                $('#shippingState option:eq(0)').text(inputVariables.storeData.resources.text.STATE_PROVINCE).attr('selected', 'selected');
                                $('#shippingState').addClass('empty');
                                $('#dr_shipping .dr_formLine').show();
                                if(datacon.COUNTRYinfo.address2 != null && datacon.COUNTRYinfo.address2 == "false"){
                                    $('.dr_formLine.address2').hide();
                                } else {
                                    $('.dr_formLine.address2').show();
                                }
                                $('select#shippingState').parent().show();
                                $('select#shippingState').attr('data-required', 'true');
                            }
                        }
                    });
                    $("select#shippingCountry option[value=" + shippingCountry + "]").attr("selected", "selected");
                    $('select#shippingCountry').attr('disabled', 'disabled');
                    // Address ordering on Customer Info Page
                    var seqList = inputVariables.storeData.resources.shippingAddrSeq[shippingCountry];
                    if(seqList){
                        if(!seqList.match("Config_AddressSequence_")){
                            var seqArray = seqList.split(',');
                            var addrContent = [];
                            addrContent.push($('<div>').append($('.addressFields .dr_formLine #shippingName1').parent().clone()).html());
                            addrContent.push($('<div>').append($('.addressFields .dr_formLine #shippingName2').parent().clone()).html());
                            $.each(seqArray,function(index,val){
                                if(val=='Address1'){
                                    var elementsToPush = $('<div>').append($('.addressFields .dr_formLine #shippingAddress1').parent().clone()).html();
                                    addrContent.push(elementsToPush);
                                }
                                else if(val=='Address2'){
                                    var elementsToPush = $('<div>').append($('.addressFields .dr_formLine #shippingAddress2').parent().clone()).html();
                                    addrContent.push(elementsToPush);
                                }
                                else if(val=='PostalCode'){
                                    var elementsToPush = $('<div>').append($('.addressFields .dr_formLine #shippingPostalCode').parent().clone()).html();
                                    addrContent.push(elementsToPush);
                                }
                                else if(val=='City'){
                                    var elementsToPush = $('<div>').append($('.addressFields .dr_formLine #shippingCity').parent().clone()).html();
                                    elementsToPush = elementsToPush + $('<div>').append($('.addressFields .dr_formLine #shippingState').parent().clone()).html();
                                    addrContent.push(elementsToPush);
                                }
                            });
                            addrContent.push($('<div>').append($('.addressFields .dr_formLine #shippingPhoneNumber').parent().clone()).html());
                            addrContent.push('<div class="clear"></div>');
                            addrContent.push($('<div>').append($('.addressFields .dr_formLine #shippingCountry').parent().clone()).html());
                            $('#dr_shippingContainer .addressFields').css('width','103%').html(addrContent.join(''));
                            $('#dr_shipping .addressFields .dr_formLine').css({'clear': 'none', 'padding-right': '2.911%', 'width':'47.089%', 'padding-left':'0'
                            });
                            $('a.newAddress').trigger('click');
                        }
                    }
                },
                error: function(){
                    //submit the form if any error
                }
            });
        });
    }
});

$.widgetize('dr_shippingContainer', function(){
    $('#dr_shipping .addressFields .dr_formLine:odd, #dr_shipping .defaultAddressFields .dr_formLine:odd').addClass('even');
    if($('input.newAddress').length > 0){
        if($('div.addressFields').attr('newaddress') != 'true'){
            if($('#shippingAddressBook option').length != 1){
                $('#dr_shipping select#shippingAddressBook').change();
            } else {
                if($('#shippingAddressBook option').eq(0).val() != 'NEW'){
                    $('#dr_shipping select#shippingAddressBook').change();
                    $('.addressFields').show();
                    $('#shippingAddressBook').append('<option value="NEW" selected="selected">...</option>');
                }
            }
        } else {
            $('input.newAddress').attr('checked', 'checked');
            $('input.newAddress').trigger('change');
        }
        $("select#shippingCountry option[value='']").attr("selected", "selected");
    }

    var $shippingContainer = $(this);

    $shippingContainer.find('.defaultAddressFields').removeClass('hide').show();

    $shippingContainer.find('.editAddress').on('click', function(){
        $(this).addClass('active');
        $('.addressFields', $shippingContainer).slideDown();
        if($('#shippingAddressBook option[value=NEW]', $shippingContainer).length > 0){
            $('a.newAddress', $shippingContainer).removeClass('active');
            $('#shippingAddressBook option[value=NEW]').remove();
            $('#shippingAddressBook', $shippingContainer).change();
        }
        $shippingContainer.find('select#shippingState').removeClass('empty');
    });

    $shippingContainer.find('select#shippingState').change(function(){
        if($(this).find('option:eq(0)').attr('selected')){
            $(this).addClass('empty');
        } else {
            $(this).removeClass('empty');
        }
    });

    /*if(navigator.userAgent.match(/msie|trident/i) && $('body').hasClass('lt-ie10')){
     $('#dr_shipping input[type=text]').each(function(){
     $(this).focus(function(){
     if($(this).hasClass('placehold')){
     setCaretTo($(this).get(0), 0, 0);
     }
     $(this).on('keypress', function(e){
     if($(this).hasClass('placehold') && e.which !== 0 && e.charCode !== 0){
     $(this).val('').removeClass('placehold');
     }
     }).on('keyup', function(e){
     if($(this).val() == ''){
     $(this).val($(this).attr('placeholder')).addClass('placehold');
     setCaretTo($(this).get(0), 0, 0);
     }
     });
     }).blur(function(){
     if($(this).val() == ''){
     $(this).val($(this).attr('placeholder')).addClass('placehold');
     }
     });
     if($('#shippingAddressBook').parent().is(':hidden')){
     $(this).val($(this).attr('placeholder')).addClass('placehold');
     }
     });

     function setCaretTo(element, start, end){
     if(element.createTextRange){
     var range = element.createTextRange();
     if(start && end){
     range.moveStart('character', start);
     range.moveEnd('character', end);
     } else {
     range.move('character', start);
     }
     range.select();
     } else if(element.selectionStart){
     element.focus();
     element.setSelectionRange(start, (end || start));
     }
     }
     }*/

    $shippingContainer.find('a.newAddress').on('click', function(){
        $('.editAddress', $shippingContainer).removeClass('active');
        $(this).addClass('active');
        $('.addressFields').slideDown();
        if($('option[value=NEW]', $('#shippingAddressBook')).length == 0){
            $('#shippingAddressBook').append('<option value="NEW" selected="selected">...</option>');
        }
        if($('div.addressFields').attr('newaddress') != 'true'){
            $('.addressFields input[type=text]').each(function(){
                $(this).val('');
            });
            if($('#shippingState').is('select')){
                $('#shippingState option').removeAttr('selected');
                $('#shippingState option:eq(0)').attr('selected', 'selected');
                $('#shippingState').addClass('empty');
            } else if($('#shippingState').is('input')){
                $('#shippingState').val('');
            }
        }
    });
});

$.widgetize('paymentInstrumentList', function(){
    var $selector = $(this).find('.selector'),
        $options = $selector.siblings('.options'),
        $optionLi = $options.find('li'),
        $selectedOption = $options.find('li[selected=selected]');

    $selector.css('width', ($options.width() - parseInt($selector.css('border-width'))));

    if($('.paymentInstrumentList .editPayment label[for]').length == 0){
        $selectedOption = $options.find('li[selected=selected]');
        $('.paymentInstrumentList .editPayment label').attr('for', $selectedOption.attr('piid_value'));
    }

    $selector.on('click', function(){
        $(this).focus();
        $selectedOption = $options.find('li[selected=selected]');
        if($options.css('visibility') == 'hidden'){
            $options.css('visibility', 'visible');
        } else {
            $options.css('visibility', 'hidden');
        }
        $selectedOption.addClass('focus');
        $options.scrollTop($selectedOption.index() * $selectedOption.outerHeight());
    }).on('keydown', function(e){
        var k = e.which;
        $selectedOption = $options.find('li[selected=selected]');
        if(k == 40 || k == 39){
            e.preventDefault();
            if($selectedOption.next().length > 0){
                var $nextOption = $options.find('li[selected=selected]').next();
                $options.find('li').removeClass('focus');
                $nextOption.attr('selected', 'selected').addClass('focus');
                $selectedOption.removeAttr('selected');
                changeOption();
                if($nextOption.position().top + $nextOption.outerHeight() > $options.height()){
                    var scroll = $nextOption.position().top + $nextOption.outerHeight() - $options.height();
                    $options.animate({
                        scrollTop: $options.scrollTop() + scroll
                    }, 100);
                }
            }
        } else if(k == 37 || k == 38){
            e.preventDefault();
            if($selectedOption.prev().length > 0){
                var $prevOption = $options.find('li[selected=selected]').prev();
                $options.find('li').removeClass('focus');
                $prevOption.attr('selected', 'selected').addClass('focus');
                $selectedOption.removeAttr('selected');
                changeOption();
                if($prevOption.position().top < 0){
                    var scroll = $prevOption.position().top;
                    $options.animate({
                        scrollTop: $options.scrollTop() + scroll
                    }, 100);
                }
            }
        }
    });

    $(document).on('click', function(e){
        var $target = $(e.target);
        if(!$target.is('.paymentInstrumentList .selector,.paymentInstrumentList .options li')){
            $options.css('visibility', 'hidden');
            $optionLi.removeClass('focus');
        }
    });

    $optionLi.on('hover', function(){
        $optionLi.removeClass('focus');
        $(this).addClass('focus');
    }).on('click', function(){
        $optionLi.removeAttr('selected');
        $(this).attr('selected', 'selected');
        changeOption();
        $options.css('visibility', 'hidden');
    });

    var changeOption = function(){
        var $selectedOption = $('.options li[selected=selected]');
        $('.paymentInstrumentList .chosen input[name="piid"]').val($selectedOption.attr('piid_value'));
        $('.paymentInstrumentList .chosen .content').html($selectedOption.html());
        $('.paymentInstrumentList .editPayment label').attr('for', $selectedOption.attr('piid_value'));
    }
    $('.editCard,.paymentInstrumentList .editLink').click(function(){
        piid = $(this).parent().attr('for');
        $('form[name="CheckoutAddressForm"] input[name="editcard"]').val('true');
        $('form[name="CheckoutAddressForm"] input[name="piid"]').val(piid);
        $('.continueButtonBottom input#checkoutButton').trigger('click');
    });
});

$.widgetize('newAddress', function(){
    $('input.newAddress').change(function(){
        if(this.checked){
            $('.addressFields').show();
            $('#shippingAddressBook').append('<option value="NEW" selected="selected">...</option>');
            var prefix = 'shipping';
            if($('div.addressFields').attr('newaddress') != 'true'){
                if(document.getElementById(prefix + 'Name1')){
                    document.getElementById(prefix + 'Name1').value = '';
                }
                if(document.getElementById(prefix + 'Name2')){
                    document.getElementById(prefix + 'Name2').value = '';
                }
                if(document.getElementById(prefix + 'CompanyName')){
                    document.getElementById(prefix + 'CompanyName').value = '';
                }
                document.getElementById(prefix + 'Address1').value = '';
                document.getElementById(prefix + 'Address2').value = '';
                document.getElementById(prefix + 'City').value = '';
                if(document.getElementById(prefix + 'State')){
                    document.getElementById(prefix + 'State').value = '';
                }
                document.getElementById(prefix + 'PostalCode').value = '';
                document.getElementById(prefix + 'PhoneNumber').value = '';
                if(document.getElementById(prefix + 'PhoneNumber2')){
                    document.getElementById(prefix + 'PhoneNumber2').value = '';
                }
                if(document.getElementById(prefix + 'FaxPhone')){
                    document.getElementById(prefix + 'FaxPhone').value = '';
                }
                if(document.getElementById(prefix + 'Email')){
                    document.getElementById(prefix + 'Email').value = '';
                }
            }
        } else {
            //$('.addressFields').show();
            $("#shippingAddressBook option[value='NEW']").remove();
            $('#dr_shipping select').change();
        }
    });
});

/* Top Features for O15 CAT */
$.widgetize('topFeaturesToggle', function(){
    $('.topFeaturesToggle .topFeatures_head span').click(function(){
        var $this = $(this),
            $parentObj = $this.parents('.topFeatures');
        $('.topFeatures_content', $parentObj).stop(true, true).slideToggle();
        if($this.hasClass('down')){
            $this.removeClass('down');
        } else {
            $this.addClass('down');
        }
    });
});

/* Featured Laptop */
$.widgetize('featuredLaptop', function(){

    var $hero = $(this).find('.pdp');

    $hero.find('.mainImageContainer').hover(

        function(){
            $('.expandAndExplore').stop(true, true).fadeIn()
        },

        function(){
            $('.expandAndExplore').stop(true, true).fadeOut()
        }).on('click', function(){
            $('.pdp > .image, .pdp > .buy').stop(true, true).animate({
                opacity: 0,
                left: '200px'
            }, 1000);
            $('.pdp > .image_switcher').show().css({
                'left': '-170px',
                'opacity': 0
            }).stop(true, true).animate({
                opacity: 1,
                left: '-200px'
            }, 1000);
            $.widgetize('image_switcher');
        });

    $('.overlayCloseBtn').on('click', function(){
        $('.pdp > .image_switcher').stop(true, true).animate({
            opacity: 0,
            left: '-220px'
        }, {
            duration: 1000,
            complete: function(){
                $(this).hide();
            }
        });
        $('.pdp > .image, .pdp > .buy').stop(true, true).animate({
            opacity: 1,
            left: 0
        }, 1000);
    });
});

/* Reversed Tile */
$.widgetize('reversed_tile', function(){
    $(".reversed_tile .overlayTip").find(".overlayTip_Icon").each(function(){
        var $content = $(this).parent().find(".overlayTip_Content");
        $(this).hover(function(){
            $content.show();
            $content.animate({
                "width": "153px",
                "height": "94px",
                "top": "-117px",
                "left": "-83px",
                "font-size": "100%"
            }, 140);
        }, function(){
            $content.animate({
                "width": "0px",
                "height": "0px",
                "top": "-22px",
                "left": "-13px",
                "font-size": "0%"
            }, 140);
            $content.hide();
        });
    });
});

/* 360 Image */
$.widgetize('photo_360', function(){
    $('img.PhotoBigPd').each(function(){
        var $photoBigPd = $(this);
        var $imgContent = $(this).parent();
        var dataID = $imgContent.parent().attr('data-largeimage');
        var thumbLength;
        $imgContent.parents('.largeImageContainer').siblings('.thumbImageContainer').children('div').each(function(){
            if($(this).attr('data-id') === dataID){
                //console.log(dataID);
                thumbLength = $(this).children('.ms_pd_imageSwitchT').length;
                //console.log(thumbLength);
                if(thumbLength > 0){
                    $photoBigPd.appendTo('body').show();
                    $photoBigPd.attr('data-width', $photoBigPd.width());
                    $photoBigPd.attr('style', 'display:none; width: ' + $photoBigPd.width() / 36 + 'px; height: ' + $photoBigPd.height() + 'px;');
                    var originalHeight = $photoBigPd.css('height'),
                        originalWidth = $photoBigPd.css('width');
                    $photoBigPd.css({
                        'width': 'auto',
                        'height': 'auto'
                    });
                    $photoBigPd.hide();

                    $photoBigPd.appendTo($imgContent);
                    if(!$photoBigPd.parent().find('div[class="rotatingPdImage"]').length){
                        $photoBigPd.parent().append('<div class="rotatingPdImage"></div>').append('<div class="rotatingPdImageWaterMark"></div>');
                    }
                    var rotationLayer = $photoBigPd.parent().find('div.rotatingPdImage'),
                        settings = {
                            cursorStartPosition: 0,
                            unit: 0,
                            spritePosition: 0,
                            spriteImageCount: 0,
                            spriteWidth: 0,
                            stageWidth: 0,
                            state: 0
                        };
                    rotationLayer.css({
                        'background-image': 'url(' + $photoBigPd.attr('src') + ')',
                        'height': originalHeight,
                        'width': originalWidth
                    });
                    rotationLayer.show();
                    $photoBigPd.parent().find('.rotatingPdImageWaterMark').show();
                    settings.spriteWidth = $photoBigPd.data('width');
                    settings.stageWidth = rotationLayer.width();
                    settings.spriteImageCount = settings.spriteWidth / settings.stageWidth;
                    settings.unit = Math.round(settings.stageWidth / settings.spriteImageCount);
                    rotationLayer.bind('selectstart', function(){
                        return false;
                    });
                    $('body').bind('mouseup', function(){
                        settings.state = 0;
                        $('.rotatingPdImageWaterMark').animate({
                            opacity: 1
                        }, 50);
                        return false;
                    });
                    rotationLayer.bind('mousedown', function(e){
                        settings.state = 1;
                        $photoBigPd.parent().find('.rotatingPdImageWaterMark').animate({
                            opacity: 0.5
                        }, 50);
                        settings.cursorStartPosition = e.pageX;
                        $('body').bind('mousemove', function(e){
                            if(settings.state){
                                var distance = parseInt(e.pageX - settings.cursorStartPosition),
                                    absDistance = Math.abs(distance),
                                    unit = settings.unit;
                                if(absDistance >= unit){
                                    var direction = distance / absDistance,
                                        a = settings.spritePosition + settings.stageWidth * direction,
                                        b = settings.spriteWidth;
                                    settings.spritePosition = (a % b + b) % b;
                                    rotationLayer.css('background-position', '-' + settings.spritePosition + 'px 0px');
                                    settings.cursorStartPosition = e.pageX;
                                }
                            }
                        });
                    });
                    $photoBigPd.attr('src', inputVariables.storeData.resources.images.transp);
                    $photoBigPd.css({
                        'width': originalWidth,
                        'height': originalHeight
                    });
                } else {
                    $photoBigPd.show();
                }
            }
        });
    });
});

/* Facet Serach */
$.widgetize('facet-search', function(){
    var categoryID = $(this).attr('category-id'),
        originalKeywords = $('.resultContainer').attr('original-keywords');

    var $facetSearch = $(this),
        lastRequest,
        f_categoryID = cookieObj.getCookie('f_categoryID'),
        f_keywords = cookieObj.getCookie('f_keywords_'+categoryID),
        f_sort = cookieObj.getCookie('f_sort_'+categoryID),
        f_startIndex = parseInt(cookieObj.getCookie('f_startIndex_'+categoryID)),
        f_size = parseInt(cookieObj.getCookie('f_size_'+categoryID)),
        c_originalKeywords = cookieObj.getCookie('originalKeywords');

    if(f_categoryID === $facetSearch.attr('category-id') && f_keywords !== null && f_sort !== null && f_startIndex !== null && f_size !== null){
        var f_fullQuery = f_keywords;

        if($facetSearch.hasClass('search') && f_keywords !== c_originalKeywords){
            f_fullQuery += ' AND ('+c_originalKeywords+')';
        }

        /* price sorting search result */
        if(f_sort.match(/listPrice/g) && $('body').hasClass('CategoryProductListPage')){
            f_size = 9999;
        }

        f_fullQuery += '&sort=' + f_sort + '&size=' + f_size + '&startIndex=' + f_startIndex;

        if($facetSearch.hasClass('search')){
            if(c_originalKeywords !== null && originalKeywords === c_originalKeywords){
                if(f_keywords !== '*:*'){
                    var itemsArray = f_keywords.split(' AND ');
                    for(var i=0; i<itemsArray.length; i++){
                        if(itemsArray[i].indexOf('(') == 0) {
                            var groupStrings = itemsArray[i].substring(1, itemsArray[i].length - 1).split(' OR ');
                            for(var j=0; j<groupStrings.length; j++){
                                var groupString = groupStrings[j].split(':');
                                $facetSearch.find("div[facetfiltername='groupFilters'] .filterbox").each(function(){
                                    if($(this).attr('facetfilterquery') === groupString[0]){
                                        $(this).attr('checked','checked');
                                    }
                                });
                            }
                        } else {
                            if(itemsArray[i].indexOf(':') !== -1){
                                var itemArray = itemsArray[i].split(':'),
                                    filterQuery = itemArray[1];
                                if(filterQuery.indexOf('[') == -1&&filterQuery.indexOf(']') == -1){
                                    filterQuery = filterQuery.replace(/"|\(|\)/g,'');
                                } else {
                                    filterQuery = filterQuery.replace(/\(|\)/g,'');
                                }
                                var conditionArray = filterQuery.split(' OR ');
                                for(var j=0; j<conditionArray.length; j++){
                                    $facetSearch.find('.filterbox').each(function(){
                                        if($(this).attr('facetfilterquery') === conditionArray[j]){
                                            $(this).attr('checked','checked');
                                        }
                                    });
                                }
                            }
                        }
                    }
                }

                filterAjax(f_categoryID,f_fullQuery,f_keywords,f_size,f_startIndex,f_sort);
            }
        } else {
            if(f_keywords !== '*:*'){
                var itemsArray = f_keywords.split(' AND ');
                for(var i=0; i<itemsArray.length; i++){
                    if(itemsArray[i].indexOf('(') == 0) {
                        var groupStrings = itemsArray[i].substring(1, itemsArray[i].length - 1).split(' OR ');
                        for(var j=0; j<groupStrings.length; j++){
                            var groupString = groupStrings[j].split(':');
                            $facetSearch.find("div[facetfiltername='groupFilters'] .filterbox").each(function(){
                                if($(this).attr('facetfilterquery') === groupString[0]){
                                    $(this).attr('checked','checked');
                                }
                            });
                        }
                    } else {
                        if(itemsArray[i].indexOf(':') !== -1){
                            var itemArray = itemsArray[i].split(':'),
                                filterQuery = itemArray[1];
                            if(filterQuery.indexOf('[') == -1&&filterQuery.indexOf(']') == -1){
                                filterQuery = filterQuery.replace(/"|\(|\)/g,'');
                            } else {
                                filterQuery = filterQuery.replace(/\(|\)/g,'');
                            }
                            var conditionArray = filterQuery.split(' OR ');
                            for(var j=0; j<conditionArray.length; j++){
                                $facetSearch.find('.filterbox').each(function(){
                                    if($(this).attr('facetfilterquery') === conditionArray[j]){
                                        $(this).attr('checked','checked');
                                    }
                                });
                            }
                        }
                    }
                }
            }

            filterAjax(f_categoryID,f_fullQuery,f_keywords,f_size,f_startIndex,f_sort);
        }
    }

    $('input.filterbox').on('change',function(){
        var categoryID = $facetSearch.attr('category-id'),
            queryArray = new Array(),
            sortData = $('.dr_catSortOptions').length?$('.dr_catSortOptions option:selected').val():'',
            keywords = '',
            fullQuery = '',
            f_sort = cookieObj.getCookie('f_sort_'+categoryID),
            startIndex = 0,
            size = parseInt($('#productListContainer').attr('size'));

        if(f_sort != null){
            sortData = f_sort;
        }
        /* price sorting search result */
        if(sortData.match(/listPrice/g) && $('body').hasClass('CategoryProductListPage')){
            size = 9999;
        }

        $('.facet-sub',$facetSearch).each(function(){
            var facetFilterQuery = new Array(),
                facetFilterChecked = false,
                facetfiltername = $(this).attr('facetfiltername');

            if($(this).find('input.filterbox:checked').length){
                facetFilterChecked = true;
                $(this).find('input.filterbox:checked').each(function(){
                    var queryString = String($(this).attr('facetfilterquery'));
                    if(facetfiltername == "groupFilters") {
                        queryString = queryString+':("true")';
                    } else {
                        if(queryString.indexOf('[') == -1&&queryString.indexOf(']') == -1){
                            queryString = '"'+queryString+'"';
                        }
                    }
                    facetFilterQuery.push(queryString);
                });
            }
            if(facetFilterChecked){
                if(facetfiltername == "groupFilters") {
                    queryArray.push('('+facetFilterQuery.join(' OR ')+')');
                } else {
                    queryArray.push(facetfiltername+':('+facetFilterQuery.join(' OR ')+')');
                }
            }
        });
        if(queryArray.length){
            fullQuery += queryArray.join(' AND ');
            keywords = fullQuery;
            if($facetSearch.hasClass('search')){
                fullQuery += ' AND ('+$('.resultContainer').attr('original-keywords')+')';
            }
        } else {
            if($facetSearch.hasClass('search')){
                fullQuery += $('.resultContainer').attr('original-keywords');
            } else {
                fullQuery += '*:*';
            }
            keywords = fullQuery;
        }
        fullQuery += '&size='+size+'&startIndex='+startIndex;
        if(sortData !== ''){
            fullQuery += '&sort='+sortData;
        }

        filterAjax(categoryID,fullQuery,keywords,size,startIndex,sortData);

    });

    function filterAjax(categoryID,fullQuery,keywords,size,startIndex,sortData){
        var $productContainer = $('#productListContainer'),
            callingPage = $facetSearch.attr('calling-page');
        fullQuery = fullQuery.replace(keywords, keywords.replace(/\%22/g, '\\%22'));
        if(lastRequest){
            lastRequest.abort();
            lastRequest = null;
        }
        lastRequest = $.ajax({
            url: '/store/' + inputVariables.storeData.page.siteid + "/" + inputVariables.storeData.page.locale + '/filterSearch/categoryID.' + categoryID + '?keywords=' + encodeURI(fullQuery),
            dataType:'html',
            data: {Env:inputVariables.storeData.page.Env,callingPage:callingPage},
            beforeSend: function(){
                /*$facetSearch.css({'top':0});*/

                $productContainer.html('<img style="display:block; margin:10em auto;" src="'+ inputVariables.storeData.resources.images.loader +'" />').fadeIn();
                $productContainer.addClass('off');
            }
        }).done(function(data){
            var resultHtml = $(data).find('.resultContainer').html(),
                $target = $(document).find('.resultContainer'),
                totalSize = parseInt($(data).find('#productListContainer').attr('total-size')),
                resultKeywords = $('.resultContainer').attr('original-keywords'),
                bvParam = { productIds: [], containerPrefix: 'BVRRInlineRating' };
            $(data).find("a[pid-ref]").each(function(){
                bvParam.productIds.push($(this).attr("pid-ref"));
            });
            $(data).find("div[rroverrideid]").each(function(){
                bvParam.productIds.push($(this).attr("rroverrideid"));
            });
            /* price sorting search result */
            if(sortData.match(/listPrice/g) && $('body').hasClass('CategoryProductListPage')){
                var $sortedData = $(data),
                    sortingArray = (sortData.match(/ascending/g))?$sortedData.find('a.product[data-sort]').priceSorting('ascending'):$sortedData.find('a.product[data-sort]').priceSorting('descending'),
                    gridRowMutipler = 1,
                    gridColumnBase = 3,
                    gridSortedContainer = '#productListContainer > .row > .product-row';

                $sortedData.find(gridSortedContainer).html('');
                for(var i = 0; i <sortingArray.length; i++){
                    if(i < gridRowMutipler*gridColumnBase){
                        $sortedData.find(gridSortedContainer + ':eq(' + (gridRowMutipler-1) + ')').append($(sortingArray[i]).wrap('<div></div>').parent().html());
                    } else {
                        gridRowMutipler++;
                        $sortedData.find(gridSortedContainer + ':eq(' + (gridRowMutipler-1) + ')').append($(sortingArray[i]).wrap('<div></div>').parent().html());
                    }
                }

                var hideAfter = 4;
                $sortedData.find('#productListContainer > .row').each(function(){
                    if($(this).index() > hideAfter){
                        $(this).hide();
                    }
                });

                $sortedData.find('#productListContainer').attr('calling-size','15');
                $sortedData.find('#productListContainer').attr('size','15');
                size = 15;
                resultHtml = $sortedData.find('.resultContainer').html();
            }

            $target.html(resultHtml).hide();

            $('.facet-title .text .product-count').html(totalSize);

            if($('.facet-sub .filterlabel input:checked').length){
                var filterLabelHtml = '';
                $('.facet-sub .filterlabel input:checked').each(function(){
                    filterLabelHtml += '<a href="javascript:void(0)" class="filter" data-id="' + $(this).attr('facetfilterquery') + '"><span class="text-container">' + $(this).siblings('span.filter-value').html() + '<span class="close-icon">&#215;</span></span></a>';
                    $target.find('.filtered-by-links').html(filterLabelHtml);
                });

                $('.filtered-by-links a.filter').on('click',function(){
                    var filterData = $(this).attr('data-id');
                    $(this).hide();
                    $('.facet-sub .filterlabel input:checked').each(function(){
                        if($(this).attr('facetfilterquery') === filterData){
                            $(this).trigger('click');
                        }
                    });

                    if(!$('.facet-sub .filterlabel input:checked').length){
                        $('.filtered-by-container').removeClass('has-filters');
                    }
                });
            } else {
                $('.filtered-by-container').removeClass('has-filters');
            }

            $('a.refine-results-link').click(function(){
                $('.slim-header,.product-list,.category-gutter,.footer-links,.site-footer').addClass('mobile-tablet-hide').hide();
                $('#body .grid-container:eq(0)').children().addClass('mobile-tablet-hide');
                $('.facet-search').addClass('active');
                $(window).scrollTop(0);
            });
            $('.facet-search a.apply,.facet-search .close-btn').click(function(){
                $('.slim-header,.product-list,.category-gutter,.footer-links,.site-footer').removeClass('mobile-tablet-hide').show();
                $('#body .grid-container:eq(0)').children().removeClass('mobile-tablet-hide');
                $('.facet-search').removeClass('active');
                $(window).scrollTop(0);
            });

            $(window).on('resize',function(){
                if($('.facet-search .close-btn').is(':hidden')){
                    $('.slim-header,.product-list,.category-gutter,.footer-links,.site-footer').removeClass('mobile-tablet-hide').show();
                    $('#body .grid-container:eq(0)').children().removeClass('mobile-tablet-hide');
                    $('.facet-search').removeClass('active');
                    $(window).scrollTop(0);
                }
            });

            if((startIndex+size) < totalSize){
                startIndex += size;
            }
            $target.find('#productListContainer').attr('startIndex',startIndex);
            $target.attr('keywords',keywords);

            if($target.hasClass('mobile-tablet-hide')){
                $.widgetize('dr_catSortOptions');
                if($('.compareContainer').length){
                    initCompare();
                }
                $productContainer.removeClass('off');
            } else {
                $target.fadeIn('400',function(){
                    $.widgetize('dr_catSortOptions');
                    if($('.compareContainer').length){
                        initCompare();
                    }
                    $productContainer.removeClass('off');
                });
            }

            if(!$('.resultContainer').hasClass('mobile-tablet-hide')){
                $(window).scrollTop(0);
            }

            $BV.ui('rr', 'inline_ratings', bvParam);
            cookieObj.setCookie('f_categoryID',categoryID);
            cookieObj.setCookie('f_keywords_'+categoryID,keywords);
            cookieObj.setCookie('f_sort_'+categoryID,sortData);
            cookieObj.setCookie('f_startIndex_'+categoryID,0);
            cookieObj.setCookie('f_size_'+categoryID,size);
            if($facetSearch.hasClass('search')){
                cookieObj.setCookie('originalKeywords',resultKeywords);
            }

            $('.filtered-by-container .clear-filters').on('click',function(){
                $('.facet-reset .clear-filters').trigger('click');
            });

        });
    }

    $('a.refine-results-link').click(function(){
        $('.slim-header,.product-list,.category-gutter,.footer-links,.site-footer').addClass('mobile-tablet-hide').hide();
        $('#body .grid-container:eq(0)').children().addClass('mobile-tablet-hide');
        $('.facet-search').addClass('active');
    });
    $('.facet-search a.apply,.facet-search .close-btn').click(function(){
        $('.slim-header,.product-list,.category-gutter,.footer-links,.site-footer').removeClass('mobile-tablet-hide').show();
        $('#body .grid-container:eq(0)').children().removeClass('mobile-tablet-hide');
        $('.facet-search').removeClass('active');
        $(window).scrollTop(0);
    });

    $('.clear-filters').on('click',function(){
        cookieObj.deleteCookie('f_categoryID');
        cookieObj.deleteCookie('f_keywords_'+categoryID);
        cookieObj.deleteCookie('f_sort_'+categoryID);
        cookieObj.deleteCookie('f_startIndex_'+categoryID);
        cookieObj.deleteCookie('f_size_'+categoryID);
        cookieObj.setCookie('originalKeywords');

        location.reload();
    });
});

/*Color Selector*/
$.widgetize('color_switcher', function(){
    $('.colorSwitchT a').live('click', function(e){
        e.preventDefault();
        switchColor.apply(this);
    });
    $('.colorSwitchT a:first').click();

    function hideColorImages(divName){
        if(divName === 'largeImageContainer'){
            $('.largeImageContainer div,.largeImageContainer div img').addClass("hide");
        } else if(divName === 'thumbImageContainer'){
            $('.thumbImageContainer > div:not(.suites)').addClass("hide");
        } else {
            $('.' + divName + '').addClass("hide");
        }
    }

    function switchColor(obj){
        //console.log('variationID=' + variationID);
        hideColorImages('largeImageContainer');
        hideColorImages('thumbImageContainer');
        var variationID = $(this).find('div').data('id');
        var activeID = $('.colorSwitchT a[class=active]').find('div').data('id');
        var imageID = "";
        if($('div[data-id=' + activeID + '] .ms_pd_imageSwitchT a[class=active]').find('img').length){
            imageID = $('div[data-id=' + activeID + '] .ms_pd_imageSwitchT a[class=active]').find('img').data('largeimage').replace(activeID, variationID);
        }
        if($('.largeImageContainer div[data-largeimage=' + variationID + '] img[data-largeimage=' + imageID + ']').length != 1){
            imageID = $('.largeImageContainer div[data-largeimage=' + variationID + '] img:first').data('largeimage');
        }
        $(".colorSwitchT a").removeClass("active");
        $(this).addClass("active");
        $('.largeImageContainer div[data-largeimage=' + variationID + '],.thumbImageContainer div[data-id=' + variationID + ']').removeClass("hide");
        $('.largeImageContainer div[data-largeimage=' + variationID + '] img').addClass("hide");
        $('.largeImageContainer div[data-largeimage=' + variationID + '] img[data-largeimage=' + imageID + ']').removeClass("hide");
        $('.largeImageContainer div[data-largeimage=' + variationID + '] div[data-largeimage=' + imageID + ']').removeClass("hide");
        $('.largeImageContainer div[data-largeimage=' + variationID + '] div[data-largeimage=' + imageID + '] div').removeClass("hide");
        var realsrc = $('.largeImageContainer div[data-largeimage=' + variationID + '] img[data-largeimage=' + imageID + ']').data('realsrc');
        $('.largeImageContainer div[data-largeimage=' + variationID + '] img[data-largeimage=' + imageID + ']').attr('src', realsrc);
        $('.thumbImageContainer div[data-id=' + variationID + '] a').removeClass("active");
        $('.thumbImageContainer div[data-id=' + variationID + '] img[data-largeimage=' + imageID + ']').parent().parent().addClass("active");
        $('.productName').html($('.largeImageContainer div[data-largeimage=' + variationID + ']').data('productname'));
        $('select.variations option').removeAttr('selected');
        $('select.variations option[value=' + variationID + ']').attr('selected', true);
        if($('.productColor').length != 0){
            $('.productColor').html($(this).attr('title'));
        }
        if($('select.variations').length != 0){
            resetVariationSelection(variationID);
        }
    }

    function resetVariationSelection(variationID){
        $('select.variations option').removeAttr('selected');
        $('select.variations option[value=' + variationID + ']').attr('selected', true);
        getStockStatus(variationID)
    }

    function getStockStatus(variationID){
        var $buttonInput = $('.buySpan_AddtoCart input');
        $buttonInput.show();
        $buttonInput.siblings('div').remove();
        if($('select.variations').find('option[value=' + variationID + ']').hasClass('physical')){
            $('.buySpan_AddtoCart').hide();
            $('#load_image').show();
            $.ajax({
                url: "/store/" + inputVariables.storeData.page.siteid + "/" + inputVariables.storeData.page.locale + "/DisplayPage/id.ProductInventoryStatusXmlPage/productID." + variationID,
                cache: false
            }).done(function(xmlData){
                $xml = $(xmlData),
                    $inventoryStatus = $xml.find("inventoryStatus");
                if($inventoryStatus.text() === 'PRODUCT_INVENTORY_OUT_OF_STOCK'){
                    $buttonInput.hide();
                    $buttonInput.siblings('div').remove();
                    $('<div>' + inputVariables.storeData.resources.text.OUT_OF_STOCK + '</div>').insertAfter($buttonInput);
                    $('#load_image').hide();
                    $('.buySpan_AddtoCart').show();
                } else {
                    $('#load_image').hide();
                    $buttonInput.show();
                    $('.buySpan_AddtoCart').show();
                }
            });
        }
    }
});

/*$.widgetize('popularAddons', function(){
 var widgetRef = $(this);
 $('form[name=ProductDetailsForm]').submit(function(){
 var parentProductID,
 buyURL,
 overrideBuyURL = false;
 if($('input[name=productID]', this).length == 0){
 parentProductID = $('select[name=productID]', this).val();
 } else {
 parentProductID = $('input[name=productID]:eq(0)', this).val();
 }

 buyURL = '/store/' + inputVariables.storeData.page.siteid + '/' + inputVariables.storeData.page.locale + '/buy/productID.' + parentProductID,

 $('input[name=popularaddons]:checked', widgetRef).each(function(){
 if($(this).attr('bundleofferid')){
 buyURL += '/OfferID.' + $(this).attr('bundleofferid')
 }
 overrideBuyURL = true;
 });
 if(overrideBuyURL){
 window.location = buyURL;
 } else {
 return true;
 }
 return false;
 });
 });*/

/*Color Selector for Addons*/
$.widgetize('addons_color_switcher', function(){
    var $root = $(this);
    $root.find('.colorSwatch .colors a').each(function(){
        if($(this).hasClass('active')){
            var dataID = $(this).find('div').data('id');
            $root.find('.popularAddons_content .dr_productName input[value=' + dataID + ']').parent().parent().show();
        }
        $root.find('.colorSwatch').show();

        $(this).click(function(){
            var checkFlag = false;
            if($root.find('.popularAddons_content .dr_productName input:checked').length > 0){
                checkFlag = true;
            }
            $root.find('.popularAddons_content .dr_productName input').removeAttr('checked');
            var dataID = $(this).find('div').data('id');
            $root.find('.popularAddons_content').hide();
            $root.find('.colorSwatch .colors a').removeClass('active');
            $(this).addClass('active');
            $root.find('.popularAddons_content .dr_productName input[value=' + dataID + ']').parent().parent().show();
            if(checkFlag){
                $root.find('.popularAddons_content .dr_productName input[value=' + dataID + ']').attr('checked', 'checked');
                $root.find('.popularAddons_content .dr_productName input[value=' + dataID + ']').trigger('click');
                $root.find('.popularAddons_content .dr_productName input[value=' + dataID + ']').attr('checked', 'checked');
            }
            return false;
        });
    });
});
/* Carrier Switcher widget */
$.widgetize('carrierSelector', function(){
    function switchCarriers(index, carrierName){
        $('.carrierSelections a').removeClass("active");
        $('.carrierSelections a:eq(' + index + ')').addClass("active");
        resetSelection(carrierName);
    }

    function resetColorSwitcher(carrierName){
        $('.colorSwitchT', '.color_switcher').each(function(){
            $(this).hide();
            if($(this).hasClass(carrierName)){
                $(this).show();
            }
        });
        $('.colorSwitchT:visible a:eq(0)', '.color_switcher').trigger('click');
    }

    function resetImageSwitcher(variationID){
        $('.thumbImageContainer div[data-id=' + variationID + '] .ms_pd_imageSwitchT:first a').click();
    }

    function resetSelection(carrierName){
        if($('.color_switcher').length != 0){
            resetColorSwitcher(carrierName);
        } else {
            $('select.variations option').removeAttr('selected');
            $('select.variations option.' + carrierName).attr('selected', true);
            resetImageSwitcher($('select.variations option:selected').val());
        }
    }

    function resetCarriers(carrier){
        $('select.variations').hide();
        if(carrier == true){
            $('.carrierContainer').removeClass('hide');
            var carrierName = $('div.carrierSelections a.active').find('img').attr('class').replace('provider ', '');
            $('div.carrierSelections a').each(function(index){
                $(this).click(function(e){
                    e.preventDefault();
                    if(!$(this).hasClass('active')){
                        carrierName = $(this).find('img').attr('class').replace('provider ', '');
                        switchCarriers(index, carrierName);
                    }
                });
            });
            resetSelection(carrierName);
        } else {
            $('.carrierContainer').addClass('hide');
        }
    }
    resetCarriers($('#variationVersionWithContract').is(':checked'));
    $('.variationSelector input[type=radio]').each(function(){
        $(this).click(function(){
            resetCarriers($('#variationVersionWithContract').is(':checked'));
        });
    });
});

/* Mobile variation price display */
$.widgetize('selectedVariationPrice', function(){
    var $variationSelector = $('.variationSelector'),
        $selectedVariationPrice = $('.selectedVariationPrice');

    function resetPriceDisplay(){
        $('.productPrice', $variationSelector).hide();
        showSelectedVariationPrice();
    }

    function showSelectedVariationPrice(){
        var $selectedVar = $('.' + $('input[type=radio]:checked', $variationSelector).attr('id'), $selectedVariationPrice);
        $('.productPrice', $selectedVariationPrice).removeClass('active');
        $selectedVar.addClass("active");
    }
    resetPriceDisplay();
    $('input[type=radio]', $variationSelector).each(function(index){
        $(this).click(function(){

            showSelectedVariationPrice();
        });
    });
});

/*Mobile Activations product submission*/
$.widgetize('triggerOverlayPopUp', function(){
    var overlayPopUpWrap = ( $('#overlayPopUpWrap').length ) ? $('#overlayPopUpWrap') : $('#overlayPopUpWrap', window.parent.document);
    function showOverlayPopUp(objID){
        if(objID != ""){
            var $container = $('#overlayPopInnerContent', overlayPopUpWrap),
                $obj = $('#' + objID);
            if($container.find($obj).length == 0){
                $obj.appendTo($container);
                $(".closeButton,.button.close", overlayPopUpWrap).bind('click', function(e) {
                    e.preventDefault();
                    hideOverlayPopUp();
                    //$triggerOverlayPopUpParam.unbind('change');
                });
            }
            $obj.removeClass('hide').siblings().addClass('hide');
            overlayPopUpWrap.not(":visible").fadeTo("fast",1);

            $(window).scrollTop(0);
            var $overlayPopInner = $('.overlayPopInner').removeAttr('style'),
                pageHeight = $('html').outerHeight();
            overlayHeight = $overlayPopInner.outerHeight() + $overlayPopInner.offset().top,
                overHeight = ($(window).height() < overlayHeight)?true:false,
                initTop = $overlayPopInner.offset().top,
                initScrollTop = $(window).scrollTop(),
                overlayScroll = function(){
                    var cssTop = initTop - ($(window).scrollTop() * (overlayHeight / pageHeight));
                    if(cssTop + overlayHeight > $(window).height() && $(window).scrollTop() > initScrollTop){
                        $overlayPopInner.css('top',cssTop);
                    } else if($(window).scrollTop() <= initScrollTop){
                        $overlayPopInner.css('top',initTop - initScrollTop);
                    }
                };
            if(overHeight){
                $(window).scroll(overlayScroll);
            } else{
                $(window).off('scroll',overlayScroll);
            }
        }
    }
    function hideOverlayPopUp(){
        overlayPopUpWrap.fadeTo("fast",0, function() {
            $(this).hide();
        });
        if($('div[id=load_image]:visible').length){
            $('div[id=load_image]:visible').hide().siblings(':hidden').show();
        }
    }
    function stopBtnSubmitSpinContainer(){
        //$.widgetize('cart');
        if( $('.cart').is( ':visible' ) )
        {
            $.getJSON( '/store/'+ inputVariables.storeData.page.siteid +'/' + inputVariables.storeData.page.locale + '/DisplayPage/id.DRCartSummaryJSONPage/output.json/jsonp=?', function( cartJsonData )
            {
                cookieObj.setPathDomainCookie('_cjd',JSON.stringify(cartJsonData));
                if( cartJsonData.lineItems > 0 )
                {
                    var rtlCharacter='';
                    if( $( 'body' ).hasClass( 'rtlanguage' ) )
                        rtlCharacter='?';
                    $( '.lineItemQuantity' ).text( rtlCharacter + cartJsonData.lineItems );
                }
                updateSignInOutLink(cartJsonData);
            });
        }
        $('input, a.button', '.btnSubmitSpinContainer').parent().show();
        $('#load_image').show();
    }
    function resetOverlayPopUp(obj){
        var $triggerOverlayPopUp = obj,
            $triggerOverlayPopUpParam = ($triggerOverlayPopUp.find('input.trigger').length > 0) ? $triggerOverlayPopUp.find('input.trigger') : $triggerOverlayPopUp.siblings('input.trigger'),
            triggeredByBtn = $triggerOverlayPopUp.hasClass('button'),
            triggeredByBuyBtn = $triggerOverlayPopUp.hasClass('buyBtn_AddtoCart');

        if(triggeredByBtn){
            $triggerOverlayPopUp.click(function(e){
                $('input, a.button', '.btnSubmitSpinContainer').parent().hide();
                $('#load_image').show();
                e.preventDefault();
                var productID = $('input[name=productID]').val(),
                    quantity = $('input[name=quantity]').val(),
                    buyURL = '/store/' + inputVariables.storeData.page.siteid + '/' + inputVariables.storeData.page.locale + '/AddItemToRequisition/productID.' + productID + '/quantity.' + quantity;
                var url = ($triggerOverlayPopUp.attr('href').length > 0) ? $triggerOverlayPopUp.attr('href') : buyURL;

                if(triggeredByBuyBtn && url != ""){
                    $.ajax({
                        type: 'POST',
                        url: url,
                        success: function(){
                            if($('#load_image').is(':visible')){
                                stopBtnSubmitSpinContainer();
                            }
                            showOverlayPopUp($triggerOverlayPopUpParam.val());
                        }
                    });
                }
                else{
                    showOverlayPopUp($triggerOverlayPopUpParam.val());
                }
            });
        }
        else{
            showOverlayPopUp($triggerOverlayPopUpParam.val());
            $triggerOverlayPopUpParam.bind('change', function(){
                showOverlayPopUp($(this).val());
            });
        }
    }
    resetOverlayPopUp($(this));
});

/*Automatically jump to the next field*/
$.widgetize('moveOnMax', function(){
    var $field = $(this);
    $field.each(function(){
        $(this).keyup(function(e){
            //not tab or shift or arrow left or arrow right
            if(!(e.keyCode == 16 || e.keyCode == 9 || e.keyCode == 37 || e.keyCode == 39)){
                var len = $(this).val().length;
                if(len >= $(this).attr('maxlength')){
                    $(this).nextAll(".anchor:eq(0)").focus();
                }
            }
        });
    });
});

/*Automatically make inputs hashed*/
$.widgetize('autoHashed', function(){
    var $field = $(this);
    $field.keyup(function(e){
        var inputType = $(this).attr('type'),
            cloneValue = '';
        //not tab or shift or arrow left or arrow right
        if(!(e.keyCode == 16 || e.keyCode == 9 || e.keyCode == 37 || e.keyCode == 39)){
            if($.trim($(this).val()).length == parseInt($(this).attr('maxlength')) && inputType != 'password'){
                var clone = $(this).clone(true, true).attr('type','password').insertAfter($(this)).focus();
                clone.val(clone.val()+'.').prev().remove();
                cloneValue = clone.val();
            } else if($.trim($(this).val()).length < parseInt($(this).attr('maxlength')) && inputType == 'password'){
                var clone = $(this).clone(true, true).attr('type','text').insertAfter($(this)).focus();
                clone.val(clone.val()+'.').prev().remove();
                cloneValue = clone.val();
            }
            if (cloneValue.length) {
                clone.attr('value', clone.val().substr(0,clone.val().length-1));
            }
        }
    });
    $field.blur(function(){
        if($.trim($(this).val()).length == parseInt($(this).attr('maxlength'))){
            $(this).clone(true, true).attr('type','password').insertAfter($(this)).prev().remove();
        } else{
            $(this).clone(true, true).attr('type','text').insertAfter($(this)).prev().remove();
        }
    });
});

$.widgetize('shoppingCartFrame', function(){
    $(this).load(function(){
        $(this).css('height', $(this).contents().find('#dr_CallCenterToolShoppingCart').outerHeight());
        if($('#shoppingCartFrame').contents().find("body").attr('data-isbbymobileincart')){
            if($('#shoppingCartFrame').contents().find("body").attr('data-isbbymobileincart') === 'false'){
                cookieObj.deleteCookie('activationType');
                if($('select#data-userType option').length === 1){
                    $('select#data-userType').append('<option value="existing">Existing customer</option>');
                }
            }
        }else{
            cookieObj.deleteCookie('activationType');
            if($('select#data-userType option').length === 1){
                $('select#data-userType').append('<option value="existing">Existing customer</option>');
            }
        }
        var activationTypeCookie = cookieObj.getCookie('activationType');
        if(activationTypeCookie){
            $('select#data-userType option[value=existing]').remove();
            $('select#data-userType').trigger('change');
        }
    });
    $('.continueButtonBottom').find('input#checkoutButton').bind('click', function(){
        cookieObj.deleteCookie('isSubsInCart');
        if($('#shoppingCartFrame').contents().find("body").attr('data-issubsincart')==="true"){
            cookieObj.setPathCookie('isSubsInCart','true');
        }else{
            cookieObj.setPathCookie('isSubsInCart','false');
        }
    });
});

$.widgetize('ThankYouPage', function(){
    cookieObj.deleteCookie('activationType');
});

$.widgetize('ThreePgCheckoutConfirmOrderPage', function(){
    $('#whitelabelSmallIframe').load(function(){
        if($('#whitelabelSmallIframe').contents().find("body").hasClass('plan-feature-summary') == false){
            $('#whitelabelSmallIframe').hide();
        }
    });

    $('form[name="CheckoutConfirmOrderForm"] input[type="hidden"][name="optIn"]').remove();
    $('form[name="CheckoutConfirmOrderForm"] input[type="hidden"][name="ORIG_VALUE_optIn"]:first').remove();
    var $OptInContainer = $('.optInContainer'),
        isDefaultOptIn = $OptInContainer.attr('defaultoptin').toUpperCase() === "TRUE" ? true : false;
    if(isDefaultOptIn){
        $('input', $OptInContainer).attr('checked', true);
        $('label', $OptInContainer).html(inputVariables.storeData.resources.text.EMAIL_OPT_OUT_TEXT);
    }
});

/*Tab switcher*/
$.widgetize('tabSwitcher', function(){
    var $obj = $(this),
        tabLen = $('.tabSwitcher').length,
        currentIdx = $obj.parent().attr('id').replace('tab', '');
    $left = $obj.find('li.leftwards').find('a').click(function(e){
        e.preventDefault();
        $('.widget.tabs li:eq(' + (currentIdx - 2) + ')').find('a').click();
    }),
        $right = $obj.find('li.rightwards').find('a').click(function(e){
            e.preventDefault();
            $('.widget.tabs li:eq(' + (currentIdx) + ')').find('a').click();
        });
    if(tabLen < 2){
        $obj.css('visibility', 'hidden');
    } else {
        if(currentIdx == 1){
            $left.addClass('disabled');
        } else if(currentIdx == tabLen){
            $right.addClass('disabled');
        }
    }
    $('.tabs.leftright').show();
});
/*Rows with hover effect and click event*/
$.widgetize('smartRows', function(){
    var $obj = $(this),
        $header = $obj.find('th').parent('tr');
    $rows = $obj.children('tbody').children('tr').not($header),
        $lastRow = $obj.children('tbody').children('tr:last').addClass('lastRow');

    /* Assign click behavior to rows that sets radio button to checked and adds style of 'selected' to the row */
    $rows.each(function(){
        var $row = $(this),
            $radio = $row.find('input.radio'),
            $chkbox = $row.find('input.checkbox').click(function(){
                if($(this).is(':checked')){
                    $(this).removeAttr('checked');
                } else {
                    $(this).attr('checked', 'checked');
                }
            }),
            selected = ($radio.is(':checked') || $chkbox.is(':checked')) ? true : false;
        $row.toggleClass('selected', selected).click(function(){
            if($chkbox.length){
                if($chkbox.is(':checked')){
                    $chkbox.removeAttr('checked');
                } else {
                    $chkbox.attr('checked', 'checked');
                }
                $(this).toggleClass('selected', $chkbox.is(':checked'));
            }
            if($radio.length){
                $(this).addClass('selected').siblings().removeClass('selected').find('input.radio').removeAttr('checked');
                $radio.attr('checked', 'checked');
            }
        });
    });
    /* Pre-select the first radio */
    $('.radioCheck').each(function(){
        var name = $(this).attr('name');
        if(!$('.radioCheck[name=' + name + ']:checked').length){
            $('.radioCheck[name=' + name + ']:first').click();
        }
    });
    /* Assign a 'hover' function to all rows but the first - used for styling */
    $rows.hover(function(){
        $(this).toggleClass('hover');
    });
});

$.widgetize('OfficeComparePage', function(){
    $(".ms_office_expanded_content").hide();
    $(".ms_office_product_header").click(function(){
        $(this).toggleClass("expanded");
        $(this).next(".ms_office_expanded_content").stop(true, true).slideToggle("slow");
    });
});

$.widgetize('childProducts', function(){
    var container = $('.childProducts');
    $.each(container, function(idx, definition){
        var items = $('.grid-unit', this).get(),
            size = 2,
            i = 1;
        while(items.length){
            if(i == 1){
                $(items.splice(0, size + 2)).wrapAll('<div class="grid-row column-4"></div>');
            } else {
                $(items.splice(0, size + 2)).wrapAll('<div class="grid-row column-4"></div>');
            }
            i++;
        }
    });
    /*
     var container = $('.childProducts'),
     items = container.find('.grid-unit').get(),
     size = 2,
     i=1;
     while (items.length){
     if(i==1){
     $(items.splice(0, size+2)).wrapAll('<div class="grid-row column-4 "></div>');
     } else {
     $(items.splice(0, size+2)).wrapAll('<div class="grid-row column-4 "></div>');
     }
     i++;
     }
     */
});

$.widgetize('ThreePgCheckoutCollectPaymentInfoPage', function(){
    var newLocation = $('#dr_ThreePgCheckoutCollectPaymentInfo div.iframe').attr("iframe-src");
    if(newLocation){
        if($('body').hasClass('dr_site_msde') || $('body').hasClass('dr_site_msfr') || $('body').hasClass('dr_site_mseea') || $('body').hasClass('dr_site_mseea1')){
            var isSubsInCart = cookieObj.getCookie('isSubsInCart');
            cookieObj.deleteCookie('isSubsInCart');
            if(isSubsInCart == 'false'){
                newLocation = newLocation.replace("viewmode=4","viewmode=0");
            }
        }
        newLocation = newLocation.replace(/&lmkt.*&pistatus/, '&lmkt=en-us&pistatus');
        window.location = newLocation;
    }
});

$.widgetize('product-offers', function(){

    function renderAnswerTechs(){
        $("<link/>", {
            rel: "stylesheet",
            type: "text/css",
            href: "//dri1.img.digitalrivercontent.net/Storefront/Site/mscommon/cm/multimedia/css/AnswerDesk/AnswerTechs.css"
        }).appendTo("head");
        $(function () {
            var protocol = (("https:" == document.location.protocol) ? "https:" : "http:");
            $.ajax({
                url: protocol + '//dri2.img.digitalrivercontent.net/Storefront/Site/mscommon/cm/multimedia/js/AnswerDesk/AnswerTechs.js', type: 'get', dataType: 'script', cache: true
            });
        });
    }

    var productIDrequest = $('.buy-box').attr('data-basepProductId');
    if(productIDrequest)  {
        $.ajax({
            url:'/store/' + inputVariables.storeData.page.siteid + '/' + inputVariables.storeData.page.locale + '/PDPOfferInfo/productID.' + productIDrequest + '/ThemeID.' + inputVariables.storeData.page.themeID + '/mktp.' + inputVariables.storeData.page.mktp + '/Currency.' + inputVariables.storeData.page.currency + ((inputVariables.storeData.page.Env === 'DESIGN')?'/Env.DESIGN':''),
            datatype:'html',
            cache:true,
            success: function (data) {
                var $data = $(data);

                /** ms_PDP_Agent **/
                var $answerTechs = $('section[pop-ref=ms_PDP_Agent]',$data);
                if($answerTechs.length){
                    $($answerTechs).insertBefore(".nav-wrap");
                    renderAnswerTechs();
                }

                /** ms_PDP_Additional_Info **/
                var $additionalInfo = $('div[pop-ref=ms_PDP_Additional_Info]',$data);
                if($additionalInfo.length){
                    $('.product-additional-info-main').html('');
                    $('.product-additional-info-main').append($('div.product-additional-info',$additionalInfo));
                    $('.product-additional-info.sort2').insertAfter('.product-additional-info.sort1:last-child');
                    $('.product-additional-info-main').removeClass('hide-option');
                    if($('div.product-additional-info-hero',$additionalInfo).length){
                        $('.product-additional-info-main-hero').html('');
                        $('.product-additional-info-main-hero').removeClass('hide-option');
                        $('.product-additional-info-main-hero').append($('div.product-additional-info-hero',$additionalInfo));
                        $('.product-additional-info-hero.sortT2').insertAfter('.product-additional-info-hero.sortT1:last-child');
                    }
                    if($('.product-additional-info-main[default-vid]').length) {
                        $('.product-additional-info-main div.product-additional-info.variation').hide();
                        $(".product-additional-info-main div.product-additional-info.variation[id='ms_PDP_Additional_Info_" + $('.product-additional-info-main').attr('default-vid') + "']").show();
                    }
                }

                /** ms_PDP_Buy_Button_Promo_Text **/
                var $buyButtonPromoText = $('div[pop-ref=ms_PDP_Buy_Button_Promo_Text]',$data);
                if($buyButtonPromoText.length){
                    $('.ms_BuyButtonPromoText').html('');
                    $('.ms_BuyButtonPromoText').append($('span#ms_PDP_Buy_Button_Promo_Text',$buyButtonPromoText));
                    $('.ms_BuyButtonPromoText').removeClass('hide-option');
                }

                /** ms_PDP_CrossSell **/
                var $pdpCrossSell = $('div[pop-ref=ms_PDP_CrossSell]',$data);
                if($pdpCrossSell.length){
                    $('.goesGreatWith .grid-container').html($pdpCrossSell.html());
                    $('.goesGreatWith').removeClass('hide-option');
                } else {
                    $('section.goesGreatWith').hide();
                }

                /** ms_PDP_Related_Products **/
                var $relatedProducts = $('div[pop-ref=ms_PDP_Related_Products]',$data);
                if($relatedProducts.length){
                    var bvParam = { productIds: [], containerPrefix: 'BVRRInlineRating' };
                    $('.pdp-related-products').replaceWith($relatedProducts.html());
                    $('.rating-summary',$relatedProducts).each(function(){
                        bvParam.productIds.push($(this).attr('pid-ref'));
                    });
                    $BV.ui('rr', 'inline_ratings', bvParam);
                }

                /** ms_PDP_Bottom_Banner **/
                var $bottomBanner = $('div[pop-ref=ms_PDP_Bottom_Banner]',$data);
                if($bottomBanner.length){
                    selectedVariationID = $(".variations option:selected").attr("value") || $('.product-colors li.selected a').attr('var-pid') || $('.surface-family li.selected a').attr('var-pid');
                    $('.pd-bottom-banner').html($bottomBanner.html()).removeClass('hide');
                    $('#product-footer').removeClass('product-footer');
                    if($bottomBanner.find('.varOfferDiv').length){
                        $('.pd-bottom-banner').find(".grid-container[data-pid='"+productIDrequest+"']").remove();
                        $('.pd-bottom-banner').find(".grid-container[data-pid='"+selectedVariationID+"']").removeClass("hide-option");
                    }
                }

                /** Social network buttons **/
                /** facebook script **/
                if( inputVariables.storeData.page.siteid === 'msmx'){
                    (function(d, s, id) { var js, fjs = d.getElementsByTagName(s)[0]; if (d.getElementById(id)) return; js = d.createElement(s); js.id = id; js.src = "//connect.facebook.net/" + inputVariables.storeData.page.locale + "/all.js#xfbml=1"; fjs.parentNode.insertBefore(js, fjs); }(document, 'script', 'facebook-jssdk'));
                }
                /** pinit script **/
                $.getScript("//assets.pinterest.com/js/pinit.js");
                /** twitter script **/
                !function (d, s, id) { var js, fjs = d.getElementsByTagName(s)[0], p = /^http:/.test(d.location) ? 'http' : 'https'; if (!d.getElementById(id)) { js = d.createElement(s); js.id = id; js.src = p + '://platform.twitter.com/widgets.js'; fjs.parentNode.insertBefore(js, fjs); } }(document, 'script', 'twitter-wjs');

                var $socialNetworkbuttons = $('.socialNetworks',$data);
                $('.social-media').html($socialNetworkbuttons.html());
            }
        });
    }
});

/*$.widgetize('product-additional-info-main', function(){
 var productIDrequest = $('.buy-box').attr('data-basepProductId');
 if(productIDrequest)  {
 $.ajax({
 url:'/store/' + inputVariables.storeData.page.siteid + '/' + inputVariables.storeData.page.locale + '/Content/pbPage.AdditionalInfo/productID.' + productIDrequest + '/',
 datatype:'html',
 cache:true,
 success: function (data) {
 var $data = $(data);
 if($('div.product-additional-info',$data).length > 0){
 $('.product-additional-info-main').html('');
 $('.product-additional-info-main').append($('div.product-additional-info',$data));
 $('.product-additional-info.sort2').insertAfter('.product-additional-info.sort1:last-child');
 $('.product-additional-info-main').removeClass('hide-option');
 if($('.product-additional-info-main[default-vid]').length) {
 $('.product-additional-info-main div.product-additional-info.variation').hide();
 $(".product-additional-info-main div.product-additional-info.variation[id='ms_PDP_Additional_Info_" + $('.product-additional-info-main').attr('default-vid') + "']").show();
 }
 }
 if($('span#ms_PDP_Buy_Button_Promo_Text',$data).length > 0){
 $('.ms_BuyButtonPromoText').html('');
 $('.ms_BuyButtonPromoText').append($('span#ms_PDP_Buy_Button_Promo_Text',$data));
 $('.ms_BuyButtonPromoText').removeClass('hide-option');
 }
 }
 });
 }
 });*/

if(inputVariables.storeData.page.currentPageName.length > 0){
    if(inputVariables.storeData.page.currentPageName == 'ThreePgCheckoutAddressPaymentInfoPage' || inputVariables.storeData.page.currentPageName == 'CallCenterToolPage'){
        $(document).ready(function(){
            // Append maxlength dynamically
            $('input#shippingPostalCode').attr('maxlength', inputVariables.storeData.resources.text.SiteSetting_PostalCodeMaxLength);
        });
    }
}

/*$.widgetize('goesGreatWith', function(){
 var productIDrequest = $('.buy-box').attr('data-basepProductId'),
 offerHtmldivAll = '';
 if(productIDrequest){
 $.ajax({
 url: '/store/' + inputVariables.storeData.page.siteid + '/' + inputVariables.storeData.page.locale + '/OfferInfoWidget/productID.' + productIDrequest + '/popName.ms_PDP_CrossSell/output.XML/productContent.price+displayName',
 datatype: 'xml',
 cache: true,
 success: function(xml){
 var $data = $(xml),
 offerHtmldiv = '',
 offerHtml = '',
 offerHtmlDivLast = '',
 $offerInstance = $data.find('offerInstance');
 $offerInstance.each(function(index, element){
 var $currentInstance = $(this),
 salesPitchKey1 = $currentInstance.find("salesPitchKey1").text(),
 salesPitchKey2 = $currentInstance.find("salesPitchKey2").text(),
 salesPitchKey3 = $currentInstance.find("salesPitchKey3").text(),
 salesPitchKey4 = $currentInstance.find("salesPitchKey4").text(),
 offerID = $currentInstance.find("offerID").text(),
 miscDiscountKey = $currentInstance.find("miscDiscountKey").text(),
 offerType = $currentInstance.find("offerType").text(),
 offerImage = $currentInstance.find("offerImage").text();
 widgetTitle = $currentInstance.find("widgetTitle").text();
 $currentInstance.find('offerProductInstance').each(function(){
 var $currentProductInstance = $(this),
 $product = $currentProductInstance.find('product'),
 pidref = $product.find('baseProductID').text(),
 productID = $product.attr('productID'),
 hasVariations = $product.find('hasVariations').text(),
 msDisplayFromPricing = $product.find('msDisplayFromPricing').text(),
 displayName = $product.find('displayName').text(),
 msModuleImageMedium2up = $product.find('msModuleImageMedium2up').text(),
 msModuleShortDescription = $product.find('msModuleShortDescription').text(),
 imageName = $product.find('imageName').text(),
 salesPitchKey = $product.find('salesPitchKey').text(),
 msChannelType = $product.find('msChannelType').text(),
 msChannelTypeLabel = '',
 priceHtml = '',
 priceLabel = inputVariables.storeData.resources.text.SALE_COLON,
 $price = $product.find('price'),
 unitPrice = $price.find('unitPrice').text(),
 unitPriceWithDiscount = $price.find('unitPriceWithDiscount').text(),
 youSave = $price.find('youSave').text(),
 discounted = $price.find('discounted').text();
 priceHtml = '<p class="actual-price">';
 if(salesPitchKey != ''){
 msModuleShortDescription = salesPitchKey;
 }
 if(discounted === 'true'){
 if(offerType == 'BuyMGetN'){
 priceLabel = inputVariables.storeData.resources.text.BUNDLE_PRICE_COLON;
 }
 if(salesPitchKey4 != ''){
 priceLabel = salesPitchKey4 + ' ';
 }
 priceHtml = priceHtml + priceLabel;
 }
 priceHtml = priceHtml + unitPriceWithDiscount + '</p>';
 if(discounted === 'true'){
 priceHtml = priceHtml + '<p class="byline">' + inputVariables.storeData.resources.text.REGULAR_PRICE_COLON + ' <span>' + unitPrice + '</span></p>';
 if(salesPitchKey2 != ''){
 //priceHtml = priceHtml + '<p class="box orange">'+salesPitchKey2+'</p>';
 }
 }
 if(imageName != ''){
 msModuleImageMedium2up = imageName;
 }
 offerHtml = '<div class="grid-unit" data-offerid="' + offerID + '"><div class="product-add-on"><img alt="' + displayName + '" src="' + msModuleImageMedium2up + '"><div class="details-box">' + priceHtml + '<h3 class="heading--small">' + displayName + '</h3><p class="description">' + msModuleShortDescription + '</p><div class="add-to-cart"><a href="javascript:void(0)" class="add-to-cart" pid-ref="' + productID + '">' + inputVariables.storeData.resources.text.BTN_ADD_TO_CART + '</a></div></div></div></div>';
 offerHtmldiv = offerHtmldiv + offerHtml;
 });
 offerHtmlDivLast = '<h1 class="heading--large">' + widgetTitle + '</h1><div class="grid-row column-2 row-padding-top">' + offerHtmldiv + '</div>';
 });
 offerHtmldivAll = offerHtmldivAll + offerHtmlDivLast;
 if(offerHtmldivAll != ''){
 $('.goesGreatWith .grid-container').html(offerHtmldivAll);
 $('.goesGreatWith').removeClass('hide-option');
 } else {
 $('section.goesGreatWith').hide();
 }
 }
 });
 }
 });*/


$.widgetize('recommended-products', function(){
    if ($(this).attr('data-type') !== 'html') {
        var productIDrequest = $('.buy-box').attr('data-basepProductId'),
            popname = $(this).attr('pop'),
            content = $(this).attr('data-content'),
            offerHtmldivAllproducts = '',
            urlAjax = '';
        if (productIDrequest) {
            if (popname) urlAjax = '/store/' + inputVariables.storeData.page.siteid + '/' + inputVariables.storeData.page.locale + '/OfferInfoWidget/productID.' + productIDrequest + '/popName.' + popname + '/output.XML/productContent.price+displayName';
        } else {
            if (popname) {
                if (!$('body').hasClass('ProductDetailsPage')) {
                    urlAjax = '/store/' + inputVariables.storeData.page.siteid + '/' + inputVariables.storeData.page.locale + '/OfferInfoWidget/popName.' + popname + '/output.XML/productContent.price+displayName+' + content;
                }
            }
        }
        if (urlAjax != '') {
            $.ajax({
                url: urlAjax,
                datatype: 'xml',
                data: {
                    Env: inputVariables.storeData.page.Env
                },
                cache: true,
                success: function(xml){
                    var $data = $(xml),
                        $offerInstance = $data.find('offerInstance'),
                        bvParam = { productIds: [], containerPrefix: 'BVRRInlineRating' },
                        columnLimit = 4;
                    $offerInstance.each(function(index, element){
                        var $currentInstance = $(this),
                            offerHtmldiv = '',
                            offerProductHtmldiv = '',
                            offerRowHtmldiv = '',
                            offerImageHeader = '',
                            sectionID = '',
                            salesPitchKey1 = $currentInstance.find("salesPitchKey1").text(),
                            salesPitchKey2 = $currentInstance.find("salesPitchKey2").text(),
                            salesPitchKey3 = $currentInstance.find("salesPitchKey3").text(),
                            salesPitchKey4 = $currentInstance.find("salesPitchKey4").text(),
                            extraLinkHref1 = $currentInstance.find("extraLinkHref1").text(),
                            noPrice = ( salesPitchKey3.match( /noPrice/ )? true:false ),
                            noShortDescription = ( salesPitchKey3.match( /noShortDescription/ )? true:false ),
                            isFirstRow = true,
                            offerID = $currentInstance.find("offerID").text(),
                            miscDiscountKey = $currentInstance.find("miscDiscountKey").text(),
                            offerImage = $currentInstance.find("offerImage").text(),
                            widgetTitle = $currentInstance.find("widgetTitle").text(),
                            sortclass = ' sort' + miscDiscountKey,
                            productCount = $currentInstance.find('offerProductInstance').length;
                        salesPitchKey3 = salesPitchKey3.replace( /(noPrice|noShortDescription)/g, '' ); //Clean up the variable since we already set the correct variables.
                        if (widgetTitle == '') {
                            widgetTitle = salesPitchKey1;
                        } else {
                            sectionID = ' id="' + salesPitchKey1 + '"';
                        }
                        if ( offerImage != '' && salesPitchKey3 != '' && salesPitchKey3.match( /\</g ) ) {
                            offerImageHeader = '<div class="grid-row column-2 table"><div class="grid-unit table-cell"><a href="#"><img alt="Product image alt text" src="' + offerImage + '"></a></div><div class="grid-unit table-cell"><div class="product-overview-text">' + salesPitchKey3 + '</div></div></div>';
                            sortclass += ' simple-overview';
                        }
                        $currentInstance.find('offerProductInstance').each(function( productIndex ){
                            var $currentProductInstance = $(this),
                                offerHtml = '',
                                salesPitchKeyExtraInfo1 = $currentProductInstance.find('salesPitchKeyExtraInfo1').text(),
                                salesPitchKeyExtraInfo2 = $currentProductInstance.find('salesPitchKeyExtraInfo2').text(),
                                salesPitchKeyExtraInfo3 = $currentProductInstance.find('salesPitchKeyExtraInfo3').text(),
                                imageName = $currentProductInstance.find('imageName').text(),
                                $product = $currentProductInstance.find('product'),
                                pidref = $product.find('baseProductID').text(),
                                hasVariations = $product.find('hasVariations').text(),
                                msDisplayFromPricing = $product.find('msDisplayFromPricing').text(),
                                displayName = $product.find('displayName').text(),
                                msModuleImageMedium = $product.find('msModuleImageMedium').text(),
                                salesPitchKey = $product.find('salesPitchKey').text(),
                                msModuleShortDescription = $product.find('msModuleShortDescription').text(),
                                msChannelType = $product.find('msChannelType').text(),
                                soldOutText = '',
                                msChannelTypeLabel = '',
                                priceHtml = '',
                                youSaveHtml = '<p class="tag"></p>',
                                $price = $product.find('price'),
                                taxIncludedInPrice = $price.find('taxIncludedInPrice').text(),
                                unitPrice = $price.find('unitPrice').text(),
                                unitPriceWithDiscount = $price.find('unitPriceWithDiscount').text(),
                                youSave = $price.find('youSave').text(),
                                discounted = $price.find('discounted').text(),
                                msUrlProductName = $product.find('msUrlProductName').text(),
                                msPriceOverride = $product.find('msPriceOverride').text(),
                                msPDPButtonOverrideURL = $product.find('msPDPButtonOverrideURL').text(),
                                isPriceHidden = "false",
                                ratingAndReviews = $product.find('PDPRatingsAndReviews').text(),
                                rrOverridePID = $product.find('PDPRatingsAndReviewsOverridePID').text(),
                                productSalesPitch = $product.find('productSalesPitch').text(),
                                SiteSetting_VatDisplayNonPCF = inputVariables.storeData.resources.text.SiteSetting_VatDisplayNonPCF,
                                vatLabel = ' <span class="incVat">' + inputVariables.storeData.resources.text.INCLUDING_VAT + '</span>';
                            if ($product.find('isPriceHidden').length > 0) {
                                isPriceHidden = $product.find('isPriceHidden').text();
                            }
                            if (salesPitchKey4 != '') {
                                msModuleShortDescription = salesPitchKey4;
                            }
                            if (salesPitchKey3 == '') {
                                salesPitchKey3 = salesPitchKey;
                            }
                            if (productSalesPitch != '') {
                                youSaveHtml = '<p class="tag">' + productSalesPitch + '</p>';
                            }

                            if (msChannelType === 'Education') msChannelTypeLabel = '<div class="actual-price academicPriceLabel">' + inputVariables.storeData.resources.text.ACADEMIC_PRICE_LABLE + '</div>';

                            if (!msPriceOverride && !noPrice) {
                                vatLabel = (taxIncludedInPrice == 'true' && SiteSetting_VatDisplayNonPCF != 'no') ? vatLabel : '';
                                if (discounted === 'true') {
                                    priceHtml = '<p class="actual-price">';
                                    if (msDisplayFromPricing === 'true') {
                                        if (inputVariables.storeData.page.siteid == "msjp") {
                                            unitPriceWithDiscount = unitPriceWithDiscount + vatLabel + inputVariables.storeData.resources.text.STARTING_AT_LABEL;
                                        } else if (inputVariables.storeData.page.siteid == "mskr"){
                                            unitPriceWithDiscount = unitPriceWithDiscount + inputVariables.storeData.resources.text.STARTING_AT_LABEL + vatLabel;
                                        } else {
                                            unitPriceWithDiscount = inputVariables.storeData.resources.text.STARTING_AT + unitPriceWithDiscount + vatLabel;
                                        }
                                    } else {
                                        unitPriceWithDiscount = inputVariables.storeData.resources.text.NOW + unitPriceWithDiscount + vatLabel;
                                    }
                                    //priceHtml = '<p class="actual-price">' + inputVariables.storeData.resources.text.NOW + unitPriceWithDiscount + vatLabel + '</p><p class="byline">' + inputVariables.storeData.resources.text.WAS_PRICE_COLON + ' ' + unitPrice + '</p>';
                                    priceHtml = priceHtml + unitPriceWithDiscount + '</p><p class="byline">' + inputVariables.storeData.resources.text.WAS_PRICE_COLON + ' ' + unitPrice + '</p>';
                                    if (productSalesPitch != '') {} else {
                                        youSaveHtml = '<p class="tag">' + ( salesPitchKeyExtraInfo1 == '' ? inputVariables.storeData.resources.text.SAVE + youSave : salesPitchKeyExtraInfo1 ) + '</p>';
                                    }
                                }
                                if (discounted === 'false') {
                                    priceHtml = '<span class="real-price">';
                                    if (msDisplayFromPricing === 'true') {
                                        //priceHtml = priceHtml + inputVariables.storeData.resources.text.STARTING_AT;
                                        if (inputVariables.storeData.page.siteid == "msjp") {
                                            unitPrice = unitPrice + vatLabel + inputVariables.storeData.resources.text.STARTING_AT_LABEL;
                                        } else if (inputVariables.storeData.page.siteid == "mskr"){
                                            unitPrice = unitPrice + inputVariables.storeData.resources.text.STARTING_AT_LABEL + vatLabel;
                                        } else {
                                            unitPrice = inputVariables.storeData.resources.text.STARTING_AT + unitPrice + vatLabel;
                                        }
                                    } else {
                                        unitPrice = unitPrice + vatLabel;
                                    }
                                    //priceHtml = priceHtml + unitPrice + vatLabel + '</p>';
                                    priceHtml = priceHtml + unitPrice + '</span>';
                                }
                            } else {
                                if (msPriceOverride === 'noPrice' || noPrice) {
                                    msPriceOverride = '';
                                } else {
                                    msChannelTypeLabel = '';
                                    msPriceOverride = msPriceOverride;
                                }
                            }
                            if ( salesPitchKeyExtraInfo1 != '' ) {
                                youSaveHtml = '<p class="tag">' + salesPitchKeyExtraInfo1 + '</p>';
                            }
                            if (isPriceHidden === "true") {
                                msChannelTypeLabel = '';
                                priceHtml = '';
                            }
                            if (imageName != '') {
                                msModuleImageMedium = imageName;
                            }
                            if ( salesPitchKeyExtraInfo3 == 'sold-out' ) {
                                soldOutText = '<p class="sold-out-label">' + inputVariables.storeData.resources.text.SOLD_OUT + '</p>';
                            }
                            offerHtml = '<a aria-label="' + displayName + '" class="product col-sm-6' + ( salesPitchKeyExtraInfo3 != '' ? ' ' + salesPitchKeyExtraInfo3 : '' ) + '" pid-ref="' + pidref + '" href="/store/' + inputVariables.storeData.page.siteid + '/' + inputVariables.storeData.page.locale + '/pdp' + msUrlProductName + '/productID.' + pidref + '"><div class="row">' + soldOutText + '<div class="image-container col-xs-4 col-sm-12"><div data-alt="' + displayName + '" data-picture=""><div data-src="' + msModuleImageMedium + '"></div><div data-viewport="tablet" data-src="' + msModuleImageMedium + '"></div><noscript><img alt="' + displayName + '" src="' + msModuleImageMedium + '"/></noscript></div></div><div class="content-container col-xs-8 col-sm-12">' + youSaveHtml + '<h3 class="heading--small">' + displayName + '</h3>' + ( noShortDescription? '' : '<div class="description">' + msModuleShortDescription + '</div>' ) + ( offerImageHeader != '' || salesPitchKey3 == 'noPrice' ? '' : '<p class="byline">' + salesPitchKey3 + '</p>' ) + msChannelTypeLabel + priceHtml + msPriceOverride + ( salesPitchKeyExtraInfo2 == '' ? '' : '<p class="byline">' + salesPitchKeyExtraInfo2 + '</p>' ) + ( ratingAndReviews == 'true' ? '<div id="BVRRInlineRating-' + ( rrOverridePID == '' ? pidref : rrOverridePID ) + '" class="rating-summary"></div>' : '' ) + '</div>' + '</div></a>';
                            if ( ( productIndex + 1 ) % columnLimit == 3 ) {
                                offerProductHtmldiv += '</div><div class="product-row col-md-6">';
                            }
                            offerProductHtmldiv += offerHtml;
                            if ( ( productIndex + 1 ) % columnLimit == 0 || productCount === ( productIndex + 1 ) ) {
                                offerRowHtmldiv += '<div class="product-row col-md-6">' + offerProductHtmldiv + '</div>';
                                offerProductHtmldiv = '';
                                isFirstRow = false;
                            }
                            bvParam.productIds.push(rrOverridePID == '' ? pidref : rrOverridePID);
                        });
                        offerHtmldiv = '<section' + sectionID + ' class="' + sortclass + ' container-fluid category-products-wrapper" data-offerid="' + offerID + '">' + ( widgetTitle != '' ? '<h2 class="products-heading">' + widgetTitle + salesPitchKey2 + '</h2>' : '' ) + '<div class="category-products"><div class="row">' + offerImageHeader + offerRowHtmldiv + '</div>' + ( extraLinkHref1 != '' ? '<div class="grid-container">' + extraLinkHref1 + '</div>' : '' ) + '</div></section>';
                        offerHtmldivAllproducts = offerHtmldivAllproducts + offerHtmldiv;
                    });
                    if (offerHtmldivAllproducts != '') {
                        $('.recommended-products').replaceWith(offerHtmldivAllproducts);
                        $('.sort1.category-products-wrapper .image-container img').load(function(){$('section.sort1.category-products-wrapper .image-container').equalHeights();});
                        $('.sort2.category-products-wrapper').insertAfter('.sort1.category-products-wrapper').find('.image-container img').load(function(){$('section.sort2.category-products-wrapper .image-container').equalHeights();});
                        $('.sort3.category-products-wrapper').insertAfter('.sort2.category-products-wrapper').find('.image-container img').load(function(){$('section.sort3.category-products-wrapper .image-container').equalHeights();});
                        $('.sort4.category-products-wrapper').insertAfter('.sort3.category-products-wrapper').find('.image-container img').load(function(){$('section.sort4.category-products-wrapper .image-container').equalHeights();});
                        $('.sort5.category-products-wrapper').insertAfter('.sort4.category-products-wrapper').find('.image-container img').load(function(){$('section.sort5.category-products-wrapper .image-container').equalHeights();});
                        $('.sort6.category-products-wrapper').insertAfter('.sort5.category-products-wrapper').find('.image-container img').load(function(){$('section.sort6.category-products-wrapper .image-container').equalHeights();});
                        $('.sort7.category-products-wrapper').insertAfter('.sort6.category-products-wrapper').find('.image-container img').load(function(){$('section.sort7.category-products-wrapper .image-container').equalHeights();});
                        $('.sort8.category-products-wrapper').insertAfter('.sort7.category-products-wrapper').find('.image-container img').load(function(){$('section.sort8.category-products-wrapper .image-container').equalHeights();});
                        $('.sort9.category-products-wrapper').insertAfter('.sort8.category-products-wrapper').find('.image-container img').load(function(){$('section.sort9.category-products-wrapper .image-container').equalHeights();});
                        $('.sort10.category-products-wrapper').insertAfter('.sort9.category-products-wrapper').find('.image-container img').load(function(){$('section.sort10.category-products-wrapper .image-container').equalHeights();});
                        $('.recommended-products').removeClass('hide-option');
                    }
                    if (window.picturefill) {
                        window.picturefill.init();
                    }
                    $BV.ui('rr', 'inline_ratings', bvParam);
                }
            });
        }
    }
});

$.widgetize('recommended-products-legacy', function(){
    if ($(this).attr('data-type') !== 'html') {
        var productIDrequest = $('.buy-box').attr('data-basepProductId'),
            popname = $(this).attr('pop'),
            content = $(this).attr('data-content'),
            offerHtmldivAllproducts = '',
            urlAjax = '';
        if (productIDrequest) {
            if(popname) urlAjax = '/store/' + inputVariables.storeData.page.siteid + '/' + inputVariables.storeData.page.locale + '/OfferInfoWidget/productID.' + productIDrequest + '/popName.' + popname + '/output.XML/productContent.price+displayName';
        } else {
            if (popname) {
                if (!$('body').hasClass('ProductDetailsPage')) {
                    urlAjax = '/store/' + inputVariables.storeData.page.siteid + '/' + inputVariables.storeData.page.locale + '/OfferInfoWidget/popName.' + popname + '/output.XML/productContent.price+displayName+' + content;
                }
            }
        }
        if (urlAjax != '') {
            $.ajax({
                url: urlAjax,
                datatype: 'xml',
                data: {
                    Env: inputVariables.storeData.page.Env
                },
                cache: true,
                success: function(xml){
                    var $data = $(xml),
                        $offerInstance = $data.find('offerInstance'),
                        bvParam = { productIds: [], containerPrefix: 'BVRRInlineRating' },
                        columnLimit = 4;
                    $offerInstance.each(function(index, element){
                        var $currentInstance = $(this),
                            offerHtmldiv = '',
                            offerProductHtmldiv = '',
                            offerRowHtmldiv = '',
                            offerImageHeader = '',
                            sectionID = '',
                            salesPitchKey1 = $currentInstance.find("salesPitchKey1").text(),
                            salesPitchKey2 = $currentInstance.find("salesPitchKey2").text(),
                            salesPitchKey3 = $currentInstance.find("salesPitchKey3").text(),
                            salesPitchKey4 = $currentInstance.find("salesPitchKey4").text(),
                            extraLinkHref1 = $currentInstance.find("extraLinkHref1").text(),
                            noPrice = ( salesPitchKey3.match( /noPrice/ )? true:false ),
                            noShortDescription = ( salesPitchKey3.match( /noShortDescription/ )? true:false ),
                            isFirstRow = true,
                            offerID = $currentInstance.find("offerID").text(),
                            miscDiscountKey = $currentInstance.find("miscDiscountKey").text(),
                            offerImage = $currentInstance.find("offerImage").text(),
                            widgetTitle = $currentInstance.find("widgetTitle").text(),
                            sortclass = ' sort' + miscDiscountKey,
                            productCount = $currentInstance.find('offerProductInstance').length;
                        salesPitchKey3 = salesPitchKey3.replace( /(noPrice|noShortDescription)/g, '' ); //Clean up the variable since we already set the correct variables.
                        if (widgetTitle == '') {
                            widgetTitle = salesPitchKey1;
                        } else {
                            sectionID = ' id="' + salesPitchKey1 + '"';
                        }
                        if ( offerImage != '' && salesPitchKey3 != '' && salesPitchKey3.match( /\</g ) ) {
                            offerImageHeader = '<div class="grid-row column-2 table"><div class="grid-unit table-cell"><a href="#"><img alt="Product image alt text" src="' + offerImage + '"></a></div><div class="grid-unit table-cell"><div class="product-overview-text">' + salesPitchKey3 + '</div></div></div>';
                            sortclass += ' simple-overview';
                        }
                        $currentInstance.find('offerProductInstance').each(function( productIndex ){
                            var $currentProductInstance = $(this),
                                offerHtml = '',
                                salesPitchKeyExtraInfo1 = $currentProductInstance.find('salesPitchKeyExtraInfo1').text(),
                                salesPitchKeyExtraInfo2 = $currentProductInstance.find('salesPitchKeyExtraInfo2').text(),
                                salesPitchKeyExtraInfo3 = $currentProductInstance.find('salesPitchKeyExtraInfo3').text(),
                                imageName = $currentProductInstance.find('imageName').text(),
                                $product = $currentProductInstance.find('product'),
                                pidref = $product.find('baseProductID').text(),
                                hasVariations = $product.find('hasVariations').text(),
                                msDisplayFromPricing = $product.find('msDisplayFromPricing').text(),
                                displayName = $product.find('displayName').text(),
                                msModuleImageMedium = $product.find('msModuleImageMedium').text(),
                                salesPitchKey = $product.find('salesPitchKey').text(),
                                msModuleShortDescription = $product.find('msModuleShortDescription').text(),
                                msChannelType = $product.find('msChannelType').text(),
                                soldOutText = '',
                                msChannelTypeLabel = '',
                                priceHtml = '',
                                youSaveHtml = '<p class="orange-text-color savings-message"></p>',
                                $price = $product.find('price'),
                                taxIncludedInPrice = $price.find('taxIncludedInPrice').text(),
                                unitPrice = $price.find('unitPrice').text(),
                                unitPriceWithDiscount = $price.find('unitPriceWithDiscount').text(),
                                youSave = $price.find('youSave').text(),
                                discounted = $price.find('discounted').text(),
                                msUrlProductName = $product.find('msUrlProductName').text(),
                                msPriceOverride = $product.find('msPriceOverride').text(),
                                msPDPButtonOverrideURL = $product.find('msPDPButtonOverrideURL').text(),
                                isPriceHidden = "false",
                                ratingAndReviews = $product.find('PDPRatingsAndReviews').text(),
                                rrOverridePID = $product.find('PDPRatingsAndReviewsOverridePID').text(),
                                productSalesPitch = $product.find('productSalesPitch').text(),
                                SiteSetting_VatDisplayNonPCF = inputVariables.storeData.resources.text.SiteSetting_VatDisplayNonPCF,
                                vatLabel = ' <span class="incVat">' + inputVariables.storeData.resources.text.INCLUDING_VAT + '</span>';
                            if ($product.find('isPriceHidden').length > 0) {
                                isPriceHidden = $product.find('isPriceHidden').text();
                            }
                            if (salesPitchKey4 != '') {
                                msModuleShortDescription = salesPitchKey4;
                            }
                            if (salesPitchKey3 == '') {
                                salesPitchKey3 = salesPitchKey;
                            }
                            if (productSalesPitch != '') {
                                youSaveHtml = '<p class="orange-text-color savings-message">' + productSalesPitch + '</p>';
                            }

                            if (msChannelType === 'Education') msChannelTypeLabel = '<div class="actual-price academicPriceLabel">' + inputVariables.storeData.resources.text.ACADEMIC_PRICE_LABLE + '</div>';

                            if (!msPriceOverride && !noPrice) {
                                vatLabel = (taxIncludedInPrice == 'true' && SiteSetting_VatDisplayNonPCF != 'no') ? vatLabel : '';
                                if (discounted === 'true') {
                                    priceHtml = '<p class="actual-price">';
                                    if (msDisplayFromPricing === 'true') {
                                        if (inputVariables.storeData.page.siteid == "msjp") {
                                            unitPriceWithDiscount = unitPriceWithDiscount + vatLabel + inputVariables.storeData.resources.text.STARTING_AT_LABEL;
                                        } else if (inputVariables.storeData.page.siteid == "mskr"){
                                            unitPriceWithDiscount = unitPriceWithDiscount + inputVariables.storeData.resources.text.STARTING_AT_LABEL + vatLabel;
                                        } else {
                                            unitPriceWithDiscount = inputVariables.storeData.resources.text.STARTING_AT + unitPriceWithDiscount + vatLabel;
                                        }
                                    } else {
                                        unitPriceWithDiscount = inputVariables.storeData.resources.text.NOW + unitPriceWithDiscount + vatLabel;
                                    }
                                    //priceHtml = '<p class="actual-price">' + inputVariables.storeData.resources.text.NOW + unitPriceWithDiscount + vatLabel + '</p><p class="byline">' + inputVariables.storeData.resources.text.WAS_PRICE_COLON + ' ' + unitPrice + '</p>';
                                    priceHtml = priceHtml + unitPriceWithDiscount + '</p><p class="byline">' + inputVariables.storeData.resources.text.WAS_PRICE_COLON + ' ' + unitPrice + '</p>';
                                    if (productSalesPitch != '') {} else {
                                        youSaveHtml = '<p class="orange-text-color savings-message">' + ( salesPitchKeyExtraInfo1 == '' ? inputVariables.storeData.resources.text.SAVE + youSave : salesPitchKeyExtraInfo1 ) + '</p>';
                                    }
                                }
                                if (discounted === 'false') {
                                    priceHtml = '<p class="actual-price">';
                                    if (msDisplayFromPricing === 'true') {
                                        //priceHtml = priceHtml + inputVariables.storeData.resources.text.STARTING_AT;
                                        if (inputVariables.storeData.page.siteid == "msjp") {
                                            unitPrice = unitPrice + vatLabel + inputVariables.storeData.resources.text.STARTING_AT_LABEL;
                                        } else if (inputVariables.storeData.page.siteid == "mskr"){
                                            unitPrice = unitPrice + inputVariables.storeData.resources.text.STARTING_AT_LABEL + vatLabel;
                                        } else {
                                            unitPrice = inputVariables.storeData.resources.text.STARTING_AT + unitPrice + vatLabel;
                                        }
                                    } else {
                                        unitPrice = unitPrice + vatLabel;
                                    }
                                    //priceHtml = priceHtml + unitPrice + vatLabel + '</p>';
                                    priceHtml = priceHtml + unitPrice + '</p>';
                                }
                            } else {
                                if (msPriceOverride === 'noPrice' || noPrice) {
                                    msPriceOverride = '';
                                } else {
                                    msChannelTypeLabel = '';
                                    msPriceOverride = msPriceOverride;
                                }
                            }
                            if (salesPitchKeyExtraInfo1 != '') {
                                youSaveHtml = '<p class="orange-text-color savings-message">' + salesPitchKeyExtraInfo1 + '</p>';
                            }

                            if (isPriceHidden === "true") {
                                msChannelTypeLabel = '';
                                priceHtml = '';
                            }
                            if (imageName != '') {
                                msModuleImageMedium = imageName;
                            }

                            if (salesPitchKeyExtraInfo3 == 'sold-out') {
                                soldOutText = '<p class="sold-out-label">' + inputVariables.storeData.resources.text.SOLD_OUT + '</p>';
                            }

                            offerHtml = '<div class="grid-unit"><a aria-label="' + displayName + '" class="product-control' + ( salesPitchKeyExtraInfo3 != ''? ' ' + salesPitchKeyExtraInfo3 : '' ) + '" pid-ref="' + pidref + '" href="/store/' + inputVariables.storeData.page.siteid + '/' + inputVariables.storeData.page.locale + '/pdp' + msUrlProductName + '/productID.' + pidref + '">' + soldOutText + '<div class="product-image-container"><img alt="' + displayName + '" src="' + msModuleImageMedium + '"/></div><div class="product-text-container">' + youSaveHtml + '<h3 class="heading--small">' + displayName + '</h3>' + ( ratingAndReviews == 'true' ? '<div id="BVRRInlineRating-' + ( rrOverridePID == '' ? pidref : rrOverridePID ) + '" class="bv-inline-rating"></div>' : '' ) + ( noShortDescription? '' : '<div class="description">' + msModuleShortDescription + '</div>' ) + ( offerImageHeader != '' || salesPitchKey3 == 'noPrice' ? '' : '<p class="byline tagline">' + salesPitchKey3 + '</p>' ) + msChannelTypeLabel + priceHtml + ( salesPitchKeyExtraInfo2 == '' ? '' : '<p class="byline tagline">' + salesPitchKeyExtraInfo2 + '</p>' ) + '</div>' + msPriceOverride + '</a></div>';
                            offerProductHtmldiv += offerHtml;
                            if ( ( productIndex + 1 ) % columnLimit == 0 || productCount === ( productIndex + 1 ) ) {
                                offerRowHtmldiv += '<div class="grid-row column-4' + ( !isFirstRow && productCount > 4? ' row-padded-top' : '' ) + '">' + offerProductHtmldiv + '</div>';
                                offerProductHtmldiv = '';
                                isFirstRow = false;
                            }
                            bvParam.productIds.push(rrOverridePID == '' ? pidref : rrOverridePID);
                        });
                        offerHtmldiv = '<section' + sectionID + ' class="' + sortclass + ' row-padded-top product-row"><div class="grid-container" data-offerid="' + offerID + '">' + ( widgetTitle != '' ? '<h1 class="heading--large">' + widgetTitle + salesPitchKey2 + '</h1>' : '' ) + offerImageHeader + offerRowHtmldiv + '</div>' + ( extraLinkHref1 != '' ? '<div class="grid-container">' + extraLinkHref1 + '</div>' : '' ) + '</section>';
                        offerHtmldivAllproducts = offerHtmldivAllproducts + offerHtmldiv;
                    });
                    if (offerHtmldivAllproducts != '') {
                        $('.recommended-products-legacy').replaceWith(offerHtmldivAllproducts);
                        /* Legacy */
                        $('.sort1.product-row .product-image-container img').load(function(){$('section.sort1.product-row .product-image-container').equalHeights();});
                        $('.sort2.product-row').insertAfter('.sort1.product-row').find('.product-image-container img').load(function(){$('section.sort2.product-row .product-image-container').equalHeights();});
                        $('.sort3.product-row').insertAfter('.sort2.product-row').find('.product-image-container img').load(function(){$('section.sort3.product-row .product-image-container').equalHeights();});
                        $('.sort4.product-row').insertAfter('.sort3.product-row').find('.product-image-container img').load(function(){$('section.sort4.product-row .product-image-container').equalHeights();});
                        $('.sort5.product-row').insertAfter('.sort4.product-row').find('.product-image-container img').load(function(){$('section.sort5.product-row .product-image-container').equalHeights();});
                        $('.sort6.product-row').insertAfter('.sort5.product-row').find('.product-image-container img').load(function(){$('section.sort6.product-row .product-image-container').equalHeights();});
                        $('.sort7.product-row').insertAfter('.sort6.product-row').find('.product-image-container img').load(function(){$('section.sort7.product-row .product-image-container').equalHeights();});
                        $('.sort8.product-row').insertAfter('.sort7.product-row').find('.product-image-container img').load(function(){$('section.sort8.product-row .product-image-container').equalHeights();});
                        $('.sort9.product-row').insertAfter('.sort8.product-row').find('.product-image-container img').load(function(){$('section.sort9.product-row .product-image-container').equalHeights();});
                        $('.sort10.product-row').insertAfter('.sort9.product-row').find('.product-image-container img').load(function(){$('section.sort10.product-row .product-image-container').equalHeights();});
                        $('.recommended-products-legacy').removeClass('hide-option');
                    }
                    $BV.ui('rr', 'inline_ratings', bvParam);
                }
            });
        }
    }
});

/* PDP Bottom Banner
 $.widgetize('pd-bottom-banner', function(){
 var $banner = $(this),
 productIDrequest = $('.buy-box').attr('data-basepProductId'),
 selectedVariationID = $(".variations option:selected").attr("value") || $('.product-colors li.selected a').attr('var-pid') || $('.surface-family li.selected a').attr('var-pid');
 $.ajax({
 url:'/store/' + inputVariables.storeData.page.siteid + '/' + inputVariables.storeData.page.locale + '/Content/pbPage.PdpBottomBanner/productID.' + productIDrequest + '/',
 datatype:'html',
 cache:true,
 success: function (data) {
 var $data = $(data);
 if($data.find('.grid-container').length){
 $banner.html($('.grid-container',$data)).removeClass('hide');
 $('#product-footer').removeClass('product-footer');
 }
 if($data.find('.varOfferDiv').length){
 $banner.find(".grid-container[data-pid='"+productIDrequest+"']").remove();
 $banner.find(".grid-container[data-pid='"+selectedVariationID+"']").removeClass("hide-option");
 }
 }
 });
 });*/


/* Shopping Cart Candy-rack */
$.widgetize('cart-candy-rack', function(){
    var $parent = $(this),
        liProductIDCollect = $(this).text(),
        urlAjax = '/store/' + inputVariables.storeData.page.siteid + '/' + inputVariables.storeData.page.locale + '/OfferInfoWidget/popName.ms_Banner_Shopping_Cart_Local/output.XML/productContent.price+displayName+shortDescription+image+thumbnail';
    $.ajax({
        url: urlAjax,
        datatype: 'xml',
        data: {
            Env: inputVariables.storeData.page.Env
        },
        cache: true,
        success: function(xml){
            var $data = $(xml),
                $offerInstance = $data.find('offerInstance'),
                offerHtmldiv = '',
                title = true;
            $offerInstance.each(function(index, element){
                var $currentInstance = $(this),
                    offerHtml = '',
                    salesPitchKey1 = $currentInstance.find("salesPitchKey1").text(),
                    salesPitchKey2 = $currentInstance.find("salesPitchKey2").text(),
                    offerID = $currentInstance.find("offerID").text(),
                    widgetTitle = $currentInstance.find("widgetTitle").text(),
                    widgetTitle = $currentInstance.find("widgetTitle").text(),
                    productCount = 1;
                if(salesPitchKey2.trim() == '2up'){
                    if(widgetTitle == ''){
                        widgetTitle = salesPitchKey1;
                    }
                    if(widgetTitle != '' && title){
                        offerHtmldiv += '<div class="recommend-heading"><h1>' + widgetTitle + '</h1></div>';
                    }
                    title = false;
                    $currentInstance.find('offerProductInstance').each(function( productIndex ){
                        var $currentProductInstance = $(this),
                            offerProduct = '',
                            imageName = $currentProductInstance.find('imageName').text(),
                            $product = $currentProductInstance.find('product'),
                            $price = $product.find('price'),
                            pidref = $product.find('baseProductID').text(),
                            hasVariations = $product.find('hasVariations').text(),
                            isPriceHidden = "false",
                            msChannelType = $product.find('msChannelType').text(),
                            discounted = $price.find('discounted').text(),
                            msDisplayFromPricing = $product.find('msDisplayFromPricing').text(),
                            unitPrice = $price.find('unitPrice').text(),
                            unitPriceWithDiscount = $price.find('unitPriceWithDiscount').text(),
                            displayPriceWithVat = inputVariables.storeData.page.displayPriceWithVat,
                            SiteSetting_VatDisplay = inputVariables.storeData.resources.text.SiteSetting_VatDisplay,
                            vatLabel = '',
                            displayName = $product.find('displayName').text(),
                            msCartCandyRackImage = $product.find('msCartCandyRackImage').text();
                        if($product.find('isPriceHidden').length > 0){
                            isPriceHidden = $product.find('isPriceHidden').text();
                        }
                        if(imageName != ''){
                            msCartCandyRackImage = imageName;
                        }
                        offerProduct += '<img src="' + msCartCandyRackImage + '" alt="' + displayName + '"/><p>' + displayName + '</p>';
                        if(!isPriceHidden) {
                            if(msChannelType === 'Education') {
                                offerProduct += '<div class="academicPriceLabel"><br />' + inputVariables.storeData.resources.text.ACADEMIC_PRICE_LABLE + '</div>';
                            }
                            if(discounted === 'true'){
                                offerProduct += '<p class="item-price productPriceDiscounted">';
                            } else {
                                offerProduct += '<p class="item-price">';
                            }
                            if(SiteSetting_VatDisplay === 'yes' && displayPriceWithVat != 'false'){
                                vatLabel = '&#160;' + inputVariables.storeData.resources.text.INCLUDING_VAT;
                            }
                            if(msDisplayFromPricing === 'true'){
                                if(inputVariables.storeData.page.siteid == "msjp"){
                                    unitPriceWithDiscount = unitPriceWithDiscount + vatLabel + inputVariables.storeData.resources.text.STARTING_AT_LABEL;
                                } else if (inputVariables.storeData.page.siteid == "mskr"){
                                    unitPriceWithDiscount = unitPriceWithDiscount + inputVariables.storeData.resources.text.STARTING_AT_LABEL + vatLabel;
                                } else{
                                    unitPriceWithDiscount = inputVariables.storeData.resources.text.STARTING_AT + unitPriceWithDiscount + vatLabel;
                                }
                            } else{
                                unitPriceWithDiscount = unitPriceWithDiscount + vatLabel;
                            }
                            offerProduct += unitPriceWithDiscount;
                            offerProduct += '</p><p class="item-price">';
                            if(discounted === 'true'){
                                offerProduct += '<span class="regular-price">' + inputVariables.storeData.resources.text.WAS_PRICE_COLON + '&#160;<del>' + unitPrice + '</del></span>';
                            }
                            offerProduct += '</p>';
                        }
                        offerProduct += '<a href="/store/' + inputVariables.storeData.page.siteid + '/' + inputVariables.storeData.page.locale + '/buy/productID.' + pidref + '">' + inputVariables.storeData.resources.text.BTN_ADD_TO_CART + '</a>';
                        offerProduct = '<li class="item-' + productCount + '">' + offerProduct + '</li>';
                        offerHtml += offerProduct;
                        productCount++;
                    });
                    offerHtmldiv += '<ul class="candy-rack">' + offerHtml + '</ul>';
                }
            });
            if(offerHtmldiv != ''){
                $parent.replaceWith(offerHtmldiv);
            }
        }
    });
});

$.widgetize('PurchasePlanLandingPage', function(){
    if(_TM.pstor_mktid){
        cookieObj.setPathCookieWithExpiration('pstor_info',_TM.pstor_mktid+','+_TM.pstor_name,365);
    }
});

$.widgetize('registrationForm-form-error', function(){
    $.magnificPopup.open({
        items: {
            src: '.registrationForm-form-error',
            fixedContentPos: 'auto',
            type: 'inline'
        }
    });
});

/**
 $.widgetize('footer-offer', function(){
  var popname = $(this).attr('pop'),
    offerHtmldivAllproducts = '',
    urlAjax = '',
    offerExtraLinks = ['extraLinks0', 'extraLinks1', 'extraLinks2', 'extraLinks3', 'extraLinks4', 'extraLinks5', 'extraLinks6', 'extraLinks7', 'extraLinks8', 'extraLinks9', 'extraLinks10'],
    linkList = [];
  if(popname){
    urlAjax = '/store/' + inputVariables.storeData.page.siteid + '/' + inputVariables.storeData.page.locale + '/OfferInfoWidget/popName.' + popname + '/output.XML/productContent.price+displayName';
  }
  if(urlAjax != ''){
    $.ajax({
      url: urlAjax,
      datatype: 'xml',
      cache: true,
      success: function(xml){
        var $data = $(xml),
          $offerInstance = $data.find('offerInstance');
        $offerInstance.each(function(index, element){
          var $currentInstance = $(this),
            offerHtmldiv = '',
            salesPitchKey1 = $currentInstance.find("salesPitchKey1").text(),
            salesPitchKey2 = $currentInstance.find("salesPitchKey2").text(),
            salesPitchKey3 = $currentInstance.find("salesPitchKey3").text(),
            salesPitchKey4 = $currentInstance.find("salesPitchKey4").text(),
            offerID = $currentInstance.find("offerID").text(),
            miscDiscountKey = $currentInstance.find("miscDiscountKey").text(),
            offerImage = $currentInstance.find("offerImage").text(),
            widgetTitle = $currentInstance.find("widgetTitle").text();

          if(widgetTitle == ''){
            widgetTitle = salesPitchKey1;
          }

          $.each(offerExtraLinks, function(i, v){
            $currentInstance.find(v).each(function(i){
              linkList.push('<li><a href="' + $(this).find('href').text() + '" aria-label="Popular resources ' + $(this).find('text').text() + '"> ' + $(this).find('text').text() + '</a></li>');
            });
          });
          offerHtmldiv = '<div class="grid-unit footer-offer" data-offerid="' + offerID + '"><div class="list-of-links list-of-links-sm footer-menu-with-arrow"><h3 class="heading no-underline seo-footer"><a href="javascript:void()">' + widgetTitle + '<span class="right-arrow-container"><span class="right-arrow"><!--arrow--></span></span></a></h3><ul>' + linkList.join('') + '</ul></div></div>';
          offerHtmldivAllproducts = offerHtmldivAllproducts + offerHtmldiv;
        });
        if(offerHtmldivAllproducts != ''){
          $('.footer-offer').replaceWith(offerHtmldivAllproducts);
          $('.footer-offer').removeClass('hide-option');
        }
        $(".rwd .footer-links .list-of-links h3.seo-footer").click(function(){
          $(this).parent().find("ul").slideToggle("fast");
          $(this).toggleClass('active');
        });
      }
    });
  }
});
 **/

$.widgetize('scrollDisplay', function(){
    var lastScrollTop = 0;
    $(window).on('scroll',loadContent);

    function loadContent(){
        var $scrollTarget = $('.scrollDisplay'),
            filterExists = $('.facet-search').length ? true : false,
            callingPage = '',
            categoryID = $scrollTarget.attr('category-id'),
            totalSize = $scrollTarget.attr('total-size'),
            size = parseInt($scrollTarget.attr('size')),
            startIndex = parseInt($scrollTarget.attr('startIndex')),
            callingSize = parseInt($scrollTarget.attr('calling-size')),
            nextStartIndex = parseInt(startIndex+1),
            nextResultSize = ((startIndex+size) < totalSize)?(startIndex+size):totalSize,
            action = $scrollTarget.attr('action'),
            sortData = $('.sort-by-container .dr_catSortOptions option:selected').val(),
            windowHeight = $(window).height(),
            windowScrollTop = $(window).scrollTop(),
            containerTop = $scrollTarget.offset().top,
            containerHeight = $scrollTarget.height();
        if(callingSize < totalSize && !$scrollTarget.hasClass('off')){
            if(((windowHeight+windowScrollTop) > (containerTop+containerHeight)) && windowScrollTop > lastScrollTop){
                if(sortData.match(/listPrice/g) && $('body').hasClass('CategoryProductListPage')){
                    $scrollTarget.append('<div class="grid-row row-padded loader" style="text-align:center;">'+inputVariables.storeData.resources.text.LOADING+'... '+nextStartIndex+'-'+nextResultSize+' '+inputVariables.storeData.resources.text.OF_TOTAL+' '+totalSize+' '+inputVariables.storeData.resources.text.RESULTS+'</div>');
                    $scrollTarget.addClass('off');
                    $('.fromTo').html('1-'+nextResultSize);
                    setTimeout(function(){
                            $scrollTarget.find('.loader').fadeOut();
                            var $priceSortingRevealSelector,
                                pageAdded = false;
                            if(filterExists){
                                $priceSortingRevealSelector = $('#productListContainer > .row:hidden:lt(5)');
                            } else {
                                $priceSortingRevealSelector = $('#productListContainer > .row:hidden:lt(4)');
                            }
                            $priceSortingRevealSelector.fadeIn('slow',function(){
                                if(!pageAdded){
                                    $scrollTarget.find('.loader').remove();
                                    if((startIndex+size) < totalSize){
                                        startIndex += size;
                                    }
                                    callingSize += size;
                                    $scrollTarget.attr('startIndex',startIndex);
                                    $scrollTarget.attr('calling-size',callingSize);
                                    if($('.compareContainer').length){
                                        initCompare();
                                    }
                                    $scrollTarget.removeClass('off');
                                    pageAdded = true;
                                }
                            }).removeAttr('style');
                        }
                        ,1000);
                } else {
                    var keywordsSting = '';
                    if($('.resultContainer').attr('keywords') && $scrollTarget.parents('.resultContainer').attr('keywords') !== ''){
                        keywordsSting += $('.resultContainer').attr('keywords');
                    }
                    if($('.resultContainer').attr('keywords') !== $('.resultContainer').attr('original-keywords')){
                        if($('.resultContainer').attr('original-keywords') && $('.resultContainer').attr('original-keywords') !== ''){
                            if($('.resultContainer').attr('keywords') !== ''){
                                keywordsSting += ' AND ('+ $('.resultContainer').attr('original-keywords') +')';
                            } else {
                                keywordsSting += $('.resultContainer').attr('original-keywords');
                            }
                        }
                    }
                    keywordsSting = keywordsSting.replace(/\%22/g, '\\%22');
                    if($scrollTarget.parents('#dr_CategoryProductList').length){
                        callingPage = 'categoryProductListPage';
                    } else if ($scrollTarget.parents('#dr_ProductSearchResults').length){
                        callingPage = 'productSearchResultPage';
                    }
                    $.ajax({
                        url:'/store/' + inputVariables.storeData.page.siteid + '/' + inputVariables.storeData.page.locale + '/' + action + '/categoryID.' + categoryID + '/startIndex.' + startIndex + '/size.' + size + '/sort.' + sortData + '?keywords=' + encodeURI(keywordsSting),
                        dataType:'html',
                        data: {Env:inputVariables.storeData.page.Env, callingPage:callingPage},
                        beforeSend:function(){
                            $scrollTarget.append('<div class="grid-row row-padded loader" style="text-align:center;">'+inputVariables.storeData.resources.text.LOADING+'... '+nextStartIndex+'-'+nextResultSize+' '+inputVariables.storeData.resources.text.OF_TOTAL+' '+totalSize+' '+inputVariables.storeData.resources.text.RESULTS+'</div>');
                            $scrollTarget.addClass('off');
                        },
                        cache:true
                    }).done(function(data){
                        var resultHtml = $(data).find('#productListContainer').html(),
                            resultCallingSize = parseInt($(data).find('#productListContainer').attr('calling-size')),
                            bvParam = { productIds: [], containerPrefix: 'BVRRInlineRating' };
                        $(data).find("a[pid-ref]").each(function(){
                            bvParam.productIds.push($(this).attr("pid-ref"));
                        });
                        $(data).find("div[rroverrideid]").each(function(){
                            bvParam.productIds.push($(this).attr("rroverrideid"));
                        });
                        $('.fromTo').html('1-'+nextResultSize);
                        $scrollTarget.find('.loader').fadeOut();
                        $(resultHtml).hide().appendTo($scrollTarget).fadeIn().removeAttr('style');
                        $('.category-products>.row').not(':last-child').find('.product-row').css('margin-bottom','1.875em');

                        $scrollTarget.find('.loader').remove();
                        $BV.ui('rr', 'inline_ratings', bvParam);
                        if((startIndex+size) < totalSize){
                            startIndex += size;
                        }
                        callingSize += resultCallingSize;
                        $scrollTarget.attr('startIndex',startIndex);
                        $scrollTarget.attr('calling-size',callingSize);
                        if($('.compareContainer').length){
                            initCompare();
                        }
                        $scrollTarget.removeClass('off');
                    });
                }
            }
            lastScrollTop = windowScrollTop;
        }
    }
});

$.widgetize('dr_catSortOptions',function(){
    var $sortTarget = $(this),
        callingPage = '',
        $productContainer = $('#productListContainer'),
        action = $productContainer.attr('action'),
        categoryID = $productContainer.attr('category-id'),
        filterExists = $('.facet-search').length ? true : false,
        searchString = $('.resultContainer').attr('original-keywords'),
        f_sort = cookieObj.getCookie('f_sort_'+categoryID),
        f_keywords = cookieObj.getCookie('f_keywords_'+categoryID),
        f_searchString = cookieObj.getCookie('f_searchString');

    if($productContainer.parents('#dr_CategoryProductList').length){
        callingPage = 'categoryProductListPage';
    } else if ($productContainer.parents('#dr_ProductSearchResults').length){
        callingPage = 'productSearchResultPage';
    }

    if(f_sort != null && f_keywords == null && (f_searchString == searchString || callingPage == 'categoryProductListPage')){//case where there is no filter selected but only sort option is selected
        var listSize =  (f_sort.match(/listPrice/g) && $('body').hasClass('CategoryProductListPage')) ? 9999 : filterExists ? 15 : 16;
        var getUrl = '/store/' + inputVariables.storeData.page.siteid + '/' + inputVariables.storeData.page.locale + '/' + action + '/categoryID.' + categoryID + '/startIndex.0/size.' + listSize + '/sort.' + f_sort+'?keywords=' + encodeURI(f_searchString);
        sortAjax(getUrl, callingPage, $productContainer, f_sort, filterExists);
        $('select.dr_catSortOptions option[value="'+f_sort+'"]').attr('selected', 'selected');
    }

    $sortTarget.on('change',function(){
        var sortData = $sortTarget.find('option:selected').val(),
            startIndex = 0,
            totalSize = parseInt($productContainer.attr('total-size')),
            size = '',
            displaySize = filterExists?15:16,
            keywordsSting = '';

        document.cookie = 'f_sort_'+categoryID+'='+sortData;
        document.cookie = 'f_searchString='+searchString;

        /* price sorting result */
        if(sortData.match(/listPrice/g) && $('body').hasClass('CategoryProductListPage')){
            size = 9999;
        } else {
            size = filterExists?15:16;
        }

        if(startIndex+displaySize > totalSize){
            $('.fromTo').html('1-'+totalSize);
        } else {
            $('.fromTo').html('1-'+displaySize);
        }
        $productContainer.attr('size',displaySize);
        $productContainer.attr('startindex',startIndex+displaySize);
        $productContainer.attr('calling-size',startIndex+displaySize);
        if($('.resultContainer').attr('keywords') && $('.resultContainer').attr('keywords') !== ''){
            keywordsSting += $('.resultContainer').attr('keywords');
        }
        if($('.resultContainer').attr('keywords') !== $('.resultContainer').attr('original-keywords')){
            if($('.resultContainer').attr('original-keywords') && $('.resultContainer').attr('original-keywords') !== ''){
                if($('.resultContainer').attr('keywords') !== ''){
                    keywordsSting += ' AND ('+ $('.resultContainer').attr('original-keywords') +')';
                } else {
                    keywordsSting += $('.resultContainer').attr('original-keywords');
                }
            }
        }
        keywordsSting = keywordsSting.replace(/\%22/g, '\\%22');

        $('.dr_catSortOptions').find('option').removeAttr('selected');
        $('.dr_catSortOptions').find('option').each(function(){
            if($(this).val() == sortData){
                $(this).attr('selected','selected');
            }
        });

        var ajaxUrl = '/store/' + inputVariables.storeData.page.siteid + '/' + inputVariables.storeData.page.locale + '/' + action + '/categoryID.' + categoryID + '/startIndex.' + startIndex + '/size.' + size + '/sort.' + sortData + '?keywords=' + encodeURI(keywordsSting);

        sortAjax(ajaxUrl, callingPage, $productContainer, sortData, filterExists);
    });

    function sortAjax(ajaxUrl, callingPage, $productContainer, sortData, filterExists){
        $.ajax({
            url:ajaxUrl,
            dataType:'html',
            data: {Env:inputVariables.storeData.page.Env, callingPage:callingPage},
            beforeSend:function(){
                $productContainer.html('<img style="display:block; margin:10em auto;" src="'+ inputVariables.storeData.resources.images.loader +'" />').hide().fadeIn();
                $productContainer.removeClass('scrollDisplay');
            },
            cache:false
        }).done(function(data){
            var resultHtml = $(data).find('#productListContainer').html(),
                bvParam = { productIds: [], containerPrefix: 'BVRRInlineRating' };
            $(data).find("a[pid-ref]").each(function(){
                bvParam.productIds.push($(this).attr("pid-ref"));
            });
            $(data).find("div[rroverrideid]").each(function(){
                bvParam.productIds.push($(this).attr("rroverrideid"));
            });
            /* price sorting result */
            if(sortData.match(/listPrice/g) && $('body').hasClass('CategoryProductListPage')){
                var $sortedData = $(data),
                    sortingArray = (sortData.match(/ascending/g))?$sortedData.find('a.product[data-sort]').priceSorting('ascending'):$sortedData.find('a.product[data-sort]').priceSorting('descending'),
                    gridRowMutipler = 1,
                    gridColumnBase = parseInt(filterExists?3:4),
                    gridSortedContainer = filterExists?'#productListContainer > .row > .product-row':'#productListContainer > .row';

                $sortedData.find(gridSortedContainer).html('');
                for(var i = 0; i <sortingArray.length; i++){
                    if(i < gridRowMutipler*gridColumnBase){
                        $sortedData.find(gridSortedContainer + ':eq(' + (gridRowMutipler-1) + ')').append($(sortingArray[i]).wrap('<div></div>').parent().html());
                    } else {
                        gridRowMutipler++;
                        $sortedData.find(gridSortedContainer + ':eq(' + (gridRowMutipler-1) + ')').append($(sortingArray[i]).wrap('<div></div>').parent().html());
                    }
                }
                if(!filterExists){
                    $sortedData.find('#productListContainer > .row').each(function(){
                        for(var i = 0; i < $(this).find('a.product').length; i+=2){
                            $(this).find('a.product').slice(i,i+2).wrapAll('<div class="product-row col-md-6"></div>');
                        }
                    });
                }

                var hideAfter = filterExists?4:3;
                $sortedData.find('#productListContainer > .row').each(function(){
                    if($(this).index() > hideAfter){
                        $(this).hide();
                    }
                });
                resultHtml = $sortedData.find('#productListContainer').html();
            }

            $productContainer.html(resultHtml).hide();
            $productContainer.fadeIn('400',function(){
                if($('.compareContainer').length){
                    initCompare();
                }
                $productContainer.addClass('scrollDisplay');
            });
            $BV.ui('rr', 'inline_ratings', bvParam);
        });
    }

    if(window.location.href.match(/sort.listPrice/g)){
        $('select.dr_catSortOptions:eq(0)').trigger('change');
    }
});

$.widgetize('search-tip', function(){
    if($('body').hasClass('ProductSearchResultsPage')){
        $('a.search-tip').click(function(e){
            e.preventDefault();
            var destination = $(this).attr('data-href'),categoryID = $(this).attr('category-id');

            cookieObj.deleteCookie('f_categoryID');
            cookieObj.deleteCookie('f_keywords_'+categoryID);
            cookieObj.deleteCookie('f_sort_'+categoryID);
            cookieObj.deleteCookie('f_startIndex_'+categoryID);
            cookieObj.deleteCookie('f_size_'+categoryID);

            window.location = destination;
        });
    }
});

$.widgetize('call-center-tool', function(){
    if($('.addressFields').length){
        $('form[name=CheckoutAddressForm] input[name=SHIPPINGname1]').val('');
        $('form[name=CheckoutAddressForm] input[name=SHIPPINGname2]').val('');
        var $CheckoutAddressForm = $('form[name=CheckoutAddressForm]');
        $CheckoutAddressForm.submit(function(){
            if($('select#shippingState').attr('data-required') === 'false'){
                $('select#shippingState').parent().remove();
            }
            $('select#shippingCountry').removeAttr('disabled');
        });
    }
    if($('select#shippingCountry').length > 0){
        $('#dr_shipping .dr_formLine').hide();
        $('select#shippingCountry').parent().show();
        $("select#shippingCountry option[selected='selected']").removeAttr("selected");
        $("select#shippingCountry option[value='']").attr("selected","selected");
        $('select#shippingCountry').change(function(){
            var shippingCountry = $(this).val();
            if(shippingCountry!=''){
                $.ajax({
                    url: '/Storefront/Site/mscommon/cm/multimedia/js/dr-CrossBorderMapping_16.js',
                    dataType: 'json',
                    cache: true,
                    success: function(data){
                        $.each(data.COUNTRYinfoModal, function(){
                            datacon = this;
                            if(datacon.COUNTRYinfo.shippingCountry === shippingCountry){
                                mappingExistence = true;
                                $('select#shippingCountry option').each(function(){
                                    var optionValue = $(this).val();
                                    if(optionValue!=shippingCountry){
                                        $(this).remove();
                                    }
                                });
                                $('select#shippingAddressBook option').each(function(){
                                    var optionValue = $(this).attr('data-country');
                                    if(optionValue!=shippingCountry){
                                        $(this).remove();
                                    }
                                });
                                if($('select#shippingAddressBook option').length <= 0){
                                    if($('input#newAddress').length){
                                        $('input.newAddress').attr('checked','checked');
                                        $('input.newAddress').trigger('change');
                                    }else{
                                        $('#shippingAddressBook').append('<option value="NEW" selected="selected">...</option>');
                                    }
                                }
                                $('select#shippingAddressBook').change();
                                if(datacon.COUNTRYinfo.shippingState==='false'){
                                    $('#dr_shipping .dr_formLine').show();
                                    $('select#shippingState').attr('data-required','false');
                                    $('select#shippingState').parent().after('<input type="hidden" name="SHIPPINGstate" value=""/>');
                                    $('select#shippingState').parent().remove();
                                }else{
                                    var shippingStateOptions = datacon.COUNTRYinfo.shippingStateOptions;
                                    $('select#shippingState').html(shippingStateOptions);
                                    $('#dr_shipping .dr_formLine').show();
                                    $('select#shippingState').parent().show();
                                    $('select#shippingState').attr('data-required','true');
                                }
                            }
                        });
                        $("select#shippingCountry option[value="+shippingCountry+"]").attr("selected","selected");
                        $('select#shippingCountry').attr('disabled','disabled');
                    },
                    error: function () {

                    }
                });
            }
        });
    }

    function getCategory(parentCategoryID,type){
        if(inputVariables.storeData.CCT.countryCodesAvailable == ''){
            if(type === 'initial'){
                overlayControl('bar','initial');
            } else {
                overlayControl('bar','show');
            }
        }
        $.post(
                "/store/" + inputVariables.storeData.page.siteid +"/" + inputVariables.storeData.page.locale + "/DisplayPage",
            {id: 'CategoryListAggregateXMLDoc', categoryID: parentCategoryID, CCT: 'true'},
            function(data){
                parseXml(data,'category');
            },
            "xml"
        );
    }

    function getProduct(categoryID){
        overlayControl('bar','show');
        $.post(
                "/store/" + inputVariables.storeData.page.siteid +"/" + inputVariables.storeData.page.locale + "/DisplayPage",
            {id: 'CategoryProductListAggregateXMLDoc', categoryID: categoryID, size: '10000', sort: 'displayName ascending', CCT: 'true'},
            function(data){
                parseXml(data,'product');
            },
            "xml"
        );
    }

    function getVariation(productID){
        overlayControl('bar','show');
        $.post(
                "/store/" + inputVariables.storeData.page.siteid +"/" + inputVariables.storeData.page.locale + "/DisplayPage",
            {id: 'ProductDetailsAggregateXMLDoc', productID: productID, CCT: 'true'},
            function(data){
                parseXml(data,'variation');
            },
            "xml"
        );
    }

    function parseXml(xml,type){
        switch (type){
            case 'category':
                var categoryList = '<option value="">Please select a category</option>';
                $(xml).find("subcategory").each(function(){
                    var bundleCat = $(this).find("bundleCategory").text();
                    bundleCat = bundleCat ? 'data-bundleCategory="' + $(this).find("bundleCategory").text() + '"' : "";
                    categoryList += '<option value="' + $(this).attr("categoryID") + '"'+bundleCat+'>' + $(this).find("subcategoryName").text() + '</option>';
                });
                $("#categoryList").html(categoryList);
                break;
            case 'product':
                $('.variationSelectorMain').html('<legend>Choose a variation</legend>');
                var productList = '';
                if($(xml).find('product').length > 0){
                    productList += '<option value="">Please select a product</option>';
                    $(xml).find('product').each(function(){
                        productList += '<option data-overrideUrl="'+$(this).find("msPDPButtonOverrideURL").text()+'" data-isBBYMobile="' + $(this).find("isBBYMobile").text()+'" data-isSubsProduct="' + $(this).find("isSubsProduct").text() +'"' + 'value="' + $(this).attr("productID") + '">' + $(this).find("displayName").text() + '</option>';
                    });
                } else {
                    productList += '<option value="">No products were found.</option>';
                }
                $("#parentList").html(productList);

                break;
            case 'variation':
                var variationContent = '';
                variationContent += '<legend>Choose a variation</legend>';
                if($(xml).find('attribute[name="isOrderable"]:first').text() === 'true'){
                    if($(xml).find("productData hasVariations").text() === 'true'){
                        $('.dr_callCenterToolProductVaration').show();
                        var isBackupMedia = 0;
                        var isPhysical = 0;
                        $(xml).find('productData > variations > variation > variationDefiningValues > attributeValue[attributeName="msDeliveryMethod"]').each(function(){
                            if($(this).attr('attributeCleanValue') === 'physical'){
                                isPhysical ++;
                            }
                        });
                        $(xml).find('productData > variations > variation > variationDefiningValues > attributeValue[attributeName="msDeliveryMethod"]').each(function(){
                            if($(this).attr('attributeCleanValue') === 'downloadbackup'){
                                isBackupMedia ++;
                            }
                        });
                        if($(xml).find('attribute[name="msVariationSelectorDisplay"]')){
                            variationContent += '<select class="menu variations" name="productID"></select>';
                            $('.variationSelectorMain').html(variationContent);

                            var variationOptions = '',
                                variationCount = $(xml).find('variations > variation').length;
                            $(xml).find('variations > variation').each(function(){
                                var className = '';
                                var option = '';
                                var selected = '';
                                var isBBYMobileVar = $(this).find('isBBYMobileVar').text();
                                var price = '';

                                if(variationCount > 6) {
                                    price = ($(this).find('displayPriceWithVat') == "false") ? ('&#45;&#160;' + $(this).find('price > actualPrice').text()) : ('&#45;&#160;' + $(this).find('price > actualPriceWithVat').text());
                                }

                                if($(this).find('attributeValue[attributeName="msDeliveryMethod"]').length > 0){
                                    className += $(this).find('attributeValue[attributeName="msDeliveryMethod"]').attr('attributeCleanValue') + ' ';
                                }

                                if($(this).find('attributeValue[attributeName="msVersionType"]').length > 0){
                                    className += $(this).find('attributeValue[attributeName="msVersionType"]').attr('attributeCleanValue') + ' ';
                                } else if ($(this).find('attributeValue[attributeName="msLicenseType"]').length > 0) {
                                    className += $(this).find('attributeValue[attributeName="msLicenseType"]').attr('attributeCleanValue') + ' ';
                                } else if ($(this).find('attributeValue[attributeName="msSubscriptionType"]').length > 0) {
                                    className += $(this).find('attributeValue[attributeName="msSubscriptionType"]').attr('attributeCleanValue') + ' ';
                                }

                                if($(this).find('attributeValue[attributeName="ms_Product_Language"]').length > 0){
                                    className += $(this).find('attributeValue[attributeName="ms_Product_Language"]').attr('attributeCleanValue') + ' ';
                                }

                                if($(this).find('variationLabelOverride').text() !== ''){
                                    option = $(this).find('variationLabelOverride').text() + '&#160;' + price;
                                } else {
                                    option = $(this).find('attributeValue[attributeName="ms_Product_Language"]').text() + '&#160;' + price;
                                }

                                if($(this).find('isOrderableVariation').text() === 'true'){
                                    if(!($(xml).find('attribute[name="msVariationSelectorDisplay"]:first').text() === 'Win8 + Language' && $(this).find('downloadableVariation').text() === 'true')){
                                        variationOptions += ('<option value="' + $(this).attr('productID') + '" data-isBBYMobileVar="'+ isBBYMobileVar +'" class="' + className + '">' + option + '</option>');
                                    }
                                }
                            });
                            $('select.variations').html(variationOptions);
                            $('select.variations').live('change',function(){
                                var isbbymobilevar = $('select.variations option:selected').attr('data-isbbymobilevar');
                                if($.trim(isbbymobilevar)==='true'){
                                    $('.CctUserSelectionMain').removeClass('hide');
                                }else{
                                    $('.CctUserSelectionMain').addClass('hide');
                                }
                            });
                            $('select#parentList').live('change',function(){
                                var isbbymobile = $('select[id="parentList"] option:selected').attr('data-isbbymobile');
                                if($.trim(isbbymobile)==='true'){
                                    $('.CctUserSelectionMain').removeClass('hide');
                                }else{
                                    $('.CctUserSelectionMain').addClass('hide');
                                }
                            });
                            $('select.variations option:first').attr('selected','selected');
                            $('select.variations').trigger('change');
                        }
                        $('.variationSelectorMain').removeClass('enabled');
                        $.widgetize('variationSelectorMain');
                    } else {
                        $('.dr_callCenterToolProductVaration').hide();
                    }

                    var $relatedProductNodes = $(xml).find('popName:contains("PDP_RelatedProducts")').parent().find('offerInstances offerInstance');
                    if($relatedProductNodes.length !== 0){
                        var offerContent = '';
                        offerContent += '<h2 class="grid_8 alpha omega">Related Products</h2>';
                        offerContent += '<div class="tab grid_8 alpha omega"><ul>';
                        $relatedProductNodes.each(function(index){
                            index = index + 1;
                            if($(this).find('salesPitchKey1').text() !== null){
                                offerContent += '<li><a title="' + $(this).find('salesPitchKey1').text() + '" href="#tab' + index + '" title="' + $(this).find('salesPitchKey1').text() + '">' + $(this).find('salesPitchKey1').text() + '</a>';
                            } else {
                                offerContent += '<li>&#160;';
                            }
                            offerContent += '</li>';
                        });
                        offerContent += '</ul>';
                        $relatedProductNodes.each(function(index){
                            index ++;
                            offerContent += '<div class="section grid_8 alpha omega" data-size="8" data-display="' + $(this).find('salesPitchKey2').text() + '" id="tab' + index + '">';
                            offerContent += '<h3>' + $(this).find('salesPitchKey1').text() + '</h3>';
                            offerContent += '<div class="categoryTab"><div class="tabs leftright"><ul calss="navScroll"><li class="leftwards"><a href="#" title="Left">&#160;</a></li><li class="rightwards"><a href="#" title="Right">&#160;</a></li></ul></div></div>';
                            if($(this).find('salesPitchKey2').text() === '8up'){
                                offerContent += '<div class="sectionContainer grid_8 alpha omega"><div class="slider grid_8 alpha omega">';
                                $(this).find('offerProductInstance').each(function(index){
                                    index ++;
                                    var mod = index%4;
                                    if(mod !== 0){
                                        offerContent += '<div class="item article grid_2 alpha">';
                                    } else {
                                        offerContent += '<div class="item article grid_2 omega">';
                                    }
                                    offerContent += '<a class="dr_productName" href="#selector" title="' + $(this).find('product displayName').text() + '" pid-ref="' + $(this).find('product').attr('productID')  + '"><img ';
                                    if($(this).find('product msMedium').text() !== ''){
                                        offerContent += 'src="/DRHM/Storefront/Company/msintl/images/details/medium/' + $(this).find('product msMedium').text() + '"';
                                    } else {
                                        offerContent += 'src="' + $(xml).find('global images MissingImage.gif').text() + '" class="missingImage_msMedium"';
                                    }
                                    offerContent += ' alt="' + $(this).find('product displayName').text() + '" /></a>';
                                    offerContent += '<a class="dr_productName" href="#selector" title="' + $(this).find('product displayName').text() + '" pid-ref="' + $(this).find('product').attr('productID')  + '">' + $(this).find('product displayName').text() + '</a>';
                                    offerContent += '<p class="shortDescription">' + $(this).find('product shortDescription').text() + '</p>';
                                    if($(this).find('product price discounted').text() === 'true'){
                                        offerContent += '<p class="productPrice productPriceDiscounted">';
                                    } else {
                                        offerContent += '<p class="productPrice">';
                                    }
                                    if($(this).find('product hasVariations').text() === 'true'){
                                        offerContent += '<span class="dr_from">From: </span>';
                                    }
                                    offerContent += $(this).find('product price unitPriceWithDiscount').text();
                                    offerContent += '</p>';
                                    if($(this).find('product price discounted').text() === 'true'){
                                        offerContent += '<div class="regularPrice"><span class="dr_regularPriceLabel">Regular price: </span><span class="lineThrough">' + $(this).find('product price unitPrice').text() + '</span></div>';
                                    }
                                    offerContent += '</div>';
                                    if(mod === 0){
                                        offerContent += '<div class="clear">&#160;</div>';
                                    }
                                });
                                offerContent += '</div></div>';
                            } else if ($(this).find('salesPitchKey2').text() === '2up'){
                                offerContent += '<div class="sectionContainer grid_8 alpha omega"><div class="slider grid_8 alpha omega">';
                                $(this).find('offerProductInstance').each(function(index){
                                    index ++;
                                    var mod = index%2;
                                    if(mod !== 0){
                                        offerContent += '<div class="article grid_4 alpha">';
                                    } else {
                                        offerContent += '<div class="article grid_4 omega">';
                                    }
                                    offerContent += '<div class="grid_2 alpha"><a href="#selector" title="' + $(this).find('product displayName').text() + '" pid-ref="' + $(this).find('product').attr('productID')  + '"><img ';
                                    if($(this).find('product msMedium').text() !== ''){
                                        offerContent += 'src="/DRHM/Storefront/Company/msintl/images/details/medium/' + $(this).find('product msMedium').text() + '"';
                                    } else {
                                        offerContent += 'src="' + $(xml).find('global images MissingImage.gif').text() + '"';
                                    }
                                    offerContent += ' alt="' + $(this).find('product displayName').text() + '" /></a></div>';
                                    offerContent += '<div class="grid_2 omega"><a class="dr_productName" href="#selector" title="' + $(this).find('product displayName').text() + '" pid-ref="' + $(this).find('product').attr('productID')  + '">' + $(this).find('product displayName').text() + '</a>';
                                    offerContent += '<p class="shortDescription">' + $(this).find('product shortDescription').text() + '</p>';
                                    if($(this).find('product price discounted').text() === 'true'){
                                        offerContent += '<p class="productPrice productPriceDiscounted">';
                                    } else {
                                        offerContent += '<p class="productPrice">';
                                    }
                                    if($(this).find('product hasVariations').text() === 'true'){
                                        offerContent += '<span class="dr_from">From: </span>';
                                    }
                                    offerContent += $(this).find('product price unitPriceWithDiscount').text();
                                    offerContent += '</p>';
                                    if($(this).find('product price discounted').text() === 'true'){
                                        offerContent += '<div class="regularPrice"><span class="dr_regularPriceLabel">Regular price: </span><span class="lineThrough">' + $(this).find('product price unitPrice').text() + '</span></div>';
                                    }
                                    offerContent += '</div></div>';
                                    if(mod === 0){
                                        offerContent += '<div class="clear">&#160;</div>';
                                    }
                                });
                                offerContent += '</div></div>';
                            }

                            offerContent += '</div>';
                        });

                        offerContent += '</div></div>';

                        $('#dr_CallCenterToolRelatedProductOfferSection .horizontal_tabbed_scroller.tabs').html(offerContent);

                        /** for IE 7 **/
                        $('#dr_CallCenterToolRelatedProductOfferSection .horizontal_tabbed_scroller.tabs .tab ul:first li a').each(function(){
                            var split = $(this).attr('href').split('#');
                            $(this).attr('href', '#' + split[1]);
                        });
                        /** for IE 7 **/

                        $('#dr_CallCenterToolRelatedProductOfferSection .horizontal_tabbed_scroller.tabs').removeClass('enabled');
                        $.widgetize('widget.tabs');
                    } else {
                        $('#dr_CallCenterToolRelatedProductOfferSection .horizontal_tabbed_scroller.tabs').html('');
                    }
                }
                break;
        }
        overlayControl('bar','hide');
    }

    function initialize(type){
        getCategory(inputVariables.storeData.CCT.initialCategoryID,type);
        $('select#parentList').html('<option value="">Please select a product</option>');
        $('.CctUserSelectionMain').addClass('hide');
        if(inputVariables.storeData.CCT.isCountryCodesAvailableEmpty === 'false' || inputVariables.storeData.page.siteid  === 'mseea'){
            if(inputVariables.storeData.CCT.overrideOverlayImpl !== 'true'){
                if(inputVariables.storeData.CCT.threeWordsCountryCodeExists !== 'true' || inputVariables.storeData.CCT.twoWordsCountryCodeExists !== 'true'){
                    if(inputVariables.storeData.CCT.marketOverlay === ''){
                        overlayControl('market','show');
                        $('#dr_buyNow').hide();
                    }
                    if(inputVariables.storeData.CCT.threeWordsCountryCodeExists === 'true' && inputVariables.storeData.CCT.twoWordsCountryCodeExists === 'true'){
                        if($('.currentCountry').length < 1){
                            $('.header').append('<p class="currentCountry" style="float:right; position:relative; top:50px;">Current Shopper Country: <b>' + $('.countrySection select option:selected').text() + '</b></p>');
                        }
                    }
                }
            }
            if(inputVariables.storeData.CCT.overrideOverlayImpl === 'true'){
                if(inputVariables.storeData.CCT.multiTheme === ''){
                    if(inputVariables.storeData.CCT.CALL_CENTER_TOOL_MULTI_THEME_MARKETS_URL !== 'CALL_CENTER_TOOL_MULTI_THEME_MARKETS_URL'){
                        overlayControl('market','show');
                        $('.marketSelector').css('padding','52px');
                        $('#dr_buyNow,.localeSection,.countrySection h4').hide();
                    }
                } else {
                    if($('.currentCountry').length < 1){
                        $('.header').append('<p class="currentCountry" style="float:right; position:relative; top:50px;">Current Shopper Country: <b>' + $('.countrySection select option:selected').text() + '</b></p>');
                    }
                }
            }
        }
        if(inputVariables.storeData.CCT.isCountryCodesAvailableEmpty === 'true'){
            if(inputVariables.storeData.CCT.multiTheme === ''){
                if(inputVariables.storeData.CCT.CALL_CENTER_TOOL_MULTI_THEME_MARKETS_URL !== 'CALL_CENTER_TOOL_MULTI_THEME_MARKETS_URL'){
                    overlayControl('market','show');
                    $('.marketSelector').css('padding','52px');
                    $('#dr_buyNow,.localeSection,.countrySection h4').hide();
                }
            } else {
                if($('.currentCountry').length < 1){
                    $('.header').append('<p class="currentCountry" style="float:right; position:relative; top:50px;">Current Shopper Country: <b>' + $('.countrySection select option:selected').text() + '</b></p>');
                }
            }
        }

        $('form[name=callCenterToolWLIDSearchForm]').attr('action',window.location);
        $('.variationSelectorMain').html('');
        $('.dr_callCenterToolCategory').show();
        $('.dr_callCenterToolBaseProduct').show();
        $('.dr_callCenterToolProductVaration').hide();
        $('.dr_callCenterToolBaseProductDirectSelect').hide();
        $('.sectionContainer a').attr('href','#selector');
        $('#shoppingCartFrame').load(function(){
            iframeResize($(this));
        });
    }

    function iframeResize(frameObj){
        frameObj.css('height',frameObj.contents().find('#dr_CallCenterToolShoppingCart').outerHeight());
    }

    function overlayControl(div,event){
        if(div === 'market'){
            if(event === 'show'){
                $('#dr_scs_progress_wrapper').hide();
                $('.marketSelector').show();
                $('.overlayWrap').css({ opacity: 0.5, 'width':$(document).width(),'height':$(document).height(),'display':'block'});
            } else if (event === 'hide') {
                $('.overlayWrap').css({'display':'none'});
                $('.marketSelector').hide();
            }
        } else {
            $('#dr_CallCenterToolShoppingCartSection').ready(function(){
                if(event === 'show'){
                    $('.overlayWrap').css({ opacity: 0.5, 'width':$(document).width(),'height':$(document).height(),'display':'block'});
                    $('#dr_scs_progress_wrapper').css({'top':$('#dr_CallCenterToolShoppingCartSection').position().top, 'display':'block'});
                    $('#dr_buyNow').focus();
                    $('#dr_callCenterToolProductSelector select').attr('disabled','disabled');
                } else if (event === 'initial'){
                    $('.overlayWrap').css({ opacity: 0.5, 'width':$(document).width(),'height':$(document).height(),'display':'block'});
                    $('#dr_scs_progress_wrapper').css({'top':($(window).height() - $('#dr_scs_progress_wrapper').height()) / 2 + $(window).scrollTop() + "px", 'display':'block'});
                    $('#dr_callCenterToolProductSelector select').attr('disabled','disabled');
                } else if (event === 'hide'){
                    if($('.marketSelector').css('display') === 'none'){
                        $('.overlayWrap').css({'display':'none'});
                        $('#dr_scs_progress_wrapper').css({'display':'none'});
                        $('#dr_callCenterToolProductSelector select').removeAttr('disabled');
                    }
                }
            });
        }
    }

    var productPickerDataMap = [];
    initialize('initial');
    $('#dr_resetAll').click(function(){
        initialize('reset');
    });

    $('#categoryList').change(function(){
        var categoryID = $(this).val();
        $('.dr_callCenterToolProductVaration').hide();
        if(categoryID !== ''){
            getProduct(categoryID);
        } else {
            initialize('reset');
        }
    });

    $('#parentList').change(function(){
        var productID = $(this).val();
        var overrideUrl = $(this).find('option[value="'+productID+'"]').attr("data-overrideUrl");
        if(productID !== '' && ! overrideUrl.match("ProductPicker")){
            getVariation(productID);
        }
    });

    $('.tabs .sectionContainer a').click(function(){
        $('.dr_callCenterToolCategory').hide();
        $('.dr_callCenterToolBaseProduct').hide();
        $('.dr_callCenterToolBaseProductDirectSelect').show();
        $('.dr_callCenterToolBaseProductDirectSelect h4').attr('pid-ref',$(this).attr('pid-ref')).html($(this).attr('title'));
        getVariation($(this).attr('pid-ref'));
    });

    $('#dr_callCenterToolCouponCodeSection a').click(function(){
        if($('#shoppingCartFrame').contents().find('#promoCode').length !== 0){
            $('#shoppingCartFrame').contents().find('#promoCode').val($(this).text());
        } else {
            alert("Your shopping cart is currently empty.");
        }
    });

    $('.overlayWrap').on('click',function(){
        if(!$('.dr_bundleContainer').hasClass('hide')){
            $('.overlayWrap').hide();
            $('.dr_bundleContainer').addClass('hide');
        }
    });
    $('#dr_buyNow').click(function(event){
        if($('select#parentList').is(':visible') && $('select#parentList option:selected').val() === ''){
            alert("Please select a product.");
        } else {
            var overrideUrl = $('select#parentList option:selected').attr("data-overrideurl");
            var isBundleCat = $('select#categoryList option:selected').attr("data-bundleCategory");
            var activationType = '';
            if($('.CctUserSelectionMain select#data-userType').val() === 'new'){
                activationType = "New";
            }else{
                if($('.CctUserSelectionMain select#data-addOrUpgrade').val() === 'addline'){
                    activationType = "AddDevice";
                }else{
                    activationType = "Upgrade";
                }
            }
            var isbbymobileselected = $('select.variations option:selected').attr('data-isbbymobilevar');
            if($.trim(isbbymobileselected)==='true'){
                var overlayPopUpWrap = $('#overlayPopUpWrap');
                var activationTypeCookie = cookieObj.getCookie('activationType');
                if(activationTypeCookie){
                    if(activationTypeCookie == "Upgrade" || activationTypeCookie == "AddDevice"){
                        $("#overlayPopInnerContent").html("inputVariables.storeData.CCT.Monaco_BBY_PDP_UPGRADE_ERROR");
                        overlayPopUpWrap.not(":visible").fadeTo("fast",1);
                        event.preventDefault();
                        return false;
                    }else{
                        cookieObj.setPathCookie('activationType',activationType);
                    }
                }else{
                    cookieObj.setPathCookie('activationType',activationType);
                }
            }
            overrideUrl = overrideUrl ? overrideUrl.replace('http://www.microsoftstore.com','').replace('DisplayProductPickerPage','DisplayProductPickerCCTPage') : overrideUrl;
            if(overrideUrl.match('DisplayProductPickerCCTPage') && Boolean(isBundleCat)){
                $.ajax({
                    url: overrideUrl,
                    dataType: 'html',
                    beforeSend: function(){
                        $('.overlayWrap, #dr_scs_progress_wrapper').show();
                        $(".dr_bundleContainer .mfp-close").click(function(e){
                            e.preventDefault();
                            $(".dr_bundleContainer").addClass("hide");
                            $('.overlayWrap').hide();
                        });
                        /*if(productPickerDataMap[overrideUrl]){
                         $('#dr_scs_progress_wrapper').hide();
                         $('.dr_bundleContainer').removeClass('hide');
                         $('.dr_bundleContainer .ProductPickerCCTPage').html(productPickerDataMap[overrideUrl]);
                         return false;
                         }*/
                    },
                    success: function(data){
                        $('#dr_scs_progress_wrapper').hide();
                        $('.dr_bundleContainer').removeClass('hide');
                        /*productPickerDataMap[overrideUrl] = data;*/
                        $('.dr_bundleContainer .ProductPickerCCTPage').html(data);
                    }
                });
                return false;
            }
            else{
                if($('#shoppingCartFrame').contents().find("body").attr('data-isSubsInCart')==="true" && $('select[id="parentList"] option:selected').attr('data-issubsproduct')==="true"){
                    $( ".multisubs" ).remove();
                    $( "<p class='dr_error multisubs'>" + inputVariables.storeData.CCT.AUTORENEW_SUBS_MULTIPLE_PRD_ERROR_MSG + "</p>").insertAfter( "#dr_callCenterToolCouponCodeSection" );
                    event.preventDefault();
                }else{
                    $( ".multisubs" ).remove();
                    if($('select.variations').length !== 0){
                        if($('select.variations option:selected').val() !== ''){
                            overlayControl('bar','show');
                            $('#dr_CallCenterToolShoppingCartSection #shoppingCartFrame').attr('src','/store/' + inputVariables.storeData.page.siteid +"/" + inputVariables.storeData.page.locale + '/buy/quantity.1/productID.' + $('select.variations option:selected').val() + '/nextAction.DisplayCallCenterToolShoppingCartPage');
                            $('#dr_CallCenterToolShoppingCartSection #shoppingCartFrame').load(function(){
                                if($('form[name="callCenterToolWLIDSearchForm"] input[name="reqID"]').val() === ''){
                                    $('form[name="callCenterToolWLIDSearchForm"] input[name="reqID"]').val($('#dr_CallCenterToolShoppingCartSection #shoppingCartFrame').contents().find('#dr_orderNumber #orderNumber').text());
                                    initialize('initial');
                                    overlayControl('bar','hide');
                                }
                                else {
                                    initialize('reset');
                                }
                            });
                        } else {
                            alert("Please select a product variation.");
                        }
                    } else {
                        if($('.dr_callCenterToolBaseProduct').is(':visible')){
                            overlayControl('bar','show');
                            $('#dr_CallCenterToolShoppingCartSection #shoppingCartFrame').attr('src','/store/' + inputVariables.storeData.page.siteid + '/' + inputVariables.storeData.page.locale + '/buy/quantity.1/productID.' + $('select[id="parentList"] option:selected').val() + '/nextAction.DisplayCallCenterToolShoppingCartPage');
                            $('#dr_CallCenterToolShoppingCartSection #shoppingCartFrame').load(function(){
                                if($('form[name="callCenterToolWLIDSearchForm"] input[name="reqID"]').val() === ''){
                                    $('form[name="callCenterToolWLIDSearchForm"] input[name="reqID"]').val($('#dr_CallCenterToolShoppingCartSection #shoppingCartFrame').contents().find('#dr_orderNumber #orderNumber').text());
                                    initialize('initial');
                                    overlayControl('bar','hide');
                                }
                                else {
                                    initialize('reset');
                                }
                            });
                        } else {
                            overlayControl('bar','show');
                            $('#dr_CallCenterToolShoppingCartSection #shoppingCartFrame').attr('src','/store/' + inputVariables.storeData.page.siteid + '/' + inputVariables.storeData.page.locale + '/buy/quantity.1/productID.' + $('.dr_callCenterToolBaseProductDirectSelect h4').attr('pid-ref') + '/nextAction.DisplayCallCenterToolShoppingCartPage');
                            $('#dr_CallCenterToolShoppingCartSection #shoppingCartFrame').load(function(){
                                if($('form[name="callCenterToolWLIDSearchForm"] input[name="reqID"]').val() === ''){
                                    $('form[name="callCenterToolWLIDSearchForm"] input[name="reqID"]').val($('#dr_CallCenterToolShoppingCartSection #shoppingCartFrame').contents().find('#dr_orderNumber #orderNumber').text());
                                    initialize('initial');
                                    overlayControl('bar','hide');
                                }
                                else {
                                    initialize('reset');
                                }
                            });
                        }
                    }
                }
            }
        }
    });

    $('#dr_CallCenterToolShoppingCartSection #shoppingCartFrame').load(function(){
        $(this).contents().find('.dr_deleteItemLink').click(function(){
            iframeResize($('#shoppingCartFrame'));
            overlayControl('bar','show');
        });
        $(this).contents().find('form[name="ShoppingCartForm"]').submit(function(){
            overlayControl('bar','show');
        });
        overlayControl('bar','hide');
    });

    $('form[name="callCenterToolWLIDSearchForm"]').submit(function(){
        if($(this).children('input[name="reqID"]').val() === ''){
            alert("Your shopping cart is currently empty.");
            return false;
        } else {
            var email = $.trim($(this).children('input[name=email]').val()),
                reg = /@msncs\.com/ig,
                emailReg = /^[0-9a-zA-Z]+([0-9a-zA-Z]*[-._+])*[0-9a-zA-Z]*@[0-9a-zA-Z]+([-.][0-9a-zA-Z]+)*([0-9a-zA-Z]*[.])[a-zA-Z]{2,6}$/ig;
            if(reg.test(email)){
                alert("This email address is not valid for call-center purchases.");
                return false;
            } else if (!email.length) {
                alert("Enter email address.");
                return false;
            } else if(!emailReg.test(email)) {
                alert("This email address is not valid.");
                return false;
            } else if($('#shoppingCartFrame').contents().find("body #subsBillingCountries").length){
                if($('#shoppingCartFrame').contents().find("body #subsBillingCountries").val()=='' || $('#shoppingCartFrame').contents().find("body .dr_error.subsError").length){
                    $('#shoppingCartFrame').contents().find("body #subsBillingCountries").css('border','1px solid #FF0000');
                    return false;
                }else{
                    overlayControl('bar','show');
                }
            }else{
                overlayControl('bar','show');
            }
        }
    });

    $('form[name="CheckoutAddressForm"]').submit(function(){
        if($('input[name="discountNotes"]').val() === '') {
            $('span.dr_error', $(this)).css('display','inline-block');
            $('input[name="discountNotes"]').focus();
            return false;
        }

        var shippingMethodID = $('#shoppingCartFrame').contents().find('#dr_shippingEstimator select#dr_shipMethod option:selected').val();
        var shippingOptId = $('#shoppingCartFrame').contents().find('#shipMethodId').text();
        $(this).find('#dr_shipMethod').val(shippingMethodID);
        $(this).find("input[name=shippingMethodID]").attr("value", shippingOptId);
    });

    $('#discountNotes').click(function(){
        $(this).val('');
    });
    $('.localeSection select').change(function(){
        var redirectURL = $(this).find('option:selected').val();
        window.location = redirectURL;
    });

    $('.countrySection select option[twolettercc]').each(function(index){
        var thisOption = $('.countrySection select option[twolettercc]:eq('+index+')').val();
        var nextOption = $('.countrySection select option[twolettercc]:eq('+index+1+')').val();
        if(thisOption == nextOption){
            $('.countrySection select option[twolettercc]:eq('+index+1+')').remove();
        }
    });

    $('.countrySection select').change(function(){
        var redirectURL = inputVariables.storeData.actionName.CallCenterTool;
        if($("option:last",this).attr('twoLetterCC')){
            if($(this).find('option:selected').val() !== 'default'){
                redirectURL = redirectURL + '/threeWordsCountryCode.' + $(this).find('option:selected').val() + '/twoWordsCountryCode.' + $(this).find('option:selected').attr('twolettercc');
                window.location = redirectURL;
            } else if ($(this).find('option:selected').val() === 'default'){
                window.location = redirectURL;
            }
        } else {
            if($(this).find('option:selected').val() !== 'default'){
                redirectURL = location.protocol + '//' + location.hostname + $(this).find('option:selected').attr('value') + '/multiTheme.true';
                window.location = redirectURL;
            } else if ($(this).find('option:selected').val() === 'default'){
                window.location = redirectURL;
            }
        }
    });
    $('select#data-userType').change(function(){
        if($(this).val()==='existing'){
            $('.addOrUpgrade').show();
        }else{
            $('.addOrUpgrade').hide();
        }
    });
    $('.continueButtonBottom input#checkoutButton').live('click',function(e){
        if($('#termsConditionKey').length){
            if($('#termsConditionKey:not(:checked)').length){
                $('.termsConditionKeyMain').css('border','1px solid red');
                e.preventDefault();
            }
        }
    });
});

$.widgetize('MobileActivationsCoveragePage', function(){
    $('header a, .footer-links a, footer a').attr('target', '_blank');
    /*** Form elements ***/
    $("input[type=radio]", ".customerType").each(function(){
        $(this).click(function(){
            $(".coverageCheck").hide();
            $("." + $(this).attr('id')).show();
            $('input[name=customerAction]').change();
        });
        if($(this).is(':checked')){
            $(this).trigger('click');
        }
    });
    $('input[name=customerAction]').live('change', function(){
        var isUpgrade = $("#existingCustomer").is(':checked') ? $('input#upgrade').is(':checked') : false;
        /* Change breadcrumb */
        var $breadcrumb = $('.mobileActivationsBreadcrumb > div');
        if($breadcrumb.length > 1){
            $breadcrumb.not('.skipPlan').toggleClass('hide', isUpgrade);
            $breadcrumb.filter('.skipPlan').toggleClass('hide', !isUpgrade);
        }
    });
    /*** Request error ***/
    function checkPageError(){
        var messageName = '';
        if($('input[type=hidden][value!=""].trigger').length){
            messageName = $('input[type=hidden][value!=""].trigger:first').val();
        }
        if(messageName != ''){
            /* launch overlay with message */
            $('.triggerOverlayPopUp input.' + messageName).change();
        }
    }
    $('.removeMALineItemLink').click(function(e){
        var removeLink = $(this).attr('href');
        e.preventDefault();
        $('.triggerOverlayPopUp #removeMALineItem a.remove').attr('href',removeLink);
        $('.triggerOverlayPopUp input.removeMALineItem').val('removeMALineItem').change();
    });
    $('input.dr_button.disabled').live('click', function(e){
        e.preventDefault();
        checkPageError();
    });
    checkPageError();

    /*** Submit button ***/
    $('.btnSubmitSpinContainer').click(function(e){
        var $form = $(this).closest('.coverageCheck');
        if($.trim($form.find('.dr_error:visible').text()) != ''){
            $('.buttonContainer a').removeClass("hide");
            e.preventDefault();
            $('div[id=load_image]').hide();
            $('.buySpan_AddtoCart,buySpan_continue').show();
        } else {
            $('.buttonContainer a').addClass("hide");
        }
    });
    var $existingCustomerForm = $('form[name=ExistingCustomerForm]');
    $existingCustomerForm.on('submit',function(e){
        var isUpgrade = $("#existingCustomer").is(':checked') ? $('input#upgrade').is(':checked') : false;
        if(isUpgrade && $.trim( $('input[name=updatingPhoneNumber]',$existingCustomerForm).val() ).length == 0){
            e.preventDefault();
            $('#upgradeSelection #overlayPopUpContent').empty();
            $('.upgradeSelection').after($('#upgradeSelection')).val('');
            var newPlanFlag = '&newPlanFlag=false',
                firstName = '&firstName=' + $('input[name=firstName]',$existingCustomerForm).val(),
                lastName = '&lastName=' + $('input[name=lastName]',$existingCustomerForm).val(),
                phoneAreaCode = '&phoneAreaCode=' + $('input[name=phoneAreaCode]',$existingCustomerForm).val(),
                phoneLocalNumberPrefix = '&phoneLocalNumberPrefix=' + $('input[name=phoneLocalNumberPrefix]',$existingCustomerForm).val(),
                phoneLocalNumber = '&phoneLocalNumber=' + $('input[name=phoneLocalNumber]',$existingCustomerForm).val(),
                zipcode = '&zipcode=' + $('input[name=zipcode]',$existingCustomerForm).val(),
                govtIdentificationNumber = '&govtIdentificationNumber=' + $('input[name=govtIdentificationNumber]',$existingCustomerForm).val(),
                customerAction = '&customerAction=' + $('input[name=customerAction]',$existingCustomerForm).val(),
                upgradeSelection = '',
                SiteID = $('input[name=SiteID]',$existingCustomerForm),
                Locale = $('input[name=Locale]',$existingCustomerForm),
                CSRFAuthKey = $('form[name=ExistingCustomerForm] input[name=CSRFAuthKey]'),
                data = 'Action=DisplayMobileActivationsChoosePlanPage&SiteID=' + SiteID.val() + '&Locale=' + Locale.val() + newPlanFlag + firstName + lastName + phoneAreaCode + phoneLocalNumberPrefix + phoneLocalNumber + zipcode + govtIdentificationNumber + customerAction + '&Form=' + 'com.digitalriver.mobileactivations.synchronoss.gui.form.ExistingCustomerForm' + '&CallingPageID=' + 'MobileActivationsCoveragePage' + '&CSRFAuthKey=' + encodeURIComponent(CSRFAuthKey.val()) + '&ORIG_VALUE_operation=&operation=update';
            $.ajax({
                url: "/store/",
                type: "POST",
                data: data,
                cache: false,
                success: function(){
                    $.ajax({
                        url: "/store/" + SiteID.val() + "/" + Locale.val() + "/DisplayMobileActivationsUpgradeOverlayPage/",
                        cache: false,
                        success: function(html){
                            //console.log('Form post success');
                            upgradeSelection = $.trim(html);
                            $('#upgradeSelection #overlayPopUpContent').html(upgradeSelection);
                            if($('#upgradeSelection .updatingPhoneNumber').length > 1){
                                $('#upgradeSelection .formSubmit').click(function(e){
                                    e.preventDefault();
                                    var updatingPhoneNumber = $('input[name=updatingPhoneNumberList]:checked', '#upgradeSelection').val();
                                    $('input[name=updatingPhoneNumber]',$existingCustomerForm).val(updatingPhoneNumber);
                                    $('input[name=ORIG_VALUE_operation]',$existingCustomerForm).val('');
                                    $existingCustomerForm.submit();
                                });
                                $('.triggerOverlayPopUp input.upgradeSelection').val('upgradeSelection').change();
                                $('div[id=load_image]').hide();
                                $('.buySpan_continue').show();
                                $('.buttonContainer a').removeClass("hide");
                                var $closeButton = $('#upgradeSelection').closest('.overlayPopInner').find('.closeButton');
                                $closeButton.one('click', function(){
                                    $.ajax({
                                        url: "/store/" + SiteID.val() + "/" + Locale.val() + "/DisplayMobileActivationsCoveragePage/",
                                        cache: false,
                                        success:function(){}
                                    });
                                });
                            }else{
                                $('input[name=updatingPhoneNumber]',$existingCustomerForm).val($('input[name=phoneAreaCode]',$existingCustomerForm).val() + $('input[name=phoneLocalNumberPrefix]',$existingCustomerForm).val() + $('input[name=phoneLocalNumber]',$existingCustomerForm).val());
                                $('input[name=ORIG_VALUE_operation]',$existingCustomerForm).val('');
                                $existingCustomerForm.submit();
                            }
                        }
                    });
                },
                error: function(){
                    //console.log('Form post error');
                    $('div[id=load_image]').hide();
                    $('.buySpan_continue').show();
                    $('.buttonContainer a').removeClass("hide");
                }
            });
        }
    });
});

$.widgetize('promo-code', function(){
    $('.promo-code-panel a').click(function () {
        $('form[name=ShoppingCartForm]').submit();
    });
    $('form[name=ShoppingCartForm]').submit(function(){
        var $promoCode = $('input#promoCode'),
            $parentContainer = $promoCode.parents('.promo-code-panel'),
            promoCodeValue = $promoCode.val(),
            $errorContainer = $('.dr_error', $parentContainer);

        if (promoCodeValue.length === 0) {
            if($errorContainer.length !== 0){
                $errorContainer.text(inputVariables.storeData.resources.text.INVALID_COUPON_CODE);
            }
            else{
                $parentContainer.prepend('<span class="dr_error">' + inputVariables.storeData.resources.text.INVALID_COUPON_CODE + '</span>')
            }
            $('input#promoCode').addClass('dr_input_invalid');
            return false;
        }
        else {
            return true;
        }
    });
});

$.widgetize('MobileActivationsChoosePlanPage', function(){
    $('header a, .footer-links a, footer a').attr('target', '_blank');
    /*** Mini cart first load ***/
    var $default = $('input[name=serviceID]:checked'),
        emptyMiniCart = '<table border="0" cellspacing="0" cellpadding="0" width="100%"><tr><th width="1%" class="blank"> </th><th><h2>' + inputVariables.storeData.resources.text.MONTHLY_COST_ESTIMATE + '</h2></th><th width="1%" class="blank"> </th></tr><tr class="first"><td class="miniCartLoader" colspan="3"><img height="41" width="41" src="' + inputVariables.storeData.resources.images.loader + '"/></td></tr></table>';
    $('[name=sharedDataGroupId],[name=sharedDataGroupBillingCode]').removeAttr('checked');
    $default.siblings('input').click();
    var selectedRadioButton = '&' + $default.attr('name') + '=' + $default.val(),
        selectedSibling = '';
    if($default.siblings('input')){
        $default.siblings('input').each(function(){
            selectedSibling += '&' + $(this).attr('name') + '=' + $(this).val();
        });
    }
    selectedRadioButton = selectedRadioButton + selectedSibling;
    var $ServicePlanSelectionForm = $('form[name=ServicePlanSelectionForm]'),
        SiteID = $('form[name=ServicePlanSelectionForm] input[name=SiteID]'),
        Locale = $('form[name=ServicePlanSelectionForm] input[name=Locale]'),
        CSRFAuthKey = $('form[name=ServicePlanSelectionForm] input[name=CSRFAuthKey]'),
        data = 'Action=DisplayMobileActivationsChooseAddOnsPage&SiteID=' + SiteID.val() + '&Locale=' + Locale.val() + selectedRadioButton + '&Form=' + 'com.digitalriver.mobileactivations.synchronoss.gui.form.ServicePlanSelectionForm' + '&CallingPageID=' + 'MobileActivationsChoosePlanPage' + '&CSRFAuthKey=' + encodeURIComponent(CSRFAuthKey.val()) + '&ORIG_VALUE_operation=update&operation=update';
    $('.servicePlanMini').html(emptyMiniCart).addClass('loading');
    $.ajax({
        url: "/store/",
        type: "POST",
        data: data,
        cache: false,
        success: function(){
            $.ajax({
                url: "/store/" + SiteID.val() + "/" + Locale.val() + "/DisplayMobileActivationsMiniCartPage/",
                cache: false,
                success: function(html){
                    $('.servicePlanMini').html(html).removeClass('loading');
                    updateMiniCart($('input[name=serviceID]:checked'));
                    //console.log('Form post success');
                }
            });
        },
        error: function(){
            //console.log('Form post error');
        }
    });

    function updateMiniCart(obj){
        var lineCount = $('.lineItemTotal', '.servicePlanMini').attr('data-count'),
            $selected = obj,
            type = $selected.attr('data-type'),
            name = $selected.attr('data-name'),
            price = $selected.attr('data-price'),
            secondary = $selected.attr('data-secondary'),
            $sdf = $selected.siblings('input[name^=SDF_]'),
            sum = 0,
            planCostArr = [];
        //Reset options
        $('[name=sharedDataGroupId],[name=sharedDataGroupBillingCode]').removeAttr('checked');
        $selected.siblings('input').click();
        if(lineCount){
            //Collecting info by the type of plan
            switch(type) {
                case 'indv':
                    planCostArr.push({
                        name: name,
                        price:  price
                    });
                    break;
                case 'family':
                    var linePrice = price;
                    for(var i = 0; i < lineCount; i++){
                        if(i > 0) linePrice = (i == 1)?'0.00':secondary;
                        planCostArr.push({
                            name: name,
                            price:  linePrice
                        });
                    }
                    break;
                case 'share':
                    $selected.siblings('input[type=radio]').click();
                    planCostArr.push({
                        name: name,
                        price:  price
                    });
                    $sdf.each(function(){
                        var sdfName = $(this).attr('data-name'),
                            sdfPrice = $(this).attr('data-price');
                        planCostArr.push({
                            name: sdfName,
                            price:  sdfPrice
                        });
                    });
                    break;
            }
            planCostArr.sort(function(a, b){
                var a1 = parseFloat(a.price), b1 = parseFloat(b.price);
                if(a1 == b1) return 0;
                return a1< b1? 1: -1;
            });
            //Updating mini cart
            $('.cost', '.servicePlanMini').remove();
            $.each(planCostArr, function(idx, obj){
                var className = (idx > 0)?"cost":"first cost";
                $('<tr class="' + className + '"><td class="blank">&nbsp;</td><td class="plan">' + obj.name + '</td><td class="price">$' + obj.price + '</td><td class="blank">&nbsp;</td></tr>').insertBefore($('tr.lineItemTotal', '.servicePlanMini'));
                sum += parseFloat(obj.price);
            });
            $('span.totalAmount').html('$' + sum.toFixed(2));
        }
    }

    /*** Mini cart updating ***/
    $('input[name=serviceID]').on('click', function(){
        updateMiniCart($(this));
    });

    /*** Request error ***/
    function checkPageError(){
        var messageName = '';
        if($('input[type=hidden][value!=""].trigger').length){
            messageName = $('input[type=hidden][value!=""].trigger:first').val();
        }
        if(messageName != ''){
            /* launch overlay with message */
            $('.triggerOverlayPopUp input.' + messageName).change();
        }
    }
    if($('#overlayPopUpContent').length){
        checkPageError();
    }
    /*** Submit button ***/
    $('.btnSubmitSpinContainer input').click(function(e){
        var $table = $('table.servicePlan');
        if($table.find('.radio').length > 0 && $table.find('.radio:checked').length == 0){
            $('span.note, .buttonContainer a').removeClass("hide");
            e.preventDefault();
            $('div[id=load_image]').hide();
            $('.buySpan_AddtoCart').show();
        } else {
            $('span.note').addClass("hide");
            $('.buttonContainer a').addClass("hide");
            $('[name=servicePlanMiniContent]').val($('.servicePlanMini:not(".loading")').html());
        }
    });
});

$.widgetize('MobileActivationsChooseAddOnsPage', function(){
    $('header a, .footer-links a, footer a').attr('target', '_blank');
    var servicePlanMiniContent = $('[name=servicePlanMiniContent]').val();
    if(servicePlanMiniContent != ''){
        $('.servicePlanMini').html(servicePlanMiniContent);
        if(!$('.cost.selectedPlan').length){
            $('.servicePlanMini').find('.cost').each(function(){
                var $row = $(this),
                    name = $.trim($('.plan', $row).text());
                if(!$('[data-name="' + name + '"]').length){
                    $row.addClass('selectedPlan');
                }
            });
        }
        if($('input[type=radio]:checked, input[type=checkbox]:checked').length){
            updateMiniCart();
        } else{
            $('.cost').not('.selectedPlan').remove();
        }
    } else{
        /*** Mini cart first load ***/
        var $default = $('input[type=radio]:checked, input[type=checkbox]:checked'),
            emptyMiniCart = '<table border="0" cellspacing="0" cellpadding="0" width="100%"><tr><th width="1%" class="blank"> </th><th><h2>' + inputVariables.storeData.resources.text.MONTHLY_COST_ESTIMATE + '</h2></th><th width="1%" class="blank"> </th></tr><tr class="first"><td class="miniCartLoader" colspan="3"><img height="41" width="41" src="' + inputVariables.storeData.resources.images.loader + '"/></td></tr></table>',
            selectedCheckbox = '',
            selectedRadioButton = '';
        $('input[type=checkbox]:checked').each(function(){
            selectedCheckbox = '&' + $(this).attr('name') + '=' + $(this).val() + selectedCheckbox;
        });
        $('input[type=radio]:checked').each(function(){
            selectedRadioButton = '&' + $(this).attr('name') + '=' + $(this).val() + selectedRadioButton;
        });
        var $AddOnSelectionForm = $('form[name=AddOnSelectionForm]'),
            SiteID = $('form[name=AddOnSelectionForm] input[name=SiteID]'),
            Locale = $('form[name=AddOnSelectionForm] input[name=Locale]'),
            CSRFAuthKey = $('form[name=AddOnSelectionForm] input[name=CSRFAuthKey]'),
            data = 'Action=DisplayMobileActivationsCreditCheckPage&SiteID=' + SiteID.val() + '&Locale=' + Locale.val() + selectedCheckbox + selectedRadioButton + '&Form=' + 'com.digitalriver.mobileactivations.synchronoss.gui.form.AddOnSelectionForm' + '&CallingPageID=' + 'MobileActivationsChooseAddOnsPage' + '&CSRFAuthKey=' + encodeURIComponent(CSRFAuthKey.val()) + '&ORIG_VALUE_operation=update&operation=update';
        $('.servicePlanMini').html(emptyMiniCart).addClass('loading');
        $.ajax({
            url: "/store/",
            type: "POST",
            data: data,
            cache: false,
            success: function(){
                $.ajax({
                    url: "/store/" + SiteID.val() + "/" + Locale.val() + "/DisplayMobileActivationsMiniCartPage/",
                    cache: false,
                    success: function(html){
                        $('.servicePlanMini').html(html).removeClass('loading');
                        //console.log('Form post success');
                        $('.cost', '.servicePlanMini').each(function(){
                            var $row = $(this),
                                name = $.trim($('.plan', $row).text());
                            if(!$('[data-name="' + name + '"]').length){
                                $row.addClass('selectedPlan');
                            }
                        });
                        $('input[type=radio]:checked, input[type=checkbox]:checked').click();
                    }
                });
            },
            error: function(){
                //console.log('Form post error');
            }
        });
    }

    function updateMiniCart(){
        var lineCount = $('.lineItemTotal', '.servicePlanMini:eq(0)').attr('data-count'),
            sum = 0,
            serviceCostArr = [];
        if(lineCount && $('.cost.selectedPlan').length){
            //Selected add ons
            $('input[type=radio]:checked, input[type=checkbox]:checked').each(function(index){
                var name = $(this).attr('data-name'),
                    price = $(this).attr('data-price');
                serviceCostArr.push({
                    name: name,
                    price: price
                });
            });
            //Selected plan
            $('.cost.selectedPlan', '.servicePlanMini:eq(0)').each(function(){
                var $row = $(this),
                    name = $.trim($('.plan', $row).text()),
                    price = $.trim($('.price', $row).text()).replace('$','');
                serviceCostArr.push({
                    name: name,
                    price: price
                });
            });
            serviceCostArr.sort(function(a, b){
                var a1 = parseFloat(a.price), b1 = parseFloat(b.price);
                if(a1 == b1) return 0;
                return a1< b1? 1: -1;
            });
            //Updating mini cart
            $('.cost', '.servicePlanMini').remove();
            $.each(serviceCostArr, function(idx, obj){
                var className = (idx > 0)?"cost":"first cost";
                $('<tr class="' + className + '"><td class="blank">&nbsp;</td><td class="plan">' + obj.name + '</td><td class="price">$' + obj.price + '</td><td class="blank">&nbsp;</td></tr>').insertBefore($('tr.lineItemTotal', '.servicePlanMini'));
                sum += parseFloat(obj.price);
            });
            $('span.totalAmount').html('$' + sum.toFixed(2));
            //Add flag to selected plan
            $('.cost', '.servicePlanMini').each(function(){
                var $row = $(this),
                    name = $.trim($('.plan', $row).text()),
                    price = $.trim($('.price', $row).text()).replace('$','');
                if(!$('[data-name="' + name + '"]').length){
                    $row.addClass('selectedPlan');
                }
            });
        }
    }

    /*** Mini cart updating ***/
    $('input[type=radio], input[type=checkbox]').on('click', function(){
        updateMiniCart();
    });
    /*** Select by default ***/
    /*if($('input.radio.required').length === 0){
     var $AddOnSelectionForm = $('form[name=AddOnSelectionForm]'),
     SiteID = $('form[name=AddOnSelectionForm] input[name=SiteID]'),
     Locale = $('form[name=AddOnSelectionForm] input[name=Locale]');
     $.ajax({
     url: "/store/" + SiteID.val() + "/" + Locale.val() + "/DisplayMobileActivationsMiniCartPage/",
     cache: false,
     success: function(html){
     $('.servicePlanMini').html(html);
     //console.log('Form post success');
     }
     });
     } else {
     $('.selections').each(function(){
     $('.addOn input.radio.required:checked', this).click();
     });
     }*/
    /*** Submit button ***/
    $('.btnSubmitSpinContainer input').click(function(e){
        var $table = $('table.addOn');
        if($table.find('.radio').length > 0 && $table.find('.radio:checked').length == 0){
            $('span.note, .buttonContainer a').removeClass("hide");
            e.preventDefault();
            $('div[id=load_image]').hide();
            $('.buySpan_AddtoCart').show();
        } else {
            $('span.note').addClass("hide");
            $('.buttonContainer a').addClass("hide");
            $('[name=servicePlanMiniContent]').val($('.servicePlanMini:not(".loading")').html());
        }
    });
    var equalHeights = function() {
        $('.dr_MobileActivationsPage .tab .productData').removeAttr('style').equalHeights();
        $('.dr_MobileActivationsPage .popularAccessories .items .description').removeAttr('style').equalHeights();
    };
    $(window).on('resize load', function(){
        equalHeights();
    });
});

$.widgetize('MobileActivationsCreditCheckPage', function(){
    $('header a, .footer-links a, footer a').attr('target', '_blank');
    /*** Port a number ***/
    $('input[name*=port_a_number]').click(function(){
        var $container = $(this).closest('.dr_formLine').siblings('.portingNumberFieldset'),
            $obj = $('.dr_formLine input,.dr_formLine select', $container).not('[name^=ORIG_VALUE_]');
        if($(this).attr('value') == 'yes'){
            $container.removeClass('hide');
            $obj.each(function(){
                var objInfo1 = $(this).closest('.dr_formLine').find('.dr_ms_error').attr('data-required'),
                    objInfo2 = $(this).closest('.dr_formLine').find('.dr_ms_error').attr('data-validation');
                $(this).closest('.dr_formLine').find('.dr_ms_error').html('<span class="dr_error" required="' + objInfo1 + '"> </span>');
                $(this).attr('data-name', objInfo2);
            });
        } else {
            $obj.closest('.dr_formLine').find('.dr_ms_error').find('.dr_error').removeAttr('required');
            $obj.removeAttr('data-name');
            $container.addClass('hide');
            $(window).scrollTop($(window).scrollTop()-1);
        }
        $('.validation').removeClass('enabled');
        $.widgetize('validation');
    });
    $('input[name*=port_a_number]').each(function(){
        if($(this).val() === 'yes'){
            $(this).trigger('click');
        }
        $(this).attr('id', $(this).attr('name') + '_' + $(this).val()).siblings('label').attr('for', $(this).attr('name') + '_' + $(this).val());
    });

    /*** Auto fill in porting fields ***/
    function autoFillClick(obj){
        var $autoFill = obj,
            $container = $autoFill.closest('.container'),
            $elements = $('.autoFillEnabled input,.autoFillEnabled select', $container),
            $form = $('.' + $autoFill.val());
        $elements.not('[type=hidden]').each(function(){
            var val = '';
            if($form.length){
                val = $('.dr_formLine [data-name=' + $(this).attr('data-name') + ']', $form).val();
            } else {
                val = $('[name="ORIG_VALUE_' + $(this).attr('name') + '"]', $container).val();
            }
            if(val != ""){
                $(this).val(val).closest('.dr_formLine').find('.dr_ms_error').find('.dr_error').empty();
            }
        });
        $('.autoFillEnabled', $container).toggleClass('hide', $autoFill.is(':checked'));
    }
    if($('.autoFill').length){
        $('.autoFill').each(function(){
            var $autoFill = $(this),
                $container = $autoFill.closest('.container'),
                $elements = $('.autoFillEnabled input,.autoFillEnabled select', $container),
                $form = $('.' + $autoFill.val());
            $elements.filter('[name^=ORIG_VALUE_]').each(function(){
                var name = $(this).attr('name').replace('ORIG_VALUE_','');
                $(this).val($(this).siblings('[name="' + name + '"]').val());
            });
            if($form.length){
                $autoFill.click(function(){
                    autoFillClick($(this));
                });
                if($elements.length){
                    $elements.not('[type=hidden]').each(function(){
                        $('.dr_formLine [data-name=' + $(this).attr('data-name') + ']', $form).on('blur', function(){
                            if($(this).val().length){
                                $('.autoFill').filter(':checked').each(function(){
                                    autoFillClick($(this));
                                });
                            }
                        });
                    });
                    $autoFill.attr('checked', 'checked');
                    autoFillClick($(this));
                } else {
                    $autoFill.closest('.dr_formLine').addClass('hide');
                }
            } else{
                $autoFill.closest('.dr_formLine').addClass('hide');
            }
        });
    }
    /*** Default values ***/
    var billingstate = $('input[name=ORIG_VALUE_state]').val(),
        idexpirationmonth = $('input[name=ORIG_VALUE_idexpirymonth]').val(),
        idexpirationday = $('input[name=ORIG_VALUE_idexpiryday]').val(),
        idexpirationyear = $('input[name=ORIG_VALUE_idexpiryyear]').val(),
        idissuingstate = $('input[name=ORIG_VALUE_idissuingstate]').val(),
        birthmonth = $('input[name=ORIG_VALUE_month]').val(),
        birthday = $('input[name=ORIG_VALUE_day]').val(),
        birthyear = $('input[name=ORIG_VALUE_year]').val();
    $('#billingState option[value=' + billingstate + ']').attr("selected", "selected");
    $('#idExpirationMonth option[value=' + idexpirationmonth + ']').attr("selected", "selected");
    $('#idExpirationDay option[value=' + idexpirationday + ']').attr("selected", "selected");
    $('#idExpirationYear option[value=' + idexpirationyear + ']').attr("selected", "selected");
    $('#billingIdissuingStates option[value=' + idissuingstate + ']').attr("selected", "selected");
    $('#birthdayMonth option[value=' + birthmonth + ']').attr("selected", "selected");
    $('#birthdayDay option[value=' + birthday + ']').attr("selected", "selected");
    $('#birthdayYear option[value=' + birthyear + ']').attr("selected", "selected");

    var servicePlanMiniContent = $('[name=servicePlanMiniContent]').val();
    if(servicePlanMiniContent != ''){
        $('.servicePlanMini').html(servicePlanMiniContent);
    } else{
        /*** Mini cart ***/
        var $CreditCheckForm = $('form[name=CreditCheckForm]'),
            SiteID = $('form[name=CreditCheckForm] input[name=SiteID]'),
            Locale = $('form[name=CreditCheckForm] input[name=Locale]');
        $.ajax({
            url: "/store/" + SiteID.val() + "/" + Locale.val() + "/DisplayMobileActivationsMiniCartPage/",
            cache: false,
            success: function(html){
                $('.servicePlanMini').html(html);
            }
        });
    }
    /*** Request error ***/

    function checkPageError(){
        var messageName = '';
        if($('input[type=hidden].trigger').length){
            messageName = $('input[type=hidden][value!=""].trigger:first').val();
        }
        //console.log('credit check');
        if(messageName != ''){
            /* launch overlay with message */
            $('.triggerOverlayPopUp input.' + messageName).change();
        }
    }
    if($('#overlayPopUpContent').length){
        checkPageError();
    }
    /*** Submit button ***/
    $('.btnSubmitSpinContainer').click(function(e){
        var $cctCreditCheckAcceptedError = $('#cctCreditCheckAccepted').closest('.dr_formLine').find('.dr_ms_error');
        $cctCreditCheckAcceptedError.find('.dr_error').empty();
        if($('#cctCreditCheckAccepted:not(:checked)').length){
            $cctCreditCheckAcceptedError.show();
            $cctCreditCheckAcceptedError.find('.dr_error').html('Please obtain approval from the customer');
            $('html, body').animate({ scrollTop: $('span[required=cctCreditCheckAccepted]').offset().top-80 }, 500);
        }
        if($.trim($('.accountInfoFieldset').find('.dr_error:visible').text()) != ''){
            $('.buttonContainer a').removeClass("hide");
            e.preventDefault();
            $('div[id=load_image]').hide();
            $('.buySpan_AddtoCart').show();
        } else {
            $('.buttonContainer a').addClass("hide");
        }
    });
    $("input#billingIdNumber").removeAttr("maxlength");
});
$.widgetize('plancollapse', function(){
    $(this).live('keydown', function(e){
        if(e.which === 13){
            $(this).click();
            return false;
        }
    });

    $(this).click(function(e){
        $(this).toggleClass('active');
        $(this).closest('.planSizeContainer').next('.compare').slideToggle('slow',function() {
            $(window).scrollTop($(window).scrollTop()-1);
        });
        $(this).next('p').slideToggle('slow');
        return false;
    });
});

$.widgetize('popularAccessories', function(){
    var $popularAccessories = $(this);

    $popularAccessories.find('a.add').click(function(e){
        e.preventDefault();
        var $add = $(this),
            offerProducts = $('.popularAccessories a.add[data-id=' + $add.attr('data-id') + ']');
        offerProducts.addClass('hide').siblings('.processing.adding').removeClass('hide').siblings('.error, .addedToCart').addClass('hide');
        addToCart($add);
    });

    function addToCart(obj){
        var buyurl = obj.attr('data-href') + '/quantity.1?jsonCallback=?',
            offerProducts = $('.popularAccessories a.add[data-id=' + obj.attr('data-id') + ']');
        $.ajax({
            url: buyurl,
            dataType: 'jsonp',
            cache: false
        }).done(function(addedLineItemJsonData){
            offerProducts.siblings('.processing').addClass('hide').siblings('.addedToCart').removeClass('hide');
            $('.dr_MobileActivationsPage .popularAccessories .items .selection').css('height','auto').equalHeights();
        }).fail(function(jqXHR, textStatus, errorThrown){
            offerProducts.removeClass('hide').siblings('.processing').addClass('hide').siblings('.error').removeClass('hide');
        });
    }

    if($popularAccessories.find('.viewAll.expend').length) {
        $popularAccessories.find('.group:last').hide();
        $popularAccessories.find('.viewAll.expend').click(function(e){
            e.preventDefault();
            $popularAccessories.find('.group:last').slideToggle('slow');
            $(this).addClass('hide');
        });
    }

});

$.widgetize('overlayPopUpWindow', function(){
    /* OverlayPopUp trigger */
    $('.overlayPopUpWindowLink .details').live('click', function(e){
        e.preventDefault();
        $('.overlayPopUpWindow:visible').addClass('hide');
        var left = $(this).position().left + $(this).outerWidth() + 10;
        $(this).siblings('.overlayPopUpWindow').removeClass('hide').css('left', left);
    });
    $('.overlayPopUpWindow .overlayPopUpClose').live('click', function(){
        $(this).parent().addClass('hide');
    });
});

$.widgetize('servicePlanMiniContainer', function(){
    var $servicePlanMiniContainer = $(this);
    $(window).scroll(function(){
        var initTop = $servicePlanMiniContainer.siblings().offset().top - 35,
            topLimit = $servicePlanMiniContainer.siblings().outerHeight() - $servicePlanMiniContainer.outerHeight(),
            siblingHeight = $servicePlanMiniContainer.siblings().offset().top + $servicePlanMiniContainer.siblings().outerHeight(),
            currentHeight = $servicePlanMiniContainer.offset().top + $servicePlanMiniContainer.outerHeight();
        if($(window).scrollTop() > initTop && ( $(window).scrollTop() - initTop ) <= topLimit){
            $servicePlanMiniContainer.css('position','relative');
            $servicePlanMiniContainer.stop(true,false).animate({top: $(window).scrollTop() - initTop}, 'fast');
        } else if($(window).scrollTop() <= initTop){
            //move to top
            $servicePlanMiniContainer.stop(true,false).animate({top: 0}, 'fast');
        } else if(siblingHeight < currentHeight){
            //move to bottom
            var newTop = (topLimit < 0)?0:topLimit;
            $servicePlanMiniContainer.stop(true,false).animate({top: newTop}, 'fast');
        }
    });
});

$.widgetize('showcase', function(){
    var productHero = $(this).children('ul.product-hero');

    productHero.each(function(productIndex){
        var $productHeroList = $(this).children('li'),
            productName = $(this).attr('product-name'),
            threeSixtyIndex = 1,
            videoIndex = 1,
            imageIndex = 1;
        $productHeroList.each(function(listIndex){
            if($(this).children('a.container-360').length){
                var newAlt = productName+' 360 '+inputVariables.storeData.resources.text.VIEW+' '+threeSixtyIndex;
                $(this).children('a.container-360').attr('data-alt',newAlt);
                $(this).find('img.poster').attr('alt',newAlt);
                $('.product-thumbnails:eq('+productIndex+')').find('li:eq('+listIndex+') img').attr('alt',newAlt).attr('title',newAlt);
                threeSixtyIndex++;
            } else if($(this).children('div.video-container').length){
                var newAlt = productName+' '+inputVariables.storeData.resources.text.VIDEO+' '+videoIndex;
                $(this).find('div[data-class=poster]').attr('data-alt',newAlt);
                $(this).find('img.poster').attr('alt',newAlt);
                $('.product-thumbnails:eq('+productIndex+')').find('li:eq('+listIndex+') img').attr('alt',newAlt).attr('title',newAlt);
                videoIndex++;
            } else if($(this).children('img').length){
                var newAlt = productName+' '+inputVariables.storeData.resources.text.VIEW+' '+imageIndex;
                $(this).children('img').attr('alt',newAlt);
                $('.product-thumbnails:eq('+productIndex+')').find('li:eq('+listIndex+') img').attr('alt',newAlt).attr('title',newAlt);
                imageIndex++;
            } else if($(this).children('div.image-container').length){
                var newAlt = productName+' '+inputVariables.storeData.resources.text.VIEW+' '+imageIndex;
                $(this).children('div.image-container').attr('data-alt',newAlt);
                $(this).children('div.image-container').children('img').attr('alt',newAlt);
                $('.product-thumbnails:eq('+productIndex+')').find('li:eq('+listIndex+') img').attr('alt',newAlt).attr('title',newAlt);
                imageIndex++;
            }
        });
    });
});

// Address ordering on Confirm Order and TY Page
$.widgetize('addressSequenceDisplay', function(){
    var selectedCountry = $('.dr_confirmElement .country, .orderInformationElement .country').attr('data-country');
    var seqList = inputVariables.storeData.resources.shippingAddrSeq[selectedCountry];
    if(seqList){
        if(!seqList.match("Config_AddressSequence_")){
            var seqArray = seqList.split(',');
            var addrContent = [];
            $.each(seqArray,function(index,val){
                if(val=='Address1'){
                    addrContent.push($('.addrSeq .addr1').html(),'<br/>');
                }
                else if(val=='Address2' && $('.addrSeq .addr2').length){
                    addrContent.push($('.addrSeq .addr2').html(), '<br/>');
                }
                else if(val=='PostalCode'){
                    addrContent.push($('.addrSeq .postalCode').html(), '&nbsp;');
                }
                else if(val=='City'){
                    addrContent.push($('.addrSeq .city').html(), '&nbsp;');
                }
            });
            $('.addrSeq').html(addrContent.join(''));
        }
    }
});

$.widgetize('definingLevelTwo', function(){
    $('.definingLevelTwo li').live('click',function(){
        $(".product-hero .video-container:visible").pauseVideo();
        $('.definingLevelTwo li').removeClass('select');
        $('.definingLevelTwo li').removeClass('active');
        $(this).addClass('select active');
        var classToShow = $(this).data('contract'),
            classToHide = $(this).siblings().data('contract'),
            varPrevSelection = $('.definingLevelThree li.selected a').attr('selectedcolor');
        $('.definingLevelThree li').hide();
        $('.definingLevelThree').find('.'+classToShow).parent().eq(0).css('margin-left','0');
        $('.definingLevelThree').find('.'+classToShow).parent().not(':eq(0)').css('margin-left','4%');
        $('.definingLevelThree li:visible').filter(':nth-child(7n)').css('clear','none');
        $('.definingLevelThree').find('.'+classToShow).parent().show();
        $('.definingLevelThree li a').each(function(){
            if($(this).parent().css('display')!='none'){
                if($(this).attr('selectedcolor') === varPrevSelection){
                    if($('.definingLevelOne li').length===0){
                        $(this).click();
                    }
                }
            }
        });
        if($('.definingLevelOne li').length){
            var definingLevelOne = $('.definingLevelOne li.select').data('carrier');
            $('.definingLevelThree li').hide();
            $('.definingLevelThree').find("[selectedcarrier=" + definingLevelOne + "]").parent().show();
            $('.definingLevelThree').find('.'+classToHide).parent().hide();
            $('.definingLevelThree li a').each(function(){
                if($(this).parent().css('display')!='none'){
                    if($(this).attr('selectedcolor') === varPrevSelection){
                        $(this).click();
                    }
                }
            });
        }
        $('.definingLevelThree li:visible').each(function(idx){
            if(((idx+1)%7) == 0 || idx == 0){
                $(this).css({clear:'left',marginLeft:'0'});
            }else{
                $(this).css({clear:'none',marginLeft:'4%'});
            }
        });
        if($(this).data('contract') === 'withcontract'){
            $('.userSelectionMain').removeClass('hide');
        }else{
            $('.userSelectionMain').addClass('hide');
        }
        $('.definingLevelTwo li').closest('ul').prevAll('p:eq(0)').find('.selected-variation').text($.trim($('.definingLevelTwo li.select a').text()));
    });
    $('.definingLevelOne li').live('click',function(){
        $(".product-hero .video-container:visible").pauseVideo();
        $('.definingLevelOne li').removeClass('select');
        $(this).addClass('select');
        $('.definingLevelTwo li.select').trigger('click');
        $('.definingLevelTwo li.select a').trigger('click');
        $('.definingLevelOne li').closest('ul').prevAll('p:eq(0)').find('.selected-variation').text($.trim($('.definingLevelOne li.select a').text()));
    });
    $('.definingLevelOne li').first().trigger('click');
    $('.definingLevelTwo li').first().trigger('click');
});

$.widgetize('userSelection', function(){
    var activationTypeCookie = cookieObj.getCookie('activationType');
    var activationType = '';
    var overlayPopUpWrap = $('#overlayPopUpWrap');
    if(activationTypeCookie){
        $('.userSelection:eq(0) li:eq(1)').remove();
    }
    $('.userSelection:eq(0) li').live('click',function(){
        $('.userSelection:eq(0) li').removeClass('select');
        $('.userSelection:eq(0) li').removeClass('active');
        $(this).addClass('select active');
        if($(this).data('usertype') === 'existing'){
            $('.addOrUpgrade').show();
            $('.userSelection:eq(1)').show();
            $('.userSelection:eq(1) li:eq(0)').click();
        }else{
            $('.addOrUpgrade').hide();
            $('.userSelection:eq(1)').hide();
            activationType = "New";
        }
        $('.userSelection:eq(0) li').closest('ul').prevAll('p:eq(0)').find('.selected-variation').text($.trim($('.userSelection:eq(0) li.select a').text()));
    });
    $('.userSelection:eq(1) li').live('click',function(){
        $('.userSelection:eq(1) li').removeClass('select');
        $('.userSelection:eq(1) li').removeClass('active');
        $(this).addClass('select active');
        $('.userSelection:eq(1) li').closest('ul').prevAll('p:eq(0)').find('.selected-variation').text($.trim($('.userSelection:eq(1) li.select a').text()));
        if($(this).data('usertype') === 'addline'){
            activationType = "AddDevice";
        }else{
            activationType = "Upgrade";
        }
    });
    $('.userSelection:eq(0) li:eq(0)').click();
    $('.buyBtn_AddtoCart').live('click',function(event){
        if($('.definingLevelTwo li:eq(0)').hasClass("active")){
            if(activationTypeCookie){
                if(activationTypeCookie == "Upgrade" || activationTypeCookie == "AddDevice"){
                    if($('.userSelection:eq(0) li:eq(1)').length == 0){
                        $.magnificPopup.open({
                            items: {
                                src: '<div class="pdp-ma-error">Only one phone can be upgraded/added to a line at a time.</div>',
                                type: 'inline'
                            }
                        });
                        event.preventDefault();
                    }else{
                        cookieObj.setPathCookie('activationType',activationType);
                    }
                }else{
                    cookieObj.setPathCookie('activationType',activationType);
                }
            }else{
                cookieObj.setPathCookie('activationType',activationType);
            }
        }
    });

    $('#overlayPopUpWrap .overlayPopUpClose .closeButton').live('click', function(){
        hideOverlayPopUp();
    });

    function hideOverlayPopUp(){
        overlayPopUpWrap.fadeTo("fast",0, function() {
            $(this).hide();
        });
    }
    if (window.location.search.indexOf('cancel=true') > -1) {
        $('.definingLevelTwo:eq(0) li:eq(1)').click();
    }
});

$.widgetize('MobileActivationsPlanPage', function(){
    var overlayPopUpWrap = $('#overlayPopUpWrap'),
        $PlanFeatureSelectForm = $('form[name="PlanFeatureSelectForm"]');
    var activate = {},
        isIE = (window.navigator.appName === "Microsoft Internet Explorer"),
        POLL_INTERVAL_VALUE = 3,
        POLL_INTERVAL;
    function submiterror(planFeatureData){
        var SiteID = $('form[name=PlanFeatureSelectForm] input[name=SiteID]'),
            urlcct = "/store/",
            Locale = $('form[name=PlanFeatureSelectForm] input[name=Locale]'),
            CSRFAuthKey = $('form[name=PlanFeatureSelectForm] input[name=CSRFAuthKey]'),
            data = 'Action=PostMobileActivationsErrorPage&SiteID=' + SiteID.val() +
                '&Locale=' + Locale.val() +
                '&Form=' + 'com.digitalriver.mobileactivations.bby.form.PlanFeatureSelectForm' +
                '&ORIG_VALUE_MA_BBY_IFRAME_PARM=' + '' +
                '&MA_BBY_IFRAME_PARM=' + JSON.stringify(planFeatureData) +
                '&CSRFAuthKey=' + encodeURIComponent(CSRFAuthKey.val()) +
                '&CallingPageID=' + 'MobileActivationsPlanPage';
        if(inputVariables.storeData.page.cctflow){
            urlcct = "https://gc.digitalriver.com/store/";
        }
        $.ajax({
            url: urlcct,
            type: "POST",
            data: data,
            cache: false,
            success: function (html) {
                //success
            }
        });
    }

    activate.submitPlanAndFeatureSelection = function (planFeatureData) { // used by BB page to submit results back from plan selection
        //console.log('plan selection received data from bb: ', planFeatureData);
        if (!planFeatureData.errorCode) {
            var $jsonobj = planFeatureData;
            $jsonobj = JSON.stringify($jsonobj);
            //console.log('plan selection received data from bb converted to string being posted: ', $jsonobj);
            $('input[name="MA_BBY_IFRAME_PARM"]').val($jsonobj);
            $('input[name="MA_BBY_ACTIVATION_TYPE_PARM"]').val(cookieObj.getCookie('activationType'));
            if(inputVariables.storeData.page.cctflow){
                $('form[name="PlanFeatureSelectForm"]').attr('action','https://gc.digitalriver.com/store/');
            }
            $('form[name="PlanFeatureSelectForm"]').submit();
        }else{
            var arr = ["002","003","004","005","006","007","008","015","017"];
            if($.inArray(planFeatureData.errorCode,arr)!=-1){
                $('.whiteLabelerrors').removeClass('hide');
            }else{
                $('.iframeErrors').removeClass('hide');
                $('.iframeErrors .box.blue').attr("href",$('.closeButton').attr('href'));
                $("#overlayPopInnerContent p#MA_BBY_error").html(inputVariables.storeData.resources.text['MA_BBY_'+planFeatureData.errorCode]).removeClass("hide");
            }
            overlayPopUpWrap.not(":visible").fadeTo("fast",1);
            submiterror(planFeatureData);
        }
    };

    $('#overlayPopUpWrap .overlayPopUpClose .closeButton').live('click', function(){
        hideOverlayPopUp();
    });

    function hideOverlayPopUp(){
        //overlayPopUpWrap.fadeTo("fast",0, function() {
        var catUrl = $('#overlayPopUpWrap .overlayPopUpClose .closeButton').attr('href');
        window.location = catUrl;
        //$(this).hide();
        //});
    }

    submitPlanFeatureSummaryData = function(planFeatureSummaryData){
        //console.log('plan selection summary data from bb: ', planFeatureSummaryData);
    };

    activate.notifyErrorOccurred = function (planFeatureData) {
        var arr = ["002","003","004","005","006","007","008","015","017"];
        if($.inArray(planFeatureData.errorCode,arr)!=-1){
            $('.whiteLabelerrors').removeClass('hide');
        }else{
            $('.iframeErrors').removeClass('hide');
            $('.iframeErrors .box.blue').attr("href",$('.closeButton').attr('href'));
            $("#overlayPopInnerContent p#MA_BBY_error").html(inputVariables.storeData.resources.text['MA_BBY_'+planFeatureData.errorCode]).removeClass("hide");
        }
        overlayPopUpWrap.not(":visible").fadeTo("fast",1);
        submiterror(planFeatureData);
    };

    activate.pollIFrame = function(start) {
        if(POLL_INTERVAL) {
            clearInterval(POLL_INTERVAL);
        }
        if(start) {
            POLL_INTERVAL = setInterval(resizeFrame, POLL_INTERVAL_VALUE * 1000);
        }
    };

    activate.childReady = function () {
        submitDataWithLineIdAndUpc();
    };

    function resizeFrame() {
        var $f = $('#whitelabelIframe'),
            iframe = $f.get(0),
            d;
        if($f.length === 0) {
            clearInterval(POLL_INTERVAL);
        } else {
            try {
                d = $f.get(0).contentWindow.window.document;
                if(d) {
                    //$f.height(0);
                    $f.height($(d).outerHeight());
                    if(isIE) {
                        $f.get(0).scrolling = "no";
                        $f.css('overflow', 'hidden');
                    }
                }
            }catch(e) {
                $f.get(0).scrolling = "yes";
            }
        }
    }

    function submitDataWithLineIdAndUpc() {
        //console.log('call plan and feature iframe');
        var frame = document.getElementById('whitelabelIframe').contentWindow;
        var activationService = frame.WL.ActivationService;
        var plansAndOptionsRequest = new activationService.PlanAndFeaturesRequest();
        var ActivationType = activationService.ActivationType;
        var CarrierCode = activationService.CarrierCode;
        var VendorProfileCode = frame.WL.VendorProfileCode;
        //console.log("activationType cookieObj : " + cookieObj.getCookie('activationType'));
        plansAndOptionsRequest.activationType = cookieObj.getCookie('activationType');
        //plansAndOptionsRequest.vendorProfileCode = frame.WL.VendorProfileCode.att;
        //console.log("deviceInfo for plan feature: " + JSON.stringify(inputVariables.storeData.mobile.deviceInfo));
        plansAndOptionsRequest.deviceInfo = inputVariables.storeData.mobile.deviceInfo;
        var response = activationService.enterPlanAndFeatureSelection(plansAndOptionsRequest);

        if (activationService.ResponseStatusCode.SUCCESS == response.status) {
            //console.log('started bby request ', response);
        } else {
            //console.log('unable to start bby request ', response);
            $('.iframeErrors').removeClass('hide');
            $('.iframeErrors .box.blue').attr("href",$('.closeButton').attr('href'));
            $("#overlayPopInnerContent p#MA_BBY_error").html(inputVariables.storeData.resources.text['MA_BBY_001']).removeClass("hide");
            overlayPopUpWrap.not(":visible").fadeTo("fast",1);
        }
    }

    var WL = window.WL = {};
    WL.ActivationService = activate;
    return activate;
});

$.widgetize('MobileActivationsPreActivatePage', function(){
    var overlayPopUpWrap = $('#overlayPopUpWrap'),
        $PreActivateForm = $('form[name="PreActivateForm"]');
    var activate = {},
        isIE = (window.navigator.appName === "Microsoft Internet Explorer"),
        POLL_INTERVAL;

    function submiterror(PreactivationData){
        var SiteID = $('form[name=PreActivateForm] input[name=SiteID]'),
            urlcct = "/store/",
            Locale = $('form[name=PreActivateForm] input[name=Locale]'),
            CSRFAuthKey = $('form[name=PreActivateForm] input[name=CSRFAuthKey]'),
            data = 'Action=PostMobileActivationsErrorPage&SiteID=' + SiteID.val() +
                '&Locale=' + Locale.val() +
                '&Form=' + 'com.digitalriver.mobileactivations.bby.form.PreActivateForm' +
                '&ORIG_VALUE_MA_BBY_IFRAME_PARM=' + '' +
                '&MA_BBY_IFRAME_PARM=' + JSON.stringify(PreactivationData) +
                '&CSRFAuthKey=' + encodeURIComponent(CSRFAuthKey.val()) +
                '&CallingPageID=' + 'MobileActivationsPreActivatePage';
        if(inputVariables.storeData.page.cctflow){
            urlcct = "https://gc.digitalriver.com/store/";
        }
        $.ajax({
            url: urlcct,
            type: "POST",
            data: data,
            cache: false,
            success: function (html) {
                //success
            }
        });
    }

    submitPreactivationData = function (PreactivationData) { // used by BB page to submit results back from plan selection
        //console.log('plan PreactivationData received data from bb: ', PreactivationData);
        if (!PreactivationData.errorCode) {
            var $jsonobj = PreactivationData;
            $jsonobj = JSON.stringify($jsonobj);
            //console.log('plan PreactivationData received data from bb converted to string being posted: ', $jsonobj);
            $('input[name="MA_BBY_IFRAME_PARM"]').val($jsonobj);
            if(inputVariables.storeData.page.cctflow){
                $('form[name="PreActivateForm"]').attr('action','https://gc.digitalriver.com/store/');
            }
            $('form[name="PreActivateForm"]').submit();
        }else{
            var arr = ["002","003","004","005","006","007","008","015","017"];
            if($.inArray(PreactivationData.errorCode,arr)!=-1){
                $('.whiteLabelerrors').removeClass('hide');
            }else{
                $('.iframeErrors').removeClass('hide');
                $('.iframeErrors .box.blue').attr("href",$('.closeButton').attr('href'));
                $("#overlayPopInnerContent p#MA_BBY_error").html(inputVariables.storeData.resources.text['MA_BBY_'+PreactivationData.errorCode]).removeClass("hide");
            }
            overlayPopUpWrap.not(":visible").fadeTo("fast",1);
            submiterror(PreactivationData);
        }
    };

    $('#overlayPopUpWrap .overlayPopUpClose .closeButton').live('click', function(){
        hideOverlayPopUp();
    });

    function hideOverlayPopUp(){
        //overlayPopUpWrap.fadeTo("fast",0, function() {
        var catUrl = $('#overlayPopUpWrap .overlayPopUpClose .closeButton').attr('href');
        window.location = catUrl;
        //$(this).hide();
        //});
    }

    submitPlanFeatureSummaryData = function(planFeatureSummaryData){
        //console.log('plan selection summary data from bb: ', planFeatureSummaryData);
    };

    activate.notifyErrorOccurred = function (PreactivationData) {
        var arr = ["002","003","004","005","006","007","008","015","017"];
        if($.inArray(PreactivationData.errorCode,arr)!=-1){
            $('.whiteLabelerrors').removeClass('hide');
        }else{
            $('.iframeErrors').removeClass('hide');
            $('.iframeErrors .box.blue').attr("href",$('.closeButton').attr('href'));
            $("#overlayPopInnerContent p#MA_BBY_error").html(inputVariables.storeData.resources.text['MA_BBY_'+PreactivationData.errorCode]).removeClass("hide");
        }
        overlayPopUpWrap.not(":visible").fadeTo("fast",1);
        submiterror(PreactivationData);
    };

    activate.pollIFrame = function(start) {
        if(POLL_INTERVAL) {
            clearInterval(POLL_INTERVAL);
        }
        if(start) {
            POLL_INTERVAL = setInterval(resizeFrame, 3 * 1000);
        }
    };

    activate.childReady = function () {
        submitDataWithLineIdAndUpc();
        submitDataWithLineIdAndUpcSmall();
    };

    function resizeFrame() {
        $.each([ '#whitelabelIframe', '#whitelabelSmallIframe' ], function( index, value ) {
            var $f = $(value), iframe = $f.get(0), d;
            if($f.length === 0) {
                clearInterval(POLL_INTERVAL);
            } else {
                try {
                    d = $f.get(0).contentWindow.window.document;
                    if(d) {
                        //$f.height(0);
                        $f.height($(d).outerHeight());
                        if(isIE) {
                            $f.get(0).scrolling = "no";
                            $f.css('overflow', 'hidden');
                        }
                    }
                }catch(e) {
                    $f.get(0).scrolling = "yes";
                }
            }
        });
    }

    function submitDataWithLineIdAndUpc() {
        //console.log('call preactivate iframe');
        try{
            // Get the iframe
            var checkoutWindow = document.getElementById('whitelabelIframe').contentWindow;
            var activationService = checkoutWindow.WL.ActivationService;

            // Create preactivation entry data
            var request = new activationService.PreactivationFlowEntryRequest();
            //console.log("deviceInfo for preactivate: " + JSON.stringify(inputVariables.storeData.mobile.deviceInfo));
            if(inputVariables.storeData.page.cctflow){
                request.partnerChannelId = 'CallCTR';
            }
            request.deviceInfo = inputVariables.storeData.mobile.deviceInfo;
            if(inputVariables.storeData.page.cctflow){
            }else{
                if(inputVariables.storeData.mobile.customerInfo){
                    var phoneNumber = inputVariables.storeData.mobile.customerInfo.phone;
                    if(phoneNumber.length != 10){
                        inputVariables.storeData.mobile.customerInfo.phone = '';
                    }
                }
            }
            request.customerInfo = inputVariables.storeData.mobile.customerInfo; // Optional
            // Submit data
            response = activationService.enterPreactivationFlow(request);

            if (activationService.ResponseStatusCode.SUCCESS == response.status) {
                //console.log('started bby request ', response);
            }

            if (activationService.ResponseStatusCode.FAILURE == response.status) {
                //console.log('unable to start bby request ', response);
                $('.iframeErrors').removeClass('hide');
                $('.iframeErrors .box.blue').attr("href",$('.closeButton').attr('href'));
                $("#overlayPopInnerContent p#MA_BBY_error").html(inputVariables.storeData.resources.text['MA_BBY_001']).removeClass("hide");
                overlayPopUpWrap.not(":visible").fadeTo("fast",1);
            }
        }catch(error){
            //console.log('Error:' + error.description);
        }
    }

    function submitDataWithLineIdAndUpcSmall (){
        //console.log('call summary iframe');
        var checkoutWindowsmall = document.getElementById('whitelabelSmallIframe').contentWindow;
        var activationService = checkoutWindowsmall.WL.ActivationService;

        // Create request
        var request = new activationService.PlanFeatureSummaryEntryRequest();
        //console.log("deviceInfo for summary iframe: " + JSON.stringify(inputVariables.storeData.mobile.deviceInfo));
        request.deviceInfo = inputVariables.storeData.mobile.deviceInfo;
        request.displaySize = 'small'; // Or large, which ever iFrame you are wanting

        //initialize interface
        result = activationService.enterPlanFeatureSummary(request);
    }

    var WL = window.WL = {};
    WL.ActivationService = activate;
    return activate;
});

$.widgetize('subsBillingCountries', function(){
    $('select#subsBillingCountries').change(function(){
        $('.subsError').remove();
        $('#subsBillingCountries').css('border','1px solid #999999');
        var subsBillingCountries = $(this).val(),
            isEuro = $("#subsBillingCountries option:selected").attr('iseuro');
        $.ajax({
            url: inputVariables.storeData.actionName.SetSubsBillingCountryCode + '/subsBillingCountries.' + subsBillingCountries
        });
        if(isEuro==='false'){
            if($('#dr_ThreePgCheckoutShoppingCart,body.CallCenterToolShoppingCartPage').attr('data-issubsincart') === 'true'){
                if($('#dr_ThreePgCheckoutShoppingCart').attr('data-issubsmixedincart') ==='true'){
                    $('.cart-header,.shoppingCartForm').eq(0).before('<span class="dr_error subsError">' + inputVariables.storeData.resources.text.MARKET_NOT_SUPPORTED + inputVariables.storeData.resources.text.MIXED_CART_MARKET_NOT_SUPPORTED + '</span>');
                }else{
                    $('.cart-header,.shoppingCartForm').eq(0).before('<span class="dr_error subsError">'+ inputVariables.storeData.resources.text.MARKET_NOT_SUPPORTED + '</span>');
                }
            }
        }
    });
    $('select#subsBillingCountries').trigger('change');
    if($('#subsBillingCountries').length){
        $('a.checkout').bind('click',function(event){
            if($('#subsBillingCountries').val()==''){
                $('#subsBillingCountries').css('border','1px solid #FF0000');
                event.preventDefault();
            }else if($('.dr_error.subsError').length){
                event.preventDefault();
            }else{
                $('#subsBillingCountries').css('border','1px solid #999999');
            }
        });
    }
});

//ProductPickerPage
$.widgetize('ProductPickerPage', function(){
    var grpID, lastGroup = 0,scrollSec = false; showWholecart = false;
    var isGrabBin = Boolean($('#product-picker').attr('data-grabbin'));
    var bundlePID = $('#product-picker').attr('data-bundlepid');
    var grabBinVal = isGrabBin ? '&bundlePID='+bundlePID : '';
    var seqArr = [];
    function addProduct(jDataAdd, jDataEdit, editCall, $curSection, removeCall, $selectBtn) {
        doActionAndSubmit("addProduct", false, jDataAdd, jDataEdit, editCall, $curSection, removeCall, $selectBtn);
    }
    function submitForm() {
        doActionAndSubmit("submitForm");
    }
    function doActionAndSubmit(action, noSpinner, jDataAdd, jDataEdit, editCall, $curSection, removeCall, $selectBtn) {
        var section = 'body';
        if(action == "submitForm") {
            $('form[name="ProductPickerForm"] input[name="operation"]').val(action);
            document.ProductPickerForm.submit();
        }
        else {
            if (!noSpinner) {
                $('.prevent-click-overlay').removeClass('hide');
            }
            if(editCall){
                $.ajax({
                    type: "POST",
                    url: '/store/'+inputVariables.storeData.page.siteid+'/'+inputVariables.storeData.page.locale+'/DisplayPage/id.ProductPickerAggregateXMLDoc',
                    data: jDataEdit,
                    success: function(){
                        $.ajax({
                            type: "POST",
                            url: '/store/'+inputVariables.storeData.page.siteid+'/'+inputVariables.storeData.page.locale+'/DisplayPage/id.ProductPickerAggregateXMLDoc',
                            data: jDataAdd,
                            success: function(data){
                                reloadBundle(data, $curSection, removeCall, $selectBtn);
                            },
                            complete: function(){
                                $('.prevent-click-overlay').addClass('hide');
                                $selectBtn.parent().removeClass('loading');
                                if(typeof(removeCall) === 'undefined'){
                                    $selectBtn.parents('.product').addClass('selected');
                                }else if(typeof(removeCall) !== 'undefined'){
                                    if(removeCall){
                                        $selectBtn.parents('.product').removeClass('selected');
                                    }
                                }
                            }
                        });
                    }
                });
            }
            else{
                $.ajax({
                    type: "POST",
                    url: '/store/'+inputVariables.storeData.page.siteid+'/'+inputVariables.storeData.page.locale+'/DisplayPage/id.ProductPickerAggregateXMLDoc',
                    data: jDataAdd,
                    success: function(data){
                        reloadBundle(data, $curSection, removeCall, $selectBtn);
                    },
                    complete:function(){
                        $('.prevent-click-overlay').addClass('hide');
                        $selectBtn.parent().removeClass('loading');
                        $selectBtn.parents('.product').addClass('selected');
                    }
                });
            }
        }
    }
    function reloadBundle(data, $curSection, removeCall, $selectBtn){
        var cartSectionIndex = data.indexOf('<div id="cartSection"');
        var dataTemp = data.substring(cartSectionIndex,data.indexOf('</form>',cartSectionIndex));
        var $data = $(dataTemp);
        var dataPID = $selectBtn.attr('data-productid');
        var groupID = $selectBtn.parents('.category-products-wrapper').attr('data-groupid');
        var grabBinMaxQty = $curSection.attr('data-maxQty');
        var rearrangeProds = [];

        $('#'+groupID+' input[name=quantity_'+groupID+'_'+dataPID+']').val('1');
        if($.trim($data.text())){
            $('#cartSection').addClass('active');
            $('#cartSection .product-corral').html($('.product-corral',$data).html());
            if(isGrabBin && !removeCall){
                if($.inArray(dataPID, seqArr) != -1 ){
                    seqArr = jQuery.grep(seqArr, function(value) {
                        return value != dataPID;
                    });
                }
                seqArr.push(dataPID);
            }
            else if(isGrabBin && removeCall) {
                if(!$('#cartSection .product-corral .product-slot[data-productID = '+dataPID+']').length){
                    seqArr = jQuery.grep(seqArr, function(value) {
                        return value != dataPID;
                    });
                }
            }
            if(seqArr.length == 1){
                showCorral();
            }
        }
        else{
            seqArr = [];
            $('#cartSection .product-corral').html($('.product-corral',$data).html());
        }

        if(isGrabBin){
            if(seqArr.length > 1){
                for(i in seqArr){
                    var $firstEle = $('#cartSection .product-corral .product-slot').eq(0);
                    var firstProd = $firstEle.attr('data-productid');
                    var $eleToSwitch = $('#cartSection .product-corral .product-slot[data-productid = '+seqArr[i]+']');
                    if(!firstProd.match(seqArr[i])){
                        $eleToSwitch.insertBefore($firstEle);
                    }
                }
            }
            $('#cartSection .product-corral .product-slot').each(function(){
                var itemQty = $(this).attr('data-quantity');
                for(i = 1; i < itemQty; i++){
                    $(this).before($(this).clone());
                }
            });
            if($('#cartSection .product-corral .product-slot.occupied').length == grabBinMaxQty){
                $curSection.find('.select-product').addClass("disabled");
                $('#cartSection .cart-submit input').removeClass('ms-hidden');
                $('#cartSection .cart-submit span').addClass('hide');
                showCorral();
            }
            else{
                $curSection.find('.select-product').removeClass("disabled");
            }
        }
        else{
            if($('#cartSection .product-slot').not('.occupied').length == 0){
                $('#cartSection .cart-submit input').removeClass('ms-hidden');
                $('#cartSection .cart-submit span').addClass('hide');
                showCorral();
            }
            if($('#cartSection .product-slot.occupied').length == 1){
                showCorral();
            }
        }

        $('#cartSection .product-slot.occupied').on('click', function(){
            var removeProd = true;
            var pid = $(this).attr('data-productid');
            var $selectBtn = $('.select-product[data-productid="'+pid+'"]');
            $par = $(this);
            $par.addClass('removing');
            var dataObj = collectAjaxInformation($selectBtn,grpID,lastGroup,removeProd);
            grpID = dataObj.gID;
            lastGroup = dataObj.lGroup;
            scrollSec = false;
            return false;
        });
    }
    function showCorral(){
        $('#cartSection .product-corral').addClass('full-height');
        $('#cartSection .icon-circledown').addClass('expanded');
    }
    function collectAjaxInformation($selectBtn,grpID,lastGroup,removeProd){
        scrollSec = false;
        var $section = $selectBtn.parents('.category-products-wrapper');
        var groupID = $section.attr('data-groupID'),
            minQty = $section.attr('data-minqty'),
            maxQty = $section.attr('data-maxqty'),
            group =  $section.attr('data-group') - 0,
            dataType = $section.attr('data-type');
        var thisProdID = $selectBtn.attr('data-productid'),
            selectedProducts = '',
            qunatities = '',
            removeProdID = '';
        $section.find('.select-product').each(function(){
            var $selBtn = $(this);
            var prodID = $selBtn.attr('data-productid');
            var qtyAttr = 'quantity_'+groupID+'_'+prodID;
            selectedProducts = selectedProducts + '&selectedProduct='+groupID+'_'+prodID;
            if(isGrabBin){
                var $bundleItem = $('#cartSection .product-slot[data-productid = '+prodID+']');
                var prodQty = $bundleItem.length ? Number($bundleItem.attr('data-quantity')) : 0;
                if(thisProdID == prodID){
                    if(removeProd){
                        if(prodQty == 1){
                            $selBtn.removeClass('selected');
                        }
                        qunatities = qunatities + '&' + qtyAttr + '=' + Number(prodQty - 1);
                        removeProdID = thisProdID;
                    }
                    else{
                        $selBtn.addClass('selected');
                        qunatities = qunatities + '&' + qtyAttr + '=' + Number(prodQty + 1);
                    }
                }
                else{
                    if($selBtn.hasClass('selected')){
                        qunatities = qunatities + '&' + qtyAttr + '=' + prodQty;
                    }
                    else{
                        qunatities = qunatities + '&' + qtyAttr + '=' + 0;
                    }
                }
            }
            else{
                if (minQty==1 && maxQty==1){
                    if(thisProdID == prodID){
                        if(removeProd){
                            $selBtn.removeClass('selected');
                            qunatities = qunatities + '&' + qtyAttr + '=' + 0;
                            removeProdID = thisProdID;
                        }
                        else{
                            $selBtn.addClass('selected');
                            qunatities = qunatities + '&' + qtyAttr + '=' + 1;
                        }
                    }
                    else{
                        qunatities = qunatities + '&' + qtyAttr + '=' + 0;
                        $selBtn.removeClass('selected');
                    }
                }
                if ((minQty==0 && maxQty > 0) || (minQty > 0 && maxQty > 1)){
                    if(thisProdID == prodID){
                        if(removeProd){
                            $selBtn.removeClass('selected');
                            qunatities = qunatities + '&' + qtyAttr + '=' + 0;
                            removeProdID = thisProdID;
                        }
                        else{
                            $selBtn.addClass('selected');
                            qunatities = qunatities + '&' + qtyAttr + '=' + 1;
                        }
                    }
                    else{
                        if($selBtn.hasClass('selected')){
                            qunatities = qunatities + '&' + qtyAttr + '=' + 1;
                        }
                        else{
                            qunatities = qunatities + '&' + qtyAttr + '=' + 0;
                        }
                    }
                }
            }
        });
        var enableSec = false;
        $('.category-products-wrapper').each(function(){
            var $sec = $(this);
            var groupType = $sec.attr('data-type');
            var grpMinQty = $sec.attr('data-minqty');
            if(groupType.match('MANDATORY') && $sec.find('.select-product.selected').length == grpMinQty){
                enableSec = true;
            }
            else if(groupType.match('MANDATORY') && $sec.find('.select-product.selected').length != grpMinQty){
                enableSec = false;
            }
        });
        if(enableSec){
            $('.category-products-wrapper').removeClass("disableSection");
        }
        else{
            if($section.find('.select-product.selected').length == minQty){
                $section.next().removeClass("disableSection");
            }
        }
        if($section.find('.select-product.selected').length == maxQty && !isGrabBin){
            $section.find('.select-product').addClass("disabled");
            scrollSec = true;
        }
        else if(!isGrabBin){
            $section.find('.select-product').removeClass("disabled");
        }
        var editCall = false;
        if(lastGroup > group || lastGroup == group || removeProd){
            editCall = true;
        }
        grpID = !grpID ? groupID : grpID;
        var jDataAdd = 'CallingPageID=ProductPickerPage&Form=com.digitalriver.template.form.ProductPickerForm&ORIG_VALUE_operation=update&operation=addProducts&currentEditID=' + grpID + '&groupInstanceID=' + groupID + selectedProducts + qunatities + '&removeID=' + removeProdID + '&productID_'+thisProdID+'='+thisProdID + grabBinVal;
        var jDataEdit = 'CallingPageID=ProductPickerPage&Form=com.digitalriver.template.form.ProductPickerForm&ORIG_VALUE_operation=&operation=changeEditGroup&currentEditID=' + grpID + '&groupInstanceID=' + groupID + selectedProducts + qunatities + '&removeID=' + removeProdID + '&productID_'+thisProdID+'='+thisProdID + grabBinVal;
        addProduct(jDataAdd, jDataEdit, editCall, $section, removeProd, $selectBtn);
        return {'lGroup':group, 'gID':groupID}
    }
    //select bundle group product
    $('.select-product').removeAttr("onclick");
    $('.select-product').on('click', function(){
        var $selectBtn = $(this);
        if(!$selectBtn.hasClass('selected') || isGrabBin){
            if(!$selectBtn.hasClass('disabled')){
                var dataObj = collectAjaxInformation($selectBtn,grpID,lastGroup);
                grpID = dataObj.gID;
                lastGroup = dataObj.lGroup;
                $selectBtn.parent().addClass('loading');
            }
        }
        return false;
    })
});

$.widgetize('gallery-overlay', function(){
    var urlAjax = '';
    $('.grid-container',$(this)).each(function(){
        var productID = $(this).find('.model-detail').attr('data-pid'),
            galleryIndex = $(this).find('.elp-gallery-item').attr('data-variation-index');
        if(productID){
            urlAjax = '/store/' + inputVariables.storeData.page.siteid + '/' + inputVariables.storeData.page.locale + '/DisplayFeaturedItemGalleryPage/productID.' + productID + '/galleryIndex.' + galleryIndex;
        }
        if(urlAjax != ''){
            $.ajax({
                url: urlAjax,
                dataType: 'html',
                cache: true
            }).done(function(data){
                var returnIndex = $(data).filter('.elp-gallery-source').attr('data-variation-index');
                $('.elp-gallery-source:eq('+(parseInt(returnIndex)-1)+') .zoom-anim-dialog').html($(data).filter('.elp-gallery-source').find('.zoom-anim-dialog').html());
            })
        }
    });
});

//Prevent default button events
$.widgetize('disabled-button', function(){
    $(this).click(function(e){
        e.preventDefault();
        if($('.target-error').length){
            $('html, body').stop(true,false).animate({
                scrollTop: $('.target-error').eq(0).offset().top
            }, 500);
        }
    });
});

$.widgetize('ThreePgCheckoutShoppingCartPage', function(){
    $(document).find('a').each(function(){
        if(!$(this).hasClass('keepLink')){
            var hrefString = String($.trim($(this).attr('href')));
            if(hrefString.indexOf("https")===0){
                var securelink = 'true';
            }else{
                var securelink = 'false';
            }
            hrefString = hrefString.substring(hrefString.indexOf("/store/"));
            var aText = $(this).html().replace(/ |_|-/g,''),
                RegExp1 = /\/store\//,
                newHrefString = '';

            if(hrefString.match(RegExp1)){
                if(securelink === 'false'){
                    newHrefString = 'http://www.microsoftstore.com' + hrefString;
                }else{
                    newHrefString = 'https://www.microsoftstore.com' + hrefString;
                }
                $(this).attr('href',newHrefString);
            }
        }
    });
    if($('#dr_ThreePgCheckoutShoppingCart').attr('data-isbbymobileincart')){
        if($('#dr_ThreePgCheckoutShoppingCart').attr('data-isbbymobileincart') === 'false'){
            cookieObj.deleteCookie('activationType');
        }
    }else{
        cookieObj.deleteCookie('activationType');
    }
    var activationTypeCookie = cookieObj.getCookie('activationType');
    if(activationTypeCookie){
        if(activationTypeCookie == "Upgrade" || activationTypeCookie == "AddDevice"){
            $('.cart-item.addAnotherDevice').hide();
        }
    }
    if($('select#subsBillingCountries').length === 0){
        if($('#dr_ThreePgCheckoutShoppingCart').attr('data-issubsincart')){
            if($('#dr_ThreePgCheckoutShoppingCart').attr('data-issubsincart') === 'true'){
                $.ajax({
                    url: inputVariables.storeData.actionName.SetSubsBillingCountryCode + '/subsBillingCountries.' + inputVariables.storeData.page.mktp
                });
            }
        }
    }
});

$.widgetize('ProductDetailsPage', function(){
    $(document).find('a').each(function(){
        if(!$(this).hasClass('keepLink')){
            var hrefString = String($.trim($(this).attr('href')));
            if(hrefString.indexOf("microsoftstore.com")>-1 || hrefString.indexOf("//")===-1){
                if(hrefString.indexOf("https")===0){
                    var securelink = 'true';
                }else{
                    var securelink = 'false';
                }
                hrefString = hrefString.substring(hrefString.indexOf("/store/"));
                var aText = $(this).html().replace(/ |_|-/g,''),
                    RegExp1 = /\/store\//,
                    newHrefString = '';
                if(hrefString.match(RegExp1)){
                    if(securelink === 'false'){
                        newHrefString = 'http://www.microsoftstore.com' + hrefString;
                    }else{
                        newHrefString = 'https://www.microsoftstore.com' + hrefString;
                    }
                    $(this).attr('href',newHrefString);
                }
            }
        }
    });
});

$.widgetize('MobileProductDetailsPage', function(){
    $(document).find('a').each(function(){
        if(!$(this).hasClass('keepLink')){
            var hrefString = String($.trim($(this).attr('href')));
            if(hrefString.indexOf("microsoftstore.com")>-1 || hrefString.indexOf("//")===-1){
                if(hrefString.indexOf("https")===0){
                    var securelink = 'true';
                }else{
                    var securelink = 'false';
                }
                hrefString = hrefString.substring(hrefString.indexOf("/store/"));
                var aText = $(this).html().replace(/ |_|-/g,''),
                    RegExp1 = /\/store\//,
                    newHrefString = '';
                if(hrefString.match(RegExp1)){
                    if(securelink === 'false'){
                        newHrefString = 'http://www.microsoftstore.com' + hrefString;
                    }else{
                        newHrefString = 'https://www.microsoftstore.com' + hrefString;
                    }
                    $(this).attr('href',newHrefString);
                }
            }
        }
    });
});

$.widgetize('site-flow', function(){
    /*if($('body').hasClass('dr_site_msus') || $('body').hasClass('dr_site_mslatam') || $('body').hasClass('dr_site_msapac') || $('body').hasClass('dr_site_msmea')){
     var footerMarket = '<span class="icon-globe"></span>' + inputVariables.storeData.resources.text.footerMarketSelector;
     $('.locale-selector').html(footerMarket);
     $.getJSON('/store/' + inputVariables.storeData.page.siteid + '/' + inputVariables.storeData.page.locale + '/DisplayFooterMarketSelectorPage/jsonCallback=?', function(footerMarketJsonData){
     $('.locale-selector').html('<span class="icon-globe"></span>' + footerMarketJsonData.footerMarketAjax);
     });
     }*/
});

$.widgetize('checkoutFormAutoSubmit', function(){
    if(inputVariables.storeData.page.siteid == 'mseea' || inputVariables.storeData.page.siteid == 'mseea1' || inputVariables.storeData.page.siteid == 'msuk' || inputVariables.storeData.page.siteid == 'msde' || inputVariables.storeData.page.siteid == 'msfr'){
        $('select#shippingAddressBook').change();
        var shippingCountry = $('[name=SHIPPINGcountry]').val();
        $.ajax({
            url: '/Storefront/Site/mscommon/cm/multimedia/js/dr-CrossBorderMapping_16.js', //holds the mapping to the form fields
            dataType: 'json',
            cache: true,
            success: function(data){
                $.each(data.COUNTRYinfoModal, function(){
                    datacon = this;
                    if(datacon.COUNTRYinfo.shippingCountry === shippingCountry){
                        mappingExistence = true;
                        if(datacon.COUNTRYinfo.shippingState === 'false'){
                            $('.checkoutFormAutoSubmit .state-container').remove();
                        } else {
                            var shippingStateOptions = datacon.COUNTRYinfo.shippingStateOptions;
                            $('select#shippingState').html(shippingStateOptions);
                            $('#shippingState option').removeAttr('selected');
                            $('#shippingState option:eq(0)').text(inputVariables.storeData.resources.text.STATE_PROVINCE).attr('selected', 'selected');
                        }
                    }
                });
                $('select#shippingAddressBook').change();
                $('form[name=CheckoutAddressForm] input.dr_button').trigger('click');
            }
        });
    } else {
        $('select#shippingAddressBook').change();
        $('form[name=CheckoutAddressForm] input.dr_button').trigger('click');
    }
});

//new cross-border JS code for full responsive PCF
$.widgetize('shipping-information', function(){
    var $CheckoutAddressForm = $('form[name=CheckoutAddressForm]');
    $CheckoutAddressForm.submit(function(){
        if($('select#shippingState').attr('data-required') === 'false'){
            $('select#shippingState').parent().remove();
        }
        $('select#shippingCountry').removeAttr('disabled');
    });
    if($('select#shippingCountry').length > 0){
        if($('.shipping-information').hasClass('new-shopper')){
            $('.rwd .shipping-page .pcf-footer .button-block').hide();
        }
        var countryArr = new Array();
        $('.dr_shipping .dr_formLine').hide();
        $('select#shippingCountry').parent().show();
        $("select#shippingCountry option[selected='selected']").removeAttr("selected"); //deselect all options
        $("select#shippingCountry option[value='']").attr("selected", "selected");
        $(this).show();

        $('select#shippingCountry').change(function(){
            var shippingCountry = $(this).val();
            $.ajax({
                url: '/Storefront/Site/mscommon/cm/multimedia/js/dr-CrossBorderMapping_16.js', //holds the mapping to the form fields
                dataType: 'json',
                cache: true,
                success: function(data){
                    $.each(data.COUNTRYinfoModal, function(){
                        datacon = this;
                        if(datacon.COUNTRYinfo.shippingCountry === shippingCountry){
                            mappingExistence = true;
                            $('select#shippingCountry option').each(function(){
                                var optionValue = $(this).val();
                                if(optionValue != shippingCountry){
                                    $(this).remove();
                                }
                            });
                            $('select#shippingAddressBook option').each(function(){
                                var optionValue = $(this).attr('data-country');
                                if(optionValue != shippingCountry){
                                    $(this).remove();
                                }
                            });
                            if(!$('select#shippingAddressBook option').length){
                                $('#shippingAddressBook').append('<option value="NEW" selected="selected">...</option>');
                            }
                            $('select#shippingAddressBook').change();
                            if(datacon.COUNTRYinfo.shippingState === 'false'){
                                $('form[name=CheckoutAddressForm] input[type=text]').val('');
                                $('form[name=CheckoutAddressForm] select#shippingState option:eq(0)').attr('selected','selected');
                                $('#shippingState option').removeAttr('selected');
                                $('#shippingState option:eq(0)').text(inputVariables.storeData.resources.text.STATE_PROVINCE).attr('selected', 'selected');
                                if(datacon.COUNTRYinfo.address2 != null && datacon.COUNTRYinfo.address2 == "false"){
                                    $('.dr_formLine.address2').addClass('hide');
                                }
                                $('select#shippingState').parent().after('<input type="hidden" name="SHIPPINGstate" value=""/>');
                                $('select#shippingState').parent().remove();
                                $('.shipping-information .state-and-zip .zip').css('float','left');
                            } else {
                                var shippingStateOptions = datacon.COUNTRYinfo.shippingStateOptions;
                                $('select#shippingState').html(shippingStateOptions);
                                $('select#shippingAddressBook').change();
                                $('form[name=CheckoutAddressForm] input[type=text]').val('');
                                $('form[name=CheckoutAddressForm] select#shippingState option:eq(0)').attr('selected','selected');
                                $('#shippingState option').removeAttr('selected');
                                $('#shippingState option:eq(0)').text(inputVariables.storeData.resources.text.STATE_PROVINCE).attr('selected', 'selected');
                                if(datacon.COUNTRYinfo.address2 != null && datacon.COUNTRYinfo.address2 == "false"){
                                    $('.dr_formLine.address2').addClass('hide');
                                }
                                $('select#shippingState').attr('data-required', 'true');
                            }
                        }
                    });
                    $("select#shippingCountry option[value=" + shippingCountry + "]").attr("selected", "selected");
                    $('select#shippingCountry').attr('disabled', 'disabled');
                    $('.button-block .back-button').removeClass('hide-option');

                    if($('.shipping-information').hasClass('new-shopper')){
                        $('.dr_shipping .dr_formLine:not(.hide)').show();
                        $('.rwd .shipping-page .pcf-footer .button-block').show();
                    } else {
                        $('.pcf-shipping-addresses .shipping-address input[type=radio]').each(function(){
                            if($(this).attr('data-country') !== shippingCountry){
                                $(this).parents('.shipping-address').remove();
                            }
                        });
                        $('.addresses-list').show().addClass('row-padded-bottom-small');
                        $('.pcf-shipping-addresses li.shipping-address').eq(0).addClass('active');
                    }

                    // Address ordering on Customer Info Page
                    /*var seqList = inputVariables.storeData.resources.shippingAddrSeq[shippingCountry];
                     if(seqList){
                     if(!seqList.match("Config_AddressSequence_")){
                     var seqArray = seqList.split(',');
                     var addrContent = [];
                     addrContent.push($('<div>').append($('.addressFields .dr_formLine #shippingName1').parent().clone()).html());
                     addrContent.push($('<div>').append($('.addressFields .dr_formLine #shippingName2').parent().clone()).html());
                     $.each(seqArray,function(index,val){
                     if(val=='Address1'){
                     var elementsToPush = $('<div>').append($('.addressFields .dr_formLine #shippingAddress1').parent().clone()).html();
                     addrContent.push(elementsToPush);
                     }
                     else if(val=='Address2'){
                     var elementsToPush = $('<div>').append($('.addressFields .dr_formLine #shippingAddress2').parent().clone()).html();
                     addrContent.push(elementsToPush);
                     }
                     else if(val=='PostalCode'){
                     var elementsToPush = $('<div>').append($('.addressFields .dr_formLine #shippingPostalCode').parent().clone()).html();
                     addrContent.push(elementsToPush);
                     }
                     else if(val=='City'){
                     var elementsToPush = $('<div>').append($('.addressFields .dr_formLine #shippingCity').parent().clone()).html();
                     elementsToPush = elementsToPush + $('<div>').append($('.addressFields .dr_formLine #shippingState').parent().clone()).html();
                     addrContent.push(elementsToPush);
                     }
                     });
                     addrContent.push($('<div>').append($('.addressFields .dr_formLine #shippingPhoneNumber').parent().clone()).html());
                     addrContent.push('<div class="clear"></div>');
                     addrContent.push($('<div>').append($('.addressFields .dr_formLine #shippingCountry').parent().clone()).html());
                     $('#dr_shippingContainer .addressFields').css('width','103%').html(addrContent.join(''));
                     $('.dr_shipping .addressFields .dr_formLine').css({'clear': 'none', 'padding-right': '2.911%', 'width':'47.089%', 'padding-left':'0'
                     });
                     $('a.newAddress').trigger('click');
                     }
                     }*/
                },
                error: function(){
                    //submit the form if any error
                }
            });
        });

        /*** back-button ***/
        $('.button-block .back-button').click(function(){
            $('.dr_shipping .dr_formLine').hide();
            $('.validation-summary').removeClass('error');
            $('select#shippingCountry').parent().show();
            $('.shipping-information').show();
        });
    } else {
        if(!$('.shipping-information.default-address-error').length){
            $('.addresses-list').show();
        }
    }
});

// NEW PCF JS, please keep this section on the bottom
$(function(){
    /***Invalid promo code***/
    if($('.cart .promo-code .promo-code-panel .dr_error').length && $('.cart .promo-code .promo-code-panel .dr_error').text() !== ''){
        $('.cart .promo-code .toggle-icon').removeClass('icon-plus').addClass('icon-minus');
        $('.promo-code-panel').show();
    }

    /***Shipping method***/
    $('.shipping-method input[type=radio]').click(function(){
        var shippingMethodID = $(this).val(),
            shippingMethodIDSelected = $("form[name=EstimateShippingCostForm] input[name=shippingOptionID]").val(),
            shippingForm = $('form[name=EstimateShippingCostForm]');
        if(shippingMethodID !== shippingMethodIDSelected){
            $('form[name="EstimateShippingCostForm"] input[type=hidden][name=shippingOptionID]').val(shippingMethodID);
            shippingForm.submit();
        }
    });

    /***Shipping default error***/
    if($('.shipping-information.default-address-error').length && !$('.shipping-information select#shippingCountry').length){
        $('select#shippingAddressBook').change();
        $('.shipping-information').show();
        $('.rwd .ship-to-page .pcf-footer .button-block').show();
    }

    /*****Shipping Button*****/
    $('.pcf-footer .button-block .checkout, .checkoutButton .dr_checkout .checkoutCCT').click(function(){
        if($('#dr_billingContainer')){
            if(!($("#useShippingAsBilling").is(':checked'))){
                $(".dr_shipping .dr_formLine, #dr_shipping .dr_formLine").each(function(){
                    $(this).find("input[type=text]").attr("value", $('#dr_billingContainer .dr_formLine input[name='+$(this).find("input[type=text]").attr("data-input")+']').val());
                    if($(this).find('select')){
                        $(this).find('select option[value='+$('select[name='+$(this).find("select").attr("data-input")+']  option:selected').val()+']').attr("selected","selected");
                    }
                });
            }
        }
        $('.shipping-information #checkoutFormSubmitButton').trigger('click');
        if($('.validation-summary').hasClass('error')){
            $('.addresses-list').hide();
            $('.dr_shipping .dr_formLine:not(.hide)').show();
            $(this).parent().show();
            $('.shipping-information').show();
        }
    });
    /*****Choose shipping*****/
    /*Ship to this address*/
    $('.shipping-address .button-block .ship').click(function(){
        var addressEntryVal = $(this).parent().siblings('.radio-button').find('.radio input[name=SHIPPINGselectedAddressEntryRadio]').val();
        $('.shipping-information .shipping-address #shippingAddressBook option').removeAttr('selected');
        $('.shipping-information .shipping-address #shippingAddressBook option').each(function(){
            if($(this).val() === addressEntryVal){
                $(this).attr('selected','selected');
            }
        });
        $('select#shippingAddressBook').change();
        if($(this).parents('.shipping-address').data('addresserror')){
            $(this).siblings('.edit').trigger('click');
        }
        $('.pcf-footer .button-block .checkout').click(function(){
            $('.shipping-information #checkoutFormSubmitButton').trigger('click');
        });
        $('.pcf-footer .button-block .checkout').trigger('click');
    });

    /*Edit address*/
    $('.shipping-address .button-block .edit').click(function(){
        var addressEntryVal = $(this).parent().siblings('.radio-button').find('.radio input[name=SHIPPINGselectedAddressEntryRadio]').val();
        $('.shipping-information .shipping-address #shippingAddressBook option').removeAttr('selected');
        $('.shipping-information .shipping-address #shippingAddressBook option').each(function(){
            if($(this).val() === addressEntryVal){
                $(this).attr('selected','selected');
            }
        });
        if($('.dr_shipping select#shippingCountry').length > 0){
            $('.dr_shipping .dr_formLine:not(.hide)').show();
        }
        $('.shipping-address .validation-summary').removeClass('error');
        $('.shipping-address .dr_formLine').removeClass('error');
        $('.shipping-address .dr_formLine input, .shipping-address .dr_formLine select').removeClass('dr_input_invalid');
        $('select#shippingAddressBook').change();
    });

    /*Add a new address*/
    $('.new-address').click(function(){
        $('.shipping-information .textbox input[type=text]').val('');
        $('.shipping-information .drop-down-list select').find('option').removeAttr('selected');
        $('.shipping-information .drop-down-list select').find('option:eq(0)').attr('selected','selected');
        if($('.shipping-information .shipping-address #shippingAddressBook option[value=NEW]').length == 0){
            $('.shipping-information .shipping-address #shippingAddressBook').append('<option value="NEW" selected="selected">...</option>');
        }
        $('.shipping-address .validation-summary').removeClass('error');
        $('.shipping-address .dr_formLine').removeClass('error');
        $('.shipping-address .dr_formLine input, .shipping-address .dr_formLine select').removeClass('dr_input_invalid');
        if($('.dr_shipping select#shippingCountry').length > 0){
            $('.dr_shipping .dr_formLine:not(.hide)').show();
        }
    });

    /*****Choose payment******/
    /*Pay with this card*/
    $('.payment-type .button-block .pay:not(.dr_error)').click(function(){
        $('.radio-button .radio input[type=radio]').removeAttr('checked');
        $(this).parents('.text-label').siblings('.radio').find('input[name=piid]').attr('checked','checked');
        $('form[name=CheckoutAddressForm]').submit();
    });

    /*Edit card*/
    $('.payment-type .button-block .edit').click(function(){
        $('.radio-button .radio input[type=radio]').removeAttr('checked');
        $(this).parents('.text-label').siblings('.radio').find('input[name=piid]').attr('checked','checked');
        $('form[name=CheckoutAddressForm] input[name=editcard]').val('true');
        $('form[name=CheckoutAddressForm]').submit();
    });

    /*Add a new payment*/
    $('a.new-payment-method').click(function(){
        $('.radio-button .radio input[type=radio]').removeAttr('checked');
        $(this).siblings('input.new-payment').attr('checked','checked');
        $('form[name=CheckoutAddressForm] input[name=editcard]').val('true');
        $('form[name=CheckoutAddressForm]').submit();
    });
});

$(window).load(function(){
    /***Shipping default error***/
    if($('.shipping-information.default-address-error').length && !$('.shipping-information select#shippingCountry').length){
        $('.pcf-footer .button-block .checkout').click(function(){
            $('.shipping-information #checkoutFormSubmitButton').trigger('click');
        });
        $('.pcf-footer .button-block .checkout').trigger('click');
    }
});

if ($('div.rwd > #body').length) {
    /* Making sure mstResponsive is removed on Responsive pages, and fix header links for mobile */
    $('body').removeClass('mstResponsive');
    /* workaround to get media queries to work on the surface tablet */
    if (navigator.userAgent.match(/IEMobile\/10\.0/)) {
        var msViewport = document.createElement("style");
        msViewport.appendChild(document.createTextNode("@-ms-viewport{width:auto!important}"));
        document.getElementsByTagName("head")[0].appendChild(msViewport);
    }
}

$(function(){
    var errorClass = "error",
        errorClassForCheckbox = "error-checkbox",
        attrType = "value",
        defaultTextClass = "default-text",
        errorText = "";
    email = $('#email');
    email.focus(function(){
        email.removeClass(errorClass);
        email.addClass(defaultTextClass);
        if(email.val() == errorText){
            email.val('');
        }
    });
    $("#email-us-form").submit(function(e){
        e.preventDefault();
        e.stopImmediatePropagation();
        var signUpContainer = $('.email-signup'),
            completedContainer = $('.email-signup-completed'),
            email_address = email.val(),
            post_data = {jsonp: '1'},
            form_action = $(this).attr('action'),
            $emailForm = $('#email-us-form'),
            $checkbox = $emailForm.find('#emailagreement'),
            $checkboxBlock = $emailForm.find('.check-box'),
            $errorMessage = $emailForm.find('.email-agreement-error-msg');
        $("input[type='email'],input[type='hidden']",this).each(function(){
            var _this = $(this);
            post_data[_this.attr('name')] = _this.val();
        });
        window.functionCall = function(data){
            if(data.status == 'error'){
                email.addClass(errorClass);
                email.val(data.errors);
                errorText = data.errors;
                email.removeClass(defaultTextClass);
                email.blur();
            }else{
                email.removeClass(errorClass);
                $checkbox.removeClass(errorClassForCheckbox);
                signUpContainer.hide();
                $checkboxBlock.hide();
                completedContainer.show();
            }
        };
        if($checkbox.length){
            isCheckboxChecked = $checkbox.is(':checked');
            $checkbox.click(function(){
                if($(this).is(':checked')){
                    $(this).removeClass(errorClassForCheckbox);
                    $errorMessage.hide();
                }
            });
            if (!isCheckboxChecked) {
                $checkbox.addClass(errorClassForCheckbox);
                email.addClass(errorClass);
                email.removeClass(defaultTextClass);
                email.blur();
                $errorMessage.show();
            } else {
                $.ajax({
                    type: 'POST',
                    data: post_data,
                    url: '//nct.digitalriver.com/fulfill/thankyou/0295.017',
                    jsonpCallback: 'functionCall',
                    dataType: 'jsonp'
                });
            }
        }else{
            $.ajax({
                type: 'POST',
                data: post_data,
                url: '//nct.digitalriver.com/fulfill/thankyou/0295.017',
                jsonpCallback: 'functionCall',
                dataType: 'jsonp'
            });
        }
        return false;
    });
});