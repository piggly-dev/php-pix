<?php
namespace Piggly\Pix\Api\Payloads\Entities;

use DateTime;
use Exception;
use Piggly\Pix\Exceptions\InvalidFieldException;
use RuntimeException;

/**
 * Refund entity to Pix entity.
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
class Refund
{
	/**
	 * Refund status as "EM_PROCESSAMENTO" . 
	 * 
	 * @var string
	 * @since 2.0.0
	 */
	const STATUS_PROCESSING = 'EM_PROCESSAMENTO';
	
	/**
	 * Refund status as "DEVOLVIDO" . 
	 * 
	 * @var string
	 * @since 2.0.0
	 */
	const STATUS_CHARGEDBACK = 'DEVOLVIDO';

	/**
	 * Refund status as "NAO_REALIZADO" . 
	 * 
	 * @var string
	 * @since 2.0.0
	 */
	const STATUS_UNREALIZED = 'NAO_REALIZADO';

	/**
	 * All refund statuses available.
	 * 
	 * @var array<string>
	 * @since 2.0.0
	 */
	const STATUSES = [
		self::STATUS_PROCESSING,
		self::STATUS_CHARGEDBACK,
		self::STATUS_UNREALIZED
	];

	/**
	 * ID created by client.
	 *
	 * @var string
	 * @since 2.0.0
	 */
	protected $id;

	/**
	 * Return ID.
	 *
	 * @var string
	 * @since 2.0.0
	 */
	protected $rid;

	/**
	 * Return amount.
	 *
	 * @var float
	 * @since 2.0.0
	 */
	protected $amount;

	/**
	 * Return status.
	 *
	 * @var string
	 * @since 2.0.0
	 */
	protected $status;

	/**
	 * Return reason.
	 *
	 * @var string
	 * @since 2.0.0
	 */
	protected $reason;

	/**
	 * Date when return was requested.
	 *
	 * @var DateTime
	 * @since 2.0.0
	 */
	protected $requestedAt;

	/**
	 * Date when return was paid.
	 *
	 * @var DateTime
	 * @since 2.0.0
	 */
	protected $paidAt;

	/**
	 * Get date when return was paid.
	 *
	 * @since 2.0.0
	 * @return DateTime|null
	 */
	public function getPaidAt () : ?DateTime
	{ return $this->paidAt; }

	/**
	 * Set date when return was paid.
	 *
	 * @param DateTime|string $paidAt Date when return was paid.
	 * @since 2.0.0
	 * @return self
	 */
	public function setPaidAt ( $paidAt )
	{ $this->paidAt = $paidAt instanceof DateTime ? $paidAt : new DateTime($paidAt); return $this; }

	/**
	 * Get date when return was requested.
	 *
	 * @since 2.0.0
	 * @return DateTime|null
	 */
	public function getRequestedAt () : ?DateTime
	{ return $this->requestedAt; }

	/**
	 * Set date when return was requested.
	 *
	 * @param DateTime|string $requestedAt Date when return was requested.
	 * @since 2.0.0
	 * @return self
	 */
	public function setRequestedAt ( $requestedAt )
	{ $this->requestedAt = $requestedAt instanceof DateTime ? $requestedAt : new DateTime($requestedAt); return $this; }

	/**
	 * Get return reason.
	 *
	 * @since 2.0.0
	 * @return string|null
	 */
	public function getReason () : ?string
	{ return $this->reason; }

	/**
	 * Set return reason.
	 *
	 * @param string $reason Return reason.
	 * @since 2.0.0
	 * @return self
	 */
	public function setReason ( string $reason )
	{ $this->reason = $reason; return $this; }

	/**
	 * Get return status.
	 *
	 * @since 2.0.0
	 * @return string
	 */
	public function getStatus () : string
	{ return $this->status; }

	/**
	 * Set return status.
	 *
	 * @param string $status Return status.
	 * @since 2.0.0
	 * @return self
	 * @throws InvalidFieldException
	 */
	public function setStatus ( string $status )
	{ 
		try
		{ static::validateStatus($status); }
		catch ( Exception $e )
		{ throw new InvalidFieldException('Devolução.Status', $status, $e->getMessage()); }
		
		$this->status = $status; 
		return $this; 
	}

	/**
	 * Get return amount.
	 *
	 * @since 2.0.0
	 * @return float
	 */
	public function getAmount () : float
	{ return $this->amount; }

	/**
	 * Set return amount.
	 *
	 * @param float|string $amount Return amount.
	 * @since 2.0.0
	 * @return self
	 */
	public function setAmount ( $amount )
	{ $this->amount = \is_float($amount) ? $amount : \floatval($amount); return $this; }

	/**
	 * Get return ID.
	 *
	 * @since 2.0.0
	 * @return string
	 */
	public function getRid () : string
	{ return $this->rid; }

	/**
	 * Set return ID.
	 *
	 * @param string $rid Return ID.
	 * @since 2.0.0
	 * @return self
	 */
	public function setRid ( string $rid )
	{ $this->rid = $rid; return $this; }

	/**
	 * Get iD created by client.
	 *
	 * @since 2.0.0
	 * @return string
	 */
	public function getId () : string
	{ return $this->id; }

	/**
	 * Set iD created by client.
	 *
	 * @param string $id ID created by client.
	 * @since 2.0.0
	 * @return self
	 */
	public function setId ( string $id )
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
		
		if ( !empty($this->id) )
		{ $array['id'] = $this->id; }

		if ( !empty($this->rid) )
		{ $array['rtrId'] = $this->rid; }

		if ( !empty($this->amount) )
		{ $array['valor'] = \number_format($this->amount, 2, '.', ''); }

		if ( !empty($this->status) )
		{ $array['status'] = $this->status; }

		if ( !empty($this->reason) )
		{ $array['motivo'] = $this->reason; }

		if ( !empty($this->requestedAt) || !empty($this->paidAt) )
		{ 
			$array['horario'] = []; 

			if ( !empty($this->requestedAt) )
			{ $array['horario']['solicitacao'] = $this->requestedAt->format(DateTime::RFC3339); }

			if ( !empty($this->paidAt) )
			{ $array['horario']['liquidacao'] = $this->paidAt->format(DateTime::RFC3339); }
		}

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
		$importable = [
			'id' => 'setId',
			'rtrId' => 'setRid',
			'valor' => 'setAmount',
			'status' => 'setStatus',
			'motivo' => 'setReason'
		];

		foreach ( $importable as $field => $method )
		{
			if ( isset($data[$field]) )
			{ $this->{$method}($data[$field]); }
		}

		if ( isset($data['horario']) )
		{
			$importable = [
				'solicitacao' => 'setRequestedAt',
				'liquidacao' => 'setPaidAt'
			];

			foreach ( $importable as $field => $method )
			{
				if ( isset($data['horario'][$field]) )
				{ $this->{$method}($data['horario'][$field]); }
			}
		}

		return $this;
	}

	/**
	 * Throw an exception if $status is a invalid status.
	 *
	 * @param string $status
	 * @since 2.0.0
	 * @return void
	 * @throws RuntimeException If is a invalid status.
	 */
	public static function validateStatus ( string $status )
	{
		if ( \in_array($status, static::STATUSES, true) === false )
		{ throw new RuntimeException(\sprintf('O status deve ser um dos seguintes: `%s`.', \implode('`, `', static::STATUSES))); }
	}

	/**
	 * Is $expected equal to $actual.
	 *
	 * @param string $expected
	 * @param string $actual
	 * @since 2.0.0
	 * @return boolean
	 * @throws RuntimeException If some is a invalid status.
	 */
	public static function isStatus ( string $expected, string $actual ) : bool
	{ 
		if ( \in_array($expected, static::STATUSES, true) === false )
		{ throw new RuntimeException(\sprintf('O status esperado deve ser um dos seguintes: `%s`.', \implode('`, `', static::STATUSES))); }
		
		if ( \in_array($actual, static::STATUSES, true) === false )
		{ throw new RuntimeException(\sprintf('O status atual deve ser um dos seguintes: `%s`.', \implode('`, `', static::STATUSES))); }
		
		return $expected === $actual; 
	}
}