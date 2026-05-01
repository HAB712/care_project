<?php 
session_start();
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'A'){
    header('location: login_ad.php');
}
include("../connection.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/admindashb.css">
</head>
<body>

    <?php include 'sidebar.php'; ?>

    <!-- Main Content -->
    <div class="main-content">
        <nav class="navbar navbar-expand-lg navbar-light bg-white mb-4 rounded shadow-sm">
            <div class="container-fluid">
                <h4 class="mb-0 text-primary">Admin Panel</h4>
                <div class="d-flex align-items-center">
                    <div class="me-3 text-end d-none d-sm-block">
                        <small class="text-muted d-block">Today's Date</small>
                        <strong><?php echo date('Y-m-d'); ?></strong>
                    </div>
                    <button class="btn btn-sm btn-outline-secondary d-none d-sm-block">
                        <i class="far fa-calendar-alt"></i>
                    </button>
                </div>
            </div>
        </nav>

        <!-- Stats Cards -->
        <div class="row mb-4 animate-fade">
            <div class="col-md-4 mb-3">
                <div class="card dashboard-card h-100">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <h2 class="mb-0">
                                    <?php 
                                    $quer = "SELECT COUNT(*) as doctor_total FROM doctor";
                                    $res = mysqli_query($con, $quer);
                                    $row = mysqli_fetch_assoc($res);
                                    echo $row['doctor_total'];
                                    ?>
                                </h2>
                                <p class="text-muted mb-0">Doctors</p>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-user-md card-icon text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card dashboard-card h-100">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <h2 class="mb-0">
                                    <?php 
                                    $sql4 = "SELECT COUNT(*) as total FROM appointment";
                                    $result4 = mysqli_query($con, $sql4);
                                    $row4 = mysqli_fetch_assoc($result4);
                                    echo $row4['total'];
                                    ?>
                                </h2>
                                <p class="text-muted mb-0">Patients</p>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-procedures card-icon text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card dashboard-card h-100">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <h2 class="mb-0">
                                    <?php 
                                    $sql4 = "SELECT COUNT(*) as total FROM appointment";
                                    $result4 = mysqli_query($con, $sql4);
                                    $row4 = mysqli_fetch_assoc($result4);
                                    echo $row4['total'];
                                    ?>
                                </h2>
                                <p class="text-muted mb-0">Appointments</p>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-calendar-check card-icon text-info"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tables Section -->
        <div class="row animate-fade">
            <div class="col-lg-6 mb-4">
                <div class="card h-100">
                    <div class="card-header bg-white">
                        <h5 class="mb-0 text-primary">Appointment Overview</h5>
                        <p class="small text-muted mb-0">Quick access to recent appointments</p>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table data-table mb-0">
                                <thead>
                                    <tr>
                                        <th>Appointment</th>
                                        <th>Patient</th>
                                        <th>Gender</th>
                                        <th>Doctor</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php                                    
                                    $query = "SELECT * FROM appointment AS a JOIN doctor AS d ON a.docid = d.id LIMIT 3";
                                    $queryExec = mysqli_query($con, $query);
                                    $count = mysqli_num_rows($queryExec);
                                    
                                    if ($count > 0) {
                                        while ($row = mysqli_fetch_assoc($queryExec)) {
                                            echo "<tr>
                                                <td>{$row['appdate']}</td>
                                                <td>{$row['pt_name']}</td>
                                                <td>{$row['pt_gender']}</td>
                                                <td>{$row['name']}</td>
                                            </tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='4' class='text-center py-4'>No appointments found</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer bg-white text-center">
                        <a href="appointment.php" class="btn btn-primary">View All Appointments</a>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6 mb-4">
                <div class="card h-100">
                    <div class="card-header bg-white">
                        <h5 class="mb-0 text-primary">Doctors Overview</h5>
                        <p class="small text-muted mb-0">Quick view of our doctors</p>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table data-table mb-0">
                                <thead>
                                    <tr>
                                        <th>Doctor</th>
                                        <th>Specialty</th>
                                        <th>City</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sqlquery = "SELECT * FROM doctor AS d JOIN docspecialization AS dsp ON d.speciality = dsp.ds_id JOIN city as c ON d.city = c.ct_id LIMIT 3";
                                    $queryExecute = mysqli_query($con, $sqlquery);
                                    $count = mysqli_num_rows($queryExecute);
                                    if ($count > 0) {
                                        while ($row = mysqli_fetch_assoc($queryExecute)) {
                                            echo "<tr>
                                                <td>{$row['name']}</td>
                                                <td>{$row['specialist']}</td>
                                                <td>{$row['city_name']}</td>
                                            </tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='3' class='text-center py-4'>No doctors found</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer bg-white text-center">
                        <a href="doctors.php" class="btn btn-primary">View All Doctors</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mobile-bottom-nav d-lg-none">
        <a href="index.php" class="mobile-nav-item active">
            <i class="fas fa-tachometer-alt"></i>
            Dashboard
        </a>
        <a href="doctors.php" class="mobile-nav-item">
            <i class="fas fa-user-md"></i>
            Doctors
        </a>
        <a href="appointment.php" class="mobile-nav-item">
            <i class="fas fa-calendar-check"></i>
            Appointments
        </a>
        <a href="patient.php" class="mobile-nav-item">
            <i class="fas fa-procedures"></i>
            Patients
        </a>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Highlight active mobile nav item
        document.querySelectorAll('.mobile-nav-item').forEach(item => {
            if(item.href === window.location.href) {
                item.classList.add('active');
            }
        });
    </script>
</body>
</html>