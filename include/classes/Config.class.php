<?php
if(!defined('INSIDE')){ die("attemp hacking");}

class Config {
    private $config;
    private static $instance;
    
    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function __construct() {
        $this->config["title"]      = "";
        $this->config["db_server"]  = "localhost";
        $this->config["db_port"]    = 5432;
        $this->config["db_user"]    = "vpn";
        $this->config["db_pass"]    = "ds2f4ds5f4dsf54sd54fds54fs";
        $this->config["db_name"]    = "vpn";
        $this->config["prefix"]     = "";
        $this->config["secretword"] = "s5d4fs684345543416sf3s348354";
        
		$this->config["sqlite_db"]  = "vpn.db";
		
        $this->config["cookiename"] = "vpnPanel";
        //$this->config["debug"] = true;
    }

    public function getConfig() {
        return $this->config;
    }

    private function __clone() {
    }

    private function __wakeup() {
    }
}