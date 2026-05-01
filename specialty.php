<?php
// session_start();
include 'connection.php';?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Care Hospital</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Free HTML Templates" name="keywords">
    <meta content="Free HTML Templates" name="description">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@500;600;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet"> 

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />
    <link href="lib/twentytwenty/twentytwenty.css" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style2.css" rel="stylesheet">

     <!-- bootstrap.min css -->
  <link rel="stylesheet" href="plugins/bootstrap/css/bootstrap.min.css">
  <!-- Icon Font Css -->
  <link rel="stylesheet" href="plugins/icofont/icofont.min.css">
  <!-- Slick Slider  CSS -->
  <link rel="stylesheet" href="plugins/slick-carousel/slick/slick.css">
  <link rel="stylesheet" href="plugins/slick-carousel/slick/slick-theme.css">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css" integrity="sha512-DxV+EoADOkOygM4IR9yXP8Sb2qwgidEmeqAEmDKIOfPRQZOWbXCzLC6vjbZyy0vPisbH2SyW27+ddLVCN+OMzQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <!-- Main Stylesheet -->
  <link rel="stylesheet" href="css/style.css">
</head>

<body>
<style>
#appbtn{
    background-color: #223a66;
}
#appbtn:hover{
    background-color: #E12454;
}
.carousel-caption h5, h1, p{
    color:#223a66 ;
}

</style>


<?php include "header.php"?>
 <!-- Banner TStart -->
    <div class="container-fluid p-0">
     <div id="header-carousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000">
     <div class="carousel-inner">
      <div class="carousel-item active">
      <img class="d-block w-100" src="eye/hospital2.png" alt="Image">
      <div class="carousel-caption d-flex flex-column align-items-center justify-content-center bg-transparent">
        <h5 class="text-uppercase mb-3 animated slideInDown">Keep Your Self Healthy</h5>
        <h1 class="display-1 mb-md-4 animated zoomIn">Take The Best Quality Treatment</h1>
        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Sed accusamus hic optio ab dignissimos nulla animi iure, a officia soluta similique voluptatem ad aliquid provident rem nihil nesciunt, omnis itaque.</p>
      </div>
       </div>

</div>
    <!-- Banner End -->

    <!-- Team Start -->
    <div class="container-fluid py-5">
        <div class="container">
            <div class="row g-5">
                
                <div class="col-lg-4 wow slideInUp" data-wow-delay="0.1s">
                    
                    <div class="section-title bg-light rounded h-100 p-5">
                       
                        <?php
                                if (!isset($_SESSION['specialist'])) {
                               $_SESSION['specialist'] = 'Specialists'; 
                             }
                    
                                if(isset($_GET['spcid'])) {
                                  $spcid = mysqli_real_escape_string($con, $_GET['spcid']);
                                  $sql = "SELECT specialist FROM docspecialization WHERE ds_id = '$spcid' LIMIT 1";
                                 $result = mysqli_query($con, $sql);
                                   if (mysqli_num_rows($result) > 0) {
                               $spec_row = mysqli_fetch_assoc($result);
                                     $_SESSION['specialist'] = $spec_row['specialist'];
                              }
                             }
                       ?>
                        <h5 class="position-relative d-inline-block  text-uppercase" style = "color:#223a66">Our <?php echo htmlspecialchars($_SESSION['specialist']); ?></h5>
                        <h1 class="display-6 mb-4" style = "color:#223a66">Meet Our Certified & Experienced <?php echo htmlspecialchars($_SESSION['specialist']); ?></h1>
                        
                    </div>
                     
                </div>
                
                  <?php 
                     if(isset($_GET['spcid'])){
                          $spcid = mysqli_real_escape_string($con, $_GET['spcid']);
                           $sql = "SELECT * FROM doctor as d 
                           JOIN docspecialization as sp ON d.speciality = sp.ds_id 
                           JOIN city as c ON d.city = c.ct_id
                          WHERE sp.ds_id = '$spcid'";
                
                          $result = mysqli_query($con, $sql);
                          if (mysqli_num_rows($result) > 0) {
                              while ($row = mysqli_fetch_assoc($result)) {
                    ?>
                      
                        <div class="col-lg-4 wow slideInUp" data-wow-delay="0.3s">
                          
                                <div class="team-item">
                                    <div class="position-relative rounded-top" style="z-index: 1;">
                                        <img class="img-fluid rounded-top w-100" src="./doctor/doc_image/<?php echo $row['drimage']; ?>" alt="Image not found" style= "width:100%; height:300px;object-fit:cover;object-position:top;">
                                        <div class="position-absolute top-100 start-50 translate-middle bg-light rounded p-2 d-flex" >
                                            <a class="btn btn-square m-1" href="https://x.com/akuhkampala" target="blank" style="background-color:#223a66"><i class="fab fa-twitter fw-normal text-white"></i></a>
                                            <a class="btn  btn-square m-1" href="https://www.facebook.com/AKUHPakistan" target="blank" style="background-color:#223a66" ><i class="fab fa-facebook-f fw-normal text-white"></i></a>
                                            <a class="btn  btn-square m-1"href="https://www.youtube.com/@AKUHPakistan" target="blank" style="background-color:#223a66"><i class="fab fa-youtube fw-normal text-white"></i></a>
                                            <a class="btn  btn-square m-1" href="https://www.instagram.com/akuhpakistan/" target="blank" style="background-color:#223a66"><i class="fab fa-instagram fw-normal text-white"></i></a>
                                        </div>
                                    </div>
                                    <div class="team-text position-relative bg-light text-center rounded-bottom p-4 pt-5">
                                        <h4 class="mb-2 p-3">DR. <?php echo $row['name']; ?></h4>
                                        <h5 class="mb-2"><?php echo $row['city_name']; ?></h5>
                                        <p class="text-primary mb-0"> </p>
                                        <a id = 'appbtn' href="appointment_form.php?apid=<?php echo $row['id'];?>" class="btn \ py-3 px-5 text-white" >Appointment</a>
                                    </div>
                                </div>
                            </div>
                        <?php
                        }
                    }
                }
              ?>             
         </div>
      </div>
   </div>     

    <a href="#" class="btn btn-lg  btn-lg-square rounded back-to-top" style="background-color:#E12454;"><i class="bi bi-arrow-up text-white"></i></a>			
<!-- footer Start -->
<?php include "footer.php"; ?>



    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/wow/wow.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="lib/tempusdominus/js/moment.min.js"></script>
    <script src="lib/tempusdominus/js/moment-timezone.min.js"></script>
    <script src="lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>
    <script src="lib/twentytwenty/jquery.event.move.js"></script>
    <script src="lib/twentytwenty/jquery.twentytwenty.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
</body>

</html>