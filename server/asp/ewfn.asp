<%

' ------------------------------------------------------------------------------
' Functions for PayPal Shop Maker 3+
' (C) 2008-2009 e.World Technology Limited. All rights reserved.
' ------------------------------------------------------------------------------
' Function to check application status

Function ApplicationEnabled()
	Dim WrkConn, WrkRs, WrkSql
	ApplicationEnabled = True
	Set WrkConn = Server.CreateObject("ADODB.Connection")
	WrkConn.Open EW_CONNECTION_STRING
	WrkSql = "SELECT * FROM AppStatus"
	Set WrkRs = WrkConn.Execute(WrkSql)
	If Not WrkRs.EOF Then
		ApplicationEnabled = WrkRs("AppEnabled")
	End If
	WrkRs.Close
	Set WrkRs = Nothing
	WrkConn.Close
	Set WrkConn = Nothing
End Function

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

' Load email template
Sub ew_LoadEmail(fn)
	Dim sWrk
	sWrk = ew_LoadTxt(fn) ' Load text file content
	Call ew_LoadEmailEx(sWrk)
End Sub

' Load email content
Sub ew_LoadEmailEx(content)
	Dim sWrk, sHeader, arrHeader
	Dim sName, sValue
	Dim i, j

	' Initialize
	sEmailFrom = "": sEmailTo = "": sEmailCc = "": sEmailBcc = "": sEmailSubject = "": sEmailFormat = "": sEmailContent = ""
	sWrk = content ' Get content
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
Function ew_LoadTxt(fn)
	Dim fso, fobj, wrkfn

	' Get text file content
	If InStr(fn, "/") > 0 Or InStr(fn, "\") > 0 Then
		wrkfn = fn ' Assume full path given
	Else
		wrkfn = Server.MapPath(fn) ' Assume in current folder
	End If
	Set fso = Server.CreateObject("Scripting.FileSystemObject")
	If fso.FileExists(wrkfn) Then
		Set fobj = fso.OpenTextFile(wrkfn)
		ew_LoadTxt = fobj.ReadAll ' Read all Content
		fobj.Close
		Set fobj = Nothing
	Else
		ew_LoadTxt = ""
	End If
	Set fso = Nothing
End Function

' Function to Send out Email
' Supports CDO, w3JMail and ASPEmail

Function ew_SendEmail(sFrEmail, sToEmail, sCcEmail, sBccEmail, sSubject, sMail, sFormat)
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
        ew_SendEmail = False
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
        If EW_SMTPSERVER_USERNAME <> "" And EW_SMTPSERVER_PASSWORD <> "" Then
            objMail.MailServerUserName = EW_SMTPSERVER_USERNAME
            objMail.MailServerPassword = EW_SMTPSERVER_PASSWORD
        End If
        ew_SendEmail = objMail.Send(EW_SMTPSERVER)
        If Not ew_SendEmail Then
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
        objMail.Host = EW_SMTPSERVER
        If EW_SMTPSERVER_USERNAME <> "" And EW_SMTPSERVER_PASSWORD <> "" Then
            objMail.Username = EW_SMTPSERVER_USERNAME
            objMail.Password = EW_SMTPSERVER_PASSWORD
        End If
        ew_SendEmail = objMail.Send
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
            sSmtpServer = EW_SMTPSERVER
            iSmtpServerPort = EW_SMTPSERVER_PORT
            If (sIISVer < "6.0") Or (sSmtpServer <> "" And LCase(sSmtpServer) <> "localhost") Then ' XP or not localhost

                ' Set up Configuration
                Set objConfig = CreateObject("CDO.Configuration")
                objConfig.Fields("http://schemas.microsoft.com/cdo/configuration/sendusing") = 2 ' cdoSendUsingMethod = cdoSendUsingPort
                objConfig.Fields("http://schemas.microsoft.com/cdo/configuration/smtpserver") = sSmtpServer ' cdoSMTPServer
                objConfig.Fields("http://schemas.microsoft.com/cdo/configuration/smtpserverport") = iSmtpServerPort ' cdoSMTPServerPort
                If EW_SMTPSERVER_USERNAME <> "" And EW_SMTPSERVER_PASSWORD <> "" Then
                    objConfig.Fields("http://schemas.microsoft.com/cdo/configuration/smtpauthenticate") = 1 ' cdoBasic (clear text)
                    objConfig.Fields("http://schemas.microsoft.com/cdo/configuration/sendusername") = EW_SMTPSERVER_USERNAME
                    objConfig.Fields("http://schemas.microsoft.com/cdo/configuration/sendpassword") = EW_SMTPSERVER_PASSWORD
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
        ew_SendEmail = (Err.Number = 0)
    End If
End Function

' Function for writing log
Sub ew_WriteLog(pfx, key, value)
	If EW_WRITE_DATABASE_LOG Then
		Call ew_WriteLogDb(pfx, key, value)
	End If
	Call ew_WriteLogFile(pfx, key, value)
End Sub

' Function for writing to database log table
Sub ew_WriteLogDb(pfx, key, value)
	On Error Resume Next
	Dim curDate, curTime
	curDate = ew_ZeroPad(Year(Date), 4) & "/" & ew_ZeroPad(Month(Date), 2) & "/" & ew_ZeroPad(Day(Date), 2)
	curTime = ew_ZeroPad(Hour(Time), 2) & ":" & ew_ZeroPad(Minute(Time), 2) & ":" & ew_ZeroPad(Second(Time), 2)
	Dim Sql
	Sql = "INSERT INTO " & ew_DbQuote(EW_TABLENAME_LOG) & " (" & _
		ew_DbQuote(EW_FIELDNAME_LOGTYPE) & ", " & _
		ew_DbQuote(EW_FIELDNAME_LOGDATE) & ", " & _
		ew_DbQuote(EW_FIELDNAME_LOGTIME) & ", " & _
		ew_DbQuote(EW_FIELDNAME_LOGKEY) & ", " & _
		ew_DbQuote(EW_FIELDNAME_LOGVALUE) & _
		") VALUES (" & _
		"'" & ew_AdjustSql(pfx) & "', " & _
		"#" & curDate & "#, " & _
		"#" & curTime & "#, " & _
		"'" & ew_AdjustSql(key) & "', " & _
		"'" & ew_AdjustSql(value) & "'" & _
		")"
	Conn.Execute(Sql)
End Sub

' Function for writing to log file
Sub ew_WriteLogFile(pfx, key, value)
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
	folder = ew_GetRootFolder & "\" & EW_LOG_FOLDER
	file = pfx & "_" & ew_ZeroPad(Year(Date), 4) & ew_ZeroPad(Month(Date), 2) & ew_ZeroPad(Day(Date), 2) & ".txt"
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

' Function for writing to file
Sub ew_WriteFile(folder, file, content)
	On Error Resume Next
	Dim fso, ts, wrkfile
	If folder <> "" Then
		wrkfile = ew_GetRootFolder & "\" & folder & "\" & file
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
Function ew_ZeroPad(m, t)
  ew_ZeroPad = String(t - Len(m), "0") & m
End Function

' Get content using XMLHttp
' paramters:
' - url = destination url
' - method = "GET", "POST"
' - postdata = Post Data

Function ew_GetContent(url, method, postdata)
	Dim strResult
	On Error Resume Next
	Dim xmlhttp
	Set xmlhttp = Server.CreateObject("MSXML2.ServerXMLHTTP")
	If Err Then
		Err.Clear
		Set xmlhttp = Server.CreateObject("Microsoft.XMLHTTP")
		If Err Then
			ew_GetContent = ""
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
	ew_GetContent = strResult
End Function

' Creating a temp folder
Function ew_GetTempFolder(tx)
	Dim fso
	Dim sRelPath, sPath
	On Error Resume Next

	' Clean up old folders first
	Call ew_CleanupOldFolders()

	' Create temp folder
	sRelPath = ew_TempDownloadFolder(tx)
	sPath = ew_GetRootFolder & "\" & sRelPath
	Set fso = CreateObject("Scripting.FileSystemObject")
	If Not fso.FolderExists(sPath) Then fso.CreateFolder(sPath)
	ew_GetTempFolder = sRelPath
	Set fso = Nothing
End Function

' Get temp download folder
Function ew_TempDownloadFolder(tx)
	Dim sPath

	' Get download path
	sPath = Trim(EW_DOWNLOAD_PATH)
	sPath = Replace(sPath, "/", "\")
	If Right(sPath, 1) <> "\" Then sPath = sPath & "\"

	' Append scrambled path for this tx
	ew_TempDownloadFolder = sPath & ew_Scramble(tx)
End Function

' Get all item download file name
Sub ew_GetDownloadFiles(tx, arrCart)
	Dim i
	For i = 1 to UBound(arrCart, 2)
		arrCart(nAtt, i) = ew_GetDownloadFile(tx, arrCart(0, i), True)
	Next
End Sub

' Get item download file name
Function ew_GetDownloadFile(tx, item_number, chkfile)
	Dim n, url
	Dim Rs, Sql
	Dim fso, src, selected
	url = ""
	selected = True
	If EW_USE_DATABASE Then
		Sql = "SELECT " & ew_DbQuote(EW_FIELDNAME_ITEMDOWNLOAD) & ", " & _
			ew_DbQuote(EW_FIELDNAME_ITEMDOWNLOADFN) & ", " & _
			ew_DbQuote(EW_FIELDNAME_ITEMSELECTED) & " FROM " & _
			ew_DbQuote(EW_TABLENAME_ITEM) & " WHERE " & _
			ew_DbQuote(EW_FIELDNAME_ITEMNUMBER) & "='" & _
			ew_AdjustSql(item_number) & "'"
		Set Rs = Conn.Execute(Sql)
		If Not Rs.Eof Then
			url = Rs(EW_FIELDNAME_ITEMDOWNLOADFN)
			If url&"" = "" Then url = Rs(EW_FIELDNAME_ITEMDOWNLOAD)
			selected = Rs(EW_FIELDNAME_ITEMSELECTED)
		Else
			Call ew_WriteLog("DownloadFile", "record not found", tx & ": " & item_number) ' debug
		End If
		Rs.Close
		Set Rs = Nothing
	Else
		For n = 0 to nItems - 1
			If (arItems(0, n) = item_number) Then
				url = arItems(1, n)
				Exit For
			End If
		Next
	End If

	' Write error for empty URL
	If url = "" Then
		Call ew_WriteLog("DownloadFile", "item not found", tx & ": " & item_number) ' debug
	End If

	' Check if file exists
	If chkfile And url <> "" Then
		Set fso = Server.CreateObject("Scripting.FileSystemObject")
		src = ew_GetRootFolder & "\" & EW_DOWNLOAD_SRC_PATH & "\" & url
		If Not fso.FileExists(src) Then
			Call ew_WriteLog("DownloadFile", "file not found", tx & ": " & src) ' debug
			If selected Then
				url = ""
			Else
				url = EW_UNREGISTERED_DOWNLOAD
			End If
		End If
		Set fso = Nothing
	End If

	' Return URL
	ew_GetDownloadFile = url
End Function

' Binary write URL
Function ew_BinaryWriteUrl(file)
	Dim fso
	Dim url, fn, curDate, curTime
	fn = ew_GetRootFolder & "\" & EW_DOWNLOAD_SRC_PATH & "\" & file
	Set fso = CreateObject("Scripting.FileSystemObject")
	If fso.FileExists(fn) Then
		curDate = ew_ZeroPad(Year(Date), 4) & "/" & ew_ZeroPad(Month(Date), 2) & "/" & ew_ZeroPad(Day(Date), 2)
		curTime = ew_ZeroPad(Hour(Time), 2) & ":" & ew_ZeroPad(Minute(Time), 2) & ":" & ew_ZeroPad(Second(Time), 2)
		url = "fn=" & fn & "&tl=" & curDate & " " & curTime
		ew_BinaryWriteUrl = ew_GetCurrentPathInfo & "/ewdnld.asp?data=" & Server.URLEncode(teaEncrypt(url, EW_RANDOM_KEY))
	Else
		ew_BinaryWriteUrl = ""
	End If
End Function

' Write transaction data to database
Sub ew_WriteTxnData(sTx, rawdata, formdata)
	On Error Resume Next
	Dim Sql
	Sql = "INSERT INTO " & ew_DbQuote(EW_TABLENAME_TXN) & " (" & _
		ew_DbQuote(EW_FIELDNAME_TXNPPID) & ", " & _
		ew_DbQuote(EW_FIELDNAME_TXNRAWDATA) & ", " & _
		ew_DbQuote(EW_FIELDNAME_TXNDATA) & _
		") VALUES (" & _
		"'" & ew_AdjustSql(sTx) & "', " & _
		"'" & ew_AdjustSql(rawdata) & "', " & _
		"'" & ew_AdjustSql(formdata) & "'" & _
		")"
	Conn.Execute(Sql)
End Sub

' Update transaction record in database
Sub ew_WriteTxn(sTx, sBusiness, sPaymentStatus, sPayerEmail, testipn, amt)
	On Error Resume Next
	Dim Sql
	If testipn <> "1" Then testipn = "0"
	If amt = "" Or Not IsNumeric(amt) Then amt = 0
	Sql = "UPDATE " & ew_DbQuote(EW_TABLENAME_TXN) & " SET " & _
		ew_DbQuote(EW_FIELDNAME_TXNBUSINESS) & " = '" & ew_AdjustSql(sBusiness) & "', " & _
		ew_DbQuote(EW_FIELDNAME_TXNSTATUS) & " = '" & ew_AdjustSql(sPaymentStatus) & "', " & _
		ew_DbQuote(EW_FIELDNAME_TXNPAYEREMAIL) & " = '" & ew_AdjustSql(sPayerEmail) & "', " & _
		ew_DbQuote(EW_FIELDNAME_TXNTESTIPN) & " = " & testipn & ", " & _
		ew_DbQuote(EW_FIELDNAME_TXNMCGROSS) & " = " & amt & _
		" WHERE " & _
		ew_DbQuote(EW_FIELDNAME_TXNPPID) & " = '" & ew_AdjustSql(sTx) & "'"
	Conn.Execute(Sql)
End Sub

' Get item count in database
Function ew_GetItemCount(itemno)
	On Error Resume Next
	If itemno = "" Then
		ew_GetItemCount = 0
	Else
		Dim Sql, Rs
		Sql = "SELECT " & ew_DbQuote(EW_FIELDNAME_ITEMCOUNT) & " FROM " & ew_DbQuote(EW_TABLENAME_ITEM) & _
			" WHERE " & ew_DbQuote(EW_FIELDNAME_ITEMNUMBER) & " = '" & ew_AdjustSql(itemno) & "'"
		Set Rs = Conn.Execute(Sql)
		ew_GetItemCount = Rs(0)
	End If
End Function

' Update item count in database
Sub ew_UpdateItemCount(itemno, qty)
	On Error Resume Next
	Dim Sql
	If itemno = "" Then Exit Sub
	If qty = "" Or Not IsNumeric(qty) Then Exit Sub
	Sql = "UPDATE " & ew_DbQuote(EW_TABLENAME_ITEM) & " SET " & _
		ew_DbQuote(EW_FIELDNAME_ITEMCOUNT) & " = " & _
		ew_DbQuote(EW_FIELDNAME_ITEMCOUNT) & " - " & qty & _
		" WHERE " & _
		ew_DbQuote(EW_FIELDNAME_ITEMNUMBER) & " = '" & ew_AdjustSql(itemno) & "'"
	Conn.Execute(Sql)
End Sub

' Get server URL
Function ew_GetServerUrl()
	Dim url
	url = ""
	If Request.ServerVariables("HTTPS") = "off" Then
		url = url & "http://"
	Else
		url = url & "https://"
	End If
	ew_GetServerUrl = url & Request.ServerVariables("SERVER_NAME")
End Function

' Copy file to temp folder
Function ew_CopyTempFile(folder, file)
	Dim fso
	Dim sSrcPath, sFromPath, sToPath, url
	Dim filename, i

	' Get file name
	filename = Replace(file, "/", "\")
	i = InstrRev(filename, "\")
	If i > 0 Then
		filename = Mid(filename, i+1)
	End If
	sSrcPath = EW_DOWNLOAD_SRC_PATH

	' Copy file to temp folder
	sFromPath = ew_GetRootFolder ' Get root path
	sToPath = sFromPath
	sFromPath = sFromPath & "\" & sSrcPath
	sToPath = sToPath & "\" & folder
	Set fso = CreateObject("Scripting.FileSystemObject")
	If fso.FileExists(sFromPath & "\" & filename) Then
		If Not fso.FileExists(sToPath & "\" & filename) Then
			fso.CopyFile sFromPath & "\" & filename, sToPath & "\" & filename
		End If
		ew_CopyTempFile = ew_GetRootPathInfo & "/" & Replace(folder, "\", "/") & "/" & filename
	Else
		ew_CopyTempFile = ""
	End If
	Set fso = Nothing
End Function

' Delete old files and folders in download folder
Function ew_CleanupOldFolders()
	Dim sDldPath
	Dim sPath, fso, oRootFolder, oFolders, oSubFolder, oFiles, oFile
	Dim sFolderName, sFileName, sFileExt, sFileType, iFileSize, dFileCreated, dFileModified, dFileAccessed
	On Error Resume Next

	' Get download path
	sDldPath = Trim(EW_DOWNLOAD_PATH)
	sDldPath = Replace(sDldPath, "/", "\")
	If Right(sDldPath, 1) <> "\" Then sDldPath = sDldPath & "\"
	sPath = ew_GetRootFolder & "\" & sDldPath ' Append current path
	Set fso = CreateObject("Scripting.FileSystemObject")
	If fso.FolderExists(sPath) Then

		' Get root folder
		Set oRootFolder = fso.GetFolder(sPath)

		' Process list of subfolders
		Set oFolders = oRootFolder.SubFolders
		For Each oSubFolder in oFolders
			sFolderName = oSubFolder.Name
			If (LCase(sFolderName) <> LCase(Right(EW_LOG_FOLDER, Len(sFolderName)))) And _
				(LCase(sFolderName) <> LCase(Right(EW_DB_FOLDER, Len(sFolderName)))) _
				 Then ' Do not delete log/db folder
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
					If CDate(dFileCreated) < DateAdd(EW_DOWNLOAD_TIMEOUT_UNIT, EW_DOWNLOAD_TIMEOUT_INTERVAL * -1, Now) Then oFile.Delete
				Next

				' Delete empty folder
				If oFiles.Count = 0 Then oSubFolder.Delete
			End If
		Next
	End If
	Set fso = Nothing
End Function

' Function to scramble input string to 12 byte name
Function ew_Scramble(str)
	Dim ln
	Dim q, r, i, j, k, wrkint
	Dim tb, wrkstr1, wrkstr2, wrkstr3, istr, ostr, finalstr
	ln = Len(str)
	tb = EW_RANDOM_KEY

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

	' Pick 12
	ln = Len(ostr)
	finalstr = ""
	For i = 1 to 12
		Dim lnwrk
		lnwrk = (ln * i)\12
		If lnwrk <= 0 Then lnwrk = 1
		If lnwrk > ln Then lnwrk = ln
		finalstr = finalstr & Mid(ostr, lnwrk, 1)
	Next
	ew_Scramble = finalstr
	ew_Scramble = str & "_" & ew_Scramble
End Function

' Get physical root folder
Function ew_GetRootFolder()
	Dim path
	path = Server.MapPath(".") ' get current folder
	If EW_FOLDER_LEVEL > 0 Then
		ew_GetRootFolder = ew_ParentPath(path, EW_FOLDER_LEVEL, "\") ' up the required levels
	Else
		ew_GetRootFolder = path
	End If
End Function

' Get root path
Function ew_GetRootPathInfo()
	Dim path
	path = ew_GetCurrentPathInfo ' get current path
	If EW_FOLDER_LEVEL > 0 Then
		ew_GetRootPathInfo = ew_ParentPath(path, EW_FOLDER_LEVEL, "/") ' up the required levels
	Else
		ew_GetRootPathInfo = path
	End If
End Function

' Get root path
Function ew_RootPath()
	ew_RootPath = ew_GetRootPathInfo & "/" ' return root path
End Function

' Get current path
Function ew_GetCurrentPathInfo()
	Dim url, path
	url = Request.ServerVariables("SCRIPT_NAME") ' get current script name
	p = InStrRev(url, "/")
	If p > 0 Then
		path = Mid(url, 1, p-1) ' remove script name
	End If
	ew_GetCurrentPathInfo = path
End Function

' Get parent path
Function ew_ParentPath(sPath, iLevel, sPathDlm)
	Dim i, p, wrkpath
	wrkpath = sPath
	For i = 1 to iLevel
		p = InStrRev(wrkpath, sPathDlm)
		If (p > 0) Then
			wrkpath = Mid(wrkpath, 1, p-1)
		End If
	Next
	ew_ParentPath = wrkpath
End Function

' Function to quote table/field name
Function ew_DbQuote(Name)
	ew_DbQuote = EW_DB_QUOTE_START & Name & EW_DB_QUOTE_END
End Function

' Function to adjust SQL
Function ew_AdjustSql(Value)
	Dim sWrk
	sWrk = Trim(Value & "")
	sWrk = Replace(sWrk, "'", "''") ' Adjust for Single Quote
	If EW_DB_QUOTE_START = "[" Then
		sWrk = Replace(sWrk, "[", "[[]") ' Adjust for Open Square Bracket
	End If
	ew_AdjustSql = sWrk
End Function

' Get data in a table as string
Function ew_GetJsString(TableName)
	Dim Rs
	ew_GetJsString = ""
	Set Rs = Server.CreateObject("ADODB.recordset")
	Rs.Open "SELECT * FROM " & ew_DbQuote(TableName), Conn
	If Not Rs.Eof Then
		ew_GetJsString = Rs.GetString(,,",",EW_CART_DELIMITER)
	End If
	Rs.Close
	Set Rs = Nothing
	ew_GetJsString = Replace(ew_GetJsString, """", "\""")
End Function

' --------------------------
'  Menu functions
' --------------------------
' Load menu id

Sub ew_LoadMenuId()

	' Load menu id parameter
	If Request.QueryString(EW_MENU_ID).Count > 0 Then
		ew_MenuId = Request.QueryString(EW_MENU_ID)
		If Not IsNumeric(ew_MenuId) Then ew_MenuId = ""
	End If
End Sub

' Load menu details
Sub ew_LoadMenu(rs)
	If Not rs.Eof Then
		ew_MenuId = rs(EW_FIELDNAME_MENUID)
		ew_MenuLink = rs(EW_FIELDNAME_MENULINK)
		If rs(EW_FIELDNAME_MENUGEN) Then
			ew_MenuGen = 1
		Else
			ew_MenuGen = 0
		End If
		ew_MenuFn = ew_MenuPageUrl & "?" & EW_MENU_ID & "=" & ew_MenuId
		ew_MenuUrl = rs(EW_FIELDNAME_MENUURL)
		ew_MenuParentId = rs(EW_FIELDNAME_MENUPARENTID)
		If ew_MenuParentId = "" Then ew_MenuParentId = -1
		ew_MenuPageContent = rs(EW_FIELDNAME_MENUPAGECONTENT)
	End If
End Sub

' --------------------------
'  Shopping cart functions
' --------------------------
' Load category by id

Sub ew_LoadCatByID()

	' Load category id parameter
	If Request.QueryString(EW_CATEGORY_ID).Count > 0 Then
		ew_CatId = Request.QueryString(EW_CATEGORY_ID)
		If Not IsNumeric(ew_CatId) Then ew_CatId = ""
	Else
		ew_CatId = Session(EW_SESSION_CATEGORY_ID)
	End If

	' Load category name
	If ew_CatId <> "" Then
		Dim rs, sSql
		sSql = Replace(EW_CATEGORY_CATEGORYID_FILTER, "@@" & EW_FIELDNAME_CATEGORYID & "@@", ew_CatId)
		sSql = EW_CATEGORY_SELECT_SQL & " WHERE " & sSql
		Set rs = Conn.Execute(sSql)
		Call ew_LoadCat(rs)
		rs.Close
		Set rs = Nothing
		Session(EW_SESSION_CATEGORY_ID) = ew_CatId
	End If
End Sub

' Load category
Sub ew_LoadCat(rs)
	If Not rs.Eof Then
		ew_CatId = rs(EW_FIELDNAME_CATEGORYID)
		ew_CatName = rs(EW_FIELDNAME_CATEGORYNAME)
		ew_CatFn = ew_CartUrl & "?" & EW_CATEGORY_ID & "=" & ew_CatId
		ew_CatParentId = rs(EW_FIELDNAME_CATEGORYPARENTID)
	End If
End Sub

' Load sub category list
Function ew_LoadSubCatList(catid)
	Dim sSql, sWhere
	Dim sCurSubCatList, sSubCatList
	sCurSubCatList = catid
	sSubCatList = ""
	Do While (sSubCatList <> sCurSubCatList)
		If sSubCatList <> "" Then sCurSubCatList = sSubCatList
		sSql = EW_PRODUCT_SELECT_SUBCATEGORY_SQL
		sWhere = Replace(EW_PRODUCT_SUBCATEGORY_FILTER, "@@" & EW_FIELDNAME_CATEGORYPARENTID & "@@", sCurSubCatList)
		sSql = sSql & " WHERE " & sWhere & " ORDER BY " & ew_DbQuote(EW_FIELDNAME_CATEGORYID)
		sSubCatList = catid
		Set RsSubCat = conn.Execute(sSql)
		Do While Not RsSubCat.EOF
			sSubCatList = sSubCatList & "," & RsSubCat(EW_FIELDNAME_CATEGORYID)
			RsSubCat.MoveNext
		Loop
		RsSubCat.Close
		Set RsSubCat = Nothing
	Loop
	ew_LoadSubCatList = sSubCatList
End Function

' Load item id
Sub ew_LoadItemId()

	' Load item id parameter
	If Request.QueryString(EW_ITEM_ID).Count > 0 Then
		ew_ItemId = Request.QueryString(EW_ITEM_ID)
		If Not IsNumeric(ew_ItemId) Then ew_ItemId = ""
	End If
End Sub

' Load product details
Sub ew_LoadProduct(rs)
	If Not rs.Eof Then
		ew_ItemId = rs(EW_FIELDNAME_ITEMID)
		ew_ItemNumber = rs(EW_FIELDNAME_ITEMNUMBER)
		ew_ItemName = rs(EW_FIELDNAME_ITEMNAME)
		ew_ItemPrice = rs(EW_FIELDNAME_ITEMPRICE)
		ew_ItemDescription = rs(EW_FIELDNAME_ITEMDESCRIPTION)
		ew_ItemOption1FieldName = rs(EW_FIELDNAME_ITEMOPTION1FIELDNAME)
		If rs(EW_FIELDNAME_ITEMOPTION1REQUIRED) Then
			ew_ItemOption1Required = 1
		Else
			ew_ItemOption1Required = 0
		End If
		ew_ItemOption1 = rs(EW_FIELDNAME_ITEMOPTION1)
		ew_ItemOption1Type = rs(EW_FIELDNAME_ITEMOPTION1TYPE)
		ew_ItemOption2FieldName = rs(EW_FIELDNAME_ITEMOPTION2FIELDNAME)
		If rs(EW_FIELDNAME_ITEMOPTION2REQUIRED) Then
			ew_ItemOption2Required = 1
		Else
			ew_ItemOption2Required = 0
		End If
		ew_ItemOption2 = rs(EW_FIELDNAME_ITEMOPTION2)
		ew_ItemOption2Type = rs(EW_FIELDNAME_ITEMOPTION2TYPE)
		ew_ItemOption3FieldName = rs(EW_FIELDNAME_ITEMOPTION3FIELDNAME)
		If rs(EW_FIELDNAME_ITEMOPTION3REQUIRED) Then
			ew_ItemOption3Required = 1
		Else
			ew_ItemOption3Required = 0
		End If
		ew_ItemOption3 = rs(EW_FIELDNAME_ITEMOPTION3)
		ew_ItemOption3Type = rs(EW_FIELDNAME_ITEMOPTION3TYPE)
		ew_ItemOption4FieldName = rs(EW_FIELDNAME_ITEMOPTION4FIELDNAME)
		If rs(EW_FIELDNAME_ITEMOPTION4REQUIRED) Then
			ew_ItemOption4Required = 1
		Else
			ew_ItemOption4Required = 0
		End If
		ew_ItemOption4 = rs(EW_FIELDNAME_ITEMOPTION4)
		ew_ItemOption4Type = rs(EW_FIELDNAME_ITEMOPTION4TYPE)
		ew_ItemImage = rs(EW_FIELDNAME_ITEMIMAGE)
		ew_ItemImage2 = rs(EW_FIELDNAME_ITEMIMAGE2)
		ew_ItemImage3 = rs(EW_FIELDNAME_ITEMIMAGE3)
		ew_ItemImage4 = rs(EW_FIELDNAME_ITEMIMAGE4)
		ew_ItemCategory = rs(EW_FIELDNAME_ITEMCATEGORY)
		ew_ItemWeight = rs(EW_FIELDNAME_ITEMWEIGHT)
		ew_ItemCustom = rs(EW_FIELDNAME_ITEMCUSTOM)
		ew_ItemShipping = rs(EW_FIELDNAME_ITEMSHIPPING)
		ew_ItemShipping2 = rs(EW_FIELDNAME_ITEMSHIPPING2)
		ew_ItemHandling = rs(EW_FIELDNAME_ITEMHANDLING)
		ew_ItemTax = rs(EW_FIELDNAME_ITEMTAX)
		ew_ItemTaxTypeId = rs(EW_FIELDNAME_ITEMTAXTYPEID)
		ew_ItemShippingTypeId = rs(EW_FIELDNAME_ITEMSHIPPINGTYPEID)
		ew_ItemDiscountTypeId = rs(EW_FIELDNAME_ITEMDISCOUNTTYPEID)
		ew_ItemDownload = rs(EW_FIELDNAME_ITEMDOWNLOAD)
		ew_ItemCount = rs(EW_FIELDNAME_ITEMCOUNT)
		ew_ItemDownloadFn = rs(EW_FIELDNAME_ITEMDOWNLOADFN)
		ew_ItemSelected = rs(EW_FIELDNAME_ITEMSELECTED)
	End If
End Sub

' Set up pager position
Sub ew_LoadPagerPosition()
	Dim nPage, nStart

	' Exit if nDisplayRecs = 0
	If nDisplayRecs = 0 Then Exit Sub

	' Check for a START parameter
	If Request.QueryString(EW_START_REC).Count > 0 Then
		nStart = Request.QueryString(EW_START_REC)
		If IsNumeric(nStart) Then
			nPage = nStart \ nDisplayRecs
			If (nStart mod nDisplayRecs) > 0 Then nPage = nPage + 1
		End If

	' Check for a PAGE parameter
	ElseIf Request.QueryString(EW_PAGE_NO).Count > 0 Then
		nPage = Request.QueryString(EW_PAGE_NO)

	' Restore from Session
	Else
		nPage = Session(EW_SESSION_PAGE_NO)
	End If

	' Set up page number and start record position
	If IsNumeric(nPage) Then
		If nPage <= 0 Or nPage > nTotalPages Then nPage = 1
		nPageNumber = nPage
		nStartRec = (nPageNumber - 1) * nDisplayRecs + 1

		' Save to session
		Session(EW_SESSION_PAGE_NO) = nPage
	End If
End Sub

' Get image path
Function ew_ImageFn(fn, imgtype)
	Dim wrkfn
	Dim wrkext
	If Trim(fn & "") = "" Then
		ew_ImageFn = ""
	Else
		If InStrRev(fn, "\") > 0 Then
			wrkfn = Mid(fn, InStrRev(fn, "\")+1)
		Else
			wrkfn = fn
		End If
		If InStrRev(wrkfn, ".") > 0 Then
			wrkext = Mid(wrkfn, InStrRev(wrkfn, ".")+1)
		Else
			wrkext = ""
		End If
		If LCase(wrkext) = "swf" Then ' handle swf
			ew_ImageFn = Replace(wrkfn, " ", "_")
		Else ' assume image
			ew_ImageFn = Replace(imgtype & wrkfn, " ", "_")
		End If
		If EW_IMAGE_PATH <> "" Then
			ew_ImageFn = EW_IMAGE_PATH & "/" & ew_ImageFn
		End If
		Dim fso, imgfn
		imgfn = Server.MapPath(ew_ImageFn)
		Set fso = Server.CreateObject("Scripting.FileSystemObject")
		If Not fso.FileExists(imgfn) Then
			If ew_ItemSelected Then
				ew_ImageFn = ""
			Else
				ew_ImageFn = imgtype & EW_UNREGISTERED_IMAGE
				If EW_IMAGE_PATH <> "" Then ew_ImageFn = EW_IMAGE_PATH & "/" & ew_ImageFn
			End If
		End If
		Set fso = Nothing
	End If
End Function

' Get image tag
Function ew_ImageTag(id, fn, imgtype, width, height)
	Dim wrkfn, wrkext
	wrkfn = ew_ImageFn(fn, imgtype)
	If wrkfn <> "" Then
		If InStrRev(wrkfn, ".") > 0 Then
			wrkext = Mid(wrkfn, InStrRev(wrkfn, ".")+1)
		Else
			wrkext = ""
		End If
		Dim wrkid, flashver, installswf
		wrkid = Replace(id, " ", "_")
		If width = 0 Then width = "300" ' default width
		If height = 0 Then height = "150" ' default height
		flashver = EW_FLASH_VERSION ' flash version
		If LCase(wrkext) = "swf" Then ' handle swf
			ew_ImageTag = "<div id=""" & wrkid & """>" & wrkfn & "</div>" & _
				"<scr" & "ipt type=""text/javascr" & "ipt"">" & _
				"swfobject.embedSWF(""" & wrkfn & """, """ & wrkid & """, """ & width & """, """ & height & """, """ & flashver & """);" & _
				"</scr" & "ipt>"
		Else ' assume image
			ew_ImageTag = "<img src=""" & wrkfn & """ border=""0"">"
		End If
	Else
		ew_ImageTag = ""
	End If
End Function

' Format description
Function ew_FormatDescription(desc)
	ew_FormatDescription = Replace(desc&"", vbCrLf, "<br>")
End Function

' Format option
Function ew_FormatOption(id, optype, op, idx)
	Dim sScript
	Select Case UCase(optype)
	Case "SELECT-ONE"
		sScript = "SelectOneView(""" & id & """, """ & op & """, ew_option" & idx & "PleaseSelect, ew_option" & idx & "None)"
	Case "RADIO"
		sScript = "RadioView(""" & id & """, """ & op & """, 5)"
	Case "CHECKBOX"
		sScript = "CheckboxView(""" & id & """, """ & op & """, 5)"
	Case "SELECT-MULTIPLE"
		sScript = "SelectMultipleView(""" & id & """, """ & op & """, 5)"
	Case "TEXT"
		sScript = "TextView(1, """ & id & """, """", 25, 200, 0)"
	Case Else
		sScript = ""
	End Select
	If sScript <> "" Then
		sScript = "<scr" & "ipt language=""JavaScr" & "ipt"" type=""text/javascr" & "ipt"">" & vbCrLf & _
			"document.write(" & sScript & ");" & vbCrLf & _
			"</scr" & "ipt>"
	End If
	ew_FormatOption = sScript
End Function

' Format currency
Function ew_FormatCurrency(amt)
	If EW_CCY_USE_REGIONAL_OPTIONS Then
		ew_FormatCurrency = FormatCurrency(amt)
	Else
		On Error Resume Next
		Dim curLCID
		curLCID = Session.LCID ' Save Current LCID
		Session.LCID = 1033 ' Set US English Locale
		ew_FormatCurrency = FormatNumber(amt, 0, -1, 0, -1)
		If InStr(ew_FormatCurrency, ",") > 0 Then
			ew_FormatCurrency = Replace(ew_FormatCurrency, ",", EW_CCY_GROUP_SEPARATOR)
		End If
		ew_FormatCurrency = EW_CCY_DISPLAY_SYMBOL & ew_FormatCurrency
		If EW_CCY_NUM_DIGITS > 0 Then
			ew_FormatCurrency = ew_FormatCurrency & _
			EW_CCY_DECIMAL_SEPARATOR & Right(FormatNumber(amt, EW_CCY_NUM_DIGITS), EW_CCY_NUM_DIGITS)
		End If
		Session.LCID = curLCID ' Restore Current LCID
	End If
End Function

' Load alphanumeric index
Sub ew_LoadAlpha()
	Dim i

	' Load alpha parameter
	If Request.QueryString(EW_ALPHA_ID).Count > 0 Then
		ew_Alpha = Request.QueryString(EW_ALPHA_ID)
	Else
		ew_Alpha = Session(EW_SESSION_ALPHA_ID)
	End If

	' Make sure alpha is non-empty
	If ew_Alpha <> "" Then
		i = InStr(EW_PRODUCT_ALPHANUMERIC_INDEX, ew_Alpha)
		If i > 0 And i <= UBound(ew_arPagingIndex) + 1 Then
			i = ew_arPagingIndex(i-1)
			If i <= 0 Then ew_Alpha = ""
		Else
			ew_Alpha = ""
		End If
	End If

	' Load default = first non-empty entry in index if not specified
	If ew_Alpha = "" Then
		For i = 0 to UBound(ew_arPagingIndex)
			If ew_arPagingIndex(i) > 0 Then
				ew_Alpha = Mid(EW_PRODUCT_ALPHANUMERIC_INDEX, i+1, 1)
				Exit For
			End If
		Next
	End If

	' Save current alpha
	Session(EW_SESSION_ALPHA_ID) = ew_Alpha
End Sub

' Build paging index
Sub ew_BuildPagingIndex(filter)
	Dim sPagingIndex, i, sAlpha, rswrk
	sPagingIndex = ""
	For i = 1 to Len(EW_PRODUCT_ALPHANUMERIC_INDEX)
		sAlpha = Mid(EW_PRODUCT_ALPHANUMERIC_INDEX, i, 1)
		Set rswrk = Conn.Execute(ew_PagingSql(sAlpha, filter))
		If sPagingIndex <> "" Then sPagingIndex = sPagingIndex & ","
		sPagingIndex = sPagingIndex & rswrk(0)
	Next
	ew_arPagingIndex = Split(sPagingIndex, ",")
End Sub

' Get paging SQL based on index (to alphanumeric index)
Function ew_PagingSql(alpha, filter)
	Dim sFilter
	sFilter = ew_PagingSqlFilter(alpha)
	If filter <> "" Then
		If sFilter <> "" Then sFilter = sFilter & " AND "
		sFilter = sFilter & filter
	End If
	ew_PagingSql = EW_PRODUCT_SELECT_COUNT_SQL
	If sFilter <> "" Then ew_PagingSql = ew_PagingSql & " WHERE "  & sFilter
End Function

' Get paging SQL filter
Function ew_PagingSqlFilter(alpha)
	If alpha = "~" Then
		Dim sWrk, i, sAlpha
		sWrk = ""
		For i = 1 to Len(EW_PRODUCT_ALPHANUMERIC_INDEX)
			sAlpha = Mid(EW_PRODUCT_ALPHANUMERIC_INDEX, i, 1)
			If sAlpha <> "~" Then
				If sWrk <> "" Then sWrk = sWrk & " OR "
				sWrk = sWrk & Replace(EW_PRODUCT_ALPHANUMERIC_FILTER, "@@alpha@@", sAlpha)
			End If
		Next
		If sWrk <> "" Then sWrk = "NOT (" & sWrk & ")"
		ew_PagingSqlFilter = sWrk
	ElseIf alpha <> "" Then
		ew_PagingSqlFilter = Replace(EW_PRODUCT_ALPHANUMERIC_FILTER, "@@alpha@@", alpha)
	End If
End Function

' Get custom queries
Function ew_GetCustomQueries(name)
	Dim i, sSql, sFields, sFieldSeparators, sRecordSeparator
	Dim rs, arFields, wrkstr, j
	For i = 1 to UBound(ew_arCustomQueries, 2)
		If LCase(ew_arCustomQueries(0,i)) = LCase(name) Then
			sSql = ew_arCustomQueries(1,i)
			sFields = ew_arCustomQueries(2,i)
			sFieldSeparators = ew_arCustomQueries(3,i)
			sRecordSeparator = ew_arCustomQueries(4,i)

			' Execute query
			Set rs = Conn.Execute(sSql)

			' Build output
			wrkstr = ""
			If Trim(sFields) <> "" Then
				arFields = Split(sFields, ",")
				Do While Not rs.EOF
					If wrkstr <> "" Then wrkstr = wrkstr & sRecordSeparator ' add record separator
					For j = 0 To UBound(arFields)
						If arFields(j) <> "" Then
							wrkstr = wrkstr & rs(arFields(j))
							If j < UBound(arFields) Then wrkstr = wrkstr & Mid(sFieldSeparators, j+1, 1)
						End If
					Next
					rs.MoveNext
				Loop
				ew_GetCustomQueries = wrkstr
			Else
				ew_GetCustomQueries = rs(0) ' No fields specified
			End If
			Set rs = Nothing
			Exit Function
		End If
	Next
	ew_GetCustomQueries = ""
End Function

' JavaScript encode
Function ew_JSEncode(str)
	str = Replace(str, "\", "\\")
	str = Replace(str, """", "\""")
	ew_JSEncode = str
End Function

' Menu class
Class ew_Menu
	Public Id
	Public IsRoot
	Public ItemData

	' Init
	Private Sub Class_Initialize
		IsRoot = False
		Set ItemData = Server.CreateObject("Scripting.Dictionary") ' Data type: array of ew_MenuItem
  End Sub

	' Terminate
	Private Sub Class_Terminate
  	Set ItemData = Nothing
  End Sub

	' Add item to internal dictionary
	Sub AddItem(item)
		ItemData.Add ItemData.Count, item
	End Sub

	' Find item
	Function FindItem(id, out)
		Dim i, item
		FindItem = False
		For i = 0 To ItemData.Count -1
			If ItemData.Item(i).Id = id Then
				Set out = ItemData.Item(i)
				FindItem = True
				Exit Function
			ElseIf Not IsNull(ItemData.Item(i).SubMenu) Then
				FindItem = ItemData.Item(i).SubMenu.FindItem(id, out)
			End If
		Next
	End Function

	' Add a menu item
	Sub AddMenuItem(id, text, gen, fn, url, parentid)
		Dim oMenu, oParentMenu
		Set oMenu = New ew_MenuItem
		oMenu.Id = "MenuItem" & id
		oMenu.Text = text
		If gen Then
			oMenu.SetUrl(fn)
		Else
			oMenu.SetUrl(url)
		End If
		If parentid < 0 Then
			AddItem(oMenu)
		Else
			If FindItem("MenuItem" & parentid, oParentMenu) Then
				oParentMenu.AddItem(oMenu)
			End If
		End If
	End Sub

	' Render the menu
	Sub Render
		Dim i, item, itemcnt
		itemcnt = ItemData.Count
		Response.Write "<ul"
		If Id <> "" Then
			Response.Write " id=""" & Id & """"
		End If
		If IsRoot Then
			Response.Write " class=""" & EW_MENUBAR_VERTICAL_CLASSNAME & """"
		End If
		Response.Write ">" & vbCrLf
		For i = 0 to itemcnt - 1
			Response.Write "<li><a"
			If Not IsNull(ItemData.Item(i).SubMenu) Then
				Response.Write " class=""" & EW_MENUBAR_SUBMENU_CLASSNAME & """"
			End If
			If ItemData.Item(i).Url <> "" Then
				Response.Write " href=""" & Server.HTMLEncode(ItemData.Item(i).Url) & """"
			End If
			Response.Write ">" & ItemData.Item(i).Text & "</a>" & vbCrLf
			If Not IsNull(ItemData.Item(i).SubMenu) Then
				ItemData.Item(i).Submenu.Render
			End If
			Response.Write "</li>" & vbCrLf
		Next
		Response.Write "</ul>" & vbCrLf
	End Sub
End Class

' Menu item class
Class ew_MenuItem
	Public Id
	Public Text
	Public Url
	Public SubMenu ' Data type = ew_Menu
	Private Sub Class_Initialize
		Url = ""
		SubMenu = Null
  End Sub
	Sub AddItem(item) ' Add submenu item
		If IsNull(SubMenu) Then
			Set SubMenu = New ew_Menu
			SubMenu.Id = Id
		End If
		SubMenu.AddItem(item)
	End Sub
	Sub SetUrl(aurl)
		url = LTrim(aurl)
		If LCase(Left(aurl, 7)) = "http://" Or _
			LCase(Left(aurl, 8)) = "https://" Or _
			LCase(Left(aurl, 6)) = "ftp://" Or _
			LCase(Left(aurl, 7)) = "mailto:" Or _
			LCase(Left(aurl, 11)) = "javascript:" Then
			Url = aurl
		Else
			Url = ew_RootPath & aurl
		End If
	End Sub
End Class
%>
<script language="JScript" runat="server">
// URL encode
function ew_Encode(str) {
	return encodeURIComponent(str);
}
// URL decode 
function ew_Decode(str) {
	return decodeURIComponent(str);
}
/**
*
*  Base64 encode / decode
*  http://www.webtoolkit.info/
*
**/
var Base64 = {
	// private property
	_keyStr : "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",
	// public method for encoding
	encode : function (input) {
		var output = "";
		var chr1, chr2, chr3, enc1, enc2, enc3, enc4;
		var i = 0;
		input = Base64._utf8_encode(input);
		while (i < input.length) {
			chr1 = input.charCodeAt(i++);
			chr2 = input.charCodeAt(i++);
			chr3 = input.charCodeAt(i++);
			enc1 = chr1 >> 2;
			enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
			enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
			enc4 = chr3 & 63;
			if (isNaN(chr2)) {
				enc3 = enc4 = 64;
			} else if (isNaN(chr3)) {
				enc4 = 64;
			}
			output = output +
			this._keyStr.charAt(enc1) + this._keyStr.charAt(enc2) +
			this._keyStr.charAt(enc3) + this._keyStr.charAt(enc4);
		}
		return output;
	},
	// public method for decoding
	decode : function (input) {
		var output = "";
		var chr1, chr2, chr3;
		var enc1, enc2, enc3, enc4;
		var i = 0;
		input = input.replace(/[^A-Za-z0-9\+\/\=]/g, "");
		while (i < input.length) {
			enc1 = this._keyStr.indexOf(input.charAt(i++));
			enc2 = this._keyStr.indexOf(input.charAt(i++));
			enc3 = this._keyStr.indexOf(input.charAt(i++));
			enc4 = this._keyStr.indexOf(input.charAt(i++));
			chr1 = (enc1 << 2) | (enc2 >> 4);
			chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
			chr3 = ((enc3 & 3) << 6) | enc4;
			output = output + String.fromCharCode(chr1);
			if (enc3 != 64) {
				output = output + String.fromCharCode(chr2);
			}
			if (enc4 != 64) {
				output = output + String.fromCharCode(chr3);
			}
		}
		output = Base64._utf8_decode(output);
		return output;
	},
	// private method for UTF-8 encoding
	_utf8_encode : function (string) {
		string = string.replace(/\r\n/g,"\n");
		var utftext = "";
		for (var n = 0; n < string.length; n++) {
			var c = string.charCodeAt(n);
			if (c < 128) {
				utftext += String.fromCharCode(c);
			}
			else if((c > 127) && (c < 2048)) {
				utftext += String.fromCharCode((c >> 6) | 192);
				utftext += String.fromCharCode((c & 63) | 128);
			}
			else {
				utftext += String.fromCharCode((c >> 12) | 224);
				utftext += String.fromCharCode(((c >> 6) & 63) | 128);
				utftext += String.fromCharCode((c & 63) | 128);
			}
		}
		return utftext;
	},
	// private method for UTF-8 decoding
	_utf8_decode : function (utftext) {
		var string = "";
		var i = 0;
		var c = c1 = c2 = 0;
		while ( i < utftext.length ) {
			c = utftext.charCodeAt(i);
			if (c < 128) {
				string += String.fromCharCode(c);
				i++;
			}
			else if((c > 191) && (c < 224)) {
				c2 = utftext.charCodeAt(i+1);
				string += String.fromCharCode(((c & 31) << 6) | (c2 & 63));
				i += 2;
			}
			else {
				c2 = utftext.charCodeAt(i+1);
				c3 = utftext.charCodeAt(i+2);
				string += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
				i += 3;
			}
		}
		return string;
	}
}
/**
 * Encrypts a string using the XX Tiny Encryption Algorithm
 *
 * @param string the string to encode
 * @param string the key that can decode the string
 * @return string the encoded string
 * @author www.farfarfar.com
 * @version 0.1
 */
function xxTeaEncrypt(str, key)
{
	var v = strToLong(str);
	var k = strToLong(key.slice(0,16));
	var n = v.length;
	if (n == 0)
	{
		return "";
	}
	if (n == 1)
	{
		v[n++] = 0;
	}
	var z = v[n - 1], y = v[0], sum = 0, e;  // long
	var delta = 0x9E3779B9;
	var q = Math.floor((6 + 52 / n));
	while (q-- > 0)
	{
		sum += delta;
		e = sum >>> 2 & 3;
		for (var p = 0; p < n - 1; p++) // long
		{
			y = v[p + 1];
			z = v[p] += (z >>> 5 ^ y << 2) + (y >>> 3 ^ z << 4) ^ (sum ^ y) + (k[p & 3 ^ e] ^ z);
		}
		y = v[0];
		z = v[n - 1] += (z >>> 5 ^ y << 2) + (y >>> 3 ^ z << 4) ^ (sum ^ y) + (k[p & 3 ^ e] ^ z);
	}
	return longToStr(v);
}
/**
 * Decrypts a string using the XX Tiny Encryption Algorithm
 *
 * @param string the string to decode
 * @param string the key that can encoded the string
 * @return string the decoded string
 * @author www.farfarfar.com
 * @version 0.1
 */
function xxTeaDecrypt(str, key)
{
	var v = strToLong(str);
	var k = strToLong(key.slice(0,16));
	var n = v.length;
	if (n == 0)
	{
		return "";
	}
	var z = v[n - 1], y = v[0], e; // long
	var delta = 0x9E3779B9;
	var q = Math.floor((6 + 52 / n));
	var sum = q * delta;
	while (sum != 0)
	{
		e = sum >>> 2 & 3;
		for (var p = n - 1; p > 0; p--) // long
		{
			z = v[p - 1];
			y = v[p] -= (z >>> 5 ^ y << 2) + (y >>> 3 ^ z << 4) ^ (sum ^ y) + (k[p & 3 ^ e] ^ z);
		}
		z = v[n - 1];
		y = v[0] -= (z >>> 5 ^ y << 2) + (y >>> 3 ^ z << 4) ^ (sum ^ y) + (k[p & 3 ^ e] ^ z);
		sum -= delta;
	}
	return longToStr(v);
}
/**
 * Blowfish variables
 */
var charBit = 8;
var blowfish_n = 16;
var blowfish_p = new Array(18);
var blowfish_s = new Array(4);
/**
 * Modifies the Blowfish values according to the key
 *
 * @param string the key to use
 * @author www.farfarfar.com
 * @version 0.1
 */
function blowfishSetKey(key)
{
	blowfish_p = Array(
	    0x243f6a88, 0x85a308d3, 0x13198a2e, 0x03707344, 0xa4093822, 0x299f31d0, 0x082efa98, 0xec4e6c89,
	    0x452821e6, 0x38d01377, 0xbe5466cf, 0x34e90c6c, 0xc0ac29b7, 0xc97c50dd, 0x3f84d5b5, 0xb5470917,
	    0x9216d5d9, 0x8979fb1b
	);
	blowfish_s = Array(
		Array(
			0xd1310ba6, 0x98dfb5ac, 0x2ffd72db, 0xd01adfb7, 0xb8e1afed, 0x6a267e96, 0xba7c9045, 0xf12c7f99,
			0x24a19947, 0xb3916cf7, 0x0801f2e2, 0x858efc16, 0x636920d8, 0x71574e69, 0xa458fea3, 0xf4933d7e,
			0x0d95748f, 0x728eb658, 0x718bcd58, 0x82154aee, 0x7b54a41d, 0xc25a59b5, 0x9c30d539, 0x2af26013,
			0xc5d1b023, 0x286085f0, 0xca417918, 0xb8db38ef, 0x8e79dcb0, 0x603a180e, 0x6c9e0e8b, 0xb01e8a3e,
			0xd71577c1, 0xbd314b27, 0x78af2fda, 0x55605c60, 0xe65525f3, 0xaa55ab94, 0x57489862, 0x63e81440,
			0x55ca396a, 0x2aab10b6, 0xb4cc5c34, 0x1141e8ce, 0xa15486af, 0x7c72e993, 0xb3ee1411, 0x636fbc2a,
			0x2ba9c55d, 0x741831f6, 0xce5c3e16, 0x9b87931e, 0xafd6ba33, 0x6c24cf5c, 0x7a325381, 0x28958677,
			0x3b8f4898, 0x6b4bb9af, 0xc4bfe81b, 0x66282193, 0x61d809cc, 0xfb21a991, 0x487cac60, 0x5dec8032,
			0xef845d5d, 0xe98575b1, 0xdc262302, 0xeb651b88, 0x23893e81, 0xd396acc5, 0x0f6d6ff3, 0x83f44239,
			0x2e0b4482, 0xa4842004, 0x69c8f04a, 0x9e1f9b5e, 0x21c66842, 0xf6e96c9a, 0x670c9c61, 0xabd388f0,
			0x6a51a0d2, 0xd8542f68, 0x960fa728, 0xab5133a3, 0x6eef0b6c, 0x137a3be4, 0xba3bf050, 0x7efb2a98,
			0xa1f1651d, 0x39af0176, 0x66ca593e, 0x82430e88, 0x8cee8619, 0x456f9fb4, 0x7d84a5c3, 0x3b8b5ebe,
			0xe06f75d8, 0x85c12073, 0x401a449f, 0x56c16aa6, 0x4ed3aa62, 0x363f7706, 0x1bfedf72, 0x429b023d,
			0x37d0d724, 0xd00a1248, 0xdb0fead3, 0x49f1c09b, 0x075372c9, 0x80991b7b, 0x25d479d8, 0xf6e8def7,
			0xe3fe501a, 0xb6794c3b, 0x976ce0bd, 0x04c006ba, 0xc1a94fb6, 0x409f60c4, 0x5e5c9ec2, 0x196a2463,
			0x68fb6faf, 0x3e6c53b5, 0x1339b2eb, 0x3b52ec6f, 0x6dfc511f, 0x9b30952c, 0xcc814544, 0xaf5ebd09,
			0xbee3d004, 0xde334afd, 0x660f2807, 0x192e4bb3, 0xc0cba857, 0x45c8740f, 0xd20b5f39, 0xb9d3fbdb,
			0x5579c0bd, 0x1a60320a, 0xd6a100c6, 0x402c7279, 0x679f25fe, 0xfb1fa3cc, 0x8ea5e9f8, 0xdb3222f8,
			0x3c7516df, 0xfd616b15, 0x2f501ec8, 0xad0552ab, 0x323db5fa, 0xfd238760, 0x53317b48, 0x3e00df82,
			0x9e5c57bb, 0xca6f8ca0, 0x1a87562e, 0xdf1769db, 0xd542a8f6, 0x287effc3, 0xac6732c6, 0x8c4f5573,
			0x695b27b0, 0xbbca58c8, 0xe1ffa35d, 0xb8f011a0, 0x10fa3d98, 0xfd2183b8, 0x4afcb56c, 0x2dd1d35b,
			0x9a53e479, 0xb6f84565, 0xd28e49bc, 0x4bfb9790, 0xe1ddf2da, 0xa4cb7e33, 0x62fb1341, 0xcee4c6e8,
			0xef20cada, 0x36774c01, 0xd07e9efe, 0x2bf11fb4, 0x95dbda4d, 0xae909198, 0xeaad8e71, 0x6b93d5a0,
			0xd08ed1d0, 0xafc725e0, 0x8e3c5b2f, 0x8e7594b7, 0x8ff6e2fb, 0xf2122b64, 0x8888b812, 0x900df01c,
			0x4fad5ea0, 0x688fc31c, 0xd1cff191, 0xb3a8c1ad, 0x2f2f2218, 0xbe0e1777, 0xea752dfe, 0x8b021fa1,
			0xe5a0cc0f, 0xb56f74e8, 0x18acf3d6, 0xce89e299, 0xb4a84fe0, 0xfd13e0b7, 0x7cc43b81, 0xd2ada8d9,
			0x165fa266, 0x80957705, 0x93cc7314, 0x211a1477, 0xe6ad2065, 0x77b5fa86, 0xc75442f5, 0xfb9d35cf,
			0xebcdaf0c, 0x7b3e89a0, 0xd6411bd3, 0xae1e7e49, 0x00250e2d, 0x2071b35e, 0x226800bb, 0x57b8e0af,
			0x2464369b, 0xf009b91e, 0x5563911d, 0x59dfa6aa, 0x78c14389, 0xd95a537f, 0x207d5ba2, 0x02e5b9c5,
			0x83260376, 0x6295cfa9, 0x11c81968, 0x4e734a41, 0xb3472dca, 0x7b14a94a, 0x1b510052, 0x9a532915,
			0xd60f573f, 0xbc9bc6e4, 0x2b60a476, 0x81e67400, 0x08ba6fb5, 0x571be91f, 0xf296ec6b, 0x2a0dd915,
			0xb6636521, 0xe7b9f9b6, 0xff34052e, 0xc5855664, 0x53b02d5d, 0xa99f8fa1, 0x08ba4799, 0x6e85076a
		),
		Array(
			0x4b7a70e9, 0xb5b32944, 0xdb75092e, 0xc4192623, 0xad6ea6b0, 0x49a7df7d, 0x9cee60b8, 0x8fedb266,
			0xecaa8c71, 0x699a17ff, 0x5664526c, 0xc2b19ee1, 0x193602a5, 0x75094c29, 0xa0591340, 0xe4183a3e,
			0x3f54989a, 0x5b429d65, 0x6b8fe4d6, 0x99f73fd6, 0xa1d29c07, 0xefe830f5, 0x4d2d38e6, 0xf0255dc1,
			0x4cdd2086, 0x8470eb26, 0x6382e9c6, 0x021ecc5e, 0x09686b3f, 0x3ebaefc9, 0x3c971814, 0x6b6a70a1,
			0x687f3584, 0x52a0e286, 0xb79c5305, 0xaa500737, 0x3e07841c, 0x7fdeae5c, 0x8e7d44ec, 0x5716f2b8,
			0xb03ada37, 0xf0500c0d, 0xf01c1f04, 0x0200b3ff, 0xae0cf51a, 0x3cb574b2, 0x25837a58, 0xdc0921bd,
			0xd19113f9, 0x7ca92ff6, 0x94324773, 0x22f54701, 0x3ae5e581, 0x37c2dadc, 0xc8b57634, 0x9af3dda7,
			0xa9446146, 0x0fd0030e, 0xecc8c73e, 0xa4751e41, 0xe238cd99, 0x3bea0e2f, 0x3280bba1, 0x183eb331,
			0x4e548b38, 0x4f6db908, 0x6f420d03, 0xf60a04bf, 0x2cb81290, 0x24977c79, 0x5679b072, 0xbcaf89af,
			0xde9a771f, 0xd9930810, 0xb38bae12, 0xdccf3f2e, 0x5512721f, 0x2e6b7124, 0x501adde6, 0x9f84cd87,
			0x7a584718, 0x7408da17, 0xbc9f9abc, 0xe94b7d8c, 0xec7aec3a, 0xdb851dfa, 0x63094366, 0xc464c3d2,
			0xef1c1847, 0x3215d908, 0xdd433b37, 0x24c2ba16, 0x12a14d43, 0x2a65c451, 0x50940002, 0x133ae4dd,
			0x71dff89e, 0x10314e55, 0x81ac77d6, 0x5f11199b, 0x043556f1, 0xd7a3c76b, 0x3c11183b, 0x5924a509,
			0xf28fe6ed, 0x97f1fbfa, 0x9ebabf2c, 0x1e153c6e, 0x86e34570, 0xeae96fb1, 0x860e5e0a, 0x5a3e2ab3,
			0x771fe71c, 0x4e3d06fa, 0x2965dcb9, 0x99e71d0f, 0x803e89d6, 0x5266c825, 0x2e4cc978, 0x9c10b36a,
			0xc6150eba, 0x94e2ea78, 0xa5fc3c53, 0x1e0a2df4, 0xf2f74ea7, 0x361d2b3d, 0x1939260f, 0x19c27960,
			0x5223a708, 0xf71312b6, 0xebadfe6e, 0xeac31f66, 0xe3bc4595, 0xa67bc883, 0xb17f37d1, 0x018cff28,
			0xc332ddef, 0xbe6c5aa5, 0x65582185, 0x68ab9802, 0xeecea50f, 0xdb2f953b, 0x2aef7dad, 0x5b6e2f84,
			0x1521b628, 0x29076170, 0xecdd4775, 0x619f1510, 0x13cca830, 0xeb61bd96, 0x0334fe1e, 0xaa0363cf,
			0xb5735c90, 0x4c70a239, 0xd59e9e0b, 0xcbaade14, 0xeecc86bc, 0x60622ca7, 0x9cab5cab, 0xb2f3846e,
			0x648b1eaf, 0x19bdf0ca, 0xa02369b9, 0x655abb50, 0x40685a32, 0x3c2ab4b3, 0x319ee9d5, 0xc021b8f7,
			0x9b540b19, 0x875fa099, 0x95f7997e, 0x623d7da8, 0xf837889a, 0x97e32d77, 0x11ed935f, 0x16681281,
			0x0e358829, 0xc7e61fd6, 0x96dedfa1, 0x7858ba99, 0x57f584a5, 0x1b227263, 0x9b83c3ff, 0x1ac24696,
			0xcdb30aeb, 0x532e3054, 0x8fd948e4, 0x6dbc3128, 0x58ebf2ef, 0x34c6ffea, 0xfe28ed61, 0xee7c3c73,
			0x5d4a14d9, 0xe864b7e3, 0x42105d14, 0x203e13e0, 0x45eee2b6, 0xa3aaabea, 0xdb6c4f15, 0xfacb4fd0,
			0xc742f442, 0xef6abbb5, 0x654f3b1d, 0x41cd2105, 0xd81e799e, 0x86854dc7, 0xe44b476a, 0x3d816250,
			0xcf62a1f2, 0x5b8d2646, 0xfc8883a0, 0xc1c7b6a3, 0x7f1524c3, 0x69cb7492, 0x47848a0b, 0x5692b285,
			0x095bbf00, 0xad19489d, 0x1462b174, 0x23820e00, 0x58428d2a, 0x0c55f5ea, 0x1dadf43e, 0x233f7061,
			0x3372f092, 0x8d937e41, 0xd65fecf1, 0x6c223bdb, 0x7cde3759, 0xcbee7460, 0x4085f2a7, 0xce77326e,
			0xa6078084, 0x19f8509e, 0xe8efd855, 0x61d99735, 0xa969a7aa, 0xc50c06c2, 0x5a04abfc, 0x800bcadc,
			0x9e447a2e, 0xc3453484, 0xfdd56705, 0x0e1e9ec9, 0xdb73dbd3, 0x105588cd, 0x675fda79, 0xe3674340,
			0xc5c43465, 0x713e38d8, 0x3d28f89e, 0xf16dff20, 0x153e21e7, 0x8fb03d4a, 0xe6e39f2b, 0xdb83adf7
		),
		Array(
			0xe93d5a68, 0x948140f7, 0xf64c261c, 0x94692934, 0x411520f7, 0x7602d4f7, 0xbcf46b2e, 0xd4a20068,
			0xd4082471, 0x3320f46a, 0x43b7d4b7, 0x500061af, 0x1e39f62e, 0x97244546, 0x14214f74, 0xbf8b8840,
			0x4d95fc1d, 0x96b591af, 0x70f4ddd3, 0x66a02f45, 0xbfbc09ec, 0x03bd9785, 0x7fac6dd0, 0x31cb8504,
			0x96eb27b3, 0x55fd3941, 0xda2547e6, 0xabca0a9a, 0x28507825, 0x530429f4, 0x0a2c86da, 0xe9b66dfb,
			0x68dc1462, 0xd7486900, 0x680ec0a4, 0x27a18dee, 0x4f3ffea2, 0xe887ad8c, 0xb58ce006, 0x7af4d6b6,
			0xaace1e7c, 0xd3375fec, 0xce78a399, 0x406b2a42, 0x20fe9e35, 0xd9f385b9, 0xee39d7ab, 0x3b124e8b,
			0x1dc9faf7, 0x4b6d1856, 0x26a36631, 0xeae397b2, 0x3a6efa74, 0xdd5b4332, 0x6841e7f7, 0xca7820fb,
			0xfb0af54e, 0xd8feb397, 0x454056ac, 0xba489527, 0x55533a3a, 0x20838d87, 0xfe6ba9b7, 0xd096954b,
			0x55a867bc, 0xa1159a58, 0xcca92963, 0x99e1db33, 0xa62a4a56, 0x3f3125f9, 0x5ef47e1c, 0x9029317c,
			0xfdf8e802, 0x04272f70, 0x80bb155c, 0x05282ce3, 0x95c11548, 0xe4c66d22, 0x48c1133f, 0xc70f86dc,
			0x07f9c9ee, 0x41041f0f, 0x404779a4, 0x5d886e17, 0x325f51eb, 0xd59bc0d1, 0xf2bcc18f, 0x41113564,
			0x257b7834, 0x602a9c60, 0xdff8e8a3, 0x1f636c1b, 0x0e12b4c2, 0x02e1329e, 0xaf664fd1, 0xcad18115,
			0x6b2395e0, 0x333e92e1, 0x3b240b62, 0xeebeb922, 0x85b2a20e, 0xe6ba0d99, 0xde720c8c, 0x2da2f728,
			0xd0127845, 0x95b794fd, 0x647d0862, 0xe7ccf5f0, 0x5449a36f, 0x877d48fa, 0xc39dfd27, 0xf33e8d1e,
			0x0a476341, 0x992eff74, 0x3a6f6eab, 0xf4f8fd37, 0xa812dc60, 0xa1ebddf8, 0x991be14c, 0xdb6e6b0d,
			0xc67b5510, 0x6d672c37, 0x2765d43b, 0xdcd0e804, 0xf1290dc7, 0xcc00ffa3, 0xb5390f92, 0x690fed0b,
			0x667b9ffb, 0xcedb7d9c, 0xa091cf0b, 0xd9155ea3, 0xbb132f88, 0x515bad24, 0x7b9479bf, 0x763bd6eb,
			0x37392eb3, 0xcc115979, 0x8026e297, 0xf42e312d, 0x6842ada7, 0xc66a2b3b, 0x12754ccc, 0x782ef11c,
			0x6a124237, 0xb79251e7, 0x06a1bbe6, 0x4bfb6350, 0x1a6b1018, 0x11caedfa, 0x3d25bdd8, 0xe2e1c3c9,
			0x44421659, 0x0a121386, 0xd90cec6e, 0xd5abea2a, 0x64af674e, 0xda86a85f, 0xbebfe988, 0x64e4c3fe,
			0x9dbc8057, 0xf0f7c086, 0x60787bf8, 0x6003604d, 0xd1fd8346, 0xf6381fb0, 0x7745ae04, 0xd736fccc,
			0x83426b33, 0xf01eab71, 0xb0804187, 0x3c005e5f, 0x77a057be, 0xbde8ae24, 0x55464299, 0xbf582e61,
			0x4e58f48f, 0xf2ddfda2, 0xf474ef38, 0x8789bdc2, 0x5366f9c3, 0xc8b38e74, 0xb475f255, 0x46fcd9b9,
			0x7aeb2661, 0x8b1ddf84, 0x846a0e79, 0x915f95e2, 0x466e598e, 0x20b45770, 0x8cd55591, 0xc902de4c,
			0xb90bace1, 0xbb8205d0, 0x11a86248, 0x7574a99e, 0xb77f19b6, 0xe0a9dc09, 0x662d09a1, 0xc4324633,
			0xe85a1f02, 0x09f0be8c, 0x4a99a025, 0x1d6efe10, 0x1ab93d1d, 0x0ba5a4df, 0xa186f20f, 0x2868f169,
			0xdcb7da83, 0x573906fe, 0xa1e2ce9b, 0x4fcd7f52, 0x50115e01, 0xa70683fa, 0xa002b5c4, 0x0de6d027,
			0x9af88c27, 0x773f8641, 0xc3604c06, 0x61a806b5, 0xf0177a28, 0xc0f586e0, 0x006058aa, 0x30dc7d62,
			0x11e69ed7, 0x2338ea63, 0x53c2dd94, 0xc2c21634, 0xbbcbee56, 0x90bcb6de, 0xebfc7da1, 0xce591d76,
			0x6f05e409, 0x4b7c0188, 0x39720a3d, 0x7c927c24, 0x86e3725f, 0x724d9db9, 0x1ac15bb4, 0xd39eb8fc,
			0xed545578, 0x08fca5b5, 0xd83d7cd3, 0x4dad0fc4, 0x1e50ef5e, 0xb161e6f8, 0xa28514d9, 0x6c51133c,
			0x6fd5c7e7, 0x56e14ec4, 0x362abfce, 0xddc6c837, 0xd79a3234, 0x92638212, 0x670efa8e, 0x406000e0
		),
		Array(
			0x3a39ce37, 0xd3faf5cf, 0xabc27737, 0x5ac52d1b, 0x5cb0679e, 0x4fa33742, 0xd3822740, 0x99bc9bbe,
			0xd5118e9d, 0xbf0f7315, 0xd62d1c7e, 0xc700c47b, 0xb78c1b6b, 0x21a19045, 0xb26eb1be, 0x6a366eb4,
			0x5748ab2f, 0xbc946e79, 0xc6a376d2, 0x6549c2c8, 0x530ff8ee, 0x468dde7d, 0xd5730a1d, 0x4cd04dc6,
			0x2939bbdb, 0xa9ba4650, 0xac9526e8, 0xbe5ee304, 0xa1fad5f0, 0x6a2d519a, 0x63ef8ce2, 0x9a86ee22,
			0xc089c2b8, 0x43242ef6, 0xa51e03aa, 0x9cf2d0a4, 0x83c061ba, 0x9be96a4d, 0x8fe51550, 0xba645bd6,
			0x2826a2f9, 0xa73a3ae1, 0x4ba99586, 0xef5562e9, 0xc72fefd3, 0xf752f7da, 0x3f046f69, 0x77fa0a59,
			0x80e4a915, 0x87b08601, 0x9b09e6ad, 0x3b3ee593, 0xe990fd5a, 0x9e34d797, 0x2cf0b7d9, 0x022b8b51,
			0x96d5ac3a, 0x017da67d, 0xd1cf3ed6, 0x7c7d2d28, 0x1f9f25cf, 0xadf2b89b, 0x5ad6b472, 0x5a88f54c,
			0xe029ac71, 0xe019a5e6, 0x47b0acfd, 0xed93fa9b, 0xe8d3c48d, 0x283b57cc, 0xf8d56629, 0x79132e28,
			0x785f0191, 0xed756055, 0xf7960e44, 0xe3d35e8c, 0x15056dd4, 0x88f46dba, 0x03a16125, 0x0564f0bd,
			0xc3eb9e15, 0x3c9057a2, 0x97271aec, 0xa93a072a, 0x1b3f6d9b, 0x1e6321f5, 0xf59c66fb, 0x26dcf319,
			0x7533d928, 0xb155fdf5, 0x03563482, 0x8aba3cbb, 0x28517711, 0xc20ad9f8, 0xabcc5167, 0xccad925f,
			0x4de81751, 0x3830dc8e, 0x379d5862, 0x9320f991, 0xea7a90c2, 0xfb3e7bce, 0x5121ce64, 0x774fbe32,
			0xa8b6e37e, 0xc3293d46, 0x48de5369, 0x6413e680, 0xa2ae0810, 0xdd6db224, 0x69852dfd, 0x09072166,
			0xb39a460a, 0x6445c0dd, 0x586cdecf, 0x1c20c8ae, 0x5bbef7dd, 0x1b588d40, 0xccd2017f, 0x6bb4e3bb,
			0xdda26a7e, 0x3a59ff45, 0x3e350a44, 0xbcb4cdd5, 0x72eacea8, 0xfa6484bb, 0x8d6612ae, 0xbf3c6f47,
			0xd29be463, 0x542f5d9e, 0xaec2771b, 0xf64e6370, 0x740e0d8d, 0xe75b1357, 0xf8721671, 0xaf537d5d,
			0x4040cb08, 0x4eb4e2cc, 0x34d2466a, 0x0115af84, 0xe1b00428, 0x95983a1d, 0x06b89fb4, 0xce6ea048,
			0x6f3f3b82, 0x3520ab82, 0x011a1d4b, 0x277227f8, 0x611560b1, 0xe7933fdc, 0xbb3a792b, 0x344525bd,
			0xa08839e1, 0x51ce794b, 0x2f32c9b7, 0xa01fbac9, 0xe01cc87e, 0xbcc7d1f6, 0xcf0111c3, 0xa1e8aac7,
			0x1a908749, 0xd44fbd9a, 0xd0dadecb, 0xd50ada38, 0x0339c32a, 0xc6913667, 0x8df9317c, 0xe0b12b4f,
			0xf79e59b7, 0x43f5bb3a, 0xf2d519ff, 0x27d9459c, 0xbf97222c, 0x15e6fc2a, 0x0f91fc71, 0x9b941525,
			0xfae59361, 0xceb69ceb, 0xc2a86459, 0x12baa8d1, 0xb6c1075e, 0xe3056a0c, 0x10d25065, 0xcb03a442,
			0xe0ec6e0e, 0x1698db3b, 0x4c98a0be, 0x3278e964, 0x9f1f9532, 0xe0d392df, 0xd3a0342b, 0x8971f21e,
			0x1b0a7441, 0x4ba3348c, 0xc5be7120, 0xc37632d8, 0xdf359f8d, 0x9b992f2e, 0xe60b6f47, 0x0fe3f11d,
			0xe54cda54, 0x1edad891, 0xce6279cf, 0xcd3e7e6f, 0x1618b166, 0xfd2c1d05, 0x848fd2c5, 0xf6fb2299,
			0xf523f357, 0xa6327623, 0x93a83531, 0x56cccd02, 0xacf08162, 0x5a75ebb5, 0x6e163697, 0x88d273cc,
			0xde966292, 0x81b949d0, 0x4c50901b, 0x71c65614, 0xe6c6c7bd, 0x327a140a, 0x45e1d006, 0xc3f27b9a,
			0xc9aa53fd, 0x62a80f00, 0xbb25bfe2, 0x35bdd2f6, 0x71126905, 0xb2040222, 0xb6cbcf7c, 0xcd769c2b,
			0x53113ec0, 0x1640e3d3, 0x38abbd60, 0x2547adf0, 0xba38209c, 0xf746ce76, 0x77afa1c5, 0x20756060,
			0x85cbfe4e, 0x8ae88dd8, 0x7aaaf9b0, 0x4cf9aa7e, 0x1948c25c, 0x02fb8a8c, 0x01c36ae4, 0xd6ebe1f9,
			0x90d4f869, 0xa65cdea0, 0x3f09252d, 0xc208e69f, 0xb74e6132, 0xce77e25b, 0x578fdfe3, 0x3ac372e6
		)
	);
	// scramble the above values according to the key
	var data;
	var keyBytes = key.length;
	var keys = strToLong(key);
	var j = 0;
	for (var i = 0; i < blowfish_n + 2; i++)
	{
		data = 0x00000000;
		for (var k = 0; k < 4; k++)
		{
			data = (data << 8) | keys[j];
			j = j + 1;
			if (j >= keyBytes)
			{
				j = 0;
			}
		}
		blowfish_p[i] ^= data;
	}
	var datal = 0x00000000;
	var datar = 0x00000000;
	for (var i = 0; i < blowfish_n + 2; i += 2)
	{
		var t = blowfishEncryptBlock(datal, datar);
		datal = t[0];
		datar = t[1];
		blowfish_p[i] = datal;
		blowfish_p[i + 1] = datar;
	}
	for (var i = 0; i < 4; i++)
	{
		for (var j = 0; j < 256; j += 2)
		{
			var t = blowfishEncryptBlock(datal, datar);
			datal = t[0];
			datar = t[1];
			blowfish_s[i][j] = datal;
			blowfish_s[i][j + 1] = datar;
		}
	}
}
/**
 * Blowfish function: encrypt a string and return its output as a string
 *
 * @param string the string to encrypt
 * @return string the encrypted string
 * @author www.farfarfar.com
 * @version 0.1
 */
function blowfishEncrypt(str)
{
	var out = "";
	// blowfish messages must be divisible by 64 bits to work
	str = addPadding(str, 8, str.length);
	var len = str.length;
	// blowfish blocks are 64 bits long
	// 8 * 8 = 64
	// split 64-bit string to two 32-bit integers, encrypt them,
	// and convert the int back to a string
	for (var i = 0; i < len; i += 8)
	{
		var t = strToLong(str.substr(i, 8));
		t = blowfishEncryptBlock(t[0], t[1]);
		out += longToStr(Array(t[0], t[1]));
	}
	return out;
}
/**
 * Blowfish function: decrypt a string and return its output as a string
 *
 * @param string the string to encrypt
 * @return string the encrypted string
 * @author www.farfarfar.com
 * @version 0.1
 */
function blowfishDecrypt(str)
{
	var out="";
	var len = str.length;
	for (var i = 0; i < len; i += 8)
	{
		var t = strToLong(str.substr(i, 8));
		t = blowfishDecryptBlock(t[0], t[1]);
		out += longToStr(Array(t[0], t[1]));
	}
	return removePadding(out, len);
}
/**
 * Blowfish function: encrypt a 64-bit block
 *
 * @param long the first unsigned long
 * @param long the second unsigned long
 * @return long[2] the encrypted long[2]
 * @author www.farfarfar.com
 * @version 0.1
 */
function blowfishEncryptBlock(xl, xr)
{
	// cycles according to blowfish_n
	xl ^= blowfish_p[0];
	xr ^= blowfish_f(xl) ^ blowfish_p[1];
	xl ^= blowfish_f(xr) ^ blowfish_p[2];
	xr ^= blowfish_f(xl) ^ blowfish_p[3];
	xl ^= blowfish_f(xr) ^ blowfish_p[4];
	xr ^= blowfish_f(xl) ^ blowfish_p[5];
	xl ^= blowfish_f(xr) ^ blowfish_p[6];
	xr ^= blowfish_f(xl) ^ blowfish_p[7];
	xl ^= blowfish_f(xr) ^ blowfish_p[8];
	xr ^= blowfish_f(xl) ^ blowfish_p[9];
	xl ^= blowfish_f(xr) ^ blowfish_p[10];
	xr ^= blowfish_f(xl) ^ blowfish_p[11];
	xl ^= blowfish_f(xr) ^ blowfish_p[12];
	xr ^= blowfish_f(xl) ^ blowfish_p[13];
	xl ^= blowfish_f(xr) ^ blowfish_p[14];
	xr ^= blowfish_f(xl) ^ blowfish_p[15];
	xl ^= blowfish_f(xr);
	return Array(xr ^ blowfish_p[17], xl ^ blowfish_p[16]);
}
/**
 * Blowfish function: decrypt a 64-bit block
 *
 * @param long the first unsigned long
 * @param long the second unsigned long
 * @return long[2] the decrypted long[2]
 * @author www.farfarfar.com
 * @version 0.1
 */
function blowfishDecryptBlock(xl, xr)
{
	// cycles according to blowfish_n
	xl ^= blowfish_p[17];
	xr ^= blowfish_f(xl) ^ blowfish_p[16];
	xl ^= blowfish_f(xr) ^ blowfish_p[15];
	xr ^= blowfish_f(xl) ^ blowfish_p[14];
	xl ^= blowfish_f(xr) ^ blowfish_p[13];
	xr ^= blowfish_f(xl) ^ blowfish_p[12];
	xl ^= blowfish_f(xr) ^ blowfish_p[11];
	xr ^= blowfish_f(xl) ^ blowfish_p[10];
	xl ^= blowfish_f(xr) ^ blowfish_p[9];
	xr ^= blowfish_f(xl) ^ blowfish_p[8];
	xl ^= blowfish_f(xr) ^ blowfish_p[7];
	xr ^= blowfish_f(xl) ^ blowfish_p[6];
	xl ^= blowfish_f(xr) ^ blowfish_p[5];
	xr ^= blowfish_f(xl) ^ blowfish_p[4];
	xl ^= blowfish_f(xr) ^ blowfish_p[3];
	xr ^= blowfish_f(xl) ^ blowfish_p[2];
	xl ^= blowfish_f(xr);
	return Array(xr ^ blowfish_p[0], xl ^ blowfish_p[1]);
}
/**
 * Blowfish function: unsigned long F(unsigned long x)
 * @param unsigned long
 * @return unsigned long
 * @author www.farfarfar.com
 * @version 0.1
 */
function blowfish_f(x)
{
	return safeAdd(safeAdd(blowfish_s[0][(x >>> 24) & 0xff],
		blowfish_s[1][(x >>> 16) & 0xff]) ^ blowfish_s[2][(x >>> 8) & 0xff],
		blowfish_s[3][x & 0xff]);
}
/**
 * Add two integers, wrapping at 2^32 to work around some bugs in some browsers.
 *
 * @param int the first integer
 * @param int the second integer
 * @return int
 */
function safeAdd(a, b)
{
	var t = (a & 0xffff) + (b & 0xffff);
	return ((a >> 16) + (b >> 16) + (t >> 16) << 16) | (t & 0xffff);
}
/**
 * Add binary-safe padding to a string.
 *
 * @param string the string to add padding
 * @param int number for the string be divisible by
 * @param int the string length
 * @return the padded string
 * @author www.farfarfar.com
 * @version 0.2
 */
function addPadding(str, divisible, len)
{
	var paddingLen = divisible - (len % divisible);
	for (var i = 0; i < paddingLen; i++)
	{
		str += String.fromCharCode(paddingLen);
	}
	return str;
}
/**
 * Remove binary-safe padding from a string
 *
 * @param string the string to remove padding
 * @param int the string length
 * @return the unpadded string
 * @author www.farfarfar.com
 * @version 0.1
 */
function removePadding(str, len)
{
	return str.substr(0, len - (str.charCodeAt(str.length - 1)));
}
/**
 * Converts a string to an array of longs
 *
 * @param string the string to convert
 * @return long[]
 * @version 0.1
 */
function strToLong(str)
{
	var ar = new Array();
	var len = Math.ceil(str.length / 4);
	for (var i=0; i<len; i++)
	{
		ar[i] = str.charCodeAt(i << 2) + (str.charCodeAt((i << 2) + 1) << 8) +
		(str.charCodeAt((i << 2) + 2) << 16) + (str.charCodeAt((i << 2) + 3) << 24);
	}
	return ar;
}
/**
 * Converts an array of longs to a string
 *
 * @param long[] the array to convert
 * @return string
 * @version 0.1
 */
function longToStr(ar)
{
	var len = ar.length;
	for (var i=0; i<len; i++)
	{
		ar[i] = String.fromCharCode(ar[i] & 0xff, ar[i] >>> 8 & 0xff,
		ar[i] >>> 16 & 0xff, ar[i] >>> 24 & 0xff);
	}
	return ar.join('');
}
/**
 * Returns available algorithm names
 */
function methodNames()
{
	return "tea,tean,bf,bfn";
}
/**
 * Encrypts a string to a binary-safe string using the XXTEA encode algorithm and then Base64 encode
 *
 * @param string the string to encrypt
 * @param string the key to decrypt the string
 * @return string the encrypted string
 * @see #xxTeaEncrypt(String, String)
 * @see #base64Encode(String)
 */
function teaEncrypt(str, key)
{
	return Base64.encode(xxTeaEncrypt(str, key));
}
function teanEncrypt(str, key)
{
	return Base64.encode(xxTeaEncrypt(Base64.encode(str), key));
}
/**
 * Decrypts a string using the Base64 decode algorithm and then XXTEA decode
 *
 * @param string the string to encrypt
 * @param string the key to encrypted the string
 * @return string the hashed string
 * @see #xxTeaDecrypt(String, String)
 * @see #base64Decode(String)
 */
function teaDecrypt(str, key)
{
	return xxTeaDecrypt(Base64.decode(str), key);
}
function teanDecrypt(str, key)
{
	return Base64.decode(xxTeaDecrypt(Base64.decode(str), key));
}
/**
 * Encrypts a string to a binary-safe string using the blowfish encode algorithm and then Base64 encode
 *
 * @param string the string to encrypt
 * @param string the key to decrypt the string
 * @return string the encrypted string
 * @see #blowfishSetKey(String)
 * @see #blowfishEncrypt(String, String)
 * @see #base64Encode(String)
 */
function bfEncrypt(str, key)
{
	blowfishSetKey(key);
	return Base64.encode(blowfishEncrypt(str));
}
function bfnEncrypt(str, key)
{
	blowfishSetKey(key);
	return Base64.encode(blowfishEncrypt(Base64.encode(str)));
}
/**
 * Decrypts a string using the Base64 decode algorithm and then blowfish decode
 *
 * @param string the string to encrypt
 * @param string the key to encrypted the string
 * @return string the hashed string
 * @see #blowfishSetKey(String)
 * @see #blowfishDecrypt(String, String)
 * @see #base64Decodes(String)
 */
function bfDecrypt(str, key)
{
	blowfishSetKey(key);
	return blowfishDecrypt(Base64.decode(str), key);
}
function bfnDecrypt(str, key)
{
	blowfishSetKey(key);
	return Base64.decode(blowfishDecrypt(Base64.decode(str), key));
}
</script>
