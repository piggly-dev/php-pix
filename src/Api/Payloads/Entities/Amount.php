<?php
namespace Piggly\Pix\Api\Payloads\Entities;

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
	 * @var float
	 */
	protected $original;

	/**
	 * Final amount.
	 * 
	 * @since 2.0.0
	 * @var float
	 */
	protected $final;

	/**
	 * Modalities to amount.
	 * 
	 * @since 2.0.0
	 * @var array<DueAmountModality>
	 */
	protected $modalities = [];
	
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
	 * @return array
	 */
	public function getModalities () : array
	{ return $this->modalities; }

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

		if ( !empty($this->final) )
		{ $array['final'] = \number_format($this->final, 2, '.', ''); }

		if ( !empty($this->modalities) )
		{
			foreach ( $this->modalities as $modality )
			{ $array[$modality->getModality()] = $modality->export(); }
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
			'original' => 'setOriginal',
			'final' => 'setFinal'
		];

		foreach ( $importable as $field => $method )
		{
			if ( isset($data[$field]) )
			{ $this->{$method}($data[$field]); }
		}

		foreach ( DueAmountModality::MODALITIES as $modality )
		{
			if ( isset($data[$modality]) )
			{ $this->addModality((new DueAmountModality($modality))->import($data[$modality])); }
		}

		return $this;
	}
}