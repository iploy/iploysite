


<?php

	if($_GET['subsubaction']=='rename'&&$_GET['folderid']!=''&&is_numeric($_GET['folderid'])&&$folderUpdated!=true){
		if($edit_name==''){
			foreach($_SESSION['folders_list'] as $folder){
				if($folder['folder_id']==$_GET['folderid']){
					$edit_name = $folder['folder_name'] ;
				}
			}
		}
		?>
        <h1>Rename Folder</h1>
        <?php
		if($edit_name!=''){
			?>
            <form action="?action=<?php echo $_GET['action'] ; ?>&subaction=<?php echo $_GET['subaction'] ; ?>&subsubaction=<?php echo $_GET['subsubaction'] ; ?>&folderid=<?php echo $_GET['folderid'] ; ?>" method="post" name="addfolderform" style="margin:14px 10px;" >
                <input type="text" name="foldername" id="foldername" maxlength="<?php echo $max_name_length ; ?>" value="<?php echo $edit_name ; ?>" class="text" /><br />
                <input type="submit" name="sbm" value="Rename Folder" style="margin:8px 0 0;" />
            </form>
            <?php
		} else {
			?>
            <p class="error" ><?php echo draw_icon(ICON_BAD) ; ?><b>Permission Error</b>: The requested folder does not belong to you</p>
            <?php
		}
		?>

        <div class="greydivider" ></div>
        <p><?php echo draw_icon(ICON_BACK) ; ?><a href="?action=<?php echo $_GET['action'] ; ?>&subaction=<?php echo $_GET['subaction'] ; ?>" >Cancel and return to the folder management</a></p>
        <?php


	} elseif($_GET['subsubaction']=='delete'&&$_GET['folderid']!=''&&is_numeric($_GET['folderid'])&&$requires_confirmation==true&&$folderUpdated!=true){
		if($edit_name==''){
			foreach($_SESSION['folders_list'] as $folder){
				if($folder['folder_id']==$_GET['folderid']){
					$edit_name = $folder['folder_name'] ;
				}
			}
		}
		$msgf->setFolderId($folder['folder_id']) ;
		$message_count = $msgf->messagesInUserFolderCount() ;
		?>
        <h1>Delete <?php echo $edit_name ; ?></h1>
        <p class="notice" ><?php echo draw_icon(ICON_ALERT) ; ?><b>WARNING</b>: The folder you are trying to remove contains <?php echo $message_count.' message'.($message_count==1 ? '' : 's') ; ?>.</p>
		<p>What would you like to do with the messages contained in the folder?</p>
        <ul class="li_buttons" >
        	<li><a href="?action=<?php echo $_GET['action'] ; ?>&subaction=<?php echo $_GET['subaction'] ; ?>&subsubaction=<?php echo $_GET['subsubaction'] ; ?>&folderid=<?php echo $_GET['folderid'] ; ?>&deleteaction=inbox" onClick="return confirm('CONFIRM FOLDER DELETION:\nSelecting this action will move all messages from this folder into your inbox before removing the folder.\n\nAre you sure you wish to do this?');" >Move the messages to my Inbox before removing the folder</a></li>
        	<li><a href="?action=<?php echo $_GET['action'] ; ?>&subaction=<?php echo $_GET['subaction'] ; ?>&subsubaction=<?php echo $_GET['subsubaction'] ; ?>&folderid=<?php echo $_GET['folderid'] ; ?>&deleteaction=remove" onClick="return confirm('WARNING:\nThis action will perminently remove the folder and all messages inside it.\n\nThis action can not be undone.\n\nAre you sure you wish to remove this folder and all of it\'s messages?');" >Permanently delete the messages and the folder</a></li>
            <li><a href="?action=<?php echo $_GET['action'] ; ?>&subaction=<?php echo $_GET['subaction'] ; ?>" >Cancel Request</a></li>
        </ul>
		<?php


	} else {
		?>
        <h1>Manage Folders</h1>
		<h3>Existing Folders:</h3>
		<?php
			if(sizeof($_SESSION['folders_list'])>0){
				?>
				<table cellpadding="0" cellspacing="0" border="0" class="list" width="600" >
					<tr class="headrow" >
						<td>Folder Name</td>
						<td></td>
						<td></td>
						<td width="16" style="padding:0 2px 0;" ></td>
					</tr>
					<?php
					foreach($_SESSION['folders_list'] as $folder){
						$msgf->setFolderId($folder['folder_id']) ;
						$message_count = $msgf->messagesInUserFolderCount() ;
						$confirmation_html = '' ;
						if($message_count==0){
							$confirmation_html = 'onclick="return confirm(\'CONFIRM EMPTY FOLDER DELETION\\nAre you sure you wish to remove this folder?\');" ' ;
						}
						?>
						<tr>
							<td><?php echo draw_icon('inbox_folder.png') ; ?><?php echo $folder['folder_name'] ; ?></td>
                            <td><?php echo $message_count.' message'.($message_count==1 ? '' : 's') ; ?></td>
							<td nowrap ><?php echo draw_icon('textarea.png') ; ?><a href="?action=<?php echo $_GET['action'] ; ?>&subaction=<?php echo $_GET['subaction'] ; ?>&subsubaction=rename&folderid=<?php echo $folder['folder_id'] ; ?>" >Rename</a></td>
							<td class="nopad" ><a href="?action=<?php echo $_GET['action'] ; ?>&subaction=<?php echo $_GET['subaction'] ; ?>&subsubaction=delete&folderid=<?php echo $folder['folder_id'] ; ?>" <?php echo $confirmation_html ; ?>><?php echo draw_icon(ICON_DELETE) ; ?></a></td>
						</tr>
						<?php
					}
					?>
				</table>
				<?php
			} else {
				?>
				<p class="notice" ><?php echo draw_icon(ICON_ALERT) ; ?>You have not yet added any folders</p>
				<?php
			}
		?>
		<h3>Add a Folder:</h3>
		<form action="?action=<?php echo $_GET['action'] ; ?>&subaction=<?php echo $_GET['subaction'] ; ?>&subsubaction=addfolder" method="post" name="addfolderform" style="margin:14px 10px;" >
			<input type="text" name="foldername" id="foldername" maxlength="<?php echo $max_name_length ; ?>" value="" class="text" /><br />
			<input type="submit" name="sbm" value="Add This Folder" style="margin:8px 0 0;" />
		</form>

        <div class="greydivider" ></div>
        <p><?php echo draw_icon(ICON_BACK) ; ?><a href="?action=<?php echo $_GET['action'] ; ?>" >Return to the message inbox</a></p>
		<?php
	}
?>