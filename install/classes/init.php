<? 

namespace Whoyasha;

use Exception;

Class ModuleInit {
	
	private static ?ModuleInit $instance = null;

	/**
	 * gets the instance via lazy initialization (created on first usage)
	 */
	public static function getInstance(): ModuleInit
	{
		if (static::$instance === null) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	/**
	 * is not allowed to call from outside to prevent from creating multiple instances,
	 * to use the singleton, you have to obtain the instance from Singleton::getInstance() instead
	 */
	private function __construct()
	{
	}

	/**
	 * prevent the instance from being cloned (which would create a second instance of it)
	 */
	private function __clone()
	{
	}

	/**
	 * prevent from being unserialized (which would create a second instance of it)
	 */
	public function __wakeup()
	{
		throw new Exception("Cannot unserialize singleton");
	}
	
	public function init() {
		
		$params = $this->SetSettings();
		
		$params["LOADER_CLASSNAME"] = "\\" . ucwords($params["VENDOR"]) . "\\BxUtils\\CLoader";

		foreach($params as $code => $set)
			$this->{$code} = $set;
			
		if ( isset($params["LIBS"]) )
			$this->loadLibs($params["LIBS"]);
		
		return $this;
	}
	
	public function getSettings( $key ) {
		return $this->{$key};
	}
	
	public function getOptions( $key ) {
		return $key;
	}
	
	public function setOptions( $key, $value ) {
		return "Set Options here";
	}
	
	private function includeCLoader() {
		
		$path = $_SERVER["DOCUMENT_ROOT"] . $this->MODULE_PATH . "/bx_utils/" . ucwords($this->VENDOR) . "/BxUtils/CLoader.php";

		if ( !class_exists($this->LOADER_CLASSNAME) && file_exists($path) )
			require_once($path);

		if ( !class_exists($this->LOADER_CLASSNAME) )
			return false;

		return true;
	}
	
	public function loadLibs( array $libs = [], $debug = false ) : bool {

		if ( !class_exists($this->LOADER_CLASSNAME) ) 
			$this->includeCLoader();

		$classes = [];

		foreach ( $libs as $lib ) {
			$lib_path = $_SERVER["DOCUMENT_ROOT"] . $this->MODULE_PATH . "/" . $lib;

			if ( is_dir($lib_path) ) {
				$lib = $this->LOADER_CLASSNAME::load($this->MODULE_PATH . "/" . $lib);
				if ( $this->checkClasses($lib, $debug) )
					$classes = array_merge($lib, $classes);
			} else {
				$this->ERRORS = "Директория для подключения классов " . $lib_path . " не существует.";
			}
		}
	
		if ( count($classes) == 0 )
			return false;
	
		if ( array_key_exists("ERRORS", $classes) ) 
			$this->ERRORS = implode("<br/>", $classes["ERRORS"]);

		$this->MODULE_CLASSES = count($this->MODULE_CLASSES) > 0 ? array_merge($this->MODULE_CLASSES, $classes) : $classes;
		return true;
	}
	
	public function checkClasses($lib, $debug = false ) {
		if ( 	$lib["FATAL_ERRORS"] && 
				$lib["FATAL_ERRORS"]["IN"] == $this->MODULE_ID ) {
					$this->ERRORS = $lib["FATAL_ERRORS"]["MESSAGE"];
					return false;
				}
	
		return true;
	}
}

// Class ModuleInit {
// 	
// 	public $ERRORS = false;
// 	protected $MODULE_CLASSES = [];
// 	
// 	function __construct() {
// 		
// 		$params = $this->SetSettings();
// 		
// 		$params["LOADER_CLASSNAME"] = "\\" . ucwords($params["VENDOR"]) . "\\BxUtils\\CLoader";
// 
// 		foreach($params as $code => $set)
// 			$this->{$code} = $set;
// 			
// 		if ( isset($params["LIBS"]) )
// 			$this->loadLibs($params["LIBS"]);
// 	}
// 	
// 	public function get( $key ) {
// 		return $this->{$key};
// 	}
// 	
// 	private function includeCLoader() {
// 		
// 		$path = $_SERVER["DOCUMENT_ROOT"] . $this->MODULE_PATH . "/bx_utils/" . ucwords($this->VENDOR) . "/BxUtils/CLoader.php";
// 
// 		if ( !class_exists($this->LOADER_CLASSNAME) && file_exists($path) )
// 			require_once($path);
// 
// 		if ( !class_exists($this->LOADER_CLASSNAME) )
// 			return false;
// 
// 		return true;
// 	}
// 	
// 	public function loadLibs( array $libs = [], $debug = false ) : bool {
// 		
// 		$check = class_exists($this->LOADER_CLASSNAME) ? "Y" : "N";
// 
// 		if ( !class_exists($this->LOADER_CLASSNAME) ) 
// 			$this->includeCLoader();
// 
// 		$classes = [];
// 
// 		foreach ( $libs as $lib ) {
// 			$lib_path = $_SERVER["DOCUMENT_ROOT"] . $this->MODULE_PATH . "/" . $lib;
// 
// 			if ( is_dir($lib_path) ) {
// 				$lib = $this->LOADER_CLASSNAME::load($this->MODULE_PATH . "/" . $lib);
// 				if ( $this->checkClasses($lib, $debug) )
// 					$classes = array_merge($lib, $classes);
// 			} else {
// 				$this->ERRORS = "Директория для подключения классов " . $lib_path . " не существует.";
// 			}
// 		}
// 	
// 		if ( count($classes) == 0 )
// 			return false;
// 	
// 		if ( array_key_exists("ERRORS", $classes) ) 
// 			$this->ERRORS = implode("<br/>", $classes["ERRORS"]);
// 
// 		$this->MODULE_CLASSES = count($this->MODULE_CLASSES) > 0 ? array_merge($this->MODULE_CLASSES, $classes) : $classes;
// 		return true;
// 	}
// 	
// 	public function SetSettings() {}
// 	
// 	public function setOptions() {
// 		
// 		$path = $_SERVER["DOCUMENT_ROOT"] . $this->MODULE_PATH . "/install/lib/options.php";
// 		
// 		if ( file_exists($path) ) {
// 			include($path);
// 			
// 			if ( class_exists('Whoyasha\ModuleOptions') )
// 				return new Whoyasha\ModuleOptions;
// 		}
// 		
// 		return false;
// 	}
// 	
// 	public function checkClasses($lib, $debug = false ) {
// 		if ( 	$lib["FATAL_ERRORS"] && 
// 				$lib["FATAL_ERRORS"]["IN"] == $this->MODULE_ID ) {
// 					$this->ERRORS = $lib["FATAL_ERRORS"]["MESSAGE"];
// 					return false;
// 				}
// 	
// 		return true;
// 	}
// }