<?php
session_start();

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "store_db";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Sanitize user inputs
    $email = $conn->real_escape_string($_POST['email']);
    $password = $conn->real_escape_string($_POST['password']);

    // Hash the password before comparing with the database
    $hashed_password = ($password);

    // Prepare a parameterized query
    $stmt = $conn->prepare("SELECT * FROM admin WHERE Email = ? AND password = ?");
    $stmt->bind_param("ss", $email, $hashed_password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        // Valid credentials, redirect to home page or perform other actions
        $_SESSION['loggedin'] = true;
        header("Location: home.php");
        exit;
    } else {
        $login_err = "Invalid email or password";
    }

    // Close statement
    $stmt->close();

    // Close connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login</title>
    <link rel="stylesheet" href="sigin.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Briem+Hand:wght@100..900&family=Kaushan+Script&family=Lugrasimo&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Rubik:ital,wght@0,300..900;1,300..900&display=swap"
        rel="stylesheet">
</head>
<body>
<h1>Welcome to system</h1>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="form">
        <div class="Group">
            <label for="email">Email:</label><br>
            <input type="email" name="email" required>
        </div>
        <div class="Group">
            <label for="password">Password:</label><br>
            <input type="password" name="password" required>
        </div>
            <button class="btn" type="submit" style="cursor: pointer">login</button>
            
        <?php if(isset($login_err)) { ?>
            <div class="error-msg"><?php echo $login_err; ?></div>
        <?php } ?>
    </form>
</body>
</html>