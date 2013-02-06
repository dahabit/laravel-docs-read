# Uso Básico do Banco de Dados

- [Configuração](#configuration)
- [Executando Consultas](#running-queries)
- [Acessando Conexões](#accessing-connections)

<a name="configuration"></a>
## Configuração

Laravel faz conexão com banco de dados e execução de consultas ser extremamente simples. O arquivo de configuração do banco dados é `app/config/database.php`. Neste arquivo você pode definir tudo de sua conexão, bem como especificar que conexão será usada por padrão. Exemplos para todos os sistemas de base de dados suportadas são fornecidos neste arquivo.

Atualmente Laravel suporta quatro sistemas de banco de dados: MySQL, Postgres, SQLite, e SQL Server.

<a name="running-queries"></a>
## Executando Consultas

Uma que sua conexão com o banco de dados está configurada, você pode executar consultas usando a classe `DB`.

**Executando Um Consulta Select**

	$results = DB::select('select * from users where id = ?', array(1));

O método `select` irá sempre retornar uma `array` de resultados.

**Executando Uma Instrução Insert**

	DB::insert('insert into users (id, name) values (?, ?)', array(1, 'Dayle'));

**Executando Uma Instrução Update**

	DB::update('update users set votes = 100 where name = ?', array('John'));

**Executando Uma Instrução Delete**

	DB::delete('delete from users');

> **Nota:** Instruções `update` e `delete` retornam o número de linhas afetadas pela operação.

<a name="accessing-connections"></a>
## Acessando Conexões

Quando múltiplas conexões são usadas, é possível acessar elas via método `DB::connection`:

	$users = DB::connection('foo')->select(...);
