<?php
namespace Piggly\Pix\Emv;

use Piggly\Pix\Exceptions\InvalidEmvFieldException;

/**
 * Base EMV field object.
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
abstract class AbstractField
{
	/**
	 * Field id.
	 *
	 * @var string
	 * @since 2.0.0
	 */
	protected $id;

	/**
	 * Field name.
	 *
	 * @var string
	 * @since 2.0.0
	 */
	protected $name;

	/**
	 * Field size.
	 *
	 * @var integer
	 * @since 2.0.0
	 */
	protected $size;

	/**
	 * Is required?
	 *
	 * @var bool
	 * @since 2.0.0
	 */
	protected $required = false;

	/**
	 * Get field value.
	 *
	 * @since 2.0.0
	 * @return string
	 */
	abstract public function getValue () : ?string;

	/**
	 * Get if it is required.
	 *
	 * @since 2.0.0
	 * @return bool
	 */
	public function isRequired ()
	{ return $this->required; }

	/**
 	 * Set if it is required.
	 *
	 * @param bool $required Is required?
	 * @since 2.0.0
	 * @return self
	 */
	public function required ( bool $required )
	{ $this->required = $required; return $this; }

	/**
	 * Get field maximum size.
	 *
	 * @since 2.0.0
	 * @return integer
	 */
	public function getSize () : int
	{ return $this->size; }

	/**
 	 * Set field size.
	 *
	 * @param integer $size Field size.
	 * @since 2.0.0
	 * @return self
	 */
	public function setSize ( int $size )
	{ $this->size = $size; return $this; }

	/**
	 * Get field name.
	 *
	 * @since 2.0.0
	 * @return string
	 */
	public function getName () : string
	{ return $this->name; }

	/**
 	 * Set field name.
	 *
	 * @param string $name Field name.
	 * @since 2.0.0
	 * @return self
	 */
	public function setName ( string $name )
	{ $this->name = $name; return $this; }

	/**
	 * Get field id.
	 *
	 * @since 2.0.0
	 * @return string
	 */
	public function getId () : string
	{ return $this->id; }

	/**
 	 * Set field id.
	 *
	 * @param string $id Field id.
	 * @since 2.0.0
	 * @return self
	 */
	public function setId ( string $id )
	{ $this->id = $id; return $this; }

	/**
 	 * Export field to emv format: ID + LENGTH + VALUE.
	 *
	 * @since 2.0.0
	 * @return string
	 * @throws InvalidEmvFieldException When field is wrong.
	 */
	public function export () : string
	{
		$value = $this->getValue();

		if ( \is_null($value) )
		{ 
			if ( $this->required ) 
			{ throw new InvalidEmvFieldException($this->name??'Unknown', 'null', 'Required'); }
			else 
			{ return ''; } 
		}

		$len = \str_pad( \strlen($value), 2, '0', STR_PAD_LEFT );
		return $this->id.$len.$value;
	}
}