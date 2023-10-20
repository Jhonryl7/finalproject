<?php 
session_start();

include_once("DBUtil.php");

$email = $_POST['email'];
$password = $_POST['password'];

$conn = getConnection();

$sql = "SELECT * FROM informations WHERE email='$email'";
$result = $conn->query($sql);

if($result->num_rows == 1) {
    $row = $result->fetch_assoc();

    if(password_verify($password, $row['password'])) {
         // Password is correct, set session variables

         $_SESSION['email'] = $email;
         $_SESSION['position'] = $row['position'];

         // Redirect based on position
         if($row['position'] == 'user'){
            header("Location: user_dashboard.php");
            exit();
         }elseif ($row['position'] == 'admin'){
            header("Location: NiceAdmin/index.php");
            exit();
         }
    }else{
        echo "Incorrect password. <a href='login.php'>Go back to login</a>";
    }
}else{
    echo "User not found. <a href='login.php'>Go back to login</a>";
}

closeConnection($conn);
?>
