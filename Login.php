<?php

@$LoginUsername = strtolower($_POST['LoginUsername']);
@$LoginPassword = $_POST['LoginPassword'];
@$LoginSubmit = $_POST['LoginSubmit'];
@$LoginRememberMe = $_POST['LoginRememberMe'];
@$LoginError = "";

if ($LoginSubmit) {
	if ($LoginUsername&&$LoginPassword) {
		
		include 'dbConnect.php';
		$Query = mysql_query("SELECT * FROM members WHERE Username='$LoginUsername'");
		$NumRows = mysql_num_rows($Query);

		if ($NumRows == 1) {
		
			while ($Row = mysql_fetch_assoc($Query)){
				
				@$DbUsername = $Row['Username'];
				@$DbPassword = $Row['Password'];
				@$DbId = $Row['Id'];
				@$DbActive = $Row['Active'];
			}

			// check to see if they match!i
			if ($LoginUsername==$DbUsername&&sha1($LoginPassword)==$DbPassword){
				if ($DbActive == 1) {
					if ($LoginRememberMe) {
						$_SESSION['Username'] = $LoginUsername;
						$_SESSION['Id'] = $DbId;
						setcookie("Username",$LoginUsername,time()+3600);
						setcookie("Password",$LoginPassword,time()+3600);
						header( 'Location: Cart.php' );
					}else{
						$_SESSION['Username'] = $LoginUsername;
						$_SESSION['Id'] = $DbId;
						header( 'Location: Cart.php' );
					}
					
				}else{
					$LoginError = "- <small>This account is not active!</small>";
				}
		
			}else{
				$LoginError = "- <small>Those details are incorrect!</small>";
			}

		}else{
			$LoginError = "- <small>Those details are incorrect!</small>";
		}

	}else{
		$LoginError = "- <small>Please fill in all fields!</small>";
	}
	
}

?>