<?php 

include_once "header.php";
require_once "connect.php";

$thisPage = "manage.php";
?>
<html>
  <head>
    <title><?php echo $titletext ?></title>
    <style type="text/css">
      table {
          border-collapse: collapse;
          width: 100%;
      }      
      table, tr, td {
        border: 2px solid black;
      }
      tr.header {
        font-style: italic;
        font-weight: bold;
        text-align: center;
      }
      tr.rowdata {
        text-align: center;
      }
      .longbutton {
      	width: 120px;
      }
    </style>
    <script type="text/javascript">
		function deleteRecord(rowid) {
			var res = confirm("Confirm you want to delete this record by click on OK.");
			if(res == false) {
				document.getElementById('operation').value = "VIEW";
				return false;
			} else {
				document.getElementById('operation').value = "DELETE";
				document.getElementById('rowid').value = rowid;
				document.getElementById('frmMain').method = "POST";
				document.getElementById('frmMain').action = "<?php echo $thisPage ?>";
				document.getElementById('frmMain').submit();
			}
		}

		function markAttendance(rowid) {
			document.getElementById('operation').value = "ATTENDED";
			document.getElementById('rowid').value = rowid;
			document.getElementById('frmMain').method = "POST";
			document.getElementById('frmMain').action = "<?php echo $thisPage ?>";
			document.getElementById('frmMain').submit();
		}

		function unMarkAttendance(rowid) {
			document.getElementById('operation').value = "UNATTEND";
			document.getElementById('rowid').value = rowid;
			document.getElementById('frmMain').method = "POST";
			document.getElementById('frmMain').action = "<?php echo $thisPage ?>";
			document.getElementById('frmMain').submit();
		}

		function attendeeReport() {
			window.location.href="report.php?attended=Y";
		}
		
		function nonAttendeeReport() {
			window.location.href="report.php?attended=N";
		}
		
		function editSchedules() {
			window.location.href="schedules.php";
		}
    </script>
  </head>
  <body>
    <form id="frmMain">
      <input type="hidden" name="operation" id="operation">
      <input type="hidden" name="so" id="so">
      <input type="hidden" name="rowid" id="rowid">
<?php

$mysqli = new mysqli($hostname, $username, $password, $dbname);
if ($mysqli->connect_error )
{
  die('Error: (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}

// Check if a delete operation 
if (isset($_REQUEST["operation"])) {
	if ($_REQUEST["operation"] == "DELETE") {
	
		$strsql = "DELETE FROM $usertable WHERE rowid = " . $_REQUEST['rowid'];
	
	} elseif ($_REQUEST["operation"] == "ATTENDED") {
	
		$strsql = "UPDATE $usertable SET attended = 'Y' WHERE rowid = " . $_REQUEST['rowid'];
	
	} elseif ($_REQUEST["operation"] == "UNATTEND") {
		
		$strsql = "UPDATE $usertable SET attended = 'N' WHERE rowid = " . $_REQUEST['rowid'];
		
	} else {
		// Check If Record Exists
		if (isset($_REQUEST["so"])) {
			if ($_REQUEST["so"] == "ENROLLED") {
				$strsql = "SELECT * FROM $usertable ORDER BY datetimestamp";
			}
			elseif ($_REQUEST["so"] == "FNAME") {
				$strsql = "SELECT * FROM $usertable ORDER BY fname";
			}
			elseif ($_REQUEST["so"] == "LNAME") {
				$strsql = "SELECT * FROM $usertable ORDER BY lname";
			}
			elseif (trim($_GET["so"]) == "CONTACT") {
				$strsql = "SELECT * FROM $usertable ORDER BY mobile";
			}
			elseif (trim($_GET["so"]) == "EMAIL") {
				$strsql = "SELECT * FROM $usertable ORDER BY email";
			}
			elseif (trim($_GET["so"]) == "SCHEDULE") {
				$strsql = "SELECT * FROM $usertable ORDER BY schedule";
			}
			elseif (trim($_GET["so"]) == "ATTENDED") {
				$strsql = "SELECT * FROM $usertable ORDER BY attended,fname,lname";
			}
			elseif (trim($_GET["so"]) == "REFERRER") {
				$strsql = "SELECT * FROM $usertable ORDER BY referrer";
			}
			elseif (trim($_GET["so"]) == "OTHER") {
				$strsql = "SELECT * FROM $usertable ORDER BY other";
			}
			else {
				$strsql = "SELECT * FROM $usertable ORDER BY datetimestamp";
			}
		} else {
			$strsql = "SELECT * FROM $usertable";
		}
	}
} else {
	// just VIEW
	$strsql = "SELECT * FROM $usertable";
}


if (!isset($_REQUEST['operation'])) {
	// default to VIEW
	$operation = "VIEW";
	$result = $mysqli->query($strsql);
} else {
	// check if either VIEW, UPDATE or DELETE operation
	if (($_REQUEST['operation'] == "DELETE") || ($_REQUEST['operation'] == "ATTENDED")  || ($_REQUEST['operation'] == "UNATTEND")) {
		$result = $mysqli->query($strsql);
		$strsql = "SELECT * FROM $usertable";
		$result = $mysqli->query($strsql);
	} else {
		$result = $mysqli->query($strsql);
	}
	$operation = $_REQUEST['operation'];
}

$cols = 13;  // how many columns in the table

echo "<table>";
echo "<tr><td colspan='" . $cols . "'><h1>LIST OF REGISTRANTS</h1><h2>" . $titletext . "</h2></td></tr>";

echo "<tr><td colspan='" . $cols . "'>";
echo "<input type='button' value='Edit Preferred Schedule drop-down list' onclick='editSchedules()'>";
echo "&nbsp;&nbsp;";
echo "<input type='button' value='View List of People who have attended' onclick='attendeeReport()'>";
echo "&nbsp;&nbsp;";
echo "<input type='button' value='View List of People who have NOT attended' onclick='nonAttendeeReport()'>";
echo "</td></tr>";

echo "<tr class='header'>";
echo "<td>No.</td>";
echo "<td><a href='" . $thisPage . "?so=FNAME&operation=" . $operation . "'>First Name</a></td>";
echo "<td>M.I.</td>";
echo "<td><a href='" . $thisPage . "?so=LNAME&operation=" . $operation . "'>Last Name</a></td>";
echo "<td><a href='" . $thisPage . "?so=CONTACT&operation=" . $operation . "'>Mobile No.</a></td>";
echo "<td><a href='" . $thisPage . "?so=EMAIL&operation=" . $operation . "'>E-Mail Address</a></td>";
echo "<td><a href='" . $thisPage . "?so=SCHEDULE&operation=" . $operation . "'>Preferred Schedule</a></td>";
echo "<td><a href='" . $thisPage . "?so=REFERRER&operation=" . $operation . "'>Referrer</a></td>";
echo "<td><a href='" . $thisPage . "?so=OTHER&operation=" . $operation . "'>Referred By</a></td>";
echo "<td><a href='" . $thisPage . "?so=ATTENDED&operation=" . $operation . "'>Attended</a></td>";
echo "<td><a href='" . $thisPage . "?so=ENROLLED&operation=" . $operation . "'>Enrolled</a></td>";
echo "<td colspan='2'>Actions</td>";
echo "</tr>";

$ctr = 0;
if ($result->num_rows > 0)
{
  while($row = $result->fetch_assoc())
  {
    $ctr = $ctr + 1;
    echo "<tr class='rowdata'>";
    echo "<td>" . $ctr . "</td>";
    echo "<td>" . $row["fname"] ."</td>";
    echo "<td>" . $row["minit"] . "</td>";
    echo "<td>" . $row["lname"] . "</td>";
    echo "<td>" . $row["mobile"] . "</td>";
    echo "<td>" . $row["email"] . "</td>";
    echo "<td>" . $row["schedule"] . "</td>";
    echo "<td>" . $row["referrer"] . "</td>";
    echo "<td>" . $row["other"] . "</td>";
    echo "<td>" . $row["attended"] . "</td>";
    echo "<td>" . $row["datetimestamp"] . "</td>";
    if ($row["attended"] == "N") {
	    echo "<td><input type='button' class='longbutton' value='Mark as Attended' onclick='markAttendance(" . $row['rowid']. ")'></td>";
    } else {
    	echo "<td><input type='button' class='longbutton' value='Attended' onclick='unMarkAttendance(" . $row['rowid']. ")'></td>";
    }
	echo "<td><input type='button' value='Delete' onclick='deleteRecord(" . $row['rowid']. ")'></td>";
    echo "</tr>";
  }
} else {
  // no records found
  echo "<tr><td colspan='" . $cols . "' align='center'><b>No data to display</b></td></tr>";
}

$mysqli->close();

echo "</table>";

?>
</form>
</body>
</html>

