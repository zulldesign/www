<html>
<head>
	<title>IPN Debug (Unregistered version)</title>
<script type="text/javascript">

function testipn(f) {
	if (f.ipndata.value == '') {
		alert('Please enter data first.');
		f.ipndata.focus();
		return;
	}
	var xmlHttp = ewpp_CreateXMLHttp();		
	if (!xmlHttp) {
		alert('Your browser does not support XMLHTTP.');		
		return;
	}

//	var url = String(location);
//	var p = url.lastIndexOf('/');
//	if (p > -1)
//		p = url.lastIndexOf('/', p-1);	
//	if (p > -1)
//		url = url.substring(0, p+1);
//	url += 'ipn.php';

	url = '../ipn.php';
	xmlHttp.open('POST', url, true);
	xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	var fn = function() {
		if (xmlHttp.readyState == 4) {
			var result = xmlHttp.responseText;
			if (result == '')				
				result = "IPN script has been executed.";
			document.getElementById("result").innerHTML = result;
		}
	}
	xmlHttp.onreadystatechange = fn;
	xmlHttp.send(f.ipndata.value);
}

// Create XMLHTTP
function ewpp_CreateXMLHttp() {

	if (!(document.getElementsByTagName || document.all))
		return;		
	var ret = null;
	try {
		ret = new ActiveXObject('Msxml2.XMLHTTP');
	}	catch (e) {
	    try {
	        ret = new ActiveXObject('Microsoft.XMLHTTP');
	    } catch (ee) {
	        ret = null;
	    }
	}
	if (!ret && typeof XMLHttpRequest != 'undefined')
	    ret = new XMLHttpRequest();	
	return ret;
}
</script>
<meta name="generator" content="PayPal Shop Maker v5.0.0.2 (Unregistered version)">
</head>
<body>
<p><b>IPN Debug Script</b></p>
<form>
Note: If you don't know if your server supports PHP, run the <a href="index.html">Server Testing Script</a> first.<br><br>
Copy and paste the IPN data from PayPal below:<br>
<textarea cols="60" rows="10" name="ipndata"></textarea>
<br>
<input type="button" value="  Test  " onClick="testipn(this.form);">
</form>
<div id="result"></div>
<br><br>
</body>
</html>
