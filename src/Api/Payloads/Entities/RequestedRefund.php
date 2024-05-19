<?php
namespace Piggly\Pix\Api\Payloads\Entities;

use Exception;
use Piggly\Pix\Exceptions\InvalidFieldException;
use Piggly\Pix\Utils\Helper;

/**
 * Requested refund entity to Pix entity.
 *
 * @package \Piggly\Pix
 * @subpackage \Piggly\Pix\Api\Payloads\Entities
 * @version 3.0.0
 * @since 3.0.0
 * @category Entity
 * @author Caique Araujo <caique@piggly.com.br>
 * @author Piggly Lab <dev@piggly.com.br>
 * @license MIT
 * @copyright 2024 Piggly Lab <dev@piggly.com.br>
 */
class RequestedRefund
{
	/**
	 * Return amount.
	 *
	 * @var float
	 * @since 3.0.0
	 */
	protected $amount;

	/**
	 * Return nature.
	 *
	 * @var string|null
	 * @since 3.0.0
	 */
	protected $nature = null;

	/**
	 * Return description.
	 *
	 * @var string|null
	 * @since 3.0.0
	 */
	protected $description = null;

	/**
	 * Create a new Refund entity.
	 *
	 * @param float|string $amount
	 * @since 3.0.0
	 * @return self
	 */
	public function __construct ( $amount )
	{
		$this->setAmount($amount);
	}

	/**
	 * Get return nature.
	 *
	 * @since 3.0.0
	 * @return string|null
	 */
	public function getNature () : ?string
	{ return $this->nature; }

	/**
	 * Set return nature.
	 *
	 * @param string $natureza Return nature.
	 * @since 3.0.0
	 * @return self
	 * @throws InvalidFieldException
	 */
	public function setNature ( string $nature )
	{
		try
		{ Refund::validateNature($nature); }
		catch ( Exception $e )
		{ throw new InvalidFieldException('Devolução.Natureza', $nature, $e->getMessage()); }

		$this->nature = $nature;
		return $this;
	}

	/**
	 * Get return description.
	 *
	 * @since 3.0.0
	 * @return string|null
	 */
	public function getDescription () : ?string
	{ return $this->description; }

	/**
	 * Set return description.
	 *
	 * @param string $description Return description.
	 * @since 3.0.0
	 * @return self
	 */
	public function setDescription ( string $description )
	{ $this->description = $description; return $this; }

	/**
	 * Get return amount.
	 *
	 * @since 3.0.0
	 * @return float
	 */
	public function getAmount () : float
	{ return $this->amount; }

	/**
	 * Set return amount.
	 *
	 * @param float|string $amount Return amount.
	 * @since 3.0.0
	 * @return self
	 */
	public function setAmount ( $amount )
	{ $this->amount = \is_float($amount) ? $amount : \floatval($amount); return $this; }

	/**
	 * Export this object to an array.
	 *
	 * @since 3.0.0
	 * @return array
	 */
	public function export () : array
	{
		$array = [];

		if ( !empty($this->amount) )
		{ $array['valor'] = \number_format($this->amount, 2, '.', ''); }

		if ( !empty($this->description) )
		{ $array['descricao'] = $this->description; }

		if ( !empty($this->nature) )
		{ $array['natureza'] = $this->nature; }

		return $array;
	}

	/**
	 * Import data to array.
	 *
	 * @param array $data
	 * @since 3.0.0
	 * @return self
	 */
	public function import ( array $data )
	{
		Helper::fill($data, $this, [
			'descricao' => 'setDescription',
			'natureza' => 'setNature'
		]);

		return $this;
	}

	/**
	 * Create a new entity.
	 *
	 * @param string $type
	 * @param array $data
	 * @since 3.0.0
	 * @return RequestedRefund
	 */
	public static function create ( array $data )
	{
		$e = new RequestedRefund($data['valor']);
		return $e->import($data);
	}

}