<?php

use Piggly\Pix\Parser;
use Piggly\Pix\Reader;
use Piggly\Pix\Payload;

// Sample
$pixCode = '00020126770014BR.GOV.BCB.PIX0136aae2196f-5f93-46e4-89e6-73bf4138427b0215Descrição Teste52040000530398654041.005802BR5922Caique Monteiro Araujo6009SAO PAULO61080540900062160512NUR1pycKbhb063046BF7';
$reader  = new Reader($pixCode);

// User input
// -> Required
$keyValue = $reader->getPixKey();
$keyType  = Parser::getKeyType($keyValue);
$merchantName = $reader->getMerchantName();
$merchantCity = $reader->getMerchantCity();

// -> Optional
$amount = 109.90; // Payment amount as float
$tid = '034593-09'; // Transaction id
$description = 'Pagamento 01'; // Any type of description, characters allowed 
$reusable = false;

// Create the pix payload
$pix = 
	(new Payload())
		// ->applyValidCharacters()
		// ->applyUppercase()
		// ->applyEmailWhitespace()
		->setPixKey($keyType, $keyValue)
		->setMerchantName($merchantName)
		->setMerchantCity($merchantCity)
		->setAmount($amount)
		->setTid($tid)
		->setDescription($description)
		->setAsReusable($reusable);

// Prints: pix code
echo $pix->getPixCode();

// Prints: <img style="margin:12px auto" src="{{base64}}" alt="QR Code de Pagamento" />
echo '<img style="margin:12px auto" src="'.$pix->getQRCode().'" alt="QR Code de Pagamento" />';