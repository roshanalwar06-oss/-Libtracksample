<?php
// ...existing code...
// Member dashboard with sidebar, search, book location
session_start();
require 'config.php';
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'member') {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Dashboard - Lib Track</title>
    <link rel="stylesheet" href="style.css">
    <!-- Bootstrap 5 & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body>
        <div class="container-fluid">
            <div class="row min-vh-100">
                <!-- Sidebar -->
                <nav class="col-md-3 col-lg-2 d-md-block bg-dark sidebar py-4 shadow-lg position-fixed h-100" style="z-index:1000;">
                    <div class="text-center mb-4">
                        <i class="bi bi-book display-5 text-primary"></i>
                        <h2 class="fw-bold text-white mt-2">Lib Track</h2>
                    </div>
                    <ul class="nav flex-column">
                        <li class="nav-item mb-2"><a class="nav-link active text-white bg-primary rounded" href="member.php"><i class="bi bi-house-door me-2"></i>Dashboard</a></li>
                        <li class="nav-item mb-2"><a class="nav-link text-white" href="search.php"><i class="bi bi-search me-2"></i>Search Books</a></li>
                        <li class="nav-item mt-4"><a class="nav-link text-danger fw-bold" href="logout.php"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
                    </ul>
                </nav>
                <!-- Main Content -->
                <main class="col-md-9 ms-sm-auto col-lg-10 px-md-5 py-5 offset-md-3 offset-lg-2" style="background: linear-gradient(135deg, #232526 0%, #414345 100%); min-height:100vh;">
                    <h1 class="fw-bold text-primary mb-4"><i class="bi bi-house-door me-2"></i>Member Dashboard</h1>
                    <div class="alert alert-info shadow-sm">Welcome, <b><?php echo $_SESSION['username']; ?></b>! Use the sidebar to search for books and view their locations.</div>
                    <div class="card mt-5 p-4 shadow-lg border-0 bg-gradient" style="background: linear-gradient(120deg, #007bff 60%, #00c6ff 100%);">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <i class="bi bi-search display-4 text-white"></i>
                            </div>
                            <div class="flex-grow-1 ms-4">
                                <h4 class="text-white mb-2">Quick Book Search</h4>
                                <form method="GET" action="search.php" class="row g-2">
                                    <div class="col-8">
                                        <input type="text" name="query" class="form-control form-control-lg" placeholder="Search books by title, author, or location" required>
                                    </div>
                                    <div class="col-4">
                                        <button type="submit" class="btn btn-light btn-lg w-100 fw-bold"><i class="bi bi-search"></i> Search</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
    <script src="script.js"></script>
</body>
</html>