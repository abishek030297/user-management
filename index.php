<?php
// Autoload classes automatically
spl_autoload_register(function ($class_name) {
    $directories = ['models/', 'controllers/', 'config/', 'services/', 'interfaces/', 'traits/'];
    foreach ($directories as $directory) {
        if (file_exists($directory . $class_name . '.php')) {
            require_once $directory . $class_name . '.php';
            return;
        }
    }
});

if (!isset($_GET['export'])) {
    require_once 'views/header.php';
}

// Dependency injection setup
$database = new Database();
$db = $database->getConnection();

// Inject dependencies into service
$userController = new UserController($db);
$userService = new UserService($userController);

// Handle form submission using service
if ($_POST && isset($_POST['add_user'])) {
    $user_type = $_POST['user_type'];
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    
    $extra_data = [];
    if ($user_type === User::TYPE_STUDENT) {
        $extra_data = ['grade' => $_POST['grade'], 'major' => $_POST['major']];
    } else if ($user_type === User::TYPE_TEACHER) {
        $extra_data = ['subject' => $_POST['subject'], 'experience' => $_POST['experience']];
    }
    
    $userData = [
        'type' => $user_type,
        'name' => $name,
        'email' => $email,
        'extra_data' => $extra_data
    ];
    
    // Use service for business logic
    $result = $userService->createUserWithValidation($userData);
    
    if ($result['success']) {
        echo "<p style='color: green;'>{$result['message']}</p>";
        // Demonstrate interface usage
        echo "<p><strong>Logs:</strong> " . $result['user']->getLastLog() . "</p>";
    } else {
        echo "<p style='color: red;'>{$result['message']}</p>";
    }
}

// Handle export request
if (isset($_GET['export'])) {
    $format = $_GET['format'] ?? 'json';
    $exportData = $userService->exportUsers($format);
    
    switch($format) {
        case 'json':
            header('Content-Type: application/json');
            header('Content-Disposition: attachment; filename="users_export.json"');
            echo json_encode($exportData, JSON_PRETTY_PRINT);
            exit;
            
        case 'csv':
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="users_export.csv"');
            echo $exportData;
            exit;
            
        case 'array':
            echo "<pre>" . print_r($exportData, true) . "</pre>";
            exit;
    }
}

require_once 'views/add_user.php';

// Add export buttons section
?>
<div class="export-section" style="margin: 20px 0; padding: 15px; background: #f8f9fa; border-radius: 5px;">
    <h3>Export Users Data</h3>
    <p>Download user data in different formats:</p>
    <div class="export-buttons">
        <a href="?export=1&format=json" class="btn btn-primary" style="display: inline-block; padding: 10px 15px; background: #007bff; color: white; text-decoration: none; border-radius: 4px; margin-right: 10px;">
            📥 Export as JSON
        </a>
        <a href="?export=1&format=csv" 
           style="display: inline-block; padding: 10px 15px; background: #17a2b8; color: white; text-decoration: none; border-radius: 5px; margin: 5px;">
            📥 Download CSV
        </a>
        <a href="?export=1&format=array" class="btn btn-secondary" style="display: inline-block; padding: 10px 15px; background: #6c757d; color: white; text-decoration: none; border-radius: 4px;">
            📥 Export as Array
        </a>
        <!-- <a href="javascript:void(0)" onclick="showExportPreview()" class="btn btn-info" style="display: inline-block; padding: 10px 15px; background: #17a2b8; color: white; text-decoration: none; border-radius: 4px; margin-left: 10px;">
            👁️ Preview Data
        </a> -->
    </div>
</div>

<script>
function showExportPreview() {
    // Simple AJAX to show preview without leaving page
    fetch('?export=1&format=array')
        .then(response => response.text())
        .then(data => {
            alert('User Data Preview:\n\n' + data);
        });
}
</script>

<?php
// Display users list
$users = $userController->getUsers();
require_once 'views/list_users.php';

require_once 'views/footer.php';
?>