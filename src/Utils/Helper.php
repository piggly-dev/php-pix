<?php
namespace Piggly\Pix\Utils;

/**
 * Cast data to another format.
 *
 * @package \Piggly\Pix
 * @subpackage \Piggly\Pix\Utils
 * @version 3.0.0
 * @since 3.0.0
 * @category Util
 * @author Caique Araujo <caique@piggly.com.br>
 * @author Piggly Lab <dev@piggly.com.br>
 * @license MIT
 * @copyright 2024 Piggly Lab <dev@piggly.com.br>
 */
class Helper
{
	/**
	 * Mixed data should be an array.
	 *
	 * @param mixed $mixed
	 * @since 3.0.0
	 * @return array
	 */
	public static function shouldBeArray ( $mixed ): bool
	{
		return empty($mixed) === false && \is_array($mixed);
	}

	/**
	 * Fill class with data.
	 *
	 * @param array $data
	 * @param object $class
	 * @param array $methods
	 * @since 3.0.0
	 * @return array Fields not filled.
	 */
	public static function fill(array $data, object $class, array $methods): array {
		foreach ( $methods as $field => $method )
		{
			if ( isset($data[$field]) || empty($data[$field]) === false )
			{
				$class->{$method}($data[$field]);
				unset($data[$field]);
			}
		}

		return $data;
	}
}