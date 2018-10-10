<?php
require '/home/ubuntu/vendor/autoload.php';

// Program Overview- Developed this PHP custom program to perform the following-
// 		Step 0- Wait for the http request from Torque Pro application
//		Step 1- Read the Telematics data using a GET request
//		Step 2- Convert the time stamp from milli-seconds to human readable timestamp
//		Step 3- Keep checking the Speed data and trigger OBD alert when the speed threshold is reached for any of the vehicles
//		Step 4- Establish connection with RDS MySQL Database to commit the record
//		Step 5- Insert record to the RDS MySQL Database


//Declaring AWS SNS client variable

use Aws\Sns\SnsClient;
$snsclient = SnsClient::factory(array(
    'region'  => 'us-east-1',
    'version' => '2010-03-31'
));

//DB credentials

$servername = "";
$port = "";
$username = "";
$password = "";
$dbname = "";


// Step 1- Read the Telematics data using a GET request

if (sizeof($_GET) > 0) {
$id = $_GET["eml"];
$timestamp= $_GET["time"];
$long = $_GET["kff1005"];
$lat = $_GET["kff1006"];
$speed_gps = $_GET["kff1001"];
$gps_bearing = $_GET["kff1007"];
$accel = $_GET["kff1220"];
$accel_sensor = $_GET["kff1223"];
$speed_obd = $_GET["kd"];
$mileage = $_GET["kff1203"];
}


//Step 2- Convert the time stamp from milli-seconds to human readable timestamp

$time = $timestamp / 1000;
$date = date('Y-m-d H:i:s', $time);

//Step 3- Keep checking the Speed data and trigger OBD alert when the speed threshold is reached for any of the vehicles
// Anomaly notification - overspeeding alert!!!

if ($speed_obd > 70){
$result = $snsclient->publish([
    'Message' => "Overspeeding: $id at $date, speed travelled - $speed_obd kph.",
    'TopicArn' => '',   //Topic ARN name
]);
}

//Step 4- Establish connection with RDS MySQL Database to commit the record
// Create DB connection
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

//Step 5- Insert record to the RDS MySQL Database
//Insert record to DB

$sql = "insert into metric_data values ('$id','$date',$long,$lat,$speed_gps,$gps_bearing,$accel,$accel_sensor,$speed_obd,$mileage)";
if ($conn->query($sql) === TRUE) {
    echo " Record inserted successfully";
} else {
    echo "Error adding record: " . $conn->error;
}

//Closing DB connection

$conn->close();

?>
