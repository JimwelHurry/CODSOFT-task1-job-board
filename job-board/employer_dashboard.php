<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'employer') {
    header("Location: login.php");
    exit();
}

require 'db.php';

$employer_id = $_SESSION['user_id'];

// Handle the delete job request
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $delete_sql = "DELETE FROM jobs WHERE id = ? AND employer_id = ?";
    $delete_stmt = $conn->prepare($delete_sql);
    $delete_stmt->bind_param("ii", $delete_id, $employer_id);
    if ($delete_stmt->execute()) {
        header("Location: employer_dashboard.php");
        exit();
    } else {
        echo "Error deleting job listing.";
    }
}

// Handle the edit job form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_job_id'])) {
    $job_id = $_POST['edit_job_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $location = $_POST['location'];
    $salary = $_POST['salary'];

    $update_sql = "UPDATE jobs SET title = ?, description = ?, category = ?, location = ?, salary = ? WHERE id = ? AND employer_id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("ssssdii", $title, $description, $category, $location, $salary, $job_id, $employer_id);
    if ($update_stmt->execute()) {
        header("Location: employer_dashboard.php");
        exit();
    } else {
        echo "Error updating job listing.";
    }
}

// Fetch job listings
$sql = "SELECT id, title, description, category, location, salary FROM jobs WHERE employer_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $employer_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employer Dashboard</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/employer.css">
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="employer_dashboard.php">Employer Dashboard</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="post_job.php">➕ Post a New Job</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">🚪 Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5 pt-5">
        <h2 class="text-center">Employer Dashboard</h2>
        <h3>Your Job Listings</h3>

        <?php if ($result->num_rows > 0): ?>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Job Title</th>
                            <th>Description</th>
                            <th>Category</th>
                            <th>Location</th>
                            <th>Salary</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['title']); ?></td>
                                <td><?php echo htmlspecialchars(substr($row['description'], 0, 50)) . '...'; ?></td>
                                <td><?php echo htmlspecialchars($row['category']); ?></td>
                                <td><?php echo htmlspecialchars($row['location']); ?></td>
                                <td><?php echo htmlspecialchars($row['salary']); ?></td>
                                <td class="action-links">
                                    <!-- Edit Button -->
                                    <button class="btn btn-warning" data-toggle="modal" data-target="#editModal<?php echo $row['id']; ?>">✏️ Edit</button>

                                    <!-- Delete Button -->
                                    <a href="employer_dashboard.php?delete_id=<?php echo $row['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this job?')">❌ Delete</a>
                                </td>
                            </tr>

                            <!-- Edit Modal -->
                            <div class="modal fade" id="editModal<?php echo $row['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="editModalLabel<?php echo $row['id']; ?>" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editModalLabel<?php echo $row['id']; ?>">Edit Job Listing</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form method="POST">
                                                <input type="hidden" name="edit_job_id" value="<?php echo $row['id']; ?>">
                                                <div class="form-group">
                                                    <label for="title">Job Title</label>
                                                    <input type="text" class="form-control" name="title" value="<?php echo htmlspecialchars($row['title']); ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="description">Description</label>
                                                    <textarea class="form-control" name="description" required><?php echo htmlspecialchars($row['description']); ?></textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label for="category">Category</label>
                                                    <input type="text" class="form-control" name="category" value="<?php echo htmlspecialchars($row['category']); ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="location">Location</label>
                                                    <input type="text" class="form-control" name="location" value="<?php echo htmlspecialchars($row['location']); ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="salary">Salary</label>
                                                    <input type="number" class="form-control" name="salary" value="<?php echo htmlspecialchars($row['salary']); ?>" required>
                                                </div>
                                                <button type="submit" class="btn btn-primary">Update Job</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="text-center">❌ No jobs posted yet.</p>
        <?php endif; ?>

    </div>

    <!-- Bootstrap JS and Popper.js -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
