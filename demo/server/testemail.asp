<!--#include file="asp/config.asp"-->
<!--#include file="asp/ntkfn.asp"-->
<% 
sFrEmail = Request.Form("from")
sToEmail = Request.Form("to")
sCcEmail = Request.Form("cc")
sBccEmail = Request.Form("bcc")
sSubject = Request.Form("subject")
sMail = Request.Form("body")
sFormat = Request.Form("format")

If Trim(sFrEmail) = "" Then
	Response.Write "Missing sender email."
	Response.End
End If

If Trim(sToEmail) = "" Then
	Response.Write "Missing recipient email."
	Response.End
End If

If Send_Email(sFrEmail, sToEmail, sCcEmail, sBccEmail, sSubject, sMail, sFormat) Then
	Response.Write "Email sent."
Else
	Response.Write "Falied to send email. Error: " & Err.Description
End If
%>