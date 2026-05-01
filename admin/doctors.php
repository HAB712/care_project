<?php 
session_start();
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'A'){
    header('location: login_ad.php');
    exit();
}

include("../connection.php");


if(isset($_GET['delid'])){
    $delid = mysqli_real_escape_string($con, $_GET['delid']);
    $quer = "SELECT * FROM doctor WHERE id='$delid'";
    $rest = mysqli_query($con, $quer);
    $row = mysqli_fetch_assoc($rest);
    $deleamil = $row['email'];
    
    $query1 = "DELETE FROM signup WHERE email = '$deleamil'";
    $rest1 = mysqli_query($con, $query1);

    $deleteQuery = "DELETE FROM doctor WHERE id='$delid'";
    $rest2 = mysqli_query($con, $deleteQuery);

    $deleteappoint = "DELETE FROM appointment WHERE docid ='$delid'";
    $rest3 = mysqli_query($con, $deleteappoint);

    if($rest1 && $rest2 && $rest3){
        $_SESSION['message'] = "Doctor removed successfully";
    } else {
        $_SESSION['error'] = "Error removing doctor";
    }
    header('Location: doctors.php');
    exit();
}

$searchQuery = "";
if(isset($_POST['search']) && !empty($_POST['search'])){
    $search = mysqli_real_escape_string($con, $_POST['search']);
    $searchQuery = " WHERE (d.name LIKE '%$search%' OR d.email LIKE '%$search%')";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
       <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">   
    <title>Doctors</title>
<style>             
     :root {
            --primary-blue: #0d6efd;
            --blue-hover: #0b5ed7;
            --light-blue: #e7f1ff;
            --primary-red: #dc3545;
            --red-hover: #bb2d3b;
            --sidebar-width: 250px;
        }
            td{
                font-size: 100%;
            }
        @media (max-width: 1185px) {
             td{
                font-size: 80%;
            }
        }
        @media (max-width: 1065px) {
             td{
                font-size: 70%;
            }
        }
         @media (max-width: 1021px) {
             td{
                font-size: 65%;
            }
        }
        @media (max-width: 992px) {
          
            td{
                font-size: 70%;
            }
            
          
        }
        
        /* Animation */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .animate-fade {
            animation: fadeIn 0.5s ease-out;
        }

        /* Custom soft primary button */
 .btn-primary-soft {
  background-color: var(--light-blue);
  border: 1px solid var(--primary-blue);
  color: var(--primary-blue);
  transition: all 0.3s ease;
 }

 .btn-primary-soft:hover {
  background-color: var(--primary-blue);
  color: #fff;
 }

 .dash-body {
  padding: 20px;
  margin-left: 260px;
  transition: margin-left 0.3s ease;
 }

 @media (max-width: 992px) {
  .dash-body {
  padding: 20px;
  margin-left: 0;
 }
 }

 @media (max-width: 768px) {
  .dates{
    display: hidden;
  }
  td{
         font-size: 70%;
     }
 }
        
        .btn {
            padding: 8px 20px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.2s;
        }
        .btn:hover {
            color : white;
        }
        .btn-close {
            position: absolute;
            right: 20px;
            top: 20px;
            background: none;
            border: none;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
        }
        
        .btn-secondary {
            background: #6c757d;
            color: white;
        }
        
        .btn-secondary:hover {
            background: #5a6268;
        }
        
  .btn-outline-danger {
    border: 1px solid #dc3545;
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
   

        <!-- Main content -->
    <div class="dash-body mt-4">
     <div>
        <h2 class="text-center mb-4 dct">Doctor Management</h2>
      </div>

  <div class="row align-items-center mb-3">
    <div class="col-md-2 mb-2">
      <button onclick="history.back()" class="btn btn-primary-soft" style="background-color: #0d6efd; color: white;">
        <i class="bi bi-arrow-left"></i> Back
      </button>
    </div>

    <div class="col-md-6 mb-2">
      <form action="" method="post" class="d-flex gap-2">
        <input type="search" name="search" class="form-control" placeholder="Search Doctor name or Email" list="doctors"
          value="<?php echo isset($_POST['search']) ? htmlspecialchars($_POST['search']) : ''; ?>">
        <button type="submit" class="btn btn-primary">Search</button>
      </form>
    </div>

    <div class="col-md-4 text-md-end text-muted small dates">
      <div>Today's Date</div>
      <div class="fw-bold"><?php date_default_timezone_set('Asia/Karachi'); echo date('Y-m-d'); ?> <button class="btn btn-outline-secondary"><img src="../img/calendar.svg" width="20"></button></div>
    </div>
    
  </div>

  <div class="row justify-content-between mb-3">
    <div class="col-md-6">
      <h5 class="fw-semibold text-dark ms-2">Add New Doctor</h5>
    </div>
    <div class="col-md-6 text-md-end">
      <a href="add-new.php" class="btn btn-primary">
        <i class="bi bi-plus-circle me-1"></i> Add New
      </a>
    </div>
  </div>

  <div class="row mb-3">
    <div class="col-12">
      <h6 class="ms-2 text-muted">
        <?php 
          echo isset($_POST['search']) && !empty($_POST['search']) 
              ? "Search Results for '".htmlspecialchars($_POST['search'])."'" 
              : "All Doctors";
        ?>
      </h6>
    </div>
  </div>

  <div class="row">
    <div class="col-12 table-responsive">
      <table class="table table-hover align-middle">
        <thead class="table-light">
          <tr>
            <th>Doctor Name</th>
            <th>Email</th>
            <th>Specialties</th>
            <th>Shift</th>
            <th class='gen'>Gender</th>
            <th class='gen'>Clinic Address</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php 
          $sql = "SELECT * FROM doctor as d JOIN docspecialization as dc ON d.speciality = dc.ds_id $searchQuery";
          $query = mysqli_query($con, $sql);
          $count = mysqli_num_rows($query);
          if($count > 0){
            while($row = mysqli_fetch_assoc($query)){
              $id = $row['id'];
              $name = $row['name'];
              $email = $row['email'];
              $gender = $row['gender'];
              $specialty = $row['speciality'];
              $shift = $row['shift']; 
              $address = $row['address'];
              $specialist = $row['specialist'];

              echo "<tr>
                      <td>Dr. $name</td>
                      <td>$email</td>
                      <td>$specialist</td>
                      <td>$shift</td>
                      <td class='gen'>$gender</td>
                      <td class='gen'>$address</td>
                      <td>
                        <a href='view.php?viewid=$id' class='btn btn-sm btn-outline-primary view-btn' data-id='$id'>
                          <i class='bi bi-eye'></i>
                        </a>
                        <a href='doctors.php?delid=$id' class='btn btn-sm btn-danger' onclick='return confirm(\"Are you sure?\")'>
                          <i class='bi bi-trash'></i>
                        </a>
                      </td>
                    </tr>";
            }
          } else {
            echo "<tr><td colspan='7' class='text-center text-muted'>No doctors found</td></tr>";
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>