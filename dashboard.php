<!DOCTYPE html>
<?php
$servername = "projectifydb.c5n6aasporw4.ap-southeast-1.rds.amazonaws.com:3306";
$username = "admin";
$password = "spstudent";
$db_name = "projectify";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $db_name);

$redirect = "<meta http-equiv='refresh' content='3;URL=dashboard.php'><p/>Redirecting you back to dashboard in 3 seconds...";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["projecttodelete"])) {
        $deleteid = $_POST["projecttodelete"];
        $user = $_POST["userEmail"];

        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error() . $redirect);
        }

        $sql = "SELECT * FROM permissions_table WHERE project_id = '$deleteid' AND user_email='$user' AND permission_type='Owner'";

        $result = mysqli_query($conn, $sql);
        if ($result) {
            if (mysqli_num_rows($result) > 0) {
                $sql = "DELETE FROM reminders_table WHERE project_id = '$deleteid'";
                $sql2 = "DELETE FROM tasks_table WHERE project_id = '$deleteid'";
                $sql3 = "DELETE FROM permissions_table WHERE project_id = '$deleteid'";

                if (mysqli_query($conn, $sql) && mysqli_query($conn, $sql2) && mysqli_query($conn, $sql3)) {
                    $sql = "DELETE FROM projects_table WHERE project_id = '$deleteid'";
                    if (!mysqli_query($conn, $sql)) {
                        die("Delete tasks and permissions failed: " . mysqli_error($conn) . $redirect);
                    }
                } else {
                    die("Delete project failed: " . mysqli_error($conn) . $redirect);
                }
            }
            else {
                echo "<script>alert('You do not own this project!')</script>";
            }
        }
    }

    if (isset($_POST["userEmail"])) {
        $useremail = $_POST["userEmail"];
    }
}
function getAllProjects()
{
    global $servername, $username, $password, $db_name, $redirect, $useremail;

    // Create connection
    $conn = mysqli_connect($servername, $username, $password, $db_name);

    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error() . $redirect);
    }

    $sql = "SELECT * FROM projects_table WHERE project_id IN (SELECT project_id FROM permissions_table WHERE user_email='$useremail')";

    $result = mysqli_query($conn, $sql);
    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            // output data of each row
            while ($row = mysqli_fetch_assoc($result)) {

                echo "<div class='col-md mb-4'>";
                echo "<div class='card shadow-sm mb-3>";
                echo "<div class='card-body'>";
                echo "<h3 class='card-title' style='text-align: left; '>" . $row['project_name'] . "</h3>";
                echo "<p class='card-text' style='text-align: left' margin-bottom: 20px'>" . $row['project_desc'] . "</p>";
                echo "<br>";
                echo "<div class='d-flex justify-content-between align-items-center'>";
                echo "<div class='btn-group'>";
                echo "<a href='./project.php?id=" . $row['project_id'] . "&name=" . $row['project_name'] . "' class='btn btn-sm btn-outline-secondary'>View Project</a>";
                echo "<button class='btn btn-sm btn-outline-danger' onclick='deleteProject(" . $row['project_id'] . ")'>Delete</button>";
                echo "</div>";
                echo "</div>";
                echo "</div>";
                echo "</div>";


            }
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

    <script>
        if ( window.history.replaceState ) {
            window.history.replaceState( null, null, window.location.href );
        }

        function deleteProject(projectid) {
            const user = firebase.auth().currentUser;
            if (user !== null) {
                var form = document.createElement("form");
                form.id = "deleteProjectForm"
                form.method = "post";
                form.action = "<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>"

                var inputhidden = document.createElement("input");
                inputhidden.type = "hidden";
                inputhidden.name = "projecttodelete";
                inputhidden.value = projectid;

                var inputhidden2 = document.createElement("input");
                inputhidden2.type = "hidden";
                inputhidden2.name = "userEmail";
                inputhidden2.value = user.email;

                form.appendChild(inputhidden);
                form.appendChild(inputhidden2);

                document.body.appendChild(form);
                document.getElementById('deleteProjectForm').submit();
            }
        }

        function fillUserDetails() {
            const user = firebase.auth().currentUser;
            if (user !== null) {
                document.getElementById("createProjectEmail").value = user.email;
            }
        }

        function getAllProjects() {
            const user = firebase.auth().currentUser;
            if (user !== null) {
                var form = document.createElement("form");
                form.id = "allProjectForm";
                form.method = "post";
                form.action = "<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>"

                var inputhidden = document.createElement("input");
                inputhidden.type = "hidden";
                inputhidden.name = "userEmail";
                inputhidden.value = user.email;

                form.appendChild(inputhidden);

                document.body.appendChild(form);
                document.getElementById('allProjectForm').submit();
            }
        }
    </script>
    <style>
        .background-image {
            background-image: url("images/flowers.jpeg");
            background-size: cover;
            background-position: center;
            -webkit-backdrop-filter: blur(10px);
            backdrop-filter: blur(10px);
        }

        .blur-image {
            -webkit-backdrop-filter: blur(50px);
            backdrop-filter: blur(50px);
        }

         #addProjectSection {
             display: flex;
             justify-content: center;
             align-items: center;
             height: 100vh;

         }

    </style>

    <link rel="stylesheet" href="styles/dashboard.css">
    <link rel="icon" href="images/favicon.ico" type="image/x-icon"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>

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

<!--<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="#">Navbar</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
        <div class="navbar-nav">
            <a href='#' class="active" id="projectManagement" onclick="showSection('projectManagementSection')">Project Management</a>
            <a href='#' id="permission" onclick="showSection('permissionSection')">Permission</a>
            <a href='#' id="task" onclick="showSection('taskSection')">Task</a>
            <a href='#' id="calendar" onclick="showSection('calendarSection')">Calendar</a>
            <a href='#' id="teamMember" onclick="showSection('teamMemberSection')">Team Member</a>
        </div>
    </div>
</nav>-->

<section class="project-management" id="projectManagementSection">
    <div class="container-xxl">
        <div class="row">
            <div class="col-md mb-4" >
                <div class="card">
                    <img class="card-img-top" src="images/placeholder.jpg" alt="Card image cap" style="height: 225px">
                    <div class="card-body">
                        <h5 class="card-title">All Projects</h5>
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                        <a onclick="getAllProjects();" href="#" class="btn btn-primary">All projects</a>
                    </div>
                </div>
            </div>
            <div class="col-md mb-4">
                <div class="card">
                    <img class="card-img-top" src="images/placeholder.jpg" alt="Card image cap" style="height: 225px">
                    <div class="card-body">
                        <h5 class="card-title">Add Projects</h5>
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                        <a onclick="showSection('addProjectSection'); fillUserDetails();" href="#" class="btn btn-primary">Add project</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md mb-4">
                <div class="card">
                    <img class="card-img-top" src="images/placeholder.jpg" alt="Card image cap" style="height: 225px">
                    <div class="card-body">
                        <h5 class="card-title">Projects to do</h5>
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                        <a href="not_started.php" class="btn btn-primary">All projects</a>
                    </div>
                </div>
            </div>
            <div class="col-md mb-4">
                <div class="card">
                    <img class="card-img-top" src="images/placeholder.jpg" alt="Card image cap" style="height: 225px">
                    <div class="card-body">
                        <h5 class="card-title">Projects in progress</h5>
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                        <a href="in_progress.php" class="btn btn-primary">Add project</a>
                    </div>
                </div>
            </div>
            <div class="col-md mb-4">
                <div class="card">
                    <img class="card-img-top" src="images/placeholder.jpg" alt="Card image cap" style="height: 225px">
                    <div class="card-body">
                        <h5 class="card-title">Finished Projects</h5>
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                        <a href="done.php" class="btn btn-primary">Add project</a>
                    </div>
                </div>
            </div>
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

<section id="addProjectSection" style="display: none; padding: 20px;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <h1 class="card-title">It all starts here</h1>
                        <p class="card-text">Kickstart your journey towards project success. Start assigning tasks and deadlines today!</p>

                        <form method="POST" action="scripts/createproject.php" id="addProjectForm">
                            <div class="form-group" style="margin-top: 50px">
                                <label for="projectname" style="margin-right: 650px">Project Name:</label>
                                <input type="text" class="form-control" id="projectname" name="projectname" placeholder="My CSAD project" maxlength="50" required>
                                <input type="hidden" id="createProjectEmail" name="createProjectEmail">
                                <small class="form-text text-muted" >Maximum limit of 50 characters.</small>
                            </div>

                            <div class="form-group" style="margin-top: 50px; margin-bottom: 50px">
                                <label for="projectdescription" style="margin-right: 600px">Project Description:</label>
                                <textarea class="form-control" id="projectdescription" name="projectdescription" placeholder="CSAD is a fun module!" rows="3"></textarea>
                            </div>

                            <button type="button" class="btn btn-primary" id="createprojectbutton" onclick="validateAddProject();">Create</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
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

<section id="allProjectsSection" style="display: none; padding: 20px;">
    <div class="container">
        <div class="card">
            <div class="card-body">
                <h1 style="margin-bottom: 25px" class="card-title">All Projects</h1>
                <?php getAllProjects(); ?>
            </div>
        </div>
    </div>
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

<footer>

    <a href="about_us.html">About Us</a>
    <a href="project_intro.html">Project Introduction</a>

</footer>

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
    if (isset($_POST["projecttodelete"]) || isset($_POST["userEmail"])) {
        echo "<script>showSection('allProjectsSection')</script>";
    }
}
?>