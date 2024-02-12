<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Homepage</title>
    <link rel="stylesheet" href="styles/homepage.css">
</head>
<body>

<header>
    <div class="logo">
        <img src="images/icon.png" alt="Logo">
    </div>
    <div class="welcome">
        Welcome
    </div>
    <div class="right-section">
        <img src="images/notification.png" alt="notification">
        <a href="logout.php">Logout</a>
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
        <div class="card all-project">
            <h3>All Project</h3>
            <p class="card-link"><a href="all_project.php">View Details</a></p>
        </div>
        <div class="card add-project">
            <h3>Add Project</h3>
            <p class="card-link"><a href="add_project.php">View Details</a></p>
        </div>
    </div>
    <div class="bottom-cards">
        <!-- Bottom three cards content goes here -->
        <div class="card not-started-card">
            <h3>Not Started</h3>
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
    <div class="cloud-section">
        <div class="cloud-container">
            <div class="cloud">
                <img src="images/permission_img1.png" alt="permissionImg" class="cloud-image">
            </div>
        </div>

        <div class="table-section">
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
        </div>
    </div>


</section>

<section id="taskSection" style="display: none; justify-content: center;">

    <div class="horizontal-section" style="font-size: 20px">
        <div class="task-subsection" id="todo" style="background-color: #f2f2f2;" >
            <h2>To Do</h2>
            <div class="card" >
                Design the calendar page layout<br>
                <button class="delete-button" onclick="deleteTask('todo')">Delete</button>
                <div class="time">1/29/2024</div>
            </div>
            <div class="card">
                Design the login page layout<br>
                <button class="delete-button" onclick="deleteTask('todo')">Delete</button>
                <div class="time">2/1/2024</div>
            </div>
            <div class="card">
                Save the user information in the database<br>
                <button class="delete-button" onclick="deleteTask('todo')">Delete</button>
                <div class="time">2/3/2024</div>
            </div>
            <button class="add-button" onclick="showAddTaskForm('todo')">Add Task</button>
        </div>

        <div class="task-subsection" id="inProgress" style="background-color: #f2f2f2;">
            <h2>In Progress</h2>
            <div class="card">
                Design the homepage page layout<br>
                <button class="delete-button" onclick="deleteTask('inProgress')">Delete</button>
                <div class="time">2/4/2024</div>
            </div>
            <div class="card">
                Notification<br>
                <button class="delete-button" onclick="deleteTask('inProgress')">Delete</button>
                <div class="time">2/6/2024</div>
            </div>
            <button class="add-button" onclick="showAddTaskForm('inProgress')">Add Task</button>
        </div>

        <div class="task-subsection" id="done" style="background-color: #f2f2f2;">
            <h2>Done</h2>
            <div class="card">
                Logout function<br>
                <button class="delete-button" onclick="deleteTask('done')">Delete</button>
                <div class="time">1/27/2024</div>
            </div>
            <div class="card">
                Design the website icon<br>
                <button class="delete-button" onclick="deleteTask('done')">Delete</button>
                <div class="time">1/29/2024</div>
            </div>
            <div class="card">
                Continue with Google<br>
                <button class="delete-button" onclick="deleteTask('done')">Delete</button>
                <div class="time">2/2/2024</div>
            </div>
            <button class="add-button" onclick="showAddTaskForm('done')">Add Task</button>
        </div>
    </div>
</section>


<section class="calendar" id="calendarSection" style="display: none;">
    <!-- Calendar content goes here -->
    <object type="text/html" data="calendar.html" style="width: 100%;height: 600px"></object>

</section>

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

    function showAddTaskForm(sectionId) {
        // You can implement logic here to show an add task form for the specified section
        // For example, display a modal or navigate to a new page with the add task form
        console.log(`Add task form for ${sectionId}`);
    }

</script>

</body>
</html>
