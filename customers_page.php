<!doctype html>
<html>
<head>
<style>
	* {
	  box-sizing: border-box;
	  margin: 0;
	  flex: 1;
	}
	/* Create two columns that floats next to each other */
	.column1 {
	  float: left;
	  width: 18%;
	  padding: 5px;  
	  height: 97vh;
	  overflow-y: auto;
	  border: 0px solid #000000;
	}
	.column2 {
	  float: left;
	  width: 82%;
	  padding: 5px;
	  height: 97vh;
	  overflow-y: auto;
	  border: 0px solid #000000;
	}
</style>

<link rel="stylesheet" href="css/ex.css" type="text/css" />
<style type="text/css">
	table.dataTable {
		border-collapse: collapse;
	}
	table.dataTable td, table.dataTable th {
		padding: 6px 8px;
		border:1px solid #000;
	}
	table.dataTable th {
		text-align:left;
	}
	table.dataTable td.num {
		text-align:right;
	}	
	table.dataTable td.foot {
		text-align: center;
	}
</style>

<?php 
  setlocale(LC_MONETARY, 'en_US');
  include 'includes/code_php.php';
?>

<div class="column1" style="background-color:#E0FFFF;">	
	<h2><center><u>Customers Table</u></center></h2><br><br>
	<p>Back to <a href="prjmgt_home.html">PM Home</a></p>
	<br><br>	
	<button type="submit" onclick='location.href="?DisplayAll=1"'>Display All</button>
	<button type="submit" onclick='location.href="?ClearAll=1"' style="margin:6px;">Clear</button>
	<br>
	<br>
	<form name="udaForm" action="" method="post">	
		<hr><br><br>
		<input type="submit" name="update_id" value="Update Cust Id">
		<input type="submit" name="add_id" value="Add Cust Id" style="margin:7px;">	
		<input type="submit" name="delete_id" value="Delete Cust Id" style="margin:7px;">
		<br>
		<input type="submit" name="get_id" value="Customer Id">
		:<input type="text" name="custid" id="custid" size="8" style="margin:5px;"><br>
		Prj Id :<input type="text" name="prjid" id="prjid" size="25" style="margin:5px;"><br>
		Name :<input type="text" name="custname" id="custname" size="25" style="margin:5px;"><br>
		Address :<input type="text" name="address" id="address" size="25" style="margin:5px;"><br>
		Phone :<input type="text" name="phone_number" id="phone_number" style="margin:5px;"><br>
		Email :<input type="text" name="email" id="email" style="margin:5px;"><br>
		Prj Location :<input type="text" name="prj_location" id="prj_location" size="25" style="margin:5px;"><br>
	</form>	
	<p></p>

</div>

<div class="column2" style="background-color:#FFFFFF;">	

<?php
  // Project table field names
  $tHead = array("cust_id","prj_id","name","address","phone_number","email","prj_location");
  // Project table 1 record data (as strings)
  $tData = array("","","","","","","");

require('includes/html_table.class.php');
if($_GET){
    if(isset($_GET['DisplayAll'])){
        DisplayAll();
    }
    if(isset($_GET['ClearAll'])){
        ClearAll();
    }	
}

if($_POST){
    if(isset($_POST['update_id'])){
		$nid = $_POST["custid"];
		get_customer_form_data();
		update_customer_data($nid);
		put_customer_form_data();
    }
    if(isset($_POST['add_id'])){ 
		$nid = $_POST["custid"];
		get_customer_form_data();		
		add_customer_data($nid);
		put_customer_form_data();
    }		
    if(isset($_POST['delete_id'])){ 
		$nid = $_POST["custid"];
		get_customer_form_data();		
		delete_customer_data($nid);
		//put_customer_form_data();  // The record is deleted, so leave form inputs blank.
    }			
    if(isset($_POST['get_id'])){
		$nid = $_POST["custid"];
		get_customer_data($nid);
    }
}


function get_customer_form_data() {
//-------------------------------------------------------------------
	global $tData;
	$tData[0] = $_POST["custid"];
	$tData[1] = $_POST["prjid"];
	$tData[2] = $_POST["custname"];
	$tData[3] = $_POST["address"];
	$tData[4] = $_POST["phone_number"];
	$tData[5] = $_POST["email"];
	$tData[6] = $_POST["prj_location"];
}

function put_customer_form_data() {
//-------------------------------------------------------------------
	global $tData;
	// cust_id, prj_id, name, address, phone_number, email, prj_location
	//----------------------------------------------------------
	$str = "document.getElementById('custid').value = '" . $tData[0] . "';";
	echo "<script type='text/javascript'>";
	echo ($str);
	echo "</script>";
	$str = "document.getElementById('prjid').value = '" . $tData[1] . "';";
	echo "<script type='text/javascript'>";
	echo ($str);
	echo "</script>";	
	$str = "document.getElementById('custname').value = '" . $tData[2] . "';";
	echo "<script type='text/javascript'>";
	echo ($str);
	echo "</script>";
	$str = "document.getElementById('address').value = '" . $tData[3] . "';";
	echo "<script type='text/javascript'>";
	echo ($str);
	echo "</script>";
	$str = "document.getElementById('phone_number').value = '" . $tData[4] . "';";
	echo "<script type='text/javascript'>";
	echo ($str);
	echo "</script>";
	$str = "document.getElementById('email').value = '" . $tData[5] . "';";
	echo "<script type='text/javascript'>";
	echo ($str);
	echo "</script>";
	$str = "document.getElementById('prj_location').value = '" . $tData[6] . "';";
	echo "<script type='text/javascript'>";
	echo ($str);
	echo "</script>";
}

function get_customer_data($nid) {
//-------------------------------------------------------------------	
	global $tHead, $tData;
	$j = 0;
	$nColumns = 7;	
	$tbl = new HTML_Table('', 'dataTable');	
	$conn = get_db_connection();	
	
	// Run query to get record for customer id = $nid
	$qstr = "SELECT * FROM customers where cust_id =" . $nid;
	$res1 = mysqli_query($conn, $qstr);	
	if (!$res1) {
		printf("<br>Error: Check that customer id = %s exist.<br><br>", $nid);
		die("Error description: " . mysqli_error($conn));		
	}
	else {
		$row1 = mysqli_fetch_assoc($res1);
	}
	// Store current record data to local and global variables.
	$tData[0] = $row1['cust_id'];
	$tData[1] = $row1['prj_id'];
	$tData[2] = $row1['name'];
	$tData[3] = $row1['address'];
	$tData[4] = $row1['phone_number'];
	$tData[5] = $row1['email'];
	$tData[6] = $row1['prj_location'];

	// Copy the record data to the text boxes.
	put_customer_form_data();
	
	// Close the connection
	mysqli_close($conn);
}

function update_customer_data($nid) {
//-------------------------------------------------------------------	
	global $tData;	
	$nColumns = 7;	
	$conn = get_db_connection();
	
	// Run query to update the record for customer id = $nid
	// Example;
	// update customers set prj_id='103', name='Orion Rocket', address='JSC Houston TX', phone_number='2812561035', 
	//   email='orion@nasa.gov', prj_location='MSFC AL', where cust_id=201;
	$qstr =  "UPDATE customers SET prj_id='" . $tData[1] . "'";
	$qstr = $qstr . ", name='" . $tData[2] . "'";
	$qstr = $qstr . ", address='" . $tData[3] . "'";
	$qstr = $qstr . ", phone_number='" . $tData[4] . "'";
	$qstr = $qstr . ", email='" . $tData[5] . "'";
	$qstr = $qstr . ", prj_location='" . $tData[6] . "'";
	$qstr = $qstr . " where cust_id='" . $nid . "';";	
	$res1 = mysqli_query($conn, $qstr);	
	if (!$res1) {		
		printf("<br>Error: Check the record data for errors.<br><br>");		
		mysqli_close($conn);
		die("Error description: " . mysqli_error($conn));
	}
	else {
		printf("<br><br>Customer id = %s was updated.<br>", $nid);
	}
	
	// Close the connection
	mysqli_close($conn);
}

function add_customer_data($nid) {
//-------------------------------------------------------------------	
	global $tData;	
	$nColumns = 7;	
	$conn = get_db_connection();
	
	// Run query to insert (add) the record for project id = $nid
	// Example;
	// INSERT INTO customers VALUES 
	// ('10, '201', 'JSC NASA', 'Houston, TX', '2812561035', 'orion@nasa.gov', 'SSC Mississippi');
	$qstr =  "INSERT INTO customers VALUES(";
	$qstr = $qstr . "'" . $tData[0] . "'";    // cust_id
	$qstr = $qstr . ", '" . $tData[1] . "'";  // prj_id
	$qstr = $qstr . ", '" . $tData[2] . "'";  // name
	$qstr = $qstr . ", '" . $tData[3] . "'";  // address
	$qstr = $qstr . ", '" . $tData[4] . "'";  // phone_number
	$qstr = $qstr . ", '" . $tData[5] . "'";  // email
	$qstr = $qstr . ", '" . $tData[6]. "');"; // prj_location	
	$res1 = mysqli_query($conn, $qstr);	
	if (!$res1) {
		printf("<br>sql = %s", $qstr);
		printf("<br>Error: Check the record data for errors.<br><br>");		
		mysqli_close($conn);		
		die("Error description: " . mysqli_error($conn));
		put_customer_form_data();
	}
	else {
		printf("<br><br>Customner id = %s was added.<br>", $nid);
	}
	
	// Close the connection
	mysqli_close($conn);
}

function delete_customer_data($nid) {
//-------------------------------------------------------------------	
	$conn = get_db_connection();	
	
	// Run query to delete record for customer id = $nid
	$qstr = "DELETE FROM customers where cust_id =" . $nid;
	$res1 = mysqli_query($conn, $qstr);	
	if (!$res1) {
		printf("<br>Error: Check the record data for errors.<br><br>", $nid);
		mysqli_close($conn);
		die("Error description: " . mysqli_error($conn));		
	}
	else {
		printf("<br><br>Customer id = %s was deleted.<br>", $nid);		
	}
	
	// Close the connection
	mysqli_close($conn);
}

function DisplayAll() {
//-------------------------------------------------------------------	
	global $tHead;
	$j = 0;
	$nColumns = 7;	
	$tbl = new HTML_Table('', 'dataTable');	
	$conn = get_db_connection();
	
	//Run the Select query
	printf("The data in the Customers table:<br><br>");
	$res = mysqli_query($conn, 'SELECT * FROM customers order by cust_id asc');
	
	// Make the display table header.
	$tbl->addRow();
	$tbl->addCell($tHead[0], 'first', 'header');
	for ($j = 1; $j < $nColumns; $j++) {
		$tbl->addCell($tHead[$j], '', 'header');	  
	}
	
	while ($row = mysqli_fetch_assoc($res)) {	
	   $tbl->addRow();
	   for ($j = 0; $j < $nColumns; $j++) {
			$tbl->addCell($row[$tHead[$j]]);
		}	   
	}
	// Show the table of current records.
	echo $tbl->display();

	//Close the connection
	mysqli_close($conn);      
}

function ClearAll() {
//-------------------------------------------------------------------	
	$str = "document.clear();";
	echo "<script type='text/javascript'>";
	echo ($str);
	echo "</script>";	
}

?>

</dev>
</body>
</head>
</html>
