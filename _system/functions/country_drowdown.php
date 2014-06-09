<?php


	function country_dropdown($fld_name, $fld_id, $fld_current){
		$common_selected = false ;
		include('_system/_config/countries_array.php') ;
		$return_var = '<select name="'.$fld_name.'" id="'.$fld_id.'" >'."\n" ;
		$return_var.= '<option value="" >No Country Selected&nbsp;&nbsp;&nbsp;&nbsp;</value>'."\n" ;
		// optgroup common
		/*
		$return_var.= '<optgroup label="Most Common Choices" >'."\n" ;
		foreach($countries_common as $country){
			$return_var.= '<option value="'.$country.'" '; 
			if($country==$fld_current){
				$common_selected = true ;
				$return_var.= 'selected="selected" ' ;
			}
			$return_var.= '>'.$country.'&nbsp;&nbsp;&nbsp;&nbsp;</value>'."\n" ;
		}
		$return_var.= '</optgroup>'."\n" ;
		*/
		// optgroup everything else
		//$return_var.= '<optgroup label="All Countries" >'."\n" ;
		foreach($countries as $country){
			$return_var.= '<option value="'.$country.'" '; 
			if($country==$fld_current&&$common_selected!=true){
				$return_var.= 'selected="selected" ' ;
			}
			$return_var.= '>'.$country.'&nbsp;&nbsp;&nbsp;&nbsp;</value>'."\n" ;
		}
		//$return_var.= '</optgroup>'."\n" ;
		$return_var.= '<select>'."\n" ;
		return $return_var ;
	}

?>
