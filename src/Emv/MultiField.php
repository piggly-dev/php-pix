<?php
namespace Piggly\Pix\Emv;

use Piggly\Pix\Exceptions\InvalidEmvFieldException;

/**
 * Multi-field EMV object.
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
class MultiField extends AbstractField
{
	/**
	 * Min ID to any field added.
	 *
	 * @var int
	 * @since 2.0.0
	 */
	protected $minId;

	/**
	 * Max ID to any field added.
	 *
	 * @var int
	 * @since 2.0.0
	 */
	protected $maxId;

	/**
	 * Children fields.
	 *
	 * @var array<Field>
	 * @since 2.0.0
	 */
	protected $fields = [];

	/**
	 * Create a new field with defaults.
	 *
	 * @param string $id
	 * @param string $name
	 * @param integer $size
	 * @param boolean $required
	 * @param string $value
	 * @param integer $minId
	 * @param integer $maxId
	 * @since 2.0.0
	 * @return void
	 */
	public function __construct (
		string $id = null, 
		string $name = null, 
		int $size = null, 
		bool $required = null, 
		int $minId = null,
		int $maxId = null 
	)
	{
		if ( !\is_null($id) ) $this->setId($id);
		if ( !\is_null($name) ) $this->setName($name);
		if ( !\is_null($size) ) $this->setSize($size);
		if ( !\is_null($required) ) $this->required($required);
		if ( !\is_null($minId) ) $this->setMinId($minId);
		if ( !\is_null($maxId) ) $this->setMaxId($maxId);
	}

	/**
	 * Get field value.
	 *
	 * @since 2.0.0
	 * @return string
	 */
	public function getValue () : ?string
	{
		if ( empty($this->fields) )
		{ return ''; }

		$value = '';

		foreach ( $this->fields as $field )
		{ $value .= $field->export(); }

		return $value;
	}

	/**
	 * Add a child field to this.
	 * Throw an exception if field id 
	 * is not allowed.
	 *
	 * @param AbstractField $field
	 * @since 2.0.0
	 * @return self
	 * @throws InvalidEmvFieldException
	 */
	public function addField ( AbstractField $field )
	{ 
		$id = \intval($field->getId());

		if ( $id < $this->minId || $id > $this->maxId )
		{ throw new InvalidEmvFieldException($this->name, $field->getId(), \sprintf('O ID não está dento do limite `%s` até `%s` aceito pelo campo.', $this->minId, $this->maxId)); }

		$this->fields[$field->getId()] = $field; return $this; 
	}

	/**
	 * Get a child field by $id.
	 *
	 * @param string $id
	 * @since 2.0.0
	 * @return Field|MultiField|null
	 */
	public function getField ( string $id )
	{ return $this->fields[$id] ?? null; }

	/**
	 * Check if has child field by $id.
	 *
	 * @param string $id
	 * @since 2.0.0
	 * @return bool
	 */
	public function hasField ( string $id ) : bool
	{ return isset($this->fields[$id]); }

	/**
	 * Remove a child field by $id.
	 *
	 * @param string $id
	 * @since 2.0.0
	 * @return self
	 */
	public function removeField ( string $id )
	{ unset($this->fields[$id]); return $this; }

	/**
	 * Get max ID to any field added.
	 *
	 * @since 2.0.0
	 * @return int
	 */
	public function getMaxId () : int
	{ return $this->maxId; }

	/**
 	 * Set max ID to any field added.
	 *
	 * @param int $maxId Max ID to any field added.
	 * @since 2.0.0
	 * @return self
	 */
	public function setMaxId ( int $maxId )
	{ $this->maxId = $maxId; return $this; }

	/**
	 * Get min ID to any field added.
	 *
	 * @since 2.0.0
	 * @return int
	 */
	public function getMinId () : int
	{ return $this->minId; }

	/**
 	 * Set min ID to any field added.
	 *
	 * @param int $minId Min ID to any field added.
	 * @since 2.0.0
	 * @return self
	 */
	public function setMinId ( int $minId )
	{ $this->minId = $minId; return $this; }
}