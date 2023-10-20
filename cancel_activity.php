<?php
include_once("DBUtil.php"); 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $activityId = $_POST['id'];

    try {
        
        $db = new PDO("mysql:host=localhost;dbname=finaldb", "root", "");

        $stmt = $db->prepare("UPDATE acitivities SET status = 'canceled' WHERE id = ?");
        $stmt->execute([$activityId]);

        
    } catch (PDOException $e) {
        
        echo "Error: " . $e->getMessage();
    }
}
?>
