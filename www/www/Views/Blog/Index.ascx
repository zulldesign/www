<%@ Control Language="VB" Inherits="System.Web.Mvc.ViewUserControl" %>
<base target="main">
<title>My trip to the mountains</title>
<body style="font-family: Verdana, Geneva, Arial, Helvetica, sans-serif; color: black;">
<h1 style="color: steelblue;">Mountain</h1>
<h2>Trip</h2>
<p>My <b>trip</b> to the mountains was quite fun.</p>
<p>Here is a picture of the mountain!</p>
<p><img src="/Images/mountain-trip.jpg" title="mountain sample" border="1"></p>
<p><a href="default.aspx" title="home">Home</p>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title></title>
	<link rel="stylesheet" href="../../node_modules/mocha/mocha.css" />
</head>
<body>
	<div id="mocha"></div>

	<div id="buynow-sm">
		<h2>Buy Now (Small)</h2>
		<script async src="/Images/JavaScriptButtons/dist/button.js?merchant=6XF3MPZBZV6HU"
			data-button="buynow"
			data-type="form"
			data-name="Buy now!"
			data-amount="1.00"
			data-size="small"
			data-callback="http://example.com/callback"
		></script>
	</div>

	<div id="buynow-md">
		<h2>Buy Now (Medium)</h2>
		<script async src="../../dist/button.js?merchant=6XF3MPZBZV6HU"
			data-button="buynow"
			data-type="form"
			data-name="Buy now!"
			data-amount="1.00"
			data-size="medium"
		></script>
	</div>
	<script src="/Images/JavaScriptButtons/test/functional/lib/require.js" data-main="lib/runner"></script>
</body>
</html>