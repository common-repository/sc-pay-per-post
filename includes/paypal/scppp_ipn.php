<?php

function scppp_paypal_ipn(){
	global $table_prefix, $wpdb;
	
	$sql="SELECT paypal_sandbox FROM ".$table_prefix."scppp_paypal";
	$result=$wpdb->get_row($sql);
	$sandbox=$result->paypal_sandbox;
	
	$scppp_paypal='https://www.paypal.com/cgi-bin/webscr';
	$scppp_paypal_sandbox='https://www.sandbox.paypal.com/cgi-bin/webscr';
	
	if($sandbox==1){
		$url=$scppp_paypal_sandbox;
	}
	else{
		$url=$scppp_paypal;
	}
	
	$postdata = '';
	foreach($_POST as $i => $v) {
		$postdata .= $i.'='.urlencode($v).'&';
	}
	$postdata .= 'cmd=_notify-validate';
	
	$web = parse_url($url);
	if ($web['scheme'] == 'https') { 
		$web['port'] = 443;  
		$ssl = 'ssl://'; 
	} else { 
		$web['port'] = 80;
		$ssl = ''; 
	}
	$fp = @fsockopen($ssl.$web['host'], $web['port'], $errnum, $errstr, 30);
	
	if (!$fp) { 
		echo $errnum.': '.$errstr;
	} else {
		fputs($fp, "POST ".$web['path']." HTTP/1.1\r\n");
		fputs($fp, "Host: ".$web['host']."\r\n");
		fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n");
		fputs($fp, "Content-length: ".strlen($postdata)."\r\n");
		fputs($fp, "Connection: close\r\n\r\n");
		fputs($fp, $postdata . "\r\n\r\n");
	
		while(!feof($fp)) { 
			$info[] = @fgets($fp, 1024); 
		}
		fclose($fp);
		$info = implode(',', $info);
		if (eregi('VERIFIED', $info)) { 
			$scppp_status='accepted';
			$txn_id=$_POST['txn_id'];
			$payment_status=$_POST['payment_status'];
			
			$wpdb->update( ''.$table_prefix.'scppp_purchase_log',
					array( 'payment_status' => $payment_status, 'scppp_status' => $scppp_status),
					array( 'transaction_id' => $txn_id ),
					array('%s','%s'),
					array('%s')
				);
			 
		} else {
			// invalid, log error or something	
		}
	}

}

?>