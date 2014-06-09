<?php
// fix for people on the old style upload.
$img_base = 'userfiles/photo/'.$_SESSION['user_id'].'/' ;
// create the editable image and folder first if it doesnt exist
if(!is_dir($img_base.'_editable/')){
	// get the original filename and type
	$this_dir = opendir($img_base) ;
	$img_filename = '' ;
	$img_orig = '' ;
	while(($file=readdir($this_dir)) != false){
		if(substr($file,0,1)!='.'&&substr($file,0,1)!='_'){
			$img_orig = $img_base.$file ;
			$img_filename = $file ;
		}
	}
	closedir($this_dir) ;
	// Create the image
	$image = new SimpleImage();
	$image->load($img_orig);
	$image_info = getimagesize($img_orig);
	$upload_width = $image_info[0] ;
	$upload_height = $image_info[1] ;
	// Work out which side of the EDITABLE image to maintain
	if(($upload_width/$upload_height)>($upload_height/$upload_width)&&($upload_width/$upload_height)>(IMAGE_UPLOAD_EDITABLE_WIDTH/IMAGE_UPLOAD_EDITABLE_HEIGHT)){
		$editable_scale_type = 'height_max' ;
	} else {
		$editable_scale_type = 'width_max' ;
	}
	// scale it
	if($editable_scale_type=='width_max'){
		$image->resizeToWidth(IMAGE_UPLOAD_EDITABLE_WIDTH);
	} else {
		$image->resizeToHeight(IMAGE_UPLOAD_EDITABLE_HEIGHT);
	}
	mkdir($img_base.'_editable/') ;
	$image->save($img_base.'_editable/'.$img_filename);
}	



// now load the editable image info
	$img_path = $img_base.'_editable/' ;
	$this_dir = opendir($img_path) ;
	$img_filename = '' ;
	while(($file=readdir($this_dir)) != false){
		if(substr($file,0,1)!='.'&&substr($file,0,1)!='_'){
			$img_path.= $file ;
			$img_filename = $file ;
		}
	}
	closedir($this_dir) ;
	list($imgWidth, $imgHeight, $imgType, $imgAttr) = getimagesize($img_path) ;
	// Work out which side of the EDITABLE image to maintain
	if(($imgWidth/$imgHeight)>($imgHeight/$imgWidth)&&($imgWidth/$imgHeight)>(IMAGE_UPLOAD_EDITABLE_WIDTH/IMAGE_UPLOAD_EDITABLE_HEIGHT)){
		$editable_scale_type = 'height_max' ;
	} else {
		$editable_scale_type = 'width_max' ;
	}

	if($editable_scale_type=='width_max'){
		// width is bigger than needed
		$jsHeight = $imgHeight ;
		$jsWidth = IMAGE_UPLOAD_EDITABLE_WIDTH ;
	} else {
		// height is bigger than needed
		$jsHeight = IMAGE_UPLOAD_EDITABLE_HEIGHT ;
		$jsWidth = $imgWidth ;
	}

?>

<link rel="stylesheet" type="text/css" href="js/mooCrop/ysr-crop.css" media="all" />
<script type="text/javascript" language="javascript" src="js/mooCrop/ysr-crop.js"></script>
<script type="text/javascript" language="javascript" >
var ch;
window.addEvent("domready", function() {
	ch = new CwCrop({
		minsize: {x: <?php echo IMAGE_UPLOAD_LARGE_WIDTH ; ?>, y: <?php echo IMAGE_UPLOAD_LARGE_HEIGHT ; ?>},
		maxsize: {x: <?php echo $imgWidth ; ?>, y: <?php echo $jsHeight ; ?>},
		fixedratio: 1.195,
		// initialmax: true,
		initialposition: {x: 0, y: 0},
		onCrop: function(values) {
		   document.forms["crop"].elements["crop[x]"].value = values.x;
			document.forms["crop"].elements["crop[y]"].value = values.y;
			document.forms["crop"].elements["crop[w]"].value = values.w;
			document.forms["crop"].elements["crop[h]"].value = values.h;
			// alert("Selected area is: " + values.x + "," + values.y + " @ " + values.w + " x " + values.h);
			/*
			Here the form should be submitted usually (instead of the alert..):
			*/
			document.forms["crop"].submit();
		}
	});
});

</script>

<h1>Crop Photograph</h1>
<div>
    <div id="imgouter">
        <div id="cropframe" style="background-image: url('user_image.php?userid=<?php echo $_SESSION['user_id'] ; ?>&size=editable&admin=true')">
            <div id="draghandle"></div>
            <div id="resizeHandleXY" class="resizeHandle"></div>
                    <div id="cropinfo" rel="Click to crop">
                        <div title="Click to crop" id="cropbtn"></div>
                        <div id="cropdims"></div>
                    </div>
        </div>
        <div id="imglayer" style="width:<?php echo $imgWidth ; ?>px; height:<?php echo $imgHeight ; ?>px; background:url('user_image.php?userid=<?php echo $_SESSION['user_id'] ; ?>&size=editable&admin=true') center no-repeat #333; padding:1px; position:relative;" ></div>
    </div>
    <div id="formset" style="width:<?php echo $imgWidth ; ?>px;" >
        <!-- example form, the values are written to the hidden fields -->
        <form name="crop" method="post" action="?action=<?php echo $_GET['action'] ; ?>&amp;subaction=<?php echo $_GET['subaction'] ; ?>&amp;crop=true">
            <p align="center" >
                <button onclick="ch.doCrop()">Crop Image</button>
            </p>
            <input type="hidden" name="crop[x]" value="0" />
            <input type="hidden" name="crop[y]" value="0" />
            <input type="hidden" name="crop[w]" value="0" />
            <input type="hidden" name="crop[h]" value="0" />
            <input type="hidden" name="filename" value="<?php echo $img_filename ; ?>" />
        </form>
</div>
</div>

<?php
if($_GET['docrop']=='true'){
	?>
	<p><?php echo draw_icon('back.png') ; ?><a href="?action=<?php echo $_GET['action'] ; ?>&amp;subaction=<?php echo $_GET['subaction'] ; ?>" >Cancel this request and return to the image upload screen</a></p>
    <?php
}
?>