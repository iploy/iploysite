<script language="javascript" type="text/javascript" >
function confirmform(current){
	if(document.getElementById('username').value!=current&&current!=''){
		if(confirm('WARNING:\nModifying your username will cause your existing affiliate links to no longer function\nwhich may result in lost referrals from graduates using your old address.\n\nAre you sure you wish to update your username?')){
			return true ;
		} else {
			return false ;
		}
	}
}
</script>
<form action="?action=affiliates&amp;subaction=<?php echo ($formAction=='edit' ? 'edit' : 'signup') ; ?>" method="post" onsubmit="return confirmform('<?php echo $affiliateInfo['username'] ; ?>');" >

	<?php echo ($formAction=='edit' ? '<p class="notice" >'.draw_icon(ICON_ALERT).'<b>WARNING</b>:<br />
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Modifying your username will cause any links you have distributed to stop working.<br />
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- This may cause may result in lost referrals from graduates using your old address.<br />
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- If you change your username, you do so at your own risk. iPloy will not be held responsible for lost referrals.
	</p>' : '') ; ?>

    <label for="username" >Unique User ID *</label>
    <input class="text" name="username" id="username" type="text" maxlength="50" value="<?php echo $affiliateInfo['username'] ; ?>" />
    <p><b>Note</b>: Letters and numbers, no spaces or symbols.</p>

    <h3>Payment Information</h3>
    <p>Please enter your name and address so we know where to send your rewards!</p>
    <div class="float_container" >
        <div style="float:left; margin-right:16px;" >
            <label for="payment_name" >Recipient Name *</label>
            <input class="text" name="payment_name" id="payment_name" type="text" maxlength="120" value="<?php echo $affiliateInfo['payment_name'] ; ?>" />
            <label for="payment_address_1" >Address Line 1 *</label>
            <input class="text" name="payment_address_1" id="payment_address_1" type="text" maxlength="120" value="<?php echo $affiliateInfo['payment_address_1'] ; ?>" />
            <label for="payment_address_2" >Address Line 2</label>
            <input class="text" name="payment_address_2" id="payment_address_2" type="text" maxlength="120" value="<?php echo $affiliateInfo['payment_address_2'] ; ?>" />
        </div>
        <div style="float:left;" >
            <label for="payment_address_town" >Town *</label>
            <input class="text" name="payment_address_town" id="payment_address_town" type="text" maxlength="120" value="<?php echo $affiliateInfo['payment_address_town'] ; ?>" />
            <label for="payment_address_county" >County *</label>
            <input class="text" name="payment_address_county" id="payment_address_county" type="text" maxlength="120" value="<?php echo $affiliateInfo['payment_address_county'] ; ?>" />
            <label for="payment_address_postcode" >Postcode *</label>
            <input class="text" name="payment_address_postcode" id="payment_address_postcode" type="text" maxlength="12" value="<?php echo $affiliateInfo['payment_address_postcode'] ; ?>" />
        </div>
    </div>

    <div><input type="submit" value="<?php echo ($formAction=='edit' ? 'Update Details' : 'Signup Now') ; ?>" /></div>

</form>

<?php
if($formAction=='edit'){
    ?>
    <p><?php echo draw_icon(ICON_BAD) ; ?><a href="?action=<?php echo $_GET['action'] ; ?>" >Cancel modifications and return to the affiliate homepage</a>.</p>
    <?php
}
?>