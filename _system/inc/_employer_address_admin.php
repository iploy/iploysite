<script language="javascript" type="text/javascript" src="js/check_me.js" ></script>
<?php

	include('_system/_config/_address_required_fields_array.php') ;
	include_once('_system/functions/check_my_array.php') ;
	include_once('_system/functions/country_drowdown.php') ;

	$is_new_billing = false ;
	if($_GET['subaction']=='modify'){
		// list addresses
		$abf = new address_book ;
		$abf->setUserId($_SESSION['user_id']) ;
		$abf->setId($_GET['addressid']) ;
		$valuesArray = $abf->getAddress() ;
	} elseif($_GET['subaction']=='saveaddress'){
		// must be a failed save
		$valuesArray = $_POST ;
	}  elseif($_GET['subaction']=='savenewaddress'){
		// must be a failed add
		$is_new_billing = true ;
		$valuesArray = $_POST ;
	} else {
		// must be an add
		$is_new_billing = true ;
		$valuesArray = array() ;
	}


	if($_GET['subaction']=='modify'||$_GET['subaction']=='saveaddress'){
		$form_action = '?action='.$_GET['action'].'&amp;subaction=saveaddress&amp;addressid='.$_GET['addressid'] ;
		$submit_label = 'Save Changes' ;
	} else {
		// must be an add
		$form_action = '?action='.$_GET['action'].'&amp;subaction=savenewaddress' ;
		$submit_label = 'Add Address' ;
	}

?>

<form action="<?php echo $form_action ; ?>" onsubmit="isSumbition();" name="addressform" id="addressform" method="post" enctype="multipart/form-data" >

	<input type="hidden" name="type" id="type" value="<?php echo htmlspecialchars($valuesArray['type']) ;?>" />

    <div class="formfloater" >

        <label for="contact_first_name" >Contact Name <?php echo check_my_array('contact_first_name',$employer_required_fields,'*') ; ?></label>
        <input onchange="isModified();" type="text" name="contact_first_name" id="contact_first_name" class="text" value="<?php echo htmlspecialchars($valuesArray['contact_first_name']) ;?>" style="width:120px;" />&nbsp;<input onchange="isModified();" type="text" name="contact_surname" id="contact_surname" class="text" value="<?php echo htmlspecialchars($valuesArray['contact_surname']) ;?>" style="width:170px;" />
    
        <label for="contact_position" >Contact Position <?php echo check_my_array('contact_position',$employer_required_fields,'*') ; ?></label>
        <input name="contact_position" id="contact_position" type="text" class="text" value="<?php echo htmlspecialchars($valuesArray['contact_position']) ;?>" />
    
        <label for="contact_email" >Contact Email <?php echo check_my_array('contact_email',$employer_required_fields,'*') ; ?></label>
        <input name="contact_email" id="contact_email" type="text" class="text" value="<?php echo htmlspecialchars($valuesArray['contact_email']) ;?>" />
    
        <label for="contact_tel" >Contact Tel <?php echo check_my_array('contact_tel',$address_required_fields,'*') ; ?></label>
        <input name="contact_tel" id="contact_tel" type="text" class="text" value="<?php echo htmlspecialchars($valuesArray['contact_tel']) ;?>" />

    </div>


    <div class="formfloater" >
    
        <label for="contact_address_1" >Contact Address Line 1 <?php echo check_my_array('contact_address_1',$employer_required_fields,'*') ; ?></label>
        <input name="contact_address_1" id="contact_address_1" type="text" class="text" value="<?php echo htmlspecialchars($valuesArray['contact_address_1']) ;?>" />
    
        <label for="contact_address_2" >Contact Address Line 2 <?php echo check_my_array('contact_address_2',$employer_required_fields,'*') ; ?></label>
        <input name="contact_address_2" id="contact_address_2" type="text" class="text" value="<?php echo htmlspecialchars($valuesArray['contact_address_2']) ;?>" />
    
        <label for="contact_town" >Town <?php echo check_my_array('contact_town',$employer_required_fields,'*') ; ?></label>
        <input name="contact_town" id="contact_town" type="text" class="text" value="<?php echo htmlspecialchars($valuesArray['contact_town']) ;?>" />
    
        <label for="contact_state" >County <?php echo check_my_array('contact_state',$employer_required_fields,'*') ; ?></label>
        <input name="contact_state" id="contact_state" type="text" class="text" value="<?php echo htmlspecialchars($valuesArray['contact_state']) ;?>" />
    
        <label for="contact_postcode" >Postcode <?php echo check_my_array('contact_postcode',$employer_required_fields,'*') ; ?></label>
        <input name="contact_postcode" id="contact_postcode" type="text" class="text" value="<?php echo htmlspecialchars($valuesArray['contact_postcode']) ;?>" />
    
        <label for="contact_country" >Country <?php echo check_my_array('contact_country',$employer_required_fields,'*') ; ?></label>
        <?php echo country_dropdown('contact_country','contact_country',$valuesArray['contact_country']) ; ?>

	</div>


    <div style="clear:both;">
    <?php
		if(($valuesArray['type']=='billing'&&$valuesArray['is_default_billing']!='yes')||$is_new_billing==true){
			?>
			<div class="lister" ><ul>
            	<li><input type="checkbox" name="is_default_billing" id="is_default_billing" value="yes" /><span onclick="check_me('is_default_billing','multi');" >Make default billing address</span></li>
            </ul></div>
			<?php
		} elseif($valuesArray['type']=='billing'){
			?>
            <input type="hidden" name="is_default_billing" id="is_default_billing" value="<?php echo htmlspecialchars($valuesArray['is_default_billing']) ;?>" />
            <?php
		}
	?>
    <input type="submit" name="gradsubmit" id="gradsubmit" value="<?php echo $submit_label ; ?>" />&nbsp;&nbsp;&nbsp;&nbsp; [ <a href="?action=<?php echo $_GET['action'] ; ?>" >Cancel Request</a> ]
    </div>

</form>