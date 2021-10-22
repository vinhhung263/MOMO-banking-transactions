<?php
//This source code below is my mini project (reference)
//You can custom anything for yourself.
//Notice : I use 3 table for this function
// Table member : store member information and balance(wallet/point..)
// Table callbacklog : store every callbacklog
// Table balancelog : store every changes of balance
beautifyPOSTDATA($POST);
$now = date('Y-m-d H:i:s');
$error = "";
$error_field = "";

$postdata = @$_POST;
// escape data
foreach ($postdata as &$item) {
	$item = $mysql->escape($item);
}

//For example, your rule about description is "<brandname> <customer account>"
//Firstly i check if description has <brandname>
if (strpos($postdata['description'], '<brandname>') !== false) {

	//Then i check if transaction_id is available in table Callbacklog
	$query = "SELECT id FROM callbacklog WHERE transaction_id = '".$postdata['transaction_id']."'";
	$data_query_callbacklog = $mysql->query($query)->row;
	if(empty($data_query_callbacklog)){
		//If transaction_id is not available, push transaction to database and update Balance(wallet,point,..) from Member table
		$description = $postdata['description'];
		$amount = $postdata['amount'];
		$create_date = $postdata['create_date'];

		//Find customer account based on description "<brandname> <customer account>" 
		//(in this case, customer account is email account)
		// Ex: mail hungpv263@gmail.com -> account hungpv263
		$account = explode(' ',$description)[1] . '@';
		//After that, i query that member having email account like description of transaction
		$query = "SELECT memberid,balance FROM member WHERE memberemail LIKE '%$account%'";
		$data_query_member = $mysql->query($query)->row;
		if(!empty($data_query_member)){
			//If existing this member then add balance
			$current_balance = $data_query_member['balance'];
			$new_balance = $current_balance + $amount;
			$memberid = $data_query_member['memberid'];
			//Update balance in member table
			$query = "UPDATE member SET balance = '$new_balance' WHERE memberid = '$memberid'";
			$mysql->query($query);

			//Add balancelog after updating balance for member
			$data_insert = array(
				'memberid' => $memberid,
				'currentbalance' => $current_balance,
				'newbalance' => $new_balance,
				'reason' => "Receive from " .$postdata['service_source'],
				'createddate' => $create_date,
				'addeddate' => $create_date,
				'createdby' => 'callback',
				'addedby' => 'callback',
			);
			$mysql->forminsert('balancelog',$data_insert);
		}
	}
	
	//Add callbacklog after Rezer call callbackURL to your website
	$mysql->forminsert('callbacklog',$postdata);
}




?>
