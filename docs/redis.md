# Redis

- [Introdução](#introduction)
- [Configuração](#configuration)
- [Uso](#usage)

<a name="introduction"></a>
## Introdução

[Redis](http://redis.io) é um avançado software livre de armazenamento chave-valor. Ele é muitas vezes referido como um servidor de estrutura de dados uma vez que pode conter chaves do tipo [strings](http://redis.io/topics/data-types#strings), [hashes](http://redis.io/topics/data-types#hashes), [lists](http://redis.io/topics/data-types#lists), [sets](http://redis.io/topics/data-types#sets), e [sorted sets](http://redis.io/topics/data-types#sorted-sets).

<a name="configuration"></a>
## Configuração

A configuração do Redis para sua aplicação é armazenado no arquivo **app/config/database.php**. Nesse arquivo, você verá um array **redis** contendo o servidor Redis usado na sua aplicação:

	'redis' => array(

		'default' => array('host' => '127.0.0.1', 'port' => 6379),

	),

A configuração padrão do servidor deve ser suficiente para o desenvolvimento. No entanto, você é livre para modificar esse array com base no seu ambiente. Basta dar a cada servidor Redis um nome, e especificar o host e a porta usada pelo servidor.

<a name="usage"></a>
## Uso

Você obtém uma instancia do Redis chamando o método `Redis::connection`:

	$redis = Redis::connection();

Isto lhe dará uma instância do servidor Redis padrão. Você pode passar o nome do servidor para o método `connection` para obter um servidor específico, tal como definido em sua configuração Redis:

	$redis = Redis::connection('other');

Uma vez que você tiver uma instância do cliente Redis, é possível emitir qualquer um dos [comandos Redis](http://redis.io/commands) desta instância. Laravel usa métodos mágicos para passar comandos ao servidor Redis:

	$redis->set('name', 'Taylor');

	$name = $redis->get('name');

	$values = $redis->lrange('names', 5, 10);

Observe os argumentos para o comando são simplesmente passado para o método mágico. Claro, você não é obrigado a usar os métodos mágicos, você também pode passar comandos para o servidor usando o método `command`:

	$values = $redis->command('lrange', array(5, 10));

Quando você está estiver executando comandos contra a conexão padrão, basta usar métodos mágicos estáticos da classe `Redis`:

	Redis::set('name', 'Taylor');

	$name = Redis::get('name');

	$values = Redis::lrange('names', 5, 10);

> **Nota:** [Cache](/docs/cache) e [sessões](/docs/session) com drivers Redis está incluso no Laravel.