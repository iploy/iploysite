<?php

	if(IS_DEV==false){
		$isSSL = false ;
		$needsSSL = false ;
		if(strstr(SITE_DOMAIN,"https://")){
			$isSSL = true ;
		}
		foreach(explode(",",SSL_PAGES) as $sslString){
			if(strstr($_SERVER['REQUEST_URI'],$sslString)){
				$needsSSL = true ;
			}
		}
		if($isSSL==false&&$needsSSL==true){
			header('Location: '.str_replace("http://","https://",substr(SITE_DOMAIN,0,strlen(SITE_DOMAIN)-1).$_SERVER['REQUEST_URI'])) ;
			exit() ;
		}
		if($isSSL==true&&$needsSSL==false){
			header('Location: '.str_replace("https://","http://",substr(SITE_DOMAIN,0,strlen(SITE_DOMAIN)-1).$_SERVER['REQUEST_URI'])) ;
			exit() ;
		}
	}

?>