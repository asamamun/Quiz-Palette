<?php include "includes/header.php"?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #f0f4f4 0%, #d9e6e6 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .exam-container {
            margin-top: 100px;
            padding: 2rem;
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            animation: fadeIn 0.5s ease-in;
        }
        .form-select {
            transition: all 0.3s ease;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
        }
        .form-select:focus {
            border-color: #096B68;
            box-shadow: 0 0 0 3px rgba(9, 107, 104, 0.1);
        }
       
/* Default state */
button.btn.btn-primary {
    background: #0a7d79 !important;   /* or your preferred color */
    border-color: transparent !important;
    color: #fff;  /* optional - for contrast */
}

/* Hover state */
button.btn.btn-primary:hover {
    background: #075956 !important;
    transform: translateY(-2px);
    border-color: transparent !important;
}
      
        .btn-outline-primary {
            background: #0a7d79 !important;
            border-color: #096B68 !important;
            color: #096B68 !important;
        }
        .btn-outline-primary:hover {
            background: #075956 !important;
            color: white !important;
        }

        
        .card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: none;
            border-radius: 10px;
            overflow: hidden;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.15);
        }
        .card-header {
            background: #096B68;
            color: white;
            font-weight: 600;
            border-radius: 10px 10px 0 0;
        }
        .section-title {
            color: #1e293b;
            position: relative;
            margin-bottom: 2rem;
        }
        .section-title::after {
            content: '';
            width: 60px;
            height: 4px;
            background: #096B68;
            position: absolute;
            bottom: -10px;
            left: 0;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .loading-spinner {
            display: none;
            text-align: center;
            padding: 2rem;
        }
        .spinner-border {
            border-color: #096B68;
            border-right-color: transparent;
        }
        .alert-info, .alert-warning, .alert-danger {
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <div class="container exam-container">
        <h2 class="section-title mb-4">Find Your Exam</h2>
        <form id="examFilterForm">
            <div class="row g-4">
                <div class="col-md-4">
                    <label for="categories" class="form-label fw-semibold">Category</label>
                    <select name="categories" id="categories" class="form-select">
                        <option value="">Select Category</option>
                        <?php
                        $db = new MysqliDb();
                        $cats = $db->get("categories", null, ['id','name']);
                        foreach ($cats as $value) {
                            echo "<option value='{$value['id']}'>{$value['name']}</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="classes" class="form-label fw-semibold">Class</label>
                    <select name="classes" id="classes" class="form-select">
                        
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="subjects" class="form-label fw-semibold">Subject</label>
                    <select name="subjects" id="subjects" class="form-select">
                        
                    </select>
                </div>
            </div>
            <div class="d-grid gap-2 mt-4">
                <button class="btn btn-primary" type="submit">
                    <i class="fas fa-search me-2"></i>Search Exams
                </button>
            </div>
        </form>

        <hr class="my-5">
        
        <h3 class="section-title">Available Exams</h3>
        <div id="exams" class="row g-4">
            <div class="loading-spinner">
                <div class="spinner-border" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        </div>
    </div>

  

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= settings()['root'] ?>/assets/js/jquery-3.7.1.min.js"></script>
    <script>
        $(document).ready(function() {
            const $examsContainer = $('#exams');
            const $loadingSpinner = $('.loading-spinner');

            function showLoading() {
                $loadingSpinner.show();
                $examsContainer.children().not('.loading-spinner').hide();
            }

            function hideLoading() {
                $loadingSpinner.hide();
                $examsContainer.children().not('.loading-spinner').show();
            }

            // Get classes when category changes
            $('#categories').change(function() {
                const category_id = $(this).val();
                $('#classes').empty().append('<option value="">Select Class</option>');
                $('#subjects').empty().append('<option value="">Select Subject</option>');
                $examsContainer.empty().append($loadingSpinner);
                
                if (category_id) {
                    $.ajax({
                        url: "admin/ajax/get_classes.php",
                        method: "POST",
                        data: { cat_id: category_id },
                        dataType: "json",
                        success: function(response) {
                            response.data?.forEach(element => {
                                $('#classes').append(`<option value="${element.id}">${element.name}</option>`);
                            });
                        },
                        error: function() {
                            $examsContainer.html('<div class="alert alert-danger">Error loading classes.</div>');
                        }
                    });
                }
            });

            // Get subjects and exams when class changes
            $('#classes').change(function() {
                const class_id = $(this).val();
                const category_id = $('#categories').val();
                $('#subjects').empty().append('<option value="">Select Subject</option>');
                
                if (class_id) {
                    // Get subjects
                    $.ajax({
                        url: "admin/ajax/get_subjects.php",
                        method: "POST",
                        data: { class_id: class_id },
                        dataType: "json",
                        success: function(response) {
                            response.data?.forEach(element => {
                                $('#subjects').append(`<option value="${element.id}">${element.name}</option>`);
                            });
                        },
                        error: function() {
                            $examsContainer.html('<div class="alert alert-danger">Error loading subjects.</div>');
                        }
                    });

                    // Get exams
                    loadExams(category_id, class_id);
                }
            });

            // Get exams when subject changes
            $('#subjects').change(function() {
                const subject_id = $(this).val();
                const class_id = $('#classes').val();
                const category_id = $('#categories').val();
                
                if (subject_id) {
                    loadExams(category_id, class_id, subject_id);
                }
            });

            // Form submission handler
            $('#examFilterForm').submit(function(e) {
                e.preventDefault();
                const category_id = $('#categories').val();
                const class_id = $('#classes').val();
                const subject_id = $('#subjects').val();
                
                if (category_id) {
                    loadExams(category_id, class_id, subject_id);
                } else {
                    $examsContainer.html('<div class="alert alert-warning">Please select a category.</div>');
                }
            });

            function loadExams(category_id, class_id = '', subject_id = '') {
                showLoading();
                
                $.ajax({
                    url: "admin/ajax/get_exams.php",
                    method: "POST",
                    data: {
                        category_id: category_id,
                        class_id: class_id,
                        subject_id: subject_id
                    },
                    dataType: "json",
                    success: function(response) {
                        hideLoading();
                        $examsContainer.empty().append($loadingSpinner);
                        
                        if (response.success && response.data.length > 0) {
                            response.data.forEach(record => {
                                $examsContainer.append(`
                                    <div class="col-md-4">
                                        <div class="card text-bg-light">
                                            <div class="card-header">${record.title}</div>
                                            <div class="card-body">
                                                <h5 class="card-title">${record.description}</h5>
                                                <p class="card-text"><i class="fas fa-clock me-2"></i>Duration: ${record.duration}</p>
                                                <div class="d-flex justify-content-between">
                                                <a class="btn btn-outline-primary text-white" href="leaderboard_info.php?examid=${record.id}">
                                                    <i class="fas fa-play me-2"></i>Leaderboard
                                                </a>                  
                                                <a class="btn btn-outline-primary text-white" href="exam-quiz.php?examid=${record.id}">
                                                    <i class="fas fa-play me-2"></i>Start Exam
                                                </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                `);
                            });
                        } else {
                            $examsContainer.html('<div class="alert alert-info">No exams found.</div>');
                        }
                    },
                    error: function() {
                        hideLoading();
                        $examsContainer.html('<div class="alert alert-danger">Error loading exams.</div>');
                    }
                });
            }
        });
    </script>
</body>

</html>
<?php include "includes/footer.php" ?>