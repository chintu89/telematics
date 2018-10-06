<?php
require '/home/ubuntu/vendor/autoload.php';

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


// Getting metric data from GET request

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


//converting time in milliseconds to readable timestamp

$time = $timestamp / 1000;
$date = date('Y-m-d H:i:s', $time);

// Anamoly notification - overspeeding

if ($_GET["kff1001"] > 80){
$result = $snsclient->publish([
    'Message' => "Overspeeding: $id at $date",
    'TopicArn' => '', // Topic ARN from SNS
]);
}


// Create DB connection
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

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
