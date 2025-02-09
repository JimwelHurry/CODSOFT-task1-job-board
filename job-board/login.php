<?php
include 'db.php';
session_start();



// Check if user is already logged in, if yes, redirect to dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            // Redirect to dashboard.php
            header("Location: dashboard.php");
            exit();  // Don't forget to call exit() after header to stop further script execution
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "No user found with this email.";
    }
}

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
    <title>Login - Job Board</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Optional: Google Fonts for styling -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/login.css">
    <link rel="stylesheet" href="assets/css/job-board.css">
    
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
                        <a class="nav-link" href="login.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Jobs</a>
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

    <!-- Hero Section -->
    <div class="hero-section">
        <h1>Find Your Dream Job Today</h1>
        <p>Browse through the latest job listings or post a job if you're an employer.</p>
        <a href="login.php" class="btn btn-light btn-post-job">Login to Apply</a>
    </div>

    <!-- Job Listings -->
    <div class="container">
        <h2 class="text-center mb-4">Featured Job Listings</h2>

        <!-- Search Form -->
        <form method="GET" class="text-center mb-4">
            <input type="text" name="search" placeholder="🔍 Search for jobs..." value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit" class="btn btn-primary">Search</button>
        </form>

        <<div class="row">
    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="col-md-4">
                <div class="card job-listing-card">
                    <div class="card-header">
                        <h4 class="job-card-title"><?php echo htmlspecialchars($row['title']); ?></h4>
                    </div>
                    <div class="card-body">
                        <p class="job-card-description"><?php echo htmlspecialchars($row['description']); ?></p>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p class="text-center" style="width: 100%;">❌ No job listings found.</p>
    <?php endif; ?>
</div>



        <div class="text-center mt-4">
            <a href="login.php" class="btn btn-secondary">See All Jobs</a>
        </div>
    </div>

    <!-- Post a Job Section -->
    <div class="container text-center">
        <h3 class="my-5">Are You an Employer?</h3>
        <p>Post a job opening and find your next great hire.</p>
        <a href="login.php" class="btn btn-success btn-post-job">Post a Job</a>
    </div>

    <!-- Login Form -->
    <div class="container" style="margin-top: 100px;">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header text-center">
                        <h3>Job Board Login</h3>
                    </div>
                    <div class="card-body">
                        <form method="POST">  
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" name="email" id="email" placeholder="Enter your email" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" name="password" id="password" placeholder="Enter your password" required>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">Login</button>
                        </form>
                        <br>
                        <p class="text-center">Don't have an account? <a href="register.php" style="color: #00f;">Sign up here</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and Popper.js (for responsive behavior) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>


