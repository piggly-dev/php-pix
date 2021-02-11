<?php
namespace Piggly\Tests\Pix;

use Exception;
use PHPUnit\Framework\TestCase;
use Piggly\Pix\Parser;

class ParserTest extends TestCase
{
	/** @test */
	public function isValidRandomKey ()
	{
		$uuidKey = 'aae2196f-5f93-46e4-89e6-73bf4138427b';
		$this->assertTrue(Parser::validateRandom($uuidKey));
	}

	/** @test */
	public function isNotValidRandomKey ()
	{
		$uuidKey = 'aae2196f-5f93-46e4-89e6-73bf41384b';
		$this->assertFalse(Parser::validateRandom($uuidKey));
	}

	/** @test */
	public function isValidCpfKey ()
	{
		$validate = true;

		$cpf = '192.794.630-12';
		$validate = $validate && Parser::validateDocument($cpf);

		$cpf = '192.794630-12';
		$validate = $validate && Parser::validateDocument($cpf);

		$cpf = '19279463012';
		$validate = $validate && Parser::validateDocument($cpf);

		$this->assertTrue($validate);
	}

	/** @test */
	public function isNotValidCpfKey ()
	{
		$cpf = '12345678901';
		$this->assertFalse(Parser::validateDocument($cpf));
	}

	/** @test */
	public function isValidCnpjKey ()
	{
		$validate = true;

		$cnpj = '15.918.804/0001-41';
		$validate = $validate && Parser::validateDocument($cnpj);

		$cnpj = '15.918.804000141';
		$validate = $validate && Parser::validateDocument($cnpj);

		$cnpj = '15918804000141';
		$validate = $validate && Parser::validateDocument($cnpj);

		$this->assertTrue($validate);
	}

	/** @test */
	public function isNotValidCnpjKey ()
	{
		$cnpj = '12.345.678/9000-00';
		$this->assertFalse(Parser::validateDocument($cnpj));
	}

	/** @test */
	public function isValidEmailKey ()
	{
		$email = 'caique@piggly.com.br';
		$this->assertTrue(Parser::validateEmail($email));
	}

	/** @test */
	public function isNotValidEmailKey ()
	{
		$email = 'caique@piggly';
		$this->assertFalse(Parser::validateEmail($email));
	}

	/** @test */
	public function isValidPhoneKey ()
	{
		$validate = true;

		$phone = '+5534999401377';
		$validate = $validate && Parser::validatePhone($phone);

		$phone = '+5534 99940-1377';
		$validate = $validate && Parser::validatePhone($phone);

		$phone = '+55 (34) 99940-1377';
		$validate = $validate && Parser::validatePhone($phone);

		$phone = '(34) 99940-1377';
		$validate = $validate && Parser::validatePhone($phone);

		$phone = '(34) 9940-1377';
		$validate = $validate && Parser::validatePhone($phone);

		$phone = '3499401377';
		$validate = $validate && Parser::validatePhone($phone);

		$phone = '3499401377';
		$validate = $validate && Parser::validatePhone($phone);

		$this->assertTrue($validate);
	}

	/** @test */
	public function isNotValidPhoneKey ()
	{;
		$phone = '349994013777';
		$this->assertFalse(Parser::validatePhone($phone));
	}

	/** @test */
	public function isCpfParsed () 
	{
		$cpf = '192.794.630-12';
		$this->assertTrue( '19279463012' === Parser::parseDocument($cpf) );
	}

	/** @test */
	public function isCnpjParsed () 
	{
		$cnpj = '15.918.804/0001-41';
		$this->assertTrue( '15918804000141' === Parser::parseDocument($cnpj) );
	}

	/** @test */
	public function isEmailParsed () 
	{
		$email = 'caique@piggly.com.br';
		$this->assertTrue( 'caique@piggly.com.br' === Parser::parseEmail($email) );
	}

	/** @test */
	public function isEmailWhitespaceParsed () 
	{
		$email = 'caique@piggly.com.br';
		$this->assertTrue( 'caique piggly.com.br' === Parser::parseEmail($email, true) );
	}

	/** @test */
	public function isPhoneParsed () 
	{
		$phone = '(34) 9 9940-1377';
		$this->assertTrue( '+5534999401377' === Parser::parsePhone($phone) );
	}

	/** @test */
	public function isKeyTypeCPF () 
	{
		$key = '19279463012';
		$this->assertTrue( Parser::KEY_TYPE_DOCUMENT === Parser::getKeyType($key) );
	}

	/** @test */
	public function isKeyTypeCNPJ () 
	{
		$key = '15918804000141';
		$this->assertTrue( Parser::KEY_TYPE_DOCUMENT === Parser::getKeyType($key) );
	}

	/** @test */
	public function isKeyTypeEmail () 
	{
		$key = 'caique@piggly.com.br';
		$this->assertTrue( Parser::KEY_TYPE_EMAIL === Parser::getKeyType($key) );
	}

	/** @test */
	public function isKeyTypeEmailWhitespace () 
	{
		$key = 'caique piggly.com.br';
		$this->assertTrue( Parser::KEY_TYPE_EMAIL === Parser::getKeyType($key) );
	}

	/** @test */
	public function isKeyTypeRandom () 
	{
		$key = 'c0827588-c337-48e2-b9c0-b1a78f042390';
		$this->assertTrue( Parser::KEY_TYPE_RANDOM === Parser::getKeyType($key) );
	}

	/** @test */
	public function isKeyTypePhone () 
	{
		$key = '+5534999401377';
		$this->assertTrue( Parser::KEY_TYPE_PHONE === Parser::getKeyType($key) );
	}

	/** @test */
	public function isKeyTypeUnknow () 
	{
		$this->expectException('Exception');

		$key = 'unknowkey';
		Parser::getKeyType($key);
	}
}