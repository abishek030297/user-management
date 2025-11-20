<?php
// Abstract base class - cannot be instantiated directly
// Implements interfaces and uses traits
abstract class User implements Loggable, Exportable {
    use LoggerTrait, TimestampTrait;
    
    protected $id;
    protected $name;
    protected $email;
    protected $type;
    
    const TYPE_STUDENT = 'student';
    const TYPE_TEACHER = 'teacher';
    
    public function __construct($name, $email) {
        $this->name = $name;
        $this->email = $email;
        $this->setTimestamps(); // Initialize timestamps
        $this->logAction("User created: $name");
    }
    
    // Abstract method - must be implemented by child classes
    abstract public function getRole();
    
    // Interface implementation
    public function toArray() {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'type' => $this->type,
            'role' => $this->getRole(),
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt
        ];
    }
    
    public function toJSON() {
        return json_encode($this->toArray(), JSON_PRETTY_PRINT);
    }
    
    // Getters and setters with encapsulation
    public function getId() { return $this->id; }
    public function setId($id) { 
        $this->id = $id; 
        $this->logAction("User ID set: $id");
    }
    
    public function getName() { return $this->name; }
    public function setName($name) { 
        $this->name = $name; 
        $this->setTimestamps();
    }
    
    public function getEmail() { return $this->email; }
    public function setEmail($email) { 
        $this->email = $email; 
        $this->setTimestamps();
    }
    
    public function getType() { return $this->type; }
    
    // Can be overridden by child classes (polymorphism)
    public function getDetails() {
        return "Name: {$this->name}, Email: {$this->email}";
    }
    
    // Static method - called on class, not object
    public static function getUserTypes() {
        return [
            self::TYPE_STUDENT => 'Student',
            self::TYPE_TEACHER => 'Teacher'
        ];
    }
}
?>