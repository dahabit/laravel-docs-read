# Unidade de Teste

- [Introdução](#introduction)
- [Definindo & Executando Testes](#defining-and-running-tests)
- [Ambiente de Teste](#test-environment)
- [Chamando Rotas nos Testes](#calling-routes-from-tests)
- [Métodos Auxiliares](#helper-methods)

<a name="introduction"></a>
## Introdução

Laravel é construído com testes de unidade em mente. Na verdade, o suporte para testes com PHPUnit está incluído, e um arquivo `phpunit.xml` já está configurado para a sua aplicação. Além de PHPUnit, Laravel também utiliza o Symfony HttpKernel, DomCrawler e o componente BrowserKit para permitir que você inspecione e manipule seus views durante o teste, permitindo simular um navegador da web.

Existe um arquivo de exemplo de teste no diretório `app/tests`. Depois de instalar um aplicativo Laravel novo, basta executar o `phpunit` na linha de comando para executar os testes.

<a name="defining-and-running-tests"></a>
## Definindo & Executando Testes

Para criar um caso de teste, basta criar um arquivo de teste no diretório `app/tests`. A classe de teste deve estender `TestCase`. Então, basta definir métodos de teste, como faria normalmente ao usar PHPUnit.

**Um Exemplo De Classe De Teste**

	class FooTest extends TestCase {

		public function testSomethingIsTrue()
		{
			$this->assertTrue(true);
		}

	}

Você pode executar todos os testes do seu aplicativo executando o comando `phpunit` de seu terminal.

> **Nota:** Se você precisa definir seu próprio método `setUp`, certifique-se de chamar `parent::setUp`.

<a name="test-environment"></a>
## Ambiente de Teste

Ao executar testes de unidade, Laravel automaticamente vai definir o ambiente de configuração para `test`. Além disso, Laravel inclui arquivos de configuração para `session` e `cache` no ambiente de teste. Ambos os drivers estão definidos como `array` no ambiente de teste, ou seja, nenhum dado de sessão ou cache serão mantidos durante o teste. Você é livre para criar configurações de outros ambientes de testes.

<a name="calling-routes-from-tests"></a>
## Chamando Rotas nos Testes

Você pode facilmente chamar uma das suas rotas para um teste usando o método `call`:

**Chamando Uma Rota No Teste**

	$response = $this->call('GET', 'user/profile');

	$response = $this->call($method, $uri, $parameters, $files, $server, $content);

Você pode analizar o objeto `Illuminate\Http\Response`:

	$this->assertEquals('Hello World', $response->getContent());

Também é possível chamar um controlador no teste:

**Chamando Um Controlador No Teste**

	$response = $this->action('GET', 'HomeController@index');

	$response = $this->action('GET', 'UserController@profile', array('user' => 1));

O método `getContent` irá retornar o conteúdo de strings avaliadas na resposta. Se sua rota retorna um `View`, você pode acessar isso usando a propriedade `original`:

	$view = $response->original;

	$this->assertEquals('John', $view['name']);

### DOM Crawler

Você também pode chamar uma rota e receber um DOM Crawler que você pode usar para inspecionar o conteúdo:

	$crawler = $this->client->request('GET', '/');

	$this->assertTrue($this->client->getResponse()->isOk());

	$this->assertCount(1, $crawler->filter('h1:contains("Hello World!")'));

Para mais informações sobre como usar o crawler, consulte a seu [documentação oficial](http://symfony.com/doc/master/components/dom_crawler.html).

<a name="helper-methods"></a>
## Métodos Auxiliares

A Classe `TestCase` possui vários métodos auxiliares para fazer os testes da aplicação mais fácil.

Você pode definir o usuário atual, autenticado, com o método `be`:

**Definindo O Usuário Autenticado**

	$user = new User(array('name' => 'John'));

	$this->be($user);

Você também será capaz de re-semear o seu banco de dados usando o método `seed`:

**Re-Semeando O Banco De Dados No Teste**

	$this->seed();

	$this->seed($connection);

Mais informações podem ser encontradas na seção sobre [migrações e seeding](/docs/migrations#database-seeding) nesta documentação.
