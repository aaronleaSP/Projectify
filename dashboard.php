<!DOCTYPE html>
<?php
    function getAllProjects()
    {
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


        $sql = "SELECT * FROM projects_table";

        $result = mysqli_query($conn, $sql);
        if ($result) {
            if (mysqli_num_rows($result) > 0) {
                // output data of each row
                echo "<table>";
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr><td>", $row['project_name'], "</td>";
                    echo "<td>", $row['project_desc'], "</td></tr>";
                }
                echo "</table>";
            }
        }
    }
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Projectify - Dashboard</title>

    <script src="https://www.gstatic.com/firebasejs/8.2.7/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.2.7/firebase-auth.js"></script>

    <script src="scripts/getuser.js"> // Get user email and corresponding logic</script>

    <link rel="stylesheet" href="styles/dashboard.css">

    <link rel="icon" href="images/favicon.ico" type="image/x-icon"/>
</head>
<body>
<header>
    <div class="logo">
        <a onclick="showSection('projectManagementSection')">
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
    <a href='#' class="active" id="projectManagement" onclick="showSection('projectManagementSection')">Project Management</a>
    <a href='#' id="permission" onclick="showSection('permissionSection')">Permission</a>
    <a href='#' id="task" onclick="showSection('taskSection')">Task</a>
    <a href='#' id="calendar" onclick="showSection('calendarSection')">Calendar</a>
    <a href='#' id="teamMember" onclick="showSection('teamMemberSection')">Team Member</a>
</nav>

<section class="project-management" id="projectManagementSection">
    <div class="top-cards">
        <!-- Top two cards content goes here -->
        <div class="card all-project" style="flex-grow: 1">
            <h3>All Project</h3>
            <p class="card-link"><a onclick="showSection('allProjectsSection')">View Details</a></p>
        </div>
        <div class="card add-project">
            <h3>Add Project</h3>
            <p class="card-link"><a onclick="showSection('addProjectSection')">View Details</a></p>
        </div>
    </div>
    <div class="bottom-cards">
        <!-- Bottom three cards content goes here -->
        <div class="card not-started-card">
            <h3>To Do</h3>
            <p class="card-link"><a href="not_started.php">Open</a></p>
        </div>
        <div class="card in-progress">
            <h3>In Progress</h3>
            <p class="card-link"><a href="in_progress.php">View Details</a></p>
        </div>
        <div class="card done">
            <h3>Done</h3>
            <p class="card-link"><a href="done.php">View Details</a></p>
        </div>
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

<section id="taskSection" style="display: none;">
    <!-- Task content goes here -->
    <div id="todo">
        <h2>To Do</h2>
        <div class="card">Task Type 1</div>
        <div class="card">Task Type 2</div>
        <div class="card">Task Type 3</div>
    </div>

    <div id="inprogress">
        <h2>In Progress</h2>
        <div class="card">Task Type 4</div>
        <div class="card">Task Type 5</div>
    </div>

    <div id="done">
        <h2>Done</h2>
        <div class="card">Task Type 6</div>
        <div class="card">Task Type 7</div>
        <div class="card">Task Type 8</div>
    </div>
</section>

<section class="calendar" id="calendarSection" style="display: none;">
    <!-- Calendar content goes here -->
    <p>Calendar Section Content</p>
    <div id="scheduleForm">
        <label for="dateInput">Choose a Date:</label>
        <input type="date" id="dateInput" name="dateInput">
        <button id="scheduleButton" onclick="displaySchedule()">Schedule</button>
    </div>

    <div id="scheduleDisplay"></div>
</section>

<script>
    function displaySchedule() {
        var selectedDate = document.getElementById('dateInput').value;
        var scheduleDisplay = document.getElementById('scheduleDisplay');
        scheduleDisplay.innerHTML = `<p>Schedule for ${selectedDate}</p>`;

        // Add your logic to display the schedule based on the selected date
        // You can fetch data from the server, show events, etc.
    }
</script>

<section id="addProjectSection" style="display: none;">
    <!-- Add project goes here -->
    <h1>It all starts here</h1>
    <span>Kickstart your journey towards project success. Start assigning tasks and deadlines today!</span><p/>


    <form method="POST" action="scripts/createproject.php" id="addProjectForm">
        <label for="projectname">Project Name:</label><br/>
        <input type="text" id="projectname" name="projectname" placeholder="My CSAD project" maxlength="50" required><br/>
        <span>Maximum limit of 50 characters.</span><p/>

        <label for="projectdescription">Project Description:</label><br/>
        <input type="text" id="projectdescription" name="projectdescription" placeholder="CSAD is a fun module!"><br/>
        <p/><input type="button" id="createprojectbutton" value="Create" onclick="validateAddProject();">
    </form>

</section>

<script>
    function validateAddProject() {
        var projectname = document.getElementById('projectname');
        var regex = /^[a-zA-Z0-9_ ]+$/;
        if ((projectname.value).trim() !== "" && regex.test(projectname.value)) {
            document.getElementById('addProjectForm').submit();
        } else {
            alert("Project name can only contain alphabets, digits, spaces and underscore!") // Can change this to display CSS error
        }
    }
</script>

<section id="allProjectsSection" style="display: none;">
    <!-- Add project goes here -->
    <h1>All Projects</h1>
    <?php getAllProjects() ?>;

</section>

<script>
    function validateAddProject() {
        var projectname = document.getElementById('projectname');
        var regex = /^[a-zA-Z0-9_ ]+$/;
        if ((projectname.value).trim() !== "" && regex.test(projectname.value)) {
            document.getElementById('addProjectForm').submit();
        } else {
            alert("Project name can only contain alphabets, digits, spaces and underscore!") // Can change this to display CSS error
        }
    }
</script>

<section class="team-member" id="teamMemberSection" style="display: none;">
    <!-- Team Member content goes here -->
    <ul>
        <li>Team Member 1</li>
        <li>Team Member 2</li>
        <li>Team Member 3</li>
    </ul>
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