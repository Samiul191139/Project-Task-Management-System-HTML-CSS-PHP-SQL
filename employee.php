<?php
session_start();
include("database.php");

// Check if the user is logged in
if (!isset($_SESSION["e_id"])) {
    header("Location: login.php");
    exit();
}

$e_id = $_SESSION["e_id"];

// Query to get the counts
$sql_projects = "SELECT COUNT(DISTINCT project_id) AS project_count FROM task WHERE employee_id = ?";
$sql_tasks = "SELECT COUNT(*) AS task_count FROM task WHERE employee_id = ?";
$sql_completed_tasks = "SELECT COUNT(*) AS completed_count FROM task WHERE employee_id = ? AND status = 'completed'";
$sql_incomplete_tasks = "SELECT COUNT(*) AS incomplete_count FROM task WHERE employee_id = ? AND status != 'completed'";

$stmt_projects = $conn->prepare($sql_projects);
$stmt_projects->bind_param("i", $e_id);
$stmt_projects->execute();
$result_projects = $stmt_projects->get_result();
$project_count = $result_projects->fetch_assoc()['project_count'];

$stmt_tasks = $conn->prepare($sql_tasks);
$stmt_tasks->bind_param("i", $e_id);
$stmt_tasks->execute();
$result_tasks = $stmt_tasks->get_result();
$task_count = $result_tasks->fetch_assoc()['task_count'];

$stmt_completed_tasks = $conn->prepare($sql_completed_tasks);
$stmt_completed_tasks->bind_param("i", $e_id);
$stmt_completed_tasks->execute();
$result_completed_tasks = $stmt_completed_tasks->get_result();
$completed_count = $result_completed_tasks->fetch_assoc()['completed_count'];

$stmt_incomplete_tasks = $conn->prepare($sql_incomplete_tasks);
$stmt_incomplete_tasks->bind_param("i", $e_id);
$stmt_incomplete_tasks->execute();
$result_incomplete_tasks = $stmt_incomplete_tasks->get_result();
$incomplete_count = $result_incomplete_tasks->fetch_assoc()['incomplete_count'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Dashboard</title>
    <link rel="stylesheet" href="CSS\e_styles.css">
</head>
<body>
    <header>
        <div class="container">
            <h1 class="logo"></h1>
            <nav>
                <ul>
                    <li><a href="employee.php">Home</a></li>
                    <li><a href="e_task.php">View Tasks</a></li>
                    <li><a href="#">Contact</a></li>
                    <li><a href="e_logout.php">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <main>
        <h2>Welcome to Your Dashboard</h1>
        <?php echo "<p>User ID: " . $_SESSION["e_id"] . "</p>"; ?>
        <?php echo "<p>Mail: " . $_SESSION["e_email"] . "</p>"; ?>
        <?php
        if (isset($_SESSION["e_id"])) {
            // echo "<p>You are logged in as User ID: " . $_SESSION["e_id"] . "</p>";
        } else {
            echo "<p class='notify'>You are not logged in </p>";
        }
        ?>
        <div class="dashboard">
            <div class="card">
                <h2>Projects Assigned</h2>
                <p><?php echo $project_count; ?></p>
            </div>
            <div class="card">
                <h2>Tasks Assigned</h2>
                <p><?php echo $task_count; ?></p>
            </div>
            <div class="card">
                <h2>Completed Tasks</h2>
                <p><?php echo $completed_count; ?></p>
            </div>
            <div class="card">
                <h2>Incomplete Tasks</h2>
                <p><?php echo $incomplete_count; ?></p>
            </div>
        </div>

        <h2>Ongoing Projects</h2>
        <table>
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Project ID</th>
                    <th>Task No.</th>
                    <th>Assigned Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql_ongoing_projects = "SELECT project_id, id, Date, status FROM task WHERE employee_id = ? AND status != 'completed'";
                $stmt_ongoing_projects = $conn->prepare($sql_ongoing_projects); //prepare() function prepares the SQL query defined in $sql_ongoing_projects for execution.
                $stmt_ongoing_projects->bind_param("i", $e_id); //binds the parameter values to the placeholders in the prepared statement
                $stmt_ongoing_projects->execute(); //executes the prepared statement
                $result_ongoing_projects = $stmt_ongoing_projects->get_result(); //holds the result set returned by the executed SQL query

                $row_num = 1;
                while ($row = $result_ongoing_projects->fetch_assoc()) 
                {
                    echo "<tr>";
                    echo "<td>" . $row_num++ . "</td>";
                    echo "<td>" . $row['project_id'] . "</td>";
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td>" . $row['Date'] . "</td>";
                    echo "<td>"  . $row['status'] . "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </main>
</body>
</html>