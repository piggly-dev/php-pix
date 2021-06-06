<?php
namespace Piggly\Pix\Api\Payloads\Concerns;

/**
 * Add extra fields behavior to a payload or entity.
 * 
 * @package \Piggly\Pix
 * @subpackage \Piggly\Pix\Api\Payloads\Concerns
 * @version 2.0.0
 * @since 2.0.0
 * @category Concern
 * @author Caique Araujo <caique@piggly.com.br>
 * @author Piggly Lab <dev@piggly.com.br>
 * @license MIT
 * @copyright 2021 Piggly Lab <dev@piggly.com.br>
 */
trait UseExtra
{
	/**
	 * Extra fields.
	 *
	 * @var array
	 * @since 2.0.0
	 */
	protected $extra = [];

	/**
	 * Add extra field to payload.
	 *
	 * @param string $name
	 * @param mixed $value
	 * @since 2.0.0
	 * @return self
	 */
	public function addExtra ( string $name, $value )
	{ $this->extra[$name] = $value; return $this; }

	/**
	 * Get payload extra field.
	 *
	 * @since 2.0.0
	 * @return mixed
	 */
	public function getExtra ( string $name )
	{ return $this->extra[$name] ?? null; }

	/**
	 * Check if payload has extra field.
	 *
	 * @since 2.0.0
	 * @return bool
	 */
	public function hasExtra ( string $name ) : bool
	{ return isset($this->extra[$name]); }

	/**
	 * Remove extra field from payload.
	 *
	 * @param string $name
	 * @since 2.0.0
	 * @return void
	 */
	public function removeExtra ( string $name )
	{ unset($this->extra[$name]); return $this; }
}