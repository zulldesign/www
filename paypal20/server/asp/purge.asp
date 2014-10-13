<!--#include file="config.asp"-->
<!--#include file="ntkfn.asp"-->
<%
a = Request.QueryString("a")
If (a = "purge") Then
	Call CleanUp()
%>
<p><%=NTK_PURGE_SUCCESS%></p>
<%
Else
%>
<p><%=NTK_CLICK_TO_PURGE%> <a href="purge.asp?a=purge"><%=NTK_PURGE%></a></p>
<%
End If

' Clean up old folders
Sub CleanUp()
On Error Resume Next
	Dim sDldPath
	sDldPath = Trim(NTK_DOWNLOAD_PATH)
	sDldPath = Replace(sDldPath, "/", "\")
	If Right(sDldPath, 1) <> "\" Then sDldPath = sDldPath & "\"
	' Clean up old folders first
	Call CleanupOldFolders(sDldPath)
End Sub
%>