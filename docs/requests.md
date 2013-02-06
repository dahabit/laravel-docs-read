# Requisições & Entradas

- [Entradas Básicas](#basic-input)
- [Cookies](#cookies)
- [Entradas Antigas](#old-input)
- [Arquivos](#files)
- [Informações da Requisição](#request-information)

<a name="basic-input"></a>
## Entradas Básicas

Você pode acessar todas as entradas do usuário com alguns métodos simples. Você não precisa se preocupar com os verbos da requisição HTTP, assim como entrada, eles são acessados todos da mesma forma.

**Recuperando Um Valor de Entrada**

	$name = Input::get('name');

**Recuperando Um Valor Padrão Se O Valor de Entrada Está Ausente**

	$name = Input::get('name', 'Sally');

**Determinando SE Um Valor de Entrada Está Presente**

	if (Input::has('name'))
	{
		//
	}

**Obtendo Todas As Entradas Da Requisição**

	$input = Input::all();

**Obtendo Somente Algumas Entradas Da Requisição**

	$input = Input::only('username', 'password');

	$input = Input::except('credit_card');

Algumas bibliotecas JavaScript como Backbone pode enviar entradas para a aplicação como JSON.

**Recuperando Entrada JSON**

	$input = Input::json();

<a name="cookies"></a>
## Cookies

Todos os cookies criados pelo framework Laravel são encriptados e assinado com um código de autenticação, significando que será considerado inválido se forem alterados pelo cliente.

**Recuperando Um Valor De Cookie**

	$value = Cookie::get('name');

**Anexando Um Novo Cookie Para A Resposta**

	$response = Response::make('Hello World');

	$response->withCookie(Cookie::make('name', 'value', $minutes));

**Criando Um Cookie Que Dura Pra Sempre**

	$cookie = Cookie::forever('name', 'value');

<a name="old-input"></a>
## Entradas Antigas

Você pode precisar manter entradas de uma requisição até a próxima requisição. Por exemplo, você pode precisar re-popular um formulário depois de checar por erros de validação.

**Flashing Entradas Na Sessão**

	Input::flash();

**Flashing Somente Algumas Entradas Na Sessão**

	Input::flashOnly('username', 'email');

	Input::flashExcept('password');

Uma vez que você frequentemente irá querer entradas flash associadas com o redirecionamento para a página anterior, você pode facilmente encadear entradas flashing para um redirecionamento.

	return Redirect::to('form')->withInput();

	return Redirect::to('form')->withInput(Input::except('password'));

> **Nota:** Você pode flash(manter rapidamente) outros dados através de requisições usando a classe [Session](/docs/session).

**Recuperando Dados antigos**

	Input::old('username');

<a name="files"></a>
## Arquivos

**Recuperando Um Arquivo Enviado**

	$file = Input::file('photo');

**Determinando Se O Arquivo Foi Enviado**

	if (Input::hasFile('photo'))
	{
		//
	}

O objeto retorno será um método `file` que é um instância da classe `Symfony\Component\HttpFoundation\File\UploadedFile`, que estende a classe PHP `SplFileInfo` e fornece uma variedade de métodos para interagir com o arquivo.

**Movendo Um Arquivo Enviado**

	Input::file('photo')->move($destinationPath);

	Input::file('photo')->move($destinationPath, $fileName);

**Recuperando O Caminho De Um Arquivo Enviado**

	$path = Input::file('photo')->getRealPath();

**Recuperando O Tamanho De Um Arquivo Enviado**

	$size = Input::file('photo')->getSize();

**Recuperando O Tipo MIME De Um Arquivo Enviado**

	$mime = Input::file('photo')->getMimeType();

<a name="request-information"></a>
## Informações da Requisição

A classe `Request` fornece muitos métodos para examinar a requisição HTTP para sua aplicação e estende a classe `Symfony\Component\HttpFoundation\Request`. Aqui está alguns destaques.

**Recuperando A URI Da Requisição**

	$uri = Request::path();

**Determinando Se O Caminho Da Requisição Casa Com Um Padrão**

	if (Request::is('admin/*'))
	{
		//
	}

**Obtém A Url Da Requisição**

	$url = Request::url();

**Recuperando Um Cabeçalho da Requisição**

	$value = Request::header('Content-Type');

**Recuperando Valores Do $_SERVER**

	$value = Request::server('PATH_INFO');

**Determinando Se A Requisição Está Usando AJAX**

	if (Request::ajax())
	{
		//
	}

**Determinando Se A Requisição Está Sobre HTTPS**

	if (Request::secure())
	{
		//
	}
