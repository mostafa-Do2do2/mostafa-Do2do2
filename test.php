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
<html>
<head>
    <title>Warehouse</title>
    <style>
        body {
            background-color: #968f8f;
            font-family: Arial, sans-serif;
        }
        .form {
            max-width: 300px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            margin-top: 50px;
        }
        .form h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        .form .Group {
            margin-bottom: 15px;
        }
        .form .Group label {
            display: block;
            margin-bottom: 5px;
        }
        .form .Group input[type="email"],
        .form .Group input[type="password"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 3px;
            box-sizing: border-box;
        }
        .form .Group .btn {
            background-color: #4CAF50;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 3px;
            cursor: pointer;
        }
        .form .Group .btn:hover {
            background-color: #45a049;
        }
        .error-msg {
            color: red;
            margin-top: 10px;
            text-align: center;
        }
    </style>
</head>
<body>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="form">
        <h1>Sign in</h1>
        <div class="Group">
            <label for="email">Email:</label>
            <input type="email" name="email" required>
        </div>
        <div class="Group">
            <label for="password">Password:</label>
            <input type="password" name="password" required>
        </div>
        <div class="Group">
            <button class="btn" type="submit">Sign In</button>
        </div>
        <?php if(isset($login_err)) { ?>
            <div class="error-msg"><?php echo $login_err; ?></div>
        <?php } ?>
    </form>
</body>
</html>
