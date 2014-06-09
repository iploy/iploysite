<?php

	include_once('_system/_config/configure.php') ;
	include_once('_system/inc/app_top.php') ;

	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $_SESSION['APP_CLIENT_NAME'] ; ?> | Contact Us</title>
<meta name="description" content="" />
<meta name="keywords" content="" />
<?php include('_global_head.php') ; ?>
</head>
<body>
<?php include('_global_body_start.php') ; ?>
<div align="center" ><!-- Alignment Div -->

	<?php include('_header.php') ; ?>

	<div class="container" align="left" ><!-- Container Div -->
        <div class="bodymain">
			<div class="bodymain_center" >
                <h1>Contact Us</h1>
				<p class="notice"><?php echo draw_icon(ICON_ALERT) ; ?><b>PLEASE NOTE: </b> This site is in an  early beta stage and is still under construction.</p>
				<p>However, if you have  any questions for us or encountered any problems with the site, then please do  not hesitate to contact us.</p>
                <table cellpadding="0" cellspacing="8" style="margin-top:-8px;" >
   	  <tr>
                    	<td width="60" valign="top"><b>Email: </b></td>
                   	<td valign="top"><a href="mailto:admin@iploy.co.uk">admin@iploy.co.uk</a></td>
                  </tr>
                	<tr>
                    	<td valign="top"><b>Phone: </b></td>
                   	  <td valign="top">01283 845 033</td>
                  </tr>
                	<tr>
                    	<td valign="top"><b>Address: </b></td>
                   	  <td valign="top"><?php echo $_SESSION['APP_CLIENT_NAME'] ; ?><br />
                        University Centre<br />
                        Lichfield Street<br />
                        Burton on Trent<br />
                        Staffordshire<br />
                        DE14 3RL</td>
                  </tr>
              </table>
          </div>
		</div>
	</div><!-- Container Div End -->
	<?php include('_footer.php') ; ?>


</div><!-- Alignment Div End -->
<?php include('_global_body_end.php') ; ?>
</body>
</html>
<?php include_once('_system/inc/app_bottom.php') ; ?>
