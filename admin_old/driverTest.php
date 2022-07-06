<style>
    table, th, td {
      border: 1px solid black;
      border-collapse: collapse;
    }
    th, td {
      background-color: #96D4D4;
    }

    table {
        margin-bottom: 10px;
    }
</style>

<?php // Connection
    $servername = "localhost";
    $username = "paulette";
    $password = "sW9TRsJbpdHJjke";
    $dbname = "eagles";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
?>


<?php
    

     // Driver page functions
     // returns mysqli result using SELECT statement
    function Testing_Var_Dump($var){
        global $testing;
        $testing = True;
        if($testing == True){
            var_dump($var);
            echo("<br>");
            echo("<br>");
        }
    }

    function Append_Line(&$buffer,$line) {
        $buffer = $buffer.$line."\r\n";
    }

    function Display_Tables($mysqlIResult) {
        
        $output = "";

        if (mysqli_num_rows($mysqlIResult) > 0){
            
            // for each driver...
            while($arrayRow = mysqli_fetch_assoc($mysqlIResult)){

                if(count($arrayRow) > 0){

                    Append_Line($output,"<table>\r\n<form action='driverTest.php'");
                    

                    // don't echo these columns
                    $forbiddenCols = ["DriverID","UserID","CoDriverID","AddedDateTime","AddedUserID"];

                    foreach ($arrayRow as $col => $val) {

                        if(!in_array($col, $forbiddenCols)){                            

                            Append_Line($output,"<tr><td>".$col."</td>");                            

                            if($col == "CoDriver"){                                
                                Append_Line($output,"<td><select name='test'>");
                                while ($dropdownrow = mysqli_fetch_array($mysqlIResult)){
                                    Append_Line($output,"<option value='" . $dropdownrow['DriverID'] . "'>" . $dropdownrow['Name'] . "</option>");
                                }
                                Append_Line($output,"</td></select>");
                            } 
                            else {
                                Append_Line($output,"<td><input value='".$val."'/>");
                            }                            
                            Append_Line($output,"</td></tr>");

                        }
                    }
                
                    Append_Line($output,"<tr><td colspan=100><input type='submit'/></td></tr>");
                    Append_Line($output,"</form></table>");
                }
                
            }
            //return $output;
        }
        
        return $output;
    }

    function Mysqli_Table ($selectSql) {
        global $conn; 

        $result1 = mysqli_query($conn, $selectSql);
        //Testing_Var_Dump($result1);
        //Check_SQL_Execution($result1);
        return $result1; 

    }


?>

Drivers <br/><br/>

<?php
    
    // BEGIN TEST CODE
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    $selectSql = "SELECT d.*, Co.Name as CoDriver FROM Driver as d LEFT JOIN Driver as Co ON Co.DriverID = d.CoDriverID";

    // Get result from DB
    $result = Mysqli_Table($selectSql);

    //Testing_Var_Dump($result);

    // write foreach here. one for each result
    echo (Display_Tables($result));

    mysqli_free_result($result); 

    // END TEST
?>

