<?php
namespace Piggly\Pix\Api\Payloads\Entities;

use Piggly\Pix\Utils\Helper;

/**
 * Amount entity to Cob payload.
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
class Amount
{
	/**
	 * Original amount.
	 *
	 * @since 2.0.0
	 * @var float|null
	 */
	protected $original = null;

	/**
	 * Final amount.
	 *
	 * @since 2.0.0
	 * @var float|null
	 */
	protected $final = null;

	/**
	 * Capability of change amount in payer.
	 *
	 * @since 2.0.0
	 * @var boolean
	 */
	protected $changeability = false;

	/**
	 * Modalities to amount.
	 *
	 * @since 2.0.0
	 * @var array<DueAmountModality>
	 */
	protected $modalities = [];

	/**
	 * Components associated to Pix.
	 *
	 * @var array<PixComponentAmount>
	 * @since 3.0.0
	 */
	protected $withdraws = [];

	/**
	 * Get original amount.
	 *
	 * @since 2.0.0
	 * @return float|null
	 */
	public function getOriginal () : ?float
	{ return $this->original; }

	/**
	 * Set original amount.
	 *
	 * @param float|string $original Original amount.
	 * @since 2.0.0
	 * @return self
	 */
	public function setOriginal ( $original )
	{ $this->original = \is_float($original) ? $original : \floatval($original); return $this; }

	/**
	 * Get final amount.
	 *
	 * @since 2.0.0
	 * @return float|null
	 */
	public function getFinal () : ?float
	{ return $this->final; }

	/**
	 * Set final amount.
	 *
	 * @param float|string $final Original amount.
	 * @since 2.0.0
	 * @return self
	 */
	public function setFinal ( $final )
	{ $this->final = \is_float($final) ? $final : \floatval($final); return $this; }

	/**
	 * Add modality to pix transacion.
	 *
	 * @param DueAmountModality $modality
	 * @since 2.0.0
	 * @return self
	 */
	public function addModality ( DueAmountModality $modality )
	{ $this->modalities[$modality->getModality()] = $modality; return $this; }

	/**
	 * Get modality by modality unique id.
	 *
	 * @param string $modality
	 * @since 2.0.0
	 * @return DueAmountModality|null
	 */
	public function getModality ( string $modality ) : ?DueAmountModality
	{ return $this->modalities[$modality] ?? null; }

	/**
	 * Get all modalities associated to pix transaction.
	 *
	 * @since 2.0.0
	 * @return array<DueAmountModality>
	 */
	public function getModalities () : array
	{ return $this->modalities; }

	/**
	 * Get if payer can change amount.
	 *
	 * @since 3.0.0
	 * @return bool
	 */
	public function getChangeability () : bool
	{ return $this->changeability; }

	/**
	 * Set if payer can change amount.
	 *
	 * @param bool|int|string $changeability
	 * @since 3.0.0
	 * @return self
	 */
	public function payerCanChangeAmount ( $changeability )
	{ $this->changeability = \boolval($changeability); return $this; }


	/**
	 * Add component to pix transacion.
	 *
	 * @param string $type
	 * @param PixComponentAmount|array $component
	 * @since 3.0.0
	 * @return self
	 */
	public function addWithdraw( string $type, $component )
	{
		$component = $component instanceof PixComponentAmount ? $component : (new PixComponentAmount($type, $component['valor']))->import($component);
		$this->withdraws[$type] = $component;
		return $this;
	}

	/**
	 * Get component by type.
	 *
	 * @param string $rid
	 * @since 3.0.0
	 * @return PixComponentAmount|null
	 */
	public function getWithdraw( string $rid ) : ?PixComponentAmount
	{ return $this->withdraws[$rid] ?? null; }

	/**
	 * Get all components associated to pix transaction.
	 *
	 * @since 3.0.0
	 * @return array<PixComponentAmount>
	 */
	public function getWithdraws() : array
	{ return $this->withdraws; }

	/**
	 * Export this object to an array.
	 *
	 * @since 2.0.0
	 * @return array
	 */
	public function export () : array
	{
		$array = [
			'original' => \number_format($this->original, 2, '.', '')
		];

		if ( $this->changeability )
		{ $array['modalidadeAlteracao'] = 1; }

		if ( !empty($this->final) )
		{ $array['final'] = \number_format($this->final, 2, '.', ''); }

		if ( !empty($this->modalities) )
		{
			foreach ( $this->modalities as $modality )
			{ $array[$modality->getModality()] = $modality->export(); }
		}

		if ( !empty($this->withdraws) ) {
			$array['retirada'] = [];

			foreach ( $this->withdraws as $c )
			{ $array['retirada'][$c->getType()] = $c->export(); }
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
		Helper::fill($data, $this, [
			'original' => 'setOriginal',
			'final' => 'setFinal',
			'modalidadeAlteracao' => 'payerCanChangeAmount'
		]);

		if ( isset($data['modalidadeAlteracao']) )
		{ $this->payerCanChangeAmount(\boolval($data['modalidadeAlteracao'])); }

		foreach ( DueAmountModality::MODALITIES as $modality )
		{
			if ( Helper::shouldBeArray($data[$modality] ?? null) )
			{ $this->addModality((new DueAmountModality($modality))->import($data[$modality])); }
		}

		if ( Helper::shouldBeArray($data['retirada'] ?? null) )
		{
			foreach ( $data['retirada'] as $type => $componenteValor )
			{ $this->addWithdraw($type, $componenteValor); }
		}

		return $this;
	}

	/**
	 * Create a new entity.
	 *
	 * @param array $data
	 * @since 3.0.0
	 * @return Amount
	 */
	public static function create ( array $data )
	{
		$e = new Amount();
		return $e->import($data);
	}
}