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
            echo "<p>You are not logged in.</p>";
            header("Location: login.php");
            exit();
        }
        ?>
    </main>
    <?php
    // Include the database connection file
    include("database.php");    
    if (isset($_SESSION["a_id"])) 
    {
        // Query to get total projects
        $projects_query = "SELECT COUNT(*) AS total_projects FROM project";
        $projects_result = mysqli_query($conn, $projects_query);
        $projects_row = mysqli_fetch_assoc($projects_result);
        $total_projects = $projects_row['total_projects'];
        
        // Query to get total tasks
        $tasks_query = "SELECT COUNT(*) AS total_tasks FROM task";
        $tasks_result = mysqli_query($conn, $tasks_query);
        $tasks_row = mysqli_fetch_assoc($tasks_result);
        $total_tasks = $tasks_row['total_tasks'];
        
        // Query to get total completed tasks
        $completed_tasks_query = "SELECT COUNT(*) AS total_completed_tasks FROM task WHERE status = 'Completed'";
        $completed_tasks_result = mysqli_query($conn, $completed_tasks_query);
        $completed_tasks_row = mysqli_fetch_assoc($completed_tasks_result);
        $total_completed_tasks = $completed_tasks_row['total_completed_tasks'];
        
        // Query to get total employees
        $employees_query = "SELECT COUNT(*) AS total_employees FROM users";
        $employees_result = mysqli_query($conn, $employees_query);
        $employees_row = mysqli_fetch_assoc($employees_result);
        $total_employees = $employees_row['total_employees'];
        
        // Query to get list of employees with their id, email, and active task count
        $employees_list_query = "SELECT id, email FROM users";
        $employees_list_result = mysqli_query($conn, $employees_list_query);
    } 
    else 
    {
        // Redirect if not logged in
        header("Location: login.php");
        exit();
    }
?>

<main>
    <div class="dashboard">
        <div class="dashboard-summary">
            <div class="summary-item">
                <h3>Total Projects</h3>
                <p><?php echo $total_projects; ?></p>
            </div>
            <div class="summary-item">
                <h3>Total Tasks</h3>
                <p><?php echo $total_tasks; ?></p>
            </div>
            <div class="summary-item">
                <h3>Completed Tasks</h3>
                <p><?php echo $total_completed_tasks; ?></p>
            </div>
            <div class="summary-item">
                <h3>Total Employees</h3>
                <p><?php echo $total_employees; ?></p>
            </div>
        </div>
    </div>
</main>
</body>
</html>