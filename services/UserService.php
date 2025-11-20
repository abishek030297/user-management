<?php
// Service class with dependency injection
class UserService {
    private $userController;
    private $logger;
    
    // Constructor injection - dependencies provided when object is created
    public function __construct(UserController $userController, LoggerTrait $logger = null) {
        $this->userController = $userController;
        $this->logger = $logger;
    }
    
    // Business logic separated from controller
    public function createUserWithValidation($userData) {
        try {
            $this->validateUserData($userData);
            
            // Factory pattern to create appropriate user object
            $user = $this->userController->createUserObject(
                $userData['type'],
                $userData['name'], 
                $userData['email'],
                $userData['extra_data']
            );
            
            $result = $this->userController->createUser($user);
            
            if ($result) {
                return ['success' => true, 'message' => 'User created successfully', 'user' => $user];
            }
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    
    public function exportUsers($format = 'array') {
        $users = $this->userController->getUsers();
        $exportData = [];
        
        foreach ($users as $userData) {
            try {
                // Use the new method to create user object from database data
                $user = $this->userController->createUserObjectFromData($userData);
                
                if ($user instanceof Exportable) {
                    if ($format === 'json') {
                        $exportData[] = json_decode($user->toJSON(), true);
                    } else {
                        $exportData[] = $user->toArray();
                    }
                }
            } catch (Exception $e) {
                // Skip invalid users and continue
                error_log("Error exporting user: " . $e->getMessage());
                continue;
            }
        }
        
        // Handle CSV format
        if ($format === 'csv') {
            return $this->convertToCSV($exportData);
        }
        
        return $exportData;
    }
    
    private function convertToCSV($data) {
        if (empty($data)) return 'No data available';
        
        // Start output buffering
        ob_start();
        
        // Open output stream
        $output = fopen('php://output', 'w');
        
        // Add headers (use first item's keys as column names)
        fputcsv($output, array_keys($data[0]));
        
        // Add data rows
        foreach ($data as $row) {
            fputcsv($output, $row);
        }
        
        fclose($output);
        $csv = ob_get_clean();
        
        return $csv;
    }
    
    private function validateUserData($data) {
        if (empty($data['name']) || empty($data['email'])) {
            throw new Exception("Name and email are required");
        }
    }
}
?>