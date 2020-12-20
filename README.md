# Crie seus códigos Pix com PHP

[![Latest Version on Packagist](https://img.shields.io/packagist/v/piggly/php-pix.svg?style=flat-square)](https://packagist.org/packages/piggly/php-pix) [![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE) 

O **Pix** é o mais novo método de pagamento eletrônico criado pelo **Banco Central do Brasil**. Você encontra todos os detalhes na [página oficial](https://www.bcb.gov.br/estabilidadefinanceira/pix) do Pix.

Essa biblioteca foi criada para ser utilizada principalmente com o plugin de **Woocommerce** [Pix por Piggly](https://wordpress.org/plugins/pix-por-piggly/). Mas, pode ser utilizada em qualquer sistema onde seja necessário a criação de payloads, códigos e QRCodes Pix.

Se você apreciar a função desta biblioteca e quiser apoiar este trabalho, sinta-se livre para fazer qualquer doação para a chave aleatória Pix `aae2196f-5f93-46e4-89e6-73bf4138427b` ❤.

## Instalação

Essa biblioteca pode ser instalada via **Composer** com `composer require piggly/php-pix`;

## Como o Pix funciona?

De acordo com o [Manual do BR Code](https://www.bcb.gov.br/content/estabilidadefinanceira/SiteAssets/Manual%20do%20BR%20Code.pdf) e todas as [Especificações Técnicas](https://www.bcb.gov.br/content/estabilidadefinanceira/forumpireunioes/Anexo%20I%20-%20QRCodes%20-%20Especifica%C3%A7%C3%A3o%20-%20vers%C3%A3o%201-1.pdf), o Pix implmentado pelo Banco Central do Brasil adota todas as proposta do padrão EMV®1. As principais funções que essa biblioteca executa, são:

1. Geração do **QR Code** o método `getQRCode()`, utilizando a biblioteca `chillerlan/php-code` ([veja aqui](https://github.com/chillerlan/php-qrcode));
2. O código Pix em formato de texto com o método `getPixCode()` para o formato de pagamento **Pix Copia & Cola**.

### Padrão EMV®1

Por padrão, o **BR Code** utiliza apenas caracteres alfanuméricos, identificado pelo seguinte regex `[A-Za-z0-9\$\%\*\+\-\.\/\ ]`. A estrutura do código EMV®1 é composta por três conjuntos de caracteres:

1. ID `[\d]{2}`;
2. Tamanho em caracteres do conteúdo `[\d]{2}`;
3. Conteúdo `[A-Za-z0-9\$\%\*\+\-\.\/\ ]`.

#### Exemplos

O código `000200`, representa:

* `00` ID para `Payload Format Indicator`;
* `02` Tamanho em caracteres do conteúdo;
* `01` Conteúdo do campo, neste caso identificando a `Versão do Payload`;

## Como essa biblioteca ajuda?

Cada campo **EMV®1** contém suas especificações, entre elas o tamanho do campo permitido, caracteres permitidos e afins. Além das chaves terem os tipos pré-definidos como: Chave Aleatória, CPF/CNPJ, E-mail e Telefone. Todas as validações são feitas por essa biblioteca:

* Conteúdo do campo como `[A-Za-z0-9\$\%\*\+\-\.\/\ ]`;
* Chave aleatória no formato `uuid`;
* Chave de CPF/CNPJ com um valor válido e apenas `numérico`;
* Chave de E-mail com um valor válido e substituindo `@` por ` ` espaço;
* Chave de Telefone com um valor válido e apenas `numérico`.

### Classe `Parser`

A classe `Parser` apresenta todos os métodos como `static` e segue o seguinte formato:

* Métodos com `validate` validam se o valor da chave é compatível com o formato esperado por seu tipo, retornando `Exception` quando inválido;
* Métodos com `parse` apenas tratam os campos retirando todos os caracteres inválidos para serem utilizando, também seguindo o tipo da chave;
* O método `getAlias()` retorna a `label` conforme o tipo de chave. Por exemplo, ao receber `Parser::KEY_TYPE_RANDOM` retorna `Chave Aleatória`.

### Classe `Payload`

A classe `Payload` é responsável por montar o payload do Pix e segute o seguinte formato:

* Métodos com `set` determinam valores para os atributos do Pix;
* O método `getPixCode()` retorna o código Pix em formato de texto;
* O método `getQRCode` returna uma `string` no formato `data:image/png;base64`.

### Os atributos do Pix

Os atributos **obrigatórios** do Pix são:

* `Pix Key` alterado pelo método `setPixKey()` com o tipo e o valor da chave Pix;
* `Merchant Name` alterado pelo método `setMerchantName()` com o nome do titular da conta como conta no banco. Tamanho máximo de `25 caracteres`.
* `Merchant City` alterado pelo método `setMerchantCity()` com a cidade do titular da conta como conta no banco. Tamanho máximo de `15 caracteres`.

Os atributos **opcionais** do Pix são:

* `Point of Initiation Method` alterado pelo método `setAsReusable()` sendo `true` como código Pix reutilizável e `false` como código Pix utilizável apenas uma vez.
* `Merchant Account Information . Label` alterado pelo método `setDescription()` com a descrição do pagamento. Tamanho máximo de `36 caracteres`.
* `Transaction Amount` alterado pelo método `setAmount()` com o valor da transação em `float`. Tamanho máximo de `13 caracteres`.
* `Additional Data Field . Reference Label` alterado pelo método `setTid()` com o ID da transação. Tamanho máximo de `25 caracteres`.

## Como utilizar?

Em [sample/pix.php](sample/pix.php) você encontra um exemplo de implementação. Esta biblioteca é bem simples de utilizar e tudo que você precisa fazer é solicitar ao usuário ou ter os seguintes dados para gerar o Pix:

Obrigatórios:

* `$keyType  = Parser::KEY_TYPE_RANDOM;`
* `$keyValue = 'aae2196f-5f93-46e4-89e6-73bf4138427b';`
* `$merchantName = 'STUDIO PIGGLY';`
* `$merchantCity = 'Uberaba';`

Opcionais:

* `$amount = 109.90;`
* `$tid = '034593-09';`
* `$description = 'Pagamento 01';`
* `$reusable = false;`

Depois crie o objeto `Parser` e utilize os métodos `getPixCode()` ou `getQRCode()`, conforme as suas necessidades.

## Testes realizados

O código Pix gerado por essa biblioteca, incluindo a função **QR Code** e **Pix Copia & Cola**, foi testado nos seguintes aplicativos de banco:

* Banco do Brasil;
* Banco Inter;
* BMG;
* Bradesco;
* C6;
* Itaú;
* Mercado Pago;
* Nubank;
* PagPank;
* Santander.

Como o código utiliza o padrão do Pix é possível que funcione em todos os bancos. Mas, caso encontre problemas ou dificuldades hesite em abrir uma [Issue](https://github.com/piggly-dev/php-pix/issues) ou enviar um e-mail para [dev@piggly.com.br](mailto:dev@piggly.com.br).

## Changelog

Veja o arquivo [CHANGELOG](CHANGELOG.md) para informações sobre todas as mudanças no código.

## Testes de Código

Essa biblioteca utiliza o [PHPUnit](https://phpunit.de/).

```
vendor/bin/phpunit
```

## Contribuições

Veja o arquivo [CONTRIBUTING](CONTRIBUTING.md) para informações antes de enviar sua contribuição.

## Segurança

Se você descobrir qualquer issue relacionada a segurança, por favor, envie um e-mail para [dev@piggly.com.br](mailto:dev@piggly.com.br) ao invés de utilizar o rastreador de issues do Github.

## Créditos

- [Caique Araujo](https://github.com/caiquearaujo)
- [All Contributors](../../contributors)

## Apoie o projeto

**Piggly Studio** é uma agência localizada no Rio de Janeiro, Brasil. Se você apreciar a função desta biblioteca e quiser apoiar este trabalho, sinta-se livre para fazer qualquer doação para a chave aleatória Pix `aae2196f-5f93-46e4-89e6-73bf4138427b` ❤.

## License

MIT License (MIT). Veja [LICENSE](LICENSE) para mais informações.