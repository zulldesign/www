﻿Imports System
Imports System.Web
Imports System.Web.SessionState
Imports System.Collections
Imports System.Threading
Imports System.Web.Configuration
Imports System.Configuration

Namespace AspNet.SessionState

  Public NotInheritable Class CustomSessionStateModule
    Implements IHttpModule, IDisposable

    Private pSessionItems As Hashtable = New Hashtable()
    Private pTimer As Timer
    Private pTimerSeconds As Integer = 10
    Private pInitialized As Boolean = False
    Private pTimeout As Integer
    Private pCookieMode As HttpCookieMode = HttpCookieMode.UseCookies
    Private pHashtableLock As ReaderWriterLock = New ReaderWriterLock()
    Private pSessionIDManager As ISessionIDManager
    Private pConfig As SessionStateSection


    ' The SessionItem class is used to store data for a particular session along with
    ' an expiration date and time. SessionItem objects are added to the local Hashtable
    ' in the OnReleaseRequestState event handler and retrieved from the local Hashtable
    ' in the OnAcquireRequestState event handler. The ExpireCallback method is called
    ' periodically by the local Timer to check for all expired SessionItem objects in the
    ' local Hashtable and remove them. 
    Private Class SessionItem
      Friend Items As SessionStateItemCollection
      Friend StaticObjects As HttpStaticObjectsCollection
      Friend Expires As DateTime
    End Class


    '
    ' IHttpModule.Init
    '

    Public Sub Init(ByVal app As HttpApplication) Implements IHttpModule.Init
      ' Add event handlers.
      AddHandler app.AcquireRequestState, New EventHandler(AddressOf Me.OnAcquireRequestState)
      AddHandler app.ReleaseRequestState, New EventHandler(AddressOf Me.OnReleaseRequestState)

      ' Create a SessionIDManager.
      pSessionIDManager = New SessionIDManager()
      pSessionIDManager.Initialize()

      ' If not already initialized, initialize timer and configuration.
      If Not pInitialized Then
        SyncLock GetType(CustomSessionStateModule)
          If Not pInitialized Then
            ' Create a Timer to invoke the ExpireCallback method based on
            ' the pTimerSeconds value (e.g. every 10 seconds).
            pTimer = New Timer(New TimerCallback(AddressOf Me.ExpireCallback), Nothing, 0, pTimerSeconds * 1000)

            ' Get the configuration section and set timeout and CookieMode values.
            Dim cfg As System.Configuration.Configuration = WebConfigurationManager.OpenWebConfiguration(System.Web.Hosting.HostingEnvironment.ApplicationVirtualPath)
            pConfig = CType(cfg.GetSection("system.web/sessionState"), SessionStateSection)

            pTimeout = CInt(pConfig.Timeout.TotalMinutes)
            pCookieMode = pConfig.Cookieless

            pInitialized = True
          End If
        End SyncLock
      End If
    End Sub



    '
    ' IHttpModule.Dispose
    '
    Public Sub Dispose() Implements IHttpModule.Dispose, IDisposable.Dispose
      If Not pTimer Is Nothing Then CType(pTimer, IDisposable).Dispose()
    End Sub


    '
    ' Called periodically by the Timer created in the Init method to check for 
    ' expired sessions and remove expired data.
    '
    Sub ExpireCallback(ByVal state As Object)
      Try
        pHashtableLock.AcquireWriterLock(Int32.MaxValue)

        Me.RemoveExpiredSessionData()

      Finally
        pHashtableLock.ReleaseWriterLock()
      End Try

    End Sub

    '
    ' Recursivly remove expired session data from session collection.
    '
    Private Sub RemoveExpiredSessionData()
      Dim sessionID As String
      Dim entry As DictionaryEntry

      For Each entry In pSessionItems
        Dim item As SessionItem = CType(entry.Value, SessionItem)

        If DateTime.Compare(item.Expires, DateTime.Now) <= 0 Then
          sessionID = entry.Key.ToString()
          pSessionItems.Remove(entry.Key)

          Dim stateProvider As HttpSessionStateContainer = New HttpSessionStateContainer(sessionID, item.Items, item.StaticObjects, pTimeout, False, pCookieMode, SessionStateMode.Custom, False)

          SessionStateUtility.RaiseSessionEnd(stateProvider, Me, EventArgs.Empty)
          Me.RemoveExpiredSessionData()
          Exit For
        End If
      Next entry
    End Sub



    '
    ' Event handler for HttpApplication.AcquireRequestState
    '
    Private Sub OnAcquireRequestState(ByVal [source] As Object, ByVal args As EventArgs)
      Dim app As HttpApplication = CType([source], HttpApplication)
      Dim context As HttpContext = app.Context
      Dim isNew As Boolean = False
      Dim sessionID As String
      Dim sessionData As SessionItem = Nothing
      Dim supportSessionIDReissue As Boolean = True

      pSessionIDManager.InitializeRequest(context, False, supportSessionIDReissue)
      sessionID = pSessionIDManager.GetSessionID(context)


      If Not (sessionID Is Nothing) Then
        Try
          pHashtableLock.AcquireReaderLock(Int32.MaxValue)
          sessionData = CType(pSessionItems(sessionID), SessionItem)

          If Not (sessionData Is Nothing) Then
            sessionData.Expires = DateTime.Now.AddMinutes(pTimeout)
          End If
        Finally
          pHashtableLock.ReleaseReaderLock()
        End Try
      Else
        Dim redirected, cookieAdded As Boolean

        sessionID = pSessionIDManager.CreateSessionID(context)
        pSessionIDManager.SaveSessionID(context, sessionID, redirected, cookieAdded)

        If redirected Then Return
      End If
      If sessionData Is Nothing AndAlso Not WebApplication.Config.Setup.Performance.InhibitSessionForCrawlers OrElse Not WebApplication.Extension.IsCrawler(HttpContext.Current.Request) Then
        ' Identify the session as a new session state instance. Create a new SessionItem
        ' and add it to the local Hashtable.
        isNew = True

        sessionData = New SessionItem()

        sessionData.Items = New SessionStateItemCollection()
        sessionData.StaticObjects = SessionStateUtility.GetSessionStaticObjects(context)
        sessionData.Expires = DateTime.Now.AddMinutes(pTimeout)

        Try
          pHashtableLock.AcquireWriterLock(Int32.MaxValue)
          pSessionItems(sessionID) = sessionData
        Finally
          pHashtableLock.ReleaseWriterLock()
        End Try
      End If

      ' Add the session data to the current HttpContext.
      SessionStateUtility.AddHttpSessionStateToContext(context, New HttpSessionStateContainer(sessionID, sessionData.Items, sessionData.StaticObjects, pTimeout, isNew, pCookieMode, SessionStateMode.Custom, False))

      ' Execute the Session_OnStart event for a new session.
      If isNew Then RaiseEvent Start(Me, EventArgs.Empty)
    End Sub


    '
    ' Event for Session_OnStart event in the Global.asax file.
    '
    Public Event Start As EventHandler



    '
    ' Event handler for HttpApplication.ReleaseRequestState
    '
    Private Sub OnReleaseRequestState(ByVal [source] As Object, ByVal args As EventArgs)
      Dim app As HttpApplication = CType([source], HttpApplication)
      Dim context As HttpContext = app.Context
      Dim sessionID As String

      ' Read the session state from the context
      Dim stateProvider As HttpSessionStateContainer = CType(SessionStateUtility.GetHttpSessionStateFromContext(context), HttpSessionStateContainer)

      ' If Session.Abandon() was called, remove the session data from the local Hashtable
      ' and execute the Session_OnEnd event from the Global.asax file.
      If stateProvider.IsAbandoned Then
        Try
          pHashtableLock.AcquireWriterLock(Int32.MaxValue)

          sessionID = pSessionIDManager.GetSessionID(context)
          pSessionItems.Remove(sessionID)
        Finally
          pHashtableLock.ReleaseWriterLock()
        End Try

        SessionStateUtility.RaiseSessionEnd(stateProvider, Me, EventArgs.Empty)
      End If

      SessionStateUtility.RemoveHttpSessionStateFromContext(context)
    End Sub

  End Class
End Namespace
