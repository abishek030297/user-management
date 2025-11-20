<?php
// Student class inherits from User
class Student extends User {
    private $grade;
    private $major;
    
    public function __construct($name, $email, $grade, $major) {
        parent::__construct($name, $email); // Call parent constructor
        $this->grade = $grade;
        $this->major = $major;
        $this->type = parent::TYPE_STUDENT;
    }
    
    // Implement abstract method from parent
    public function getRole() {
        return "Student";
    }
    
    // Override parent method (polymorphism)
    public function getDetails() {
        return parent::getDetails() . ", Grade: {$this->grade}, Major: {$this->major}";
    }
    
    // Add student-specific properties to export
    public function toArray() {
        $baseArray = parent::toArray();
        $baseArray['grade'] = $this->grade;
        $baseArray['major'] = $this->major;
        return $baseArray;
    }
    
    public function getGrade() { return $this->grade; }
    public function setGrade($grade) { $this->grade = $grade; }
    
    public function getMajor() { return $this->major; }
    public function setMajor($major) { $this->major = $major; }
}
?>