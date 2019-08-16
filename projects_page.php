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
	  width: 17%;
	  padding: 5px;  
	  height: 97vh;
	  overflow-y: auto;
	  border: 0px solid #000000;
	}
	.column2 {
	  float: left;
	  width: 83%;
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
  include 'includes/code_php.php';
  setlocale(LC_MONETARY, 'en_US');
  $prj_stat = 1; 

?>
<!-- <body bgcolor="#E0FFFF">   -->

<div class="column1" style="background-color:#E0FFFF;">	
	<h2><center><u>Projects Table</u></center></h2><br><br>
	<p>Back to <a href="prjmgt_home.html">PM Home</a></p>
	<br><br>	
	<button type="submit" onclick='location.href="?DisplayAll=1"'>Display All</button>
	<button type="submit" onclick='location.href="?ClearAll=1"' style="margin:6px;">Clear</button>
	<br>
	<br>
	<form name="udaForm" action="" method="post">	
		<input type="submit" name="report_id" value="Report prj id">
		:<input type="text" name="report_prjid" id="report_prjid" size="8" style="margin:5px;">
		<hr>
		<br><br>
		<input type="submit" name="update_prjid" value="Update Prj Id">
		<input type="submit" name="add_prjid" value="Add Proj Id" style="margin:7px;">	
		<input type="submit" name="delete_prjid" value="Delete Prj Id" style="margin:7px;">
		<br>
		<input type="submit" name="get_prjid" value="Project Id">
		:<input type="text" name="prjid" id="prjid" size="8" style="margin:5px;"><br>
		Name :<input type="text" name="prjname" id="prjname" size="22" style="margin:5px;"><br>
 		Start Date :<input type="text" name="start_date" id="start_date" style="margin:5px;"><br>
		End Date :<input type="text" name="end_date" id="end_date" style="margin:5px;"><br>
		Actual Date :<input type="text" name="act_date" id="act_date" style="margin:5px;"><br>
		Description :<input type="text" name="description" id="description" size="25" style="margin:5px;"><br>
		PM Mngr Id :<input type="text" name="pmid" id="pmid" style="margin:5px;"><br>
		Budget Est :<input type="text" name="ebudget" id="ebudget" style="margin:5px;"><br>
		Hours :<input type="text" name="hours" id="hours" size="12" style="margin:5px;"><br>
		
		Status : <select name="prjstatus" id="prjstatus" style="margin:5px;">
		<option value="Ongoing">Ongoing</option>
		<option value="Cancelled">Cancelled</option>
		<option value="Completed">Completed</option>
		<option value="Suspended">Suspended</option>
		</select>		
	</form>	
	<p></p>

</div>

<div class="column2" style="background-color:#FFFFFF;">	

<?php
  // Project table field names
  $tHead = array("prj_id","name","start_date","end_date_proj","end_date_act","description","pm_emp_id","budget_est","hours_prj","status");
  // Project table 1 record data (as strings)
  $tData = array("","","","","","","","","","");

require('includes/html_table.class.php');
if($_GET){
    if(isset($_GET['DisplayAll'])){
        DisplayAll();
    }
    if(isset($_GET['ClearAll'])){
        ClearAll();
    }
	if(isset($_GET['report_id'])){		
        report_project($_GET["report_id"]);
    }
	
}

if($_POST){
	if(isset($_POST['prjstatus'])){
		$GLOBALS['$prj_stat'] = $_POST['prjstatus'];
    }
    if(isset($_POST['report_id'])){
		$nid = $_POST["report_prjid"];
		report_project($nid);
    }
    if(isset($_POST['update_prjid'])){
		$nid = $_POST["prjid"];
		get_project_form_data();
		update_project_data($nid);
		put_project_form_data();
    }
    if(isset($_POST['add_prjid'])){ 
		$nid = $_POST["prjid"];
		get_project_form_data();		
		add_project_data($nid);
		put_project_form_data();
    }		
    if(isset($_POST['delete_prjid'])){ 
		$nid = $_POST["prjid"];
		get_project_form_data();		
		delete_project_data($nid);
		//put_project_form_data();  // The record is deleted, so leave form inputs blank.
    }			
    if(isset($_POST['get_prjid'])){
		$nid = $_POST["prjid"];
		get_project_data($nid);
    }
}


function get_project_form_data() {
//-------------------------------------------------------------------
	global $tData;
	$tData[0] = $_POST["prjid"];
	$tData[1] = $_POST["prjname"];
	$tData[2] = $_POST["start_date"];
	$tData[3] = $_POST["end_date"];
	$tData[4] = $_POST["act_date"];
	$tData[5] = $_POST["description"];
	$tData[6] = $_POST["pmid"];
	$tData[7] = $_POST["ebudget"];
	$tData[8] = $_POST["hours"];
	$tData[9] = $_POST["prjstatus"];
}

function put_project_form_data() {
//-------------------------------------------------------------------
	global $tData;
	// prjid, prjname, start_date, end_date, act_date, description, pmid, ebudget, hours, prjstatus
	//----------------------------------------------------------
	$str = "document.getElementById('prjid').value = '" . $tData[0] . "';";
	echo "<script type='text/javascript'>";
	echo ($str);
	echo "</script>";
	$str = "document.getElementById('prjname').value = '" . $tData[1] . "';";
	echo "<script type='text/javascript'>";
	echo ($str);
	echo "</script>";
	$str = "document.getElementById('start_date').value = '" . $tData[2] . "';";
	echo "<script type='text/javascript'>";
	echo ($str);
	echo "</script>";
	$str = "document.getElementById('end_date').value = '" . $tData[3] . "';";
	echo "<script type='text/javascript'>";
	echo ($str);
	echo "</script>";
	$str = "document.getElementById('act_date').value = '" . $tData[4] . "';";
	echo "<script type='text/javascript'>";
	echo ($str);
	echo "</script>";
	$str = "document.getElementById('description').value = '" . $tData[5] . "';";
	echo "<script type='text/javascript'>";
	echo ($str);
	echo "</script>";
	$str = "document.getElementById('pmid').value = '" . $tData[6] . "';";
	echo "<script type='text/javascript'>";
	echo ($str);
	echo "</script>";	
	$str = "document.getElementById('ebudget').value = '" . $tData[7] . "';";
	echo "<script type='text/javascript'>";
	echo ($str);
	echo "</script>";
	$str = "document.getElementById('hours').value = '" . $tData[8] . "';";
	echo "<script type='text/javascript'>";
	echo ($str);
	echo "</script>";
	$str = "document.getElementById('prjstatus').value = '" . $tData[9] . "';";
	echo "<script type='text/javascript'>";
	echo ($str);
	echo "</script>";
}

function get_project_data($nid) {
//-------------------------------------------------------------------	
	global $tHead, $tData;
	$j = 0;
	$nColumns = 10;	
	$tbl = new HTML_Table('', 'dataTable');	
	$conn = get_db_connection();	
	
	// Run query to get record for project id = $nid
	$qstr = "SELECT * FROM projects where prj_id =" . $nid;
	$res1 = mysqli_query($conn, $qstr);	
	if (!$res1) {
		printf("<br>Error: Check that project id = %s exist.<br><br>", $nid);
		die("Error description: " . mysqli_error($conn));		
	}
	else {
		$row1 = mysqli_fetch_assoc($res1);
	}
	// Store current record data to local and global variables.
	$tData[0] = $pid = $row1['prj_id'];
	$tData[1] = $pjname = $row1['name'];
	$tData[2] = $sdate = $row1['start_date'];
	$tData[3] = $edate = $row1['end_date_proj'];
	$tData[4] = $adate = $row1['end_date_act'];
	$tData[5] = $descript = $row1['description'];
	$tData[6] = $pmid = $row1['pm_emp_id'];
	$tData[7] = $ebudget = $row1['budget_est'];
	$tData[8] = $phours = $row1['hours_prj'];
	$tData[9] = $status = $row1['status'];
//	printf("<br>%s <br>%s <br>%s <br>%s <br>%s <br>%s", $pid,$pjname,$sdate,$edate,$adate,$descript);
//	printf("<br>%s <br>%s <br>%s <br>%s<br><br>", $pmid,$ebudget,$phours,$status);

	// Copy the record data to the text boxes.
	put_project_form_data();
	
	// Close the connection
	mysqli_close($conn);
}


function update_project_data($nid) {
//-------------------------------------------------------------------	
	global $tData;	
	$nColumns = 10;	
	$conn = get_db_connection();	
	$len = strlen($tData[4]);  // Length of project actual end date.
	
	// Run query to update the record for project id = $nid
	// Example;
	// update projects set name='GE HVAC System', start_date='2019-01-14', end_date_proj='2020-02-15', 
	//   end_date_act=null, description='Redesign GE building HVAC system.', pm_emp_id=120, 
	//   budget_est=1164800, hours_prj=34.8, status='Suspended' where prj_id=102;
	$qstr =  "UPDATE projects SET name='" . $tData[1] . "'";
	
	// Convert dates incase they are not in the YYYY-MM-DD, format.	
	$qstr = $qstr . ", start_date='" . $tData[2] . "'";
	$qstr = $qstr . ", end_date_proj='" . $tData[3] . "'";
	if (strncmp($tData[4],'null', 4) == 0 or strncmp($tData[4],'NULL', 4) == 0 or $len < 9) {
		$qstr = $qstr . ", end_date_act=null ";
	}
	else {
		$qstr = $qstr . ", end_date_act='" . $tData[4] . "'";
	}
	$qstr = $qstr . ", description='" . $tData[5] . "'";
	$qstr = $qstr . ", pm_emp_id=" . $tData[6];
	$qstr = $qstr . ", budget_est=" . $tData[7];
	$qstr = $qstr . ", hours_prj=" . $tData[8];
	$qstr = $qstr . ", status='" . $tData[9] . "'";	
	$qstr = $qstr . " where prj_id =" . $nid . ";";
	//printf("<br>sql = %s", $qstr);
	$res1 = mysqli_query($conn, $qstr);	
	if (!$res1) {		
		printf("<br>Error: Check the record data for errors.<br><br>");		
		mysqli_close($conn);
		die("Error description: " . mysqli_error($conn));
	}
	else {
		printf("<br><br>Project id = %s was updated.<br>", $nid);
	}
	
	// Close the connection
	mysqli_close($conn);
}

function add_project_data($nid) {
//-------------------------------------------------------------------	
	global $tData;	
	$nColumns = 10;	
	$conn = get_db_connection();	
	$len = strlen($tData[4]);  // Length of project actual end date.
	
	// Run query to insert (add) the record for project id = $nid
	// Example;
	// INSERT INTO projects VALUES 
	// ('201', 'New Project', '2019-10-17', '2021-05-23', null, 'A new project', '235', '982000', '0', 'Ongoing');
	//   end_date_act=null, description='Redesign GE building HVAC system.', pm_emp_id=120, 
	//   budget_est=1164800, hours_prj=34.8, status='Suspended' where prj_id=102;
	$qstr =  "INSERT INTO projects VALUES(";
	$qstr = $qstr . "'" . $tData[0] . "'";    // prj_id
	$qstr = $qstr . ", '" . $tData[1] . "'";  // name	
	$qstr = $qstr . ", '" . $tData[2] . "'";  // start_date
	$qstr = $qstr . ", '" . $tData[3] . "'";  // end_date_proj	
	$qstr = $qstr . ", null ";                // A new project should have end_date_act; not yet know (null).
	$qstr = $qstr . ", '" . $tData[5] . "'";  // description
	$qstr = $qstr . ", '" . $tData[6] . "'";  // pm_emp_id
	$qstr = $qstr . ", '" . $tData[7] . "'";  // budget_est
	$qstr = $qstr . ", '" . $tData[8] . "'";  // hours_prj
	$qstr = $qstr . ", '" . $tData[9]. "');"; // status
	//printf("<br>sql = %s", $qstr);
	$res1 = mysqli_query($conn, $qstr);	
	if (!$res1) {		
		printf("<br>Error: Check the record data for errors.<br><br>");		
		mysqli_close($conn);		
		die("Error description: " . mysqli_error($conn));
		put_project_form_data();
	}
	else {
		printf("<br><br>Project id = %s was added.<br>", $nid);
	}
	
	// Close the connection
	mysqli_close($conn);
}

function delete_project_data($nid) {
//-------------------------------------------------------------------	
	global $tHead, $tData;
	$j = 0;
	$nColumns = 10;	
	$tbl = new HTML_Table('', 'dataTable');	
	$conn = get_db_connection();	
	
	// Run query to delete record for project id = $nid
	$qstr = "DELETE FROM projects where prj_id =" . $nid;
	$res1 = mysqli_query($conn, $qstr);	
	if (!$res1) {
		printf("<br>Error: Check the record data for errors.<br><br>", $nid);
		mysqli_close($conn);
		die("Error description: " . mysqli_error($conn));		
	}
	else {
		printf("<br><br>Project id = %s was deleted.<br>", $nid);		
	}
	
	// Close the connection
	mysqli_close($conn);
}

function DisplayAll() {
//-------------------------------------------------------------------	
	global $tHead;
	$j = 0;
	$nColumns = 10;	
	$tbl = new HTML_Table('', 'dataTable');	
	$conn = get_db_connection();	
	
	// Run the Select query
	printf("<b><u>Current data in Projects table:</b></u><br><br>");
	$res = mysqli_query($conn, 'SELECT * FROM projects order by prj_id asc');
	
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
	
	// Close the connection
	mysqli_close($conn);   
}

function ClearAll() {
//-------------------------------------------------------------------	
	$str = "document.clear();";
	echo "<script type='text/javascript'>";
	echo ($str);
	echo "</script>";	
}

function report_project($nid) {
//-------------------------------------------------------------------
	global $tHead;
	$j = 0;
	$nColumns = 10;	
	$tbl = new HTML_Table('', 'dataTable');	
	$conn = get_db_connection();	
	
	// Run query to get record for project id = $nid
	$qstr = "SELECT * FROM projects where prj_id =" . $nid;
	$res1 = mysqli_query($conn, $qstr);	
	if (!$res1) {
		printf("<br>Error: Check that project id = %s exist.<br><br>", $nid);
		die("Error description: " . mysqli_error($conn));		
	}
	else {
		$row1 = mysqli_fetch_assoc($res1);
	}
	$pmid = $row1['pm_emp_id'];
	
	// Run query to get project manager name for emp_id = $pmid
	$qstr = "select name from employees where emp_id =" . $pmid;
	$res2 = mysqli_query($conn, $qstr);	
	if (!$res2) {
		die("Error description: " . mysqli_error($conn));
	}
	else {
		$row2 = mysqli_fetch_assoc($res2);
	}
		
	printf("<br>");
	printf("<b><u>Report for Project ID = %s</b></u><br><br>", $nid);
	printf("%14s %s<br>",  "Name         : ", $row1[$tHead[1]]);
	printf("%14s %s<br>",  "Description  : ", $row1[$tHead[5]]);
	printf("%14s %s<br>",  "Status       : ", $row1[$tHead[9]]);
	printf("%14s %s<br>",  "Start        : ", $row1[$tHead[2]]);
	printf("%14s %s<br>",  "End          : ", $row1[$tHead[3]]);
	printf("%14s %s<br>",  "Actual       : ", $row1[$tHead[4]]);
	printf("%14s $%s<br>", "Buget        : ", number_format($row1[$tHead[7]], 0));
	printf("%14s %s<br>",  "Total hours  : ", $row1[$tHead[8]]);
	printf("%14s %s<br><br>","Project Mngr : ",$row2['name']);
	
	// Run query to get list of employees working on project id = $nid
	$qstr = "SELECT DISTINCT emp_id FROM tasks where prj_id =" . $nid . " order by emp_id asc";
	$res3 = mysqli_query($conn, $qstr);	
	if (!$res3) {
		die("Error description: " . mysqli_error($conn));
	}
	else {
		// Make the list of employees table header.
		$tbl->addRow();
		$tbl->addCell("Name", 'first', 'header');
		$tbl->addCell("Job Title", '', 'header');
		$tbl->addCell("Emp ID", '', 'header');
		$tbl->addCell("Department", '', 'header');

		while ($row3 = mysqli_fetch_assoc($res3)) {
		//-------------------------------------------------------------
			$empid = $row3['emp_id'];			
			// Run query to get current employee ($empid) data from the employees table with emp_id = $empid
			$qstr = "SELECT name, job_title, dept_id FROM employees where emp_id =" . $empid;
			$res4 = mysqli_query($conn, $qstr);	
			if (!$res4) {
				die("Error description: " . mysqli_error($conn));
			}
			else {
				$row4 = mysqli_fetch_assoc($res4);
				$deptid = $row4['dept_id'];				
				// Run query to get department id ($deptid) name from the departments table with dept_id = ($deptid)
				$qstr = "SELECT name FROM departments where dept_id =" . $deptid;
				$res5 = mysqli_query($conn, $qstr);	
				if (!$res5) {
					die("Error description: " . mysqli_error($conn));
				}
				else {				
					$row5 = mysqli_fetch_assoc($res5);
					$tbl->addRow();
					$tbl->addCell($row4['name']);
					$tbl->addCell($row4['job_title']);
					$tbl->addCell($empid);
					$tbl->addCell($row5['name']);
				}				
			}
		//-------------------------------------------------------------
		}  
	}
	
	// Close the connection
	mysqli_close($conn);
	
	// Show the table of current records.
	printf("Employees working on the project:<br><br>");
	echo $tbl->display();
	printf("<br><br>");
	
	// Copy the given project id to it's text box.
	$str = "document.getElementById('report_prjid').value = '" . $nid . "';";
	echo "<script type='text/javascript'>";
	echo ($str);
	echo "</script>";
}

?>
</div>
</body>

</head>
</html>
