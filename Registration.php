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
    <title>Registration</title>
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
                    <h2>Register</h2>
                    <form action="#" method="post">
                        <input type="number" name="id" placeholder="ID">
                        <ion-icon name="person-outline"></ion-icon>
                        <input type="text" name="fullname" placeholder="Username">
                        <ion-icon name="mail-outline"></ion-icon>
                        <input type="email" name="email" placeholder="Email">
                        <ion-icon name="lock-closed-outline"></ion-icon>
                        <input type="password" name="password" placeholder="Password">
                        <ion-icon name="lock-closed-outline"></ion-icon>
                        <input type="password" name="confirm_password" placeholder="Confirm Password">
                        <div class="dropdown">
                            <select name="user_type" id="user_type">
                                <option value="users">User</option>
                                <option value="admins">Admin</option>
                            </select>
                        </div>
                        <!-- <div class="notify" > </div> -->
                        <button type="submit" name="submit" class="btn">Register</button>
                    </form>
                    <p class="inform">Already Registered? <a href="login.php">Login</a></p>
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
if (isset($_POST["submit"])) 
{
    $id = $_POST["id"];
    $fullName = $_POST["fullname"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $passwordRepeat = $_POST["confirm_password"];
    $userType = $_POST["user_type"];
    
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    if (empty($id)) 
    {
        echo "<p class='notify'>ID is required</p>";
    }
    elseif (empty($fullName) || empty($email) || empty($password) || empty($passwordRepeat)) 
    {
        echo'<div class="notify">All fields are required</div>';
    }
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL))  //filter function - Syntax [filter_var($email, FILTER_VALIDATE_EMAIL) {return massage if true}]
    {        
        echo'<div class="notify">Email is not valid</div>';
    }
    elseif (strlen($password) < 4) //strlen - string length function
    {
        echo'<div class="notify">Password must be at least 4 characters long</div>';
    }
    elseif ($password !== $passwordRepeat) 
    {
        echo'<div class="notify">Password does not match</div>';
    }
    else 
    {
        require_once "database.php";
        
        // Check if email already exists
        $sql = "SELECT * FROM $userType WHERE email = ?";
        $stmt = mysqli_stmt_init($conn);
        if (mysqli_stmt_prepare($stmt, $sql)) 
        {
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $rowCount = mysqli_num_rows($result);
            if ($rowCount > 0) 
            {   
                echo '<div class="notify">Email already exists!</div>';
            }
            else
            {
                // Check if ID already exists
                $sql = "SELECT * FROM $userType WHERE id = ?";
                $stmt = mysqli_stmt_init($conn);
                if (mysqli_stmt_prepare($stmt, $sql)) 
                {
                    mysqli_stmt_bind_param($stmt, "i", $id); // Assuming id is an integer
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);
                    if (mysqli_num_rows($result) > 0) 
                    {
                        echo '<div class="notify">ID already registered</div>';
                    } 
                    else 
                    {
                        // Insert new record
                        $sql = "INSERT INTO $userType (id, name, email, password) VALUES (?, ?, ?, ?)";
                        $stmt = mysqli_stmt_init($conn);
                        if (mysqli_stmt_prepare($stmt, $sql)) 
                        {
                            mysqli_stmt_bind_param($stmt, "isss", $id, $fullName, $email, $passwordHash);
                            mysqli_stmt_execute($stmt);
                            echo '<div class="notify">You are registered</div>';
                        } 
                        else 
                        {
                            echo '<div class="notify">Something went wrong</div>';
                        }
                    }
                }
                else
                {
                    echo '<div class="notify">Something went wrong</div>';
                }
            }
        } 
        else 
        {
            die('<div class="notify">Something went wrong</div>');
        }
        
    }
}
?>