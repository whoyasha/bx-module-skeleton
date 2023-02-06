<?php
use \Bitrix\Main\Loader;	
	
/******************************************
 * Загружает классы из указанной директории ($path)
 * Названия файлов и папок обязательно UpperCamelCase
 * Формирование namespace по пути файла. Example: file /Vdnh/Tabloid/VDate.php == class \Vdnh\Tabloid\VDate
 * Все папки /lang/ в указанной и вложенных директориях игнорируются
 * Все папки /draft/ в указанной и вложенных директориях игнорируются
 * если файл есть, но класс в нём не определён - добавляет ERRORS в $result
 * если директория по пути пуста, или не определён ни один класс - вернёт массив FATAL_ERRORS
 ******************************************/

final class CLoader {
	
	/** @param string $path Lib directory path
	 *  @return array|null
	 */
	public static function load($path = CLOADER_DEFAULT_LIB) {
			
		$result = static::getClassArray($path);
			
		if(count($result) == 0) {
			$IS_MODULE = array_slice(explode("/", $path), -3, 1)[0] == "modules";
			$MODULE_ID = array_slice(explode("/", $path), -2, 1)[0];
			$PLACE = $IS_MODULE ? "module" : "path";
			$PREF = $IS_MODULE ? "В модуле " : "По пути ";
			$IN = $IS_MODULE ? $MODULE_ID : $path;
			$MESS = $IS_MODULE ? " по пути ". $path : "";
				
			return ["FATAL_ERRORS" => ["IN" => $IN, "MESSAGE" => $PREF . $IN . $MESS . " не определён ни один класс."]];
		}
				
		Loader::registerAutoLoadClasses(null, $result);
				
		foreach($result as $class => $class_path)
			if(!class_exists($class)) {
				$result["ERRORS"][] = "Класс \"" . $class  . "\" не определён. Файл класса: \"" . $class_path . "\"";
			
			unset($result[$class]);
		}
		
		return $result;
	}
	
	private static function getClassArray($path) {
		
		$classes = array();
		$result = array();
		$base_dir = $_SERVER["DOCUMENT_ROOT"] . $path;
		$dirs = static::getDirectories($base_dir, $base_dir, 0);

		for($i = 0; $i <= 1; $i++) {
			$input_dir = $i == 0 ? $dirs : $result;
			$dir = new \RecursiveArrayIterator($input_dir);
			$hasChildren = $dir->hasChildren();
			if($hasChildren === true) {
				$get_result = static::iterateChildren($dir)["children_result"];
				$get_classes = static::iterateChildren($dir)["classes_result"];
				if(count($get_result) > 0)
					$result = $get_result;
				if(count($get_classes) > 0)
					$classes = array_merge($classes, $get_classes);
			}
			unset($dir, $children, $hasChildren);
		}
		
		foreach($classes as $rsClasses)
			foreach($rsClasses as $resClasses)
				$arrClasses[] = $resClasses;
		
		foreach($arrClasses as $acl) 
			foreach($acl as $key => $aclss)
				$arClasses[$key] = $aclss;

		return $arClasses;
	}
	
	private static function iterateChildren($dir) {
		
		$result = array();
		foreach($dir as $child)
			$children[] = $dir->getChildren();	
		foreach($children as $key => $prep) {
			if(count(static::getClasses($prep)) > 0)
				$result["classes_result"][] = static::getClasses($prep);
			else
				$result["children_result"] = static::getChildrens($prep);
		}
		return $result;
	}
	
	private static function getChildrens($value) {
		
		$result = array();
		foreach($value as $key => $val)
			if($key != "classes")
				foreach($val as $class)
					$result[] = $class;
					
		return $result;
	}
	
	private static function getClasses($value) {
		
		$result = array();
		foreach($value as $key => $val)
			if($key == "classes")
				foreach($val as $name => $class)
					if(count($class) > 0 && !preg_match("/(lang)/", $name) && !preg_match("/(draft)/", $name))
						$result[] = array($name => $class);
						
		return $result;
	}
	
	private static function getDirectories($class_dir, $base_dir, $start_level = 0) {
		
		$result = array();
		foreach(scandir($base_dir) as $file) {
			if($file == "lang" || $file == "draft") continue;
			if($file == "." || $file == "..") continue;
			$dir = $base_dir . DIRECTORY_SEPARATOR . $file;
			
			if(is_dir($dir)) {
				$files = array();
				$current = explode($class_dir, $dir);
				$children =  static::getDirectories($class_dir, $dir, $start_level +1);
				$namespace = str_replace(DIRECTORY_SEPARATOR,"\\", $current[1]);

				if(count($children) > 0) 
					$result[$file][DIRECTORY_SEPARATOR] = $children;
				
				if(count($children) == 0) {
					foreach(scandir($dir) as $get_file) {
						if($get_file == "." || $get_file == "..") continue;
						$files[] = $get_file;
					}
					
					if(count($files) > 0) {
						foreach($files as $fl) {
							$path = str_replace($_SERVER["DOCUMENT_ROOT"], "", $dir);
							$classname = $namespace . "\\" . str_replace(".php","", $fl);
							$result[$file]["classes"][$classname] = $path . DIRECTORY_SEPARATOR . $fl;
						}
					}
				}
			}
		}
		return $result;
	}
}