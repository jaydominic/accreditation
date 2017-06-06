<?php

include_once "header.php";
require_once "connect.php";

$thisPage = "schedules.php";
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
      tr.entrydata {
        text-align: left;
        background-color: skyblue;
      }
      td.fullname {
        width: 200px;
      }
      td.phone {
        width: 100px;
      }
      td.email {
        width: 150px;
      }
    </style>
    <script type="text/javascript">
		function removeRec(rowid, accred_date, accred_sched) {
			var str = "<?php echo $thisPage ?>?operation=DELETE&rowid=" + rowid;
			res = confirm("Are you sure you want to remove schedule " + accred_date + " (" + accred_sched + ")?\nChanges will take effect immediately!");
			if (res==true) {
				// alert(str);
				window.location.href=str;
			} else {
				return false;
			}
		}

		function addRec() {
			if (document.getElementById("accred_date").value == "") {
				alert("Select a date first.");
				document.getElementById("accred_date").focus();
				return false;
			}
			if (document.getElementById("accred_sched").value == "") {
				alert("Specify the start and end time for this event.");
				document.getElementById("accred_sched").focus();
				return false;
			}
			var str = "<?php echo $thisPage ?>?operation=INSERT&accred_date=" + 
					encodeURIComponent(document.getElementById("accred_date").value) + 
					"&accred_sched=" + encodeURIComponent(document.getElementById("accred_sched").value);
			// alert(str);
			window.location.href=str;
		}

		function attendeeReport() {
			window.location.href="report.php?attended=Y";
		}
		
		function nonAttendeeReport() {
			window.location.href="report.php?attended=N";
		}

		function manageRecords() {
			window.location.href="manage.php";
		}
	</script>
    <script type="text/javascript" src="datetimepicker_css.js"></script>
  </head>
  <body>
    <form id="frmMain">
<?php 

$mysqli = new mysqli($hostname, $username, $password, $dbname);
if ($mysqli->connect_error )
{
	die('Error: (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}

if (!isset($_REQUEST['operation'])) {
	// default to VIEW
	$strsql = "SELECT * FROM $schedtable ORDER BY accred_date, accred_sched";
	$result = $mysqli->query($strsql);
} else {
	// check if either INSERT, DELETE or VIEW operation
	if (($_REQUEST['operation'] == "DELETE") && isset($_REQUEST['rowid'])) {
		// perform the DELETE operation
		$strsql = "DELETE FROM $schedtable WHERE rowid = " . $_REQUEST['rowid'];
		$result = $mysqli->query($strsql);
		$strsql = "SELECT * FROM $schedtable ORDER BY accred_date, accred_sched";
		$result = $mysqli->query($strsql);
	} elseif (($_REQUEST['operation'] == "INSERT") && isset($_REQUEST['accred_date']) && isset($_REQUEST['accred_sched'])) {
		// perform the INSERT operation
		$strsql = "INSERT INTO $schedtable (accred_date, accred_sched) VALUES ('" . $_REQUEST['accred_date'] . "', '" . $_REQUEST['accred_sched'] . "')";
		$result = $mysqli->query($strsql);
		// then view the new record
		$strsql = "SELECT * FROM $schedtable ORDER BY accred_date, accred_sched";
		$result = $mysqli->query($strsql);
	} else {  // just VIEW records
		$strsql = "SELECT * FROM $schedtable ORDER BY accred_date, accred_sched";
		$result = $mysqli->query($strsql);
	}
}

$cols = 4;  // how many columns in the table

?>
	<table>
		<tr>
			<td colspan='<?php echo $cols ?>'>
				<h1>ACCREDITATION SCHEDULES</h1><h2><?php echo $titletext ?></h2>
			</td>
		</tr>
		<tr>
			<td colspan='<?php echo $cols ?>'>
				<input type='button' value='Manage Records' onclick='manageRecords()'>&nbsp;&nbsp;
				<input type='button' value='View List of People who have attended' onclick='attendeeReport()'>&nbsp;&nbsp;
				<input type='button' value='View List of People who have NOT attended' onclick='nonAttendeeReport()'>
			</td>
		</tr>

		<tr class='entrydata'>
			<td colspan='<?php echo $cols ?>'>
				<b>Enter New Accreditation Date:</b>&nbsp;&nbsp;
				<input type='text' id='accred_date'>
				<img src='images/cal.gif' onclick="NewCssCal('accred_date','yyyyMMdd')" style='cursor: pointer;' />
			</td>
		</tr>
		<tr class='entrydata'>
			<td colspan='<?php echo $cols ?>'>
				<b>Enter Accreditation Schedule:</b>&nbsp;&nbsp;&nbsp;
				<input type='text' id='accred_sched'>&nbsp;&nbsp;
				<font color='red'>(<b><i>format:</i></b> start time AM/PM - end time AM/PM)</font>
			</td>
		</tr>
		<tr class='entrydata'>
			<td colspan='<?php echo $cols ?>'>
				<input type='button' value='Save this Schedule' onclick='addRec()'>
			</td>
		</tr>

		<tr class='header'>
			<td>No.</td>
			<td>Date</td>
			<td>Schedule</td>
			<td>Action</td>
		</tr>

<?php 

$ctr = 0;
if ($result->num_rows > 0)
{
	while($row = $result->fetch_assoc())
	{
		$ctr = $ctr + 1;
		echo "<tr class='rowdata'>";
		echo "<td>" . $ctr . "</td>";
		echo "<td>" . date_format(date_create($row["accred_date"]),"M d, Y") ."</td>";
		echo "<td>" . $row["accred_sched"] ."</td>";
		echo "<td><input type='button' value='Remove' onclick='removeRec(" . $row["rowid"] . ", \"" . $row["accred_date"] . "\", \"" . $row["accred_sched"] . "\")'></td>";
		echo "</tr>";
	}
	
}
echo "</table>";
$mysqli->close();

?>
    </form>
  </body>
</html>

