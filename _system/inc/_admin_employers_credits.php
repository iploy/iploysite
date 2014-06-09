<?php

	// = = = = = = = = = = = = = = = = = = = = = = = = =  = = = = = = = = = = = = = = = = = = = = = = = = = 
	// on screen messages
	if($_GET['msg']!=''){
		// blank them first
		$screen_message = '' ;
		$screen_message_type = '' ;
		// check the error message
		if($_GET['msg']=='incomplete'){
			$screen_message = draw_icon(ICON_BAD).'Your transaction could not be completed.' ;
			$screen_message_type = 'error' ;
			// canceled
			if($_GET['error']=='cancelled'){
				$screen_message = draw_icon(ICON_BAD).'The transaction was cancelled as per your request. Please select a package if you would like to try again.' ;
				$screen_message_type = 'error' ;
			}
			// declined
			if($_GET['error']=='declined'){
				$screen_message = draw_icon(ICON_BAD).'Your card was declined. Please try again or use a different card to continue.' ;
				$screen_message_type = 'error' ;
			}
			// rejected
			if($_GET['error']=='rejected'){
				$screen_message = draw_icon(ICON_BAD).'Your card was rejected. Please try again or use a different card to continue.' ;
				$screen_message_type = 'error' ;
			}
			// unknown
			if($_GET['error']=='unknown'){
				$screen_message = draw_icon(ICON_BAD).'An unspecified error occured. Please try again or use a different card to continue.' ;
				$screen_message_type = 'error' ;
			}
		}
		if($_GET['msg']=='cardnotallowed'){
			$screen_message = draw_icon(ICON_BAD).'The card type that you have entered is not accepted by our system.  Please try another card to continue.' ;
			$screen_message_type = 'error' ;
		}
		if($_GET['msg']=='error'){
			$screen_message = draw_icon(ICON_BAD).'An unspecified error occured. Please contact us for further assistance' ;
			$screen_message_type = 'error' ;
			if($_GET['error']!=''){
				$screen_message.= ' quoting error code '.$_GET['error'] ;
			}
		}
		include('_system/inc/_screen_message_handler.php') ; 
	}
	// = = = = = = = = = = = = = = = = = = = = = = = = =  = = = = = = = = = = = = = = = = = = = = = = = = = 


	include_once('_system/classes/address_book.php') ;
	include('_system/functions/draw_address_list.php') ; 
	include_once('_system/classes/products.php') ;
	$pf = new products ;	

	if(is_numeric($_GET['product_id'])&&$_GET['product_id']>=0){
		// check if product exists
		$pf->setProductId($_GET['product_id']) ;
		$product = $pf->getProduct() ;
	}

	//============================================================================================================================
	if($_GET['subaction']=='buy'&&$product!=false||$_GET['subaction']=='pay'&&$product!=false&&$_POST['tandc']!='yes'){
		include_once('_system/inc/_admin_employers_credits_buy.php') ;
	//============================================================================================================================
	} elseif($_GET['subaction']=='pay'&&$product!=false){
		include_once('_system/inc/_admin_employers_credits_pay.php') ;
	//============================================================================================================================
	} else {
		?>
        <h3>Credits</h3>
        <p><b>Current Credit Balance</b>: <?php echo $credits_total.' graduate credit'.($credits_total==1 ? '' : 's').'.' ; ?></p>
        <p>Select a credit pack from the options below to buy credits online.</p>
        <ul class="product_buttons" >
        <?php
			
			$pf->setProductCategoryId('1') ;
			$products = $pf->getProductList() ;
			foreach($products as $product){
				if($product['number_of_credits']!=1){
					$credits_label = 'credits' ;
				} else {
					$credits_label = 'credit' ;
				}
				if(!$basePrice){
					$basePrice = $product['price'] ;
				}
				// Variables
				$numberOfCredits = intval($product['name']) ;
				$priceDifference = $basePrice-($product['price']/$numberOfCredits);
				if($priceDifference>0){
					$priceDifference = 'Total Saving &pound;'.number_format(($priceDifference*$numberOfCredits),2) ;
				} else {
					$priceDifference = '' ;
				}
				?>
				<li class="button_<?php echo $numberOfCredits ; ?>" ><a href="?action=credits&amp;subaction=buy&amp;product_id=<?php echo $product['id'] ; ?>" >
                <span class="number" ><?php echo $product['number_of_credits'].' '.$credits_label ; ?></span>
                <span class="img" ><img src="images/graphics/credits/new.png" width="180" height="100" border="0" alt="" /></span>
                <b>&pound;<?php echo number_format($product['price'],2) ; ?></b><br />
                &pound;<?php echo number_format($product['price']/$numberOfCredits,2) ; ?> Per Credit<br />
                <?php // echo $priceDifference ; ?>
                </a></li>
				<?php
			}
		?>
        </ul>
        <style type="text/css" >
		.cards {
			margin:16px 0 22px;
		}
		.cards img {
			margin:0 8px;
		}
		</style>
        <div class="cards" align="center" >
        	<img src="images/graphics/cards/sagepay_sm.png" width="135" height="60" border="0" alt="" /><img src="images/graphics/cards/visa_sm.png" width="96" height="60" border="0" alt="" /><img src="images/graphics/cards/mastercard_sm.png" width="96" height="60" border="0" alt="" /><img src="images/graphics/cards/switch_sm.png" width="96" height="60" border="0" alt="" /><img src="images/graphics/cards/delta_sm.png" width="96" height="60" border="0" alt="" />
        </div>
        <h3>Alternate Options</h3>
        <div class="lister" >
        <ul>
        	<li><?php echo draw_icon('time.png') ; ?><a href="?action=purchasehistory" >Click here to view your transaction history</a></li>
        </ul>
        </div>
        <?php
		// ADMIN ONLY PART
		if(strstr($_SESSION['email'],'@devmac.co.uk')||strstr($_SESSION['email'],'@iploy.co.uk')){
			?>
			<h3>Quick Credits (<?php echo $_SESSION['APP_CLIENT_NAME'] ; ?> Admin Only)</h3>
			<ul>
				<li><a href="?action=<?php echo $_GET['action'] ; ?>&amp;subaction=add1" >Quick Add 1 Credit</a></li>
				<li><a href="?action=<?php echo $_GET['action'] ; ?>&amp;subaction=add5" >Quick Add 5 Credits</a></li>
				<li><a href="?action=<?php echo $_GET['action'] ; ?>&amp;subaction=spend1" >Quick Spend 1 Credit</a></li>
				<li><a href="?action=<?php echo $_GET['action'] ; ?>&amp;subaction=spend5" >Quick Spend 5 Credits</a></li>
			</ul>
			<?php
		}
	}
	//============================================================================================================================

?>