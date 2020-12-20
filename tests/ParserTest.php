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
		Parser::validateRandom($uuidKey);
		$this->assertTrue(true);
	}

	/** @test */
	public function isNotValidRandomKey ()
	{
		$this->expectException(Exception::class);
		$uuidKey = 'aae2196f-5f93-46e4-89e6-73bf41384b';
		Parser::validateRandom($uuidKey);
	}

	/** @test */
	public function isValidCpfKey ()
	{
		$cpf = '192.794.630-12';
		Parser::validateDocument($cpf);

		$cpf = '192.794630-12';
		Parser::validateDocument($cpf);

		$cpf = '19279463012';
		Parser::validateDocument($cpf);

		$this->assertTrue(true);
	}

	/** @test */
	public function isNotValidCpfKey ()
	{
		$this->expectException(Exception::class);
		$cpf = '123.456.789-10';
		Parser::validateDocument($cpf);
	}

	/** @test */
	public function isValidCnpjKey ()
	{
		$cnpj = '15.918.804/0001-41';
		Parser::validateDocument($cnpj);

		$cnpj = '15.918.804000141';
		Parser::validateDocument($cnpj);

		$cnpj = '15918804000141';
		Parser::validateDocument($cnpj);

		$this->assertTrue(true);
	}

	/** @test */
	public function isNotValidCnpjKey ()
	{
		$this->expectException(Exception::class);
		$cnpj = '12.345.678/9000-00';
		Parser::validateDocument($cnpj);
	}

	/** @test */
	public function isValidEmailKey ()
	{
		$email = 'caique@piggly.com.br';
		Parser::validateEmail($email);
		$this->assertTrue(true);
	}

	/** @test */
	public function isNotValidEmailKey ()
	{
		$this->expectException(Exception::class);
		$email = 'caique@piggly';
		Parser::validateEmail($email);
	}

	/** @test */
	public function isValidPhoneKey ()
	{
		$phone = '(34) 99940-1377';
		Parser::validatePhone($phone);

		$phone = '(34) 9940-1377';
		Parser::validatePhone($phone);

		$phone = '3499401377';
		Parser::validatePhone($phone);

		$phone = '3499401377';
		Parser::validatePhone($phone);

		$this->assertTrue(true);
	}

	/** @test */
	public function isNotValidPhoneKey ()
	{
		$this->expectException(Exception::class);
		$phone = '349994013777';
		Parser::validatePhone($phone);
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
		$this->assertTrue( 'caique piggly.com.br' === Parser::parseEmail($email) );
	}

	/** @test */
	public function isPhoneParsed () 
	{
		$phone = '(34) 9 9940-1377';
		$this->assertTrue( '+5534999401377' === Parser::parsePhone($phone) );
	}
}