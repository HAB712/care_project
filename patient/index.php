<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'patient') {
    header('location: login_pt.php');
}

include "../connection.php";

$username = ucfirst($_SESSION["user_name"]);
$email = $_SESSION["user_email"];

// Get appointment count
$queryCount = "SELECT COUNT(*) as total FROM appointment WHERE pt_email = '$email'";
$resCount = mysqli_query($con, $queryCount);
$apptCount = ($rowC = mysqli_fetch_assoc($resCount)) ? $rowC['total'] : 0;

// Get upcoming appointments
$queryUpcoming = "SELECT a.appdate, a.pt_name, a.pt_gender, d.name as doctor 
                  FROM appointment as a 
                  JOIN doctor as d ON a.docid = d.id 
                  WHERE pt_email = '$email' 
                  ORDER BY a.appdate DESC LIMIT 3";
$upcomingResult = mysqli_query($con, $queryUpcoming);
$upcomingCount = mysqli_num_rows($upcomingResult);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Dashboard</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7fc;
            color: #333;
        }

        /* Sidebar is expected to be fixed left with width ~260px */
        .dash-body {
            margin-left: 260px;
            padding: 25px;
            transition: margin-left 0.3s ease;
            min-height: 100vh;
        }

        /* ---------- HEADER ---------- */
        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            margin-bottom: 25px;
            background: white;
            padding: 15px 25px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .page-title {
            font-size: 26px;
            font-weight: 700;
            color: #1e293b;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 15px;
            color: #64748b;
        }

        .date-text {
            font-size: 14px;
            text-align: right;
        }

        .date-big {
            font-weight: 600;
            font-size: 18px;
            color: #334155;
        }

        .calendar-icon {
            width: 44px;
            height: 44px;
            background: #e0f2fe;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.2s;
        }

        .calendar-icon:hover {
            background: #bae6fd;
        }

        .calendar-icon img {
            width: 24px;
            height: 24px;
        }

        /* ---------- WELCOME CARD ---------- */
        .welcome-card {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            border-radius: 16px;
            padding: 30px;
            margin-bottom: 25px;
            color: white;
            box-shadow: 0 8px 20px rgba(37,99,235,0.25);
        }

        .welcome-card h1 {
            font-size: 32px;
            margin: 8px 0 15px;
            font-weight: 700;
        }

        .welcome-card p {
            font-size: 16px;
            line-height: 1.6;
            opacity: 0.95;
        }

        .welcome-card a {
            color: #facc15;
            text-decoration: none;
            font-weight: 600;
            border-bottom: 1px dashed #facc15;
            transition: 0.2s;
        }

        .welcome-card a:hover {
            color: #fff;
            border-bottom-color: #fff;
        }

        /* ---------- TWO COLUMN LAYOUT ---------- */
        .dashboard-grid {
            display: flex;
            gap: 25px;
            flex-wrap: wrap;
        }

        .left-column {
            flex: 1 1 650px;
            display: flex;
            flex-direction: column;
            gap: 25px;
        }

        .right-column {
            flex: 1 1 300px;
            /* right side intentionally left empty, can hold other widgets later */
        }

        /* ---------- STATS CARDS ---------- */
        .stats-row {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }

        .stat-card {
            flex: 1 1 200px;
            background: white;
            border-radius: 16px;
            padding: 25px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 12px rgba(0,0,0,0.04);
            transition: transform 0.2s, box-shadow 0.2s;
            text-decoration: none;
            color: inherit;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 18px rgba(0,0,0,0.08);
        }

        .stat-info .stat-value {
            font-size: 36px;
            font-weight: 700;
            color: #1e293b;
            line-height: 1.2;
        }

        .stat-info .stat-label {
            font-size: 15px;
            color: #64748b;
            margin-top: 6px;
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 14px;
            background-size: 28px;
            background-position: center;
            background-repeat: no-repeat;
            background-color: #eff6ff;
        }

        .stat-icon.appointments {
            background-image: url('../img/icons/doctors-hover.svg');
        }

        .stat-icon.settings {
            background-image: url('../img/icons/session-iceblue.svg');
        }

        /* ---------- UPCOMING APPOINTMENTS TABLE ---------- */
        .appointments-section {
            background: white;
            border-radius: 16px;
            padding: 20px 25px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.04);
        }

        .section-title {
            font-size: 20px;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 20px;
        }

        .styled-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }

        .styled-table th {
            text-align: left;
            padding: 12px 16px;
            background: #f8fafc;
            color: #475569;
            font-weight: 600;
            border-bottom: 2px solid #e2e8f0;
        }

        .styled-table td {
            padding: 12px 16px;
            border-bottom: 1px solid #f1f5f9;
            color: #334155;
        }

        .styled-table tbody tr:hover {
            background-color: #f0f9ff;
            transition: background-color 0.2s;
        }

        .styled-table tbody tr:last-child td {
            border-bottom: none;
        }

        .no-data {
            text-align: center;
            padding: 30px;
            color: #94a3b8;
            font-style: italic;
        }

        /* ---------- RESPONSIVE ---------- */
        @media (max-width: 992px) {
            .dash-body {
                margin-left: 0;
                padding-bottom: 100px; /* space for mobile bottom nav */
            }

            .dashboard-grid {
                flex-direction: column;
            }

            .right-column {
                display: none; /* hide empty column on mobile */
            }
        }

        @media (max-width: 768px) {
            .page-title {
                font-size: 22px;
            }

            .welcome-card {
                padding: 20px;
            }

            .welcome-card h1 {
                font-size: 26px;
            }

            .stats-row {
                flex-direction: column;
            }

            .stat-card {
                width: 100%;
            }

            .appointments-section {
                padding: 15px;
            }

            .styled-table {
                font-size: 13px;
            }
        }

        @media (max-width: 480px) {
            .dashboard-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }

            .header-right {
                width: 100%;
                justify-content: space-between;
            }

            .date-text, .calendar-icon {
                display: none; /* hide date & icon on very small screens */
            }

            .styled-table th, .styled-table td {
                padding: 8px 10px;
            }
        }
    </style>
</head>
<body>
    <?php include 'sidebar.php'; ?>

    <div class="dash-body">
        <!-- HEADER -->
        <div class="dashboard-header">
           <a href="../index.php" style="text-decoration: none;"> <h2 class="page-title">Home</h2> </a> 
            <div class="header-right">
                <div class="date-text">
                    <p style="margin:0; font-size:13px;">Today's Date</p>
                    <p class="date-big" style="margin:0;">
                        <?php 
                        date_default_timezone_set('Asia/Kolkata');
                        echo date('Y-m-d');
                        ?>
                    </p>
                </div>
                <div class="calendar-icon">
                    <img src="../img/calendar.svg" alt="calendar">
                </div>
            </div>
        </div>

        <!-- WELCOME -->
        <div class="welcome-card">
            <h1>Welcome, <?php echo $username; ?>!</h1>
            <p>
                Need to check your appointments? Visit 
                <a href="appointment.php">My Appointments</a>.
                <br>
                Want to update your profile? Go to 
                <a href="settings.php">Settings</a>.
            </p>
        </div>

        <!-- TWO-COLUMN CONTENT -->
        <div class="dashboard-grid">
            <!-- LEFT COLUMN: Stats + Upcoming Appointments -->
            <div class="left-column">
                <!-- Status cards -->
                <div class="stats-row">
                    <div class="stat-card">
                        <div class="stat-info">
                            <div class="stat-value"><?php echo $apptCount; ?></div>
                            <div class="stat-label">My Appointments</div>
                        </div>
                        <div class="stat-icon appointments"></div>
                    </div>
                    <a href="settings.php" class="stat-card" style="text-decoration:none;">
                        <div class="stat-info">
                            <div class="stat-value" style="color:#2563eb;">⚙️</div>
                            <div class="stat-label">Settings</div>
                        </div>
                        <div class="stat-icon settings"></div>
                    </a>
                </div>

                <!-- Upcoming Appointments Table -->
                <div class="appointments-section">
                    <div class="section-title">Your Upcoming Appointments</div>
                    <?php if ($upcomingCount > 0): ?>
                    <table class="styled-table">
                        <thead>
                            <tr>
                                <th>Patient Name</th>
                                <th>Gender</th>
                                <th>Doctor</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = mysqli_fetch_assoc($upcomingResult)): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['pt_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['pt_gender']); ?></td>
                                <td>Dr. <?php echo htmlspecialchars($row['doctor']); ?></td>
                                <td><?php echo htmlspecialchars($row['appdate']); ?></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                    <?php else: ?>
                    <div class="no-data">No upcoming appointments found.</div>
                    <?php endif; ?>
                </div>
            </div>

       
        </div>
    </div>
</body>
</html>