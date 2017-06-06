<?php

include_once 'header.php';

if (trim($_POST["fname"]) == "") {
    header('Location:  registration.php');
    exit();
}

if (trim($_POST["lname"]) == "") {
    header('Location:  registration.php');
    exit();
}

if (trim($_POST["minit"]) == "") {
    header('Location:  registration.php');
    exit();
}

if (trim($_POST["mobile"]) == "") {
    header('Location:  registration.php');
    exit();
}

if (trim($_POST["email"]) == "") {
    header('Location:  registration.php');
    exit();
}

if (trim($_POST["schedule_list"]) == "") {
	header('Location:  registration.php');
	exit();
}

if (trim($_POST["schedule_option"]) == "") {
	header('Location:  registration.php');
	exit();
}

if (trim($_POST["schedule_desc"]) == "") {
	header('Location:  registration.php');
	exit();
}
/*
if (trim($_POST["broker"]) == "") {
	header('Location:  registration.php');
	exit();
}
*/

$vmail = htmlspecialchars($_POST["email"]);

$to = "info@driven-group.com, " . $vmail;
//$to = "jaydominic@gmail.com, " . $vmail;

$subject = "REGISTRATION FORM - " . $titletext;

$message = "<h1>" . $titletext . "</h1><br>";
$message .= "<b><i>PERSONAL INFORMATION SUBMITTED</i></b><br>";
$message .= "<b>Name:</b> " . htmlspecialchars($_POST["fname"]) . " " . htmlspecialchars($_POST["minit"]) . " " . htmlspecialchars($_POST["lname"]) . "<br>";
$message .= "<b>Mobile Phone(s):</b> " . htmlspecialchars($_POST["mobile"]) . "<br>";
$message .= "<b>E-mail:</b> " . $vmail . "<br>";
$message .= "<b>Preferred Schedule:</b> " . htmlspecialchars($_POST["schedule_desc"]) . "<br>";
$message .= "<b>How did you learn about this?</b> " . htmlspecialchars($_POST["referrer"]). "<br>";
$message .= "<b>Referrer:</b> " . htmlspecialchars($_POST["other"]). "<br><br>";
$message .= "<b>IMPORTANT REMINDERS!</b><br>";
$message .= "<ul><li><i>Note that you must have at least finished 2nd year college -OR- earned a minimum of 72 units of college education</i></li>";
// $message .= "<li><i>The processing fee will be charged in case of non-appearance/absence by the participant.</i></li>";
// $message .= "<li><i>For participants who do not have any sales yet, the processing fee (due to non-appearance) will be charged to their respective BROKER.</i></li>";
$message .= "<b><i>Thank you for registering and we look forward to seeing you at the event!</i></b><br>";

// Always set content-type when sending HTML email
$headers = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type:text/html;charset=UTF-8' . "\r\n";

// More headers
$headers .= 'From: auto-mailer@submitform.info' . "\r\n";
$headers .= 'Cc: doah1127@gmail.com' . "\r\n";
$headers .= 'Bcc: jaydominic@gmail.com' . "\r\n";

mail($to, $subject, $message, $headers);

//Connect To Database and save the form data
require "connect.php";

$mysqli = new mysqli($hostname, $username, $password, $dbname);
if ($mysqli->connect_error )
{
  die('Error: (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}

//Define variables for form data
$fname = $mysqli->real_escape_string($_POST["fname"]); 
$minit = $mysqli->real_escape_string($_POST["minit"]); 
$lname = $mysqli->real_escape_string($_POST["lname"]); 
$mobile =  $mysqli->real_escape_string($_POST["mobile"]);
$email = $mysqli->real_escape_string($_POST["email"]);
$schedule = $mysqli->real_escape_string($_POST["schedule_desc"]);
$referrer = $mysqli->real_escape_string($_POST["referrer"]);
$other = $mysqli->real_escape_string($_POST["other"]);

$sqlstr = "INSERT INTO $usertable (fname, lname, minit, mobile, email, schedule, referrer, other, attended, datetimestamp) " .
		"VALUES ('$fname', '$lname', '$minit', '$mobile', '$email', '$schedule', '$referrer', '$other', 'N', NOW())";

//echo $sqlstr . "<br><br>";

$retval = $mysqli->query($sqlstr);
if(! $retval )
{
  die('Error: (' . $mysqli->errno . ') ' . $mysqli->error);
}

$mysqli->close();

echo "<br><br><br><center><h2>Thank you for registering!<br>You will be receiving a confirmation email soon.<br>Click <a href='registration.php'>here</a> if you want to register another attendee.</h2></center>";


?>
