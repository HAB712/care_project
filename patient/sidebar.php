<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-blue: #0d6efd;
            --blue-hover: #0b5ed7;
            --light-blue: #e7f1ff;
            --primary-red: #dc3545;
            --red-hover: #bb2d3b;
            --sidebar-width: 250px;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            padding-bottom: 70px; /* Space for mobile bottom nav */
        }
        
        /* Sidebar styling - hidden on mobile */
        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            background: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            z-index: 1000;
        }
        
        /* Mobile bottom navigation */
        .mobile-bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: white;
            box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
            display: none;
            z-index: 1000;
        }
        
        .mobile-nav-item {
            padding: 10px 0;
            text-align: center;
            color: #495057;
            text-decoration: none;
            font-size: 12px;
        }
        
        .mobile-nav-item.active {
            color: var(--primary-blue);
        }
        
        .mobile-nav-item i {
            display: block;
            font-size: 20px;
            margin-bottom: 5px;
        }
        
        .profile-img {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
        }
        
        .nav-link {
            color: #495057;
            border-radius: 5px;
            margin: 5px 10px;
            padding: 10px 15px;
        }
        
        .nav-link:hover, .nav-link.active {
            background-color: var(--light-blue);
            color: var(--primary-blue);
        }
        
        .nav-link i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        
        /* Responsive adjustments */
        @media (max-width: 992px) {
            .sidebar {
                display: none;
            }
            
            .mobile-bottom-nav {
                display: flex;
                justify-content: space-around;
            }
        }
        
        /* Animation */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .animate-fade {
            animation: fadeIn 0.5s ease-out;
        }
    </style>
</head>
<body>
    <!-- Sidebar - Hidden on mobile -->
    <div class="sidebar d-none d-lg-block">
        <div class="sidebar-header text-center p-5">
            <img src="../img/user.png" alt="Profile" class="profile-img mb-3">
            <h5 class="mb-1"><?php echo substr($username,0,13)  ?>..</h5>
            <p class="text-muted small"><?php echo substr($email,0,22)  ?>..</p>
            <a href="logout_pt.php" class="btn btn-sm btn-outline-danger w-100 mt-2">Logout</a>
        </div>
        <ul class="nav flex-column mt-3">
            <li class="nav-item">
                <a class="nav-link" href="index.php">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="appointment.php">
                    <i class="fas fa-user-md"></i> My Appointments
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="settings.php">
                    <i class="fas fa-gear"></i> Settings
                </a>
            </li>
           
        </ul>
    </div>

      
    <!-- Mobile Bottom Navigation - Shows only on small screens -->
    <div class="mobile-bottom-nav d-lg-none">
        <a href="index.php" class="mobile-nav-item">
            <i class="fas fa-tachometer-alt"></i>
            Dashboard
        </a>
        <a href="appointment.php" class="mobile-nav-item">
            <i class="fas fa-calendar-check"></i>
            My Appointments
        </a>
        <a href="settings.php" class="mobile-nav-item">
            <i class="fas fa-gear"></i>
             Settings
        </a>
    
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Highlight active mobile nav item
        document.querySelectorAll('.mobile-nav-item').forEach(item => {
            if(item.href === window.location.href) {
                item.classList.add('active');
            }
        });
    </script>
</body>
</html>