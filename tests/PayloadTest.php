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
			'merchantName' => 'Studio Piggly',
			'merchantCity' => 'Uberaba',
			'amount' => 109.90, // float
			'tid' => 'Boleto 00001-00',
			'description' => 'Descrição do Pagamento!',
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
			'00020101021126850014br.gov.bcb.pix0136aae2196f-5f93-46e4-89e6-73bf4138427b0223Descrição do Pagamento!5204000053039865406109.905802BR5913Studio Piggly6007Uberaba62190515Boleto 00001-00630457CF',
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
			'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAATEAAAExCAIAAACbBwI/AAAABnRSTlMA/wD/AP83WBt9AAAACXBIWXMAAA7EAAAOxAGVKw4bAAAJaElEQVR4nO3dy24ku7EF0NsX/v9fPh545IRBgIgHt9RrTVXKzCppg6hAMPjnn3/++T8gxv+/fgDgv8gkZJFJyCKTkEUmIYtMQhaZhCwyCVlkErLIJGSRScgik5BFJiGLTEIWmYQsMglZZBKyyCRkkUnI8q/KL//586frOc4+Q4M+9z2PFLp6yFc3Ojtf+fzMcy9em+R09ZCVSzWqfDjWScgik5BFJiGLTEKWUo3no/FLf+PX+qtCxVUNoLHmcb5UpbY0V4mpFMAa/4KNV65orB5ZJyGLTEIWmYQsMglZOms8H2t9LXPFlfOLKzeqaCxTVQoz51pL443Ol5r7YM/mimfWScgik5BFJiGLTEKWwRrPmqtSREhfS2OhovIGK/1DlXpYpV/q/BivNpQ1sk5CFpmELDIJWWQSsvyGGs/H3B6iq8LMXD/N2VofT+W+VyozhH4i6yRkkUnIIpOQRSYhy2CNZ+7bduOVK9N6Kh0za1NkKoWoq7df+aO8qh5dPcYa6yRkkUnIIpOQRSYhS2eNZ+27eKUSM7eXp7ElaG46UeNntVYPq7RPhVSPrlgnIYtMQhaZhCwyCVn+hPQurHk1nudjblDy+TFenST1C97vGuskZJFJyCKTkEUmIUupxlNpg2g80WmuR2SuB6jxbKw5ISd2NWps65n737BOQhaZhCwyCVlkErLszVx+VdS5eozGUTeNDxlS07p6yKvhPXOnwYeUba5YJyGLTEIWmYQsMglZOvt45g5pyqx5vGpzadTYMTP3jiq1tMY63NofxToJWWQSssgkZJFJyLI3jyezo+LV0OHMctFcNeV8qcaS3tzMZX088JeSScgik5BFJiFLZ43nR1Q15hpKPtbeYOOlXp0yVrnyR2N96ErjG7ROQhaZhCwyCVlkErKE1nh+xDFMjSWBkBnEazuq5qZXn+9bsdb0Y52ELDIJWWQSssgkZCnNXK7UHl5N+61Y+5Y/N0Xm/NPKX+Hqp3OPcfXPMLdHrMI6CVlkErLIJGSRSciyd67Wx1WF4PzFfc7amN3Gxp3GikijtbFAjW/w6s9trxb8WjIJWWQSssgkZBk8Vytkh9GrocOvtkFdmfsLrp0Ftvb21+aAWychi0xCFpmELDIJWUp9PJVGh1e7sc6/W6nizJVtrj66xsrE2u6kqyvPnbq11h92Zp2ELDIJWWQSssgkZNmbx3P19XptgG+l5jE3rmaucSekBNJ4qbn2mrXD0j+sk5BFJiGLTEIWmYQse+dqnV/8sXai01nINqgfceL3x6ttUHO/uzacyToJWWQSssgkZJFJyNJZ4/leeqzk8yMm/VSqOHP7j0JKemsVr7XqUePnbJ2ELDIJWWQSssgkZNnr45kbOnxlra3n902Cnjto/Xyjs8ozV17c+Lsf1knIIpOQRSYhi0xClsEaz1nj/qPMxo7zixs1bihr9KojqmKuanXFOglZZBKyyCRkkUnIUpq5/FH5ar52iPfVoVQVIU0hV2eBhUwJurpv43/Oq6LOh3USssgkZJFJyCKTkKWzxnNlrdbykVnVePVpnK1VNSpnkH2sneiujwf+FjIJWWQSssgkZCnt1co8SOtKyHiekN9tHPxT0dipE/J/dcU6CVlkErLIJGSRSchS6uNZ62up9Hm8mgR93hV1vtSrClDjjRorMY0ln8ZenLmhQdZJyCKTkEUmIYtMQpZSjefVrJfzY5wvVakBrO3WufITd1SFTFWuvHjun8E6CVlkErLIJGSRScjSuVfrI/N7fOOI3sZ9TyETdzJPkv8F55ddsU5CFpmELDIJWWQSsoTu1Zr7Pt3YbtI4CWatXDQ33Tik56nx01jb9vVhnYQsMglZZBKyyCRkKfXxzAk5xXptls+rOcKNHTNzb+HKWj1MjQf+FjIJWWQSssgkZBmcx1PReKlX1ZTGbVAhHVGNn2Tj1q0rmUWdD+skZJFJyCKTkEUmIUvnXq25r+aNj1G51JW5KTKNxYa5ylPjqVsVr07dqrBOQhaZhCwyCVlkErKUajwfc308r0YSf8yNb6lUy+bOA/9YO+B9baZO5tYt6yRkkUnIIpOQRSYhS+e5WmsHHs2N982cA3R+jMZenMZ6SaO5/5zKfe3Vgr+FTEIWmYQsMglZBs/Vuvrdtc6VV70aV/ddm+Uz9+K57qK5Y9j18QD/g0xCFpmELDIJWZ718bwqRYRMr5n72OdGP1891dqVG//r1j6rM+skZJFJyCKTkEUmIcve2emvthT9iGPJ18oYjVe+8qpecja32VAfD/weMglZZBKyyCRk6Tw7/WOtQ+h837mmn5BRRnP1sMqLr6w1DM2dQt84NMg6CVlkErLIJGSRScgyeK7Wj6gAVW7U+LV+7tip842uftpYAZorJjWWA+fue2adhCwyCVlkErLIJGTprPFUNI4kfnU4VGMF6FWR4/wYVz89WzvSvPLiV01O1knIIpOQRSYhi0xClsFztV5t/Gks+cwdDrX2GGu73q6s1dLm3u/cP7B1ErLIJGSRScgik5Blbx7PXFPIWsGgschxtnZ0/Nq4mspjzO1cO1sb/fxhnYQsMglZZBKyyCRk6TxXa24b1NWNzvd9New4s/bw6sCytSPNz4/xsXYi25l1ErLIJGSRScgik5Dl2dnpc9+2zxqLDWeZ9aHzY5y9OtDqlbV/lQ/rJGSRScgik5BFJiHL3szlV6cjhfgRu6Ia73t1qcbmqvONztY6dc6sk5BFJiGLTEIWmYQsg/N4Gp3P1Wq8ckVjn0flOPRXH875MRrHc59/99X0anu14NeSScgik5BFJiFLZx/Pq4k7lfaLtfOxM8fzXA13vrJ24PlaW8/c5qwP6yRkkUnIIpOQRSYhy+BerVf7gK40dtv8iOO9Xs3FnhvQPFem+ljrWrNOQhaZhCwyCVlkErLszeNpNLeV6dWRVVdCzpmqXPn84leVtrk/2RXrJGSRScgik5BFJiHLj6zxXGnc6XN15fONKnWaxtE+lRaZVyWQxjamykwde7XgbyGTkEUmIYtMQpbBGs9aDaBxt87akea/b1NY4/SauUFHr+pSV6yTkEUmIYtMQhaZhCydNZ7MQ8sbNdYP5ipPld9tHJNztnajyn1fsU5CFpmELDIJWWQSsvwJ+V4L/Id1ErLIJGSRScgik5BFJiGLTEIWmYQsMglZZBKyyCRkkUnIIpOQRSYhi0xCFpmELDIJWWQSssgkZJFJyPJvKIAMX9X+6uIAAAAASUVORK5CYII=',
			$pix->getQRCode(Payload::OUTPUT_PNG, Payload::ECC_L)
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
			'00020101021126580014br.gov.bcb.pix0136aae2196f-5f93-46e4-89e6-73bf4138427b5204000053039865802BR5913Studio Piggly6007Uberaba63048967',
			$pix->getPixCode()
		);
	}

	/** @test */
	public function emailToWhitespace ()
	{
		$pix = 
			(new Payload())
				->applyEmailWhitespace()
				->setPixKey(Parser::KEY_TYPE_EMAIL, 'caique@piggly.com.br')
				->setMerchantName($this->pixData['merchantName'])
				->setMerchantCity($this->pixData['merchantCity']);

		$this->assertSame(
			'00020101021126420014br.gov.bcb.pix0120caique piggly.com.br5204000053039865802BR5913Studio Piggly6007Uberaba6304BB89',
			$pix->getPixCode()
		);
	}

	/** @test */
	public function removeInvalidChars ()
	{
		$pix = 
			(new Payload())
			->applyValidCharacters()
			->setPixKey($this->pixData['keyType'], $this->pixData['keyValue'])
			->setMerchantName($this->pixData['merchantName'])
			->setMerchantCity($this->pixData['merchantCity'])
			->setAmount($this->pixData['amount'])
			->setTid($this->pixData['tid'])
			->setDescription($this->pixData['description'])
			->setAsReusable($this->pixData['reusable']);

		$this->assertSame(
			'00020101021126840014br.gov.bcb.pix0136aae2196f-5f93-46e4-89e6-73bf4138427b0222Descricao do Pagamento5204000053039865406109.905802BR5913Studio Piggly6007Uberaba62190515Boleto 00001-006304BE1C',
			$pix->getPixCode()
		);
	}

	/** @test */
	public function uppercaseAll ()
	{
		$pix = 
			(new Payload())
			->applyUppercase() 
			->setPixKey($this->pixData['keyType'], $this->pixData['keyValue'])
			->setMerchantName($this->pixData['merchantName'])
			->setMerchantCity($this->pixData['merchantCity'])
			->setAmount($this->pixData['amount'])
			->setTid($this->pixData['tid'])
			->setDescription($this->pixData['description'])
			->setAsReusable($this->pixData['reusable']);

		$this->assertSame(
			'00020101021126850014br.gov.bcb.pix0136aae2196f-5f93-46e4-89e6-73bf4138427b0223DESCRIÇÃO DO PAGAMENTO!5204000053039865406109.905802BR5913STUDIO PIGGLY6007UBERABA62190515BOLETO 00001-006304E1DD',
			$pix->getPixCode()
		);
	}

	/** @test */
	public function removeInvalidCharsAndUppercaseAll ()
	{
		$pix = 
			(new Payload())
			->applyUppercase() 
			->applyValidCharacters()
			->setPixKey($this->pixData['keyType'], $this->pixData['keyValue'])
			->setMerchantName($this->pixData['merchantName'])
			->setMerchantCity($this->pixData['merchantCity'])
			->setAmount($this->pixData['amount'])
			->setTid($this->pixData['tid'])
			->setDescription($this->pixData['description'])
			->setAsReusable($this->pixData['reusable']);

		$this->assertSame(
			'00020101021126840014br.gov.bcb.pix0136aae2196f-5f93-46e4-89e6-73bf4138427b0222DESCRICAO DO PAGAMENTO5204000053039865406109.905802BR5913STUDIO PIGGLY6007UBERABA62190515BOLETO 00001-006304C5E1',
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
		$this->expectExceptionMessage('O valor `Chave Aleatória` para 0000 está inválido.');

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