<?php
$servername = "localhost";
$username = "root";
$password = "";
$db_name = "login";

// Create connection
$conn = mysqli_connect($servername, $username, $password);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
echo "Connected successfully\n";


$sql = "CREATE DATABASE IF NOT EXISTS $db_name";
if (mysqli_query($conn, $sql)) {
    echo "Database created successfully";
} else {
    die("Error creating database: " . mysqli_error($conn));
}

mysqli_select_db($conn, $db_name);

$sql = "CREATE TABLE IF NOT EXISTS Products (id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, item VARCHAR(30), price FLOAT)";
if (mysqli_query($conn, $sql)) {
    echo "Table created successfully";
} else {
    die("Error creating Table: " . mysqli_error($conn));
}

$sql = "INSERT INTO Products (item, price) VALUES ('Apple', 2.35)";
if (mysqli_query($conn, $sql)) {
    echo "item created successfully";
} else {
    die("Error creating item: " . mysqli_error($conn));
}

$sql = "INSERT INTO Products (item, price) VALUES ('Banana', 1.50)";
if (mysqli_query($conn, $sql)) {
    echo "item created successfully";
} else {
    die("Error creating item: " . mysqli_error($conn));
}

$sql = "INSERT INTO Products (item, price) VALUES ('Orange', 2.99)";
if (mysqli_query($conn, $sql)) {
    echo "item created successfully";
} else {
    die("Error creating item: " . mysqli_error($conn));
}
