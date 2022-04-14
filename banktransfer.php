<?php
// Secret key from Rezer App
$SECRET_KEY = "***************************"; 
$signature = $_SERVER["HTTP_REZPAY_SIGNATURE"];
$algoFromHeader = $_SERVER["HTTP_REZPAY_SIGNATURE_ALGO"];
$algo = "SHA256";

$rawPayload = file_get_contents('php://input');
$mySignature = hash_hmac($algo, $rawPayload, $SECRET_KEY);

if ($mySignature != $signature) {
	http_response_code(403);
	die('Signature is invalid.');
}

$payload = json_decode($rawPayload,true);
$dataInsert = array(
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

//Function is customed to send transaction data to API AddBankTransfer.php
callAPI('callback_addBankTransfer.api',array(),$dataInsert);
// Response that callback is success in order not to Re-callback
http_response_code(200);
?>
