<?php
// ...existing code...
// Admin dashboard with sidebar, cards, statistics
session_start();
require 'config.php';
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}
// Fetch statistics
$total_books = $conn->query("SELECT COUNT(*) FROM books")->fetch_row()[0];
$issued_books = $conn->query("SELECT COUNT(*) FROM issued_books WHERE return_date IS NULL")->fetch_row()[0];
$total_members = $conn->query("SELECT COUNT(*) FROM users WHERE role='member'")->fetch_row()[0];
$total_fines = $conn->query("SELECT SUM(amount) FROM fines")->fetch_row()[0] ?? 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Bootstrap 5 CDN -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
            main, .card, .alert, .dropdown-menu, .table, .table th, .table td {
                color: #111 !important;
            }
            .sidebar {
                background: #fff !important;
                box-shadow: 2px 0 16px #e0e7ef88 !important;
            }
            .sidebar .text-primary, .fw-bold.text-primary, .btn-primary, .bg-primary, .nav-link.active.bg-primary {
                background: #f7b42c !important;
                color: #fff !important;
                border: none !important;
            }
            .btn-primary:hover, .nav-link.active.bg-primary:hover {
                background: #ffd200 !important;
                color: #fff !important;
            }
            .card.text-bg-primary {
                background: linear-gradient(120deg, #f7b42c 60%, #ffd200 100%) !important;
                color: #fff !important;
            }
            .card.text-bg-success {
                background: linear-gradient(120deg, #a8edea 60%, #fed6e3 100%) !important;
                color: #333 !important;
            }
            .card.text-bg-info {
                background: linear-gradient(120deg, #fceabb 60%, #f8b500 100%) !important;
                color: #333 !important;
            }
            .card.text-bg-danger {
                background: linear-gradient(120deg, #f8b500 60%, #fceabb 100%) !important;
                color: #333 !important;
            }
            .table-dark {
                background: #f7b42c !important;
                color: #fff !important;
            }
        </style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Lib Track</title>
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
                        <i class="bi bi-book-half display-5" style="color:#007bff;"></i>
                        <h2 class="fw-bold mt-2" style="color:#fff;">Lib Track</h2>
                    </div>
                    <ul class="nav flex-column">
                        <li class="nav-item mb-2"><a class="nav-link active text-white bg-primary rounded" href="admin.php"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a></li>
                        <li class="nav-item mb-2"><a class="nav-link text-white" href="add_book.php"><i class="bi bi-plus-square me-2"></i>Add Book</a></li>
                        <li class="nav-item mb-2"><a class="nav-link text-white" href="manage_books.php"><i class="bi bi-journal-bookmark me-2"></i>Manage Books</a></li>
                        <li class="nav-item mb-2"><a class="nav-link text-white" href="manage_members.php"><i class="bi bi-people me-2"></i>Manage Members</a></li>
                        <li class="nav-item mb-2"><a class="nav-link text-white" href="issue_book.php"><i class="bi bi-arrow-right-circle me-2"></i>Issue Book</a></li>
                        <li class="nav-item mb-2"><a class="nav-link text-white" href="return_book.php"><i class="bi bi-arrow-left-circle me-2"></i>Return Book</a></li>
                        <li class="nav-item mt-4"><a class="nav-link text-danger fw-bold" href="logout.php"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
                    </ul>
                </nav>
                <!-- Main Content -->
                <main class="col-md-9 ms-sm-auto col-lg-10 px-md-5 py-5 offset-md-3 offset-lg-2" style="background: linear-gradient(135deg, #232526 0%, #414345 100%); min-height:100vh;">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h1 class="fw-bold text-primary mb-0"><i class="bi bi-speedometer2 me-2"></i>Admin Dashboard</h1>
                        <div class="dropdown">
                            <button class="btn btn-outline-light dropdown-toggle fw-bold" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-person-circle me-1"></i> <?php echo isset($_SESSION['username']) ? $_SESSION['username'] : 'Admin'; ?>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item text-danger fw-bold" href="logout.php"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="row g-4 mb-5">
                        <div class="col-md-3">
                            <div class="card text-bg-primary shadow h-100">
                                <div class="card-body text-center">
                                    <i class="bi bi-journal-bookmark display-5 mb-2"></i>
                                    <h5 class="card-title">Total Books</h5>
                                    <p class="card-text fs-2 fw-bold"><?php echo $total_books; ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-bg-success shadow h-100">
                                <div class="card-body text-center">
                                    <i class="bi bi-arrow-right-circle display-5 mb-2"></i>
                                    <h5 class="card-title">Issued Books</h5>
                                    <p class="card-text fs-2 fw-bold"><?php echo $issued_books; ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-bg-info shadow h-100">
                                <div class="card-body text-center">
                                    <i class="bi bi-people display-5 mb-2"></i>
                                    <h5 class="card-title">Members</h5>
                                    <p class="card-text fs-2 fw-bold"><?php echo $total_members; ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-bg-danger shadow h-100">
                                <div class="card-body text-center">
                                    <i class="bi bi-cash-coin display-5 mb-2"></i>
                                    <h5 class="card-title">Total Fines</h5>
                                    <p class="card-text fs-2 fw-bold">₹<?php echo $total_fines; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="alert alert-info mt-4 shadow-sm">Welcome to the admin dashboard! Use the sidebar to manage books, members, and transactions.</div>
                </main>
                <!-- Bootstrap 5 JS for dropdowns -->
                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
            </div>
        </div>
    <script src="script.js"></script>
</body>
</html>