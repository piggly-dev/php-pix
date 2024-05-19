<?php
namespace Piggly\Pix\Api\Payloads\Entities;

use DateTime;

/**
 * Pix entity to Cob payload.
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
class Pix
{
	/**
	 * End to end identification.
	 *
	 * @var string
	 * @since 2.0.0
	 */
	protected $e2eid;

	/**
	 * Pix amount.
	 *
	 * @var float
	 * @since 2.0.0
	 */
	protected $amount;

	/**
	 * Transaction id.
	 *
	 * @var string|null
	 * @since 2.0.0
	 */
	protected $tid;

	/**
	 * Pix processed date.
	 *
	 * @var DateTime
	 * @since 2.0.0
	 */
	protected $processedAt;

	/**
	 * Extra information.
	 *
	 * @var string
	 * @since 2.0.0
	 */
	protected $info;

	/**
	 * Components associated
	 * to Pix.
	 *
	 * @var array<PixComponentAmount>
	 * @since 3.0.0
	 */
	protected $components = [];

	/**
	 * Refunds associated
	 * to Pix.
	 *
	 * @var array<Refund>
	 * @since 2.0.0
	 */
	protected $refunds = [];

	/**
	 * Create a new Pix entity.
	 *
	 * @param string $e2eid
	 * @param float|string $amount
	 * @since 3.0.0
	 * @return self
	 */
	public function __construct ( string $e2eid, $amount )
	{
		$this->setE2eid($e2eid);
		$this->setAmount($amount);
	}

	/**
	 * Get extra information.
	 *
	 * @since 2.0.0
	 * @return string|null
	 */
	public function getInfo () : ?string
	{ return $this->info; }

	/**
	 * Set extra information.
	 *
	 * @param string $info Extra information.
	 * @since 2.0.0
	 * @return self
	 */
	public function setInfo ( string $info )
	{ $this->info = $info; return $this; }

	/**
	 * Get pix processed date.
	 *
	 * @since 2.0.0
	 * @return DateTime|null
	 */
	public function getProcessedAt () : ?DateTime
	{ return $this->processedAt; }

	/**
	 * Set pix processed date.
	 *
	 * @param DateTime|string $processedAt Pix processed date.
	 * @since 2.0.0
	 * @return self
	 */
	public function setProcessedAt ( $processedAt )
	{ $this->processedAt = $processedAt instanceof DateTime ? $processedAt : new DateTime($processedAt); return $this; }

	/**
	 * Get pix amount.
	 *
	 * @since 2.0.0
	 * @return float
	 */
	public function getAmount () : float
	{ return $this->amount; }

	/**
	 * Set pix amount.
	 *
	 * @param float|string $amount Pix amount.
	 * @since 2.0.0
	 * @return self
	 */
	public function setAmount ( $amount )
	{ $this->amount = \is_float($amount) ? $amount : \floatval($amount); return $this; }

	/**
	 * Get transaction id.
	 *
	 * @since 2.0.0
	 * @return string|null
	 */
	public function getTid () : ?string
	{ return $this->tid; }

	/**
	 * Set transaction id.
	 *
	 * @param string $tid Transaction id.
	 * @since 2.0.0
	 * @return self
	 */
	public function setTid ( string $tid )
	{ $this->tid = $tid; return $this; }

	/**
	 * Get end to end identification.
	 *
	 * @since 2.0.0
	 * @return string
	 */
	public function getE2eid () : string
	{ return $this->e2eid; }

	/**
	 * Set end to end identification.
	 *
	 * @param string $e2eid End to end identification.
	 * @since 2.0.0
	 * @return self
	 */
	public function setE2eid ( string $e2eid )
	{ $this->e2eid = $e2eid; return $this; }

	/**
	 * Add refund to pix transacion.
	 *
	 * @param Refund|array $refund
	 * @since 2.0.0
	 * @return self
	 */
	public function addRefund ( $refund )
	{
		$refund = $refund instanceof Refund ? $refund : (new Refund($refund['id'], $refund['rtrId'], $refund['status'], $refund['valor']))->import($refund);
		$this->refunds[$refund->getRid()] = $refund;
		return $this;
	}

	/**
	 * Get refund by refund unique id.
	 *
	 * @param string $rid
	 * @since 2.0.0
	 * @return Refund|null
	 */
	public function getRefund ( string $rid ) : ?Refund
	{ return $this->refunds[$rid] ?? null; }

	/**
	 * Get all refunds associated to pix transaction.
	 *
	 * @since 2.0.0
	 * @return array<Refund>
	 */
	public function getRefunds () : array
	{ return $this->refunds; }

	/**
	 * Add component to pix transacion.
	 *
	 * @param string $type
	 * @param PixComponentAmount|array $component
	 * @since 3.0.0
	 * @return self
	 */
	public function addComponent ( string $type, $component )
	{
		$component = $component instanceof PixComponentAmount ? $component : (new PixComponentAmount($type, $component['valor']))->import($component);
		$this->components[$type] = $component;
		return $this;
	}

	/**
	 * Get component by type.
	 *
	 * @param string $rid
	 * @since 3.0.0
	 * @return PixComponentAmount|null
	 */
	public function getComponent ( string $rid ) : ?PixComponentAmount
	{ return $this->components[$rid] ?? null; }

	/**
	 * Get all components associated to pix transaction.
	 *
	 * @since 3.0.0
	 * @return array<PixComponentAmount>
	 */
	public function getComponents () : array
	{ return $this->components; }

	/**
	 * Export this object to an array.
	 *
	 * @since 2.0.0
	 * @return array
	 */
	public function export () : array
	{
		$array = [];

		if ( !empty($this->e2eid) )
		{ $array['endToEndId'] = $this->e2eid; }

		if ( !empty($this->tid) )
		{ $array['txid'] = $this->tid; }

		if ( !empty($this->amount) )
		{ $array['valor'] = \number_format($this->amount, 2, '.', ''); }

		if ( !empty($this->processedAt) )
		{ $array['horario'] = $this->processedAt->format(DateTime::RFC3339); }

		if ( !empty($this->info) )
		{ $array['infoPagador'] = $this->info; }

		if ( !empty($this->refunds) )
		{
			$array['devolucoes'] = [];

			foreach ( $this->refunds as $r )
			{ $array['devolucoes'][] = $r->export(); }
		}

		if ( !empty($this->components) ) {
			$array['componentesValor'] = [];

			foreach ( $this->components as $c )
			{ $array['componentesValor'][$c->getType()] = $c->export(); }
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
			'txid' => 'setTid',
			'horario' => 'setProcessedAt',
			'infoPagador' => 'setInfo'
		];

		foreach ( $importable as $field => $method )
		{
			if ( empty($data[$field]) === false )
			{ $this->{$method}($data[$field]); }
		}

		if ( empty($data['devolucoes']) === false && \is_array($data['devolucoes']) )
		{
			foreach ( $data['devolucoes'] as $devolucao )
			{ $this->addRefund($devolucao); }
		}

		if ( empty($data['componentesValor']) === false && \is_array($data['componentesValor']) )
		{
			foreach ( $data['componentesValor'] as $type => $componenteValor )
			{ $this->addComponent($type, $componenteValor); }
		}

		return $this;
	}
}