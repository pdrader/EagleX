<html>
<head><title>TEST PARSER</title></head>

<?php

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);


	include("PlancakeEmailParser.php");




	//$homeDir = "/home/gvnvf61ftx5b";

//copy from email dir 
//current path is /home/gvnvf61ftx5b/public_html/admin/emailparser
//copy("../../../mail/.caleb@eaglex_llc/cur/email:2,S","testmove/email2s");

//get current file path
//echo(getcwd()); // /home/gvnvf61ftx5b/public_html/admin/emailparser

//path to email on server:
// /home/gvnvf61ftx5b/mail/.caleb@eaglex_llc/cur/email:2,S

// relative position on server:
// ../../../mail/.caleb@eaglex_llc/cur/email:2,S


function isDeadheadEmail($string){
	if(str_contains($string, "Receipt for Panther PRO")){
		return true;
	}
	return false;
}

function coalesceArray($arr){
	$return = "";
	foreach($arr as $var){
		if(!empty(trim($var))){
			$return = trim($var);
			break;
		}
	}
	return $return;
}

function extractAcceptedDate($string){
	
	//LOAD ACCEPTED AT 07-14-22 13:44
	preg_match('/LOAD ACCEPTED AT (\d\d)-(\d\d)-(\d\d)/', $string, $acceptedDateRegexArray1);
	
	//DISP: gazakik  07-15-22 18:58
	preg_match('/DISP:.*(\d\d)-(\d\d)-(\d\d)/', $string, $acceptedDateRegexArray2);

	// return the best one based on possible values:
	
	if (empty($acceptedDateRegexArray1)){
		return $acceptedDateRegexArray2;
	} 
	else{
		return $acceptedDateRegexArray1;
	}
}

function extractProNumber($string){
	preg_match('/Receipt for Panther PRO (\d+)/', $string, $proNumRegexArray);
	return $proNumRegexArray[1];
}

function extractDHRPM($string){
	preg_match('/DH RPM: (\d+\.\d+)/', $string, $DHRPMRegexArray);
	return $DHRPMRegexArray[1];
}

function extractDHMiles($string){
	preg_match('/ESTIMATED DH MILES: (\d+)/', $string, $DHMilesRegexArray);
	return $DHMilesRegexArray[1];
}

function extractDHFlat($string){
	preg_match('/OR DH FLAT: (\d+\.\d+)/', $string, $DHFlatRegexArray);
	return $DHFlatRegexArray[1];
}


$path = "../../../mail/.panther@eaglex_llc/cur/";
foreach(scandir($path) as $file){

	if($file[0] == '.'){continue;}

	if( is_file($path.$file) ){
		
		$emailPath = $path.$file;
		$emailParser = new PlancakeEmailParser(file_get_contents($emailPath));

		// You can use some predefined methods to retrieve headers...
		//$emailTo = $emailParser->getTo();
		$emailSubject = $emailParser->getSubject();
		//$emailCc = $emailParser->getCc();
		$emailBody =  $emailParser->getBody();
		// ... or you can use the 'general purpose' method getHeader()
		//$emailDeliveredToHeader = $emailParser->getHeader('Delivered-To');

		//$emailBody = $emailParser->getPlainBody();			

		if (isDeadHeadEmail($emailParser->getSubject())){

			echo("File name: ".$file."<br>");
			echo("Subject:".$emailSubject."<br>");
			
			// grab pro number
			$proNum = extractProNumber($emailSubject);
			echo("PRO ".$proNum." <br>");
			
			// grab accepted date ARRAY 1=m   2=d    3=y
			$acceptedDateArray = extractAcceptedDate($emailBody);

			//and convert to seconds
			$acceptedDateM = intval($acceptedDateArray[1]);
			$acceptedDateD = intval($acceptedDateArray[2]);
			$acceptedDateY = intval($acceptedDateArray[3]);

			$acceptedDateSeconds = mktime(0, 0, 0, $acceptedDateM, $acceptedDateD, $acceptedDateY);

			//then pass it to the date function
			$acceptedDate = date('Y-m-d H:i:s', $acceptedDateSeconds);
			echo("Accepted Date: ".$acceptedDate." <br>");

			// grab deadhead rate per mile
			$DHRPM = extractDHRPM($emailBody);
			echo("DHRPM: ".$DHRPM." <br>");

			// grab deadhead flat
			$DHFlat = extractDHFlat($emailBody);
			echo("DH Flat: ".$DHFlat." <br>");

			// grab deadhead miles
			$DHMiles = extractDHMiles($emailBody);
			echo("DHMiles: ".$DHMiles." <br>");

			//push to table			
			$servername = "localhost";
			$username = "eaglex_crm";
			$password = "c#6Q;x~x7LAq";
			$dbname = "eaglex_crm";

			// Create connection
			$conn = new mysqli($servername, $username, $password, $dbname);

			// Check connection
			if ($conn->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			}

			while(mysqli_next_result($conn)){;}
			//Create statement to insert ProNumber, RPM, Flat, Miles, FileName, Subject
			$insertQuery = "CALL InsertToDeadhead("
				."'".$proNum."',"
				."'".$acceptedDate."',"
				.$DHRPM.","
				.$DHFlat.","
				.$DHMiles.","
				."'".$file."',"
				."'".$emailSubject."'"
				.")";
			
			//run it
			if ($conn->query($insertQuery) === TRUE) {
			  echo "New record created successfully. ".$insertQuery."<br><br>";
			} else {
			  echo "Error: " . $sql . "<br>" . $conn->error;
			}

			//todo: unique pro number
				//todo: email sent date. make sure it's in range.
					//todo: compare DHRPM to weekly panther rate (in DB)

		}
		

	}else if( is_dir($path.$file) ){
		//do nothing
	}
}


?>

</html>