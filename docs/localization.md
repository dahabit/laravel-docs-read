# Localização

- [Introdução](#introduction)
- [Arquivos de Linguagem](#language-files)
- [Uso Básico](#basic-usage)
- [Pluralização](#pluralization)

<a name="introduction"></a>
## Introdução

A classe `Lang` do Laravel fornece uma maneira conveniente para recuperação de strings em várias linguagens, permitindo que você facilmente dê suporte a muitas linguagens na sua aplicação.

<a name="language-files"></a>
## Arquivos de Linguagem

Strings de linguagens estão armazenados no diretório `app/lang`. Neste diretório você deverá ter um outros diretório para cada linguagens suportada na sua aplicação.

	/app
		/lang
			/en
				messages.php
			/pt
				messages.php

Arquivos de linguagens são simples arrays de strings com chaves. Por exemplo:

**Exemplo De Arquivo De Linguagem**

	<?php

	return array(
		'welcome' => 'Welcome to our application'
	);

A linguagem padrão está no arquivo de configuração `app/config/app.php`. Você pode mudar a linguagem ativa a qualquer momento usando o método `App::locale`:

**Mudando A Linguagem Padrão em Tempo de Execução**

	App::setLocale('pt');

<a name="basic-usage"></a>
## Uso Básico

**Recuperando Linhas De Arquivo De Linguagem**

	echo Lang::get('messages.welcome');

O primeiro segmento da string passada no método `get` é nome do arquivo de linguagem, o segundo é o nome da linha que você deseja recuperar.

> **Nota*: Se uma linha da linguagem não existir, a chave será retorna pelo método `get`.

**Fazendo Substituições Das Linhas**

Você também pode definir place-holders em suas linhas de linguagem:

	'welcome' => 'Welcome, :name',

Quando, passado um segundo argumento para o método `Lang::get` ele fará a substituição:

	echo Lang::get('messages.welcome', array('name' => 'Dayle'));

**Verificando Se Um Arquivo De Linguagem Contem Uma Linha**

	if (Lang::has('messages.welcome'))
	{
		//
	}

<a name="pluralization"></a>
## Pluralização

Pluralização é um problema complicado, diferentes linguagens possuem suas variadas regras complexas de pluralização. Você pode gerenciar facilmente em seus arquivos de linguagens. Usando o caractere "pipe", é possível separar o singular do plural que forma a string:

	'apples' => 'There is one apple|There are many apples',

Então basta usar o método `Lang::choice` para recuperar a linha:

	echo Lang::choice('messages.apples', 10);

O tradutor do Laravel é alimentado pelo poderoso componente Symfony Translation, também é possível criar regras de pluralização mais explicitas facilmente:

	'apples' => '{0} There are none|[1,19] There are some|[20,Inf] There are many',
