<?php 
session_start();
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'A'){
    header('location: login_ad.php');
    exit();
}

include("../connection.php");

if(isset($_POST['ins'])){
    // Escape all inputs
    $name = mysqli_real_escape_string($con, trim($_POST['name']));
    $email = mysqli_real_escape_string($con, trim($_POST['email']));
    $password = $_POST['pass'];
    $phone = mysqli_real_escape_string($con, trim($_POST['phone']));
    
    $errors = [];

    if(!preg_match('/^[A-Za-z]{3,50}$/', $name)){
        $errors[] = 'Name must be 3-50 characters with letters and no spaces';
    }

    if(!preg_match('/^[0][3][0-9]{2}[-][0-9]{7}$/', $phone)){
        $errors[] = 'Phone number must be in the format 0302-xxxxxxx';
    }

    // Password validation
    if(!preg_match('/^(?=.*[@.\-#$%^&*=+?!])[A-Za-z0-9@.\-#$%^&*=+?!]{6,}$/', $password)){
        $errors[] = 'Password must be at least 6 characters with at least one special character';
    }

    // Email validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }
    
    // Check if email already exists
    $emailQuery = "SELECT * FROM signup WHERE email = '$email'";
    $emailResult = mysqli_query($con, $emailQuery);
    if(!$emailResult) {
        echo "<script>alert('Database error: ".mysqli_error($con)."'); window.history.back();</script>";
        exit();
    }
    if(mysqli_num_rows($emailResult) > 0) {
        $errors[] = 'Email already exists.';
    }


    if (!empty($errors)) {
        $errorMessages = implode("\\n", $errors);
        echo "<script>alert('$errorMessages'); window.history.back();</script>";
        exit();
    }


    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    if (!$hashed_password) {
        echo "<script>alert('Password hashing failed'); window.history.back();</script>";
        exit();
    }

    $query = "INSERT INTO signup (name, email, password, phone, role) 
              VALUES ('$name', '$email', '$hashed_password', '$phone', 'A')";
    
    $result = mysqli_query($con, $query);
    
    if($result){
        echo "<script>alert('Admin Added Successfully'); window.location.href= 'index.php';</script>";
    } else {
        echo "<script>alert('Admin Add Failed: ".mysqli_error($con)."'); window.history.back();</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add City and Specialization</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-blue: #2563eb;
            --dark-blue: #1d4ed8;
            --light-blue: #dbeafe;
            --lighter-blue: #eff6ff;
            --accent-blue: #3b82f6;
            --text-dark: #1e293b;
            --text-light: #64748b;
            --border-color: #e2e8f0;
            --success-green: #10b981;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8fafc;
            color:#1e293b;
            line-height: 1.6;
        }
        
        .dash-body {
            padding: 2rem;
            margin-left: var(--sidebar-width);
            transition: all 0.3s ease;
        }
        
        .card {
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
            border: none;
            overflow: hidden;
            background-color: white;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(37, 99, 235, 0.1);
        }
        
        .card-header {
            background: linear-gradient(135deg, #2563eb, #2563eb);
            color: white;
            padding: 10px;
            border-bottom: none;
            text-align: center;
        }
        
        .card-header h3 {
            font-weight: 600;
            margin: 0;
             color: white;
             padding: 10px;
            letter-spacing: 0.5px;
        }
        
        .card-body {
            padding: 2.5rem;
        }
        
        .form-label {
            font-weight: 500;
            /* color: #2563eb; */
            margin-bottom: 0.5rem;
        }
        
        .form-control {
            border: 1px solid #2563eb;
            border-radius: 8px;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 0.25rem rgba(37, 99, 235, 0.15);
        }
        
        .input-group-text {
            border: 1px solid #e2e8f0;
            border-right: none;
        }
        
        .input-group .form-control {
            border-left: none;
        }
        
        .password-toggle {
            cursor: pointer;
            background-color: #dbeafe;
            color: #2563eb;
            border: 1px solid #e2e8f0;
            border-left: none;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            border: none;
            padding: 0.75rem 2rem;
            font-weight: 500;
            letter-spacing: 0.5px;
            border-radius: 8px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(37, 99, 235, 0.1);
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(37, 99, 235, 0.15);
            background: linear-gradient(135deg, #1d4ed8, #2563eb);
        }
        
        .form-text {
            color: #64748b;
            font-size: 0.85rem;
            margin-top: 0.5rem;
        }
        
        /* Floating label effect */
        .form-floating label {
            color: #64748b;
        }
        
        /* Success message */
        .alert-success {
            background-color: rgba(16, 185, 129, 0.1);
            border-color: rgba(16, 185, 129, 0.2);
            color: #10b981;
        }
        
        @media (max-width: 992px) {
            .dash-body {
                margin-left: 0;
                padding-bottom: 80px;
            }
            
            .card-body {
                padding: 1.5rem;
            }
        }
        
        @media (max-width: 576px) {
            .mobile-hidden {
                display: none;
            }
            
            .card-header {
                padding: 1.25rem;
            }
            
            .card-body {
                padding: 1.25rem;
            }
        }
        
        /* Animation */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .animate-fade {
            animation: fadeIn 0.5s ease-out;
        }
        
        /* Custom checkbox */
        .form-check-input:checked {
            background-color: #2563eb;
            border-color: #2563eb;
        }
    </style>
</head>
<body>
    <?php include 'sidebar.php'; ?>

    <!-- Main Content -->
    <div class="dash-body animate-fade">
        <div class="container py-4">
            <div class="row justify-content-center">
                <div class="col-lg-8 col-md-10">
                    <div class="card">
                        <div class="card-header">
                            <h3><i class="fas fa-user-shield me-2"></i> Add New Administrator</h3>
                        </div>
                        <div class="card-body">
                            <form id="adminAddForm" class="needs-validation" novalidate action="add_admin.php" method="post">
                                <div class="mb-4">
                                    <label for="name" class="form-label">Full Name</label>
                                    <div class="input-group">
                                        <span class="input-group-text" style=" background-color: #c2dafaff; color: #005cd4ff;"><i class="fas fa-user-tie"></i></span>
                                        <input type="text" class="form-control" id="name" name="name" placeholder="Enter full name" required>
                                    </div>
                                    <div class="form-text">3-50 characters, letters and no spaces</div>
                                    <div class="invalid-feedback">Please provide a valid name</div>
                                </div>
                                <div class="mb-4">
                                    <label for="phone" class="form-label">Phone Number</label>
                                    <div class="input-group">
                                        <span class="input-group-text" style=" background-color: #c2dafaff;color: #005cd4ff;"><i class="fas fa-mobile-alt"></i></span>
                                        <input type="tel" class="form-control" id="phone" name="phone" placeholder="0345-4526321" required>
                                    </div>
                                    <div class="invalid-feedback">Please provide a valid phone number</div>
                                </div>
                                <div class="mb-4">
                                    <label for="email" class="form-label">Email Address</label>
                                    <div class="input-group">
                                        <span class="input-group-text" style=" background-color: #c2dafaff;color: #005cd4ff;"> <i class="fas fa-envelope"></i></span>
                                        <input type="email" class="form-control" id="email" name="email" placeholder="admin@example.com" required>
                                    </div>
                                    <div class="invalid-feedback">Please provide a valid email address</div>
                                </div>
                                <div class="mb-4">
                                    <label for="password" class="form-label">Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text" style=" background-color: #c2dafaff;color: #005cd4ff;"><i class="fas fa-lock"></i></span>
                                        <input type="password" class="form-control" id="password" name="pass" placeholder="Create a strong password" required>
                                        <span class="input-group-text password-toggle" id="togglePassword" >
                                            <i class="fas fa-eye"></i>
                                        </span>
                                    </div>
                                    <div class="form-text">Minimum 8 characters</div>
                                    <div class="invalid-feedback">Password must meet requirements</div>
                                </div>
                                <div class="mb-4 form-check">
                                    <input type="checkbox" class="form-check-input" id="confirmAdmin" required>
                                    <label class="form-check-label" for="confirmAdmin">Confirm this user should have administrator privileges</label>
                                    <div class="invalid-feedback">You must confirm admin privileges</div>
                                </div>
                                <div class="d-grid gap-2 mt-5">
                                    <button type="submit" class="btn btn-primary btn-lg" name="ins">
                                        <i class="fas fa-user-plus me-2"></i> Create Administrator Account
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        // Password toggle functionality
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const icon = this.querySelector('i');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
        
       
    </script>
</body>
</html>