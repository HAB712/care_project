   <?php 
 session_start();
    if(!isset($_SESSION['role']) || $_SESSION['role'] != 'A'){
        header('location: login_ad.php');
    }
    
    include("../connection.php");
$filter_date = isset($_POST['sheduledate']) ? $_POST['sheduledate'] : '';
$where_clause = "";

if (!empty($filter_date)) {
    $where_clause = "WHERE a.appdate = '$filter_date'";
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <title>Appointments</title>
    <link rel="stylesheet" href="../assets/css/appointdoc.css">
</head>
<body>


   <?php include 'sidebar.php'; ?>

<!-- Main Content -->
<div class="dash-body">
  <div class="row">
    <div class="col-12">
      <div class="d-flex justify-content-around align-items-center">
        <button onclick="window.history.back()" class="btn btn-primary">Back</button>
        <h2 class="ap">Appointment Manager</h2>
        <div class="dt">
          <p class="mb-0 small dt">Today's Date</p>
          <h6 class='dt'><?php date_default_timezone_set('Asia/Kolkata'); echo date('Y-m-d'); ?></h6>
        </div>
      </div>

      <form method="post" class="row g-3 align-items-end mt-4">
        <div class="col-auto">
          <label for="date" class="form-label">Date</label>
          <input type="date" name="sheduledate" id="date" value="<?php echo htmlspecialchars($filter_date); ?>" class="form-control">
        </div>
        <div class="col-auto">
          <button type="submit" name="filter" class="btn btn-outline-primary">Filter</button>
        </div>
      </form>
      <div class="table-responsive">
        <form method="post" action="update_status.php">
          <table class="table table-bordered mt-4">
            <thead>
              <tr>
                <th>Patient Name</th>
                <th>Gender</th>
                <th>Doctor</th>
                <th>City</th>
                <th>Date</th>
                <th>Time</th>
                <th>Message</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $query = "SELECT a.*, d.name as doctor_name, c.city_name 
                        FROM appointment a 
                        JOIN doctor d ON a.docid = d.id 
                        JOIN city c ON d.city = c.ct_id
                        $where_clause
                        ORDER BY a.appdate DESC, a.apptime DESC";
              $queryExec = mysqli_query($con, $query);
              if (mysqli_num_rows($queryExec) > 0) {
                while ($row = mysqli_fetch_assoc($queryExec)) {
              ?>
              <tr>
                <td><?php echo ucfirst($row['pt_name']); ?></td>
                <td><?php echo $row['pt_gender']; ?></td>
                <td><?php echo $row['doctor_name']; ?></td>
                <td><?php echo $row['city_name']; ?></td>
                <td><?php echo $row['appdate']; ?></td>
                <td><?php echo $row['apptime']; ?></td>
                <td><?php echo $row['message']; ?></td>
                <td>
                  <select name="status[<?php echo $row['id']; ?>]" class="form-select">
                    <option value="pending" <?php if ($row['status'] == 'pending') echo 'selected'; ?>>Pending</option>
                    <option value="approved" <?php if ($row['status'] == 'approved') echo 'selected'; ?>>Approved</option>
                    <option value="rejected" <?php if ($row['status'] == 'rejected') echo 'selected'; ?>>Rejected</option>
                  </select>
                </td>
                <td>
                  <button type="submit" name="submit" class="btn btn-primary btn-sm">Update</button>
                </td>
              </tr>
              <?php }} else { ?>
              <tr>
                <td colspan="9" class="text-center py-5">
                  <img src="../img/notfound.svg" width="150"><br>
                  <span class="text-muted">No appointments on this date!</span>
                </td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
        </form>
      </div>

    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>