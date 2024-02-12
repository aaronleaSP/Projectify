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
    $projectname = $_GET["name"];
    $id = $_GET["id"];

    if (isset($_POST["task"])) {
        $taskname = $_POST["task"];
        $taskstatus = $_POST["taskstatus"];

        $sql = "INSERT INTO tasks_table (project_id, task_name, task_status) VALUES ('$id', '$taskname', '$taskstatus')";
        if (mysqli_query($conn, $sql)) {
            $taskid = mysqli_insert_id($conn);

        } else {
            die("Create task failed: " . mysqli_error($conn) . $redirect);
        }
    }

    if (isset($_POST["taskdesc"])) {
        $taskdesc = $_POST["taskdesc"];
        $taskid = $_POST["taskid"];

        $sql = "UPDATE tasks_table SET task_description='$taskdesc' WHERE task_id=$taskid";
        if (!mysqli_query($conn, $sql)) {
            die("Update task description failed: " . mysqli_error($conn) . $redirect);
        }
    }
}



function retrieveTask($category) {
    global $conn, $id;
    $sql = "SELECT * FROM tasks_table WHERE project_id = '$id' AND task_status = '$category'";

    $result = mysqli_query($conn, $sql);
    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            // output data of each row
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<div class='card' onclick='modifyTask(", $row['task_id'].',"'.$row['task_name'].'","'.$row['task_description'].'","'.$row['task_status'].'"',");'>", $row['task_name'], "</div>";
            }
        }
    }
}
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($projectname);?></title>

    <script src="https://www.gstatic.com/firebasejs/8.2.7/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.2.7/firebase-auth.js"></script>

    <script src="scripts/getuser.js"> // Get user email and corresponding logic</script>

    <style>
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
        .column {
            display: inline-block;
            vertical-align: top;
            width: 30%;
            padding: 10px;
            border: 1px solid #ccc;
            margin: 10px;
            background-color: #f9f9f9;
            border-radius: 5px;
        }
        .card {
            background-color: #fff;
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
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

        function modifyTask(taskid, taskname, taskdesc, taskstatus) {
            var modal = document.getElementById('myModal');
            document.getElementById('taskname').innerHTML = "<b>" + taskname + "</b>";
            document.getElementById('taskid').innerText = "TaskId: " + taskid;
            if (taskdesc !== null) {
                document.getElementById('taskdescription').value = taskdesc;
            } else document.getElementById('taskdescription').value = "";
            document.getElementById('taskstatus').innerText = taskstatus;

            document.getElementById('formtaskid').value = taskid;
            modal.style.display = "block";
        }

        function closeModal() {
            var modal = document.getElementById('myModal');
            modal.style.display = "none";
            hideUpdateTaskDesc();
            hideCreateSubTask();
        }

        function showCreateSubTask() {
            document.getElementById("createSubTask").style.display = "block";
        }

        function hideCreateSubTask() {
            document.getElementById("createSubTask").style.display = "none";
        }

        function showUpdateTaskDesc() {
            document.getElementById("updateTaskDescription").style.display = "block";
        }

        function hideUpdateTaskDesc() {
            document.getElementById("updateTaskDescription").style.display = "none";
        }

        function updateTaskDesc() {
            if ((document.getElementById("taskdescription").value).trim() !== "") {
                document.getElementById("updateTaskDescForm").submit();
            }
        }
    </script>

    <link rel="stylesheet" href="styles/dashboard.css">
</head>
<body>
<header>
    <div class="logo">
        <a href="dashboard.php">
            <img src="images/icon.png" alt="Logo">
        </a>
    </div>
    <div class="welcome">
        Welcome, <span id="user"></span>
    </div>
    <div class="right-section">
        <img src="images/notification.png" alt="notification" id="notification">
        <input type="button" value="Log out" id="signout" onclick="signOut()">
    </div>
</header>

<nav>
    <a class="active" id="projectBoard" onclick="showSection('projectBoardSection')">Project Board</a>
    <a id="timeline" onclick="showSection('timelineSection')">Timeline</a>
    <a id="permission" onclick="showSection('permissionSection')">Invite Collaborators</a>
</nav>
<!-- Modal popup -->
<div id="myModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <div style="display: flex">
            <div style="flex: 1">
                <span id="taskname">Task Name</span><span> / </span><span id="taskid">Task ID</span><br/>
                <span id="taskstatus">"Task Status"</span>
                <p/>
                <b>Description</b><br/>
                <?php global $projectname, $id; ?>
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']), '?id=' , $id , '&name=' , $projectname;?>" method="post" id="updateTaskDescForm">
                    <textarea id="taskdescription" name="taskdesc" placeholder="Add a description..." onclick="showUpdateTaskDesc();"></textarea><br/>
                    <input type="hidden" name="taskid" id="formtaskid">
                    <div id="updateTaskDescription" style="display: none">
                        <input type="button" value="Save" onclick="hideUpdateTaskDesc(); updateTaskDesc();">
                        <input type="button" value="Cancel" onclick="hideUpdateTaskDesc();"><p/>
                    </div>
                </form>
                <b>Child issues</b><input type="button" value="Add subtasks" onclick="showCreateSubTask();">
                <table>
                    <tr>
                        <td>SUBTASK ID</td>
                        <td>SUBTASK NAME</td>
                        <td>SUBTASK ASSIGNEE</td>
                        <td>SUBTASK STATUS</td>
                    </tr>
                </table><br/>
                <div id="createSubTask" style="display: none">
                    <input type="text" id="subtaskname" placeholder="Add a subtask..."><br/>
                    <input type="button" value="Create">
                    <input type="button" value="Cancel" onclick="hideCreateSubTask();">
                </div>
            </div>

            <div>
                <b>Modify</b><br/>
                <input type="button" value="Assignee"><br/>
                <input type="button" value="Dates"><p/>
                <b>Status</b><br/>
                <select>
                    <option>To Do</option>
                    <option>In Progress</option>
                    <option>Done</option>
                </select>
            </div>

            <section class="calendar" id="calendarSection" style="display: none;">
                <!-- Calendar content goes here -->
                <p/><b>Select Start and End Dates</b>
                <div id="scheduleForm">
                    <label for="dateInput">Start Date:</label>
                    <input type="date" id="dateInput" name="dateInput" onkeydown="return false;">

                    <label for="dateInput2">End Date:</label>
                    <input type="date" id="dateInput2" name="dateInput" onkeydown="return false;">

                    <button id="scheduleButton" onclick="displaySchedule()">Schedule</button>
                </div>

                <div id="scheduleDisplay"></div>
            </section>

            <script>
                function displaySchedule() {
                    var selectedDate = document.getElementById('dateInput').value;
                    var selectedDate2 = document.getElementById('dateInput2').value;
                    var scheduleDisplay = document.getElementById('scheduleDisplay');
                    scheduleDisplay.innerHTML = `<p>Schedule for ${selectedDate} to ${selectedDate2}</p>`;

                    // Add your logic to display the schedule based on the selected date
                    // You can fetch data from the server, show events, etc.
                }
            </script>
        </div>
    </div>
</div>

<section id="projectBoardSection">
    <div class="column" id="todo">
        <h2>To Do</h2>
        <?php retrieveTask("To Do"); ?>
        <!-- Add card button -->
        <input type="button" id="buttontodo" value="Add a task" onclick="addTask('todo');">
    </div>
    <div class="column" id="inprogress">
        <h2>In Progress</h2>
        <?php retrieveTask("In Progress"); ?>
        <!-- Add card button -->
        <input type="button" id="buttoninprogress" value="Add a task" onclick="addTask('inprogress');">
    </div>
    <div class="column" id="done">
        <h2>Done</h2>
        <?php retrieveTask("Done"); ?>
        <!-- Add card button -->
        <input type="button" id="buttondone" value="Add a task" onclick="addTask('done')">
    </div>
</section>

<section class="permission" id="permissionSection" style="display: none;">
    <!-- Permission content goes here -->
    <p>Permission Section Content</p>
    <table>
        <tr>
            <th>Name</th>
            <th>Task</th>
            <th>Permission</th>
        </tr>
        <tr>
            <td>HaHa</td>
            <td>
                <label>
                    <select class="form-select" >
                        <option selected>All</option>
                        <option value="1">Task 1</option>
                        <option value="2">Task 2</option>
                        <option value="3">Task 3</option>
                    </select>
                </label>
            </td>

            <td>
                <label>
                    <select class="form-select" >
                        <option selected>Can edit</option>
                        <option value="1">Can view</option>
                        <option value="2">No</option>
                    </select>
                </label>
            </td>
        </tr>
    </table>

</section>

<section id="timelineSection" style="display: none">
    Timeline Section
</section>

<script>
    function showSection(sectionId) {
        // Hide all sections
        document.querySelectorAll('section').forEach(section => {
            section.style.display = 'none';
        });

        // Show the clicked section
        document.getElementById(sectionId).style.display = 'block';
    }
</script>
</body>
</html>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["taskdesc"])) {
        $sql = "SELECT * FROM tasks_table WHERE task_id='$taskid'";

        $result = mysqli_query($conn, $sql);
        if ($result) {
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<script>modifyTask(", $row['task_id'] . ',"' . $row['task_name'] . '","' . $row['task_description'] . '","' . $row['task_status'] . '"', ");</script>";
                }
            }
        }
    }
}
?>