<?php
namespace Piggly\Pix\Api\Payloads\Entities;

use DateTime;

/**
 * Calendar entity to Cob payload.
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
class Calendar
{
	/**
	 * Cob created at.
	 * 
	 * @since 2.0.0
	 * @var DateTime
	 */
	protected $createdAt;

	/**
	 * Cob presented at.
	 * 
	 * @since 2.0.0
	 * @var DateTime
	 */
	protected $presentedAt;

	/**
	 * Cob expires after x seconds.
	 * 
	 * @since 2.0.0
	 * @var int
	 */
	protected $expiresIn;

	/**
	 * Cob due date.
	 * 
	 * @since 2.0.0
	 * @var DateTime
	 */
	protected $dueDate;

	/**
	 * Cob expires after x days.
	 * 
	 * @since 2.0.0
	 * @var int
	 */
	protected $expirationAfter;
	
	/**
	 * Get date of creation to current calendar.
	 * 
	 * @since 2.0.0
	 * @return DateTime
	 */
	public function getCreatedAt () : DateTime
	{ return $this->createdAt; }

	/**
	 * Set created at to current calendar.
	 * 
	 * @param string|DateTime $createdAt
	 * @since 2.0.0
	 * @return self
	 */
	public function setCreatedAt ( $createdAt )
	{ $this->createdAt = $createdAt instanceof DateTime ? $createdAt : new DateTime($createdAt); return $this; }

	/**
	 * Get date of presentation to current calendar.
	 * 
	 * @since 2.0.0
	 * @return DateTime
	 */
	public function getPresentedAt () : DateTime
	{ return $this->presentedAt; }

	/**
	 * Set presented at to current calendar.
	 * 
	 * @param string|DateTime $presentedAt
	 * @since 2.0.0
	 * @return self
	 */
	public function setPresentedAt ( $presentedAt )
	{ $this->presentedAt = $presentedAt instanceof DateTime ? $presentedAt : new DateTime($presentedAt); return $this; }

	/**
	 * Get time to expires in seconds to current calendar.
	 * 
	 * @since 2.0.0
	 * @return int
	 */
	public function getExpiresIn () : int
	{ return $this->expiresIn; }

	/**
	 * Set time in seconds to expiration of current calendar.
	 * 
	 * @param int $seconds
	 * @since 2.0.0
	 * @return self
	 */
	public function setExpiresIn ( int $seconds )
	{ $this->expiresIn = $seconds; return $this;	}
	
	/**
	 * Get due date to current calendar.
	 * 
	 * @since 2.0.0
	 * @return DateTime
	 */
	public function getDueDate () : DateTime
	{ return $this->dueDate; }

	/**
	 * Set due date to current calendar.
	 * 
	 * @param string|DateTime $dueDate
	 * @since 2.0.0
	 * @return self
	 */
	public function setDueDate ( $dueDate )
	{ $this->dueDate = $dueDate instanceof DateTime ? $dueDate : new DateTime($dueDate); return $this; }

	/**
	 * Get days after due date to expires to current calendar.
	 * 
	 * @since 2.0.0
	 * @return int
	 */
	public function getExpirationAfter () : int
	{ return $this->expirationAfter; }

	/**
	 * Set time in days to expiration after due date of current calendar.
	 * 
	 * @param int $days
	 * @since 2.0.0
	 * @return self
	 */
	public function setExpirationAfter ( int $days )
	{ $this->expirationAfter = $days; return $this; }

	/**
	 * Export this object to an array.
	 * 
	 * @since 2.0.0
	 * @return array
	 */
	public function export () : array
	{
		$array = [];
		
		if ( isset( $this->createdAt ) )
		{ $array['criacao'] = $this->createdAt->format(DateTime::RFC3339); }
		
		if ( isset( $this->presentedAt ) )
		{ $array['apresentacao'] = $this->presentedAt->format(DateTime::RFC3339); }
		
		if ( isset( $this->expiresIn ) )
		{ $array['expiracao'] = $this->expiresIn; }

		if ( isset( $this->dueDate ) )
		{ $array['dataDeVencimento'] = $this->dueDate->format('Y-m-d'); }

		if ( isset( $this->expirationAfter ) )
		{ $array['validadeAposVencimento'] = $this->expirationAfter; }

		return $array;
	}

	/**
	 * Import data to array.
	 * 
	 * @param string $type Person type
	 * @param array $data
	 * @since 2.0.0
	 * @return self
	 */
	public function import ( array $data )
	{
		$importable = [
			'criacao' => 'setCreatedAt',
			'apresentacao' => 'setPresentedAt',
			'expiracao' => 'setExpiresIn',
			'dataDeVencimento' => 'setDueDate',
			'validadeAposVencimento' => 'setExpirationAfter'
		];

		foreach ( $importable as $field => $method )
		{
			if ( isset($data[$field]) )
			{ $this->{$method}($data[$field]); }
		}

		return $this;
	}
}