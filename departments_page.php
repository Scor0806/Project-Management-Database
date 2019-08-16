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
	<h2><center><u>Departments Table</u></center></h2><br><br>
	<p>Back to <a href="prjmgt_home.html">PM Home</a></p>
	<br><br>	
	<button type="submit" onclick='location.href="?DisplayAll=1"'>Display All</button>
	<button type="submit" onclick='location.href="?ClearAll=1"' style="margin:6px;">Clear</button>
	<br>
	<br>
	<form name="udaForm" action="" method="post">
		<input type="submit" name="report_id" value="Report Department Id">
		:<input type="text" name="report_deptid" id="report_deptid" size="8" style="margin:5px;">	
		<hr><br><br>
		<input type="submit" name="update_id" value="Update Dept Id">
		<input type="submit" name="add_id" value="Add Dept Id" style="margin:7px;">	
		<input type="submit" name="delete_id" value="Delete Dept Id" style="margin:7px;">
		<br>
		<input type="submit" name="get_id" value="Department Id">
		:<input type="text" name="deptid" id="deptid" size="8" style="margin:5px;"><br>		
		Name :<input type="text" name="deptname" id="deptname" size="20" style="margin:5px;"><br>
		Description :<input type="text" name="descript" id="descript" size="20" style="margin:5px;"><br>
		Manager Id :<input type="text" name="mngrid" id="mngrid" style="margin:5px;"><br>
		Location :<input type="text" name="location" id="location" style="margin:5px;"><br>
	</form>	
	<p></p>
</div>

<div class="column2" style="background-color:#FFFFFF;">	

<?php
  // Department table field names
	$tHead = array("dept_id","name","description","mngr_emp_id","location");
	//	Department table header names
	$tDHead = array("Department ID","Name","Description","Manager ID","Location");
  // Department table 1 record data (as strings)
  $tData = array("","","","","");

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
    if(isset($_POST['report_id'])){
		$nid = $_POST["report_deptid"];
		report_department($nid);
    }	
    if(isset($_POST['update_id'])){
		$nid = $_POST["deptid"];
		get_department_form_data();
		update_department_data($nid);
		put_department_form_data();
    }
    if(isset($_POST['add_id'])){ 
		$nid = $_POST["deptid"];
		get_department_form_data();		
		add_department_data($nid);
		put_department_form_data();
    }		
    if(isset($_POST['delete_id'])){ 
		$nid = $_POST["deptid"];
		get_department_form_data();		
		delete_department_data($nid);
		//put_department_form_data();  // The record is deleted, so leave form inputs blank.
    }			
    if(isset($_POST['get_id'])){
		$nid = $_POST["deptid"];
		get_department_data($nid);
    }
}


function get_department_form_data() {
//-------------------------------------------------------------------
	global $tData;
	$tData[0] = $_POST["deptid"];
	$tData[1] = $_POST["deptname"];
	$tData[2] = $_POST["descript"];
	$tData[3] = $_POST["mngrid"];
	$tData[4] = $_POST["location"];
}

function put_department_form_data() {
//-------------------------------------------------------------------
	global $tData;
	// "dept_id","name","description","mngr_emp_id","location"
	// deptid, deptname, descript, mngrid, location
	//----------------------------------------------------------
	$str = "document.getElementById('deptid').value = '" . $tData[0] . "';";
	echo "<script type='text/javascript'>";
	echo ($str);
	echo "</script>";
	$str = "document.getElementById('deptname').value = '" . $tData[1] . "';";
	echo "<script type='text/javascript'>";
	echo ($str);
	echo "</script>";	
	$str = "document.getElementById('descript').value = '" . $tData[2] . "';";
	echo "<script type='text/javascript'>";
	echo ($str);
	echo "</script>";
	$str = "document.getElementById('mngrid').value = '" . $tData[3] . "';";
	echo "<script type='text/javascript'>";
	echo ($str);
	echo "</script>";
	$str = "document.getElementById('location').value = '" . $tData[4] . "';";
	echo "<script type='text/javascript'>";
	echo ($str);
	echo "</script>";
}

function get_department_data($nid) {
//-------------------------------------------------------------------	
	global $tData;
	$conn = get_db_connection();	
	
	// Run query to get record for employee id = $nid
	$qstr = "SELECT * FROM departments where dept_id =" . $nid;
	$res1 = mysqli_query($conn, $qstr);	
	if (!$res1) {
		printf("<br>Error: Check that department id = %s exist.<br><br>", $nid);
		die("Error description: " . mysqli_error($conn));		
	}
	else {
		$row1 = mysqli_fetch_assoc($res1);
	}
	// Store current record data to local and global variables.
	$tData[0] = $row1['dept_id'];
	$tData[1] = $row1['name'];
	$tData[2] = $row1['description'];
	$tData[3] = $row1['mngr_emp_id'];
	$tData[4] = $row1['location'];

	// Copy the record data to the text boxes.
	put_department_form_data();
	
	// Close the connection
	mysqli_close($conn);
}

function update_department_data($nid) {
//-------------------------------------------------------------------	
	global $tData;	
	$nColumns = 5;	
	$conn = get_db_connection();
	
	// Run query to update the record for department id = $nid
	$qstr =  "UPDATE departments SET name='" . $tData[1] . "'";
	$qstr = $qstr . ", description='" . $tData[2] . "'";
	$qstr = $qstr . ", mngr_emp_id='" . $tData[3] . "'";
	$qstr = $qstr . ", location='" . $tData[4] . "'";
	$qstr = $qstr . " where dept_id='" . $nid . "';";	
	$res1 = mysqli_query($conn, $qstr);	
	if (!$res1) {
		printf("<br>Error: Check the record data for errors.<br><br>");		
		mysqli_close($conn);
		die("Error description: " . mysqli_error($conn));
	}
	else {
		printf("<br><br>Department id = %s was updated.<br>", $nid);
	}
	
	// Close the connection
	mysqli_close($conn);
}

function add_department_data($nid) {
//-------------------------------------------------------------------	
	global $tData;	
	$nColumns = 5;	
	$conn = get_db_connection();
	
	// Run query to insert (add) the record for employee id = $nid
	$qstr =  "INSERT INTO departments VALUES(";
	$qstr = $qstr . "'" . $tData[0] . "'";    // dept_id
	$qstr = $qstr . ", '" . $tData[1] . "'";  // name
	$qstr = $qstr . ", '" . $tData[2] . "'";  // description
	$qstr = $qstr . ", '" . $tData[3] . "'";  // mngr_emp_id
	$qstr = $qstr . ", '" . $tData[4]. "');"; // location	
	$res1 = mysqli_query($conn, $qstr);	
	if (!$res1) {
		printf("<br>sql = %s", $qstr);
		printf("<br>Error: Check the record data for errors.<br><br>");		
		mysqli_close($conn);		
		die("Error description: " . mysqli_error($conn));
		put_department_form_data();
	}
	else {
		printf("<br><br>Department id = %s was added.<br>", $nid);
	}
	
	// Close the connection
	mysqli_close($conn);
}

function delete_department_data($nid) {
//-------------------------------------------------------------------	
	$conn = get_db_connection();	
	
	// Run query to delete record for department id = $nid
	$qstr = "DELETE FROM departments where dept_id =" . $nid;
	$res1 = mysqli_query($conn, $qstr);	
	if (!$res1) {
		printf("<br>Error: Check the record data for errors.<br><br>", $nid);
		mysqli_close($conn);
		die("Error description: " . mysqli_error($conn));		
	}
	else {
		printf("<br><br>Department id = %s was deleted.<br>", $nid);		
	}
	
	// Close the connection
	mysqli_close($conn);
}

function DisplayAll() {
//-------------------------------------------------------------------	
	global $tHead;
	global $tDHead;
	$j = 0;
	$nColumns = 5;	
	$tbl = new HTML_Table('', 'dataTable');	
	$conn = get_db_connection();
	
	//Run the Select query
	printf("The data in the departments table:<br><br>");
	$res = mysqli_query($conn, 'SELECT * FROM departments order by dept_id asc');
	
	// Make the display table header.
	$tbl->addRow();
	$tbl->addCell($tDHead[0], 'first', 'header');
	for ($j = 1; $j < $nColumns; $j++) {
		$tbl->addCell($tDHead[$j], '', 'header');	  
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

function report_department($nid) {
//-------------------------------------------------------------------
	global $tHead;  // "dept_id","name","description","mngr_emp_id","location"
	$tHead2 = array("emp_id","name","job_title","phone", "email", "age","dept_id","gender","wage","emp_status");
	$j = 0;
	$nColumns = 10;	
	$tbl = new HTML_Table('', 'dataTable');	
	$conn = get_db_connection();	
	
	// Run query to get record for employee id = $nid
	$qstr = "SELECT * FROM departments where dept_id =" . $nid;
	//printf("<br> sql = %s<br>", $qstr);
	$res1 = mysqli_query($conn, $qstr);	
	if (!$res1) {
		printf("<br>Error: Check that department id = %s exist.<br><br>", $nid);
		die("Error description: " . mysqli_error($conn));		
	}
	else {
		$row1 = mysqli_fetch_assoc($res1);
	}
	$idn = $row1['mngr_emp_id'];
	
	// Run query to get managers name for dept_id = $idn
	$qstr = "select name from employees where emp_id =" . $idn;	
	$res2 = mysqli_query($conn, $qstr);	
	if (!$res2) {
		die("Error description: " . mysqli_error($conn));
	}
	else {
		$row2 = mysqli_fetch_assoc($res2);
	}
		
	printf("<br>");
	printf("<b><u>Report for Department ID = %s</b></u><br><br>", $nid);
	printf("%14s %s<br>",  "Department Name : ", $row1[$tHead[1]]);
	printf("%14s %s<br>",  "Description     : ", $row1[$tHead[2]]);	
	printf("%14s %s<br>",  "Location        : ", $row1[$tHead[4]]);
	printf("%14s %s<br>",  "Manager         : ", $row2['name']);	
	printf("<br>");
	
	// Make the list of employees table header.
	$tbl->addRow();
	$tbl->addCell("Employee ID", 'first', 'header');
	$tbl->addCell("Name", '', 'header');
	$tbl->addCell("Job Title", '', 'header');
	$tbl->addCell("Phone", '', 'header');
	$tbl->addCell("Email", '', 'header');

	// Run query to get list of employees working in department id = $nid
	$qstr = "SELECT emp_id, name, job_title, phone, email FROM employees where dept_id =" . $nid . " order by emp_id asc";	
	$res3 = mysqli_query($conn, $qstr);
	if (!$res3) {
		die("Error description: " . mysqli_error($conn));
	}
	else {
		while ($row3 = mysqli_fetch_assoc($res3)) {	
		   $tbl->addRow();
		   for ($j = 0; $j < 5; $j++) {
				$tbl->addCell($row3[$tHead2[$j]]);
			}	   
		}
	}
	
	// Close the connection
	mysqli_close($conn);
	
	// Show the table of current records.
	printf("Employees who work in %s:<br><br>", $row1[$tHead[1]]);
	echo $tbl->display();
	printf("<br><br>");
	
	// Copy the given department id to it's text box.
	$str = "document.getElementById('report_id').value = '" . $nid . "';";
	echo "<script type='text/javascript'>";
	echo ($str);
	echo "</script>";

}

?>

</dev>
</body>
</head>
</html>
