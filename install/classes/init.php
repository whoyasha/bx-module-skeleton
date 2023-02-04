<? 

namespace Whoyasha;

Class ModuleInit {
	
	public $ERRORS = false;
	protected $MODULE_CLASSES = [];
	
	function __construct() {
		
		$params = $this->SetSettings();
		
		$params["LOADER_CLASSNAME"] = "\\" . ucwords($params["VENDOR"]) . "\\BxUtils\\CLoader";

		foreach($params as $code => $set)
			$this->{$code} = $set;
			
		if ( isset($params["LIBS"]) )
			$this->loadLibs($params["LIBS"]);
	}
	
	public function get( $key ) {
		return $this->{$key};
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
		
		$check = class_exists($this->LOADER_CLASSNAME) ? "Y" : "N";

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
	
	public function SetSettings() {}
	
	public function setOptions() {
		
		$path = $_SERVER["DOCUMENT_ROOT"] . $this->MODULE_PATH . "/install/lib/options.php";
		
		if ( file_exists($path) ) {
			include($path);
			
			if ( class_exists('Whoyasha\ModuleOptions') )
				return new Whoyasha\ModuleOptions;
		}
		
		return false;
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
