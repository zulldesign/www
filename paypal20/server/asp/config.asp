<!--##SESSION config##-->
<%
' Language settings
Const NTK_TEST_TRANSACTION = "<!--##@'TestTransaction##-->"
Const NTK_ITEM_NUMBER = "<!--##@'ItemNumber##-->"
Const NTK_ITEM_NAME = "<!--##@'ItemName##-->"
Const NTK_QUANTITY = "<!--##@'Quantity##-->"
Const NTK_DOWNLOAD_URL = "<!--##@'DownloadUrl##-->"
Const NTK_EMAIL_SENT_TO = "<!--##@'EmailSentTo##-->"
Const NTK_EMAIL_SENT_ERROR = "<!--##@'EmailSentError##-->"
Const NTK_PAYMENT_SUCCESS_MESSAGE = "<!--##@'PaymentSuccessMessage##-->"
Const NTK_PAYMENT_FAIL_MESSAGE = "<!--##@'PaymentFailMessage##-->"
Const NTK_PAYMENT_STATUS_MESSAGE = "<!--##@'PaymentStatusMessage##-->"
Const NTK_PAYMENT_CONTACT_MESSAGE = "<!--##@'PaymentContactMessage##-->"
Const NTK_TRY_AGAIN = "<!--##@'TryAgain##-->"
Const NTK_DOWNLOAD_MESSAGE = "<!--##@'DownloadMessage##-->"
Const NTK_CLICK_TO_DOWNLOAD = "<!--##@'ClickToDownload##-->"
Const NTK_DOWNLOAD = "<!--##@'Download##-->"
Const NTK_EMAIL_SENT = "<!--##@'EmailSent##-->"
Const NTK_IPN_PAGE_MESSAGE = "<!--##@'IPNPageMessage##-->"
Const NTK_IPN_THANKYOU_MESSAGE = "<!--##@'IPNThankYouMessage##-->"
Const NTK_PDT_PAGE_MESSAGE = "<!--##@'PDTPageMessage##-->"
Const NTK_PDT_THANKYOU_MESSAGE = "<!--##@'PDTThankYouMessage##-->"
Const NTK_CLICK_TO_PURGE = "<!--##@'ClickToPurge##-->"
Const NTK_PURGE = "<!--##@'Purge##-->"
Const NTK_PURGE_SUCCESS = "<!--##@'PurgeSuccess##-->"

' Paypal settings
Const NTK_BUSINESS = "<!--##=TblppEmail##-->"
Const NTK_SENDER_EMAIL = "<!--##=PROJ.SenderEmail##-->"
Const NTK_RECIPIENT_EMAIL = "<!--##=PROJ.RecipientEmail##-->"
<!--##$COND #Product/TblppSandbox/EQ/True##-->
Const NTK_PAYPAL_URL = "https://www.sandbox.paypal.com/cgi-bin/webscr"
<!--##$/COND##-->
<!--##$COND #Product/TblppSandbox/EQ/False##-->
Const NTK_PAYPAL_URL	= "https://www.paypal.com/cgi-bin/webscr"
<!--##$/COND##-->
Const NTK_DEFAULT_PAGE = "<!--##=PROJ.DefaultPage##-->"
Const NTK_IDENTITY_TOKEN = "<!--##=PROJ.PDTIdentityToken##-->"

' Smtp settings
Const NTK_SMTPSERVER = "<!--##=PROJ.SmtpServer##-->"
Const NTK_SMTPSERVER_PORT = <!--##=PROJ.SmtpServerPort##-->
Const NTK_SMTPSERVER_USERNAME = "<!--##=PROJ.SmtpServerUserName##-->"
Const NTK_SMTPSERVER_PASSWORD = "<!--##=PROJ.SmtpServerPassword##-->"

' Download settings
Const NTK_DOWNLOAD_TIMEOUT_UNIT = "h" ' hour
Const NTK_DOWNLOAD_TIMEOUT_INTERVAL = <!--##=PROJ.DownloadTimeout##--> ' download timeout period
Const NTK_DOWNLOAD_SRC_PATH = "<!--##=DownloadSrcPath##-->" ' download source path
Const NTK_DOWNLOAD_PATH = "<!--##=PROJ.DownloadPath##-->" ' download path

' IPN/ PDT / Digital goods settings
<!--##$COND PROJ/UseIPN/EQ/True##-->
Const NTK_IPN_ENABLED = True
<!--##$/COND##-->
<!--##$COND PROJ/UseIPN/EQ/False##-->
Const NTK_IPN_ENABLED = False
<!--##$/COND##-->
<!--##$COND PROJ/UsePDT/EQ/True##-->
Const NTK_PDT_ENABLED = True
<!--##$/COND##-->
<!--##$COND PROJ/UsePDT/EQ/False##-->
Const NTK_PDT_ENABLED = False
<!--##$/COND##-->
Dim NTK_DigitalDownload
<!--##$COND PROJ/DigitalDownload/EQ/True##-->
NTK_DigitalDownload = True
<!--##$/COND##-->
<!--##$COND PROJ/DigitalDownload/EQ/False##-->
NTK_DigitalDownload = False
<!--##$/COND##-->
Dim NTK_DownloadApproval
<!--##$COND PROJ/DownloadApproval/EQ/True##-->
NTK_DownloadApproval = True
<!--##$/COND##-->
<!--##$COND PROJ/DownloadApproval/EQ/False##-->
NTK_DownloadApproval = False
<!--##$/COND##-->

' Other settings
Const NTK_LOG_FOLDER = "server/download/log_<!--##=PROJ.RandomKey##-->" ' log folder relative to root
Const NTK_RANDOM_KEY = "<!--##=PROJ.RandomKey##-->"

' Items in array
<!--%$ITEMS-->
<!--%$COND RECORD/CurrentRecord/EQ/1-->
Dim arItems(1, <!--%=RECORD.RecordCount-->), nItems
nItems = 0
<!--%$/COND-->
<!--%$COND !Item/ItemDownload/NE/''-->
arItems(0, nItems) = "<!--%=ItemNumber_Quote-->"
arItems(1, nItems) = "<!--%=ItemDownload-->"
nItems = nItems + 1
<!--%$/COND-->
<!--%$/ITEMS-->
%>
<!--##/SESSION##-->