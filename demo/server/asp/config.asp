<%

' Language settings
Const NTK_TEST_TRANSACTION = "*** Test Transaction ***"
Const NTK_ITEM_NUMBER = "Item Number: "
Const NTK_ITEM_NAME = "Item Name: "
Const NTK_QUANTITY = "Quantity: "
Const NTK_DOWNLOAD_URL = "Download Url: "
Const NTK_EMAIL_SENT_TO = "Email sent to"
Const NTK_EMAIL_SENT_ERROR = "Email sent error"
Const NTK_PAYMENT_SUCCESS_MESSAGE = "Thank you for your purchase. Your payment is received successfully."
Const NTK_PAYMENT_FAIL_MESSAGE = "Thank you for your purchase. Unfortunately your payment is not successful. Please click the below url to try again:"
Const NTK_PAYMENT_STATUS_MESSAGE = "Thank you for your purchase. Your payment status is "
Const NTK_PAYMENT_CONTACT_MESSAGE = "Please contact below email for details: "
Const NTK_TRY_AGAIN = "Try again"
Const NTK_DOWNLOAD_MESSAGE = "You will receive an email shortly for the download."
Const NTK_CLICK_TO_DOWNLOAD = "Click to download"
Const NTK_DOWNLOAD = "download"
Const NTK_EMAIL_SENT = "Notification email sent to customer for the download."
Const NTK_IPN_PAGE_MESSAGE = "You have reached the IPN page"
Const NTK_IPN_THANKYOU_MESSAGE = "Thank you for your order"
Const NTK_PDT_PAGE_MESSAGE = "You have reached the PDT page"
Const NTK_PDT_THANKYOU_MESSAGE = "Thank you for your order"
Const NTK_CLICK_TO_PURGE = "Click to purge old download folders"
Const NTK_PURGE = "purge"
Const NTK_PURGE_SUCCESS = "Purge old download folders successful"

' Paypal settings
Const NTK_BUSINESS = "admin@zulldesign.ml"
Const NTK_SENDER_EMAIL = ""
Const NTK_RECIPIENT_EMAIL = ""
Const NTK_PAYPAL_URL	= "https://www.paypal.com/cgi-bin/webscr"
Const NTK_DEFAULT_PAGE = "index.html"
Const NTK_IDENTITY_TOKEN = ""

' Smtp settings
Const NTK_SMTPSERVER = "localhost"
Const NTK_SMTPSERVER_PORT = 25
Const NTK_SMTPSERVER_USERNAME = ""
Const NTK_SMTPSERVER_PASSWORD = ""

' Download settings
Const NTK_DOWNLOAD_TIMEOUT_UNIT = "h" ' hour
Const NTK_DOWNLOAD_TIMEOUT_INTERVAL = 24 ' download timeout period
Const NTK_DOWNLOAD_SRC_PATH = "server/download_" ' download source path
Const NTK_DOWNLOAD_PATH = "server/download/" ' download path

' IPN/ PDT / Digital goods settings
Const NTK_IPN_ENABLED = False
Const NTK_PDT_ENABLED = False
Dim NTK_DigitalDownload
NTK_DigitalDownload = False
Dim NTK_DownloadApproval
NTK_DownloadApproval = False

' Other settings
Const NTK_LOG_FOLDER = "server/download/log_C2219007A7C2BCA43E21DA3E873DAADC" ' log folder relative to root
Const NTK_RANDOM_KEY = "C2219007A7C2BCA43E21DA3E873DAADC"

' Items in array
Dim arItems(1, 10), nItems
nItems = 0
%>
