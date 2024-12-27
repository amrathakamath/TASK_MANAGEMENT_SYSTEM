<?php
// Database connection
$servername = "localhost"; // Change if necessary
$username = "your_username"; // Your database username
$password = "your_password"; // Your database password
$dbname = "etmsh"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to retrieve attendance log
$sql = "SELECT * FROM attendance_log";
$result = $conn->query($sql);

// Check if there are results
if ($result->num_rows > 0) {
    echo "<h1>Attendance Log</h1>";
    echo "<table border='1'>
            <tr>
                <th>Log ID</th>
                <th>Attendance ID</th>
                <th>User ID</th>
                <th>In Time</th>
                <th>Out Time</th>
                <th>Created At</th>
            </tr>";
    
    // Output data of each row
    while($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . $row["log_id"] . "</td>
                <td>" . $row["aten_id"] . "</td>
                <td>" . $row["atn_user_id"] . "</td>
                <td>" . $row["in_time"] . "</td>
                <td>" . $row["out_time"] . "</td>
                <td>" . $row["created_at"] . "</td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "0 results";
}

// Close connection
$conn->close();
?>