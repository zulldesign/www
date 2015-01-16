<?php

function show_wp_cart_coupon_discount_settings_page()
{    
    if (isset($_POST['wpspsc_coupon_settings']))
    {
        $nonce = $_REQUEST['_wpnonce'];
        if ( !wp_verify_nonce($nonce, 'wpspsc_coupon_settings')){
                wp_die('Error! Nonce Security Check Failed! Go back to Coupon/Discount menu and save the settings again.');
        }
        update_option('wpspsc_enable_coupon', (isset($_POST['wpspsc_enable_coupon']) && $_POST['wpspsc_enable_coupon']=='1') ? '1':'');
        echo '<div id="message" class="updated fade"><p><strong>';
        echo 'Coupon Settings Updated!';
        echo '</strong></p></div>';
    }
    if (isset($_POST['wpspsc_save_coupon']))
    {
        $nonce = $_REQUEST['_wpnonce'];
        if ( !wp_verify_nonce($nonce, 'wpspsc_save_coupon')){
                wp_die('Error! Nonce Security Check Failed! Go back to email settings menu and save the settings again.');
        }
        
        $collection_obj = WPSPSC_Coupons_Collection::get_instance();
        $coupon_code = trim(stripcslashes($_POST["wpspsc_coupon_code"]));
        $discount_rate = trim($_POST["wpspsc_coupon_rate"]);
        $expiry_date = trim($_POST["wpspsc_coupon_expiry_date"]);
        $coupon_item = new WPSPSC_COUPON_ITEM($coupon_code, $discount_rate, $expiry_date);
        $collection_obj->add_coupon_item($coupon_item);
        WPSPSC_Coupons_Collection::save_object($collection_obj);
        
        echo '<div id="message" class="updated fade"><p><strong>';
        echo 'Coupon Saved!';
        echo '</strong></p></div>';
    }
    
    if(isset($_REQUEST['wpspsc_delete_coupon_id']))
    {
        $coupon_id = $_REQUEST['wpspsc_delete_coupon_id'];
        $collection_obj = WPSPSC_Coupons_Collection::get_instance();
        $collection_obj->delete_coupon_item_by_id($coupon_id);
        echo '<div id="message" class="updated fade"><p>';
        echo 'Coupon successfully deleted!';
        echo '</p></div>';
    }
    $wpspsc_enable_coupon = '';
    if (get_option('wpspsc_enable_coupon') == '1'){
        $wpspsc_enable_coupon = 'checked="checked"';
    }
    ?>
    
    <div style="background: none repeat scroll 0 0 #FFF6D5;border: 1px solid #D1B655;color: #3F2502;margin: 10px 0;padding: 5px 5px 5px 10px;text-shadow: 1px 1px #FFFFFF;">	
    <p><?php _e("For more information, updates, detailed documentation and video tutorial, please visit:", "WSPSC"); ?><br />
    <a href="https://www.tipsandtricks-hq.com/wordpress-simple-paypal-shopping-cart-plugin-768" target="_blank"><?php _e("WP Simple Cart Homepage", "WSPSC"); ?></a></p>
    </div>
    
    <form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
    <?php wp_nonce_field('wpspsc_coupon_settings'); ?>
    <input type="hidden" name="coupon_settings_update" id="coupon_settings_update" value="true" />

    <div class="postbox">
    <h3><label for="title"><?php _e("Coupon/Discount Settings", "WSPSC");?></label></h3>
    <div class="inside">

    <form method="post" action="">
    <table class="form-table" width="100%" border="0" cellspacing="0" cellpadding="6">
    
    <tr valign="top">
    <th scope="row"><?php _e("Enable Discount Coupon Feature", "WSPSC");?></th>
    <td>
    <input type="checkbox" name="wpspsc_enable_coupon" value="1" <?php echo $wpspsc_enable_coupon; ?> />
    <span class="description"><?php _e("When checked your customers will be able to enter a coupon code in the shopping cart before checkout.", "WSPSC");?></span>
    </td>
    </tr>
    
    <tr valign="top">
    <th scope="row">
    <div class="submit">
        <input type="submit" name="wpspsc_coupon_settings" class="button-primary" value="<?php echo (__("Update &raquo;", "WSPSC")) ?>" />
    </div>
    </th>
    <td></td>
    </tr>

    </table>

    </form>
    </div></div>

    <form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
    <?php wp_nonce_field('wpspsc_save_coupon'); ?>
    <input type="hidden" name="info_update" id="info_update" value="true" />

    <div class="postbox">
    <h3><label for="title"><?php _e("Add Coupon/Discount", "WSPSC");?></label></h3>
    <div class="inside">

    <form method="post" action="">
    <table class="form-table" border="0" cellspacing="0" cellpadding="6" style="max-width:650px;">

    <tr valign="top">

    <td width="25%" align="left">
    Coupon Code<br />
    <input name="wpspsc_coupon_code" type="text" size="15" value=""/>   
    </td>

    <td width="25%" align="left">
    Discount Rate (%)<br />
    <input name="wpspsc_coupon_rate" type="text" size="7" value=""/>            
    </td>
    
    <td width="25%" align="left">
    Expiry Date<br />
    <input name="wpspsc_coupon_expiry_date" class="wpspsc_coupon_expiry" type="text" size="15" value=""/>            
    </td>

    <td width="25%" align="left">
    <div class="submit">
        <input type="submit" name="wpspsc_save_coupon" class="button-primary" value="<?php echo (__("Save Coupon &raquo;", "WSPSC")) ?>" />
    </div>                
    </td> 

    </tr>

    </table>

    </form>
    </div></div>
    
    <?php
    
    //display table
    $output = "";
    $output .= '
    <table class="widefat" style="max-width:800px;">
    <thead><tr>
    <th scope="col">'.(__("Coupon Code", "WSPSC")).'</th>
    <th scope="col">'.(__("Discount Rate (%)", "WSPSC")).'</th>
    <th scope="col">'.(__("Expiry Date", "WSPSC")).'</th>    
    <th scope="col"></th>
    </tr></thead>
    <tbody>';

    $collection_obj = WPSPSC_Coupons_Collection::get_instance();
    if($collection_obj)
    {
        $coupons = $collection_obj->coupon_items; 
        $number_of_coupons = count($coupons);
        if($number_of_coupons > 0)
        {
            $row_count = 0;
            foreach ($coupons as $coupon)
            {
                $output .= '<tr>';
                $output .= '<td><strong>'.$coupon->coupon_code.'</strong></td>';
                $output .= '<td><strong>'.$coupon->discount_rate.'</strong></td>';
                if(empty($coupon->expiry_date)){
                    $output .= '<td><strong>'.__('No Expiry','WSPSC').'</strong></td>';
                }else{
                    $output .= '<td><strong>'.$coupon->expiry_date.'</strong></td>';
                }
                $output .= '<td>';
                $output .= "<form method=\"post\" action=\"\" onSubmit=\"return confirm('Are you sure you want to delete this entry?');\">";				
                $output .= "<input type=\"hidden\" name=\"wpspsc_delete_coupon_id\" value=".$coupon->id." />";
                $output .= '<input style="border: none; background-color: transparent; padding: 0; cursor:pointer;" type="submit" name="Delete" value="Delete">';
                $output .= "</form>";
                $output .= '</td>';
                $output .= '</tr>';
                $row_count = $row_count + 1;
            }
        }
        else
        {
            $output .= '<tr><td colspan="5">'.(__("No Coupons Configured.", "WSPSC")).'</td></tr>';
        }
    }
    else
    {
        $output .= '<tr><td colspan="5">'.(__("No Record found", "WSPSC")).'</td></tr>';
    }

    $output .= '</tbody>
    </table>';

    //$output .= '<p><a href="options-general.php?page=wordpress-paypal-shopping-cart&action=discount-settings">Add New</a></p>';   
    echo $output;
    wpspsc_settings_menu_footer();
}

