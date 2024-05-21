<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Terminal</title>
    <link rel="stylesheet" href="CSS\a_styles.css">
    <script>
        // Function to hide the notification after a specified duration
        function hideNotification() {
            var notification = document.querySelector('.notify');
            if (notification) {
                setTimeout(function() {
                    notification.style.display = 'none';
                }, 5000); // 5000 milliseconds = 5 seconds
            }
        }
        // Call the function when the page loads
        window.onload = function() {
            hideNotification();
        };
    </script>
</head>
<body>
    <header>
    <div class="contact-form">
        <h1></h1>
    </div>
    <nav>
        <form method="post">
            <ul>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="project.php" class="nav-link">View Projects</a></li>
                <li><a href="a_create.php" class="nav-link">Create Project</a></li>
                <li class="dropdown">
                <button class="dropbtn">Options</button>
                <div class="dropdown-content">
                    <button type="submit" name="ui" class="btn">User Information</button>
                    <button type="submit" name="dashboard" class="btn">Dashboard</button>
                    <button type="submit" name="apply_leave" class="btn">Apply for Leave</button>
                    <a href="a_logout.php" class="nav-link">Logout</a>
                </div>
            </li>
            </ul>
        </form>
    </nav>
    </header>
    

    <main>
    <?php
    session_start();
        if (isset($_SESSION["a_id"])) 
        {
            if (isset($_POST["ui"])) 
            {
                echo "<p class='notify'>You are logged in as Admin </p>";
                echo "<div class='info'>";
                echo "<div>User ID: " . $_SESSION["a_id"] . "</div>";
                echo "<div>Email: " . $_SESSION["a_email"] . "</div>";
                echo "</div>";
                // Display other user information here
            }
            elseif (isset($_POST["dashboard"])) 
            {
                header ("Location: dashboard.php") ;
            } 
            elseif (isset($_POST["apply_leave"])) 
            {
                echo "<p class='notify'>Sorry! No leave for you. Enjoy this music! </p>";
                echo "<div class='vid-container'>";
                echo '<div class="vid">';
                echo '<iframe width="1280" height="720" src="https://www.youtube.com/embed/dQw4w9WgXcQ?autoplay=1&mute=1" title="Rick Astley - Never Gonna Give You Up (Official Music Video)" frameborder="0" allow="accelerometer; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
                echo '</div>';
                echo '</div>';
                // Display dashboard content here
            }
        } 
        else 
        {
            echo "<p class='notify'>You are not logged in.</p>";
            header("Location: login.php");
            exit();
        }
        ?>
    </main>
</body>
</html>