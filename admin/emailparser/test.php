<html>
<head><title>TEST PARSER</title></head>

<?php

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

//custom "warning handler" curtesy of
//https://stackoverflow.com/questions/1241728/can-i-try-catch-a-warning
set_error_handler("warning_handler");

function warning_handler($errno, $errstr) { 
	echo "Warning/Error! #";
	echo htmlspecialchars($errno).": ".htmlspecialchars($errstr)."<br>";
	echo "Current directory: '".htmlspecialchars(getcwd())."'<br>";
}

//restore_error_handler();


//include("PlancakeEmailParser.php");
//begin include.

/*************************************************************************************
* ===================================================================================*
* Software by: Danyuki Software Limited                                              *
* This file is part of Plancake.                                                     *
*                                                                                    *
* Copyright 2009-2010-2011 by:     Danyuki Software Limited                          *
* Support, News, Updates at:  http://www.plancake.com                                *
* Licensed under the LGPL version 3 license.                                         *                                                       *
* Danyuki Software Limited is registered in England and Wales (Company No. 07554549) *
**************************************************************************************
* Plancake is distributed in the hope that it will be useful,                        *
* but WITHOUT ANY WARRANTY; without even the implied warranty of                     *
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the                      *
* GNU Lesser General Public License v3.0 for more details.                           *
*                                                                                    *
* You should have received a copy of the GNU Lesser General Public License           *
* along with this program.  If not, see <http://www.gnu.org/licenses/>.              *
*                                                                                    *
**************************************************************************************
*
* Valuable contributions by:
* - Chris 
*
* **************************************************************************************/

/**
 * Extracts the headers and the body of an email
 * Obviously it can't extract the bcc header because it doesn't appear in the content
 * of the email.
 *
 * N.B.: if you deal with non-English languages, we recommend you install the IMAP PHP extension:
 * the Plancake PHP Email Parser will detect it and used it automatically for better results.
 * 
 * For more info, check:
 * https://github.com/plancake/official-library-php-email-parser
 * 
 * @author dan
 */
class PlancakeEmailParser {

    const PLAINTEXT = 1;
    const HTML = 2;

    /**
     *
     * @var boolean
     */    
    private $isImapExtensionAvailable = false;
    
    /**
     *
     * @var string
     */
    private $emailRawContent;

    /**
     *
     * @var associative array
     */
    protected $rawFields;

    /**
     *
     * @var array of string (each element is a line)
     */
    protected $rawBodyLines;

    /**
     *
     * @param string $emailRawContent
     */
    public function  __construct($emailRawContent) {
        $this->emailRawContent = $emailRawContent;

        $this->extractHeadersAndRawBody();
        
        if (function_exists('imap_open')) {
            $this->isImapExtensionAvailable = true;
        }
    }

    private function extractHeadersAndRawBody()
    {
        $lines = preg_split("/(\r?\n|\r)/", $this->emailRawContent);

        $currentHeader = '';

        $i = 0;
        foreach ($lines as $line)
        {
            if(self::isNewLine($line))
            {
                // end of headers
                $this->rawBodyLines = array_slice($lines, $i);
                break;
            }
            
            //consider removing htmlspecialchars?
            if ($this->isLineStartingWithPrintableChar($line)) // start of new header
            {
                preg_match('/([^:]+): ?(.*)$/', $line, $matches);
                $newHeader = strtolower($matches[1]);
                $value = $matches[2];
                $this->rawFields[$newHeader] = htmlspecialchars($value);
                $currentHeader = $newHeader;
            }
            else // more lines related to the current header
            {
                if ($currentHeader) { // to prevent notice from empty lines
                    $this->rawFields[$currentHeader] .= substr($line, 1);
                }
            }
            $i++;
        }
    }

    /**
     *
     * @return string (in UTF-8 format)
     * @throws Exception if a subject header is not found
     */
    public function getSubject()
    {
        
        if (!isset($this->rawFields['subject']))
        {
            throw new Exception("Couldn't find the subject of the email");
        }
        
        $ret = '';
        
        if ($this->isImapExtensionAvailable) {
            foreach (imap_mime_header_decode($this->rawFields['subject']) as $h) { // subject can span into several lines
                $charset = ($h->charset == 'default') ? 'US-ASCII' : $h->charset;
                $ret .=  iconv($charset, "UTF-8//TRANSLIT", $h->text);
            }
        } else {
            $ret = utf8_encode(iconv_mime_decode($this->rawFields['subject']));
        }
        
        return $ret;
    }

    /**
     *
     * @return array
     */
    public function getCc()
    {
        if (!isset($this->rawFields['cc']))
        {
            return array();
        }

        return explode(',', $this->rawFields['cc']);
    }

    /**
     *
     * @return array
     * @throws Exception if a to header is not found or if there are no recipient
     */
    public function getTo()
    {
        //var_dump($this);
        //var_dump($rawFields);
        var_dump($this->rawFields['to']);

        //if (!is_array($this->rawFields['to'])){
        //    return $this->rawFields['to'];
        //}

        //if TO is empty, throw e
        if (!isset($this->rawFields['to']))
        {
            throw new Exception("Couldn't find the recipients of the email");
        }
        
        //return array
        return explode(',', $this->rawFields['to']);
    }
	
    /**
     *
     * @return bool
     */
    public function isDeadheadEmail(){

        $ret = false;

        if (str_contains($this->getSubject(), 'Receipt for Panther PRO')) { 
            //if(str_contains($this->getFromEmail(),'panthertruckreceipts@arcb.com')){
                $ret = true;    
            //}
        }
        return $ret;        
    }

    /**
     *
     * @return string|false
     */
    public function getFromEmail()
    {
        $from = self::getHeader("From");
        $pattern = '/<(.*?)>/s';
        preg_match($pattern, $from, $matches);
        return $this->rawFields['from'];
        //return (count($matches) == 2) ? $matches[1] : false;
    }

    /**
     * return string - UTF8 encoded
     * 
     * Example of an email body
     * 
        --0016e65b5ec22721580487cb20fd
        Content-Type: text/plain; charset=ISO-8859-1

        Hi all. I am new to Android development.
        Please help me.

        --
        My signature

        email: myemail@gmail.com
        web: http://www.example.com

        --0016e65b5ec22721580487cb20fd
        Content-Type: text/html; charset=ISO-8859-1
     */
    public function getBody($returnType=self::PLAINTEXT)
    {
        $body = '';
        $detectedContentType = false;
        $contentTransferEncoding = null;
        $charset = 'ASCII';
        $waitingForContentStart = true;

        if ($returnType == self::HTML)
            $contentTypeRegex = '/^Content-Type: ?text\/html/i';
        else
            $contentTypeRegex = '/^Content-Type: ?text\/plain/i';
        
        // there could be more than one boundary
        preg_match_all('!boundary=(.*)$!mi', $this->emailRawContent, $matches);
        $boundaries = $matches[1];
        // sometimes boundaries are delimited by quotes - we want to remove them
        foreach($boundaries as $i => $v) {
            $boundaries[$i] = str_replace(array("'", '"'), '', $v);
        }
        
        foreach ($this->rawBodyLines as $line) {
            if (!$detectedContentType) {
                
                if (preg_match($contentTypeRegex, $line, $matches)) {
                    $detectedContentType = true;
                }
                
                if(preg_match('/charset=(.*)/i', $line, $matches)) {
                    $charset = strtoupper(trim($matches[1], '"')); 
                }       
                
            } else if ($detectedContentType && $waitingForContentStart) {
                
                if(preg_match('/charset=(.*)/i', $line, $matches)) {
                    $charset = strtoupper(trim($matches[1], '"')); 
                }                 
                
                if ($contentTransferEncoding == null && preg_match('/^Content-Transfer-Encoding: ?(.*)/i', $line, $matches)) {
                    $contentTransferEncoding = $matches[1];
                }                
                
                if (self::isNewLine($line)) {
                    $waitingForContentStart = false;
                }
            } else {  // ($detectedContentType && !$waitingForContentStart)
                // collecting the actual content until we find the delimiter
                
                // if the delimited is AAAAA, the line will be --AAAAA  - that's why we use substr
                if (is_array($boundaries)) {
                    if (in_array(substr($line, 2), $boundaries)) {  // found the delimiter
                        break;
                    }
                }
                $body .= $line . "\n";
            }
        }

        if (!$detectedContentType)
        {
            // if here, we missed the text/plain content-type (probably it was
            // in the header), thus we assume the whole body is what we are after
            $body = implode("\n", $this->rawBodyLines);
        }

        // removing trailing new lines
        $body = preg_replace('/((\r?\n)*)$/', '', $body);

        if ($contentTransferEncoding == 'base64')
            $body = base64_decode($body);
        else if ($contentTransferEncoding == 'quoted-printable')
            $body = quoted_printable_decode($body);        
        
        if($charset != 'UTF-8') {
            // FORMAT=FLOWED, despite being popular in emails, it is not
            // supported by iconv
            $charset = str_replace("FORMAT=FLOWED", "", $charset);
           
	    $bodyCopy = $body; 
            $body = iconv($charset, 'UTF-8//TRANSLIT', $body);
            
            if ($body === FALSE) { // iconv returns FALSE on failure
                $body = utf8_encode($bodyCopy);
            }
        }

        //var_dump($body);
        return $body;
    }

    /**
     * @return string - UTF8 encoded
     * 
     */
    public function getPlainBody()
    {
        return $this->getBody(self::PLAINTEXT);
    }

    /**
     * return string - UTF8 encoded
     */
    public function getHTMLBody()
    {
        return $this->getBody(self::HTML);
    }

    /**
     * N.B.: if the header doesn't exist an empty string is returned
     *
     * @param string $headerName - the header we want to retrieve
     * @return string - the value of the header
     */
    public function getHeader($headerName)
    {
        $headerName = strtolower($headerName);

        if (isset($this->rawFields[$headerName]))
        {
            return $this->rawFields[$headerName];
        }
        return '';
    }

    /**
     *
     * @param string $line
     * @return boolean
     */
    public static function isNewLine($line)
    {
        $line = str_replace("\r", '', $line);
        $line = str_replace("\n", '', $line);

        return (strlen($line) === 0);
    }

    /**
     *
     * @param string $line
     * @return boolean
     */
    private function isLineStartingWithPrintableChar($line)
    {
        return preg_match('/^[A-Za-z]/', $line);
    }
}

// end include.




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


$path = __DIR__."/../../../mail/.panther@eaglex_llc/cur/";
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