<!-- index.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
</head>
<body>
    <h1>Welcome to the Home Page</h1>
    <?php
    session_start();
    if (isset($_SESSION["user"])) {
        echo "<p>You are logged in as User ID: " . $_SESSION["user"] . "</p>";
        echo "<a href='logout.php'>Logout</a>";
    } 
    else {
        echo "<p>You are not logged in.</p>";
        echo "<a href='login.php'>Login</a>";
    }
    ?>
</body>
</html>