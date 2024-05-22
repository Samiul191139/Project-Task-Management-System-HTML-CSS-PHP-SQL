<?php
session_start();
if (!isset($_SESSION["a_id"])) 
{
    header("Location: login.php");
    exit();
}
include("database.php");


$sql = "SELECT * FROM project";
$result = mysqli_query($conn, $sql);

if (!(mysqli_num_rows($result) > 0)) {
    echo '<p class="notify">No project found. Create a new project </p>';
    //echo "<a href='a_create.php'>New Project</a>";
} else {
    // Initialize arrays to store task counts
    $p_t = array(); // Number of tasks for each project
    $p_t_c = array(); // Number of completed tasks for each project

    // Fetch task counts for each project
    $sql_nt = "SELECT project_id, COUNT(id) AS task_count FROM `task` GROUP BY project_id";
    $r_nt = mysqli_query($conn, $sql_nt);
    while ($row = mysqli_fetch_assoc($r_nt)) {
        $p_t[$row['project_id']] = $row['task_count'];
        $p_t_c[$row['project_id']] = 0; // Initialize completed tasks count to zero
    }

    // Update completed tasks count
    $sql_ntc = "SELECT project_id, status FROM `task` ORDER BY project_id ASC";
    $r_ntc = mysqli_query($conn, $sql_ntc);
    while ($row = mysqli_fetch_assoc($r_ntc)) {
        if ($row['status'] === 'completed') {
            $p_t_c[$row['project_id']]++; // Increment completed tasks count
        }
    }

    // Update total percentage for each project
    foreach ($p_t as $project_id => $total_tasks) {
        if ($total_tasks != 0) {
            $u_tp = ($p_t_c[$project_id] / $total_tasks) * 100;
        } else {
            $u_tp = 0; // Handle division by zero error
        }
        $sqln = "UPDATE project SET total_percentage='{$u_tp}' WHERE id={$project_id}";
        mysqli_query($conn, $sqln);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projects</title>
    <link rel="stylesheet" href="CSS\a_styles.css">
</head>
<body>
    <header>
        <div class="contact-form">
            <h1></h1>
        </div>
        <nav>
            <ul>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="project.php">View Projects</a></li>
                <li><a href="a_create.php">Create Project</a></li>
                <li><a href="a_logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <table>
            <tr>
                <th>Project ID</th>
                <th>Project Name</th>
                <th>Description</th>
                <th>Task Assigned</th>
                <th>Creation Date</th>
                <th>Due Date</th>
                <th>Project completed (%)</th>
                <th>Action</th>
            </tr>
            <?php mysqli_data_seek($result, 0); 
            while($row=mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo $row["id"]; ?></td>
                    <td><?php echo $row["name"]; ?></td>
                    <td><?php echo $row["description"]; ?></td>
                    <td><?php echo isset($p_t[$row['id']]) ? $p_t[$row['id']] : 0; ?></td>
                    <td><?php echo $row["Date"]; ?></td>
                    <td><?php echo $row["due_date"]; ?></td>
                    <td><?php echo $row["total_percentage"]; ?></td>
                    <td>
                        <form action="a_delete_project.php" method="POST">
                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                            <button type="submit" name="delete">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    </main>
</body>
</html>