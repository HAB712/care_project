
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
   <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <!-- Icon Font Css -->
  <link rel="stylesheet" href="plugins/icofont/icofont.min.css">
  <!-- Slick Slider  CSS -->
  <link rel="stylesheet" href="plugins/slick-carousel/slick/slick.css">
  <link rel="stylesheet" href="plugins/slick-carousel/slick/slick-theme.css">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css" integrity="sha512-DxV+EoADOkOygM4IR9yXP8Sb2qwgidEmeqAEmDKIOfPRQZOWbXCzLC6vjbZyy0vPisbH2SyW27+ddLVCN+OMzQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <!-- Main Stylesheet -->
   <link rel="stylesheet" href="css/style2.css">
  <link rel="stylesheet" href="css/style.css">
  
  <style>
	    .faq-wrap {
      max-width: 900px;
      margin: 2.5rem auto;
      padding: 1.25rem;
    }
    .faq-card {
      border: 0;
      background: linear-gradient(180deg, rgba(255,255,255,0.85), rgba(250,250,252,0.85));
      box-shadow: 0 6px 22px rgba(18, 38, 63, 0.08);
      border-radius: 12px;
      overflow: hidden;
    }
    .faq-header {
      padding: 1.25rem 1.5rem;
      border-bottom: 1px solid rgba(0,0,0,0.05);
      display: flex;
      gap: .75rem;
      align-items: center;
    }
    .faq-title {
      margin: 0;
      font-size: 1.125rem;
      font-weight: 600;
      color: #0f172a;
    }
    .faq-sub {
      font-size: .875rem;
      color: #6b7280;
    }
    .accordion-button {
      padding: 1rem 1.25rem;
      font-weight: 600;
      color: #0b1220;
    }
    .accordion-body {
      padding: .95rem 1.25rem 1.5rem 1.25rem;
      color: #344054;
      line-height: 1.5;
    }
    @media (max-width: 575.98px) {
      .faq-wrap { margin: 1rem; padding: .75rem; }
      .faq-header { padding: .75rem; }
    }

	
	header, .site-header, .navbar {
    position: relative; /* keep in flow unless intentionally fixed */
    z-index: 1100;
}

/* Banner should sit below header */
.banner, .hero {
    position: relative;
    z-index: 1000;
}

/* Ensure login cards and feature-items appear above banner */
.login-card, .card-login, .feature-item {
    position: relative;
    z-index: 1200;
}

  </style>

</head>

<body id="top">

<?php include "header.php"; ?>
	



<!-- Slider Start -->
<section class="banner">
	<div class="container">
		<div class="row">
			<div class="col-lg-6 col-md-12 col-xl-7">
				<div class="block">
					<div class="divider mb-3"></div>
					<span class="text-uppercase text-sm letter-spacing mt-3" style="color:#091E3E;">Total Health care solution</span>
					<h1 class="mb-3 mt-4" style="color:#091E3E;">Your most trusted health partner</h1>
					
					<p class="mb-4 pr-5" style="color:#091E3E;">Welcome to Care Hospital — delivering compassionate, expert healthcare with advanced technology and a patient-first approach. Your health, our priority.</p>
					
				</div>
			</div>
		</div>
	</div>
</section>

	<?php if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'patient' ){?>
<section class="features">
	<div class="container">
		<div class="row">
			<div class="col-lg-12">
				<div class="feature-block d-lg-flex">
					<div class="feature-item mb-5 mb-lg-0">
						 <div class="login-icon doctor">👨‍⚕️</div>
						<h4 class="mb-3">Doctor Login</h4>
						<p class="mb-4">Medical professionals can access patient records, schedule appointments, and manage treatments.</p>
						<a href="./doctor/login_dt.php" class="btn btn-main btn-round-full">Login as Doctor</a>
					</div>
				
					<div class="feature-item mb-5 mb-lg-0">
						<div class="login-icon patient">👨‍💼</div>
						<h4 class="mb-3">Patient Logins</h4>
						<p class="mb-4">Patients can view their medical records, book appointments, and communicate with healthcare providers.</p>
						<a href="./patient/login_pt.php" class="btn btn-main btn-round-full">Login as Patient</a>
					</div>
				
					<div class="feature-item mb-5 mb-lg-0">
						<div class="login-icon patient">👤</div>
						<h4 class="mb-3">Admin Logins</h4>
						<p class="mb-4">System administrators can access the dashboard to manage users,update,  settings, and system configurations.</p>
						<a href="./admin/login_ad.php" class="btn btn-main btn-round-full">Login as Admin</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<?php }?>


<section class="section about">
	<div class="container">
		<div class="row align-items-center">
			<div class="col-lg-4 col-sm-6">
				<div class="about-img">
					<img src="images/about/img-1.jpg" alt="" class="img-fluid">
					<img src="images/about/img-2.jpg" alt="" class="img-fluid mt-4">
				</div>
			</div>
			<div class="col-lg-4 col-sm-6">
				<div class="about-img mt-4 mt-lg-0">
					<img src="images/about/img-3.jpg" alt="" class="img-fluid">
				</div>
			</div>
			<div class="col-lg-4">
				<div class="about-content pl-4 mt-4 mt-lg-0">
					<h2 class="title-color">Your Health, Our Priority</h2>
					<p class="mt-4 mb-5">We provide comprehensive medical services across multiple specialties, delivered by experienced doctors and compassionate staff.
With state-of-the-art facilities and a patient-centered approach, we’re here to support you at every step of your health journey.
Discover the care you deserve — from routine check-ups to advanced treatments.</p>

					<a href="service.php" class="btn btn-main-2 btn-round-full btn-icon">Services<i class="icofont-simple-right ml-3"></i></a>
				</div>
			</div>
		</div>
	</div>
</section>
<section class="cta-section ">
	<div class="container">
		<div class="cta position-relative">
			<div class="row">
				<div class="col-lg-3 col-md-6 col-sm-6">
					<div class="counter-stat">
						<i class="icofont-doctor"></i>
						<span class="h3">58</span>k
						<p>Happy People</p>
					</div>
				</div>
				<div class="col-lg-3 col-md-6 col-sm-6">
					<div class="counter-stat">
						<i class="icofont-flag"></i>
						<span class="h3">700</span>+
						<p>Surgery Comepleted</p>
					</div>
				</div>
				
				<div class="col-lg-3 col-md-6 col-sm-6">
					<div class="counter-stat">
						<i class="icofont-badge"></i>
						<span class="h3">40</span>+
						<p>Expert Doctors</p>
					</div>
				</div>
				<div class="col-lg-3 col-md-6 col-sm-6">
					<div class="counter-stat">
						<i class="icofont-globe"></i>
						<span class="h3">20</span>
						<p>Worldwide Branch</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<section class="section service gray-bg">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-lg-7 text-center">
				<div class="section-title">
					<h2>Specialty</h2>
					<div class="divider mx-auto my-4"></div>
					<p>Discover our wide range of medical specialties designed to meet your unique health needs.From cardiology to orthopedics, our expert teams provide advanced, personalized care in every department.</p>
				</div>
			</div>
		</div>

		<div class="row">

		<?php 
				$sql1 = 'SELECT * FROM docspecialization';
				$query1 = mysqli_query($con, $sql1);
				if (mysqli_num_rows($query1) > 0) {
                    while ($row = mysqli_fetch_assoc($query1)) {
				?>
			<div class="col-lg-4 col-md-6 col-sm-6">
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
						<img src="doctor/doc_image/noor_20250728_222842.jpg" alt="" class="img-fluid">
					</div>

					<div class="client-info">
						<h4>Eye Specialist</h4>
						<span>Dr. Noor</span>
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

 <section class="faq-wrap">
    <div class="faq-card">
      <div class="faq-header">
        <i class="bi bi-hospital fs-3 text-primary" aria-hidden="true"></i>
        <div>
          <h3 class="faq-title">Frequently Asked Questions</h3>
          <div class="faq-sub">Short answers to common patient questions</div>
        </div>
      </div>

      <div class="p-3">
        <div class="accordion" id="hospitalFaqAccordion">

          <!-- FAQ 1 -->
		   <?php 
           $sqli = 'SELECT * FROM faqs';
		   	$queryi = mysqli_query($con, $sqli);  
			$counti = mysqli_num_rows($queryi);
			if($counti>0){
				while($rowi = mysqli_fetch_assoc($queryi)){
		   ?>
		  <div class="accordion-item">
			<h2 class="accordion-header" id="faqOneHeader<?php echo $rowi['id']; ?>">
			  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqOne<?php echo $rowi['id']; ?>" aria-expanded="false" aria-controls="faqOne<?php echo $rowi['id']; ?>">
				<?php echo $rowi['question']; ?>
			  </button>
			</h2>
			<div id="faqOne<?php echo $rowi['id']; ?>" class="accordion-collapse collapse" aria-labelledby="faqOneHeader<?php echo $rowi['id']; ?>" data-bs-parent="#hospitalFaqAccordion">
			  <div class="accordion-body">
				<?php echo $rowi['answer']; ?>
			  </div>
			</div>
		  </div>
		  <?php 
				}
			}
		   ?>
         


        </div>

        <!-- Small contact CTA -->
        <div class="mt-3 d-flex justify-content-end">
          <a href="contact.php" class="btn btn-outline-primary btn-sm" role="button"><i class="bi bi-telephone me-1"></i>Contact Patient Services</a>
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
   
  <!-- Bootstrap JS Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="plugins/counterup/jquery.easing.js"></script>
    <!-- Slick Slider -->
    <script src="plugins/slick-carousel/slick/slick.min.js"></script>
    <!-- Counterup -->
    <script src="plugins/counterup/jquery.waypoints.min.js"></script>
    
    <script src="plugins/shuffle/shuffle.min.js"></script>
    <script src="plugins/counterup/jquery.counterup.min.js"></script>
    <!-- Google Map -->
    <script src="plugins/google-map/map.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAkeLMlsiwzp6b3Gnaxd86lvakimwGA6UA&callback=initMap"></script>    
    
    <script src="js/script.js"></script>
    <script src="js/contact.js"></script>

	

  </body>
  </php>
   