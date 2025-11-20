<?php
// Trait for managing created/updated timestamps
trait TimestampTrait {
    protected $createdAt;
    protected $updatedAt;
    
    public function setTimestamps() {
        $now = date('Y-m-d H:i:s');
        if (empty($this->createdAt)) {
            $this->createdAt = $now;
        }
        $this->updatedAt = $now;
    }
    
    public function getCreatedAt() {
        return $this->createdAt;
    }
    
    public function getUpdatedAt() {
        return $this->updatedAt;
    }
}
?>