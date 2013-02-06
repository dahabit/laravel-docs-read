# Configuração

- [Introdução](#introduction)
- [Configuração do Ambiente](#environment-configuration)

<a name="introduction"></a>
## Introdução

Todos os arquivos de configuração do framework Laravel são armazenadas no diretório `app/config`. Cada opção em cada arquivo está documentada, então sinta-se livre para navegar através dos arquivos e se familiarizar com as opções disponíveis para você.

Algumas vezes você precisará acessar valores de configuração em tempo de execução. Você pode fazer isso usando a classe `Config`:

**Acessando um Valor de Configuração**

	Config::get('app.timezone');

Observe que a sintaxe com "ponto" deve ser usado para acessar valores nos vários arquivos. Você pode também definir valores de configuração em tempo de execução:

**Definindo uma Valor de Configuração**

	Config::set('database.default', 'sqlite');

<a name="environment-configuration"></a>
## Configuração de Ambiente

Muitas vezes, é útil ter valores diferentes de configuração com base no ambiente onde aplicativo é executado. Por exemplo, você pode querer usar um controlador de cache diferente em sua máquina de desenvolvimento local do que no servidor de produção. É fácil de realizar isso usando configuração baseado em ambiente.

Simplesmente crie um diretório dentro de `config` que case com seu nome de ambiente, como `local`. Depois, crie arquivos de configuração que sobrescreva e especifique as opções para este ambiente. Por exemplo, para sobrescrever o driver de cache o ambiente local, você deve criar um arquivo `cache.php` em `app/config/local` com o seguinte conteúdo:

	<?php

	return array(

		'driver' => 'file',

	);

> **Nota:** Não use 'testing' como um nome de ambiente. Isso é reservado para as unidades de teste.

Observe que você não precisa especificar _todas_ opções que está no arquivo de configuração base, mas somente a opção que desejar sobrescrever. O arquivo de configuração de ambiente irá "cascatear" os arquivos de base.

Depois, precisamos informar o framework como determinar qual ambiente está sendo executado. O ambiente padrão é sempre `production`. No entanto, você pode configurar outros ambientes dentro do arquivo `start.php` no diretório raiz da sua instalação. Nesse arquivo você procurará uma chamada `$app->detectEnvironment`. A matrir passada para esse método é usado para determinar o ambiente atual. Você pode adicionar outros nomes de ambientes e máquinas para o array, como necessitar.
