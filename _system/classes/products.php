<?php

	class products {

		private $product_id = false ;
		private $product_category_id = false ;
		private $dataList = array() ;
		private $is_active = false ;

		// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 

		public function getProduct(){
			$returnval = false ;
			// Open connection
			$conn = new ConnectionSimple ;
			$conn->connect(DM_DB_NAME) ;
			// sql
			$sql = "SELECT * FROM products WHERE id='".$this->product_id."' LIMIT 0,1 ; " ;
			$result = mysql_query($sql) or die(mysql_error()) ;
			if(mysql_num_rows($result)>0){
				$returnval = mysql_fetch_array($result) ;
			}
			// Close
			$conn->disconnect(DM_DB_NAME) ;
			// return
			return $returnval ;
		}

		public function getProductList($is_count=false){
			// blank the array
			$this->dataList = array() ;
			// Open connection
			$conn = new ConnectionSimple ;
			$conn->connect(DM_DB_NAME) ;
			// sql
			if($is_count==false){
				$sql = "SELECT * " ;
			} else {
				$sql = "SELECT COUNT(1) as product_count " ;
			}
			$sql.= "FROM products " ;
			$sql.= "LEFT JOIN products_to_categories ON products.id = products_to_categories.product_id " ;
			$sql.= "WHERE is_active" ;
			if($is_active===1||$is_active==='1'){
				$sql.= "=1 " ;
			} elseif($is_active===0||$is_active==='0'){
				$sql.= "=0 " ;
			} else {
				$sql.= ">-1 " ;
			}
			if($this->product_category_id!=false){
				$sql.= "AND products_to_categories.category_id = '".$this->product_category_id."' " ;
			}
			$sql.= "ORDER BY list_order ASC ; " ;
			// echo '<p class="notice" >'.$sql.'</p>' ;
			$result = mysql_query($sql) or die(mysql_error()) ;
			// create array
			while($row = mysql_fetch_array($result)){
				array_push($this->dataList, $row);
			}
			// Close
			$conn->disconnect(DM_DB_NAME) ;
			// return
			return $this->dataList ;
		}

		public function getCategoryList($is_count=false){
			// blank the array
			$this->dataList = array() ;
			// Open connection
			$conn = new ConnectionSimple ;
			$conn->connect(DM_DB_NAME) ;
			// sql
			if($is_count==false){
				$sql = "SELECT * " ;
			} else {
				$sql = "SELECT COUNT(1) as category_count " ;
			}
			$sql.= "FROM product_categories WHERE is_active=1 ORDER BY list_order ASC ; " ;
			$result = mysql_query($sql) or die(mysql_error()) ;
			// create array
			while($row = mysql_fetch_array($result)){
				array_push($this->dataList, $row);
			}
			// Close
			$conn->disconnect(DM_DB_NAME) ;
			// return
			return $dataList ;
		}

		public function fixOrder($id,$newOrder,$type='category'){
			if($type=='product'){
				$sql_table='products' ;
			} else {
				$sql_table='product_categories' ;
			}
			// Open
			$conn = new ConnectionSimple ;
			$conn->connect(DM_DB_NAME) ;
			// sql
			$sql = "UPDATE ".$sql_table." SET list_order='".$newOrder."' WHERE id='".$id."' ; " ;
			mysql_query($sql) or die( mysql_error()) ;
			// Close
			$conn->disconnect(DM_DB_NAME) ;
		}

		// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 

		public function setProductId($var){
			if($var!=''&&is_numeric($var)&&$var>0){
				$this->product_id = $var ;
			}
		}

		public function setProductCategoryId($var){
			if($var!=''&&is_numeric($var)&&$var>0){
				$this->product_category_id = $var ;
			}
		}

	}

?>