<?php
	include("PlancakeEmailParser.php");

	//$homeDir = "/home/gvnvf61ftx5b";

	$emailPath = "email.txt";
	
	$emailParser = new PlancakeEmailParser(file_get_contents($emailPath));

// You can use some predefined methods to retrieve headers...
$emailTo = $emailParser->getTo();
$emailSubject = $emailParser->getSubject();
$emailCc = $emailParser->getCc();
// ... or you can use the 'general purpose' method getHeader()
$emailDeliveredToHeader = $emailParser->getHeader('Delivered-To');

$emailBody = $emailParser->getPlainBody();

echo($emailBody);




?>