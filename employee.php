<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Terminal</title>
    <link rel="stylesheet" href="CSS\e_styles.css">
</head>
    <h1>Welcome to the Home Page</h1>
    <?php
    session_start();
    if (isset($_SESSION["e_id"]))
    {
        echo "<p>You are logged in as User ID: " . $_SESSION["e_id"] . "</p>";
        echo "<p>User logged in</p>";
        echo "<a href='logout.php'>Logout</a>";
    } 
    else {
        echo "<p>You are not logged in.</p>";
        echo "<p> User not logged in </p>";
        echo "<a href='login.php'>Login</a>";
    }
    ?>
<body>
    <header>
        <div class="container">
        <h1 class="logo"></h1>
        <nav>
            <ul>
            <li><a href="#">Home</a></li>
            <li><a href="#">About</a></li>
            <li><a href="#">Pricing</a></li>
            <li><a href="#">Contact</a></li>
            </ul>
        </nav>
        </div>
  </header>
</body>
</html>
<?php echo " ". $_SESSION["e_id"] ?>