<?php
namespace Vdnh\BxUtils;
	
use \Bitrix\Main\Loader;

use \Bitrix\Main\SystemException,
	\Bitrix\Main\Application,
	\Bitrix\Main\HttpRequest,
	\Bitrix\Main\Web\Uri;

class Helper {
	
	/**
	 * Подставляет ведущий ноль
	 */
	public static function setLZ( $num ) {
		return str_pad($num, 2, 0, STR_PAD_LEFT);
	}
	
	/**
	 * Убирает ведущий ноль
	 */
	public static function unsetLZ( $num ) {
		return (int) ltrim($num, "0");
	}
	
	/**
	 * Склонение по числу
	 */
	public function numDecline( $number, $titles, $show_number = false ){
		if( is_string( $titles ) ){
			$titles = preg_split( '/, */', $titles );
		}
		// когда указано 2 элемента
		if( empty( $titles[2] ) ){
			$titles[2] = $titles[1];
		}
		$cases = [ 2, 0, 1, 1, 1, 2 ];
		$intnum = abs( (int) strip_tags( $number ) );
		$title_index = ( $intnum % 100 > 4 && $intnum % 100 < 20 )
			? 2
			: $cases[ min( $intnum % 10, 5 ) ];
	
		return ( $show_number ? "$number " : '' ) . $titles[ $title_index ];
	}
	
	/**
	 * Преобразует camelCase в snake_case
	 */
	public static function camelCaseToDashed($string) {
		return strtolower(preg_replace('/([a-zA-Z])(?=[A-Z])/', '$1_', $string));
	}
	
	/**
	 * Преобразует snake_case в camelCase
	 */
	public static function dashesToCamelCase($string, $divider = "_", $capitalizeFirstCharacter = false) {
		
		$string = preg_replace('/[\'\/~`\!@#\$%\^&\*\+=\{\}\[\]\|;:"\<\>,\.\?\\\]/', "", $string);
	
		$str = str_replace($divider, '', ucwords($string, $divider));
	
		if (!$capitalizeFirstCharacter)
			$str = lcfirst($str);
	
		return $str;
	}
	
	/**
	 * Bx translit
	 * @mixed \Cutil
	 */
	public function Translit($str, $divider = "_") {
		return \Cutil::translit($str, "ru", ["replace_space" => $divider, "replace_other" => $divider]);
	}
	
	/**
	 * Bx any getRequest methods to array
	 * @mixed \Bitrix\Main\Application
	 */
	public static function getRequest() : array {
		$request 	= Application::getInstance()->getContext()->getCurrent()->getRequest();
		
		return [
			"METHOD"  => $request->isPost() ? "POST" : "GET",
			// "QUERIES" => $request->isPost() ? $request->getPostList() : $request->getQueryList(),
			"QUERIES" => $request->getValues(),
			"IS_AJAX" => $request->isAjaxRequest(),
			"CURRENT" => $request->getRequestUri(),
		];
	}
	
	/**
	 * Bx work with url
	 * @mixed \Bitrix\Main\Web\Uri
	 * @mixed \Bitrix\Main\HttpRequest
	 */
	public static function MakeUrl($uri_str, $add_params = [], $delete_params = []) {
		
		$uri = new Uri($uri_str);
		$uri->deleteParams(HttpRequest::getSystemParameters());
		$uri->deleteParams($delete_params);
		$uri->addParams($add_params);

		return $uri->getUri();
	}
	
	/**
	 * Create camelCase method from snake_case strings of class and method
	 */
	public static function getMethod( $class, $method , $check = false) {

		if ( method_exists(static::class, 'dashesToCamelCase') )
			$camelCase = static::dashesToCamelCase( ToLower($method) );
				
		if ( $check )
			static::showMess( $camelCase, true, "camelCase" );	
		
		if ( method_exists($class, $camelCase) )
			return $camelCase;
					
		return false;
	}
	
	public function showMess( $mess, $is_array = false, $type = "danger" ) {
		$colors = [
			"exception" => "blue",
			"danger" 	=> "red",
			"warnings" 	=> "orange",
			"success" 	=> "green"
		];

		if ( $is_array ) {
			echo"<pre class=\"var_dump\" style=\"max-width: 70vw; max-height: 100vh; overflow: auto;\">" . $type . " : "; print_r( $mess ); echo"</pre>";
		} else {
			$mess = $type == "exception" ? $mess->getMessage() . "<hr style=\"border:none;border-top: 1px solid white;\"/><small>file: " . $mess->getFile() . "<br/>line: " . $mess->getLine() . "</small>" : $mess;
			echo "<div class=\"var_dump\" style=\"color: white; background-color: " . $colors[$type] . "\">" . $mess . "</div>";
		}
	}
	
	/**
	 * Удаляем пустые элементы массива
	 */
	public function unsetEmpty( $array ) {
		$empty = [""];
		return array_diff($array, $empty);
	}
	
	/**
	 * @return array $array  
	 */
	public function sortBy($array, $orderBy, $orderBy1 = false ) : array {
		
		uasort($array, function ($a, $b) use ($orderBy) {
			
			$result = 0;
			foreach ( $orderBy as $key => $value ) {
				
				if ($a[$key] == $b[$key])
					continue;
		 
				$result = ($a[$key] < $b[$key]) ? -1 : 1;
					
				if ($value == 'desc')
					$result = -$result;
		 
				break;
			}
			
			return $result;
		});
		
		if ( $orderBy1 )
			static::sortBy($array, $orderBy1);
		
		return $array;
	}
}