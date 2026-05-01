<?php

session_start();
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'A') {
    header('location: login_ad.php');
    exit();
}

include("../connection.php");

if(isset($_GET['delid'])) {
    $city_id = $_GET['delid'];

    $get_doctors = "SELECT id, email FROM doctor WHERE city='$city_id'";
    $doctor_result = mysqli_query($con, $get_doctors);
    

    $all_success = true;
    
  
    while($doctor = mysqli_fetch_assoc($doctor_result)) {
        $doctor_id = $doctor['id'];
        $doctor_email = $doctor['email'];
        

        $delete_appointments = "DELETE FROM appointment WHERE docid='$doctor_id'";
        if(!mysqli_query($con, $delete_appointments)) {
            $all_success = false;
            echo "Error deleting appointments: " . mysqli_error($con);
        }
        

        $delete_signup = "DELETE FROM signup WHERE email='$doctor_email'";
        if(!mysqli_query($con, $delete_signup)) {
            $all_success = false;
            echo "Error deleting signup: " . mysqli_error($con);
        }

        $delete_doctor = "DELETE FROM doctor WHERE id='$doctor_id'";
        if(!mysqli_query($con, $delete_doctor)) {
            $all_success = false;
            echo "Error deleting doctor: " . mysqli_error($con);
        }
    }
 
    $delete_city = "DELETE FROM city WHERE ct_id='$city_id'";
    if(!mysqli_query($con, $delete_city)) {
        $all_success = false;
        echo "Error deleting city: " . mysqli_error($con);
    }
    

    if($all_success) {
        echo "<script>
            alert('City and all related data deleted successfully!');
            window.location.href='addcity.php';
        </script>";
    }
}


if(isset($_GET['spc_id'])) {
    $spc_id = $_GET['spc_id'];
    
    $delete_specialist = "DELETE FROM docspecialization WHERE ds_id = '$spc_id'";
    $qu_del = mysqli_query($con, $delete_specialist);
    if($qu_del) {
        echo "<script>
            alert('Specialist deleted successfully!');
            window.location.href='addcity.php';
        </script>";
    } else {
        echo "Error deleting specialist: " . mysqli_error($con);
    }
}
?>