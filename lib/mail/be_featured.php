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
	Practice Name: '.$_POST['pracName'].'<br/>
	Physician Name: '.$_POST['physName'].'<br/>
	E-Mail: '.$_POST['email'].'<br/>
	Phone: '.$_POST['phone'].'<br/>
	Reference Physician #1: '.$_POST['physRef1'].'<br/>
	Reference Physician #1 Phone: '.$_POST['physRef1phone'].'<br/>
	Reference Physician #2: '.$_POST['physRef2'].'<br/>
	Reference Physician #2 Phone: '.$_POST['physRef2phone'].'<br/>
	Reference Physician #3: '.$_POST['physRef3'].'<br/>
	Reference Physician #3 Phone: '.$_POST['physRef3phone'].'<br/>
	Comments: '.$_POST['comments'].
	'</body>
	</html>';

	processMail('My Dr Space - Featured Physician', $message, 'Featured Physician Sign Up');
	
}
?>