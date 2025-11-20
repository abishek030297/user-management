<?php
// Inheritance: Teacher class extends User class
class Teacher extends User {
    private $subject;
    private $experience;
    
    public function __construct($name, $email, $subject, $experience) {
        parent::__construct($name, $email);
        $this->subject = $subject;
        $this->experience = $experience;
        $this->type = parent::TYPE_TEACHER;
    }
    
    // Implementing abstract method
    public function getRole() {
        return "Teacher";
    }
    
    // Polymorphism: Overriding parent method
    public function getDetails() {
        return parent::getDetails() . ", Subject: {$this->subject}, Experience: {$this->experience} years";
    }
    
    // Getters and Setters
    public function getSubject() {
        return $this->subject;
    }
    
    public function setSubject($subject) {
        $this->subject = $subject;
    }
    
    public function getExperience() {
        return $this->experience;
    }
    
    public function setExperience($experience) {
        $this->experience = $experience;
    }
}
?>