<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'patient') {
    header('location: login_pt.php');
}

include "../connection.php";

$username = ucfirst($_SESSION["user_name"]);
$email = $_SESSION["user_email"];

// Base query
$query = "SELECT a.*, d.name as doctor_name, c.city_name, ds.specialist
          FROM appointment a 
          JOIN doctor d ON a.docid = d.id 
          JOIN docspecialization ds ON d.speciality = ds.ds_id
          JOIN city c ON d.city = c.ct_id 
          WHERE pt_email = '$email'";

// Filter by date
if (isset($_POST['filter']) && !empty($_POST['sheduledate'])) {
    $shedule = mysqli_real_escape_string($con, $_POST['sheduledate']);
    $query .= " AND appdate = '$shedule'";
}

$queryExec = mysqli_query($con, $query);
$count = mysqli_num_rows($queryExec);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Appointments</title>
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

        /* Sidebar offset (sidebar.php expected to be fixed left ~260px) */
        .dash-body {
            margin-left: 260px;
            padding: 25px;
            transition: margin-left 0.3s ease;
            min-height: 100vh;
        }

        /* ---------- HEADER ---------- */
        .app-header {
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

        /* ---------- FILTER SECTION ---------- */
        .filter-bar {
            background: white;
            border-radius: 12px;
            padding: 20px 25px;
            margin-bottom: 25px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .filter-form {
            display: flex;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
        }

        .filter-label {
            font-weight: 600;
            color: #334155;
            white-space: nowrap;
        }

        .filter-input {
            flex: 1 1 200px;
            padding: 10px 15px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            font-size: 14px;
            background: white;
            color: #1e293b;
            transition: border-color 0.2s;
        }

        .filter-input:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37,99,235,0.1);
        }

        .btn-filter {
            background: #2563eb;
            color: white;
            border: none;
            padding: 10px 25px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
            white-space: nowrap;
        }

        .btn-filter:hover {
            background: #1d4ed8;
        }

        /* ---------- APPOINTMENTS TABLE ---------- */
        .table-section {
            background: white;
            border-radius: 12px;
            padding: 20px 25px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .table-title {
            font-size: 20px;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 20px;
        }

        .table-container {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .styled-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 800px; /* ensures horizontal scroll on small screens */
        }

        .styled-table th {
            text-align: left;
            padding: 12px 16px;
            background: #f8fafc;
            color: #475569;
            font-weight: 600;
            border-bottom: 2px solid #e2e8f0;
            white-space: nowrap;
        }

        .styled-table td {
            padding: 12px 16px;
            border-bottom: 1px solid #f1f5f9;
            color: #334155;
            font-size: 14px;
        }

        .styled-table tbody tr:hover {
            background-color: #f0f9ff;
            transition: background-color 0.2s;
        }

        .styled-table tbody tr:last-child td {
            border-bottom: none;
        }

        /* Status badge */
        .status-badge {
            background: #e0f2fe;
            color: #0369a1;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            display: inline-block;
        }

        /* Empty state */
        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #64748b;
        }

        .empty-state img {
            width: 120px;
            margin-bottom: 20px;
            opacity: 0.8;
        }

        .empty-state p {
            font-size: 18px;
            margin-bottom: 20px;
        }

        .btn-show-all {
            background: #f1f5f9;
            color: #1e293b;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }

        .btn-show-all:hover {
            background: #e2e8f0;
        }

        /* ---------- RESPONSIVE ---------- */
        @media (max-width: 992px) {
            .dash-body {
                margin-left: 0;
                padding-bottom: 100px; /* for mobile bottom nav */
            }
        }

        @media (max-width: 768px) {
            .app-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }

            .header-right {
                width: 100%;
                justify-content: space-between;
            }

            .filter-form {
                flex-direction: column;
                align-items: stretch;
            }

            .filter-input {
                width: 100%;
            }

            .btn-filter {
                width: 100%;
                text-align: center;
            }

            .styled-table th,
            .styled-table td {
                padding: 8px 12px;
                font-size: 13px;
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

            .table-title {
                font-size: 18px;
            }

            .empty-state img {
                width: 80px;
            }
        }
    </style>
</head>
<body>
    <?php include 'sidebar.php'; ?>

    <div class="dash-body">
        <!-- HEADER -->
        <div class="app-header">
            <div class="back-and-title">
                <a href="index.php" style="text-decoration:none;">
                    <button class="btn-back">← Back</button>
                </a>
                <h2 class="page-title">Appointment Manager</h2>
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

        <!-- FILTER -->
        <div class="filter-bar">
            <form method="post" class="filter-form">
                <span class="filter-label">Filter by Date:</span>
                <input type="date" name="sheduledate" class="filter-input" value="<?php echo isset($_POST['sheduledate']) ? htmlspecialchars($_POST['sheduledate']) : ''; ?>">
                <button type="submit" name="filter" class="btn-filter">Apply Filter</button>
            </form>
        </div>

        <!-- APPOINTMENTS LIST -->
        <div class="table-section">
            <div class="table-title">My Appointments (<?php echo $count; ?>)</div>

            <?php if ($count > 0): ?>
            <div class="table-container">
                <table class="styled-table">
                    <thead>
                        <tr>
                            <th>Patient Name</th>
                            <th>Gender</th>
                            <th>Email</th>
                            <th>City</th>
                            <th>Doctor</th>
                            <th>Specialist</th>
                            <th>Appointment Date</th>
                            <th>Time</th>
                            <th>Message</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($queryExec)): ?>
                        <tr>
                            <td><?php echo ucfirst(htmlspecialchars($row['pt_name'])); ?></td>
                            <td><?php echo htmlspecialchars($row['pt_gender']); ?></td>
                            <td><?php echo htmlspecialchars($row['pt_email']); ?></td>
                            <td><?php echo htmlspecialchars($row['city_name']); ?></td>
                            <td>Dr. <?php echo htmlspecialchars($row['doctor_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['specialist']); ?></td>
                            <td><?php echo htmlspecialchars($row['appdate']); ?></td>
                            <td><?php echo htmlspecialchars($row['apptime']); ?></td>
                            <td><?php echo htmlspecialchars($row['message']); ?></td>
                            <td><span class="status-badge"><?php echo htmlspecialchars($row['status']); ?></span></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <div class="empty-state">
                <img src="../img/notfound.svg" alt="No appointments">
                <p>We couldn't find anything!</p>
                <a href="appointment.php" style="text-decoration:none;">
                    <button class="btn-show-all">Show All Appointments</button>
                </a>
            </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>