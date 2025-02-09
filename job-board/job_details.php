<?php
session_start();
require 'db.php';

if (!isset($_GET['id'])) {
    echo "❌ Invalid job ID.";
    exit();
}

$job_id = $_GET['id'];

// Fetch job details
$sql = "SELECT title, description, category, location, salary FROM jobs WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $job_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "❌ Job not found.";
    exit();
}

$job = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Details</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f7fa;
            color: #333;
            padding-top: 50px;
        }
        .navbar {
            background-color: #007bff;
        }
        .navbar a {
            color: white;
        }
        .navbar a:hover {
            color: #f1f1f1;
        }
        .job-details {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            padding: 30px;
            margin: 20px;
        }
        .job-details h2 {
            color: #007bff;
        }
        .btn-apply, .btn-back {
            background-color: #007bff;
            color: white;
            border-radius: 20px;
            padding: 10px 20px;
            font-size: 1.1rem;
            margin-top: 20px;
        }
        .btn-apply:hover, .btn-back:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="job-board.php">Job Board</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="job-board.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="job_listing.php">Jobs</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="register.php">Sign Up</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Job Details Section -->
    <div class="container job-details">
        <h2><?php echo htmlspecialchars($job['title']); ?></h2>
        <p><strong>Category:</strong> <?php echo htmlspecialchars($job['category']); ?></p>
        <p><strong>Location:</strong> <?php echo htmlspecialchars($job['location']); ?></p>
        <p><strong>Salary:</strong> <?php echo htmlspecialchars($job['salary']); ?></p>
        <p><strong>Description:</strong><br> <?php echo nl2br(htmlspecialchars($job['description'])); ?></p>

        <div class="d-flex justify-content-between">
            <a href="apply_job.php?id=<?php echo $job_id; ?>" class="btn btn-apply">📩 Apply for this Job</a>
            <a href="job_seeker_dashboard.php" class="btn btn-back">🔙 Back to Job Listings</a>
        </div>
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

