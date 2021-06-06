<?php
namespace Piggly\Pix\Api\Payloads\Entities;

use Exception;
use Piggly\Pix\Exceptions\InvalidFieldException;
use Piggly\Pix\Parser;
use RuntimeException;

/**
 * Person entity to Cob payload.
 * 
 * @package \Piggly\Pix
 * @subpackage \Piggly\Pix\Api\Payloads\Entities
 * @version 2.0.0
 * @since 2.0.0
 * @category Entity
 * @author Caique Araujo <caique@piggly.com.br>
 * @author Piggly Lab <dev@piggly.com.br>
 * @license MIT
 * @copyright 2021 Piggly Lab <dev@piggly.com.br>
 */
class Person
{
	/**
	 * Person type as "recebedor" . 
	 * 
	 * @var string
	 * @since 2.0.0
	 */
	const TYPE_RECEIVER = 'recebedor';
	
	/**
	 * Person type as "devedor" . 
	 * 
	 * @var string
	 * @since 2.0.0
	 */
	const TYPE_DEBTOR = 'devedor';

	/**
	 * All person types available.
	 * 
	 * @var array<string>
	 * @since 2.0.0
	 */
	const TYPES = [
		self::TYPE_RECEIVER,
		self::TYPE_DEBTOR
	];

	/**
	 * Person document.
	 * 
	 * @since 2.0.0
	 * @var string
	 */
	protected $document;

	/**
	 * Person document type.
	 * 
	 * @since 2.0.0
	 * @var string
	 */
	protected $documentType;

	/**
	 * Person name.
	 * 
	 * @since 2.0.0
	 * @var string
	 */
	protected $name;

	/**
	 * Person fantasy name.
	 * 
	 * @since 2.0.0
	 * @var string
	 */
	protected $fantasyName;
	
	/**
	 * Person street address.
	 * 
	 * @since 2.0.0
	 * @var string
	 */
	protected $streetAddress;
	
	/**
	 * Person city.
	 * 
	 * @since 2.0.0
	 * @var string
	 */
	protected $city;
	
	/**
	 * Person state.
	 * 
	 * @since 2.0.0
	 * @var string
	 */
	protected $state;
	
	/**
	 * Person zip code.
	 * 
	 * @since 2.0.0
	 * @var string
	 */
	protected $zipCode;
	
	/**
	 * Person email.
	 * 
	 * @since 2.0.0
	 * @var string
	 */
	protected $email;
	
	/**
	 * Person type.
	 * 
	 * @since 2.0.0
	 * @var string
	 */
	protected $type;

	/**
	 * Create a person.
	 * 
	 * @param string $type 
	 * @since 2.0.0
	 * @return self
	 */
	public function __construct ( string $type = self::TYPE_DEBTOR )
	{ $this->setType($type); }

	/**
	 * Get document to current person.
	 * 
	 * @since 2.0.0
	 * @return string
	 */
	public function getDocument () : ?string
	{ return $this->document; }

	/**
	 * Get document type to current person.
	 * 
	 * @since 2.0.0
	 * @return string
	 */
	public function getDocumentType () : ?string
	{ return $this->documentType; }

	/**
	 * Set CPF/CNPJ to current person.
	 * 
	 * @param string $document
	 * @since 2.0.0
	 * @return self
	 * @throws InvalidFieldException When document is not a valid CPF/CNPJ.
	 */
	public function setDocument ( string $document )
	{
		$parsed = Parser::parseDocument($document);

		if ( Parser::validateCpf($parsed) )
		{ 
			$this->document = $document; 
			$this->documentType = 'cpf';
			return $this; 
		}
		else if ( Parser::validateCnpj($parsed) )
		{ 
			$this->document = $document; 
			$this->documentType = 'cnpj';
			return $this; 
		}

		throw new InvalidFieldException('Pessoa.Documento', $document, 'Nenhum CPF/CNPJ válido detectado.');
	}

	/**
	 * Get name to current person.
	 * 
	 * @since 2.0.0
	 * @return string
	 */
	public function getName () : ?string
	{ return $this->name; }

	/**
	 * Set name to current person.
	 * 
	 * @param string $name
	 * @since 2.0.0
	 * @return self
	 */
	public function setName ( string $name )
	{ $this->name = $name; return $this; }

	/**
	 * Get fantasy name to current person.
	 * 
	 * @since 2.0.0
	 * @return string
	 */
	public function getFantasyName () : ?string
	{ return $this->fantasyName; }

	/**
	 * Set fantasy name to current person.
	 * 
	 * @param string $fantasyName
	 * @since 2.0.0
	 * @return self
	 */
	public function setFantasyName ( string $fantasyName )
	{ $this->fantasyName = $fantasyName; return $this; }

	/**
	 * Get street address to current person.
	 * 
	 * @since 2.0.0
	 * @return string
	 */
	public function getStreetAddress () : ?string
	{ return $this->streetAddress; }

	/**
	 * Set street address to current person.
	 * 
	 * @param string $streetAddress
	 * @since 2.0.0
	 * @return self
	 */
	public function setStreetAddress ( string $streetAddress )
	{ $this->streetAddress = $streetAddress; return $this; }

	/**
	 * Get city to current person.
	 * 
	 * @since 2.0.0
	 * @return string
	 */
	public function getCity () : ?string
	{ return $this->city; }

	/**
	 * Set city to current person.
	 * 
	 * @param string $city
	 * @since 2.0.0
	 * @return self
	 */
	public function setCity ( string $city )
	{ $this->city = $city; return $this; }

	/**
	 * Get state to current person.
	 * 
	 * @since 2.0.0
	 * @return string
	 */
	public function getState () : ?string
	{ return $this->state; }

	/**
	 * Set state to current person.
	 * 
	 * @param string $state
	 * @since 2.0.0
	 * @return self
	 */
	public function setState ( string $state )
	{
		if ( strlen($state) > 2 )
		{ throw new InvalidFieldException('Pessoa.UF', $state, 'O estado precisa ser identificado com apenas duas letras.'); }

		$this->state = $state;
		return $this;
	}

	/**
	 * Get zipcode to current person.
	 * 
	 * @since 2.0.0
	 * @return string
	 */
	public function getZipCode () : ?string
	{ return $this->zipCode; }

	/**
	 * Set zipcode to current person.
	 * 
	 * @param string $state
	 * @since 2.0.0
	 * @return self
	 */
	public function setZipCode ( string $zipcode )
	{
		$_zipcode = \str_replace('-', '', $zipcode);

		if ( strlen($_zipcode) > 8 )
		{ throw new InvalidFieldException('Pessoa.CEP', $zipcode, 'O CEP está inválido.'); }

		$this->zipcode = \substr($_zipcode, 0, 5) . '-' . \substr($_zipcode, 5, 3);
		return $this;
	}

	/**
	 * Get email to current person.
	 * 
	 * @since 2.0.0
	 * @return string
	 */
	public function getEmail () : ?string
	{ return $this->email; }

	/**
	 * Set email to current person.
	 * 
	 * @param string $state
	 * @since 2.0.0
	 * @return self
	 */
	public function setEmail ( string $email )
	{ $this->email = $email; return $this; }

	/**
	 * Get type to current person.
	 * 
	 * @since 2.0.0
	 * @return string
	 */
	public function getType () : string
	{ return $this->type; }

	/**
	 * Set type to current person.
	 * 
	 * @param string $type
	 * @since 2.0.0
	 * @return self
	 */
	public function setType ( string $type )
	{
		try
		{ static::validateType($type); }
		catch ( Exception $e )
		{ throw new InvalidFieldException('Pessoa.Tipo', $type, $e->getMessage()); }

		$this->type = $type;
		return $this;
	}

	/**
	 * Export this object to an array.
	 * 
	 * @since 2.0.0
	 * @return array
	 */
	public function export () : array
	{
		$array = [];

		if ( isset( $this->document ) )
		{ $array[$this->documentType] = $this->document; }
		
		if ( isset( $this->name ) )
		{ $array['nome'] = $this->name; }
		
		if ( isset( $this->fantasyName ) )
		{ $array['nomeFantasia'] = $this->fantasyName; }
		
		if ( isset( $this->city ) )
		{ $array['cidade'] = $this->city; }
		
		if ( isset( $this->state ) )
		{ $array['uf'] = $this->state; }
		
		if ( isset( $this->streetAddress ) )
		{ $array['logradouro'] = $this->streetAddress; }
		
		if ( isset( $this->zipCode ) )
		{ $array['cep'] = $this->zipCode; }
		
		if ( isset( $this->email ) )
		{ $array['email'] = $this->email; }

		return $array;
	}

	/**
	 * Import data to array.
	 * 
	 * @param string $type Person type
	 * @param array $data
	 * @since 2.0.0
	 * @return self
	 */
	public function import ( array $data )
	{
		$importable = [
			'nome' => 'setName',
			'nomeFantasia' => 'setFantasyName',
			'cpf' => 'setDocument',
			'cnpj' => 'setDocument',
			'logradouro' => 'setStreetAddress',
			'cidade' => 'setCity',
			'cep' => 'setZipCode',
			'uf' => 'setState',
			'email' => 'setEmail'
		];

		foreach ( $importable as $field => $method )
		{
			if ( isset($data[$field]) )
			{ $this->{$method}($data[$field]); }
		}

		return $this;
	}

	/**
	 * Throw an exception if $type is a invalid type.
	 *
	 * @param string $type
	 * @since 2.0.0
	 * @return void
	 * @throws RuntimeException If is a invalid type.
	 */
	public static function validateType ( string $type )
	{
		if ( \in_array($type, static::TYPES, true) === false )
		{ throw new RuntimeException(\sprintf('O tipo de pessoa deve ser um dos seguintes: `%s`.', \implode('`, `', static::TYPES))); }
	}

	/**
	 * Is $expected equal to $actual.
	 *
	 * @param string $expected
	 * @param string $actual
	 * @since 2.0.0
	 * @return boolean
	 * @throws RuntimeException If some is a invalid type.
	 */
	public static function isType ( string $expected, string $actual ) : bool
	{ 
		if ( \in_array($expected, static::TYPES, true) === false )
		{ throw new RuntimeException(\sprintf('O tipo de pessoa esperado deve ser um dos seguintes: `%s`.', \implode('`, `', static::TYPES))); }
		
		if ( \in_array($actual, static::TYPES, true) === false )
		{ throw new RuntimeException(\sprintf('O tipo de pessoa atual deve ser um dos seguintes: `%s`.', \implode('`, `', static::TYPES))); }
		
		return $expected === $actual; 
	}
}