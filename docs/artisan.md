# Artisan CLI

- [Introdução](#introduction)
- [Uso](#usage)

<a name="introduction"></a>
## Introdução

Artisan é o nome da inteface de linha de comando incluso no Laravel. Ele fornece alguns comandos úteis para você usar enquanto desenvolve sua aplicação. Ele é impulsionado pelo poderoso componente Symfony Console.

<a name="usage"></a>
## Uso

Para ver uma lista de todos os comandos do Artisan disponíveis, use o comando `list`:

**Listando Todos Comandos Disponíveis**

	php artisan list

Cada comando inclui também um "help" que mostra e descreve argumentos disponíveis do comando e opções. Para ver um help, simplesmente preceda o comando com `help`:

**Visualizar O Help De Um Comando**

	php artisan help migrate

Você pode especificar o ambiente configurado que será usado para executar o comando, usando o switch `--env`:

**Especificando O Ambiente Configurado**

	php artisan migrate --env=local

Você também pode ver a versão atual da sua instalação do Laravel usando a opção `--version`:

**Exibindo Sua Versão Atual Do Laravel**

	php artisan --version
