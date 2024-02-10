<!DOCTYPE html>
<?php
$name = $gender = "";
$errMsg = "";
$error = FALSE;

$redirect = "<meta http-equiv='refresh' content='3;URL=dashboard.php'><p/>Redirecting you back to dashboard in 3 seconds...";

$servername = "projectifydb.c5n6aasporw4.ap-southeast-1.rds.amazonaws.com:3306";
$username = "admin";
$password = "spstudent";
$db_name = "projectify";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $db_name);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error() . $redirect);
}


if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (empty($_GET["id"]) || empty($_GET["name"])) {
        echo "Error: Cannot get project id / name!\n";
        echo $redirect;
        return;
    } else {
        $id = $_GET["id"];
        $projectname = $_GET["name"];
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["task"])) {
        $taskname = $_POST["task"];
        $taskstatus = $_POST["taskstatus"];
        $projectname = $_GET["name"];
        $id = $_GET["id"];


        $sql = "INSERT INTO tassks_table (project_id, task_name, task_status) VALUES ('$id', '$taskname', '$taskstatus')";
        if (mysqli_query($conn, $sql)) {
            $taskid = mysqli_insert_id($conn);

        } else {
            die("Create task failed: " . mysqli_error($conn) . $redirect);

        }
    }
}



function retrieveTask($category) {
    global $conn, $id, $redirect;
    $sql = "SELECT * FROM tasks_table WHERE project_id = '$id' AND task_status = '$category'";

    $result = mysqli_query($conn, $sql);
    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            // output data of each row
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<div class='card'>", $row['task_name'], "</div>";
            }
        }
    }
}
?>


<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($projectname);?></title>
    <style>
        .card {
            border: 1px solid #ddd;
            padding: 20px;
            background-color: #f9f9f9; /* Card background color */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Box shadow for 3D effect */
            text-align: center;
            transition: transform 0.3s; /* Add transition effect for hover */
            margin: 10px; /* Adjust the margin between cards */
        }

        .card:hover {
            transform: translateY(-5px); /* Move the card up slightly on hover */
        }
    </style>
    <script>
        function addTask(category) {
            var existingForms = document.querySelectorAll("#addTaskForm");
            existingForms.forEach(function(form) {
               form.parentNode.removeChild(form);
            });

            document.getElementById("buttontodo").style.display = "block";
            document.getElementById("buttoninprogress").style.display = "block";
            document.getElementById("buttondone").style.display = "block";

            <?php global $projectname, $id; ?>
            document.getElementById("button" + category).style.display = "none";

            var form = document.createElement("form");
            form.id = "addTaskForm"
            form.method = "post";
            form.action = "<?php echo htmlspecialchars($_SERVER['PHP_SELF']), '?id=' , $id , '&name=' , $projectname;?>";

            var input = document.createElement("input");
            input.name = "task";
            input.type = "text";
            input.placeholder = "Enter a task..."

            var inputhidden = document.createElement("input");
            inputhidden.type = "hidden";
            inputhidden.name = "taskstatus";
            if (category === "todo") {
                inputhidden.value = "To Do";
            }
            else if (category === "inprogress") {
                inputhidden.value = "In Progress";
            } else {
                inputhidden.value = "Done";
            }

            var button = document.createElement("input");
            button.type = "button";
            button.value = "Add task";
            button.onclick = function() {
                if ((input.value).trim() !== "") {
                    document.getElementById('addTaskForm').submit();
                }
            }

            form.appendChild(input);
            form.appendChild(inputhidden);
            form.appendChild(document.createElement("br"));
            form.appendChild(button);

            document.getElementById(category).appendChild(form);
        }
    </script>
</head>
<body>
</form>
<div style="display: table; border-spacing: 30px">
    <div id="todo" style="display: table-cell">
        <h2>To Do</h2>
        <?php retrieveTask("To Do"); ?>
        <!--
        <div class="card">Task Type 1</div>
        <div class="card">Task Type 2</div>
        <div class="card">Task Type 3</div>
        -->
        <input type="button" id="buttontodo" value="Add a task" onclick="addTask('todo');">
    </div>

    <div id="inprogress" style="display: table-cell">
        <h2>In Progress</h2>
        <?php retrieveTask("In Progress"); ?>
        <!--
        <div class="card">Task Type 4</div>
        <div class="card">Task Type 5</div>
        -->
        <input type="button" id="buttoninprogress" value="Add a task" onclick="addTask('inprogress');">
    </div>

    <div id="done" style="display: table-cell">
        <h2>Done</h2>
        <?php retrieveTask("Done"); ?>
        <!--
        <div class="card">Task Type 6</div>
        <div class="card">Task Type 7</div>
        <div class="card">Task Type 8</div>
        -->
        <input type="button" id="buttondone" value="Add a task" onclick="addTask('done')">
    </div>
</div>
</body>
</html>
