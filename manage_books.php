<?php
// ...existing code...
session_start();
require 'config.php';
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}
// Fetch all books
$books = $conn->query("SELECT * FROM books");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Books - Lib Track</title>
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
                        <i class="bi bi-book-half display-5 text-primary"></i>
                        <h2 class="fw-bold text-white mt-2">Lib Track</h2>
                    </div>
                    <ul class="nav flex-column">
                        <li class="nav-item mb-2"><a class="nav-link text-white" href="admin.php"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a></li>
                        <li class="nav-item mb-2"><a class="nav-link text-white" href="add_book.php"><i class="bi bi-plus-square me-2"></i>Add Book</a></li>
                        <li class="nav-item mb-2"><a class="nav-link active text-white bg-primary rounded" href="manage_books.php"><i class="bi bi-journal-bookmark me-2"></i>Manage Books</a></li>
                        <li class="nav-item mb-2"><a class="nav-link text-white" href="manage_members.php"><i class="bi bi-people me-2"></i>Manage Members</a></li>
                        <li class="nav-item mb-2"><a class="nav-link text-white" href="issue_book.php"><i class="bi bi-arrow-right-circle me-2"></i>Issue Book</a></li>
                        <li class="nav-item mb-2"><a class="nav-link text-white" href="return_book.php"><i class="bi bi-arrow-left-circle me-2"></i>Return Book</a></li>
                        <li class="nav-item mt-4"><a class="nav-link text-danger fw-bold" href="logout.php"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
                    </ul>
                </nav>
                <!-- Main Content -->
                <main class="col-md-9 ms-sm-auto col-lg-10 px-md-5 py-5 offset-md-3 offset-lg-2" style="background: linear-gradient(135deg, #232526 0%, #414345 100%); min-height:100vh;">
                    <h1 class="fw-bold text-primary mb-4"><i class="bi bi-journal-bookmark me-2"></i>Manage Books</h1>
                    <div class="card shadow-lg border-0">
                        <div class="card-body p-0">
                            <table class="table table-hover table-striped mb-0 align-middle">
                                <thead class="table-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Title</th>
                                        <th>Author</th>
                                        <th>Location</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while($row = $books->fetch_assoc()) { ?>
                                    <tr>
                                        <td><?php echo $row['id']; ?></td>
                                        <td><?php echo $row['title']; ?></td>
                                        <td><?php echo $row['author']; ?></td>
                                        <td><span class="badge bg-info text-dark fs-6"><?php echo $row['location']; ?></span></td>
                                        <td>
                                            <a href="edit_book.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i> Edit</a>
                                            <a href="delete_book.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this book?');"><i class="bi bi-trash"></i> Delete</a>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </main>
            </div>
        </div>
    <script src="script.js"></script>
</body>
</html>