
// Mootools on-hover link highlight by Rob Sheldrake @ Devmac Systems UK
window.addEvent('domready', function(){

	function getBrowserVersion(){
	}
	function getBrowserName(){
	}

	alertvar = '' ;
	alertvar+= 'navigator.appName: '+navigator.appName +'\n' ;
	alertvar+= 'navigator.appVersion: '+navigator.appVersion +'\n' ;
	alertvar+= 'navigator.userAgent: '+navigator.userAgent +'\n' ;
	alert(alertvar) ; 
	moo_hover_focus_opacity = 1.0 ; 
	moo_hover_dim_opacity = 0.45 ;
	$$('.mooHover').each(function(moo_hover){
		moo_hover.getElements('a').each(function(moo_link){
			moo_link.setStyles({'opacity':moo_hover_focus_opacity,'position':'relative'}) ;
			moo_link.addEvent('mouseover',function(){
				moo_link.set('morph', {duration: 150});
				moo_hover.getElements('a').morph({'opacity':moo_hover_dim_opacity}) ;
				moo_link.morph({'opacity':moo_hover_focus_opacity}) ;
			});
			moo_link.addEvent('mouseout',function(){
				moo_hover.getElements('a').morph({'opacity':moo_hover_focus_opacity}) ;
			});
		})
	});

});