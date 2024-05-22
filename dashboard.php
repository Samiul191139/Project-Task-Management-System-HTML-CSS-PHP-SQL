<?php
session_start();

// Include the database connection file
include("database.php");

if (!isset($_SESSION["a_id"])) {
    // Redirect if not logged in
    header("Location: login.php");
    exit();
}

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
$completed_tasks_query = "SELECT COUNT(*) AS total_completed_tasks FROM task WHERE status = 'completed'";
$completed_tasks_result = mysqli_query($conn, $completed_tasks_query);
$completed_tasks_row = mysqli_fetch_assoc($completed_tasks_result);
$total_completed_tasks = $completed_tasks_row['total_completed_tasks'];

// Query to get total ongoing tasks
$ongoing_tasks_query = "SELECT COUNT(*) AS total_ongoing_tasks FROM task WHERE status = 'on going'";
$ongoing_tasks_result = mysqli_query($conn, $ongoing_tasks_query);
$ongoing_tasks_row = mysqli_fetch_assoc($ongoing_tasks_result);
$total_ongoing_tasks = $ongoing_tasks_row['total_ongoing_tasks'];

// Query to get total incomplete tasks
$incomplete_tasks_query = "SELECT COUNT(*) AS total_incomplete_tasks FROM task WHERE status = 'incomplete'";
$incomplete_tasks_result = mysqli_query($conn, $incomplete_tasks_query);
$incomplete_tasks_row = mysqli_fetch_assoc($incomplete_tasks_result);
$total_incomplete_tasks = $incomplete_tasks_row['total_incomplete_tasks'];

// Query to get total employees
$employees_query = "SELECT COUNT(*) AS total_employees FROM users";
$employees_result = mysqli_query($conn, $employees_query);
$employees_row = mysqli_fetch_assoc($employees_result);
$total_employees = $employees_row['total_employees'];

// Query to get list of employees with their id, email, and active task count
$employees_list_query = "SELECT id, email FROM users";
$employees_list_result = mysqli_query($conn, $employees_list_query);

// Query to get the employee with the highest number of completed tasks
$top_employee_query = "
SELECT u.id AS employee_id, u.email, COUNT(t.id) AS completed_tasks, COUNT(DISTINCT p.id) AS projects_done FROM users u LEFT JOIN task t ON u.id = t.employee_id AND t.status = 'completed' LEFT JOIN project p ON t.project_id = p.id GROUP BY u.id, u.email 
ORDER BY completed_tasks DESC LIMIT 3";
$top_employee_result = mysqli_query($conn, $top_employee_query);
$top_employee_row = mysqli_fetch_assoc($top_employee_result);

$completed_tasks_by_employee_query = "
    SELECT 
        u.id AS employee_id, 
        u.email, 
        COUNT(t.id) AS completed_tasks
    FROM 
        users u
    LEFT JOIN 
        task t ON u.id = t.employee_id AND t.status = 'completed'
    GROUP BY 
        u.id, u.email
    ORDER BY 
        completed_tasks DESC
        limit 3";
$completed_tasks_by_employee_result = mysqli_query($conn, $completed_tasks_by_employee_query);

// Initialize arrays to store employee data for the chart
$employee_ids = [];
$employee_emails = [];
$completed_tasks_counts = [];

while ($row = mysqli_fetch_assoc($completed_tasks_by_employee_result)) {
    $employee_ids[] = $row['employee_id'];
    $employee_emails[] = $row['email'];
    $completed_tasks_counts[] = $row['completed_tasks'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Terminal</title>
    <link rel="stylesheet" href="CSS\dashstyle.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                    <li><a href="admin.php">More</a></li>
                    <li><a href="a_logout.php">Logout</a></li>
                </ul>
            </form>
        </nav>
    </header>

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
            <h3>Top Employee with Highest Completed Tasks</h3>
            <div class="flex">
                <table class="left">
                    <tr>
                        <th>ID</th>
                        <th>Email</th>
                        <th>Completed Tasks</th>
                    </tr>
                    <?php
                    // Re-fetch the data before using it in the table
                    $completed_tasks_by_employee_result = mysqli_query($conn, $completed_tasks_by_employee_query);
                    // Fetch and display the top 3 employees with the highest completed tasks
                    while ($employee_row = mysqli_fetch_assoc($completed_tasks_by_employee_result)) {
                        echo "<tr>";
                        echo "<td>" . $employee_row['employee_id'] . "</td>";
                        echo "<td>" . $employee_row['email'] . "</td>";
                        echo "<td>" . $employee_row['completed_tasks'] . "</td>";
                        echo "</tr>";
                    }
                    ?>
                </table>


                <div class="right">
                    <div class="chart-container">
                        <canvas id="completedTasksChart"></canvas>
                    </div>
                </div>
            </div>

            <h3>Employees List</h3>
            <div class="flex">
            <table class="left">
                <tr>
                    <th>ID</th>
                    <th>Email</th>
                    <th>Ongoing Task</th>
                    <th>Assigned Project</th>
                    <th>Completed Project</th>
                    <th>Completed Task</th>
                </tr>
                <?php
                // Fetch and display the list of employees
                while ($employee_row = mysqli_fetch_assoc($employees_list_result)) {
                    // Query to get active task count for each employee
                    $employee_id = $employee_row['id'];
                    $active_task_count_query = "SELECT COUNT(*) AS active_task_count FROM task WHERE employee_id = '$employee_id' AND status = 'on going'";
                    $active_task_count_result = mysqli_query($conn, $active_task_count_query);
                    $active_task_count_row = mysqli_fetch_assoc($active_task_count_result);
                    $active_task_count = $active_task_count_row['active_task_count'];

                // Query to get assigned project count for each employee
                    $assigned_projects_query = "SELECT COUNT(DISTINCT project_id) AS assigned_project_count FROM task WHERE employee_id = '$employee_id'";
                    $assigned_projects_result = mysqli_query($conn, $assigned_projects_query);
                    $assigned_projects_row = mysqli_fetch_assoc($assigned_projects_result);
                    $assigned_project_count = $assigned_projects_row['assigned_project_count'];
                    
                // Query to get completed project count for each employee
                    $completed_projects_query = "SELECT COUNT(DISTINCT project_id) AS completed_project_count FROM task WHERE employee_id = '$employee_id' AND status = 'completed'";
                    $completed_projects_result = mysqli_query($conn, $completed_projects_query);
                    $completed_projects_row = mysqli_fetch_assoc($completed_projects_result);
                    $completed_project_count = $completed_projects_row['completed_project_count'];

                // Query to get total completed tasks
                    $completed_task_query = "SELECT COUNT(*) AS total_completed_task FROM task WHERE employee_id = '$employee_id' AND status = 'completed'";
                    $completed_task_result = mysqli_query($conn, $completed_task_query);
                    $completed_task_row = mysqli_fetch_assoc($completed_task_result);
                    $total_completed_task = $completed_task_row['total_completed_task'];

                    echo "<tr>";
                    echo "<td>" . $employee_row['id'] . "</td>";
                    echo "<td>" . $employee_row['email'] . "</td>";
                    echo "<td>" . $active_task_count . "</td>";
                    echo "<td>" . $assigned_project_count . "</td>";
                    echo "<td>" . $completed_project_count . "</td>";
                    echo "<td>" . $total_completed_task . "</td>";
                    echo "</tr>";
                }
                ?>
            </table>
            <div class="right">
                    <div class="chart-container">
                        <canvas id="tasksDistributionChart"></canvas>
                    </div>
            </div>
        </div>
    </div>

        <script>
            // JavaScript code to generate the bar chart using Chart.js
            var ctx = document.getElementById('completedTasksChart').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: <?php echo json_encode($employee_emails); ?>,
                    datasets: [{
                        label: 'Completed Tasks',
                        data: <?php echo json_encode($completed_tasks_counts); ?>,
                        backgroundColor: 'rgba(18, 236, 84, 1)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 3
                    }]
                },
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }
            });

            // JavaScript code to generate the pie chart for task distribution
            var ctxPie = document.getElementById('tasksDistributionChart').getContext('2d');
            var tasksDistributionChart = new Chart(ctxPie, {
                type: 'pie',
                data: {
                    labels: ['Completed Tasks', 'Ongoing Tasks', 'Incomplete Tasks'],
                    datasets: [{
                        data: [<?php echo $total_completed_tasks; ?>, <?php echo $total_ongoing_tasks; ?>, <?php echo $total_incomplete_tasks; ?>],
                        backgroundColor: [
                            'rgba(75, 192, 192, 0.6)',
                            'rgba(255, 206, 86, 0.6)',
                            'rgba(255, 99, 132, 0.6)'
                        ],
                        borderColor: [
                            'rgba(75, 192, 192, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(255, 99, 132, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
        </script>
    </main>
    <main>
</body>
</html>
