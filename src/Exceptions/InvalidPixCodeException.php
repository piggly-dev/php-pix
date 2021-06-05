<?php
namespace Piggly\Pix\Exceptions;

use Exception;

/**
 * Exception when something went wrong to
 * Pix code.
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
class InvalidPixCodeException extends Exception
{
	/**
	 * @since 1.2.0
	 * @var string $pixCode
	 */
	protected $pixCode;

	/**
	 * Get pix key.
	 * @since 1.2.0
	 * @var string $pixCode
	 */
	public function getPixCode () : string
	{ return $this->pixCode; }

	/**
	 * Exception when the pix code is invalid.
	 * 
	 * @since 1.2.0
	 * @param string $pixCode
	 */
	public function __construct ( string $pixCode )
	{
		$this->pixCode = $pixCode;

		parent::__construct(
			\sprintf('O código Pix `%s` é invalido.', $pixCode)
		);
	}
}