<?php
session_start();
include("database.php");

// Check if the user is logged in
if (!isset($_SESSION["e_id"])) {
    header("Location: login.php");
    exit();
}

$e_id = $_SESSION["e_id"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Update</title>
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
        </table>
        <input type="submit" name="submit" value="Submit" class="submit-btn">
            <form action="e_task.php" method="post">
            <input type="submit" name="submit" value="Go Back" class="submit-btn">
            </form>
        </form>
    <?php
    $sql = "SELECT * FROM task WHERE employee_id = {$e_id} ORDER BY project_id ASC";
    $result = mysqli_query($conn, $sql);
    if (!(mysqli_num_rows($result) > 0)) {
        // if the number of rows is not greater than 0 or empty
        echo "NO task found.";
    } 
    else 
    {
        ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <table>
                <tr>
                    <th>Project NO</th>
                    <th>Task NO</th>
                    <th>Description</th>
                    <th>Assigned Date</th>
                    <th>Current Status</th>
                    <th>Change</th>
                </tr>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row["project_id"]); ?></td>
                        <td><?php echo htmlspecialchars($row["id"]); ?></td>
                        <td><?php echo htmlspecialchars($row["description"]); ?></td>
                        <td><?php echo htmlspecialchars($row["Date"]); ?></td>
                        <td><?php echo htmlspecialchars($row["status"]); ?></td>
                        <td>
                            <select name="statuses[<?php echo $row['id']; ?>]">
                                <option value="incomplete" <?php if ($row["status"] == 'incomplete') echo 'selected'; ?>>incomplete</option>
                                <option value="on going" <?php if ($row["status"] == 'on going') echo 'selected'; ?>>on going</option>
                                <option value="completed" <?php if ($row["status"] == 'completed') echo 'selected'; ?>>completed</option>
                            </select>
                        </td>
                    </tr>
                <?php endwhile; ?>
        <?php
    }
    ?>
    </main>
</body>
</html>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) 
{
    if (isset($_POST['statuses']) && is_array($_POST['statuses'])) 
    {
        foreach ($_POST['statuses'] as $task_id => $status) 
        {
            $u_status = mysqli_real_escape_string($conn, $status);
            $task_id = mysqli_real_escape_string($conn, $task_id);
            $sqln = "UPDATE task SET status='$u_status' WHERE id=$task_id AND employee_id=$e_id";
            mysqli_query($conn, $sqln);
        }
    }
}
?>
