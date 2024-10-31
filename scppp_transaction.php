<?php

function scppp_view_purchase_log(){
	global $table_prefix, $wpdb;
}

function scppp_delete_purchase_log(){
	global $table_prefix, $wpdb;
}

function scppp_transaction_history(){
	global $table_prefix, $wpdb;
	
	if(isset($_POST['veiw_purchase_log'])){
		scppp_view_purchase_log();
	}
	else{
		if(isset($_POST['delete_purchase_log'])){
			scppp_delete_purchase_log();
		}
		scppp_veiw_transaction_history();
	}
}

function scppp_veiw_transaction_history(){
	global $table_prefix, $wpdb;
	
	$sql="SELECT * FROM ".$table_prefix."scppp_purchase_log ORDER BY id DESC";
	$results=$wpdb->get_results($sql);
	
	echo '<div class="wrap">';
	echo '<div class="icon32" id="icon-themes"><br></div><h2>SCPPP Transaction History</h2><br />';
	echo '<table cellspacing="0" class="widefat fixed">';
	echo '<thead><tr>';
	echo '<th class="manage-column" width="20">Sn</th>';
	echo '<th class="manage-column">User Name</th>';
	echo '<th class="manage-column">Payer Name</th>';
	echo '<th class="manage-column">Payer Status</th>';
	echo '<th class="manage-column">Item</th>';
	echo '<th class="manage-column" width="130">Transaction ID</th>';
	echo '<th class="manage-column">Payment Status</th>';
	echo '<th class="manage-column" width="60">Amount</th>';
	echo '<th class="manage-column" width="80">Date Paied</th>';
	echo '<th class="manage-column" width="60">Status</th>';
	echo '<th class="manage-column" width="25"></th>';
	echo '<th class="manage-column" width="25"></th>';
	echo '</tr></thead>';
	echo '<tfoot><tr><th colspan="12"></th></tr></tfoot>';
	$i=1;
	foreach($results as $plres){
		echo '<tr>';
		echo '<td>'.$i++.'</td>';
		echo '<td>'.$plres->wp_user_name.'</td>';
		echo '<td>'.$plres->payer_first_name.' '.$plres->payer_last_name.'</td>';
		echo '<td>'.$plres->payer_status.'</td>';
		echo '<td>'.$plres->item_name.'</td>';
		echo '<td>'.$plres->transaction_id.'</td>';
		echo '<td>'.$plres->payment_status.'</td>';
		echo '<td>'.$plres->total_price.'</td>';
		echo '<td>'.$plres->payment_date.'</td>';
		echo '<td>'.$plres->scppp_status.'</td>';
		echo '<td>';
		?><form method="post" enctype="multipart/form-data" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']);?>" ><?php
		echo '<input type="hidden" name="purchase_log_id" id="purchase_log_id" value="'.$plres->id.'" />';
		echo '<input type="hidden" name="veiw_purchase_log" id="veiw_purchase_log" />';
		echo '</form>';
		echo '</td>';
		echo '<td>';
		?><form method="post" enctype="multipart/form-data" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']);?>" ><?php
		echo '<input type="hidden" name="purchase_log_id" id="purchase_log_id" value="'.$plres->id.'" />';
		echo '<input type="hidden" name="delete_purchase_log" id="delete_purchase_log" />';
		echo '</form>';
		echo '</td>';
		echo '</tr>';
	}
	echo '</table>';
	echo '</div>';
}

function scppp_insert_transaction_data(){
	global $user_login, $current_user, $table_prefix, $wpdb;
	
	$user_id=$current_user->ID;
	$user_name=$current_user->user_login;
	$session_id='123';
	$scppp_status='pending';

	$auth_id=$_GET['authcode'];
	$transaction_subject=$_POST['transaction_subject'];//2096
	$txn_type=$_POST['txn_type'];
	$payment_date=$_POST['payment_date'];
	$last_name=$_POST['last_name'];
	$residence_country=$_POST['residence_country'];
	$pending_reason=$_POST['pending_reason'];
	$item_name=$_POST['item_name'];
	$payment_gross=$_POST['payment_gross'];
	$mc_currency=$_POST['mc_currency'];
	$business=$_POST['business'];
	$payment_type=$_POST['payment_type'];
	$protection_eligibility=$_POST['protection_eligibility'];
	$payer_status=$_POST['payer_status'];
	$verify_sign=$_POST['verify_sign'];
	$txn_id=$_POST['txn_id'];
	$payer_email=$_POST['payer_email'];
	$tax=$_POST['tax'];
	$first_name=$_POST['first_name'];
	$receiver_email=$_POST['receiver_email'];
	$quantity=$_POST['quantity'];
	$payer_id=$_POST['payer_id'];
	$receiver_id=$_POST['receiver_id'];
	$item_number=$_POST['item_number'];
	$payment_status=$_POST['payment_status'];
	$handling_amount=$_POST['handling_amount'];
	$shipping=$_POST['shipping'];
	$mc_gross=$_POST['mc_gross'];
	$custom=$_POST['custom'];
	
	
	$wpdb->insert( ''.$table_prefix.'scppp_purchase_log',
					array(
						'wp_user_id'		=> $user_id,
						'wp_user_name'		=> $user_name,
						'payer_first_name'	=> $first_name,
						'payer_last_name'	=> $last_name,
						'payer_id'			=> $payer_id,
						'payer_status'		=> $payer_status,
						'payer_email'		=> $payer_email,
						'verify_sign' 		=> $verify_sign,
						'transaction_id' 	=> $txn_id,
						'transaction_type'	=> $txn_type,
						'session_id'		=> $session_id,
						'auth_id'			=> $auth_id,
						'item_name'			=> $item_name,
						'item_number'		=> $item_number,
						'item_quantity'		=> $quantity,
						'total_price'		=> $payment_gross,
						'mc_gross'			=> $mc_gross,
						'handling_amount'	=> $handling_amount,
						'shipping'			=> $shipping,
						'tax'				=> $tax,
						'payment_status'	=> $payment_status,
						'payment_type'		=> $payment_type,
						'payment_date'		=> $payment_date,
						'scppp_status'		=> $scppp_status
					),
					array('%d','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%d','%f','%f','%f','%f','%f','%s','%s','%s','%s')
				);
	$inserted_id=$wpdb->insert_id;
	
}

?>