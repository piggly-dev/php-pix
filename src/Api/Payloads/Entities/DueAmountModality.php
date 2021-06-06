<?php
namespace Piggly\Pix\Api\Payloads\Entities;

use Exception;
use Piggly\Pix\Api\Payloads\Concerns\UseExtra;
use Piggly\Pix\Exceptions\InvalidFieldException;
use RuntimeException;

/**
 * Due amount modality entity to Cob payload.
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
class DueAmountModality
{
	use UseExtra;

	/**
	 * Reduction modality.
	 * 
	 * @var string 
	 * @since 2.0.0
	 */
	const MODALITY_REDUCTION = 'abatimento';

	/**
	 * Fee modality.
	 * 
	 * @var string 
	 * @since 2.0.0
	 */
	const MODALITY_FEE = 'juros';

	/**
	 * Discount modality.
	 * 
	 * @var string 
	 * @since 2.0.0
	 */
	const MODALITY_DISCOUNT = 'desconto';

	/**
	 * Bank fine modality.
	 * 
	 * @var string 
	 * @since 2.0.0
	 */
	const MODALITY_BANKFINE = 'multa';

	/**
	 * All modalities available.
	 * 
	 * @var array
	 * @since 2.0.0
	 */
	const MODALITIES = [
		self::MODALITY_REDUCTION,
		self::MODALITY_FEE,
		self::MODALITY_DISCOUNT,
		self::MODALITY_BANKFINE
	];

	/**
	 * Reduction modality as fixed.
	 * 
	 * @var integer 
	 * @since 2.0.0
	 */
	const REDUCTION_MODALITY_FIXED = 1;

	/**
	 * Reduction modality as percentage.
	 * 
	 * @var integer 
	 * @since 2.0.0
	 */
	const REDUCTION_MODALITY_PERCENTAGE = 2;

	/**
	 * All reduction modalities available.
	 * 
	 * @var array
	 * @since 2.0.0
	 */
	const REDUCTION_MODALITIES = [1, 2];

	/**
	 * Fee modality as fixed by day.
	 * 
	 * @var integer 
	 * @since 2.0.0
	 */
	const FEE_MODALITY_FIXED_REGULAR_CALENDAR = 1;

	/**
	 * Fee modality as fixed by business day.
	 * 
	 * @var integer 
	 * @since 2.0.0
	 */
	const FEE_MODALITY_FIXED_BUSINESS_CALENDAR = 5;

	/**
	 * Fee modality as percentage by day at regular calendar.
	 * 
	 * @var integer 
	 * @since 2.0.0
	 */
	const FEE_MODALITY_PERCENTAGE_BY_DAY_REGULAR_CALENDAR = 2;

	/**
	 * Fee modality as percentage by mount at regular calendar.
	 * 
	 * @var integer 
	 * @since 2.0.0
	 */
	const FEE_MODALITY_PERCENTAGE_BY_MONTH_REGULAR_CALENDAR = 3;

	/**
	 * Fee modality as percentage by year at regular calendar.
	 * 
	 * @var integer 
	 * @since 2.0.0
	 */
	const FEE_MODALITY_PERCENTAGE_BY_YEAR_REGULAR_CALENDAR = 4;

	/**
	 * Fee modality as percentage by day at business calendar.
	 * 
	 * @var integer 
	 * @since 2.0.0
	 */
	const FEE_MODALITY_PERCENTAGE_BY_DAY_BUSINESS_CALENDAR = 6;

	/**
	 * Fee modality as percentage by mount at business calendar.
	 * 
	 * @var integer 
	 * @since 2.0.0
	 */
	const FEE_MODALITY_PERCENTAGE_BY_MONTH_BUSINESS_CALENDAR = 7;

	/**
	 * Fee modality as percentage by year at business calendar.
	 * 
	 * @var integer 
	 * @since 2.0.0
	 */
	const FEE_MODALITY_PERCENTAGE_BY_YEAR_BUSINESS_CALENDAR = 8;

	/**
	 * All fee modalities available.
	 * 
	 * @var array
	 * @since 2.0.0
	 */
	const FEE_MODALITIES = [1, 2, 3, 4, 5, 6, 7, 8];

	/**
	 * Discount modality as fixed.
	 * 
	 * @var integer 
	 * @since 2.0.0
	 */
	const DISCOUNT_MODALITY_FIXED = 1;

	/**
	 * Discount modality as fixed by day.
	 * 
	 * @var integer 
	 * @since 2.0.0
	 */
	const DISCOUNT_MODALITY_FIXED_REGULAR_CALENDAR = 3;

	/**
	 * Discount modality as fixed by business day.
	 * 
	 * @var integer 
	 * @since 2.0.0
	 */
	const DISCOUNT_MODALITY_FIXED_BUSINESS_CALENDAR = 4;

	/**
	 * Discount modality as percentage.
	 * 
	 * @var integer 
	 * @since 2.0.0
	 */
	const DISCOUNT_MODALITY_PERCENTAGE = 2;

	/**
	 * Discount modality as percentage by day.
	 * 
	 * @var integer 
	 * @since 2.0.0
	 */
	const DISCOUNT_MODALITY_PERCENTAGE_REGULAR_CALENDAR = 5;

	/**
	 * Discount modality as percentage by business day.
	 * 
	 * @var integer 
	 * @since 2.0.0
	 */
	const DISCOUNT_MODALITY_PERCENTAGE_BUSINESS_CALENDAR = 6;

	/**
	 * All discount modalities available.
	 * 
	 * @var array
	 * @since 2.0.0
	 */
	const DISCOUNT_MODALITIES = [1, 2, 3, 4, 5, 6,];

	/**
	 * Bank fine modality as fixed.
	 * 
	 * @var integer 
	 * @since 2.0.0
	 */
	const BANKFINE_MODALITY_FIXED = 1;

	/**
	 * Bank fine modality as percentage.
	 * 
	 * @var integer 
	 * @since 2.0.0
	 */
	const BANKFINE_MODALITY_PERCENTAGE = 2;

	/**
	 * All bank fine modalities available.
	 * 
	 * @var array
	 * @since 2.0.0
	 */
	const BANKFINE_MODALITIES = [1, 2];

	/**
	 * Modality alias.
	 *
	 * @var string
	 * @since 2.0.0
	 */
	protected $modality;

	/**
	 * Modality id.
	 *
	 * @var integer
	 * @since 2.0.0
	 */
	protected $mid;

	/**
	 * Modality amount.
	 *
	 * @var float
	 * @since 2.0.0
	 */
	protected $amount;

	/**
	 * Create an amount modality.
	 * 
	 * @param string $modality 
	 * @since 2.0.0
	 * @return self
	 * @throws InvalidFieldException
	 */
	public function __construct ( string $modality )
	{
		try
		{ static::validate($modality); }
		catch ( Exception $e )
		{ throw new InvalidFieldException('Modalidade.Tipo', $modality, $e->getMessage()); }

		$this->modality = $modality;
		return $this;
	}

	/**
	 * Get modality alias.
	 *
	 * @since 2.0.0
	 * @return string
	 */
	public function getModality () : string
	{ return $this->modality; }

	/**
	 * Get modality id.
	 *
	 * @since 2.0.0
	 * @return integer
	 */
	public function getId () : int
	{ return $this->mid; }

	/**
	 * Set modality id.
	 *
	 * @param integer $mid Modality id.
	 * @since 2.0.0
	 * @return self
	 * @throws InvalidFieldException
	 */
	public function setId ( int $mid ) 
	{ 
		try
		{ static::validateId($this->modality, $mid); }
		catch ( Exception $e )
		{ throw new InvalidFieldException('Modalidade.Id', $mid, $e->getMessage()); }

		$this->mid = $mid; 
		return $this; 
	}

	/**
	 * Get modality amount.
	 *
	 * @since 2.0.0
	 * @return float|null
	 */
	public function getAmount () : ?float
	{ return $this->amount; }

	/**
	 * Set modality amount.
	 *
	 * @param float|string $amount Modality amount.
	 * @since 2.0.0
	 * @return self
	 */
	public function setAmount ( $amount )
	{ $this->amount = \is_float($amount) ? $amount : \floatval($amount); return $this; }

	/**
	 * Export this object to an array.
	 * 
	 * @since 2.0.0
	 * @return array
	 */
	public function export () : array
	{
		$array = [
			'modalidade' => $this->mid
		];

		if ( !empty($this->amount) )
		{ $array['valorPerc'] = $this->amount; }

		return \array_merge($array, $this->extra);
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
			'modalidade' => 'setId',
			'valorPerc' => 'setAmount'
		];

		foreach ( $importable as $field => $method )
		{
			if ( isset($data[$field]) )
			{ 
				$this->{$method}($data[$field]); 
				unset($data[$field]);
			}
		}

		// Import extra fields
		foreach ( $data as $field => $value )
		{ $this->addExtra($field, $value); }

		return $this;
	}

	/**
	 * Throw an exception if $modality is a invalid modality.
	 *
	 * @param string $modality
	 * @since 2.0.0
	 * @return void
	 * @throws RuntimeException If is a invalid modality.
	 */
	public static function validate ( string $modality )
	{
		if ( \in_array($modality, static::MODALITIES, true) === false )
		{ throw new RuntimeException(\sprintf('O tipo de modalidade deve ser um dos seguintes: `%s`.', \implode('`, `', static::MODALITIES))); }
	}

	/**
	 * Throw an exception if $id is a invalid $modality id.
	 *
	 * @param string $modality
	 * @param integer $id
	 * @since 2.0.0
	 * @return void
	 * @throws RuntimeException If is a invalid modality.
	 */
	public static function validateId ( string $modality, int $id )
	{
		$valid = false;

		switch ( $modality )
		{
			case static::MODALITY_BANKFINE:
				$valid = \in_array($id, static::BANKFINE_MODALITIES, true) !== false;
				break;
			case static::MODALITY_DISCOUNT:
				$valid = \in_array($id, static::DISCOUNT_MODALITIES, true) !== false;
				break;
			case static::MODALITY_FEE:
				$valid = \in_array($id, static::FEE_MODALITIES, true) !== false;
				break;
			case static::MODALITY_REDUCTION:
				$valid = \in_array($id, static::REDUCTION_MODALITIES, true) !== false;
				break;
		}

		if ( !$valid )
		{ throw new RuntimeException(\sprintf('O ID da modalidade é incompatível para o tipo `%s`.', $modality)); }
	}

	/**
	 * Is $expected equal to $actual.
	 *
	 * @param string $expected
	 * @param string $actual
	 * @since 2.0.0
	 * @return boolean
	 * @throws RuntimeException If some is a invalid modality.
	 */
	public static function is ( string $expected, string $actual ) : bool
	{ 
		if ( \in_array($expected, static::MODALITIES, true) === false )
		{ throw new RuntimeException(\sprintf('O tipo de modalidade esperado deve ser um dos seguintes: `%s`.', \implode('`, `', static::MODALITIES))); }
		
		if ( \in_array($actual, static::MODALITIES, true) === false )
		{ throw new RuntimeException(\sprintf('O tipo de modalidade atual deve ser um dos seguintes: `%s`.', \implode('`, `', static::MODALITIES))); }
		
		return $expected === $actual; 
	}
}