<?php
namespace Piggly\Pix\Enums;

/**
 * All QRCode basic configuration.
 *
 * @package \Piggly\Pix
 * @subpackage \Piggly\Pix\Enums
 * @version 1.0.0
 * @since 1.0.0
 * @category Enum
 * @author Caique Araujo <caique@piggly.com.br>
 * @author Piggly Lab <dev@piggly.com.br>
 * @license MIT
 * @copyright 2021 Piggly Lab <dev@piggly.com.br>
 */
class QrCode
{
	/** @var string OUTPUT_SVG Return QR Code in SVG. */
	const OUTPUT_SVG = 'svg';
	/** @var string OUTPUT_PNG Return QR Code in PNG. */
	const OUTPUT_PNG = 'png';

	/** @var int Error Correction Capability Level L (7%) */
	const ECC_L = 0b01;
	/** @var int Error Correction Capability Level M (15%) */
	const ECC_M = 0b00;
	/** @var int Error Correction Capability Level Q (25%) */
	const ECC_Q = 0b11;
	/** @var int Error Correction Capability Level H (30%) */
	const ECC_H = 0b10;
}