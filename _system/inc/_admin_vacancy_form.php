<?php
	$icoCount = 0 ;
	$icoRowCount = 0 ;
	$icoPerRow = 12 ;
	$icon_list_function = new bookmarkIcons ;
	$icon_list_function->setPath(VACANCY_ICON_PATH) ;
	$icon_list_function->setReverseArray(true) ;
	$icon_list_function->getList() ;
	$icon_list = $icon_list_function->getList() ;
?>
<script language="javascript" type="text/javascript" src="js/check_me.js" ></script>
<form action="?action=<?php echo $_GET['action'] ; ?>&subaction=<?php echo $subaction ; ?>post&vid=<?php echo $_GET['vid'] ; ?>" method="post" >
    <label for="app_client_name" >Vacancy Name</label>
    <input type="text" name="vacancy_name" id="vacancy_name" value="<?php echo $prepop_name ; ?>" class="text" />

	<?php
	/*
    <label for="app_client_name" >Icon</label>
    <div class="iconlist">
        foreach($icon_list as $icon){
            $ico_checked = '' ;
            if(($icoCount==0&&$prepop_icon=='')||$prepop_icon==$icon){
                $ico_checked = 'checked="checked" ' ;
                if($prepop_icon==$icon){
                    $ico_highlight = ' current' ;
                }
            } else {
                $ico_highlight = '' ;
            }
            if($icoRowCount==0){
                echo '<div class="icorow" >'."\n\r" ;
            }
            $icoCount ++ ;
            $icoRowCount ++ ;
            echo '<div class="icon'.$ico_highlight.'" style="background-image:url('.VACANCY_ICON_PATH.$icon.')" onclick="check_me(\'ico_'.$icoCount.'\',\'\');" ><input type="radio" name="vacancy_icon" value="'.$icon.'" id="ico_'.$icoCount.'" '.$ico_checked.'/></div>'."\n\r" ;
            if($icoRowCount==$icoPerRow){
                echo '</div>'."\n\r" ;
                $icoRowCount = 0 ;
            }
        }
        if($icoRowCount!=0){
            echo '</div>'."\n\r" ;
        }
    </div>
	*/
    ?>

	<?php
		if($subaction=='edit'){
			$button_label = 'Save Changes' ;
		} else {
			$button_label = 'Add Vacancy' ;
		}	
	?>
    <div><input type="submit" name="submit" value="<?php echo $button_label ; ?>" /></div>

</form>