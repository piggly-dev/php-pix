<?php
namespace Piggly\Pix;

use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use Piggly\Pix\Emv\MPM;
use Piggly\Pix\Enums\QrCode as QrCodeEnum;
use Piggly\Pix\Exceptions\QRCodeNotSupported;
use Piggly\Pix\Utils\Cast;

/**
 * Abstract payload to Pix code. Friendly interface
 * to MPM object.
 *
 * @package \Piggly\Pix
 * @subpackage \Piggly\Pix
 * @version 2.0.0
 * @since 2.0.0
 * @category Pix
 * @author Caique Araujo <caique@piggly.com.br>
 * @author Piggly Lab <dev@piggly.com.br>
 * @license MIT
 * @copyright 2021 Piggly Lab <dev@piggly.com.br>
 */
abstract class AbstractPayload
{
	/**
	 * EMV MPM
	 *
	 * @var MPM
	 * @since 2.0.0
	 */
	protected $mpm;

	/**
	 * Create EMV MPM object.
	 * 
	 * @since 2.0.0
	 * @return void
	 */
	public function __construct()
	{ $this->mpm = new MPM(); }

	/**
	 * Some banks may not allow the point
	 * of initiation method. This method
	 * removes it.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	public function unsetPointOfInitiation ()
	{
		$this->mpm->removeEmv('01');
		return $this;
	}

	/**
	 * Set the current pix merchant name.
	 * Max length 25
	 * 
	 * It will auto remove acents and auto
	 * cut to max length limit.
	 * 
	 * @param string $merchantName Pix merchant name.
	 * @since 2.0.0
	 * @return self
	 */
	public function setMerchantName ( string $merchantName )
	{ 
		$this->mpm->getEmv('59')->setValue(Cast::upperStr(Cast::cleanStr($merchantName)));
		return $this;
	}

	/**
	 * Set the current pix merchant city.
	 * Max length 15
	 * 
	 * It will auto remove acents and auto
	 * cut to max length limit.
	 * 
	 * @param string $merchantCity Pix merchant city.
	 * @since 2.0.0
	 * @return self
	 */
	public function setMerchantCity ( string $merchantCity  )
	{ 
		$this->mpm->getEmv('60')->setValue(Cast::upperStr(Cast::cleanStr($merchantCity)));
		return $this;
	}

	/**
	 * Set the current pix postal code.
	 * Max length 15
	 * 
	 * It will auto remove acents and auto
	 * cut to max length limit.
	 * 
	 * @param string $postalCode Pix postal code.
	 * @since 2.0.0
	 * @return self
	 */
	public function setPostalCode ( string $postalCode  )
	{ 
		$this->mpm->getEmv('61')->setValue(Cast::upperStr(Cast::cleanStr($postalCode), true));
		return $this;
	}
	
	/**
	 * Get the current pix code.
	 * 
	 * @param bool $regenerate
	 * @since 2.0.0
	 * @return string
	 * @throws EmvIdIsRequiredException When some field is invalid.
	 */
	public function getPixCode ( bool $regenerate = false ) : string
	{ return $this->mpm->export($regenerate); }
	
	/**
	 * Return the qr code based in current pix code.
	 * The qr code format is a base64 image/png.
	 * 
	 * @param string $imageType Type of output image.
	 * @param string $ecc QrCode ECC.
	 * @since 2.0.0
	 * @return string
	 * @throws Exception When something went wrong.
	 * @throws QRCodeNotSupported QR Code is not supported.
	 */
	public function getQRCode ( string $imageType = QrCodeEnum::OUTPUT_SVG, int $ecc = QrCodeEnum::ECC_M ) : string
	{ 
		if ( !self::supportQrCode() )
		{ throw new QRCodeNotSupported(); }

		$options = new QROptions([
			'outputLevel' => $ecc,
			'outputType' => $imageType
		]);

		return (new QRCode($options))->render($this->getPixCode()); 
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
	 * Return if php supports QR Code.
	 * 
	 * @since 2.0.0
	 * @return bool
	 */
	public static function supportQrCode () : bool
	{ return ((float)phpversion('Core') >= 7.2) && (extension_loaded('gd') && function_exists('gd_info')); }
}