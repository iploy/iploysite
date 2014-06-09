
var password_minimum_lenght = 6 ;
var colours_array = Array('#900','#831E00','#6D3B00','#565A00','#407700','#299600','#13B300') ;
var labels_array = Array('Unacceptable','Weak','Poor','Good','Strong','Very Strong') ;

// Mootools password strength checker by Rob Sheldrake @ Devmac Systems UK
window.addEvent('domready', function(){
	// populate the DIV (so that the div remains unpopulated without JS
	$('password_strength').innerHTML = '<div id="str_bar" ><div id="str_fill" ></div></div><div id="str_label" >Password Strength: <span id="str_txt" >Empty</span></div>' ;
	// Functions
	function password_strength(){
		// Functional Variables
		var pw = ''+$(password_field_name).value ;
		var bar_length = parseFloat($('str_bar').getStyle('width')) ;
		var pw_score = 0 ;
		// Check for a number
		if(pw.search(/\d/)>-1){
			pw_score = pw_score + 1 ;
		}
		// Check for capital letter
		if(pw.search(/[A-Z]/)>-1){
			pw_score = pw_score + 1 ;
		}
		// Check for lower case letter
		if(pw.search(/[a-z]/)>-1){
			pw_score = pw_score + 1 ;
		}
		// Check for non-letter/number
		if(pw.search(/[^a-zA-Z\d]/)>-1){
			pw_score = pw_score + 1 ;
		}
		// Check Length
		if(pw.length>8){
			pw_score = pw_score + 1 ;
		}
		// Check MINIMUM Length and reset values if below minimum length
		if(pw.length<password_minimum_lenght){
			pw_score = 0 ;
		}
		// Set colour based on simple number score
		if(pw_score==0){
			// Force 2% so that something shows rather than null
			str_fill = 2 ;
		} else {
			// work out percentage for the bar length
			str_fill = parseFloat((pw_score/5)*100).round(0) ;
		}
		// Set the colours
		if(colours_array[pw_score]){
			pw_colour = colours_array[pw_score] ;
		} else {
			pw_colour = '#000' ;
		}
		// Set the label
		if(labels_array[pw_score]){
			pw_label = labels_array[pw_score] ;
		} else {
			pw_label = 'No Label Detected' ;
		}
		if(pw.length<1){
			pw_label = 'Empty' ;
		}
		// Compile the width from the fill percentage
		str_fill = ((bar_length/100)*str_fill).round(1) ;
		// Update to screen
		$('str_fill').morph({'width':str_fill,'background-color':pw_colour}) ;
		$('str_txt').innerHTML = pw_label ;
		$('str_txt').morph({'color':pw_colour}) ;
	}
	$(password_field_name).addEvent('keyup',function(){
		password_strength() ;
	});
});