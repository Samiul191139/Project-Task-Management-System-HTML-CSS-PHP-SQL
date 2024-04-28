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
        echo "NO task found.";
        
    }
    ?>
    
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
               <td> <?php echo $row["status"]; ?></td>
               <a href="e_task.php">click to go back...</a>
               <a href="e_logout.php">Logout...</a>
               <a href="employee.php">Home...</a>
            </tr>
        <?php endwhile; ?>
    </table>
    <a href="e_task_done.php">click to change status...</a>
</body>
</html>


