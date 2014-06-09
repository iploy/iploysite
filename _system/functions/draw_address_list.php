<?php

function draw_list($object,$showlinks,$showtype){
	// update the class
	// $object->setType($list_type) ;
	$addresses_list = $object->getAddressesList() ;
	$max_per_row = 3 ;
	$row_keeper = 0 ;
	$address_count = 0 ;
	$link_keeper = '?action='.$_GET['action'] ;
	if(sizeof($addresses_list)>0){
		?>
		<div class="addresslist" >
		<?php
		// loop
		foreach($addresses_list as $address){
			$row_keeper++ ;
			$address_count++ ;
			// fix the database order count if required
			if($address_count!=$address['list_order']){
				echo '<!-- Fixed order from '.$address['list_order'].' to '.$address_count.' -->'."\n" ;
				$object->fixCount($address['id'],$address_count) ;
			}
			// start row
			if($row_keeper==1){
				?>
				<div class="address_row" >
				<?php
			}
			if($address['type']=='billing'){
				$type_label = 'Billing Contact' ;
			}
			$address_text = '' ;
			// new para for name + position
			if($address['contact_first_name']!=''||$address['contact_surname']||$address['contact_position']!=''){
				if($address['contact_first_name']!=''||$address['contact_surname']){
					$address_text.= '<b class="Highlight" >' ;
					if($address['contact_first_name']!=''){ $address_text.= $address['contact_first_name'].' ' ; }
					if($address['contact_surname']!=''){ $address_text.= $address['contact_surname'] ; }
					$address_text.= '</b><br />'."\n" ;
				}
				if($address['contact_position']!=''){ $address_text.= '<b class="small" >'.$address['contact_position'].'</b>' ; }
				$address_text.= '</p><p>'."\n" ;
			}
			// address
			if($address['contact_address_1']!=''){ $address_text.= $address['contact_address_1'].'<br />'."\n" ; }
			if($address['contact_address_2']!=''){ $address_text.= $address['contact_address_2'].'<br />'."\n" ; }
			if($address['contact_town']!=''){ $address_text.= $address['contact_town'].'<br />'."\n" ; }
			if($address['contact_state']!=''){ $address_text.= $address['contact_state'].'<br />'."\n" ; }
			if($address['contact_postcode']!=''){ $address_text.= $address['contact_postcode'].'<br />'."\n" ; }
			if($address['contact_country']!=''){ $address_text.= $address['contact_country'].'<br />'."\n" ; }
			// new para for email + tel
			if($address['contact_email']!=''||$address['contact_tel']!=''){
				$address_text.= '</p><p>' ;
				if($address['contact_email']!=''){ $address_text.= '<b>Email:</b> '.$address['contact_email'].'<br />'."\n" ; }
				if($address['contact_tel']!=''){ $address_text.= '<b>Tel:</b> '.$address['contact_tel'].'<br />'."\n" ; }
			}
			// PROMOTE DEMOTE LINKS ARENT WORKING
			// PROMOTE DEMOTE LINKS ARENT WORKING
			// PROMOTE DEMOTE LINKS ARENT WORKING
			// type image
			$type_str = '' ;
			$this_link_prefix = $link_keeper.'&amp;addressid='.$address['id'] ;
			if($showtype===true){
				if($address['type']=='billing'){
					if($address['is_default_billing']=='yes'){
						$icon_src = 'cash.png' ;
						$icon_title = 'Default Billing Address' ;
					} else {
						$icon_src = 'cash_add.png' ;
						$icon_title = 'Alternate Billing Address' ;
					}
				} else {
					$icon_src = 'account_sm.png' ;
					$icon_title = 'Contact Address' ;
				}
				$type_str.= str_replace('class="ico"','style="float:right;" title="'.$icon_title.'"',draw_icon($icon_src)) ;
			} else {
				$type_str.= '<span style="float:right; display:block; text-align:center;" ><a href="'.$this_link_prefix.'&amp;subaction=delete" onclick="return confirm(\'CONFRIM DELETE ADDRESS\nAre you sure you want to delete this address\');" >'.str_replace('class="ico"','',draw_icon('delete.png')).'</a><br />'.$address_count.'</span>' ;
			}
			//links
			$link_str = '' ;
			if($showlinks==true||$showlinks=='editonly'){
				// open
				$link_str = '<p>' ;
				// editlink
				$link_str.= draw_icon('edit.png').'<a href="'.$this_link_prefix.'&amp;subaction=modify" >Edit</a> &nbsp; ' ;
				// show the rest only if showlinks is set to true
				if($showlinks===true){
					// up link
					if($address_count<2){
						$link_str.= draw_icon('up_grey.png').'<span class="grey" >Up</grey> &nbsp; ' ;
					} else {
						$link_str.= draw_icon('up.png').'<a href="'.$this_link_prefix.'&amp;subaction=movebilling&amp;dir=up&amp;oldpos='.$address_count.'&w=w#billinglist" >Promote</a> &nbsp; ' ;
					}
					// down link
					if($address_count==sizeof($addresses_list)){
						$link_str.= draw_icon('down_grey.png').'<span class="grey" >Down</grey> &nbsp; ' ;
					} else {
						$link_str.= draw_icon('down.png').'<a href="'.$this_link_prefix.'&amp;subaction=movebilling&amp;dir=down&amp;oldpos='.$address_count.'&w=w#billinglist" >Demote</a> &nbsp; ' ;
					}
				}
				// close
				$link_str.= '</p>' ;
			}
			?>
			<div class="address" >
				<p><?php echo $type_str ; ?><?php echo $address_text ; ?></p>
				<?php echo $link_str ; // if required ?>
			</div>
			<?php
			// end row
			if($row_keeper==$max_per_row){
				$row_keeper = 0 ;
				?>
				</div>
				<?php
			}
		} 
		// fix for unclosed rows
		if($row_keeper!=0&&$row_keeper<$max_per_row){
			?>
			</div>
			<?php
		}
		?>
		</div>
		<?php
	}
}

?>