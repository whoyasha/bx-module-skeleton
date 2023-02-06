<?php
namespace Whoyasha;

Class ModuleOptions {
	
	protected $MODULE_ID = "";
	
	public function __construct($MODULE_ID) {
		$this->MODULE_ID = $MODULE_ID;
	}
	
	public function getOptions( $key = false ) {
		return $key;
	}
	
	public function setOptions( $key = false, $value = false) {
		return $key .' => ' . $value;
	}
}