
<?php
session_start();
include "db.php";

$message = "";
$messageType = "";
$activeForm = "login";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $action = $_POST["action"] ?? "login";

    if ($action === "register") {
        $activeForm = "register";

        $first_name = trim($_POST["first_name"] ?? "");
        $last_name = trim($_POST["last_name"] ?? "");
        $email = trim($_POST["email"] ?? "");
        $username = trim($_POST["username"] ?? "");
        $phone = trim($_POST["phone"] ?? "");
        $password = $_POST["password"] ?? "";
        $confirm_password = $_POST["confirm_password"] ?? "";

        if ($password !== $confirm_password) {
            $message = "Passwords do not match.";
            $messageType = "error";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO users
                (first_name, last_name, email, username, phone, password)
                VALUES (?, ?, ?, ?, ?, ?)";

            $stmt = $conn->prepare($sql);

            if ($stmt) {
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
                    $message = "Registration successful! You can now log in.";
                    $messageType = "success";
                    $activeForm = "login";
                } else {
                    $message = "Registration failed. The email or username may already exist.";
                    $messageType = "error";
                }

                $stmt->close();
            } else {
                $message = "Unable to process registration right now.";
                $messageType = "error";
            }
        }
    } else {
        $email = trim($_POST["email"] ?? "");
        $password = $_POST["password"] ?? "";

        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result && $result->num_rows > 0) {
                $users = $result->fetch_assoc();

                if (password_verify($password, $users["password"])) {
                    session_regenerate_id(true);
                    $_SESSION["first_name"] = $users["first_name"];
                    $_SESSION["email"] = $users["email"];

                    $stmt->close();
                    header("Location: securejs.php");
                    exit();
                }
            }

            $message = "Invalid email or password.";
            $messageType = "error";
            $stmt->close();
        } else {
            $message = "Unable to process login right now.";
            $messageType = "error";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secured Environment | XSS Training Lab</title>
    <link rel="stylesheet" href="index.css">
</head>
<body>

<main class="auth-container">
    <div class="brand">
        <div class="brand-icon">S</div>
        <p class="eyebrow">SECURED ENVIRONMENT</p>
        <h1>Welcome back</h1>
        <p class="description" id="formDescription">
            Sign in to continue to your XSS Training Lab.
        </p>
    </div>

    <div class="auth-tabs">
        <button type="button" class="auth-tab"
            id="loginTab"
            onclick="showForm('login')">
            Login
        </button>

        <button type="button" class="auth-tab"
            id="registerTab"
            onclick="showForm('register')">
            Register
        </button>
    </div>

    <?php if ($message !== ""): ?>
        <div class="message <?php echo htmlspecialchars($messageType); ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <form method="POST" id="loginForm" <?php echo $activeForm === "register" ? "hidden" : ""; ?>>
        <input type="hidden" name="action" value="login">

        <div class="form-group">
            <label for="loginEmail">Email</label>
            <input type="email" id="loginEmail" name="email"
                placeholder="Enter your email" autocomplete="email" required>
        </div>

        <div class="form-group">
            <label for="loginPassword">Password</label>
            <input type="password" id="loginPassword" name="password"
                placeholder="Enter your password"
                autocomplete="current-password" required>
        </div>

        <button type="submit" class="submit-button">Sign in →</button>
    </form>

    <form method="POST" id="registerForm" <?php echo $activeForm === "login" ? "hidden" : ""; ?>>
        <input type="hidden" name="action" value="register">

        <div class="form-row">
            <div class="form-group">
                <label for="firstName">First Name</label>
                <input type="text" id="firstName" name="first_name"
                    placeholder="First name" autocomplete="given-name" required>
            </div>

            <div class="form-group">
                <label for="lastName">Last Name</label>
                <input type="text" id="lastName" name="last_name"
                    placeholder="Last name" autocomplete="family-name" required>
            </div>
        </div>

        <div class="form-group">
            <label for="registerEmail">Email</label>
            <input type="email" id="registerEmail" name="email"
                placeholder="Enter your email" autocomplete="email" required>
        </div>

        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="username"
                placeholder="Choose a username" autocomplete="username" required>
        </div>

        <div class="form-group">
            <label for="phone">Phone Number</label>
            <input type="tel" id="phone" name="phone"
                placeholder="Enter your phone number" autocomplete="tel" required>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="registerPassword">Password</label>
                <input type="password" id="registerPassword" name="password"
                    placeholder="Create password"
                    autocomplete="new-password" required>
            </div>

            <div class="form-group">
                <label for="confirmPassword">Confirm Password</label>
                <input type="password" id="confirmPassword" name="confirm_password"
                    placeholder="Confirm password"
                    autocomplete="new-password" required>
            </div>
        </div>

        <button type="submit" class="submit-button">Create account →</button>
    </form>

    <footer class="page-footer">
        Educational environment for security practice.
    </footer>
</main>

<script>
function showForm(formName) {
    const loginForm = document.getElementById("loginForm");
    const registerForm = document.getElementById("registerForm");
    const loginTab = document.getElementById("loginTab");
    const registerTab = document.getElementById("registerTab");
    const description = document.getElementById("formDescription");

    const isLogin = formName === "login";

    loginForm.hidden = !isLogin;
    registerForm.hidden = isLogin;

    loginTab.classList.toggle("active", isLogin);
    registerTab.classList.toggle("active", !isLogin);

    description.textContent = isLogin
        ? "Sign in to continue to your XSS Training Lab."
        : "Create your secured account to get started.";
}

showForm(<?php echo json_encode($activeForm); ?>);
</script>

</body>
</html>
