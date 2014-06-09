<?php
	if($_SESSION['user_id']!=''&&$_SESSION['user_level']!=''&&$_SESSION['is_active']=='1'&&$_SESSION['email_is_confirmed']=='1'){
		?>
        <div id="subnav" class="subnav" align="center" >
            <div class="container" align="left" >
                <?php include('_system/inc/'.$nav_include) ; ?>
            </div>
        </div>
		<?php
	}
?>