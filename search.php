<?php
// ...existing code...
session_start();
require 'config.php';
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'member') {
    header('Location: login.php');
    exit();
}
$query = isset($_GET['query']) ? trim($_GET['query']) : '';
$results = [];
if ($query) {
    $stmt = $conn->prepare("SELECT * FROM books WHERE title LIKE ? OR author LIKE ? OR location LIKE ?");
    $search = "%$query%";
    $stmt->bind_param('sss', $search, $search, $search);
    $stmt->execute();
    $results = $stmt->get_result();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Books - Lib Track</title>
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
                        <li class="nav-item mb-2"><a class="nav-link text-white" href="member.php"><i class="bi bi-house-door me-2"></i>Dashboard</a></li>
                        <li class="nav-item mb-2"><a class="nav-link active text-white bg-primary rounded" href="search.php"><i class="bi bi-search me-2"></i>Search Books</a></li>
                        <li class="nav-item mt-4"><a class="nav-link text-danger fw-bold" href="logout.php"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
                    </ul>
                </nav>
                <!-- Main Content -->
                <main class="col-md-9 ms-sm-auto col-lg-10 px-md-5 py-5 offset-md-3 offset-lg-2" style="background: linear-gradient(135deg, #232526 0%, #414345 100%); min-height:100vh;">
                    <h1 class="fw-bold text-primary mb-4"><i class="bi bi-search me-2"></i>Search Books</h1>
                    <form method="GET" action="search.php" class="row g-3 mb-4">
                        <div class="col-md-8">
                            <input type="text" name="query" class="form-control form-control-lg" placeholder="Search books by title, author, or location" value="<?php echo htmlspecialchars($query); ?>" required>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary btn-lg w-100 fw-bold"><i class="bi bi-search"></i> Search</button>
                        </div>
                    </form>
                    <?php if($query && $results && $results->num_rows > 0) { ?>
                    <div class="card shadow-lg border-0 mt-4">
                        <div class="card-body p-0">
                            <table class="table table-hover table-striped mb-0 align-middle">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Title</th>
                                        <th>Author</th>
                                        <th>Location</th>
                                        <th>Availability</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while($row = $results->fetch_assoc()) { ?>
                                    <tr>
                                        <td><?php echo $row['title']; ?></td>
                                        <td><?php echo $row['author']; ?></td>
                                        <td><span class="badge bg-info text-dark fs-6"><?php echo $row['location']; ?></span></td>
                                        <td>
                                            <?php
                                                $book_id = $row['id'];
                                                $issued = $conn->query("SELECT * FROM issued_books WHERE book_id=$book_id AND return_date IS NULL")->num_rows;
                                                if ($issued) {
                                                    echo '<span class="badge bg-danger">Issued</span>';
                                                } else {
                                                    echo '<span class="badge bg-success">Available</span>';
                                                }
                                            ?>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php } else if($query) { echo '<div class="alert alert-warning mt-4">No books found.</div>'; } ?>
                </main>
            </div>
        </div>
    <script src="script.js"></script>
</body>
</html>