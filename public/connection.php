<?php
				date_default_timezone_set('Asia/Manila');
	
				$servername = "localhost";
				#$servername = "localhost";
				$username = "root";
				$password = "";
				$dbname = "meter_reading";

				// Create connection
				$conn = mysqli_connect($servername, $username, $password, $dbname);
				// Check connection
				if (!$conn) {
					die("Connection failed: " . mysqli_connect_error());
				}
?>
