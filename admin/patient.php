<?php 
 session_start();
    if(!isset($_SESSION['role']) || $_SESSION['role'] != 'A'){
        header('location: login_ad.php');
    }
    
    
    include("../connection.php");

   
$sqlmain = "SELECT specialistid,docid,pt_name,pt_gender,pt_email,a.phone AS appoint_phone, 
                   pt_address,country,appdate,apptime,message,status,city_name, a.id AS appoint_id,d.id AS doctor_id 
                   FROM appointment a JOIN doctor d ON a.docid = d.id join city c ON d.city = c.ct_id";
$search_condition = "";
$search_value = "";


if(isset($_POST['search']) && !empty($_POST['search'])){
    $search_value = mysqli_real_escape_string($con, $_POST['search']); 
    $search_condition = " WHERE city_name LIKE '%$search_value%'";
} 

$sqlmain .= $search_condition;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>Patients</title>
<style>
        /* Add this to your existing <style> section */
.overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 1000;
    justify-content: center;
    align-items: center;
}

.overlay.show {
    display: flex;
}

.popup {
    background-color: white;
    border-radius: 8px;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.2);
    max-width: 600px;
    width: 90%;
    max-height: 90vh;
    overflow-y: auto;
    position: relative;
    animation: transitionIn-Y-bottom 0.5s ease-out;
}

@keyframes transitionIn-Y-bottom {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.close {
    position: absolute;
    top: 15px;
    right: 20px;
    font-size: 28px;
    font-weight: bold;
    color: #aaa;
    cursor: pointer;
    text-decoration: none;
}

.close:hover {
    color: #000;
}

.add-doc-form-container {
    width: 100%;
    padding: 20px;
}

.label-td {
    padding: 15px;
    border-bottom: 1px solid #f0f0f0;
}

.label-td label {
    margin: 0;
    font-weight: 500;
    color: #333;
}
        .popup { animation: transitionIn-Y-bottom 0.5s; }
        .sub-table { animation: transitionIn-Y-bottom 0.5s; }
        .sub-table tbody tr { transition: all 0.3s ease; }
        .sub-table tbody tr:nth-child(even) { background-color: #f8f9fa; }
        .sub-table tbody tr:hover { background-color: #e9f7fe; box-shadow: 0 2px 8px rgba(0,0,0,0.05); }

        .button-container { display: flex; flex-wrap: wrap; gap: 10px; }
        .button { display: flex; align-items: center; gap: 8px; padding: 8px 14px; border-radius: 6px; font-weight: 600; font-size: 14px; text-decoration: none; }
        .delete-btn { background-color: #ff4d4d; color: white; }
        .delete-btn:hover { background-color: #ff1a1a; }
        .view-btn { background-color: #4d79ff; color: white; }
        .view-btn:hover { background-color: #1a53ff; }

        .dash-body { margin-left: 260px; height: 100vh; padding: 20px; }
        @media (max-width: 992px) { .dash-body { margin-left: 0; width: 100%; } }
        @media (max-width: 768px) {
              .dash-body .dt {
              display: none;
            }
         }
       @media (max-width: 264px) {
  .dct{
         font-size: 110%;
     }
}

</style>
</head>
<body>

<?php include 'sidebar.php'; ?>

<div class="dash-body">
      <div>
        <h2 class="text-center p-2 mb-3 dct">Patient Management</h2>
      </div>
    <div class="row align-items-center mb-3">
        <div class="col-md-3 col-6 mb-2">
            <button onclick="window.history.back()" class="btn btn-primary">
                <i class="fa fa-arrow-left me-2"></i> Back
            </button>
        </div>
        <div class="col-md-5 col-12 mb-2">
            <form action="" method="post" class="d-flex">
                <input type="search" name="search" class="form-control me-2"
                    value="<?php echo isset($_POST['search']) ? htmlspecialchars($_POST['search']) : ''; ?>"
                    placeholder="Search Patient by City" list="patient">
                <button type="submit" class="btn btn-primary">Search</button>
            </form>
        </div>
        <div class="col-md-4 col-6 text-md-end text-end mb-2 dt">
            <p class="mb-0 text-muted" style="font-size:14px;">Today's Date</p>
            <p class="fw-bold mb-0 ">
                <?php date_default_timezone_set('Asia/Kolkata'); echo date('Y-m-d'); ?> <button class="btn btn-outline-secondary ">
                <img src="../img/calendar.svg" alt="Calendar" width="20" class="">
            </button>
            </p>
        </div>
    </div>

    <h5 class="mb-3">All Patients</h5>

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle sub-table">
            <thead class="table-light">
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Telephone</th>
                    <th>City</th>
                    <th>Gender</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $resultmain = mysqli_query($con, $sqlmain);
            if(!$resultmain) { die("Query failed: " . mysqli_error($con)); }

            if(mysqli_num_rows($resultmain) > 0){
                while($row = mysqli_fetch_assoc($resultmain)){
                    echo '<tr>
                        <td>'.ucfirst($row['pt_name']).'</td>
                        <td>'.$row['pt_email'].'</td>
                        <td>'.substr($row['appoint_phone'],0,10).'</td>
                        <td>'.ucfirst($row['city_name']).'</td>
                        <td>'.ucfirst($row['pt_gender']).'</td>
                        <td>
                            <div class="button-container">
                                <a href="?action=view&id='.$row['appoint_id'].'" class="button view-btn">
                                    <i class="fa fa-eye"></i> View
                                </a>
                                <a href="delete-appointment.php?delid='.$row['appoint_id'].'" class="button delete-btn">
                                    <i class="fa fa-trash"></i> Delete
                                </a>
                            </div>
                        </td>
                    </tr>';
                }
            } else {
                echo '<tr>
                    <td colspan="6" class="text-center py-5">
                        <img src="../img/notfound.svg" width="100" class="mb-3">
                        <p class="fw-bold">We couldn\'t find anything related to your keywords!</p>
                        <a href="patient.php" class="btn btn-primary-soft">Show all Patients</a>
                    </td>
                </tr>';
            }
            ?>
            </tbody>
        </table>
    </div>
</div>

<?php 
    if(isset($_GET['action']) && $_GET['action'] == 'view' && isset($_GET['id'])){
        $id = mysqli_real_escape_string($con, $_GET["id"]);
        $sqlmains = "SELECT * FROM appointment WHERE id = '$id'";
        $resultmains = mysqli_query($con, $sqlmains);
        
        if($resultmains && mysqli_num_rows($resultmains) > 0) {
            $row = mysqli_fetch_assoc($resultmains);
            $name = $row["pt_name"];
            $email = $row["pt_email"];
            $gender = $row["pt_gender"];
            $dob = $row["dob"];
            $tele = $row["phone"];
            $status = $row["status"];

            echo '
            <div id="popup1" class="overlay show">
                <div class="popup">
                    <a class="close" href="patient.php">&times;</a>
                    <div style="display: flex; justify-content: center;">
                        <table class="sub-table add-doc-form-container" border="0">
                            <tr>
                                <td class="label-td">
                                    <h3 class="text-center mb-3">Patient Details</h3>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td">
                                    <label class="form-label"><strong>Name:</strong> '.htmlspecialchars($name).'</label>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td">
                                    <label class="form-label"><strong>Email:</strong> '.htmlspecialchars($email).'</label>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td">
                                    <label class="form-label"><strong>Gender:</strong> '.htmlspecialchars($gender).'</label>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td">
                                    <label class="form-label"><strong>Telephone:</strong> '.htmlspecialchars($tele).'</label>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td">
                                    <label class="form-label"><strong>Date of Birth:</strong> '.htmlspecialchars($dob).'</label>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td">
                                    <label class="form-label"><strong>Status:</strong> '.htmlspecialchars($status).'</label>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td text-center">
                                    <a href="patient.php" class="btn btn-primary">Close</a>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            ';
        }
    };
?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>