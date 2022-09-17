<?php
class ClassAutoloader {
    public function __construct() {
        spl_autoload_register(array($this, 'loader'));
    }
    private function loader($className) {
        //print 'Trying to load '. $className. ' via '. __METHOD__. "()<br>";
        require_once("include/classes/".$className.".class.php");
    }
}
$autoloader = new ClassAutoloader();
?>