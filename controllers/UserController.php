<?php
class UserController {
    private $db;
    private $table_name = "users";
    
    // Dependency injection via constructor
    public function __construct(PDO $db) {
        $this->db = $db;
    }
    
    public function createUser($user) {
        $query = "INSERT INTO users SET name=:name, email=:email, type=:type, grade=:grade, major=:major, subject=:subject, experience=:experience";
        $stmt = $this->db->prepare($query);
        
        // Store values in variables for binding
        $name = $user->getName();
        $email = $user->getEmail();
        $type = $user->getType();
        
        // Initialize type-specific variables
        $grade = $major = $subject = $experience = null;
        
        // Check object type and set appropriate values
        if ($user instanceof Student) {
            $grade = $user->getGrade();
            $major = $user->getMajor();
        } else if ($user instanceof Teacher) {
            $subject = $user->getSubject();
            $experience = $user->getExperience();
        }
        
        // Bind parameters
        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":type", $type);
        $stmt->bindParam(":grade", $grade);
        $stmt->bindParam(":major", $major);
        $stmt->bindParam(":subject", $subject);
        $stmt->bindParam(":experience", $experience);
        
        return $stmt->execute();
    }
    
    public function getUsers() {
        $query = "SELECT * FROM users ORDER BY id DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Factory method pattern - creates objects based on type
    public function createUserObject($type, $name, $email, $extra_data = []) {
        switch($type) {
            case User::TYPE_STUDENT:
                return new Student($name, $email, $extra_data['grade'], $extra_data['major']);
            case User::TYPE_TEACHER:
                return new Teacher($name, $email, $extra_data['subject'], $extra_data['experience']);
            default:
                throw new Exception("Invalid user type");
        }
    }

    // Create user object from database data
    public function createUserObjectFromData($userData) {
        $extra_data = [];
        
        if ($userData['type'] === User::TYPE_STUDENT) {
            $extra_data = [
                'grade' => $userData['grade'],
                'major' => $userData['major']
            ];
        } else if ($userData['type'] === User::TYPE_TEACHER) {
            $extra_data = [
                'subject' => $userData['subject'],
                'experience' => $userData['experience']
            ];
        }
        
        $user = $this->createUserObject($userData['type'], $userData['name'], $userData['email'], $extra_data);
        
        // Set the ID from database
        if (isset($userData['id'])) {
            $user->setId($userData['id']);
        }
        
        return $user;
    }
    
    // Method injection example - accepts Exportable interface
    public function exportUserData(Exportable $user, $format = 'array') {
        return ($format === 'json') ? $user->toJSON() : $user->toArray();
    }
}
?>