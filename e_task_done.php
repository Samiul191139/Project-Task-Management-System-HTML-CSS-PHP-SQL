<?php
session_start();
include("database.php");

// Check if the user is logged in
if (!isset($_SESSION["e_id"])) {
    header("Location: login.php");
    exit();
}

$e_id = $_SESSION["e_id"];

// Handle form submission
if($_SERVER["REQUEST_METHOD"]=="POST" && isset($_POST['statuses']))
{
    $statuses=array();
    foreach($_POST["statuses"] as $status)
    { // assign done and ofcourse there goinng to all be ordered by project id.
        $statuses[]=$status;
    }
    
    // Fetch the result set again to update the statuses
    $sql = "SELECT * FROM task WHERE employee_id = {$e_id} ORDER BY project_id ASC";
    $result=mysqli_query($conn,$sql);
    
    // Loop through the rows and update the statuses
    $i=0;
    while($row = mysqli_fetch_assoc($result)) 
    {
        $u_status = mysqli_real_escape_string($conn, $statuses[$i] ?? 'incomplete');
        $project_id = mysqli_real_escape_string($conn, $row["project_id"]);
        $task_id = mysqli_real_escape_string($conn, $row["id"]);
        
        $sqln = "UPDATE task SET status='{$u_status}' WHERE project_id={$project_id} AND id={$task_id}";
        mysqli_query($conn, $sqln);
        
        $i++;
    }
}

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
        <div class="left">
            <form action="e_task.php" method="post">
            <input type="submit" name="submit" value="Go Back" class="l-submit-btn">
            </form>
        </div>
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
                <?php while($row=mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?php echo $row["project_id"]; ?></td>
                <td><?php echo $row["id"]; ?></td>
                <td><?php echo $row["description"]; ?></td>
                <td><?php echo $row["Date"]; ?></td>
                <td><?php echo $row["status"]; ?></td>
                <td>
                    <select name="statuses[]" id="">
                        <option value="incomplete" <?php if ($row["status"] == 'incomplete') echo 'selected'; ?>>Incomplete</option>
                        <option value="on going" <?php if ($row["status"] == 'on going') echo 'selected'; ?>>On Going</option>
                        <option value="completed" <?php if ($row["status"] == 'completed') echo 'selected'; ?>>Completed</option>
                    </select>
                </td>
            </tr>
        <?php endwhile; ?>
        </table>
        <?php
    }
    ?>
    </main>
    <input type="submit" name="submit" value="Submit" class="r-submit-btn">
</body>
</html>