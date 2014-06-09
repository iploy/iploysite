<?php
	$pageSize = array(100,250,500,1000) ;

	// Perform requested updates

	if(($_GET['subaction']=='suspend'||$_GET['subaction']=='activate')&&$_GET['userid']!=''&&is_numeric($_GET['userid'])){
		include_once('_system/classes/user_info.php') ;
		$modify_user_function = new user_info ;
		if($_GET['subaction']=='suspend'){
			$modify_user_function->userIsActiveChange($_GET['userid'],0) ;
		} elseif($_GET['subaction']=='activate'){
			$modify_user_function->userIsActiveChange($_GET['userid'],1) ;
		}
	}

	// User List time ------------------------------------
	include_once('_system/classes/user_list.php') ;
	$user_list_function = new user_list ;
	// Add show superuser clause
	if($_SESSION['user_level']==='0'){
		$user_list_function->setShowSu(true) ;
	}
	// Add order by clauses
	if($_GET['orderby']=='level'){ $user_list_function->setOrderBy('user_level') ; }
	if($_GET['orderby']=='email'){ $user_list_function->setOrderBy('email') ; }
	if($_GET['orderby']=='date'){ $user_list_function->setOrderBy('date_created') ; }
	if($_GET['orderby']=='isconfirmed'){ $user_list_function->setOrderBy('email_is_confirmed') ; }
	if($_GET['orderby']=='status'){ $user_list_function->setOrderBy('is_active') ; }
	// Add order direction
	if($_GET['orderdir']=='desc'){ $user_list_function->setOrderDir('DESC') ; }
	// page offest
	if($_GET['pageoffset']!=''&&is_numeric($_GET['pageoffset'])){ $user_list_function->setPageOffset($_GET['pageoffset']) ; }
	// page size
	if($_SESSION['pagesize']==''){
		$_SESSION['pagesize'] = $pageSize[0] ;
	}
	if($_GET['pagesize']!=''&&is_numeric($_GET['pagesize'])&&$_GET['pagesize']>0){
		$_SESSION['pagesize'] = $_GET['pagesize'] ;
	}
	if($_SESSION['pagesize']!=''&&is_numeric($_SESSION['pagesize'])){ $user_list_function->setPageSize($_SESSION['pagesize']) ; }
	// Generate the list
	$user_list = $user_list_function->getUserList('data') ;
	$user_list_total = $user_list_function->getUserList('count') ;


	// Default some variables
	$order_dir = '' ; // default empty is ASC

?>
<h1>Member Admin Options</h1>
<p>For emailing users and suspending or reactivating user accounts.</p>
<?php
	
	//if($user_list_total>$_SESSION['pagesize']){
		?>
        <div style="float:right; margin-right:16px;">Show Max:
        <?php
		for($i=0;$i<sizeof($pageSize);$i++){
            if($i==0||$user_list_total>$pageSize[$i-1]){
				$bold_prefix = '' ;
				$bold_postfix = '' ;
				if($_SESSION['pagesize']==$pageSize[$i]){
					$bold_prefix = '<b>' ;
					$bold_postfix = '</b>' ;
				}
				if($i>0){
					echo '&bull;' ;
				}
                ?>
                <?php echo $bold_prefix ; ?><a href="?action=<?php echo $_GET['action'] ; ?>&orderby=<?php echo $_GET['orderby'] ; ?>&orderdir=<?php echo $_GET['orderdir'] ; ?>&amp;pagesize=<?php echo $pageSize[$i] ; ?>" ><?php echo $pageSize[$i] ; ?></a><?php echo $bold_postfix ; ?> 
                <?php
            }
		}
        ?>
        </div>
		<?php
	//}
?>



<?php
	$first_number = ($_GET['pageoffset']*$_SESSION['pagesize'])+1 ;
	$last_number = ($_GET['pageoffset']+1)*$_SESSION['pagesize'] ;
	if($last_number>$user_list_total){
		$last_number = $user_list_total ;
	}
	if($first_number!=$last_number){
		$paging_label = 'records '.$first_number.' to '.$last_number ;
	} else {
		$paging_label = 'record '.$last_number ;
	}
?>
<h4>Current Users (Showing <?php echo $paging_label ; ?> of <?php echo $user_list_total ; ?> total records)</h4>
<table cellpadding="0" cellspacing="0" class="list" width="97%" >
	<tr class="headrow" >

		<?php
			// ID - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
			$order_dir = '' ; // default empty is ASC
			if($_GET['orderby']=='level'){
				if($_GET['orderdir']==''){
					$order_dir = 'desc' ;
				}
			}
		?>
		<td><?php
        
			if($_GET['orderby']=='level'){
				if($_GET['orderdir']=='desc'){
					echo draw_icon('order_down.png') ;
				} else {
					echo draw_icon('order_up.png') ;
				}
			}
		
		?><a href="?action=<?php echo $_GET['action'] ; ?>&orderby=level&orderdir=<?php echo $order_dir ; ?>&amp;pageoffset=<?php echo $_GET['pageoffset'] ; ?>" >Level</a></td>

		<?php
			// ID - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
			$order_dir = '' ; // default empty is ASC
			if($_GET['orderby']==''){
				if($_GET['orderdir']==''){
					$order_dir = 'desc' ;
				}
			} else {
				$order_dir = 'asc' ;
			}
		?>
		<td><?php
        
			if($_GET['orderby']==''){
				if($_GET['orderdir']=='desc'){
					echo draw_icon('order_down.png') ;
				} else {
					echo draw_icon('order_up.png') ;
				}
			}
		
		?><a href="?action=<?php echo $_GET['action'] ; ?>&orderby=&orderdir=<?php echo $order_dir ; ?>&amp;pageoffset=<?php echo $_GET['pageoffset'] ; ?>" >User ID</a></td>


		<?php

			//Email - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
			if($_GET['orderby']=='email'){
				if($_GET['orderdir']==''){
					$order_dir = 'desc' ;
				}
			}
		?>
		<td><?php
        
			if($_GET['orderby']=='email'){
				if($_GET['orderdir']=='desc'){
					echo draw_icon('order_down.png') ;
				} else {
					echo draw_icon('order_up.png') ;
				}
			}
		
		?><a href="?action=<?php echo $_GET['action'] ; ?>&orderby=email&orderdir=<?php echo $order_dir ; ?>&amp;pageoffset=<?php echo $_GET['pageoffset'] ; ?>" >Email</a></td>
		<?php
			// Date - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
			$order_dir = '' ; // default empty is ASC
			if($_GET['orderby']=='date'){
				if($_GET['orderdir']==''){
					$order_dir = 'desc' ;
				}
			}
		?>
		<td><?php
        
			if($_GET['orderby']=='date'){
				if($_GET['orderdir']=='desc'){
					echo draw_icon('order_down.png') ;
				} else {
					echo draw_icon('order_up.png') ;
				}
			}
		
		?><a href="?action=<?php echo $_GET['action'] ; ?>&orderby=date&orderdir=<?php echo $order_dir ; ?>&amp;pageoffset=<?php echo $_GET['pageoffset'] ; ?>" >Date Joined</a></td>


		<?php
			// Confirmed Status - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
			$order_dir = '' ; // default empty is ASC
			if($_GET['orderby']=='isconfirmed'){
				if($_GET['orderdir']==''){
					$order_dir = 'desc' ;
				}
			}
		?>
		<td><?php
        
			if($_GET['orderby']=='isconfirmed'){
				if($_GET['orderdir']=='desc'){
					echo draw_icon('order_down.png') ;
				} else {
					echo draw_icon('order_up.png') ;
				}
			}
		
		?><a href="?action=<?php echo $_GET['action'] ; ?>&orderby=isconfirmed&orderdir=<?php echo $order_dir ; ?>&amp;pageoffset=<?php echo $_GET['pageoffset'] ; ?>" >Confirmed</a></td>


		<?php
			// Active Status - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
			$order_dir = '' ; // default empty is ASC
			if($_GET['orderby']=='status'){
				if($_GET['orderdir']==''){
					$order_dir = 'desc' ;
				}
			}
		?>
		<td><?php
        
			if($_GET['orderby']=='status'){
				if($_GET['orderdir']=='desc'){
					echo draw_icon('order_down.png') ;
				} else {
					echo draw_icon('order_up.png') ;
				}
			}
		
		?><a href="?action=<?php echo $_GET['action'] ; ?>&orderby=status&orderdir=<?php echo $order_dir ; ?>&amp;pageoffset=<?php echo $_GET['pageoffset'] ; ?>" >Status</a></td>


		<td>Options</td>
	</tr>
	<?php
	$row_class = '' ;
	foreach($user_list as $user){
	//id, email, date_created, is_active, user_level, email_is_confirmed
		if($user['user_level']==='0'){
			$user_label = 'Super User' ;
			$user_icon = 'superuser.png' ;
		}elseif($user['user_level']==1){
			$user_label = 'Graduate' ;
			$user_icon = 'graduate_sm.png' ;
		}elseif($user['user_level']==2){
			$user_label = 'Employee' ;
			$user_icon = 'employer.png' ;
		}elseif($user['user_level']==3){
			$user_label = $_SESSION['APP_CLIENT_NAME'].' Admin' ;
			$user_icon = 'admin.png' ;
		}
		?>
		<tr class="medium <?php echo $row_class ; ?>" title="<?php echo $user_label ; ?>" >
			<td><?php echo str_replace('class="ico"','',draw_icon($user_icon)) ; ?></td>
			<td><b><?php echo $user['id'] ; ?></b></td>
			<td><?php echo $user['email'] ; ?></td>
			<td><?php echo date(DM_PHP_SCREENDATE_FORMAT,strtotime($user['date_created'])) ; ?></td>
			<td><?php
				if($user['email_is_confirmed']==1){
					echo '<b title="Unconfirmed Email address" class="green" >'.draw_icon('email_yes.png').'Yes</b>' ;
				} else {
					echo '<b title="Email Address Confirmed" class="red" >'.draw_icon('email_no.png').'No</b>' ;
				}
			?></td>
			<td><?php
				if($user['is_active']==1){
					if($user['email_is_confirmed']==1){
						echo '<b title="Active User" class="green" >'.draw_icon(ICON_GOOD).'Active</b>' ;
					} else {
						echo '<b title="User active, but login disabled email address is confirmed by the user" class="orange" >'.draw_icon('email_warning.png').'Unconfirmed</b>' ;
					}
				} else {
					echo '<b title="Suspended User" class="red" >'.draw_icon(ICON_BAD).'Suspended</b>' ;
				}
			?></td>
			<?php
				if($user['is_active']==1){
					$toggle_action = 'Suspend' ;
					$toggle_img = 'lock_open.png' ;
				} else {
					$toggle_action = 'Activate' ;
					$toggle_img = 'lock_closed.png' ;
				}
			?>
			<td class="options" width="10" nowrap="nowrap" ><?php
			// send regular email
			?><a href="mailto:<?php echo $user['email'] ; ?>" title="Send regular E-Mail" ><?php echo draw_icon('at_sign.png') ; ?></a><?php
            // Send internal message
			if($user['is_active']==1&&$user['email_is_confirmed']==1){
				?><a href="message_send.php?profileid=<?php echo $user['id'] ; ?>" title="Send system mail" ><?php echo draw_icon('email.png') ; ?></a><?php
			} else {
				echo draw_icon('email_grey.png') ;
			}
			// View Profile
				if($user['user_level']==1){
					?><a href="view_profile.php?profileid=<?php echo $user['id'] ; ?>" target="_blank" ><?php echo draw_icon('profile.png') ; ?></a><?php
				//} elseif($user['user_level']==2){
				//	? ><a href="employer.php?profileid=<?php echo $user['id'] ; ? >" target="_blank" ><?php echo draw_icon('profile.png') ; ? ></a><?php
				} else {
					echo draw_icon('profile_grey.png') ;
				}
			// toggle active
			?><a title="<?php echo $toggle_action ; ?>" href="?action=<?php echo $_GET['action'] ; ?>&amp;subaction=<?php echo strtolower($toggle_action) ; ?>&amp;userid=<?php echo $user['id'] ; ?>&amp;orderby=<?php echo $_GET['orderby'] ; ?>&amp;orderdir=<?php echo $_GET['orderdir'] ; ?>&amp;pageoffset=<?php echo $_GET['pageoffset'] ; ?>" ><?php echo draw_icon($toggle_img) ; ?></a></td>
		</tr>
		<?php
		if($row_class==''){
			$row_class = ' offrow' ;
		} else {
			$row_class = '' ;
		}
	}
	?>

</table>
<?php
	if($user_list_total>$_SESSION['pagesize']){
	?>
	<p>Show Records <?php

		$page_offset = 0 ;
		while(($page_offset*$_SESSION['pagesize'])<$user_list_total){
			$first_number = ($page_offset*$_SESSION['pagesize'])+1 ;
			$last_number = ($page_offset+1)*$_SESSION['pagesize'] ;
			if($last_number>$user_list_total){
				$last_number = $user_list_total ;
			}
			if($page_offset!=0){
				echo ' &bull; ' ;
			}
			if($first_number!=$last_number){
				$paging_label = $first_number.'-'.$last_number ;
			} else {
				$paging_label = $last_number ;
			}
			echo '<a href="?action='.$_GET['action'].'&amp;orderby='.$_GET['orderby'].'&amp;orderdir='.$_GET['orderdir'].'&amp;pageoffset='.$page_offset.'" >'.$paging_label.'</a>' ;
			$page_offset ++ ;
		}
	
	?></p>
	<?php
	}
?>