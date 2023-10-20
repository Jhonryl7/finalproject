<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Page</title>
    <link rel="stylesheet" href="css/user.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
</head>
<body>
    <?php include_once("include/header.php")?><br>

<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "finalDb";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM acitivities ORDER BY date"; // Sort activities by date by default
$result = $conn->query($sql);

$activities = []; 

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $activities[] = $row;
    }
}

$conn->close();
?>



<h1> Welcome to USER PAGE</h1>

<div class="sidebar">
    <div class="center-button">
        <button class="custom-button" onclick="openModal()">Add Activity</button>
    </div>
    <div class="editbtn">
        <button class="custom-button" onclick="openEditModal()">Edit Activity</button>
    </div>
</div>


<div id="myModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h5>Add Activity:</h5>
        <form action="process_activity.php" method="post">
            <label for="activityName">Activity Name:</label>
            <input type="text" name="activityName" required><br>

            <label for="date">Date:</label>
            <input type="date" name="date" required><br>

            <label for "time">Time:</label>
            <input type="time" name="time" required><br>

            <label for="location">Location:</label>
            <input type="text" name="location" required><br>

            <label for="ootd">Outfit to wear:</label>
            <input type="text" name="ootd" required><br>

            <input type="submit" value="Submit">
        </form>
    </div>
</div>

<div class="activity-list">
    <h4>List of to do Activities</h4>
    <div class="scrollable-table">
        <table class="scrollable-table">
            <thead>
                <tr>
                    <th>Activity Name</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Location</th>
                    <th>Outfit to Wear</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php
foreach ($activities as $activity) {
    echo "<tr>";
    echo "<td>" . $activity['activityName'] . "</td>";
    echo "<td>" . $activity['date'] . "</td>";
    echo "<td>" . $activity['time'] . "</td>";
    echo "<td>" . $activity['location'] . "</td>";
    echo "<td>" . $activity['outfitToWear'] . "</td>";
    echo '<td class="action-buttons">';
    echo '<button class="remarks-button" onclick="addRemarks(' . $activity['id'] . ')">Remarks</button>';
    echo '<button class="done-button" onclick="markAsDone(' . $activity['id'] . ')">Done</button>';
    echo '<button class="cancel-button" onclick="cancelActivity(' . $activity['id'] . ')">Cancel</button>';
    echo '</td>';
    echo "</tr>";
}
?>

            </tbody>
        </table>
    </div>
</div>

<div id="remarksModal" class="remarks-modal">
    <div class="remarks-modal-content">
        <span class="close" onclick="closeRemarksModal()">&times;</span>
        <h5>Add Remarks:</h5>
        <!-- Add form for adding remarks -->
        <form id="remarksForm">
            <label for="remarks">Remarks:</label>
            <textarea id="remarks" name="remarks" rows="4" cols="50"></textarea><br>
            <button type="submit">Submit Remarks</button>
        </form>
    </div>
</div>

<?php include_once("include/footer.php")?>


<script>
    function closeRemarksModal() {
        document.getElementById("remarksModal").style.display = "none";
        document.getElementById("remarksForm").reset();
    }

    function openModal() {
        document.querySelector('form').reset();
        document.getElementById("myModal").style.display = "block";
    }

    function closeModal() {
        document.getElementById("myModal").style.display = "none";
    }

    function editActivity(activityName, date, time, location, outfitToWear) {
        const form = document.querySelector('form');
        form.activityName.value = activityName;
        form.date.value = date;
        form.time.value = time;
        form.location.value = location;
        form.ootd.value = outfitToWear;
        
        document.getElementById("myModal").style.display = "block";
    }

    function openEditModal() {
        const form = document.querySelector('form');
        form.activityName.value = "Activity to Edit";
        form.date.value = "2023-10-15";  
        form.time.value = "15:30"; 
        form.location.value = "Location to Edit";
        form.ootd.value = "Outfit to Edit";

        // Open the modal for editing
        document.getElementById("myModal").style.display = "block";
    }


    function addRemarks(activityId) {
    // Get the remarks from the textarea
    var remarks = $("#remarks").val();

    $.post("add_remarks.php", { activityId: activityId, remarks: remarks }, function(data) {
        // Handle the response from the server, if needed
    });
}

function markAsDone(activityId) {
    $.post("mark_as_done.php", { activityId: activityId }, function(data) {
        // Handle the response from the server, if needed
    });
}

function cancelActivity(activityId) {
    $.post("cancel_activity.php", { activityId: activityId }, function(data) {
        // Handle the response from the server, if needed
    });
}
</script>
</body>
</html>
