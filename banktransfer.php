<?php
//Your Config
//require_once(__DIR__ . '/config.php');


// Secret key, from "Quản lý API" - "Thông tin API" on Rezer App
$SECRET_KEY = "***************************"; 

// Confirm signature
$signature = $_SERVER["HTTP_REZPAY_SIGNATURE"]; // Signature server in header RezPay-Signature
$algoFromHeader = $_SERVER["HTTP_REZPAY_SIGNATURE_ALGO"]; // Algorithm HMAC

$algo = "SHA256"; // Default is SHA256
//
$rawPayload = file_get_contents('php://input'); // Get payload
$mySignature = hash_hmac($algo, $rawPayload, $SECRET_KEY); // Create signature with SECRET KEY
//
if ($mySignature != $signature) {
	http_response_code(403);
	die('Signature is invalid.');
}

//Signature is valid , you can save this transaction log
$payload = json_decode($rawPayload,true); // Decode json

$data_insert = array(
	'transaction_id' => $payload['transaction_id'],
	'uid' => $payload['uid'],
	'account_id' => $payload['account']['id'],
	'account_name' => $payload['account']['name'],
	'account_provider' => $payload['account']['provider'],
	'connect_info_id' => $payload['connect_info']['id'],
	'connect_info_name' => $payload['connect_info']['name'],
	'type' => $payload['type'],
	'service_source' => $payload['service_source'],
	'amount' => $payload['amount'],
	'description' => $payload['description'],
	'create_date' => $payload['create_date'],
);

//My Custom API, to save data in database
callAPI('callback_addBankTransfer.api',array(),$data_insert);

http_response_code(200); // Tell RezPay server that callback is success in order not to Re-callback
echo 'Giao dịch thành công';

?>