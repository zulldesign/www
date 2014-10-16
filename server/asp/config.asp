<%

' --------------------------
'  Configuration parameters
' --------------------------
' Language settings

Const EW_TEST_TRANSACTION = "*** Test Transaction ***"
Const EW_ITEM_NUMBER = "Item Number: "
Const EW_ITEM_NAME = "Item Name: "
Const EW_QUANTITY = "Quantity: "
Const EW_TOTAL = "Total: "
Const EW_OPTION_SEP = ": "
Const EW_DOWNLOAD_URL = "Download URL: "

'Const EW_TRANSACTION_ID = "PayPal Transaction ID"
Const EW_TRANSACTION_ID = "Transaction ID"
Const EW_SHIP_COST = "Shipping Cost"
Const EW_HANDLE_COST = "Handling Cost"
Const EW_TAX_COST = "Tax"
Const EW_SHIPPING_DETAILS = "Shipping details"
Const EW_FIRST_NAME = "First Name"
Const EW_LAST_NAME = "Last Name"
Const EW_ADDRESS_1 = "Address 1"
Const EW_ADDRESS_2 = "Address 2"
Const EW_PHONE = "Phone"
Const EW_CITY = "City"
Const EW_ZIP = "Postal Code"
Const EW_COUNTRY = "Country"
Const EW_STATE = "State"
Const EW_EMAIL = "Email"
Const EW_SHIP_METHOD = "Shipping Method"
Const EW_EMAIL_SENT_TO = "Email sent to"
Const EW_EMAIL_SENT_ERROR = "Email sent error"
Const EW_PAYMENT_SUCCESS_MESSAGE = "Thank you for your purchase. Your payment is received successfully."
Const EW_PAYMENT_FAIL_MESSAGE = "Thank you for your purchase. Unfortunately your payment is not successful. Please click the below URL to try again:"
Const EW_PAYMENT_STATUS_MESSAGE = "Thank you for your purchase. Your payment status is "
Const EW_PAYMENT_CONTACT_MESSAGE = "Please contact below email for details: "
Const EW_TRY_AGAIN = "Try again"
Const EW_DOWNLOAD_MESSAGE = "You will receive an email shortly for the download."
Const EW_CLICK_TO_DOWNLOAD = "Click to download"
Const EW_CONTACT_US = "Please contact us."
Const EW_MISSING_URL = "Missing URL"
Const EW_MISSING_URL_START = "<--$url_"
Const EW_MISSING_URL_END = "-->"
Const EW_EMAIL_CONTENT_START = "*** Begin Email Content ***"
Const EW_EMAIL_CONTENT_END = "*** End Email Content ***"
Const EW_DOWNLOAD = "download"
Const EW_EMAIL_SENT = "Notification email sent to customer."
Const EW_IPN_PAGE_MESSAGE = "You have reached the IPN page"
Const EW_IPN_FAIL_MESSAGE = ""
Const EW_IPN_THANKYOU_MESSAGE = "Thank you for your order"
Const EW_PDT_PAGE_MESSAGE = "You have reached the PDT page"
Const EW_PDT_THANKYOU_MESSAGE = "Thank you for your order"
Const EW_CLICK_TO_PURGE = "Click to purge old download folders"
Const EW_PURGE = "purge"
Const EW_PURGE_SUCCESS = "Purge old download folders successful"

' PayPal settings
Const EW_BUSINESS = "sales@mycompany.com"
Const EW_SENDER_EMAIL = "sales@mycompany.com"
Const EW_RECIPIENT_EMAIL = "sales@mycompany.com"
Const EW_PAYPAL_URL	= "https://www.paypal.com/cgi-bin/webscr"
Const EW_DEFAULT_PAGE = "index.html"
Const EW_IDENTITY_TOKEN = ""
Const EW_PAYPAL_OPTION_0 = "os0d"
Const EW_PAYPAL_OPTION_1 = "os1d"
Const EW_PAYPAL_OPTION_2 = "os2d"
Const EW_PAYPAL_OPTION_3 = "os3d"

' Currency settings
Const EW_CCY_USE_REGIONAL_OPTIONS = True
Const EW_CCY_DISPLAY_SYMBOL = "$"
Const EW_CCY_GROUP_SEPARATOR = ","
Const EW_CCY_DECIMAL_SEPARATOR = "."
Const EW_CCY_NUM_DIGITS = 2

' SMTP settings
Const EW_SMTPSERVER = "localhost"
Const EW_SMTPSERVER_PORT = 25
Const EW_SMTPSERVER_USERNAME = ""
Const EW_SMTPSERVER_PASSWORD = ""

' Download settings
Const EW_DOWNLOAD_TIMEOUT_UNIT = "h" ' Hour
Const EW_DOWNLOAD_TIMEOUT_INTERVAL = 24 ' Download timeout period
Const EW_DOWNLOAD_SRC_PATH = "server/download_02C69C4D3817FDC4600DA3FEAD5B3C25" ' Download source path
Const EW_DOWNLOAD_PATH = "server/download/" ' Download path
Const EW_UNREGISTERED_DOWNLOAD = "unregistered.jpg"

' IPN / PDT / Digital Download settings
Const EW_USE_PAYPAL = True
Const EW_IPN_ENABLED = False
Const EW_PDT_ENABLED = False
Dim EW_DIGITAL_DOWNLOAD
EW_DIGITAL_DOWNLOAD = False
Dim EW_DOWNLOAD_APPROVAL
EW_DOWNLOAD_APPROVAL = False
Const EW_DOWNLOAD_BINARY_WRITE = True

' Other settings
Const EW_LOG_FOLDER = "server/download/log_02C69C4D3817FDC4600DA3FEAD5B3C25" ' Log folder relative to root
Const EW_DB_FOLDER = "server/download/db_02C69C4D3817FDC4600DA3FEAD5B3C25" ' Database folder relative to root
Const EW_RANDOM_KEY = "02C69C4D3817FDC4600DA3FEAD5B3C25"

' Parameters and Session names
Const EW_MENU_ID = "menu"
Const EW_CATEGORY_ID = "cat"
Const EW_SESSION_CATEGORY_ID = "categoryid"
Const EW_ITEM_ID = "id"
Const EW_START_REC = "start"
Const EW_PAGE_NO = "page"
Const EW_SESSION_PAGE_NO = "pagenumber"

' Search keyword
Const EW_COMMAND = "cmd"
Const EW_COMMAND_RESET = "reset"
Const EW_SEARCH_KEYWORD = "keyword"
Const EW_SESSION_SEARCH_KEYWORD = "searchkeyword"

' Use database
Const EW_USE_DATABASE = False
Const EW_APPLICATION_STOPPED = "Application is stopped. Please try again later."

' Write database log
Const EW_WRITE_DATABASE_LOG = False
Const EW_DB_QUOTE_START = "["
Const EW_DB_QUOTE_END = "]"

' Table names
Const EW_TABLENAME_MENU = "Menu"
Const EW_TABLENAME_CATEGORY = "Category"
Const EW_TABLENAME_PRODUCT = "Product"
Const EW_TABLENAME_ITEM = "Item"
Const EW_TABLENAME_REGION = "Region"
Const EW_TABLENAME_COUNTRY = "Country"
Const EW_TABLENAME_STATE = "State"
Const EW_TABLENAME_DISCOUNT = "Discount"
Const EW_TABLENAME_SHIPPINGMETHOD = "ShippingMethod"
Const EW_TABLENAME_SHIPPING = "Shipping"
Const EW_TABLENAME_SHIPPINGTYPE = "ShippingType"
Const EW_TABLENAME_TAX = "Tax"
Const EW_TABLENAME_TXN = "Transaction"
Const EW_TABLENAME_TXNITEM = "TransactionItem"
Const EW_TABLENAME_LOG = "Log"

' Field names (Menu)
Const EW_FIELDNAME_MENUID = "MenuId"
Const EW_FIELDNAME_MENULINK = "MenuLink"
Const EW_FIELDNAME_MENUGEN = "MenuGen"
Const EW_FIELDNAME_MENUURL = "MenuUrl"
Const EW_FIELDNAME_MENUPARENTID = "MenuParentId"
Const EW_FIELDNAME_MENUDISPLAYORDER = "MenuDisplayOrder"
Const EW_FIELDNAME_MENUPAGECONTENT = "MenuPageContent"

' Field names (Category)
Const EW_FIELDNAME_CATEGORYID = "CategoryId"
Const EW_FIELDNAME_CATEGORYNAME = "CategoryName"
Const EW_FIELDNAME_CATEGORYPARENTID = "CategoryParentId"
Const EW_FIELDNAME_CATEGORYDISPLAYORDER = "CategoryDisplayOrder"

' Field names (Item)
Const EW_FIELDNAME_ITEMID = "ItemId"
Const EW_FIELDNAME_ITEMNUMBER = "ItemNumber"
Const EW_FIELDNAME_ITEMNAME = "ItemName"
Const EW_FIELDNAME_ITEMPRICE = "ItemPrice"
Const EW_FIELDNAME_ITEMDESCRIPTION = "ItemDescription"
Const EW_FIELDNAME_ITEMOPTION1FIELDNAME = "ItemOption1FieldName"
Const EW_FIELDNAME_ITEMOPTION1REQUIRED = "ItemOption1Required"
Const EW_FIELDNAME_ITEMOPTION1 = "ItemOption1"
Const EW_FIELDNAME_ITEMOPTION1TYPE = "ItemOption1Type"
Const EW_FIELDNAME_ITEMOPTION2FIELDNAME = "ItemOption2FieldName"
Const EW_FIELDNAME_ITEMOPTION2REQUIRED = "ItemOption2Required"
Const EW_FIELDNAME_ITEMOPTION2 = "ItemOption2"
Const EW_FIELDNAME_ITEMOPTION2TYPE = "ItemOption2Type"
Const EW_FIELDNAME_ITEMOPTION3FIELDNAME = "ItemOption3FieldName"
Const EW_FIELDNAME_ITEMOPTION3REQUIRED = "ItemOption3Required"
Const EW_FIELDNAME_ITEMOPTION3 = "ItemOption3"
Const EW_FIELDNAME_ITEMOPTION3TYPE = "ItemOption3Type"
Const EW_FIELDNAME_ITEMOPTION4FIELDNAME = "ItemOption4FieldName"
Const EW_FIELDNAME_ITEMOPTION4REQUIRED = "ItemOption4Required"
Const EW_FIELDNAME_ITEMOPTION4 = "ItemOption4"
Const EW_FIELDNAME_ITEMOPTION4TYPE = "ItemOption4Type"
Const EW_FIELDNAME_ITEMIMAGE = "ItemImage"
Const EW_FIELDNAME_ITEMIMAGE2 = "ItemImage2"
Const EW_FIELDNAME_ITEMIMAGE3 = "ItemImage3"
Const EW_FIELDNAME_ITEMIMAGE4 = "ItemImage4"
Const EW_FIELDNAME_ITEMCATEGORY = "ItemCategory"
Const EW_FIELDNAME_ITEMWEIGHT = "ItemWeight"
Const EW_FIELDNAME_ITEMCUSTOM = "ItemCustom"
Const EW_FIELDNAME_ITEMSHIPPING = "ItemShipping"
Const EW_FIELDNAME_ITEMSHIPPING2 = "ItemShipping2"
Const EW_FIELDNAME_ITEMHANDLING = "ItemHandling"
Const EW_FIELDNAME_ITEMTAX = "ItemTax"
Const EW_FIELDNAME_ITEMTAXTYPEID = "ItemTaxTypeId"
Const EW_FIELDNAME_ITEMSHIPPINGTYPEID = "ItemShippingTypeId"
Const EW_FIELDNAME_ITEMDISCOUNTTYPEID = "ItemDiscountTypeId"
Const EW_FIELDNAME_ITEMCOUNT = "ItemCount"
Const EW_FIELDNAME_ITEMDOWNLOAD = "ItemDownload"
Const EW_FIELDNAME_ITEMDOWNLOADFN = "ItemDownloadFn"
Const EW_FIELDNAME_ITEMSELECTED = "ItemSelected"

' Field names (Transaction)
Const EW_FIELDNAME_TXNPPID = "TxnPPId"
Const EW_FIELDNAME_TXNBUSINESS = "TxnBusiness"
Const EW_FIELDNAME_TXNTESTIPN = "TxnTestIPN"
Const EW_FIELDNAME_TXNSTATUS = "TxnStatus"
Const EW_FIELDNAME_TXNPAYEREMAIL = "TxnPayerEmail"
Const EW_FIELDNAME_TXNMCGROSS = "TxnMcGross"
Const EW_FIELDNAME_TXNRAWDATA = "TxnRawData"
Const EW_FIELDNAME_TXNDATA = "TxnData"

' Field names (Log)
Const EW_FIELDNAME_LOGTYPE = "LogType"
Const EW_FIELDNAME_LOGDATE = "LogDate"
Const EW_FIELDNAME_LOGTIME = "LogTime"
Const EW_FIELDNAME_LOGKEY = "LogKey"
Const EW_FIELDNAME_LOGVALUE = "LogValue"

' Connection string
Dim EW_CONNECTION_STRING
EW_CONNECTION_STRING = "Provider=Microsoft.Jet.OLEDB.4.0;Data Source=" & Server.MapPath(ew_RootPath&"server/download/db_02C69C4D3817FDC4600DA3FEAD5B3C25/paypal.mdb") & ";"

' Cursor Location
Const EW_CURSORLOCATION = 2

' Cart delimiter
Const EW_CART_DELIMITER = "|"

' Menu SQL
Dim EW_MENU_SELECT_SQL
EW_MENU_SELECT_SQL = "SELECT * FROM " & ew_DbQuote(EW_TABLENAME_MENU)
Dim EW_MENU_MENUID_FILTER
EW_MENU_MENUID_FILTER = ew_DbQuote(EW_FIELDNAME_MENUID) & " = @@" & EW_FIELDNAME_MENUID & "@@"
Dim EW_MENU_DEFAULT_ORDERBY
EW_MENU_DEFAULT_ORDERBY = ew_DbQuote(EW_FIELDNAME_MENUPARENTID) & ", " & ew_DbQuote(EW_FIELDNAME_MENUDISPLAYORDER) & ", " & ew_DbQuote(EW_FIELDNAME_MENUID)

' Category SQL
Dim EW_CATEGORY_SELECT_SQL
EW_CATEGORY_SELECT_SQL = "SELECT * FROM " & ew_DbQuote(EW_TABLENAME_CATEGORY)
Dim EW_CATEGORY_CATEGORYID_FILTER
EW_CATEGORY_CATEGORYID_FILTER = ew_DbQuote(EW_FIELDNAME_CATEGORYID) & " = @@" & EW_FIELDNAME_CATEGORYID & "@@"
Dim EW_CATEGORY_DEFAULT_ORDERBY
EW_CATEGORY_DEFAULT_ORDERBY = ew_DbQuote(EW_FIELDNAME_CATEGORYPARENTID) & ", " & ew_DbQuote(EW_FIELDNAME_CATEGORYDISPLAYORDER) & ", " & ew_DbQuote(EW_FIELDNAME_CATEGORYID)

' Option to include sub categories
Const EW_INCLUDE_SUBCATEGORY = False

' Product SQL
Dim EW_PRODUCT_SELECT_SQL
EW_PRODUCT_SELECT_SQL = "SELECT * FROM " & ew_DbQuote(EW_TABLENAME_PRODUCT)
Dim EW_PRODUCT_DEFAULT_FILTER
EW_PRODUCT_DEFAULT_FILTER = ew_DbQuote(EW_FIELDNAME_ITEMSELECTED) & " = True"
Dim EW_PRODUCT_CATEGORY_FILTER
EW_PRODUCT_CATEGORY_FILTER = ew_DbQuote(EW_FIELDNAME_CATEGORYID) & " IN (@@" & EW_FIELDNAME_CATEGORYID & "@@)"
Dim EW_PRODUCT_SELECT_SUBCATEGORY_SQL, EW_PRODUCT_SUBCATEGORY_FILTER
EW_PRODUCT_SELECT_SUBCATEGORY_SQL = "SELECT DISTINCT " & ew_DbQuote(EW_FIELDNAME_CATEGORYID) & " FROM " & ew_DbQuote(EW_TABLENAME_CATEGORY)
EW_PRODUCT_SUBCATEGORY_FILTER = ew_DbQuote(EW_FIELDNAME_CATEGORYPARENTID) & " IN (@@" & EW_FIELDNAME_CATEGORYPARENTID & "@@)"
Dim EW_PRODUCT_ITEM_FILTER
EW_PRODUCT_ITEM_FILTER = ew_DbQuote(EW_FIELDNAME_ITEMID) & " = @@" & EW_FIELDNAME_ITEMID & "@@"
Dim EW_PRODUCT_ITEMCOUNT_FILTER
Const EW_USE_ITEM_COUNT = False ' Mark all items for sale
Const EW_SHOW_SOLD_OUT = True ' Show sold out items (if not use item count)

' Search filter
Dim EW_PRODUCT_SEARCH_FILTER
EW_PRODUCT_SEARCH_FILTER = _
	ew_DbQuote(EW_FIELDNAME_ITEMNAME) & " LIKE '%@" & EW_SEARCH_KEYWORD & "@%' OR " & _
	ew_DbQuote(EW_FIELDNAME_ITEMNUMBER) & " LIKE '%@" & EW_SEARCH_KEYWORD & "@%'"

' Search setting, possible options as follow
' - "" => Exact Match
' - "OR" => Any words
' - "AND" => All words

Const EW_SEARCH_TYPE = "OR" ' Any words

' Row classes
Const EW_ROW_CLASS_NAME = "ewTableRow"
Const EW_ALT_ROW_CLASS_NAME = "ewTableAltRow"

' Image path
Dim EW_IMAGE_PATH
EW_IMAGE_PATH = ew_RootPath & "images"
Const EW_UNREGISTERED_IMAGE = "unregistered.jpg"

' Image type
Const EW_IMAGE_THUMBNAIL_LIST = "l_"
Const EW_IMAGE_THUMBNAIL_VIEW = "v_"
Const EW_IMAGE_FULL_VIEW = ""

' Image width/height
Const EW_IMAGE_THUMBNAIL_WIDTH = 200
Const EW_IMAGE_THUMBNAIL_HEIGHT = 0
Const EW_IMAGE_THUMBNAIL_WIDTH_VIEW = 250
Const EW_IMAGE_THUMBNAIL_HEIGHT_VIEW = 0
Const EW_FLASH_VERSION = "10.0.0"

' --------------------------
'  Common variables
' --------------------------
' Menu details

Dim ew_MenuId
Dim ew_MenuLink
Dim ew_MenuGen
Dim ew_MenuFn
Dim ew_MenuUrl
Dim ew_MenuParentId
Dim ew_MenuPageContent

' Current category
Dim ew_CurCatId
Dim ew_CurCatName

' Category
Dim ew_CatId
Dim ew_CatFn
Dim ew_CatName
Dim ew_CatParentId

' Cart action
Dim ew_CartAction

' Cart details
Dim ew_ItemId
Dim ew_ItemNumber
Dim ew_ItemName
Dim ew_ItemPrice
Dim ew_ItemDescription
Dim ew_ItemOption1FieldName
Dim ew_ItemOption1Required
Dim ew_ItemOption1
Dim ew_ItemOption1Type
Dim ew_ItemOption2FieldName
Dim ew_ItemOption2Required
Dim ew_ItemOption2
Dim ew_ItemOption2Type
Dim ew_ItemOption3FieldName
Dim ew_ItemOption3Required
Dim ew_ItemOption3
Dim ew_ItemOption3Type
Dim ew_ItemOption4FieldName
Dim ew_ItemOption4Required
Dim ew_ItemOption4
Dim ew_ItemOption4Type
Dim ew_ItemImage
Dim ew_ItemImage2
Dim ew_ItemImage3
Dim ew_ItemImage4
Dim ew_ItemCategory
Dim ew_ItemWeight
Dim ew_ItemCustom
Dim ew_ItemShipping
Dim ew_ItemShipping2
Dim ew_ItemHandling
Dim ew_ItemTax
Dim ew_ItemTaxTypeId
Dim ew_ItemShippingTypeId
Dim ew_ItemDiscountTypeId
Dim ew_ItemDownload
Dim ew_ItemCount
Dim ew_ItemDownloadFn
Dim ew_ItemSelected

' Menu URL
Const ew_MenuFullUrl = "../../menu.asp"
Dim ew_MenuPageUrl
ew_MenuPageUrl = Mid(ew_MenuFullUrl, InStrRev(ew_MenuFullUrl, "/")+1) ' Remove path

' Cart URLs
Const ew_CartFullUrl = "../../cart.asp"
Dim ew_CartUrl
ew_CartUrl = Mid(ew_CartFullUrl, InStrRev(ew_CartFullUrl, "/")+1) ' Remove path
Dim ew_CartDetailFullUrl, ew_CartDetailUrl

' Menu
Const EW_MENUBAR_VERTICAL_CLASSNAME = "MenuBarVertical"
Const EW_MENUBAR_SUBMENU_CLASSNAME = "MenuBarItemSubmenu"
Const EW_MENUBAR_RIGHTHOVER_IMAGE = "Spry_1_6_1_022408/SpryMenuBarRightHover.gif"
%>
