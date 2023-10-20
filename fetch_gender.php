<?php
include_once("DBUtil.php");

$conn = getConnection();

$sql = "SELECT gender, COUNT(*) as count FROM informations GROUP BY gender";
$result = $conn->query($sql);

$genders = [];
$count = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $genders[] = $row['gender'];
        $count[] = $row['count'];
    }
}

closeConnection($conn);

$genderData = [
    "genders" => $genders,
    "count" => $count
];

echo json_encode($genderData);
?>
