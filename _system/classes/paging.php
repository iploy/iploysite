<?php

	class paging {

		private $page_size = 0 ;
		private $list_total = 0 ;
		private $link_prefix = '?paging=true' ;

		public function generatePaging(){
			$return_str = '' ;
			$page_offset = 0 ;
			while(($page_offset*$this->page_size)<$this->list_total){
				$first_number = ($page_offset*$this->page_size)+1 ;
				$last_number = ($page_offset+1)*$this->page_size ;
				if($last_number>$this->list_total){
					$last_number = $this->list_total ;
				}
				if($page_offset!=0){
					$return_str.= ' &bull; ' ;
				}
				if($first_number!=$last_number){
					$paging_label = $first_number.'-'.$last_number ;
				} else {
					$paging_label = $last_number ;
				}
				if($_GET['page_offset']==$page_offset){
					$paging_prefix = '<b>' ;
					$paging_postfix = '</b>' ;
				} else {
					$paging_prefix = '' ;
					$paging_postfix = '' ;
				}
				$return_str.= $paging_prefix.'<a href="'.$this->link_prefix.'&amp;page_offset='.$page_offset.'" >'.$paging_label.'</a>'.$paging_postfix ;
				$page_offset ++ ;
			}
			return $return_str ;
		}

		// Getters / Setters
		public function setPageSize($var){
			$this->page_size = $var ;
		}
		public function setListTotal($var){
			$this->list_total = $var ;
		}
		public function setLinkPrefix($var){
			$this->link_prefix = $var ;
		}

	}

?>