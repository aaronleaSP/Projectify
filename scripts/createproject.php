<?php
$servername = "projectifydb.c5n6aasporw4.ap-southeast-1.rds.amazonaws.com:3306";
$username = "admin";
$password = "spstudent";
$db_name = "projectify";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $db_name);

$redirect = "<meta http-equiv='refresh' content='3;URL=../dashboard.php'><p/>Redirecting you back to dashboard in 3 seconds...";

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error() . $redirect);
}

if (isset($_POST["projectname"]) && isset($_POST["projectdescription"])) {
    $redirect = "<meta http-equiv='refresh' content='3;URL=../dashboard.php'><p/>Redirecting you back to dashboard in 3 seconds...";

    $projectname = $_POST["projectname"];
    $projectdesc = $_POST["projectdescription"];

    $username = $_POST["createProjectUser"];
    $email = $_POST["createProjectEmail"];

    $sql = "INSERT INTO projects_table (project_name, project_desc) VALUES ('$projectname', '$projectdesc')";
    if (mysqli_query($conn, $sql)) {
        $projectid = mysqli_insert_id($conn);

        $sql = "INSERT INTO permissions_table (project_id, user_email, permission_type) VALUES ('$projectid', '$email', 'Owner')";

        if (mysqli_query($conn, $sql)) {
            header("Location: ../project.php?id=$projectid&name=$projectname");
            exit();
        } else {
            die("Create permission failed: " .  mysqli_error($conn) . $redirect);
        }
    } else {
        die("Create project failed: " . mysqli_error($conn) . $redirect);
    }
}
?>