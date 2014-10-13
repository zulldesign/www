<!--##SESSION config##-->
<?php
// Language settings
define("NTK_TEST_TRANSACTION", "<!--##@\TestTransaction##-->", true);
define("NTK_ITEM_NUMBER", "<!--##@\ItemNumber##-->", true);
define("NTK_ITEM_NAME", "<!--##@\ItemName##-->", true);
define("NTK_QUANTITY", "<!--##@\Quantity##-->", true);
define("NTK_DOWNLOAD_URL", "<!--##@\DownloadUrl##-->", true);
define("NTK_EMAIL_SENT_TO", "<!--##@\EmailSentTo##-->", true);
define("NTK_EMAIL_SENT_ERROR", "<!--##@\EmailSentError##-->", true);
define("NTK_PAYMENT_SUCCESS_MESSAGE", "<!--##@\PaymentSuccessMessage##-->", true);
define("NTK_PAYMENT_FAIL_MESSAGE", "<!--##@\PaymentFailMessage##-->", true);
define("NTK_PAYMENT_STATUS_MESSAGE", "<!--##@\PaymentStatusMessage##-->", true);
define("NTK_PAYMENT_CONTACT_MESSAGE", "<!--##@\PaymentContactMessage##-->", true);
define("NTK_TRY_AGAIN", "<!--##@\TryAgain##-->", true);
define("NTK_DOWNLOAD_MESSAGE", "<!--##@\DownloadMessage##-->", true);
define("NTK_CLICK_TO_DOWNLOAD", "<!--##@\ClickToDownload##-->", true);
define("NTK_DOWNLOAD", "<!--##@\Download##-->", true);
define("NTK_EMAIL_SENT", "<!--##@\EmailSent##-->", true);
define("NTK_IPN_PAGE_MESSAGE", "<!--##@\IPNPageMessage##-->", true);
define("NTK_IPN_THANKYOU_MESSAGE", "<!--##@\IPNThankYouMessage##-->", true);
define("NTK_PDT_PAGE_MESSAGE", "<!--##@\PDTPageMessage##-->", true);
define("NTK_PDT_THANKYOU_MESSAGE", "<!--##@\PDTThankYouMessage##-->", true);
define("NTK_CLICK_TO_PURGE", "<!--##@\ClickToPurge##-->", true);
define("NTK_PURGE", "<!--##@\Purge##-->", true);
define("NTK_PURGE_SUCCESS", "<!--##@\PurgeSuccess##-->", true);

// Paypal settings
define("NTK_BUSINESS", "<!--##=TblppEmail##-->", true);
define("NTK_SENDER_EMAIL", "<!--##=PROJ.SenderEmail##-->", true);
define("NTK_RECIPIENT_EMAIL", "<!--##=PROJ.RecipientEmail##-->", true);
<!--##$COND #Product/TblppSandbox/EQ/True##-->
define("NTK_PAYPAL_URL", "https://www.sandbox.paypal.com/cgi-bin/webscr", true);
<!--##$/COND##-->
<!--##$COND #Product/TblppSandbox/EQ/False##-->
define("NTK_PAYPAL_URL", "https://www.paypal.com/cgi-bin/webscr", true);
<!--##$/COND##-->
define("NTK_DEFAULT_PAGE", "<!--##=PROJ.DefaultPage##-->", true);
define("NTK_IDENTITY_TOKEN", "<!--##=PROJ.PDTIdentityToken##-->", true);

// Smtp settings
define("NTK_SMTPSERVER", "<!--##=PROJ.SmtpServer##-->", true);
define("NTK_SMTPSERVER_PORT", <!--##=PROJ.SmtpServerPort##-->, true);
define("NTK_SMTPSERVER_USERNAME", "<!--##=PROJ.SmtpServerUserName##-->", true);
define("NTK_SMTPSERVER_PASSWORD", "<!--##=PROJ.SmtpServerPassword##-->", true);

// Download settings
define("NTK_DOWNLOAD_TIMEOUT_UNIT", "h", true); // hour
define("NTK_DOWNLOAD_TIMEOUT_INTERVAL", <!--##=PROJ.DownloadTimeout##-->, true); // download timeout period
define("NTK_DOWNLOAD_SRC_PATH", "<!--##=DownloadSrcPath##-->", true); // download source path
define("NTK_DOWNLOAD_PATH", "<!--##=PROJ.DownloadPath##-->", true); // download path

// IPN/ PDT / Digital goods settings
<!--##$COND PROJ/UseIPN/EQ/True##-->
define("NTK_IPN_ENABLED", True, true);
<!--##$/COND##-->
<!--##$COND PROJ/UseIPN/EQ/False##-->
define("NTK_IPN_ENABLED", False, true);
<!--##$/COND##-->
<!--##$COND PROJ/UsePDT/EQ/True##-->
define("NTK_PDT_ENABLED", True, true);
<!--##$/COND##-->
<!--##$COND PROJ/UsePDT/EQ/False##-->
define("NTK_PDT_ENABLED", False, true);
<!--##$/COND##-->
<!--##$COND PROJ/DigitalDownload/EQ/True##-->
define("NTK_DigitalDownload", True, true);
<!--##$/COND##-->
<!--##$COND PROJ/DigitalDownload/EQ/False##-->
define("NTK_DigitalDownload", False, true);
<!--##$/COND##-->

<!--##$COND PROJ/DownloadApproval/EQ/True##-->
define("NTK_DownloadApproval", True, true);
<!--##$/COND##-->
<!--##$COND PROJ/DownloadApproval/EQ/False##-->
define("NTK_DownloadApproval", False, true);
<!--##$/COND##-->

// Other settings
define("NTK_LOG_FOLDER", "server/download/log_<!--##=PROJ.RandomKey##-->", true); // log folder relative to root
define("NTK_RANDOM_KEY", "<!--##=PROJ.RandomKey##-->", true);

// Items in array
$arItems = array();
<!--%$ITEMS-->
<!--%$COND !Item/ItemDownload/NE/''-->
$arItems["<!--%=\Product.ItemNumber-->"] = "<!--%=ItemDownload-->";
<!--%$/COND-->
<!--%$/ITEMS-->
?>
<!--##/SESSION##-->