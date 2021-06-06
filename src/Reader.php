<?php
namespace Piggly\Pix;

use Exception;
use Piggly\Pix\Emv\Field;
use Piggly\Pix\Emv\MPM;
use Piggly\Pix\Emv\MultiField;
use Piggly\Pix\Exceptions\CannotParseKeyTypeException;
use Piggly\Pix\Exceptions\InvalidPixCodeException;

/**
 * The Pix Reader class.
 * This is used to extract pix data of a
 * pix code and return the AbstractPayload
 * object with all data extracted.
 *
 * @package \Piggly\Pix
 * @subpackage \Piggly\Pix
 * @version 2.0.0
 * @since 1.1.0
 * @category Pix
 * @author Caique Araujo <caique@piggly.com.br>
 * @author Piggly Lab <dev@piggly.com.br>
 * @license MIT
 * @copyright 2021 Piggly Lab <dev@piggly.com.br>
 */
class Reader
{
	/**
	 * Pix code.
	 * 
	 * @since 1.1.0
	 * @var string
	 */
	protected $raw;

	/**
	 * EMV MPM object.
	 * 
	 * @since 2.0.0
	 * @var MPM
	 */
	protected $mpm;

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
	 * @since 1.2.0 Custom exception; Throw exception when pix code is invalid.
	 * @since 2.0.0 Work with EMV MPM object.
	 * @param string $pixCode Current pix code to extract...
	 * @return self
	 * @throws InvalidPixCodeException When pix code is invalid.
	 */
	public function extract ( string $pixCode )
	{
		if ( !$this->isValidCode($pixCode) )
		{ throw new InvalidPixCodeException($pixCode); }

		$this->raw = $pixCode; 
		$this->mpm = new MPM();

		while ( !empty($pixCode) )
		{ $this->extractor($pixCode, $this->mpm); }

		return $this;
	}

	/**
	 * Extract from $code all $emvs.
	 * 
	 * @param string $code
	 * @param MPM|MultiField $emvs
	 * @since 2.0.0
	 * @return self
	 */
	protected function extractor ( string &$code, $emvs = null )
	{
		$curr_id  = $this->getData($code, 2);
		$curr_emv = $this->getEMV($curr_id, $code);

		$emv = $emvs instanceof MPM ? $emvs->getEmv($curr_id) : $emvs->getField($curr_id);

		if ( \is_null($emv) )
		{ return $this; }

		if ( $emv instanceof Field )
		{ 
			$emv->setValue($curr_emv['value']); 
			return $this;
		}

		if ( $emv instanceof MultiField )
		{ 
			$pixCode = $curr_emv['value'];

			while ( !empty($pixCode) )
			{ $this->extractor($pixCode, $emv); }

			return $this;
		}

		return $this;
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
	 * Will export EMVs to a payload object.
	 * 
	 * Return StaticPayload when the Point of Initiation Method
	 * is equal to 11, and return DynamicPayload when is equal
	 * to 12.
	 * 
	 * @since 1.2.0
	 * @since 1.2.1 Get pix key type only when pix key exists.
	 * @since 2.0.0 Work with EMV MPM object.
	 * @return StaticPayload|DynamicPayload
	 * @throws InvalidPixCodeException
	 * @throws CannotParseKeyTypeException
	 */
	public function export ()
	{
		if ( !$this->isValidCode($this->raw) )
		{ throw new InvalidPixCodeException($this->raw); }

		// Create a new and fresh MPM instance
		$mpm = new MPM();
		$pixCode = $this->raw;

		while ( !empty($pixCode) )
		{ $this->extractor($pixCode, $mpm); }

		$poi = $mpm->getEmv('01')->getValue();

		if ( $poi == 11 )
		{ return (new StaticPayload())->changeMpm($mpm); }
		else if ( $poi == 12 )
		{ return (new DynamicPayload())->changeMpm($mpm); }

		$url = $mpm->getEmv('26')->getField('25')->getValue();

		if ( empty($url) )
		{ return (new StaticPayload())->changeMpm($mpm); }
		else
		{ return (new DynamicPayload())->changeMpm($mpm); }

		// Cannot determine the Point of Initiation Method
		throw new InvalidPixCodeException($this->raw);
	}

	/**
	 * Get the EMV MPM object.
	 *
	 * @since 2.0.0
	 * @return MPM
	 */
	public function getMPM () : MPM
	{ return $this->mpm; }

	/**
	 * Get current Pix Key.
	 * 
	 * @since 1.1.0
	 * @since 2.0.0 Changed to MPM object.
	 * @return string|null
	 */
	public function getPixKey () : ?string
	{ return $this->mpm->getEmv('26')->getField('01')->getValue();	}

	/**
	 * Get current Pix description.
	 * 
	 * @since 1.1.0
	 * @since 2.0.0 Changed to MPM object.
	 * @return string|null
	 */
	public function getDescription () : ?string
	{ return $this->mpm->getEmv('26')->getField('02')->getValue();	}

	/**
	 * Get current Pix payment url.
	 * 
	 * @since 1.1.0
	 * @since 2.0.0 Changed to MPM object.
	 * @return string|null
	 */
	public function getUrl () : ?string
	{ return $this->mpm->getEmv('26')->getField('25')->getValue();	}

	/**
	 * Get current Pix amount.
	 * If pix amount is not set it will return zero.
	 * 
	 * @since 1.1.0
	 * @since 2.0.0 Changed to MPM object.
	 * @return float
	 */
	public function getAmount () : float
	{ return \floatval($this->mpm->getEmv('54')->getValue()??0); }

	/**
	 * Get current Pix merchant name.
	 * 
	 * @since 1.1.0
	 * @since 2.0.0 Changed to MPM object.
	 * @return string|null
	 */
	public function getMerchantName () : ?string
	{ return $this->mpm->getEmv('59')->getValue(); }

	/**
	 * Get current Pix merchant city.
	 * 
	 * @since 1.1.0
	 * @since 2.0.0 Changed to MPM object.
	 * @return string|null
	 */
	public function getMerchantCity () : ?string
	{ return $this->mpm->getEmv('60')->getValue(); }

	/**
	 * Get current Pix postal code.
	 * 
	 * @since 1.1.0
	 * @since 2.0.0 Changed to MPM object.
	 * @return string|null
	 */
	public function getPostalCode () : ?string
	{ return $this->mpm->getEmv('61')->getValue(); }

	/**
	 * Get current Pix transaction id.
	 * 
	 * @since 1.1.0
	 * @since 2.0.0 Changed to MPM object.
	 * @return string|null
	 */
	public function getTid () : ?string
	{ return $this->mpm->getEmv('62')->getField('05')->getValue(); }

	/**
	 * Get EMV from $code string extracting your id, size and value.
	 * 
	 * @param string $code
	 * @param string $id
	 * @since 1.1.0
	 * @return array
	 */
	protected function getEMV ( string $id, string &$code ) : array 
	{
		$size = $this->getData($code, 2);

		return [
			'id' => $id,
			'size' => $size,
			'value' => $this->getData($code, \intval($size))
		];
	}

	/**
	 * Updates the code string extracting data and returning data
	 * extracted.
	 * 
	 * @param string $code
	 * @param int $size
	 * @since 1.1.0
	 * @since 2.0.0 Changed parameters
	 * @return string
	 */
	protected function getData ( string &$code, int $size = 2 ) : string
	{ 
		// Extract string till $size position
		$extracted = \mb_substr($code, 0, $size);
		// Update data after $size position
		$code = \mb_substr($code, $size );
		return $extracted; 
	}

	/**
	 * Validates if pix code is QRCPS-MPM version.
	 * 
	 * @since 1.1.0
	 * @return bool
	 */
	protected function isValidCode ( string $pixCode ) : bool
	{ return strpos($pixCode, '000201') !== false; }
}