<?php
/*
---------------------------------------------------------------------
  COSC 3380 Design of Database Systems.
  Source file for gernal php functions used in the project code.
  
  Group # 14
  4/16/2019
---------------------------------------------------------------------
*/

function get_db_connection() {
//-------------------------------------------------------------------	
	$host = 'localhost';
	$username = 'root';
	$password = '1234';
	$db_name = 'prjmgt';
	// Establishes the connection
	$dbconn = mysqli_init();
	mysqli_real_connect($dbconn, $host, $username, $password, $db_name, 3306);
	if (mysqli_connect_errno($dbconn)) {
		die('Failed to connect to MySQL: '.mysqli_connect_error());
	}		
	return $dbconn;	
}


?>