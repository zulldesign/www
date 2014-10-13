<!--##SESSION pdt_include##-->
<?php
// PHP5 with register_long_arrays off
if (@phpversion() >= '5.0.0' && (!@ini_get('register_long_arrays') || @ini_get('register_long_arrays') == '0' || strtolower(@ini_get('register_long_arrays')) == 'off'))
{
	$HTTP_POST_VARS = &$_POST;
	$HTTP_GET_VARS = &$_GET;
	$HTTP_SERVER_VARS = &$_SERVER;
	$HTTP_ENV_VARS = &$_ENV;
}

include("config.php");
include("ntkfn.php");
?>
<!--##/SESSION##-->

<!--##SESSION pdt_content##-->
<?php
$arrCart = array();
$arrTxDetails = array();

$sMsg = ""; // Initialize

if (NTK_PDT_ENABLED) {

	$sTx = @$HTTP_GET_VARS["tx"];
	if ($sTx == "") {
		$sMsg .= NTK_PDT_PAGE_MESSAGE . "<br><br>";
	} else {

		$sTxDetails = GetTransaction($sTx);
		$arTxDetails = split("\n", $sTxDetails);

		foreach ($arTxDetails as $key => $value) {
			$idx = strpos(trim($value), "=");
			if ($idx !== false) {
				list($sKey, $sValue) = split("=", $value);
				$arrTxDetails[strtolower($sKey)] = urldecode($sValue);
			}
		}

		$sBusiness = GetPDTValue("business");
		$sPaymentStatus = GetPDTValue("payment_status");

		// Audit trail for reference
		WriteLog("PDT", "txn_id", $sTx);
		WriteLog("PDT", "business", $sBusiness);
		WriteLog("PDT", "status", $sPaymentStatus);

		$bSuccess = ($sPaymentStatus == "Completed" && strtolower($sBusiness) == strtolower(NTK_BUSINESS));

		if ($bSuccess) {
			$item = GetPDTValue("item_number");
			if ($item <> "") {
				$nCartItems = 1;
				$arrCart[] = array($item, GetPDTValue("item_name"), GetPDTValue("quantity"));
				// Audit trail for reference
				WriteLog("PDT", "item_number", $item);
				WriteLog("PDT", "item_name", $arrCart[0][1]);
				WriteLog("PDT", "quantity", $arrCart[0][2]);
			} else {
				$nCartItems = GetPDTValue("num_cart_items");
				for ($i=1; $i<=$nCartItems; $i++) {
					$arrCart[] = array(GetPDTValue("item_number$i"),
						GetPDTValue("item_name$i"), GetPDTValue("quantity$i"));
					// Audit trail for reference
					WriteLog("PDT", "item_number_$i", $arrCart[$i-1][0]);
					WriteLog("PDT", "item_name_$i", $arrCart[$i-1][1]);
					WriteLog("PDT", "quantity_$i", $arrCart[$i-1][2]);
				}
			}
		} else {
			$nCartItems = 0;
		}

		if ($bSuccess) {
			$sMsg .= NTK_PAYMENT_SUCCESS_MESSAGE . "<br><br>";
		} else {
			// Handle different payment status here
			if ($sPaymentStatus == "Denied" || $sPaymentStatus == "Failed") {
				$sMsg .= NTK_PAYMENT_FAIL_MESSAGE . "<br>";
				$sMsg .= "<a href=\"" . RootPath() . NTK_DEFAULT_PAGE . "\">" . NTK_TRY_AGAIN . "</a><br><br>";
			} else {
				if ($sPaymentStatus == "")
					$sPaymentStatus = "Unknown";
				$sMsg .= NTK_PAYMENT_STATUS_MESSAGE . " '" . $sPaymentStatus . "'.<br>";
				$sMsg .= NTK_PAYMENT_CONTACT_MESSAGE . "<a href=\"mailto:" . NTK_BUSINESS . "\">" . NTK_BUSINESS . "</a>.<br><br>";
			}

		}

		if (NTK_DownloadApproval) {
			$sMsg .= NTK_DOWNLOAD_MESSAGE . "<br><br>";
		} else {

			if ($bSuccess) {

				$folder = GetTempFolder($sTx); // Get temp folder based on tx
				// Show download links for all purchased items
				for ($i=1; $i<=$nCartItems; $i++) {
					list($item_number, $item_name, $quantity) = $arrCart[$i-1];
					if (NTK_DigitalDownload) {
						$file = GetDownloadFile($item_number);
					} else {
						$file = "";
					}
					if ($file <> "")
						$url = CopyTempFile($folder, $file); // Get url
					else
						$url = "";
					if ($url <> "") {
						// Audit trail for reference
						WriteLog("PDT", "url_$i", $url);
						$sMsg .= NTK_ITEM_NUMBER . " " . $item_number . "<br>";
						$sMsg .= NTK_ITEM_NAME . " " . $item_name . "<br>";
						$sMsg .= NTK_QUANTITY . " " . $quantity . "<br>";
						$sMsg .= NTK_CLICK_TO_DOWNLOAD . " " . "<a href=\"" . $url . "\" target=\"_blank\">" . NTK_DOWNLOAD . "</a><br><br>";
					} else {
						$sMsg .= NTK_ITEM_NUMBER . " " . $item_number . "<br>";
						$sMsg .= NTK_ITEM_NAME . " " . $item_name . "<br>";
						$sMsg .= NTK_QUANTITY . " " . $quantity . "<br><br>";
					}
				}

			}
		}
	}

} else {
	$sMsg .= NTK_PDT_THANKYOU_MESSAGE . "<br><br>";
}

if ($sMsg <> "") {
	echo "<span class=\"paypalsb\">" . $sMsg . "</span>";
}

function GetTransaction($tx) {

	global $HTTP_POST_VARS;
	$sCmd = "_notify-synch";
	$sIdentityToken = NTK_IDENTITY_TOKEN;

	$url = NTK_PAYPAL_URL;
	$method = "POST";
	$postdata = "";
	foreach ($HTTP_POST_VARS as $key => $value) {
		if ($postdata <> "") $postdata .= "&";
		$postdata .= $key . "=" . urlencode(GetPostVars($key));
	}
	$postdata .= "&cmd=" . urlencode($sCmd) . "&at=" . urlencode($sIdentityToken) . "&tx=" . urlencode($tx);
	return GetContent($url, $method, $postdata);

}

function GetPDTValue($name) {
	global $arrTxDetails;
	return @$arrTxDetails[strtolower($name)];
}

function FoundCartItem($item_name) {
	for ($i=1; $i<=$nCartItems; $i++) {
		if ($item_name == $arrCart[$i-1][1])
			return true;
	}
	return false;
}
?>
<!--##/SESSION##-->