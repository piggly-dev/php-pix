<?php
namespace Piggly\Pix\Exceptions;

use Exception;

/**
 * Exception when QR Code is not supported.
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
class QRCodeNotSupported extends Exception
{
	/**
	 * Exception when does not have support to QR Code
	 * 
	 * @since 1.2.2
	 */
	public function __construct ()
	{ parent::__construct('Para gerar QR Codes, certifique-se de ter a versão `7.2` do PHP instalada e a extensão `gd`.'); }
}