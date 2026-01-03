<?php
session_start(); // Must be the very first line
include 'db.php';

// Handle Register
if (isset($_POST['register'])) {
    $user = mysqli_real_escape_string($conn, $_POST['username']);
    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
    $check = mysqli_query($conn, "SELECT username FROM users WHERE username='$user'");
    if (mysqli_num_rows($check) > 0) {
        echo "<script>alert('Username already taken!');</script>";
    } else {
        mysqli_query($conn, "INSERT INTO users (username, password) VALUES ('$user', '$pass')");
        echo "<script>alert('Registration Successful! Please Login.');</script>";
    }
}

// Handle Login
if (isset($_POST['login'])) {
    $user = mysqli_real_escape_string($conn, $_POST['username']);
    $pass = $_POST['password'];
    
    $result = mysqli_query($conn, "SELECT * FROM users WHERE username='$user'");
    $row = mysqli_fetch_assoc($result);
    
    if ($row && password_verify($pass, $row['password'])) {
        $_SESSION['user'] = $user;
        header("Location: dashboard.php");
        exit(); // Crucial to stop script execution
    } else {
        $error = "Invalid username or password";
    }
}

// HANDLE LOGOUT
if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['user']);
    header("Location: index.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login / Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f0f2f5; }
        .main-card { border: none; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); overflow: hidden; }
        .login-side { background: #fff; padding: 40px; }
        .register-side { background: #0d6efd; color: white; padding: 40px; }
        .register-side input { background: rgba(255,255,255,0.2); border: none; color: white; }
        .register-side input::placeholder { color: rgba(255,255,255,0.7); }
        .register-side button { background: white; color: #0d6efd; font-weight: bold; }
    </style>
</head>
<body class="d-flex align-items-center min-vh-100">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <div class="card main-card">
                    <div class="row g-0">
                        
                        <div class="col-md-6 login-side">
                            <h3 class="mb-4 fw-bold text-primary">Welcome Back</h3>
                            <?php if(isset($error)): ?>
                                <div class="alert alert-danger py-2"><?php echo $error; ?></div>
                            <?php endif; ?>
                            
                            <form method="POST">
                                <div class="mb-3">
                                    <label class="form-label text-muted">Username</label>
                                    <input type="text" name="username" class="form-control form-control-lg" required>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label text-muted">Password</label>
                                    <input type="password" name="password" class="form-control form-control-lg" required>
                                </div>
                                <button type="submit" name="login" class="btn btn-primary btn-lg w-100">Login</button>
                            </form>
                        </div>

                        <div class="col-md-6 register-side d-flex flex-column justify-content-center">
                            <h3 class="mb-3">New Here?</h3>
                            <p class="mb-4 opacity-75">Create an account to manage your inventory and access all features.</p>
                            
                            <form method="POST">
                                <div class="mb-3">
                                    <input type="text" name="username" class="form-control form-control-lg" placeholder="Choose Username" required>
                                </div>
                                <div class="mb-4">
                                    <input type="password" name="password" class="form-control form-control-lg" placeholder="Choose Password" required>
                                </div>
                                <button type="submit" name="register" class="btn btn-light btn-lg w-100">Create Account</button>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>