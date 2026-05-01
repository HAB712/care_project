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
	<?php

$status = '';
$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $message = trim($_POST['message'] ?? '');

    // Validation
    $errors = [];

    // Name validation (only letters, spaces, and basic punctuation)
    if (empty($name)) {
        $errors[] = 'Name is required.';
    } elseif (!preg_match("/^[a-zA-ZÀ-ÿ\s'-]{2,50}$/u", $name)) {
        $errors[] = 'Name must be 2-50 characters with only letters, spaces, hyphens, and apostrophes.';
    }

    // Email validation
    if (empty($email)) {
        $errors[] = 'Email is required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Valid email is required.';
    } elseif (strlen($email) > 100) {
        $errors[] = 'Email must be less than 100 characters.';
    }


    if (empty($subject)) {
        $errors[] = 'Subject is required.';
    } elseif (!preg_match("/^[a-zA-Z0-9\s\-.,!?]{5,100}$/", $subject)) {
        $errors[] = 'Subject must be 5-100 characters with only letters, numbers, spaces, and basic punctuation.';
    }


    if (empty($phone)) {
        $errors[] = 'Phone number is required.';
    } elseif (!preg_match("/^\+?[\d\s\-()]{10,20}$/", $phone)) {
        $errors[] = 'Please enter a valid phone number (10-20 digits, may include +, spaces, hyphens, or parentheses).';
    }


    if (empty($message)) {
        $errors[] = 'Message is required.';
    } elseif (strlen($message) < 10) {
        $errors[] = 'Message must be at least 10 characters.';
    } elseif (strlen($message) > 1000) {
        $errors[] = 'Message must be less than 1000 characters.';
    }

    if (empty($errors)) {
        $stmt = $con->prepare("INSERT INTO contact_messages (name, email, subject, phone, message) VALUES (?, ?, ?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("sssss", $name, $email, $subject, $phone, $message);
            if ($stmt->execute()) {
                $status = 'success';
                $msg = '✅ Message sent successfully.';
                
                // Clear form fields after successful submission
                $name = $email = $subject = $phone = $message = '';
            } else {
                $status = 'error';
                $msg = '❌ Failed to send message. Database error: ' . $stmt->error;
            }
            $stmt->close();
        } else {
            $status = 'error';
            $msg = '❌ Database preparation error.';
        }
    } else {
        $status = 'error';
        $msg = '❌ ' . implode('<br>', $errors);
    }

    $con->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contact Us | Healthcare Services</title>
  <style>
    :root {
      --primary-color: #4a6fa5;
      --secondary-color: #166088;
      --accent-color: #4fc1a6;
      --dark-color: #1a2639;
      --light-color: #f8f9fa;
      --error-color: #e63946;
      --success-color: #2a9d8f;
    }
    
  
    /* Contact Section */
    .contact-section {
      padding: 80px 0;
    }
    
    .section-title {
      text-align: center;
      margin-bottom: 50px;
    }
    
    .section-title h2 {
      font-size: 2.2rem;
      color: var(--dark-color);
      margin-bottom: 15px;
      position: relative;
      display: inline-block;
    }
    
    .section-title h2::after {
      content: '';
      position: absolute;
      bottom: -10px;
      left: 50%;
      transform: translateX(-50%);
      width: 80px;
      height: 3px;
      background: var(--accent-color);
      border-radius: 3px;
    }
    
    .section-title p {
      color: #666;
      font-size: 1.1rem;
      max-width: 700px;
      margin: 0 auto;
    }
    
    /* Contact Grid */
    .contact-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 30px;
      margin-bottom: 60px;
    }
    
    .contact-card {
      background: white;
      border-radius: 10px;
      padding: 30px;
      text-align: center;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .contact-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
    }
    
    .contact-icon {
      width: 70px;
      height: 70px;
      background: linear-gradient(135deg, var(--accent-color), var(--primary-color));
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 20px;
      color: white;
      font-size: 1.8rem;
    }
    
    .contact-card h3 {
      font-size: 1.4rem;
      color: var(--dark-color);
      margin-bottom: 15px;
    }
    
    .contact-card p {
      color: #666;
      font-size: 1rem;
    }
    
    /* Contact Form */
    .contact-form-container {
      background: white;
      border-radius: 10px;
      padding: 40px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
      max-width: 800px;
      margin: 0 auto;
    }
    
    .form-group {
      margin-bottom: 25px;
      position: relative;
    }
    
    .form-group label {
      display: block;
      margin-bottom: 8px;
      font-weight: 600;
      color: var(--dark-color);
      font-size: 0.95rem;
    }
    
    .form-control {
      width: 100%;
      padding: 12px 15px;
      border: 1px solid #ddd;
      border-radius: 6px;
      font-size: 1rem;
      transition: all 0.3s ease;
      background-color: #f8f9fa;
    }
    
    .form-control:focus {
      outline: none;
      border-color: var(--accent-color);
      box-shadow: 0 0 0 3px rgba(79, 193, 166, 0.2);
      background-color: white;
    }
    
    textarea.form-control {
      min-height: 150px;
      resize: vertical;
    }
    
    .submit-btn {
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      color: white;
      border: none;
      padding: 14px 30px;
      font-size: 1rem;
      font-weight: 600;
      border-radius: 6px;
      cursor: pointer;
      transition: all 0.3s ease;
      text-transform: uppercase;
      letter-spacing: 1px;
      display: inline-block;
    }
    
    .submit-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(22, 96, 136, 0.3);
    }
    
    /* Status Message */
    .status-message {
      padding: 15px;
      border-radius: 6px;
      margin-bottom: 25px;
      font-weight: 500;
      display: none;
    }
    
    .status-message.success {
      background-color: rgba(42, 157, 143, 0.1);
      color: var(--success-color);
      border-left: 4px solid var(--success-color);
      display: block;
    }
    
    .status-message.error {
      background-color: rgba(230, 57, 70, 0.1);
      color: var(--error-color);
      border-left: 4px solid var(--error-color);
      display: block;
    }
    
    /* Map Section */
    .map-container {
      height: 400px;
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
      margin-top: 60px;
      position: relative;
    }
    
    .map-container iframe {
      width: 100%;
      height: 100%;
      border: none;
      filter: grayscale(50%) contrast(110%) brightness(95%);
    }
    
        
    /* Responsive Adjustments */
    @media (max-width: 768px) {
      .page-header h1 {
        font-size: 2.2rem;
      }
      
      .section-title h2 {
        font-size: 1.8rem;
      }
      
      .contact-form-container {
        padding: 30px 20px;
      }
    }
  </style>
</head>
<body>

  <!-- Page Header -->
 <section class="page-title bg-1">
  <div class="overlay"></div>
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="block text-center">
          <span class="text-white">Get in touch with us</span>
          <h1 class="text-capitalize mb-5 text-lg">Contact</h1>

          <!-- <ul class="list-inline breadcumb-nav">
            <li class="list-inline-item"><a href="index.html" class="text-white">Home</a></li>
            <li class="list-inline-item"><span class="text-white">/</span></li>
            <li class="list-inline-item"><a href="#" class="text-white-50">News details</a></li>
          </ul> -->
        </div>
      </div>
    </div>
  </div>
</section>

  
  <!-- Contact Section -->
 <!-- Contact Form -->
<div class="contact-form-container" style="margin-top:50px; margin-bottom:50px;">
    <?php if ($status): ?>
        <div class="status-message <?php echo $status; ?>">
            <?php echo $msg; ?>
        </div>
    <?php endif; ?>
    <h1 class="head" style="text-align:center; color:#223A66;">Contact Form</h1>
    <form method="post" action="">
        <div class="form-group">
            <label for="name">Full Name</label>
            <input type="text" id="name" name="name" class="form-control" 
                   value="<?php echo htmlspecialchars($name ?? ''); ?>" required>
            <small class="hint">Letters, spaces, hyphens only (2-50 characters)</small>
        </div>
        
        <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" class="form-control" 
                   value="<?php echo htmlspecialchars($email ?? ''); ?>" required>
        </div>
        
        <div class="form-group">
            <label for="subject">Subject</label>
            <input type="text" id="subject" name="subject" class="form-control" 
                   value="<?php echo htmlspecialchars($subject ?? ''); ?>" required>
            <small class="hint">5-100 characters with basic punctuation</small>
        </div>
        
        <div class="form-group">
            <label for="phone">Phone Number</label>
            <input type="tel" id="phone" name="phone" class="form-control" 
                   value="<?php echo htmlspecialchars($phone ?? ''); ?>" required>
            <small class="hint">Format: +1 (123) 456-7890 or 1234567890</small>
        </div>
        
        <div class="form-group">
            <label for="message">Your Message</label>
            <textarea id="message" name="message" class="form-control" required><?php 
                echo htmlspecialchars($message ?? ''); 
            ?></textarea>
            <small class="hint">10-1000 characters</small>
        </div>
        
        <button type="submit" class="submit-btn">Send Message</button>
    </form>
</div>
  
 <!-- footer Start -->
<?php include "footer.php"?>

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
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAkeLMlsiwzp6b3Gnaxd86lvakimwGA6UA&callback=initMap"></script>    
    
    <script src="js/script.js"></script>
    <script src="js/contact.js"></script>

  </body>
  </php>
   