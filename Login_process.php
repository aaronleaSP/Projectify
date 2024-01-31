<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    session_start();

    // Connect to the database
    $conn = new mysqli("localhost", "root", "", "login");

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get form data
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT id, username, password FROM tb_user WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            // Login successful
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            header("Location: homepage.php"); // Redirect to the homepage
        } else {
            // Incorrect password
            $_SESSION['error_message'] = "Incorrect password";
            header("Location: login.php");
        }
    } else {
        // User not found
        $_SESSION['error_message'] = "User not found";
        header("Location: login.php");
    }

    // Close the database connection
    $conn->close();
}

?>
