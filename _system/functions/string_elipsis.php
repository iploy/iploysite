<?php
//
// Current Version : 2.0
//
// Version History:
// 2010-07-06 - v2.0 Complete Re-Write, Major bug fixes and streamlining of code. Dynamic closing of tags. Much more stable
// 2010-02-09 - v1.2b Bug Fix in non-strip-tagged requests
// 2010-02-07 - v1.2 Massive update - Script now finishes un-closed tags and removes partially complete open tags.
// 2010-01-26 - v1.1 Added Fix for concatination of further small words after ignoring a longer word in the for loop.
//

	function string_elipsis($se_str,$se_len,$se_strip_tags=true){		
		// If stripping tags, return at this point
		$se_stripped = strip_tags($se_str) ;
		if($se_strip_tags==true){
			// If stripping tags, simply loop thru the words used.
			$se_str_array = explode(" ",$se_stripped) ;
			$i = 0 ;
			$return_str = '' ;
			while(isset($se_str_array[$i])){
				if(strlen(trim($return_str.' '.$se_str_array[$i]))<=$se_len){
					$return_str = trim($return_str.' '.$se_str_array[$i]) ;
				} else {
					$i = sizeof($se_str_array) ;
				}
				$i++ ;
			}
			$return_str = trim($return_str) ;
			if(strlen($return_str)<strlen($se_stripped)){
				$se_str = trim($return_str).'...' ;
			}
			// Return the Result
			return $se_str ;
		} else {
			$opened_tags_array = array() ;
			$closed_tags_array = array() ;
			$se_str_array = explode('<',$se_str) ;
			$i = 0 ;
			$return_str = '' ;
			$return_txt_only = '' ;
			while(isset($se_str_array[$i])){
				$this_split = explode(">",$se_str_array[$i]) ;
				// Check opened or closed tags
				if($this_split[0]!=''){
					$first_after_tag = true ;
					if(substr($this_split[0],0,1)!='/'){
						$return_str .= ' <'.$this_split[0].'>' ;
						$opened_tags_array[] = $this_split[0] ;
					} else {
						$return_str .= '<'.$this_split[0].'>' ;
						$closed_tags_array[] = $this_split[0] ;
					}
					// Loop thru words
					$this_split = explode(" ",$this_split[1]) ;
					foreach($this_split as $this_word){
						if(strlen(trim($return_txt_only.' '.$this_word))<=$se_len&&$stop_going==false){
							if($first_after_tag==true){
								$this_divider = '' ;
							} else {
								$this_divider = ' ' ;
							}
							$return_str = trim($return_str.$this_divider.$this_word) ;
							$return_txt_only = trim($return_txt_only.' '.$this_word) ;
							$first_after_tag = false ;
						} else {
							$stop_going = true ;
							$i = sizeof($se_str_array) ;
						}
					}
				}
				$i++ ;
			}
			$return_str = trim($return_str) ;
			$return_txt_only = trim($return_txt_only) ;
			// Add the ellipsis when required
			if(strlen($return_txt_only)<strlen($se_stripped)){
				$return_str = trim($return_str).'...' ;
			}
			// Detect unclosed tags
			$sizeofarray = sizeof($opened_tags_array) ;
			foreach($closed_tags_array as $closed_tag){
				$stopthisone = false ;
				$i = 0 ;
				while($i<$sizeofarray&&$stopthisone==false){
					$this_split = explode(" ",$opened_tags_array[$i]) ;
					if($this_split[0]==str_replace("/","",$closed_tag)){
						unset($opened_tags_array[$i]) ;
						$stopthisone = true ;
					} 
					$i++ ;
				}
			}
			// Reverse the order of the ramaining opened tags to close in the right order
			$opened_tags_array = array_reverse($opened_tags_array) ;
			// Close off remaining tags
			foreach($opened_tags_array as $tag){
				$this_split = explode(" ",$tag) ;
				$return_str .= '</'.$this_split[0].'>' ;
			}
			/* Debug
			echo "<p><b>Special</b>:<br />" ;
			echo htmlspecialchars($return_str)."</p>" ;
			echo "<p><b>Norm</b>:<br />" ;
			echo $return_txt_only."</p>" ;
			*/
			// Return the reconstructed string
			return $return_str ;
		}

	}

?>