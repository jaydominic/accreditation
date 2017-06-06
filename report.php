<?php 
include_once 'header.php';
require_once 'connect.php';

$thisPage = "report.php";
?>
<html>
  <head>
    <title><?php echo $titletext ?></title>
    <!--
    <link rel="stylesheet" href="css/style.css">
    //-->
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
    </style>
    <script type="text/javascript">
		function attendeeReport() {
			window.location.href="report.php?attended=Y";
		}
		
		function nonAttendeeReport() {
			window.location.href="report.php?attended=N";
		}

		function editSchedules() {
			window.location.href="schedules.php";
		}

		function manageRecords() {
			window.location.href="manage.php";
		}
	</script>
  </head>
  <body>
<?php

$mysqli = new mysqli($hostname, $username, $password, $dbname);
if ($mysqli->connect_error )
{
  die('Error: (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}

# Check If Record Exists
if (isset($_REQUEST["so"]) && isset($_REQUEST['attended'])) {
	if ($_REQUEST["so"] == "ENROLLED") {
		if ($_REQUEST['attended']=="Y") {
			$strsql = "SELECT * FROM $usertable WHERE attended='Y' ORDER BY datetimestamp";
		} else {
			$strsql = "SELECT * FROM $usertable WHERE attended='N' ORDER BY datetimestamp";
		}
	} elseif ($_REQUEST["so"] == "FNAME") {
		if ($_REQUEST['attended']=="Y") {
			$strsql = "SELECT * FROM $usertable WHERE attended='Y' ORDER BY fname";
		} else {
			$strsql = "SELECT * FROM $usertable WHERE attended='N' ORDER BY fname";
		}
	} elseif ($_REQUEST["so"] == "LNAME") {
		if ($_REQUEST['attended']=="Y") {
			$strsql = "SELECT * FROM $usertable WHERE attended='Y' ORDER BY lname";
		} else {
			$strsql = "SELECT * FROM $usertable WHERE attended='N' ORDER BY lname";
		}
	} elseif (trim($_GET["so"]) == "CONTACT") {
		if ($_REQUEST['attended']=="Y") {
			$strsql = "SELECT * FROM $usertable WHERE attended='Y' ORDER BY mobile";
		} else {
			$strsql = "SELECT * FROM $usertable WHERE attended='N' ORDER BY mobile";
		}
	} elseif (trim($_GET["so"]) == "EMAIL") {
		if ($_REQUEST['attended']=="Y") {
			$strsql = "SELECT * FROM $usertable WHERE attended='Y' ORDER BY email";
		} else {
			$strsql = "SELECT * FROM $usertable WHERE attended='N'ORDER BY email";
		}
	} elseif (trim($_GET["so"]) == "SCHEDULE") {
		if ($_REQUEST['attended']=="Y") {
			$strsql = "SELECT * FROM $usertable WHERE attended='Y' ORDER BY schedule";
		} else {
			$strsql = "SELECT * FROM $usertable WHERE attended='N' ORDER BY schedule";
		}
	} elseif (trim($_GET["so"]) == "REFERRER") {
		if ($_REQUEST['attended']=="Y") {
			$strsql = "SELECT * FROM $usertable WHERE attended='Y' ORDER BY referrer";
		} else {
			$strsql = "SELECT * FROM $usertable WHERE attended='N' ORDER BY referrer";
		}
	} elseif (trim($_GET["so"]) == "OTHER") {
		if ($_REQUEST['attended']=="Y") {
			$strsql = "SELECT * FROM $usertable WHERE attended='Y' ORDER BY other";
		} else {
			$strsql = "SELECT * FROM $usertable WHERE attended='N' ORDER BY other";
		}
	} else {
		if ($_REQUEST['attended']=="Y") {
			$strsql = "SELECT * FROM $usertable WHERE attended='Y'";
		} else {
			$strsql = "SELECT * FROM $usertable WHERE attended='N'";
		}
	}
} elseif (isset($_REQUEST["so"]) && !isset($_REQUEST['attended'])) {
	if ($_REQUEST["so"] == "ENROLLED") {
		$strsql = "SELECT * FROM $usertable ORDER BY datetimestamp";
	} elseif ($_REQUEST["so"] == "FNAME") {
		$strsql = "SELECT * FROM $usertable ORDER BY fname";
	} elseif ($_REQUEST["so"] == "LNAME") {
		$strsql = "SELECT * FROM $usertable ORDER BY lname";
	} elseif (trim($_GET["so"]) == "CONTACT") {
		$strsql = "SELECT * FROM $usertable ORDER BY mobile";
	} elseif (trim($_GET["so"]) == "EMAIL") {
		$strsql = "SELECT * FROM $usertable ORDER BY email";
	} elseif (trim($_GET["so"]) == "SCHEDULE") {
		$strsql = "SELECT * FROM $usertable ORDER BY schedule";
	} elseif (trim($_GET["so"]) == "REFERRER") {
		$strsql = "SELECT * FROM $usertable ORDER BY referrer";
	} elseif (trim($_GET["so"]) == "OTHER") {
		$strsql = "SELECT * FROM $usertable ORDER BY other";
	} else {
		$strsql = "SELECT * FROM $usertable";
	}
} elseif (!isset($_REQUEST["so"]) && isset($_REQUEST['attended'])) {
	if ($_REQUEST['attended'] == "Y") {
		$strsql = "SELECT * FROM $usertable WHERE attended='Y' ORDER BY datetimestamp";
	} else {
		$strsql = "SELECT * FROM $usertable WHERE attended='N' ORDER BY datetimestamp";
	}
} else {
	$strsql = "SELECT * FROM $usertable ORDER BY datetimestamp";
}

$cols = 11;  // how many columns in the table

$result = $mysqli->query($strsql);

echo "<table>";
if (isset($_REQUEST['attended'])) {
	if ($_REQUEST['attended'] == "Y") {
		echo "<tr><td colspan='" . $cols . "'><h1>LIST OF REGISTRANTS (Attended)</h1><h2>" . $titletext . "</h2></td></tr>";
	} else {
		echo "<tr><td colspan='" . $cols . "'><h1>LIST OF REGISTRANTS (Not Attended)</h1><h2>" . $titletext . "</h2></td></tr>";
	}
} else {
	echo "<tr><td colspan='" . $cols . "'><h1>LIST OF REGISTRANTS</h1><h2>" . $titletext . "</h2></td></tr>";
}

echo "<tr><td colspan='" . $cols . "'>";
//echo "<input type='button' value='Manage Records' onclick='manageRecords()'>";
//echo "&nbsp;&nbsp;";
//echo "<input type='button' value='Edit Preferred Schedule drop-down list' onclick='editSchedules()'>";
//echo "&nbsp;&nbsp;";
if (isset($_REQUEST['attended'])) {
	if ($_REQUEST['attended']=="Y") {
		echo "<input type='button' value='View List of People who have NOT attended' onclick='nonAttendeeReport()'>";
	} else {
		echo "<input type='button' value='View List of People who have attended' onclick='attendeeReport()'>";
	}
} else {
	echo "<input type='button' value='View List of People who have NOT attended' onclick='nonAttendeeReport()'>";
	echo "&nbsp;&nbsp;";
	echo "<input type='button' value='View List of People who have attended' onclick='attendeeReport()'>";
}
	echo "&nbsp;&nbsp;";
echo "</td></tr>";

echo "<tr class='header'>";
echo "<td>No.</td>";
if (isset($_REQUEST['attended'])) {
	if ($_REQUEST['attended']=="Y") {
		echo "<td><a href='". $thisPage . "?so=FNAME&attended=Y'>First Name</a></td>";
		echo "<td>M.I.</td>";
		echo "<td><a href='" . $thisPage . "?so=LNAME&attended=Y'>Last Name</a></td>";
		echo "<td><a href='" . $thisPage . "?so=CONTACT&attended=Y'>Mobile No.</a></td>";
		echo "<td><a href='" . $thisPage . "?so=EMAIL&attended=Y'>E-Mail Address</a></td>";
		echo "<td><a href='" . $thisPage . "?so=SCHEDULE&attended=Y'>Preferred Schedule</a></td>";
		echo "<td><a href='" . $thisPage . "?so=REFERRER&attended=Y'>Referrer</a></td>";
		echo "<td><a href='" . $thisPage . "?so=OTHER&attended=Y'>Referred By</a></td>";
		echo "<td><a href='" . $thisPage . "?so=ATTENDED&attended=Y'>Attended</a></td>";
		echo "<td><a href='" . $thisPage . "?so=ENROLLED&attended=Y'>Enrolled</a></td>";
	} elseif ($_REQUEST['attended']=="N") {
		echo "<td><a href='". $thisPage . "?so=FNAME&attended=N'>First Name</a></td>";
		echo "<td>M.I.</td>";
		echo "<td><a href='" . $thisPage . "?so=LNAME&attended=N'>Last Name</a></td>";
		echo "<td><a href='" . $thisPage . "?so=CONTACT&attended=N'>Mobile No.</a></td>";
		echo "<td><a href='" . $thisPage . "?so=EMAIL&attended=N'>E-Mail Address</a></td>";
		echo "<td><a href='" . $thisPage . "?so=SCHEDULE&attended=N'>Preferred Schedule</a></td>";
		echo "<td><a href='" . $thisPage . "?so=REFERRER&attended=N'>Referrer</a></td>";
		echo "<td><a href='" . $thisPage . "?so=OTHER&attended=N'>Referred By</a></td>";
		echo "<td><a href='" . $thisPage . "?so=ATTENDED&attended=N'>Attended</a></td>";
		echo "<td><a href='" . $thisPage . "?so=ENROLLED&attended=N'>Enrolled</a></td>";
	} else {
		echo "<td><a href='". $thisPage . "?so=FNAME'>First Name</a></td>";
		echo "<td>M.I.</td>";
		echo "<td><a href='" . $thisPage . "?so=LNAME'>Last Name</a></td>";
		echo "<td><a href='" . $thisPage . "?so=CONTACT'>Mobile No.</a></td>";
		echo "<td><a href='" . $thisPage . "?so=EMAIL'>E-Mail Address</a></td>";
		echo "<td><a href='" . $thisPage . "?so=SCHEDULE'>Preferred Schedule</a></td>";
		echo "<td><a href='" . $thisPage . "?so=REFERRER'>Referrer</a></td>";
		echo "<td><a href='" . $thisPage . "?so=OTHER'>Referred By</a></td>";
		echo "<td><a href='" . $thisPage . "?so=ATTENDED'>Attended</a></td>";
		echo "<td><a href='" . $thisPage . "?so=ENROLLED'>Enrolled</a></td>";
	}
} else {
	echo "<td><a href='". $thisPage . "?so=FNAME'>First Name</a></td>";
	echo "<td>M.I.</td>";
	echo "<td><a href='" . $thisPage . "?so=LNAME'>Last Name</a></td>";
	echo "<td><a href='" . $thisPage . "?so=CONTACT'>Mobile No.</a></td>";
	echo "<td><a href='" . $thisPage . "?so=EMAIL'>E-Mail Address</a></td>";
	echo "<td><a href='" . $thisPage . "?so=SCHEDULE'>Preferred Schedule</a></td>";
	echo "<td><a href='" . $thisPage . "?so=REFERRER'>Referrer</a></td>";
	echo "<td><a href='" . $thisPage . "?so=OTHER'>Referred By</a></td>";
	echo "<td><a href='" . $thisPage . "?so=ATTENDED'>Attended</a></td>";
	echo "<td><a href='" . $thisPage . "?so=ENROLLED'>Enrolled</a></td>";
}
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
    echo "</tr>";
  }
} else {
  // no records found
  echo "<tr><td colspan='" . $cols . "' align='center'><b>No data to display</b></td></tr>";
}

$mysqli->close();

echo "</table>";

?>
</body>
</html>

