<!doctype html>
<html>
<head>
<style>
	* {
	  box-sizing: border-box;
	  margin: 1;
	  flex: 0;
	}
	/* Create two columns that floats next to each other */
	.column1 {
	  float: left;
	  width: 20%;
	  padding: 5px;  
	  height: 100vh;
	  overflow-y: scroll;
	  border: 0px solid #000080;
	}
	.column2 {
	  float: left;
	  width: 80%;
	  padding: 5px;
	  height: 1200px;
	  overflow-y: auto;
	  border: 0px solid #000080;
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
	<h2><center><u>Task Times Tables</u></center></h2><br>
	<p>Back to <a href="prjmgt_home_page.php">PM Home</a></p><br>
	<button type="submit" onclick='location.href="?DisplayAllTimes=1"' style="margin:5px;">All Tasks Times</button>
	<button type="submit" onclick='location.href="?ClearAll=1"' style="margin:6px;">Clear</button>	
	<br>
	<br>
	<form name="udaForm" action="" method="post">
		<input type="submit" name="report_id" value="Report Task Times Id">
		:<input type="text" name="report_taskid" id="report_taskid" size="8" style="margin:5px;">	
		<hr><br><br>
		<input type="submit" name="delete_id" value="Delete Task Id" style="margin:7px;">
		<br>
		<input type="submit" name="get_id" value="Task Id">
		:<input type="text" name="taskid" id="taskid" size="8" style="margin:5px;"><br>		
		Project Id :<input type="text" name="prjid" id="prjid" size="22" style="margin:5px;"><br>
		Description :<input type="text" name="descript" id="descript" size="22" style="margin:5px;"><br>
		Employee Id :<input type="text" name="empid" id="empid" style="margin:5px;"><br>
		Daily Hrs :<input type="text" name="dailyhrs" id="dailyhrs" style="margin:5px;"><br>
	</form>	
	<p></p>

</div>

<div class="column2" style="background-color:#FFFFFF;">	

<?php
  // Tasks table field names
  $tHead = array("task_id","prj_id","description","emp_id","daily_hrs");
  $tHead2 = array("tasks_task_id","task_date","prj_id","emp_id","hours");
  // Tasks table 1 record data (as strings)
  $tData = array("","","","","");

require('includes/html_table.class.php');
if($_GET){
    if(isset($_GET['DisplayAllTasks'])){
        DisplayAllTasks();
    }	
    if(isset($_GET['DisplayAllTimes'])){
        DisplayAllTimes();
    }
    if(isset($_GET['ClearAll'])){
        ClearAll();
    }	
}

if($_POST){
    if(isset($_POST['report_id'])){
		$nid = $_POST["report_taskid"];
		report_tasks($nid);
    }	
    if(isset($_POST['update_id'])){
		$nid = $_POST["taskid"];
		get_tasks_form_data();
		update_tasks_data($nid);
		put_tasks_form_data();
    }
    if(isset($_POST['add_id'])){ 
		$nid = $_POST["taskid"];
		get_tasks_form_data();		
		add_tasks_data($nid);
		put_tasks_form_data();
    }		
    if(isset($_POST['delete_id'])){ 
		$nid = $_POST["taskid"];
		get_tasks_form_data();		
		delete_tasks_data($nid);
		//put_tasks_form_data();  // The record is deleted, so leave form inputs blank.
    }			
    if(isset($_POST['get_id'])){
		$nid = $_POST["taskid"];
		get_tasks_data($nid);
    }
}


function put_tasks_form_data() {
//-------------------------------------------------------------------
	global $tData;
	// "task_id","prj_id","description","emp_id","daily_hrs"
	// taskid, prjid, descript, empid, dailyhrs
	//----------------------------------------------------------
	$str = "document.getElementById('taskid').value = '" . $tData[0] . "';";
	echo "<script type='text/javascript'>";
	echo ($str);
	echo "</script>";
	$str = "document.getElementById('prjid').value = '" . $tData[1] . "';";
	echo "<script type='text/javascript'>";
	echo ($str);
	echo "</script>";	
	$str = "document.getElementById('descript').value = '" . $tData[2] . "';";
	echo "<script type='text/javascript'>";
	echo ($str);
	echo "</script>";
	$str = "document.getElementById('empid').value = '" . $tData[3] . "';";
	echo "<script type='text/javascript'>";
	echo ($str);
	echo "</script>";
	$str = "document.getElementById('dailyhrs').value = '" . $tData[4] . "';";
	echo "<script type='text/javascript'>";
	echo ($str);
	echo "</script>";
}

function get_tasks_data($nid) {
//-------------------------------------------------------------------	
	global $tData;
	$conn = get_db_connection();	
	
	// Run query to get record for employee id = $nid
	$qstr = "SELECT * FROM tasks where task_id =" . $nid;
	$res1 = mysqli_query($conn, $qstr);	
	if (!$res1) {
		printf("<br>Error: Check that task id = %s exist.<br><br>", $nid);
		die("Error description: " . mysqli_error($conn));		
	}
	else {
		$row1 = mysqli_fetch_assoc($res1);
	}
	// Store current record data to local and global variables.
	$tData[0] = $row1['task_id'];
	$tData[1] = $row1['prj_id'];
	$tData[2] = $row1['description'];
	$tData[3] = $row1['emp_id'];
	$tData[4] = $row1['daily_hrs'];

	// Copy the record data to the text boxes.
	put_tasks_form_data();
	
	// Close the connection
	mysqli_close($conn);
}

function update_tasks_data($nid) {
//-------------------------------------------------------------------	
	global $tData;	
	$nColumns = 5;	
	$conn = get_db_connection();
	
	// Run query to update the record for department id = $nid
	$qstr =  "UPDATE tasks SET prj_id='" . $tData[1] . "'";
	$qstr = $qstr . ", description='" . $tData[2] . "'";
	$qstr = $qstr . ", emp_id='" . $tData[3] . "'";
	$qstr = $qstr . ", daily_hrs='" . $tData[4] . "'";
	$qstr = $qstr . " where task_id='" . $nid . "';";	
	$res1 = mysqli_query($conn, $qstr);	
	if (!$res1) {
		printf("<br>Error: Check the record data for errors.<br><br>");		
		mysqli_close($conn);
		die("Error description: " . mysqli_error($conn));
	}
	else {
		printf("<br><br>Task id = %s was updated.<br>", $nid);
	}
	
	// Close the connection
	mysqli_close($conn);
}

function add_tasks_data($nid) {
//-------------------------------------------------------------------	
	global $tData;	
	$nColumns = 5;	
	$conn = get_db_connection();
	
	// Run query to insert (add) the record for employee id = $nid
	$qstr =  "INSERT INTO tasks VALUES(";
	$qstr = $qstr . "'" . $tData[0] . "'";    // task_id
	$qstr = $qstr . ", '" . $tData[1] . "'";  // prj_id
	$qstr = $qstr . ", '" . $tData[2] . "'";  // description
	$qstr = $qstr . ", '" . $tData[3] . "'";  // emp_id
	$qstr = $qstr . ", '" . $tData[4]. "');"; // daily_hrs	
	$res1 = mysqli_query($conn, $qstr);	
	if (!$res1) {
		printf("<br>sql = %s", $qstr);
		printf("<br>Error: Check the record data for errors.<br><br>");		
		mysqli_close($conn);		
		die("Error description: " . mysqli_error($conn));
		put_tasks_form_data();
	}
	else {
		printf("<br><br>Department id = %s was added.<br>", $nid);
	}
	
	// Close the connection
	mysqli_close($conn);
}

function delete_tasks_data($nid) {
//-------------------------------------------------------------------	
	$conn = get_db_connection();	
	
	// Run query to delete record for task id = $nid
	$qstr = "DELETE FROM tasks where task_id =" . $nid;
	$res1 = mysqli_query($conn, $qstr);	
	if (!$res1) {
		printf("<br>Error: Check the record data for errors.<br><br>", $nid);
		mysqli_close($conn);
		die("Error description: " . mysqli_error($conn));		
	}
	else {
		printf("<br><br>Task id = %s was deleted.<br>", $nid);		
	}
	
	// Close the connection
	mysqli_close($conn);
}

function DisplayAllTasks() {
//-------------------------------------------------------------------	
	global $tHead;
	$j = 0;
	$nColumns = 5;	
	$tbl = new HTML_Table('', 'dataTable');	
	$conn = get_db_connection();
	
	// Run the Select query
	printf("The data in the tasks table:<br><br>");
	$res = mysqli_query($conn, 'SELECT * FROM tasks order by task_id asc');
	
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
	printf("<br><br>");

	//Close the connection
	mysqli_close($conn);      
}

function DisplayAllTimes() {
//-------------------------------------------------------------------	
	global $tHead2;
	$j = 0;
	$nColumns = 5;	
	$tbl = new HTML_Table('', 'dataTable');	
	$conn = get_db_connection();
	
	// Run the Select query
	printf("The data in the task_times table:<br><br>");
	$res = mysqli_query($conn, 'SELECT * FROM task_times order by tasks_task_id asc');
	
	// Make the display table header.
	$tbl->addRow();
	$tbl->addCell($tHead2[0], 'first', 'header');
	for ($j = 1; $j < $nColumns; $j++) {
		$tbl->addCell($tHead2[$j], '', 'header');	  
	}
	
	while ($row = mysqli_fetch_assoc($res)) {	
	   $tbl->addRow();
	   for ($j = 0; $j < $nColumns; $j++) {
			$tbl->addCell($row[$tHead2[$j]]);
		}	   
	}
	// Show the table of current records.
	echo $tbl->display();
	printf("<br><br>");
	
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

function report_tasks($nid) {
//-------------------------------------------------------------------
	global $tHead;  // "task_id","prj_id","description","emp_id","daily_hrs"
	global $tHead2; // "tasks_task_id","task_date","prj_id","emp_id","hours"
	$j = 0;
	$nColumns = 5;	
	$tbl = new HTML_Table('', 'dataTable');	
	$conn = get_db_connection();	
	
	
	// Run query to get record for task id = $nid
	$qstr = "SELECT * FROM tasks where task_id =" . $nid;
	//printf("<br> sql = %s<br>", $qstr);
	$res1 = mysqli_query($conn, $qstr);	
	if (!$res1) {
		printf("<br>Error: Check that task id = %s exist.<br><br>", $nid);
		die("Error description: " . mysqli_error($conn));		
	}
	else {
		$row1 = mysqli_fetch_assoc($res1);
	}
	
		
	printf("<br>");
	printf("<b><u>Report for Task Id = %s</b></u><br><br>", $nid);
	printf("%14s %s<br>",  "Project Id  : ", $row1[$tHead[1]]);
	printf("%14s %s<br>",  "Description : ", $row1[$tHead[2]]);	
	printf("<br>");
	
	// Run query to get records for tasks times with task id = $nid
	$qstr = "SELECT * FROM task_times where tasks_task_id =" . $nid;	
	$res2 = mysqli_query($conn, $qstr);
	
	// Make the display table header.
	$tbl->addRow();
	$tbl->addCell($tHead2[0], 'first', 'header');
	for ($j = 1; $j < $nColumns; $j++) {
		$tbl->addCell($tHead2[$j], '', 'header');	  
	}
	
	while ($row2 = mysqli_fetch_assoc($res2)) {	
	   $tbl->addRow();
	   for ($j = 0; $j < $nColumns; $j++) {
			$tbl->addCell($row2[$tHead2[$j]]);
		}	   
	}
	// Close the connection
	mysqli_close($conn);

	printf("<br>All task times entered for task id = %s<br><br>", $nid);
	// Show the table of current records.
	echo $tbl->display();
	printf("<br><br>");	
	
	// Copy the given department id to it's text box.
	$str = "document.getElementById('report_id').value = '" . $nid . "';";
	echo "<script type='text/javascript'>";
	echo ($str);
	echo "</script>";
}

?>
</div>
</body>
</head>
</html>
