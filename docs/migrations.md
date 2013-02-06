# Migrações & Seeding

- [Introdução](#introduction)
- [Criando Migrações](#creating-migrations)
- [Executando Migrações](#running-migrations)
- [Revertendo Migrações](#rolling-back-migrations)
- [Seeding(Semeando) Banco de Dados](#database-seeding)

<a name="introduction"></a>
## Introdução

Migrações é tipo de controle de versão para o seu banco de dados. Eles permitem um time modificar o esquema de um banco de dados e manter-se atualizados do esquema atual. Migrações é tipicamente emparelhado com o [Construtor de Esquemas](/docs/schema) para facilmente gerenciar seus esquemas da aplicação.

<a name="creating-migrations"></a>
## Criando Migrações

Para cria migrações, use o comando `migrate:make` do Artisan CLI:

**Criando Uma Migração**

	php artisan migrate:make create_users_table

A migração sera alocada no diretório `app/database/migrations`, e irá conter um timestamp que permite  ao framework determinar a ordem das migrações.

É possivel especificar uma opção `--path` quando criar uma migração. A opção path(diretório) deverá ser relativo ao diretório raiz de sua instalação:

	php artisan migrate:make foo --path=app/migrations

As opções `--table` e `--create` são usadas para indicar o nome da tabela e se a migração será a criação de uma nova tabela:

	php artisan migrate:make create_users_table --table=users --create

<a name="running-migrations"></a>
## Executando Migrações

**Executando Todas As Migrações**

	php artisan migrate

**Executando Todas As Migrações De Um Path(diretório)**

	php artisan migrate --path=app/foo/migrations

**Executando Todas As Migrações De Um Pacote**

	php artisan migrate --package=vendor/package

> **Nota:** Se você receber um erro "class not found" quando executar migrações, tente executar o comando `composer update`.

<a name="rolling-back-migrations"></a>
## Revertendo Migrações

**Revertendo A Última Operação Migração**

	php artisan migrate:rollback

**Revertendo Todas As Migrações**

	php artisan migrate:reset

**Revertendo Todas As Migrações E Executando Tudo Novamente**

	php artisan migrate:refresh

	php artisan migrate:refresh --seed

<a name="database-seeding"></a>
## Seeding(Semeando) Banco de Dados

Laravel inclui uma maneira simples de semear seu banco de dados com dados de teste, utilizando arquivos. Todos os arquivos de seed(sementes) estão armazenados em `app/database/seeds`. Arquivos de seed(semente) deverá ter o mesmo nome da tabela que irão semear, e simplesmente retornar um array de registros.

**Exemplo De Arquivo Seed**

	<?php

	return array(

		array(
			'email' => 'john@example.com',
			'votes' => 10,
			'created_at' => new DateTime,
			'updated_at' => new DateTime,
		),

		array(
			'email' => 'smith@example.com',
			'votes' => 20,
			'created_at' => new DateTime,
			'updated_at' => new DateTime,
		),

	);

Para semear seu banco de dados, basta usar o comando `db:seed` do Artisan CLI:

	php artisan db:seed

Também é possivel semear usando o comando `migrate:refresh`, que também reverterá e re-executará todas as suas migrações:

	php artisan migrate:refresh --seed
