<!DOCTYPE html>
<?php
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
        $user = $_POST["user"];

        $sql = "SELECT * FROM permissions_table WHERE project_id = '$id' AND user_email='$user' AND (permission_type='Editor' OR permission_type='Owner')";

        $result = mysqli_query($conn, $sql);
        if ($result) {
            if (mysqli_num_rows($result) > 0) {
                $sql = "INSERT INTO tasks_table (project_id, task_name, task_status) VALUES ('$id', '$taskname', '$taskstatus')";
                if (mysqli_query($conn, $sql)) {
                    $taskid = mysqli_insert_id($conn);
                } else {
                    die("Create task failed: " . mysqli_error($conn) . $redirect);
                }
            } else {
                echo "<script>alert('You have viewer permissions only!')</script>";
            }
        }
    }

    if (isset($_POST["taskdesc"])) {
        $taskdesc = htmlspecialchars($_POST["taskdesc"], ENT_QUOTES);
        $taskid = $_POST["taskid"];
        $user = $_POST["user"];

        $sql = "SELECT * FROM permissions_table WHERE project_id = '$id' AND user_email='$user' AND (permission_type='Editor' OR permission_type='Owner')";

        $result = mysqli_query($conn, $sql);
        if ($result) {
            if (mysqli_num_rows($result) > 0) {
                $sql = "UPDATE tasks_table SET task_description='$taskdesc' WHERE task_id=$taskid";
                if (!mysqli_query($conn, $sql)) {
                    die("Update task description failed: " . mysqli_error($conn) . $redirect);
                }
            }
            else {
                echo "<script>alert('You have viewer permissions only!')</script>";
            }
        }
    }

    if (isset($_POST["memberEmail"])) {
        $memberemail = $_POST["memberEmail"];
        $user = $_POST["user"];

        $sql = "SELECT * FROM permissions_table WHERE project_id = '$id' AND user_email='$user' AND permission_type='Owner'";

        $result = mysqli_query($conn, $sql);
        if ($result) {
            if (mysqli_num_rows($result) > 0) {
                if (isset($_POST["updatedPermissionValue"])) {
                    $memberpermission = $_POST["updatedPermissionValue"];

                    $sql = "UPDATE permissions_table SET permission_type='$memberpermission' WHERE user_email='$memberemail'";
                    if (!mysqli_query($conn, $sql)) {
                        die("Update member failed: " . mysqli_error($conn) . $redirect);
                    }
                }
                else {
                    $sql = "SELECT * FROM permissions_table WHERE project_id = '$id' AND user_email='$memberemail'";

                    $result = mysqli_query($conn, $sql);
                    if ($result) {
                        if (mysqli_num_rows($result) == 0) {
                            $memberpermission = $_POST["permissionValue"];

                            if ($memberpermission == 1) $memberpermission = "Editor";
                            else $memberpermission = "Viewer";

                            $sql = "INSERT INTO permissions_table (project_id, user_email, permission_type) VALUES ('$id', '$memberemail', '$memberpermission')";
                            if (!mysqli_query($conn, $sql)) {
                                die("Add member failed: " . mysqli_error($conn) . $redirect);
                            }
                        }
                    }
                }
            }
            else {
                echo "<script>alert('Only project owner can set permissions!')</script>";
            }
        }
    }

    if (isset($_POST["deletedTask"])) {
        $taskid = $_POST["deletedTask"];
        $user = $_POST["user"];

        $sql = "SELECT * FROM permissions_table WHERE project_id = '$id' AND user_email='$user' AND (permission_type='Editor' OR permission_type='Owner')";

        $result = mysqli_query($conn, $sql);
        if ($result) {
            if (mysqli_num_rows($result) > 0) {
                $sql = "DELETE FROM reminders_table WHERE task_id = '$taskid'";

                $sql2 = "DELETE FROM tasks_table WHERE task_id='$taskid'";

                if (!mysqli_query($conn, $sql) || !mysqli_query($conn, $sql2)) {
                    die("Delete task failed: " . mysqli_error($conn) . $redirect);
                }
            } else {
                echo "<script>alert('You have viewer permissions only!')</script>";
            }
        }
    }

    if (isset($_POST["updatedTaskStatus"])) {
        $updatetaskstatus = $_POST["updatedTaskStatus"];
        $updatetaskid = $_POST["updatedTaskId"];
        $user = $_POST["user"];

        $sql = "SELECT * FROM permissions_table WHERE project_id = '$id' AND user_email='$user' AND (permission_type='Editor' OR permission_type='Owner')";

        $result = mysqli_query($conn, $sql);
        if ($result) {
            if (mysqli_num_rows($result) > 0) {
                $sql = "UPDATE tasks_table SET task_status='$updatetaskstatus' WHERE task_id='$updatetaskid'";
                if (!mysqli_query($conn, $sql)) {
                    die("Update task status failed: " . mysqli_error($conn) . $redirect);
                }
            }
            else {
                echo "<script>alert('You have viewer permissions only!')</script>";
            }
        }
    }

    if (isset($_POST["addAssigneeEmail"])) {
        $assigneeEmail = $_POST["addAssigneeEmail"];
        $taskid = $_POST["updatedTaskId"];
        $user = $_POST["addAssigneeUser"];

        $sql = "SELECT * FROM permissions_table WHERE project_id = '$id' AND user_email='$user' AND (permission_type='Editor' OR permission_type='Owner')";

        $result = mysqli_query($conn, $sql);
        if ($result) {
            if (mysqli_num_rows($result) > 0) {
                $sql = "UPDATE tasks_table SET assignee_email='$assigneeEmail' WHERE task_id='$taskid'";
                if (!mysqli_query($conn, $sql)) {
                    die("Update task assignee failed: " . mysqli_error($conn) . $redirect);
                }
            }
            else {
                echo "<script>alert('You have viewer permissions only!')</script>";
            }
        }
    }

    if (isset($_POST["startDate"])) {
        $startDate = $_POST["startDate"];
        $endDate = $_POST["endDate"];
        $remindOption = $_POST["remindOption"];
        $user = $_POST["user"];
        $taskid = $_POST["taskid"];
        $remindDateTime = $_POST["remindDateTime"];

        $remindDateTime = str_replace('T', ' ', $remindDateTime);
        $remindDateTime = str_replace('Z', '', $remindDateTime);

        $remindDateTime = date('Y-m-d H:i:s', strtotime($remindDateTime));

        $sql = "SELECT * FROM permissions_table WHERE project_id = '$id' AND user_email='$user' AND (permission_type='Editor' OR permission_type='Owner')";

        $result = mysqli_query($conn, $sql);
        if ($result) {
            if (mysqli_num_rows($result) > 0) {
                $sql = "SELECT * FROM reminders_table WHERE task_id='$taskid'";

                $result = mysqli_query($conn, $sql);
                if ($result) {
                    if (mysqli_num_rows($result) == 0) {
                        $sql = "INSERT INTO reminders_table (project_id, task_id, start_date, end_date, remind_datetime, remind_option) VALUES ('$id' ,'$taskid', '$startDate', '$endDate', '$remindDateTime', '$remindOption')";
                        if (!mysqli_query($conn, $sql)) {
                            die("Create reminder failed: " . mysqli_error($conn) . $redirect);
                        }
                    } else {
                        $sql = "UPDATE reminders_table SET start_date='$startDate', end_date='$endDate', remind_datetime='$remindDateTime', remind_option='$remindOption' WHERE task_id='$taskid'";
                        if (!mysqli_query($conn, $sql)) {
                            die("Update reminder failed: " . mysqli_error($conn) . $redirect);
                        }
                    }
                }
            }
            else {
                echo "<script>alert('You have viewer permissions only!')</script>";
            }
        }
    }
}



function retrieveTask($category) {
    global $conn, $id;
    static $invokedBefore = false;

    $sql = "SELECT * FROM tasks_table WHERE project_id = '$id' AND task_status = '$category'";

    $result = mysqli_query($conn, $sql);
    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            // output data of each row
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<div class='card' onclick='modifyTask(", $row['task_id'].',"'.$row['task_name'].'",'. htmlspecialchars(json_encode($row['task_description']), ENT_QUOTES).',"'.$row['task_status'].'","'.$row['assignee_email'].'"',
                ");'><span>", htmlspecialchars($row['task_name']), "</span><span style='text-align: left;' id='taskDate", $row['task_id'], "'></span></div>";

                $sql2 = "SELECT * FROM reminders_table WHERE project_id = '$id' AND task_id='" .$row['task_id']. "'";

                $result2 = mysqli_query($conn, $sql2);

                if ($result2) {
                    if (mysqli_num_rows($result2) > 0) {
                        while ($row2 = mysqli_fetch_assoc($result2)) {
                            echo "<div style='display: none' class='reminder' id='", $row['task_id'], "'>", $row2['start_date'] . "#" . $row2['end_date'] . "#" . $row2['remind_datetime'], "</div>";
                        }
                    }
                }
            }
        }
    }

    if (!$invokedBefore) {
        $sql = "SELECT * FROM permissions_table WHERE project_id = '$id' AND (permission_type='Owner' OR permission_type='Editor')";

        $result = mysqli_query($conn, $sql);
        if ($result) {
            $invokedBefore = true;
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<span class='assign' style='display: none;'>" . $row['user_email'] . "</span>";
                }
            }
        }
    }
}

function retrievePermission() {
    global $conn, $id;
    $sql = "SELECT * FROM permissions_table WHERE project_id = '$id'";

    $result = mysqli_query($conn, $sql);
    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            echo "<table><tr><th>Email Address</th><th>Permission</th></tr>";
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr><td>", $row['user_email'], "</td>";
                if ($row['permission_type'] == "Owner") {
                    echo "<td><b>Owner</b></td>";
                } else if ($row['permission_type'] == "Editor") {
                    echo "<td><label><select onchange='updateMember(this, ", '"'.$row['user_email'].'"',");'>", "<option value='1' selected>Editor</option><option value='2'>Viewer</option></select></label></td></tr>";
                } else {
                    echo "<td><label><select onchange='updateMember(this, ", '"'.$row['user_email'].'"',");'>", "<option value='1'>Editor</option><option value='2' selected>Viewer</option></select></label></td></tr>";
                }
            }
            echo "</table>";
        }
        else {
            echo "No results";
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

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>

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
        textarea{
            resize: none;
        }
        .card-menu {
            display: none;
            width: 200px;
            background-color: #f9f9f9;
            padding: 20px;
            position: absolute;
            z-index: 1;
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
        }


        .card-menu button:hover {
            background-color: #777;
        }
    </style>
    <script>
        if ( window.history.replaceState ) {
            window.history.replaceState( null, null, window.location.href );
        }

        function addTask(category) {
            const user = firebase.auth().currentUser.email;

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
            form.action = "<?php echo htmlspecialchars($_SERVER['PHP_SELF']), '?id=' , $id , '&name=' , $projectname;?>"

            var input = document.createElement("input");
            input.name = "task";
            input.type = "text";
            input.placeholder = "Enter a task..."
            input.onkeydown = function(event) {
                if (event.key === 'Enter') event.preventDefault();
            }

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

            var inputuser = document.createElement("input");
            inputuser.type = "hidden";
            inputuser.name = "user";
            inputuser.value = user;

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
            form.appendChild(inputuser);
            form.appendChild(document.createElement("br"));
            form.appendChild(button);

            document.getElementById(category).appendChild(form);
        }

        function modifyTask(taskid, taskname, taskdesc, taskstatus, assigneeEmail) {
            var modal = document.getElementById('myModal');
            document.getElementById('taskname').innerHTML = "<b>" + taskname + "</b>";
            document.getElementById('taskstatus').innerText = taskstatus;
            if (taskdesc !== null) {
                document.getElementById('taskdescription').value = taskdesc;
            } else document.getElementById('taskdescription').value = "";

            document.getElementById('formtaskid').value = taskid;
            document.getElementById("assigneeEmail").value = assigneeEmail;

            document.getElementById('assigneetaskid').value = taskid;
            document.getElementById("scheduletaskid").value = taskid;

            document.getElementById('deleteTask').onclick = function() {
                deleteTask(taskid);
            }

            let select = document.getElementById('selectTaskStatus');
            if (taskstatus === "To Do") select.selectedIndex = 0;
            else if (taskstatus === "In Progress") select.selectedIndex = 1;
            else select.selectedIndex = 2;

            document.getElementById("startDate").value = "";
            document.getElementById("endDate").value = "";
            document.getElementById("endTime").value = "";

            document.getElementById("selectDueStatus").value = 1;

            document.getElementById('selectTaskStatus').onchange = function() {
                let selectedOption = this.value;
                updateTaskStatus(selectedOption, taskid);
            }

            var dates = document.getElementById(taskid);

            if (dates !== null) {
                dates = dates.innerText.split("#");

                var formattedDate = "";

                for (let i = 0; i < 2; i++) {
                    var date = new Date(dates[i]);

                    var day = date.getDate();
                    var monthIndex = date.getMonth();

                    var months = [
                        "Jan", "Feb", "Mar", "Apr", "May", "Jun",
                        "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
                    ];

                    formattedDate += day + ' ' + months[monthIndex];

                    if (i === 0) formattedDate += " - "
                }
                document.getElementById("dateButton").value = formattedDate;
            } else {
                document.getElementById("dateButton").value = "Dates";
            }

            modal.style.display = "block";

            if (assigneeEmail.trim() !== "") {
                document.getElementById("assigneeButton").value = assigneeEmail;
            } else {
                document.getElementById("assigneeButton").value = "Assignee";
            }
        }

        function deleteTask(taskid) {
            <?php global $projectname, $id; ?>

            var form = document.createElement("form");
            form.id = "deleteTaskForm"
            form.method = "post";
            form.action = "<?php echo htmlspecialchars($_SERVER['PHP_SELF']), '?id=' , $id , '&name=' , $projectname;?>"


            var inputhidden = document.createElement("input");
            inputhidden.type = "hidden";
            inputhidden.name = "deletedTask";
            inputhidden.value = taskid;

            const user = firebase.auth().currentUser.email;

            var inputuser = document.createElement("input");
            inputuser.type = "hidden";
            inputuser.name = "user";
            inputuser.value = user;

            form.appendChild(inputhidden);
            form.appendChild(inputuser);

            document.body.appendChild(form);
            document.getElementById('deleteTaskForm').submit();
        }

        function closeModal() {
            var modal = document.getElementById('myModal');
            modal.style.display = "none";
            hideUpdateTaskDesc();
            hideCreateSubTask();
            closeCardMenu();
            closeDateCardMenu();
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
                const user = firebase.auth().currentUser.email;

                document.getElementById("updateTaskDescUser").value = user;

                document.getElementById("updateTaskDescForm").submit();
            }
        }

        function updateTaskStatus(element, taskid) {
            <?php global $projectname, $id; ?>

            var form = document.createElement("form");
            form.id = "updateTaskStatusForm"
            form.method = "post";
            form.action = "<?php echo htmlspecialchars($_SERVER['PHP_SELF']), '?id=' , $id , '&name=' , $projectname;?>"


            var inputhidden = document.createElement("input");
            inputhidden.type = "hidden";
            inputhidden.name = "updatedTaskStatus";

            const user = firebase.auth().currentUser.email;

            var inputuser = document.createElement("input");
            inputuser.type = "hidden";
            inputuser.name = "user";
            inputuser.value = user;

            if (element === "1") {
                inputhidden.value = "To Do";
            } else if (element === "2") {
                inputhidden.value = "In Progress";
            } else inputhidden.value = "Done";

            var inputhidden2 = document.createElement("input");
            inputhidden2.type = "hidden";
            inputhidden2.name = "updatedTaskId";
            inputhidden2.value = taskid;

            form.appendChild(inputhidden);
            form.appendChild(inputhidden2);
            form.appendChild(inputuser);

            document.body.appendChild(form);

            document.getElementById('updateTaskStatusForm').submit();
        }

        function addMember() {
            let regexEmail = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
            let memberEmail = document.getElementById('memberEmail').value;
            if ((memberEmail).trim() !== "" && regexEmail.test(memberEmail)) {
                auth.fetchSignInMethodsForEmail(memberEmail).then((signInMethods) => {
                    if (signInMethods.length > 0) {
                        const user = firebase.auth().currentUser.email;

                        document.getElementById("addMemberUser").value = user;
                        document.getElementById("addMemberForm").submit();
                    } else {
                        alert("User does not exist!");
                    }
                }).catch((error) => {
                   alert("Error: " + error);
                });
            }
        }

        function updateMember(element, email) {
            var form = document.createElement("form");
            form.id = "updateMemberForm"
            form.method = "post";
            form.action = "<?php echo htmlspecialchars($_SERVER['PHP_SELF']), '?id=' , $id , '&name=' , $projectname;?>"

            var input = document.createElement("input");
            input.name = "memberEmail";
            input.type = "hidden";
            input.value = email;

            var inputhidden = document.createElement("input");
            inputhidden.type = "hidden";
            inputhidden.name = "updatedPermissionValue";
            if (element.value === '1') {
                inputhidden.value = "Editor";
            } else inputhidden.value = "Viewer";

            const user = firebase.auth().currentUser.email;
            var inputuser = document.createElement("input");
            inputuser.type = "hidden";
            inputuser.name = "user";
            inputuser.value = user;

            form.appendChild(input);
            form.appendChild(inputhidden);
            form.appendChild(inputuser);

            document.body.appendChild(form);
            document.getElementById('updateMemberForm').submit();
        }

        let populatedList = false;
        // Menu toggle for aaron ;)
        function toggleCardMenu(element) {
            if (!populatedList) {
                populatedList = true;
                var assigneesList = document.querySelectorAll('.assign');

                assigneesList.forEach(element => {
                    let trElement = document.createElement('tr');
                    let tdElement = document.createElement('td');
                    tdElement.textContent = element.textContent;
                    trElement.appendChild(tdElement);
                    document.getElementById('assigneeTable').appendChild(trElement);

                    tdElement.onclick = function() {
                        const user = firebase.auth().currentUser.email;

                        document.getElementById("addAssigneeUser").value = user;
                        document.getElementById("addAssigneeEmail").value = tdElement.textContent;

                        document.getElementById("addAssigneeForm").submit();
                    }
                });
            }

            var cardMenu = document.getElementById("cardMenu");
            if (cardMenu.style.display === "none") {
                cardMenu.style.display = "block";
                var btn = element;
                cardMenu.style.top = (btn.offsetTop + btn.offsetHeight) + "px";
                cardMenu.style.left = btn.offsetLeft + "px";
            } else {
                cardMenu.style.display = "none";
            }
        }

        function toggleDateCardMenu(element) {
            var cardMenu = document.getElementById("dateCardMenu");
            if (cardMenu.style.display === "none") {
                cardMenu.style.display = "block";
                var btn = element;
                cardMenu.style.top = (btn.offsetTop + btn.offsetHeight) + "px";
                cardMenu.style.left = btn.offsetLeft + "px";
            } else {
                cardMenu.style.display = "none";
            }

        }

        function closeDateCardMenu() {
            document.getElementById("dateCardMenu").style.display = "none";
        }

        function closeCardMenu() {
            var cardMenu = document.getElementById("cardMenu");
            cardMenu.style.display = "none";
        }

        function scheduleDates() {
            var startDate = document.getElementById("startDate").value;
            var endDate = document.getElementById("endDate").value;
            var endTime = document.getElementById("endTime").value;

            var dueStatus = document.getElementById("selectDueStatus").value;
            var remindOption = document.getElementById("remindOption");

            if (startDate.trim() === "" || endDate.trim() === "" || endTime.trim() === "") {
                alert("Date and time cannot be empty!");
                return;
            }

            var remindDateTimeString = endDate + " " + endTime;
            var remindDateTime = new Date(remindDateTimeString);

            if (dueStatus === "1") {
                remindOption.value = "None";
                remindDateTime = "";
            }
            else if (dueStatus === "2") {
                remindOption.value = "At time of due date";
            }
            else if (dueStatus === "3") {
                remindOption.value = "5 Minutes before";
                remindDateTime.setMinutes(remindDateTime.getMinutes() - 5);
            }
            else if (dueStatus === "4") {
                remindOption.value = "15 Minutes before";
                remindDateTime.setMinutes(remindDateTime.getMinutes() - 15);
            }
            else if (dueStatus === "5") {
                remindOption.value = "1 Hour before";
                remindDateTime.setMinutes(remindDateTime.getMinutes() - 60);
            }
            else {
                remindOption.value = "1 Day before";
                remindDateTime.setDate(remindDateTime.getDate() - 1);
            }

            const user = firebase.auth().currentUser.email;
            document.getElementById("scheduleUser").value = user;

            if (remindDateTime !== "") {
                var remindDateFormatted = new Date(remindDateTime);
                remindDateTimeString = remindDateFormatted.toISOString();
                document.getElementById("remindDateTime").value = remindDateTimeString;
            } else {
                document.getElementById("remindDateTime").value = "";
            }

            document.getElementById("scheduleDateForm").submit();
        }

        function updateEndDate() {
            var endDate = document.getElementById("endDate");
            endDate.min = document.getElementById("startDate").value;

            endDate.value = "";
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
<!-- NEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEW -->

<div class="modal modal-xl" id="myModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <p id="taskname" class="h2">Task Name</p>
                <p id="taskstatus" style="margin-left: 10px;">Task ID</p>
                <input type="button"  onclick="closeModal();" class="btn-close" aria-label="Close">
            </div>
            <div class="modal-body">
                <?php global $projectname, $id; ?>
                <div class="row">
                    <div class="col-md-8">
                        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) . '?id=' . $id . '&name=' . $projectname;?>" method="post" id="updateTaskDescForm">
                            <b>Description</b>
                            <div class="form-group">
                                <input type="hidden" id="updateTaskDescUser" name="user">
                                <input type="hidden" id="assigneeEmail" name="assigneeEmail">
                                <textarea class="form-control" id="taskdescription" name="taskdesc" placeholder="Add a description..." onclick="showUpdateTaskDesc();" rows="10"></textarea>
                            </div>

                            <input type="hidden" name="taskid" id="formtaskid">
                            <div id="updateTaskDescription" style="display: none; margin-top: 15px">
                                <input type="button" value="Save" class="btn btn-warning" onclick="hideUpdateTaskDesc(); updateTaskDesc();">
                                <input type="button" value="Cancel" class="btn btn-warning" onclick="hideUpdateTaskDesc();"><p/>
                            </div>
                        </form>
                        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) . '?id=' . $id . '&name=' . $projectname;?>" method="post" id="addAssigneeForm">
                            <input type="hidden" name="updatedTaskId" id="assigneetaskid">
                            <input type="hidden" name="addAssigneeEmail" id="addAssigneeEmail">
                            <input type="hidden" name="addAssigneeUser" id="addAssigneeUser">
                        </form>
                        <div>
                            <b>Child issues</b><input type="button" value="Add subtasks" onclick="showCreateSubTask();">
                            <table>
                                <tr>
                                    <td>SUBTASK ID</td>
                                    <td>SUBTASK NAME</td>
                                    <td>SUBTASK ASSIGNEE</td>
                                    <td>SUBTASK STATUS</td>
                                </tr>
                            </table><br/>
                            <div id="createSubTask" style="display: none;">
                                <input type="text" id="subtaskname" placeholder="Add a subtask..."><br/>
                                <input type="button" value="Create">
                                <input type="button" value="Cancel" onclick="hideCreateSubTask();">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4" style="border: black">
                        <div class="align-items-center" style="flex: 2;">
                            <div>
                                <p>Modify</p>
                                <div>
                                    <input type="button" class="btn btn-light" id="assigneeButton" style="width: 100%; text-align: start"  value="Assignee" onclick="toggleCardMenu(this); closeDateCardMenu();">
                                    <!-- Card menu for Assignee (Aaron) -->
                                    <div id="cardMenu" class="card-menu" style="width: auto; display: none;" >
                                        <input type="button"  onclick="closeCardMenu()" class="btn-close" style="margin-bottom: 15px; float: right">
                                        <h5 class="card-title"></h5>
                                        <p class="card-text">Assign a member to work on this task.</p>
                                        <table class="table table-hover">
                                            <thead>
                                            <tr>
                                                <th scope="col">Members</th>
                                            </tr>
                                            </thead>
                                            <tbody id="assigneeTable">
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <div style="margin-top: 10px;">
                                <div style="margin-top: 10px;">
                                    <input type="button" class="btn btn-light" id="dateButton" style="width: 100%; text-align: start" value="Dates" onclick="toggleDateCardMenu(this); closeCardMenu();">
                                    <!-- Card menu for Dates -->
                                    <div id="dateCardMenu" class="card-menu" style="width: auto; display: none;">
                                        <input type="button" onclick="closeDateCardMenu()" class="btn-close" style="margin-bottom: 15px; float: right;">
                                        <h5 class="card-title">Dates</h5>
                                        <p class="card-text">Here you can manage dates and deadlines.</p>
                                        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) . '?id=' . $id . '&name=' . $projectname;?>" method="post" id="scheduleDateForm" onsubmit="return false;">
                                            <div style="display: flex; flex-direction: row;">
                                                <div style="margin-right: 20px;">
                                                    <label for="startDate">Start Date:</label>
                                                    <input type="date" id="startDate" name="startDate" onkeydown="return false;" onchange="updateEndDate();" required>
                                                </div>
                                                <div style="margin-right: 20px;">
                                                    <label for="endDate">End Date:</label>
                                                    <input type="date" id="endDate" name="endDate" onkeydown="return false;" required>
                                                    <input style="width: 100%;" type="time" id="endTime" name="endTime" onkeydown="return false;" required>
                                                </div>
                                                <div>
                                                    <label for="selectDueStatus">Remind me:</label>
                                                    <select class="due-select" id="selectDueStatus" name="selectDueStatus" required>
                                                        <option value="1" selected>None</option>
                                                        <option value="2">At time of due date</option>
                                                        <option value="3">5 Minutes before</option>
                                                        <option value="4">15 Minutes before</option>
                                                        <option value="5">1 Hour before</option>
                                                        <option value="6">1 Day before</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <br>
                                            <input type="hidden" name="user" id="scheduleUser">
                                            <input type="hidden" name="taskid" id="scheduletaskid">
                                            <input type="hidden" name="remindDateTime" id="remindDateTime">
                                            <input type="hidden" name="remindOption" id="remindOption">
                                            <input type="button" id="scheduleButton" class="btn btn-primary" style="width: 100%;" value="Schedule" onclick="scheduleDates();">
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div style="margin-top: 15px;">
                            <p>Status</p>
                            <select class="form-select" id="selectTaskStatus" aria-label="Default select example">
                                <option value="1">To Do</option>
                                <option value="2">In Progress</option>
                                <option value="3">Done</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <div style="margin-top: 15px;">
                    <input type="button" class="btn btn-danger" value="Delete Task" id="deleteTask">
                </div>
            </div>
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
    <h1>Invite Collaborators</h1>
    <?php retrievePermission(); ?>
    <table>
        <tr>
            <td colspan="3">
                <?php global $projectname, $id; ?>
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']), '?id=' , $id , '&name=' , $projectname;?>" method="post" id="addMemberForm" onsubmit="return false;">
                    <input type="email" placeholder="Email address" id="memberEmail" name="memberEmail">
                    <input type="hidden" name="user" id="addMemberUser">
                    <select style="width: auto" name="permissionValue">
                        <option selected value="1">Editor</option>
                        <option value="2">Viewer</option>
                    </select>
                    <input type="button" value="Add member" onclick="addMember();">
                </form>
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

    var reminders = document.querySelectorAll('.reminder');

    reminders.forEach(function(reminder) {
        dates = reminder.innerText.split("#");

        var formattedDate = "";

        for (let i = 0; i < 2; i++) {
            var date = new Date(dates[i]);

            var day = date.getDate();
            var monthIndex = date.getMonth();

            var months = [
                "Jan", "Feb", "Mar", "Apr", "May", "Jun",
                "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
            ];

            formattedDate += day + ' ' + months[monthIndex];

            if (i === 0) formattedDate += " - ";
        }

        document.getElementById("taskDate" + reminder.id).innerText = formattedDate;
    });


</script>
</body>
</html>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["taskdesc"]) || isset($_POST["addAssigneeEmail"]) || isset($_POST["startDate"])) {

        $sql = "SELECT * FROM tasks_table WHERE task_id='$taskid'";

        $result = mysqli_query($conn, $sql);
        if ($result) {
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<script>modifyTask(", $row['task_id'] . ',"' . $row['task_name'] .'",'. json_encode($row['task_description']) .',"'. $row['task_status'] . '","' . $row['assignee_email'] .'"', ");</script>";
                }
            }
        }
    }

    if (isset($_POST["memberEmail"])) {
        echo "<script>showSection('permissionSection')</script>";
    }
}
?>