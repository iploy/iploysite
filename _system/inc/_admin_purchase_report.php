<script language="javascript" type="text/javascript" src="js/check_me.js" ></script>
<script src="js/mootools_datepicker/languages/Locale.en-US.DatePicker.js" type="text/javascript" language="javascript" ></script>
<script src="js/mootools_datepicker/js/Picker.js" type="text/javascript" language="javascript" ></script>
<script src="js/mootools_datepicker/js/Picker.Attach.js" type="text/javascript" language="javascript" ></script>
<script src="js/mootools_datepicker/js/Picker.Date.js" type="text/javascript" language="javascript" ></script>
<link href="js/mootools_datepicker/themes/iploy/iploy.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" >
window.addEvent('domready', function(){
    new DatePicker($$('.date'), {
        format: '<?php echo DM_JS_DATE_PICK_FORMAT ; ?>',
        pickerClass: 'datepicker_carmemo',
        timePicker: false,
        startView: 'years',
        toggleElements: '.datetoggle'
    });
});
</script>

<h1>Purchase Report</h1>
<?php

$resultTypes = array(
	'Credit Purchases'=>'credits',
	'Profile Unlocks'=>'profiles'
) ;

if(in_array($_GET['type'],$resultTypes)){

	$bottomDate = '' ;
	if($_GET['bottomdate']!=''){
		$bottomDate = date(DM_PHP_DATE_FORMAT,strtotime($_GET['bottomdate'].' 00:00:00')) ;
	}
	$topDate = '' ;
	if($_GET['topdate']!=''){
		$topDate = date(DM_PHP_DATE_FORMAT,strtotime($_GET['topdate'].' 23:59:59')) ;
	}

	include_once(SITE_PATH.'_system/classes/transactions.php') ;
	$purchaseStats = new transactions ;
	$prThisRange = $purchaseStats->getSummary($_GET['type'],'detailed',$bottomDate,$topDate) ;

}

?>
<script language="javascript" type="text/javascript" >
function vReport(){
	bottomdate = document.getElementById('bottomdate').value ;
	topdate = document.getElementById('topdate').value ;
	if(bottomdate==''&&topdate==''){
		if(confirm('You have not specified a search range, this could produce a large report and take a few minutes to process.\n\nAre you sure you wish to continue with no date range?')){
			return true ;
		} else {
			return false ;
		}
	} else {
		return true ;
	}
}
</script>
<form action="?" method="get" onSubmit="return vReport();" >
	<input type="hidden" value="<?php echo $_GET['action'] ; ?>" name="action" />
	<div class="float_container" >

		<div class="floater lister" style="min-width:0;" >
            <label for="type" >Type</label>
            <ul>
            <?php
			$c = 1 ;
			foreach($resultTypes as $key => $value){
				echo '<li><input name="type" id="type'.$c.'" type="radio" value="'.$value.'" '.($_GET['type']==$value||($_GET['type']==''&&$c==1) ? ' checked="checked" ' : '').'>' ;
				echo '<span onclick="check_me(\'type'.$c.'\',\'radio\');" >'.$key.'</span>' ;
				echo '</li>'."\n" ;
				$c++ ;
			}
			?>
            </ul>
        </div>

		<div class="floater" >
	        <label for="bottomdate" >Start</label>
	        <div class="datetoggle" ></div><input onchange="isModified();" readonly="readonly" tabindex="3" type="text" name="bottomdate" id="bottomdate" class="text date fldmar" value="<?php echo $_GET['bottomdate'] ; ?>" />&nbsp;&nbsp;&nbsp;<?php echo draw_icon('calendar.png') ; ?>&nbsp;<span onclick="document.getElementById('bottomdate').value=''; isModified();" class="pointer" ><?php echo draw_icon('delete.png') ; ?></span>
		</div>

		<div class="floater" >
            <label for="bottom" >End</label>
            <div class="datetoggle" ></div><input onchange="isModified();" readonly="readonly" tabindex="3" type="text" name="topdate" id="topdate" class="text date fldmar" value="<?php echo $_GET['topdate'] ; ?>" />&nbsp;&nbsp;&nbsp;<?php echo draw_icon('calendar.png') ; ?>&nbsp;<span onclick="document.getElementById('topdate').value=''; isModified();" class="pointer" ><?php echo draw_icon('delete.png') ; ?></span>
		</div>

		<div class="floater" style="min-width:0;" >
            <label for="sub" >&nbsp;</label>
   			<input type="submit" name="sub" id="sub" value="Fetch Report" style="margin:0;" />
		</div>

	</div>

</form>
<?php
if($_GET['type']!=''){
	if(sizeof($prThisRange)>0){
		?>
		<div class="greydivider" ></div>
		<p>Your filter returned <?php echo sizeof($prThisRange).' result'.(sizeof($prThisRange)==1 ? '' : 's') ; ?></p>
		<table cellpadding="0" cellspacing="0" border="0" class="list" width="920" >
		<tr class="headrow" >
			<td>Date</td>
			<td>Client</td>
			<td>Product</td>
			<td>Price</td>
		</tr>
		<?php
		$priceTotal = 0 ;
		foreach($prThisRange as $pr){
			if($rowClass==''){
				$rowClass = ' class="offrow" ' ;
			} else {
				$rowClass = '' ;
			}
			$productName = $pr['product_name'] ;
			if($_GET['type']=='profiles'){
				$productName = 'Profile: <a href="view_profile.php?profileid='.$pr['graduate_id'].'" target="_blank" >'.str_replace('Graduate Profile: ','',$pr['product_name']).'</a>' ;
				$productPrice = $pr['product_price_in_credits'].' credit'.($pr['product_price_in_credits']==1 ? '' : 's') ;
				$priceTotal = $priceTotal + $pr['product_price_in_credits'] ;
			} else {
				$productPrice = '&pound;'.number_format($pr['product_price'],2) ;
				$priceTotal = $priceTotal + $pr['product_price'] ;
			}
			?>
			<tr<?php echo $rowClass ; ?>>
				<td><?php echo date(DM_PHP_SCREENSHORTDATE_FORMAT,strtotime($pr['transaction_date'])) ; ?></td>
				<td><?php echo $pr['company_name'] ; ?></td>
				<td><b><?php echo $productName ; ?></b></td>
				<td><?php echo $productPrice ; ?></td>
			</tr>
			<?php
		}
		?>
        <tr class="totalrow" >
            <td></td>
            <td></td>
            <td style="text-align:right; padding-right:0;" ><b>Total:</b></td>
            <td><b><?php echo ($_GET['type']=='profiles' ? $priceTotal.' credit'.($priceTotal==1 ? '' : 's') : '&pound;'.number_format($priceTotal,2)) ; ?></b></td>
        </tr>
		</table>
		<?php
	} else {
		?>
		<p>Your filter returned <?php echo sizeof($prThisRange).' result'.(sizeof($prThisRange)==1 ? '' : 's') ; ?></p>
		<?php
	}
} else {
	?>
    <p>Please select a date range to display a transaction report</p>
    <?php
}
?>


