<h2>Transaction History</h2>
<?php
	// check the message type
	if($_GET['msg']!=''){
		if($_GET['msg']=='success'){
			$screen_message = draw_icon(ICON_GOOD).'Thank you, you transaction was completed successfully.' ;
			$screen_message_type = 'success' ;
		}
		include('_system/inc/_screen_message_handler.php') ; 
	}


	$screen_message = draw_icon(ICON_ALERT).'<b>Security Information</b>: iPloy only stores your profile and contact information. We <b>do not</b> save any information relating to your credit card in our databases.' ;
	$screen_message_type = 'notice' ;
	include('_system/inc/_screen_message_handler.php') ; 



	$pageOffset = 0 ;
	$pageSize = 50 ;
	$orderBy = 'date' ;
	$orderDir = 'DESC' ;
	$qs_keeper = '' ;
	// page offset
	if($_GET['order_by']=='date'||$_GET['order_by']=='prodname'){
		$orderBy = $_GET['order_by'] ;
	}
	if($_GET['order_dir']=='asc'||$_GET['order_dir']=='desc'){
		$orderDir = strtoupper($_GET['order_dir']) ;
	}
	if($_GET['page_offset']!=''&&is_numeric($_GET['page_offset'])&&$_GET['page_offset']>0){
		$pageOffset = $_GET['page_offset'] ;
	}
	if($_GET['page_size']!=''&&is_numeric($_GET['page_size'])&&$_GET['page_size']>0){
		$pageSize = $_GET['page_size'] ;
	}

	include('_system/classes/transactions.php') ; 

	$th = new transactions ;
	$transactions = $th->getTransactionHistory($_SESSION['user_id'],false,$pageOffset,$pageSize,$orderBy,$orderDir) ;
	$transactions_total = $th->getTransactionHistory($_SESSION['user_id'],true) ;

	if($transactions===false){
		echo '<p class="error" >'.draw_icon(ICON_BAD).'A fatal error occured while fetching your transaction history</p>' ;

	} elseif($transactions_total>0){
		$start_number = ($pageSize*$pageOffset)+1 ;
		$end_number = $pageSize*($pageOffset+1) ;
		if($end_number>$transactions_total){
			$end_number = $transactions_total ;
		}
		// dont show this message if the user has manually edited a larger page then possible into the querystring
		if(sizeof($transactions)>0){
			if($start_number!=$end_number){
				echo '<p>Shwoing records '.$start_number.' to '.$end_number.' of '.$transactions_total.' records</p>' ;
			} else {
				echo '<p>Shwoing record '.$start_number.' of '.$transactions_total.' records</p>' ;
			}
		}
		include('_system/functions/number_length.php') ; 
		$link_keeper = '?action='.$_GET['action'].'&page_offset='.$_GET['page_offset'].'&page_size='.$_GET['page_size'] ;
		echo '<table cellpadding="0" cellspacing="0" class="list" width="920" >'."\n" ;
		echo '<tr>'."\n" ;
		// header row
		echo '<tr class="headrow" >'."\n" ;
		if($orderBy=='prodname'&&$orderDir=='ASC'){
			$linkdir = 'desc' ;
			$imgtype = 'desc' ;
		} else {
			$linkdir = 'asc' ;
			$imgtype = 'asc' ;
		}
		echo '<td><a href="'.$link_keeper.'&order_by=prodname&order_dir='.$linkdir.'" >Product Name' ;
		if($orderBy=='prodname'){
			echo draw_icon('order_'.strtolower($imgtype).'.png') ;
		}
		echo '</a></td>'."\n" ;
		echo '<td>Price</td>'."\n" ;
		if($orderBy=='date'&&$orderDir=='DESC'){
			$linkdir = 'asc' ;
			$imgtype = 'asc' ;
		} else {
			$linkdir = 'desc' ;
			$imgtype = 'desc' ;
		}
		echo '<td><a href="'.$link_keeper.'&order_by=date&order_dir='.$linkdir.'" >Transaction Date' ;
		if($orderBy=='date'){
			echo draw_icon('order_'.strtolower($imgtype).'.png') ;
		}
		echo '</a></td>'."\n" ;
		echo '<td>Transaction ID</td>'."\n" ;
		echo '<td width="16" ></td>'."\n" ;
		echo '</tr>'."\n" ;
		// loop  
		foreach($transactions as $transaction){
			if($rowclass==''){
				$rowclass = ' class="offrow" ' ;
			} else {
				$rowclass='' ;
			}
			// price logic
			$this_price = 'Free' ;
			if($transaction['product_price']>0){
				$this_price = '&pound;'.$transaction['product_price'] ;
			}
			if($transaction['product_price_in_credits']>0){
				$this_price = $transaction['product_price_in_credits'].' Credits' ;
			}
			// TX code (transaction ID)
			$vendortxcode = 'iploy-free-'.number_length($transaction['th_id'],15) ;
			if($transaction['vendortxcode']!=''){
				$vendortxcode = $transaction['vendortxcode'] ;
			}
			// billing address
			$billing_address = '' ;
			if($transaction['contact_first_name']!=''||$transaction['contact_surname']!=''){
				$billing_address.= '<b>'.$transaction['contact_first_name'].' '.$transaction['contact_surname'].'</b><br />'."\n" ;
			}
			if($transaction['contact_address_1']!=''){
				$billing_address.= $transaction['contact_address_1'].'<br />'."\n" ;
			}
			if($transaction['contact_address_2']!=''){
				$billing_address.= $transaction['contact_address_2'].'<br />'."\n" ;
			}
			if($transaction['contact_town']!=''){
				$billing_address.= $transaction['contact_town'].'<br />'."\n" ;
			}
			if($transaction['contact_state']!=''){
				$billing_address.= $transaction['contact_state'].'<br />'."\n" ;
			}
			if($transaction['contact_postcode']!=''){
				$billing_address.= $transaction['contact_postcode'].'<br />'."\n" ;
			}
			if($transaction['contact_country']!=''){
				$billing_address.= $transaction['contact_country'].'<br />'."\n" ;
			}
			// icon
			if($transaction['is_default_billing']=='yes'){
				$billing_icon = 'cash.png' ;
			} else {
				$billing_icon = 'cash_add.png' ;
			}
			//draw to screen
			echo '<tr'.$rowclass.'>'."\n" ;
			if($transaction['graduate_id']>0){
				$prodname = 'Graduate Profile: <a href="view_profile.php?profileid='.$transaction['graduate_id'].'" >'.ucwords(substr($transaction['product_name'],18)).'</a>' ;
			} else {
				$prodname = $transaction['product_name'] ;
			}
			echo '<td>'.$prodname.'</td>'."\n" ;
			echo '<td>'.$this_price.'</td>'."\n" ;
			echo '<td>'.date(DM_PHP_SCREENSHORTDATETIME_FORMAT,strtotime($transaction['transaction_date'])).'</td>'."\n" ;
			echo '<td>'.$vendortxcode.'</td>'."\n" ;
			echo '<td>' ;
			if($billing_address!=''){
				echo '<span class="nopad hoverspan" ><span>'.$billing_address.'</span>'.draw_icon($billing_icon).'</span>' ;
			}
			echo '</td>'."\n" ;
			echo '</tr>'."\n" ;
		}
		echo '</tr>'."\n" ;
		echo '</table>'."\n" ;

		if($transactions_total>sizeof($transactions)){
			include_once('_system/classes/paging.php') ;
			$pf = new paging ;
			$pf->setPageSize($pageSize) ;
			$pf->setListTotal($transactions_total) ;
			$pf->setLinkPrefix('?action='.$_GET['action'].'&page_size='.$pageSize) ;
			$paging = 'View Records : '.$pf->generatePaging() ;
		} else {
			$paging = '&nbsp;' ;
		}

		echo '<p>' ;
		// show records
		echo '<span style="float:right;" >Per Page : ' ;
		$pagesizes = explode(",","50,100,150") ;
		foreach($pagesizes as $pagesize){
			if($pageSize==$pagesize){
				$prefix = '<b>' ;
				$postfix = '</b>' ;
			} else {
				$prefix = '' ;
				$postfix = '' ;
			}
			echo $prefix.'<a href="?action='.$_GET['action'].'&page_offset='.$_GET['page_offset'].'&page_size='.$pagesize.'" >'.$pagesize.'</a>'.$postfix ;
			if($pagesizes[sizeof($pagesizes)-1]!=$pagesize){
				echo ' &bull; ' ;
			}
		}
		echo '</span>' ;
		// paging
		echo $paging ;
		echo '</p>'."\n" ;
	} else {
		echo '<p class="notice" >'.draw_icon(ICON_ALERT).'You have not yet performed any transactions</p>' ;
	}

?>