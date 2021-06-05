<?php
namespace Piggly\Pix\Exceptions;

use Exception;

/**
 * Exception when a field is invalid.
 * Used to API payloads.
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
class InvalidFieldException extends Exception
{
	/**
	 * @since 1.2.0
	 * @var string $fieldName
	 */
	protected $fieldName;

	/**
	 * @since 1.2.0
	 * @var string $fieldValue
	 */
	protected $fieldValue;
	
	/**
	 * @since 1.2.0
	 * @var string $errorMessage
	 */
	protected $fieldMessage;

	/**
	 * Get fieldName.
	 * @since 1.2.0
	 * @var string $fieldName
	 */
	public function getFieldName () : string
	{ return $this->fieldName; }

	/**
	 * Get fieldValue.
	 * @since 1.2.0
	 * @var string $fieldValue
	 */
	public function getFieldValue () : string
	{ return $this->fieldValue; }

	/**
	 * Get fieldMessage.
	 * @since 1.2.0
	 * @var string $fieldMessage
	 */
	public function getFieldMessage () : string
	{ return $this->fieldMessage; }

	/**
	 * Exception when the field value is invalid.
	 * 
	 * @since 1.2.0
	 * @param string $field
	 * @param string $value
	 * @param string $error
	 */
	public function __construct ( string $field, string $value, string $error )
	{
		$this->fieldName = $field;
		$this->fieldValue = $value;
		$this->fieldMessage = $error;

		parent::__construct(
			\sprintf('O valor `%s` para o campo `%s` é invalido: %s', $field, $value, $error )
		);
	}
}