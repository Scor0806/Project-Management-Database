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
	<h2><center><u>Employees Table</u></center></h2><br><br>
	<p>Back to <a href="prjmgt_home.html">PM Home</a></p>
	<br><br>	
	<button type="submit" onclick='location.href="?DisplayAll=1"'>Display All</button>
	<button type="submit" onclick='location.href="?ClearAll=1"' style="margin:6px;">Clear</button>
	<br>
	<br>
	<form name="udaForm" action="" method="post">
		<input type="submit" name="report_id" value="Report Emp id">
		:<input type="text" name="report_empid" id="report_empid" size="8" style="margin:5px;">	
		<hr><br><br>
		<input type="submit" name="update_id" value="Update Emp Id">
		<input type="submit" name="add_id" value="Add Emp Id" style="margin:7px;">	
		<input type="submit" name="delete_id" value="Delete Emp Id" style="margin:7px;">
		<br>
		<input type="submit" name="get_id" value="Employee Id">
		:<input type="text" name="empid" id="empid" size="8" style="margin:5px;"><br>		
		Name :<input type="text" name="empname" id="empname" size="20" style="margin:5px;"><br>
		Job Title :<input type="text" name="jobtitle" id="jobtitle" size="20" style="margin:5px;"><br>
		Age :<input type="text" name="empage" id="empage" style="margin:5px;"><br>
		Dept Id :<input type="text" name="deptid" id="deptid" style="margin:5px;"><br>
		
		Gender : <select name="empgender" id="empgender" style="margin:5px;"><br>
		<option value="M">M</option>
		<option value="F">F</option>
		</select><br>
		
		Wage :<input type="text" name="empwage" id="empwage" size="10" style="margin:5px;"><br>
		
		Emp Status : <select name="empstatus" id="empstatus" style="margin:5px;">
		<option value="Active">Active</option>
		<option value="Inactive">Inactive</option>
		</select>
	</form>	
	<p></p>
</div>

<div class="column2" style="background-color:#FFFFFF;">	

<?php
  // Project table field names
  $tHead = array("emp_id","name","job_title","age","dept_id","gender","wage","emp_status");
  // Project table 1 record data (as strings)
  $tData = array("","","","","","","","");

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
		$nid = $_POST["report_empid"];
		report_employee($nid);
    }	
    if(isset($_POST['update_id'])){
		$nid = $_POST["empid"];
		get_employee_form_data();
		update_employee_data($nid);
		put_employee_form_data();
    }
    if(isset($_POST['add_id'])){ 
		$nid = $_POST["empid"];
		get_employee_form_data();		
		add_employee_data($nid);
		put_employee_form_data();
    }		
    if(isset($_POST['delete_id'])){ 
		$nid = $_POST["empid"];
		get_employee_form_data();		
		delete_employee_data($nid);
		//put_employee_form_data();  // The record is deleted, so leave form inputs blank.
    }			
    if(isset($_POST['get_id'])){
		$nid = $_POST["empid"];
		get_employee_data($nid);
    }
}


function get_employee_form_data() {
//-------------------------------------------------------------------
	global $tData;
	$tData[0] = $_POST["empid"];
	$tData[1] = $_POST["empname"];
	$tData[2] = $_POST["jobtitle"];
	$tData[3] = $_POST["empage"];
	$tData[4] = $_POST["deptid"];
	$tData[5] = $_POST["empgender"];
	$tData[6] = $_POST["empwage"];
	$tData[7] = $_POST["empstatus"];
}

function put_employee_form_data() {
//-------------------------------------------------------------------
	global $tData;
	// "emp_id","name","job_title","age","dept_id","gender","wage","emp_status"
	// empid, empname, jobtitle, empage, deptid, empgender, empwage, empstatus
	//----------------------------------------------------------
	$str = "document.getElementById('empid').value = '" . $tData[0] . "';";
	echo "<script type='text/javascript'>";
	echo ($str);
	echo "</script>";
	$str = "document.getElementById('empname').value = '" . $tData[1] . "';";
	echo "<script type='text/javascript'>";
	echo ($str);
	echo "</script>";	
	$str = "document.getElementById('jobtitle').value = '" . $tData[2] . "';";
	echo "<script type='text/javascript'>";
	echo ($str);
	echo "</script>";
	$str = "document.getElementById('empage').value = '" . $tData[3] . "';";
	echo "<script type='text/javascript'>";
	echo ($str);
	echo "</script>";
	$str = "document.getElementById('deptid').value = '" . $tData[4] . "';";
	echo "<script type='text/javascript'>";
	echo ($str);
	echo "</script>";
	$str = "document.getElementById('empgender').value = '" . $tData[5] . "';";
	echo "<script type='text/javascript'>";
	echo ($str);
	echo "</script>";
	$str = "document.getElementById('empwage').value = '" . $tData[6] . "';";
	echo "<script type='text/javascript'>";
	echo ($str);
	echo "</script>";
	$str = "document.getElementById('empstatus').value = '" . $tData[7] . "';";
	echo "<script type='text/javascript'>";
	echo ($str);
	echo "</script>";	
}

function get_employee_data($nid) {
//-------------------------------------------------------------------	
	global $tHead, $tData;
	$j = 0;
	$nColumns = 8;	
	$tbl = new HTML_Table('', 'dataTable');	
	$conn = get_db_connection();	
	
	// Run query to get record for employee id = $nid
	$qstr = "SELECT * FROM employees where emp_id =" . $nid;
	$res1 = mysqli_query($conn, $qstr);	
	if (!$res1) {
		printf("<br>Error: Check that employee id = %s exist.<br><br>", $nid);
		die("Error description: " . mysqli_error($conn));		
	}
	else {
		$row1 = mysqli_fetch_assoc($res1);
	}
	// Store current record data to local and global variables.
	$tData[0] = $row1['emp_id'];
	$tData[1] = $row1['name'];
	$tData[2] = $row1['job_title'];
	$tData[3] = $row1['age'];
	$tData[4] = $row1['dept_id'];
	$tData[5] = $row1['gender'];
	$tData[6] = $row1['wage'];
	$tData[7] = $row1['emp_status'];

	// Copy the record data to the text boxes.
	put_employee_form_data();
	
	// Close the connection
	mysqli_close($conn);
}

function update_employee_data($nid) {
//-------------------------------------------------------------------	
	global $tData;	
	$nColumns = 8;	
	$conn = get_db_connection();
	
	// Run query to update the record for employee id = $nid
	$qstr =  "UPDATE employees SET name='" . $tData[1] . "'";
	$qstr = $qstr . ", job_title='" . $tData[2] . "'";
	$qstr = $qstr . ", age='" . $tData[3] . "'";
	$qstr = $qstr . ", dept_id='" . $tData[4] . "'";
	$qstr = $qstr . ", gender='" . $tData[5] . "'";
	$qstr = $qstr . ", wage='" . $tData[6] . "'";
	$qstr = $qstr . ", emp_status='" . $tData[7] . "'";
	$qstr = $qstr . " where emp_id='" . $nid . "';";	
	$res1 = mysqli_query($conn, $qstr);	
	if (!$res1) {
		printf("<br>Error: Check the record data for errors.<br><br>");		
		mysqli_close($conn);
		die("Error description: " . mysqli_error($conn));
	}
	else {
		printf("<br><br>Employee id = %s was updated.<br>", $nid);
	}
	
	// Close the connection
	mysqli_close($conn);
}

function add_employee_data($nid) {
//-------------------------------------------------------------------	
	global $tData;	
	$nColumns = 8;	
	$conn = get_db_connection();	
	
	// Run query to insert (add) the record for employee id = $nid
	$qstr =  "INSERT INTO employees VALUES(";
	$qstr = $qstr . "'" . $tData[0] . "'";    // emp_id
	$qstr = $qstr . ", '" . $tData[1] . "'";  // name
	$qstr = $qstr . ", '" . $tData[2] . "'";  // job_title
	$qstr = $qstr . ", '" . $tData[3] . "'";  // age
	$qstr = $qstr . ", '" . $tData[4] . "'";  // dept_id
	$qstr = $qstr . ", '" . $tData[5] . "'";  // gender
	$qstr = $qstr . ", '" . $tData[6] . "'";  // wage
	$qstr = $qstr . ", '" . $tData[7]. "');"; // emp_status	
	$res1 = mysqli_query($conn, $qstr);	
	if (!$res1) {
		printf("<br>sql = %s", $qstr);
		printf("<br>Error: Check the record data for errors.<br><br>");		
		mysqli_close($conn);		
		die("Error description: " . mysqli_error($conn));
		put_employee_form_data();
	}
	else {
		printf("<br><br>employee id = %s was added.<br>", $nid);
	}
	
	// Close the connection
	mysqli_close($conn);
}

function delete_employee_data($nid) {
//-------------------------------------------------------------------	
	$conn = get_db_connection();	
	
	// Run query to delete record for employee id = $nid
	$qstr = "DELETE FROM employees where emp_id =" . $nid;
	$res1 = mysqli_query($conn, $qstr);	
	if (!$res1) {
		printf("<br>Error: Check the record data for errors.<br><br>", $nid);
		mysqli_close($conn);
		die("Error description: " . mysqli_error($conn));		
	}
	else {
		printf("<br><br>Employee id = %s was deleted.<br>", $nid);		
	}
	
	// Close the connection
	mysqli_close($conn);
}

function DisplayAll() {
//-------------------------------------------------------------------	
	global $tHead;
	$j = 0;
	$nColumns = 8;	
	$tbl = new HTML_Table('', 'dataTable');	
	$conn = get_db_connection();
	
	//Run the Select query
	printf("The data in the employees table:<br><br>");
	$res = mysqli_query($conn, 'SELECT * FROM employees order by emp_id asc');
	
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

function report_employee($nid) {
//-------------------------------------------------------------------
	global $tHead;  // "emp_id","name","job_title","age","dept_id","gender","wage","emp_status"
	$tHead2 = array("task_id","prj_id","description","emp_id","daily_hrs");
	$j = 0;
	$nColumns = 10;	
	$tbl = new HTML_Table('', 'dataTable');	
	$conn = get_db_connection();	
	
	// Run query to get record for employee id = $nid
	$qstr = "SELECT * FROM employees where emp_id =" . $nid;
	//printf("<br> sql = %s<br>", $qstr);
	$res1 = mysqli_query($conn, $qstr);	
	if (!$res1) {
		printf("<br>Error: Check that employee id = %s exist.<br><br>", $nid);
		die("Error description: " . mysqli_error($conn));		
	}
	else {
		$row1 = mysqli_fetch_assoc($res1);
	}
	$idn = $row1['dept_id'];
	
	// Run query to get department name for emp_id = $idn
	$qstr = "select name from departments where dept_id =" . $idn;
	$res2 = mysqli_query($conn, $qstr);	
	if (!$res2) {
		die("Error description: " . mysqli_error($conn));
	}
	else {
		$row2 = mysqli_fetch_assoc($res2);
	}
		
	printf("<br>");
	printf("<b><u>Report for Employee ID = %s</b></u><br><br>", $nid);
	printf("%14s %s<br>",  "Name         : ", $row1[$tHead[1]]);
	printf("%14s %s<br>",  "Job Title    : ", $row1[$tHead[2]]);	
	printf("%14s %s<br>",  "Age          : ", $row1[$tHead[3]]);
	printf("%14s %s<br>",  "Department   : ", $row2['name']);
	printf("%14s %s<br>",  "Gender       : ", $row1[$tHead[5]]);
	printf("%14s $%s<br>", "Wage ($/hr)  : ", number_format($row1[$tHead[6]], 0));
	printf("%14s %s<br>",  "Status       : ", $row1[$tHead[7]]);
	
	// Make the display table header.
	$tbl->addRow();
	$tbl->addCell($tHead2[0], 'first', 'header');
	$tbl->addCell($tHead2[1], '', 'header');
	$tbl->addCell($tHead2[2], '', 'header');	  
	
	// Run query to get list of employees working on project id = $nid
	$qstr = "SELECT task_id, prj_id, description FROM tasks where emp_id =" . $nid . " order by task_id asc";
	$res3 = mysqli_query($conn, $qstr);
	if (!$res3) {
		die("Error description: " . mysqli_error($conn));
	}
	else {
		while ($row3 = mysqli_fetch_assoc($res3)) {	
		   $tbl->addRow();
		   for ($j = 0; $j < 3; $j++) {
				$tbl->addCell($row3[$tHead2[$j]]);
			}	   
		}
	}
	
	// Close the connection
	mysqli_close($conn);
	
	// Show the table of current records.
	printf("<br>");
	printf("Projects and task %s has or is working on:<br><br>", $row1[$tHead[1]]);
	echo $tbl->display();
	printf("<br><br>");
	
	// Copy the given project id to it's text box.
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
