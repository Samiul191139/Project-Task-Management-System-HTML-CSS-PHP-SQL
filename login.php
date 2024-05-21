<?php
session_start();
if (isset($_SESSION["user"])) 
{
   header("Location: index.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="CSS\loginstyle.css">
</head>
<body>
<header>
    <h3 class="logo">Logo</h3>
    <nav class="navigation">
        <a href="#" class="nav-link">Home</a>
        <a href="about.php" class="nav-link">About</a>
        <a href="#" class="nav-link">Services</a>
        <a href="#" class="nav-link">Contact</a>
        <a href="login.php" class="btnloginpopup">Login</a>
    </nav>
</header>
    <div class="contact-form">
        <div class="container">
            <div class="main">
                <div class="content">
                    <h2>Login</h2>
                    <form action="#" method="post">
                        <ion-icon name="mail-outline"></ion-icon>
                        <input type="number" name="id" placeholder="Enter Your ID">
                        <input type="email" name="email" placeholder="Enter Your Email">
                        <input type="password" name="password" placeholder="Enter Password">
                        <div class="dropdown">
                            <select name="user_type" id="user_type">
                                <option value="users">User</option>
                                <option value="admins">Admin</option>
                            </select>
                        </div>
                        <button type="submit" name="submit" class="btn">Login</button>
                    </form>
                    <p class="inform">Don't have an account? <a href="Registration.php">Register here</a></p>
                </div>
            </div>
        </div>
    </div> 
</body>
<footer>
    <p class="foot">Copyright © 2024 Task Managemnt System®. All rights reserved. </p>
</footer>
</html>

<?php
//session_start();
include("database.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
    $id = filter_input(INPUT_POST, "id", FILTER_SANITIZE_NUMBER_INT);
    $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL); // Use FILTER_SANITIZE_EMAIL for email
    $type = $_POST["user_type"];

    if (empty($id) || empty($password) || empty($email)) 
    {
        echo'<div class="notify">Please fill in all the fields!</div>';
    } 
    else 
    {
        // Escape variables to prevent SQL injection
        $id = mysqli_real_escape_string($conn, $id);
        $email = mysqli_real_escape_string($conn, $email);

        // Construct and execute the query
        $sql = "SELECT * FROM $type WHERE id = '$id' AND email = '$email'";
        $result = mysqli_query($conn, $sql);

        if ($result && mysqli_num_rows($result) > 0) 
        {
            $row = mysqli_fetch_assoc($result);

            // Password verification
            if (password_verify($password, $row['password']))
            {
                // Insert login data into the login table
                $insert_sql = "INSERT INTO login (id, type, password, email) 
                               VALUES ('$id', '$type', '$password', '$email')";
                mysqli_query($conn, $insert_sql);

                // Redirect based on user type
                if ($type == "users") 
                {
                    $_SESSION["e_id"] = $id;
                    $_SESSION["e_password"]=$password;
                    $_SESSION["e_email"] = $email;
                    $_SESSION["e_type"] = $type;
                    header("Location: employee.php");
                    exit();
                } 
                else 
                {
                    $_SESSION["a_id"] = $id;
                    $_SESSION["a_password"]=$password;
                    $_SESSION["a_email"] = $email;
                    $_SESSION["a_type"] = $type;
                    header("Location: dashboard.php");
                    exit();
                }
            } 
            else 
            {
                echo'<div class="notify">Password Invalid!</div>';
            }
        } 
        else 
        {
            echo'<div class="notify">User not found! </div>';
        }
    }
} 
else 
{
    echo "";
}

mysqli_close($conn);
?>