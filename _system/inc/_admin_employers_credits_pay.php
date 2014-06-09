<?php

	$abf = new address_book ;
	$abf->setUserId($_SESSION['user_id']) ;
	if($_GET['addressid']!=''&&is_numeric($_GET['addressid'])&&$_GET['addressid']>0){
		$abf->setId($_GET['addressid']) ;
	} else {
		$abf->setType('default_billing') ;
	}
	$address=$abf->getAddress() ;
	if($address){
		if($address['contact_first_name']!=''&&$address['contact_surname']!=''&&$address['contact_email']!=''&&$address['contact_tel']!=''&&$address['contact_address_1']!=''&&$address['contact_town']!=''&&$address['contact_state']!=''&&$address['contact_postcode']!=''&&$address['contact_country']!=''){
			?>
			<h3>Payment Details</h3>
			<p><?php echo draw_icon(ICON_ALERT) ; ?><a href="?action=<?php echo $_GET['action'] ; ?>" >Click here to cancel this transaction and select a different package.</a></p>
			<ul>
                <li><b>Selected Package</b>: <b class="Highlight" ><?php echo $product['name'] ; ?></b></li>
                <li><b>Price</b>: <b class="Highlight" >&pound;<?php echo number_format($product['price'],2) ; ?></b></li>
				<li><b>Billing Contact</b>: <?php echo $address['contact_first_name'].' '.$address['contact_surname'].' ('.$address['contact_email'].' / '.$address['contact_tel'].')' ; ?> [ <a href="?action=<?php echo $_GET['action'] ; ?>&subaction=buy&product_id=<?php echo $_GET['product_id'] ; ?>&addressid=<?php echo $_GET['addressid'] ; ?>" >Change</a> ]</li>
				<?php
					$ba = $address['contact_address_1'] ;
					if($address['contact_address_2']!=''){
						$ba.= ', '.$address['contact_address_2'] ;
					}
					$ba.= ', '.$address['contact_town'] ;
					$ba.= ', '.$address['contact_state'] ;
					$ba.= ', '.$address['contact_postcode'] ;
					$ba.= ', '.$address['contact_country'] ;
				?>
				<li><b>Billing Address</b>: <?php echo $ba ; ?></li>
			</ul>
			<?php
			include('transaction_process.php') ;
		} else {
			?>
			<p class="error" ><?php echo draw_icon(ICON_BAD) ; ?>The selected address is incomplete and can not be used to make payments.</p>
			<?php
		}
	} else {
		?>
		<p class="error" ><?php echo draw_icon(ICON_BAD) ; ?>That address does not exist in your address book. Please do not manually modify the HTTP address in the browser.</p>
		<?php
	}
	?>
	<?php


	

?>