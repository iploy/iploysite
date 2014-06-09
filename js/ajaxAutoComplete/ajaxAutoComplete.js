
// Ajax Auto Complete Text Fields (aac)
var aacMinLength = 2 ;
var aacReturnDivider = '|' ;
var aacScriptUrl = '_ajax_search_comm.php' ;
var aacFieldClass = 'aac' ;
var aacReportDivId = 'aacreport' ;
var aacNoIdPrefix = 'aacFld' ;
var aacReultsClass = 'aacResults' ;
var aacReultsPostfix = '_results' ;
var accNoResultsClass = 'accnoresults' ;
var aacRunDelay = 250 ;
var aacRequestTracker = 0 ; // this is used to ensure requests are interupted after a button is pressed to save on lookups
var aacReports = true ;
var aacValuesArray = new Array() ;
var aacValuesLocation = 0 ;
var accIsHighlight = false ;
var aacIdCount = 0 ;
// base function for the ajax request
function aacRequest(aacFieldId,requestNumber){
	// delay the function to ensure no repeated lookups
	(function(){
		if(aacRequestTracker==requestNumber&&$(aacFieldId).value.length>=aacMinLength){ // this catches any old requests and only renders the latest
			//make the ajax call
			aacRequestName = $(aacFieldId).name ;
			aacRequestValue = $(aacFieldId).value ;
			var req = new Request({
				method: 'post',
				url: aacScriptUrl,
				data: { 
					'fldName' : aacRequestName,
					'fldValue' : aacRequestValue,
					'aacDivider' : aacReturnDivider
				},
				onRequest: function() {  },
				onComplete: function(response){ aacToScreen(aacFieldId,response); } 
			}).send();
		} else {
			aacHide(aacFieldId) ;
		}
	}).delay(aacRunDelay)
}
// take the request, process it, draw it, show the results div, all that business.
function aacToScreen(aacFieldId,aacResponse){
	// split the response
	aacValuesLocation = 0 ;
	resultsHtml = '<ul>'+'\n' ;
	if(aacResponse!=''){
		aacValuesArray = aacResponse.split(aacReturnDivider) ;
		for(i=0;i<aacValuesArray.length;i++){
			// alert(responseSplit[i]) ;
			resultsHtml+= '<li title="'+aacValuesArray[i]+'" onclick="accPopulate(\''+aacFieldId+'\',\''+aacValuesArray[i]+'\')" >'+aacValuesArray[i]+'</li>'+'\n' ;
		}
	} else {
		// what to do if empty 
		resultsHtml+= '<li class="'+accNoResultsClass+'" >No Matches Found</li>'+'\n' ;
	}
	resultsHtml+= '</ul>'+'\n' ;
	$(aacFieldId+aacReultsPostfix).innerHTML = resultsHtml ;
	// show the div
	$(aacFieldId+aacReultsPostfix).setStyles({
		'visibility':'visible'
	});
}
// function for doing the highlighting
function doHighlight(aacFieldId,e){
	// keyHighlight
	thisList = $(aacFieldId+aacReultsPostfix).getElements('li') ;
	if(aacValuesArray.length>0){
		if(e.key=='up'||e.key=='down'){
			accIsHighlight = true ;
			if(e.key=='up'){
				if(aacValuesLocation>1){
					aacValuesLocation = aacValuesLocation - 1 ;
					if(thisList[aacValuesLocation]){
						thisList[aacValuesLocation].set('class','') ;
					}
					if(thisList[aacValuesLocation-1]){
						thisList[aacValuesLocation-1].set('class','keyHighlight') ;
					}
				}
			} else if(e.key=='down'){
				if(aacValuesLocation<aacValuesArray.length){
					aacValuesLocation = aacValuesLocation + 1 ;
					if(thisList[aacValuesLocation-2]){
						thisList[aacValuesLocation-2].set('class','') ;
					}
					if(thisList[aacValuesLocation-1]){
						thisList[aacValuesLocation-1].set('class','keyHighlight') ;
					}
				}
			}
			// if the content is bigger than the container, be ready to scroll
			if($(aacFieldId+aacReultsPostfix).getElement('ul').offsetHeight > $(aacFieldId+aacReultsPostfix).offsetHeight){
				// first work out how many we have to count to till we are off the screen
				offScreen = parseFloat($(aacFieldId+aacReultsPostfix).offsetHeight/thisList[0].offsetHeight).floor() ;
				$(aacFieldId+aacReultsPostfix).scrollTo(0,thisList[0].offsetHeight*(aacValuesLocation-offScreen+(offScreen/2).floor()));
			}
		} else if(e.key=='enter'||e.key=='return'){
			if(accIsHighlight==true){
				e.stop(); 
//				alert(aacValuesArray[aacValuesLocation-1]) ;
				$(aacFieldId).value = aacValuesArray[aacValuesLocation-1] ;
				aacHide(aacFieldId) ;
			}
		} else if(e.key=='escape'||e.key=='esc'){
			aacHide(aacFieldId) ;
		}
	}
}
// hide the reults div
function aacHide(aacFieldId){
	$(aacFieldId+aacReultsPostfix).setStyles({
		'visibility':'hidden'
	});
	aacValuesArray = new Array() ;
	accIsHighlight = false ;
}
function accPopulate(aacFieldId,aacValue){
	$(aacFieldId).value = aacValue ;
}
// initialisation function
function aacInit(){
	$$('.'+aacFieldClass).each(function(aacField){
		// set an ID if none exists
		if(!aacField.id){
			aacField.id = aacNoIdPrefix+aacIdCount ;
			aacIdCount++ ;
		}
		// inject the container before the field
		var resultsDiv  = new Element('div', {
			id: aacField.id+aacReultsPostfix,
			class:aacReultsClass,
			style:'margin-top:'+(aacField.offsetHeight-1)+'px; position:absolute; visibility:hidden;'
		});
		resultsDiv.inject(aacField, 'before');
		// turn off browser autocomplete
		aacField.set('autocomplete','off') ;
		// add the keyup event for the ajax call
		aacField.addEvent('keyup',function(e){
			// add a request when down is pressed and nothing is shown
			if(aacValuesArray.length==0&&e.key=='down'){
				aacRequestTracker++ ;
				aacRequest(this.id,aacRequestTracker);
				accIsHighlight = false ;
				
			} else if(e.key.length==1||e.key=='backspace'||e.key=='delete'){
				aacRequestTracker++ ;
				aacRequest(this.id,aacRequestTracker);
			}
		}) ;
		aacField.addEvent('keydown',function(e){ doHighlight(this.id,e) }) ;
		// add an onblue to hide the request
		aacField.addEvent('blur',function(){
			var thisId = this.id ;
			(function(){aacHide(thisId);}).delay(200) ;
		}) ;
	});
}

window.addEvent('domready', function(){
	aacInit() ;
});