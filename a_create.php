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
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="project.php">View Projects</a></li>
                <li><a href="a_create.php">Create Project</a></li>
                <li><a href="a_logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <h2>Project Form</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <label for="id">Enter Project ID:</label><br>
            <input type="text" name="id"><br>
            <label for="name">Enter Project Name:</label><br>
            <input type="text" name="name"><br>
            <label for="description">Give Description:</label><br>
            <textarea id="description" name="description" rows="4" cols="50" placeholder="Enter your description here..."></textarea><br>
            <label for="due_date">Due Date:</label><br>
            <input type="date" name="due_date"><br><br>
            <input type="submit" name="submit" value="Submit" class="submit-btn">
            <input type="submit" name="cancel" value="Cancel" class="cancel-btn">
        </form>
    </main>
</body>
</html>

<?php
session_start();
include("database.php");

if (!isset($_SESSION["a_id"])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
    if (isset($_POST['submit'])) {
        $id = filter_input(INPUT_POST, "id", FILTER_SANITIZE_NUMBER_INT);
        $name = filter_input(INPUT_POST, "name", FILTER_SANITIZE_SPECIAL_CHARS);
        $description = filter_input(INPUT_POST, "description", FILTER_SANITIZE_SPECIAL_CHARS);
        $due_date = filter_input(INPUT_POST, "due_date", FILTER_SANITIZE_STRING);

        $_SESSION["project_id"] = $id;
        
        if (empty($id)) {
            echo "<p class='notify'>Please enter Project ID.</p>";
        } elseif (empty($name)) {
            echo "<p class='notify'>Please enter Project Name.</p>";
        } elseif (empty($description)) {
            echo "<p class='notify'>Please enter Project Description.</p>";
        } elseif (empty($due_date)) {
            echo "<p class='notify'>Please enter a Due Date.</p>";
        } else {
            // Check if the project ID already exists
            $check_sql = "SELECT id FROM project WHERE id = ?";
            $stmt = $conn->prepare($check_sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $check_result = $stmt->get_result();
            
            if ($check_result->num_rows > 0) {
                echo "<p class='notify'>Project ID already exists. Please choose a different ID.</p>";
            } 
            else 
            {
                // Check if the due date is not earlier than today
                $current_date = date("Y-m-d");
                if ($due_date < $current_date) {
                    echo "<p class='notify'>Due date cannot be earlier than the project creation date.</p>";
                } else {
                    // Insert project with due date
                    $sql = "INSERT INTO project (id, name, description, Date, due_date) VALUES (?, ?, ?, ?, ?)";
                    $stmt = $conn->prepare($sql);
                    $creation_date = date("Y-m-d");
                    $stmt->bind_param("issss", $id, $name, $description, $creation_date, $due_date);
                    $stmt->execute();
                    
                    echo "<p class='notify'>Project created.</p>";
                    header("Location: a_create_task.php");
                }
            }
        }
    } elseif (isset($_POST['cancel'])) {
        echo "<p class='notify'>Project creation cancelled.</p>";
        exit();
    }
}
mysqli_close($conn);
?>