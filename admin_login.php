
<?php
session_start();
require_once "config.php";

$error = "";

if (
    isset($_SESSION["admin_logged_in"]) &&
    $_SESSION["admin_logged_in"] === true
) {
    header("Location: admin.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"] ?? "");
    $password = $_POST["password"] ?? "";

    // Temporary demo credentials
    $adminUsername = "admin";
    $adminPassword = "Travel@123";

    if (
        $username === $adminUsername &&
        $password === $adminPassword
    ) {
        session_regenerate_id(true);
        $_SESSION["admin_logged_in"] = true;

        header("Location: admin.php");
        exit;
    } else {
        $error = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Admin Login | Travel Explorer</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <style>
        body {
            background: #f3f6fb;
        }

        .login-card {
            width: 100%;
            max-width: 420px;
            border: none;
            border-radius: 18px;
        }

        .login-title {
            font-weight: 700;
        }

        .form-control {
            padding: 12px;
            border-radius: 9px;
        }

        .login-btn {
            padding: 12px;
            border-radius: 9px;
            font-weight: 600;
        }

        .password-toggle {
            margin-top: 10px;
            font-size: 14px;
            cursor: pointer;
        }
    </style>
</head>

<body>

<div class="container d-flex justify-content-center align-items-center"
     style="min-height: 100vh;">

    <div class="card login-card shadow p-4 p-md-5">

        <h2 class="text-center login-title mb-2">
            ✈️ Travel Explorer
        </h2>

        <p class="text-center text-muted mb-4">
            Admin Login
        </p>

        <?php if ($error !== ""): ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">

            <div class="mb-3">
                <label for="username" class="form-label">
                    Username
                </label>

                <input
                    type="text"
                    id="username"
                    name="username"
                    class="form-control"
                    placeholder="Enter username"
                    autocomplete="username"
                    required
                >
            </div>

            <div class="mb-1">
                <label for="password" class="form-label">
                    Password
                </label>

                <input
                    type="password"
                    id="password"
                    name="password"
                    class="form-control"
                    placeholder="Enter password"
                    autocomplete="current-password"
                    required
                >
            </div>

            <!-- Show / Hide Password -->
            <div class="form-check password-toggle mb-4">
                <input
                    type="checkbox"
                    id="showPassword"
                    class="form-check-input"
                >

                <label
                    for="showPassword"
                    class="form-check-label"
                >
                    Show password
                </label>
            </div>

            <button
                type="submit"
                class="btn btn-primary w-100 login-btn"
            >
                Login
            </button>

        </form>

        <div class="text-center mt-4">
            <a href="index.php" class="text-decoration-none">
                ← Back to Website
            </a>
        </div>

    </div>
</div>

<script>
    const passwordInput = document.getElementById("password");
    const showPassword = document.getElementById("showPassword");

    showPassword.addEventListener("change", function () {
        passwordInput.type = this.checked ? "text" : "password";
    });
</script>

</body>
</html>
