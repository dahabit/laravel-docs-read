# Email

- [Configuração](#configuration)
- [Uso Básico](#basic-usage)
- [Incorporando Anexos Inline](#embedding-inline-attachments)

<a name="configuration"></a>
## Configuração

Laravel fornece uma limpa e simples API da popular biblioteca [SwiftMailer](http://swiftmailer.org). O arquivo de configuração de email é `app/config/mail.php`, e contém opções que lhe permite mudar o seu servidor de SMTP, porta, e credenciais, bem como definir um global endereço `from` para todas as mensagens enviadas pela biblioteca. Sinta-se livre para usar qualquer servidor de SMTP.

<a name="basic-usage"></a>
## Uso Básico

O método `Mail::send` é usado para enviar uma mensagem de email:

	Mail::send('emails.welcome', $data, function($m)
	{
		$m->to('foo@example.com', 'John Smith')->subject('Welcome!');
	});

O primeiro argumento passado para `send` é o nome que da view que será usada no corpo da mensagem. O segundo são parâmetros passados para a view, e o terceiro é uma Closure que permite você especificar outras várias opções para sua mensagem de email.

> **Nota:** A variável `$message` é sempre passada para o email view, e permite a incorporação de anexos inline. Então, é melhor evitar passar uma variável `message` para o seu view.

Você pode também especificar um texto simples como adicional a um HTML view:

	Mail::send(array('html.view', 'text.view'), $data, $callback);

É possível também especificar outras opções para a messagem de email, como muitas cópias(carbon copies) ou anexos:

	Mail::send('emails.welcome', $data, function($m)
	{
		$m->from('us@example.com', 'Laravel');

		$m->to('foo@example.com')->cc('bar@example.com');

		$m->attach($pathToFile);
	});

Quando anexar arquivos a uma mensagem, dá para informar o MIME type e / ou nome de exibição:

	$m->attach($pathToFile, array('as' => $display, 'mime' => $mime));

> **Nota:** A instancia de mensagem passada para a Closure `Mail::send` estende a classe de mensagem SwiftMailer, dando a possibilidade de você chamar qualquer outro método dessa classe para montar sua mensagem de email.

<a name="embedding-inline-attachments"></a>
## Incorporando Anexos Inline

Incorporar imagens inline dentro do seu email é tipicamente incômodo; sendo assim, Laravel fornece uma conveniente maneira para anexar imagens no seu email e recuperar o apropriado CID.

**Incorporando Uma Image Em Um E-Mail View**

	<body>
		Here is an image:

		<img src="<?php echo $message->embed($pathToFile); ?>">
	</body>

**Incorporando Dados Bruto Em Um E-Mail View**

	<body>
		Here is an image from raw data:

		<img src="<?php echo $message->embedData($data, $name); ?>">
	</body>

Observe que a variável `$message` sempre é passada para views de email pela classe `Mail`.
