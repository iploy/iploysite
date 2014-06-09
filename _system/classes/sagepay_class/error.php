<?php session_start(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Transaction Details</title>
</head>

<body>
<?php 
echo '<h1>Transaction Error</h1>' . "\n";
echo '<p>There has been an error processing your transaction.</p> <p>Error Code ' . $_GET['error'] . '</p>';
?>
</body>
</html>
