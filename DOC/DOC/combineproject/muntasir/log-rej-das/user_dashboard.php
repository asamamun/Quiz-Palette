
<?php
session_start();

// Handle logout request
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    $_SESSION = array();
    session_destroy();
    header("Location: index.php");
    exit;
}

// Verify user is logged in
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: index.php");
    exit;
}

// Database config — update as necessary
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'test_db';

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch products from the database
$products_data = [];
$result = $conn->query("SELECT p.id, p.name, p.description, p.price, c.name as category_name FROM products p JOIN categories c ON p.category_id = c.id ORDER BY p.id ASC");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $products_data[] = $row;
    }
    $result->free();
}
$conn->close();
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <title>User Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body, html {
            height: 100%;
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f7fa;
        }
        .product-card {
            margin-bottom: 20px;
        }
        .search-bar {
            margin-bottom: 20px;
        }
        .logout-btn {
            position: fixed;
            top: 20px;
            right: 20px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <!-- Logout Button -->
        <a href="?action=logout" class="btn btn-danger logout-btn">Logout</a>

        <h2>Welcome to User Dashboard</h2>
        <p>Hello <strong><?php echo htmlspecialchars($_SESSION["username"]); ?></strong>, you are logged in as <strong>User</strong>.</p>

        <!-- Search Bar -->
        <div class="search-bar">
            <input type="text" id="search" class="form-control" placeholder="Search for products...">
        </div>

        <!-- Products in Bootstrap Cards -->
        <div class="row" id="product-container">
            <?php foreach ($products_data as $product): ?>
                <div class="col-md-4">
                    <div class="card product-card">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars($product['description']); ?></p>
                            <p class="card-text">Price: $<?php echo htmlspecialchars($product['price']); ?></p>
                            <p class="card-text">Category: <?php echo htmlspecialchars($product['category_name']); ?></p>
                            <button class="btn btn-primary">Buy Now</button>
                            <button class="btn btn-secondary">Add to Cart</button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // JavaScript for search functionality
        document.getElementById('search').addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase();
            const productCards = document.querySelectorAll('.product-card');

            productCards.forEach(card => {
                const productName = card.querySelector('.card-title').textContent.toLowerCase();
                if (productName.includes(searchTerm)) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>
