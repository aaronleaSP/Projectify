<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login and Registration</title>
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            background-color: #f2f2f2;
        }

        .container {
            display: flex;
            max-width: 800px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .image-container {
            flex: 1;
        }

        .image-container img {
            width: 100%;
            height: auto;
            border-top-left-radius: 8px;
            border-bottom-left-radius: 8px;
        }

        .form-container {
            flex: 1;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .form-container h2 {
            margin-bottom: 20px;
        }

        .form-container form {
            display: none;
            text-align: left;
        }

        .form-container form.active {
            display: block;
        }

        .toggle-buttons {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .toggle-buttons button {
            flex: 1;
            margin: 0 5px;
            cursor: pointer;
            padding: 10px;
            border: none;
            border-radius: 4px;
            width: 100px;
            transition: background-color 0.3s;
        }

        .toggle-buttons button.active {
            background-color: cornflowerblue;
            color: #fff;
        }

        .button {
            background-color: palegreen;
            margin-left: 50px;
        }

        .error-message {
            background-color: #FFCCCC;
            color: #FF0000;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            width: 100px;
            margin-left: 10px; /* Adjust the left margin */
        }
    </style>
</head>

<body>
<div class="container">
    <div class="image-container">
        <img src="images/bg.jpg" alt="Your Image">
    </div>

    <div class="form-container" id="loginContainer">
        <h2>Welcome! Please Login or Register</h2>
        <div class="toggle-buttons">
            <button onclick="toggleForm('loginForm')" id="loginButton" class="active">Login</button>
            <button onclick="toggleForm('registerForm')" id="registerButton">Register</button>
        </div>

        <form id="loginForm" action="Login_process.php" method="post" class="active">
            <?php
            session_start();
            if (isset($_SESSION['error_message'])) {
                echo '<p class="error-message">' . $_SESSION['error_message'] . '</p>';
                unset($_SESSION['error_message']); // Clear the error message
            }
            ?>

            <label for="username">Username:</label><br>
            <input type="text" id="username" name="username" required><p></p>

            <label for="password">Password:</label><br>
            <input type="password" id="password" name="password" required><p></p>

            <button class="button" type="submit">Enter</button>
        </form>

        <form id="registerForm" action="Register_process.php" method="post">
            <label for="newUsername">Username:</label><br>
            <input type="text" id="newUsername" name="newUsername" required><br>

            <label for="email">Email:</label><br>
            <input type="email" id="email" name="email" required><br>

            <label for="phone">Phone Number:</label><br>
            <input type="tel" id="phone" name="phone" required><br>

            <label for="newPassword">Password:</label><br>
            <input type="password" id="newPassword" name="newPassword" required><br>

            <label for="confirmPassword">Confirm Password:</label><br>
            <input type="password" id="confirmPassword" name="confirmPassword" required><p></p>

            <button class="button" type="submit" onclick="changeButtonColor('registerButton')">Register</button>
        </form>
    </div>
</div>

<script>
    function toggleForm(formId) {
        var forms = document.querySelectorAll('.form-container form');
        forms.forEach(function(form) {
            form.classList.remove('active');
        });

        document.getElementById(formId).classList.add('active');

        var buttons = document.querySelectorAll('.toggle-buttons button');
        buttons.forEach(function(button) {
            button.classList.remove('active');
        });

        document.getElementById(formId === 'loginForm' ? 'loginButton' : 'registerButton').classList.add('active');
    }

    function changeButtonColor(buttonId) {
        var buttons = document.querySelectorAll('.toggle-buttons button');
        buttons.forEach(function(button) {
            button.classList.remove('active');
        });

        document.getElementById(buttonId).classList.add('active');
    }
</script>

</body>
</html>
