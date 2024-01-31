<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Homepage</title>
    <link rel="stylesheet" href="homepage.css">
</head>
<body>

<header>
    <div class="logo">
        <img src="logo.png" alt="Logo">
    </div>
    <div class="welcome">
        Welcome
    </div>
    <div class="right-section">
        <img src="notification.png" alt="notification">
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

    <div id="inProgress">
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
