<?php

	include_once('_system/_config/configure.php') ;
	include_once('_system/classes/connection.php') ;


	function convert_availability_to_date($str){

		$strArray = explode(" ",$str) ;
		$returnStr = '' ;

		if($strArray[0]=='Winter'){
			$returnStr = $strArray[1].'-12-19' ;
		} elseif($strArray[0]=='Spring'){
			$returnStr = $strArray[1].'-3-19' ;
		} elseif($strArray[0]=='Summer'){
			$returnStr = $strArray[1].'-6-19' ;
		} elseif($strArray[0]=='Autumn'){
			$returnStr = $strArray[1].'-9-19' ;
		}

		return $returnStr ; 

	}

?>
<h1>Update Dates</h1>
<?php

	// Open
	$conn = new ConnectionSimple ;
	$conn->connect(DM_DB_NAME) ;

	if($_GET['do']=='go'){

		echo '<p>Going...</p>'."\n" ;

		// do
		$sql = "SELECT login_id, first_name, surname, availability FROM graduates WHERE availability NOT LIKE '%-%';" ;
		$result = mysql_query($sql) or die( mysql_error()) ;

		// loop
		while($row = mysql_fetch_array($result)){
			$sql = "UPDATE graduates SET availability='".convert_availability_to_date($row['availability'])."' WHERE login_id='".$row['login_id']."' ; " ;
			mysql_query($sql) or die( mysql_error()) ;
			echo '<p>'.$sql.'</p>' ;
		}

	} else {

		?>
        <ul>
            <li><a href="?do=go" >Click here to go</a></li>
        </ul>
        <?php

	}

	// Close
	$conn->disconnect(DM_DB_NAME) ;

?>