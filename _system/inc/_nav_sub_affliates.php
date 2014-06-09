
<ul <?php echo ($subClass!='' ? 'class="'.$subClass.'" ' : '') ; ?> style="list-style:none;" >
	<li><a href="?action=affiliatesadmin&amp;subaction=requests" ><?php echo draw_icon('cash_register.png') ; ?>View Payment Requests</a></li>
	<li><a href="?action=affiliatesadmin&amp;subaction=historyout" ><?php echo draw_icon('bank.png') ; ?>View Payout History</a></li>
	<li><a href="?action=affiliatesadmin&amp;subaction=users" ><?php echo draw_icon('people.png') ; ?>View Registered Affiliates</a></li>
</ul>


<?php
	$subClass = '' ;
?>
