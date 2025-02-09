<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'job_seeker') {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    echo "❌ Invalid job ID.";
    exit();
}

$job_id = $_GET['id'];
$job_seeker_id = $_SESSION['user_id'];
$upload_dir = "uploads/resumes/"; // Resume upload directory

// Check if the job exists
$check_job = $conn->prepare("SELECT title FROM jobs WHERE id = ?");
$check_job->bind_param("i", $job_id);
$check_job->execute();
$job_result = $check_job->get_result();

if ($job_result->num_rows == 0) {
    echo "❌ Job not found.";
    exit();
}

$job = $job_result->fetch_assoc();
$job_title = $job['title'];

// Check if the user has already applied
$check_application = $conn->prepare("SELECT * FROM applications WHERE user_id = ? AND job_id = ?");
$check_application->bind_param("ii", $job_seeker_id, $job_id);
$check_application->execute();
$app_result = $check_application->get_result();

if ($app_result->num_rows > 0) {
    echo "⚠️ You have already applied for this job.";
    exit();
}

// Handle resume upload
$resume_path = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['resume'])) {
    $file_name = basename($_FILES["resume"]["name"]);
    $file_type = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    $allowed_types = array("pdf", "docx");

    if (!in_array($file_type, $allowed_types)) {
        echo "❌ Invalid file type. Only PDF and DOCX files are allowed.";
        exit();
    }

    $new_file_name = "resume_" . $job_seeker_id . "_" . time() . "." . $file_type;
    $resume_path = $upload_dir . $new_file_name;

    if (!move_uploaded_file($_FILES["resume"]["tmp_name"], $resume_path)) {
        echo "❌ Failed to upload resume.";
        exit();
    }

     // Insert application into the database
     $apply_sql = "INSERT INTO applications (job_id, user_id, resume, status, applied_at) VALUES (?, ?, ?, 'Pending', NOW())";
     $stmt = $conn->prepare($apply_sql);
     $stmt->bind_param("iis", $job_id, $job_seeker_id, $resume_path);
 
     if ($stmt->execute()) {
         echo "✅ Application submitted successfully!";
 
         // Send email notification through Web3Forms
         $post_data = array(
             'access_key' => '5f67acd7-a369-4705-bacf-7463b65704a3', // Replace with your access key
             'name' => $_SESSION['name'], // Name from session or form
             'email' => $_SESSION['email'], // Email from session
             'message' => "A new job application has been submitted for the job: " . htmlspecialchars($job_title),
         );
 
         // Use cURL to send data to Web3Forms API
         $ch = curl_init();
         curl_setopt($ch, CURLOPT_URL, "https://api.web3forms.com/submit");
         curl_setopt($ch, CURLOPT_POST, 1);
         curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
         $response = curl_exec($ch);
         curl_close($ch);
 
         if ($response === false) {
             echo "❌ Failed to send email notification.";
         }
     } else {
         echo "❌ Error applying: " . $stmt->error;
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
    <title>Apply for <?php echo htmlspecialchars($job_title); ?></title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Optional: Google Fonts for styling -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f7fa;
            color: #333;
            padding-top: 50px;
            animation: fadeIn 1s ease-out;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
        }
        .btn-submit {
            background-color: #007bff;
            color: white;
            border-radius: 20px;
            padding: 10px 20px;
            font-size: 1.1rem;
            width: 100%;
            transition: background-color 0.3s ease;
        }
        .btn-submit:hover {
            background-color: #0056b3;
        }
        .form-group {
            margin-bottom: 20px;
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        /* Mobile Adjustments */
        @media (max-width: 767px) {
            h2 {
                font-size: 1.8rem;
            }
            .form-control {
                width: 100%;
            }
        }

    </style>
</head>
<body>

    <div class="container">
        <h2 class="text-center mb-4">Apply for <?php echo htmlspecialchars($job_title); ?></h2>

        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="resume">Upload Resume (PDF/DOCX only):</label>
                <input type="file" name="resume" id="resume" class="form-control" required>
            </div>

            <input type="submit" value="Submit Application" class="btn btn-submit">
        </form>

        <div class="text-center mt-4">
            <a href="job_seeker_dashboard.php" class="btn btn-secondary">🔙 Back to Job Listings</a>
        </div>
    </div>

    <!-- Bootstrap JS and Popper.js -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
