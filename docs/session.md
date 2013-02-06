# Sessão

- [Configuração](#configuration)
- [Uso de Sessão](#session-usage)
- [Dados Flash](#flash-data)
- [Sessões no Banco de Dados](#database-sessions)

<a name="configuration"></a>
## Configuração

Uma vez que o HTTP é um protocolo "sem lado", sessões fornecem uma maneira de armazenar informações sobre o usuário nas requisições. Laravel embarca com uma variedade de back-ends de sessão, disponíveis para o uso através de uma limpa API unificada. Suporta populares back-ends como [Memcached](http://memcached.org), [Redis](http://redis.io), e banco de dados.

A configuração da sessão está armazenada em `app/config/session.php`. Certifique-se de revisar toda as opções documentadas neste arquivo. Por padrão, Laravel usa `cookie` para driver de sessão, que irá funcionar bem para a maioria das aplicações.

<a name="session-usage"></a>
## Uso de Sessão

**Guardando Um Item Na Sessão**

	Session::put('key', 'value');

**Recuperando Um Item Da Sessão**

	$value = Session::get('key');

**Recuperando Um Item Ou Retornando Um Valor Padrão**

	$value = Session::get('key', 'default');

	$value = Session::get('key', function() { return 'default'; });

**Determinando Se Um Item Existe Na Sessão**

	if (Session::has('users'))
	{
		//
	}

**Removendo Um Item Da Sessão**

	Session::forget('key');

**Removendo Todos Os Itens Da Sessão**

	Session::flush();

**Retomando A Sessão**

	Session::regenerate();

<a name="flash-data"></a>
## Dados Flash

Às vezes, você pode querer armazenar itens na sessão somente para a próxima solicitação. Use o método `Session::flash`:

	Session::flash('key', 'value');

**Reflashing O Dado Flash Atual Para Uma Outra Requisição**

	Session::reflash();

**Reflashing Somente Um Subconjunto De Dados Flash**

	Session::keep(array('username', 'email'));

<a name="database-sessions"></a>
## Sessões no Banco de Dados

Para usar o `banco de dados` como driver de sessão, você precisa configurar uma tabela para os itens de sessão. Abaixo está um exemplo de declaração de `Schema` para a tabela:

	Schema::create('sessions', function($t)
	{
		$t->string('id')->unique();
		$t->text('payload');
		$t->integer('last_activity');
	});
