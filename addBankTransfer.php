/*
 Notice : I use 3 tables
 - member : store member information and balance(wallet/point..)
 - callbacklog : store every callback log
 - balancelog : store every changes of balance
*/

<?php
$now = date('Y-m-d H:i:s');
$postdata = @$_POST; // $_POST is $dataInsert from banktransfer.php

foreach ($postdata as &$item) {
	$item = $mysql->escape($item);
}

//Ex: Rule about description is "<brandname> <customer account>"
if (strpos($postdata['description'], '<brandname>') !== false) {
	$query = "SELECT id FROM callbacklog WHERE transaction_id = '".$postdata['transaction_id']."'";
	$dataQueryCallbackLog = $mysql->query($query)->row;

	if(empty($dataQueryCallbackLog)){
		$description = $postdata['description'];
		$amount = $postdata['amount'];
		$createDate = $postdata['create_date'];

		//Find customer account based on description "<brandname> <customer account>" 
		//(customer account is email account)
		$account = explode(' ',$description)[1] . '@';
		$query = "SELECT memberid,balance FROM member WHERE memberemail LIKE '%$account%'";
		$data_query_member = $mysql->query($query)->row;

		if(!empty($data_query_member)){
			$current_balance = $data_query_member['balance'];
			$new_balance = $current_balance + $amount;
			$memberid = $data_query_member['memberid'];

            $query = "UPDATE member SET balance = '$new_balance' WHERE memberid = '$memberid'";
			$mysql->query($query);

			$dataInsert = array(
				'memberid' => $memberid,
				'currentbalance' => $current_balance,
				'newbalance' => $new_balance,
				'reason' => "Receive from " .$postdata['service_source'],
				'createddate' => $createDate,
				'addeddate' => $createDate,
				'createdby' => 'callback',
				'addedby' => 'callback',
			);
			$mysql->forminsert('balancelog',$dataInsert); //insert data to table balancelog
		}
	}
	$mysql->forminsert('callbacklog',$postdata); //insert log
}
?>
