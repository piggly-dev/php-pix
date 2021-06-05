<?php
namespace Piggly\Pix\Exceptions;

use Exception;

/**
 * Exception when EMV id is required and not set.
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
class EmvIdIsRequiredException extends Exception
{
	/**
	 * @since 1.2.0
	 * @var string $emvId
	 */
	protected $emvId;

	/**
	 * Get emvId.
	 * @since 1.2.0
	 * @var string $emvId
	 */
	public function getEmvId () : string
	{ return $this->emvId; }

	/**
	 * Exception when the emv id is required.
	 * 
	 * @since 1.2.0
	 * @param string $emvId
	 */
	public function __construct ( string $emvId )
	{
		$this->emvId = $emvId;
		
		parent::__construct(
			\sprintf('O campo EMV `%s` é obrigatório.', $emvId )
		);
	}
}