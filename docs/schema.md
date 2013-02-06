# Construtor de Esquema

- [Introdução](#introduction)
- [Criando & Removendo Tabelas](#creating-and-dropping-tables)
- [Adicionando Colunas](#adding-columns)
- [Removendo Colunas](#dropping-columns)
- [Adicionando Índices](#adding-indexes)
- [Removendo Índices](#dropping-indexes)

<a name="introduction"></a>
## Introdução

O classe `Schema` do Laravel fornece uma maneira agnóstica de manipular tabelas de banco de dados. Ela funciona bem com todas as bases de dados suportadas pelo Laravel, e tem uma API unificada para todos estes sistemas.

<a name="creating-and-dropping-tables"></a>
## Criando & Removendo Tabelas

Para criar uma nova tabela de banco de dados, o método `Schema::create` é usado:

	Schema::create('users', function($table)
	{
		$table->increments('id');
	});

O primeiro argumento passado para o método `create` é o mesmo nome da tabela, e o segundo é uma `Closure` que irá receber um objeto `Blueprint` que será usado para definir a tabela.

Para especificar qual a conexão a operação do esquema deve usar, utilize o método `Schema::connection`:

	Schema::connection('foo')->create('users', function($table)
	{
		$table->increments('id'):
	});

Para remover a tabela, use o método `Schema::drop`:

	Schema::drop('users');

	Schema::dropIfExists('users');

<a name="adding-columns"></a>
## Adicionando Colunas

Para atualizar uma tabela existente, nos usaremos o método `Schema::table`:

	Schema::table('users', function($table)
	{
		$table->string('email');
	});

O construtor tabela contém uma variedade de tipos de colunas que você pode usar na construção de suas tabelas:

Comando  | Descrição
------------- | -------------
`$table->increments('id');`  |  Incrementando um ID para a tabela (chave primária).
`$table->string('email');`  |  Equivalente a uma coluna VARCHAR
`$table->string('name', 100);`  |  Equivalente a uma coluna VARCHAR com tamanho
`$table->integer('votes');`  |  Equivalente a um INTEGER(inteiro)
`$table->float('amount');`  |  Equivalente a um FLOAT
`$table->decimal('amount', 5, 2);`  |  Equivalente a um DECIMAL com precisão e escala
`$table->boolean('confirmed');`  |  Equivalente a um BOOLEAN
`$table->date('created_at');`  |  Equivalente a um DATE
`$table->dateTime('created_at');`  |  Equivalente a um DATETIME
`$table->time('sunrise');`  |  Equivalente a um TIME
`$table->timestamp('added_on');`  |  Equivalente a um TIMESTAMP
`$table->timestamps();`  |  Adiciona as colunas **created\_at** e **updated\_at**
`$table->text('description');`  |  Equivalente a um TEXT
`$table->binary('data');`  |  Equivalente a um BLOB
`$table->enum('choices', array('foo', 'bar'));` | Equivalente a um ENUM
`->nullable()`  |  Designado a colunas que permite valores NULL
`->default($value)`  |  Declara o valor padrão de uma coluna
`->unsigned()`  |  Define um INTEGER UNSIGNED

<a name="dropping-columns"></a>
## Removendo Colunas

**Removendo Uma Coluna De Uma Tabela**

	Schema::table('users', function($table)
	{
		$table->dropColumn('votes');
	});

<a name="adding-indexes"></a>
## Adicionando Índices

O construtor esquema suporta diversos tipos de índices. Existem duas formas de adicioná-los. Primeiro, você pode defini-los fluentemente em uma definição de coluna, ou você pode adicioná-los separadamente:

**Definindo Fluentemente Uma Coluna E Índice**

	$table->string('email')->unique();

Ou, você pode optar por adicionar os índices em linhas separadas. Abaixo segue a lista de todos os tipos de índices disponíveis:

Comando  | Descrição
------------- | -------------
`$table->primary('id');`  |  Adicionando chave primária
`$table->primary(array('first', 'last'));`  |  Adicionando chave composta
`$table->unique('email');`  |  Adicionando índice único
`$table->index('state');`  |  Adicionando índice básico

<a name="dropping-indexes"></a>
## Removendo Índices

Para remover índice você deve especificar o nome do índice. Laravel atribui um nome razoável para os índices por padrão. Simplesmente concatena o nome da tabela, os nomes da coluna no índice, e do tipo de índice. Veja alguns exemplos:

Comando  | Descrição
------------- | -------------
`$table->dropPrimary('users_id_primary');`  |  Removendo uma chave primária da tabela "users"
`$table->dropUnique('users_email_unique');`  |  Removendo um índice único da tabela "users"
`$table->dropIndex('geo_state_index');`  |  Removendo índice básico da tabela "geo"
