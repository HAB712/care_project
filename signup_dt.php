<?php
session_start();
include "connection.php";
if($_SERVER["REQUEST_METHOD"] === "POST"){
    $name = $_POST['name'];
   $phone =  $_POST['phone'];
    $email = $_POST['email'];
   $password = $_POST['password'];
    // $role = $_POST['role'];
    $errors = [];

    if (empty($name) || strlen($name) < 3) {
        $errors[] = "Name must be at least 3 characters long.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    if (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters long.";
    }

    if (!empty($errors)) {
        $error = $errors[0];
        echo "<script>alert('$error'); window.history.back();</script>";
        exit();
    }

    $Emailquery = "SELECT * FROM signup WHERE email = '$email'";
    $res = mysqli_query($con, $Emailquery);

    if (mysqli_num_rows($res) > 0) {
        echo "<script>alert('Email already exists.'); window.history.back();</script>";
    } else {
        $HashedPass = password_hash($password, PASSWORD_BCRYPT);
         $Insquery = "INSERT INTO signup (name, phone,  email,  password, role) VALUES ('$name', '$phone', '$email', '$HashedPass', 'doctor')";
        
        $rs = mysqli_query($con, $Insquery);

        if ($rs) {
          // Set session variables
          $_SESSION["docname"] = $name;
          $_SESSION["docemail"] = $email;
          $_SESSION["docid"] = mysqli_insert_id($con);
          $_SESSION["docphone"] = $phone;

          echo "<script>alert('Registration successful'); window.location.href = './doctor/signup_dt.php';</script>";
      } else {
          echo "<script>alert('Error: Registration failed.'); window.history.back();</script>";
      }

 
    }


}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Medical Signup Card</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <style>
    body, html {
      height: 100%;
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background: url('https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQvZoA6sbvexAtRW8SjnpMRU8Cxte_u4AohiS44ww1HSjNb0pyUs1jmB64BqUkfomSKzKg&usqp=CAU') no-repeat center center fixed;
      background-size: cover;
      position: relative;
    }

    /* Dark overlay on background */
    body::before {
      content: "";
      position: absolute;
      top: 0; left: 0;
      width: 100%; height: 100%;
      background-color: rgba(0, 0, 51, 0.7); /* Dark blue overlay */
      z-index: 0;
    }

    .signup-wrapper {
      position: relative;
      z-index: 1;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      padding: 1rem;
    }

    .signup-card {
      display: flex;
      flex-direction: row;
      background: #fff;
      border-radius: 15px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
      overflow: hidden;
      max-width: 900px;
      width: 100%;
      animation: fadeInUp 1s ease;
    }

    .signup-image {
      flex: 1;
      background: url('https://images.unsplash.com/photo-1638202993928-7267aad84c31?q=80&w=687&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D') no-repeat center;
      background-size: cover;
    }

    .signup-form {
      flex: 1;
      padding: 3rem 2rem;
      background-color: #ffffff;
    }

    h2 {
      color: #007bff;
      text-align: center;
      margin-bottom: 1.5rem;
    }

    .form-control:focus {
      border-color: #007bff;
      box-shadow: 0 0 0 0.2rem rgba(0,123,255,0.25);
    }

    .btn-primary {
      background-color: #007bff;
      border: none;
      transition: 0.3s;
    }

    .btn-primary:hover {
      background-color: #0056b3;
      transform: scale(1.03);
    }

    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(30px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    @media (max-width: 768px) {
      .signup-card {
        flex-direction: column;
      }

      .signup-image {
        height: 200px;
      }
    }
  </style>
</head>
<body>

  <div class="signup-wrapper">
    <div class="signup-card">
      <!-- Left Image Side -->
      <div class="signup-image"></div>

      <!-- Right Form Side -->
      <div class="signup-form">
        <h2>Medical Signup</h2>
        <form action="signup_dt.php" method="POST" id="signupForm">
          <div class="form-floating mb-3">
            <input type="text" class="form-control" id="name" name="name" placeholder="Your Name" required>
            <h6 id="lb1"></h6>
            <label for="name">Full Name</label>
          </div>

          <div class="form-floating mb-3">
            <input type="text" class="form-control" id="phone" name="phone" placeholder="Your Phone No." required>
            <h6 id="lb2"></h6>
            <label for="phone">Phone No.</label>
          </div>

          <div class="form-floating mb-3">
            <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com" required>
            <h6 id="lb3"></h6>
            <label for="email">Email Address</label>
          </div>

          <div class="form-floating mb-3">
            <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
            <h6 id="lb4"></h6>
            <label for="password">Password</label>
          </div>

     

          <button type="submit" name ="submit" class="btn btn-primary w-100 py-2">Sign Up</button>
        </form>
      </div>
    </div>
  </div>


   <script>

const nameReg = /^[A-Za-z]{3,20}$/;  
const phoneReg = /^[0][3][0-9]{2}[-][0-9]{7}$/;  
const emailReg = /^[A-Za-z0-9./+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;  
const passReg = /^(?=.*[@.\-#$%^&*=+?!])[A-Za-z0-9@.\-#$%^&*=+?!]{6,}$/; 



const form = document.getElementById("signupForm");

form.addEventListener("submit", function (event) {

    let name = document.getElementById("name").value.trim();
    let phone = document.getElementById("phone").value.trim();
    let email = document.getElementById("email").value.trim();
    let pass = document.getElementById("password").value.trim();

    let valid = true;

    // Name validation
    if (!nameReg.test(name)) {
        document.getElementById("lb1").innerHTML = "Invalid Name";
        valid = false;
    } else {
        document.getElementById("lb1").innerHTML = "";
    }

    // Phone validation
    if (!phoneReg.test(phone)) {
        document.getElementById("lb2").innerHTML = "Invalid Phone";
        valid = false;
    } else {
        document.getElementById("lb2").innerHTML = "";
    }

    // Email validation
    if (!emailReg.test(email)) {
        document.getElementById("lb3").innerHTML = "Invalid Email";
        valid = false;
    } else {
        document.getElementById("lb3").innerHTML = "";
    }

    // Password validation
    if (!passReg.test(pass)) {
        document.getElementById("lb4").innerHTML = "Enter Strong Password";
        valid = false;
    } else {
        document.getElementById("lb4").innerHTML = "";
    }

    if (!valid) {
        event.preventDefault();
          }
});
  </script>
</body>
</html>
