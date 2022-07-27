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
$proNumArray = explode("Receipt for Panther PRO ", $emailBody);
echo("PRO ".substr($proNumArray[1],0,11)." <br>");

// grab deadhead rate per mile
$DHRPMArray = explode("DH RPM: ", $emailBody);
echo("DH RPM ".substr($DHRPMArray[1],0,4)." <br>");

// grab deadhead flat
$DHFlatArray = explode("DH FLAT: ", $emailBody);
echo("DH Flat: ".substr($DHFlatArray[1],0,4)." <br>");

// grab deadhead miles
$DHMilesArray = explode("DH MILES: ", $emailBody);
echo("DH Miles: ".substr($DHMilesArray[1],0,3)." <br><br><br>");

//copy from email dir 
//current path is /home/gvnvf61ftx5b/public_html/admin/emailparser
copy("../../../mail/.caleb@eaglex_llc/cur/email:2,S","testmove/email2s");

//get current file path
//echo(getcwd()); // /home/gvnvf61ftx5b/public_html/admin/emailparser

//path to email on server:
// /home/gvnvf61ftx5b/mail/.caleb@eaglex_llc/cur/email:2,S

// relative position on server:
// ../../../mail/.caleb@eaglex_llc/cur/email:2,S

//$dir = new DirectoryIterator(dirname("../../../mail/.caleb@eaglex_llc/cur/"));
//foreach ($dir as $fileinfo) {
//    if (!$fileinfo->isDot()) {
//    	echo($fileinfo->getFilename());
//        //var_dump($fileinfo->getFilename());
//        echo("<br>");
//    }
//}

$path = "../../../mail/.caleb@eaglex_llc/cur/";
foreach(scandir($path) as $file){

	if($file[0] == '.'){continue;}

	if( is_file($path.$file) ){
		echo($file."<br>");
	}else if( is_dir($path.$file) ){
		//do nothing
	}
}


?>