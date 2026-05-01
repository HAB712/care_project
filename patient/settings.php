<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'patient') {
    header('location: login_pt.php');
}

include "../connection.php";

$username = ucfirst($_SESSION["user_name"]);
$email = $_SESSION["user_email"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings</title>
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

        /* Sidebar offset (sidebar.php is included, expected ~260px) */
        .dash-body {
            margin-left: 260px;
            padding: 25px;
            transition: margin-left 0.3s ease;
            min-height: 100vh;
        }

        /* ---------- HEADER ---------- */
        .settings-header {
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

        .back-and-title {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .btn-back {
            background: #f1f5f9;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            color: #334155;
            cursor: pointer;
            transition: background 0.2s;
            white-space: nowrap;
        }

        .btn-back:hover {
            background: #e2e8f0;
        }

        .page-title {
            font-size: 24px;
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
            line-height: 1.4;
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
        }

        .calendar-icon img {
            width: 24px;
            height: 24px;
        }

        /* ---------- SETTINGS SECTION ---------- */
        .settings-section {
            background: white;
            border-radius: 16px;
            padding: 25px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.04);
            max-width: 600px;
            margin: 0 auto; /* center the card */
        }

        .settings-card {
            display: flex;
            align-items: center;
            gap: 20px;
            padding: 20px;
            border-radius: 12px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            text-decoration: none;
            color: inherit;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .settings-card:hover {
            background: #f0f9ff;
            border-color: #bae6fd;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            transform: translateY(-2px);
        }

        .settings-icon {
            width: 50px;
            height: 50px;
            border-radius: 14px;
            background-color: #eff6ff;
            background-image: url('../img/icons/view-iceblue.svg');
            background-size: 28px;
            background-position: center;
            background-repeat: no-repeat;
            flex-shrink: 0;
        }

        .settings-text h3 {
            font-size: 18px;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 6px;
        }

        .settings-text p {
            font-size: 14px;
            color: #64748b;
            line-height: 1.5;
        }

        /* ---------- RESPONSIVE ---------- */
        @media (max-width: 992px) {
            .dash-body {
                margin-left: 0;
                padding-bottom: 100px; /* space for mobile bottom nav */
            }
        }

        @media (max-width: 768px) {
            .settings-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }

            .header-right {
                width: 100%;
                justify-content: space-between;
            }

            .settings-section {
                padding: 15px;
            }

            .settings-card {
                flex-direction: column;
                text-align: center;
            }

            .settings-icon {
                margin: 0 auto;
            }
        }

        @media (max-width: 480px) {
            .date-text,
            .calendar-icon {
                display: none;
            }

            .page-title {
                font-size: 20px;
            }
        }
    </style>
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

        <!-- SETTINGS CARD -->
        <div class="settings-section">
            <a href="update-account.php" class="settings-card">
                <div class="settings-icon"></div>
                <div class="settings-text">
                    <h3>View & Edit Account Details</h3>
                    <p>Manage your personal information and account preferences.</p>
                </div>
            </a>
            <!-- You can easily add more settings cards here later -->
        </div>
    </div>
</body>
</html>