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
    <title>Task update</title>
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
        <h2>Information Table</h2>
        <form action="e_task_done.php" method="post">
        <input type="submit" name="submit" value="Change Status" class="r-submit-btn">
        </form>
        <?php
        // Query to get the tasks
        $sql = "SELECT * FROM task WHERE employee_id = ? ORDER BY project_id ASC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $e_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) 
        {
            echo "<table>
                <tr>
                    <th>Project NO.</th>
                    <th>Task NO.</th>
                    <th>Description</th>
                    <th>Assigned Date</th>
                    <th>Status</th>
                </tr>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                    <td>" . htmlspecialchars($row["project_id"]) . "</td>
                    <td>" . htmlspecialchars($row["id"]) . "</td>
                    <td>" . htmlspecialchars($row["description"]) . "</td>
                    <td>" . htmlspecialchars($row["Date"]) . "</td>
                    <td>" . htmlspecialchars($row["status"]) . "</td>
                </tr>";
            }
            echo "</table>";
        } 
        else 
        {
            echo "<p class='notify'>No task found </p>";
        }
        $stmt->close();
        ?>
        <br>
        <form action="employee.php" method="post">
        <input type="submit" name="submit" value="Go Back" class="submit-btn">
        </form>
    </main>
</body>
</html>
