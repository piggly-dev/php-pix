<?php
namespace Piggly\Pix;

use Piggly\Pix\Emv\MPM;
use Piggly\Pix\Utils\Cast;

/**
 * Static payload to Pix code. Used when
 * there is not API to create a pix.
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
class StaticPayload extends AbstractPayload
{
	/**
	 * Set all default emvs to static payload.
	 * 
	 * @since 2.0.0
	 * @return void
	 */
	public function __construct ()
	{
		parent::__construct();
		
		// Pix Key is Required
		$this->mpm->getEmv('26')->getField('01')->required(true);
		// Remove Payment URL
		$this->mpm->getEmv('26')->removeField('25');
		// Set default Reference Label
		$this->mpm->getEmv('62')->getField('05')->setDefault('***');
	}

	/**
	 * Change EMV MPM object.
	 *
	 * @param MPM $mpm
	 * @since 2.0.0
	 * @return self
	 */
	public function changeMpm ( MPM $mpm )
	{
		// Pix Key is Required
		$this->mpm->getEmv('26')->getField('01')->required(true);
		// Remove Payment URL
		$mpm->getEmv('26')->removeField('25');
		// Set default Reference Label
		$mpm->getEmv('62')->getField('05')->setDefault('***');

		$this->mpm = $mpm;
		return $this;
	}

	/**
	 * Set the current pix key.
	 * 
	 * @param string $keyType Pix key type.
	 * @param string $pixKey Pix key.
	 * @since 2.0.0
	 * @return self
	 * @throws InvalidPixKeyTypeException When pix key type is invalid.
	 * @throws InvalidPixKeyException When pix key is invalid base in key type.
	 */
	public function setPixKey ( string $type, string $key )
	{
		// Validate Key
		Parser::validate($type, $key);
		$this->mpm->getEmv('26')->getField('01')->setValue(Parser::parse($type, $key));
		return $this;
	}

	/**
	 * Set the current pix description.
	 * Max length 40
	 * 
	 * Merchant Account Information has size 
	 * limit as 99 characters including:
	 * 
	 * GUI ID+SIZE = 04 chars
	 * KEY ID+SIZE = 04 chars
	 * GUI SIZE = 14 chars
	 * KEY SIZE = 00..36 chars
	 * 
	 * The number of chars which has left will be vary based
	 * in GUI + KEY size. Which means at least it will have
	 * 40 chars available to description field. 
	 * 
	 * That's why we choose 40 chars as max length size.
	 * 
	 * @param string $description Pix description.
	 * @since 2.0.0
	 * @return self
	 */
	public function setDescription ( string $description )
	{ 
		$this->mpm->getEmv('26')->getField('02')->setValue(Cast::upperStr(Cast::cleanStr($description)), true);
		return $this;
	}

	/**
	 * Set the current pix transaction id.
	 * Max length 25
	 * 
	 * When $tid is null, Parser::getRandom()
	 * will generate an unique transaction id.
	 * You can still use Parse::getRandom()
	 * as parameter of this method.
	 * 
	 * A static pix code created including
	 * a transaction id, can be consulted by 
	 * usign an pix api which allows it.
	 * 
	 * @param string|null $tid Pix transaction id.
	 * @since 2.0.0
	 * @since 2.0.1 Fix remove of * char.
	 * @return self
	 */
	public function setTid ( ?string $tid )
	{ 
		$_tid = null;

		if ( is_null( $tid ) )
		{ $_tid = Parser::getRandom(); }
		else 
		{ $_tid = preg_replace('/[^A-Za-z0-9\*]+/', '', $tid);}
				
		$this->mpm->getEmv('62')->getField('05')->setValue($_tid);
		return $this;
	}

	/**
	 * Get the current transaction id. 
	 * When setTid() was set to null,
	 * Parser::getRandom() will generate
	 * an unique transaction id.
	 * 
	 * You may need to know this transaction id.
	 * 
	 * @since 2.0.0
	 * @return string|null
	 */
	public function getTid () : ?string
	{ return $this->mpm->getEmv('62')->getField('05')->getValue(); }

	/**
	 * Set the current pix transaction amount.
	 * 
	 * EMV -> ID 54
	 * Max length 13 0000000000.00
	 * 
	 * @param string $amount Pix transaction amount.
	 * @since 2.0.0
	 * @return self
	 * @throws InvalidEmvFieldException When amount is greater than 13 chars.
	 */
	public function setAmount ( float $amount )
	{ 
		$this->mpm->getEmv('54')->setValue(number_format( $amount, 2, '.', '' ));
		return $this; 
	}
}