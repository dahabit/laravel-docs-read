# Views & Repostas

- [Respostas Básicas](#basic-responses)
- [Redirecionamentos](#redirects)
- [Views](#views)
- [Compositor de Views](#view-composers)
- [Repostas Especiais](#special-responses)

<a name="basic-responses"></a>
## Repostas Básicas

**Retornando Texto Das Rotas**

	Route::get('/', function()
	{
		return 'Hello World';
	});

**Criando Respostas Customizadas**

Uma instância de `Response` herda da classe `Symfony\Component\HttpFoundation\Response`, fornecendo uma variedade de métodos para construir respostas HTTP.

	$response = Response::make($contents, $statusCode);

	$response->headers->set('Content-Type', $value);

	return $response;

**Anexando Cookies Nas Respostas**

	$cookie = Cookie::make('name', 'value');

	return Response::make($content)->withCookie($cookie);

<a name="redirects"></a>
## Redirecionamentos

**Retornando Um Redirecionamento**

	return Redirect::to('user/login');

**Retornando Um Redirecionamento Para Uma Rota Nomeada**

	return Redirect::route('login');

**Retornando Um Redirecionamento Para Uma Rota Nomeada Com Parâmetros**

	return Redirect::route('profile', array(1));

**Retornando Um Redirecionamento Para Uma Rota Nomeada Com Parâmetros Nomeados**

	return Redirect::route('profile', array('user' => 1));

**Retornando Um Redirecionamento Para Uma Ação Controladora**

	return Redirect::action('HomeController@index');

**Retornando Um Redirecionamento Para Uma Ação Controladora Com Parâmetros**

	return Redirect::action('UserController@profile', array(1));

**Retornando Um Redirecionamento Para Uma Ação Controladora Com Parâmetros Nomeados**

	return Redirect::action('UserController@profile', array('user' => 1));

<a name="views"></a>
## Views

Views tipicamente contem o HTML da sua aplicação e fornece uma conveniente maneira de separar o controle e a logica de dominio da sua lógica de apresentação. Views são armazenados no diretório `app/views`.

Uma simples view pode ser algo assim:

	<!-- View armazenada em app/views/greeting.php -->

	<html>
		<body>
			<h1>Hello, <?php echo $name; ?></h1>
		</body>
	</html>

Está pode ser retornada para o navegador assim:

	Route::get('/', function()
	{
		return View::make('greeting', array('name' => 'Taylor'));
	});

O segundo argumento passado para o `View::make` é um array com dados que estarão disponíveis na sua view.

**Passando Dados Para As Views**

	$view = View::make('greeting', $data);

	$view = View::make('greeting')->with('name', 'Steve');

No exemplo acima a variavel `$name` estará acessível na view, e contém `Steve`.

**Passando Uma Sub-View Para A View**

Algumas vezes você pode querer passar uma view para dentro de outra view. Por exemplo, dada uma sub-view armazenada em `app/views/child/view.php`, podemos passar isso para outra view assim:

	$view = View::make('greeting')->nest('child', 'child.view');

	$view = View::make('greeting')->nest('child', 'child.view', $data);

A sub-view pode então ser exibida na view pai view:

	<html>
		<body>
			<h1>Hello!</h1>
			<?php echo $child; ?>
		</body>
	</html>

<a name="view-composers"></a>
## Compositor de Views

Compositor de Views são callbacks ou métodos de classe que são chamados quando uma view é criada. Se você tem dados que são necessários vincular a uma view cada vez que ela é criada por toda sua aplicação, um compositor de view pode organizar o código num só local. Portanto, compositor de view podem funcionar como "modelo de view" ou "apresentadores".

**Definindo Um Compositor de View**

	View::composer('profile', function($event)
	{
		$event->view->with('count', User::count());
	});

Agora cada vez que a view `profile` é criada, o dado `count` será vinculado ao view.

Se você preferir compositor baseado em classe, que fornecerá os benefícios de ser resolvido através da aplicação [conteúdo IoC](/docs/ioc), poderá fazê-lo:

	View::composer('profile', 'ProfileComposer');

Uma classe compositor de view deve ser definida assim como:

	class ProfileComposer {

		public function compose($event)
		{
			$event->view->with('count', User::count());
		}

	}

Observe que não há uma convenção de onde suas classes compositoras devem ser armazenadas. Você é livre para armazenar em qualquer lugar, desde que possam ser carregadas automaticamente usando as diretivas no seu arquivo `composer.json`.

<a name="special-responses"></a>
## Respostas Especiais

**Criando Uma Resposta JSON**

	return Response::json(array('name' => 'Steve', 'state' => 'CA'));

**Criando Uma Resposta JSONP**

	return Response::json(array('name' => 'Steve', 'state' => 'CA'))->setCallback(Input::get('callback'));

**Criando Um Resposta Para Download De Arquivo**

	return Response::download($pathToFile);

	return Response::download($pathToFile, $status, $headers);
