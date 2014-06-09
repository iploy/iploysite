
function injectContent(contentUrl,containerId){
	// make the ajax call
	var req = new Request({
		method: 'get',
		url: contentUrl,
		onRequest: function() {  },
		onComplete: function(response){ $(containerId).innerHTML = response ; } 
	}).send();
}


