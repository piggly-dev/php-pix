<?php
namespace Piggly\Pix\Emv;

/**
 * Payload to EMV MPM Format.
 * 
 * @see https://www.emvco.com/wp-content/plugins/pmpro-customizations/oy-getfile.php?u=/wp-content/uploads/documents/EMVCo-Merchant-Presented-QR-Specification-v1.1.pdf
 * @package \Piggly\Pix
 * @subpackage \Piggly\Pix\Emv
 * @version 2.0.0
 * @since 2.0.0
 * @category Emv
 * @author Caique Araujo <caique@piggly.com.br>
 * @author Piggly Lab <dev@piggly.com.br>
 * @license MIT
 * @copyright 2021 Piggly Lab <dev@piggly.com.br>
 */
class MPM
{
	/**
	 * All emvs fields.
	 *
	 * @var array<AbstractField>
	 * @since 2.0.0
	 */
	protected $emvs;

	/**
	 * Cache for last code.
	 *
	 * @var string
	 * @since 2.0.0
	 */
	protected $code;
	
	/**
	 * All default EMVS.
	 * 
	 * @since 2.0.0
	 * @return void
	 */
	public function __construct ()
	{
		$this->emvs = [
			'00' => new Field('00', 'Payload Format Indicator', 2, true, '01'),
			'01' => new Field('01', 'Point of Initiation Method', 2, false, '11'),
			'26' => new MultiField('26', 'Merchant Account Information', 99, true, 0, 99),
			'52' => new Field('52', 'Merchant Category Code', 4, true, '0000'),
			'53' => new Field('53', 'Transaction Currency', 3, true, '986'),
			'54' => new Field('54', 'Transaction Amount', 13, false),
			'58' => new Field('58', 'Country Code', 2, true, 'BR'),
			'59' => new Field('59', 'Merchant Name', 25, true),
			'60' => new Field('60', 'Merchant City', 15, true),
			'61' => new Field('61', 'Postal Code', 10, false),
			'62' => new MultiField('62', 'Additional Data Field Template', 99, true, 0, 99)
		];

		$this->emvs['26']
			->addField(new Field('00', 'Globally Unique Identifier', 32, true, 'br.gov.bcb.pix'))
			->addField(new Field('01', 'Pix Key', 36, false))
			->addField(new Field('02', 'Payment Description', 40, false))
			->addField(new Field('25', 'Payment URL', 77, false));

		$this->emvs['62']
			->addField(new Field('05', 'Reference Label', 25, false));
	}

	/**
	 * Get a child emv by $id.
	 *
	 * @param string $id
	 * @since 2.0.0
	 * @return Field|MultiField|null
	 */
	public function getEmv ( string $id )
	{ return $this->emvs[$id] ?? null; }

	/**
	 * Check if has child emv by $id.
	 *
	 * @param string $id
	 * @since 2.0.0
	 * @return bool
	 */
	public function hasEmv ( string $id ) : bool
	{ return isset($this->emvs[$id]); }

	/**
	 * Remove a child emv by $id.
	 *
	 * @param string $id
	 * @since 2.0.0
	 * @return self
	 */
	public function removeEmv ( string $id )
	{ unset($this->emvs[$id]); return $this; }

	/**
	 * Export EMVs to a payload code.
	 *
	 * @param bool $regenerate
	 * @since 2.0.0
	 * @return string
	 * @throws InvalidEmvFieldException When field is wrong.
	 */
	public function export ( bool $regenerate = false ) : string
	{
		if ( !empty($this->code) && !$regenerate )
		{ return $this->code; }

		$emvs = $this->emvs;
		\ksort($this->emvs);

		$code = '';

		foreach ( $emvs as $field )
		{ $code .= $field->export(); }

		$code .= '6304'.static::CRC16($code.'6304');

		$this->code = $code;
		return $this->code;
	}

	/**
	 * Calcultate the CRC16 to any $payload string.
	 *
	 * @param string $payload
	 * @since 2.0.0
	 * @return string
	 */
	public static function CRC16 ( string $payload ) : string
	{ 
		// Standard values by BACEN
		$polynomial = 0x1021;
		$response   = 0xFFFF;

		// Checksum
		if ( ( $length = \strlen($payload) ) > 0 ) 
		{
			for ( $offset = 0; $offset < $length; $offset++ ) 
			{
				$response ^= ( \ord( $payload[$offset] ) << 8 );
				
				for ( $bitwise = 0; $bitwise < 8; $bitwise++ ) 
				{
					if ( ( $response <<= 1 ) & 0x10000 ) 
					{ $response ^= $polynomial; }

					$response &= 0xFFFF;
				}
			}
	  }

	  // CRC16 calculated
	  return \strtoupper(\str_pad(\dechex($response), 4, '0', \STR_PAD_LEFT));
	}
}