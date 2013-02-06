# Roteamento

- [Roteamento Básico](#basic-routing)
- [Parâmetros de Rota](#route-parameters)
- [Filtros de Rota](#route-filters)
- [Rotas Nomeadas](#named-routes)
- [Grupos de Rota](#route-groups)
- [Roteamento de Sub-Domínio](#sub-domain-routing)
- [Prefíxo de Rotas](#route-prefix)
- [Lançando Erros 404](#throwing-404-errors)
- [Controladores de Recurso](#resource-controllers)

<a name="basic-routing"></a>
## Roteamento Básico

A maioria das rotas do seu aplicativo será definida no arquivo `app/routes.php`. A simplicidade das rotas no Laravel consiste de uma URI e um Closure callback.

**Rota Básica GET**

	Route::get('/', function()
	{
		return 'Hello World';
	});

**Rota Básica POST**

	Route::post('foo/bar', function()
	{
		return 'Hello World';
	});

**Registrando uma Rota Respondendo Qualquer HTTP Verbo**

	Route::any('foo', function()
	{
		return 'Hello World';
	});

**Forçando uma Rota Usar HTTPS**

	Route::get('foo', array('https', function()
	{
		return 'Must be over HTTPS';
	}));

<a name="route-parameters"></a>
## Parâmetros de Rota

	Route::get('user/{id}', function($id)
	{
		return 'User '.$id;
	});

**Parâmetros de Rota Opcional**

	Route::get('user/{name?}', function($name)
	{
		return $name;
	});

**Parâmetros de Rota Opcional Com Valor Padrão**

	Route::get('user/{name?}', function($name = 'John')
	{
		return $name;
	});

**Restrição de Rota Com Expressão Regular**

	Route::get('user/{name}', function($name)
	{
		//
	})
	->where('name', '[A-Za-z]+');

	Route::get('user/{id}', function($id)
	{
		//
	})
	->where('id', '[0-9]+');

<a name="route-filters"></a>
## Filtros de Rota

Filtros de rota fornecem uma maneira conveniente de limitar o acesso a uma determinada rota, o que é útil para a criação de áreas de seu site que exigem autenticação. Existem vários filtros incluídos no framework Laravel, includo o filtro `auth`, filtro `guest`, e o filtro `csrf`. Eles estão alocados no arquivo `app/filters.php`.

**Definindo Filtros de Rota**

	Route::filter('old', function()
	{
		if (Input::get('age') < 200)
		{
			return Redirect::to('home');
		}
	});

Se uma resposta é retornada de um filtro, a mesma será considerada a resposta da requisição e a rota não será executada.

**Anexando um Filtro a uma Rota**

	Route::get('user', array('before' => 'old', function()
	{
		return 'You are over 200 years old!';
	}));

**Anexando Multiplos Filtros a uma Rota**

	Route::get('user', array('before' => 'auth|old', function()
	{
		return 'You are authenticated and over 200 years old!';
	}));

**Especificando Parâmetros do Filtro**

	Route::filter('age', function($value)
	{
		//
	});

	Route::get('user', array('before' => 'age:200', function()
	{
		return 'Hello World';
	}));

**Filtros Baseados em Padrão**

Você pode também especificar que um filtro se aplique a todo um conjunto de rota baseado na sua URI.

	Route::filter('admin', function()
	{
		//
	});

	Route::when('admin/*', 'admin');

No exemplo acima, o filtro `admin` será aplicado a todas as rotas iniciadas com `admin/`. O asterisco é usado como um curinga, e irá casar qualquer combinação de caracteres.

**Classes de Filtro**

Para filtros avançados, você talvez queira usar uma classe em vez de uma Closure. Uma vez que classes de filtro são resolvidas fora da aplicação do [IoC container](/docs/ioc), você será capaz de utilizar a injeção de dependência nesses filtros para maior testabilidade.

**Definindo Classes de Filtro**

	class FooFilter {

		public function filter()
		{
			// Lógica do Filtro...
		}

	}

**Registrando uma Classe de Filtro**

	Route::filter('foo', 'FooFilter');

<a name="named-routes"></a>
## Rotas Nomeadas

Rotas nomeadas torna a referência para rotas, ao gerar redirecionamentos ou URLs, mais conveniente. Você pode especificar um nome para rota assim como:

	Route::get('user/profile', array('as' => 'profile', function()
	{
		//
	}));

Agora, você pode usar seus nomes de rotas quando gerar URLs ou redirecionamentos:

	$url = URL::route('profile');

	$redirect = Redirect::route('profile');

<a name="route-groups"></a>
## Grupos de Rota

Algumas vezes você pode precisar aplicar filtros para um grupo de rotas. Em vez de especificar um filtro para cada rota, você pode usar uma grupo de rota:

	Route::group(array('before' => 'auth'), function()
	{
		Route::get('/', function()
		{
			// Has Auth Filter
		});

		Route::get('user/profile', function()
		{
			// Has Auth Filter
		});
	});

<a name="sub-domain-routing"></a>
## Roteamento de Sub-Domínio

Rotas em Laravel são capazes de lidar com sub-domínios, e passar seu curinga como parâmetro para o o domínio:

**Registrando Roteamento de Sub-Domínio**

	Route::group(array('domain' => '{account}.myapp.com'), function()
	{

		Route::get('user/{id}', function($account, $id)
		{
			//
		});

	});

<a name="route-prefix"></a>
## Prefixos de Rota

Prefixar opção de rota oferece a vantagem de segmentar a URL para uma rota ou lista de rotas agrupadas. 

**Prefixando Rotas Agrupadas**

	Route::group(array('prefix' => 'admin'), function()
	{

		Route::get('user', function()
		{
			//
		});

	});

<a name="throwing-404-errors"></a>
## Lançando Erros 404

Existem duas maneiras de manualmente lançar um erro 404 de uma rota. A primeira, deve usar o métodos `App::abort`:

	App::abort(404);

A segunda, você pode lançar uma instância de `Symfony\Component\HttpKernel\Exception\NotFoundHttpException`.

Para mais informações sobre manipulações de exceções 404 e o uso de respostas customizadas para esses erros, você pode encontrar na seção de [erros](/docs/errors#handling-404-errors) da documentação.

<a name="resource-controllers"></a>
## Controladores de Recursos

Controladores de Recursos torna fácil construir controles RESTful atraves dos recursos. 

Veja a documentação sobre [Controladores](/docs/controllers#resource-controllers) para obter mais informações.
