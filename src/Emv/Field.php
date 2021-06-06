<?php
namespace Piggly\Pix\Emv;

use Piggly\Pix\Exceptions\InvalidEmvFieldException;
use Piggly\Pix\Utils\Cast;

/**
 * Single-field EMV object.
 * 
 * @package \Piggly\Pix
 * @subpackage \Piggly\Pix\Emv
 * @version 2.0.0
 * @since 2.0.0
 * @category Emv
 * @author Caique Araujo <caique@piggly.com.br>
 * @author Piggly Lab <dev@piggly.com.br>
 * @license MIT
 * @copyright 2021 Piggly Lab <dev@piggly.com.br>
 */
class Field extends AbstractField
{
	/**
	 * Field value.
	 *
	 * @var string
	 * @since 2.0.0
	 */
	protected $value;

	/**
	 * Default field value.
	 *
	 * @var string
	 * @since 2.0.0
	 */
	protected $default;

	/**
	 * Create a new field with defaults.
	 *
	 * @param string $id
	 * @param string $name
	 * @param integer $size
	 * @param boolean $required
	 * @param string $value
	 * @param string $default
	 * @since 2.0.0
	 * @return void
	 */
	public function __construct (
		string $id = null, 
		string $name = null, 
		int $size = null, 
		bool $required = null, 
		string $default = null 
	)
	{
		if ( !\is_null($id) ) $this->setId($id);
		if ( !\is_null($default) ) $this->setDefault($default);
		if ( !\is_null($name) ) $this->setName($name);
		if ( !\is_null($size) ) $this->setSize($size);
		if ( !\is_null($required) ) $this->required($required);
	}

	/**
	 * Get field value.
	 *
	 * @since 2.0.0
	 * @return string
	 */
	public function getValue () : ?string
	{ return $this->value ?? $this->default ?? null; }

	/**
 	 * Set field value.
	  
	 * Will auto cut string if length
	 * is greater than size allowed.
	 *
	 * @param string|null $value Field value.
	 * @since 2.0.0
	 * @return self
	 */
	public function setValue ( ?string $value )
	{ 
		$len = \strlen($value);

		if ( $len > $this->size )
		{ $value = Cast::cutStr($this->name, $value, $this->size); }
		
		$this->value = $value; 
		return $this; 
	}

	/**
	 * Get default field value.
	 *
	 * @since 2.0.0
	 * @return string
	 */
	public function getDefault ()
	{ return $this->default; }

	/**
 	 * Set default field value.
	 *
	 * @param string|null $default Default field value.
	 * @since 2.0.0
	 * @return self
	 */
	public function setDefault ( ?string $default = null )
	{ $this->default = $default; return $this; }
}