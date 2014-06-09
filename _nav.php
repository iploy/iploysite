<?php

	include_once('_system/_config/configure.php') ;
	include_once('_system/inc/app_top.php') ;

	$urlPrefix = '' ;
	if($_GET['prefix']=='../'){
		$urlPrefix = '../' ;
	}

?>
<div class="topnav navigation" >
	<ul>
	<?php
        if(!$_SESSION['email']){
            ?>
			<?php
        }else{
            ?>
       		<li class="<?php echo $my_account_icon_class ; ?>" ><a href="<?php echo $urlPrefix ; ?>home.php"><?php echo $my_account_link_txt ; ?></a></li>
            <li>|</li>
            <li><a href="<?php echo $urlPrefix ; ?>login.php?action=logout">Logout</a></li>
            <li>|</li>
            <?php
        }
    ?>
		<li><a href="<?php echo $urlPrefix ; ?>contact_us.php" >Help</a></li>
		<li>|</li>
		<li><a href="<?php echo $urlPrefix ; ?>contact_us.php" >Contact <?php echo $_SESSION['APP_CLIENT_NAME'] ; ?></a></li>
    </ul>
</div>
<br  />
<div class="nav navigation" >
    <ul>
        <li><a href="<?php echo $urlPrefix ; ?>./">Home</a></li>
		<li><a href="<?php echo $urlPrefix ; ?>about_iploy.php" >About <?php echo $_SESSION['APP_CLIENT_NAME'] ; ?></a></li>
        <!--
		-->
        <li><a href="<?php echo $urlPrefix ; ?>graduate_signup.php">Graduates</a></li>
		<li><a href="<?php echo $urlPrefix ; ?>employer_signup.php" >Employers</a></li>
        <li><a href="<?php echo $urlPrefix ; ?>advice/" >Advice</a></li>
        <li><a href="<?php echo $urlPrefix ; ?>blog/" target="_blank" >Blog</a></li>
        <?php
			if(!$_SESSION['email']){
				?>
	            <li><a href="<?php echo $urlPrefix ; ?>login.php">Login</a></li>
				<?php
			}
		?>
    </ul>
</div>