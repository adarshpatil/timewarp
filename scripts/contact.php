<?php

require_once('recaptchalib.php');

//get private key from file
$filename = '../../../captcha_private_key.txt';
$file = realpath($filename);
$fileHandle = fopen($file, "r") or die("Unable to captcha key open file!");
$privatekey = fgets($fileHandle);
fclose($fileHandle);

$resp = recaptcha_check_answer ($privatekey,
                                $_SERVER["REMOTE_ADDR"],
                                $_POST["recaptcha_challenge_field"],
                                $_POST["recaptcha_response_field"]);

if (!$resp->is_valid) {
	// What happens when the CAPTCHA was entered incorrectly
	echo '<script type="text/javascript">
		window.onload = function () { alert("Pssst..That CAPTCHA was incorrect. Try again"); history.back() }
	      </script>';
}
else {
	$EmailFrom = "timewarp-auto@adarshpatil.in";
	$EmailTo = "me@adarshpatil.in";
	$subject = "CONTACT from adarshpatil.in";
	$name = Trim(stripslashes($_POST['name'])); 
	$email = Trim(stripslashes($_POST['email'])); 
	$message = Trim(stripslashes($_POST['cf_message'])); 

	// prepare email body text
	$body = "";
	$body .= "Name: ";
	$body .= $name;
	$body .= "\n";
	$body .= "Email: ";
	$body .= $email;
	$body .= "\n";
	$body .= "Message: ";
	$body .= $message;
	$body .= "\n";

	$body = wordwrap($body,70);

	// send email 
	$success = mail($EmailTo, $subject, $body, "From: <$EmailFrom>");

	// redirect to success page 
	if ($success){
	echo '<script type="text/javascript">
          	window.onload = function () { alert("Yipeee, I heard you! I will get back to you as soon as possible.."); history.back() }
	     </script>';
	}
	else{
	echo '<script type="text/javascript">
          	window.onload = function () { alert("Thanks! But something went wrong, try again later.."); history.back() }
	      </script>';
	}
}
?>
