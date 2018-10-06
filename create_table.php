<?php
$servername = "";
$port = "";
$username = "";
$password = "";
$dbname = "";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname, $port);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$sql = "CREATE TABLE metric_data (
`id` varchar(32) NOT NULL,
`time` varchar(25) NOT NULL,
`long` float NOT NULL,
`lat` float NOT NULL,
`speed_gps` float NOT NULL,
`gps_bearing` float NOT NULL,
`accel` float NOT NULL,
`accel_sensor` float NOT NULL,
`speed_obd` float NOT NULL,
`mileage` float NOT NULL
)";

if ($conn->query($sql) === TRUE) {
    echo "Table created successfully";
} else {
    echo "Error creating table: " . $conn->error;
}
$conn->close();
?>
