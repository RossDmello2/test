<?php
// File to store users data
$storage_file = 'users_data.php';

// Initialize or load existing users
function loadUsers() {
    global $storage_file;
    if (file_exists($storage_file)) {
        include $storage_file;
        return isset($users) ? $users : array();
    }
    return array();
}

// Save users to file
function saveUsers($users) {
    global $storage_file;
    $data = "<?php\n\$users = " . var_export($users, true) . ";\n?>";
    file_put_contents($storage_file, $data);
}

// Function to validate username
function validateUsername($username) {
    $users = loadUsers();
    
    foreach ($users as $user) {
        if ($user['username'] === $username) {
            echo json_encode(["status" => "taken"]);
            return;
        }
    }
    echo json_encode(["status" => "available"]);
}

// Function to validate email
function validateEmail($email) {
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $users = loadUsers();
        foreach ($users as $user) {
            if ($user['email'] === $email) {
                echo json_encode(["status" => "invalid"]);
                return;
            }
        }
        echo json_encode(["status" => "valid"]);
    } else {
        echo json_encode(["status" => "invalid"]);
    }
}

// Handle form submission
if (isset($_POST['submit'])) {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    
    // Validate inputs
    if (empty($username) || empty($email)) {
        echo json_encode(["status" => "error", "message" => "Username and email are required"]);
        exit;
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(["status" => "error", "message" => "Invalid email format"]);
        exit;
    }
    
    // Load existing users
    $users = loadUsers();
    
    // Check if username or email already exists
    foreach ($users as $user) {
        if ($user['username'] === $username || $user['email'] === $email) {
            echo json_encode(["status" => "error", "message" => "Username or email already exists"]);
            exit;
        }
    }
    
    // Add new user
    $users[] = [
        'username' => $username,
        'email' => $email,
        'created_at' => date('Y-m-d H:i:s')
    ];
    
    // Save updated users array
    saveUsers($users);
    echo json_encode(["status" => "success", "message" => "Registration successful!"]);
}
// Handle real-time username validation
else if (isset($_POST['username']) && !isset($_POST['submit'])) {
    $username = trim($_POST['username']);
    validateUsername($username);
}
// Handle real-time email validation
else if (isset($_POST['email']) && !isset($_POST['submit'])) {
    $email = trim($_POST['email']);
    validateEmail($email);
}
?>