function disableSelection(target){
	if (typeof target.onselectstart!="undefined") //IE route
		target.onselectstart=function(){return false}
	else if (typeof target.style.MozUserSelect!="undefined") //Firefox route
		target.style.MozUserSelect="none"
	else //All other route (ie: Opera)
		target.onmousedown=function(){return false}
	target.style.cursor = "default"
}
window.addEvent('domready', function(){
	var mooNavHeight = 22 ;
	var visible_opacity = 1 ;
	var invisible_opacity = 0.001 ;
	var mooViewZindexCore = 100 ; // Bottom end Zindex, any more are proritised from this number
	var mooViewNav_mode = 'html' ; // can be html, number or title
	var mooViewNav_html = '&bull;' ; // if blank will assume numbers - &diams;
	var mooViewSlideType = 'smooth' ; // Can be fast, smooth,  moody, bounce, basic or none
	var mooViewSlideDelay = 7000 ;
	var mooViewTitleFadeOutDuration = 400 ;
	var mooViewTitleFadeBackInDelay = 100 ;
	var mooViewStopDelay = 15000 ;
	var leftRightNav = false ; // should we use the left/right next/previous links
	var mooViewFadeOutQuickNav = true ; // If false shows quicklinks all the time, if not shows only on hover
	var mooView_blankTitles = false ; // If set to false titles will never be blank and the system will use "Image ##", if true, blank titles are used
	var mooViewVisible = 1.0 ;
	var mooViewInvisible = 0.001 ;
	var debug_show_animationreport = false ;
	var mooView_data = new Array() ;
	// Animation Setup
	if(mooViewSlideType=='fast'){
		mooViewSlideType = 'pow:out' ;
		mooViewSlideTime = 700 ;
	} else if(mooViewSlideType=='smooth'){
		mooViewSlideType = 'sine:in' ;
		mooViewSlideTime = 600 ;
	} else if(mooViewSlideType=='moody'){
		mooViewSlideType = 'pow:in' ;
		mooViewSlideTime = 500 ;
	} else if (mooViewSlideType=='bounce'){
		mooViewSlideType = 'bounce:out' ;
		mooViewSlideTime = 800 ;
	} else if (mooViewSlideType=='basic'){
		mooViewSlideType = 'linear' ;
		mooViewSlideTime = 900 ;
	} else if (mooViewSlideType=='none'){
		mooViewSlideType = 'linear' ;
		mooViewSlideTime = 0 ;
	}
	// play loop annimation
	function animation_handler(ah_id,ah_type){
		if(debug_show_animationreport==true){
			$('mooViewReport_'+ah_id).innerHTML = mooView_data[ah_id]['animation_status'] ;
		}
		if(ah_type=='logic'){
		 // alert(mooView_data[ah_id]['animation_status']) ;
			if(mooView_data[ah_id]['animation_status']=='init'){
				mooView_data[ah_id]['animation_status'] = 'play' ;
				(function(){
					animation_handler(ah_id,'anim') ;
				}).delay(mooViewSlideDelay) ;
			} else if(mooView_data[ah_id]['animation_status']=='play'){
				(function(){
					animation_handler(ah_id,'anim') ;
				}).delay(mooViewSlideDelay+mooViewSlideTime) ;
			} else if(mooView_data[ah_id]['animation_status']=='stop'){
				mooView_data[ah_id]['animation_status'] = 'play' ;
				animation_handler(ah_id,'anim') ;
			}
		}
		if(ah_type=='anim'){
			if(mooView_data[ah_id]['animation_status']=='init'||mooView_data[ah_id]['animation_status']=='play'){
				imgQuickNav(ah_id,'right',false) ;
				animation_handler(ah_id,'logic') ;
			} else {
				(function(){
					animation_handler(ah_id,'logic') ;
				}).delay(mooViewStopDelay) ;
			}
		}
	}
	// move master div function
	function moveToMe(mtm_view_id,mtm_img_id,mtm_img_title){
		// Move
		mooView_data[mtm_view_id]['current_img'] = mtm_img_id ;
		$('mooView_'+mtm_view_id).morph({'margin-left':'-'+mtm_img_id*mooView_data[mtm_view_id]['stage_width']}) ;
		// Set title
		$('mooViewTitle_'+mtm_view_id).set('morph',{duration:mooViewTitleFadeOutDuration}) ;
		$('mooViewTitle_'+mtm_view_id).morph({'opacity':invisible_opacity}) ;
		(function(){$('mooViewTitle_'+mtm_view_id).innerHTML=mtm_img_title}).delay(mooViewTitleFadeOutDuration) ;
		(function(){$('mooViewTitle_'+mtm_view_id).morph({'opacity':visible_opacity});}).delay(mooViewTitleFadeOutDuration+mooViewTitleFadeBackInDelay) ;
		// Highlight
		$$('#mooViewNav_'+mtm_view_id+' span').set('morph',{duration:500}) ;
		$$('#mooViewNav_'+mtm_view_id+' span').morph({'color':mooView_data[mtm_view_id]['color']}) ;
		(function(){$('mooViewNav_'+mtm_view_id+'_'+mtm_img_id).morph({'color':mooView_data[mtm_view_id]['selected_color']});}).delay(200) ;
		mooView_data[mtm_view_id]['current_img'] = mtm_img_id ;
	}
	// Move left / right function
	function imgQuickNav(iqn_view_id,iqn_dir,iqn_click){
		if(iqn_click==true){
			mooView_data[iqn_view_id]['animation_status'] = 'stop' ;
		}
		if(iqn_dir=='left'){
			// Check if the count isnt already min
			if(mooView_data[iqn_view_id]['current_img']==0){
				mooView_data[iqn_view_id]['current_img'] = (mooView_data[iqn_view_id]['count']-1) ;
			} else {
				mooView_data[iqn_view_id]['current_img'] = mooView_data[iqn_view_id]['current_img'] - 1 ;
			}
		} else {
			// Check if the count isnt already max
			if(mooView_data[iqn_view_id]['current_img']==(mooView_data[iqn_view_id]['count']-1)){
				mooView_data[iqn_view_id]['current_img'] = 0 ;
			} else {
				mooView_data[iqn_view_id]['current_img'] = mooView_data[iqn_view_id]['current_img'] + 1 ;
			}
		}
		moveToMe(iqn_view_id,mooView_data[iqn_view_id]['current_img'],mooView_data[iqn_view_id][mooView_data[iqn_view_id]['current_img']]) ;
	}
	// Before we do anything else, remove ALL padding from ALL mooView areas, Padding bad ok.
	$$('.mooView').setStyles({'padding':'0'}) ;
	// Loop through each instance of .mooView. This allows multiples
	var i=0 ;
	$$('.mooView').each(function(mooView){
		disableSelection(mooView) ;
		mooView.set('morph',{duration:mooViewSlideTime, transition: mooViewSlideType}) ;
		// Default the variables in the associative array for this loop's data
		mooView_data[i] = new Array() ;
		mooView_data[i]['animation_status'] = 'init' ;
		mooView_data[i]['current_img'] = 0 ;
		mooView_data[i]['current_title'] = mooView.getElements('img')[0].title ;
		// Grab original data and Populate the ghost content
		if(leftRightNav==true){
			quickNav = '<span id="mooViewQuickNavLeft_'+i+'" class="mooViewQuickNav mooViewQuickNav_'+i+' mooViewQuickNavLeft" ></span><span id="mooViewQuickNavRight_'+i+'" class="mooViewQuickNav mooViewQuickNav_'+i+' mooViewQuickNavRight" ></span>' ;
		} else {
			quickNav = '' ;
		}
		mooView.innerHTML = '<div id="mooView_'+i+'" class="mooViewSlider" >'+mooView.innerHTML+'</div><div class="mooViewTitle" id="mooViewTitle_'+i+'" ></div><div class="mooViewNav" id="mooViewNav_'+i+'" ></div>'+ quickNav;
		// Report
		if(debug_show_animationreport==true){
			mooView.innerHTML = mooView.innerHTML + '<div id="mooViewReport_'+i+'" >R</div>' ;
			$('mooViewReport_'+i).setStyles({'position':'absolute','margin':'6px','opacity':0.8,'padding':10,'color':'#FF0','background':'#111'}) ;
		}
		$$('.mooViewSlider').set('morph',{duration:mooViewSlideTime, transition: mooViewSlideType}) ;
		$('mooViewTitle_'+i).innerHTML = mooView_data[i]['current_title'] ;
		// Grab original data
		mooView_data[i]['count'] = mooView.getElements('img').length ;
		mooView_data[i]['image_old_width'] = mooView.getElements('img')[0].getAttribute('width') ;
		mooView_data[i]['image_old_height'] = mooView.getElements('img')[0].getAttribute('height') ;
		mooView_data[i]['stage_width'] = mooView.offsetWidth-(parseFloat(mooView.getStyle('border-left-width'))+parseFloat(mooView.getStyle('border-right-width'))) ;
		// alert("mooView_data[i]['stage_width'] = "+mooView_data[i]['stage_width']) ;
		mooView_data[i]['stage_height'] = mooView_data[i]['image_old_height']*(mooView_data[i]['stage_width']/mooView_data[i]['image_old_width']) ;
		// Set required styles
		mooView.setStyles({'overflow':'hidden','position':'relative','height':mooView_data[i]['stage_height']}) ;
		$('mooView_'+i).setStyles({'width':mooView_data[i]['image_old_width']*mooView_data[i]['count'],'position':'absolute'}) ;
		// Build the navigation and set image styles
		img_nav = '' ;
		img_count = 0 ;
		mooView.getElements('img').each(function(mooView_img){
			// set image styles
			mooView_img.setStyles({'float':'left','height':mooView_data[i]['stage_height'],'width':mooView_data[i]['stage_width']}) ;
			// Build the navigation
			if(mooViewNav_mode=='html'&&mooViewNav_html!=''){
				this_link_html = mooViewNav_html ;
			} else if(mooViewNav_mode=='title'&&mooView_img.title!=''){
				this_link_html = mooView_img.title ;
			} else {
				this_link_html = img_count + 1 ;
			}
			if(img_count==0){
				this_selected = 'class="selected"' ;
			} else {
				this_selected = '' ;
			}
			if(mooView_img.title!=''){
				temp_img_title = mooView_img.title ;
			} else {
				if(mooView_blankTitles==false){
					temp_img_title = "Image "+(img_count+1) ;
					mooView_img.title = temp_img_title ;
				} else {
					temp_img_title = "" ;
					mooView_img.title = temp_img_title ;
				}
			}
			img_nav = img_nav + '<span title="'+temp_img_title+'" id="mooViewNav_'+i+'_'+img_count+'" '+this_selected+'>'+this_link_html+'</span>' ;
			mooView_data[i][img_count] = temp_img_title ;
			// advance the counter
			img_count++;
		});
		$('mooViewNav_'+i).innerHTML = img_nav ;
		// Grab the normal and hover colours from the generated menu for the later functions
		mooView_data[i]['color'] = $('mooViewNav_'+i).getStyle('color') ;
		mooView_data[i]['selected_color'] = $$('#mooViewNav_'+i+' .selected').getStyle('color') ;
		// Set styles
		$('mooViewNav_'+i).setStyles({'position':'absolute','float':'right','margin-top':(mooView_data[i]['stage_height']-mooNavHeight),'width':mooView_data[i]['stage_width'],'line-height':mooNavHeight,'z-index':mooViewZindexCore+1}) ;
		$('mooViewTitle_'+i).setStyles({'position':'absolute','margin-top':mooView_data[i]['stage_height'],'line-height':mooNavHeight,'z-index':mooViewZindexCore}) ;
		// Set global quick nav styles and individual styles
		$$('.mooViewQuickNav_'+i).setStyles({'position':'absolute','cursor':'pointer'}) ;
		mooView_data[i]['quicknav_width'] = parseFloat($$('.mooViewQuickNav_'+i).getStyle('width')) ;
		mooView_data[i]['quicknav_height'] = parseFloat($$('.mooViewQuickNav_'+i).getStyle('height')) ;

		// options for left/right next/previous links
		if(leftRightNav==true){
			$('mooViewQuickNavRight_'+i).setStyles({'margin-left':mooView_data[i]['stage_width']-mooView_data[i]['quicknav_width'],'margin-top':(mooView_data[i]['stage_height']/2)-(mooView_data[i]['quicknav_height']/2)}) ;
			$('mooViewQuickNavLeft_'+i).setStyles({'margin-top':(mooView_data[i]['stage_height']/2)-(mooView_data[i]['quicknav_height']/2)}) ;
			// Make invisible for the hover function but only if requested.
			if(mooViewFadeOutQuickNav==true){
				$$('.mooViewQuickNav').setStyles({'opacity':mooViewInvisible}) ;
				// Set the mouseover function on the main div to show the links on hover
				mooView.addEvent('mouseenter', function(){
					mooView.getElements('.mooViewQuickNav').morph({'opacity':mooViewVisible}) ;
				});
				mooView.addEvent('mouseleave', function(){
					mooView.getElements('.mooViewQuickNav').morph({'opacity':mooViewInvisible}) ;
				});
			}
			// Add the quicknav links
			$('mooViewQuickNavRight_'+i).addEvent('click', function(){
				imgQuickNav(parseFloat(this.id.split("_")[1]),'right',true) ;
			});
			$('mooViewQuickNavLeft_'+i).addEvent('click', function(){
				imgQuickNav(parseFloat(this.id.split("_")[1]),'left',true) ;
			});
		}

		// Loop the images
		$('mooViewNav_'+i).getElements('span').each(function(img_nav){
			// Individual button styles
			img_nav.setStyles({'display':'inline-block'}) ;
			img_nav.addEvent('click', function(){
				mooView_data[parseFloat(this.id.split("_")[1])]['animation_status'] = 'stop' ;
				moveToMe(parseFloat(this.id.split("_")[1]),parseFloat(this.id.split("_")[2]),mooView_data[this.id.split("_")[1]][this.id.split("_")[2]]) ;
			});
		});
		animation_handler(i,'logic') ;
		// increment counter for multi-embed
		i++ ;
	});
});