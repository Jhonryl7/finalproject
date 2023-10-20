<?php

include_once("DBUtil.php");

$firstname = $_POST['firstname'];
$lastname = $_POST['lastname'];
$position = $_POST['position'];
$gender = $_POST['gender'];
$status = $_POST['status'];
$email = $_POST['email'];
$password = $_POST['password'];



$conn = getConnection();

$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$sql = "INSERT INTO informations (firstname, lastname, position, gender, status, email, password)
VALUES('$firstname', '$lastname', '$position', '$gender', '$status', '$email', '$hashed_password')";


if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
  } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }

  echo '<script type="text/javascript">
  alert("Registration successful! Click OK to proceed to the login page.");
  window.location.href = "login.php";
</script>';

closeConnection($conn);

?>