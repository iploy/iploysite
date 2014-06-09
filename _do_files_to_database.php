<div style="font-size:12px;" >
<?php

if($_GET['run']=='photo'||$_GET['run']=='cv'||$_GET['run']=='certificate'){

	include_once('_system/_config/configure.php') ;
	include('_system/functions/delete_directory.php') ;

	switch($_GET['run']){
		case 'photo' :
		break ;
		case 'cv' :
		break ;
		case 'certificate' :
		break ;
	}

// Open = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = =
	$conn = new ConnectionSimple ;
	$conn->connect(DM_DB_NAME) ;
// Open = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = =

	$idArray = array() ;

	// do the loop
	$folderPath = 'userfiles/'.$_GET['run'].'/' ;
	if ($handle = opendir($folderPath)) {
		echo "<ul>\n" ;
		// loop the parent folder
		while (false !== ($file = readdir($handle))) {
			if(is_dir($folderPath.$file)&&$file!='.'&&$file!='..'){
				$hasFile = false ;
				$newPath = $folderPath.$file."/" ;
				// now loop through the child folder
				if ($handle2 = opendir($newPath)) {
					while (false !== ($file2 = readdir($handle2))) {
						if(!is_dir($newPath.$file2)){
							$hasFile = true ;
						}
					}
				}
				if($hasFile==true){
					$idArray[] = $file ;
				} else {
					delete_directory($newPath) ;
					echo "<li>Empty Directory Removed @ $newPath</li>\n" ;
				}
				closedir($handle2);
			}
		}
		closedir($handle);
		echo "</ul>\n" ;
	}

	echo "<p>".sizeof($idArray)." files found</p>" ;

	$sql = "" ;
	foreach($idArray as $id){
		if($sql!=""){
			$sql.= "OR " ;
		}
		$sql.= "login_id='".$id."' " ;
	}
	$sql = "UPDATE graduates SET has_".$_GET['run']."='1' WHERE ".$sql." ; " ;
	mysql_query($sql) or die( mysql_error()) ;

	echo "<p><b>The following SQL query was executed:</b><br />".$sql."</p>" ;

// Close = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = =
	$conn->disconnect(DM_DB_NAME) ;
// Close = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = =

	echo '<hr />'."\n" ;

}

?>
<h4>Do Files:</h4>
<ul>
	<li><a href="?run=photo" >Photo</a></li>
	<li><a href="?run=cv" >CV</a></li>
	<li><a href="?run=certificate" >Certificate</a></li>
</ul>
</div>
