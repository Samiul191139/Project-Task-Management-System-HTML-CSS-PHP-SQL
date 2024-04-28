<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout</title>
    <link rel="stylesheet" href="CSS\a_styles.css">
</head>
<body>
    <header>
        <h1>Logout</h1>
    </header>

    <main>
        <?php
            session_start();
            include("database.php");
            $id = $_SESSION["a_id"];
            $password = $_SESSION["a_password"];
            $email = $_SESSION["a_email"];
            $type = $_SESSION["a_type"];

            $sql = "INSERT INTO logout (id,password,type,email) VALUES ('$id', '$password','$type','$email')";
            mysqli_query($conn,$sql);
            echo "You are logged out";
            session_destroy();
            header("Location: login.php");
        ?>
    </main>
</body>
</html>
