<?php
namespace Piggly\Pix\Api\Payloads\Entities;

use Piggly\Pix\Utils\Helper;
use RuntimeException;

/**
 * Amount entity to Cob payload.
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
class PixComponentAmount
{
	/**
	 * Component type as "original" .
	 *
	 * @var string
	 * @since 3.0.0
	 */
	const TYPE_ORIGINAL = 'original';

	/**
	 * Component type as "saque" .
	 *
	 * @var string
	 * @since 3.0.0
	 */
	const TYPE_WITHDRAW = 'saque';

	/**
	 * Component type as "juros" .
	 *
	 * @var string
	 * @since 3.0.0
	 */
	const TYPE_FEES = 'juros';

	/**
	 * Component type as "multa" .
	 *
	 * @var string
	 * @since 3.0.0
	 */
	const TYPE_FINE = 'multa';

	/**
	 * Component type as "abatimento" .
	 *
	 * @var string
	 * @since 3.0.0
	 */
	const TYPE_REBATE = 'abatimento';

	/**
	 * Component type as "desconto" .
	 *
	 * @var string
	 * @since 3.0.0
	 */
	const TYPE_DISCOUNT = 'desconto';

	/**
	 * Component type as "troco" .
	 *
	 * @var string
	 * @since 3.0.0
	 */
	const TYPE_EXCHANGE = 'troco';

	/**
	 * All Component type available.
	 *
	 * @var array<string>
	 * @since 3.0.0
	 */
	const TYPES = [
		self::TYPE_ORIGINAL,
		self::TYPE_WITHDRAW,
		self::TYPE_FEES,
		self::TYPE_FINE,
		self::TYPE_REBATE,
		self::TYPE_DISCOUNT,
		self::TYPE_EXCHANGE
	];

	/**
	 * Type.
	 *
	 * @since 3.0.0
	 * @var float
	 */
	protected $type;

	/**
	 * Amount.
	 *
	 * @since 3.0.0
	 * @var float
	 */
	protected $amount;

	/**
	 * Modality agent.
	 *
	 * @since 3.0.0
	 * @var string|null
	 */
	protected $modalityAgent = null;

	/**
	 * Provider of withdrawal service.
	 *
	 * @since 3.0.0
	 * @var string|null
	 */
	protected $providerOfWithdrawalService = null;

	/**
	 * Create a new Component entity.
	 *
	 * @param string $type
	 * @param float|string $amount
	 * @since 3.0.0
	 * @return self
	 */
	public function __construct ( string $type, $amount )
	{
		$this->setType($type);
		$this->setAmount($amount);
	}

	/**
	 * Get type.
	 *
	 * @since 3.0.0
	 * @return string
	 */
	public function getType () : string
	{ return $this->type; }

	/**
	 * Set type.
	 *
	 * @param string $type Type.
	 * @since 3.0.0
	 * @return self
	 * @throws RuntimeException If is a invalid type.
	 */
	public function setType ( string $type )
	{
		static::validateType($type);
		$this->type = $type;
		return $this;
	}

	/**
	 * Get original amount.
	 *
	 * @since 3.0.0
	 * @return float
	 */
	public function getAmount () : float
	{ return $this->amount; }

	/**
	 * Set original amount.
	 *
	 * @param float|string $original Original amount.
	 * @since 3.0.0
	 * @return self
	 */
	public function setAmount ( $original )
	{ $this->amount = \is_float($original) ? $original : \floatval($original); return $this; }

	/**
	 * Get modality agent.
	 *
	 * @since 3.0.0
	 * @return string|null
	 */
	public function getModalityAgent () : ?string
	{ return $this->modalityAgent; }

	/**
	 * Set modality agent.
	 *
	 * @param string $modalityAgent Original amount.
	 * @since 3.0.0
	 * @return self
	 */
	public function setModalityAgent ( string $modalityAgent )
	{ $this->modalityAgent = $modalityAgent; return $this; }

	/**
	 * Get provider of withdrawal service.
	 *
	 * @since 3.0.0
	 * @return string|null
	 */
	public function getProviderOfWithdrawalService () : ?string
	{ return $this->providerOfWithdrawalService; }

	/**
	 * Set provider of withdrawal service.
	 *
	 * @param string $providerOfWithdrawalService Original amount.
	 * @since 3.0.0
	 * @return self
	 */
	public function setProviderOfWithdrawalService ( string $providerOfWithdrawalService )
	{ $this->providerOfWithdrawalService = $providerOfWithdrawalService; return $this; }

	/**
	 * Export this object to an array.
	 *
	 * @since 3.0.0
	 * @return array
	 */
	public function export () : array
	{
		$array = [
			'valor' => \number_format($this->amount, 2, '.', '')
		];

		if ( !empty($this->modalityAgent) )
		{ $array['modalidadeAgente'] = $this->modalityAgent; }

		if ( !empty($this->providerOfWithdrawalService) )
		{ $array['prestadorDoServicoDeSaque'] = $this->providerOfWithdrawalService; }

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
			'modalidadeAgente' => 'setModalityAgent',
			'prestadorDoServicoDeSaque' => 'setProviderOfWithdrawalService'
		]);

		return $this;
	}

	/**
	 * Create a new entity.
	 *
	 * @param string $type
	 * @param array $data
	 * @since 3.0.0
	 * @return PixComponentAmount
	 */
	public static function create ( string $type, array $data )
	{
		$e = new PixComponentAmount($type, $data['valor']);
		return $e->import($data);
	}

	/**
	 * Throw an exception if $type is a invalid type.
	 *
	 * @param string $type
	 * @since 3.0.0
	 * @return void
	 * @throws RuntimeException If is a invalid type.
	 */
	public static function validateType ( string $type )
	{
		if ( \in_array($type, static::TYPES, true) === false )
		{ throw new RuntimeException(\sprintf('O TIPO deve ser um dos seguintes: `%s`.', \implode('`, `', static::TYPES))); }
	}

	/**
	 * Is $expected equal to $actual.
	 *
	 * @param string $expected
	 * @param string $actual
	 * @since 3.0.0
	 * @return boolean
	 * @throws RuntimeException If some is a invalid TYPE.
	 */
	public static function isType ( string $expected, string $actual ) : bool
	{
		if ( \in_array($expected, static::TYPES, true) === false )
		{ throw new RuntimeException(\sprintf('O TIPO esperado deve ser um dos seguintes: `%s`.', \implode('`, `', static::TYPES))); }

		if ( \in_array($actual, static::TYPES, true) === false )
		{ throw new RuntimeException(\sprintf('O TIPO atual deve ser um dos seguintes: `%s`.', \implode('`, `', static::TYPES))); }

		return $expected === $actual;
	}
}