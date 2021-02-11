<?php
namespace Piggly\Pix;

/**
 * The Pix Reader class.
 * 
 * This is used to extract pix data of a pix code and return the Payload
 * object with all data extracted.
 *
 * @since      1.1.0 
 * @package    Piggly\Pix
 * @subpackage Piggly\Pix
 * @author     Caique <caique@piggly.com.br>
 */
class Reader
{
	/**
	 * Pix code.
	 * @since 1.1.0
	 * @var string
	 */
	protected $raw;

	/**
	 * Pix code.
	 * @since 1.1.0
	 * @var string
	 */
	protected $pixCode;

	/**
	 * Pix code.
	 * @since 1.1.0
	 * @var array
	 */
	protected $emvs;

	/**
	 * Set the current pix code.
	 * 
	 * @since 1.1.0
	 * @param string $pixCode Pix code.
	 * @since 1.1.0
	 * @return self
	 * @throws Exception
	 */
	public function __construct ( string $pixCode )
	{ return $this->extract($pixCode); }

	/**
	 * Read the pix code mounting each EMV field and adding it
	 * to an array. Some EMVs has children, then, it extract its
	 * children too.
	 * 
	 * @since 1.1.0
	 * @param string $pixCode Current pix code to extract...
	 * @return self
	 */
	public function extract ( string $pixCode ) : self
	{
		$this->pixCode = $pixCode; 
		$this->raw     = $pixCode; 
		$this->emvs    = [];

		while ( !empty($this->pixCode) )
		{
			$currId = $this->getData(2, $this->pixCode);
			$this->emvs[] = $this->getEMV($currId, $this->pixCode);

			switch ( $currId )
			{
				case Payload::ID_MERCHANT_ACCOUNT_INFORMATION:
				case Payload::ID_ADDITIONAL_DATA_FIELD_TEMPLATE:
					$this->extractChild( count($this->emvs)-1 );
					break;
				default:
					break;
			}
		}

		return $this;
	}

	/**
	 * Read the value to current EMV index, and mounting each EMV child 
	 * field and adding it to an array.
	 * 
	 * @since 1.1.0
	 * @return void
	 */
	protected function extractChild( int $index )
	{
		$emv   =& $this->emvs[$index];
		$value =  $emv['value'];
		$data  =  [];

		while ( !empty( $value ) )
		{
			$currId = $this->getData(2, $value);
			$data[] = $this->getEMV($currId, $value);
		}
		
		$emv['value'] = $data;
	}

	/**
	 * Raw pix code.
	 * 
	 * @since 1.1.0
	 * @return string
	 */
	public function getRaw () : string 
	{ return $this->raw; }

	/**
	 * An array with all emvs.
	 * 
	 * @since 1.1.0
	 * @return string
	 */
	public function getEMVs () : array 
	{ return $this->emvs; }

	/**
	 * Get current Pix Key.
	 * 
	 * @since 1.1.0
	 * @return string|null
	 */
	public function getPixKey () : ?string
	{
		$emv = $this->findEMV( Payload::ID_MERCHANT_ACCOUNT_INFORMATION );

		if ( !empty( $emv ) )
		{
			$emv = $this->findEMV( Payload::ID_MERCHANT_ACCOUNT_INFORMATION_KEY, $emv['value'] );

			if ( !empty( $emv ) )
			{ return $emv['value']; }
		}

		return null;
	}

	/**
	 * Get current Pix description.
	 * 
	 * @since 1.1.0
	 * @return string|null
	 */
	public function getDescription () : ?string
	{
		$emv = $this->findEMV( Payload::ID_MERCHANT_ACCOUNT_INFORMATION );

		if ( !empty( $emv ) )
		{
			$emv = $this->findEMV( Payload::ID_MERCHANT_ACCOUNT_INFORMATION_DESCRIPTION, $emv['value'] );

			if ( !empty( $emv ) )
			{ return $emv['value']; }
		}

		return null;
	}

	/**
	 * Get current Pix amount.
	 * 
	 * @since 1.1.0
	 * @return float|null
	 */
	public function getAmount () : ?float
	{
		$emv = $this->findEMV( Payload::ID_TRANSACTION_AMOUNT );

		if ( !empty( $emv ) )
		{ return floatval($emv['value']); }

		return null;
	}

	/**
	 * Get current Pix merchant name.
	 * 
	 * @since 1.1.0
	 * @return string|null
	 */
	public function getMerchantName () : ?string
	{
		$emv = $this->findEMV( Payload::ID_MERCHANT_NAME );

		if ( !empty( $emv ) )
		{ return $emv['value']; }

		return null;
	}

	/**
	 * Get current Pix merchant name.
	 * 
	 * @since 1.1.0
	 * @return string|null
	 */
	public function getMerchantCity () : ?string
	{
		$emv = $this->findEMV( Payload::ID_MERCHANT_CITY );

		if ( !empty( $emv ) )
		{ return $emv['value']; }

		return null;
	}

	/**
	 * Get current Pix transaction id.
	 * 
	 * @since 1.1.0
	 * @return string|null
	 */
	public function getTid () : ?string
	{
		$emv = $this->findEMV( Payload::ID_ADDITIONAL_DATA_FIELD_TEMPLATE );

		if ( !empty( $emv ) )
		{
			$emv = $this->findEMV( Payload::ID_ADDITIONAL_DATA_FIELD_TEMPLATE_TID, $emv['value'] );

			if ( !empty( $emv ) )
			{ return $emv['value']; }
		}

		return null;
	}

	/**
	 * Find an EMV based in your id.
	 * 
	 * @since 1.1.0
	 * @param string $id
	 * @param array $emvs Default is $this->emvs
	 * @return array|null
	 */
	protected function findEMV ( string $id, ?array $emvs = null ) : ?array
	{
		if ( empty( $emvs ) )
		{ $emvs = $this->emvs; }

		foreach ( $emvs as $emv )
		{
			if ( $emv['id'] === $id )
			{ return $emv; }
		}

		return null;
	}

	/**
	 * Get EMV from $code string extracting your id, size and value.
	 * 
	 * @since 1.1.0
	 * @param string $id
	 * @param string $code
	 * @return array
	 */
	protected function getEMV ( $id, &$code ) : array 
	{
		$size = $this->getData(2, $code);

		return [
			'id' => $id,
			'size' => $size,
			'value' => $this->getData( intval($size), $code )
		];
	}

	/**
	 * Updates the code string extracting data and returning data
	 * extracted.
	 * 
	 * @since 1.1.0
	 * @return string
	 */
	protected function getData ( int $size = 2, &$code ) : string
	{ 
		// Extract string till $size position
		$extracted = mb_substr($code, 0, $size);
		// Update data after $size position
		$code = mb_substr($code, $size );
		return $extracted; 
	}
}