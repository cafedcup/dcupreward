<?php
	class Database{			
		private static $instance;
		private $dbLink;
		
		private function __construct(){ $this->dbConnect(); }
		public function __destruct(){ $this->close_connection(); }
		private function __clone(){ }
		public static function getInstance(){
			if(!isset(self::$instance)){ self::$instance = new Database(); }
			return self::$instance;
		}//end singleton
		
		public function dbConnect(){
			require_once('dbConfig.php');
			if(!$this->dbLink = mysql_connect($dbConfig['dbServer'].':'.$dbConfig['dbPort'],$dbConfig['dbUser'],$dbConfig['dbPass'])){
				echo '<pre> Could not create connection</pre>';
				exit();     
			}
			$dbSelect = mysql_select_db($dbConfig['dbName'],$this->dbLink);
			
			if(!$dbSelect){ echo '<pre> Could not connect to database</pre>'; exit(); }
			self::query('SET NAMES "utf8"');
			unset($dbSelect);
		}   
		/*
		public function dbConnect(){
			require_once('dbConfig.php');
			$this->dbLink = pg_connect($dbConfig['dbServer'])
    or die('Could not connect: ' . pg_last_error());
		}
		*/
		public function insert_data($dbTable,$fieldVal){
			$fieldStr = $dataStr = '';
			foreach($fieldVal as $key=>$val){				
				$key = htmlspecialchars(trim($key),ENT_QUOTES,'UTF-8');
				$val = htmlspecialchars(trim($val),ENT_QUOTES,'UTF-8');
				$fieldStr .= ''.$key.',';
				$dataStr .='"'.$val.'",';
			}//end foreach
			$fieldStr = substr($fieldStr,0,-1);
			$dataStr = substr($dataStr,0,-1);			
			self::query('INSERT INTO '.$dbTable.'('.$fieldStr.') VALUES('.$dataStr.')');
			unset($fieldStr,$dataStr);
			return mysql_affected_rows($this->dbLink);
		}
		/*
		public function insert_data(($dbTable,$fieldVal)){
			$result = pg_insert($this->dbLink,$dbTable,$fieldVal) or die('Query failed: ' . pg_last_error());
			pg_free_result($result);
		}
		*/
		public function insertAndGetId($dbTable,$fieldVal){
			if(self::insert_data($dbTable,$fieldVal)){ return self::getLastInsertId(); }
			return 0;
		}
		
		public function getLastInsertId(){
			$result = self::query('SELECT LAST_INSERT_ID()');
			$newID = self::fetch_row($result);
			return $newID[0];
		}
		
		public function update_data($dbTable,$fieldVal,$condition){
			$queryStr = '';
			foreach($fieldVal as $key=>$val){
				$key = htmlspecialchars(trim($key),ENT_QUOTES,'UTF-8');	
				$val = htmlspecialchars(trim($val),ENT_QUOTES,'UTF-8');
				$queryStr .= $key.'="'.$val.'",';
			}//end foreach
			$queryStr = substr($queryStr,0,-1);
			self::query('UPDATE '.$dbTable.' SET '.$queryStr.' WHERE '.$condition);
			unset($queryStr);	  	
			return mysql_affected_rows($this->dbLink);
		}   
		
		public function delete_data($dbTable,$condition){			
			return self::query('DELETE FROM '.$dbTable.' WHERE '.$condition);
		}
		
		// get number of rows
		public function getResultRowCount($dbResult){ return mysql_num_rows($dbResult); }
		//get number of fields
		public function getResultFieldCount($dbResult){ return mysql_num_fields($dbResult); }
		//return numeric index array
		public function fetch_row($dbResult){ return mysql_fetch_row($dbResult); }
		//return both numeric index array and associate array
		public function fetch_array($dbResult){ return mysql_fetch_array($dbResult); }
		//return associate array
		public function fetch_associate($dbResult){ return mysql_fetch_assoc($dbResult); }
		public function query($queryStr){
			$result = mysql_query($queryStr,$this->dbLink);	   
			if($result){ return $result; }
			else{
				echo '<pre> Query Error: ',$queryStr,'</pre>';
				echo '<pre>',mysql_error(),'</pre>';
				exit();
			}		  
		}   

		public function optimize_table($dbTable){ return self::query('OPTIMIZE TABLE '.$dbTable); }
		public function free_result($dbResult){ return mysql_free_result($dbResult); }
		
		public function close_connection(){			
			mysql_close($this->dbLink);
			$this->dbLink = false;		
		}
		/*		
		public function get_admin_pw($user_name){
		    $query = "SELECT admin_pw FROM dcup_admin_mst WHERE admin_name = '" . $user_name . "'";
		    $result = pg_query($this->dbLink,$query) or die('Query failed: ' . pg_last_error());
		    while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
		        foreach ($line as $col_value) {        
		            $admin_pw = $col_value;
		        }
		    }
		    // Free resultset
		    self::pg_free_result($result);
		    return $admin_pw;
		}

		public function is_admin_exist($user_name){
		    $query = "SELECT * FROM dcup_admin_mst WHERE admin_name = '" . $user_name . "'";
		    $result = pg_query(this->dbLink,$query) or die('Query failed: ' . pg_last_error());
		    while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
		        foreach ($line as $col_value) {        
		            $admin = $col_value;
		        }
		    }
		    // Free resultset
		    pg_free_result($result);
		    return $admin != '';
		}
		*/
	}//end class
?>