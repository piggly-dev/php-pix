<?php
namespace Piggly\Pix;

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
		// Transaction Amount is Required
		$this->mpm->getEmv('54')->required(true);
		// Remove Payment URL
		$this->mpm->getEmv('26')->removeField('25');
		// Set default Reference Label
		$this->mpm->getEmv('62')->getField('05')->setDefault('***');
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
	public function setKey ( string $type, string $key )
	{
		// Validate Key
		Parser::validate($type, $key);
		return $this->mpm->getEmv('26')->getField('01')->setValue(Parser::parse($type, $key));
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
		$this->mpm->getEmv('26')->getField('02')->setValue(Cast::cleanStr(Cast::upperStr($description)));
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
	 * @return self
	 */
	public function setTid ( ?string $tid )
	{ 
		$_tid = null;

		if ( is_null( $tid ) )
		{ $_tid = Parser::getRandom(); }
		else 
		{ $_tid = preg_replace('/[^A-Za-z\0-9]+/', '', $tid);}
				
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

// /**
//  * The Pix Payload class.
//  * 
//  * This is used to set up pix data and follow the EMV®1 pattern and standards.
//  * When set up all data, the export() method will generate the full pix payload.
//  *
//  * @since      1.0.0 
//  * @package    Piggly\Pix
//  * @subpackage Piggly\Pix
//  * @author     Caique <caique@piggly.com.br>
//  */
// class StaticPayload extends Payload
// {
// 	/**
// 	 * Defines if payment is reusable.
// 	 * @since 1.2.0
// 	 * @var boolean
// 	 */
// 	protected $reusable = true;

// 	/**
// 	 * In static payload always will be true. It will be ignored.
// 	 * 
// 	 * @param string $reusable If pix can be reusable.
// 	 * @since 1.2.0 Will be ignored
// 	 * @return self
// 	 */
// 	public function setAsReusable ( bool $reusable = true )
// 	{ $this->reusable = true; return $this; }
// }