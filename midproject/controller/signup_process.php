<?php
// Start session
session_start();

// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "web_tech";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Validate and sanitize input data
$username = sanitize_input($_POST['username']);
$password = sanitize_input($_POST['password']);
$name = sanitize_input($_POST['name']);
$phone = sanitize_input($_POST['phone']);

// Validate input fields
if (empty($username) || empty($password) || empty($name) || empty($phone)) {
    echo "All fields are required";
    exit;
}

// Additional validation for specific fields
if (strlen($username) < 5 || strlen($username) > 20 || !ctype_alnum($username)) {
    echo "Username must be alphanumeric and 5-20 characters long";
    exit;
}

if (strlen($password) < 8 || !preg_match("/[a-z]/", $password) || !preg_match("/[A-Z]/", $password) || !preg_match("/\d/", $password)) {
    echo "Password must be at least 8 characters long and contain at least one lowercase letter, one uppercase letter, and one digit";
    exit;
}

if (!ctype_alpha(str_replace(' ', '', $name))) {
    echo "Name must contain only letters and spaces";
    exit;
}

if (strlen($phone) != 11 || !ctype_digit($phone)) {
    echo "Phone number must be 11 digits long";
    exit;
}

// Hash the password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Insert data into database
$sql = "INSERT INTO login (username, password, name, phone_number) VALUES ('$username', '$hashed_password', '$name', '$phone')";

if ($conn->query($sql) === TRUE) {
    echo "User registered successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// Close connection
$conn->close();

// Function to sanitize input data
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>
