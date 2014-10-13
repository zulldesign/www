<!--#include file="config.asp"-->
<!--#include file="ntkfn.asp"-->
<html>
<head>
<title></title>
<meta HTTP-EQUIV="Pragma" CONTENT="no-cache">
<meta HTTP-EQUIV="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript">
<!--
NTK_RootPath = "<%=RootPath%>"; // configure root path
NTK_CartAction = 1; // clear cart
//-->
</script>
<script language="JavaScript" src="<%=RootPath%>ntkcartconfig.js"></script>
<script language="JavaScript" src="<%=RootPath%>ntkcartpp.js"></script>
<script language="JavaScript" src="<%=RootPath%>ntkcart.js"></script>
<script language="JavaScript" src="<%=RootPath%>menu.js"></script>
<script language="JavaScript" src="<%=RootPath%>ntkmenuconfig.js"></script>
<script language="JavaScript" src="<%=RootPath%>ntkcategoryconfig.js"></script>
<link href="<%=RootPath%>demo.css" rel="stylesheet" type="text/css">
<meta name="generator" content="PayPal Shop Builder v2.0.0.2">
</head>
<body leftmargin=0 topmargin=0 marginheight=0 marginwidth=0>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<!-- Logo and top banner background color -->
	<tr class="ntkTopRow">
		<td><img src="<%=RootPath%>images/paypalsb_logo.png" border="0"></td>
	</tr>
</table>
<table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0" class="ntkMain">	
<tr>
	<!-- Begin Menu Column -->
	<td width="20%" height="100%" valign="top" class="ntkLeftColumn">		
		<table width="100%" border="0" cellspacing="0" cellpadding="0">		
		<tr><td nowrap>	
<script type="text/javascript" language="javascript">
	if (document.getElementById) {
		oMenu_root.Render();
	} else {
		document.write(ntk_browserNotSupported);
	}
</script>
		</td></tr>		
		</table>	
		<br>
		<table width="100%" border="0" cellspacing="0" cellpadding="0">		
		<tr><td nowrap>	
		<span class="paypalsb"><b>Browse</b></span>
		</td></tr>		
		</table>	
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr><td nowrap>
<script type="text/javascript" language="javascript">
	if (document.getElementById) {
		oCat_root.Render();
	} else {
		document.write(ntk_browserNotSupported);
	}
</script>
		</td></tr>
		</table>
	</td>
	<!-- End Menu Column -->
	<!-- Begin Content column -->
	<td valign="top" class="ntkMidColumn">
	<!-- 	<div class="paypalsb"></div> -->
<%
Const nAtt = 3
Dim arrCart()
Dim nCartItems
Dim bSuccess
Dim sTx, sTxDetails, arTxDetails, i, sMsg
sMsg = "" ' Initialize
If NTK_PDT_ENABLED Then
	sTx = Request.QueryString("tx")
	If (sTx = "" Or IsNull(sTx)) Then
		sMsg = sMsg & NTK_PDT_PAGE_MESSAGE & "<br><br>"
	Else
		sTxDetails = GetTransaction(sTx)
		arTxDetails = Split(sTxDetails, vbLf)
		For i = 0 to UBound(arTxDetails)
			sLine = Trim(arTxDetails(i))
			idx = InStr(sLine, "=")
			If idx > 0 Then
				sKey = Left(sLine, idx-1)
				sValue = Mid(sLine, idx+1)

				'Response.Write "key: " & sKey & "<br>"
				'Response.Write "value: " & sValue & "<br>"

			End If
		Next
		Dim sBusiness, sPaymentStatus
		sBusiness = GetPDTValue("business")
		sPaymentStatus = GetPDTValue("payment_status")
		Dim item

		'Response.Write "Business: " & sBusiness & "<br>"
		'Response.Write "PaymentStatus: " & sPaymentStatus & "<br>"
		' Audit trail for reference

		Call WriteLog("PDT", "txn_id", sTx)
		Call WriteLog("PDT", "business", sBusiness)
		Call WriteLog("PDT", "status", sPaymentStatus)
		bSuccess = (sPaymentStatus = "Completed" And LCase(sBusiness) = LCase(NTK_BUSINESS))
		If bSuccess Then

			'Response.Write "Business: " & sBusiness & "<br>"
			'Response.Write "PaymentStatus: " & sPaymentStatus & "<br>"

			item = GetPDTValue("item_number")
			If item <> "" Then
				nCartItems = 1
				Redim arrCart(nAtt, nCartItems)
				arrCart(0,1) = item
				arrCart(1,1) = GetPDTValue("item_name")
				arrCart(2,1) = GetPDTValue("quantity")

				' Audit trail for reference
				Call WriteLog("PDT", "item_number", item)
				Call WriteLog("PDT", "item_name", arrCart(1,1))
				Call WriteLog("PDT", "quantity", arrCart(2,1))
			Else
				nCartItems = GetPDTValue("num_cart_items")
				Redim arrCart(nAtt, nCartItems)
				For i = 1 to nCartItems
					arrCart(0,i) = GetPDTValue("item_number"&i)
					arrCart(1,i) = GetPDTValue("item_name"&i)
					arrCart(2,i) = GetPDTValue("quantity"&i)

					' Audit trail for reference
					Call WriteLog("PDT", "item_number_"&i, arrCart(0,i))
					Call WriteLog("PDT", "item_name_"&i, arrCart(1,i))
					Call WriteLog("PDT", "quantity_"&i, arrCart(2,i))
				Next
			End If
		Else
			nCartItems = 0
		End If
		If bSuccess Then
			sMsg = sMsg & NTK_PAYMENT_SUCCESS_MESSAGE & "<br><br>"
		Else

			' Handle different payment status here
			If sPaymentStatus = "Denied" Or sPaymentStatus = "Failed" Then
				sMsg = sMsg & NTK_PAYMENT_FAIL_MESSAGE & "<br>"
				sMsg = sMsg & "<a href=""" & RootPath & NTK_DEFAULT_PAGE & """>" & NTK_TRY_AGAIN & "</a><br><br>"
			Else
				If sPaymentStatus = "" Then sPaymentStatus = "Unknown"
				sMsg = sMsg & NTK_PAYMENT_STATUS_MESSAGE & " '" & sPaymentStatus & "'.<br>"
				sMsg = sMsg & NTK_PAYMENT_CONTACT_MESSAGE & "<a href=""mailto:" & NTK_BUSINESS & """>" & NTK_BUSINESS & "</a>.<br><br>"
			End If
		End If
		If NTK_DownloadApproval Then
			sMsg = sMsg & NTK_DOWNLOAD_MESSAGE & "<br><br>"
		Else
			If bSuccess Then
				Dim item_number, item_name, quantity, folder, file, url
				folder = GetTempFolder(sTx) ' Get temp folder based on tx

				' Show download links for all purchased items
				For i = 1 to nCartItems
					item_number = arrCart(0,i)
					item_name = arrCart(1,i)
					quantity = arrCart(2,i)
					If NTK_DigitalDownload Then
						file = GetDownloadFile(item_number)
					Else
						file = ""
					End If
					If file <> "" Then
						url = CopyTempFile(folder, file) ' Get rul
					Else
						url = ""
					End If
					If url <> "" Then

						' Audit trail for reference
						Call WriteLog("PDT", "url_"&i, url)
						sMsg = sMsg & NTK_ITEM_NUMBER & " " & item_number & "<br>"
						sMsg = sMsg & NTK_ITEM_NAME & " " & item_name & "<br>"
						sMsg = sMsg & NTK_QUANTITY & " " & quantity & "<br>"
						sMsg = sMsg & NTK_CLICK_TO_DOWNLOAD & " " & "<a href=""" & url & """ target=""_blank"">" & NTK_DOWNLOAD & "</a><br><br>"
					Else
						sMsg = sMsg & NTK_ITEM_NUMBER & " " & item_number & "<br>"
						sMsg = sMsg & NTK_ITEM_NAME & " " & item_name & "<br>"
						sMsg = sMsg & NTK_QUANTITY & " " & quantity & "<br><br>"
					End If
				Next
			End If
		End If
	End If
Else
	sMsg = sMsg & NTK_PDT_THANKYOU_MESSAGE & "<br><br>"
End If
If sMsg <> "" Then
	Response.Write "<span class=""paypalsb"">" & sMsg & "</span>"
End If
Function GetTransaction(tx)
	Dim sCmd, sIdentityToken
	Dim url, method, posdata
	Dim sName
	sCmd = "_notify-synch"
	sIdentityToken = NTK_IDENTITY_TOKEN
	url = NTK_PAYPAL_URL
	method = "POST"
	postdata = ""
	For Each sName in Request.Form
		If postdata <> "" Then postdata = postdata & "&"
		postdata = postdata & sName & "=" & Request.Form(sName)
	Next
	postdata = postdata & "&cmd=" & Server.URLEncode(sCmd) & "&at=" & Server.URLEncode(sIdentityToken) & "&tx=" & Server.URLEncode(tx)
	GetTransaction = GetContent(url, method, postdata)
End Function
%>
<%
Function GetPDTValue(key)
	Dim sLine, i, idx
	Dim sKey, sValue
	GetPDTValue = "" ' Initialize
	For i = 0 to UBound(arTxDetails)
		sLine = Trim(arTxDetails(i))
		idx = InStr(sLine, "=")
		If idx > 0 Then
			sKey = Left(sLine, idx-1)
			If LCase(sKey) = LCase(key) Then
				GetPDTValue = URLDecode(Mid(sLine, idx+1))
				Exit For
			End If
		End If
	Next
End Function

'------------------------------------------------------
' URL decode to retrieve the original value

Function URLDecode(sConvert)
	Dim aSplit
	Dim sOutput
	Dim I
	If IsNull(sConvert) Then
		URLDecode = ""
		Exit Function
	End If

	' convert all pluses to spaces
	sOutput = REPLACE(sConvert, "+", " ")

	' next convert %hexdigits to the character
	aSplit = Split(sOutput, "%")
	If IsArray(aSplit) Then
		sOutput = aSplit(0)
		For I = 0 to UBound(aSplit) - 1
			sOutput = sOutput & _
			Chr("&H" & Left(aSplit(i + 1), 2)) &_
			Right(aSplit(i + 1), Len(aSplit(i + 1)) - 2)
		Next
	End If
	URLDecode = sOutput
End Function

'Function FoundCartItem(item_number, item_name)
Function FoundCartItem(item_name)
	Dim i
	FoundCartItem = False
	For i = 1 to nCartItems

		'If (item_number = arrCart(0,i)) And _
		   '(item_name = arrCart(1,i)) Then

		If (item_name = arrCart(1,i)) Then
		   FoundCartItem = True
		   Exit Function
		End If
	Next
End Function
%>
		<p>&nbsp;</p>
	</td>
	<!-- End Content column -->
	<!-- Begin Cart column -->
	<td width="20%" valign="top" class="ntkRightColumn">			
	<p><b><div class="paypalsb">Shopping Cart</div></b></p>
	<script language="JavaScript">
	<!--
	CartView(0);
	CartButton(0);
	//-->
	</script>			
	</td>
	<!-- End Cart column -->
</tr>
</table>
<!-- Footor -->
<table width="100%" border="0" cellspacing="0" cellpadding="0">	
	<tr class="ntkBottomRow">		
		<td>&nbsp;<!-- Note: Only licensed users are allowed to remove or change the following copyright statement. -->
			<span class="paypalsb" style="color: #808080;">&copy;2005-2007 NTK Software. All rights reserved.</span>		
		</td>
	</tr>
</table>
</body>
</html>
