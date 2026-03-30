<?php
session_start();

include 'connection.php';
?>


<header>
	
	<nav class="navbar navbar-expand-lg navigation" style="background-color: rgb(253, 253, 253);" id="navbar">
		<div class="container">
		 	 <a class="navbar-brand" href="index.php">
			  	<img src="images/logo1.png" alt="" class="img-fluid" style="height:100px; width:100px;">
			  </a>

		  	<button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#navbarmain" aria-controls="navbarmain" aria-expanded="false" aria-label="Toggle navigation">
			<span class="icofont-navigation-menu"></span>
		  </button>
	  
		  <div class="collapse navbar-collapse" id="navbarmain">
			<ul class="navbar-nav">
			  <li class="nav-item active">
				<a class="nav-link" href="index.php">Home</a>
			  </li>
			   <li class="nav-item"><a class="nav-link" href="about.php">About us</a></li>
			    <li class="nav-item"><a class="nav-link" href="service.php">Services</a></li>
			    <li class="nav-item"><a class="nav-link" href="blog.php">Blogs</a></li>


			   
			 

			   
			   <li class="nav-item dropdown">
				<?php 
				if(isset($_SESSION['role']) && $_SESSION['role'] == 'patient' ){?>
				<a class="nav-link dropdown-toggle d-flex align-items-center" id="dropdown05" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    <i class="fa-solid fa-circle-user me-2" style="font-size:25px;"></i>
    <?php echo ucfirst($_SESSION['user_name']);?>
    <i class="icofont-thin-down ms-2"></i>
</a>
				<ul class="dropdown-menu" aria-labelledby="dropdown05">
						<li><a class="dropdown-item" href="patient./index.php">Dashboard</a></li>

						<li><a class="dropdown-item" href="patient/logout_pt.php">Logout</a></li>
					</ul>
				<?php }
				else{
					echo'<a class="nav-link" href="contact.php">Contact</a>';
				}
				?>
			   </li>
			</ul>
		  </div>
		</div>
	</nav>
</header>	
 
