<?php

function print_wp_shopping_cart($args = array()) {
    $output = "";
    if (!cart_not_empty()) {
        $empty_cart_text = get_option('wp_cart_empty_text');
        if (!empty($empty_cart_text)) {
            $output .= '<div class="wp_cart_empty_cart_section">';
            if (preg_match("/http/", $empty_cart_text)) {
                $output .= '<img src="' . $empty_cart_text . '" alt="' . $empty_cart_text . '" class="wp_cart_empty_cart_image" />';
            } else {
                $output .= $empty_cart_text;
            }
            $output .= '</div>';
        }
        $cart_products_page_url = get_option('cart_products_page_url');
        if (!empty($cart_products_page_url)) {
            $output .= '<div class="wp_cart_visit_shop_link"><a rel="nofollow" href="' . $cart_products_page_url . '">' . (__("Visit The Shop", "WSPSC")) . '</a></div>';
        }
        return $output;
    }
    $email = get_bloginfo('admin_email');
    $use_affiliate_platform = get_option('wp_use_aff_platform');
    $defaultCurrency = get_option('cart_payment_currency');
    $defaultSymbol = get_option('cart_currency_symbol');
    $defaultEmail = get_option('cart_paypal_email');
    if (!empty($defaultCurrency))
        $paypal_currency = $defaultCurrency;
    else
        $paypal_currency = __("USD", "WSPSC");
    if (!empty($defaultSymbol))
        $paypal_symbol = $defaultSymbol;
    else
        $paypal_symbol = __("$", "WSPSC");

    if (!empty($defaultEmail))
        $email = $defaultEmail;

    $decimal = '.';
    $urls = '';

    $return = get_option('cart_return_from_paypal_url');
    if (empty($return)) {
        $return = WP_CART_SITE_URL . '/';
    }
    $return_url = add_query_arg('reset_wp_cart', '1', $return);

    $urls .= '<input type="hidden" name="return" value="' . $return_url . '" />';

    $notify = WP_CART_SITE_URL . '/?simple_cart_ipn=1';
    $urls .= '<input type="hidden" name="notify_url" value="' . $notify . '" />';

    $title = get_option('wp_cart_title');
    //if (empty($title)) $title = __("Your Shopping Cart", "WSPSC");

    global $plugin_dir_name;
    $output .= '<div class="shopping_cart">';
    if (!get_option('wp_shopping_cart_image_hide')) {
        $output .= "<img src='" . WP_CART_URL . "/images/shopping_cart_icon.png' value='" . (__("Cart", "WSPSC")) . "' title='" . (__("Cart", "WSPSC")) . "' />";
    }
    if (!empty($title)) {
        $output .= '<h2>';
        $output .= $title;
        $output .= '</h2>';
    }

    $output .= '<br /><span id="pinfo" style="display: none; font-weight: bold; color: red;">' . (__("Hit enter to submit new Quantity.", "WSPSC")) . '</span>';
    $output .= '<table style="width: 100%;">';

    $count = 1;
    $total_items = 0;
    $total = 0;
    $form = '';
    if ($_SESSION['simpleCart'] && is_array($_SESSION['simpleCart'])) {
        $output .= '
        <tr>
        <th class="wspsc_cart_item_name_th">' . (__("Item Name", "WSPSC")) . '</th><th class="wspsc_cart_qty_th">' . (__("Quantity", "WSPSC")) . '</th><th class="wspsc_cart_price_th">' . (__("Price", "WSPSC")) . '</th><th></th>
        </tr>';
        $item_total_shipping = 0;
        $postage_cost = 0;
        foreach ($_SESSION['simpleCart'] as $item) {
            $total += $item['price'] * $item['quantity'];
            $item_total_shipping += $item['shipping'] * $item['quantity'];
            $total_items += $item['quantity'];
        }
        if (!empty($item_total_shipping)) {
            $baseShipping = get_option('cart_base_shipping_cost');
            $postage_cost = $item_total_shipping + $baseShipping;
        }

        $cart_free_shipping_threshold = get_option('cart_free_shipping_threshold');
        if (!empty($cart_free_shipping_threshold) && $total > $cart_free_shipping_threshold) {
            $postage_cost = 0;
        }

        foreach ($_SESSION['simpleCart'] as $item) {
            
            $output .= "<tr><td style='overflow: hidden;'>";
            $output .= '<div class="wp_cart_item_info">';
            if(isset($args['show_thumbnail'])){
                $output .= '<span class="wp_cart_item_thumbnail"><img src="'.$item['thumbnail'].'" class="wp_cart_thumb_image" ></span>';
            }
            $item_info = apply_filters('wspsc_cart_item_name', '<a href="'.$item['cartLink'].'">'.$item['name'].'</a>', $item);
            $output .= '<span class="wp_cart_item_name">'.$item_info.'</span>';
            $output .= '<span class="wp_cart_clear_float"></span>';
            $output .= '</div>';
            $output .= '</td>';
            
            $output .= "<td style='text-align: center'><form method=\"post\"  action=\"\" name='pcquantity' style='display: inline'>
                <input type=\"hidden\" name=\"product\" value=\"" . htmlspecialchars($item['name']) . "\" />
	        <input type='hidden' name='cquantity' value='1' /><input type='text' name='quantity' value='" . $item['quantity'] . "' size='1' onchange='document.pcquantity.submit();' onkeypress='document.getElementById(\"pinfo\").style.display = \"\";' /></form></td>
	        <td style='text-align: center'>" . print_payment_currency(($item['price'] * $item['quantity']), $paypal_symbol, $decimal) . "</td>
	        <td><form method=\"post\" action=\"\" class=\"wp_cart_remove_item_form\">
	        <input type=\"hidden\" name=\"product\" value=\"" . $item['name'] . "\" />
	        <input type='hidden' name='delcart' value='1' />
	        <input type='image' src='" . WP_CART_URL . "/images/Shoppingcart_delete.png' value='" . (__("Remove", "WSPSC")) . "' title='" . (__("Remove", "WSPSC")) . "' /></form></td></tr>
	        ";

            $form .= "
	            <input type=\"hidden\" name=\"item_name_$count\" value=\"" . $item['name'] . "\" />
	            <input type=\"hidden\" name=\"amount_$count\" value='" . wpspsc_number_format_price($item['price']) . "' />
	            <input type=\"hidden\" name=\"quantity_$count\" value=\"" . $item['quantity'] . "\" />
	            <input type='hidden' name='item_number_$count' value='" . $item['item_number'] . "' />
	        ";
            $count++;
        }
        if (!get_option('wp_shopping_cart_use_profile_shipping')) {
            $postage_cost = wpspsc_number_format_price($postage_cost);
            $form .= "<input type=\"hidden\" name=\"shipping_1\" value='" . $postage_cost . "' />"; //You can also use "handling_cart" variable to use shipping and handling here 
        }
        if (get_option('wp_shopping_cart_collect_address')) {//force address collection
            $form .= "<input type=\"hidden\" name=\"no_shipping\" value=\"2\" />";
        }
    }

    $count--;

    if ($count) {
        if ($postage_cost != 0) {
            $output .= "
                <tr><td colspan='2' style='font-weight: bold; text-align: right;'>" . (__("Subtotal", "WSPSC")) . ": </td><td style='text-align: center'>" . print_payment_currency($total, $paypal_symbol, $decimal) . "</td><td></td></tr>
                <tr><td colspan='2' style='font-weight: bold; text-align: right;'>" . (__("Shipping", "WSPSC")) . ": </td><td style='text-align: center'>" . print_payment_currency($postage_cost, $paypal_symbol, $decimal) . "</td><td></td></tr>";
        }

        $output .= "<tr><td colspan='2' style='font-weight: bold; text-align: right;'>" . (__("Total", "WSPSC")) . ": </td><td style='text-align: center'>" . print_payment_currency(($total + $postage_cost), $paypal_symbol, $decimal) . "</td><td></td></tr>";

        if (isset($_SESSION['wpspsc_cart_action_msg']) && !empty($_SESSION['wpspsc_cart_action_msg'])) {
            $output .= '<tr><td colspan="4"><span class="wpspsc_cart_action_msg">' . $_SESSION['wpspsc_cart_action_msg'] . '</span></td></tr>';
        }

        if (get_option('wpspsc_enable_coupon') == '1') {
            $output .= '<tr><td colspan="4">
                <div class="wpspsc_coupon_section">
                <span class="wpspsc_coupon_label">' . (__("Enter Coupon Code", "WSPSC")) . '</span>
                <form  method="post" action="" >
                <input type="text" name="wpspsc_coupon_code" value="" size="10" />
                <span class="wpspsc_coupon_apply_button"><input type="submit" name="wpspsc_apply_coupon" class="wpspsc_apply_coupon" value="' . (__("Apply", "WSPSC")) . '" /></span>
                </form>
                </div>
                </td></tr>';
        }

        $paypal_checkout_url = WP_CART_LIVE_PAYPAL_URL;
        if (get_option('wp_shopping_cart_enable_sandbox')) {
            $paypal_checkout_url = WP_CART_SANDBOX_PAYPAL_URL;
        }

        $form_target_code = '';
        if (get_option('wspsc_open_pp_checkout_in_new_tab')) {
            $form_target_code = 'target="_blank"';
        }

        $output .= "<tr class='wpspsc_checkout_form'><td colspan='4'>";
        $output .= '<form action="' . $paypal_checkout_url . '" method="post" ' . $form_target_code . '>';
        $output .= $form;
        if ($count)
            $output .= '<input type="image" src="' . WP_CART_URL . '/images/' . (__("paypal_checkout_EN.png", "WSPSC")) . '" name="submit" class="wp_cart_checkout_button" alt="' . (__("Make payments with PayPal - it\'s fast, free and secure!", "WSPSC")) . '" />';

        $output .= $urls . '
            <input type="hidden" name="business" value="' . $email . '" />
            <input type="hidden" name="currency_code" value="' . $paypal_currency . '" />
            <input type="hidden" name="cmd" value="_cart" />
            <input type="hidden" name="upload" value="1" />
            <input type="hidden" name="rm" value="2" />
            <input type="hidden" name="charset" value="utf-8" />
            <input type="hidden" name="bn" value="TipsandTricks_SP" />';
        $wp_cart_note_to_seller_text = get_option('wp_cart_note_to_seller_text');
        if (!empty($wp_cart_note_to_seller_text)) {
            $output .= '<input type="hidden" name="no_note" value="0" /><input type="hidden" name="cn" value="' . $wp_cart_note_to_seller_text . '" />';
        }
        $page_style_name = get_option('wp_cart_paypal_co_page_style');
        if (!empty($page_style_name)) {
            $output .= '<input type="hidden" name="page_style" value="' . $page_style_name . '" />';
        }
        $output .= wp_cart_add_custom_field();
        $output .= '</form>';
        $output .= '</td></tr>';
    }
    $output .= "</table></div>";
    return $output;
}
