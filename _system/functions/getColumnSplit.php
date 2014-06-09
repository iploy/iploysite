<?php

	function getColumnSplit($totalRecords,$numberOfColumns){
		$returnArray = array() ;
		$returnArray[] = ceil($totalRecords/$numberOfColumns) ;
		for($i=2;$i<=$numberOfColumns;$i++){
			if($i!=$numberOfColumns){
				$returnArray[] = $returnArray[0]*$i ;
			} else {
				$returnArray[] = $returnArray[$numberOfColumns-2]+($totalRecords-$returnArray[$numberOfColumns-2]) ;
			}
		}
		return $returnArray ;
	}

?>