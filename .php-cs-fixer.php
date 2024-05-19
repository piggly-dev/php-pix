<?php
/*
 * This document has been generated with
 * https://mlocati.github.io/php-cs-fixer-configurator/#version:3.8.0|configurator
 * you can change this configuration by importing this file.
 */
$config = new PhpCsFixer\Config();
return $config
	->setIndent("\t")
	->setRules([
		'@PSR12' => true,
	])
	->setUsingCache(false)
	->setFinder(
		PhpCsFixer\Finder::create()
		->exclude('vendor')
		->in(__DIR__)
	)
;
