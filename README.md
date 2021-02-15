# Crie seus códigos Pix com PHP

[![Latest Version on Packagist](https://img.shields.io/packagist/v/piggly/php-pix.svg?style=flat-square)](https://packagist.org/packages/piggly/php-pix) [![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE) 

O **Pix** é o mais novo método de pagamento eletrônico criado pelo **Banco Central do Brasil**. Você encontra todos os detalhes na [página oficial](https://www.bcb.gov.br/estabilidadefinanceira/pix) do Pix.

Essa biblioteca foi criada para ser utilizada principalmente com o plugin de **Woocommerce** [Pix por Piggly](https://wordpress.org/plugins/pix-por-piggly/). Mas, pode ser utilizada em qualquer sistema onde seja necessário a criação de payloads, códigos e QRCodes Pix.

* Compatível com o Pix versão 2.2.1, veja mais detalhes [clicando aqui](https://www.bcb.gov.br/content/estabilidadefinanceira/pix/Regulamento_Pix/II-ManualdePadroesparaIniciacaodoPix.pdf).

> Confira também nossa micro interface pix em [piggly/php-pix-app](https://github.com/piggly-dev/php-pix-app)

> Se você apreciar a função desta biblioteca e quiser apoiar este trabalho, sinta-se livre para fazer qualquer doação para a chave aleatória Pix `aae2196f-5f93-46e4-89e6-73bf4138427b` ❤.

## Instalação

Essa biblioteca pode ser instalada via **Composer** com `composer require piggly/php-pix`;

### Atualização das versões < 1.1.* para 1.2.0

Muitas alterações aconteceram. Abaixo descrevemos por classes o que mudou, o que continuou e como isso afeta o seu antigo código:

#### Tratamento de Exceções

Antes, todos os erros no código retornavam um objeto simples `Exception` com uma mensagem sobre mais detalhes. Mas, agora, dividimos as principais exceções entre alguns tipos de objetos, são eles:

* `CannotParseKeyTypeException`: Impossível determinar o tipo da chave Pix;
* `EmvIdIsRequiredException`: O campo EMV não foi preenchdo e é obrigatório;
* `InvalidCobFieldException`: Algum campo COB está inválido;
* `InvalidEmvFieldException`: Algum campo EMV está inválido;
* `InvalidFieldException`: Algum campo está inválido;
* `InvalidPixCodeException`: O código Pix recebido é inválido;
* `InvalidPixKeyException`: A chave Pix informada é incompatível com o tipo;
* `InvalidPixKeyTypeException`: O tipo de chave Pix informado é inválido.

> As classes acima possuem métodos `get*()` personalizados para você obter de forma mais precisa dos dados de erros equivalentes.

> Se você já tratava exceções, nada muda! Exceto que, agora, tem mais controle para tratar determinados tipos de erros.

#### Classe **Reader**

* O método `extract()` resultará em uma exceção `InvalidPixCodeException` se o código Pix não foi um código Pix válido;
* O método `export()` criará um `Payload` compatível com o código Pix lido.

#### Classe **Parser**

* O método `getKeyType()` resultará em uma excessão `CannotParseKeyTypeException` se não for possível determinar o tipo da chave Pix;
* Os métodos `validateCpf()` e `validateCnpj()` agora são públicos e podem ser utilizados individualmente;
* O método `getRandom()` cria uma `string` aleatória contendo `25` caracteres entre `[A-Za-z0-9]`;
* O método `validate()` pode resultar em dois tipos de exceções: `InvalidPixKeyTypeException` quando o tipo da chave é inválido; `InvalidPixKeyException` quando a chave não é compatível com o tipo.

#### Classe **Payload**

* O valor padrão inicial do `tid` sempre será `***` quando não preenchido;
* O método `setPixKey()` pode resultar em dois tipos de exceções: `InvalidPixKeyTypeException` quando o tipo da chave é inválido; `InvalidPixKeyException` quando a chave não é compatível com o tipo;
* O método `setDescription()` aceita até `40` caracteres;
* O método `setTid()` pode receber um valor vazio, quando acontecer, irá gerar um valor aleatório para o ID da transação com o método `Parser::getRandom()`;
* O método `getTid()` foi adicionado;
* O método `setAsReusable()` foi **descontinuado** (veja mais detalhes abaixo);
* Ao definir alguns campos, eles podem retornar a exceção `EmvIdIsRequiredException` se o campo não estiver preenchido ou `InvalidEmvFieldException` se o campo estiver incorreto.

##### Descontinuação do `setAsReusable()`

O método `setAsReusable()` definia se um código Pix era único (dinâmico) ou reutilizável (estático). Ele continua valendo para criações manuais, entretanto, para organização do código, o `Payload` foi dividido em duas classes:

* Classe `StaticPayload`: para códigos Pix estáticos; não muda nada, com excessão que sempre será um código reutilizável e ignora o método `setAsReusable()`;
* Classe `DynamicPayload`: para códigos Pix dinâmicos; altera o comportamento padrão do `Payload`, pois ignora os métodos `setPixKey()`, `setDescription()` e `setAsReusable()`. Os métodos ainda podem ser chamados, entretanto não alterarão valores no `Payload`. Além disso, acrescenta o método `setPayloadUrl()` com a URL do payload recebido pelo PSP (SPI). Cuidado ao utilizar `setTid()` alguns SPIs aceitam, enquanto outros não.

> A classe **Dynamic Payload** está diretamente associada a um QR Code dinâmico e só pode ser utilizada após criação da cobrança via alguma API Pix. A api será responsável por criar a cobrança no SPI e retornar o `location` (uma URL que contem os dados do pix). Esse `location` deve ser adicionado em `setPayloadUrl()`.

**Exemplo**

```php
// Montagem de um pix estático
$payload = (new StaticPayload())
	->setMerchantName($merchantName)
	->setMerchantCity($merchantCity)
	->setPixKey($keyType, $keyValue)
	->setDescription($description)
	->setAmount($amount)
	->setTid($tid);

// Montagem de um pix dinâmico
$payload = (new DynamicPayload())
	->setMerchantName($merchantName)
	->setMerchantCity($merchantCity)
	->setAmount($amount)
	->setPayloadUrl($url);

// Lendo um código pix e obtendo o payload
$payload = (new Reader($pixCode))->export();
```

#### Classe **CobPayload**

> O problema com as APIs do Pix é que, mesmo com o Banco Central lançando o padrão de comunicação para ser adotado, muitos SPI fazem as coisas do seu jeito. A única maneira de desenvolve uma biblioteca compatível com todas as principais APIs do mercado é tendo acesso a elas. Entendemos que isso está distante do propósito deste projeto. Afinal, cada API deveria ter sua própria biblioteca e então utilizar a nossa para montar os códigos Pix.

> Mas, decidimos implementar a classe `CobPayload` que utiliza o padrão do Banco Central do Brasil. Cada API deveria ter um `Payload` parecido para implementar os métodos. Fique a vontade para criar conforme a API que irá utilizar.

É utilizada para tratamento dos dados da **API Pix** e segue os padrões determinados em [bacen/pix-api](https://github.com/bacen/pix-api). Essa classe auxilia você criar um payload mais limpo, assim como receber os dados da **API Pix** de uma forma muito mais orgânica e organizada.

Além dos métodos para obter/setar dados do `CobPayload`, haverão dois métodos disponíveis sendo eles:

* `export()`: exporta todos os dados da classe `CobPayload` para o array compatível com a **API Pix**;
* `import()`: importa todos os dados de resposta da **API Pix** para os objetos relacionados criando um `CobPayload` organizado.

O `CobPayload` e as classes deviradas disponíveis em `Entities/Cob/*` são bem flexíveis e fazem a importação/exportação de todos os dados disponíveis conforme os modelos padrões da **API Pix**, não há muito com o que se preocupar.

> Em breve, vamos criar uma documentação detalhada sobre essas classes.

**Exemplo**

```php
// Não implementamos ainda uma classe $api
$cobResponse = $api->getCob($tid);

// Cria o cob para normalizar os dados
$cob = (new CobPayload())->import($cobResponse);

// Nome do recebedor do Pix
$cob->getSender()->getName();
// Nome do devedor do Pix
$cob->getRecipient()->getName();
// Valor original do Pix
$cob->getAmount()->getOriginalAmount();
// Status da cobrança do Pix
$cob->getStatus();
// -> exemplos de dados

// Você também pode criar o seu cob e enviar via $api
$devedor = (new Entities\Cob\Person())->setDocument('12345678930');
$recebedor = (new Entities\Cob\Person())->setDocument('11222333000100');
$valor = (new Entities\Cob\Amount())->setOriginalAmount('1.00');
$calendario = (new Entities\Cob\Calendar())->setDueDate(DateTime::now()->add(new DateInterval('P10D')));

$cob = (new CobPayload())
	->setSender($recebedor)
	->setRecipient($devedor)
	->setAmount($valor)
	->setCalendar($calendario);

// Não implementamos ainda uma classe $api
// Envie o payload do cob
$cobResponse = $api->createCob($cob->export());

// Capture a resposta
$cob = (new CobPayload())->import($cobResponse);

// E, depois pode convertê-lo para um payload:

```

### Atualização das versões 1.0.* para 1.1.0

Nenhum método foi alterado ou removido. As mesmas funções foram mantiadas, apenas a lógica interna de determinados métodos foram melhoradas e novas classes e recursos foram acoplados. A migração pode ser realizada tranquilamente e sem problemas. Algumas coisas que mudaram:

* Se você utiliza os métodos `validate*()` do `Parser` para validar individualmente os tipos das chaves Pix, esses métodos não retornam mais uma `Exception`, mas um `boolean`. Somente o método `validate()` continua retornando uma `Exception`. Confira a mudança da código abaixo:

```php
// !! ANTES
Parser::validateDocument($cpf);
// -> trazia uma excessão e interrompia a reprodução do código.

// !! AGORA
$valid = Parser::validateDocument($cpf);
// -> você precisa tratar excessões manualmente
if ( !$valid )
{ throw new Exception('A chave de CPF/CNPJ não é válida.'); }

// !! O MÉTODO VALIDATE() AINDA TRAZ EXCESSÕES
Parser::validate($pixKeyType,$pixKey);
// -> irá interromper o código se a chave não for válida.
```

## Como o Pix funciona?

De acordo com o [Manual do BR Code](https://www.bcb.gov.br/content/estabilidadefinanceira/SiteAssets/Manual%20do%20BR%20Code.pdf) e todas as [Especificações Técnicas](https://www.bcb.gov.br/content/estabilidadefinanceira/forumpireunioes/Anexo%20I%20-%20QRCodes%20-%20Especifica%C3%A7%C3%A3o%20-%20vers%C3%A3o%201-1.pdf), o Pix implmentado pelo Banco Central do Brasil adota todas as proposta do padrão EMV®1. As principais funções que essa biblioteca executa, são:

1. Geração do **QR Code** o método `getQRCode()`, utilizando a biblioteca `chillerlan/php-code` ([veja aqui](https://github.com/chillerlan/php-qrcode));
2. O código Pix em formato de texto com o método `getPixCode()` para o formato de pagamento **Pix Copia & Cola**.

### Padrão EMV®1 

Por padrão, o **BR Code** utiliza apenas caracteres alfanuméricos, identificado pelo seguinte regex `[A-Za-z0-9\$\%\*\+\-\.\/\ \@]`. A estrutura do código EMV®1 é composta por três conjuntos de caracteres:

1. ID `[\d]{2}`;
2. Tamanho em caracteres do conteúdo `[\d]{2}`;
3. Conteúdo `[A-Za-z0-9\$\%\*\+\-\.\/\ \@]`.

#### Exemplos

O código `000200`, representa:

* `00` ID para `Payload Format Indicator`;
* `02` Tamanho em caracteres do conteúdo;
* `01` Conteúdo do campo, neste caso identificando a `Versão do Payload`;

## Como essa biblioteca ajuda?

Cada campo **EMV®1** contém suas especificações, entre elas o tamanho do campo permitido, caracteres permitidos e afins. Além das chaves terem os tipos pré-definidos como: Chave Aleatória, CPF/CNPJ, E-mail e Telefone. Todas as validações são feitas por essa biblioteca:

* Conteúdo do campo como `[A-Za-z0-9\$\%\*\+\-\.\/\ \@]`;
* Chave aleatória no formato `uuid`;
* Chave de CPF/CNPJ com um valor válido e apenas `numérico`;
* Chave de E-mail com um valor válido;
* Chave de Telefone com um valor válido e apenas `numérico`.

> Alguns bancos, por trabalharem com versões antigas do Pix, podem ou não aceitar o caractere `@` para Chaves de E-mail. Atualizamos a biblioteca para ativar a substituição do `@` por espaço no e-mail `$pix->applyEmailWhitespace()` sinta-se livre para fazer os testes com a sua chave pix.

> A partir das versões mais recentes do Pix, ocorreram mudanças no ID da transação para QR Codes estáticos, são elas descritas: O objeto primitivo EMV 62-05 Reference Label, conforme especificado no manual do BR Code, é limitado a 25 caracteres [...] Os caracteres permitidos no contexto do Pix para o campo txid (EMV 62-05) são: `[a-zA-Z0-9]` [...] se o gerador do QR optar por não utilizar um transactionID, o valor "***" deverá ser usado para indicar essa escolha.

### Classe `Parser`

A classe `Parser` apresenta todos os métodos como `static` e segue o seguinte formato:

* Métodos com `validate` validam se o valor da chave é compatível com o formato esperado por seu tipo, retornando `true` quando verdadeiro e `false` quando inválido;
* Métodos com `parse` apenas tratam os campos retirando todos os caracteres inválidos para serem utilizando, também seguindo o tipo da chave;
* O método `validate()` que recebe a chave e o tipo da chave retornando uma `Exception` quando a chave/tipo forem incompatíveis ou inválidos;
* O método `getKeyType()` traduz a chave recebida para um dos tipos correspondentes. Trará uma `Exception` se nenhum tipo válido for identificado;
* O método `getAlias()` retorna a `label` conforme o tipo de chave. Por exemplo, ao receber `Parser::KEY_TYPE_RANDOM` retorna `Chave Aleatória`.

### Classe `Payload`

A classe `Payload` é responsável por montar o payload do Pix e segute o seguinte formato:

* O método `applyEmailWhitespace()` determina que o `@` deve ser substituido por um espaço (para bancos que utilizem versões não atualizadas do Pix);
* O método `applyValidCharacters()` determina que acentos e caracteres inválidos como `[\!\.\,\@\#\$\%\&\*\(\)\/\*\?]` deve ser removidos/substituídos nos campos do Pix. Esse efeito é aplicado em `description`, `merchantName` e `merchantCity`;
* * O método `applyUppercase()` transformas todos os caracteres dos campos em maiúsculo. Esse efeito é aplicado em `description`, `merchantName` e `merchantCity`;
* Métodos com `set` determinam valores para os atributos do Pix;
* O método `getPixCode()` retorna o código Pix em formato de texto;
* O método `getQRCode()` retorna uma `string` formatada em `base64`. A saída pode ser controlada com os valores `Payload::OUTPUT_*` para o formato de saída do QR Code e, também, os valores `Payload::ECC_*` para controlar as porcentagens de perca de dados do QR Code.

> Agora a classe é subdividida entre duas classes `StaticPayload` e `DynamicPayload`. Para fins de compatibilidade a classe `Payload` poderá ser criada, mas por padrão ela terá o comportamento de um Pix estático `StaticPayload`.

### Classe `Reader`

> Cada banco determina as informações que a chave Pix terá. Nesses casos, quando o pix é configurado com informações incorretas, podem haver uma série de problemas de compatibilidade. Por conta disso, criamos essa classe.

A classe `Reader` nasceu para ser um tradutor dos códigos pix. O objetivo é ler um código Pix gerado por determinado banco e extrair os seguintes dados: _Chave Pix, Tipo da Chave, Descrição, ID da Transação, Nome do Titular, Cidade e Valor_.

> A classe é automática, ou seja, ao criar uma nova instância ela já irá extrair todos os dados do código Pix.

* Métodos com `get` determinam valores que podem ser obtidos do código Pix;
* O método `extract()` executa novamente a extração dos dados de um outro código Pix, por exemplo.
* O método `export()` exporta os dados Pix obtidos para um Payload compatível, sendo ele `StaticPayload` ou `DynamicPayload`.

> Você pode exportar os dados do Reader para um Payload com o método `export()`.

### Classe CobPayload

> O problema com as APIs do Pix é que, mesmo com o Banco Central lançando o padrão de comunicação para ser adotado, muitos SPI fazem as coisas do seu jeito. A única maneira de desenvolve uma biblioteca compatível com todas as principais APIs do mercado é tendo acesso a elas. Entendemos que isso está distante do propósito deste projeto. Afinal, cada API deveria ter sua própria biblioteca e então utilizar a nossa para montar os códigos Pix.

> Mas, decidimos implementar a classe `CobPayload` que utiliza o padrão do Banco Central do Brasil. Cada API deveria ter um `Payload` parecido para implementar os métodos. Fique a vontade para criar conforme a API que irá utilizar.

É utilizada para tratamento dos dados da **API Pix** e segue os padrões determinados em [bacen/pix-api](https://github.com/bacen/pix-api). Essa classe auxilia você criar um payload mais limpo, assim como receber os dados da **API Pix** de uma forma muito mais orgânica e organizada.

Além dos métodos para obter/setar dados do `CobPayload`, haverão dois métodos disponíveis sendo eles:

* `export()`: exporta todos os dados da classe `CobPayload` para o array compatível com a **API Pix**;
* `import()`: importa todos os dados de resposta da **API Pix** para os objetos relacionados criando um `CobPayload` organizado.

O `CobPayload` e as classes deviradas disponíveis em `Entities/Cob/*` são bem flexíveis e fazem a importação/exportação de todos os dados disponíveis conforme os modelos padrões da **API Pix**, não há muito com o que se preocupar.

> Em breve, vamos criar uma documentação detalhada sobre essas classes.

**Exemplo**

```php
// Não implementamos ainda uma classe $api
$cobResponse = $api->getCob($tid);

// Cria o cob para normalizar os dados
$cob = (new CobPayload())->import($cobResponse);

// Nome do recebedor do Pix
$cob->getSender()->getName();
// Nome do devedor do Pix
$cob->getRecipient()->getName();
// Valor original do Pix
$cob->getAmount()->getOriginalAmount();
// Status da cobrança do Pix
$cob->getStatus();
// -> exemplos de dados

// Você também pode criar o seu cob e enviar via $api
$devedor = (new Entities\Cob\Person())->setDocument('12345678930');
$recebedor = (new Entities\Cob\Person())->setDocument('11222333000100');
$valor = (new Entities\Cob\Amount())->setOriginalAmount('1.00');
$calendario = (new Entities\Cob\Calendar())->setDueDate(DateTime::now()->add(new DateInterval('P10D')));

$cob = (new CobPayload())
	->setSender($recebedor)
	->setRecipient($devedor)
	->setAmount($valor)
	->setCalendar($calendario);

// Não implementamos ainda uma classe $api
// Envie o payload do cob
$cobResponse = $api->createCob($cob->export());

// Capture a resposta
$cob = (new CobPayload())->import($cobResponse);

// E, depois pode convertê-lo para um payload:

```

### Os atributos do Pix

> Somente os campos `description` e `tid` continuam a conter limitações de caracteres devido a muitas incompatibilidades que estavam surgindo.

Os atributos **obrigatórios** do Pix são:

* `Pix Key` alterado pelo método `setPixKey()` com o tipo `Parser::KEY_TYPE_*` e o valor da chave Pix;
* `Merchant Name` alterado pelo método `setMerchantName()` com o nome do titular da conta como consta na instituição bancária.
* `Merchant City` alterado pelo método `setMerchantCity()` com a cidade da agência da conta como consta na instituição bancária.

Os atributos **opcionais** do Pix são:

* `Point of Initiation Method` alterado pelo método `setAsReusable()` sendo `true` como código Pix reutilizável e `false` como código Pix utilizável apenas uma vez. Para utilizar `true` deve-se haver um **PSP** autorizado para controlar os **QR Code Dinâmicos**. Ainda não implementamos conexões com **APIs** de **PSPs**.
* `Merchant Account Information . Label` alterado pelo método `setDescription()` com a descrição do pagamento. Tamanho máximo de `36 caracteres`.
* `Transaction Amount` alterado pelo método `setAmount()` com o valor da transação em `float`. Tamanho máximo de `13 caracteres`.
* `Additional Data Field . Reference Label` alterado pelo método `setTid()` com o ID da transação. Tamanho máximo de `25 caracteres`.

## Incompatibilidade de Chaves

O **Pix** ainda é muito recente e, apenas das padronizações do **Banco Central do Brasil**, muitos bancos criaram algumas variações e definiram como aceitam determinadas chaves. A recomendação principal é: **utilize chaves aleatórias**.

As chaves aleatórias seguem o padrão universal `uuid`, então, não tem o que cada banco inventar aqui. Elas precisam ser aceita no formato `v4`. Não detectamos qualquer problema de incompatibilidade com esses tipos de chaves.

Algumas chaves que encontramos incompatibilidades para determinados bancos:

* E-mail: alguns bancos aceitam `@`, outros aceitam espaço e outros aceitam ambos;
* Telefone: alguns bancos aceitam `+55`, outros ignoram e outros aceitam com e sem `+55`;
* Transaction ID (tid): alguns bancos não aceitam caracteres diferentes `[a-zA-Z0-9]`, enquanto outros aceitam;

### Divergências entre Pix Copia & Cola e QR Codes

Há alguns relatos que alguns bancos leem o **QR Code**, mas não leem o **Pix Copia & Cola**. Este não é um problema da biblioteca, o código Pix de ambos são o mesmo! Caso esteja curioso, abra um leitor de QR Code e leia o código é examente o mesmo que o **Pix Copia & Cola**.

Nesse caso, tente utilizar as funções corretivas como `applyEmailWhitespace()`, `applyValidCharacters()` e `applyUppercase()`. Seu `tid` será automaticamente corrigido para o formato correto. Alguns bancos podem ter leituras diferentes e, talvez, existam caracteres inválidos para a leitura do **Pix Copia & Cola**.

## Como utilizar?

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

* Versão da Biblioteca: 1.1.0;
* Banco Emitente: NuBank;
* Banco Pagador: Inter;
* Tipo de Erro: O **QR Code** é inválido;
* Chave Pix Gerada: `00020101021126740014br.gov.bcb.pix0136aae2196f-5f93-46e4-89e6-73bf4138427b0212Pagamento 0152040000053039865406109.905802BR5913STUDIO PIGGLY6007Uberaba62130509034593-09630444C9`;

## Futuras Implementações

Queremos possibilitar o suporte para Pix Dinâmicos, esperamos em breve colocar um suporte as APIs para fazer esses tipos de transações. Por enquanto, a comunicação entre bancos e provedores de pagamento está muito complicada. Provavelmente, apenas extenderemos a class `Payload` para `DynamicPayload` permitindo a inclusão dos novos campos e criaremos interfaces e classes abstratas para cada um configurar para a API do seu PSP.

Por enquanto, na classe `Reader` extraímos apenas as informações básicas e essenciais. Em breve, tornaremos o `Payload` ainda mais flexível e permitiremos que o `Reader` leia ainda mais dados.

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