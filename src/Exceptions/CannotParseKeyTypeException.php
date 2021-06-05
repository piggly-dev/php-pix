<?php
namespace Piggly\Pix\Exceptions;

use Exception;

/**
 * Exception when cannot parse pix key type.
 *
 * @package \Piggly\Pix
 * @subpackage \Piggly\Pix\Exceptions
 * @version 1.2.0
 * @since 1.2.0
 * @category Exception
 * @author Caique Araujo <caique@piggly.com.br>
 * @author Piggly Lab <dev@piggly.com.br>
 * @license MIT
 * @copyright 2021 Piggly Lab <dev@piggly.com.br>
 */
class CannotParseKeyTypeException extends Exception
{
	/**
	 * @since 1.2.0
	 * @var string $pixKey
	 */
	protected $pixKey;

	/**
	 * Get pix key.
	 * @since 1.2.0
	 * @var string $pixKey
	 */
	public function getPixKey () : string
	{ return $this->pixKey; }

	/**
	 * Exception when cannot parse key type from a pix key.
	 * 
	 * @since 1.2.0
	 * @param string $pixKey
	 */
	public function __construct ( string $pixKey )
	{
		$this->pixKey = $pixKey;

		parent::__construct(
			\sprintf('Não é possível determinar o tipo da chave para `%s`', $pixKey)
		);
	}
}