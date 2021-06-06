# Crie/Leia códigos Pix sem complicações com PHP

[![Latest Version on Packagist](https://img.shields.io/packagist/v/piggly/php-pix.svg?style=flat-square)](https://packagist.org/packages/piggly/php-pix) [![Packagist Downloads](https://img.shields.io/packagist/dt/piggly/php-pix?style=flat-square)](https://packagist.org/packages/piggly/php-pix) [![Packagist Stars](https://img.shields.io/packagist/stars/piggly/php-pix?style=flat-square)](https://packagist.org/packages/piggly/php-pix) [![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE) ![PHP](https://img.shields.io/packagist/php-v/piggly/php-pix?style=flat-square)

![Versão Atual](https://img.shields.io/badge/version-2.x.x-green?style=flat-square) 

O **Pix** é o mais novo método de pagamento eletrônico criado pelo **Banco Central do Brasil**. Você encontra todos os detalhes na [página oficial](https://www.bcb.gov.br/estabilidadefinanceira/pix) do Pix. Saiba mais como ele funciona e como nossa biblioteca trabalha [clicando aqui](https://github.com/piggly-dev/php-pix/wiki/04.-Pix).

Essa biblioteca foi criada para ser utilizada principalmente com o plugin de **Woocommerce** [Pix por Piggly](https://wordpress.org/plugins/pix-por-piggly/). Mas, pode ser utilizada em qualquer sistema onde seja necessário a criação de payloads, códigos e QRCodes Pix. Afinal, ela foi criada para otimizar o processo de trabalho com os códigos Pix. Com ela, você poderá:

* Gerar códigos Pix estáticos e dinâmicos;
* Criar QR Codes para os códigos pix gerados;
* Ler códigos Pix importando os dados Pix;
* Criar payloads de Cobranças e Devoluções para interagir com as APIs Pix;
* Usar uma base de comunicação com a Api para organizar seus códigos;
* Gerar códigos compatíveis com a última versão do Pix.

Leia sobre a solução de problemas [clicando aqui](https://github.com/piggly-dev/php-pix/wiki/09.-Solu%C3%A7%C3%A3o-de-Problemas) caso suas chaves Pix sejam inválidas. E, não deixe de conferir nossa [documentação completa](https://github.com/piggly-dev/php-pix/wiki).

> Se você apreciar a função desta biblioteca e quiser apoiar este trabalho, sinta-se livre para fazer qualquer doação para a chave aleatória Pix `aae2196f-5f93-46e4-89e6-73bf4138427b` ❤.

## Atualização para a versão 2.0.0

Muitas coisas mudaram, além do suporte ao **PHP 8** os campos EMV foram otimizados e expandidos. Além disso, as requisições para APIs foram removidas e os payloads para as APIs foram reformulados. Recomendamos ler a documentação de mudanças [clicando aqui](https://github.com/piggly-dev/php-pix/wiki/11.-Migrar-para-a-vers%C3%A3o-2.x.x) e verificar as diferenças.

A biblioteca `chillerlan/php-qrcode` não pode ser atualizada em `composer.json`, tornando necessário utilizar a `flag` `--ignore-platform-reqs` no **PHP 8**. Veja abaixo:

```bash
# composer install
composer --ignore-platform-req=php install

# composer update
composer --ignore-platform-req=php update

# e todos os demais comandos do composer
```

> Muitos utilizadores dessa biblioteca ainda utilizando a versão `7.2` do PHP e não é o momento ideal para perder esse suporte. A partir da versão `2.1.x` pretendemores remover esse suporte.

Para realizar as requisições da api, recomendamos a biblioteca [piggly/php-api-client](https://github.com/piggly-dev/php-api-client), ela foi desenvolvida para ser flexível e muito mais robusta que a solução anterior utilizada na versão 1.x.x.

Veja as principais mudanças da versão 2.x.x:

* O padrão EMV MPM foi atualizado e remodelado para suportar novos campos que podem ser utilizados nos Pix;
* A validação e verificação das chaves Pix foi aprimorada;
* A leitura de um código Pix resulta em um objeto de EMVs mais flexível;
* Os modificadores do payload foram removidos, sendo que agora, todos os dados preenchidos são automaticamente tratados e cortados respeitando completamente o padrão EMV;
* A classe `BaseAPI` foi removida, adotando como sugestão a biblioteca [piggly/php-api-client](https://github.com/piggly-dev/php-api-client) que traz muito mais flexibilidade e segurança;
* Os payloads para APIs foram remodelados para serem mais eficientes e flexíveis.

## Instalação

### Composer

* No terminal, dentro da sua pasta do projeto, digite `composer require piggly/php-pix`;
* Não esqueça de iniciar o composer incluindo o arquivo `require_once('vendor/autoload.php);` na base do código.

### Instalação Manual

* Baixe ou clone esse repositório com `git clone https://github.com/piggly-dev/php-pix.git`;
* Depois, vá para a pasta `/path/to/php-pix` e dê o comando `composer install` para instalar todas as dependências.
* Adicione o `autoload` do composer na base do seu projeto `require_once('/path/to/php-pix/vendor/autoload.php);`.

## Dependências

Essa bibliotecca possuí as seguintes dependências:

* [Extensão GD do PHP](https://www.php.net/manual/pt_BR/book.image.php) para gerar QR Codes;
* PHP +7.2 ou PHP +8.0.

## Como utilizar?

> Recomendamos que leia a documentação completa [clicando aqui](https://github.com/piggly-dev/php-pix/wiki)

Em [samples/payload.php](samples/payload.php) e [samples/reader.php](samples/reader.php) você encontra um exemplo de implementação. Esta biblioteca é bem simples de utilizar e tudo que você precisa fazer é solicitar ao usuário ou ter os seguintes dados para gerar o Pix:

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

Depois crie o objeto `Payload` e utilize os métodos `getPixCode()` ou `getQRCode()`, conforme as suas necessidades. Você também pode criar o objeto `Reader` para extrair os dados de uma chave pix válida.

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

Como o código utiliza o padrão do Pix é possível que funcione em todos os bancos. Alguns bancos ainda estão resilientes em utilizar os padrões determinados. Então, caso encontre problemas ou dificuldades não hesite em abrir uma [Issue](https://github.com/piggly-dev/php-pix/issues) ou enviar um e-mail para [dev@piggly.com.br](mailto:dev@piggly.com.br).

Ao enviar um e-mail ou abrir uma issue, certifique-se de informar:

* Versão da Biblioteca: 2.0.0;
* Banco Emitente: NuBank;
* Banco Pagador: Inter;
* Tipo de Erro: O **QR Code** é inválido;
* Chave Pix Gerada: `00020101021126740014br.gov.bcb.pix0136aae2196f-5f93-46e4-89e6-73bf4138427b0212Pagamento 0152040000053039865406109.905802BR5913STUDIO PIGGLY6007Uberaba62130509034593-09630444C9`;

## Changelog

Veja o arquivo [CHANGELOG](CHANGELOG.md) para informações sobre todas as mudanças no código.

## Testes de Código

Essa biblioteca utiliza o [PHPUnit](https://phpunit.de/). Realizamos testes com todas as principais classes dessa aplicação.

```
vendor/bin/phpunit
```

## Contribuições

Veja o arquivo [CONTRIBUTING](CONTRIBUTING.md) para informações antes de enviar sua contribuição.

## Segurança

Se você descobrir qualquer issue relacionada a segurança, por favor, envie um e-mail para [dev@piggly.com.br](mailto:dev@piggly.com.br) ao invés de utilizar o rastreador de issues do Github.

## Créditos

- [Caique Araujo](https://github.com/caiquearaujo)
- [Todos os colaboradores](../../contributors)

## Apoie o projeto

**Piggly Studio** é uma agência localizada no Rio de Janeiro, Brasil. Se você apreciar a função desta biblioteca e quiser apoiar este trabalho, sinta-se livre para fazer qualquer doação para a chave aleatória Pix `aae2196f-5f93-46e4-89e6-73bf4138427b` ❤.

## License

MIT License (MIT). Veja [LICENSE](LICENSE) para mais informações.