# Erros & Logging

- [Erros Detalhados](#error-detail)
- [Exceções HTTP](#http-exceptions)
- [Manipulando Erros](#handling-errors)
- [Manipulando Erros 404](#handling-404-errors)
- [Logging](#logging)

## Erros Detalhados

Por padrão, erros detalhados estão habilitados em sua aplicação. Isso significa que quando ocorrer um erro, você ira ver uma página de erro com uma detalhada pilha de rastreamento e mensagem de erro. Você pode desabilitar os erros detalhados, definindo a opção `debug` como `false` no arquivo`app/config/app.php` da sua aplicação. **É altamente recomendado que você desabilite erros detalhados em seu ambiente de produção.**

## Manipulando Erros

Por padrão, o arquivo `app/start/global.php` contem um manipulador de erro para todas as exceções:

	App::error(function(Exception $exception)
	{
		Log::error($exception);
	});

Este é o manipulador de erro mais básico. Sendo assim, você pode especificar mais manipuladores se necessário. Manipuladores são chamados com base em seus type-hint(indução de tipo) de manipulação de Exceções. Por exemplo, você pode criar um manipulador que somente manipula instância de `RuntimeException`:

	App::error(function(RuntimeException $exception)
	{
		// Handle the exception...
	});

Se um manipulador de exceção retorna uma reposta, esta resposta irá ser enviada para o navegador e nenhum outro manipulador de erro sera chamado:

	App::error(function(InvalidUserException $exception)
	{
		Log::error($exception);

		return 'Sorry! Something is wrong with this account!';
	});

<a name="http-exceptions"></a>
## Exceções HTTP

Exceções em relação a HTTP, referem-se a erros que podem ocorrer durante uma requisição do cliente. Isso significa que uma página de erro não encontrada (404), um erro de autorização (401) ou mesmo um erro 500 serão gerados. Para retornar essa resposta, use o seguinte:

	App::abort(404, 'Page not found');

O primeiro argumento é o código do estado do HTTP, o segundo é uma mensagem personalizada que você queira mostrar junto com o erro.

Para uma exceção 401 não-autorizada, faça o seguinte:

	App::abort(401, 'You are not authorized.');

Essas exceções podem ser executadas a qualquer momento durante o ciclo de vida da requisição.

<a name="handling-404-errors"></a>
## Manipulando Erros 404

Você pode registrar um manipulador de erro que lide com todos os erros "404 Not Found" em sua aplicação, permitindo que você retorne uma página personalizada 404:

	App::missing(function($exception)
	{
		return View::make('errors.missing');
	});

<a name="logging"></a>
## Logging

Instalações do Laravel logging fornece uma simples camada em cima do poderoso [Monolog](http://github.com/seldaek/monolog). Por padrão, Laravel é configurado para criar logs diários para sua aplicação, e esses arquivos são armazenados em `app/storage/logs`. Você escrever informações nesses logs da seguinte maneira:

	Log::info('This is some useful information.');

	Log::warning('Something could be going wrong.');

	Log::error('Something is really going wrong.');

O logger fornece os sete níveis de logging descritos em [RFC 5424](http://tools.ietf.org/html/rfc5424): **debug**, **info**, **notice**, **warning**, **error**, **critical**, e **alert**.

Monolog possui uma variedade de manipuladores adicionais que você pode usar para logging. Se necessário, você pode acessar a instância subjacente do Monolog inicialmente usado pelo Laravel:

	$monolog = Log::getMonolog();
