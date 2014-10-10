<%@ Control Language="VB" Inherits="System.Web.Mvc.ViewUserControl" %>

<title>My trip to the mountains</title>
<body style="font-family: Verdana, Geneva, Arial, Helvetica, sans-serif; color: black;">
<h1 style="color: steelblue;">Mountain</h1>
<h2>Trip</h2>
<p>My <b>trip</b> to the mountains was quite fun.</p>
<p>Here is a picture of the mountain!</p>
<p><img src="/Images/mountain-trip.jpg" title="mountain sample" border="1"></p>
<p><a href="default.aspx" title="home">Home</p>

	<script src="/simplecart/simpleCart.js"></script>
	<script>
		simpleCart({
			checkout: {
				type: "PayPal",
				email: "admin@zulldesign.ml"
			}
		});
	</script>
	simpleCart({
		checkout: { 
			type: "PayPal" , 
			email: "admin@zulldesign.ml" 
		},
		tax: 		0.075,
		currency: 	"MYR"
	});

	<div class="simpleCart_shelfItem">
		<h2 class="item_name"> Awesome T-shirt </h2>
		<input type="text" value="1" class="item_Quantity">
		<span class="item_price">$35.99</span>
		<a class="item_add" href="javascript:;"> Add to Cart </a>
	</div>

	<div class="simpleCart_shelfItem">
		<img src="/images/item_thumb.jpg" class="item_thumb" />
		<h2 class="item_name"> Awesome T-shirt </h2>
		<select class="item_size">
			<option value="Small"> Small </option>
			<option value="Medium"> Medium </option>
			<option value="Large"> Large </option>
		</select>
		<input type="text" value="1" class="item_Quantity">
		<span class="item_price">$35.99</span>
		<a class="item_add" href="javascript:;"> Add to Cart </a>
	</div>


