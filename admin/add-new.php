<?php 
session_start();
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'A'){
    header('location: login_ad.php');
    exit();
}

include("../connection.php");

if(isset($_POST['ins'])){
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $pass = mysqli_real_escape_string($con, $_POST['pass']);
    $phone = mysqli_real_escape_string($con, $_POST['phone']);
    $gender = isset($_POST['gender']) ? mysqli_real_escape_string($con, ucfirst(strtolower($_POST['gender']))) : '';
    $dob = mysqli_real_escape_string($con, $_POST['dob']);
    $cnic = mysqli_real_escape_string($con, $_POST['cnic']);
    $city = mysqli_real_escape_string($con, $_POST['city']);
    $educate = mysqli_real_escape_string($con, $_POST['education']);
    $exp = mysqli_real_escape_string($con, $_POST['exp']);
    $special = mysqli_real_escape_string($con, $_POST['special']);
    $shift = mysqli_real_escape_string($con, $_POST['shift']);
    $clinic = isset($_POST['clinic']) ? implode(',', array_map(function($item) use ($con) { 
        return mysqli_real_escape_string($con, $item); 
    }, $_POST['clinic'])) : '';
    
    $errors = [];

    if (!preg_match('/^[0-9]{13}$/', $cnic)) {
        $errors[] = 'Invalid CNIC format. Please use 13 digits only.';
    }

    if (empty($name) || strlen($name) < 3) {
        $errors[] = "Name must be at least 3 characters long.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    if (strlen($pass) < 6) {
        $errors[] = "Password must be at least 6 characters long.";
    }
    
    if (empty($gender)) {
        $errors[] = "Please select a gender.";
    }
    
    if (empty($clinic)) {
        $errors[] = "Please select at least one clinic address.";
    }


    if (!empty($errors)) {
        $errorMessages = implode("\\n", $errors);
        echo "<script>alert('$errorMessages'); window.history.back();</script>";
        exit();
    }

    $Emailquery = "SELECT * FROM signup WHERE email = '$email'";
    $result = mysqli_query($con, $Emailquery);
    if(!$result) {
        echo "<script>alert('Database error: ".mysqli_error($con)."'); window.history.back();</script>";
        exit();
    }
    if(mysqli_num_rows($result) > 0) {
        echo "<script>alert('Email already exists.'); window.history.back();</script>";
        exit();
    }

   
    $hashpass = password_hash($pass, PASSWORD_BCRYPT);

    $image_name = $_FILES['image']['name'];
    $image_tmp = $_FILES['image']['tmp_name'];
    $img_size = $_FILES['image']['size'];
    $img_error = $_FILES['image']['error'];

    $target_folder = '../doctor/doc_image/';
    $allowed_extension = ['jpeg','jpg','png','jfif'];
    $file_extension = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));

    if($img_size > 2 * 1024 * 1024){
        echo "<script>alert('Image size is too large (max 2MB)'); window.history.back();</script>";
        exit();
    }
    
    if(!in_array($file_extension, $allowed_extension)){
        echo "<script>alert('Only JPG, JPEG, PNG, JFIF files are allowed.'); window.history.back();</script>";
        exit();
    }

    $rename_img = preg_replace('/[^a-zA-Z0-9]/', '_', pathinfo($image_name, PATHINFO_FILENAME));
    $new_name = $rename_img.'_'.date("Ymd_His").'.'.$file_extension;
    $target = $target_folder.$new_name;

    if(move_uploaded_file($image_tmp, $target)){   
        
        $querysign = "INSERT INTO signup (name, email, phone, password, role) 
                     VALUES ('$name', '$email', '$phone', '$hashpass', 'doctor')";
        $resultsign = mysqli_query($con, $querysign);
        
        if(!$resultsign){
            echo "<script>alert('Error in signup".mysqli_error($con)."'); window.history.back();</script>";
            exit();
        }
        
        $query = "INSERT INTO doctor (name, email, phone, gender, DOB, city, CNIC, education, experience, speciality, shift, address, drimage) 
                 VALUES ('$name','$email','$phone','$gender','$dob','$city','$cnic','$educate','$exp','$special','$shift','$clinic','$new_name')";
        $result = mysqli_query($con, $query);

        if($result && $resultsign){
            echo "<script>alert('Doctor added successfully!'); window.location.href='doctors.php';</script>";
        } else {
            echo "<script>alert('Error adding doctor: ".mysqli_error($con)."'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('Error uploading image.'); window.history.back();</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediCare - Doctor Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #0d6efd;
            --secondary-color: #198754;
            --accent-color: #6f42c1;
            --light-bg: #f8f9fa;
            --dark-text: #212529;
            --card-gradient: linear-gradient(135deg, #f0f7ff, #e6f7ff);
        }
        
        body {
            background: linear-gradient(135deg, #e0f7fa, #bbdefb);
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 20px 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--dark-text);
            overflow-x: hidden;
        }
        
        .registration-container {
            max-width: 1000px;
            margin: 0 auto;
            animation: fadeIn 0.8s ease-out;
        }
        
        .registration-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.3s ease;
        }
        
        .registration-card:hover {
            transform: translateY(-5px);
        }
        
        .header-section {
            background: linear-gradient(120deg, var(--primary-color), var(--accent-color));
            color: white;
            padding: 30px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .header-section::before {
            content: "";
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 70%);
            transform: rotate(30deg);
            animation: pulse 8s infinite;
        }
        
        .header-section i {
            font-size: 3.5rem;
            margin-bottom: 20px;
            position: relative;
            z-index: 2;
        }
        
        .form-section {
            padding: 30px;
            background: var(--card-gradient);
        }
        
        .form-control, .form-select {
            border-radius: 12px;
            padding: 14px 20px;
            border: 2px solid #d6d6d6ff;
            transition: all 0.3s;
            font-size: 1rem;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.2);
        }
        
        .input-group-text {
            background: #e9ecef;
            border: 1px solid #c9c9c9ff;
            border-radius: 12px 0 0 12px;
            transition: all 0.3s;
            padding: 0 20px;
        }
        
        .form-control:focus + .input-group-text {
            border-color: var(--primary-color);
            background: var(--primary-color);
            color: white;
        }
        
        .btn-register {
            background: linear-gradient(to right, var(--primary-color), var(--accent-color));
            border: none;
            border-radius: 50px;
            padding: 14px 35px;
            font-weight: 600;
            letter-spacing: 1px;
            transition: all 0.4s;
            position: relative;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            font-size: 1.1rem;
        }
        
        .btn-register:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            background: linear-gradient(to right, #0b5ed7, #5a3dc4);
        }
        
        .btn-register:active {
            transform: translateY(1px);
        }
        
        .btn-register::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: rgba(255, 255, 255, 0.1);
            transform: rotate(30deg);
            transition: all 0.6s;
        }
        
        .btn-register:hover::after {
            transform: rotate(30deg) translate(20%, 20%);
        }
        
        .form-check-input:checked {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .terms-text {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
        }
        
        .terms-text:hover {
            text-decoration: underline;
        }
        
        .login-link {
            color: var(--primary-color);
            font-weight: 500;
        }
        
        .login-link:hover {
            text-decoration: none;
            color: var(--accent-color);
        }
        
        .password-toggle {
            cursor: pointer;
            transition: color 0.3s;
            padding: 0 20px;
        }
        
        .password-toggle:hover {
            color: var(--primary-color);
        }
        
        .gender-options {
            display: flex;
            gap: 20px;
            margin-top: 8px;
        }
        
        .gender-option {
            flex: 1;
            text-align: center;
            padding: 12px;
            border-radius: 12px;
            background: #f8f9fa;
            border: 2px solid #e0e0e0ff;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .gender-option:hover {
            border-color: var(--primary-color);
            background: rgba(13, 110, 253, 0.05);
        }
        
        .gender-option.active {
            background: rgba(13, 110, 253, 0.1);
            border-color: var(--primary-color);
            color: var(--primary-color);
        }
        
        .gender-option i {
            font-size: 1.5rem;
            margin-bottom: 8px;
            display: block;
        }
        
        .clinic-checkbox {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 10px;
        }
        
        .clinic-checkbox .form-check {
            flex: 1 0 45%;
            background: white;
            padding: 15px;
            border-radius: 12px;
            border: 2px solid #d6d6d6ff;
            transition: all 0.3s;
        }
        
        .clinic-checkbox .form-check:hover {
            border-color: var(--primary-color);
            box-shadow: 0 5px 10px rgba(0,0,0,0.05);
        }
        
        .form-check-input {
            margin-top: 0.3em;
            border : 1px solid #bdbdbdff;
        }
        
        .form-label {
            font-weight: 600;
            margin-bottom: 8px;
            color: #495057;
        }
        
        .section-title {
            position: relative;
            padding-bottom: 10px;
            margin: 25px 0 20px;
            font-weight: 700;
            color: var(--primary-color);
        }
        
        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 60px;
            height: 4px;
            background: var(--primary-color);
            border-radius: 2px;
        }
        #lb1, #lb2 , #lb3, #lb4 , #lb5 {
            color: #df0000ff;
        }
        
        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @keyframes pulse {
            0% { transform: rotate(30deg) scale(1); }
            50% { transform: rotate(30deg) scale(1.05); }
            100% { transform: rotate(30deg) scale(1); }
        }
        
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }
        
        .animate-input {
            animation: inputFade 0.5s ease-out;
        }
        
        @keyframes inputFade {
            from { opacity: 0; transform: translateX(-10px); }
            to { opacity: 1; transform: translateX(0); }
        }
        
        .floating-icon {
            position: absolute;
            opacity: 0.1;
            font-size: 8rem;
            z-index: 0;
            animation: float 6s infinite ease-in-out;
        }
        
        .floating-icon:nth-child(1) {
            top: 10%;
            left: 5%;
            animation-delay: 0s;
        }
        
        .floating-icon:nth-child(2) {
            top: 60%;
            right: 5%;
            animation-delay: 1s;
        }
        
        .floating-icon:nth-child(3) {
            bottom: 10%;
            left: 20%;
            animation-delay: 2s;
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .header-section {
                padding: 20px;
            }
            
            .header-section i {
                font-size: 2.5rem;
            }
            
            .form-section {
                padding: 20px;
            }
            
            .gender-options {
                flex-direction: column;
                gap: 10px;
            }
            
            .clinic-checkbox .form-check {
                flex: 1 0 100%;
            }
        }
    </style>
</head>
<body>
    <div class="registration-container">
        <div class="floating-icon"><i class="fas fa-heartbeat"></i></div>
        <div class="floating-icon"><i class="fas fa-stethoscope"></i></div>
        <div class="floating-icon"><i class="fas fa-hospital"></i></div>
        
        <div class="registration-card">
            <div class="row g-0">
                <!-- Header Section -->
                <div class="col-md-4 header-section">
                    <i class="fas fa-user-md"></i>
                    <h2>Add Doctor</h2>
                    <p class="mt-3">Join our network of medical professionals</p>
                    <div class="mt-4">
                        <div class="d-flex align-items-center mb-3">
                            <i class="fas fa-check-circle me-3"></i>
                            <span>Access to thousands of patients</span>
                        </div>
                        <div class="d-flex align-items-center mb-3">
                            <i class="fas fa-check-circle me-3"></i>
                            <span>Modern practice management tools</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-check-circle me-3"></i>
                            <span>Secure patient records</span>
                        </div>
                    </div>
                </div>
                
        
                <div class="col-md-8 form-section">
                    <h3 class="mb-4">Add New Doctor</h3>
                    
                    <form id="registrationForm" action="add-new.php" method="POST" enctype="multipart/form-data">
                     <div class="row">
                           <div class="col-md-6 mb-3 animate-input">
                                <label class="form-label">Name</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="name" name="name" required> 
                                </div>
                                <h6 id="lb1"></h6>
                            </div>
                        
                         <div class="col-md-6 mb-3 animate-input">
                                <label  class="form-label">Email</label>
                                <div class="input-group">
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                                <h6 id="lb3"></h6>
                            </div>
                        
                            <div class="col-md-6 mb-3 animate-input">
                                <label  class="form-label">Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="pas" name="pass" required>                                   
                                </div>
                                 <h6 id="lb4"></h6>
                            </div>

                             <div class="col-md-6 mb-3 animate-input">
                                <label  class="form-label">Phone</label>
                                <div class="input-group">              
                                    <input type="tel" class="form-control" id="pho" name="phone" required>
                                </div>
                                 <h6 id="lb2"></h6>
                            </div>

                                              
                         <h5 class="section-title">Personal Information</h5>
                        
                          <div class="row">
                            <div class="col-md-6 mb-3 animate-input">
                                <label class="form-label">Gender</label>
                                <div class="gender-options">
                                    <div class="gender-option" data-value="male">
                                        <i class="fas fa-mars"></i>
                                        <div>Male</div>
                                        <input type="radio" name="gender" value="Male" class="d-none" required>
                                    </div>
                                    <div class="gender-option" data-value="female">
                                        <i class="fas fa-venus"></i>
                                        <div>Female</div>
                                        <input type="radio" name="gender" value="Female" class="d-none">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6 mb-3 animate-input">
                                <label for="birthDate" class="form-label">Date of Birth</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                    <input type="date" class="form-control" id="birthDate" required name="dob">
                                </div>
                            </div>
                          </div>
                        
                          <div class="row">
                             <div class="col-md-6 mb-3 animate-input">
                                <label class="form-label">CNIC Number</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                                    <input type="text" class="form-control" id="cnic" placeholder="4210158034364" name="cnic" required>
                                </div>
                                <h6 id="lb5"></h6>
                            </div>
                            
                            <div class="col-md-6 mb-3 animate-input">
                                <label for="city" class="form-label">City</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-city"></i></span>
                                    <select class="form-select" id="city" required name="city">
                                       <option value="" disabled selected>Select your City</option>
                                   <?php
								 $query23 = "SELECT ct_id, city_name FROM city";
								 $result12 = mysqli_query($con,$query23);
								 while($row12 = mysqli_fetch_assoc($result12)){
									echo "<option value = '{$row12['ct_id']}'>{$row12['city_name']}</option>";
								 }
								 ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3 animate-input">
                                <label class="form-label">Education</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa-solid fa-graduation-cap"></i></span>
                                    <input type="text" class="form-control"  name="education" required>
                                </div>
                            </div>
                            
                            <div class="col-md-6 mb-3 animate-input">
                              <div class="mb-3 animate-input">
                                <label for="phone" class="form-label">Experience</label>
                               <div class="input-group">
                                <input type="text" class="form-control" id="experiece" required name="exp">
                            </div> 
                        </div>          

                     </div>

                         <div class="col-md-12 mb-3 animate-input">
                             <label for="specialty" class="form-label">Insert Your Passport Size Picture</label>
                             <div class="input-group">
                                  <input type="file" class="form-control" id="image" name="image" accept=".jpg, .jpeg, .png, .jfif" required>
                              </div>
                         </div> 
                                   
                        <h5 class="section-title">Professional Information</h5>
                        
                        <div class="mb-3 animate-input">
                            <label for="specialty" class="form-label">Medical Speciality</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-stethoscope"></i></span>
                                <select class="form-select" id="speciality" required name="special">
                                    <option value="" disabled selected>Select your speciality</option>
                                   <?php
								 $query2 = "select ds_id,specialist from docspecialization";
								 $result1 = mysqli_query($con,$query2);
								 while($row1 = mysqli_fetch_assoc($result1)){
									echo "<option value = '{$row1['ds_id']}'>{$row1['specialist']}</option>";
								 }
								 ?>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3 animate-input">
                            <label for="specialty" class="form-label">Shift</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-clock-rotate-left"></i></span>
                                <select class="form-select" id="specialty" required name="shift">
                                    <option value="" disabled selected>Select your Shift</option>
                                    <option value="Morning">Morning</option>
                                    <option value="Night">Night</option>
                                </select>
                            </div>
                        </div>

                     
                        
                        <div class="mb-3 animate-input">
                            <label class="form-label">Clinic Address</label>
                            <div class="clinic-checkbox">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="clinic1" name='clinic[]' value="Main Clinic">
                                    <label class="form-check-label" for="clinic1">
                                        <i class="fas fa-clinic-medical me-2"></i> Main Clinic
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="clinic2" name='clinic[]' value="Downtown Branch">
                                    <label class="form-check-label" for="clinic2">
                                        <i class="fas fa-clinic-medical me-2"></i> Downtown Branch
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="clinic3" name='clinic[]' value="Westside Hospital">
                                    <label class="form-check-label" for="clinic3">
                                        <i class="fas fa-clinic-medical me-2"></i> Westside Hospital
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="clinic4" name='clinic[]' value="City Health Center">
                                    <label class="form-check-label" for="clinic4">
                                        <i class="fas fa-clinic-medical me-2"></i> City Health Center
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="terms" required>
                                <label class="form-check-label" for="terms">
                                    I agree to the <a href="#" class="terms-text">Terms of Service</a> and <a href="#" class="terms-text">Privacy Policy</a>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="newsletter">
                                <label class="form-check-label" for="newsletter">
                                    Subscribe to our newsletter for medical updates
                                </label>
                            </div>
                        </div>
                        
                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary btn-lg btn-register" name='ins' id="submit">
                                <i class="fas fa-user-plus me-2"></i> Submit
                            </button>
                        </div>   
                    </form>

                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        
       
const nameReg = /^[A-Za-z]{3,50}$/; 
const phoneReg = /^[0][3][0-9]{2}[-][0-9]{7}$/;    
const emailReg = /^[^\s@]+@[^\s@]+\.[^\s@]+$/; 
const passReg = /^(?=.*[@.\-#$%^&*=+?!])[A-Za-z0-9@.\-#$%^&*=+?!]{6,}$/;
const cnicReg = /^[0-9]{13}$/;

const form = document.getElementById("registrationForm");

form.addEventListener("submit", function (event) {
    let name = document.getElementById("name").value.trim();
    let phone = document.getElementById("pho").value.trim();
    let email = document.getElementById("email").value.trim();
    let pass = document.getElementById("pas").value.trim();
    let cnic = document.getElementById("cnic").value.trim();

    let valid = true;


    if (!nameReg.test(name)) {
        document.getElementById("lb1").innerHTML = "No Space in Name";
        valid = false;
    } else {
        document.getElementById("lb1").innerHTML = "";
    }

    if (!phoneReg.test(phone)) {
       document.getElementById("lb2").innerHTML = "Invalid Phone";
        valid = false;
    } else {
        document.getElementById("lb2").innerHTML = "";
    }

    if (!emailReg.test(email)) {
        document.getElementById("lb3").innerHTML = "Invalid email format";
        valid = false;
    } else {
        document.getElementById("lb3").innerHTML = "";
    }

    if (!passReg.test(pass)) {
         document.getElementById("lb4").innerHTML = "Enter Strong Password";
        valid = false;
    } else {
        document.getElementById("lb4").innerHTML = "";
    }

        if (!cnicReg.test(cnic)) {
         document.getElementById("lb5").innerHTML = "Enter Valid cnic";
        valid = false;
    } else {
        document.getElementById("lb5").innerHTML = "";
    }

    if (!valid) {
        event.preventDefault();
    }
});
        
        document.querySelectorAll('.gender-option').forEach(option => {
            option.addEventListener('click', function() {
                document.querySelectorAll('.gender-option').forEach(opt => {
                    opt.classList.remove('active');
                });
                this.classList.add('active');
                const radio = this.querySelector('input[type="radio"]');
                radio.checked = true;
            });
        });
        

        const inputs = document.querySelectorAll('.form-control, .form-select, textarea');
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.parentElement.classList.add('animate-input');
            });
        });
        
   
        const animateElements = document.querySelectorAll('.animate-input');
        animateElements.forEach((el, index) => {
            el.style.animationDelay = `${index * 0.1}s`;
        });
    </script>
</body>
</html>
