<?php

	$tandc_class = '' ;
	if($_GET['subaction']=='pay'&&$product!=false&&$_POST['tandc']!='yes'){
		$screen_message = draw_icon(ICON_BAD).'You must accept the iPloy Employer Terms and Conditions in order to continue.' ;
		$screen_message_type = 'error' ;
		$tandc_class = 'red bold' ;
	}

	?>
	<h3>Purchase Credit Pack</h3>
    <?php include('_system/inc/_screen_message_handler.php') ; ?>
	<p><?php echo draw_icon(ICON_ALERT) ; ?><a href="?action=<?php echo $_GET['action'] ; ?>" >Click here to select a different package.</a></p>
	<ul>
		<li><b>Selected Package</b>: <b class="Highlight" ><?php echo $product['name'] ; ?></b></li>
		<li><b>Price</b>: <b class="Highlight" >&pound;<?php echo number_format($product['price'],2) ; ?></b></li>
	</ul>
	<p>To continue with this purchase, please confirm your billing address or select an alternate address:</p>
	<?php
		// get billing address at this point, and confirm the user owns it
	// list addresses
	$abf = new address_book ;
	$abf->setUserId($_SESSION['user_id']) ;
	if($_GET['addressid']!=''&&is_numeric($_GET['addressid'])&&$_GET['addressid']>0){
		$abf->setId($_GET['addressid']) ;
		$is_alt_address = true ;
		$alt_list_type = 'billing' ;
	} else {
		$abf->setType('default_billing') ;
		$is_alt_address = false ;
		$alt_list_type = 'general_billing' ;
	}
	if($abf->getAddressesList(true)>0){
		?>
		<div><?php draw_list($abf,false,true) ; ?></div>
        <script language="javascript" type="text/javascript" >
		function validateTandC(){
			var errormsg = '' ;
			if(document.getElementById('tandc').checked!=true){
				errormsg = 'TERMS AND CONDITIONS ERROR\nYou must accept the iPloy Employer Terms and Conditions in order to continue.' ;
			}
			if(errormsg!=''){
				alert(errormsg) ;
				return false ;
			} else {
				return true ;
			}
		}
		</script>
		<script language="javascript" type="text/javascript" >
        window.addEvent('domready', function(){
			function popWindow(){
			}
        	$$('a[rel="mooPop"]').each(function(mooPop){
				mooPop.setProperty('rev',mooPop.getProperty('href')) ;
				mooPop.setProperty('target','') ;
				mooPop.setProperty('href','#buyform') ;
				mooPop.addEvent('click',function(){
					w = window.open(mooPop.getProperty('rev'), "mywindow","location=0,status=0,scrollbars=1, width=700,height=500");
					w.focus() ;
				});
			});
        });
        </script>
        <form action="?action=<?php echo $_GET['action'] ; ?>&amp;subaction=pay&amp;product_id=<?php echo $_GET['product_id'] ; ?>&amp;addressid=<?php echo $_GET['addressid'] ; ?>" method="post" id="buyform" style="margin:0;" onsubmit="return validateTandC();" >
        <p class="<?php echo $tandc_class ; ?>" ><input type="checkbox" id="tandc" name="tandc" value="yes" /> I agree to the iPloy <a href="employers_terms_and_conditions.php" target="_blank" rel="mooPop" >Employer Terms and Conditions</a></p>
		<ul class="li_buttons" style="margin:6px 6px 0;" >
			<li><input type="submit" value="Continue using this address" /></li>
			<li><a href="?action=addresses" >Edit My Address Book</a></li>
			<li><a href="?action=<?php echo $_GET['action'] ; ?>" >Select a Different Package</a></li>
		</ul>
        </form>
		<?php
	} else {
		?>
		<p class="error" ><?php echo draw_icon(ICON_BAD) ; ?>That address does not exist in your address book. Please do not manually modify the HTTP address in the browser.</p>
		<?php
	}
	?>
	<?php
	$abf->setType($alt_list_type) ;
	$abf->setId('0') ;
	if($is_alt_address==true){
		$abf->setNotId($_GET['addressid']) ;
	}
	$addresses_list = $abf->getAddressesList() ;
	if(sizeof($addresses_list)>0){
		?>
		<h5>Other billing address</h5>
		<div class="lister" ><ul>
		<?php
		foreach($addresses_list as $address){
			$this_label = '' ;
			if($address['contact_first_name']!=''){
				$this_label.= ''.$address['contact_first_name'] ;
			}
			if($address['contact_surname']!=''){
				$this_label.= ' '.$address['contact_surname'] ;
			}
			if($address['contact_address_1']!=''){
				$this_label.= ', '.$address['contact_address_1'] ;
			}
			if($address['contact_address_2']!=''){
				$this_label.= ', '.$address['contact_address_2'] ;
			}
			if($address['contact_town']!=''){
				$this_label.= ', '.$address['contact_town'] ;
			}
			if($address['contact_state']!=''){
				$this_label.= ', '.$address['contact_state'] ;
			}
			if($address['contact_postcode']!=''){
				$this_label.= ', '.$address['contact_postcode'] ;
			}
			if($address['contact_country']!=''){
				$this_label.= ', '.$address['contact_country'] ;
			}
			// link
			$this_link = '?action='.$_GET['action'].'&amp;subaction='.$_GET['subaction'].'&product_id='.$_GET['product_id'] ;
			// Icon
			if($address['is_default_billing']=='yes'){
				$icon_src = 'cash.png' ;
				$icon_title = 'Default Billing Address' ;
			} else {
				$icon_src = 'cash_add.png' ;
				$icon_title = 'Alternate Billing Address' ;
				$this_link.= '&amp;addressid='.$address['id'] ;
			}
			echo '<li><span title="'.$icon_title.'" >'.draw_icon($icon_src).'</span><a href="'.$this_link.'" >'.$this_label.'</a></li>' ;
		}
		?>
		</ul></div>
		<?
	}

?>