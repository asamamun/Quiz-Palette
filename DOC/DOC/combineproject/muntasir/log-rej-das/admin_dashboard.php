<?php
session_start();

// Verify user is logged in and is admin
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== "admin") {
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

$add_user_err = $add_user_success = "";
$edit_user_err = $edit_user_success = "";
$delete_user_err = $delete_user_success = "";
$add_product_err = $add_product_success = "";
$edit_product_err = $edit_product_success = "";
$delete_product_err = $delete_product_success = "";
$add_category_err = $add_category_success = "";
$edit_category_err = $edit_category_success = "";
$delete_category_err = $delete_category_success = "";
$active_page = "dashboard";

// Sanitize helper
function sanitize_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Handle logout request
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    $_SESSION = array();
    session_destroy();
    header("Location: index.php");
    exit;
}

// Determine active page via GET param
if (isset($_GET['page'])) {
    $page = $_GET['page'];
    if (in_array($page, ['dashboard', 'add_user', 'view_users', 'edit_user', 'add_product', 'view_products', 'edit_product', 'add_category', 'view_categories', 'edit_category'])) {
        $active_page = $page;
    }
}

// Handle Add User form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_user'])) {
    $username = sanitize_input($_POST['username']);
    $email = sanitize_input($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = $_POST['role'];

    if (empty($username) || empty($email) || empty($password) || empty($confirm_password) || empty($role)) {
        $add_user_err = "Please fill in all fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $add_user_err = "Invalid email format.";
    } elseif ($password !== $confirm_password) {
        $add_user_err = "Passwords do not match.";
    } elseif (strlen($password) < 6) {
        $add_user_err = "Password must be at least 6 characters.";
    } elseif (!in_array($role, ['admin', 'moderator', 'user'])) {
        $add_user_err = "Invalid role selected.";
    } else {
        // Check if email exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $add_user_err = "Email already registered.";
        } else {
            // Insert user
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $username, $email, $hashed_password, $role);
            if ($stmt->execute()) {
                $add_user_success = "User successfully added.";
            } else {
                $add_user_err = "Error adding user.";
            }
        }
        $stmt->close();
    }
}

// Handle Edit User form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_user'])) {
    $user_id = $_GET['id'];
    $username = sanitize_input($_POST['username']);
    $email = sanitize_input($_POST['email']);
    $role = $_POST['role'];

    if (empty($username) || empty($email) || empty($role)) {
        $edit_user_err = "Please fill in all fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $edit_user_err = "Invalid email format.";
    } elseif (!in_array($role, ['admin', 'moderator', 'user'])) {
        $edit_user_err = "Invalid role selected.";
    } else {
        // Update user
        $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, role = ? WHERE id = ?");
        $stmt->bind_param("sssi", $username, $email, $role, $user_id);
        if ($stmt->execute()) {
            $edit_user_success = "User successfully updated.";
        } else {
            $edit_user_err = "Error updating user.";
        }
        $stmt->close();
    }
}

// Handle Delete User request
if (isset($_GET['action']) && $_GET['action'] === 'delete_user' && isset($_GET['id'])) {
    $user_id = $_GET['id'];
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    if ($stmt->execute()) {
        $delete_user_success = "User successfully deleted.";
    } else {
        $delete_user_err = "Error deleting user.";
    }
    $stmt->close();
}

// Handle Add Product form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_product'])) {
    $name = sanitize_input($_POST['name']);
    $description = sanitize_input($_POST['description']);
    $price = $_POST['price'];
    $category_id = $_POST['category_id'];

    if (empty($name) || empty($price) || empty($category_id)) {
        $add_product_err = "Please fill in all required fields.";
    } else {
        // Insert product
        $stmt = $conn->prepare("INSERT INTO products (name, description, price, category_id) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssdi", $name, $description, $price, $category_id);
        if ($stmt->execute()) {
            $add_product_success = "Product successfully added.";
        } else {
            $add_product_err = "Error adding product.";
        }
        $stmt->close();
    }
}

// Handle Edit Product form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_product'])) {
    $product_id = $_GET['id'];
    $name = sanitize_input($_POST['name']);
    $description = sanitize_input($_POST['description']);
    $price = $_POST['price'];
    $category_id = $_POST['category_id'];

    if (empty($name) || empty($price) || empty($category_id)) {
        $edit_product_err = "Please fill in all required fields.";
    } else {
        // Update product
        $stmt = $conn->prepare("UPDATE products SET name = ?, description = ?, price = ?, category_id = ? WHERE id = ?");
        $stmt->bind_param("ssdii", $name, $description, $price, $category_id, $product_id);
        if ($stmt->execute()) {
            $edit_product_success = "Product successfully updated.";
        } else {
            $edit_product_err = "Error updating product.";
        }
        $stmt->close();
    }
}

// Handle Delete Product request
if (isset($_GET['action']) && $_GET['action'] === 'delete_product' && isset($_GET['id'])) {
    $product_id = $_GET['id'];
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    if ($stmt->execute()) {
        $delete_product_success = "Product successfully deleted.";
    } else {
        $delete_product_err = "Error deleting product.";
    }
    $stmt->close();
}

// Handle Add Category form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_category'])) {
    $name = sanitize_input($_POST['name']);
    $description = sanitize_input($_POST['description']);

    if (empty($name)) {
        $add_category_err = "Please fill in all required fields.";
    } else {
        // Insert category
        $stmt = $conn->prepare("INSERT INTO categories (name, description) VALUES (?, ?)");
        $stmt->bind_param("ss", $name, $description);
        if ($stmt->execute()) {
            $add_category_success = "Category successfully added.";
        } else {
            $add_category_err = "Error adding category.";
        }
        $stmt->close();
    }
}

// Handle Edit Category form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_category'])) {
    $category_id = $_GET['id'];
    $name = sanitize_input($_POST['name']);
    $description = sanitize_input($_POST['description']);

    if (empty($name)) {
        $edit_category_err = "Please fill in all required fields.";
    } else {
        // Update category
        $stmt = $conn->prepare("UPDATE categories SET name = ?, description = ? WHERE id = ?");
        $stmt->bind_param("ssi", $name, $description, $category_id);
        if ($stmt->execute()) {
            $edit_category_success = "Category successfully updated.";
        } else {
            $edit_category_err = "Error updating category.";
        }
        $stmt->close();
    }
}

// Handle Delete Category request
if (isset($_GET['action']) && $_GET['action'] === 'delete_category' && isset($_GET['id'])) {
    $category_id = $_GET['id'];
    $stmt = $conn->prepare("DELETE FROM categories WHERE id = ?");
    $stmt->bind_param("i", $category_id);
    if ($stmt->execute()) {
        $delete_category_success = "Category successfully deleted.";
    } else {
        $delete_category_err = "Error deleting category.";
    }
    $stmt->close();
}

// Fetch all users for view_users page

$users_data = [];
if ($active_page === "view_users") {
    $result = $conn->query("SELECT id, username, email, role, created_at FROM users ORDER BY id ASC");
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $users_data[] = $row;
        }
        $result->free();
    }
}


// Fetch all products for view_products page
$products_data = [];
if ($active_page === "view_products") {
    $result = $conn->query("SELECT p.id, p.name, p.description, p.price, p.created_at, c.name as category_name FROM products p JOIN categories c ON p.category_id = c.id ORDER BY p.id ASC");
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $products_data[] = $row;
        }
        $result->free();
    }
}


// Fetch all categories for view_categories page
$categories_data = [];
if ($active_page === "view_categories" || $active_page === "add_product" || $active_page === "edit_product") {
    $result = $conn->query("SELECT id, name, description FROM categories ORDER BY id ASC");
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $categories_data[] = $row;
        }
        $result->free();
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body, html {
            height: 100%;
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f7fa;
        }
        .sidebar {
            height: 100vh;
            width: 220px;
            background-color: #764ba2;
            color: white;
            position: fixed;
            top: 0;
            left: 0;
            overflow-x: hidden;
            padding-top: 20px;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            border-radius: 0 30px 30px 0;
            box-shadow: 3px 0 15px rgba(0,0,0,0.1);
        }
        .sidebar a {
            padding: 15px 25px;
            text-decoration: none;
            font-size: 1rem;
            color: white;
            display: block;
            font-weight: 600;
            transition: background-color 0.3s ease;
            border-radius: 0 20px 20px 0;
        }
        .sidebar a:hover, .sidebar a.active {
            background-color: #5a357a;
        }
        .sidebar .logout-link {
            margin-top: auto;
            padding-bottom: 20px;
            color: #ff6b6b;
            font-weight: 700;
        }
        .main-content {
            margin-left: 220px;
            padding: 30px;
            max-width: calc(100% - 220px);
            min-height: 100vh;
            background: white;
            border-radius: 25px 0 0 25px;
            box-shadow: 0 0 20px rgba(0,0,0,0.05);
            overflow-y: auto;
        }
        h2 {
            color: #764ba2;
            font-weight: 700;
            margin-bottom: 20px;
        }
        label {
            font-weight: 600;
            color: #444;
        }
        .form-control:focus {
            border-color: #764ba2;
            box-shadow: 0 0 8px #764ba2b0;
        }
        .btn-primary {
            background-color: #764ba2;
            border-color: #764ba2;
            font-weight: 700;
            transition: background-color 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #5a357a;
            border-color: #5a357a;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.95rem;
        }
        thead {
            background-color: #764ba2;
            color: white;
        }
        th, td {
            padding: 12px 15px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }
        tbody tr:hover {
            background-color: #f1f1f1;
        }
        @media (max-width: 600px) {
            .sidebar {
                width: 60px;
                padding-top: 10px;
                border-radius: 0;
            }
            .sidebar a {
                padding: 12px 10px;
                font-size: 0;
            }
            .sidebar a::before {
                font-size: 1.6rem;
                display: block;
                margin-bottom: 5px;
            }
            .sidebar a.active::before, .sidebar a:hover::before {
                color: #fff;
            }
            .main-content {
                margin-left: 60px;
                padding: 15px;
                border-radius: 0;
                max-width: calc(100% - 60px);
            }
            table {
                font-size: 0.85rem;
            }
        }
    </style>
</head>
<body>
    <nav class="sidebar" aria-label="Admin Sidebar Navigation">
        <a href="?page=dashboard" class="<?php echo ($active_page === 'dashboard') ? 'active' : ''; ?>" aria-current="<?php echo ($active_page === 'dashboard') ? 'page' : ''; ?>">Dashboard</a>
        <a href="?page=add_user" class="<?php echo ($active_page === 'add_user') ? 'active' : ''; ?>">Add User</a>
        <a href="?page=view_users" class="<?php echo ($active_page === 'view_users') ? 'active' : ''; ?>">View Users</a>
        <a href="?page=add_product" class="<?php echo ($active_page === 'add_product') ? 'active' : ''; ?>">Add Product</a>
        <a href="?page=view_products" class="<?php echo ($active_page === 'view_products') ? 'active' : ''; ?>">View Products</a>
        <a href="?page=add_category" class="<?php echo ($active_page === 'add_category') ? 'active' : ''; ?>">Add Category</a>
        <a href="?page=view_categories" class="<?php echo ($active_page === 'view_categories') ? 'active' : ''; ?>">View Categories</a>
        <a href="?action=logout" class="logout-link">Logout</a>
    </nav>
    <main class="main-content">
        
<?php if ($active_page === 'dashboard'): ?>
    <h2>Welcome to Admin Dashboard</h2>
    <p>Hello <strong><?php echo htmlspecialchars($_SESSION["username"]); ?></strong>, you are logged in as <strong>Admin</strong>.</p>
    <p>Use the menu to manage users, products, and categories.</p>

    <!-- Add a section to display user information -->
    <h3>User Summary</h3>
    <?php
    $result = $conn->query("SELECT id, username, email, role, created_at FROM users ORDER BY id ASC LIMIT 5");
    if ($result && $result->num_rows > 0):
    ?>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Registered At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['id']); ?></td>
                            <td><?php echo htmlspecialchars($row['username']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars(ucfirst($row['role'])); ?></td>
                            <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p>No users found.</p>
    
<?php endif; ?>

        <?php elseif ($active_page === 'add_user'): ?>
            <h2>Add New User</h2>
            <form method="POST" action="?page=add_user" novalidate>
                <div class="mb-3">
                    <label for="username">Username <span class="text-danger">*</span></label>
                    <input type="text" id="username" name="username" class="form-control" required />
                    <div class="invalid-feedback">Please enter a username.</div>
                </div>
                <div class="mb-3">
                    <label for="email">Email address <span class="text-danger">*</span></label>
                    <input type="email" id="email" name="email" class="form-control" required />
                    <div class="invalid-feedback">Please enter a valid email.</div>
                </div>
                <div class="mb-3">
                    <label for="password">Password <span class="text-danger">*</span></label>
                    <input type="password" id="password" name="password" class="form-control" minlength="6" required />
                    <div class="invalid-feedback">Password must be at least 6 characters.</div>
                </div>
                <div class="mb-3">
                    <label for="confirm_password">Confirm Password <span class="text-danger">*</span></label>
                    <input type="password" id="confirm_password" name="confirm_password" class="form-control" minlength="6" required />
                    <div class="invalid-feedback">Passwords must match.</div>
                </div>
                <div class="mb-3">
                    <label for="role">Role <span class="text-danger">*</span></label>
                    <select id="role" name="role" class="form-select" required>
                        <option value="" selected disabled>Choose...</option>
                        <option value="admin">Admin</option>
                        <option value="moderator">Moderator</option>
                        <option value="user">User</option>
                    </select>
                    <div class="invalid-feedback">Please select a role.</div>
                </div>
                <?php if ($add_user_err): ?>
                    <div class="alert alert-danger"><?php echo $add_user_err; ?></div>
                <?php elseif ($add_user_success): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo $add_user_success; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                <button type="submit" name="add_user" class="btn btn-primary">Add User</button>
            </form>

        <?php elseif ($active_page === 'view_users'): ?>
            <h2>All Registered Users</h2>
            <?php if (isset($delete_user_success)): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php echo $delete_user_success; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php elseif (isset($delete_user_err)): ?>
                <div class="alert alert-danger"><?php echo $delete_user_err; ?></div>
            <?php endif; ?>
            <?php if (!empty($users_data)): ?>
                <div class="table-responsive" style="max-height: 500px;">
                    <table class="table table-striped table-hover" aria-describedby="User list table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Registered At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users_data as $user): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($user['id']); ?></td>
                                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                                    <td><?php echo htmlspecialchars(ucfirst($user['role'])); ?></td>
                                    <td><?php echo htmlspecialchars($user['created_at']); ?></td>
                                    <td>
                                        <a href="?page=edit_user&id=<?php echo $user['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                                        <a href="?action=delete_user&id=<?php echo $user['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p>No users found.</p>
            <?php endif; ?>

        <?php elseif ($active_page === 'edit_user'): ?>
            <h2>Edit User</h2>
            <?php
            $user_id = $_GET['id'];
            $stmt = $conn->prepare("SELECT id, username, email, role FROM users WHERE id = ?");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();
            $stmt->close();
            ?>
            <form method="POST" action="?page=edit_user&id=<?php echo $user_id; ?>" novalidate>
                <div class="mb-3">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" class="form-control" value="<?php echo htmlspecialchars($user['username']); ?>" required />
                    <div class="invalid-feedback">Please enter a username.</div>
                </div>
                <div class="mb-3">
                    <label for="email">Email address</label>
                    <input type="email" id="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" required />
                    <div class="invalid-feedback">Please enter a valid email.</div>
                </div>
                <div class="mb-3">
                    <label for="role">Role</label>
                    <select id="role" name="role" class="form-select" required>
                        <option value="admin" <?php echo ($user['role'] === 'admin') ? 'selected' : ''; ?>>Admin</option>
                        <option value="moderator" <?php echo ($user['role'] === 'moderator') ? 'selected' : ''; ?>>Moderator</option>
                        <option value="user" <?php echo ($user['role'] === 'user') ? 'selected' : ''; ?>>User</option>
                    </select>
                    <div class="invalid-feedback">Please select a role.</div>
                </div>
                <?php if (isset($edit_user_err)): ?>
                    <div class="alert alert-danger"><?php echo $edit_user_err; ?></div>
                <?php elseif (isset($edit_user_success)): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo $edit_user_success; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                <button type="submit" name="edit_user" class="btn btn-primary">Update User</button>
            </form>

        <?php elseif ($active_page === 'add_product'): ?>
            <h2>Add New Product</h2>
            <form method="POST" action="?page=add_product" novalidate>
                <div class="mb-3">
                    <label for="name">Product Name <span class="text-danger">*</span></label>
                    <input type="text" id="name" name="name" class="form-control" required />
                    <div class="invalid-feedback">Please enter a product name.</div>
                </div>
                <div class="mb-3">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" class="form-control"></textarea>
                </div>
                <div class="mb-3">
                    <label for="price">Price <span class="text-danger">*</span></label>
                    <input type="number" id="price" name="price" class="form-control" step="0.01" required />
                    <div class="invalid-feedback">Please enter a valid price.</div>
                </div>
                <div class="mb-3">
                    <label for="category_id">Category <span class="text-danger">*</span></label>
                    <select id="category_id" name="category_id" class="form-select" required>
                        <option value="" selected disabled>Choose...</option>
                        <?php foreach ($categories_data as $category): ?>
                            <option value="<?php echo $category['id']; ?>"><?php echo htmlspecialchars($category['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                    <div class="invalid-feedback">Please select a category.</div>
                </div>
                <?php if ($add_product_err): ?>
                    <div class="alert alert-danger"><?php echo $add_product_err; ?></div>
                <?php elseif ($add_product_success): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo $add_product_success; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                <button type="submit" name="add_product" class="btn btn-primary">Add Product</button>
            </form>

        <?php elseif ($active_page === 'view_products'): ?>
            <h2>All Products</h2>
            <?php if (isset($delete_product_success)): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php echo $delete_product_success; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php elseif (isset($delete_product_err)): ?>
                <div class="alert alert-danger"><?php echo $delete_product_err; ?></div>
            <?php endif; ?>
            <?php if (!empty($products_data)): ?>
                <div class="table-responsive" style="max-height: 500px;">
                    <table class="table table-striped table-hover" aria-describedby="Product list table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Price</th>
                                <th>Category</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($products_data as $product): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($product['id']); ?></td>
                                    <td><?php echo htmlspecialchars($product['name']); ?></td>
                                    <td><?php echo htmlspecialchars($product['description']); ?></td>
                                    <td><?php echo htmlspecialchars($product['price']); ?></td>
                                    <td><?php echo htmlspecialchars($product['category_name']); ?></td>
                                    <td><?php echo htmlspecialchars($product['created_at']); ?></td>
                                    <td>
                                        <a href="?page=edit_product&id=<?php echo $product['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                                        <a href="?action=delete_product&id=<?php echo $product['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p>No products found.</p>
            <?php endif; ?>

        <?php elseif ($active_page === 'edit_product'): ?>
            <h2>Edit Product</h2>
            <?php
            $product_id = $_GET['id'];
            $stmt = $conn->prepare("SELECT id, name, description, price, category_id FROM products WHERE id = ?");
            $stmt->bind_param("i", $product_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $product = $result->fetch_assoc();
            $stmt->close();
            ?>
            <form method="POST" action="?page=edit_product&id=<?php echo $product_id; ?>" novalidate>
                <div class="mb-3">
                    <label for="name">Product Name</label>
                    <input type="text" id="name" name="name" class="form-control" value="<?php echo htmlspecialchars($product['name']); ?>" required />
                    <div class="invalid-feedback">Please enter a product name.</div>
                </div>
                <div class="mb-3">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" class="form-control"><?php echo htmlspecialchars($product['description']); ?></textarea>
                </div>
                <div class="mb-3">
                    <label for="price">Price</label>
                    <input type="number" id="price" name="price" class="form-control" step="0.01" value="<?php echo htmlspecialchars($product['price']); ?>" required />
                    <div class="invalid-feedback">Please enter a valid price.</div>
                </div>
                <div class="mb-3">
                    <label for="category_id">Category</label>
                    <select id="category_id" name="category_id" class="form-select" required>
                        <option value="" selected disabled>Choose...</option>
                        <?php foreach ($categories_data as $category): ?>
                            <option value="<?php echo $category['id']; ?>" <?php echo ($product['category_id'] == $category['id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($category['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                    <div class="invalid-feedback">Please select a category.</div>
                </div>
                <?php if (isset($edit_product_err)): ?>
                    <div class="alert alert-danger"><?php echo $edit_product_err; ?></div>
                <?php elseif (isset($edit_product_success)): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo $edit_product_success; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                <button type="submit" name="edit_product" class="btn btn-primary">Update Product</button>
            </form>

        <?php elseif ($active_page === 'add_category'): ?>
            <h2>Add New Category</h2>
            <form method="POST" action="?page=add_category" novalidate>
                <div class="mb-3">
                    <label for="name">Category Name <span class="text-danger">*</span></label>
                    <input type="text" id="name" name="name" class="form-control" required />
                    <div class="invalid-feedback">Please enter a category name.</div>
                </div>
                <div class="mb-3">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" class="form-control"></textarea>
                </div>
                <?php if ($add_category_err): ?>
                    <div class="alert alert-danger"><?php echo $add_category_err; ?></div>
                <?php elseif ($add_category_success): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo $add_category_success; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                <button type="submit" name="add_category" class="btn btn-primary">Add Category</button>
            </form>

        <?php elseif ($active_page === 'view_categories'): ?>
            <h2>All Categories</h2>
            <?php if (isset($delete_category_success)): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php echo $delete_category_success; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php elseif (isset($delete_category_err)): ?>
                <div class="alert alert-danger"><?php echo $delete_category_err; ?></div>
            <?php endif; ?>
            <?php if (!empty($categories_data)): ?>
                <div class="table-responsive" style="max-height: 500px;">
                    <table class="table table-striped table-hover" aria-describedby="Category list table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($categories_data as $category): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($category['id']); ?></td>
                                    <td><?php echo htmlspecialchars($category['name']); ?></td>
                                    <td><?php echo htmlspecialchars($category['description']); ?></td>
                                    <td>
                                        <a href="?page=edit_category&id=<?php echo $category['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                                        <a href="?action=delete_category&id=<?php echo $category['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this category?');">Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p>No categories found.</p>
            <?php endif; ?>

        <?php elseif ($active_page === 'edit_category'): ?>
            <h2>Edit Category</h2>
            <?php
            $category_id = $_GET['id'];
            $stmt = $conn->prepare("SELECT id, name, description FROM categories WHERE id = ?");
            $stmt->bind_param("i", $category_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $category = $result->fetch_assoc();
            $stmt->close();
            ?>
            <form method="POST" action="?page=edit_category&id=<?php echo $category_id; ?>" novalidate>
                <div class="mb-3">
                    <label for="name">Category Name</label>
                    <input type="text" id="name" name="name" class="form-control" value="<?php echo htmlspecialchars($category['name']); ?>" required />
                    <div class="invalid-feedback">Please enter a category name.</div>
                </div>
                <div class="mb-3">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" class="form-control"><?php echo htmlspecialchars($category['description']); ?></textarea>
                </div>
                <?php if (isset($edit_category_err)): ?>
                    <div class="alert alert-danger"><?php echo $edit_category_err; ?></div>
                <?php elseif (isset($edit_category_success)): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo $edit_category_success; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                <button type="submit" name="edit_category" class="btn btn-primary">Update Category</button>
            </form>
        <?php endif; ?>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        (() => {
            'use strict';
            const form = document.querySelector('form');
            if (form) {
                form.addEventListener('submit', event => {
                    // Password match validation for Add User form
                    const password = form.querySelector("#password");
                    const confirmPassword = form.querySelector("#confirm_password");
                    if (password && confirmPassword && password.value !== confirmPassword.value) {
                        event.preventDefault();
                        event.stopPropagation();
                        confirmPassword.classList.add('is-invalid');
                        confirmPassword.nextElementSibling.textContent = "Passwords do not match.";
                        return;
                    } else if (confirmPassword) {
                        confirmPassword.classList.remove('is-invalid');
                    }
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            }
        })();
    </script>
</body>
</html>
<?php
$conn->close();
?>
