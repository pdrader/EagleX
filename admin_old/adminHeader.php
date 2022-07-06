<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta content="width=device-width, initial-scale=1.0" name="viewport">

	<title>EagleX.LLC</title>
	<meta content="" name="description">
	<meta content="" name="keywords">

	<!-- Favicons -->
	<link href="../assets/img/favicon.png" rel="icon">
	
	<!-- Pure Table CSS -->
	<link rel="stylesheet" href="./admin.css">

</head>

<body>

	<?php 
	// Continue only if User is present in the URL OR in Post vars
	if(isset($_GET["User"])){
		$URLUser = $_GET["User"];
		//$URLUserIsset = isset($URLUser);
		//$GetUserIsset = isset($_GET["User"]);

		//echo("<br>URLUser = ");
		//var_dump($URLUser);

		//echo("<br>Length of URLUser = ");
		//var_dump(strlen($URLUser));

		//echo("<br>URLUserIsset = ");
		//var_dump($URLUserIsset);

		//echo("<br>GetUserIsset = ");
		//var_dump($GetUserIsset);

		// grab the User variable if present in URL
		$user_Is_In_Url_Vars = isset($_GET["User"]);
		//echo("<br>user_Is_In_Url_Vars = ");
		//var_dump($user_Is_In_Url_Vars);

		//echo("<br>user is in url vars AND length of urluser is 32");
		//var_dump(($user_Is_In_Url_Vars && strlen($URLUser) == 32));

		// create a variable if URL var is present
		if(($user_Is_In_Url_Vars && strlen($URLUser) == 32)){
			//echo("<br> We are setting userMD5 now.");
			$userMD5 = trim(htmlspecialchars($_GET["User"]));      
		}

		// show the site only if variable was created
		if(isset($userMD5)){

			//echo("<br>userMD5 = ");
			//var_dump($userMD5);

			// entire page is in this block
			$servername = "localhost";
			$username = "paulette";
			$password = "sW9TRsJbpdHJjke";
			$dbname = "eagles";

			// Create connection
			$conn = new mysqli($servername, $username, $password, $dbname);

			// Check connection
			if ($conn->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			}

			$userQuery = "CALL spGetUserByMD5('".$userMD5."')";
			$userData = $conn->query($userQuery);

			if ($userData->num_rows > 0){
				while($row = $userData->fetch_assoc()) {
					$UserID = $row["UserID"];
					$Admin = $row["Admin"];
					//var_dump($row);
				}

				if(isset($Admin) && $Admin==1){
					displayPage($UserID);
				}
			}
		}
	}







	?>