<?php

//Variables
@$RegisterUsername = strip_tags(strtolower($_POST['RegisterUsername']));
@$RegisterPassword = strip_tags($_POST['RegisterPassword']);
@$RegisterRetypePassword = strip_tags($_POST['RegisterRetypePassword']);
@$RegisterFirstname = ucfirst(strip_tags(strtolower($_POST['RegisterFirstname'])));
@$RegisterLastname = ucfirst(strip_tags(strtolower($_POST['RegisterLastname'])));
@$RegisterEmail = strip_tags($_POST['RegisterEmail']);
@$RegisterTimeStamp = date('Y-m-d H:i:s');
@$RegisterSubmit = $_POST['RegisterSubmit'];
@$RegisterError = "";


if ($RegisterSubmit) {
	if ($RegisterUsername&&$RegisterPassword&&$RegisterRetypePassword&&$RegisterEmail&&$RegisterFirstname&&$RegisterLastname) {

		include 'dbConnect.php';
		//Username Check
		$query = mysql_query("SELECT * FROM members WHERE Username='$RegisterUsername'");
		$numrows = mysql_num_rows($query);
		//Email Check
		$query2 = mysql_query("SELECT * FROM members WHERE Email='$RegisterEmail'");
		$numrows2 = mysql_num_rows($query2);

		if ($numrows == 0){
			if ($numrows2 == 0){
				if ($RegisterPassword == $RegisterRetypePassword){
					 if (preg_match("/^[^@]*@[^@]*\.[^@]*$/", $RegisterEmail)) {
	              		if (strlen($RegisterUsername) <= 20) {
	              			if (strlen($RegisterPassword) >= 6) {
	              				//Encrypt password
								$RegisterPassword = sha1 ($RegisterPassword);
	              				//Register user
	              				$RegUser = mysql_query("INSERT INTO members(Username, Password, Firstname, Lastname, Email, TimeStamp) VALUES ('$RegisterUsername', '$RegisterPassword', '$RegisterFirstname','$RegisterLastname','$RegisterEmail','$RegisterTimeStamp')");
	              				//Get the inserted ID here to use in the activation email
								$id = mysql_insert_Id();
								//Activation Email
								$to = "$RegisterEmail";
								$from = "!!!!";
								$subject = "Activation Email";
								$message = '<html>
								<body bgcolor="#FFFFFF">
								Hi ' . $RegisterUsername . ',
								<br /><br />
								You must complete this step to activate your account with us.
								<br /><br />
								Please click here to activate now &gt;&gt;
								<a href="http://www.!!!!!!.com/Activation.php?id=' . $id . '">
								ACTIVATE NOW</a>
								<br /><br />
								Your Login Data is as follows: 
								<br /><br />
								E-mail Address: ' . $RegisterEmail . ' <br />
								Password: ' . $RegisterPassword . ' 
								<br /><br /> 
								Thanks for signing up! 
								</body>
								</html>';
								//End of message
								$headers = "From: $from\r\n";
								$headers .= "Content-type: text/html\r\n";
								$to = "$to";
								//Sending the activation email
								//mail($to, $subject, $message, $headers);

								$RegisterError = "- <small>An activaion link has been sent to your email</small>";
					
	              			}else{
	              				$RegisterError = "- <small>Your password must be more than 6 character long!</small>";
	              			}

	              		}else{
	              			$RegisterError = "- <small>Your username must be less than 20 character long!</small>";
	              		}

	   				 }else{
	   				 	$RegisterError = "- <small>That email address is invalid!</small>";
	   				 }
				
				}else{
				$RegisterError = "- <small>Passwords do not match!</small>";
				}

			}else{
				$RegisterError = "- <small>This Email is already in use!</small>";
			}
		
		}else{
			$RegisterError = "- <small>This user already exists!</small>";
		}
		
	}else{
	$RegisterError = "- <small>Please fill in all fields!</small>";
	}

}




?>

