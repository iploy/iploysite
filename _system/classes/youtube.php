<?php

	// attributes available in each video and list
	// url				- video url
	// videoId 			- video ID parsed from URL
	// title 			- video title
	// description		- video description
	// category			- category
	// keywords			- keywords
	// thumb			- thumbnail url
	// thumbWidth		- thumbnail url
	// thumbHeight		- thumbnail url
	// lengthSecs		- length of the video in seconds
	// lengthMins		- length of the video in minutes and seconds
	// viewCount 		- number of views
	// == Video embed only
	// embedCode		- Video embed code using defined options
	// author			- Video uploader

	class youtube {
		// vars
		private $userName = false ;
		private $userId = false ;
		private $videoId = false ;
		private $embedWidth = 420 ;
		private $embedHeight = 315 ;
		private $showRelatedVideos = 0 ;
		private $autoPlay = 0 ;
		private $useFlashEmbed = false ;
		private $flashEmbedWmode = 'window' ;
		private $forcedWidth = false ;
		private $dataList = array() ;
		private $pathFix = '' ;
		// functions
		public function getVideoList(){
			$returnVal = array() ;
			$returnVal['error'] = false ;
			if($this->userName!=false){
				$this->dataList = array() ;
				// http://gdata.youtube.com/feeds/api/users/spambanjo/uploads
				// $videoFeedUrl = 'http://gdata.youtube.com/feeds/api/users/'.$this->userName.'/uploads' ;
				$videoFeedUrl = $this->pathFix.'userfiles/youtube_cache/'.$this->userId.'/uploads.xml' ;
				// libxml_use_internal_errors(true);
				//echo $videoFeedUrl.'<br />' ;
				$sxml = simplexml_load_file($videoFeedUrl) ;
				if($sxml){
					if(sizeof($sxml)>0){
						foreach ($sxml->entry as $entry){
							$thisVideo = array() ;
							// get nodes in media: namespace for media information
							$media = $entry->children('http://search.yahoo.com/mrss/') ;
							$thisVideo['title'] = $media->group->title ;
							$thisVideo['description'] = $media->group->description ;
							$thisVideo['category'] = $media->group->category ;
							$thisVideo['keywords'] = $media->group->keywords ;
							// get video player URL
							$attrs = $media->group->player->attributes() ;
							$thisVideo['url'] = $attrs['url'] ;
							$thisVideo['videoId'] = $this->videoIdFromUrl($thisVideo['url']) ;
							// get video thumbnail
							$attrs = $media->group->thumbnail[0]->attributes() ;
							// $thisVideo['time'] = $attrs['time'] ; 
							$thisVideo['thumb'] = $attrs['url'] ; 
							if($this->forcedWidth!=false){
								$thisVideo['thumbWidth'] = $this->forcedWidth ; 
								$thisVideo['thumbHeight'] = $this->calculateNewHeight($attrs['width'],$attrs['height'],$this->forcedWidth) ;  ;
							} else {
								$thisVideo['thumbWidth'] = $attrs['width'] ; 
								$thisVideo['thumbHeight'] = $attrs['height'] ;
							}
							// get <yt:duration> node for video length
							$yt = $media->children('http://gdata.youtube.com/schemas/2007') ;
							$attrs = $yt->duration->attributes() ;
							$thisVideo['lengthSecs'] = $attrs['seconds'] ; 
							$mins = floor($attrs['seconds']/60) ;
							$secs = $attrs['seconds']-($mins*60) ; 
							$thisVideo['lengthMins'] = $mins.':'.($secs<10 ? '0' : '').$secs ; 
							// get <yt:stats> node for viewer statistics
							$yt = $entry->children('http://gdata.youtube.com/schemas/2007') ;
							$attrs = $yt->statistics->attributes() ;
							$thisVideo['viewCount'] = $attrs['viewCount'] ; 
							// return or whatever
							array_push($this->dataList, $thisVideo);
						}
						if(sizeof($this->dataList)>0){
							return $this->dataList ;
						} else {
							$returnVal['error'] = "No videos were found in the user's feed" ;
							return $returnVal ;
						}
					}
				} else {
					$returnVal['error'] = "The specified Youtube user does not exist or the data feed was damaged" ;
					return $returnVal ;
				}
			} else {
				$returnVal['error'] = "No username supplied for Youtube feed" ;
				return $returnVal ;
			}
		}
		// Parse Video ID from url function
		public function videoIdFromUrl($url){
			$urlSplit = explode("v=",$url) ;
			$urlSplit = explode("&",$urlSplit[1]) ;
			return $urlSplit[0] ;
		}
		// get video information and embed code
		public function getVideoById(){
			$returnVal = array() ;
			if($this->videoId!=false&&$this->userName!=false){
				//libxml_use_internal_errors(true);
				//$videoFeedUrl = 'http://gdata.youtube.com/feeds/api/videos/'.$this->videoId ;
				//@ $sxml = simplexml_load_file($videoFeedUrl);
				$videoFeedUrl = $this->pathFix.'userfiles/youtube_cache/'.$this->userId.'/videos/'.$this->videoId.'.xml' ;
				$sxml = simplexml_load_file($videoFeedUrl);
				if($sxml){
					$thisVideo = array() ;
					$thisVideo['lastUpdated'] = $sxml->updated ;
					$thisVideo['published'] = $sxml->published ;
					$thisVideo['title'] = $sxml->title ;
					$thisVideo['description'] = $sxml->content ;
					$thisVideo['author'] = $sxml->author->name ;
					$media = $sxml->children('http://search.yahoo.com/mrss/') ;
					$attrs = $media->group->player->attributes() ;
					$thisVideo['url'] = $attrs['url'] ;
					$attrs = $media->group->thumbnail[0]->attributes() ;
					$thisVideo['thumb'] = $attrs['url'] ;
					if($this->forcedWidth!=false){
						$thisVideo['thumbWidth'] = $this->forcedWidth ; 
						$thisVideo['thumbHeight'] = $this->calculateNewHeight($attrs['width'],$attrs['height'],$this->forcedWidth) ;  ;
					} else {
						$thisVideo['thumbWidth'] = $attrs['width'] ; 
						$thisVideo['thumbHeight'] = $attrs['height'] ;
					}
					$yt = $media->children('http://gdata.youtube.com/schemas/2007') ;
					$attrs = $yt->duration->attributes() ;
					$thisVideo['lengthSecs'] = $attrs['seconds'] ;
					$mins = floor($attrs['seconds']/60) ;
					$secs = $attrs['seconds']-($mins*60) ;
					$thisVideo['lengthMins'] = $mins.':'.($secs<10 ? '0' : '').$secs ;  
					// build the querysting extra
					$videoOptionsQuerystring = 'rel='.$this->showRelatedVideos.'&amp;autoplay='.$this->autoPlay ;
					if($this->useFlashEmbed==false){
						$thisVideo['embedCode'] = '<iframe width="'.$this->embedWidth.'" height="'.$this->embedHeight.'" src="http://www.youtube.com/embed/'.$this->videoId.'?'.$videoOptionsQuerystring.'" frameborder="0" allowfullscreen ></iframe>' ;
					} else {
						$thisVideo['embedCode'] = '<object width="'.$this->embedWidth.'" height="'.$this->embedHeight.'"><param name="movie" value="http://www.youtube.com/v/'.$this->videoId.'?version=3&amp;hl=en_GB&amp;'.$videoOptionsQuerystring.'"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always" ></param><param name="wmode" value="'.$this->flashEmbedWmode.'" /><embed src="http://www.youtube.com/v/'.$this->videoId.'?version=3&amp;hl=en_GB&amp;'.$videoOptionsQuerystring.'" type="application/x-shockwave-flash" width="'.$this->embedWidth.'" height="'.$this->embedHeight.'" allowscriptaccess="always" allowfullscreen="true" wmode="'.$this->flashEmbedWmode.'" ></embed></object>' ;
					}
					return $thisVideo ;
				} else {
					$returnVal['error'] = 'Invalid video ID or the video does not exists' ;
					return $returnVal ;
				}
			} else {
				$returnVal['error'] = 'No Video ID supplied for Youtube embed' ;
				return $returnVal ;
			}
		}
		//calculate new height from forced width
		public function calculateNewHeight($origW,$origH,$newW){
			return $origH*($newW/$origW) ;
		}
		// Getters / Setters
		public function setUserName($var){
			$this->userName = $var ;
		}
		public function setUserId($var){
			$this->userId = $var ;
		}
		public function setVideoId($var){
			$this->videoId = $var ;
		}
		public function setEmbedWidth($var){
			if(is_numeric($var)&&$var>0){
				$this->embedWidth = $var ;
			}
		}
		public function setEmbedHeight($var){
			if(is_numeric($var)&&$var>0){
				$this->embedHeight = $var ;
			}
		}
		public function setShowRelatedVideos($var){
			if($var===true){
				$this->showRelatedVideos = 1 ;
			} else {
				$this->showRelatedVideos = 0 ;
			}
		}
		public function setAutoPlay($var){
			if($var===true){
				$this->autoPlay = 1 ;
			} else {
				$this->autoPlay = 0 ;
			}
		}
		public function setUseFlashEmbed($var){
			if($var===true){
				$this->useFlashEmbed = true ;
			} else {
				$this->useFlashEmbed = false ;
			}
		}
		public function setFlashEmbedWmode($var){
			if(strtolower($var)=='transparent'){
				$this->flashEmbedWmode = 'transparent' ;
			} elseif(strtolower($var)=='opaque'){
				$this->flashEmbedWmode = 'opaque' ;
			} elseif(strtolower($var)=='direct'){
				$this->flashEmbedWmode = 'direct' ;
			} else {
				$this->flashEmbedWmode = 'window' ;
			}
		}
		public function setForcedWidth($var){
			if(is_numeric($var)&&$var>0){
				$this->forcedWidth = $var ;
			
			}
		}
		public function setPathFix($var){
			$this->pathFix = $var ;
		}
	}

?>
