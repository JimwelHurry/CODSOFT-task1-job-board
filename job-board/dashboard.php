<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$role = $_SESSION['role'];

if ($role === "employer") {
    header("Location: employer_dashboard.php");
} else {
    header("Location: job_seeker_dashboard.php");
}
exit();
?>
