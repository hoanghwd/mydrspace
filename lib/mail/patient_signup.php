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
	First Name: '.$_POST['firstName'].'<br/>
	Last Name: '.$_POST['lastName'].'<br/>
	Address: '.$_POST['address1'].'<br/>
	Apt/Suite: '.$_POST['address2'].'<br/>
	City: '.$_POST['city'].'<br/>
	State: '.$_POST['state'].'<br/>
	Zip: '.$_POST['zip'].'<br/>
	Home Phone: '.$_POST['homePhone'].'<br/>
	Alternate Phone: '.$_POST['altPhone'].'<br/>
	E-Mail: '.$_POST['email'].'<br/>
	Treating Doctor: '.$_POST['treatDr'].'<br/>
	Insurance Type: '.$_POST['insType'].'<br/>
	Insurance Company: '.$_POST['insCo'].'<br/>
	Login: '.$_POST['login'].'<br/>
	Password: '.$_POST['password'].
	'</body>
	</html>';

	processMail('My Dr Space - Patient Sign Up', $message, 'Patient Sign Up');
	
}
?>