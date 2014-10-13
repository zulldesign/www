<!--#include file="config.asp"-->
<!--#include file="ntkfn.asp"-->
<%
Const nAtt = 3
Dim arrCart()
Dim nCartItems

Dim sTx
Dim sBusiness, sPaymentStatus, sPayerEmail
Dim testipn, IsTesting, testind
Dim amount
Dim i
Dim item

Dim sMail

If NTK_IPN_ENABLED Then

	If VerifiedTransaction() Then

		sTx = Request.Form("txn_id")
		sBusiness = Request.Form("receiver_email")
		sPaymentStatus = Request.Form("payment_status")
		sPayerEmail = Request.Form("payer_email")
		testipn = Request.Form("test_ipn") ' ***
		amount = Request.Form("mc_gross") ' ***
		If testipn = "1" Then
			testind = " " & NTK_TEST_TRANSACTION
		Else
			testind = ""
		End If

		' Audit trail for reference
		Call WriteLog("IPN", "txn_id", sTx)
		Call WriteLog("IPN", "business", sBusiness)
		Call WriteLog("IPN", "status", sPaymentStatus)
		Call WriteLog("IPN", "payer_email", sPayerEmail)
		Call WriteLog("IPN", "test_ipn", testipn)
		Call WriteLog("IPN", "mc_gross", amount)

		If sPaymentStatus = "Completed" And LCase(sBusiness) = LCase(NTK_BUSINESS) Then
			item = Request.Form("item_number")
			If item <> "" Then
				nCartItems = 1
				Redim arrCart(nAtt, nCartItems)
				arrCart(0,1) = item
				arrCart(1,1) = Request.Form("item_name")
				arrCart(2,1) = Request.Form("quantity")
			Else
				nCartItems = Request.Form("num_cart_items")
				Redim arrCart(nAtt, nCartItems)
				For i = 1 to nCartItems
					arrCart(0,i) = Request.Form("item_number"&i)
					arrCart(1,i) = Request.Form("item_name"&i)
					arrCart(2,i) = Request.Form("quantity"&i)
				Next
			End If
		'Else
			'nCartItems = 0
		'End If

			Dim item_number, item_name, quantity, folder, file, url

			folder = GetTempFolder(sTx) ' Get temp folder based on tx

			sMail = ""
			For i = 1 to nCartItems
				If sMail <> "" Then sMail = sMail & vbCrLf & vbCrLf ' separate between items
				item_number = arrCart(0,i)
				item_name = arrCart(1,i)
				quantity = arrCart(2,i)
				If NTK_DigitalDownload Then
					file = GetDownloadFile(item_number)
				Else
					file = ""
				End If
				If file <> "" Then
					url = CopyTempFile(folder, file)
					url = GetServerUrl & url
					Call WriteLog("IPN", "item_number_"&i, item_number)
					Call WriteLog("IPN", "item_name_"&i, item_name)
					Call WriteLog("IPN", "quantity_"&i, quantity)
					Call WriteLog("IPN", "url_"&i, url)
					sMail = sMail & NTK_ITEM_NUMBER & item_number & vbCrLf & _
									NTK_ITEM_NAME & item_name & vbCrLf & _
									NTK_QUANTITY & quantity & vbCrLf & _
									NTK_DOWNLOAD_URL & url
				Else
					Call WriteLog("IPN", "item_number_"&i, item_number)
					Call WriteLog("IPN", "item_name_"&i, item_name)
					Call WriteLog("IPN", "quantity_"&i, quantity)
					sMail = sMail & NTK_ITEM_NUMBER & item_number & vbCrLf & _
									NTK_ITEM_NAME & item_name & vbCrLf & _
									NTK_QUANTITY & quantity
				End If
			Next

			' Requires approval
			If NTK_DownloadApproval Then

				' Write notify email text to file
				Dim sContent
				sContent = LoadTxt("ipn.txt")
				sContent = Replace(sContent, "<!--$From-->", NTK_SENDER_EMAIL) ' Replace sender
				sContent = Replace(sContent, "<!--$To-->", sPayerEmail) ' Replace receiver
				sContent = Replace(sContent, "<!--$Txn_ID-->", sTx) ' Replace transaction id
				sContent = Replace(sContent, "<!--$OrderDetails-->", sMail) ' Replace order details
				file = "notify_" & sTx & ".txt"
				Call WriteFile(folder, file, sContent)
				' Send email to seller for approval
				Call LoadEmail("approval.txt")
				sEmailFrom = Replace(sEmailFrom, "<!--$From-->", NTK_SENDER_EMAIL) ' Replace sender
				sEmailTo = Replace(sEmailTo, "<!--$To-->", NTK_RECIPIENT_EMAIL) ' Replace receiver
				sEmailSubject = Replace(sEmailSubject, "<!--$Txn_ID-->", sTx) ' Replace transaction id
				sEmailSubject = sEmailSubject & testind ' ***
				sEmailContent = Replace(sEmailContent, "<!--$Txn_ID-->", sTx) ' Replace transaction id
				sEmailContent = Replace(sEmailContent, "<!--$OrderAmount-->", amount) ' Replace order amount
				sEmailContent = Replace(sEmailContent, "<!--$OrderDetails-->", sMail) ' Replace order details
				url = GetServerUrl & GetCurrentPathInfo & "/approval.asp?tx=" & sTx & "&testipn=" & testipn
				sEmailContent = Replace(sEmailContent, "<!--$ApprovalUrl-->", url) ' Replace approval url
				If Send_Email(sEmailFrom, sEmailTo, sEmailCc, sEmailBcc, sEmailSubject, sEmailContent, sEmailFormat) Then
					Call WriteLog("IPN", NTK_EMAIL_SENT_TO, sEmailTo)
					If sEmailCc <> "" Then
						Call WriteLog("IPN", NTK_EMAIL_SENT_TO, sEmailCc)
					End If
					If sEmailBcc <> "" Then
						Call WriteLog("IPN", NTK_EMAIL_SENT_TO, sEmailBcc)
					End If
				Else
					Call WriteLog("IPN", NTK_EMAIL_SENT_TO, sEmailTo)
					If sEmailCc <> "" Then
						Call WriteLog("IPN", NTK_EMAIL_SENT_TO, sEmailCc)
					End If
					If sEmailBcc <> "" Then
						Call WriteLog("IPN", NTK_EMAIL_SENT_TO, sEmailBcc)
					End If
					Call WriteLog("IPN", NTK_EMAIL_SENT_ERROR, Err.Description)
				End If

			' Send notification email directly
			Else

				Call LoadEmail("ipn.txt")
				sEmailFrom = Replace(sEmailFrom, "<!--$From-->", NTK_SENDER_EMAIL) ' Replace sender
				sEmailTo = Replace(sEmailTo, "<!--$To-->", sPayerEmail) ' Replace receiver
				sEmailSubject = Replace(sEmailSubject, "<!--$Txn_ID-->", sTx) ' Replace transaction id
				sEmailSubject = sEmailSubject & testind ' ***
				If sEmailBcc <> "" Then sEmailBcc = sEmailBcc & ";"
				sEmailBcc = sEmailBcc & NTK_RECIPIENT_EMAIL ' Bcc recipient
				sEmailContent = Replace(sEmailContent, "<!--$Txn_ID-->", sTx) ' Replace transaction id
				sEmailContent = Replace(sEmailContent, "<!--$OrderDetails-->", sMail) ' Replace order details
				If Send_Email(sEmailFrom, sEmailTo, sEmailCc, sEmailBcc, sEmailSubject, sEmailContent, sEmailFormat) Then
					Call WriteLog("IPN", NTK_EMAIL_SENT_TO, sEmailTo)
					If sEmailCc <> "" Then
						Call WriteLog("IPN", NTK_EMAIL_SENT_TO, sEmailCc)
					End If
					If sEmailBcc <> "" Then
						Call WriteLog("IPN", NTK_EMAIL_SENT_TO, sEmailBcc)
					End If
				Else
					Call WriteLog("IPN", NTK_EMAIL_SENT_TO, sEmailTo)
					If sEmailCc <> "" Then
						Call WriteLog("IPN", NTK_EMAIL_SENT_TO, sEmailCc)
					End If
					If sEmailBcc <> "" Then
						Call WriteLog("IPN", NTK_EMAIL_SENT_TO, sEmailBcc)
					End If
					Call WriteLog("IPN", NTK_EMAIL_SENT_ERROR, Err.Description)
				End If

			End If

		End If

	Else
		Response.Write NTK_IPN_PAGE_MESSAGE
	End If

Else
	Response.Write NTK_IPN_THANKYOU_MESSAGE
End If
%>
<%
Function VerifiedTransaction()

	Dim sCmd
	Dim url, method, posdata
	Dim sName, sStatus

	sCmd = "_notify-validate"

	url = NTK_PAYPAL_URL
	method = "POST"
	postdata = ""
	For Each sName in Request.Form
		If postdata <> "" Then postdata = postdata & "&"
		postdata = postdata & sName & "=" & Request.Form(sName)
	Next
	If postdata <> "" Then
		postdata = postdata & "&"
		postdata = postdata & "cmd=" & Server.URLEncode(sCmd)
		sStatus = GetContent(url, method, postdata)
	End If

	VerifiedTransaction = (sStatus = "VERIFIED")

End Function
%>