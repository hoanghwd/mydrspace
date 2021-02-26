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
	Suite: '.$_POST['address2'].'<br/>
	City: '.$_POST['city'].'<br/>
	State: '.$_POST['state'].'<br/>
	Zip: '.$_POST['zip'].'<br/>
	Email: '.$_POST['email'].'<br/>
	Phone Number: '.$_POST['phone'].'<br/>
	Specialties: '.$_POST['specialities'].'<br/>
	Practice Name: '.$_POST['pracName'].'<br/>
	Education: '.$_POST['educ'].'<br/>
	In-Network Insurances: '.$_POST['inIns'].'<br/>
	Hospital Affiliations: '.$_POST['hospAff'].'<br/>
	Languages: '.$_POST['lang'].'<br/>
	Professional Statement: '.$_POST['proStmt'].
	'</body>
	</html>';

	processMail('My Dr Space - Physician Profile', $message, 'Physician Profile');
	
}
?>