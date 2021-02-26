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
	Contact: '.$_POST['contactName'].'<br/>
	Telephone Number: '.$_POST['contactTel'].'<br/>
	Email: '.$_POST['contactEmail'].'<br/>
	Message:'
	.$_POST['contactMessage'].
	'</body>
	</html>';

	processMail('My Dr Space Info Request', $message, 'My Dr Space Contact Form');
	
}
?>