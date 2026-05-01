<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'doctor') {
    header('location: login_dt.php');
}

include "../connection.php";

$username = ucfirst($_SESSION["docname"]);
$email = $_SESSION["docemail"];
$sql = "SELECT * FROM doctor WHERE email = '$email';";
$query = mysqli_query($con, $sql);
$row = mysqli_fetch_assoc($query);
$userid = $row['id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Settings</title>
    <link rel="stylesheet" href="../assets/css/settingdoc.css">
</head>
<body>
    <?php include 'sidebar.php'; ?>

    <div class="dash-body">
        <!-- HEADER -->
        <div class="settings-header">
            <div class="back-and-title">
                <a href="index.php" style="text-decoration:none;">
                    <button class="btn-back">← Back</button>
                </a>
                <h2 class="page-title">Settings</h2>
            </div>
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

       
        <div class="settings-section">
            <div class="settings-grid">
        
                <a href="update_doc.php?upid=<?php echo $userid; ?>" class="settings-card">
                    <div class="fa fa-pencil"></div>
                    <div class="settings-text">
                        <h3>Account Settings</h3>
                        <p>Edit your profile details, update your information, and customize your account.</p>
                    </div>
                </a>

                <a href="view.php?viewid=<?php echo $userid; ?>" class="settings-card">
                    <div class="fa fa-eye"></div>
                    <div class="settings-text">
                        <h3>View Account Details</h3>
                        <p>See your personal information and review your account profile.</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</body>
</html>