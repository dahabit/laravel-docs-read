# Controladores

- [Controladores Básicos](#basic-controllers)
- [Filtros de Controladores](#controller-filters)
- [Controladores RESTful](#restful-controllers)
- [Controladores de Recursos](#resource-controllers)

<a name="basic-controllers"></a>
## Controladores Básicos

Em vez de definir todos os seus níveis lógicos de rota num simples arquivo `routes.php`, você pode querer organizar o comportamento usando classes Controladoras. Controladores podem agrupar lógicas de rota relacionais em uma clase, bem como tirar vantagem de recursos mais avançados, tais como o framework automático para [injeção de dependências](/docs/ioc).

Controladores são armazenados no diretório `app/controllers`, e esses diretório está registrando na opção `classmap(mapeamento de classes)` no seu arquivo `composer.json` por padrão.

Aqui está um exemplo básico de Classe Controladora:

	class UserController extends BaseController {

		/**
		 * Show the profile for the given user.
		 */
		public function showProfile($id)
		{
			$user = User::find($id);

			return View::make('user.profile', array('user' => $user));
		}

	}

Todos os controladores devem estender a classe `BaseController`. A `BaseController` também se encontra no diretório `app/controllers`, e pode ser usado como local para colocar a lógica compartilhada. A `BaseController` estende a classe `Controller` do framework. Agora, podemos rotear essa ação controladora assim como:

	Route::get('user/{id}', 'UserController@showProfile');

Se preferir organizar seus controladores usando PHP namespaces, basta usar o nome da classe totalmente qualificado para definir a rota:

	Route::get('foo', 'Namespace\FooController@method');

Você também pode especificar nomes em rotas de controlador:

	Route::get('foo', array('uses' => 'FooController@method',
											'as' => 'name'));

> **Nota:** Depois de criar uma nova classe, certifique-se de executar `composer dump-autoload` na linha de comando. Isso permitirá que o framework automaticamente carregue suas classes.

<a name="controller-filters"></a>
## Filtros de Controladores

[Filtros](/docs/routing#route-filters) pode ser especificado em rotas controladoras da mesma maneira que rotas "regular":

	Route::get('profile', array('before' => 'auth',
				'uses' => 'UserController@showProfile'));

No entanto, você também pode especificar filtros de dentro de seu controlador:

	class UserController extends BaseController {

		/**
		 * Instantiate a new UserController instance.
		 */
		public function __construct()
		{
			$this->beforeFilter('auth');

			$this->beforeFilter('csrf', array('on' => 'post'));

			$this->afterFilter('log', array('only' =>
								array('fooAction', 'barAction')));
		}

	}

YVocê também pode especificar filtros de controladores inline usando uma Clousure:

	class UserController extends BaseController {

		/**
		 * Instantiate a new UserController instance.
		 */
		public function __construct()
		{
			$this->beforeFilter(function()
			{
				//
			});
		}

	}

<a name="restful-controllers"></a>
## Controladores RESTful

Laravel permite a você facilmente definir uma única rota para lidar com cada ação de um controlador simples, usando convenções de REST. Primeiro, defina a rota usando o método `Route::controller`:

**Definindo Controlador RESTful**

	Route::controller('users', 'UserController');

O método `controller` aceita dois argumentos. O primeiro é a URI base do controlador, enquanto o segundo é o nome da classe do controlador. Depois, basta adicionar métodos para o seu controlador, prefixado com o verbo HTTP:

	class UserController extends BaseController {

		public function getIndex()
		{
			//
		}

		public function postProfile()
		{
			//
		}

	}

O método `index` irá responder ao URI raiz tratada pelo controlador, na qual, neste caso, é `users`.

Se sua ação controlador contém várias palavras, você pode acessar a ação usando sintaxe "dash(-)" na URI. Exemplo, a ação do controlador `UserController` responderia à URI `users/admin-profile`:

	public function getAdminProfile() {}

<a name="resource-controllers"></a>
## Controladores de Recursos

Controladores de recursos tornam mais fácil construir controladores RESTful em torno de recursos. Por exemplo, você pode querer criar um controlador que gerencia "photos(fotos)" armazenados pelo seu aplicativo. Usando o controlador `controller:make` através do CLI Artisan e o método `Route::resource`, podemos criar rapidamente um controlador.

Para criar o controlador via linha de comando, execute o seguinte comando:

	php artisan controller:make PhotoController

Agora podemos registar uma rota de recursos para o controlador:

	Route::resource('photo', 'PhotoController');

Esta única declaração de rota cria múltiplas rotas para lidar com uma variedade de ações RESTful em photo(foto). Da mesma forma, o controlador gerado já terá esboçado métodos para cada uma dessas ações com notas informando que URIs e verbos serão tratados por eles.

**Ações Trados Por Controlador de Recursos**

Verbo     | Caminho               | Ação
----------|-----------------------|--------------
GET       | /resource             | index
GET       | /resource/create      | create
POST      | /resource             | store
GET       | /resource/{id}        | show
GET       | /resource/{id}/edit   | edit
PUT/PATCH | /resource/{id}        | update
DELETE    | /resource/{id}        | destroy

Às vezes, você só precisa lidar com um subconjunto das ações de recursos::

	php artisan controller:make PhotoController --only=index,show

	php artisan controller:make PhotoController --except=index

E, você também pode especificar um subconjunto de ações para tratar na rota:

	Route::resource('photo', 'PhotoController',
					array('only' => array('index', 'show')));
