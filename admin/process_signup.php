<?php
require_once('../config.php');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mtms_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Your SQL query to get branch_id from branch_list
$getBranchIdQuery = "SELECT id FROM branch_list WHERE name = ?";
$getBranchIdStmt = $conn->prepare($getBranchIdQuery);
$getBranchIdStmt->bind_param("s", $branchName);

// Set parameter and execute to get branch_id
$branchName = $_POST['branch'];
$getBranchIdStmt->execute();
$getBranchIdResult = $getBranchIdStmt->get_result();
$branchData = $getBranchIdResult->fetch_assoc();
$branchId = $branchData['id'];

// Close the get branch_id statement
$getBranchIdStmt->close();

// Your SQL query to insert data into the database
$insertUserDataQuery = "INSERT INTO users (firstname, lastname, username, password, type, phone_number, branch_id, balance)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

// Prepare and bind the statement
$stmt = $conn->prepare($insertUserDataQuery);
$stmt->bind_param("ssssssii", $first_name, $last_name, $username, $password, $type, $mobile_number, $branchId, $balance);

// Set parameters and execute
$type = 1;
$balance = 0;
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$username = $_POST['username'];
$password = md5($_POST['password']); // Use MD5 hashing for consistency
$mobile_number = $_POST['mobile_number'];

if ($stmt->execute()) {
    // Redirect to the login page after successful signup
    header('Location: login.php');
    exit();
} else {
    echo "Error: " . $insertUserDataQuery . "<br>" . $conn->error;
}

// Close the connection
$stmt->close();
$conn->close();
?>