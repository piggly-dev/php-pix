<?php
namespace Piggly\Tests\Pix;

use PHPUnit\Framework\TestCase;
use Piggly\Pix\Emv\MPM;
use Piggly\Pix\Parser;
use Piggly\Pix\StaticPayload;

/**
 * @coversDefaultClass \Piggly\Pix\StaticPayload
 */
class ValidStaticPixTest extends TestCase
{
	/**
	 * Assert if $payload code is same as $expected.
	 * 
	 * It will ignore case and ignore the CRC16.
	 * 
	 * Some bank may lower or upper case data, remove
	 * or add fields automatic. It will return different
	 * CRC16 for "same" data. That's why it will ignore
	 * it and uppercase both codes.
	 *
	 * @covers ::getPixCode
	 * @dataProvider dataPixes
	 * @test Expecting positive assertion.
	 * @param string $expected Expected result.
	 * @param StaticPayload $payload Payload to pix.
	 * @return boolean
	 */
	public function isPixValid ( string $expected, StaticPayload $payload )
	{ 
		$expected = \substr(\strtoupper($expected), 0, \strlen($expected)-8);
		$actual = \substr(\strtoupper($payload->getPixCode()), 0, \strlen($payload->getPixCode())-8);
		$this->assertEquals($expected, $actual); 
	}
	
	/**
	 * Assert if $expected matches to $actual CRC16.
	 *
	 * @covers MPM::CRC16
	 * @dataProvider dataCRC
	 * @test Expecting positive assertion.
	 * @param string $actual String to generate.
	 * @return boolean
	 */
	public function isCRCValid ( string $actual )
	{ 
		$expected = \substr($actual, -4);
		$actual   = \str_replace($expected, '', $actual);
		$this->assertEquals($expected, MPM::CRC16($actual)); 
	}

	/**
	 * A list with valid pix created.
	 * Provider to isPixValid() method.
	 * 
	 * Please, add here only pix code generated
	 * by bank applications.
	 * 
	 * @return array
	 */
	public function dataPixes () : array
	{
		$pix = [];

		// Try to reproduce the codes with StaticPayload

		// BANCO INTER (SEM TID)
		$pix[] = [
			'00020101021126860014br.gov.bcb.pix0136285fb964-0087-4a94-851a-5a161ed8888a0224Solicitacao de pagamento52040000530398654041.015802BR5913STUDIO PIGGLY6007Uberaba62070503***63044EED',
			(new StaticPayload())
				->setAmount(1.01)
				->setPixKey(Parser::KEY_TYPE_RANDOM, '285fb964-0087-4a94-851a-5a161ed8888a')
				->setDescription('Solicitação de pagamento')
				->setMerchantName('Studio Piggly')
				->setMerchantCity('Uberaba')
		];
		// BANCO INTER (COM TID)
		$pix[] = [
			'00020101021126790014br.gov.bcb.pix0136285fb964-0087-4a94-851a-5a161ed8888a0217DC ACENTUACAO 00152040000530398654041.025802BR5913STUDIO PIGGLY6007Uberaba62090505TX10263040665',
			(new StaticPayload())
				->setAmount(1.02)
				->setPixKey(Parser::KEY_TYPE_RANDOM, '285fb964-0087-4a94-851a-5a161ed8888a')
				->setTid('TX-102')
				->setDescription('DC Acentuação 001')
				->setMerchantName('Studio Piggly')
				->setMerchantCity('Uberaba')
		];

		// NUBANK (SEM TID)
		$pix[] = [
			'00020126580014BR.GOV.BCB.PIX0136aae2196f-5f93-46e4-89e6-73bf4138427b52040000530398654041.015802BR5922Caique Monteiro Araujo6009SAO PAULO61080540900062070503***630456D6',
			(new StaticPayload())
				->setAmount(1.01)
				->setPixKey(Parser::KEY_TYPE_RANDOM, 'aae2196f-5f93-46e4-89e6-73bf4138427b')
				->setMerchantName('Caique Monteiro Araujo')
				->setMerchantCity('São Paulo')
				->setPostalCode('05409000')
				->unsetPointOfInitiation()
		];

		// NUBANK (COM TID)
		$pix[] = [
			'00020126580014BR.GOV.BCB.PIX0136aae2196f-5f93-46e4-89e6-73bf4138427b52040000530398654041.025802BR5922Caique Monteiro Araujo6009SAO PAULO61080540900062090505TX1026304F4DB',
			(new StaticPayload())
				->setAmount(1.02)
				->setPixKey(Parser::KEY_TYPE_RANDOM, 'aae2196f-5f93-46e4-89e6-73bf4138427b')
				->setTid('TX-102')
				->setMerchantName('Caique Monteiro Araujo')
				->setMerchantCity('São Paulo')
				->setPostalCode('05409000')
				->unsetPointOfInitiation()
		];

		return $pix;
	}

	/**
	 * A list with valid pix created to validate CRC.
	 * Provider to isCRCValid() method.
	 * 
	 * Please, add here only pix code generated
	 * by bank applications.
	 * 
	 * @return array
	 */
	public function dataCRC () : array
	{
		$pix = [];

		// Try to reproduce the codes with StaticPayload

		// BANCO INTER (SEM TID)
		$pix[] = [
			'00020101021126860014br.gov.bcb.pix0136285fb964-0087-4a94-851a-5a161ed8888a0224Solicitacao de pagamento52040000530398654041.015802BR5913STUDIO PIGGLY6007Uberaba62070503***63044EED',
		];
		// BANCO INTER (COM TID)
		$pix[] = [
			'00020101021126790014br.gov.bcb.pix0136285fb964-0087-4a94-851a-5a161ed8888a0217DC ACENTUACAO 00152040000530398654041.025802BR5913STUDIO PIGGLY6007Uberaba62090505TX10263040665',
		];

		// NUBANK (SEM TID)
		$pix[] = [
			'00020126580014BR.GOV.BCB.PIX0136aae2196f-5f93-46e4-89e6-73bf4138427b52040000530398654041.015802BR5922Caique Monteiro Araujo6009SAO PAULO61080540900062070503***630456D6',
		];

		// NUBANK (COM TID)
		$pix[] = [
			'00020126580014BR.GOV.BCB.PIX0136aae2196f-5f93-46e4-89e6-73bf4138427b52040000530398654041.025802BR5922Caique Monteiro Araujo6009SAO PAULO61080540900062090505TX1026304F4DB'
		];

		return $pix;
	}
}