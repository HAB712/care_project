<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'doctor') {
    header('location: login_dt.php');
}
include "../connection.php";

$username = ucfirst($_SESSION["docname"]);
$email = $_SESSION["docemail"];

$search_term = isset($_POST['search12']) ? $_POST['search12'] : '';
?>
    
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Patients</title>
    <link rel="stylesheet" href="../assets/css/patientdoc.css">
</head>
<body>

    <div>
        <?php include 'sidebar.php';?>

        <div class="dash-body">
            <!-- HEADER -->
            <div class="patients-header">
                <div class="back-and-title">
                    <a href="index.php" style="text-decoration:none;">
                        <button class="btn-back">← Back</button>
                    </a>
                    <h2 class="page-title">My Patients</h2>
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

            <!-- SEARCH (Desktop - shown on wider screens) -->
            <div class="search-section search-desktop">
                <form method="post" class="search-form">
                    <input type="search" name="search12" class="search-input" 
                           placeholder="Search Patient name or Email" 
                           value="<?php echo htmlspecialchars($search_term); ?>">
                    <button type="submit" name="search" class="btn-search">Search</button>
                </form>
            </div>

            <!-- SEARCH (Mobile - shown on small screens) -->
            <div class="search-section search-mobile">
                <form method="post" class="search-form">
                    <input type="search" name="search12" class="search-input" 
                           placeholder="Search Patient name or Email"
                           value="<?php echo htmlspecialchars($search_term); ?>">
                    <button type="submit" name="search" class="btn-search">Search</button>
                </form>
            </div>

            <!-- PATIENTS TABLE -->
            <div class="table-section">
                <div class="section-title">
                    <?php if (!empty($search_term)) echo 'Results for "' . htmlspecialchars($search_term) . '"'; else echo 'All Patients'; ?>
                </div>

                <div class="table-container">
                    <table class="styled-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Gender</th>
                                <th>Appointment Date</th>
                                <th>Message</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Base query with search
                            $ptquery = "SELECT specialistid, docid, pt_name, pt_gender, pt_email, a.phone AS appoint_phone, 
                                       pt_address, country, appdate, apptime, message, status, a.id AS appoint_id, d.id AS doctor_id 
                                       FROM appointment a JOIN doctor d ON a.docid = d.id 
                                       WHERE d.email = '$email'";
                            if (!empty($search_term)) {
                                $ptquery .= " AND (a.pt_name LIKE '%$search_term%' OR a.pt_email LIKE '%$search_term%')";
                            }
                            $ptres = mysqli_query($con, $ptquery);
                            $pt_count = mysqli_num_rows($ptres);

                            if ($pt_count > 0) {
                                while ($row = mysqli_fetch_assoc($ptres)) {
                                    ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars(ucfirst($row['pt_name'])); ?></td>
                                        <td><?php echo htmlspecialchars($row['pt_email']); ?></td>
                                        <td><?php echo htmlspecialchars($row['appoint_phone']); ?></td>
                                        <td><?php echo htmlspecialchars($row['pt_gender']); ?></td>
                                        <td><?php echo htmlspecialchars($row['appdate']); ?></td>
                                        <td><?php echo htmlspecialchars($row['message']); ?></td>
                                        <td>
                                            <a href="?action=view&id=<?php echo $row['appoint_id']; ?>" class="btn-view">View</a>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                echo '<tr>
                                    <td colspan="7">
                                        <div class="empty-state">
                                            <img src="../img/notfound.svg" alt="No patients found">
                                            <p>';
                                echo (!empty($search_term)) ? 'No patients found matching your search' : 'You Have No Patients!';
                                echo '</p>
                                        </div>
                                    </td>
                                </tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <?php 
    // POPUP VIEW (when GET parameters exist)
    if (isset($_GET['action']) && $_GET['action'] == 'view' && isset($_GET['id'])) {
        $id = $_GET["id"];
        $sqlmains = "SELECT * FROM appointment WHERE id = '$id'";
        $resultmains = mysqli_query($con, $sqlmains);
        $row = mysqli_fetch_assoc($resultmains);
        if ($row) {
            $name = $row["pt_name"];
            $email = $row["pt_email"];
            $gender = $row["pt_gender"];
            $dob = $row["dob"];
            $tele = $row["phone"];
            $status = $row["status"];
            ?>
            <div id="popup1" class="overlay">
                <div class="popup">
                    <a class="close" href="patient.php">&times;</a>
                    <div class="popup-content">
                        <div class="detail-row">
                            <span class="detail-label">Name:</span>
                            <span class="detail-value"><?php echo htmlspecialchars($name); ?></span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Email:</span>
                            <span class="detail-value"><?php echo htmlspecialchars($email); ?></span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Gender:</span>
                            <span class="detail-value"><?php echo htmlspecialchars($gender); ?></span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Phone:</span>
                            <span class="detail-value"><?php echo htmlspecialchars($tele); ?></span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Date of Birth:</span>
                            <span class="detail-value"><?php echo htmlspecialchars($dob); ?></span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Status:</span>
                            <span class="detail-value"><?php echo htmlspecialchars($status); ?></span>
                        </div>
                        <a href="patient.php"><button class="btn-ok">OK</button></a>
                    </div>
                </div>
            </div>
            <?php
        }
    }
    ?>
</body>
</html>