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


