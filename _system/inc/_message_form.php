<?php

	include_once('_system/_config/_message_required_fields.php') ;

	if(!is_array($message_array)){
		$message_array = array() ;
	}

	// add recipient from the querystring if present in the queerystring and not present in the message array
	if(($_GET['profileid']!=''||$_POST['profileid']!='')&&$message_array['profileid']==''){
		if($_POST['profileid']!=''){
			$message_array['profileid'] = $_POST['profileid'] ;
		} else {
			$message_array['profileid'] = $_GET['profileid'] ;
		}
	}

	// add conversation Index from the querystring if present in the queerystring and not present in the message array
	if($message_array['conversation_index']==''){
		$message_array['conversation_index'] = 0 ;
	}

	// options for admins
	$show_add_recipients_link = false ;
	if($_SESSION['user_level']==0||$_SESSION['user_level']==3){
		$show_add_recipients_link= true ;
	}

?>
<script language="javascript" type="text/javascript" >
function validate_message_form(){
	var required_fields = new Array() ;
	var member_count = 0 ;
	<?php
	for($i=0;$i<sizeof($message_required_fields);$i++){
		?>
		this_value = document.getElementById('<?php echo $message_required_fields[$i] ; ?>').value ; // trim is a mootools function
		if(this_value==''){
			required_fields[member_count] = '<?php echo $message_friendly_fields[$i] ; ?>' ;
			member_count ++ ;
		}
		<?php
	}
	?>
	// build the message
	message_txt = '' ;
	for(i=0;i<required_fields.length;i++){
		if(i>0&&i<(required_fields.length-1)){
			message_txt+= ', ' ; //  add a comma to anything but the first and last
		} else if (required_fields.length>1&&i==(required_fields.length-1)){
			message_txt+= ' and ' ; // add and to the last one
		}
		message_txt+= required_fields[i] ;
	}
	if(message_txt!=''){
		if(required_fields.length==1){
			field_txt = 'field is' ;
		} else {
			field_txt = 'fields are' ;
		}
		alert('MESSAGE SUBMISSION ERROR\nThe '+message_txt+' '+field_txt+' required') ;
		return false ;
	} else {
		return true ;
	}
}
</script>
<form action="?sendmessage=true&amp;profileid=<?php echo $_GET['profileid'] ; ?>&replyid=<?php echo $_GET['replyid'] ; ?>" method="post" name="msgform" id="msgform" onsubmit="return validate_message_form();" >

	<label for="msg_recipient" >Recipient<?php echo (sizeof($recipientsArray)!=1 ? 's' : '') ; ?></label>
	<input type="text" style="width:886px;"  name="msg_recipient" id="msg_recipient" readonly="readonly" value="<?php echo $msg_recipient ; ?>" class="text grey" />
    <!-- should be hidden START -->
    <input type="hidden" name="profileid" id="profileid" readonly="readonly" value="<?php echo $message_array['profileid'] ; ?>" />
    <input type="hidden" name="conversation_index" id="conversation_index" readonly="readonly" value="<?php echo $message_array['conversation_index'] ; ?>" />
    <!-- should be hidden END -->
	<?php
		/*
		if($show_add_recipients_link==true){
			?>
			[ADDRECIPIENTSLINK] 
			<?php
		}
		*/
	?>

	<label for="msg_subject" >Subject *</label>
    <input type="text" name="msg_subject" id="msg_subject" class="text" value="<?php echo $message_array['msg_subject'] ; ?>" />

	<a name="msgContent" ></a>
    <label for="msg_content" >Message Content *</label>
	<textarea style="width:886px;" name="msg_content" id="msg_content" class="text" ><?php echo $message_array['msg_content'] ; ?></textarea>


	<div style="clear:both;" >
    	<div id="editorResizeLinks" ></div>
        <input type="submit" value="Send Message" />
    </div>

</form>