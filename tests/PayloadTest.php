<?php
namespace Piggly\Tests\Pix;

use Exception;
use PHPUnit\Framework\TestCase;
use Piggly\Pix\Parser;
use Piggly\Pix\Payload;

class PayloadTest extends TestCase
{
	/** @var array Pix data. */
	protected $pixData;

	protected function setUp () 
	{
		$this->pixData = [
			'keyType'  => Parser::KEY_TYPE_RANDOM,
			'keyValue' => 'aae2196f-5f93-46e4-89e6-73bf4138427b',
			'merchantName' => 'STUDIO PIGGLY',
			'merchantCity' => 'Uberaba',
			'amount' => 109.90, // float
			'tid' => '034593-09',
			'description' => 'Pagamento 01',
			'reusable' => false
		];
	}

	/** @test */
	public function aValidPixCodeWithAllData ()
	{
		$pix = 
			(new Payload())
				->setPixKey($this->pixData['keyType'], $this->pixData['keyValue'])
				->setMerchantName($this->pixData['merchantName'])
				->setMerchantCity($this->pixData['merchantCity'])
				->setAmount($this->pixData['amount'])
				->setTid($this->pixData['tid'])
				->setDescription($this->pixData['description'])
				->setAsReusable($this->pixData['reusable']);

		$this->assertSame(
			'00020101021126740014br.gov.bcb.pix0136aae2196f-5f93-46e4-89e6-73bf4138427b0212Pagamento 015204000053039865406109.905802BR5913STUDIO PIGGLY6007Uberaba62130509034593-096304E828',
			$pix->getPixCode()
		);
	}

	/** @test */
	public function aValidPixQRCodeWithAllData ()
	{
		$pix = 
			(new Payload())
				->setPixKey($this->pixData['keyType'], $this->pixData['keyValue'])
				->setMerchantName($this->pixData['merchantName'])
				->setMerchantCity($this->pixData['merchantCity'])
				->setAmount($this->pixData['amount'])
				->setTid($this->pixData['tid'])
				->setDescription($this->pixData['description'])
				->setAsReusable($this->pixData['reusable']);

		$this->assertSame(
			'data:image/png;base64,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',
			$pix->getQRCode()
		);
	}

	/** @test */
	public function aValidPixCodeOnlyRequiredData ()
	{
		$pix = 
			(new Payload())
				->setPixKey($this->pixData['keyType'], $this->pixData['keyValue'])
				->setMerchantName($this->pixData['merchantName'])
				->setMerchantCity($this->pixData['merchantCity']);

		$this->assertSame(
			'00020101021126580014br.gov.bcb.pix0136aae2196f-5f93-46e4-89e6-73bf4138427b5204000053039865802BR5913STUDIO PIGGLY6007Uberaba63042546',
			$pix->getPixCode()
		);
	}

	/** @test */
	public function throwExceptionWhenPixKeyNotSet ()
	{
		$this->expectExceptionMessage('O id `01` não pode ser vazio.');

		(new Payload())
			->setMerchantName($this->pixData['merchantName'])
			->setMerchantCity($this->pixData['merchantCity'])
			->getPixCode();
	}

	/** @test */
	public function throwExceptionWhenInvalidPixKey ()
	{
		$this->expectExceptionMessage('A chave aleatória `0000` está inválida.');

		(new Payload())
			->setPixKey($this->pixData['keyType'], '0000')
			->setMerchantName($this->pixData['merchantName'])
			->setMerchantCity($this->pixData['merchantCity'])
			->getPixCode();
	}

	/** @test */
	public function throwExceptionWhenPixMerchantNotSet ()
	{
		$this->expectExceptionMessage('O id `59` não pode ser vazio.');

		(new Payload())
			->setPixKey($this->pixData['keyType'], $this->pixData['keyValue'])
			->setMerchantCity($this->pixData['merchantCity'])
			->getPixCode();
	}
}