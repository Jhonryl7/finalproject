<?php
include_once("DBUtil.php"); 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $activityId = $_POST['id'];
    $remarks = $_POST['remarks'];

    try {

        $db = getDatabaseConnection(); 

        $stmt = $db->prepare("INSERT INTO acitivities (activity_id, remarks) VALUES (?, ?)");
        $stmt->execute([$activityId, $remarks]);

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
