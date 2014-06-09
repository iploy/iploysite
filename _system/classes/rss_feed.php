<?php

class rssFeed {

	private $url = '' ;
	private $items = 5 ;

	function HTTPRequest($url){
		$ch = curl_init(); 
		curl_setopt($ch,CURLOPT_URL,$url); 
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1); 
		//curl_setopt($ch,CURLOPT_MUTE,1); 
		curl_setopt($ch,CURLOPT_USERAGENT,$_SERVER["HTTP_USER_AGENT"]); 
		curl_setopt($ch, CURLOPT_TIMEOUT, 10); //times out after 10 
		curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1); 
		$data = curl_exec ($ch); 
		curl_close ($ch); 
		return $data; 
	}
    

	// that function
	public function getFeed(){
		$returnVar = array() ;
		$returnVar['success'] = false ;
		$returnVar['status'] = 'The feed getter was not started due to a misconfiguration' ;
		$returnVar['data'] = array() ;
		// let's roll
		if($this->url!=''){
			//if($rawFeed = file_get_contents($this->url)){
			if($rawFeed = $this->HTTPRequest($this->url)){
				if($xml = new SimpleXmlElement($rawFeed)){
					// channel metadata
					$returnVar['data']['channel'] = array();
					$returnVar['data']['channel']['title']       = $xml->channel->title;
					$returnVar['data']['channel']['link']        = $xml->channel->link;
					$returnVar['data']['channel']['description'] = $xml->channel->description;
					$returnVar['data']['channel']['pubDate']     = $xml->pubDate;
					$returnVar['data']['channel']['timestamp']   = strtotime($xml->pubDate);
					$returnVar['data']['channel']['generator']   = $xml->generator;
					$returnVar['data']['channel']['language']    = $xml->language;
					// extract the articles
					$returnVar['data']['items'] = array();
					foreach ($xml->channel->item as $xmlItem){
						if(sizeof($returnVar['data']['items'])<$this->items){
							$item = array() ;
							$item['channel'] = $blog;
							$item['title'] = $xmlItem->title;
							$item['link'] = $xmlItem->link;
							$item['comments'] = $xmlItem->comments;
							$item['pubDate'] = $xmlItem->pubDate;
							$item['timestamp'] = strtotime($xmlItem->pubDate);
							$item['description'] = (string) trim($xmlItem->description);
							$item['isPermaLink'] = $xmlItem->guid['isPermaLink'];
							array_push($returnVar['data']['items'],$item) ;
						}
					}
					$returnVar['success'] = true ;
					$returnVar['status'] = 'The feed was returned successfully' ;
				} else {
					$returnVar['status'] = 'SimpleXml could not initialise - Maybe it is missing?' ;
				}
			} else {
				$returnVar['status'] = 'Remote URL file-access is disabled in the server configuration' ;
			}
		}
		// return
		return $returnVar ;
	}

	// get / set
	public function setUrl($var){
		$this->url = $var ;
	}
	public function setItems($var){
		if(is_numeric($var)){
			$this->items = $var ;
		}
	}

}

?>
