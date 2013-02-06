# Desenvolvendo Pacotes

- [Introdução](#introduction)
- [Criando um Pacote](#creating-a-package)
- [Estrutura do Pacote](#package-structure)
- [Provedores de Serviço](#service-providers)
- [Convenções de Pacotes](#package-conventions)
- [Fluxo de Desenvolvimento](#development-workflow)
- [Roteamento do Pacote](#package-routing)
- [Configuração do Pacote](#package-configuration)
- [Migrações do Pacote](#package-migrations)
- [Assets do Pacote](#package-assets)
- [Publicando Pacotes](#publishing-packages)

<a name="introduction"></a>
## Introdução

Pacotes são a principal forma de adicionar funcionalidades ao Laravel. Pacotes podem ser, desde uma ótima maneira de trabalhar com datas como [Carbon](https://github.com/briannesbitt/Carbon), uma estrutura de testes BDD completa como [Behat](https://github.com/Behat/Behat).

Naturalmente, existem diferentes tipos de pacotes. Alguns pacotes são independentes, ou seja, eles funcionam com qualquer estrutura, não apenas com Laravel. Carbono e Behat são exemplos de pacotes autônomos. Qualquer um desses pacotes pode ser usado com Laravel simplesmente requisitando-os em seu arquivo `composer.json`.

Porém, outros pacotes são destinados especificamente para uso com Laravel. Nas versões anteriores do Laravel, chamavam-se "bundles". Estes pacotes podiam ter rotas, controladores, view, configuração e migrações especificamente destinadas a melhorar o Laravel. Como nenhum processo especial é necessário para desenvolver pacotes autônomos, este guia abrange principalmente o desenvolvimento daqueles que são específicos para Laravel.

Todos os pacotes do Laravel são distribuídos via [Packagist](http://packagist.org) e [Composer](http://getcomposer.org), então aprender sobre essas maravilhosas ferramentas de distribuição de pacotes do PHP é essencial.

<a name="creating-a-package"></a>
## Criando um Pacote

A maneira mais fácil de criar um pacote para uso com Laravel é o comando `workbench` do Artisan.

**Emitindo O Comando Workbench Do Artisan**

	php artisan workbench

Este comando irá pedir várias informações, tais como o vendor e nome do pacote, bem como o seu nome e endereço de e-mail. Essas informações são usadas para criar o namespace e arquivo `composer.json` de seu pacote.

O nome de vendor é uma forma de diferenciar o seu pacote de outros pacotes com o mesmo nome, de pacotes de autores diferentes. Por exemplo, se eu (Taylor Otwell) criar um novo pacote chamado "Zapper", o nome do fornecedor poderá ser `Taylor`, enquanto o nome do pacote seria `Zapper`.

Uma vez que o comando `workbench` for executado, o pacote vai estar disponível dentro do diretório `workbench` da sua instalação Laravel. Primeiro, você deve executar o comando `composer install` **a partir do diretório raiz de seus pacotes workbench**, no qual instalará dependências e criará os arquivos autoload do Composer do seu pacote. Você pode instruir o comando `workbench` a fazer isso automaticamente ao criar um pacote usando a diretiva `--composer`:

**Criando Um Pacote WorkBench E Executando o Composer**

	php artisan workbench --composer

Agora, você deverá registrar o `ServiceProvider` que foi criado para o seu pacote. Para registrar o provedor adicionando no array `providers` no arquivo `app/config/app.php`. Isso irá instruir o Laravel carregar seu pacote quando seu aplicativo for iniciado. Provedores de Serviço usa uma nomenclatura `[Pacote]ServiceProvider` por convenção. Então, usando o exemplo acima, você adicionaria `Taylor\Zapper\ZapperServiceProvider` para o array `providers`.

Uma vez que o provedor tenha sido registrado, você está pronto para começar a desenvolver o seu pacote! No entanto, antes de mergulhar, você pode querer dar uma olhada nas seções abaixo para se familiarizar melhor com a estrutura de pacotes e fluxo de desenvolvimento.

<a name="package-structure"></a>
## Estrutura do Pacote

Usando o comando `workbench`, seu pacote será configurado com as convenções que permitam o funcionamento correto com outras partes do framework Laravel:

**Estrutura de Diretório de Pacote Básico**

	/src
		/Vendor
			/Package
				PackageServiceProvider.php
		/config
		/lang
		/migrations
		/views
	/tests
	/public

Vamos explorar melhor essa estrutura. O diretório `src/Vendor/Package` é a home de todas as classes do seu pacote, incluindo o `ServiceProvider`. Diretórios `config`, `lang`, `migrations`, e `views`, como você pode imaginar, irá conter os recursos correspondentes para o seu pacote. Os pacotes podem ter qualquer um desses recursos, assim como aplicações "comuns".

<a name="service-providers"></a>
## Provedores de Serviço

Os provedores de serviços são simplesmente classes de inicialização(bootstrap) para pacotes. Por padrão, elas possuem dois métodos: `boot` e `register`. Nesses métodos você pode fazer o que quiser, como: incluir arquivos de rota, registrar bindings no IoC container, atribuir eventos, ou qualquer outra coisa.

O método `register` é chamado imediatamente quando o provedor de serviço é registrando, enquanto o comando `boot` somente é chamado corretante quando um pedido é encaminhado. Então, se ações em seu provedor de serviço depender que um outro provedor de serviço esteja registrado, ou você está substituindo serviços vinculados por um outro provedor, certifique-se de usar o método `boot`.

Quando criar um pacote usando `workbench`, o comando `boot` já conterá uma ação:

	$this->package('vendor/package');

Este método permite ao Laravel saber como carregar corretamente os views, a configuração e outros recursos para a sua aplicação. Em geral, não deve haver necessidade de alterar esta linha, uma vez que irá configurar o pacote usando as convenções do workbench.

<a name="package-conventions"></a>
## Convenções de Pacotes

Ao utilizar recursos de um pacote, como configuração de itens ou views, a sintaxe com dois pontos duplo será geralmente utilizada:

**Carregando Uma View De Um Pacote**

	return View::make('package::view.name');

**Recuperando A Configuração de Um Item do Pacote**

	return Config::get('package::group.option');

> **Nota:** Se o pacote contém migrações, considere prefixar o nome de migração com o nome do pacote a fim de evitar conflitos de classe com potenciais nome de outros pacotes.

<a name="development-workflow"></a>
## Fluxo de Desenvolvimento

Ao desenvolver um pacote, é recomendado desenvolvé-lo no contexto de uma aplicação, permitindo a você facilmente ver e experimentar seus templates, etc. Então, ao iniciar, instale uma cópia do framework Laravel, e então use o comando `workbench` para criar a estrutura do seu pacote.

Depois do comando `workbench` criar o seu pacote, dê o comando `git init` de dentro do diretório `workbench/[vendor]/[pacote]` e um `git push` para enviar o seu pacote diretamente do workbench! Isso permitirá que você desenvolva convenientemente o seu pacote em um contexto de aplicação sem ficar emperrado por comandos `composer update` constantes.

Desde que seus pacotes estejam no diretório `workbench`, você pode ficar se perguntando como o Composer faz autoload dos arquivos dos seus pacotes. Se o diretório `workbench` existir, Laravel irá inteligentemente buscar os seus pacotes, carregando seus arquivos autoload do Composer, do seu pacote, quando a aplicação iniciar!

<a name="package-routing"></a>
## Roteamento do Pacote

Em versões anteriores do Laravel, uma cláusula `handles(manipuladora)` era usada para especificar quais URIs o pacote poderia responder. No entanto, em Laravel 4, um pacote pode responder a qualquer URI. Para carregar um arquivo de rotas para o seu pacote, basta `include(inclui-lo)` dentro de seu provedor de serviços no método `register`.

**Incluindo Um Arquivo De Rota De Um Provedor De Serviço**

	public function register()
	{
		$this->package('vendor/package');

		include __DIR__.'/routes.php';
	}

<a name="package-configuration"></a>
## Configuração do Pacote

Alguns pacotes requer arquivos de configuração. Esses arquivos devem ser definidos da mesma maneira que os arquivos de configuração de uma aplicação típica. E, ao usar o método padrão `$this->package` registrado nos recursos em seu provedor de serviço, pode ser acessado usando sintaxe "double-colon(dúplo dois pontos)":

**Acessando Arquivos De Configuração do Pacote**

	Config::get('package::file.option');

Mas, se sua aplicação contém um simples arquivo de configuração, você pode nomeá-lo de `config.php`.Feito isso, você acessa as opções diretamente, sem especificar o nome do aquivo:

**Acessando Um Simples Arquivo De Configuração do Pacote**

	Config::get('package::option');

### Cascading Configuration Files

Quando outros desenvolvedores instalarem o pacote, eles podem querer substituir algumas das opções de configuração. No entanto, se alterar os valores no código fonte do pacote, eles serão substituídos nas próximas atualizações do pacote pelo Composer. Em vez disso, o comando `config:publish` deve ser usado:

**Executando Comando De Publicação De Configuração**

	php artisan config:publish vendor/package

Quando este comando é executado, os arquivos de configuração do seu pacote serão copiados para `app/config/packages/vendor/package` onde eles podem ser facilmente modificados pelo desenvolvedor!

> **Nota:** O desenvolvedor pode criar arquivos de configurações para ambientes específicos de seu pacote colocando os em `app/config/packages/vendor/package/environment`.

<a name="package-migrations"></a>
## Migrações do Pacote

Você pode facilmente criar e executar migrações para qualquer um de seus pacotes. Para criar uma migração para um pacote no workbench, use a opção `--bench`:

**Criando Migrações Para Um Pacote Workbench**

	php artisan migrate:make create_users_table --bench="vendor/package"

**Executando Migrações Para Um Pacote Workbench**

	php artisan migrate --bench="vendor/package"

Para executar migrações de um pacote pronto que foi instalado via Composer no seu diretório `vendor`, use a diretiva `--package`:

**Executando Migrações Para Um Pacote Instalado**

	php artisan migrate --package="vendor/package"

<a name="package-assets"></a>
## Assets do Pacote

Algumas pacotes necessitam de recursos como JavaScript, CSS, e imagens. Mas, nos não podemos linkar os recursos que estão nos diretórios `vendor` ou `workbench`, então precisamos de um jeito de mover esses recursos para o diretório `public` da nossa aplicação. O comando `asset:publish` irá cuidar disso para você:

**Movendo Assets(Recusos) do Pacote Para O Public**

	php artisan asset:publish vendor/package

Se o pacote está no `workbench`, use a diretiva `--bench`:

	php artisan asset:publish --bench="vendor/package"

Esse pacote moverá os recursos de dentro do diretório `public/packages` de acordo com o nome de vendor e nome do pacote. Sendo assim, um pacote de nome `userscape/kudos` deverá ter seus recursos movidos para `public/packages/userscape/kudos`. Usando essa convenção, a publicação de recursos permite seguramente ativar recursos nas suas views do pacote.

<a name="publishing-packages"></a>
## Publicando Pacotes

Quando o pacote está pronto para publicar, você deve enviá-lo para o repositório [Packagist](http://packagist.org). Se o pacote é específico para Laravel, considere acressentar uma tag `laravel` no seu arquivo `composer.json`.

Além disso, é cortês e útil marcar os seus lançamentos para que os desenvolvedores possam depender de versões estáveis ​​ao solicitar o seu pacote nos arquivos `composer.json`. Se uma versão estável não está pronta, considere usar a diretiva `branch-alias` do Composer.

Uma vez que o pacote foi publicado, sinta-se livre para continuar a desenvolvê-lo dentro do contexto de aplicação criado pelo `workbench`. Esta é uma excelente forma de continuar a desenvolver convenientemente o pacote, mesmo depois de ter sido publicado.

Algumas organizações optam por hospedar seu próprio repositório privado de pacotes, para os seus desenvolvedores. Se está interessado em fazer isso, consulte a documentação do projeto [Satis](http://github.com/composer/satis) fornecido pela equipe do Composer.
