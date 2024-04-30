<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Project</title>
    <link rel="stylesheet" href="CSS\a_styles.css">
</head>
<body>
    <header>
    <div class="contact-form">
        <h1></h1>
    </div>
        <nav>
            <ul>
                <li><a href="admin.php">Home</a></li>
                <li><a href="project.php">View Projects</a></li>
                <li><a href="a_create.php">Create Project</a></li>
                <li><a href="a_logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>
</body>
</html>
<?php
session_start();
    echo "<div class='info'>";
    echo "Tasks created for Project: {$_SESSION["project_id"]}.<br>";
    echo "Tasks amount: ";
        if(isset($_SESSION["task_count"])) {
            echo $_SESSION["task_count"];
        } else {
            echo "0"; // If task count is not set, display 0
        }
        ?>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <br>Click "Done" to go to the home page: <br>
        <input type="submit" name="done" value="done" class="submit-btn">
    </form>
    </div>
    <?php
    if(isset($_POST['done'])) {
       // session_destroy(); // i blv if we do session here then we will loose session values in loggin so no.
        header("Location: dashboard.php");
    }
    ?>