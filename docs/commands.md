# Artisan Development

- [Introdução](#introduction)
- [Criando Um Comando](#building-a-command)
- [Registrando Comandos](#registering-commands)
- [Chamando Outro Comando](#calling-other-commands)

<a name="introduction"></a>
## Introdução

Além dos comandos que o Artisan disponibiliza, é possível criar os seus próprios comandos de trabalho para sua aplicação. Você pode armazenar seus comandos personalizados no diretório`app/commands`; contudo, você é livre para escolher onde armazenar, desde que seus comandos possam ser carregados automaticamente com base nas configurações do seu `composer.json`.

<a name="building-a-command"></a>
## Criando Um Comando

### Gerando a Classe

Para criar um novo comando, você pode usar o comando `command:make` do Artisan, que irá gerar um esboço para ajudar você a iniciar:

**Gerando Uma Nova Classe De Comando**

	php artisan command:make FooCommand

Por padrão, o comandos gerados será armazenado no diretório `app/commands`; mas você pode especificar um caminho ou namespace de sua preferência:

	php artisan command:make FooCommand --path="app/classes" --namespace="Classes"

### Escrevando Comandos

Uma vez que seu comando foi gerado, você deverá preencher o `name` e a `description` nas propriedades da classe, que serão exibidas quando o comando `list` for executado.

O método `fire` será chamado quando seu comando for executado. Você pode qualquer lógica de comando nesse método.

### Argumentos & Opções

Os métodos `getArguments` e `getOptions` são onde você pode definir quais argumentos e opções que seu comando ira receber. Ambos métodos retornam um array de comandos, que são descritos por uma lista de opções.

Quando definimos `arguments(argumentos)`, os valores de definição do array representam o seguinte:

	array($name, $mode, $description, $defaultValue)

O argumento `mode` pode ser qualquer um a seguir: `InputArgument::REQUIRED` ou `InputArgument::OPTIONAL`.

Ao definir `options(opções)`, os valores de definição do array representa o seguinte:

	array($name, $shortcut, $mode, $description, $defaultValue)

Para opções, o argumento `mode` pode ser: `InputOption::VALUE_REQUIRED`, `InputOption::VALUE_OPTIONAL`, `InputOption::VALUE_IS_ARRAY`, `InputOption::VALUE_NONE`.

O modo `VALUE_IS_ARRAY` indica que você pode usar o switch(--) muitas vezes quando chamar o comando:

	php artisan foo --option=bar --option=baz

A opção `VALUE_NONE` indica que a opção pode usar simplesmente o "switch":

	php artisan foo --option

### Recuperando Entrada

Enquanto o seu comando é executado, você obviamente vai precisar acessar os valores dos argumentos e opções aceitos pela sua aplicação. Para fazer isso, você pode usar o método `argument` e `option`:

**Recuperando O Valor Do Argumento De Comando**

	$value = $this->argument('name');

**Recuperando Todos Os Argumentos**

	$arguments = $this->argument();

**Recuperando O Valor De Uma Opção de Comando**

	$value = $this->option('name');

**Recuperando Todas As Opções**

	$options = $this->option();

### Escrevendo Saídas

Para enviar uma saída para o console, você pode usar os métodos `info`, `comment`, `question` e `error`. Cada um desses métodos fará o uso apropriado das cores ANSI em suas cores propostas.

**Enviando Informação Para Console**

	$this->info('Display this on the screen');

**Enviando Uma Mensagem De Erro Para O Console**

	$this->error('Something went wrong!');

### Fazendo Perguntas

Você pode usar os métodos `ask` e `confirm` para solicitar entradas ao usuário:

**Solicitando Entradas Do Usuário**

	$name = $this->ask('What is your name?');

**Solicitando Confirmação Do Usuário**

	if ($this->confirm('Do you wish to continue? [yes|no]'))
	{
		//
	}

Você pode especificar um valor padrão para o método `confirm`, que deverá ser `true` ou `false`:

	$this->confirm($question, true);

<a name="registering-commands"></a>
## Registrando Comandos

Certo de que seu comando está pronto, você precisa registrá-lo no Artisan para que posa usá-lo. Isso é tipicamente feito no arquivo `app/start/artisan.php`. Nele, você pode usar o método `Artisan::add` para registrá-lo:

**Registrando Um Comando No Artisan**

	Artisan::add(new CustomCommand);

Se seu comando está registrado na aplicação [IoC container](/docs/ioc), use o método `Artisan::resolve` torná-lo disponível no Artisan:

**Registrando Um Comando Que Está No IoC Container**

	Artisan::resolve('binding.name');

<a name="calling-other-commands"></a>
## Chamando Outro Comando

Algumas vezes você desejará chamar outro comando a partir do seu comando. Faça usando o método `call`:

**Comando Outro Comando**

	$this->call('command.name', array('argument' => 'foo', '--option' => 'bar'));
