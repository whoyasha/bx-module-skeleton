<?
namespace Whoyasha;

Class ModuleParams {

	public static function get($MODULE_ID) {

		$settings_json = "/home/bitrix/composer.json";
		
		if ( !file_exists($settings_json) )
			$settings_json = $_SERVER["DOCUMENT_ROOT"] . "/composer.json";
			
		if ( !file_exists($settings_json) )
			$settings_json = $_SERVER["DOCUMENT_ROOT"] . getLocalPath("/composer.json");
		
		if ( file_exists($settings_json) ) {
			
			$settings = NULL;
			$file_content = file_get_contents($settings_json);
			$items = \Bitrix\Main\Web\Json::decode($file_content, true);
		
			$VENDOR = $items["extra"]["installer-vendor"];
			$name = str_replace($VENDOR . "_", "", $MODULE_ID);

			foreach ( $items["extra"]["modules"] as $id => $item)
				if ( $item["name"] == $name )
					$settings = $item;
		
			$params = [
				"MODULE_ID"          => $MODULE_ID,
				"MODULE_NAME"        => $settings["module_name"],
				"VENDOR"             => $VENDOR,
				"AUTHORS"            => $items["authors"],
				"MODULE_DESCRIPTION" => $settings["module_description"],
				"MODULE_VERSION"     => "0.0.0",
				"MODULE_PATH"        => getLocalPath("modules/" . $MODULE_ID),
			];
			
			if ( isset($settings["libs"]) )
				$params["LIBS"] = $settings["libs"];
				
				

			return \Bitrix\Main\Web\Json::encode($params);
		}
		
		return false;
	}
}
