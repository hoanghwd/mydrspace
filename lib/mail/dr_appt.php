<?php 
session_start();

include_once $_SERVER['DOCUMENT_ROOT'].'/mail/securimage/securimage.php';

$securimage = new Securimage();
if ($securimage->check($_POST['captcha_code']) == false) 
{
  // the code was incorrect
  // you should handle the error so that the form processor doesn't continue
	 
  // or you can use the following code if there is no validation or you do not know how
  echo "The image code entered is incorrect/blank.";
  
  exit;
}
else
{
	include('mail.php');
	$message =
	'<!doctype html>
	<html>
	<head>
	<meta charset="utf-8">
	<title>Untitled Document</title>
	</head>
	<body>
	Doctor: '.$_POST['appt_dr'].'<br/>
	First Name: '.$_POST['appt_fname'].'<br/>
	Last Name: '.$_POST['appt_lname'].'<br/>
	Phone: '.$_POST['appt_phone'].'<br/>
	E-Mail: '.$_POST['appt_email'].'<br/>
	Preferred Time: '.$_POST['appt_time'].'<br/>
	Preferred Date: '.$_POST['appt_date'].'<br/>
	Comments: '.$_POST['appt_comments'].
	'</body>
	</html>';

	processMail('My Dr Space - Doctor Appointment', $message, 'Doctor Appointment Received');
	
}
?>