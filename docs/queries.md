# Construtor de Consultas

- [Introdução](#introduction)
- [Selects](#selects)
- [Joins](#joins)
- [Condições(Wheres) Avançadas](#advanced-wheres)
- [Agregação](#aggregates)
- [Expressões Cruas(Raw)](#raw-expressions)
- [Inserts](#inserts)
- [Updates](#updates)
- [Deletes](#deletes)

<a name="introduction"></a>
## Introdução

O construtor de consultas de banco de dados fornece uma conveniente interface fluente para criar e executar consultas no banco de dados. Ele pode ser usado para executar a maioria das operações de banco de dados em seu aplicativo, e funciona em todos os sistemas de banco de dados suportados.

> **Nota:** O construtor de consultas Laravel usa ligação de parâmetros do PDO para proteger seu aplicativo contra ataques de injeção de SQL. Não há necessidade de limpar as strings passadas.

<a name="selects"></a>
## Selects

**Recuperando Todas As Linhas Da Tabela**

	$users = DB::table('users')->get();

	foreach ($users as $user)
	{
		var_dump($user->name);
	}

**Recuperando Uma Única Linha Da Tabela**

	$user = DB::table('users')->where('name', 'John')->first();

	var_dump($user->name);

**Recuperando Uma Única Coluna Da Linha**

	$name = DB::table('users')->where('name', 'John')->pluck('name');

**Especificando Uma Cláusula Select**

	$users = DB::table('users')->select('name', 'email')->get();

	$users = DB::table('users')->distinct()->get();

	$users = DB::table('users')->select('name as user_name')->get();

**Usando O Operador Where**

	$users = DB::table('users')->where('votes', '>', 100)->get();

**Instruções OR(Ou)**

	$users = DB::table('users')
	                    ->where('votes', '>', 100)
	                    ->orWhere('name', 'John')
	                    ->get();

**Usando Where Between**

	$users = DB::table('users')
	                    ->whereBetween('votes', array(1, 100))->get();

**Usando Where In Com Um Array**

	$users = DB::table('users')
	                    ->whereIn('id', array(1, 2, 3))->get();

	$users = DB::table('users')
	                    ->whereNotIn('id', array(1, 2, 3))->get();

**Usando Where Nulo Para Buscar Registros Com Valores Não Definidos**

	$users = DB::table('users')
	                    ->whereNull('updated_at')->get();

**Order By, Group By, e Having**

	$users = DB::table('users')
	                    ->orderBy('name', 'desc')
	                    ->groupBy('count')
	                    ->having('count', '>', 100)
	                    ->get();

**Offset & Limit**

	$users = DB::table('users')->skip(10)->take(5)->get();

<a name="joins"></a>
## Joins

O construtor de consulta também pode ser usado para escrever declarações join. Dê uma olhada nos exemplos:

**Declaração De Join Básica**

	DB::table('users')
	            ->join('contacts', 'users.id', '=', 'contacts.user_id')
	            ->join('orders', 'users.id', '=', 'orders.user_id')
	            ->select('users.id', 'contacts.phone', 'orders.price');

Você também pode especificar cláusulas de join mais avançados:

	DB::table('users')
	        ->join('contacts', function($join)
	        {
	        	$join->on('users.id', '=', 'contacts.user_id')->orOn(...);
	        })
	        ->get();

<a name="advanced-wheres"></a>
## Condições(Wheres) Avançadas

Às vezes você necessita criar cláusulas where mais avançadas como "where exists" ou aninhados agrupamentos de parâmetros. O construtor de consultas do Laravel pode resolver isso muito bem:

**Agrupamento de Parâmetros**

	DB::table('users')
	            ->where('name', '=', 'John')
	            ->orWhere(function($query)
	            {
	            	$query->where('votes', '>', 100)
	            	      ->where('title', '<>', 'Admin');
	            })
	            ->get();

A consulta acima irá produzir o seguinte SQL:

	select * from users where name = 'John' or (votes > 100 and title <> 'Admin')

**Instrução Exists**

	DB::table('users')
	            ->whereExist(function($query)
	            {
	            	$query->select(DB::raw(1))
	            	      ->from('orders')
	            	      ->whereRaw('orders.user_id = users.id');
	            })
	            ->get();

A consulta acima irá produzir o seguinte SQL:

	select * from users
	where exists (
		select 1 from orders where orders.user_id = users.id
	)

<a name="aggregates"></a>
## Agregadores

O construtor de consultas também fornece uma variedade de métodos de agregadores, tais como `count`, `max`, `min`, `avg`, e `sum`.

**Usando Métodos Agregadores**

	$users = DB::table('users')->count();

	$price = DB::table('orders')->max('price');

	$price = DB::table('orders')->min('price');

	$price = DB::table('orders')->avg('price');

	$total = DB::table('users')->sum('votes');

<a name="raw-expressions"></a>
## Expressões Cruas(Raw)

Às vezes, você pode precisar usar uma expressão crua em uma consulta. Estas expressões serão injetados na consulta como strings, por isso tome cuidado para não criar quaisquer brechas de injeção de SQL! Para criar uma expressão crua, use o método `DB::raw`:

**Usando Uma Expressão Crua**

	$users = DB::table('users')
	                     ->select(DB::raw('count(*) as user_count, status'))
	                     ->where('status', '<>', 1)
	                     ->groupBy('status')
	                     ->get();

**Incrementando ou decrementando o valor de uma coluna**

	DB::table('users')->increment('votes');

	DB::table('users')->decrement('votes');

<a name="inserts"></a>
## Inserts

**Inserindo Registros na Tabela**

	DB::table('users')->insert(
		array('email' => 'john@example.com', 'votes' => 0),
	);

Se a tabela tem um id auto-incrementável, use `insertGetId` para inserir e já recuperar o id:

**Inserindo Registro Na Tabela Com ID Auto-Incrementável**

	$id = DB::table('users')->insertGetId(
		array('email' => 'john@example.com', 'votes' => 0),
	);

> **Nota:** Ao usar PostgreSQL o método insertGetId espera que a coluna de auto-incremento se chame "id".

**Inserindo Múltiplos Registros Numa Tabela**

	DB::table('users')->insert(
		array('email' => 'taylor@example.com', 'votes' => 0),
		array('email' => 'dayle@example.com', 'votes' => 0),
	);

<a name="updates"></a>
## Updates

**Atualizando Registros De Uma tabela**

	DB::table('users')
	            ->where('id', 1)
	            ->update(array('votes' => 1));

<a name="deletes"></a>
## Deletes

**Apagando Registros de Uma Tabela**

	DB::table('users')->where('votes', '<', 100)->delete();

**Apagando Todos Os Registros De Uma Tabela**

	DB::table('users')->delete();
