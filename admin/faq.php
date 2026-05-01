<?php
session_start();
include("../connection.php");


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  
    if (isset($_POST['add_faq'])) {
        $question = mysqli_real_escape_string($con, $_POST['question']);
        $answer = mysqli_real_escape_string($con, $_POST['answer']);
        
        $sql = "INSERT INTO faqs (question, answer) VALUES ('$question', '$answer')";
        if (mysqli_query($con, $sql)) {
            $success_msg = "FAQ added successfully!";
            header("Location: faq.php");
            exit();
        } else {
            $error_msg = "Error adding FAQ: " . mysqli_error($con);
        }
    }
    
    if (isset($_POST['update_faq'])) {
        $id = (int)$_POST['faq_id'];
        $question = mysqli_real_escape_string($con, $_POST['question']);
        $answer = mysqli_real_escape_string($con, $_POST['answer']);
        
        $sql = "UPDATE faqs SET question='$question', answer='$answer' WHERE id=$id";
        if (mysqli_query($con, $sql)) {
            $success_msg = "FAQ updated successfully!";
            header("Location: faq.php");
            exit();
        } else {
            $error_msg = "Error updating FAQ: " . mysqli_error($con);
        }
    }
}

if (isset($_GET['delete_id'])) {
    $id = (int)$_GET['delete_id'];
    echo "<script>
            if(confirm('Are you sure you want to delete this FAQ?')) {
                window.location.href = 'faq.php?confirm_delete=$id';
            } else {
                window.location.href = 'faq.php';
            }
          </script>";
    exit();
}

if (isset($_GET['confirm_delete'])) {
    $id = (int)$_GET['confirm_delete'];
    
    $sql = "DELETE FROM faqs WHERE id=$id";
    if (mysqli_query($con, $sql)) {
        $success_msg = "FAQ deleted successfully!";
        header("Location: faq.php");
        exit();
    } else {
        $error_msg = "Error deleting FAQ: " . mysqli_error($con);
    }
}

$faqs = [];
$result = mysqli_query($con, "SELECT * FROM faqs ORDER BY id DESC");
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $faqs[] = $row;
    }
}

$edit_mode = false;
$edit_question = '';
$edit_answer = '';
$edit_id = '';

if (isset($_GET['edit_id'])) {
    $edit_id = (int)$_GET['edit_id'];
    $result = mysqli_query($con, "SELECT * FROM faqs WHERE id = $edit_id");
    if ($result && mysqli_num_rows($result) > 0) {
        $edit_faq = mysqli_fetch_assoc($result);
        $edit_question = htmlspecialchars($edit_faq['question']);
        $edit_answer = htmlspecialchars($edit_faq['answer']);
        $edit_mode = true;
    } else {
        header("Location: faq.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQ Management</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/faq.css">
</head>
<body>
    <?php include 'sidebar.php'; ?>

    <!-- Main Content -->
    <div class="dash-body animate-fade">
        <div class="container-fluid">
            <!-- Page Header -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center">
                        <button onclick="window.history.back()" class="btn btn-soft-primary">
                            <i class="fas fa-arrow-left me-2"></i>Back
                        </button>
                        <h2 class="mb-0 text-center">FAQ Management</h2>
                        <div class="mobile-hidden">
                            <p class="mb-0 small text-muted">Today's Date</p>
                            <h6><?php date_default_timezone_set('Asia/Kolkata'); echo date('Y-m-d'); ?></h6>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Success/Error Messages -->
            <?php if (isset($success_msg)): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php echo $success_msg; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            
            <?php if (isset($error_msg)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php echo $error_msg; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            
            <!-- Content Row -->
            <div class="row">
                <!-- Add/Edit FAQ Form -->
                <div class="col-lg-6 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-header <?php echo $edit_mode ? 'bg-warning' : 'bg-primary'; ?> text-white">
                            <h5 class="card-title mb-0">
                                <i class="fas <?php echo $edit_mode ? 'fa-edit' : 'fa-plus-circle'; ?> me-2"></i>
                                <?php echo $edit_mode ? 'Update FAQ' : 'Add New FAQ'; ?>
                            </h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="">
                                <?php if ($edit_mode): ?>
                                    <input type="hidden" name="faq_id" value="<?php echo $edit_id; ?>">
                                <?php endif; ?>
                                
                                <div class="mb-3">
                                    <label for="question" class="form-label">Question</label>
                                    <input type="text" class="form-control" id="question" name="question" 
                                           value="<?php echo $edit_mode ? $edit_question : ''; ?>" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="answer" class="form-label">Answer</label>
                                    <textarea class="form-control" id="answer" name="answer" rows="4" required><?php echo $edit_mode ? $edit_answer : ''; ?></textarea>
                                </div>
                                
                                <div class="d-grid">
                                    <?php if ($edit_mode): ?>
                                        <button type="submit" name="update_faq" class="btn btn-warning">
                                            <i class="fas fa-save me-2"></i>Update FAQ
                                        </button>
                                        <a href="faq.php" class="btn btn-secondary mt-2">
                                            <i class="fas fa-times me-2"></i>Cancel Edit
                                        </a>
                                    <?php else: ?>
                                        <button type="submit" name="add_faq" class="btn btn-primary">
                                            <i class="fas fa-plus me-2"></i>Add FAQ
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- FAQ List -->
                <div class="col-lg-6">
                    <div class="card shadow-sm">
                        <div class="card-header bg-info text-white">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-list me-2"></i>
                                Existing FAQs (<?php echo count($faqs); ?>)
                            </h5>
                        </div>
                        <div class="card-body">
                            <?php if (count($faqs) > 0): ?>
                                <div class="accordion" id="faqAccordion">
                                    <?php foreach ($faqs as $index => $faq): ?>
                                        <div class="accordion-item faq-item mb-3">
                                            <h2 class="accordion-header" id="heading<?php echo $faq['id']; ?>">
                                                <button class="accordion-button collapsed" type="button" 
                                                        data-bs-toggle="collapse" 
                                                        data-bs-target="#collapse<?php echo $faq['id']; ?>" 
                                                        aria-expanded="false" 
                                                        aria-controls="collapse<?php echo $faq['id']; ?>">
                                                    <?php echo htmlspecialchars($faq['question']); ?>
                                                </button>
                                            </h2>
                                            <div id="collapse<?php echo $faq['id']; ?>" 
                                                 class="accordion-collapse collapse" 
                                                 aria-labelledby="heading<?php echo $faq['id']; ?>" 
                                                 data-bs-parent="#faqAccordion">
                                                <div class="accordion-body">
                                                    <p><?php echo nl2br(htmlspecialchars($faq['answer'])); ?></p>
                                                    <div class="d-flex action-buttons mt-3">
                                                        <a href="faq.php?edit_id=<?php echo $faq['id']; ?>" 
                                                           class="btn btn-sm btn-warning me-2">
                                                            <i class="fas fa-edit me-1"></i>Edit
                                                        </a>
                                                        <a href="faq.php?delete_id=<?php echo $faq['id']; ?>" 
                                                           class="btn btn-sm btn-danger">
                                                            <i class="fas fa-trash me-1"></i>Delete
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-info text-center">
                                    <i class="fas fa-info-circle me-2"></i>
                                    No FAQs found. Add your first FAQ using the form on the left.
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>