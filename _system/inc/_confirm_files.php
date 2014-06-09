<?php

	include_once('_system/classes/file_confirmation.php') ;
	// Always need this
	$list_function = new fileConfirmation ;

	// Setup the file list
	if($_GET['action']=='adminimg'){
		$unconfimred_functional_string = 'photo' ;
		$unconfimred_file_type = 'Photograph' ;
	}elseif($_GET['action']=='admincv'){
		$unconfimred_functional_string = 'cv' ;
		$unconfimred_file_type = 'CV' ;
	}elseif($_GET['action']=='admincertificate'){
		$unconfimred_functional_string = 'certificate' ;
		$unconfimred_file_type = 'Certificate' ;
	}
	$unconfirmed_file_list = $list_function->listUnconfirmedFiles($unconfimred_functional_string) ;
	$unconfirmed_file_user_info = new user_info ;

	?>
	<style type="text/css" >
	.file_list {
		overflow:auto;
		margin:16px 0 0 16px;
	}
	.file_list .file {
		border:1px solid #999;
		margin:0 16px 16px 0;
		float:left;
		background:#F6F6F6;
		padding:10px;
		width:428px;
		overflow:auto;
		line-height:20px;
	}
	.file_list .file:hover {
		background:#FFFFCC;
	}
	.file_list .file .img {
		width:<?php echo IMAGE_UPLOAD_MEDIUM_WIDTH ; ?>px;
		height:<?php echo IMAGE_UPLOAD_MEDIUM_HEIGHT ; ?>px;
		background-position:center;
		background-repeat:no-repeat;
		background-color:#DDD;
		float:left;
		display:block;
	}
	.file_list .file .info {
		margin-left:<?php echo IMAGE_UPLOAD_MEDIUM_WIDTH+10 ; ?>px;
	}
	.dl_link {
		position:absolute;
		background:url(images/icons/disc.png);
		width:16px;
		height:16px;
		margin-left:-<?php echo IMAGE_UPLOAD_MEDIUM_WIDTH-4 ; ?>px;
		margin-top:4px;
	}
	</style>
	<h1>Confirm <?php echo $unconfimred_file_type ; ?>s</h1>
	<?php

    if(sizeof($unconfirmed_file_list)>0){
    
        ?>
        <p>The following <?php echo sizeof($unconfirmed_file_list) ; ?> <?php echo $unconfimred_file_type ; if(sizeof($unconfirmed_file_list)<>1){ echo 's are' ; } else { echo ' is' ; } ?>  awaiting activation</p>
        <div class="file_list" >
        <?php

        foreach($unconfirmed_file_list as $unconfirmed_file){
            $this_user_info = $unconfirmed_file_user_info->getInfoByUserId($unconfirmed_file['user_id'],false) ;
            ?>
            <div class="file" >
                <?php
				$isImage = false ;
				$filePathArray = explode(".",strtolower($unconfirmed_file['file_path_full'])) ;
				foreach($_SESSION['ALLOWED_FILES_IMAGES'] as $imgType){
					if(strtolower($imgType)==$filePathArray[sizeof($filePathArray)-1]){
						$isImage = true ;
					}
				}
				
                if($unconfimred_functional_string=='photo'){
                    ?>
                    <a href="user_image.php?userid=<?php echo $unconfirmed_file['user_id'] ; ?>&amp;size=original&amp;admin=true&.jpg" class="img lytebox" style="background-image:url(user_image.php?userid=<?php echo $unconfirmed_file['user_id'] ; ?>&amp;size=med&amp;admin=true);" ></a>
                    <?php
                } elseif($unconfimred_functional_string=='certificate'){
					if($isImage==true){
						?>
						<a href="download_userfile.php?userid=<?php echo $unconfirmed_file['user_id'] ; ?>&amp;size=original&amp;type=certificate&amp;admin=true&.jpg" class="dl_link" title="Click here to save or click the lager image to view online" ></a>
						<a href="user_image.php?userid=<?php echo $unconfirmed_file['user_id'] ; ?>&amp;type=certificate&amp;size=original&amp;admin=true&.jpg" class="img lytebox bgdocument" title="Click here to view online or click the disc icon to save" ></a>
						<?php
					} else {
						?>
						<a href="download_userfile.php?userid=<?php echo $unconfirmed_file['user_id'] ; ?>&amp;type=certificate" class="img bgdocument" ></a>
						<?php
					}
                } else {
                    ?>
                    <a href="download_userfile.php?userid=<?php echo $unconfirmed_file['user_id'] ; ?>&amp;type=cv" class="img bgdocument" ></a>
                    <?php
                }
                ?>
                <div class="info" >
                    <span class="small" ><b>User ID</b>: <?php echo $unconfirmed_file['user_id'] ; ?> &bull; <b>Request ID</b>: <?php echo $unconfirmed_file['id'] ; ?></span><br />
                    <?php echo draw_icon(ICON_ACCOUNT) ; ?><a target="_blank" href="view_profile.php?userid=<?php echo $unconfirmed_file['user_id'] ; ?>" ><?php echo $this_user_info['email'] ; ?></a><br />
                    <?php echo draw_icon('calendar.png').date(DM_PHP_SCREENDATE_FORMAT,strtotime($unconfirmed_file['date_added'])) ; ?><br />
                    <a href="?action=<?php echo $_GET['action'] ; ?>&amp;subaction=confirm&amp;requestid=<?php echo $unconfirmed_file['id'] ; ?>" onclick="return confirm('Are you sure you want to confirm Request ID <?php echo $unconfirmed_file['id'] ; ?> for <?php echo $this_user_info['email'] ; ?>\'s profile?');" >Confirm this <?php echo $unconfimred_file_type ; ?></a> | <a href="?action=<?php echo $_GET['action'] ; ?>&amp;subaction=remove&amp;requestid=<?php echo $unconfirmed_file['id'] ; ?>" onclick="return confirm('Are you sure you want to remove Request ID <?php echo $unconfirmed_file['id'] ; ?>?');" >Remove this <?php echo $unconfimred_file_type ; ?></a>
                </div>
            </div>
            <?php
        }

        ?>
        </div>
        <?php

    } else {

        ?>
        <p class="notice" ><?php echo draw_icon(ICON_ALERT) ; ?>There are no <?php echo strtolower($unconfimred_file_type) ; ?>s awaiting confirmation</p>
        <?php

    }
?>