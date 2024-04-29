<?php 
session_start();
include("database.php"); 
?>

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

    <main>
        <h2>Project Form</h2>
        <form action="<?php htmlspecialchars( $_SERVER["PHP_SELF"] )?>" method="post">
            <label for="id">Enter Project ID:</label><br>
            <input type="text" name="id"><br>
            <label for="name">Enter Project Name:</label><br>
            <input type="text" name="name"><br>
            <label for="description">Give Description:</label><br>
            <textarea id="description" name="description" rows="4" cols="50" placeholder="Enter your description here..."></textarea><br>
            <input type="submit" name="submit" value="Submit" class="submit-btn">
        </form>
    </main>
        <?php if (!isset($_POST['submit'])) { ?>
            <form class="task-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            Enter the number of tasks to create: 
            <input type="number" name="num_tasks" min="1" required> <br>
            <input type="submit" name="submit" value="Submit" class="submit-btn">
            <input type="submit" name="cancel" value="Cancel" class="cancel-btn">
            <input type="hidden" name="project_id" value="<?php echo $_SESSION['project_id']; ?>"> <!-- Add a hidden input field for project ID -->
            </form>
        <?php } ?>
    </main>
</body>
</html>

<?php
if (session_status() === PHP_SESSION_NONE) 
{
    session_start();
}
if (!isset($_SESSION["a_id"])) 
{
    header("Location: login.php");
    exit();
}
include("database.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
    if (isset($_POST['cancel'])) 
    { // Check if cancel button is clicked
        $id = filter_input(INPUT_POST, "project_id", FILTER_SANITIZE_NUMBER_INT); // Retrieve project ID from hidden field
        $delete_sql = "DELETE FROM project WHERE id = '$id'";
        if (mysqli_query($conn, $delete_sql)) 
        {
            echo "<p class='notify'> Project creation cancelled </p>";
        } 
        else 
        {
            echo "Error deleting record: " . mysqli_error($conn);
        }
        exit();
    }

    $num_tasks = filter_input(INPUT_POST, "num_tasks", FILTER_VALIDATE_INT);

    if ($num_tasks === false || $num_tasks <= 0) 
    {
        echo "Please enter a valid number of tasks.";
    }
    else 
    {

        for ($i = 0; $i < $num_tasks; $i++) 
        {

            ?>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                Enter Task ID for Task <?php echo $i + 1; ?>: <br>
                <input type="text" name="ids[]"><br>
                Enter Project ID: 
                <input type="text" name="p_ids[]" value="<?php echo $_SESSION["project_id"];?>"><br> 
                Give Description for Task <?php echo $i + 1; ?>: <br>
                <textarea id="description" name="descriptions[]" rows="4" cols="50" placeholder="Enter your description here..."></textarea><br>
                Enter Employee ID: <br>
                <select name="e_ids[]">
                    <?php
                    $sql = "SELECT id FROM users";
                    $result = mysqli_query($conn, $sql);
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '<option value="' . $row['id'] . '">' . $row['id'] . '</option>';
                    }
                    ?>
                </select><br><br>
            <?php
        }
    }
    ?>
        <input type="submit" name="submit_task" value="Submit Task "><br><br>
    </form>
    <?php
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit_task"])) {
    $ids = array();
    $p_ids = array();
    $descriptions = array();
    $e_ids = array();

    foreach ($_POST["ids"] as $id) {
        $ids[] = $id;
    }
    foreach ($_POST["p_ids"] as $p_id) {
        $p_ids[] = $p_id;
    }
    foreach ($_POST["descriptions"] as $description) {
        $descriptions[] = $description;
    }
    foreach ($_POST["e_ids"] as $e_id) {
        $e_ids[] = $e_id;
    }
    
    $allFieldsFilled = true;

    // Check if any value is empty in any of the arrays
    foreach ($ids as $id) {
        if (empty($id)) {
            $allFieldsFilled = false;
            break; // Exit loop if any value is empty
        }
    }
    
    // Repeat the same check for other arrays
    foreach ($p_ids as $p_id) {
        if (empty($p_id)) {
            $allFieldsFilled = false;
            break;
        }
    }
    
    foreach ($descriptions as $description) {
        if (empty($description)) {
            $allFieldsFilled = false;
            break;
        }
    }
    
    foreach ($e_ids as $e_id) {
        if (empty($e_id)) {
            $allFieldsFilled = false;
            break;
        }
    }
    
    // Check if all fields are filled
    if (!$allFieldsFilled) {
        echo "Please fill in all fields for the Tasks.";
    } else {
        // Insert tasks into the database
        $maxCount = max(count($ids), count($p_ids), count($descriptions), count($e_ids)); // we know that every one will have 4 values
        $_SESSION["task_count"] = $maxCount; // Assuming $maxCount holds the count of tasks created
        for ($i = 0; $i < $maxCount; $i++) 
        { // we can write 4 instead of max count
            // Initialize variables for each iteration
            $id = $ids[$i % count($ids)] ;  // again we can write 4 insted of using count since we know all will have 4 values
            $p_id = $p_ids[$i % count($p_ids)] ;
            $description = $descriptions[$i % count($descriptions)] ;
            $e_id = $e_ids[$i % count($e_ids)] ;

            $sql = "INSERT INTO task (id, project_id, description, employee_id) 
                    VALUES ('$id', '$p_id', '$description', '$e_id')";
            mysqli_query($conn, $sql);
        }
        mysqli_close($conn);
        header("Location: a_done.php");
        }
    }
?>