<style type="text/css" >

#message_home {
	position:relative;
	height:596px;
}
#message_home .left, #message_home .right {
	position:absolute;
	bottom:0;
	top:0;
	border:1px solid #555;
	background:#FAFAFA;
}
#message_home .left {
	left:0;
	width:180px;
}
#message_home .right {
	right:0;
	width:755px;
	background:#DDD;
}
#message_home h5 {
	margin:0;
}
#message_home .left h5 {
	margin:0 0 6px;
}
#message_home h5 {
	background:#777;
	border-bottom:1px solid #555;
	padding:0 7px;
	color:#FFF;
	line-height:28px;
	font-size:13px;
}
#message_home h5 .ico {
	margin-right:5px;
}
#message_home .floatr {
	float:right;
	line-height:28px;
	color:#FFF;
	margin-right:6px;
	font-weight:bold;
}
#message_home .floatr select {
	margin:4px -3px 0 0 ;
}
#message_home .left a {
	color:#555;
}
#messages {
	width:100%;
	background:#FFF;
}
#messages tr td {
	line-height:26px;
	padding:0 4px 0 6px;
	border-bottom:1px solid #CCC;
	font-size:12px;
}
#messages tr td div {
	height:24px;
	overflow:hidden;
}

#messages .boldrow td {
	font-weight:bold;
}
#messages .off td {
	background-color:#FAFAFA;
}
#messages tr:hover td {
	background-color:#FFFFCC;
}
#messages .headrow td, #messages .headrow:hover td {
	background:#9A9A9A;
	color:#FFF;
	font-weight:bold;
	border-bottom:1px solid #555;
}


#message_postnav {
	line-height:28px;
	overflow:auto;
	padding:0 4px;
}
#message_postnav .right {
	float:right;
}



.addfolder {
}
.addfolder h6 {
	margin:6px 10px 6px 0;
	padding:6px 0 0;
	border-top:1px solid #999;
	color:#666;
	font-size:12px;
}
.addfolder form {
	margin:0;
	padding:0;
}
.addfolder input[type="text"] {
	width:132px;
	padding:4px;
	color:#555;
}
.addfolder input[type="image"] {
	margin:0 0 -2px 6px;
}

.addfolder .managefolders {
	margin:4px 1px;
	font-size:11px;
}

</style>
<h1>Messages</h1>
<div id="message_home" >
    <div class="right" >
        <form name="actionform" id="actionform" action="?action=<?php echo $_GET['action'] ; ?>&folderid=<?php echo $_GET['folderid'] ; ?>&subaction=msgaction&forcerecount=true" class="nopad nomar" method="post" >
        	<input type="hidden" name="folder_type" id="folder_type" value="<?php echo $folder_type ; ?>" />
            <?php
                $msgf->setFolderId($folder_id) ;
                $msgCountFull = $msgf->getMessageList(true,$folder_type) ;
                $msgf->setPageSize($page_size) ;
                $msgf->setPageOffset($page_offset) ;
                $msgList = $msgf->getMessageList(false,$folder_type) ;
            ?>
            <span class="floatr" id="msgaction_select" ></span>
            <h5><?php echo draw_icon($folder_icon).''.$folder_name ; ?></h5>
            <table cellpadding="0" cellspacing="0" border="0" id="messages" >
            <?php
                // no new messages
                if(sizeof($msgList)==0){
                    ?>
                    <tr>
                        <td>There are no messages in this folder</td> 
                    </tr>
                    <?php
                } else {
                    // headers
                    if($folder_type=='sent'){
                        $correspondent_label = 'To' ;
                        $action_label = 'Sent' ;
                    } else {
                        $correspondent_label = 'From' ;
                        $action_label = 'Received' ;
                    }
                    ?>
                    <tr class="headrow" >
                        <td><?php echo $correspondent_label ; ?></td>
                        <td>Subject</td>
                        <td><?php echo $action_label ; ?></td>
                        <td id="selectall" width="16" ></td>
                    </tr>
                    <?php
                }
                // do the loop
                if($msgCountFull-($page_size*$page_offset)>$page_size){
                    $list_max = $page_size ;
                } else {
                    $list_max = $msgCountFull-($page_size*$page_offset) ;
                }
                foreach($msgList as $msg){
                    if($row_class==''){
                        $row_class = 'off' ;
                    } else {
                        $row_class = '' ;
                    }
                    if($msg['is_read']==0||$msg['is_read']==''||is_null($msg['is_read'])){
                        $icon_label = 'Unread Message' ;
                        if($folder_type!='sent'){
                            $boldrow_class = 'boldrow ' ;
                        } 
                        $row_icon = 'email.png' ;
                    } else {
                        $read_date = date('D '.DM_PHP_SCREENSHORTDATETIME_FORMAT,strtotime($msg['read_date'])) ;
                        if($folder_type!='sent'){
                            $icon_label = 'You read this message on '.$read_date ;
                        } else {
                            $icon_label = 'The recipient read this message on '.$read_date ;
                        }
                        $boldrow_class = '' ;
                        $row_icon = 'email_open.png' ;
                    }
                    // to / from name
                    if($msg['user_level']==0){ //  admin
                        $correspondant = $_SESSION['APP_CLIENT_NAME'].' Developer' ;
                    } elseif($msg['user_level']==1){ // grad
                        $correspondant = $msg['first_name'].' '.$msg['surname'] ;  
                    } elseif($msg['user_level']==2){ // employer
                        $correspondant = $msg['company_name'] ;  
                    } elseif($msg['user_level']==3){ // employer
                        $correspondant = $_SESSION['APP_CLIENT_NAME'].' Administrator' ;
                    }
                    if($correspondant==''){
                        $correspondant = 'Unspecified UL:'.$msg['author_user_level'] ;
                    }
                    // if you need to echo the array
                    // echo '<tr><td colspan=100 >'.print_r($msg,true).'</td></tr>' ;
                    ?>
                    <tr class="<?php echo $row_class.' '.$boldrow_class ; ?>" >
                        <td class="txt" nowrap="nowrap" >
                            <span title="<?php echo $icon_label ; ?>" ><?php echo draw_icon($row_icon) ; ?></span> 
                            <a href="message_view.php?messageid=<?php echo $msg['message_id'] ; ?>&subaction=<?php echo $_GET['subaction'] ; ?>" ><?php echo $correspondant ; ?></a>
                        </td> 
                        <td><div><?php echo $msg['subject'] ; ?></div></td>
                        <td nowrap ><?php echo date('D '.DM_PHP_SCREENSHORTDATETIME_FORMAT,strtotime($msg['sent_date'])) ; ?></td>
                        <td class="options" align="right" >
                            <div><input type="checkbox" name="msgidarray[]" value="<?php echo $msg['message_id'] ; ?>" /></div>
                        </td>
                    </tr>
                    <?php
                }
            ?>
            </table>
        </form>
    </div>
    <div class="left" >
        <h5 style="" >Folders</h5>
        <div class="lister" >
            <ul>
                <?php
                    if($_SESSION['unread_count_0']>0){
                        $label_prefix = '<b title="Unread Messages" >' ;
                        $label_postfix = '</b>' ;
                    } else {
                        $label_prefix = '' ;
                        $label_postfix = '' ;
                    }
                ?>
                <li><?php echo draw_icon($inbox_icon).$label_prefix ; ?><a href="<?php echo $link_base ; ?>" >Inbox (<?php echo $_SESSION['unread_count_0'] ; ?>)</a><?php echo $label_postfix ; ?></li>
                <?php
                    foreach($_SESSION['folders_list'] as $userFolder){
                        if($_SESSION['unread_count_'.$userFolder['folder_id']]>0){
                            $label_prefix = '<b title="Unread Messages" >' ;
                            $label_postfix = '</b>' ;
                        } else {
                            $label_prefix = '' ;
                            $label_postfix = '' ;
                        }
                        echo '<li class="ellipsis" style="width:160px;" >' ;
						echo draw_icon('inbox_folder.png') ;
						echo $label_prefix.'<a href="?action='.$_GET['action'].'&folderid='.$userFolder['folder_id'].'" >'.$userFolder['folder_name'].'</a> '.($_SESSION['unread_count_'.$userFolder['folder_id']]>0 ? '('.$_SESSION['unread_count_'.$userFolder['folder_id']].')' : '').$label_postfix ;
						echo '</li>'."\n" ;
                    }
                ?>
                <li><?php echo draw_icon('inbox_out.png') ; ?><a href="<?php echo $link_base ; ?>&amp;folderid=sent" >Sent Items</a></li>
            </ul>
            <div class="addfolder" >
                <h6>Add a Folder</h6>
                <form action="?action=<?php echo $_GET['action'] ; ?>&subaction=addfolder" method="post" name="addfolderform" >
                    <input type="text" name="foldername" id="foldername" maxlength="<?php echo $max_name_length ; ?>" value="" /><input type="image" src="images/icons/add.png" width="16" height="16" border="0" />
                </form>
            <p class="managefolders" ><a href="?action=<?php echo $_GET['action'] ; ?>&subaction=folders" >Manage Folders</a></p>
            </div>
        </div>
    </div>
</div>
<script language="javascript" type="text/javascript" >
window.addEvent('domready', function() {
	var msgact_select = '' ;
	<?php
	if(sizeof($msgList)>0){
	
		?>
	msgact_select+= '<select name="msgaction" id="msgaction" >\n' ;
	msgact_select+= '<option value="" >Selected Messages&nbsp;&nbsp;&nbsp;&nbsp;</option>\n' ;
	<?php
		if($folder_type!='sent'){
		?>
			msgact_select+= '<optgroup label="Mark Messages" >\n' ;
			msgact_select+= '<option value="mark_read" >Mark as Read&nbsp;&nbsp;&nbsp;&nbsp;</option>\n' ;
			msgact_select+= '<option value="mark_unread" >Mark as Unread&nbsp;&nbsp;&nbsp;&nbsp;</option>\n' ;
			msgact_select+= '</optgroup>\n' ;
			<?php
				if(sizeof($_SESSION['folders_list'])>0){
					?>
					msgact_select+= '<optgroup label="Move to folder" >\n' ;
					<?php
						if($folder_id>0){
						?>
						msgact_select+= '<option value="0" >Move to Inbox&nbsp;&nbsp;&nbsp;&nbsp;</option>\n' ;
						<?php
						}
					?>
					<?php
						foreach($_SESSION['folders_list'] as $userFolder){
							if($folder_id!=$userFolder['folder_id']){
								?>
								msgact_select+= '<option value="<?php echo $userFolder['folder_id'] ; ?>" >Move to <?php echo $userFolder['folder_name'] ; ?>&nbsp;&nbsp;&nbsp;&nbsp;</option>\n' ;
								<?php
							}
						}
					?>
					msgact_select+= '</optgroup>\n' ;
					<?php
				}
		}
		?>
		msgact_select+= '<optgroup label="Delete Messages" >\n' ;
		msgact_select+= '<option value="delete" >Delete Messages&nbsp;&nbsp;&nbsp;&nbsp;</option>\n' ;
		msgact_select+= '</optgroup>\n' ;
		msgact_select+= '</select>\n' ;
		<?php
	} else {
		?>
		msgact_select = "<?php echo $msgCountFull.' Message'.($msgCountFull==1 ? '' : 's') ; ?>" ;
		<?php
	}
	?>
	$('msgaction_select').innerHTML = msgact_select ;


	// add the select all button
	var selectall_status = false ;
	if($('selectall')){
		$('selectall').innerHTML = '<?php echo draw_icon('checkbox_no.png') ; ?>' ;
		$('selectall').getElement('.ico').setStyles({'margin':'0 0 0 3px'}) ;
		$('selectall').getElement('.ico').set('title','Select All') ;
		// onclick event
		$('selectall').getElement('.ico').addEvent('click',function(){
			if(selectall_status==false){
				selectall_status = true ;
			} else {
				selectall_status = false ;
			}
			selectall(selectall_status) ;
			$$('input[name="msgidarray[]"]').each(function(msgId){
				msgId.checked = selectall_status ;
			}) ;
		}) ;
	}

	// onclick event for the checkboxes
	$$('input[name="msgidarray[]"]').addEvent('click',function(){
		selectall_status = false ;
		selectall(selectall_status) ;
	}) ;

	function selectall(selected){
		if(selected==true){
			old_label = '_no' ;
			new_label = '_yes' ;
		} else {
			old_label = '_yes' ;
			new_label = '_no' ;
		}
		$('selectall').getElement('.ico').setAttribute('src',$('selectall').getElement('.ico').getAttribute('src').replace(old_label,new_label)) ;
	}
	// now add the action
	if($('msgaction')){
		$('msgaction').addEvent('change',function(){
			if($('msgaction').value!=''){
				id_counter = 0 ;
				$$('input[name="msgidarray[]"]').each(function(msgId){
					if(msgId.checked){
						id_counter++ ;
					}
				});
				// error if none found
				if(id_counter<1){
					$('msgaction').value = '' ;
					alert('PLEASE NOTE:\nYou did not select any messages') ;
					return false ;
				} else {
					// check for confirmation on delete
					if($('msgaction').value=='delete'){
						if(confirm('CONFIRM DELETE MESSAGES\nYou are about to delete '+id_counter+' message'+(id_counter==1 ? '' : 's')+'\n\nPress OK to continue')){
							$('actionform').submit() ;
						} else {
							$('msgaction').value = '' ;
							return false ;
						}
					} else {
					// else do the thing
						$('actionform').submit() ;
					}
				}
			}
		}) ;
	}
});

</script>

<div id="message_postnav">
	<?php
        if($page_size<$msgCountFull){
			echo '<div class="right" ><b>Mesages</b>:' ;
            include_once('_system/classes/paging.php') ;
            $pf = new paging ;
            $pf->setPageSize($page_size) ;
            $pf->setListTotal($msgCountFull) ;
            $pf->setLinkPrefix($link_base.'&subaction='.$_GET['subaction'].'&folderid='.$_GET['folderid']) ;
            echo $pf->generatePaging() ;
			echo '</div>' ;
        }
    ?>
</div>