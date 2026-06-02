<?php
// ...existing code...
session_start();
require 'config.php';
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $book_id = $_POST['book_id'];
    $member_id = $_POST['member_id'];
    $issue_date = date('Y-m-d');
    $stmt = $conn->prepare("INSERT INTO issued_books (book_id, member_id, issue_date) VALUES (?, ?, ?)");
    $stmt->bind_param('iis', $book_id, $member_id, $issue_date);
    if ($stmt->execute()) {
        $msg = 'Book issued successfully!';
    } else {
        $msg = 'Error issuing book.';
    }
}
// Fetch books and members
$books = $conn->query("SELECT id, title FROM books");
$members = $conn->query("SELECT id, username FROM users WHERE role='member'");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Issue Book - Lib Track</title>
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
                        <li class="nav-item mb-2"><a class="nav-link text-white" href="manage_books.php"><i class="bi bi-journal-bookmark me-2"></i>Manage Books</a></li>
                        <li class="nav-item mb-2"><a class="nav-link text-white" href="manage_members.php"><i class="bi bi-people me-2"></i>Manage Members</a></li>
                        <li class="nav-item mb-2"><a class="nav-link active text-white bg-primary rounded" href="issue_book.php"><i class="bi bi-arrow-right-circle me-2"></i>Issue Book</a></li>
                        <li class="nav-item mb-2"><a class="nav-link text-white" href="return_book.php"><i class="bi bi-arrow-left-circle me-2"></i>Return Book</a></li>
                        <li class="nav-item mt-4"><a class="nav-link text-danger fw-bold" href="logout.php"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
                    </ul>
                </nav>
                <!-- Main Content -->
                <main class="col-md-9 ms-sm-auto col-lg-10 px-md-5 py-5 offset-md-3 offset-lg-2" style="background: linear-gradient(135deg, #232526 0%, #414345 100%); min-height:100vh;">
                    <h1 class="fw-bold text-primary mb-4"><i class="bi bi-arrow-right-circle me-2"></i>Issue Book</h1>
                    <div class="card shadow-lg border-0 p-4 bg-gradient" style="background: linear-gradient(120deg, #00c6ff 60%, #007bff 100%);">
                        <form method="POST" action="issue_book.php" class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label text-white">Select Book</label>
                                <select name="book_id" class="form-select form-select-lg" required>
                                    <option value="">Select Book</option>
                                    <?php while($row = $books->fetch_assoc()) { ?>
                                    <option value="<?php echo $row['id']; ?>"><?php echo $row['title']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-white">Select Member</label>
                                <select name="member_id" class="form-select form-select-lg" required>
                                    <option value="">Select Member</option>
                                    <?php while($row = $members->fetch_assoc()) { ?>
                                    <option value="<?php echo $row['id']; ?>"><?php echo $row['username']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-12 mt-3">
                                <button type="submit" class="btn btn-light btn-lg w-100 fw-bold"><i class="bi bi-arrow-right-circle"></i> Issue Book</button>
                            </div>
                        </form>
                        <?php if(isset($msg)) echo '<div class="alert alert-info mt-3">'.$msg.'</div>'; ?>
                    </div>
                </main>
            </div>
        </div>
    <script src="script.js"></script>
</body>
</html>