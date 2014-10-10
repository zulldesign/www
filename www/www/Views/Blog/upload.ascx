<%@ Control Language="VB" Inherits="System.Web.Mvc.ViewUserControl" %>

<title>Welcome to my site</title>
<h1 style="color: Navy;">My Homepage!</h1>
<h2 style="color: darkgreen;">All about the mountain</h2>
<p>Mountain Trip Pictures</p>
<body style="font-family: Verdana, Geneva, Arial, Helvetica, sans-serif;">

<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="DNXV8BMNNYYAS">
<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_buynowCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>



<form target="paypal" action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="Z5BU82XPS8KDC">
<table>
<tr><td><input type="hidden" name="on0" value="Name of drop-down menu">Name of drop-down menu</td></tr><tr><td><select name="os0">
	<option value="Option 1">Option 1 RM0.01 MYR</option>
	<option value="Option 2">Option 2 RM0.02 MYR</option>
	<option value="Option 3">Option 3 RM0.03 MYR</option>
</select> </td></tr>
</table>
<input type="hidden" name="currency_code" value="MYR">
<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_cart_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>


<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="Z5BU82XPS8KDC">
<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_subscribe_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>



