<%
On Error Resume Next

Randomize
RndStr = Cstr(Int((999999 - 100000 + 1) * Rnd + 100000))
File = Server.MapPath("download\" & RndStr & ".tmp")

' Try write a file
Set fso = Server.Createobject("Scripting.FileSystemObject")
Set ts = fso.OpenTextFile(File, 8, True)
ts.writeline(RndStr)
ts.Close
Set ts = Nothing

If fso.FileExists(File) Then
	Response.Write "Succeeded to write file in the ""download"" folder.<br>"
	Set TmpFile = fso.GetFile(File)
	TmpFile.Delete
	If fso.FileExists(File) Then
		Response.Write "Error: Failed to delete file in the ""download"" folder.<br>"
	Else
		Response.Write "Succeeded to delete file in the ""download"" folder.<br>"
		Response.Write "The ""download"" folder is properly setup.<br>"
	End If
Else
	p = InStrRev(File, "\")
	If (p > 0) Then	Path = Mid(File, 1, p-1)
	Response.Write "Error: Failed to write file in ""download"" folder.<br>"
	Response.Write "*** Please setup write permission to the folder """ & Path & """ on this server. ***<br>"
End If

Set fso = Nothing
%>
