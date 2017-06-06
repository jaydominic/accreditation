<!DOCTYPE html>
<?php 
include_once 'header.php';
require_once "connect.php";

$thisPage = "registration.php";

?>
<html>
  <head>
    <title><?php echo $titletext ?></title>
    <script type="text/javascript">
    function checkForm() {

      if (document.getElementById("fname").value == null || document.getElementById("fname").value == "") {
        alert("Please enter your first name");
        document.getElementById("fname").focus();
        return false;
      }

      if (document.getElementById("minit").value == null || document.getElementById("minit").value == "") {
          alert("Please enter your middle initial");
          document.getElementById("minit").focus();
          return false;
      }

      if (document.getElementById("lname").value == null || document.getElementById("lname").value == "") {
          alert("Please enter your last name");
          document.getElementById("lname").focus();
          return false;
      }

      if (document.getElementById("mobile").value == null || document.getElementById("mobile").value == "") {
          alert("Please enter your contact number(s)");
          document.getElementById("mobile").focus();
          return false;
      }

      if (document.getElementById("email").value == null || document.getElementById("email").value == "") {
          alert("Please enter your email address");
          document.getElementById("email").focus();
          return false;
      }

      if (document.getElementById("schedule_list").selectedIndex == 0) {
          alert("Please select your preferred accreditation schedule");
          document.getElementById("schedule_list").focus();
          return false;
      }

      if (document.getElementById("referrer").selectedIndex == 0) {
          alert("Please specify how you learned about this.");
          document.getElementById("referrer").focus();
          return false;
      }
/*
      if (document.getElementById("other").value == "OTHER") {
          alert("Please specify how you learned about this.");
          document.getElementById("other").value = "";
          document.getElementById("other").focus();
          return false;
      }
*/
      return true;

    }

	function updateOther() {
		var x = document.getElementById("referrer");
		// alert(x.options[x.selectedIndex].value);
		document.getElementById("other").value = x.options[x.selectedIndex].value;
	}
    
    function setSchedule() {
      var x = document.getElementById("schedule_list");
      document.getElementById("schedule_option").value = x.options[x.selectedIndex].value;
      document.getElementById("schedule_desc").value = x.options[x.selectedIndex].innerHTML;
    }
    </script>
  </head>
  <body>
    <center>

      <table width="800" cellpadding="1" cellspacing="1" border="0">
        <tr>
          <td>
            <img src="images/drivenknighs-logo.jpg" border="0" alt="Driven Knights" height="180" width="150" />
          </td>
          <td>
            <h1><font color="#003c9e">REGISTRATION FORM</font></h1>
            <h1><i><strong><font color="#000000"><?php echo $titletext; ?></font></strong></i></h1>
            <b>VENUE:</b>&nbsp;&nbsp;DRIVEN LAB, 600 Cordillera St. Highway Hills, Mandaluyong City
          </td>
        </tr>
      </table>

      <hr>

      <form name="regform" id="regform" action="register.php" method="POST" onsubmit="return checkForm()">

        <table width="800" cellpadding="1" cellspacing="1" border="1" bgcolor="">

          <tr>
            <td colspan="2" align="center"><h2><b>Please Enter Your Personal Information Below</b></h2></td>
          </tr>

          <tr style="background-color: lightblue;">
            <td colspan="2"><b><font color="#FF0000">IMPORTANT REMINDERS!</font>&nbsp;&nbsp;Please read carefully before proceeding:</b><br>
              <ul>
                <li><i>Attendees must have at least attained <font color="#FF0000">2nd year</font> college</b> <b>-OR-</b> earned a minimum of <font color="#FF0000">72 units</font> of college education</i></li>
<!--  
				<li><i>The processing fee will be charged in case of non-appearance/absence by the participant.</i></li>
                <li><i>By registering, attendees are assumed to have informed their assigned BROKER that they will be attending this activity.</i></li>
				<li><i>For participants who do not have any sales yet, the processing fee (due to non-appearance) will be charged to their respective BROKER.</i></li>
-->
                <li><i>All fields with an asterisk (<font color="#FF0000">*</font>) must be filled up.</i></li>
              </ul>
            </td>
          </tr>

          <tr>
            <td width="250">&nbsp;&nbsp;<font color="#FF0000">*</font> <b>First Name:</b></td>
            <td><input type="text" name="fname" id="fname" style="width: 300px;"></td>
          </tr>
          <tr>
            <td>&nbsp;&nbsp;<font color="#FF0000">*</font> <b>Middle Initial:</b></td>
            <td><input type="text" name="minit" id="minit" size="3" maxlength="1" style="width: 30px;"></td>
          </tr>
          <tr>
            <td>&nbsp;&nbsp;<font color="#FF0000">*</font> <b>Last Name:</b></td>
            <td><input type="text" name="lname" id="lname" style="width: 300px;"></td>
          </tr>
          <tr>
            <td>&nbsp;&nbsp;<font color="#FF0000">*</font> <b>Mobile No(s).:</b></td>
            <td><input type="text" name="mobile" id="mobile" style="width: 300px;"></td>
          </tr>
          <tr>
            <td>&nbsp;&nbsp;<font color="#FF0000">*</font> <b>E-Mail:</b>&nbsp;&nbsp;</td>
            <td><input type="text" name="email" id="email" style="width: 300px;"></td>
          </tr>
          <tr>
            <td>&nbsp;&nbsp;<font color="#FF0000">*</font> <b>Preferred Schedule:</b>&nbsp;&nbsp;</td>
            <td>
            	<select name="schedule_list" id="schedule_list" onchange="setSchedule()" style="width: 250px;">
            		<option value="0" selected>&lt;select an accreditation schedule&gt;</option>
<?php 
$mysqli = new mysqli($hostname, $username, $password, $dbname);
if ($mysqli->connect_error )
{
	die('Error: (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}

$strsql = "SELECT * FROM $schedtable ORDER BY accred_date, accred_sched";
$result = $mysqli->query($strsql);

if ($result->num_rows > 0)
{
	while($row = $result->fetch_assoc())
	{
		echo "<option value='" . $row["rowid"] ."'>" . date_format(date_create($row["accred_date"]), "M d, Y") . " (" . $row["accred_sched"] . ")</option>";
	}
}
$mysqli->close();
?>
            	</select>
            	<input type="hidden" name="schedule_option" id="schedule_option" value="">
            	<input type="hidden" name="schedule_desc" id="schedule_desc" value="">
            </td>
          </tr>

          <tr>
            <td>&nbsp;&nbsp;<font color="#FF0000">*</font> <b>How did you learn about this?</b></td>
            <td>
            	<select name="referrer" id="referrer" style="width: 250px;" onchange="updateOther()">
            		<option value="NONE" selected>&lt;select an option&gt;</option>
            		<option value="FRIEND">From a friend</option>
            		<option value="FACEBOOK">Facebook</option>
            		<option value="OLX">OLX</option>
            		<option value="OTHER">Other</option>
            	</select>
          </tr>

          <tr>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;<b>Referrer:</b></td>
            <td><input type="text" name="other" id="other" style="width: 250px;"></td>
          </tr>

          <tr>
            <td colspan="2" align="center"><input type="submit" value="Submit"></td>
          </tr>
        </table>

        <br><br><br><br>

      </form>

    </center>
  </body>
</html>


