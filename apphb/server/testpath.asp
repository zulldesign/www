<%
If LCase(Request.ServerVariables("HTTPS")) = "off" Then
	URL = "http://"
Else
	URL = "https://"
End If
URL = URL & Request.ServerVariables("SERVER_NAME")
URL = URL & Request.ServerVariables("URL")
Response.Write "<b>Current URL:</b> <font color=#0000FF>" & URL & "</font><br>"
Pos = InStrRev(URL, "/")
Path = Left(URL, Pos)
FullPath = Path & "asp/ipn.asp"
Response.Write "<b>Notify URL for IPN:</b> <a href='" & FullPath & "'>" & FullPath & "</a><br>"
FullPath = Path & "asp/pdt.asp"
Response.Write "<b>Auto Return URL for PDT:</b> <a href='" & FullPath & "'>" & FullPath & "</a><br>"
Response.Write "Click above URL to check if it is valid."
%>
