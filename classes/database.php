<?php
Class DBSQL
{
   function DBSQL($db_host, $db_name, $db_user, $db_pass)
   {
      $conn=mysql_connect($db_host,$db_user,$db_pass);
	  if (!$conn){
		die('Could not connect: ' . mysql_error());
		}
	  mysql_select_db($db_name,$conn);
	  $this->CONN = $conn;
      return true;
   }

   function select($sql="")
   {
      if (empty($sql)) return false;
      if (empty($this->CONN)) return false;
      $conn = $this->CONN;
      $results = mysql_query($sql,$conn);
      if ((!$results) or (empty($results)))
      {
         return false;
      }
      $count = 0;
      $data = array();
      while ($row = mysql_fetch_array($results)) {
         $data[$count] = $row;
         $count++;
      }
      mysql_free_result($results);
      return $data;
   }

   function insert($sql="")
   {
      if (empty($sql)) return false;
      if (empty($this->CONN)) return false;

      $conn = $this->CONN;
      $results = mysql_query($sql,$conn);
      if (!$results) return false;
      $results = mysql_insert_id();
      return $results;
   }


   function update($sql="")
   {
      if(empty($sql)) return false;
      if(empty($this->CONN)) return false;

      $conn = $this->CONN;
      $result = mysql_query($sql,$conn);
      return $result;
   }


   function delete($sql="")
   {
      if(empty($sql)) return false;
      if(empty($this->CONN)) return false;

      $conn = $this->CONN;
      $result = mysql_query($sql,$conn);
      return $result;
   }

   function createtable($sql="")
   {
      if(empty($sql)) return false;
      if(empty($this->CONN)) return false;

      $conn = $this->CONN;
      $result = mysql_query($sql,$conn);
      return $result;
   }

   function droptable($sql="")
   {
      if(empty($sql)) return false;
      if(empty($this->CONN)) return false;

      $conn = $this->CONN;
      $result = mysql_query($sql,$conn);
      return $result;
   }

   function createindex($sql="")
   {
      if(empty($sql)) return false;
      if(empty($this->CONN)) return false;

      $conn = $this->CONN;
      $result = mysql_query($sql,$conn);
      return $result;
   }

   function dropindex($sql="")
   {
      if(empty($sql)) return false;
      if(empty($this->CONN)) return false;

      $conn = $this->CONN;
      $result = mysql_query($sql,$conn);
      return $result;
   }

}

Class CustomSQL extends DBSQL
{
   // the constructor
   function CustomSQL($db_host, $db_name, $db_user, $db_pass)
   {
      $this->DBSQL($db_host, $db_name, $db_user, $db_pass);
   }
   
   function updateSellerProducts($data=array()){
   	  if( count($data)>0 ){
	  	$sql = "INSERT INTO table (field_one, field_two) VALUES ";
		foreach( $data as $key=>$val ){
			$sql .= "('".$data[$key][0]."','".$data[$key][1]."')";
			if( isset( $data[$key+1][0] ) ){
				$sql .= ',';
			}
		}
      	$res = $this->update($sql);
	  }
   }
   
}

?>