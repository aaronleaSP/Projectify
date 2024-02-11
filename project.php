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


        $sql = "INSERT INTO tasks_table (project_id, task_name, task_status) VALUES ('$id', '$taskname', '$taskstatus')";
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
                echo "<div class='card' onclick='modifyTask(", $row['task_id'], ");'>", $row['task_name'], "</div>";
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

        /* Modal styles */
        .modal {
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            z-index: 1; /* Sit on top */
            left: 0;
            top: 0;
            width: 100%; /* Full width */
            height: 100%; /* Full height */
            overflow: auto; /* Enable scroll if needed */
            background-color: rgb(0,0,0); /* Fallback color */
            background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
            padding-top: 60px;
        }

        /* Modal content */
        .modal-content {
            background-color: #fefefe;
            margin: 5% auto; /* 5% from the top and centered */
            padding: 20px;
            border: 1px solid #888;
            width: 80%; /* Could be more or less, depending on screen size */
        }

        /* Close button */
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
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

        function modifyTask(taskid) {
            var modal = document.getElementById('myModal');
            modal.style.display = "block";
        }

        function closeModal() {
            var modal = document.getElementById('myModal');
            modal.style.display = "none";
        }
    </script>
</head>
<body>
<!-- Modal popup -->
<div id="myModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <p/>
        <span id="taskid">Task ID</span>
        <p/>
        <span id="taskname">Task Name</span><br/>
        <input type="button" value="Add a subtask">
        <input type="button" value="Assign task"><p/>
        <b>Description</b><br/>
        <input type="text" id="taskdescription" placeholder="Add a description..."><br/>
        <input type="button" value="Save"><p/>
        <b>Child issues</b>
        <table>
            <tr>
                <td>SUBTASK ID</td>
                <td>SUBTASK NAME</td>
                <td>SUBTASK ASSIGNEE</td>
                <td>SUBTASK STATUS</td>
            </tr>
        </table><br/>
        <input type="text" id="subtaskname" placeholder="Add a subtask..."><br/>
        <input type="button" value="Create">
    </div>
</div>
<div style="display: table; border-spacing: 30px">
    <div id="todo" style="display: table-cell">
        <h2>To Do</h2>
        <?php retrieveTask("To Do"); ?>
        <input type="button" id="buttontodo" value="Add a task" onclick="addTask('todo');">
    </div>

    <div id="inprogress" style="display: table-cell">
        <h2>In Progress</h2>
        <?php retrieveTask("In Progress"); ?>
        <input type="button" id="buttoninprogress" value="Add a task" onclick="addTask('inprogress');">
    </div>

    <div id="done" style="display: table-cell">
        <h2>Done</h2>
        <?php retrieveTask("Done"); ?>
        <input type="button" id="buttondone" value="Add a task" onclick="addTask('done')">
    </div>
</div>
</body>
</html>
