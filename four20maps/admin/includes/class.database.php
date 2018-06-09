<?php
class DB {
	var $hostname 	= 'localhost';
	var $username 	= 'four20maps';
	var $password 	= 'induco123';
	var $db_name	= 'four20ma_storefinder';
	var $connection;
	var $errors		= array();
	var $queries	= array();
	var $query		= '';
	var $insert_id	= 0;
	var $debug		= 1;
	
	
	function DB($params=array()) {
		if(!empty($params)) {
			foreach($params as $k=>$v) {
				if(isset($this->{$k})) {
					$this->{$k} = $v;
				}
			}
		}
		
		if($this->hostname != '' && $this->username != '' && $this->db_name != '') {
			$this->connection = mysql_connect($this->hostname,$this->username,$this->password);
			if(!$this->connection) {
				$this->errors[] = 'Could not connect: '.mysql_error();
				return FALSE;
			}
			if(!mysql_select_db($this->db_name,$this->connection)) {
				$this->errors[] = 'Database error: '.mysql_error();
				return FALSE;
			}
		} else {
			$this->errors[] = 'Wrong database information';
			return FALSE;
		}
		
		if($this->debug && !empty($this->errors)) {
			$this->print_debug($this->errors);
		}
		
	return TRUE;
	}
	
	
	function query($sql) {
		$this->queries[] = $sql;
		$result = mysql_query($sql);
		if(!$result) {
			$this->errors[] = 'Invalid query: '.mysql_error();
		}
		if($result===TRUE) {
			$this->insert_id = mysql_insert_id();
		}
		
		if($this->debug && !empty($this->errors)) {
			$this->print_debug($this->errors);
			$this->print_debug($sql);
		}
		
	return $result;
	}
	
	function cat_query($sql) {
		$this->queries[] = $sql;
		$result = mysql_query($sql);
		if(!$result) {
			$this->errors[] = 'Invalid query: '.mysql_error();
		}
		if($result===TRUE) {
			return $this->insert_id = mysql_insert_id();
		}
		
		if($this->debug && !empty($this->errors)) {
			$this->print_debug($this->errors);
			$this->print_debug($sql);
		}
		
	return $result;
	}
	
	
	function get_rows($sql) {
		$result = $this->query($sql);
		$rows = array();
		if($result !== FALSE) {
			while($row = mysql_fetch_array($result,MYSQL_ASSOC)) {
				$rows[] = $row;
			}
		}
	return $rows;
	}
	
	
	function get_row($sql) {
		$result = $this->query($sql);
		$row = array();
		if($result !== FALSE) {
			$row = mysql_fetch_array($result,MYSQL_ASSOC);
		}
	return $row;
	}


	function escape($str) {
		return $str;
	}
	function cat_insert($table, $data) {

		foreach($data as $k=>$v) {
			$data[$k] = $this->escape($v);
		}

		if(!isset($data['created'])) {
			$data['created'] = date("Y-m-d H:i:s");
		}
		

		$fields = $this->get_sql_field_names($table);
		foreach($data as $k=>$v) {
			if(!in_array($k,$fields)) {
				unset($data[$k]);
			}
		}

		$keys 	= array_keys($data);
		$values = array_values($data);

		  $sql = "INSERT INTO ".$this->escape($table)." (".implode(',',$keys).") VALUES('".implode("','",$values)."')"; 
		
	return $this->cat_query($sql);
	}
	
	function insert($table, $data) {

		foreach($data as $k=>$v) {
			$data[$k] = $this->escape($v);
		}

		if(!isset($data['created'])) {
			$data['created'] = date("Y-m-d H:i:s");
		}
		

		$fields = $this->get_sql_field_names($table);
		foreach($data as $k=>$v) {
			if(!in_array($k,$fields)) {
				unset($data[$k]);
			}
		}

		$keys 	= array_keys($data);
		$values = array_values($data);

		  $sql = "INSERT INTO ".$this->escape($table)." (".implode(',',$keys).") VALUES('".implode("','",$values)."')"; 
		
	return $this->query($sql);
	}
	
	
	function update($table, $data, $id) {

		if(!isset($data['modified'])) {
			$data['modified'] = date("Y-m-d H:i:s");
		}

		$fields = $this->get_sql_field_names($table);
		$update = '';
		foreach($data as $k=>$v) {
			if(in_array($k,$fields)) {
				$update .= "`$k`='".$this->escape($v)."', ";
			}
		}

		$update = substr($update, 0, strrpos($update, ','));
		
		if (strpos($id, ',') !== false) {
			$sql = "UPDATE ".$this->escape($table)." SET ".$update." WHERE id IN (".$this->escape($id).")";
		}else{
			$sql = "UPDATE ".$this->escape($table)." SET ".$update." WHERE id=".$this->escape($id);
		}
		
    //     echo $sql;exit;
	return $this->query($sql);
	}
   function updateAllByKeyword($table, $data, $id) {

		if(!isset($data['modified'])) {
			$data['modified'] = date("Y-m-d H:i:s");
		}

		$fields = $this->get_sql_field_names($table);
		$update = '';
		foreach($data as $k=>$v) {
			if(in_array($k,$fields)) {
				$update .= "`$k`='".$this->escape($v)."', ";
			}
		}

		$update = substr($update, 0, strrpos($update, ','));
		
		 
			$sql = "UPDATE ".$this->escape($table)." SET ".$update." WHERE name like '%".$this->escape($id)."%'";
		 
		
    //     echo $sql;exit;
	return $this->query($sql);
	}
function updateAll($table, $data) {

		if(!isset($data['modified'])) {
			$data['modified'] = date("Y-m-d H:i:s");
		}

		$fields = $this->get_sql_field_names($table);
		$update = '';
		foreach($data as $k=>$v) {
			if(in_array($k,$fields)) {
				$update .= "`$k`='".$this->escape($v)."', ";
			}
		}

		$update = substr($update, 0, strrpos($update, ','));
		

		  $sql = "UPDATE ".$this->escape($table)." SET ".$update;
		 

	return $this->query($sql);
}

	function udpdateByCondtion($table, $updatecolumn,$updatevalue, $column='id',$columnvalue='id'){
		 $sql = "UPDATE ".$this->escape($table)." SET ".$updatecolumn."=".$this->escape($updatevalue)." WHERE $column=".$this->escape($columnvalue);
		return $this->query($sql);
	}
	function delete($table, $id,$column='id') {
		

		$sql = "DELETE FROM ".$this->escape($table)." WHERE $column=".$this->escape($id);

	return $this->query($sql);
	}

	function get_insert_id() {
		return $this->insert_id;
	}


	function get_sql_field_names($table) {
		$columns = array();
		$rows = $this->get_rows("SHOW COLUMNS FROM ".$table);
		foreach($rows as $k=>$v) {
			if(!in_array($v['Field'], $columns)) {
				$columns[] = $v['Field'];
			}
		}
	return $columns;
	}
	
	function dateRange($first,$last,$step = '+1 day',$format = 'Y/m/d' ) {

	$dates = array();
	$current = strtotime( $first );
	$last = strtotime( $last );

	while( $current <= $last ) {

		$dates[] = date( $format, $current );
		$current = strtotime( $step, $current );
	}

	return $dates;
}
	
	
	
	


	function print_debug($debug) {
		echo '<pre>';
		if(is_string($debug)) {
			echo $debug;
		} else {
			print_r($debug);
		}
		echo '</pre>';
	}
}