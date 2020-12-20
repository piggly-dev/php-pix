<?php
namespace Piggly\Pix;

use Exception;

/**
 * The Pix Parser class.
 * 
 * This is used to parse and format data following patterns and 
 * standards of a pix.
 *
 * @since      1.0.0
 * @package    Piggly\Pix
 * @subpackage Piggly\Pix
 * @author     Caique <caique@piggly.com.br>
 */
class Parser
{
	/** @var string KEY_TYPE_RANDOM Random key. */
	const KEY_TYPE_RANDOM = 'random';
	/** @var string KEY_TYPE_DOCUMENT Document key. */
	const KEY_TYPE_DOCUMENT = 'document';
	/** @var string KEY_TYPE_EMAIL Email key. */
	const KEY_TYPE_EMAIL = 'email';
	/** @var string KEY_TYPE_PHONE Phone key. */
	const KEY_TYPE_PHONE = 'phone';

	/**
	 * Return the alias for key value.
	 * 
	 * @since 1.0.0
	 * @param string $key
	 * @return string
	 */
	public static function getAlias ( string $key ) : string 
	{
		switch ( $key )
		{
			case self::KEY_TYPE_RANDOM:
				return 'Chave Aleatória';
			case self::KEY_TYPE_DOCUMENT:
				return 'CPF/CNPJ';
			case self::KEY_TYPE_EMAIL:
				return 'E-mail';
			case self::KEY_TYPE_PHONE:
				return 'Telefone';
		}

		return 'Chave Desconhecida';
	}

	/**
	 * Validate a $value based in the respective pix $key.
	 * 
	 * @since 1.0.0
	 * @param string $key Pix key.
	 * @param string $value Pix value.
	 * @throws Exception
	 */
	public static function validate ( string $key, string $value )
	{
		switch ( $key )
		{
			case self::KEY_TYPE_RANDOM:
				return self::validateRandom($value);
			case self::KEY_TYPE_DOCUMENT:
				return self::validateDocument($value);
			case self::KEY_TYPE_EMAIL:
				return self::validateEmail($value);
			case self::KEY_TYPE_PHONE:
				return self::validatePhone($value);
		}

		throw new Exception(sprintf('A chave `%s` é desconhecida.', $key));
	}

	/**
	 * Validates the random key value.
	 * 
	 * @since 1.0.0
	 * @param string $random Pix key value.
	 * @throws Exception
	 */
	public static function validateRandom ( string $random )
	{
		if ( !preg_match('/[0-9a-fA-F]{8}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{12}/', $random) )
		{ throw new Exception(sprintf('A chave aleatória `%s` está inválida.', $random)); }
	}

	/**
	 * Validates the document key value.
	 * 
	 * @since 1.0.0
	 * @param string $document Pix key value.
	 * @throws Exception
	 */
	public static function validateDocument ( string $document )
	{
		$parsed = self::parseDocument($document);
		
		if ( strlen($parsed) === 11 )
		{ self::validateCpf($parsed); return; }
		else if ( strlen($parsed) === 14 )
		{ self::validateCnpj($parsed); return; }

		throw new Exception(sprintf('A chave de CPF/CNPJ `%s` está inválida.', $document));
	}

	/**
	 * Validates a CPF number.
	 * 
	 * @since 1.0.0
	 * @param string $document String with only numbers.
	 * @return void
	 * @throws Exception When invalid.
	 */
	protected static function validateCpf ( string $document )
	{
		// Prevent equal numbers
		if (preg_match('/(\d)\1{10}/', $document)) 
		{ throw new Exception(sprintf('A chave de CPF/CNPJ `%s` está inválida.', $document)); }
	
		// CPF Checksum
		for ($t = 9; $t < 11; $t++) 
		{
			for ( $d = 0, $c = 0; $c < $t; $c++ ) 
			{ $d += $document[$c] * (($t + 1) - $c); }

			$d = ((10 * $d) % 11) % 10;

			if ( $document[$c] != $d ) 
			{ throw new Exception(sprintf('A chave de CPF/CNPJ `%s` está inválida.', $document)); }
	  	}
	}

	/**
	 * Validates a CNPJ number.
	 * 
	 * @since 1.0.0
	 * @param string $document String with only numbers.
	 * @return void
	 * @throws Exception When invalid.
	 */
	protected static function validateCnpj ( string $document )
	{
		// Prevent equal numbers
		if (preg_match('/(\d)\1{13}/', $document)) 
		{ throw new Exception(sprintf('A chave de CPF/CNPJ `%s` está inválida.', $document)); }
	
		// CNPJ first Checksum
		for ( $i = 0, $j = 5, $sum = 0; $i < 12; $i++ )
		{
			$sum += $document[$i] * $j;
			$j = ($j == 2) ? 9 : $j - 1;
		}

		$result = $sum % 11;

		if ( $document[12] !== (string)( $result < 2 ? 0 : 11 - $result ) )
		{ throw new Exception(sprintf('A chave de CPF/CNPJ `%s` está inválida.', $document)); }
	
		// CNPJ second Checksum
		for ($i = 0, $j = 6, $sum = 0; $i < 13; $i++)
		{
			$sum += $document[$i] * $j;
			$j = ($j == 2) ? 9 : $j - 1;
		}

		$result = $sum % 11;

		if ( $document[13] !== (string)( $result < 2 ? 0 : 11 - $result ) )
		{ throw new Exception(sprintf('A chave de CPF/CNPJ `%s` está inválida.', $document)); }
	}

	/**
	 * Validates the email key value.
	 * 
	 * @since 1.0.0
	 * @param string $email Pix key value.
	 * @throws Exception
	 */
	public static function validateEmail ( string $email )
	{
		if ( !preg_match("/[^\@]+\@[^\.]+\..+/", $email) )
		{ throw new Exception(sprintf('A chave de e-mail `%s` está inválida.', $email)); }
	}

	/**
	 * Validates the phone key value.
	 * 
	 * @since 1.0.0
	 * @param string $phone Pix key value.
	 * @throws Exception
	 */
	public static function validatePhone ( string $phone )
	{
		$parsed = self::parsePhone($phone);

		if ( !preg_match('/^(\+55)(\d{10,11})$/', $parsed) )
		{ throw new Exception(sprintf('A chave de telefone `%s` está inválida.', $phone)); }
	}

	/**
	 * Parse a $value based in the respective pix $key.
	 * 
	 * @since 1.0.0
	 * @param string $key Pix key.
	 * @param string $value Pix value.
	 * @return string
	 * @throws Exception
	 */
	public static function parse ( string $key, string $value ) : string
	{
		switch ( $key )
		{
			case self::KEY_TYPE_RANDOM:
				return $value;
			case self::KEY_TYPE_DOCUMENT:
				return self::parseDocument($value);
			case self::KEY_TYPE_EMAIL:
				return self::parseEmail($value);
			case self::KEY_TYPE_PHONE:
				return self::parsePhone($value);
		}

		throw new Exception(sprintf('A chave `%s` é desconhecida.', $key));
	}

	/**
	 * Parse any document string to a correct document format.
	 * 
	 * @since 1.0.0
	 * @param string $document
	 * @return string
	 */
	public static function parseDocument ( string $document ) : string
	{ return preg_replace('/[^\d]+/', '', $document); }

	/**
	 * Parse any email string to a correct email format.
	 * 
	 * @since 1.0.0
	 * @param string $email
	 * @return string
	 */
	public static function parseEmail ( string $email ) : string
	{ return str_replace('@', ' ', $email); }

	/**
	 * Parse any phone string to a correct phone format.
	 * 
	 * @since 1.0.0
	 * @param string $phone
	 * @return string
	 */
	public static function parsePhone ( string $phone ) : string
	{
		$phone = str_replace('+55', '', $phone);
		$phone = preg_replace('/[^\d]+/', '', $phone);
		return '+55'.$phone;
	}
}