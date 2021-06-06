<?php
namespace Piggly\Pix;

use Piggly\Pix\Emv\MPM;
use Piggly\Pix\Exceptions\InvalidEmvFieldException;

/**
 * Dynamic payload to Pix code. Used when
 * pix was issued by an API and returned
 * as a URL.
 *
 * @package \Piggly\Pix
 * @subpackage \Piggly\Pix
 * @version 2.0.0
 * @since 2.0.0
 * @category Payload
 * @author Caique Araujo <caique@piggly.com.br>
 * @author Piggly Lab <dev@piggly.com.br>
 * @license MIT
 * @copyright 2021 Piggly Lab <dev@piggly.com.br>
 */
class DynamicPayload extends AbstractPayload
{
	/**
	 * Set all default emvs to dynamic payload.
	 * 
	 * @since 2.0.0
	 * @return void
	 */
	public function __construct ()
	{
		parent::__construct();
		
		// Change point of initiation method
		$this->mpm->getEmv('01')->setValue('12');
		// Remove Transaction Amount
		$this->mpm->removeEmv('54');
		// Remove Pix Key
		$this->mpm->getEmv('26')->removeField('01');
		// Remove Payment Description
		$this->mpm->getEmv('26')->removeField('02');
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
		// Change point of initiation method
		$mpm->getEmv('01')->setValue('12');
		// Remove Transaction Amount
		$mpm->removeEmv('54');
		// Remove Pix Key
		$mpm->getEmv('26')->removeField('01');
		// Remove Payment Description
		$mpm->getEmv('26')->removeField('02');
		// Set default Reference Label
		$mpm->getEmv('62')->getField('05')->setDefault('***');

		$this->mpm = $mpm;
		return $this;
	}

	/**
	 * Set current payload JSON URL.
	 *
	 * @param string $url
	 * @since 2.0.0
	 * @return self
	 */
	public function setUrl ( string $url )
	{
		if ( \preg_match('/^https?:\/\/(www\.)?[-a-zA-Z0-9@:%._\+~#=]{1,256}\.[a-zA-Z0-9()]{1,6}\b([-a-zA-Z0-9()@:%_\+.~#?&\/=]*)$/i', $url) === false )
		{ throw new InvalidEmvFieldException($this->mpm->getEmv('26')->getField('25')->getName(), $url, 'Não é uma URL válida.'); }
	
		$this->mpm->getEmv('26')->getField('25')->setValue($url);
		return $this;
	}
}