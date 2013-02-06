# Instalação

- [Instalando via Composer](#install-composer)
- [Instalando o Laravel](#install-laravel)
- [Requisitos do Servidor](#server-requirements)
- [Configurações](#configuration)
- [URLs Amigáveis](#pretty-urls)

<a name="install-composer"></a>
## Instalando via Composer

Laravel usa o [Composer](http://getcomposer.org) para gerir suas dependências. Primeiro, baixe uma copia do `composer.phar`. Depois de obter o arquivo PHAR, você pode mantê-lo no seu diretório do projeto ou movê-lo para `usr/local/bin` usando-o globalmente no seu sistema. No Windows, você pode usar o Composer [Windows installer](https://getcomposer.org/Composer-Setup.exe).

<a name="install-laravel"></a>
## Instalando o Laravel

Com o Composer instalado, baixe a [ultima versão](https://github.com/laravel/laravel/archive/develop.zip) do framework Laravel framework e extrai-o no diretório do seu servidor. Depois, no diretório raiz da sua aplicação, execute o comando `php composer.phar install` todas as dependências do framework.

<a name="server-requirements"></a>
## Requisitos do Servidor

O framework Laravel tem poucos requisitos:

- PHP >= 5.3.7
- MCrypt PHP Extension

<a name="configuration"></a>
## Configuração

Laravel não necessita de quase nenhuma configuração. Você é livre para iniciar o desenvolvimento! Porém, você pode querer revisar o arquivo `app/config/app.php` e sua documentação. Neste arquivo você encontrará varias opções, como `timezone` e `locale` que talvez você deseje mudar de acordo a sua aplicação.

> **Nota:** Você deve se certificar de definir a opção `key` no arquivo `app/config/app.php`. Este valor deve conter 32 caracteres, de uma sequência aleatória. Esta chave será usada na encriptação de valores, valores que não serão encriptados enquanto está chave não for definida. Você pode rapidamente definir esse valor usando o seguinte comando do artisan `php artisan key:generate`.

<a name="permissions"></a>
### Permissões
Laravel requer uma permissão a ser configurada - diretórios dentro de app/storage necessita de permissão de escrita.

<a name="pretty-urls"></a>
## URLs Amigáveis

O framework vem com um arquivo `public/.htaccess` usado para permitir URLs sem o `index.php`. Se você usa o Apache como servidor da sua aplicação Laravel, certifique-se de habilitar o módulo `mod_rewrite`.

Se o arquivo `.htaccess` que vem com Laravel não funcionar na sua instalação do Apache, tente este:

	Options +FollowSymLinks
	RewriteEngine on

	RewriteCond %{REQUEST_FILENAME} !-f
	RewriteCond %{REQUEST_FILENAME} !-d

	RewriteRule . index.php [L]
