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
<div class="contact-form-main">
        <div class="container">
            <div class="main">
                <div class="content">
                    <p> Project Task Managemnt System® </p>
                    <p> This Project was created as an assignment for
                    <P>"International University of Business Agriculture and Technology"</P>
                    <P> under the supervision of </p> <br>
                    <h2> Snaholata Mondal </h2>
                    <p> Lecturer, Dept. of Computer Science and Engineering </p>
                    <p> IUBAT </P>
                </div>
            </div>
        </div>
    </div> 
<div class="about-us">
    <div class="contact-form-us">
        <div class="container">
            <div class="main">
                <div class="content">
                    <h2> Samiul Karim Mazumder </h2>
                    <p> Junior Developer, Dept. of Computer Science and Engineering </p>
                    <p> IUBAT </P>
                    <p class="inform">Find me here: <a href="https://www.linkedin.com/in/samiulkarim191139/" target="_blank">linkedin</a></p>
                </div>
            </div>
        </div>
    </div>
    <div class="contact-form-us">
        <div class="container">
            <div class="main">
                <div class="content">
                    <h2> Abdullah Al Alif </h2>
                    <p> Junior Developer, Dept. of Computer Science and Engineering </p>
                    <p> IUBAT </P>
                    <p class="inform">Find me here: <a href="https://www.linkedin.com/in/abdullah-al-alif-50a0aa251/" target="_blank">linkedin</a></p>
                </div>
            </div>
        </div>
    </div>
    <div class="contact-form-us">
        <div class="container">
            <div class="main">
                <div class="content">
                    <h2> Sabiha Jannat Mahin </h2>
                    <p> Front End Developer, Dept. of Computer Science and Engineering </p>
                    <p> IUBAT </P>
                    <p class="inform">Find me here: <a href="https://www.linkedin.com/in/sabiha-jannat-874392284/" target="_blank">linkedin</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
<footer>
    <p class="foot">Copyright © 2024 Task Managemnt System®. All rights reserved. </p>
</footer>
</html>