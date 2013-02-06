# Segurança

- [Configuração](#configuration)
- [Armazenando Senhas](#storing-passwords)
- [Autenticando Usuários](#authenticating-users)
- [Protegendo Rotas](#protecting-routes)
- [Encriptação](#encryption)

<a name="configuration"></a>
## Configuração

Laravel pretende fazer a implementação de autenticação muito simples. Na verdade, quase tudo é configurado para você. O arquivo de configuração de autenticação esta em `app/config/auth.php`, que contém várias opções bem documentadas para ajustar com o comportamento da autenticação.

Por padrão, Laravel inclui um modelo `User` em seu diretório `app/models` que podem ser utilizadas com o controlador Eloquent de autenticação padrão. Por favor, lembre-se ao construir o esquema para este modelo assegure-se que o campo de senha é de pelo menos 60 caracteres.

Se o seu aplicativo não está usando Eloquent, você pode usar o driver de autenticação de `banco de dados` que usa o construtor de consultas do Laravel.

<a name="storing-passwords"></a>
## Armazenando Senhas

A classe `Hash` do Laravel fornece segurança Bcrypt:

**Hashing Uma Senha Usando Bcrypt**

	$password = Hash::make('secret');

**Verificação De Uma Senha Contra Uma Hash**

	if (Hash::check('secret', $hashedPassword))
	{
		// As senhas conferem...
	}

<a name="authenticating-users"></a>
## Autenticando Usuários

Para logar um usuário na sua aplicação, use o método `Auth::attempt`.

	if (Auth::attempt(array('email' => $email, 'password' => $password)))
	{
		// As credenciais do usuários são válidas...
	}

Tome nota de que `email` não é uma opção necessária, é apenas utilizada no exemplo. Você pode usar qualquer nome de coluna corresponde a um "nome de usuário" em seu banco de dados.

Se você quiser fornecer um "mantenha me conectado" em seu aplicativo, você pode passar `true` como o segundo argumento para o método `attemp`, que irá manter o usuário autenticado indefinidamente (ou até que saia manualmente):

**Autenticando Um Usuários E "Lembrando-se" Dele**

	if (Auth::attempt(array('email' => $email, 'password' => $password), true))
	{
		// O usuário não será esquecido...
	}

**Nota:** Se o método `attempt` retornar `true`, o usuário será considerado como logado na sua aplicação.

**Autenticando Um Usuário Com Condições Extras**

Você pode adicionar condições adicionais para se certificar que o utilizador é (por exemplo) 'active(ativo)', ou 'not suspended(não suspenso)':

    if (Auth::attempt(array('email' => $email, 'password' => $password, 'ativo' => 1, 'suspenso' => 0)))
    {
        // O usuário está ativo, não suspenso, e existe.
    }

Uma vez que um usuário é autenticado, você pode acessar o registro/modelo desse usuário:

**Acessando O Usuário Logado**

	$email = Auth::user()->email;

**Deslogando Um Usuário**

	Auth::logout();

<a name="protecting-routes"></a>
## Protegendo Rotas

Filtros de rota pode ser usado para permitir que somente usuários autenticados acessem determinada rota. Laravel fornece o filtro `auth` por padrão, e isso está definido em `app/filters.php`.

**Protegendo uma rota**

	Route::get('profile', array('before' => 'auth', function()
	{
		// Somente usuários autenticados podem acessar...
	}));

### CSRF Protection

Laravel fornece um método fácil de proteger seu aplicativo de falsificações de pedido cross-site.

**Insere a CSRF token em seu formulário ** usando `csrf_token()` ou `Session::getToken()`

    <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>">

**Valida O Envio Da CSRF token**

    Route::post('register', array('before' => 'csrf', function()
    {
        return 'Você possui uma válida CSRF token!';
    }));

<a name="encryption"></a>
## Encriptação

Laravel possui recursos para forte criptografia AES-256 através da extensão PHP mcrypt:

**Encriptando um Valor**

	$encrypted = Crypt::encrypt('secret');

> **Nota:** Certifique-se de definir uma sequência aleatório de 32 caracteres, na opção `key` no seu arquivo `app/config/app.php`. Caso contrário, os valores criptografados não será segura.

**Decodificando Um Valor**

	$decrypted = Crypt::decrypt($encryptedValue);
