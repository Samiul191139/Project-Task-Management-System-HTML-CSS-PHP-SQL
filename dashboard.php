<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Terminal</title>
    <link rel="stylesheet" href="CSS\dashstyle.css">
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
                <li><a href="admin.php">Home</a></li>
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
    <?php
    // Include the database connection file
    include("database.php");
    
    session_start();
    
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
        <h2>Dashboard</h2>
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
        
        <h3>Employees List</h3>
        <table>
            <tr>
                <th>ID</th>
                <th>Email</th>
                <th>Ongoing Task</th>
            </tr>
            <?php
                while ($employee_row = mysqli_fetch_assoc($employees_list_result)) {
                    // Query to get active task count for each employee
                    $employee_id = $employee_row['id'];
                    $active_task_count_query = "SELECT COUNT(*) AS active_task_count FROM task WHERE employee_id = '$employee_id' AND status = 'on going'";
                    $active_task_count_result = mysqli_query($conn, $active_task_count_query);
                    $active_task_count_row = mysqli_fetch_assoc($active_task_count_result);
                    $active_task_count = $active_task_count_row['active_task_count'];
                    
                    echo "<tr>";
                    echo "<td>" . $employee_row['id'] . "</td>";
                    echo "<td>" . $employee_row['email'] . "</td>";
                    echo "<td>" . $active_task_count . "</td>";
                    echo "</tr>";
                }
            ?>
        </table>
    </div>
</main>
