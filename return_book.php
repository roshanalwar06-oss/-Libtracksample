<?php
// ...existing code...
session_start();
require 'config.php';
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $issue_id = $_POST['issue_id'];
    $return_date = date('Y-m-d');
    // Calculate fine
    $stmt = $conn->prepare("SELECT issue_date FROM issued_books WHERE id = ?");
    $stmt->bind_param('i', $issue_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $issue_date = $row['issue_date'];
    $days = (strtotime($return_date) - strtotime($issue_date)) / (60*60*24);
    $fine = $days > 14 ? ($days - 14) * 5 : 0; // ₹5 per day after 14 days
    $conn->query("UPDATE issued_books SET return_date='$return_date' WHERE id=$issue_id");
    if ($fine > 0) {
        $conn->query("INSERT INTO fines (issue_id, amount) VALUES ($issue_id, $fine)");
    }
    $msg = 'Book returned! Fine: ₹' . $fine;
}
// Fetch issued books
$issued = $conn->query("SELECT ib.id, b.title, u.username, ib.issue_date FROM issued_books ib JOIN books b ON ib.book_id=b.id JOIN users u ON ib.member_id=u.id WHERE ib.return_date IS NULL");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Return Book - Lib Track</title>
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
                        <li class="nav-item mb-2"><a class="nav-link text-white" href="issue_book.php"><i class="bi bi-arrow-right-circle me-2"></i>Issue Book</a></li>
                        <li class="nav-item mb-2"><a class="nav-link active text-white bg-primary rounded" href="return_book.php"><i class="bi bi-arrow-left-circle me-2"></i>Return Book</a></li>
                        <li class="nav-item mt-4"><a class="nav-link text-danger fw-bold" href="logout.php"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
                    </ul>
                </nav>
                <!-- Main Content -->
                <main class="col-md-9 ms-sm-auto col-lg-10 px-md-5 py-5 offset-md-3 offset-lg-2" style="background: linear-gradient(135deg, #232526 0%, #414345 100%); min-height:100vh;">
                    <h1 class="fw-bold text-primary mb-4"><i class="bi bi-arrow-left-circle me-2"></i>Return Book</h1>
                    <div class="card shadow-lg border-0 p-4 bg-gradient" style="background: linear-gradient(120deg, #00c6ff 60%, #007bff 100%);">
                        <form method="POST" action="return_book.php" class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label text-white">Select Issued Book</label>
                                <select name="issue_id" class="form-select form-select-lg" required>
                                    <option value="">Select Issued Book</option>
                                    <?php while($row = $issued->fetch_assoc()) { ?>
                                    <option value="<?php echo $row['id']; ?>">
                                        <?php echo $row['title'].' ('.$row['username'].', Issued: '.$row['issue_date'].')'; ?>
                                    </option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-12 mt-3">
                                <button type="submit" class="btn btn-light btn-lg w-100 fw-bold"><i class="bi bi-arrow-left-circle"></i> Return Book</button>
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