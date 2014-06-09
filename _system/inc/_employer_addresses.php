<?php

	// addresses homepage
	include_once('_system/classes/address_book.php') ;
	include_once('_system/_config/_address_required_fields_array.php') ;

	$update_fail = false ;
	// prcess update
	if($_GET['subaction']=='saveaddress'&&$_GET['addressid']!=''&&is_numeric($_GET['addressid'])){
		foreach($address_required_fields as $address_required_field){
			if($_POST[$address_required_field]==''){
				if($update_fail!=false){
					$update_fail.= ', ' ;
				} else {
					$update_fail = '' ;
				}
				$update_fail.= ucwords(str_replace("_"," ",str_replace("contact_","",$address_required_field))) ;
			}
		}
		if($update_fail!=false){
			$update_fail = $update_fail ;
		} else {
			$auf = new address_book ;
			$auf->setUserId($_SESSION['user_id']) ;
			$auf->setId($_GET['addressid']) ;
			$type = 'billing' ;
			if($_POST['type']=='profile'){
				$type = 'profile' ;
			}
			$auf->setType($type) ;
			$auf->saveAddress($_POST,false,false,true) ;
			echo '<p class="success" >'.draw_icon(ICON_GOOD).str_replace("_"," ",ucfirst($type)).' address updated successfully ('.$_POST['contact_first_name'].' '.$_POST['contact_surname'].' @ '.$_POST['contact_address_1'].')</p>' ;
		}
	}

	$add_fail = false ;
	if($_GET['subaction']=='savenewaddress'){
		foreach($address_required_fields as $address_required_field){
			if($_POST[$address_required_field]==''){
				if($add_fail!=false){
					$add_fail.= ', ' ;
				} else {
					$add_fail = '' ;
				}
				$add_fail.= ucwords(str_replace("_"," ",str_replace("contact_","",$address_required_field))) ;
			}
		}
		if($add_fail!=true){
			$auf = new address_book ;
			$auf->setUserId($_SESSION['user_id']) ;
			if($_POST['type']=='profile'){
				$type = 'profile' ;
			} elseif($_POST['is_default_billing']=='yes'){
				$type = 'default_billing' ;
			} else {
				$type = 'billing' ;
			}
			$auf->setType($type) ;
			$auf->saveAddress($_POST,true,false,true) ;
			echo '<p class="success" >'.draw_icon(ICON_GOOD).str_replace("_"," ",ucfirst($type)).' address updated successfully ('.$_POST['contact_first_name'].' '.$_POST['contact_surname'].' @ '.$_POST['contact_address_1'].')</p>' ;
		}
	}


	if($_GET['subaction']=='movebilling'&&$_GET['dir']!=''&&$_GET['oldpos']!=''&&is_numeric($_GET['oldpos'])&&$_GET['addressid']!=''&&is_numeric($_GET['addressid'])){
		if($_GET['dir']=='up'){
			$dir = 'up' ;
		} else {
			$dir = 'down' ;
		}
		$auf = new address_book ;
		$auf->setUserId($_SESSION['user_id']) ;
		$auf->moveAddress($_GET['addressid'],$_GET['oldpos'],$dir,'billing') ;
	}

	if($_GET['subaction']=='delete'&&$_GET['addressid']!=''&&is_numeric($_GET['addressid'])){
		$auf = new address_book ;
		$auf->setUserId($_SESSION['user_id']) ;
		$auf->deleteAddress($_GET['addressid']) ;
	}




?>
<h2>Address Book</h2>
<?php

	if(($_GET['subaction']=='modify'||($_GET['subaction']=='saveaddress'&&$update_fail==true))&&$_GET['addressid']!=''&&is_numeric($_GET['addressid'])){
		// Modify based on ID address
		if($update_fail==true){
			echo '<p class="error" >'.draw_icon(ICON_BAD).'<b>The following fields are required</b>: '.$update_fail.'</p>' ;
		}
		include('_system/inc/_employer_address_admin.php') ;

	} elseif($_GET['subaction']=='addbilling'||$add_fail==true){
		// Add a billing address
		if($add_fail==true){
			echo '<p class="error" >'.draw_icon(ICON_BAD).'<b>The following fields are required</b>: '.$add_fail.'</p>' ;
		}
		include('_system/inc/_employer_address_admin.php') ; 
	} else {
		// list addresses
		include('_system/functions/draw_address_list.php') ; 
		$abf = new address_book ;
		$abf->setUserId($_SESSION['user_id']) ;
		
		?>
        <p class="greynote" ><?php echo draw_icon('bulb.png') ; ?>You can also edit the profile contacts and default billing address via your <a href="?action=profile" >profile administration</a> screen or set an alternate billing address as the default from here.</p>
        <?php
			// PROFILE = = = = = = = = = = = = = = =
			$abf->setType('defaults') ;
		?>
		<div style="margin-top:-10px;" ><?php draw_list($abf,'editonly',true) ; ?></div>
        <p><a href="?action=profile" ><?php echo draw_icon('account_sm.png') ; ?>Click here to edit your profile</a></p>
        <?php
			// BILLING = = = = = = = = = = = = = = =
			$abf->setType('general_billing') ;
			$addresses_list_count = $abf->getAddressesList(true) ;
		?>
		<h3 id="billinglist">Alternate Billing Addresses (<?php echo $addresses_list_count ; ?>)</h3>
        <?php
			if($addresses_list_count==0){
				echo '<p class="notice" >'.draw_icon(ICON_ALERT).'You have not yet added an alternate billing address</p>' ;
			}
		?>
		<p><?php echo draw_icon('add.png') ; ?><a href="?action=<?php echo $_GET['action'] ; ?>&amp;subaction=addbilling" >Add an alternate billing address</a></p>
		<?php draw_list($abf,true,false) ; ?>
        <p><?php echo draw_icon('bulb.png') ; ?>To set a new default address, select "edit" on the date you wish to promote to the default and ensure "Make default billing address" is checked before saving.</p>
		<?php

	}
?>