<%
Set fso = Server.CreateObject("Scripting.FileSystemObject")
If Not fso.FileExists(Server.Mappath("asp/config.asp")) Then
	Response.Write "Missing required ASP files, please:<br \>"
	Response.Write "1. Select ""ASP"" under ""Options"" -> ""ASP/PHP"" tab,<br \>"
	Response.Write "2. Enter the email setting under ""Options"" -> ""Email"" tab,<br \>"
	Response.Write "3. Generate scripts again and upload the ""server"" folder again."
	Set fso = Nothing
	Response.End
End If
%>
<!--#include file="asp/config.asp"-->
<!--#include file="asp/ewfn.asp"-->
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

If ew_SendEmail(sFrEmail, sToEmail, sCcEmail, sBccEmail, sSubject, sMail, sFormat) Then
	Response.Write "Email sent."
Else
	Response.Write "Falied to send email. Error: " & Err.Description
End If
%>