<?php
session_start();

 echo "<br><br>Tasks created for {$_SESSION["project_id"]}.<br><br> ";
?>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <br>Click "Done" to go to the home page: <br>
        <input type="submit" name="done" value="Done">
    </form>
    <?php
    if(isset($_POST['done'])) {
       // session_destroy(); // i blv if we do session here then we will loose session values in loggin so no.
        header("Location: admin.php");
    }
    ?>