<?php
session_start();
require 'config.php';
// ...existing code...
if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] == 'admin') {
        header('Location: admin.php');
        exit();
    } else if ($_SESSION['role'] == 'member') {
        header('Location: member.php');
        exit();
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE username = ?");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        if ($password === $row['password']) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['role'] = $row['role'];
            if ($row['role'] == 'admin') {
                header('Location: admin.php');
            } else {
                header('Location: member.php');
            }
            exit();
        } else {
            $error = 'Invalid password.';
        }
    } else {
        $error = 'User not found.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Lib Track</title>
    <link rel="stylesheet" href="style.css">
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body>
        <section class="vh-100 d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, #0f2027 0%, #2c5364 100%);">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-6 col-lg-5">
                        <div class="card shadow-lg p-4 border-0" style="background: rgba(255,255,255,0.07);backdrop-filter: blur(6px);">
                            <div class="text-center mb-4">
                                <i class="bi bi-book-half display-3 text-primary"></i>
                                <h2 class="fw-bold mt-2 mb-0 text-primary">Lib Track Login</h2>
                            </div>
                            <form method="POST" action="login.php" id="loginForm">
                                <div class="mb-3">
                                    <label class="form-label text-light">Username</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-primary text-white"><i class="bi bi-person"></i></span>
                                        <input type="text" name="username" class="form-control" placeholder="Enter username" required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-light">Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-primary text-white"><i class="bi bi-lock"></i></span>
                                        <input type="password" name="password" class="form-control" placeholder="Enter password" required>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary w-100 py-2 fw-bold">Login</button>
                            </form>
                            <div id="alert" class="mt-3 text-danger text-center fw-semibold">
                                <?php if(isset($error)) echo $error; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    <script src="script.js"></script>
</body>
</html>