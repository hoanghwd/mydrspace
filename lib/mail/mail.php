<?php
function processMail($subject, $message, $from)
{ 
	//'My Dr Space Info Request', $message, 'My Dr Space Contact Form'

	//SMTP needs accurate times, and the PHP time zone MUST be set
	//This should be done in your php.ini, but this is how to do it if you don't have access to that
	date_default_timezone_set('Etc/UTC');

	require 'PHPMailerAutoload.php';

	//Create a new PHPMailer instance
	$mail = new PHPMailer();
	//Tell PHPMailer to use SMTP
	$mail->isSMTP();
	$mail->isHTML(true);
	//Enable SMTP debugging
	// 0 = off (for production use)
	// 1 = client messages
	// 2 = client and server messages
	$mail->SMTPDebug = 0;
	//Ask for HTML-friendly debug output
	$mail->Debugoutput = 'html';

	//Set the hostname of the mail server
	$mail->Host = "pop.domain.com";
	//Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
	$mail->Port = 587;

	//Set the encryption system to use - ssl (deprecated) or tls
	$mail->SMTPSecure = 'tls';
	//Whether to use SMTP authentication q ye
	$mail->SMTPAuth = true;
	//Username to use for SMTP authentication
	$mail->Username = "noreply@mydrspace.com";
	//Password to use for SMTP authentication
	$mail->Password = "Marketing.123";
	//Set who the message is to be sent from
	$mail->setFrom('noreply@mydrspace.com', $from);
	//Set an alternative reply-to address
	//$mail->addReplyTo('replyto@example.com', 'First Last');
	//Set who the message is to be sent to
	$addresses = array('rbondoc@advantagehcs.com','msetayesh@advantagehcs.com','jzacharias@advantagehcs.com');
	foreach($addresses as $address)
	{
	$mail->addAddress($address);
	}
	//Set the subject line
	$mail->Subject = $subject;
	//Read an HTML message body from an external file, convert referenced images to embedded,
	//convert HTML into a basic plain-text alternative body
	$mail->Body = $message;
	//Replace the plain text body with one created manually
	//$mail->AltBody = 'This is a plain-text message body';

/*
//Set the hostname of the mail server
$mail->Host = 'smtp.gmail.com';

//Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
$mail->Port = 587;

//Set the encryption system to use - ssl (deprecated) or tls
$mail->SMTPSecure = 'tls';

//Whether to use SMTP authentication
$mail->SMTPAuth = true;

//Username to use for SMTP authentication - use full email address for gmail
$mail->Username = "ronnie.bondoc@gmail.com";

//Password to use for SMTP authentication
$mail->Password = "0m31g0ngfu";

//Set who the message is to be sent from
$mail->setFrom('ronnie.bondoc@gmail.com', 'Ronnie Bondoc');

//Set an alternative reply-to address
//$mail->addReplyTo('replyto@example.com', 'First Last');

//Set who the message is to be sent to
$mail->addAddress('rbondoc@advantagehcs.com', 'Ron Bondoc');

//Set the subject line
$mail->Subject = $subject;

//Read an HTML message body from an external file, convert referenced images to embedded,
//convert HTML into a basic plain-text alternative body
$mail->Body = $message;


//Replace the plain text body with one created manually
$mail->AltBody = 'This is a plain-text message body';

//Attach an image file
//$mail->addAttachment('images/phpmailer_mini.png');

*/

//send the message, check for errors
	if (!$mail->send()) 
	{
    	echo "Mailer Error: " . $mail->ErrorInfo;
	} 
	else 
	{
    	echo "Message sent!";
	}
}

?>
