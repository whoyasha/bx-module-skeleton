<?php
namespace Whoyasha;

Class ModuleInstall extends \CModule {
	
	function __construct() {
		
		$params = $this->SetSettings();
		
		foreach($params as $code => $set)
			$this->{$code} = $set;
	}

	function InstallFiles() {
		return true;
	}
	
	function UnInstallFiles() {
		return true;
	}
	
	function DoInstall() {
		global $DOCUMENT_ROOT, $APPLICATION;
		$this->InstallFiles();
		RegisterModule($this->MODULE_ID);
		RegisterModuleDependences("main", "onProlog", $this->MODULE_ID);
		$APPLICATION->IncludeAdminFile("Установка модуля " . $this->MODULE_ID, $DOCUMENT_ROOT.getLocalPath("modules/" . $this->MODULE_ID . "/install/step.php"));
	}
	
	function DoUninstall() {
		
		global $DOCUMENT_ROOT, $APPLICATION;
		$this->UnInstallFiles();
		UnRegisterModule($this->MODULE_ID);
		UnRegisterModuleDependences("main", "onProlog",  $this->MODULE_ID);
		$APPLICATION->IncludeAdminFile("Деинсталляция модуля " . $this->MODULE_ID, $DOCUMENT_ROOT.getLocalPath("modules/" . $this->MODULE_ID . "/install/unstep.php"));
		
		$log_dir = $_SERVER["DOCUMENT_ROOT"] . $this->MODULE_PATH . '/log';
		
		if ( is_dir($log_dir) ) {
			$this->RemoveDir($log_dir);
		}
	}
	
	public function SetSettings(){}

}