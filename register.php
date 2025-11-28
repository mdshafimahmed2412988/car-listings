<?php
session_start();


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];


    if ($password !== $confirm_password) {
        $error = "Passwords do not match!";
    } else {

        if ($_POST['captcha'] != $_SESSION['captcha_result']) {
            $error = "Incorrect CAPTCHA answer. Please try again!";
        } else {

            $hashed_password = password_hash($password, PASSWORD_DEFAULT);


            $servername = "localhost";
            $username_db = "2412988"; 
            $password_db = "shafim@wolv2024"; 
            $dbname = "db2412988"; 


            $conn = new mysqli($servername, $username_db, $password_db, $dbname);
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }


            $sql = "SELECT * FROM users WHERE username = ? OR email = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $username, $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $error = "Username or email already taken!";
            } else {

                $sql = "INSERT INTO users (username, email, password, is_admin) VALUES (?, ?, ?, 0)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sss", $username, $email, $hashed_password);
                $stmt->execute();

                $_SESSION['user_id'] = $stmt->insert_id;
                $_SESSION['username'] = $username;
                header("Location: login.php"); 
                exit();
            }

            $stmt->close();
            $conn->close();
        }
    }
}


$first_number = rand(1, 10);
$second_number = rand(1, 10);
$_SESSION['captcha_result'] = $first_number + $second_number;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="assets/css/styles.css"> 
</head>
<body>
    <h1>Register</h1>

    <?php if (isset($error)) { echo "<p style='color: red;'>$error</p>"; } ?>

    <form method="POST" action="register.php">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>

        <label for="confirm_password">Confirm Password:</label>
        <input type="password" id="confirm_password" name="confirm_password" required><br><br>


        <label for="captcha"><?php echo $first_number . " + " . $second_number . " = ?"; ?></label>
        <input type="text" id="captcha" name="captcha" required><br><br>

        <button type="submit">Register</button>
    </form>

    <p>Already have an account? <a href="login.php">Login here</a></p>
</body>
</html>
