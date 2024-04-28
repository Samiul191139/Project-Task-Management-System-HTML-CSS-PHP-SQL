<?php
session_start();
include("database.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
    $e_id= $_SESSION["e_id"];
    $sql = "SELECT * FROM task WHERE employee_id = {$e_id} ORDER BY project_id ASC";
    $result=mysqli_query($conn,$sql);
    if(!(mysqli_num_rows($result)>0))
    {  // if the number of rows is not greater then 0 or empty
        echo"NO task found.";
        
    }
    ?>
    <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"])?>" method="post">
    <table>
        <tr>
            <th> PROJECT NO </th>
            <th> TASK NO </th>
            <th> DECRIPTION </th>
            <th> STATUS </th>
        </tr>
        <?php while($row=mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?php echo $row["project_id"]; ?></td>
                <td><?php echo $row["id"]; ?></td>
                
                <td><?php echo $row["description"]; ?></td>
               <td> <?php echo $row["status"]; ?>
                <select name="statuses[]" id="">
                    <option value="incomplete">incomplete</option>
                    <option value="on going">on going</option>
                    <option value="completed">completed</option>
                    </select>
               </td>
            </tr>
        <?php endwhile; ?>
    </table>
    <input type="submit" name="submit" value="submit">
    </form>
    <a href="e_task.php">click to go back...</a>
</body>
</html>

<?php
if($_SERVER["REQUEST_METHOD"]=="POST")
{
    $statuses=array();
    foreach($_POST["statuses"] as $status)
    { // assign done and ofcourse there goinng to all be ordered by project id.
        $statuses[]=$status;
    }
    
    // Fetch the result set again to update the statuses
    $e_id = $_SESSION["e_id"];
    $sql = "SELECT * FROM task WHERE employee_id = {$e_id} ORDER BY project_id ASC";
    $result=mysqli_query($conn,$sql);
    
    // Loop through the rows and update the statuses
    $i=0;
    while($row = mysqli_fetch_assoc($result)) {
        $u_status = mysqli_real_escape_string($conn, $statuses[$i]);
        $project_id = mysqli_real_escape_string($conn, $row["project_id"]);
        $task_id = mysqli_real_escape_string($conn, $row["id"]);
        
        $sqln = "UPDATE task SET status='{$u_status}' WHERE project_id={$project_id} AND id={$task_id}";
        mysqli_query($conn, $sqln);
    
        $i++;
    }
}
?>