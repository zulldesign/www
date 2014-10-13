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

$arrCart = array();

$sEmailSubject = "";
$sEmailFrom = "";
$sEmailTo = "";
$sEmailCc = "";
$sEmailBcc = "";
$sEmailFormat = "";
$sEmailContent = "";

if (NTK_IPN_ENABLED) {

	if (VerifiedTransaction()) {
	
		$sTx = @$HTTP_POST_VARS["txn_id"];
		$sBusiness = @$HTTP_POST_VARS["receiver_email"];
		$sPaymentStatus = @$HTTP_POST_VARS["payment_status"];
		$sPayerEmail = @$HTTP_POST_VARS["payer_email"];
		$testipn = @$HTTP_POST_VARS["test_ipn"];
		$amount = @$HTTP_POST_VARS["mc_gross"];
		if ($testipn == "1") {
			$testind = " " . NTK_TEST_TRANSACTION;
		} else {
			$testind = "";
		}

		// Audit trail for reference
		WriteLog("IPN", "txn_id", $sTx);
		WriteLog("IPN", "business", $sBusiness);
		WriteLog("IPN", "status", $sPaymentStatus);
		WriteLog("IPN", "payer_email", $sPayerEmail);
		WriteLog("IPN", "test_ipn", $testipn);
		WriteLog("IPN", "mc_gross", $amount);

		if ($sPaymentStatus == "Completed" && strtolower($sBusiness) == strtolower(NTK_BUSINESS)) {
			$item = GetPostVars("item_number");
			if ($item <> "") {
				$nCartItems = 1;
				$arrCart[] = array($item, GetPostVars("item_name"), @$HTTP_POST_VARS["quantity"]);
			} else {
				$nCartItems = @$HTTP_POST_VARS["num_cart_items"];
				for ($i=1; $i<=$nCartItems; $i++) {
					$arrCart[] = array(GetPostVars("item_number".$i),
						GetPostVars("item_name".$i),
						@$HTTP_POST_VARS["quantity".$i]);
				}
			}
		}

			$folder = GetTempFolder($sTx); // Get temp folder based on tx

			$sMail = "";
			for ($i=1; $i<=$nCartItems; $i++) {
				if ($sMail <> "") $sMail .= "\n\n"; // separate between items
				list($item_number, $item_name, $quantity) = $arrCart[$i-1];
				if (NTK_DigitalDownload) {
					$file = GetDownloadFile($item_number);
				} else {
					$file = "";
				}
				if ($file <> "") {
					$url = CopyTempFile($folder, $file);
					$url = GetServerUrl() . $url;
					WriteLog("IPN", "item_number_$i", $item_number);
					WriteLog("IPN", "item_name_$i", $item_name);
					WriteLog("IPN", "quantity_$i", $quantity);
					WriteLog("IPN", "url_$i", $url);
					$sMail .= NTK_ITEM_NUMBER . $item_number . "\n" .
									NTK_ITEM_NAME . $item_name . "\n" .
									NTK_QUANTITY . $quantity . "\n" .
									NTK_DOWNLOAD_URL . $url;
				} else {
					WriteLog("IPN", "item_number_$i", $item_number);
					WriteLog("IPN", "item_name_$i", $item_name);
					WriteLog("IPN", "quantity_$i", $quantity);
					$sMail .= NTK_ITEM_NUMBER . $item_number . "\n" .
									NTK_ITEM_NAME . $item_name . "\n" .
									NTK_QUANTITY . $quantity;
				}
			}

			// Requires approval
			if (NTK_DownloadApproval) {

				// Write notify email text to file
				//Dim sContent
				$sContent = LoadTxt("ipn.txt");
				$sContent = str_replace("<!--\$From-->", NTK_SENDER_EMAIL, $sContent); // Replace sender
				$sContent = str_replace("<!--\$To-->", $sPayerEmail, $sContent); // Replace receiver
				$sContent = str_replace("<!--\$Txn_ID-->", $sTx, $sContent); // Replace transaction id
				$sContent = str_replace("<!--\$OrderDetails-->", $sMail, $sContent); // Replace order details
				$file = "notify_$sTx.txt";
				WriteFile($folder, $file, $sContent);
				// Send email to seller for approval
				LoadEmail("approval.txt");
				$sEmailFrom = str_replace("<!--\$From-->", NTK_SENDER_EMAIL, $sEmailFrom); // Replace sender
				$sEmailTo = str_replace("<!--\$To-->", NTK_RECIPIENT_EMAIL, $sEmailTo); // Replace receiver
				$sEmailSubject = str_replace("<!--\$Txn_ID-->", $sTx, $sEmailSubject); // Replace transaction id
				$sEmailSubject .= $testind;
				$sEmailContent = str_replace("<!--\$Txn_ID-->", $sTx, $sEmailContent); // Replace transaction id
				$sEmailContent = str_replace("<!--\$OrderAmount-->", $amount, $sEmailContent); // Replace order amount
				$sEmailContent = str_replace("<!--\$OrderDetails-->", $sMail, $sEmailContent); // Replace order details
				$url = GetServerUrl() . GetCurrentPathInfo() . "/approval.php?tx=$sTx&testipn=$testipn";
				$sEmailContent = str_replace("<!--\$ApprovalUrl-->", $url, $sEmailContent); // Replace approval url
				if (Send_Email($sEmailFrom, $sEmailTo, $sEmailCc, $sEmailBcc, $sEmailSubject, $sEmailContent, $sEmailFormat)) {
					WriteLog("IPN", NTK_EMAIL_SENT_TO, $sEmailTo);
					if ($sEmailCc <> "")
						WriteLog("IPN", NTK_EMAIL_SENT_TO, $sEmailCc);
					if ($sEmailBcc <> "")
						WriteLog("IPN", NTK_EMAIL_SENT_TO, $sEmailBcc);
				} else {
					WriteLog("IPN", NTK_EMAIL_SENT_TO, $sEmailTo);
					if ($sEmailCc <> "")
						WriteLog("IPN", NTK_EMAIL_SENT_TO, $sEmailCc);
					if ($sEmailBcc <> "")
						WriteLog("IPN", NTK_EMAIL_SENT_TO, $sEmailBcc);
					WriteLog("IPN", NTK_EMAIL_SENT_ERROR, $sEmailTo);
				}

			} else { // Send notification email directly

				LoadEmail("ipn.txt");
				$sEmailFrom = str_replace("<!--\$From-->", NTK_SENDER_EMAIL, $sEmailFrom); // Replace sender
				$sEmailTo = str_replace("<!--\$To-->", $sPayerEmail, $sEmailTo); // Replace receiver
				$sEmailSubject = str_replace("<!--\$Txn_ID-->", $sTx, $sEmailSubject); // Replace transaction id
				$sEmailSubject .= $testind;
				if ($sEmailBcc <> "")
					$sEmailBcc .= ";";
				$sEmailBcc .= NTK_RECIPIENT_EMAIL; // Bcc recipient
				$sEmailContent = str_replace("<!--\$Txn_ID-->", $sTx, $sEmailContent); // Replace transaction id
				$sEmailContent = str_replace("<!--\$OrderDetails-->", $sMail, $sEmailContent); // Replace order details
				if (Send_Email($sEmailFrom, $sEmailTo, $sEmailCc, $sEmailBcc, $sEmailSubject, $sEmailContent, $sEmailFormat)) {
					WriteLog("IPN", NTK_EMAIL_SENT_TO, $sEmailTo);
					if ($sEmailCc <> "")
						WriteLog("IPN", NTK_EMAIL_SENT_TO, $sEmailCc);
					if (sEmailBcc <> "")
						WriteLog("IPN", NTK_EMAIL_SENT_TO, $sEmailBcc);
				} else {
					WriteLog("IPN", NTK_EMAIL_SENT_TO, $sEmailTo);
					if ($sEmailCc <> "")
						WriteLog("IPN", NTK_EMAIL_SENT_TO, $sEmailCc);
					if ($sEmailBcc <> "")
						WriteLog("IPN", NTK_EMAIL_SENT_TO, $sEmailBcc);
					WriteLog("IPN", NTK_EMAIL_SENT_ERROR, $sEmailTo);
				}

			}

	} else {
		echo NTK_IPN_PAGE_MESSAGE;
	}

} else {
	echo NTK_IPN_THANKYOU_MESSAGE;
}


function VerifiedTransaction() {

	global $HTTP_POST_VARS;
	$sStatus = "";
	$sCmd = "_notify-validate";
	$url = NTK_PAYPAL_URL;
	$method = "POST";
	$postdata = "";
	foreach ($HTTP_POST_VARS as $key => $value) {
		if ($postdata <> "") $postdata .= "&";
		$postdata .= $key . "=" . urlencode(GetPostVars($key));
	}
	if ($postdata <> "") {
		$postdata .= "&cmd=" . urlencode($sCmd);
		$sStatus = GetContent($url, $method, $postdata);
	}
	return ($sStatus == "VERIFIED");

}
?>