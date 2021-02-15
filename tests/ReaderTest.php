<?php
namespace Piggly\Tests\Pix;

use Exception;
use PHPUnit\Framework\TestCase;
use Piggly\Pix\Exceptions\InvalidPixCodeException;
use Piggly\Pix\Parser;
use Piggly\Pix\Payload;
use Piggly\Pix\Reader;

class ReaderTest extends TestCase
{
	/** @var string Nubank Pix code. */
	protected $nuBank;

	protected function setUp () 
	{
		$this->nuBank = '00020126770014BR.GOV.BCB.PIX0136aae2196f-5f93-46e4-89e6-73bf4138427b0215Descrição Teste52040000530398654041.005802BR5922Caique Monteiro Araujo6009SAO PAULO61080540900062160512NUR1pycKbhb063046BF7';
	}

	/** @test */
	public function isPixCodeValid ()
	{
		$this->expectException(InvalidPixCodeException::class);
		$reader = new Reader('0002215416BR.GOV.BCB.PIX0136aae2196f-5f93-46e4-89e6-73bf4138427b0215Descrição Teste52040000530398654041.005802BR5922Caique Monteiro Araujo6009SAO PAULO61080540900062160512NUR1pycKbhb063046BF7');
	}

	/** @test */
	public function isPixKeyRight ()
	{
		$reader = new Reader($this->nuBank);
		$this->assertTrue( $reader->getPixKey() === 'aae2196f-5f93-46e4-89e6-73bf4138427b');
	}

	/** @test */
	public function isPixKeyTypeRight ()
	{
		$reader = new Reader($this->nuBank);
		$this->assertTrue( Parser::getKeyType($reader->getPixKey()) === Parser::KEY_TYPE_RANDOM);
	}

	/** @test */
	public function isDescriptionRight ()
	{
		$reader = new Reader($this->nuBank);
		$this->assertTrue( $reader->getDescription() === 'Descrição Teste');
	}

	/** @test */
	public function isMerchantNameRight ()
	{
		$reader = new Reader($this->nuBank);
		$this->assertTrue( $reader->getMerchantName() === 'Caique Monteiro Araujo');
	}

	/** @test */
	public function isMerchantCityRight ()
	{
		$reader = new Reader($this->nuBank);
		$this->assertTrue( $reader->getMerchantCity() === 'SAO PAULO');
	}

	/** @test */
	public function isTransactionAmountRight ()
	{
		$reader = new Reader($this->nuBank);
		$this->assertTrue( $reader->getAmount() == 1);
	}

	/** @test */
	public function isAPayload ()
	{
		$reader = new Reader($this->nuBank);
		$this->assertInstanceOf(Payload::class, $reader->export());
	}
}