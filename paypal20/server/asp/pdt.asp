<!--##SESSION pdt_include##-->
<!--#include file="config.asp"-->
<!--#include file="ntkfn.asp"-->
<!--##/SESSION##-->

<!--##SESSION pdt_content##-->
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
<!--##/SESSION##-->