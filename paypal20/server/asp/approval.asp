<!--#include file="config.asp"-->
<!--#include file="ntkfn.asp"-->
<%
Dim sDldPath, sDestPath, sPath, sFile
Dim sTx, testipn, testind
sTx = Request.QueryString("tx")
testipn = Request.QueryString("testipn") ' ***
If testipn = "1" Then
	testind = " " & NTK_TEST_TRANSACTION
Else
	testind = ""
End If

If sTx <> "" Then
	sDldPath = Trim(NTK_DOWNLOAD_PATH)
	sDldPath = Replace(sDldPath, "/", "\")
	If Right(sDldPath, 1) <> "\" Then sDldPath = sDldPath & "\"
	' Get the scrambled path for this tx
	sDestPath = Scramble(sTx)
	sDestPath = sDldPath & sDestPath
	' Check if download folder exists
	sPath = GetRootFolder ' get root path
	sPath = sPath & "\" & sDestPath
	Set fso = CreateObject("Scripting.FileSystemObject")
	If fso.FolderExists(sPath) Then
		' Check if file exists
		sFile = "notify_" & sTx & ".txt"
		If fso.FileExists(sPath & "\" & sFile) Then
			Call LoadEmail(sPath & "\" & sFile)
			' sEmailFrom already set up
			' sEmailTo already set up
			sEmailSubject = sEmailSubject & testind ' ***
			' Set up Bcc
			If sEmailBcc <> "" Then sEmailBcc = sEmailBcc & ";"
			sEmailBcc = sEmailBcc & NTK_RECIPIENT_EMAIL ' Bcc recipient
			' sEmailContent already set up
			If Send_Email(sEmailFrom, sEmailTo, sEmailCc, sEmailBcc, sEmailSubject, sEmailContent, sEmailFormat) Then
Call WriteLog("Approval", NTK_EMAIL_SENT_TO, sEmailTo)
			Else
Call WriteLog("Approval", NTK_EMAIL_SENT_TO, sEmailTo)
Call WriteLog("Approval", NTK_EMAIL_SENT_ERROR, Err.Description)
			End If
%>
<p><%=NTK_EMAIL_SENT%></p>
<%
		End If
	End If
	Set fso = Nothing
End If
%>
