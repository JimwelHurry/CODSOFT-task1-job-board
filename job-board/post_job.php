<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'employer') {
    header("Location: login.php");
    exit();
}

require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $location = $_POST['location'];
    $salary = $_POST['salary'];
    $employer_id = $_SESSION['user_id'];

    $sql = "INSERT INTO jobs (employer_id, title, description, category, location, salary) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssss", $employer_id, $title, $description, $category, $location, $salary);

    if ($stmt->execute()) {
        echo "✅ Job posted successfully!";
    } else {
        echo "❌ Error posting job: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post a Job</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
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
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        h2 {
            color: #007bff;
            margin-bottom: 30px;
        }
        .form-control {
            border-radius: 30px;
        }
        .btn-primary {
            border-radius: 30px;
            padding: 10px 20px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .alert {
            border-radius: 10px;
            font-size: 1.1rem;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="employer_dashboard.php">Employer Dashboard</a>
        </div>
    </nav>

    <div class="container">
        <h2>Post a New Job</h2>

        <!-- Job Posting Form -->
        <form method="POST">
            <div class="form-group">
                <label for="title">Job Title:</label>
                <input type="text" class="form-control" name="title" id="title" required>
            </div>

            <div class="form-group">
                <label for="description">Description:</label>
                <textarea class="form-control" name="description" id="description" rows="4" required></textarea>
            </div>

            <div class="form-group">
                <label for="category">Category:</label>
                <input type="text" class="form-control" name="category" id="category" required>
            </div>

            <div class="form-group">
                <label for="location">Location:</label>
                <input type="text" class="form-control" name="location" id="location" required>
            </div>

            <div class="form-group">
                <label for="salary">Salary:</label>
                <input type="text" class="form-control" name="salary" id="salary" required>
            </div>

            <button type="submit" class="btn btn-primary w-100">Post Job</button>
        </form>

        <br>

        <!-- Back Button -->
        <a href="employer_dashboard.php" class="btn btn-secondary w-100">Back to Dashboard</a>
    </div>

    <!-- Bootstrap JS and Popper.js -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>