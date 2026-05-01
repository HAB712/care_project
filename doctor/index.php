<?php
session_start();

if(!isset($_SESSION['role']) || $_SESSION['role'] != 'doctor') {
    header('location: login_dt.php');
}

include "../connection.php";

$username = ucfirst($_SESSION["docname"]);
$email = $_SESSION["docemail"];


$queryTotal = "SELECT COUNT(a.pt_name) as total FROM appointment a JOIN doctor d ON a.docid = d.id WHERE d.email = '$email'";
$resTotal = mysqli_query($con, $queryTotal);
$totalPatients = ($rowTotal = mysqli_fetch_assoc($resTotal)) ? $rowTotal['total'] : 0;


$queryPending = "SELECT COUNT(a.pt_name) as tot FROM appointment a JOIN doctor d ON a.docid = d.id WHERE d.email = '$email' AND status = 'pending'";
$resPending = mysqli_query($con, $queryPending);
$pendingCount = ($rowPending = mysqli_fetch_assoc($resPending)) ? $rowPending['tot'] : 0;


$queryRecentPending = "SELECT * FROM appointment a JOIN doctor d ON a.docid = d.id WHERE d.email = '$email' AND status = 'pending' LIMIT 3";
$resRecentPending = mysqli_query($con, $queryRecentPending);
$hasPending = mysqli_num_rows($resRecentPending) > 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Dashboard</title>
    <link rel="stylesheet" href="../assets/css/indexdoc.css">
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <?php include 'sidebar.php'; ?>

    <div class="dash-body">
        <div class="dashboard-header">
            <h2 class="page-title">Dashboard</h2>
            <div class="header-right">
                <div class="date-text">
                    <p style="margin:0; font-size:13px;">Today's Date</p>
                    <p class="date-big" style="margin:0;">
                        <?php 
                        date_default_timezone_set('Asia/Karachi');
                        echo date('Y-m-d');
                        ?>
                    </p>
                </div>
                <div class="calendar-icon">
                    <img src="../img/calendar.svg" alt="calendar">
                </div>
            </div>
        </div>

        <div class="welcome-card">
            <h1>Welcome, Dr. <?php echo $username; ?>!</h1>
            <p>
                Thanks for joining us. We're committed to delivering the best service.<br>
                View your daily schedule and manage patient appointments at your convenience.
            </p>
            <a href="appointment.php" class="btn-view-appointments">
                View Appointments →
            </a>
        </div>

        <div class="stats-row">
            <div class="stat-card">
                <div class="stat-info">
                    <div class="stat-value"><?php echo $totalPatients; ?></div>
                    <div class="stat-label">All Patients</div>
                </div>
                <div class="fas fa-procedures"></div>
            </div>
            <div class="stat-card">
                <div class="stat-info">
                    <div class="stat-value"><?php echo $pendingCount; ?></div>
                    <div class="stat-label">New Appointments</div>
                </div>
                <div class="fas fa-calendar-check"></div>
            </div>
        </div>

        <div class="appointments-section">
            <div class="section-title">Your Pending Appointments</div>
            <div class="table-container">
                <?php if ($hasPending): ?>
                <table class="styled-table">
                    <thead>
                        <tr>
                            <th>Patient Name</th>
                            <th>Gender</th>
                            <th>Appointment Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($resRecentPending)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['pt_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['pt_gender']); ?></td>
                            <td><?php echo htmlspecialchars($row['appdate']); ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <?php else: ?>
                    <div class="no-data">No pending appointments found.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>