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
</body>
</html>

<?php
session_start();
include("database.php");
if (!isset($_SESSION["a_id"])) 
{
    header("Location: login.php");
    exit();
}

if($_SERVER["REQUEST_METHOD"]=="POST") {
    $id = filter_input(INPUT_POST,"id",FILTER_SANITIZE_NUMBER_INT);
    $name = filter_input(INPUT_POST,"name",FILTER_SANITIZE_SPECIAL_CHARS);
    $description = filter_input(INPUT_POST,"description",FILTER_SANITIZE_SPECIAL_CHARS);

    $_SESSION["project_id"] = $id;

    if(empty($id)) 
    {
        echo "Please enter project ID";
    } elseif(empty($name)) 
    {
        echo "Please enter project name";
    } elseif(empty($description)) 
    {
        echo "Please enter project description";
    } 
    else 
    {
        // Check if the project ID already exists
        $check_sql = "SELECT id FROM project WHERE id = '$id'";
        $check_result = mysqli_query($conn, $check_sql);
        if (mysqli_num_rows($check_result) > 0) 
        {
            echo "<p class='notify'>Project ID already exists. Please choose a different ID.</p>";
        } 
        else 
        {
            $sql = "INSERT INTO project (id,name,description) VALUES ('$id', '$name','$description')";
            mysqli_query($conn, $sql);
            echo "<p class='notify'>Project created</p>";
            header("Location: a_create_task.php");
        }
    }
}
mysqli_close($conn);
?>