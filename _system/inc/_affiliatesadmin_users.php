
<h1>Registered Affiliates</h1>
<table class="list" width="950" cellpadding="0" cellspacing="0" border="0" style="font-size:11px; margin:16px 0;" >
	<tr class="headrow" >
    	<td>Affiliate ID</td>
		<?php
            // $headersArray located in _process_affiliateadmin_users.php
            foreach($headersArray as $label => $colname){
                echo '<td>' ;
                echo '<a href="?action='.$_GET['action'].'&subaction='.$_GET['subaction'].'&orderby='.$colname ;
                echo '&orderdir='.($_SESSION['aff_orderby']==$colname&&$_SESSION['aff_orderdir']=='asc' ? 'desc' : 'asc') ;
                echo '" >' ;
                echo $label ;
                echo '</a>' ;
                echo '</td>'."\n" ;
            }
        ?>
    	<td>User Level</td>
    </tr>
	<?php
    foreach($users as $user){
		$name = trim($user['first_name'].' '.$user['surname']) ;
		if($name==''){
			$name = '<em class="grey" >Unspecified</em>' ;
		}
		if($rowClass==''){
			$rowClass = ' class="offrow" ' ;
		} else {
			$rowClass = '' ;
		}
	    ?>
		<tr<?php echo $rowClass ; ?>>
	    	<td><?php echo draw_icon('graduate.png') ; ?><a href="?action=<?php echo $_GET['action'] ; ?>&amp;subaction=viewuser&amp;affiliateId=<?php echo $user['affiliate_id'] ; ?>" ><?php echo $user['affiliate_id'] ; ?></a></td>
	    	<td><?php echo $name ; ?></td>
	    	<td><?php echo $user['username'] ; ?></td>
	    	<td><?php echo $user['affiliates_count'] ; ?></td>
	    	<td><?php echo $user_level_labels[$user['user_level']] ; ?></td>
	    </tr>
		<?php // $user_level_labels
	}
	?>
</table>