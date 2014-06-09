href_CLASS = Array() ;
href_NEWURL = Array() ;

href_CLASS[0] = 'nav-the-book' ;
href_NEWURL[0] = 'http://www.lulu.com/product/paperback/behavioural-safety-demystified-with-an-introduction-to-nlp/6322917?productTrackingContext=center_search_results' ;

href_CLASS[1] = 'nav-behavioural-safety' ;
href_NEWURL[1] = '#' ;

href_CLASS[2] = 'nav-safety-workshops' ;
href_NEWURL[2] = '#' ;

window.addEvent('domready', function(){// Dom open
	for(i=0;i<href_CLASS.length;i++){
		$$('.'+href_CLASS[i]).getElement('a').set({'href':href_NEWURL[i],}) ;
//		$$('.link_7').getElement('a').set({'href':'http://www.google.co.uk/','target':'_blank'}) ;
	}
});// Dom open