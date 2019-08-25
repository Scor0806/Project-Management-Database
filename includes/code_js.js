window.g_user_name = "";    // Current user name.
window.g_user_empid = "0";  // Current user employee id number.
window.g_user_level = "0";  // Current user access level.
var attempt = 4;            // Variable to count number of attempts.
var allEmployees = [
    ["admin", "admin", "701", "2"],
	["employee", "employee", "702", "1"]];
	
function validate(){
	var username = document.getElementById("username").value;
	var password = document.getElementById("password").value;
	var uempid = "", ulevel ="";
	var str = "", str2 ="";
	for (j = 0; j < 2; j++) {
		if (username == allEmployees[j][0] && password == allEmployees[j][1]) {
			//alert ("Login successfully");
			// Set current user name and access level:			
			uempid = allEmployees[j][2];
			ulevel = allEmployees[j][3];
			window.g_user_name = username;
			window.g_user_level = ulevel;
			sessionStorage.setItem("g_user_name", username);
			sessionStorage.setItem("g_user_empid", uempid);
			sessionStorage.setItem("g_user_level", ulevel);
			window.location.replace("prjmgt_home.html")
			return false;
		}
	}
	// No match found, decrement attempt and return.
	attempt --;
	alert("You have "+attempt+" attempts remaining!;");
	// Disabling fields after 4 attempts.
	if (attempt == 0) {
		document.getElementById("username").disabled = true;
		document.getElementById("password").disabled = true;
		document.getElementById("submit").disabled = true;
		window.g_user_name = "";
		window.g_user_empid = "0";
		window.g_user_level = "0";
		return false;
	}	
}

function DoLogin(showhide) {
	if(showhide == "show") {
		document.getElementById("loginbox").style.visibility="visible";		
	}
	if (showhide == "hide"){
		document.getElementById("loginbox").style.visibility="hidden";		
	}	
}

function DoLogout() {
	sessionStorage.setItem("g_user_name", "");
	sessionStorage.setItem("g_user_empid", "0");
	sessionStorage.setItem("g_user_level", "0");
	window.location.replace("index.html");
}

function GoToPmSystemPage(page_number) {
	window.g_user_name = sessionStorage.getItem("g_user_name");
	window.g_user_level = sessionStorage.getItem("g_user_level");
	var str1 = "You do not have access rights to the ";
	var str2 = "";
	
	// Check if current user has access rights to the requested page.
	if (window.g_user_level != "2" && page_number != 5) {				
		switch(page_number) {
			case 1:
				str2 = "Projects";
			break;
			case 2:
				str2 = "Customers";
			break;
			case 3:
				str2= "Departments";				
			break;
			case 4:
				str2 = "Employees";				
			break;
			case 5:
				str2 = "Tasks";				
			break;
		}
		str1 = str1.concat(str2, " page!");
		alert(str1);
	}
	else {	
		switch(page_number) {
			case 1:
				location.replace("projects_page.php");
			break;
			case 2:
				location.replace("customers_page.php");
			break;
			case 3:
				location.replace("departments_page.php");
			break;
			case 4:
				location.replace("employees_page.php");
			break;
			case 5:
				location.replace("tasks_page.php");
			break;
		}	
	}
}


