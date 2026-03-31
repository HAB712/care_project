<?php include 'connection.php';?>
<!DOCTYPE php>
<php lang="zxx">
<head>
  <meta http-equiv="Content-Type" content="text/php; charset=UTF-8">
  <meta name="description" content="Orbitor,business,company,agency,modern,bootstrap4,tech,software">
  <meta name="author" content="themefisher.com">

  <title>Care Hospital</title>

  <!-- Favicon -->
  <link rel="shortcut icon" type="image/x-icon" href="/images/favicon.ico" />

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
<body id="top">

<?php include "header.php"; ?>
	

 <!-- Page Header -->
 <section class="page-title bg-1">
  <div class="overlay"></div>
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="block text-center">
          <span class="text-white">Meet Our Doctors</span>
          <h1 class="text-capitalize mb-5 text-lg">Specialty with their Experts</h1>
        </div>
      </div>
    </div>
  </div>
</section>
<section class="section service gray-bg">
	<div class="container">
		

		<div class="row">

		<?php 
				$sql1 = 'SELECT * FROM docspecialization';
				$query1 = mysqli_query($con, $sql1);
				if (mysqli_num_rows($query1) > 0) {
                    while ($row = mysqli_fetch_assoc($query1)) {
				?>
			<div class="col-lg-6 col-md-6 col-sm-6">
				<div class="service-item mb-4">
					<div class="icon d-flex align-items-center">
						<i class="icofont-laboratory text-lg"></i>
						<h4 class="mt-3 mb-3"><a href="specialty.php?spcid=<?php echo $row['ds_id']; ?>"><?php echo $row['specialist'] ?></a></h4>
					</div>

					<div class="content">
						<p class="mb-4"><?php echo $row['description']?></p>
					</div>
				</div>
			</div>
			<?php 
					}
				}
			?>
	
		</div>
	</div>
</section>

<section class="section testimonial-2 gray-bg">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-lg-7">
				<div class="section-title text-center">
					<h2>We served over 5000+ Patients</h2>
					<div class="divider mx-auto my-4"></div>
					<p>Hear from our patients and their families about their experiences at Care Hospital.
Their stories reflect the compassionate care, trust, and healing we strive to deliver every day.</p>
				</div>
			</div>
		</div>
	</div>

	<div class="container">
		<div class="row align-items-center">
			<div class="col-lg-12 testimonial-wrap-2">
				<div class="testimonial-block style-2  gray-bg">
					<i class="icofont-quote-right"></i>

					<div class="testimonial-thumb">
						<img src="images/team/farah.png" alt="" class="img-fluid">
					</div>

					<div class="client-info ">
						<h4>Cardiologist</h4>
						<span>Dr. Farah</span>
						<p>
						The doctor was incredibly kind, attentive, and made me feel completely at ease.
I truly appreciated the time taken to explain every step of my treatment.
						</p>
					</div>
				</div>

				<div class="testimonial-block style-2  gray-bg">
					<div class="testimonial-thumb">
						<img src="doctor/doc_image/anaiya_20250807_221550.jpg" alt="" class="img-fluid">
					</div>

					<div class="client-info">
						<h4>Eye specialist</h4>
						<span>Dr. Wania</span>
						<p>
						The eye specialist was incredibly thorough and explained everything in a way I could understand. My vision has improved significantly thanks to their expert care.
						</p>
					</div>
					
					<i class="icofont-quote-right"></i>
				</div>

				<div class="testimonial-block style-2  gray-bg">
					<div class="testimonial-thumb">
						<img src="doctor/doc_image/dermad1_20250726_091311.jpg" alt="" class="img-fluid">
					</div>

					<div class="client-info">
						<h4>Neurologist!</h4>
						<span>Dr. Maham</span>
						<p>
						The neurologist was patient, attentive, and incredibly knowledgeable.
They took the time to explain my condition clearly and helped me feel confident in the treatment plan.
						</p>
					</div>
					
					<i class="icofont-quote-right"></i>
				</div>

				<div class="testimonial-block style-2  gray-bg">
					<div class="testimonial-thumb">
						<img src="images/team/3.jpg" alt="" class="img-fluid">
					</div>

					<div class="client-info">
						<h4>Eye Specialist</h4>
						<span>Dr. Kashan</span>
						<p class="mt-4">
						I was nervous about my eye procedure, but the doctor made me feel completely at ease.
Their skill and reassurance gave me full confidence throughout the process.
						</p>
					</div>
					<i class="icofont-quote-right"></i>
				</div>

				<div class="testimonial-block style-2  gray-bg">
					<div class="testimonial-thumb">
						<img src="images/team/1.jpg" alt="" class="img-fluid">
					</div>

					<div class="client-info">
						<h4>Dentist</h4>
						<span>Dr.Umer</span>
						<p>
						The dentist was gentle, professional, and made sure I was comfortable throughout the procedure.
I’m very happy with the results and the care I received.
						</p>
					</div>
					<i class="icofont-quote-right"></i>
				</div>
			</div>
		</div>
	</div>
</section>











<!-- footer Start -->
      
<?php include "footer.php"; ?>

    <!-- 
    Essential Scripts
    =====================================-->

    
    <!-- Main jQuery -->
    <script src="plugins/jquery/jquery.js"></script>
    <!-- Bootstrap 4.3.2 -->
    <script src="plugins/bootstrap/js/popper.js"></script>
    <script src="plugins/bootstrap/js/bootstrap.min.js"></script>
    <script src="plugins/counterup/jquery.easing.js"></script>
    <!-- Slick Slider -->
    <script src="plugins/slick-carousel/slick/slick.min.js"></script>
    <!-- Counterup -->
    <script src="plugins/counterup/jquery.waypoints.min.js"></script>
    
    <script src="plugins/shuffle/shuffle.min.js"></script>
    <script src="plugins/counterup/jquery.counterup.min.js"></script>
    <!-- Google Map -->
    <script src="plugins/google-map/map.js"></script>
 
    
    <script src="js/script.js"></script>
    <script src="js/contact.js"></script>

  </body>
  </php>
   
