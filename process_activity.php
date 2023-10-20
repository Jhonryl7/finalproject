<?php
// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "finaldb";

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve form data
$activityName = $_POST['activityName'];
$date = $_POST['date'];
$time = $_POST['time'];
$location = $_POST['location'];
$ootd = $_POST['ootd'];

// Prepare the SQL query
$sql = "INSERT INTO acitivities (activityName, date, time, location, outfitToWear)
        VALUES ('$activityName', '$date', '$time', '$location', '$ootd')";

// Execute the SQL query
if ($conn->query($sql) === TRUE) {
    echo '<script>alert("Record added successfully."); window.location.href = "user_dashboard.php";</script>';
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// Close the database connection
$conn->close();
?>
