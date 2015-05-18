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
	// Your code here to handle a successful verification
	$EmailFrom = "timewarp-auto@adarshpatil.in";
	$EmailTo = "me@adarshpatil.in";
	$subject = "COMMENT on adarshpatil.in BLOG";
	$name = Trim(stripslashes($_POST['name'])); 
	$email = Trim(stripslashes($_POST['email']));
	$url = Trim(stripslashes($_POST['url'])); 
	$comment = Trim(stripslashes($_POST['comment'])); 
	$blog = Trim(stripslashes($_POST['blog']));

	// prepare email body text
	$body = "";
	$body .= "Name: ";
	$body .= $name;
	$body .= "\n";

	$body .= "Email: ";
	$body .= $email;
	$body .= "\n";

	$body .= "URL: ";
	$body .= $url;
	$body .= "\n";

	$body .= "Comment: ";
	$body .= $comment;
	$body .= "\n";

	$body .= "on blog: ";
	$body .= $blog;
	$body .= "\n";

	$body = wordwrap($body,70);

	// send email 
	$success = mail($EmailTo, $subject, $body, "From: <$EmailFrom>");

	// redirect to success page 
	if ($success){
		echo '<script type="text/javascript">
          		window.onload = function () { alert("Thanks! Your comment will_be_posted after review"); history.back() }
		      </script>';
	}
	else{
		echo '<script type="text/javascript">
			window.onload = function () { alert("Thanks! But something went wrong, try again later..."); history.back() }
		      </script>';
	}
	
}

?>
