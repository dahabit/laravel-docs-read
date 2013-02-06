# Cache

- [Configuração](#configuration)
- [Uso do Cache](#cache-usage)
- [Cache no Banco de Dados](#database-cache)

<a name="configuration"></a>
## Configuração

Laravel fornece uma API unificada para vários tipos de sistema de cache. A configuração do cache encontra-se em `app/config/cache.php`. Neste arquivo você pode especificar que driver de cache você prefere usar por padrão em toda sua aplicação. Laravel suporta caching backends populares como [Memcached](http://memcached.org) e [Redis](http://redis.io) fora da caixa.

O arquivo de configuração também contem varias outras opções, que são documentadas no próprio arquivo, então certifique-se de ler sobre essas opções. Por padrão, Laravel é configurado para usar o cache driver `file`, que armazena, serializado, o objetos de cache no sistema de arquivos. Para grandes aplicações, recomenda-se que você use um cache em memória como o Memcached ou APC.

<a name="cache-usage"></a>
## Uso do Cache

**Armazenando Um Item No Cache**

	Cache::put('key', 'value', $minutes);

**Recuperando Um Item Do Cache**

	$value = Cache::get('key');

**Recuperando Um Item Ou Retornando Um Valor Padrão**

	$value = Cache::get('key', 'default');

	$value = Cache::get('key', function() { return 'default'; });

**Armazenando Um Item No Cache Permanentemente**

	Cache::forever('key', 'value');

Algumas vezes você pode querer recuperar um item do cache, mas também armazenar um valor padrão, caso seja requisitado e não existir. Isso é possível usando o método `Cache::remember`:

	$value = Cache::remember('users', $minutes, function()
	{
		return DB::table('users')->get();
	});

Você também pode combinar os métodos `remember` e `forever`:

	$value = Cache::rememberForever('users', function()
	{
		return DB::table('users')->get();
	});

Observe que todos os itens armazenados no cache são serializados, então você é livre para armazenar qualquer tipo de dado.

**Removendo Um Item Do Cache**

	Cache::forget('key');

<a name="database-cache"></a>
## Cache no Banco de Dados

Quando usar `database` como cache driver, você precisará configurar uma tabela para armazenar os itens do cache. Abaixo está um exemplod e declaração de `Schema` para a tabela:

	Schema::create('cache', function($t)
	{
		$t->string('key')->unique();
		$t->text('value');
		$t->integer('expiration');
	});
