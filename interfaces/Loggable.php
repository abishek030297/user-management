<?php
// Interface defines contract for logging functionality
interface Loggable {
    public function logAction($action);
    public function getLogDetails();
}
?>