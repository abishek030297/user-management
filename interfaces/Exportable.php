<?php
// Interface for classes that can export their data
interface Exportable {
    public function toArray();
    public function toJSON();
}
?>