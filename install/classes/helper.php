<?
namespace Whoyasha;

Class ModuleInstallHelper {

	public static function getSettings( $MODULE_ID ) {

		if ( $items = static::getSittingFromFile() ) {
		
			$VENDOR = $items["extra"]["installer-vendor"];
			$name = str_replace($VENDOR . "_", "", $MODULE_ID);

			foreach ( $items["extra"]["modules"] as $id => $item ) {
				$item_name = explode("/", $item["name"])[1];
				$item_name = !empty($item_name) ? $item_name : $item["name"];
			
				if ( $item_name == $name )
					$settings = $item;
			}
		
			$params = [
				"MODULE_ID"          => $MODULE_ID,
				"MODULE_NAME"        => $settings["module_name"],
				"VENDOR"             => $VENDOR,
				"AUTHORS"            => $items["authors"],
				"MODULE_DESCRIPTION" => $settings["module_description"],
				"MODULE_VERSION"     => "1.1.1",
				"MODULE_VERSION_DATE"=> "2023-20-20",
				"MODULE_PATH"        => getLocalPath("modules/" . $MODULE_ID),
			];
			
			if ( isset($settings["libs"]) )
				$params["LIBS"] = $settings["libs"];
				
				

			return \Bitrix\Main\Web\Json::encode($params);
		}
		
		return false;
	}
	
	public static function getSittingFromFile() {
		
		$settings_json = "/home/bitrix/composer.json";

		if ( !file_exists($settings_json) )
			$settings_json = $_SERVER["DOCUMENT_ROOT"] . "/composer.json";
	
		if ( !file_exists($settings_json) )
			$settings_json = $_SERVER["DOCUMENT_ROOT"] . getLocalPath("/composer.json");
	
		if ( file_exists($settings_json) ) {
			$file_content = file_get_contents($settings_json);
			return \Bitrix\Main\Web\Json::decode($file_content, true);
		}
		
		return false;
	}
	
	public static function dashesToCamelCase($string, $divider = "_") {
		$string = preg_replace('/[\'\/~`\!@#\$%\^&\*\+=\{\}\[\]\|;:"\<\>,\.\?\\\]/', "", $string);
		$str = str_replace($divider, '', \ucwords($string, $divider));
		return $str;
	}
	
	public static function config( $MODULE_ID ) {
		
	}
}
