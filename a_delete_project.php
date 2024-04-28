<?php
session_start();
include("database.php");

if(isset($_POST["delete"])) 
{
    $id = $_POST["id"];
    
    // Delete tasks with the project
    $sql_delete_tasks = "DELETE FROM task WHERE project_id=$id";
    mysqli_query($conn, $sql_delete_tasks);
    
    // Delete the project
    $sql_delete_project = "DELETE FROM project WHERE id=$id";
    mysqli_query($conn, $sql_delete_project);
    
    // Redirect back to project.php after deletion
    header("Location: project.php");
    exit();
} 
else 
{
    header("Location: project.php");
    exit();
}
?>