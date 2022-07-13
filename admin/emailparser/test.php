<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


	include("PlancakeEmailParser.php");

	//$homeDir = "/home/gvnvf61ftx5b";

	$emailPath = "email.txt";
	
	$emailParser = new PlancakeEmailParser(file_get_contents($emailPath));

// You can use some predefined methods to retrieve headers...
//$emailTo = $emailParser->getTo();
$emailSubject = $emailParser->getSubject();
$emailCc = $emailParser->getCc();
$emailBody =  $emailParser->getBody();
// ... or you can use the 'general purpose' method getHeader()
$emailDeliveredToHeader = $emailParser->getHeader('Delivered-To');

//$emailBody = $emailParser->getPlainBody();

// grab pro number 
$proNum = explode("Receipt for Panther PRO ", $emailBody);
echo("PRO ".substr($proNum[1],0,11)." ");


// grab deadhead rate per mile
$DHRPM = explode("DH RPM: ", $emailBody);
echo("DH RPM ".substr($DHRPM[1],0,4));

?>