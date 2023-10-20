<?php
include_once("DBUtil.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {
    if ($_POST['action'] == 'delete') {
    
        $userId = $_POST['user_id'];
        $conn = getConnection();
        $sql = "DELETE FROM informations WHERE id = $userId";

        if ($conn->query($sql) === TRUE) {
            echo "deleted";
        } else {
            echo "error";
        }

        closeConnection($conn);
        exit;
    } elseif ($_POST['action'] == 'update') {
       
        $userId = $_POST['user_id'];
        $newStatus = $_POST['new_status'];

       
        if ($newStatus === 'active' || $newStatus === 'inactive') {
            $conn = getConnection();
            $sql = "UPDATE informations SET status = '$newStatus' WHERE id = $userId";

            if ($conn->query($sql) === TRUE) {
                echo "updated";
            } else {
                echo "error";
            }

            closeConnection($conn);
        } else {
            echo "invalid";
        }

        exit;
    }
}
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page</title>
    <link rel="stylesheet" href="css/admin.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
</head>
<body>

<?php include_once("include/header.php"); ?>
<div class="admin-header">
        <h1>Welcome to ADMIN PAGE</h1>
    </div>
    
    <div class="page-title">
        <h4>List of Users</h4>
    </div>

<div class="admin-table-container">
    <table class="admin-table">
        <thead>
            <tr>
                <th>User ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Position</th>
                <th>Gender</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            function displayUsers() {
                $conn = getConnection();

                $sql = "SELECT * FROM informations";

                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr id='user_row_" . $row["id"] . "'>";
                        echo "<td>" . $row["id"] . "</td>";
                        echo "<td>" . $row["firstName"] . "</td>";
                        echo "<td>" . $row["lastName"] . "</td>";
                        echo "<td>" . $row["position"] . "</td>";
                        echo "<td>" . $row["gender"] . "</td>";
                        echo "<td>" . $row["status"] . "</td>";
                        echo "<td><button class='edit-btn' onclick='deleteUser(" . $row["id"] . ")'>Delete</button>
                            <button class='delete-btn' onclick='updateUser(" . $row["id"] . ")'>Update</button></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>No users found.</td></tr>";
                }

                closeConnection($conn);
            }

            displayUsers();
            ?>
        </tbody>
    </table>
</div>



<div class="piegraph">
<canvas id="genderChart" width="500" height="200"></canvas>
</div>


<script>
    function deleteUser(userId) {
        var confirmation = confirm("Are you sure you want to delete this user?");
        if (confirmation) {
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4) {
                    if (xhr.status == 200 && xhr.responseText === "deleted") {
                        alert("User deleted successfully.");
                       
                        var row = document.getElementById('user_row_' + userId);
                        row.parentNode.removeChild(row);
                    } else {
                        alert("Failed to delete user.");
                    }
                }
            };
            xhr.open("POST", "", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.send("action=delete&user_id=" + userId);
        }
    }

    function updateUser(userId) {
    var newStatus = prompt("Enter new status (Active/Inactive):");

    if (newStatus === null) {
     
        return;
    }

    newStatus = newStatus.trim().toLowerCase();

    if (newStatus === 'active' || newStatus === 'inactive') {
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4) {
                if (xhr.status == 200 && xhr.responseText === "updated") {
                    alert("Status updated successfully.");
                 
                    location.reload();
                } else {
                    alert("Failed to update status.");
                }
            }
        };
        xhr.open("POST", "", true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.send("action=update&user_id=" + userId + "&new_status=" + newStatus);
    } else {
        alert("Invalid status. Please enter 'Active' or 'Inactive'.");
    }
}

function getGenderDistribution() {
    let genderData = {
        male: 0,
        female: 0,
        others: 0
    };

    <?php
    $conn = getConnection();
    $sql = "SELECT CASE 
                WHEN gender = 'male' THEN 'Male'
                WHEN gender = 'female' THEN 'Female'
                ELSE 'Others'
            END AS gender, COUNT(*) AS count FROM informations GROUP BY gender";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $gender = strtolower($row["gender"]);
            $count = $row["count"];
            echo "genderData['$gender'] = $count;";
        }
    }
    closeConnection($conn);
    ?>

    return genderData;
}


    // Function to create the pie chart
    function createGenderPieChart() {
        let genderData = getGenderDistribution();
        let ctx = document.getElementById('genderChart').getContext('2d');
        
        let chart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Male', 'Female', 'Others'],
                datasets: [{
                    data: [genderData['male'], genderData['female'], genderData['others']],
                    backgroundColor: ['Green', 'Red', 'Yellow']
                }]
            },
            options: {
                     title: {
                     display: true,
                     text: 'User Gender Distribution',
                     fontSize: 30,
                     fontColor: 'black',
                     fontWeight: 'bold' 
            }
        }

        });
    }

    // Call the function to create the chart
    createGenderPieChart();

</script>
<?php include_once("include/footer.php"); ?>
</body>
</html>





