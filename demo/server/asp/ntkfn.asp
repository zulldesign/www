<%
' Function to Load Email Content from input file name
' - Content Loaded to the following variables
' - Subject: sEmailSubject
' - From: sEmailFrom
' - To: sEmailTo
' - Cc: sEmailCc
' - Bcc: sEmailBcc
' - Format: sEmailFormat
' - Content: sEmailContent
'
Dim sEmailFrom, sEmailTo, sEmailCc, sEmailBcc, sEmailSubject, sEmailFormat, sEmailContent
sEmailFrom = "": sEmailTo = "": sEmailCc = "": sEmailBcc = "": sEmailSubject = "": sEmailFormat = "": sEmailContent = ""
Sub LoadEmail(fn)

	Dim sWrk, sHeader, arrHeader
	Dim sName, sValue
	Dim i, j

	' Initialize
	sEmailFrom = "": sEmailTo = "": sEmailCc = "": sEmailBcc = "": sEmailSubject = "": sEmailFormat = "": sEmailContent = ""

	sWrk = LoadTxt(fn) ' Load text file content
	sWrk = Replace(sWrk, vbCrLf, vbLf) ' Convert to Lf
	sWrk = Replace(sWrk, vbCr, vbLf) ' Convert to Lf
	If sWrk <> "" Then
		' Locate Header & Mail Content
		i = InStr(sWrk, vbLf&vbLf)
		If i > 0 Then
			sHeader = Mid(sWrk, 1, i)
			sEmailContent = Mid(sWrk, i+2)
			arrHeader = Split(sHeader, vbLf)
			For j = 0 to UBound(arrHeader)
				i = InStr(arrHeader(j), ":")
				If i > 0 Then
					sName = Trim(Mid(arrHeader(j), 1, i-1))
					sValue = Trim(Mid(arrHeader(j), i+1))
					Select Case LCase(sName)
						Case "subject": sEmailSubject = sValue
						Case "from": sEmailFrom = sValue
						Case "to": sEmailTo = sValue
						Case "cc": sEmailCc = sValue
						Case "bcc": sEmailBcc = sValue
						Case "format": sEmailFormat = sValue
					End Select
				End If
			Next
		End If
	End If

End Sub

' Function to Load a Text File
Function LoadTxt(fn)

	Dim fso, fobj, wrkfn

	' Get text file content
	If InStr(fn, "/") > 0 Or InStr(fn, "\") > 0 Then
		wrkfn = fn ' assume full path given
	Else
		wrkfn = Server.MapPath(fn) ' assume in current folder
	End If
	Set fso = Server.CreateObject("Scripting.FileSystemObject")
	If fso.FileExists(wrkfn) Then
		Set fobj = fso.OpenTextFile(wrkfn)
		LoadTxt = fobj.ReadAll ' Read all Content
		fobj.Close
		Set fobj = Nothing
	Else
		LoadTxt = ""
	End If
	Set fso = Nothing

End Function

' Function to Send out Email
' Supports CDO, w3JMail and ASPEmail
Function Send_Email(sFrEmail, sToEmail, sCcEmail, sBccEmail, sSubject, sMail, sFormat)

    On Error Resume Next
    
    Dim objMail, sServerVersion, sIISVer, EmailComponent
    
    sServerVersion = Request.ServerVariables("SERVER_SOFTWARE")
    If InStr(sServerVersion, "Microsoft-IIS") > 0 Then
        i = InStr(sServerVersion, "/")
        If i > 0 Then
            sIISVer = Trim(Mid(sServerVersion, i+1))
        End If
    End If
    
    Dim arCDO, arASPEmail, arw3JMail, arEmailComponent
    
    arw3JMail = Array("w3JMail", "JMail.Message")
    arASPEmail = Array("ASPEmail", "Persits.MailSender")
    If sIISVer < "5.0" Then ' NT using CDONTS
        arCDO = Array("CDO", "CDONTS.NewMail")
    Else ' 2000 / XP / 2003 using CDO
        arCDO = Array("CDO", "CDO.Message")
    End If
    
    ' Change your precedence here
    arEmailComponent = Array(arw3JMail, arASPEmail, arCDO)
    
    For i = 0 to UBound(arEmailComponent)
        Err.Clear
        Set objMail = Server.CreateObject(arEmailComponent(i)(1))
        If Err.Number = 0 Then
            EmailComponent = arEmailComponent(i)(0)
            Exit For
        End If
    Next
    
    If Err.Number <> 0 Then
        Send_Email = False
        Exit Function
    End If
    
    Dim arrEmail, i, sEmail

    If EmailComponent = "w3JMail" Then
        
        'Set objMail = Server.CreateObject("JMail.Message")
        objMail.Logging = True
        objMail.Silent = True
        objMail.From = sFrEmail
        arrEmail = Split(Replace(sToEmail, ",", ";"), ";")
        For i = 0 to UBound(arrEmail)
            sEmail = Trim(arrEmail(i))
            If sEmail <> "" Then
                objMail.AddRecipient sEmail
            End If
        Next
        arrEmail = Split(Replace(sCcEmail, ",", ";"), ";")
        For i = 0 to UBound(arrEmail)
            sEmail = Trim(arrEmail(i))
            If sEmail <> "" Then
                objMail.AddRecipientCC sEmail
            End If
        Next
        arrEmail = Split(Replace(sBccEmail, ",", ";"), ";")
        For i = 0 to UBound(arrEmail)
            sEmail = Trim(arrEmail(i))
            If sEmail <> "" Then
                objMail.AddRecipientBCC sEmail
            End If
        Next
        objMail.Subject = sSubject
        If LCase(sFormat) = "html" Then
            objMail.HTMLBody = sMail
        Else
            objMail.Body = sMail
        end if
        If NTK_SMTPSERVER_USERNAME <> "" And NTK_SMTPSERVER_PASSWORD <> "" Then
            objMail.MailServerUserName = NTK_SMTPSERVER_USERNAME
            objMail.MailServerPassword = NTK_SMTPSERVER_PASSWORD
        End If
        Send_Email = objMail.Send(NTK_SMTPSERVER)
        If Not Send_Email Then
            Err.Raise vbObjectError + 1, EmailComponent, objMail.Log
        End If
        Set objMail = nothing
    
    ElseIf EmailComponent = "ASPEmail" Then
        
        'Set objMail = Server.CreateObject("Persits.MailSender")
        objMail.From = sFrEmail
        arrEmail = Split(Replace(sToEmail, ",", ";"), ";")
        For i = 0 to UBound(arrEmail)
            sEmail = Trim(arrEmail(i))
            If sEmail <> "" Then
                objMail.AddAddress sEmail
            End If
        Next
        arrEmail = split(Replace(sCcEmail, ",", ";"), ";")
        For i = 0 to UBound(arrEmail)
            sEmail = Trim(arrEmail(i))
            If sEmail <> "" Then
                objMail.AddCC sEmail
            End If
        Next
        arrEmail = split(Replace(sBccEmail, ",", ";"), ";")
        For i = 0 to UBound(arrEmail)
            sEmail = Trim(arrEmail(i))
            If sEmail <> "" Then
                objMail.AddBcc sEmail
            End If
        Next
        If LCase(sFormat) = "html" Then
            objMail.IsHTML = True ' html
        Else
            objMail.IsHTML = False ' text
        End If
        objMail.Subject = sSubject
        objMail.Body = sMail
        objMail.Host = NTK_SMTPSERVER
        If NTK_SMTPSERVER_USERNAME <> "" And NTK_SMTPSERVER_PASSWORD <> "" Then
            objMail.Username = NTK_SMTPSERVER_USERNAME
            objMail.Password = NTK_SMTPSERVER_PASSWORD
        End If
        Send_Email = objMail.Send
        Set objMail = Nothing
    
    ElseIf EmailComponent = "CDO" Then
    
        Dim objConfig, sSmtpServer, iSmtpServerPort
    
        If sIISVer < "5.0" Then ' NT using CDONTS
            'Set objMail = Server.CreateObject("CDONTS.NewMail")
            objMail.From = sFrEmail
            objMail.To = Replace(sToEmail, ",", ";")
            If sCcEmail <> "" Then
                objMail.Cc = Replace(sCcEmail, ",", ";")
            End If
            If sBccEmail <> "" Then
                objMail.Bcc = Replace(sBccEmail, ",", ";")
            End If
            If LCase(sFormat) = "html" Then
                objMail.BodyFormat = 0 ' 0 means HTML format, 1 means text
                objMail.MailFormat = 0 ' 0 means MIME, 1 means text
            End If
            objMail.Subject = sSubject
            objMail.Body = sMail
            objMail.Send
            Set objMail = Nothing
        Else ' 2000 / XP / 2003 using CDO
            'Set objMail = Server.CreateObject("CDO.Message")
            sSmtpServer = NTK_SMTPSERVER
            iSmtpServerPort = NTK_SMTPSERVER_PORT
            If (sIISVer < "6.0") Or (sSmtpServer <> "" And LCase(sSmtpServer) <> "localhost") Then ' XP or not localhost
                ' Set up Configuration
                Set objConfig = CreateObject("CDO.Configuration")
                objConfig.Fields("http://schemas.microsoft.com/cdo/configuration/sendusing") = 2 ' cdoSendUsingMethod = cdoSendUsingPort
                objConfig.Fields("http://schemas.microsoft.com/cdo/configuration/smtpserver") = sSmtpServer ' cdoSMTPServer
                objConfig.Fields("http://schemas.microsoft.com/cdo/configuration/smtpserverport") = iSmtpServerPort ' cdoSMTPServerPort
                If NTK_SMTPSERVER_USERNAME <> "" And NTK_SMTPSERVER_PASSWORD <> "" Then
                    objConfig.Fields("http://schemas.microsoft.com/cdo/configuration/smtpauthenticate") = 1 'cdoBasic (clear text)
                    objConfig.Fields("http://schemas.microsoft.com/cdo/configuration/sendusername") = NTK_SMTPSERVER_USERNAME
                    objConfig.Fields("http://schemas.microsoft.com/cdo/configuration/sendpassword") = NTK_SMTPSERVER_PASSWORD
                End If
                objConfig.Fields.Update
                Set objMail.Configuration = objConfig ' Use Configuration
            End If
            objMail.From = sFrEmail
            objMail.To = Replace(sToEmail, ",", ";")
            If sCcEmail <> "" Then
                objMail.Cc = Replace(sCcEmail, ",", ";")
            End If
            If sBccEmail <> "" Then
                objMail.Bcc = Replace(sBccEmail, ",", ";")
            End If
            If LCase(sFormat) = "html" Then
                objMail.HtmlBody = sMail
            Else
                objMail.TextBody = sMail
            End If
            objMail.Subject = sSubject
            objMail.Send
            Set objMail = Nothing
            Set objConfig = Nothing
        End If
        
        Send_Email = (Err.Number = 0)
        
    End If

End Function

' Function for Writing log
Sub WriteLog(pfx, key, value)
	On Error Resume Next
	Dim fso, ts, folder, file
	Dim bWriteHeader, sHeader, sMsg
	sHeader = "date" & vbTab & _
		"time" & vbTab & _
		"key" & vbTab & _
		"value"
	sMsg = Date & vbTab & _
		Time & vbTab & _
		key & vbTab & _
		value
	folder = GetRootFolder & "\" & NTK_LOG_FOLDER
	file = pfx & "_" & ZeroPad(Year(Date), 4) & ZeroPad(Month(Date), 2) & ZeroPad(Day(Date), 2) & ".txt"
	Set fso = Server.Createobject("Scripting.FileSystemObject")
	If Not fso.FolderExists(folder) Then fso.CreateFolder(folder)
	bWriteHeader = Not fso.FileExists(folder & "\" & file)
	Set ts = fso.OpenTextFile(folder & "\" & file, 8, True)
	If bWriteHeader Then
		ts.writeline(sHeader)
	End If
	ts.writeline(sMsg)
	ts.Close
	Set ts = Nothing
	Set fso = Nothing
End Sub

' Function for Writing to file
Sub WriteFile(folder, file, content)
	On Error Resume Next
	Dim fso, ts, wrkfile
	If folder <> "" Then
		wrkfile = GetRootFolder & "\" & folder & "\" & file
	Else
		wrkfile = Server.MapPath(file)
	End If
	Set fso = Server.Createobject("Scripting.FileSystemObject")
	Set ts = fso.OpenTextFile(wrkfile, 8, True)
	ts.writeline(content)
	ts.Close
	Set ts = Nothing
	Set fso = Nothing
End Sub

' Pad zeros before number
Function ZeroPad(m, t)
  ZeroPad = String(t - Len(m), "0") & m
End Function

'-------------------------------------------------------------------------------
' Get content using XMLHttp
' url = destination url
' method = "GET", "POST"
' postdata = Post Data

Function GetContent(url, method, postdata)

	Dim strResult

	On Error Resume Next
	Dim xmlhttp
	Set xmlhttp = Server.CreateObject("MSXML2.ServerXMLHTTP")
	If Err Then
		Err.Clear
		Set xmlhttp = Server.CreateObject("Microsoft.XMLHTTP")
		If Err Then
			GetContent = ""
			Exit Function
		End If
	End If

	If method = "POST" Then

		' Indicate that page that will receive the request and the
		' type of request being submitted
		xmlhttp.Open "POST", url, False
		
		' Indicate that the body of the request contains form data
		xmlhttp.setRequestHeader "Content-Type", "application/x-www-form-urlencoded"
		
		' Send the data as name/value pairs
		xmlhttp.send postdata
	
	Else
	
		xmlhttp.Open "GET", url, False
		xmlhttp.send

	End If

	If xmlhttp.status = 200 Then
		strResult = xmlhttp.responseText
	Else
		strResult = ""
	End If

	Set xmlhttp = Nothing

	GetContent = strResult

End Function

' creating a temp folder
Function GetTempFolder(tx)
	Dim fso
	Dim sDldPath, sDestPath, sPath

On Error Resume Next
	sDldPath = Trim(NTK_DOWNLOAD_PATH)
	sDldPath = Replace(sDldPath, "/", "\")
	If Right(sDldPath, 1) <> "\" Then sDldPath = sDldPath & "\"
	' Clean up old folders first
	Call CleanupOldFolders(sDldPath)
	' Get the scrambled path for this tx
	sDestPath = Scramble(tx)
	sDestPath = sDldPath & sDestPath
	' Create temp folder
	sPath = GetRootFolder ' get root path
	sPath = sPath & "\" & sDestPath
	Set fso = CreateObject("Scripting.FileSystemObject")
	If Not fso.FolderExists(sPath) Then fso.CreateFolder(sPath)
	GetTempFolder = sDestPath
	Set fso = Nothing

End Function

' Get item download file name
Function GetDownloadFile(item_number)
	Dim n, url
	url = ""
	For n = 0 to nItems - 1
		If (arItems(0, n) = item_number) Then
			url = arItems(1, n)
			Exit For
		End If
	Next
	GetDownloadFile = url
End Function

' Get server url
Function GetServerUrl()
	Dim url
	url = ""
	If Request.ServerVariables("HTTPS") = "off" Then
		url = url & "http://"
	Else
		url = url & "https://"
	End If
	GetServerUrl = url & Request.ServerVariables("SERVER_NAME")
End Function

' copy file to temp folder
Function CopyTempFile(folder, file)
	Dim fso
	Dim sSrcPath, sFromPath, sToPath, url

	sSrcPath = NTK_DOWNLOAD_SRC_PATH
	' Copy file to temp folder
	sFromPath = GetRootFolder ' get root path
	sToPath = sFromPath
	sFromPath = sFromPath & "\" & sSrcPath
	sToPath = sToPath & "\" & folder
	Set fso = CreateObject("Scripting.FileSystemObject")
	If fso.FileExists(sFromPath & "\" & file) Then
		If Not fso.FileExists(sToPath & "\" & file) Then
			fso.CopyFile sFromPath & "\" & file, sToPath & "\" & file
		End If
		CopyTempFile = GetRootPathInfo & "/" & Replace(folder, "\", "/") & "/" & file
	Else
		CopyTempFile = ""
	End If
	Set fso = Nothing

End Function

' Delete old files and folders
Function CleanupOldFolders(path)

	Dim sPath, fso, oRootFolder, oFolders, oSubFolder, oFiles, oFile
	Dim sFolderName, sFileName, sFileExt, sFileType, iFileSize, dFileCreated, dFileModified, dFileAccessed

On Error Resume Next
	sPath = GetRootFolder ' get current path
	sPath = sPath & "\" & path
	Set fso = CreateObject("Scripting.FileSystemObject")

	If fso.FolderExists(sPath) Then
		' Get root folder
		Set oRootFolder = fso.GetFolder(sPath)
		' Process list of subfolders
		Set oFolders = oRootFolder.SubFolders
		For Each oSubFolder in oFolders
			sFolderName = oSubFolder.Name
			If LCase(sFolderName) <> LCase(Right(NTK_LOG_FOLDER, Len(sFolderName))) Then ' do not delete log folder
				Set oFiles = oSubFolder.Files
				For Each oFile in oFiles
					sFileName = oFile.Name
					sFileExt = InStrRev( sFileName, "." )
					If sFileExt < 1 Then sFileExt = "" Else sFileExt = Mid(sFileName, sFileExt+1)
					sFileType = oFile.Type
					iFileSize = oFile.Size
					dFileCreated = oFile.DateCreated
					dFileModified = oFile.DateLastModified
					dFileAccessed = oFile.DateLastAccessed
					' Delete obsolete file
					If CDate(dFileCreated) < DateAdd(NTK_DOWNLOAD_TIMEOUT_UNIT, NTK_DOWNLOAD_TIMEOUT_INTERVAL * -1, Now) Then oFile.Delete
				Next
				' Delete empty folder
				If oFiles.Count = 0 Then oSubFolder.Delete
			End If
		Next
	End If

	Set fso = Nothing

End Function

' Scramble name
' scramble input field to 12 byte name
Function Scramble(str)

	Dim ln
	Dim q, r, i, j, k, wrkint
	Dim tb, wrkstr1, wrkstr2, wrkstr3, istr, ostr, finalstr

	ln = Len(str)
	tb = NTK_RANDOM_KEY

	' Convert input to string of 23 characters
	istr = ""
	If ln = 23 Then
		' Unchanged
		istr = str
	ElseIf ln > 23 Then
		' Pick 23 bytes from string
		For i = 1 to 23
			istr = istr & Mid(str, (ln*i)\23, 1)
		Next
	Else
		' Insert characters evenly
		j = 0
		For i = 1 to 23
			k = i*ln\23
			If k = 0 Then k = 1
			If k = j Then
				istr = istr & Mid(tb, 52-i, 1)
			Else
				istr = istr & Mid(str, k, 1)
				j = k
			End If
		Next
	End If

	ostr = ""
	wrkstr1 = ""
	' Convert to numeric string
	For i = 1 to Len(istr)
		j = Asc(Mid(istr, i, 1))
		wrkstr1 = wrkstr1 & CStr(j)
	Next

	' Scramble for every 5 digits
	i = 1
	Do While i <= Len(wrkstr1)
		wrkstr2 = Mid(wrkstr1, i, 5)
		wrkint = CLng(wrkstr2)
		wrkstr3 = ""
    	Do While wrkint > 0
			q = wrkint \ 52
			r = wrkint Mod 52
			wrkstr3 = Mid(tb, r+1, 1) & wrkstr3
		    wrkint = q
		Loop
		ostr = ostr & wrkstr3
    	i = i + 5
	Loop

	'scramble = outstr
	'pick 12
	ln = Len(ostr)
	finalstr = ""
	For i = 1 to 12
		finalstr = finalstr & Mid(ostr, (ln * i)\12, 1)
	Next
	Scramble = finalstr
	Scramble = str & "_" & Scramble

End Function

Private Function GetRootFolder()
	Dim path
	path = Server.MapPath(".") ' get current folder
	GetRootFolder = ParentPath(path, 2, "\") ' up 2 levels
End Function

Private Function GetRootPathInfo()
	Dim path
	path = GetCurrentPathInfo ' get current path
	GetRootPathInfo = ParentPath(path, 2, "/") ' up 2 levels
End Function

Private Function RootPath()
	RootPath = GetRootPathInfo & "/" ' return root path
End Function

Private Function GetCurrentPathInfo()
	Dim url, path
	url = Request.ServerVariables("SCRIPT_NAME") ' get current script name
	p = InStrRev(url, "/")
	If p > 0 Then
		path = Mid(url, 1, p-1) ' remove script name
	End If
	GetCurrentPathInfo = path
End Function

Function ParentPath(sPath, iLevel, sPathDlm)
	Dim i, p, wrkpath
	wrkpath = sPath
	For i = 1 to iLevel
		p = InStrRev(wrkpath, sPathDlm)
		If (p > 0) Then
			wrkpath = Mid(wrkpath, 1, p-1)
		End If
	Next
	ParentPath = wrkpath
End Function
%>
