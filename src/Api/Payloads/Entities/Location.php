<?php
namespace Piggly\Pix\Api\Payloads\Entities;

use DateTime;
use Exception;
use Piggly\Pix\Api\Payloads\Cob;
use Piggly\Pix\Exceptions\InvalidFieldException;
use Piggly\Pix\Utils\Helper;

/**
 * Location entity to Cob payload.
 *
 * @package \Piggly\Pix
 * @subpackage \Piggly\Pix\Api\Payloads\Entities
 * @version 2.0.0
 * @since 2.0.0
 * @category Entity
 * @author Caique Araujo <caique@piggly.com.br>
 * @author Piggly Lab <dev@piggly.com.br>
 * @license MIT
 * @copyright 2021 Piggly Lab <dev@piggly.com.br>
 */
class Location
{
	/**
	 * Location id.
	 *
	 * @since 2.0.0
	 * @var int|null
	 */
	protected $id = null;

	/**
	 * Location transaction id.
	 *
	 * @since 2.0.0
	 * @var string|null
	 */
	protected $tid = null;

	/**
	 * Location url.
	 *
	 * @since 2.0.0
	 * @var string|null
	 */
	protected $location = null;

	/**
	 * Location type.
	 *
	 * @since 2.0.0
	 * @var string|null
	 */
	protected $type = null;

	/**
	 * Date when location was created.
	 *
	 * @since 2.0.0
	 * @var DateTime|null
	 */
	protected $createdAt = null;

	/**
	 * Get date when location was created.
	 *
	 * @since 2.0.0
	 * @return DateTime|null
	 */
	public function getCreatedAt () : ?DateTime
	{ return $this->createdAt; }

	/**
	 * Set date when location was created.
	 *
	 * @param DateTime|string $createdAt Date when location was created.
	 * @since 2.0.0
	 * @return self
	 */
	public function setCreatedAt ( $createdAt )
	{ $this->createdAt = $createdAt instanceof DateTime ? $createdAt : new DateTime($createdAt); return $this; }

	/**
	 * Get location type.
	 *
	 * @since 2.0.0
	 * @return string|null
	 */
	public function getType () : ?string
	{ return $this->type; }

	/**
	 * Set location type.
	 *
	 * @param string $type Location type.
	 * @since 2.0.0
	 * @return self
	 */
	public function setType ( string $type )
	{
		try
		{ Cob::validateType($type); }
		catch ( Exception $e )
		{ throw new InvalidFieldException('Location.TipoCob', $type, $e->getMessage()); }

		$this->type = $type;
		return $this;
	}

	/**
	 * Get location url.
	 *
	 * @since 2.0.0
	 * @return string|null
	 */
	public function getLocation () : ?string
	{ return $this->location; }

	/**
	 * Set location url.
	 *
	 * @param string $location Location url.
	 * @since 2.0.0
	 * @return self
	 */
	public function setLocation ( string $location )
	{ $this->location = $location; return $this; }

	/**
	 * Get location transaction id.
	 *
	 * @since 2.0.0
	 * @return string|null
	 */
	public function getTid () : ?string
	{ return $this->tid; }

	/**
	 * Set location transaction id.
	 *
	 * @param string $tid Location transaction id.
	 * @since 2.0.0
	 * @return self
	 */
	public function setTid ( string $tid )
	{ $this->tid = $tid; return $this; }

	/**
	 * Get location id.
	 *
	 * @since 2.0.0
	 * @return int|null
	 */
	public function getId () : ?int
	{ return $this->id; }

	/**
	 * Set location id.
	 *
	 * @param int $id Location id.
	 * @since 2.0.0
	 * @return self
	 */
	public function setId ( int $id )
	{ $this->id = $id; return $this; }

	/**
	 * Export this object to an array.
	 *
	 * @since 2.0.0
	 * @return array
	 */
	public function export () : array
	{
		$array = [];

		if ( !\is_null($this->id) )
		{ $array['id'] = $this->id; }

		if ( !empty($this->tid) )
		{ $array['txid'] = $this->tid; }

		if ( !empty($this->location) )
		{ $array['location'] = $this->location; }

		if ( !empty($this->type) )
		{ $array['tipoCob'] = $this->type; }

		if ( !empty($this->createdAt) )
		{ $array['criacao'] = $this->createdAt->format(DateTime::RFC3339); }

		return $array;
	}

	/**
	 * Import data to array.
	 *
	 * @param array $data
	 * @since 2.0.0
	 * @return self
	 */
	public function import ( array $data )
	{
		Helper::fill($data, $this, [
			'id' => 'setId',
			'txid' => 'setTid',
			'location' => 'setLocation',
			'tipoCob' => 'setType',
			'criacao' => 'setCreatedAt'
		]);

		return $this;
	}

	/**
	 * Create a new entity.
	 *
	 * @param string $modality
	 * @param array $data
	 * @since 3.0.0
	 * @return Location
	 */
	public static function create ( array $data )
	{
		$e = new Location();
		return $e->import($data);
	}
}