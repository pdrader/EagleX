<?php 

 if(isset($_POST["submit"])){

 	// die();

	$URLUser = $_POST["UserPost"];

	$userMD5 = trim(htmlspecialchars($_POST["UserPost"]));

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

		while(mysqli_next_result($conn)){;}
		$userQuery = "CALL spGetUserByMD5('".$userMD5."')";
		$userData = $conn->query($userQuery);



			while($row = $userData->fetch_assoc()) {
				$UserID = $row["UserID"];
				$Admin = $row["Admin"];
			}

			$table_name = $_POST['table_name'];
		if(strcmp($table_name,'Truck') == 0 ){
			$truck_number = $_POST['truck_number'];
			while(mysqli_next_result($conn)){;}
			$sql = "INSERT INTO ".$table_name." (TruckNumber) VALUES ('".$truck_number."')";

			if ($conn->query($sql) === TRUE) {
			  echo "New record created successfully";
			} else {
			  echo "Error: " . $sql . "<br>" . $conn->error;
			}			
		}
			if(isset($Admin) && $Admin==1){
				displayPage($UserID);
			}

}


include'adminHeader.php';
function displayPage($UserID){
	?>

<main>
	<!--
	--------------------------------------
	----               Trucks
	---------------------------------------
	---->


	<form action="<?php echo $_SERVER['REQUEST_URI'];?>" method="post" >
		<table width=400>
		<input type="hidden" name="table_name" value="Truck">
		<input type="hidden" name="adminAction" value="createTruck">
		<input type="hidden" name="UserPost" value="<?php echo $UserID; ?>">
			<th colspan="100">Add New Truck</th>	
			<tr>
				<td width=200>Number</td>
				<td><input type="text" name="truck_number"></td>
			</tr>

			<tr>
				<td colspan="100"><input type="Submit" value="submit" name="submit"></td>
			</tr>
		
		</table>

	</form>

<?php 
if(!empty($userMD5)){
echo($userMD5);	
}
 ?>

	<!--
	--------------------------------------
	----               Drivers
	---------------------------------------
	---->
	<table width=400>
		<th colspan=100>Add new Driver</th>
		<tr>
			<td>Name</td>
			<td><input type="text" name="driver_name"></td>
		</tr>

		<tr>
			<td>Email Address</td>
			<td><input type="text" name="email"></td>
		</tr>

		<tr>
			<td>Truck Number</td>
			<td>
				<select name="DriverTruckNumber">
					<option value="volvo" name="volvo">Volvo</option>
					<option value="saab" name="saab">Saab</option>
					<option value="mercedes" name="mercedes">Mercedes</option>
					<option value="audi" name="audi">Audi</option>
				</input>
			</td>
		</tr>
	</table>

	<!--
	--------------------------------------
	----               Pros
	---------------------------------------
	---->
	<table width=400>
		<th colspan=100>Add Pro</th>
		<tr>
			<td width=200>Pro Number</td>
			<td><input type="text" name="pro_num"></td>
		</tr>

		<tr>
			<td>Truck</td>
			<td><input type="text" name="truck"></td>
		</tr>

		<tr>
			<td>Driver</td>
			<td><input type="text" name="driver"></td>
		</tr>

		<tr>
			<td>Rate</td>
			<td><input type="text" name="rate"></td>
		</tr>

		<tr>
			<td>LoadedMiles</td>
			<td><input type="text" name="loadedmiles"></td>
		</tr>

		<tr>
			<td>Delivery Date</td>
			<td><input type="text" name="delivery_date"></td>
		</tr>

		<tr>
			<td>PaycheckDate</td>
			<td><input type="text" name="PaycheckDate"></td>
		</tr>

		<tr>
			<td>ACC Bonus</td>
			<td><input type="text" name="acc_bonus"></td>
		</tr>

		<tr>
			<td>ACC Deadhead</td>
			<td><input type="text" name="acc_deadhead"></td>
		</tr>

		<tr>
			<td>ACC Detention</td>
			<td><input type="text" name="acc_detention"></td>
		</tr>

		<tr>
			<td>ACC Handload</td>
			<td><input type="text" name="acc_handload"></td>
		</tr>

		<tr>
			<td>ACC Layover</td>
			<td><input type="text" name="acc_layover"></td>
		</tr>

		<tr>
			<td>ACC Stopoff</td>
			<td><input type="text" name="acc_stopoff"></td>
		</tr>

	</table>

	<!--
	--------------------------------------
	----               Advances
	---------------------------------------
	---->
	<table width=400>
		<th colspan=100>Add Advance:</th>
		
		<tr>
			<td width=200>Advance Amount $$</td>
			<td><input type="text" name="ad_amount"></td>
		</tr>

		<tr>
			<td>Driver</td>
			<td><input type="text" name="advance_driver"></td>
		</tr>

		<tr>
			<td>Miscellaneous $$</td>
			<td><input type="text" name="miscellaneous"></td>
		</tr>

		<tr>
			<td>Paycheck Date</td>
			<td><input type="text" name="paycheck"></td>
		</tr>
	</table>

	<!--
	--------------------------------------
	----               Deductions
	---------------------------------------
	---->
	<table width=400>
		<th colspan=100>Add Deduction:</th>
		<tr>
			<td width=200>Deduction Amount $$</td>
			<td><input type="text" name="deduction_amount"></td>
		</tr>

		<tr>
			<td>Driver</td>
			<td><input type="text" name="driver"></td>
		</tr>

		<tr>
			<td>Occupational</td>
			<td><input type="text" name="occupational"></td>
		</tr>

		<tr>
			<td>Advance Repayment $$</td>
			<td><input type="text" name="advance_repayment"></td>
		</tr>

		<tr>
			<td>Paycheck Date</td>
			<td><input type="text" name="paycheck_date"></td>
		</tr>
	</table>

</main>

<?php }
include'adminFooter.php';?>