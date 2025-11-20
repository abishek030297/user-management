<?php
// Trait provides logging functionality to any class
trait LoggerTrait {
    protected $logs = [];
    
    public function logAction($action) {
        $timestamp = date('Y-m-d H:i:s');
        $this->logs[] = "[$timestamp] $action";
    }
    
    public function getLogDetails() {
        return $this->logs;
    }
    
    public function getLastLog() {
        return end($this->logs) ?: 'No logs';
    }
}
?>