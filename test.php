<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Warehouse</title>
    <style>
        body {
            background-color: #968f8f;
        }
    </style>
</head>
<body>
    <?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "store_db";
    $conn = new mysqli($servername, $username, $password, $dbname);
    if (isset($_POST['button'])) {
        $email = mysqli_real_escape_string($conn,$_POST['email']);
        $password = mysqli_real_escape_string($conn,$_POST['password']);
        
        // Hash the password before comparing with the database
        $hashed_password = md5($password);

        // Prepare a parameterized query
        $result = mysqli_query($conn,"SELECT * FROM admin WHERE Email ='$email' AND password ='$hashed_password'") or die("select Error");
        $row = mysqli_fetch_assoc($result);
        if (is_array($row) && !empty($row)) {
            header("Location: home.html");
        } else {
            echo "Invalid email or password";
        }
    }
    ?>
    <link rel="stylesheet" href="sty.css">
    <form action="home.html" method="post" class="form">
        <h1>Sign in</h1>
        <div class="Group">
            <label for="email">Email:</label><br>
            <input type="email" name="email"><br>
        </div>
        <div class="Group">
            <label for="password">Password:</label><br>
            <input type="password" name="password"><br><br>
        </div>
        <div class="Group">
            <button class="btn" type="submit" name="button">Sign In</button>
        </div>
    </form>
</body>
</html>
