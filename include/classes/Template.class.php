<?php
if(!defined('INSIDE')){ die("attemp hacking");}

class Template {
	private static $instance;
	private $content;
	private $array_t = array();

	public static function getInstance() {
		if (!self::$instance) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function __construct($templatename) {
		$filename = TEMPLATE_DIR . '/' . $templatename . ".phtml";
		$this->content = @file_get_contents($filename) or die("Template \"{$filename}\" not found");
	}

	public function addAll($array) {
		foreach($array as $key => $val)
			$this->add($key, $val);
	}
	
	public function add($key, $val) {
		$this->array_t[$key] = $val;
	}

	public function display($ret = false) {
		$print = preg_replace_callback('#\{([a-z0-9\-_]*?)\}#Ssi', function ($matches) {
			if (isset($this->array_t[$matches[1]]))
				return $this->array_t[$matches[1]];
		}, $this->content);
		if ($ret) {
			echo $print;
		} else {
			return $print;
		}
	}

	private function __clone() {
	}

	private function __wakeup() {
	}
}