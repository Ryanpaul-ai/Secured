<?php

include "db.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $first_name = $_POST["first_name"];
    $last_name = $_POST["last_name"];
    $email = $_POST["email"];
    $username = $_POST["username"];
    $phone = $_POST["phone"];
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    if ($password != $confirm_password) {

        $message = "Passwords do not match.";

    } else {

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users
                (first_name, last_name, email, username, phone, password)
                VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);

        $stmt->bind_param(
            "ssssss",
            $first_name,
            $last_name,
            $email,
            $username,
            $phone,
            $hashed_password
        );

        if ($stmt->execute()) {

            $message = "Registration successful!";

        } else {

            $message = "Registration failed: " . $stmt->error;

        }

        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Secured Registration</title>
    <link rel="stylesheet" href="register.css">
</head>

<body>
<div class="registration-container">

<h2>Registration</h2>

<?php if ($message != ""): ?>

    <p><?php echo htmlspecialchars($message); ?></p>

<?php endif; ?>

<form method="POST">

    <label>First Name</label><br>
    <input type="text" name="first_name" required>

    <br><br>

    <label>Last Name</label><br>
    <input type="text" name="last_name" required>

    <br><br>

    <label>Email</label><br>
    <input type="email" name="email" required>

    <br><br>

    <label>Username</label><br>
    <input type="text" name="username" required>

    <br><br>

    <label>Phone Number</label><br>
    <input type="text" name="phone" required>

    <br><br>

    <label>Password</label><br>
    <input type="password" name="password" required>

    <br><br>

    <label>Confirm Password</label><br>
    <input type="password" name="confirm_password" required>

    <br><br>

    <button type="submit">Register</button>

</form>

<br>
<div class="login-link">
    Already have an account?
    <a href="index.php">Login</a>
</div>

</div>
</body>

</html>