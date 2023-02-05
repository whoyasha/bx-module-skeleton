<? 
namespace Whoyasha;

Class ModuleInit {

	public function __construct() {
		
		$params = $this->SetSettings();
		
		$params["LOADER_CLASSNAME"] = "\\" . ucwords($params["VENDOR"]) . "\\BxUtils\\CLoader";

		foreach($params as $code => $set)
			$this->{$code} = $set;
			
		if ( isset($params["LIBS"]) )
			$this->loadLibs($params["LIBS"]);
		
		return $this;
	}
	
	public function get( $key ) {
		return $this->{$key};
	}
	
	public function Options() {
		return new ModuleOptions($this->MODULE_ID);
	}
	
	private function includeCLoader() {
		
		$path = $_SERVER["DOCUMENT_ROOT"] . $this->MODULE_PATH . "/bx_utils/" . ucwords($this->VENDOR) . "/BxUtils/CLoader.php";

		if ( !class_exists($this->LOADER_CLASSNAME) && file_exists($path) )
			require_once($path);

		if ( !class_exists($this->LOADER_CLASSNAME) )
			return false;

		return $this;
	}
	
	public function loadLibs( array $libs = [], $debug = false ) {

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
			
		return $this;
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