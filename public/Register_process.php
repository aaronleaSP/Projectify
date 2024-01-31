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
    $newUsername = $_POST['newUsername'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $newPassword = password_hash($_POST['newPassword'], PASSWORD_DEFAULT);

    $check_duplicate_sql = "SELECT * FROM tb_user WHERE username = '$newUsername'";
    $result = $conn->query($check_duplicate_sql);

    if ($result->num_rows > 0) {
        // Username already exists
        $_SESSION['error_message'] = "Username already exists. Please choose a different username.";
        header("Location: register.php");
    } else {
        // Insert data into the database
        $insert_sql = "INSERT INTO tb_user (username, email, password, phone) VALUES ('$newUsername', '$email', '$newPassword',$phone)";

        if ($conn->query($insert_sql) === TRUE) {
            $_SESSION['success_message'] = "Registration successful! You can now log in.";
            header("Location: login.php");
        } else {
            $_SESSION['error_message'] = "Error: " . $insert_sql . "<br>" . $conn->error;
            header("Location: register.php");
        }
    }

    // Close the database connection
    $conn->close();
}
?>
