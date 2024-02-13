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
        .notification-container {
            display: flex;
            justify-content: center;
        }

        .notification-card {
            width: 300px; /* Fixed width */
            max-width: 500px; /* Ensures card doesn't exceed viewport width */

        }


    </style>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <link rel="stylesheet" href="styles/dashboard.css">
    <link rel="icon" href="images/favicon.ico" type="image/x-icon"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>

</head>
<body>
<!--Start of NavBar-->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <img src="images/icon.png" alt="Logo" style="height: 50px; align-content: center" onclick="function dashboard() {window.location.href = './dashboard.php'} dashboard();">

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class='navbar-nav'>
                <li class='nav-item'><a onclick="showSection('projectManagementSection'); closeNotiCardMenu();" class='nav-link'>Project Management</a></li>
                <li class='nav-item'> <a class='nav-link' onclick="toggleNotiCardMenu(this);">Notifications</a></li>
                <li class='nav-item'><a onclick="showSection('calendarSection'); closeNotiCardMenu();" class='nav-link'>Calendar</a></li>

            </ul>
            <div class='d-flex ms-auto' style="align-content: center">
                <span style="color: black;">Welcome, <span id="user"></span></span>
            </div>

            <form class='d-flex ms-auto'>
            </form>
            <input type="button" value="Log out" id="signout" onclick="signOut()">

        </div>
    </div>
</nav>

<!-- Beginning of a new start
<section class="project-management" id="projectManagementSection">
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
            <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                        class="fas fa-download fa-sm text-white-50"></i> Add Project </a>
        </div>
        <div class="row">

        </div>
    </div>

</section>-->
<!-- End of Nav Bar-->

<!-- Start of Dashboard -->
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
                        <h5 class="card-title">Projects in progress</h5>
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                        <a href="all_project.php" class="btn btn-primary">Add project</a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
<!-- End of Dashboard-->


<!-- Start of wtf is this -->
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

<section id="calendarSection" style="display: none;">
    <iframe src="calendar.html" style="width: 100%; height: 100%; position: absolute; border: none;"></iframe>
</section>

</script>
<!-- End of wtf this is-->


<!-- Start of Add Projects-->
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
<!-- End of Add Projects-->


<!-- Start of All projects-->
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
<!-- End of All projects-->

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


<!-- Start of Notification popup-->
<div class="align-items-center" style="flex: 2;">
    <!-- Card menu for Assignee (Aaron) -->
    <div id="NoticardMenu" class="card-menu" style="width: auto; display: none;">
        <input type="button" onclick="closeNotiCardMenu()" class="btn-close" style="margin-bottom: 15px; float: right;">
        <div class="list-group" style="margin: auto; max-height: 400px; overflow-y: auto;" id="notificationList">
            <!-- Notification cards will be dynamically added here -->
        </div>
    </div>
</div>
<input type="button" value="Add Notification" onclick="addNotification()"> <!-- Input button to add notification -->
<!-- Define a template for the notification card -->
<div class="notification-container">
    <template id="notificationTemplate">
        <a href="#" class="list-group-item list-group-item-action flex-column align-items-start notification-card">
            <div class="d-flex w-100 justify-content-between">
                <h5 class="mb-1">New Notification</h5>
                <small>Just now</small>
            </div>
            <p class="mb-1">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut maximus purus vel mi consequat, vitae condimentum quam consequat. Nulla facilisi. Proin hendrerit nisi id mauris hendrerit, nec venenatis orci ultricies. Maecenas condimentum, mauris nec interdum ullamcorper, felis dui eleifend magna, ac consectetur tortor velit ac est. Duis eget nunc ut velit varius gravida in at justo. Nulla facilisi. Donec hendrerit mauris eget nisi mattis, nec tempus est tempor. Vivamus suscipit, nisi non volutpat laoreet, nibh tortor vehicula lectus, vitae aliquet turpis felis eget risus. Nam id elit sed ligula vulputate congue.</p>
            <small>Read more.</small>
        </a>
    </template>
</div>




<script>
    function addNotification() {
        var notificationList = document.getElementById("notificationList");
        var notificationTemplate = document.getElementById("notificationTemplate");

        // Clone the template content
        var notificationCard = notificationTemplate.content.cloneNode(true);

        // Append the new notification card to the notification list
        notificationList.appendChild(notificationCard);
    }

    // Menu toggle for aaron ;)
    function toggleNotiCardMenu(element) {
        var cardMenu = document.getElementById("NoticardMenu");
        var notificationList = document.getElementById("notificationList");

        if (cardMenu.style.display === "none") {
            if (notificationList.children.length === 0) {
                notificationList.innerHTML = "<p>No notifications yet</p>";
            }

            cardMenu.style.display = "block";
            var btn = element;
            cardMenu.style.top = (btn.offsetTop + btn.offsetHeight) + "px";
            cardMenu.style.left = btn.offsetLeft + "px";
        } else {
            cardMenu.style.display = "none";
        }
    }

    function closeNotiCardMenu() {
        var cardMenu = document.getElementById("NoticardMenu");
        cardMenu.style.display = "none";
    }
</script>


<!-- End of Notification popup -->

<!-- Pie Chart -->

<script>
    // Dummy data for the pie chart
    var pieData = {
        labels: ["To Do", "In Progress", "Finished"],
        datasets: [{
            data: [30, 40, 30], // Dummy values
            backgroundColor: [
                'rgba(255, 99, 132, 0.7)',
                'rgba(54, 162, 235, 0.7)',
                'rgba(75, 192, 192, 0.7)'
            ]
        }]
    };

    // Get the context of the canvas element we want to select
    var ctx = document.getElementById("projectStatusChart").getContext('2d');

    // Create the pie chart
    var myPieChart = new Chart(ctx, {
        type: 'pie',
        data: pieData
    });
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