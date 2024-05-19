<?php
use Piggly\Pix\DynamicPayload;
use Piggly\Pix\Exceptions\EmvIdIsRequiredException;
use Piggly\Pix\Exceptions\InvalidEmvFieldException;
use Piggly\Pix\Exceptions\InvalidPixKeyException;
use Piggly\Pix\Exceptions\InvalidPixKeyTypeException;
use Piggly\Pix\StaticPayload;

try
{
	// Pix estático
	// Obtém os dados pix do usuário
	// -> Dados obrigatórios
	$keyType  = htmlspecialchars( filter_input( INPUT_POST, 'keyType' ) );
	$keyValue = htmlspecialchars( filter_input( INPUT_POST, 'keyValue' ) );
	$merchantName = htmlspecialchars( filter_input( INPUT_POST, 'merchantName' ) );
	$merchantCity = htmlspecialchars( filter_input( INPUT_POST, 'merchantCity' ) );

	// -> Dados opcionais
	$amount = htmlspecialchars( filter_input( INPUT_POST, 'amount' ) );
	$tid = htmlspecialchars( filter_input( INPUT_POST, 'tid' ) );
	$description = htmlspecialchars( filter_input( INPUT_POST, 'description' ) );

	$payload =
		(new StaticPayload())
			->setAmount($amount)
			->setTid($tid)
			->setDescription($description)
			->setPixKey($keyType, $keyValue)
			->setMerchantName($merchantName)
			->setMerchantCity($merchantCity);

	// Pix dinâmico
	// Obtém os dados pix do usuário
	// -> Dados obrigatórios
	$merchantName = htmlspecialchars( filter_input( INPUT_POST, 'merchantName' ) );
	$merchantCity = htmlspecialchars( filter_input( INPUT_POST, 'merchantCity' ) );

	// Obtém os dados do SPI para o Pix
	$payload =
	(new DynamicPayload())
		->setUrl($spiUrl) // URL do Pix no SPI
		->setMerchantName($merchantName)
		->setMerchantCity($merchantCity);

	// Continue o código

	// Código pix
	echo $pix->getPixCode();
	// QR Code
	echo '<img style="margin:12px auto" src="'.$pix->getQRCode().'" alt="QR Code de Pagamento" />';
}
catch ( InvalidPixKeyException $e )
{ /** Retorna que a chave pix está inválida. */ }
catch ( InvalidPixKeyTypeException $e )
{ /** Retorna que a chave pix está inválida. */ }
catch ( InvalidEmvFieldException $e )
{ /** Retorna que algum campo está inválido. */ }
catch ( EmvIdIsRequiredException $e )
{ /** Retorna que um campo obrigatório não foi preenchido. */ }