<?php
session_start();
require 'db.php';



// Get search query if available
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Base SQL query
$sql = "SELECT id, title, description, category, location, salary FROM jobs";

// Add search filter if user entered a keyword
if (!empty($search)) {
    $sql .= " WHERE title LIKE ? OR category LIKE ? OR location LIKE ?";
}

// Order by latest job postings
$sql .= " ORDER BY id DESC";

// Prepare statement
$stmt = $conn->prepare($sql);

if (!empty($search)) {
    $search_param = "%$search%";
    $stmt->bind_param("sss", $search_param, $search_param, $search_param);
}

$stmt->execute();
$result = $stmt->get_result();
?>  

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Listings</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/job_seeker.css">
    
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="job_seeker_dashboard.php">Job Seeker Dashboard</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">🚪 Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <h2>Job Listings</h2>

        <!-- Search Form -->
        <form method="GET" class="search-form">
            <div class="form-row">
                <div class="col-md-8">
                    <input type="text" name="search" class="form-control" placeholder="🔍 Search for jobs..." value="<?php echo htmlspecialchars($search); ?>">
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary w-100">Search</button>
                </div>
            </div>
        </form>

        <br>

        <!-- Job Listings Table -->
        <?php if ($result->num_rows > 0): ?>
            <table class="table table-bordered">
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
                            <td>
                                <a href="job_details.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-info">🔍 View</a> 
                                <a href="apply_job.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-success">📩 Apply</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No job listings found.</p>
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
