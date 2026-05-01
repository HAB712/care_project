<?php
session_start();

if(!isset($_SESSION['role']) || $_SESSION['role'] != 'patient') {
    header('location: patient/login_pt.php');
    exit();
}

if(!isset($_GET['apid'])) {
    header('location: index.php');
    exit();
}

include "connection.php";
$email = $_SESSION["user_email"];
$errors = [];

// Get doctor information
if(isset($_GET['apid'])){
    $idget = $_GET['apid'];
    $teen = "SELECT * FROM doctor as d join docspecialization as dsp on d.speciality = dsp.ds_id where d.id=$idget";
    $teenx = mysqli_query($con, $teen);
    $counti = mysqli_num_rows($teenx);
    if($counti > 0){
        $row1 = mysqli_fetch_assoc($teenx);
    } else {
        header('location: index.php');
        exit();
    }
}

if(isset($_POST['ins'])){
    // Get form data
    $docid = isset($_POST['doctor_id']) ? (int)$_POST['doctor_id'] : 0;
    $firstName = mysqli_real_escape_string($con, $_POST['firstName']);
    $lastName = mysqli_real_escape_string($con, $_POST['lastName']);
    $fullName = $firstName ." ". $lastName;
    $dob = $_POST['dob'];
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $gender = mysqli_real_escape_string($con, $_POST['gender']);
    $sptid = isset($_POST['speid']) ? (int)$_POST['speid'] : 0;
    $phone = mysqli_real_escape_string($con, $_POST['phone']);
    $address = mysqli_real_escape_string($con, $_POST['address']);
    $country = mysqli_real_escape_string($con, $_POST['country']);
    $appdate = $_POST['appdate'];
    $apptime = $_POST['apptime'];
    $message = mysqli_real_escape_string($con, $_POST['message']);
    
    // VALIDATION CHECKS
    
  
    $today = date('Y-m-d');
    if($appdate < $today) {
        $errors[] = "Cannot book appointment for past dates. Please select a future date.";
    }
    

    $check_patient_appointment = "SELECT COUNT(*) as patient_count FROM appointment 
                                  WHERE pt_email = '$email' AND appdate = '$appdate' AND status != 'cancelled'";
    $result_patient = mysqli_query($con, $check_patient_appointment);
    $patient_data = mysqli_fetch_assoc($result_patient);
    
    if($patient_data['patient_count'] > 0) {
        $errors[] = "You already have an appointment on " . date('M d, Y', strtotime($appdate)) . ". Please choose a different date.";
    }

    $check_daily_limit = "SELECT COUNT(*) as daily_count FROM appointment 
                          WHERE docid = '$docid' AND appdate = '$appdate' AND status != 'cancelled'";
    $result_daily = mysqli_query($con, $check_daily_limit);
    $daily_data = mysqli_fetch_assoc($result_daily);
    
    if($daily_data['daily_count'] >= 10) {
        $errors[] = "Doctor has reached the maximum appointments (10) for " . date('M d, Y', strtotime($appdate)) . ". Please choose another date.";
    }
    

    $check_time_slot = "SELECT COUNT(*) as slot_count FROM appointment 
                        WHERE docid = '$docid' AND appdate = '$appdate' AND apptime = '$apptime' AND status != 'cancelled'";
    $result_slot = mysqli_query($con, $check_time_slot);
    $slot_data = mysqli_fetch_assoc($result_slot);
    
    if($slot_data['slot_count'] > 0) {
        $errors[] = "The selected time slot is already booked. Please choose another time.";
    }
    

    if(empty($errors)) {
        $query = "INSERT INTO appointment(specialistid, docid, pt_name, dob, pt_gender, pt_email, phone, 
                  pt_address, country, appdate, apptime, message, status) 
                  VALUES ($sptid, $docid, '$fullName', '$dob', '$gender', '$email', '$phone',
                  '$address', '$country', '$appdate', '$apptime', '$message', 'pending')";
        
        $queryExec = mysqli_query($con, $query);

        if($queryExec){
            echo "<script>
                    alert('Appointment Booked Successfully!');
                    window.location.href = 'index.php';
                  </script>";
            exit();
        } else {
            $errors[] = "Error: " . mysqli_error($con);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Appointment - Care Hospital</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #007bff;
            --primary-dark: #0056b3;
            --primary-light: #cce5ff;
            --secondary: #6c757d;
            --light-bg: #f8f9fa;
            --card-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            --success: #28a745;
        }
        
        body {
            background: linear-gradient(135deg, #e8f5e9, #f1f8e9);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 20px;
        }
        
        .form-container {
            max-width: 800px;
            margin: 40px auto;
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: var(--card-shadow);
        }
        
        .form-header {
            background: linear-gradient(90deg, var(--primary), var(--primary-dark));
            color: white;
            padding: 25px 30px;
            position: relative;
            overflow: hidden;
        }
        
        .form-title {
            font-weight: 700;
            margin-bottom: 5px;
        }
        
        .form-subtitle {
            font-weight: 300;
            opacity: 0.9;
        }
        
        .form-body {
            padding: 30px;
        }
        
        .input-group {
            position: relative;
            margin-bottom: 25px;
        }
        
        .form-label {
            position: absolute;
            top: 12px;
            left: 15px;
            color: var(--secondary);
            transition: all 0.3s ease;
            pointer-events: none;
            background: white;
            padding: 0 5px;
        }
        
        .form-control {
            height: 50px;
            padding-top: 18px;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.2);
        }
        
        .form-control:focus + .form-label,
        .form-control:not(:placeholder-shown) + .form-label {
            top: -10px;
            font-size: 0.85rem;
            color: var(--primary);
            background: white;
        }
        
        .gender-options {
            display: flex;
            gap: 15px;
            margin-top: 10px;
        }
        
        .gender-option {
            flex: 1;
            text-align: center;
            padding: 15px;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .gender-option:hover {
            border-color: var(--primary);
            background-color: var(--primary-light);
        }
        
        .gender-option.active {
            border-color: var(--primary);
            background-color: var(--primary-light);
        }
        
        .gender-option i {
            font-size: 1.5rem;
            margin-bottom: 8px;
            color: var(--primary);
        }
        
        .progress-container {
            margin-bottom: 30px;
        }
        
        .progress {
            height: 8px;
            border-radius: 4px;
        }
        
        .progress-bar {
            background: linear-gradient(90deg, var(--primary), var(--primary-dark));
            transition: width 0.5s ease;
        }
        
        .step {
            display: none;
        }
        
        .step.active {
            display: block;
        }
        
        .form-footer {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e9ecef;
        }
        
        .btn-primary {
            background: linear-gradient(90deg, var(--primary), var(--primary-dark));
            border: none;
            padding: 12px 30px;
            font-weight: 600;
            border-radius: 50px;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 123, 255, 0.3);
        }
        
        .alert {
            border-radius: 10px;
            margin-bottom: 20px;
        }
        
        .card {
            border-radius: 15px;
            overflow: hidden;
            border: none;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            margin-bottom: 20px;
        }
        
        .review-item {
            padding: 12px 0;
            border-bottom: 1px solid #eee;
        }
        
        .review-item:last-child {
            border-bottom: none;
        }
        
        .step-indicator.active {
            color: var(--primary);
            font-weight: 600;
        }
        
        .form-note {
            background: var(--light-bg);
            border-radius: 10px;
            padding: 15px;
            font-size: 0.9rem;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <div class="form-header">
            <h1 class="form-title"><i class="fas fa-calendar-check me-2"></i>Book Appointment</h1>
            <p class="form-subtitle">Schedule your visit with our healthcare professionals</p>
        </div>
        
        <!-- Progress Bar -->
        <div class="progress-container p-3 bg-light">
            <div class="d-flex justify-content-between mb-2">
                <span class="step-indicator active">Personal Info</span>
                <span class="step-indicator">Appointment Details</span>
                <span class="step-indicator">Review & Confirm</span>
            </div>
            <div class="progress">
                <div class="progress-bar" style="width: 33%"></div>
            </div>
        </div>
        
        <div class="form-body">
            <!-- Display Errors -->
            <?php if(!empty($errors)): ?>
                <div class="alert alert-danger">
                    <h6><i class="fas fa-exclamation-triangle me-2"></i>Please fix the following issues:</h6>
                    <ul class="mb-0">
                        <?php foreach($errors as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form id="patientForm" action="appointment_form.php?apid=<?php echo $idget; ?>" method="POST">
                <!-- Hidden Fields -->
                <input type="hidden" name="doctor_id" value="<?php echo $idget; ?>">
                <input type="hidden" name="speid" value="<?php echo $row1['ds_id']; ?>">
                <input type="hidden" name="gender" id="genderInput" value="Male">
                <input type="hidden" name="email" value="<?php echo $_SESSION['user_email']; ?>">

                <!-- Step 1: Personal Information -->
                <div class="step active" id="step1">
                    <h4 class="mb-4"><i class="fas fa-user me-2"></i>Personal Information</h4>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <input type="text" class="form-control" id="firstName" placeholder=" " required name="firstName"
                                       value="<?php echo isset($_POST['firstName']) ? htmlspecialchars($_POST['firstName']) : ''; ?>">
                                <label for="firstName" class="form-label">First Name</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group">
                                <input type="text" class="form-control" id="lastName" placeholder=" " required name="lastName"
                                       value="<?php echo isset($_POST['lastName']) ? htmlspecialchars($_POST['lastName']) : ''; ?>">
                                <label for="lastName" class="form-label">Last Name</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <input type="date" class="form-control" id="dob" placeholder=" " required name="dob"
                                       value="<?php echo isset($_POST['dob']) ? htmlspecialchars($_POST['dob']) : ''; ?>"
                                       max="<?php echo date('Y-m-d'); ?>">
                                <label for="dob" class="form-label">Date of Birth</label>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                               
                                <div class="gender-options">
                                    <div class="gender-option active" data-gender="Male">
                                        <i class="fas fa-male"></i>
                                        <div>Male</div>
                                    </div>
                                    <div class="gender-option" data-gender="Female">
                                        <i class="fas fa-female"></i>
                                        <div>Female</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <div class="form-control bg-light"><?php echo htmlspecialchars($_SESSION['user_email']); ?></div>
                                <label class="form-label">Email</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group">
                                <input type="tel" class="form-control" id="phone" required name="phone" placeholder=" "
                                       value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>">
                                <label for="phone" class="form-label">Phone Number</label>
                            </div>
                        </div>
                    </div>

                    <div class="input-group">
                        <input type="text" class="form-control" id="address" placeholder=" " required name="address"
                               value="<?php echo isset($_POST['address']) ? htmlspecialchars($_POST['address']) : ''; ?>">
                        <label for="address" class="form-label">Street Address</label>
                    </div>

                    <div class="input-group">
                        <input type="text" class="form-control" id="country" required name="country" placeholder=" "
                               value="<?php echo isset($_POST['country']) ? htmlspecialchars($_POST['country']) : ''; ?>">
                        <label for="country" class="form-label">Country</label>
                    </div>
                </div>
                
                <!-- Step 2: Appointment Details -->
                <div class="step" id="step2">
                    <h4 class="mb-4"><i class="fas fa-calendar-alt me-2"></i>Appointment Details</h4>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <div class="form-control bg-light"><?php echo htmlspecialchars($row1['specialist']); ?></div>
                                <label class="form-label">Specialization</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group">
                                <div class="form-control bg-light"><?php echo htmlspecialchars($row1['name']); ?></div>
                                <label class="form-label">Doctor</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <input type="date" class="form-control" id="appdate" placeholder=" " name="appdate" required
                                       value="<?php echo isset($_POST['appdate']) ? htmlspecialchars($_POST['appdate']) : ''; ?>"
                                       min="<?php echo date('Y-m-d'); ?>">
                                <label for="appdate" class="form-label">Appointment Date</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group">
                                <input type="time" class="form-control" id="apptime" placeholder=" " required name="apptime"
                                       value="<?php echo isset($_POST['apptime']) ? htmlspecialchars($_POST['apptime']) : ''; ?>">
                                <label for="apptime" class="form-label">Appointment Time</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="input-group">
                        <textarea class="form-control" id="message" placeholder=" " required name="message" rows="3"><?php echo isset($_POST['message']) ? htmlspecialchars($_POST['message']) : ''; ?></textarea>
                        <label for="message" class="form-label">Reason for Appointment</label>
                    </div>
                </div>
                
                <!-- Step 3: Review and Confirm -->
                <div class="step" id="step3">
                    <h4 class="mb-4"><i class="fas fa-clipboard-check me-2"></i>Review & Confirm</h4>
                    
                    <div class="card">
                        <div class="card-header bg-light">
                            <h5 class="mb-0"><i class="fas fa-user me-2"></i>Personal Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="review-item">
                                <strong>Full Name:</strong> <span id="reviewName"></span>
                            </div>
                            <div class="review-item">
                                <strong>Date of Birth:</strong> <span id="reviewDob"></span>
                            </div>
                            <div class="review-item">
                                <strong>Gender:</strong> <span id="reviewGender">Male</span>
                            </div>
                            <div class="review-item">
                                <strong>Email:</strong> <span id="reviewEmail"><?php echo htmlspecialchars($_SESSION['user_email']); ?></span>
                            </div>
                            <div class="review-item">
                                <strong>Phone:</strong> <span id="reviewPhone"></span>
                            </div>
                            <div class="review-item">
                                <strong>Address:</strong> <span id="reviewAddress"></span>
                            </div>
                            <div class="review-item">
                                <strong>Country:</strong> <span id="reviewCountry"></span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card">
                        <div class="card-header bg-light">
                            <h5 class="mb-0"><i class="fas fa-calendar me-2"></i>Appointment Details</h5>
                        </div>
                        <div class="card-body">
                            <div class="review-item">
                                <strong>Specialist:</strong> <span id="reviewSpecialist"><?php echo htmlspecialchars($row1['specialist']); ?></span>
                            </div>
                            <div class="review-item">
                                <strong>Doctor:</strong> <span id="reviewDoctor"><?php echo htmlspecialchars($row1['name']); ?></span>
                            </div>
                            <div class="review-item">
                                <strong>Appointment Date:</strong> <span id="reviewDate"></span>
                            </div>
                            <div class="review-item">
                                <strong>Appointment Time:</strong> <span id="reviewTime"></span>
                            </div>
                            <div class="review-item">
                                <strong>Reason:</strong> <span id="reviewMessage"></span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-check mt-4">
                        <input class="form-check-input" type="checkbox" id="terms" required>
                        <label class="form-check-label" for="terms">
                            I confirm that all information provided is accurate and I understand the appointment policies.
                        </label>
                    </div>
                </div>
                
                <!-- Form Navigation -->
                <div class="form-footer">
                    <button type="button" class="btn btn-outline-secondary" id="prevBtn">Previous</button>
                    <button type="button" class="btn btn-primary" id="nextBtn">Next Step</button>
                    <button type="submit" class="btn btn-success" id="submitBtn" name="ins" style="display: none;">
                        <i class="fas fa-calendar-check me-2"></i>Confirm Appointment
                    </button>
                </div>
                
                <div class="form-note">
                    <i class="fas fa-lock"></i> Your information is protected and will only be accessible to authorized medical personnel.
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let currentStep = 1;
            const totalSteps = 3;
            
            const prevBtn = document.getElementById('prevBtn');
            const nextBtn = document.getElementById('nextBtn');
            const submitBtn = document.getElementById('submitBtn');
            const progressBar = document.querySelector('.progress-bar');
            const appdateInput = document.getElementById('appdate');
            
            // Set minimum date to today
            const today = new Date().toISOString().split('T')[0];
            appdateInput.min = today;
            
            // Update progress and UI
            function updateProgress() {
                const progress = (currentStep / totalSteps) * 100;
                progressBar.style.width = `${progress}%`;
                
                // Update step indicators
                document.querySelectorAll('.step-indicator').forEach((indicator, index) => {
                    indicator.classList.toggle('active', index < currentStep);
                });
                
                // Update button visibility
                prevBtn.style.display = currentStep === 1 ? 'none' : 'block';
                nextBtn.style.display = currentStep === totalSteps ? 'none' : 'block';
                submitBtn.style.display = currentStep === totalSteps ? 'block' : 'none';
                
                // Populate review section
                if (currentStep === totalSteps) {
                    populateReviewSection();
                }
            }
            
            // Navigate to step
            function goToStep(step) {
                document.querySelectorAll('.step').forEach(el => {
                    el.classList.remove('active');
                });
                document.getElementById(`step${step}`).classList.add('active');
                currentStep = step;
                updateProgress();
            }
            
            // Populate review section
            function populateReviewSection() {
                document.getElementById('reviewName').textContent = 
                    document.getElementById('firstName').value + ' ' + 
                    document.getElementById('lastName').value;
                
                const dob = new Date(document.getElementById('dob').value);
                document.getElementById('reviewDob').textContent = 
                    dob.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
                
                document.getElementById('reviewPhone').textContent = 
                    document.getElementById('phone').value;
                
                document.getElementById('reviewAddress').textContent = 
                    document.getElementById('address').value;
                
                document.getElementById('reviewCountry').textContent = 
                    document.getElementById('country').value;
                
                document.getElementById('reviewDate').textContent = 
                    new Date(document.getElementById('appdate').value).toLocaleDateString('en-US', { 
                        weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' 
                    });
                
                document.getElementById('reviewTime').textContent = 
                    document.getElementById('apptime').value;
                
                document.getElementById('reviewMessage').textContent = 
                    document.getElementById('message').value;
            }
            
            // Gender selection
            document.querySelectorAll('.gender-option').forEach(option => {
                option.addEventListener('click', function() {
                    document.querySelectorAll('.gender-option').forEach(el => {
                        el.classList.remove('active');
                    });
                    this.classList.add('active');
                    const selectedGender = this.getAttribute('data-gender');
                    document.getElementById('genderInput').value = selectedGender;
                    document.getElementById('reviewGender').textContent = selectedGender;
                });
            });
            
            // Date validation
            appdateInput.addEventListener('change', function() {
                const selectedDate = new Date(this.value);
                const today = new Date();
                today.setHours(0, 0, 0, 0);
                
                if (selectedDate < today) {
                    alert('Please select a future date');
                    this.value = '';
                }
            });
            
            // Navigation event handlers
            nextBtn.addEventListener('click', function() {
                // Validate current step
                const currentStepElement = document.getElementById(`step${currentStep}`);
                const inputs = currentStepElement.querySelectorAll('input[required], select[required], textarea[required]');
                let valid = true;
                
                inputs.forEach(input => {
                    if (!input.checkValidity()) {
                        input.reportValidity();
                        valid = false;
                    }
                });
                
                if (valid && currentStep < totalSteps) {
                    goToStep(currentStep + 1);
                }
            });
            
            prevBtn.addEventListener('click', function() {
                if (currentStep > 1) {
                    goToStep(currentStep - 1);
                }
            });
            
            // Form submission validation
            document.getElementById('patientForm').addEventListener('submit', function(e) {
                if (currentStep === totalSteps && !document.getElementById('terms').checked) {
                    e.preventDefault();
                    alert('Please accept the terms and conditions');
                    return;
                }
            });
            
            // Initialize
            updateProgress();
        });
    </script>
</body>
</html>